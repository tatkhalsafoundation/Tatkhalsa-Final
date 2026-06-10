import express from "express";
import path from "path";
import { createServer as createViteServer } from "vite";
import fs from "fs";

async function startServer() {
  const app = express();
  const PORT = 3000;

  app.use(express.json());
  app.use(express.urlencoded({ extended: true }));

  const THEME_PATH = path.join(process.cwd(), "wp-content", "themes", "tatkhalsa");

  // Render processed PHP theme content
  function renderPHP(filePath: string): string {
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
    content = content.replace(/<\?php[\s\S]*?tatkhalsa_get_logo_url\(\)[\s\S]*?\?>/g, '/Logo.png');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/['"]\s*\)\s*\);\s*\?>/g, '/');
    
    // Resolve admin-ajax url to mock express API
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*admin_url\(\s*['"]admin-ajax\.php['"]\s*\)\s*\);\s*\?>/g, '/api/admin-ajax.php');
    
    // Resolve navigation links
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/about\/['"]\s*\)\s*\);\s*\?>/g, '/about');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/projects\/['"]\s*\)\s*\);\s*\?>/g, '/projects');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/volunteer\/['"]\s*\)\s*\);\s*\?>/g, '/volunteer');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/punjab-flood-relief\/['"]\s*\)\s*\);\s*\?>/g, '/punjab-flood-relief');

    // Any remaining dynamic template directory calls
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*get_template_directory_uri\(\)\s*\);\s*\?>/g, '.');

    // Replace WordPress nav menu block
    const navMenuReg = /<\?php\s*if\s*\(\s*has_nav_menu\([\s\S]*?<\?php\s*\}\s*\?>/g;
    content = content.replace(navMenuReg, `
              <div class="nav-links">
                <a href="/">Home</a>
                <a href="/about">About Us</a>
                <a href="/projects">Projects</a>
                <a href="/volunteer">Volunteer</a>
                <a href="/punjab-flood-relief">Punjab Flood Relief</a>
              </div>
    `);

    // Clean up any remaining small php tag strings that are simple Echo/Esc statements
    content = content.replace(/<\?php\s*echo\s*date\(['"]Y['"]\);\s*\?>/g, new Date().getFullYear().toString());

    return content;
  }

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

      return res.json({
        success: true,
        data: {
          message: "Application submitted successfully! We will contact you soon."
        }
      });
    }

    res.status(404).json({ success: false, data: { message: `Unknown AJAX action: ${action}` } });
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
    "punjab-flood-relief": "template-punjab-flood-relief.php",
    "punjab-flood-relief.php": "template-punjab-flood-relief.php",
    "punjab-flood-relief.html": "template-punjab-flood-relief.php"
  };

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
    
    // Fall back to general static files
    const generalFilePath = path.join(process.cwd(), pageName);
    if (fs.existsSync(generalFilePath) && fs.statSync(generalFilePath).isFile()) {
      res.sendFile(generalFilePath);
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
