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
        src="<?php echo esc_url( get_template_directory_uri() . '/Logo.png' ); ?>"
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
