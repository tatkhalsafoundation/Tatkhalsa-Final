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
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: inline-flex; justify-content: center; align-items: center;">
        <img
          src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>"
          alt="Tatkhalsa Foundation Logo"
          class="hero-gurbani-logo"
          width="240"
          height="240"
          fetchpriority="high"
          style="width: 240px; height: 240px; object-fit: contain;"
        />
      </a>
    </div>

    <!-- Gurbani Text -->
    <div class="gurbani-text">
      ਏਕਹੀ ਕੀ ਸੇਵ ਸਭ ਹੀ ਕੋ ਗੁਰਦੇਵ ਏਕ ॥<br />
      ਏਕਹੀ ਸਰੂਪ ਸਭੈ ਏਕੈ ਜੋਤਿ ਜਾਨਬੋ ॥
    </div>

    <h1>Serving Humanity Through Seva, Compassion, and Community Action</h1>
    <p>Tatkhalsa Foundation is a registered non-profit organization dedicated to humanitarian relief, healthcare support, youth development, environmental initiatives, and preservation of Sikh heritage across Punjab and beyond.</p>

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
      <button class="btn" style="background: var(--accent-red); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; height: 50px; padding: 0 30px; box-sizing: border-box; border: 2px solid transparent; font-weight: bold;" onclick="openModal()">
        Contribute Now
      </button>
    </div>
  </div>
</section>

<!-- Blood Donation Call to Action Banner -->
<section class="blood-cta-banner" style="background: linear-gradient(135deg, rgba(255,51,75,0.05) 0%, rgba(220,20,60,0.1) 100%); padding: 60px 20px; border-top: 1px solid rgba(255,51,75,0.1); border-bottom: 1px solid rgba(255,51,75,0.1); transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); cursor: pointer;" onmouseover="this.style.transform='scale(1.015)'; this.style.boxShadow='0 15px 40px rgba(255,51,75,0.15)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
  <div class="blood-cta-container scroll-reveal" style="max-width: 900px; margin: 0 auto; text-align: center;">
    <div style="display: inline-block; padding: 6px 14px; background: rgba(255,51,75,0.1); color: #ff334b; font-weight: bold; border-radius: 20px; font-size: 0.85rem; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">
      <span style="display: inline-block; width: 8px; height: 8px; background: #ff334b; border-radius: 50%; margin-right: 6px; box-shadow: 0 0 8px #ff334b;"></span>
      Save Lives Today
    </div>
    <h2 style="font-size: 2.5rem; color: var(--text-dark); margin-bottom: 20px; font-weight: 800; font-family: var(--font-heading); line-height: 1.2;">
      Take the Pledge.<br>Join Blood On Call.
    </h2>
    <p style="font-size: 1.1rem; color: var(--text-light); margin-bottom: 30px; line-height: 1.6; max-width: 700px; margin-left: auto; margin-right: auto;">
      We call upon the youth and every compassionate soul to step forward. A single act of contribution can give a family their loved one back. Register your name today to become someone's emergency hero.
    </p>
    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
      <a href="<?php echo esc_url( home_url( '/blood-donors/' ) ); ?>" style="display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--secondary) 0%, #ffdf79 100%); color: #000; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(212, 175, 55, 0.5)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 15px rgba(212, 175, 55, 0.4)';">
        🩸 Register as a Donor
      </a>
      <button class="btn-outline" style="display: inline-flex; align-items: center; justify-content: center; padding: 14px 32px; border-radius: 8px; font-weight: bold; font-size: 1.1rem; border: 2px solid #ff334b; color: #ff334b; background: rgba(255, 51, 75, 0.05); cursor: pointer;" onclick="openBloodRequestModal()" onmouseover="this.style.background='rgba(255, 51, 75, 0.1)';" onmouseout="this.style.background='rgba(255, 51, 75, 0.05)';">
        Request Blood 🚨
      </button>
    </div>
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

    <style>
      /* Fallback CSS injected to ensure the campaign carousel works securely on all standard WP setups */
      @media (max-width: 768px) {
        .campaign-slide {
          opacity: 0 !important;
          visibility: hidden !important;
          pointer-events: none !important;
          position: absolute !important;
          transform: scale(0.95) !important;
          display: block !important;
          transition: opacity 0.4s ease, visibility 0.4s ease, transform 0.4s ease !important;
        }
        .campaign-slide.active {
          opacity: 1 !important;
          visibility: visible !important;
          pointer-events: auto !important;
          position: relative !important;
          transform: scale(1) translateY(0) !important;
          display: block !important;
        }
        .campaign-slide.next-preview,
        .campaign-slide.prev-preview {
          display: none !important;
          opacity: 0 !important;
          visibility: hidden !important;
        }
        .campaign-slides-container {
          height: auto !important;
          min-height: 480px !important;
          overflow: hidden !important;
        }
        .campaign-slider-wrapper {
          overflow: hidden !important;
        }
      }
    </style>

    <!-- Interactive Carousel Wrapper -->
    <div class="campaign-slider-wrapper" style="position: relative; max-width: 440px; margin: 0 auto; overflow: visible;">
      <div class="campaign-slides-container">
        
        <!-- Slide 1: Cancer Patient Nimrat Kaur -->
        <div class="campaign-slide active" data-index="0" data-title="Nimrat Kaur (2 yrs, Cancer)" onclick="handleSlideClick(0, event)">
          <div class="campaign-card">
            <span class="campaign-view-tag" onclick="openNimratKaurModal(); event.stopPropagation(); return false;" style="cursor: pointer;">View Campaign</span>
            <img class="campaign-img" loading="lazy" decoding="async" src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_nimrat_kaur_img', get_stylesheet_directory_uri() . '/assets/images/regenerated_image_1781128512768.jpg' ) ); ?>" alt="Pediatric Cancer Care Support - Nimrat Kaur" />
            <div class="campaign-overlay">
              <span class="campaign-category" style="color: var(--accent-red);">Healthcare Aid</span>
              <h3 class="campaign-title">Cancer Patient Nimrat Kaur</h3>
              <p class="campaign-desc">2-year-old Gursikh child battling blood cancer. Urgent medical treatment support needed to save her life and cover expensive oncology sessions.</p>
              
              <!-- Progress Tracker -->
              <div class="campaign-progress-wrapper">
                <div class="campaign-progress-stats">
                  <span>Raised: ₹2,25,000</span>
                  <span>Goal: ₹7,00,000</span>
                </div>
                <div class="campaign-progress-bar">
                  <div class="campaign-progress-fill" style="width: 32.1%; background: var(--accent-red);"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2: Punjab Flood Relief -->
        <div class="campaign-slide" data-index="1" data-title="Punjab Flood Relief 2025" onclick="handleSlideClick(1, event)">
          <div class="campaign-card">
            <span class="campaign-view-tag" onclick="openPunjabFloodReliefModal(); event.stopPropagation(); return false;" style="cursor: pointer;">View Campaign</span>
            <img class="campaign-img" loading="lazy" decoding="async" src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_punjab_relief_img', 'https://images.unsplash.com/photo-1514222134-b57cbb8ce073?auto=format&fit=crop&w=800&q=80' ) ); ?>" alt="Punjab Relief - Sri Harmandir Sahib" />
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
        <div class="campaign-slide" data-index="2" data-title="Grocery Help Seva" onclick="handleSlideClick(2, event)">
          <div class="campaign-card">
            <span class="campaign-view-tag" onclick="openGroceryHelpSevaModal(); event.stopPropagation(); return false;" style="cursor: pointer;">View Campaign</span>
            <img class="campaign-img" loading="lazy" decoding="async" src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_grocery_help_img', 'https://images.unsplash.com/photo-1609137144813-1d67493fa7b2?auto=format&fit=crop&w=800&q=80' ) ); ?>" alt="Grocery Help Seva - Sikh Sevadar Elder Support" />
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
        <div class="campaign-slide" data-index="3" data-title="1984 Victims Family Support" onclick="handleSlideClick(3, event)">
          <div class="campaign-card">
            <span class="campaign-view-tag" onclick="openVictimFamilySupportModal(); event.stopPropagation(); return false;" style="cursor: pointer;">View Campaign</span>
            <img class="campaign-img" loading="lazy" decoding="async" src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_1984_victim_img', 'https://images.unsplash.com/photo-1605701243007-df5b128caff8?auto=format&fit=crop&w=800&q=80' ) ); ?>" alt="1984 Victim Families Support - Sikh Elder Portrait" />
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
        <div class="campaign-slide" data-index="4" data-title="Gursikh Daughters Marriages Seva" onclick="handleSlideClick(4, event)">
          <div class="campaign-card">
            <span class="campaign-view-tag" onclick="openMarriagesSevaModal(); event.stopPropagation(); return false;" style="cursor: pointer;">View Campaign</span>
            <img class="campaign-img" loading="lazy" decoding="async" src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_marriages_seva_img', 'https://images.unsplash.com/photo-1610030469668-93535c17b6b3?auto=format&fit=crop&w=800&q=80' ) ); ?>" alt="Underprivileged Marriages support - Traditional Bridal Hands" />
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
      <a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>" class="btn" style="background-color: var(--secondary); color: var(--body-bg); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1.5px; padding: 14px 34px; box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2); transition: all 0.3s; font-weight: 700; border-radius: 50px; text-decoration: none;">
        View All Campaigns
      </a>
    </div>

  </div>
</section>

<!-- Slider JavaScript and Modal Integration -->
<script>
  let currentCampaignIndex = 0;
  const totalCampaigns = 5;

  function handleSlideClick(index, event) {
    const slide = event.currentTarget;
    if (slide.classList.contains("next-preview")) {
      nextCampaign(event);
    } else if (slide.classList.contains("prev-preview")) {
      prevCampaign(event);
    } else if (slide.classList.contains("active")) {
      const viewTag = slide.querySelector('.campaign-view-tag');
      if (viewTag) {
        viewTag.click();
      }
    }
  }

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
    <div class="ledger-box" style="margin-top: 50px; border-top: 1px solid rgba(212, 175, 55, 0.15); padding-top: 40px;">
      <h3 style="color: var(--text-dark); margin-bottom: 20px; font-family: var(--font-sans); text-align: center; font-size: 1.6rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
        <span>⚜️</span> Seva Ledger & Gateway Hub
      </h3>
      <p style="color: var(--text-light); text-align: center; max-width: 650px; margin: 0 auto 30px auto; font-size: 0.9rem; line-height: 1.5;">
        Our live ledger automatically collects, syncs, and displays donation details from active WordPress plugins. Minimal manual override needed.
      </p>

      <!-- Unified Compact Dashboard Box -->
      <div style="max-width: 720px; margin: 0 auto; background: rgba(12, 26, 48, 0.45); border: 1.5px solid rgba(212,175,55,0.22); border-radius: 14px; padding: 22px; box-shadow: 0 15px 45px rgba(0,0,0,0.35); box-sizing: border-box; backdrop-filter: blur(10px);">
        
        <!-- Top Bar: Connection Gateway Sync Monitors -->
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 14px; margin-bottom: 18px;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 1.15rem; display: inline-block;">🔌</span>
            <strong style="color: var(--primary); font-family: var(--font-sans); font-size: 1.05rem;">Sync Gateway Active</strong>
          </div>
          
          <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <span style="font-size: 0.7rem; color: #a4b3cd; display: flex; align-items: center; gap: 4px; background: rgba(0, 191, 117, 0.08); border: 1px solid rgba(0, 191, 117, 0.2); padding: 3px 8px; border-radius: 6px;">
              <span style="color: #00bf75;">●</span> GiveWP ✓
            </span>
            <span style="font-size: 0.7rem; color: #a4b3cd; display: flex; align-items: center; gap: 4px; background: rgba(0, 191, 117, 0.08); border: 1px solid rgba(0, 191, 117, 0.2); padding: 3px 8px; border-radius: 6px;">
              <span style="color: #00bf75;">●</span> WooCommerce ✓
            </span>
            <span style="font-size: 0.7rem; color: #a4b3cd; display: flex; align-items: center; gap: 4px; background: rgba(212, 175, 55, 0.06); border: 1px solid rgba(212, 175, 55, 0.15); padding: 3px 8px; border-radius: 6px;">
              <span style="color: var(--primary);">●</span> UPI Pay ✓
            </span>
          </div>
        </div>

        <!-- Middle Body: Double Integrated Feed (Only views 1 log at a time; rotates gracefully) -->
        <div style="position: relative; overflow: hidden; min-height: 105px; display: flex; align-items: center; justify-content: center; background: rgba(4, 9, 20, 0.4); border: 1px solid rgba(255,255,255,0.03); border-radius: 10px; padding: 14px 18px;" id="ticker-viewport">
          
          <div id="transactions-loading" style="color: var(--text-light); text-align: center; font-size: 0.850rem; width: 100%;">
            ⏳ Querying connected plugins databases...
          </div>

          <div id="transactions-ticker-container" style="width: 100%; transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out; opacity: 1;">
            <!-- Filled dynamically with single transaction card -->
          </div>
        </div>

        <!-- Privacy Badge Shield & Interactive Webhook Simulator -->
        <div style="margin-top: 18px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 16px;">
          <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 15px;">
            
            <!-- Unified controls -->
            <div style="display: flex; align-items: center; gap: 12px; font-size: 0.75rem; color: var(--text-light);">
              <div style="display: flex; align-items: center; gap: 5px;">
                <span style="display: inline-block; width: 6px; height: 6px; background: #00bf75; border-radius: 50%; box-shadow: 0 0 6px #00bf75; animation: pulse 1.8s infinite;"></span>
                <span>Verified Seva ledger feeds</span>
              </div>
              <div style="display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.03); border-radius: 20px; padding: 2px 10px;">
                <button id="prev-tx-btn" style="background: none; border: none; color: var(--primary); cursor: pointer; font-size: 0.85rem; padding: 1px 4px; line-height: 1;" title="Previous Log">◀</button>
                <span id="ticker-counter" style="font-family: var(--font-mono); color: #fff; font-size: 0.72rem; min-width: 44px; text-align: center; display: inline-block;">0 / 0</span>
                <button id="next-tx-btn" style="background: none; border: none; color: var(--primary); cursor: pointer; font-size: 0.85rem; padding: 1px 4px; line-height: 1;" title="Next Log">▶</button>
              </div>
            </div>

            <!-- Compact simulator button & status -->
            <div style="display: flex; align-items: center; gap: 10px;">
              <button id="webhookSimulateBtn" style="padding: 8px 14px; background: rgba(212,175,55,0.12); border: 1.5px solid rgba(212,175,55,0.3); border-radius: 25px; color: var(--primary); font-weight: bold; font-size: 0.75rem; cursor: pointer; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.5px;">
                ⚡ Test Sync Plugin
              </button>
            </div>
          </div>

          <div id="simStatus" style="margin-top: 8px; font-size: 0.72rem; text-align: right; color: var(--text-light); min-height: 12px;">
            Ready to test synchronized ledger injection.
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

        let allTransactions = [];
        let curTransactionIndex = 0;
        let tickerIntervalId = null;

        // Fetch and Render Transactions list
        async function loadTransactions() {
          const container = document.getElementById("transactions-ticker-container");
          const loadingEl = document.getElementById("transactions-loading");
          const counterEl = document.getElementById("ticker-counter");
          
          try {
            const response = await fetch(`${ajaxUrl}?action=get_transactions`);
            const data = await response.json();
            
            if (data.success && data.data.transactions) {
              if (loadingEl) loadingEl.style.display = "none";
              
              allTransactions = data.data.transactions;
              
              if (allTransactions.length === 0) {
                if (container) {
                  container.style.display = "block";
                  container.innerHTML = `<div style="color: var(--text-light); padding: 15px; text-align: center; font-size: 0.85rem;">No logs synchronized. Click "Test Sync" below!</div>`;
                }
                if (counterEl) counterEl.textContent = "0 / 0";
                return;
              }
              
              // Sort by date descending (latest first) to make sure we show new ones automatically
              allTransactions.sort((a,b) => new Date(b.date) - new Date(a.date));
              
              // Initialize view at index 0 (latest)
              curTransactionIndex = 0;
              renderActiveTransaction();
              
              // Setup automatic rotation every 4500ms
              startTickerTimer();
            } else {
              if (loadingEl) loadingEl.textContent = "Unable to retrieve database. Please refresh.";
            }
          } catch (err) {
            console.error("Ledger fetch error:", err);
            if (loadingEl) loadingEl.textContent = "Network sync timeout. Refreshing soon...";
          }
        }

        function renderActiveTransaction() {
          const container = document.getElementById("transactions-ticker-container");
          const counterEl = document.getElementById("ticker-counter");
          if (!container || allTransactions.length === 0) return;

          const tx = allTransactions[curTransactionIndex];
          
          const verifiedTag = tx.verified == 1 
            ? `<span style="font-size: 0.68rem; font-weight: bold; background: rgba(0, 191, 117, 0.12); color: #00bf75; padding: 2px 7px; border-radius: 8px; border: 1.2px solid rgba(0, 191, 117, 0.2)">Synced Verified ✓</span>`
            : `<span style="font-size: 0.68rem; font-weight: bold; background: rgba(212, 175, 55, 0.1); color: var(--primary); padding: 2px 7px; border-radius: 8px; border: 1.2px solid rgba(212,175,55,0.2)">Parsing ⏳</span>`;

          const contributorName = tx.anonymous == 1 ? "Anonymous Sevadar" : tx.name;
          const noteElement = tx.note 
            ? `<p style="margin: 4px 0 0 0; font-size: 0.78rem; color: #a4b3cd; font-style: italic; line-height: 1.3;">"${escapeHtml(tx.note)}"</p>` 
            : '';

          // Transition animation via fade out/in
          container.style.opacity = "0";
          container.style.transform = "translateY(5px)";
          
          setTimeout(() => {
            container.style.display = "block";
            container.innerHTML = `
              <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px; width: 100%;">
                <div style="display: flex; align-items: center; gap: 12px; text-align: left;">
                  <div style="background: rgba(212,175,55,0.06); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); border: 1.2px solid rgba(212,175,55,0.18); flex-shrink: 0; font-size: 1rem;">
                    ⚜️
                  </div>
                  <div>
                    <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 6px;">
                      <strong style="color: #fff; font-size: 0.92rem; font-weight: 700;">${escapeHtml(contributorName)}</strong>
                      ${verifiedTag}
                    </div>
                    <span style="font-size: 0.72rem; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: 2px;">${escapeHtml(tx.seva_type)}</span>
                    ${noteElement}
                  </div>
                </div>
                <div style="text-align: right; flex-shrink: 0;">
                  <span style="font-size: 1.1rem; font-weight: 800; color: var(--accent-green); display: block;">₹${parseFloat(tx.amount).toLocaleString('en-IN')}</span>
                  <span style="font-size: 0.7rem; color: var(--text-light); display: block; margin-top: 1px;">${formatDateString(tx.date)}</span>
                </div>
              </div>
            `;
            
            // Fade in
            container.style.opacity = "1";
            container.style.transform = "translateY(0)";
          }, 180);

          if (counterEl) {
            counterEl.textContent = `${curTransactionIndex + 1} / ${allTransactions.length}`;
          }
        }

        function startTickerTimer() {
          if (tickerIntervalId) clearInterval(tickerIntervalId);
          tickerIntervalId = setInterval(() => {
            if (allTransactions.length <= 1) return;
            curTransactionIndex = (curTransactionIndex + 1) % allTransactions.length;
            renderActiveTransaction();
          }, 4500);
        }

        // Previous and Next button listeners
        const prevBtn = document.getElementById("prev-tx-btn");
        const nextBtn = document.getElementById("next-tx-btn");
        if (prevBtn) {
          prevBtn.addEventListener("click", () => {
            if (allTransactions.length <= 1) return;
            curTransactionIndex = (curTransactionIndex - 1 + allTransactions.length) % allTransactions.length;
            renderActiveTransaction();
            startTickerTimer(); // Reset timer on manual click
          });
        }
        if (nextBtn) {
          nextBtn.addEventListener("click", () => {
            if (allTransactions.length <= 1) return;
            curTransactionIndex = (curTransactionIndex + 1) % allTransactions.length;
            renderActiveTransaction();
            startTickerTimer(); // Reset timer on manual click
          });
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
        <p>Blood On Call</p>
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
