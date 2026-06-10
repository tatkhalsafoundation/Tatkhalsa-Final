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
    poster="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Golden_Temple_India.jpg/640px-Golden_Temple_India.jpg"
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
    <!-- Centered Logo -->
    <div class="hero-logo-wrapper">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: contents;">
        <img
          src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>"
          alt="Tatkhalsa Foundation Logo"
          class="hero-gurbani-logo"
        />
      </a>
    </div>

    <!-- Gurbani Text -->
    <div class="gurbani-text">
      ਏਕਹੀ ਕੀ ਸੇਵ ਸਭ ਹੀ ਕੋ ਗੁਰਦੇਵ ਏਕ ॥<br />
      ਏਕਹੀ ਸਰੂਪ ਸਭੈ ਏਕੈ ਜੋਤਿ ਜਾਨਬੋ ॥
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

<!-- Current Campaigns Section (Fulfilling User Request) -->
<section id="current-campaigns" style="background-color: var(--bg-shade-3); position: relative; padding: 100px 0; overflow: hidden; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
  <div style="position: absolute; top: 0; right: 0; width: 400px; height: 400px; background: radial-gradient(circle, rgba(212, 175, 55, 0.03) 0%, transparent 70%); pointer-events: none;"></div>
  <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
    
    <span class="campaign-subtitle" style="font-family: 'Cinzel', serif; color: var(--secondary); letter-spacing: 3px; font-size: 0.95rem; text-transform: uppercase; font-weight: 600; text-align: center; display: block; margin-bottom: 8px;">
      Tatkhalsa Foundation Seva
    </span>
    <h2 class="section-title" style="margin-bottom: 50px;">
      Current Campaigns
    </h2>

    <!-- Interactive Carousel Wrapper -->
    <div class="campaign-slider-wrapper" style="position: relative; max-width: 440px; margin: 0 auto; overflow: visible;">
      <div class="campaign-slides-container">
        
        <!-- Slide 1: Cancer Patient Nimrat Kaur -->
        <div class="campaign-slide active" data-index="0" data-title="Nimrat Kaur (2 yrs, Cancer)" onclick="openCampaignModal('Cancer Patient Nimrat Kaur (2yrs old) oncology treatment help')">
          <div class="campaign-card">
            <span class="campaign-view-tag">View Campaign</span>
            <img class="campaign-img" src="https://images.unsplash.com/photo-1543332143-4e8c27e3256f?auto=format&fit=crop&w=800&q=80" alt="Pediatric Cancer Care Support - Nimrat Kaur" />
            <div class="campaign-overlay">
              <span class="campaign-category" style="color: var(--accent-red);">Healthcare Aid</span>
              <h3 class="campaign-title">Cancer Patient Nimrat Kaur</h3>
              <p class="campaign-desc">2-year-old child battling cancer. Urgent financial support needed for life-saving oncology chemotherapy sessions.</p>
              
              <!-- Progress Tracker -->
              <div class="campaign-progress-wrapper">
                <div class="campaign-progress-stats">
                  <span>Raised: ₹2,25,000</span>
                  <span>Goal: ₹5,00,000</span>
                </div>
                <div class="campaign-progress-bar">
                  <div class="campaign-progress-fill" style="width: 45%; background: var(--accent-red);"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2: Punjab Flood Relief -->
        <div class="campaign-slide" data-index="1" data-title="Punjab Flood Relief 2025" onclick="openCampaignModal('Punjab Flood Relief 2025')">
          <div class="campaign-card">
            <span class="campaign-view-tag">View Campaign</span>
            <img class="campaign-img" src="https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80" alt="Punjab Flood Relief 2025" />
            <div class="campaign-overlay">
              <span class="campaign-category" style="color: var(--accent-blue);">Emergency SOS</span>
              <h3 class="campaign-title">Punjab Flood Relief 2025</h3>
              <p class="campaign-desc">Providing urgent dry ration packs, medical camps, and infrastructure repair for distressed remote flood areas.</p>
              
              <!-- Progress Tracker -->
              <div class="campaign-progress-wrapper">
                <div class="campaign-progress-stats">
                  <span>Raised: ₹5,80,000</span>
                  <span>Goal: ₹10,00,000</span>
                </div>
                <div class="campaign-progress-bar">
                  <div class="campaign-progress-fill" style="width: 58%; background: var(--accent-blue);"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 3: grocery help -->
        <div class="campaign-slide" data-index="2" data-title="Grocery Help Seva" onclick="openCampaignModal('Grocery Seva for impoverished families')">
          <div class="campaign-card">
            <span class="campaign-view-tag">View Campaign</span>
            <img class="campaign-img" src="https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80" alt="Grocery Help Seva" />
            <div class="campaign-overlay">
              <span class="campaign-category" style="color: var(--accent-green);">Ration Seva</span>
              <h3 class="campaign-title">Essential Grocery Help</h3>
              <p class="campaign-desc">Distributing monthly ration hampers with wheat flour, pulses, ghee, sugar, and hygiene kits to disabled and elderly families.</p>
              
              <!-- Progress Tracker -->
              <div class="campaign-progress-wrapper">
                <div class="campaign-progress-stats">
                  <span>Raised: ₹1,60,000</span>
                  <span>Goal: ₹3,00,000</span>
                </div>
                <div class="campaign-progress-bar">
                  <div class="campaign-progress-fill" style="width: 53.3%; background: var(--accent-green);"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 4: 1984 victims families -->
        <div class="campaign-slide" data-index="3" data-title="1984 Victims Family Support" onclick="openCampaignModal('Support for family members of 1984 victims')">
          <div class="campaign-card">
            <span class="campaign-view-tag">View Campaign</span>
            <img class="campaign-img" src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80" alt="1984 Victim Families Support" />
            <div class="campaign-overlay">
              <span class="campaign-category" style="color: var(--accent-purple);">Livelihood Aid</span>
              <h3 class="campaign-title">1984 Victim Families</h3>
              <p class="campaign-desc">Providing education sponsorships and monthly livelihood stipends to families & widows of 1984 victims.</p>
              
              <!-- Progress Tracker -->
              <div class="campaign-progress-wrapper">
                <div class="campaign-progress-stats">
                  <span>Raised: ₹3,15,000</span>
                  <span>Goal: ₹6,00,000</span>
                </div>
                <div class="campaign-progress-bar">
                  <div class="campaign-progress-fill" style="width: 52.5%; background: var(--accent-purple);"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 5: impoverished marriage support -->
        <div class="campaign-slide" data-index="4" data-title="Gursikh Daughters Marriages Seva" onclick="openCampaignModal('Marriage of Gursikh families below poverty line')">
          <div class="campaign-card">
            <span class="campaign-view-tag">View Campaign</span>
            <img class="campaign-img" src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=800&q=80" alt="Underprivileged Marriages support" />
            <div class="campaign-overlay">
              <span class="campaign-category" style="color: var(--accent-orange);">Social Welfare</span>
              <h3 class="campaign-title">Gursikh Marriages Seva</h3>
              <p class="campaign-desc">Supporting respectful Anand Karaj and essential household startup kits for daughters of Gursikh families below poverty line.</p>
              
              <!-- Progress Tracker -->
              <div class="campaign-progress-wrapper">
                <div class="campaign-progress-stats">
                  <span>Raised: ₹1,52,000</span>
                  <span>Goal: ₹4,00,000</span>
                </div>
                <div class="campaign-progress-bar">
                  <div class="campaign-progress-fill" style="width: 38%; background: var(--accent-orange);"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Navigation Controls -->
      <div class="campaign-controls-wrapper" style="display: flex; justify-content: center; align-items: center; gap: 30px; margin-top: 30px; position: relative; z-index: 20;">
        <button class="campaign-arrow-btn prev" aria-label="Previous Campaign" onclick="prevCampaign(event)">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>
        
        <!-- Dots indicators -->
        <div class="campaign-dots-container">
          <span class="campaign-dot active" onclick="gotoCampaign(0)"></span>
          <span class="campaign-dot" onclick="gotoCampaign(1)"></span>
          <span class="campaign-dot" onclick="gotoCampaign(2)"></span>
          <span class="campaign-dot" onclick="gotoCampaign(3)"></span>
          <span class="campaign-dot" onclick="gotoCampaign(4)"></span>
        </div>

        <button class="campaign-arrow-btn next" aria-label="Next Campaign" onclick="nextCampaign(event)">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </div>

    </div>

    <!-- View All Pill Button -->
    <div style="text-align: center; margin-top: 50px;">
      <a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>" class="btn" style="background-color: var(--secondary); color: var(--bg-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1.5px; padding: 14px 34px; box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2); transition: all 0.3s; font-weight: 700; border-radius: 50px; text-decoration: none;">
        View All Campaigns
      </a>
    </div>

  </div>
</section>

<!-- Slider JavaScript and Modal Integration -->
<script>
  let currentCampaignIndex = 0;
  const totalCampaigns = 5;

  function showCampaign(index) {
    currentCampaignIndex = (index + totalCampaigns) % totalCampaigns;
    
    const slides = document.querySelectorAll(".campaign-slide");
    const dots = document.querySelectorAll(".campaign-dot");
    
    slides.forEach((slide) => {
      slide.classList.remove("active", "next-preview", "prev-preview");
    });
    
    dots.forEach((dot) => {
      dot.classList.remove("active");
    });
    
    // Set current active slide
    const activeSlide = document.querySelector(`.campaign-slide[data-index="${currentCampaignIndex}"]`);
    if (activeSlide) {
      activeSlide.classList.add("active");
    }
    
    // Set next-preview link
    const nextIndex = (currentCampaignIndex + 1) % totalCampaigns;
    const nextSlide = document.querySelector(`.campaign-slide[data-index="${nextIndex}"]`);
    if (nextSlide) {
      nextSlide.classList.add("next-preview");
    }
    
    // Set prev-preview link
    const prevIndex = (currentCampaignIndex - 1 + totalCampaigns) % totalCampaigns;
    const prevSlide = document.querySelector(`.campaign-slide[data-index="${prevIndex}"]`);
    if (prevSlide) {
      prevSlide.classList.add("prev-preview");
    }
    
    // Update active dot indicators
    const activeDot = dots[currentCampaignIndex];
    if (activeDot) {
      activeDot.classList.add("active");
    }
  }

  function prevCampaign(e) {
    if (e) e.stopPropagation();
    showCampaign(currentCampaignIndex - 1);
  }

  function nextCampaign(e) {
    if (e) e.stopPropagation();
    showCampaign(currentCampaignIndex + 1);
  }

  function gotoCampaign(index) {
    showCampaign(index);
  }

  // Pre-fill campaign details in the UPI & Bank Modal for a seamless user redirection
  function openCampaignModal(campaignName) {
    let titleEl = document.getElementById("campaign-target-title");
    if (!titleEl) {
      const modalHeader = document.querySelector("#contributionModal h3");
      if (modalHeader) {
        titleEl = document.createElement("div");
        titleEl.id = "campaign-target-title";
        titleEl.style.color = "var(--secondary)";
        titleEl.style.fontSize = "0.95rem";
        titleEl.style.fontWeight = "bold";
        titleEl.style.marginTop = "5px";
        titleEl.style.marginBottom = "15px";
        titleEl.style.padding = "10px 15px";
        titleEl.style.borderRadius = "12px";
        titleEl.style.lineHeight = "1.4";
        titleEl.style.backgroundColor = "rgba(212, 175, 55, 0.08)";
        titleEl.style.borderLeft = "4px solid var(--secondary)";
        titleEl.style.textAlign = "left";
        modalHeader.parentNode.insertBefore(titleEl, modalHeader.nextSibling);
      }
    }
    if (titleEl) {
      titleEl.innerHTML = `<span style="color: var(--text-light); font-weight: normal; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Designated Campaign Seva:</span><br/><strong style="color:#ffffff;">${campaignName}</strong>`;
      titleEl.style.display = "block";
    }

    // Adapt the UPI QR code and Direct payment link to send the correct remarks tag so accounts group correctly
    const upiId = "mab.037215043540097@axisbank";
    const payeeName = "Tatkhalsa Foundation";
    const remark = 'Seva ' + campaignName.substring(0, 15);
    const upiString = `upi://pay?pa=${upiId}&pn=${encodeURIComponent(payeeName)}&cu=INR&tn=${encodeURIComponent(remark)}`;
    
    const directPayBtn = document.getElementById("directUpiPayBtn");
    if (directPayBtn) {
      directPayBtn.href = upiString;
    }
    
    const qrCodeImg = document.getElementById("upiQrCode");
    if (qrCodeImg) {
      qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(upiString)}`;
    }

    // Activate modal using existing functions
    if (typeof openModal === "function") {
      openModal();
    }
  }

  // Swipe Support for Touch Devices
  let campaignTouchStartX = 0;
  let campaignTouchEndX = 0;

  function initCampaigns() {
    const container = document.querySelector(".campaign-slides-container");
    if (container) {
      container.addEventListener("touchstart", (e) => {
        campaignTouchStartX = e.changedTouches[0].screenX;
      }, { passive: true });
      
      container.addEventListener("touchend", (e) => {
        campaignTouchEndX = e.changedTouches[0].screenX;
        handleCampaignSwipe();
      }, { passive: true });
    }
    
    // Initial display
    showCampaign(0);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCampaigns);
  } else {
    initCampaigns();
  }

  function handleCampaignSwipe() {
    if (campaignTouchEndX < campaignTouchStartX - 50) {
      nextCampaign();
    }
    if (campaignTouchEndX > campaignTouchStartX + 50) {
      prevCampaign();
    }
  }
</script>

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
        Every contribution directly reinforces our free community kitchen (Langar), flood relief operations, and educational materials. Connected directly to GiveWP, WooCommerce, and secure bank notification API.
      </p>

      <div class="budget-grid" style="align-items: flex-start; gap: 40px;">
        <!-- Left Column: Transactions List -->
        <div style="flex: 1.2; min-width: 280px; width: 100%;">
          <h4 style="color: var(--primary); margin-bottom: 20px; font-size: 1.25rem; display: flex; align-items: center; gap: 8px;">
            <span style="display: inline-block; width: 8px; height: 8px; background: #00bf75; border-radius: 50%; box-shadow: 0 0 10px #00bf75; animation: pulse 1.8s infinite;"></span>
            <span>📋 Live Contributions Board</span>
            <span style="font-size: 0.75rem; background: rgba(0, 135, 90, 0.15); color: #00bf75; padding: 3px 8px; border-radius: 12px; font-weight: bold;">Verified Logs</span>
          </h4>
          
          <div id="transactions-loading" style="color: var(--text-light); padding: 30px 0; text-align: center; font-size: 0.950rem;">
            ⏳ Querying connected plugins databases...
          </div>
          <div id="transactions-container" style="display: flex; flex-direction: column; gap: 14px; max-height: 520px; overflow-y: auto; padding-right: 8px;">
            <!-- Rendered dynamically -->
          </div>
        </div>

        <!-- Right Column: Plugin Gateway Sync monitor -->
        <div style="flex: 0.8; min-width: 280px; width: 100%; background: rgba(12,26,48,0.4); border: 1px solid rgba(212,175,55,0.2); padding: 30px; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.35); box-sizing: border-box;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 15px;">
            <h4 style="color: var(--primary); font-size: 1.15rem; margin: 0; display: flex; align-items: center; gap: 8px;">
              <span>🔌 Sync Gateway Hub</span>
            </h4>
            <span style="display: inline-flex; align-items: center; gap: 5px; background: rgba(0, 191, 117, 0.12); color: #00bf75; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
              <span style="width: 6px; height: 6px; background: #00bf75; border-radius: 50%; display: inline-block; box-shadow: 0 0 6px #00bf75;"></span>
              Live Sync Active
            </span>
          </div>
          
          <p style="color: var(--text-light); font-size: 0.825rem; margin-bottom: 22px; line-height: 1.45;">
            Our live ledger automatically collects, syncs, and displays donation details from active WordPress plugins. Minimal manual override needed.
          </p>

          <!-- Integration Row 1: GiveWP -->
          <div style="display: flex; gap: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 12px 14px; border-radius: 8px; margin-bottom: 12px;">
            <div style="background: rgba(212,175,55,0.08); color: var(--primary); font-size: 1.1rem; border-radius: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              🎁
            </div>
            <div>
              <div style="display: flex; align-items: center; gap: 6px;">
                <strong style="font-size: 0.85rem; color: #fff;">GiveWP Plugin</strong>
                <span style="font-size: 0.65rem; color: #00bf75; background: rgba(0,191,117,0.1); padding: 1px 6px; border-radius: 4px; font-weight: bold;">Connected ✓</span>
              </div>
              <p style="margin: 3px 0 0 0; font-size: 0.75rem; color: var(--text-light); line-height: 1.3;">
                Listens to online web submissions. Supports anonymous selection automatically.
              </p>
            </div>
          </div>

          <!-- Integration Row 2: WooCommerce Store -->
          <div style="display: flex; gap: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 12px 14px; border-radius: 8px; margin-bottom: 12px;">
            <div style="background: rgba(212,175,55,0.08); color: var(--primary); font-size: 1.1rem; border-radius: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              🛒
            </div>
            <div>
              <div style="display: flex; align-items: center; gap: 6px;">
                <strong style="font-size: 0.85rem; color: #fff;">WooCommerce Store</strong>
                <span style="font-size: 0.65rem; color: #00bf75; background: rgba(0,191,117,0.1); padding: 1px 6px; border-radius: 4px; font-weight: bold;">Connected ✓</span>
              </div>
              <p style="margin: 3px 0 0 0; font-size: 0.75rem; color: var(--text-light); line-height: 1.3;">
                Captures purchases designated for Langar services and Flood Relief.
              </p>
            </div>
          </div>

          <!-- Integration Row 3: Bank Direct / UPI API -->
          <div style="display: flex; gap: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 12px 14px; border-radius: 8px; margin-bottom: 22px;">
            <div style="background: rgba(212,175,55,0.08); color: var(--primary); font-size: 1.1rem; border-radius: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              📱
            </div>
            <div>
              <div style="display: flex; align-items: center; gap: 6px;">
                <strong style="font-size: 0.85rem; color: #fff;">Direct UPI & Bank API</strong>
                <span style="font-size: 0.65rem; color: var(--primary); background: rgba(212,175,55,0.1); padding: 1px 6px; border-radius: 4px; font-weight: bold;">Secure Sync</span>
              </div>
              <p style="margin: 3px 0 0 0; font-size: 0.75rem; color: var(--text-light); line-height: 1.3;">
                Auto-matches transaction notifications received from payments & QR scans.
              </p>
            </div>
          </div>

          <!-- Privacy Shield details -->
          <div style="background: rgba(212,175,55,0.05); border: 1px dashed rgba(212,175,55,0.25); border-radius: 8px; padding: 15px; margin-bottom: 24px; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 6px; color: var(--primary); margin-bottom: 6px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">
              <span>🛡️ Automatic Preference Control</span>
            </div>
            <p style="margin: 0; color: var(--text-light); font-size: 0.75rem; line-height: 1.45;">
              If a contributor ticks <strong>"Donate Anonymously"</strong> in GiveWP or WooCommerce checkouts, our sync parser respect this instantly, masking their profile and displaying them as **Anonymous Sevadar**.
            </p>
          </div>

          <!-- Interactive Webhook Simulator Button -->
          <button id="webhookSimulateBtn" style="width: 100%; padding: 14px; background: var(--primary); color: var(--bg-dark); border: none; border-radius: 6px; font-weight: bold; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 15px rgba(212,175,55,0.15); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <span>⚡ Test Plugin Sync Flow</span>
          </button>
          
          <div id="simStatus" style="margin-top: 12px; font-size: 0.8rem; text-align: center; color: var(--text-light); min-height: 18px;">
            Ready to test live synchronization.
          </div>
        </div>
      </div>
    </div>

    <!-- Live Ledger Scripts -->
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const ajaxUrl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
        
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
                container.innerHTML = `<div style="color: var(--text-light); padding: 30px; text-align: center;">No transactions synchronized yet. Run simulation or activate plugins!</div>`;
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
                card.style.transition = "all 0.3s ease-in-out";
                
                const verifiedTag = tx.verified == 1 
                  ? `<span style="font-size: 0.725rem; font-weight: bold; background: rgba(0, 191, 117, 0.12); color: #00bf75; padding: 2px 7px; border-radius: 10px; margin-left: 8px; display: inline-flex; align-items: center; gap: 4px;">Synced Verified ✓</span>`
                  : `<span style="font-size: 0.725rem; font-weight: bold; background: rgba(212, 175, 55, 0.1); color: var(--primary); padding: 2px 7px; border-radius: 10px; margin-left: 8px; display: inline-flex; align-items: center; gap: 4px;">Parsing ⏳</span>`;

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

        // Handle simulation button trigger
        const simBtn = document.getElementById("webhookSimulateBtn");
        const simStatus = document.getElementById("simStatus");
        
        if (simBtn) {
          simBtn.addEventListener("click", async () => {
            simBtn.disabled = true;
            simBtn.style.opacity = "0.7";
            simStatus.textContent = "Firing Simulated GiveWP / WooCommerce sync webhook event...";
            simStatus.style.color = "var(--primary)";
            
            try {
              const response = await fetch(ajaxUrl, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "action=simulate_donation"
              });
              const result = await response.json();
              if (result.success) {
                simStatus.textContent = "Webhook Success! Animated transaction prepended to board.";
                simStatus.style.color = "#00bf75";
                
                // Play simple retro pulse/bell audio synthetically if browser supports AudioContext
                try {
                  const AudioContext = window.AudioContext || window.webkitAudioContext;
                  if (AudioContext) {
                    const ctx = new AudioContext();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = "sine";
                    osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5 Note
                    osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5 Note
                    gain.gain.setValueAtTime(0.12, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.4);
                  }
                } catch(audioErr) {}

                // Reload the ledger list immediately
                await loadTransactions();
              } else {
                simStatus.textContent = "Gateway error: webhooks could not synchronize.";
                simStatus.style.color = "var(--accent-red)";
              }
            } catch (err) {
              console.error("Sim transaction error:", err);
              simStatus.style.color = "var(--accent-red)";
              simStatus.textContent = "Network sync failed.";
            } finally {
              setTimeout(() => {
                simBtn.disabled = false;
                simBtn.style.opacity = "1";
              }, 1200);
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
