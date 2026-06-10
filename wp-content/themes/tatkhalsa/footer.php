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
      // Scroll to Top Logic
      const backToTopBtn = document.getElementById("backToTop");
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

      // Modal Display Mechanics
      function openModal() {
        document.getElementById("contributionModal").classList.add("active");
      }
      function closeModal() {
        document.getElementById("contributionModal").classList.remove("active");
      }

      document.getElementById("contributionModal").addEventListener("click", function (e) {
          if (e.target === this) closeModal();
      });

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
        qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(upiString)}`;
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

      // Sync Mobile Dropdown Select Option with Active Page URL
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

      if (mobileNavSelect) {
        const currentPath = window.location.pathname;
        let matchedIndex = -1;
        for (let i = 0; i < mobileNavSelect.options.length; i++) {
          const opt = mobileNavSelect.options[i];
          if (opt.value) {
            try {
              const optPath = new URL(opt.value, window.location.origin).pathname;
              if (currentPath === optPath || (currentPath === "/" && optPath === "/index.php") || (currentPath === "/index.php" && optPath === "/")) {
                opt.selected = true;
                matchedIndex = i;
                break;
              }
            } catch (e) {
              if (currentPath.includes(opt.value)) {
                opt.selected = true;
                matchedIndex = i;
                break;
              }
            }
          }
        }

        // Sync visual custom dropdown active option (placeholder is index 0)
        if (matchedIndex !== -1 && customOpts.length >= matchedIndex) {
          const activeOptIndex = matchedIndex - 1; 
          if (activeOptIndex >= 0 && customOpts[activeOptIndex]) {
            customOpts.forEach(o => o.classList.remove("active"));
            customOpts[activeOptIndex].classList.add("active");
            if (customLabel) {
              customLabel.innerText = customOpts[activeOptIndex].innerText;
            }
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

          const formData = new FormData();
          formData.append("action", "submit_volunteer");
          formData.append("vName", document.getElementById("vName").value);
          formData.append("vEmail", document.getElementById("vEmail").value);
          formData.append("vPhone", document.getElementById("vPhone").value);
          formData.append("vMessage", document.getElementById("vMessage").value);

          try {
            const response = await fetch("<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", {
              method: "POST",
              body: formData
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

    <?php wp_footer(); ?>
  </body>
</html>
