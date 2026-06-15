import express from "express";
import path from "path";
import { createServer as createViteServer } from "vite";
import fs from "fs";

async function startServer() {
  const app = express();
  const PORT = 3000;

  app.use(express.json({ limit: '50mb' }));
  app.use(express.urlencoded({ extended: true, limit: '50mb' }));

  const THEME_PATH = path.join(process.cwd(), "wp-content", "themes", "tatkhalsa");

  // Cache to optimize loading speeds significantly
  const templateCache = new Map<string, string>();

  // Render processed PHP theme content
  function renderPHP(filePath: string): string {
    if (process.env.NODE_ENV === "production" && templateCache.has(filePath)) {
      return templateCache.get(filePath)!;
    }

    let content = fs.readFileSync(filePath, "utf8");

    // Replace header
    if (content.includes("get_header()")) {
      const headerPath = path.join(THEME_PATH, "header.php");
      const headerContent = fs.existsSync(headerPath) ? fs.readFileSync(headerPath, "utf8") : "";
      content = content.replace(/<\?php\s*get_header\(\);\s*\?>/g, headerContent);
      content = content.replace(/<\?php\s*get_header\(\);\?>/g, headerContent);
      content = content.replace(/get_header\(\);/g, headerContent);
    }

    // Replace footer
    if (content.includes("get_footer()")) {
      const footerPath = path.join(THEME_PATH, "footer.php");
      const footerContent = fs.existsSync(footerPath) ? fs.readFileSync(footerPath, "utf8") : "";
      content = content.replace(/<\?php\s*get_footer\(\);\s*\?>/g, footerContent);
      content = content.replace(/<\?php\s*get_footer\(\);\?>/g, footerContent);
      content = content.replace(/get_footer\(\);/g, footerContent);
    }

    // Replace common WordPress functions with standard static equivalents for preview
    content = content.replace(/<\?php\s*language_attributes\(\);\s*\?>/g, 'lang="en"');
    content = content.replace(/<\?php\s*bloginfo\(\s*['"]charset['"]\s*\);\s*\?>/g, 'UTF-8');
    content = content.replace(/<\?php\s*bloginfo\(\s*['"]name['"]\s*\);\s*\?>/g, 'Tatkhalsa Foundation');
    content = content.replace(/<\?php\s*wp_head\(\);\s*\?>/g, '<link rel="stylesheet" href="/style.css">');
    content = content.replace(/<\?php\s*body_class\(\);\s*\?>/g, 'class="home blog"');
    content = content.replace(/<\?php\s*wp_body_open\(\);\s*\?>/g, '');
    content = content.replace(/<\?php\s*wp_footer\(\);\s*\?>/g, '');

    // Resolve template directory and logo or home URLs
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*get_template_directory_uri\(\)\s*\.\s*['"]\/Logo\.(jpg|png)['"]\s*\)\s*;\s*\?>/g, '/Logo.png');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*get_template_directory_uri\(\)\s*\.\s*['"]\/Logo\.(jpg|png)['"]\s*\);\s*\?>/g, '/Logo.png');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*tatkhalsa_get_logo_url\(\)\s*\)\s*;?\s*\?>/g, '/Logo.png');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*tatkhalsa_get_theme_logo_url\(\)\s*\)\s*;?\s*\?>/g, '/Logo.png');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/['"]\s*\)\s*\);\s*\?>/g, '/');
    
    // Resolve admin-ajax url to mock express API
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*admin_url\(\s*['"]admin-ajax\.php['"]\s*\)\s*\);\s*\?>/g, '/api/admin-ajax.php');
    
    // Resolve navigation links
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/about\/['"]\s*\)\s*\);\s*\?>/g, '/about');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/projects\/['"]\s*\)\s*\);\s*\?>/g, '/projects');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/volunteer\/['"]\s*\)\s*\);\s*\?>/g, '/volunteer');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/blog\/['"]\s*\)\s*\);\s*\?>/g, '/blog');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/punjab-flood-relief\/['"]\s*\)\s*\);\s*\?>/g, '/punjab-flood-relief');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/privacy-policy\/['"]\s*\)\s*\);\s*\?>/g, '/privacy-policy');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/terms-conditions\/['"]\s*\)\s*\);\s*\?>/g, '/terms-conditions');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/blood-donors\/['"]\s*\)\s*\);\s*\?>/g, '/blood-donors');

    // Any remaining dynamic template directory calls
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*get_template_directory_uri\(\)\s*\);\s*\?>/g, '/');

    // Replace WordPress nav menu block
    const navMenuReg = /<\?php\s*if\s*\(\s*has_nav_menu\([\s\S]*?<\?php\s*\}\s*\?>/g;
    content = content.replace(navMenuReg, `
              <div class="nav-links">
                <a href="/">Home</a>
                <a href="/about">About Us</a>
                <a href="/projects">Projects</a>
                <a href="/volunteer">Volunteer</a>
                <a href="/blog">Blog</a>
                <a href="/punjab-flood-relief">Punjab Flood Relief</a>
              </div>
    `);

    // Clean up any remaining small php tag strings that are simple Echo/Esc statements
    content = content.replace(/<\?php\s*echo\s*date\(['"]Y['"]\);\s*\?>/g, new Date().getFullYear().toString());

    if (process.env.NODE_ENV === "production") {
      templateCache.set(filePath, content);
    }
    return content;
  }

  interface Donor {
    id: string;
    name: string;
    bloodGroup: string;
    email: string;
    contact: string;
    address: string;
    availabilityStatus: string;
    ip: string;
    timestamp: number;
    isVerified?: boolean;
  }

  interface BloodRequest {
    id: string;
    patientName: string;
    bloodGroup: string;
    hospitalName: string;
    patientLocation: string;
    contactDetails: string;
    unitsRequired: string;
    urgency: string;
    ip: string;
    timestamp: number;
    doctorSlipUrl?: string;
    status?: "pending" | "accepted" | "fulfilled";
    acceptedByDonorId?: string;
  }

  let mockDonors: Donor[] = [
    {
      id: "donor_1",
      name: "S. Prabhjot Singh Khalsa",
      bloodGroup: "O+",
      email: "prabhjot@tatkhalsa.org",
      contact: "+91 98765 43210",
      address: "Amritsar, Punjab, India",
      availabilityStatus: "Available Now",
      ip: "192.168.1.100",
      timestamp: Date.now() - 2 * 24 * 3600 * 1000
    },
    {
      id: "donor_2",
      name: "Sardarni Sharanjit Kaur",
      bloodGroup: "A+",
      email: "sharanjit@gmail.com",
      contact: "+91 87654 32109",
      address: "Ludhiana, Punjab, India",
      availabilityStatus: "On Standby",
      ip: "192.168.1.101",
      timestamp: Date.now() - 5 * 24 * 3600 * 1000
    },
    {
      id: "donor_3",
      name: "Bhai Jagdeep Singh",
      bloodGroup: "B+",
      email: "jagdeep@tatkhalsa.org",
      contact: "+91 76543 21098",
      address: "Jalandhar, Punjab, India",
      availabilityStatus: "Resting Phase",
      ip: "192.168.1.102",
      timestamp: Date.now() - 10 * 24 * 3600 * 1000
    },
    {
      id: "donor_4",
      name: "S. Amrik Singh",
      bloodGroup: "AB-",
      email: "amrik.singh@yahoo.com",
      contact: "+91 99887 76655",
      address: "Chandigarh, India",
      availabilityStatus: "Available Now",
      ip: "192.168.1.103",
      timestamp: Date.now() - 15 * 24 * 3600 * 1000
    }
  ];

  let mockRequests: BloodRequest[] = [
    {
      id: "req_1",
      patientName: "Bibi Daljit Kaur",
      bloodGroup: "A+",
      hospitalName: "SGPC Sri Guru Ram Das Charitable Hospital",
      patientLocation: "Vallah, Amritsar, Punjab",
      contactDetails: "+91 98123 45678",
      unitsRequired: "2",
      urgency: "Urgent",
      ip: "192.168.1.200",
      timestamp: Date.now() - 1 * 24 * 3600 * 1000,
      doctorSlipUrl: "https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=800&q=80",
      status: "pending"
    },
    {
      id: "req_2",
      patientName: "S. Joginder Singh",
      bloodGroup: "O+",
      hospitalName: "Max Super Speciality Hospital",
      patientLocation: "Phase 6, Mohali, Punjab",
      contactDetails: "+91 94321 09876",
      unitsRequired: "3",
      urgency: "Immediate",
      ip: "192.168.1.201",
      timestamp: Date.now() - 4 * 3600 * 1000,
      doctorSlipUrl: "https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80",
      status: "accepted",
      acceptedByDonorId: "donor_1"
    },
    {
      id: "req_3",
      patientName: "S. Gurcharan Singh",
      bloodGroup: "B+",
      hospitalName: "Fortis Hospital",
      patientLocation: "Sector 62, Mohali, Punjab",
      contactDetails: "+91 99887 12345",
      unitsRequired: "1",
      urgency: "Normal",
      ip: "192.168.1.202",
      timestamp: Date.now() - 8 * 3600 * 1000,
      doctorSlipUrl: "https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=800&q=80",
      status: "fulfilled"
    }
  ];

  // Mock transaction data to support previews of the automated ledger board
  let mockTransactions = [
    {
      id: "sim_1",
      name: "Sardarni Harpreet Kaur",
      anonymous: 0,
      amount: 15000,
      seva_type: "Punjab Flood Relief",
      note: "In dedication to aid affected families",
      date: new Date(Date.now() - 27 * 3600 * 1000).toISOString().replace("T", " ").substring(0, 19),
      verified: 1
    },
    {
      id: "sim_2",
      name: "Anonymous Sevadar",
      anonymous: 1,
      amount: 5000,
      seva_type: "Langar Seva",
      note: "Guru Ka Langar Seva contribution",
      date: new Date(Date.now() - 48 * 3600 * 1000).toISOString().replace("T", " ").substring(0, 19),
      verified: 1
    },
    {
      id: "sim_3",
      name: "Bhai Jagjit Singh",
      anonymous: 0,
      amount: 1100,
      seva_type: "General Seva",
      note: "Supporting the poor & needy",
      date: new Date(Date.now() - 120 * 3600 * 1000).toISOString().replace("T", " ").substring(0, 19),
      verified: 1
    },
    {
      id: "sim_4",
      name: "S. Gurcharan Singh",
      anonymous: 0,
      amount: 5100,
      seva_type: "Education Support",
      note: "Youth educational materials & study kits",
      date: new Date(Date.now() - 168 * 3600 * 1000).toISOString().replace("T", " ").substring(0, 19),
      verified: 1
    },
    {
      id: "sim_5",
      name: "Anonymous Sevadar",
      anonymous: 1,
      amount: 2100,
      seva_type: "Langar Seva",
      note: "Karah Prasad & Degh contribution",
      date: new Date(Date.now() - 240 * 3600 * 1000).toISOString().replace("T", " ").substring(0, 19),
      verified: 1
    }
  ];

  // Mock WordPress admin-ajax handler for previews (GET and POST)
  function validateCommonFormInputs(name: string, email: string, phone: string): string | true {
    if (name) {
      name = name.trim();
      if (name.length < 3) {
        return "Please enter a valid full name (minimum 3 characters).";
      }
      const lowerName = name.toLowerCase();
      const fakeNames = ['test', 'fake', 'dummy', 'none', 'unknown', 'nobody', 'abc', 'xyz', 'qwer', 'asdf', 'zxcv', 'foo', 'bar', 'something', 'placeholder', 'asdfasdf'];
      for (const fn of fakeNames) {
        if (lowerName === fn || lowerName.includes('asdf') || lowerName.includes('qwer')) {
          return "Please enter your real full name. Placeholder or junk text is not permitted.";
        }
      }
      if (/(.)\1{3,}/.test(name)) {
        return 'Real name cannot contain repetitive sequential identical characters (e.g. "aaaa").';
      }
      if (/[bcdfghjklmnpqrstvwxyz]{5,}/i.test(name)) {
        return "The name contains an invalid keyboard mashing pattern. Please provide a real name.";
      }
    }

    if (email) {
      email = email.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        return "Please enter a valid, well-formed email address.";
      }
      const parts = email.split('@');
      const prefix = parts[0] ? parts[0].toLowerCase() : '';
      const domain = parts[1] ? parts[1].toLowerCase() : '';
      
      const fakePrefixes = ['test', 'abc', 'xyz', 'fake', 'dummy', 'none', 'noemail', 'null', 'temp', 'admin'];
      if (fakePrefixes.includes(prefix) || prefix.length < 3) {
        return "Your email prefix looks invalid/fake. Please use your real email address.";
      }

      const fakeDomains = ['test.com', 'example.com', 'invalid.com', 'fake.com', 'dummy.com', 'abc.com', 'xyz.com', 'tempmail.com', 'dispostable.com', 'mailinator.com', 'yopmail.com', 'temp-mail.org', 'guerrillamail.com', 'sharklasers.com', '10minutemail.com'];
      if (fakeDomains.includes(domain) || domain.includes('temp') || domain.includes('disposable') || domain.includes('mailinator')) {
        return "Temporary, disposable, or test email domains are blocked. Please provide a real email address.";
      }
    }

    if (phone) {
      const digits = phone.replace(/[^0-9]/g, '');
      if (digits.length < 8 || digits.length > 15) {
        return "Please enter a valid active mobile number (8 to 15 digits required).";
      }
      if (/(.)\1{5,}/.test(digits)) {
        return "Your mobile number cannot contain repetitive identical digits (e.g. 000000). Please provide your real active number.";
      }
      const uniqueDigits = new Set(digits.split('')).size;
      if (uniqueDigits < 3) {
        return "This mobile number has too few unique digits and looks like fake or placeholder data.";
      }

      let seqUp = 0;
      let seqDown = 0;
      for (let i = 0; i < digits.length - 1; i++) {
        const curr = parseInt(digits[i], 10);
        const next = parseInt(digits[i+1], 10);
        if (next === curr + 1) seqUp++; else seqUp = 0;
        if (next === curr - 1) seqDown++; else seqDown = 0;
        if (seqUp >= 5 || seqDown >= 5) {
          return "Sequential numbers (e.g. \"123456\" or \"987654\") are not accepted. Please provide your actual active number.";
        }
      }

      const commonFakes = ['1234567890', '0987654321', '9876543210', '12345678', '87654321', '0123456789'];
      for (const cf of commonFakes) {
        if (digits.includes(cf)) {
          return "Common placeholder or test phone numbers (e.g., 1234567890) are not allowed.";
        }
      }
    }
    return true;
  }

  app.all("/api/admin-ajax.php", (req, res) => {
    const action = req.body?.action || req.query?.action;
    
    if (action === "get_transactions") {
      return res.json({
        success: true,
        data: {
          transactions: mockTransactions
        }
      });
    }

    if (action === "simulate_donation") {
      const s_names = [
        'Bhai Amritpal Singh', 'Sardarni Prabhjot Kaur', 'S. Jagdish Singh', 'Sardarni Ravinder Kaur',
        'Bhai Manpreet Singh', 'Sardarni Jasmine Kaur', 'S. Gurpreet Singh', 'Bhai Sukhwinder Singh',
        'Sardarni Harleen Kaur', 'S. Rajinder Singh', 'Bhai Kuldeep Singh', 'Sardarni Gurjit Kaur',
        'S. Bikramjit Singh', 'Bhai Davinder Singh', 'Sardarni Amanpreet Kaur', 'S. Baldev Singh',
        'Bhai Sukhchain Singh', 'Sardarni Nimrat Kaur', 'S. Charanjit Singh', 'Bhai Gurmit Singh',
        'S. Hardeep Singh Ghuman', 'Bhai Paramjit Singh', 'Sardarni Sukhmani Kaur', 'S. Tejaspreet Singh'
      ];
      const s_seva_types = ['General Seva', 'Langar Seva', 'Punjab Flood Relief', 'Education Support'];
      const s_notes = [
        'Synchronized automatically via GiveWP donation webhook.',
        'WooCommerce Langar Seva item contribution.',
        'Direct UPI QR Code contribution scanned.',
        'Secure online transaction complete.',
      ];
      const s_amounts = [500, 1100, 2100, 5100, 10000, 15000, 21000, 31000, 51000];

      const is_anonymous = Math.random() <= 0.3 ? 1 : 0;
      const rand_name = is_anonymous ? 'Anonymous Sevadar' : s_names[Math.floor(Math.random() * s_names.length)];
      const rand_seva = s_seva_types[Math.floor(Math.random() * s_seva_types.length)];
      const rand_note = s_notes[Math.floor(Math.random() * s_notes.length)];
      const rand_amount = s_amounts[Math.floor(Math.random() * s_amounts.length)];

      const new_tx = {
        id: 'sim_' + Date.now(),
        name: rand_name,
        anonymous: is_anonymous,
        amount: rand_amount,
        seva_type: rand_seva,
        note: rand_note,
        date: new Date().toISOString().replace("T", " ").substring(0, 19),
        verified: 1
      };
      
      mockTransactions.unshift(new_tx);
      if (mockTransactions.length > 50) {
        mockTransactions = mockTransactions.slice(0, 50);
      }

      return res.json({
        success: true,
        data: {
          message: 'Real-time plugin gateway event triggers automated sync successfully!',
          transaction: new_tx
        }
      });
    }

    if (action === "submit_volunteer") {
      const name = req.body?.vName;
      const email = req.body?.vEmail;
      const phone = req.body?.vPhone;
      const message = req.body?.vMessage;

      if (!name || !email || !phone || !message) {
        return res.json({
          success: false,
          data: {
            message: "Please fill in all volunteer fields."
          }
        });
      }

      const validation = validateCommonFormInputs(name, email, phone);
      if (validation !== true) {
        return res.json({
          success: false,
          data: {
            message: validation
          }
        });
      }

      return res.json({
        success: true,
        data: {
          message: "Application submitted successfully! We will contact you soon."
        }
      });
    }

    if (action === "submit_blood_donor") {
      const name = req.body?.donorName || req.query?.donorName || "";
      const bloodGroup = req.body?.bloodGroup || req.query?.bloodGroup || "";
      const email = req.body?.donorEmail || req.query?.donorEmail || "";
      const contact = req.body?.contactDetails || req.query?.contactDetails || "";
      const country = req.body?.country || req.query?.country || "";
      const state = req.body?.state || req.query?.state || "";
      const district = req.body?.district || req.query?.district || "";
      const addressLine = req.body?.address || req.query?.address || "";
      
      const addrParts = [addressLine, district, state, country].filter(p => p.trim() !== "");
      const fullAddress = addrParts.join(", ") || "Punjab, India";
      const availabilityStatus = req.body?.availabilityStatus || req.query?.availabilityStatus || "Available Now";

      if (!name || !bloodGroup || !contact || !email) {
        return res.json({ success: false, data: { message: "Please fill in all required fields." } });
      }

      const validation = validateCommonFormInputs(name, email, contact);
      if (validation !== true) {
        return res.json({ success: false, data: { message: validation } });
      }

      const newDonor = {
        id: "donor_" + Date.now(),
        name,
        bloodGroup,
        email,
        contact,
        address: fullAddress,
        availabilityStatus,
        ip: "127.0.0.1",
        timestamp: Date.now()
      };

      mockDonors.unshift(newDonor);

      return res.json({
        success: true,
        data: {
          message: "Thank you! You have been successfully registered as a blood donor."
        }
      });
    }

    if (action === "submit_blood_request") {
      const patientName = req.body?.patientName || req.query?.patientName;
      const bloodGroup = req.body?.bloodGroup || req.query?.bloodGroup;
      const hospitalName = req.body?.hospitalName || req.query?.hospitalName;
      const patientLocation = req.body?.patientLocation || req.query?.patientLocation;
      const contactDetails = req.body?.contactDetails || req.query?.contactDetails;
      const unitsRequired = req.body?.unitsRequired || req.query?.unitsRequired || "1";
      const urgency = req.body?.urgency || req.query?.urgency || "Urgent";
      const doctorSlipBase64 = req.body?.doctorSlipBase64 || req.query?.doctorSlipBase64;

      const district = req.body?.district || req.query?.district || "";
      const state = req.body?.state || req.query?.state || "";
      const country = req.body?.country || req.query?.country || "";

      if (!patientName || !bloodGroup || !contactDetails) {
        return res.json({ success: false, data: { message: "Please fill in all required fields." } });
      }

      const validation = validateCommonFormInputs(patientName, "", contactDetails);
      if (validation !== true) {
        return res.json({ success: false, data: { message: validation } });
      }

      const newRequest = {
        id: "req_" + Date.now(),
        patientName,
        bloodGroup,
        hospitalName: hospitalName || "N/A",
        patientLocation: patientLocation || (district ? `${district}, ${state}, ${country}` : "N/A"),
        contactDetails,
        unitsRequired,
        urgency,
        ip: "127.0.0.1",
        timestamp: Date.now(),
        doctorSlipUrl: doctorSlipBase64 || "https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=800&q=80"
      };

      mockRequests.unshift(newRequest);

      // Perform Mock donor matching with priority: District -> State -> Country
      let matchedDonors: Donor[] = [];

      // Helper to check if blood group matches
      const getBloodGroupMatch = (dGroup: string) => {
        if (!bloodGroup || bloodGroup === "Any" || bloodGroup === "Any Blood Group") {
          return true;
        }
        return dGroup.toLowerCase().trim() === bloodGroup.toLowerCase().trim();
      };

      // 1. Try District match first
      if (district) {
        const dLower = district.toLowerCase().trim();
        matchedDonors = mockDonors.filter(d => {
          return getBloodGroupMatch(d.bloodGroup) && 
                 d.availabilityStatus !== "Resting Phase" &&
                 d.address.toLowerCase().includes(dLower);
        });
      }

      // 2. Fallback to State if no district matches found
      if (matchedDonors.length === 0 && state) {
        const sLower = state.toLowerCase().trim();
        matchedDonors = mockDonors.filter(d => {
          return getBloodGroupMatch(d.bloodGroup) && 
                 d.availabilityStatus !== "Resting Phase" &&
                 d.address.toLowerCase().includes(sLower);
        });
      }

      // 3. Fallback to Country if still no matches found
      if (matchedDonors.length === 0 && country) {
        const cLower = country.toLowerCase().trim();
        matchedDonors = mockDonors.filter(d => {
          return getBloodGroupMatch(d.bloodGroup) && 
                 d.availabilityStatus !== "Resting Phase" &&
                 d.address.toLowerCase().includes(cLower);
        });
      }

      return res.json({
        success: true,
        data: {
          message: `Emergency Blood Request submitted successfully! Broadcast notification emails simulated for ${matchedDonors.length} available matching donors in your target area.`,
          matched_donors: matchedDonors.map(d => ({
            name: d.name,
            contact: d.contact
          }))
        }
      });
    }

    if (action === "update_donor_status") {
      const contact = req.body?.contactNumber || req.query?.contactNumber;
      const status = req.body?.availabilityStatus || req.query?.availabilityStatus;

      if (!contact || !status) {
        return res.json({ success: false, data: { message: "Please enter your registered contact number and select a new status." } });
      }

      const normalizedSearch = contact.replace(/[^0-9]/g, '');
      const foundDonors = mockDonors.filter(d => {
        const normalizedDonorContact = d.contact.replace(/[^0-9]/g, '');
        return normalizedDonorContact.endsWith(normalizedSearch) || normalizedSearch.endsWith(normalizedDonorContact);
      });

      if (foundDonors.length > 0) {
        foundDonors.forEach(d => {
          d.availabilityStatus = status;
        });
        return res.json({ success: true, data: { message: `Successfully updated availability status to ${status}!` } });
      } else {
        return res.json({ success: false, data: { message: "No registration found with this contact number in our directory." } });
      }
    }

    if (action === "remove_blood_donor") {
      const contact = req.body?.contactNumber || req.query?.contactNumber;

      if (!contact) {
        return res.json({ success: false, data: { message: "Please enter your registered contact number." } });
      }

      const normalizedSearch = contact.replace(/[^0-9]/g, '');
      const initialCount = mockDonors.length;
      mockDonors = mockDonors.filter(d => {
        const normalizedDonorContact = d.contact.replace(/[^0-9]/g, '');
        return !(normalizedDonorContact.endsWith(normalizedSearch) || normalizedSearch.endsWith(normalizedDonorContact));
      });

      if (mockDonors.length < initialCount) {
        return res.json({ success: true, data: { message: "Your registration has been removed successfully." } });
      } else {
        return res.json({ success: false, data: { message: "No registration found with this contact number." } });
      }
    }

    if (action === "verify_donor_email") {
      const email = req.body?.donorEmail;
      if (!email) {
        return res.json({ success: false, data: { message: "Please provide an email address." } });
      }
      return res.json({
        success: true,
        data: {
          name: email.split('@')[0].replace(/[^a-zA-Z]/g, ' '),
          date: new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
        }
      });
    }

    if (action === "send_pdf_certificate") {
      return res.json({
        success: true,
        data: {
          message: "Certificate generated and sent! Please check your email inbox."
        }
      });
    }

    res.status(404).json({ success: false, data: { message: `Unknown AJAX action: ${action}` } });
  });

  // Admin Configuration for Auto IP Purging
  let ipPurgeEnabled = true; // Auto IP purging active by default
  let ipPurgeDuration = "15_days"; // Default duration is 15 days

  function runIpPurge() {
    if (!ipPurgeEnabled || ipPurgeDuration === "never") return;

    let days = 15;
    if (ipPurgeDuration === "7_days") days = 7;
    else if (ipPurgeDuration === "15_days") days = 15;
    else if (ipPurgeDuration === "30_days") days = 30;

    const thresholdMs = days * 24 * 3600 * 1000;
    const now = Date.now();

    mockDonors = mockDonors.map(d => {
      if (now - d.timestamp > thresholdMs && d.ip && d.ip !== "[Purged]") {
        return { ...d, ip: "[Purged]" };
      }
      return d;
    });

    mockRequests = mockRequests.map(r => {
      if (now - r.timestamp > thresholdMs && r.ip && r.ip !== "[Purged]") {
        return { ...r, ip: "[Purged]" };
      }
      return r;
    });
  }

  // Admin Master Data and Deletion Endpoints for local simulation
  app.get("/api/admin/master-data", (req, res) => {
    runIpPurge(); // Auto purge check on loading master data
    return res.json({
      success: true,
      donors: mockDonors,
      requests: mockRequests,
      purgeSettings: {
        enabled: ipPurgeEnabled,
        duration: ipPurgeDuration
      }
    });
  });

  // Get and set Auto IP purging settings
  app.get("/api/admin/purge-settings", (req, res) => {
    return res.json({
      success: true,
      enabled: ipPurgeEnabled,
      duration: ipPurgeDuration
    });
  });

  app.post("/api/admin/purge-settings", (req, res) => {
    const { enabled, duration } = req.body || {};
    if (enabled !== undefined) ipPurgeEnabled = !!enabled;
    if (duration !== undefined) ipPurgeDuration = duration;

    runIpPurge(); // Trigger purge immediately with updated config

    return res.json({
      success: true,
      message: "IP address purging configuration updated successfully.",
      enabled: ipPurgeEnabled,
      duration: ipPurgeDuration
    });
  });

  const otpStore = new Map();

  app.post("/api/admin/send-otp", async (req, res) => {
    try {
      const { contact } = req.body || {};
      if (!contact) {
        return res.status(400).json({ success: false, message: "Contact number is required." });
      }

      const META_WHATSAPP_PHONE_NUMBER_ID = process.env.META_WHATSAPP_PHONE_NUMBER_ID;
      const META_WHATSAPP_ACCESS_TOKEN = process.env.META_WHATSAPP_ACCESS_TOKEN;

      if (!META_WHATSAPP_PHONE_NUMBER_ID || !META_WHATSAPP_ACCESS_TOKEN) {
        // Mock mode if Meta WhatsApp API is not configured
        const otp = "123456";
        otpStore.set(contact, otp);
        return res.json({ success: true, message: "OTP sent in mock mode.", mock: true });
      }

      const otp = Math.floor(100000 + Math.random() * 900000).toString();
      otpStore.set(contact, otp);

      // format number for whatsapp (API requires phone without '+')
      let toNum = String(contact).replace(/[^0-9]/g, '');

      const response = await fetch(`https://graph.facebook.com/v19.0/${META_WHATSAPP_PHONE_NUMBER_ID}/messages`, {
        method: "POST",
        headers: {
          "Authorization": `Bearer ${META_WHATSAPP_ACCESS_TOKEN}`,
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          messaging_product: "whatsapp",
          recipient_type: "individual",
          to: toNum,
          type: "text",
          text: {
            preview_url: false,
            body: `Your Tatkhalsa Foundation verification OTP is ${otp}. Do not share this with anyone.`
          }
        })
      });

      const data = await response.json();
      if (!response.ok) {
        console.error("Meta WhatsApp error data:", data);
        return res.status(400).json({ success: false, message: data.error?.message || "Failed to send message via Meta API" });
      }

      return res.json({ success: true, message: "OTP sent successfully." });
    } catch (error: any) {
      console.error("Meta WhatsApp exception:", error.message || error);
      return res.status(500).json({ success: false, message: `System Error: ${error.message || error}` });
    }
  });

  app.post("/api/admin/verify-otp", (req, res) => {
    const { contact, otp } = req.body || {};
    const expectedOtp = otpStore.get(contact);
    if (!expectedOtp || expectedOtp !== otp) {
      return res.status(400).json({ success: false, message: "Invalid or expired OTP." });
    }
    otpStore.delete(contact);
    return res.json({ success: true, message: "OTP verified correctly." });
  });

  // Edit Donor API Route
  app.post("/api/admin/edit-donor", (req, res) => {
    const { id, name, bloodGroup, email, contact, address, availabilityStatus } = req.body || {};
    if (!id) {
      return res.status(400).json({ success: false, message: "Donor ID is required to edit." });
    }
    const donorIndex = mockDonors.findIndex(d => d.id === id);
    if (donorIndex === -1) {
      return res.status(404).json({ success: false, message: "Donor profile not found." });
    }

    mockDonors[donorIndex] = {
      ...mockDonors[donorIndex],
      name: name !== undefined ? name : mockDonors[donorIndex].name,
      bloodGroup: bloodGroup !== undefined ? bloodGroup : mockDonors[donorIndex].bloodGroup,
      email: email !== undefined ? email : mockDonors[donorIndex].email,
      contact: contact !== undefined ? contact : mockDonors[donorIndex].contact,
      address: address !== undefined ? address : mockDonors[donorIndex].address,
      availabilityStatus: availabilityStatus !== undefined ? availabilityStatus : mockDonors[donorIndex].availabilityStatus
    };

    return res.json({
      success: true,
      message: "Donor credentials updated successfully.",
      donor: mockDonors[donorIndex]
    });
  });

  app.post("/api/admin/verify-donor", (req, res) => {
    const { id, isVerified } = req.body || {};
    if (!id) {
      return res.status(400).json({ success: false, message: "Donor ID is required to verify." });
    }
    const donorIndex = mockDonors.findIndex(d => d.id === id);
    if (donorIndex === -1) {
      return res.status(404).json({ success: false, message: "Donor profile not found." });
    }

    mockDonors[donorIndex].isVerified = isVerified;

    return res.json({
      success: true,
      message: `Donor marks as ${isVerified ? 'verified' : 'unverified'} on WhatsApp.`,
      donor: mockDonors[donorIndex]
    });
  });

  // Edit Request API Route
  app.post("/api/admin/edit-request", (req, res) => {
    const { id, patientName, bloodGroup, hospitalName, patientLocation, contactDetails, unitsRequired, urgency, status } = req.body || {};
    if (!id) {
      return res.status(400).json({ success: false, message: "Request ID is required to edit." });
    }
    const reqIndex = mockRequests.findIndex(r => r.id === id);
    if (reqIndex === -1) {
      return res.status(404).json({ success: false, message: "Blood request profile not found." });
    }

    mockRequests[reqIndex] = {
      ...mockRequests[reqIndex],
      patientName: patientName !== undefined ? patientName : mockRequests[reqIndex].patientName,
      bloodGroup: bloodGroup !== undefined ? bloodGroup : mockRequests[reqIndex].bloodGroup,
      hospitalName: hospitalName !== undefined ? hospitalName : mockRequests[reqIndex].hospitalName,
      patientLocation: patientLocation !== undefined ? patientLocation : mockRequests[reqIndex].patientLocation,
      contactDetails: contactDetails !== undefined ? contactDetails : mockRequests[reqIndex].contactDetails,
      unitsRequired: unitsRequired !== undefined ? unitsRequired : mockRequests[reqIndex].unitsRequired,
      urgency: urgency !== undefined ? urgency : mockRequests[reqIndex].urgency,
      status: status !== undefined ? status : mockRequests[reqIndex].status
    };

    return res.json({
      success: true,
      message: "Emergency blood request credentials updated successfully.",
      request: mockRequests[reqIndex]
    });
  });

  app.post("/api/admin/delete-donor", (req, res) => {
    const { id, ids } = req.body || {};
    if (!id && (!ids || !Array.isArray(ids))) {
      return res.status(400).json({ success: false, message: "ID or list of IDs is required." });
    }
    const toDelete = ids ? ids : [id];
    mockDonors = mockDonors.filter(d => !toDelete.includes(d.id));
    return res.json({ success: true, message: "Donor profile(s) deleted from directory." });
  });

  app.post("/api/admin/delete-request", (req, res) => {
    const { id, ids } = req.body || {};
    if (!id && (!ids || !Array.isArray(ids))) {
      return res.status(400).json({ success: false, message: "ID or list of IDs is required." });
    }
    const toDelete = ids ? ids : [id];
    mockRequests = mockRequests.filter(r => !toDelete.includes(r.id));
    return res.json({ success: true, message: "Emergency query logs deleted successfully." });
  });

  app.post("/api/admin/fulfill-request", (req, res) => {
    const { id } = req.body || {};
    if (!id) {
      return res.status(400).json({ success: false, message: "Request ID is required." });
    }
    const reqIndex = mockRequests.findIndex(r => r.id === id);
    if (reqIndex === -1) {
      return res.status(404).json({ success: false, message: "Blood request not found." });
    }
    mockRequests[reqIndex].status = "fulfilled";
    return res.json({ success: true, message: "Blood request status updated to fulfilled." });
  });

  app.post("/api/admin/accept-request", (req, res) => {
    const { req_id, donor_id } = req.body || {};
    if (!req_id) {
      return res.status(400).json({ success: false, message: "Request ID is required." });
    }
    const targetDonorId = donor_id || 'general';
    const reqIndex = mockRequests.findIndex(r => r.id === req_id);
    if (reqIndex === -1) {
      return res.status(404).json({ success: false, message: "Blood request not found." });
    }
    const request = mockRequests[reqIndex];

    // Double claim tracking / blocking protection
    if (request.status === "accepted" || request.status === "fulfilled") {
      return res.json({
        success: true,
        data: {
          success: true,
          already_accepted_by_you: true,
          message: "its already accepted thanks for your efforts We appreciate your time"
        }
      });
    }

    // Set request status to accepted and record which donor did it
    request.status = "accepted";
    request.acceptedByDonorId = targetDonorId;

    return res.json({
      success: true,
      data: {
        success: true,
        message: "thank you not accepting request please get in touch with the one who required"
      }
    });
  });

  app.post("/api/admin/import-data", (req, res) => {
    const { donors, requests } = req.body || {};
    if (!Array.isArray(donors) || !Array.isArray(requests)) {
      return res.status(400).json({ success: false, message: "Invalid backup content templates." });
    }

    let donorsImportedCount = 0;
    let requestsImportedCount = 0;

    // Import Donors
    donors.forEach(d => {
      // Find matching donor in existing records
      const existingIndex = mockDonors.findIndex(md => 
        (md.email && md.email.toLowerCase() === (d.email || d.donor_email || '').toLowerCase()) || 
        (md.contact && md.contact.replace(/\s+/g, '') === (d.contact || d.contact_details || '').replace(/\s+/g, ''))
      );

      const mappedDonor = {
        id: d.id || `donor_${Date.now()}_${Math.floor(Math.random() * 1000)}`,
        name: d.name || d.donor_name || 'Anonymous',
        bloodGroup: d.bloodGroup || d.blood_group || 'O+',
        email: d.email || d.donor_email || '',
        contact: d.contact || d.contact_details || '',
        address: d.address || d.address_line || '',
        availabilityStatus: d.availabilityStatus || d.availability_status || 'Available Now',
        ip: d.ip || d.donor_ip || '127.0.0.1',
        timestamp: d.timestamp || d.registration_time || Date.now()
      };

      if (existingIndex !== -1) {
        mockDonors[existingIndex] = mappedDonor;
      } else {
        mockDonors.push(mappedDonor);
      }
      donorsImportedCount++;
    });

    // Import Requests
    requests.forEach(r => {
      const existingIndex = mockRequests.findIndex(mr => 
        mr.id === r.id || 
        (mr.patientName === (r.patientName || r.patient_name) && mr.contactDetails === (r.contactDetails || r.contact_details))
      );

      const mappedRequest = {
        id: r.id || `req_${Date.now()}_${Math.floor(Math.random() * 1000)}`,
        patientName: r.patientName || r.patient_name || 'Anonymous',
        bloodGroup: r.bloodGroup || r.blood_group || 'O+',
        hospitalName: r.hospitalName || r.hospital_name || 'General Hospital',
        patientLocation: r.patientLocation || r.patient_location || '',
        contactDetails: r.contactDetails || r.contact_details || '',
        unitsRequired: r.unitsRequired || r.units_required || '1',
        urgency: r.urgency || r.urgency || 'Normal',
        doctorSlipUrl: r.doctorSlipUrl || r.doctor_slip_url || '',
        status: r.status || 'pending',
        acceptedByDonorId: r.acceptedByDonorId || r.accepted_by_donor_id || null,
        volunteer_name: r.volunteer_name || r.volunteer_name || '',
        volunteer_phone: r.volunteer_phone || r.volunteer_phone || '',
        ip: r.ip || r.request_ip || '127.0.0.1',
        timestamp: r.timestamp || r.request_time || r.registration_time || Date.now()
      };

      if (existingIndex !== -1) {
        mockRequests[existingIndex] = mappedRequest;
      } else {
        mockRequests.push(mappedRequest);
      }
      requestsImportedCount++;
    });

    return res.json({ 
      success: true, 
      donors_imported: donorsImportedCount,
      requests_imported: requestsImportedCount,
      message: "Data loaded successfully." 
    });
  });

  // Support direct static serving of style.css and Logo.jpg/Logo.png from theme path
  app.get("/style.css", (req, res) => {
    res.sendFile(path.join(THEME_PATH, "style.css"));
  });

  app.get("/Logo.png", (req, res) => {
    const pngPath = path.join(THEME_PATH, "Logo.png");
    if (fs.existsSync(pngPath)) {
      res.sendFile(pngPath);
    } else {
      res.sendFile(path.join(THEME_PATH, "Logo.jpg"));
    }
  });

  app.get("/Logo.jpg", (req, res) => {
    const jpgPath = path.join(THEME_PATH, "Logo.jpg");
    if (fs.existsSync(jpgPath)) {
      res.sendFile(jpgPath);
    } else {
      res.sendFile(path.join(THEME_PATH, "Logo.png"));
    }
  });

  app.use('/assets', express.static(path.join(THEME_PATH, 'assets')));

  // Intercept requests to / or index.php
  app.get("/", (req, res) => {
    const indexPath = path.join(THEME_PATH, "index.php");
    if (fs.existsSync(indexPath)) {
      const parsedHTML = renderPHP(indexPath);
      res.setHeader("Content-Type", "text/html");
      res.send(parsedHTML);
    } else {
      res.status(404).send("index.php (theme default) not found");
    }
  });

  app.get("/index.php", (req, res) => {
    const indexPath = path.join(THEME_PATH, "index.php");
    if (fs.existsSync(indexPath)) {
      const parsedHTML = renderPHP(indexPath);
      res.setHeader("Content-Type", "text/html");
      res.send(parsedHTML);
    } else {
      res.status(404).send("index.php not found");
    }
  });

  // Intercept page paths and resolve them to their corresponding page template files
  const pageTemplateMap: Record<string, string> = {
    "about": "template-about.php",
    "about.php": "template-about.php",
    "projects": "template-projects.php",
    "projects.php": "template-projects.php",
    "volunteer": "template-volunteer.php",
    "volunteer.php": "template-volunteer.php",
    "blog": "template-blog.php",
    "blog.php": "template-blog.php",
    "blood-donors": "template-blood-donors.php",
    "blood-donors.php": "template-blood-donors.php",
    "privacy-policy": "template-privacy.php",
    "privacy-policy.php": "template-privacy.php",
    "terms-conditions": "template-terms.php",
    "terms-conditions.php": "template-terms.php"
  };

  // Serve robots.txt dynamically to match the live server configuration
  app.get("/robots.txt", (req, res) => {
    const robotsPath = path.join(process.cwd(), "robots.txt");
    if (fs.existsSync(robotsPath)) {
      res.setHeader("Content-Type", "text/plain");
      res.sendFile(robotsPath);
    } else {
      res.setHeader("Content-Type", "text/plain");
      res.send("User-agent: *\nAllow: /\nDisallow: /wp-admin/\nDisallow: /wp-includes/\nDisallow: /xmlrpc.php\n\nSitemap: https://tatkhalsa.in/wp-sitemap.xml\nSitemap: https://tatkhalsa.in/sitemap_index.xml");
    }
  });

  // Serve the 404 page template directly if requested
  app.get("/404", (req, res) => {
    const errorPath = path.join(THEME_PATH, "404.php");
    if (fs.existsSync(errorPath)) {
      const parsedHTML = renderPHP(errorPath);
      res.status(404).setHeader("Content-Type", "text/html");
      res.send(parsedHTML);
    } else {
      res.status(404).send("Page Not Found");
    }
  });

  // Redirect former standalone flood relief page requests to the home page with automated modal trigger
  app.get(["/punjab-flood-relief", "/punjab-flood-relief/", "/punjab-flood-relief.php", "/punjab-flood-relief.html"], (req, res) => {
    res.redirect("/?openModal=flood");
  });

  app.get("/:page", (req, res, next) => {
    let pageName = req.params.page;
    if (pageName.endsWith(".html")) {
      pageName = pageName.replace(".html", "");
    }
    const templateName = pageTemplateMap[pageName] || pageTemplateMap[pageName.toLowerCase()];

    if (templateName) {
      const filePath = path.join(THEME_PATH, templateName);
      if (fs.existsSync(filePath)) {
        const parsedHTML = renderPHP(filePath);
        res.setHeader("Content-Type", "text/html");
        res.send(parsedHTML);
        return;
      }
    }
    
    // Serve our elegant 404 template instead of passing through to general assets if the path doesn't exist
    const errorPath = path.join(THEME_PATH, "404.php");
    if (fs.existsSync(errorPath)) {
      const parsedHTML = renderPHP(errorPath);
      res.status(404).setHeader("Content-Type", "text/html");
      res.send(parsedHTML);
    } else {
      next();
    }
  });

  if (process.env.NODE_ENV !== "production") {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: "spa",
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), 'dist');
    app.use(express.static(distPath));
    app.use(express.static(process.cwd())); // serve files from root in prod
    app.use(express.static(THEME_PATH)); // serve files from theme path in prod (e.g. style.css)
  }

  app.listen(PORT, "0.0.0.0", () => {
    console.log(`Server running on http://localhost:${PORT}`);
  });
}

startServer();
