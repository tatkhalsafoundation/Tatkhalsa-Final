<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package TatkhalsaTheme
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero" id="home">
  <!-- Dynamic BG Video with fallback poster -->
  <video
    autoplay
    muted
    loop
    playsinline
    class="hero-video"
    poster="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Golden_Temple_India.jpg/1920px-Golden_Temple_India.jpg"
  >
    <source
      src="https://upload.wikimedia.org/wikipedia/commons/2/29/Revealed-_The_Golden_Temple_%28HD_Version%29.webm"
      type="video/webm"
    />
    <source
      src="https://upload.wikimedia.org/wikipedia/commons/transcoded/2/29/Revealed-_The_Golden_Temple_%28HD_Version%29.webm/Revealed-_The_Golden_Temple_%28HD_Version%29.webm.720p.mp4"
      type="video/mp4"
    />
  </video>
  <div class="hero-overlay"></div>
  <div class="container scroll-reveal" style="position: relative; z-index: 2">
    <div class="gurbani-text">
      ਏਕਹੀ ਕੀ ਸੇਵ ਸਭ ਹੀ ਕੋ ਗੁਰਦੇਵ ਏਕ ॥<br />
      ਏਕਹੀ ਸਰੂਪ ਸਭੈ ਏਕੈ ਜੋਤਿ ਜਾਨਬੋ ॥
    </div>
    
    <!-- Centered Logo under Gurbani verse -->
    <div class="hero-logo-wrapper" style="display: flex; justify-content: center; margin-bottom: 25px; margin-top: 10px;">
      <img
        src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>"
        alt="Tatkhalsa Foundation Logo"
        class="hero-gurbani-logo"
        style="width: 240px; height: 240px; object-fit: contain;"
      />
    </div>

    <h1>Serving Humanity Through Seva, Compassion, and Community Action</h1>
    <p>
      Tatkhalsa Foundation is a registered non-profit organization dedicated
      to humanitarian relief, healthcare support, youth development,
      environmental initiatives, and preservation of Sikh heritage across
      Punjab and beyond.
    </p>

    <div class="trust-badges">
      <div class="badge-container" data-badge-id="ngo">
        <span class="badge" style="background: rgba(209, 61, 82, 0.2); border: 1px solid rgba(209, 61, 82, 0.5);">
          ✓ Registered NGO
        </span>
        <div class="badge-popup">
          <div class="badge-popup-header" style="border-bottom: 2px solid var(--secondary);">
            <span class="popup-icon">🏛️</span>
            <h4>Registered NGO</h4>
          </div>
          <p>CIN: U88900PB2023NPL059225. Officially incorporated with deep devotion towards transparent, audited community-focused Seva.</p>
        </div>
      </div>

      <div class="badge-container" data-badge-id="12a">
        <span class="badge" style="background: rgba(56, 142, 99, 0.2); border: 1px solid rgba(56, 142, 99, 0.5);">
          ✓ 12A Registered
        </span>
        <div class="badge-popup">
          <div class="badge-popup-header" style="border-bottom: 2px solid var(--accent-green);">
            <span class="popup-icon">📜</span>
            <h4>12A Registered</h4>
          </div>
          <p>Income Tax Department tax-exemption approval, validating the fully charitable nature of all our activities and accounts.</p>
        </div>
      </div>

      <div class="badge-container" data-badge-id="80g">
        <span class="badge" style="background: rgba(50, 133, 199, 0.2); border: 1px solid rgba(50, 133, 199, 0.5);">
          ✓ 80G Approved
        </span>
        <div class="badge-popup">
          <div class="badge-popup-header" style="border-bottom: 2px solid var(--accent-blue);">
            <span class="popup-icon">💰</span>
            <h4>80G Approved</h4>
          </div>
          <p>Tax-exempt contributions under Section 80G, allowing our esteemed donors in India to claim tax deductions on support.</p>
        </div>
      </div>

      <div class="badge-container" data-badge-id="csr">
        <span class="badge" style="background: rgba(144, 85, 188, 0.2); border: 1px solid rgba(144, 85, 188, 0.5);">
          ✓ CSR Eligible
        </span>
        <div class="badge-popup">
          <div class="badge-popup-header" style="border-bottom: 2px solid var(--accent-purple);">
            <span class="popup-icon">💼</span>
            <h4>CSR Eligible</h4>
          </div>
          <p>Fully compliant with corporate social responsibility guidelines, enabling corporates to execute CSR programs in Punjab.</p>
        </div>
      </div>
    </div>

    <div class="hero-buttons">
      <button class="btn" style="background: var(--accent-red); color: var(--primary);" onclick="openModal()">
        Support Our Seva
      </button>
      <a href="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>" class="btn-outline">Become a Volunteer</a>
    </div>
  </div>
</section>

<!-- Hot Update Callout Section: Punjab Flood Relief -->
<section class="sos-banner">
  <div class="sos-banner-container scroll-reveal">
    <div class="sos-indicator">
      <span class="sos-dot"></span>
      Emergency Update
    </div>
    <h2 class="sos-banner-title">
      Punjab Flood Relief 2025
    </h2>
    <p class="sos-banner-desc">
      Emergency response initiated. Our volunteer teams are actively providing medical aid, Langar, and rescue.
    </p>
    <a href="<?php echo esc_url( home_url( '/punjab-flood-relief/' ) ); ?>" class="sos-banner-btn">
      View & Support
    </a>
  </div>
</section>

<!-- Financial Transparency / Budget Allocation Section -->
<section id="transparency" style="background-color: var(--bg-shade-5); position: relative;">
  <div style="position: absolute; top: -50px; left: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, transparent 70%); pointer-events: none;"></div>
  <div class="container scroll-reveal">
    <h2 class="section-title">Financial Transparency</h2>
    <div class="budget-grid">
      <div style="flex: 1; max-width: 500px;">
        <h3 style="color: var(--text-dark); margin-bottom: 20px; font-family: var(--font-sans);">
          Annual Budget Allocation
        </h3>
        <p style="color: var(--text-light); margin-bottom: 30px;">
          We are committed to full accountability. Here is how our resources
          are allocated to maximize our impact on the community.
        </p>
        <div class="budget-chart">
          <div class="budget-bar">
            <span class="bar-label">Community Programs</span>
            <div class="bar-track">
              <div class="bar-fill" style="width: 45%; background: var(--accent-red)"></div>
            </div>
            <span class="bar-value" style="color: var(--accent-red)">45%</span>
          </div>
          <div class="budget-bar">
            <span class="bar-label">Education & Youth</span>
            <div class="bar-track">
              <div class="bar-fill" style="width: 25%; background: var(--accent-green)"></div>
            </div>
            <span class="bar-value" style="color: var(--accent-green)">25%</span>
          </div>
          <div class="budget-bar">
            <span class="bar-label">Disaster Relief</span>
            <div class="bar-track">
              <div class="bar-fill" style="width: 15%; background: var(--accent-blue)"></div>
            </div>
            <span class="bar-value" style="color: var(--accent-blue)">15%</span>
          </div>
          <div class="budget-bar">
            <span class="bar-label">Admin & Ops</span>
            <div class="bar-track">
              <div class="bar-fill" style="width: 15%; background: var(--accent-purple)"></div>
            </div>
            <span class="bar-value" style="color: var(--accent-purple)">15%</span>
          </div>
        </div>
      </div>
      <div style="flex: 1; display: flex; justify-content: center;">
        <div class="pie-wrapper">
          <div class="pie-inner">
            <span style="font-size: 0.9rem; color: var(--text-light); text-transform: uppercase;">Est. Annual</span>
            <span style="font-size: 2.2rem; font-weight: 800; color: var(--primary);">2.4M</span>
          </div>
        </div>
      </div>
    </div>

    <!-- New Dynamic Seva Contribution Ledger & Declaration Section -->
    <div class="ledger-box" style="margin-top: 60px; border-top: 1px solid rgba(212, 175, 55, 0.15); padding-top: 45px;">
      <h3 style="color: var(--text-dark); margin-bottom: 25px; font-family: var(--font-sans); text-align: center; font-size: 1.8rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
        <span>⚜️</span> Recent Seva Ledger (Dasvandh Board)
      </h3>
      <p style="color: var(--text-light); text-align: center; max-width: 700px; margin: 0 auto 40px auto; font-size: 0.95rem; line-height: 1.6;">
        Every contribution helps support our free community kitchen (Langar), flood relief operations, and educational materials. Fill in the form to self-declare a voluntary Seva contribution.
      </p>

      <div class="budget-grid" style="align-items: flex-start; gap: 40px;">
        <!-- Left Column: Transactions List -->
        <div style="flex: 1.2; min-width: 280px; width: 100%;">
          <h4 style="color: var(--primary); margin-bottom: 20px; font-size: 1.25rem; display: flex; align-items: center; gap: 8px;">
            <span>📋 Live Contributions Board</span>
            <span style="font-size: 0.75rem; background: rgba(0, 135, 90, 0.15); color: #00bf75; padding: 3px 8px; border-radius: 12px; font-weight: bold;">Verified Ledger</span>
          </h4>
          
          <div id="transactions-loading" style="color: var(--text-light); padding: 30px 0; text-align: center; font-size: 0.950rem;">
            ⏳ Retrieving active ledgers...
          </div>
          <div id="transactions-container" style="display: flex; flex-direction: column; gap: 14px; max-height: 480px; overflow-y: auto; padding-right: 8px;">
            <!-- Rendered dynamically -->
          </div>
        </div>

        <!-- Right Column: Declaration Form -->
        <div style="flex: 0.8; min-width: 280px; width: 100%; background: rgba(255,255,255,0.02); border: 1px solid rgba(212,175,55,0.15); padding: 30px; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.25); box-sizing: border-box;">
          <h4 style="color: var(--primary); margin-bottom: 12px; font-size: 1.25rem;">
            ✍️ Report Your Seva
          </h4>
          <p style="color: var(--text-light); font-size: 0.85rem; margin-bottom: 20px; line-height: 1.4;">
            If you transferred funds via Bank or UPI QR, register below to list it on the board.
          </p>
          <form id="declarationForm">
            <div style="margin-bottom: 16px;">
              <label for="dtName" id="dtNameLabel" style="display: block; color: var(--text-dark); margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Your Name / Organisation</label>
              <input type="text" id="dtName" required placeholder="Sardarni / Sardar..." style="width: 100%; padding: 12px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); background: var(--bg-dark); color: #fff; font-size: 0.95rem; box-sizing: border-box; transition: border-color 0.2s;">
            </div>

            <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px; background: rgba(212,175,55,0.05); padding: 10px 12px; border-radius: 6px; border: 1px solid rgba(212,175,55,0.08);">
              <input type="checkbox" id="dtAnonymous" style="cursor: pointer; width: 18px; height: 18px; accent-color: var(--primary);">
              <label for="dtAnonymous" style="color: var(--text-dark); font-size: 0.9rem; cursor: pointer; user-select: none; font-weight: 500;">Contribute anonymously</label>
            </div>

            <div style="margin-bottom: 16px;">
              <label for="dtAmount" style="display: block; color: var(--text-dark); margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Seva Amount (₹)</label>
              <input type="number" id="dtAmount" required min="1" placeholder="e.g. 5100" style="width: 100%; padding: 12px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); background: var(--bg-dark); color: #fff; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 16px;">
              <label for="dtSevaType" style="display: block; color: var(--text-dark); margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Select Seva Project</label>
              <select id="dtSevaType" required style="width: 100%; padding: 12px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); background: var(--bg-dark); color: #fff; font-size: 0.95rem; box-sizing: border-box; cursor: pointer;">
                <option value="General Seva">General Seva / Dasvandh</option>
                <option value="Langar Seva">Guru ka Langar Seva</option>
                <option value="Punjab Flood Relief">Punjab Flood Relief</option>
                <option value="Education Support">Educational & Youth Support</option>
              </select>
            </div>

            <div style="margin-bottom: 24px;">
              <label for="dtNote" style="display: block; color: var(--text-dark); margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Reference / Message (Optional)</label>
              <input type="text" id="dtNote" placeholder="e.g. Transferred via GPay / Gursikh Sewa" style="width: 100%; padding: 12px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); background: var(--bg-dark); color: #fff; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; padding: 14px; background: var(--primary); color: var(--bg-dark); border: none; border-radius: 6px; font-weight: bold; font-size: 1rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 15px rgba(212,175,55,0.15); text-transform: uppercase; letter-spacing: 0.5px;">
              Submit Declaration
            </button>

            <div id="dtStatus" style="margin-top: 15px; font-size: 0.9rem; text-align: center; font-weight: 500;"></div>
          </form>
        </div>
      </div>
    </div>

    <!-- Live Ledger Scripts -->
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const ajaxUrl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
        const anonCheckbox = document.getElementById("dtAnonymous");
        const nameField = document.getElementById("dtName");
        
        // Handle anonymous checkbox visibility effect
        if (anonCheckbox && nameField) {
          anonCheckbox.addEventListener("change", () => {
            if (anonCheckbox.checked) {
              nameField.value = "Anonymous Sevadar";
              nameField.setAttribute("disabled", "true");
              nameField.removeAttribute("required");
              nameField.style.opacity = "0.5";
            } else {
              nameField.value = "";
              nameField.removeAttribute("disabled");
              nameField.setAttribute("required", "true");
              nameField.style.opacity = "1";
            }
          });
        }

        // Relative Formatting
        function formatDateString(dateStr) {
          if (!dateStr) return "Just now";
          try {
            const d = new Date(dateStr.replace(/-/g, "/"));
            if (isNaN(d.getTime())) return dateStr;
            const now = new Date();
            const diffMs = now - d;
            const diffMins = Math.floor(diffMs / 60000);
            
            if (diffMins < 1) return "Just now";
            if (diffMins < 60) return `${diffMins}m ago`;
            const diffHrs = Math.floor(diffMins / 60);
            if (diffHrs < 24) return `${diffHrs}h ago`;
            
            return d.toLocaleDateString("en-IN", { month: "short", day: "numeric", year: "numeric" });
          } catch (e) {
            return dateStr;
          }
        }

        // Fetch and Render Transactions list
        async function loadTransactions() {
          const container = document.getElementById("transactions-container");
          const loadingEl = document.getElementById("transactions-loading");
          
          try {
            const response = await fetch(`${ajaxUrl}?action=get_transactions`);
            const data = await response.json();
            
            if (data.success && data.data.transactions) {
              if (loadingEl) loadingEl.style.display = "none";
              container.innerHTML = "";
              
              if (data.data.transactions.length === 0) {
                container.innerHTML = `<div style="color: var(--text-light); padding: 30px; text-align: center;">No transactions found. Be the first to report standard seva!</div>`;
                return;
              }
              
              data.data.transactions.forEach(tx => {
                const card = document.createElement("div");
                card.style.background = "rgba(255, 255, 255, 0.03)";
                card.style.border = "1px solid rgba(255, 255, 255, 0.05)";
                card.style.borderRadius = "8px";
                card.style.padding = "16px";
                card.style.display = "flex";
                card.style.justifyContent = "space-between";
                card.style.alignItems = "center";
                card.style.gap = "15px";
                card.style.transition = "all 0.25s";
                
                const verifiedTag = tx.verified == 1 
                  ? `<span style="font-size: 0.725rem; font-weight: bold; background: rgba(0, 191, 117, 0.12); color: #00bf75; padding: 2px 7px; border-radius: 10px; margin-left: 8px; display: inline-flex; align-items: center; gap: 4px;">Verified ✓</span>`
                  : `<span style="font-size: 0.725rem; font-weight: bold; background: rgba(212, 175, 55, 0.1); color: var(--primary); padding: 2px 7px; border-radius: 10px; margin-left: 8px; display: inline-flex; align-items: center; gap: 4px;">Pending Sync ⏳</span>`;

                const contributorName = tx.anonymous == 1 ? "Anonymous Sevadar" : tx.name;
                const noteElement = tx.note 
                  ? `<p style="margin: 5px 0 0 0; font-size: 0.8rem; color: var(--text-light); font-style: italic;">"${escapeHtml(tx.note)}"</p>` 
                  : '';

                card.innerHTML = `
                  <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="background: rgba(212,175,55,0.08); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); border: 1px solid rgba(212,175,55,0.15); flex-shrink: 0;">
                      ⚜️
                    </div>
                    <div>
                      <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 6px;">
                        <strong style="color: var(--text-dark); font-size: 0.95rem;">${escapeHtml(contributorName)}</strong>
                        ${verifiedTag}
                      </div>
                      <span style="font-size: 0.75rem; color: var(--primary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: 3px;">${escapeHtml(tx.seva_type)}</span>
                      ${noteElement}
                    </div>
                  </div>
                  <div style="text-align: right; flex-shrink: 0;">
                    <span style="font-size: 1.15rem; font-weight: bold; color: var(--accent-green); display: block;">₹${parseFloat(tx.amount).toLocaleString('en-IN')}</span>
                    <span style="font-size: 0.75rem; color: var(--text-light); display: block; margin-top: 3px;">${formatDateString(tx.date)}</span>
                  </div>
                `;
                container.appendChild(card);
              });
            } else {
              if (loadingEl) loadingEl.textContent = "Unable to retrieve database. Please refresh.";
            }
          } catch (err) {
            console.error("Ledger fetch error:", err);
            if (loadingEl) loadingEl.textContent = "Network sync timeout. Refreshing soon...";
          }
        }

        function escapeHtml(text) {
          if (!text) return "";
          return text
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
        }

        // Handle form declaration submit
        const decForm = document.getElementById("declarationForm");
        if (decForm) {
          decForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const statusEl = document.getElementById("dtStatus");
            statusEl.style.color = "var(--text-light)";
            statusEl.textContent = "Recording your Seva contribution details...";

            const params = new URLSearchParams();
            params.append("action", "submit_transaction");
            params.append("tName", nameField.value);
            params.append("tAnonymous", anonCheckbox.checked ? "1" : "0");
            params.append("tAmount", document.getElementById("dtAmount").value);
            params.append("tSevaType", document.getElementById("dtSevaType").value);
            params.append("tNote", document.getElementById("dtNote").value);

            try {
              const response = await fetch(ajaxUrl, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: params
              });
              const result = await response.json();
              if (result.success) {
                statusEl.style.color = "var(--accent-green)";
                statusEl.textContent = result.data.message || "Seva submitted! Thank you.";
                decForm.reset();
                if (anonCheckbox.checked) {
                  anonCheckbox.checked = false;
                  nameField.value = "";
                  nameField.removeAttribute("disabled");
                  nameField.setAttribute("required", "true");
                  nameField.style.opacity = "1";
                }
                // Reload transactions immediately to show new item
                await loadTransactions();
              } else {
                statusEl.style.color = "var(--accent-red)";
                statusEl.textContent = result.data.message || "Error submitting seva. Please verify entries.";
              }
            } catch (err) {
              console.error("Seva declaration error:", err);
              statusEl.style.color = "var(--accent-red)";
              statusEl.textContent = "Network error. Please try declaring again.";
            }
          });
        }

        // Run initial load
        loadTransactions();
      });
    </script>
  </div>
</section>

<!-- Impact Statistics Section -->
<section class="stats-section">
  <div class="container scroll-reveal" id="stats">
    <div class="stats-grid">
      <div class="stat-item">
        <h3 class="counter" data-target="5000">0</h3>
        <p>Lives Impacted</p>
      </div>
      <div class="stat-item">
        <h3 class="counter" data-target="100">0</h3>
        <p>Blood Donors</p>
      </div>
      <div class="stat-item">
        <h3 class="counter" data-target="50">0</h3>
        <p>Initiatives</p>
      </div>
      <div class="stat-item">
        <h3 class="counter" data-target="100">0</h3>
        <p>Volunteers</p>
      </div>
    </div>
  </div>
</section>

<!-- Gurbani Quote Section -->
<section class="gurbani-quote-section scroll-reveal">
  <div class="gurbani-quote-container">
    <div class="gurbani-ornament">✧ ✦ ✧</div>
    <div class="gurbani-gurmukhi">ਵਿਚਿ ਦੁਨੀਆ ਸੇਵ ਕਮਾਈਐ ॥ ਤਾ ਦਰਗਹ ਬੈਸਣੁ ਪਾਈਐ ॥</div>
    <div class="gurbani-translit">vich dhuneeaa saev kamaaeeai || thaa dharageh baisan paaeeai ||</div>
    <div class="gurbani-english">"In the midst of this world, perform selfless service, and you shall find a place of honor in the Divine Court."</div>
    <div class="gurbani-source">Sri Guru Granth Sahib Ji — Ang 26</div>
  </div>
</section>


<?php
get_footer();
?>
