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
    <h1>Serving Humanity Through Seva, Compassion, and Community Action</h1>
    <p>
      Tatkhalsa Foundation is a registered non-profit organization dedicated
      to humanitarian relief, healthcare support, youth development,
      environmental initiatives, and preservation of Sikh heritage across
      Punjab and beyond.
    </p>

    <div class="trust-badges">
      <span class="badge" style="background: rgba(209, 61, 82, 0.2); border: 1px solid rgba(209, 61, 82, 0.5);">
        ✓ Registered NGO
      </span>
      <span class="badge" style="background: rgba(56, 142, 99, 0.2); border: 1px solid rgba(56, 142, 99, 0.5);">
        ✓ 12A Registered
      </span>
      <span class="badge" style="background: rgba(50, 133, 199, 0.2); border: 1px solid rgba(50, 133, 199, 0.5);">
        ✓ 80G Approved
      </span>
      <span class="badge" style="background: rgba(144, 85, 188, 0.2); border: 1px solid rgba(144, 85, 188, 0.5);">
        ✓ CSR Eligible
      </span>
    </div>

    <div class="hero-buttons">
      <button class="btn" style="background: var(--accent-red); color: var(--primary);" onclick="openModal()">
        Support Our Seva
      </button>
      <a href="#volunteer" class="btn-outline">Become a Volunteer</a>
    </div>
  </div>
</section>

<!-- Trust Cards Compliance Badges Section -->
<section class="trust-section">
  <div class="container scroll-reveal">
    <div class="cards-grid">
      <!-- Card 1: Gold Theme -->
      <div class="trust-card">
        <h3>Registered NGO</h3>
        <p>CIN: U88900PB2023NPL059225. Officially incorporated with deep devotion towards transparent, audited community-focused Seva.</p>
      </div>
      <!-- Card 2: Green Theme -->
      <div class="trust-card">
        <h3>12A Registered</h3>
        <p>Income Tax Department tax-exemption approval, validating the fully charitable nature of all our activities and accounts.</p>
      </div>
      <!-- Card 3: Blue Theme -->
      <div class="trust-card">
        <h3>80G Approved</h3>
        <p>Tax-exempt contributions under Section 80G, allowing our esteemed donors in India to claim tax deductions on support.</p>
      </div>
      <!-- Card 4: Red Theme -->
      <div class="trust-card">
        <h3>CSR Eligible</h3>
        <p>Fully compliant with corporate social responsibility guidelines, enabling corporates to execute CSR programs in Punjab.</p>
      </div>
    </div>
  </div>
</section>

<!-- About Us Section -->
<section id="about" style="background-color: var(--bg-shade-3); position: relative;">
  <div class="container scroll-reveal">
    <div class="about-grid">
      <div class="about-image" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/Logo.jpg' ); ?>'); background-size: cover; background-position: center;"></div>
      <div>
        <h2 style="font-size: 2.5rem; margin-bottom: 20px;">
          About Tatkhalsa Foundation
        </h2>
        <p class="about-text" style="text-align: left;">
          Tatkhalsa Foundation is a registered non-profit organization
          committed to serving society through humanitarian aid, healthcare
          initiatives, environmental awareness, youth empowerment, disaster
          relief, and preservation of Sikh heritage.<br /><br />
          Guided by the principles of Seva, equality, and compassion, the
          foundation works to create a sustainable positive impact across
          communities. We believe in taking action where it matters most.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Hot Update Callout Section: Punjab Flood Relief -->
<section style="background-color: var(--accent-red); color: white; padding: 40px 0; text-align: center; position: relative; z-index: 2;">
  <div class="container scroll-reveal">
    <h2 style="font-size: 2.2rem; margin-bottom: 15px; font-family: var(--font-serif);">
      Punjab Flood Relief 2025
    </h2>
    <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto 20px auto; color: rgba(255, 255, 255, 0.9);">
      Emergency response initiated. Our volunteer teams are actively
      providing medical aid, Langar, and rescuing stranded families.
    </p>
    <a href="<?php echo esc_url( home_url( '/punjab-flood-relief/' ) ); ?>" class="btn" style="background-color: white; color: var(--accent-red); font-weight: bold; border: none; padding: 12px 30px; border-radius: 50px;">
      View Relief Efforts & Contribute
    </a>
  </div>
</section>

<!-- What We Do Services Section -->
<section id="what-we-do" style="background-color: var(--bg-shade-4); position: relative; z-index: 2;">
  <div class="container scroll-reveal">
    <h2 class="section-title">What We Do</h2>
    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon">🤝</div>
        <h3>General Charity & Relief</h3>
        <p>
          Heatwave relief, distribution of essentials, and support for
          marginalized communities in times of need.
        </p>
      </div>
      <div class="service-card">
        <div class="service-icon">❤️</div>
        <h3>Healthcare Support</h3>
        <p>
          Cancer charity support, organizing regular blood contribution
          drives, and medical assistance.
        </p>
      </div>
      <div class="service-card">
        <div class="service-icon">🏆</div>
        <h3>Youth & Sports</h3>
        <p>
          Organizing sports championships and youth development programs to
          foster physical and mental well-being.
        </p>
      </div>
      <div class="service-card">
        <div class="service-icon">🏛️</div>
        <h3>Sikh History & Heritage</h3>
        <p>
          Review board and initiatives dedicated to the preservation and
          accurate representation of Sikh history.
        </p>
      </div>
      <div class="service-card">
        <div class="service-icon">🚨</div>
        <h3>Disaster & Emergency</h3>
        <p>
          Rapid response teams providing critical aid and support during
          natural disasters and emergencies.
        </p>
      </div>
      <div class="service-card">
        <div class="service-icon">🌱</div>
        <h3>Community Welfare</h3>
        <p>
          Sustainable development projects and environmental initiatives
          focused on long-term positive impact.
        </p>
      </div>
    </div>
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

<!-- Our Impact Gallery Section -->
<section id="gallery" style="background-color: var(--bg-shade-4)">
  <div class="container scroll-reveal">
    <h2 class="section-title">Our Impact Gallery</h2>
    <div class="gallery-grid">
      <div class="gallery-item">
        <img
          src="https://upload.wikimedia.org/wikipedia/commons/e/ee/Group_of_Nihang_Singhs.jpg"
          alt="Group of Nihang Singhs"
        />
        <div class="gallery-overlay">
          <span class="gallery-text">Preserving Sikh Heritage</span>
        </div>
      </div>

      <div class="gallery-item">
        <img
          src="https://upload.wikimedia.org/wikipedia/commons/d/de/Sikhs_gathered_at_Hola_Mohalla_Holi_festival_in_Anandpur_Sahib.jpg"
          alt="Sikhs at Hola Mohalla"
        />
        <div class="gallery-overlay">
          <span class="gallery-text">Hola Mohalla</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Volunteer Section -->
<section id="volunteer" style="background-color: var(--bg-shade-3)">
  <div class="container scroll-reveal">
    <h2 class="section-title">Get Involved</h2>
    <div class="forms-grid" style="grid-template-columns: 1fr; max-width: 700px; margin: 0 auto">
      <div class="form-container">
        <h3 style="color: var(--primary)">Join Our Volunteer Network</h3>
        <p style="margin-bottom: 20px; color: var(--text-light)">
          Become part of a dedicated team committed to serving communities
          through blood contribution drives, relief missions, and
          humanitarian projects.
        </p>
        <form id="volunteerForm">
          <div class="form-group">
            <label>Name</label>
            <input type="text" id="vName" required />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" id="vEmail" required />
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="tel" id="vPhone" required />
          </div>
          <div class="form-group">
            <label>Message / Skills</label>
            <textarea
              id="vMessage"
              rows="4"
              required
              placeholder="How would you like to help?"
            ></textarea>
          </div>
          <button type="submit" class="btn" style="width: 100%">
            Become A Volunteer
          </button>
          <p id="vStatus" style="margin-top: 15px; font-weight: bold"></p>
        </form>
      </div>
    </div>
  </div>
</section>

<?php
get_footer();
?>
