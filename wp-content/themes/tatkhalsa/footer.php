    <!-- Footer -->
    <footer>
      <div class="footer-graphics">
        <!-- SVG Blob 1 (Warm Red) -->
        <svg
          style="top: -50px; left: -50px; opacity: 0.5"
          width="300"
          height="300"
          viewBox="0 0 200 200"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill="var(--accent-red)"
            d="M47.7,-57.2C59.4,-47.3,65.6,-29.9,65.3,-13.4C64.9,3.1,58.1,18.7,48.5,32.2C38.9,45.7,26.5,57.1,10.6,62.3C-5.3,67.6,-24.8,66.6,-40.1,57C-55.4,47.4,-66.6,29,-70.2,9.6C-73.7,-9.8,-69.6,-30,-57.4,-41.8C-45.2,-53.5,-24.9,-56.9,-5,-51C14.9,-45.1,29.8,-50,36,-67.1Z"
            transform="translate(100 100)"
          />
        </svg>
        <!-- SVG Blob 2 (Accent Blue) -->
        <svg
          style="bottom: -50px; right: -50px; opacity: 0.6"
          width="400"
          height="400"
          viewBox="0 0 200 200"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill="var(--accent-blue)"
            d="M60.4,-42.6C75.2,-23.4,82.2,2.7,74.5,22.6C66.8,42.4,44.5,56,21.8,61.8C-0.8,67.6,-23.8,65.7,-43.3,53.8C-62.8,42,-78.9,20.3,-77.7,-0.3C-76.6,-20.9,-58.3,-40.3,-40.2,-58.5C-22.1,-76.8,-4.2,-94,11.3,-88.7C26.8,-83.4,45.5,-61.8,60.4,-42.6Z"
            transform="translate(100 100)"
          />
        </svg>
        <!-- SVG Blob 3 (Accent Green) -->
        <svg
          style="top: 20%; right: 30%; opacity: 0.4"
          width="250"
          height="250"
          viewBox="0 0 200 200"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill="var(--accent-green)"
            d="M37.5,-63.3C49.9,-54.6,62.2,-46.8,70.5,-35.1C78.9,-23.4,83.4,-7.8,81.1,7.2C78.8,22.2,69.5,36.5,58.3,48.2C47,59.8,33.7,68.8,18.7,72.7C3.7,76.5,-13.1,75.1,-27.1,68.8C-41.2,62.5,-52.6,51.3,-62.4,38C-72.2,24.8,-80.4,9.5,-79.8,-5.5C-79.3,-20.4,-69.9,-35.1,-58,-45.8C-46,-56.6,-31.6,-63.4,-17.8,-66C-4.1,-68.6,9,-66.8,23.3,-69.5C37.5,-72.1,37.5,-63.3,37.5,-63.3Z"
            transform="translate(100 100)"
          />
        </svg>
      </div>

      <div class="container">
        <div class="footer-grid">
          <!-- Column 1: Identity & Credentials -->
          <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
              <img
                src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>"
                alt="Tatkhalsa Foundation Logo"
                style="height: 60px; width: 60px; border-radius: 50%; object-fit: cover;"
              />
              <h4 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 0; color: var(--secondary);">
                Tatkhalsa<br />Foundation
              </h4>
            </div>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem">
              Registered NGO<br />CIN: U88900PB2023NPL059225
            </p>
          </div>

          <!-- Column 2: Contact -->
          <div>
            <h4>Contact Us</h4>
            <ul class="footer-links">
              <li style="margin-bottom: 8px;">Email: <a href="mailto:info@tatkhalsa.in" style="display:inline; color:rgba(255,255,255,0.8);">info@tatkhalsa.in</a></li>
              <li style="margin-bottom: 8px; font-size: 0.95rem; color: rgba(255, 255, 255, 0.8);">
                Address: GF 37, Bazidpur,<br />SBS Nagar, Punjab - 144518
              </li>
            </ul>
          </div>

          <!-- Column 3: Registrations / Compliance -->
          <div>
            <h4>Registrations</h4>
            <ul class="footer-links">
              <li><span style="color: rgba(255, 255, 255, 0.8);">Registered NGO</span></li>
              <li><span style="color: rgba(255, 255, 255, 0.8);">12A Registered</span></li>
              <li><span style="color: rgba(255, 255, 255, 0.8);">80G Approved</span></li>
              <li><span style="color: rgba(255, 255, 255, 0.8);">CSR Eligible</span></li>
            </ul>
          </div>

          <!-- Column 4: Quick Links -->
          <div>
            <h4>Quick Links</h4>
            <ul class="footer-links">
              <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
              <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About Us</a></li>
              <li><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Projects</a></li>
              <li><a href="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>">Volunteer</a></li>
              <li><a href="<?php echo esc_url( home_url( '/punjab-flood-relief/' ) ); ?>">Punjab Flood Relief</a></li>
              <li><a href="#" onclick="openModal(); return false;">Contribute Now</a></li>
            </ul>
          </div>
        </div>

        <div class="copyright">
          &copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. All Rights Reserved. Saved & Built dynamically via custom theme template.
        </div>
      </div>
    </footer>

    <!-- Contribution Modal -->
    <div class="modal-overlay" id="contributionModal">
      <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">×</button>
        <h3 style="font-size: 1.8rem; margin-bottom: 10px; color: var(--primary);">
          Contribute to Tatkhalsa Foundation
        </h3>
        <p style="color: var(--text-light); margin-bottom: 20px;">
          Your support helps us serve humanity.
        </p>

        <!-- Direct Bank Transfer Details -->
        <div class="bank-details">
          <p>
            <span><strong>Bank:</strong> Axis Bank</span>
          </p>
          <p>
            <span><strong>Account No:</strong> 925010057912966</span>
            <button class="copy-btn" onclick="copyText('925010057912966')">
              Copy Account
            </button>
          </p>
          <p>
            <span><strong>IFSC Code:</strong> UTIB0004354</span>
            <button class="copy-btn" onclick="copyText('UTIB0004354')">
              Copy IFSC
            </button>
          </p>
        </div>

        <!-- Interactive UPI Billing Node -->
        <div class="upi-section">
          <h4 style="margin-bottom: 15px; color: var(--primary);">
            Contribute via UPI QR
          </h4>

          <div class="amount-buttons" id="amountButtons">
            <button class="amount-btn active" onclick="setUpiAmount(0, this)">
              Any Amount
            </button>
            <button class="amount-btn" onclick="setUpiAmount(500, this)">
              ₹500
            </button>
            <button class="amount-btn" onclick="setUpiAmount(1000, this)">
              ₹1000
            </button>
            <button class="amount-btn" onclick="setUpiAmount(5000, this)">
              ₹5000
            </button>
          </div>

          <div class="qr-container">
            <img
              id="upiQrCode"
              class="qr-code-img"
              src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=upi%3A%2F%2Fpay%3Fpa%3Dmab.037215043540097%40axisbank%26pn%3DTatkhalsa%2520Foundation%26cu%3DINR"
              alt="UPI QR Code"
            />
            <div class="upi-id-text">
              <span style="font-size: 0.825rem; font-family: monospace;">mab.037215043540097@axisbank</span>
              <button
                class="copy-btn"
                onclick="copyText('mab.037215043540097@axisbank')"
                style="padding: 2px 6px; font-size: 0.75rem"
              >
                Copy ID
              </button>
            </div>
          </div>

          <!-- Direct Phone UPI Link Launch Button -->
          <div class="direct-upi-pay-wrapper" style="margin-top: 20px; text-align: center;">
            <a id="directUpiPayBtn" href="upi://pay?pa=mab.037215043540097@axisbank&pn=Tatkhalsa%20Foundation&cu=INR" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #00875a; color: #ffffff; padding: 12px 20px; border-radius: 8px; font-weight: bold; text-decoration: none; font-size: 1.05rem; width: 100%; box-sizing: border-box; box-shadow: 0 4px 12px rgba(0,135,90,0.25); transition: all 0.25s;" onmouseover="this.style.background='#006644'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#00875a'; this.style.transform='none';">
              <span style="font-size: 1.2rem; line-height: 1;">📱</span> Contribute Now
            </a>
            <span style="display: block; font-size: 0.75rem; color: var(--text-light); margin-top: 8px; font-style: italic; line-height: 1.3;">
              *Tap to immediately open any available payment app (GPay, PhonePe, Paytm, BHIM, etc.) on your phone
            </span>
          </div>
        </div>

        <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 20px;">
          Contributions made to Tatkhalsa Foundation may be eligible for tax
          benefits under Section 80G of the Income Tax Act, subject to
          applicable regulations.
        </div>
      </div>
    </div>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to Top">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="18 15 12 9 6 15"></polyline>
      </svg>
    </button>

    <!-- Vanilla Javascript Interactions and States -->
    <script>
      // Header and document body scroll state
      window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
          document.body.classList.add("scrolled");
          document.querySelector(".header")?.classList.add("scrolled");
        } else {
          document.body.classList.remove("scrolled");
          document.querySelector(".header")?.classList.remove("scrolled");
        }
      });

      // Scroll to Top Logic with defensive null checks
      const backToTopBtn = document.getElementById("backToTop");
      if (backToTopBtn) {
        window.addEventListener("scroll", () => {
          if (window.scrollY > 300) {
            backToTopBtn.classList.add("visible");
          } else {
            backToTopBtn.classList.remove("visible");
          }
        });

        backToTopBtn.addEventListener("click", () => {
          window.scrollTo({
            top: 0,
            behavior: "smooth",
          });
        });
      }

      // Modal Display Mechanics with robust safety guards
      function openModal() {
        const modal = document.getElementById("contributionModal");
        if (modal) modal.classList.add("active");
      }
      function closeModal() {
        const modal = document.getElementById("contributionModal");
        if (modal) modal.classList.remove("active");
      }

      const contribModal = document.getElementById("contributionModal");
      if (contribModal) {
        contribModal.addEventListener("click", function (e) {
          if (e.target === this) closeModal();
        });
      }

      // Copy Buffer Helper with Visual confirmation
      function copyText(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Friendly non-blocking alert fallback
            const toast = document.createElement("div");
            toast.innerText = "✓ Copied to clipboard!";
            toast.style.position = "fixed";
            toast.style.bottom = "40px";
            toast.style.left = "50%";
            toast.style.transform = "translateX(-50%)";
            toast.style.backgroundColor = "var(--secondary)";
            toast.style.color = "var(--bg-dark)";
            toast.style.padding = "10px 20px";
            toast.style.borderRadius = "50px";
            toast.style.fontWeight = "bold";
            toast.style.zIndex = "3000";
            toast.style.boxShadow = "0 10px 25px rgba(0,0,0,0.5)";
            document.body.appendChild(toast);
            setTimeout(() => { toast.remove(); }, 2000);
        }).catch((err) => {
            console.error("Failed to copy", err);
        });
      }

      // UPI QR Code state management and Dynamic intent string injection
      function setUpiAmount(amount, btnElement) {
        const buttons = document.querySelectorAll(".amount-btn");
        buttons.forEach((btn) => btn.classList.remove("active"));
        if (btnElement) {
          btnElement.classList.add("active");
        }

        const upiId = "mab.037215043540097@axisbank";
        const payeeName = "Tatkhalsa Foundation";
        const currency = "INR";
        let upiString = `upi://pay?pa=${upiId}&pn=${encodeURIComponent(payeeName)}&cu=${currency}`;

        if (amount > 0) {
          upiString += `&am=${amount}`;
        }

        const qrCodeImg = document.getElementById("upiQrCode");
        if (qrCodeImg) {
          qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(upiString)}`;
        }

        const directPayBtn = document.getElementById("directUpiPayBtn");
        if (directPayBtn) {
          directPayBtn.href = upiString;
        }
      }

      // Scroll Reveal triggers
      function reveal() {
        const reveals = document.querySelectorAll(".scroll-reveal");
        for (let i = 0; i < reveals.length; i++) {
          const windowHeight = window.innerHeight;
          const elementTop = reveals[i].getBoundingClientRect().top;
          if (elementTop < windowHeight - 100) {
            reveals[i].classList.add("visible");
          }
        }
      }
      window.addEventListener("scroll", reveal);
      reveal(); // Trigger on first run

      // Stats Counters triggering
      let statsAnimated = false;
      function animateCounters() {
        const counters = document.querySelectorAll(".counter");
        counters.forEach((counter) => {
          const target = +counter.getAttribute("data-target");
          const count = +counter.innerText;
          const inc = target / 100;
          if (count < target) {
            counter.innerText = Math.ceil(count + inc);
            setTimeout(animateCounters, 15);
          } else {
            counter.innerText = target + (target >= 50 ? "+" : "");
          }
        });
      }
      
      window.addEventListener("scroll", () => {
        if (statsAnimated) return;
        const statsSection = document.getElementById("stats");
        if (!statsSection) return;
        if (statsSection.getBoundingClientRect().top < window.innerHeight) {
          animateCounters();
          statsAnimated = true;
        }
      });

      // Synchronize and manage Mobile Dropdown Selector on ALL Pages with multi-permalink normalization
      const mobileNavSelect = document.getElementById("mobileNavSelect");
      const customWrapper = document.getElementById("customMobileNavWrapper");
      const customBtn = document.getElementById("customMobileNavBtn");
      const customLabel = document.getElementById("customMobileNavLabel");
      const customOpts = document.querySelectorAll(".custom-dropdown-opt");

      if (customBtn && customWrapper) {
        customBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          const isOpen = customWrapper.classList.contains("open");
          if (isOpen) {
            customWrapper.classList.remove("open");
            customBtn.setAttribute("aria-expanded", "false");
          } else {
            customWrapper.classList.add("open");
            customBtn.setAttribute("aria-expanded", "true");
          }
        });

        // Close when clicking outside
        document.addEventListener("click", (e) => {
          if (!customWrapper.contains(e.target)) {
            customWrapper.classList.remove("open");
            customBtn.setAttribute("aria-expanded", "false");
          }
        });
      }

      // Fully normalize URLs for comparison (handling trailing slashes, index.php, clean/plain query-params)
      function getNormalizedMatchKey(urlStr) {
        try {
          const urlObj = new URL(urlStr, window.location.origin);
          let pathname = urlObj.pathname.toLowerCase().replace(/\/$/, "");
          if (pathname === "" || pathname === "/index.php") {
            pathname = "/";
          }
          const searchParams = new URLSearchParams(urlObj.search);
          const pageId = searchParams.get("page_id") || searchParams.get("p") || "";
          const pageName = searchParams.get("pagename") || "";
          
          return {
            path: pathname,
            pageId: pageId,
            pageName: pageName.toLowerCase()
          };
        } catch (err) {
          return { path: urlStr.toLowerCase(), pageId: "", pageName: "" };
        }
      }

      if (customOpts.length > 0) {
        const currentLoc = getNormalizedMatchKey(window.location.href);
        let matchedIndex = -1;

        // Try exact keys first
        customOpts.forEach((opt, idx) => {
          const optLoc = getNormalizedMatchKey(opt.href);
          
          // Match by query strings (page_id etc.) if both present
          if (currentLoc.pageId && optLoc.pageId && currentLoc.pageId === optLoc.pageId) {
            matchedIndex = idx;
          } else if (currentLoc.pageName && optLoc.pageName && currentLoc.pageName === optLoc.pageName) {
            matchedIndex = idx;
          } else if (!currentLoc.pageId && !optLoc.pageId && currentLoc.path === optLoc.path) {
            matchedIndex = idx;
          }
        });

        // Fallback checks (e.g., if we are looking at subpages or partial matching)
        if (matchedIndex === -1) {
          customOpts.forEach((opt, idx) => {
            const optLoc = getNormalizedMatchKey(opt.href);
            if (currentLoc.path.includes(optLoc.path) && optLoc.path !== "/") {
              matchedIndex = idx;
            }
          });
        }

        // Apply active class, labels, and select index if custom dropdown matched
        if (matchedIndex !== -1 && customOpts[matchedIndex]) {
          customOpts.forEach(o => o.classList.remove("active"));
          customOpts[matchedIndex].classList.add("active");
          
          if (customLabel) {
            customLabel.innerText = customOpts[matchedIndex].innerText;
          }

          if (mobileNavSelect) {
            mobileNavSelect.selectedIndex = matchedIndex + 1; // account for placeholder at index 0
          }
        }
      }

      // Mobile Hamburg setup
      const hamburger = document.getElementById("hamburger");
      const navLinks = document.getElementById("nav-links");
      if (hamburger) {
        hamburger.addEventListener("click", () => {
          navLinks.classList.toggle("active");
          const innerNav = navLinks.querySelector(".nav-links");
          if (innerNav) {
            innerNav.classList.toggle("active");
          }
        });
      }

      // Interactive badges hover and touch popup logic
      const badgeContainers = document.querySelectorAll(".badge-container");
      badgeContainers.forEach(container => {
        container.addEventListener("click", (e) => {
          e.stopPropagation();
          const alreadyActive = container.classList.contains("active");
          badgeContainers.forEach(c => c.classList.remove("active"));
          if (!alreadyActive) {
            container.classList.add("active");
          }
        });
      });

      document.addEventListener("click", () => {
        badgeContainers.forEach(c => c.classList.remove("active"));
      });

      // Real dynamic Form submission connected to WordPress backend for direct email delivery
      const vForm = document.getElementById("volunteerForm");
      if (vForm) {
        vForm.addEventListener("submit", async (e) => {
          e.preventDefault();
          const statusEl = document.getElementById("vStatus");
          statusEl.style.color = "var(--text-light)";
          statusEl.textContent = "Sending application directly to tatkhalsafoundation@gmail.com...";

          const params = new URLSearchParams();
          params.append("action", "submit_volunteer");
          params.append("vName", document.getElementById("vName").value);
          params.append("vEmail", document.getElementById("vEmail").value);
          params.append("vPhone", document.getElementById("vPhone").value);
          params.append("vMessage", document.getElementById("vMessage").value);

          try {
            const response = await fetch("<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded"
              },
              body: params
            });
            const result = await response.json();
            if (result.success) {
              statusEl.style.color = "var(--accent-green)";
              statusEl.textContent = result.data.message || "Application submitted successfully! Check your inbox.";
              e.target.reset();
            } else {
              statusEl.style.color = "var(--accent-red)";
              statusEl.textContent = result.data.message || "There was an error sending your application. Please try again.";
            }
          } catch (error) {
            console.error("Submission error:", error);
            statusEl.style.color = "var(--accent-red)";
            statusEl.textContent = "Network error. Please email us directly at tatkhalsafoundation@gmail.com";
          }
        });
      }
    </script>

    <!-- Global Dynamic Liquid Background Canvas and SVG Gooey filter -->
    <canvas id="liquid-bg-canvas" style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; z-index: -2 !important; pointer-events: none !important; filter: url(#liquid-goo) !important; opacity: 0.18 !important; display: block !important;"></canvas>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none; position: absolute; width: 0; height: 0;">
      <defs>
        <filter id="liquid-goo">
          <feGaussianBlur in="SourceGraphic" stdDeviation="15" result="blur" />
          <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
          <feBlend in="SourceGraphic" in2="goo" />
        </filter>
      </defs>
    </svg>

    <script>
      (function() {
        const canvas = document.getElementById('liquid-bg-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        
        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;
        
        // Handle responsive resize with debouncing to prevent layout stutter
        let resizeTimeout;
        window.addEventListener('resize', () => {
          clearTimeout(resizeTimeout);
          resizeTimeout = setTimeout(() => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
          }, 100);
        });
        
        // Elegant color schemes matching the exact Tatkhalsa Foundation identity
        const colors = [
          '#d4af37', // Gold Logo Accent
          '#fdf7e7', // Warm Cream Primary Accent
          '#3285c7', // Bright Compassionate Blue
          '#d4af37', // Secondary Gold
          '#142a54'  // Subtle deep corporate navy accent
        ];
        
        // Define floating liquid metaball particles
        const particles = [];
        const particleCount = Math.min(10, Math.max(5, Math.floor((width * height) / 160000)));
        
        for (let i = 0; i < particleCount; i++) {
          particles.push({
            x: Math.random() * width,
            y: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.35, // Slow emotional flowing drift
            vy: (Math.random() - 0.5) * 0.35,
            radius: Math.random() * 80 + 75, // Massive merging liquid metaballs
            color: colors[i % colors.length]
          });
        }
        
        function animate() {
          if (document.visibilityState === 'hidden') {
            requestAnimationFrame(animate);
            return;
          }
          
          ctx.clearRect(0, 0, width, height);
          
          // Smooth math loop to float particles around and wrap screen bounds fluidly
          particles.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;
            
            const margin = p.radius + 15;
            if (p.x < -margin) p.x = width + margin;
            else if (p.x > width + margin) p.x = -margin;
            
            if (p.y < -margin) p.y = height + margin;
            else if (p.y > height + margin) p.y = -margin;
            
            // Draw circle which will be merged by standard SVG filter matrix
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            ctx.fillStyle = p.color;
            ctx.fill();
          });
          
          requestAnimationFrame(animate);
        }
        
        requestAnimationFrame(animate);
      })();
    </script>

    <!-- Floating Circular WhatsApp Seva Desk Button -->
    <a href="https://wa.me/919877038520" target="_blank" rel="noopener noreferrer" class="whatsapp-float-fab" aria-label="WhatsApp Seva Desk">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.458 5.704 1.459h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
      </svg>
      <span class="tooltip-text">WhatsApp Seva Desk</span>
    </a>

    <!-- Scroll-Driven Hero Logo to Header Logo Merge Animation -->
    <script>
      (function() {
        const bigLogo = document.querySelector('.hero-gurbani-logo');
        const smallLogo = document.querySelector('.header-logo-badge');
        
        if (!smallLogo) return;
        
        // If there is no big logo on this page, immediately make the small header logo fully visible
        if (!bigLogo) {
          smallLogo.style.opacity = '1';
          smallLogo.style.transform = 'scale(1)';
          return;
        }

        // Apply hardware acceleration hints immediately
        bigLogo.style.willChange = 'transform, opacity';
        smallLogo.style.willChange = 'transform, opacity';
        bigLogo.style.transformOrigin = 'center center';
        smallLogo.style.transformOrigin = 'center center';
        
        // Hide small logo initially to prepare for merge
        smallLogo.style.opacity = '0';
        smallLogo.style.transform = 'scale(0.7)';

        let initialRect = null;
        let targetRect = null;
        let isTicking = false;

        function measurePositions() {
          // Disable styles to get the reference un-scrolled layout geometry
          const origBigTransform = bigLogo.style.transform;
          const origBigOpacity = bigLogo.style.opacity;
          const origSmallTransform = smallLogo.style.transform;
          const origSmallOpacity = smallLogo.style.opacity;
          
          bigLogo.style.transform = 'none';
          bigLogo.style.opacity = '1';
          smallLogo.style.transform = 'none';
          smallLogo.style.opacity = '1';
          
          const bigRect = bigLogo.getBoundingClientRect();
          const smallRect = smallLogo.getBoundingClientRect();
          
          initialRect = {
            width: bigRect.width,
            height: bigRect.height,
            centerX: bigRect.left + bigRect.width / 2 + window.scrollX,
            centerY: bigRect.top + bigRect.height / 2 + window.scrollY
          };
          
          targetRect = {
            width: smallRect.width,
            height: smallRect.height,
            centerX: smallRect.left + smallRect.width / 2 + window.scrollX,
            centerY: smallRect.top + smallRect.height / 2 + window.scrollY
          };
          
          // Restore
          bigLogo.style.transform = origBigTransform;
          bigLogo.style.opacity = origBigOpacity;
          smallLogo.style.transform = origSmallTransform;
          smallLogo.style.opacity = origSmallOpacity;
        }

        // Perform measurements on load & resize
        if (document.readyState === 'complete') {
          measurePositions();
          updateMerge();
        } else {
          window.addEventListener('load', () => {
            measurePositions();
            updateMerge();
          });
        }

        window.addEventListener('resize', () => {
          measurePositions();
          updateMerge();
        });

        function updateMerge() {
          if (!initialRect || !targetRect) return;
          
          const scrollY = window.scrollY;
          const maxScroll = 180; // Distance over which merge completes
          const p = Math.min(1, Math.max(0, scrollY / maxScroll));
          
          // Elegant easing function
          const pEase = p * p * (3 - 2 * p);
          
          const scaleRatio = targetRect.width / initialRect.width;
          const currentScale = 1 + pEase * (scaleRatio - 1);
          
          const tx = pEase * (targetRect.centerX - initialRect.centerX);
          const ty = pEase * (targetRect.centerY - initialRect.centerY + scrollY);
          
          bigLogo.style.transform = `translate(${tx}px, ${ty}px) scale(${currentScale})`;
          bigLogo.style.opacity = (1 - pEase).toFixed(3);
          
          smallLogo.style.opacity = pEase.toFixed(3);
          smallLogo.style.transform = `scale(${0.7 + 0.3 * pEase})`;
          
          isTicking = false;
        }

        window.addEventListener('scroll', () => {
          if (!isTicking) {
            window.requestAnimationFrame(updateMerge);
            isTicking = true;
          }
        }, { passive: true });
      })();
    </script>

    <?php wp_footer(); ?>
  </body>
</html>
