<?php
/**
 * Template Name: Projects Page
 *
 * @package TatkhalsaTheme
 */

get_header();

// Initialize standard WordPress page post object for proper SEO metadata and Yoast support
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
    }
    rewind_posts();
}
?>

<style>
  /* Page specific styles to isolate and perfectly craft the redesigned Projects view */
  .projects-page {
    background-color: var(--bg-shade-2);
    font-family: var(--font-sans);
    color: var(--primary);
    position: relative;
    padding-bottom: 60px;
  }

  /* Premium Spotlight Component */
  .spotlight-section {
    padding: 80px 0 40px 0;
    position: relative;
    z-index: 2;
  }
  .spotlight-card {
    background: linear-gradient(135deg, var(--bg-light) 0%, rgba(12, 25, 51, 0.7) 100%);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 16px;
    padding: 40px;
    position: relative;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 40px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    transition: border-color 0.4s ease, box-shadow 0.4s ease;
  }
  .spotlight-card:hover {
    border-color: rgba(212, 175, 55, 0.5);
    box-shadow: 0 20px 50px rgba(212, 175, 55, 0.08);
  }
  .spotlight-label {
    background: rgba(209, 61, 82, 0.15);
    border: 1px solid rgba(209, 61, 82, 0.4);
    color: var(--accent-red);
    font-family: var(--font-mono);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 20px;
    letter-spacing: 0.05em;
  }
  .sos-pulse {
    width: 6px;
    height: 6px;
    background-color: var(--accent-red);
    border-radius: 50%;
    animation: sosPulse 1.5s infinite ease-in-out;
  }
  @keyframes sosPulse {
    0% { transform: scale(0.9); opacity: 1; }
    50% { transform: scale(1.4); opacity: 0.4; }
    100% { transform: scale(0.9); opacity: 1; }
  }
  .spotlight-title {
    font-family: var(--font-serif);
    font-size: 2.2rem;
    color: var(--cream);
    margin-bottom: 15px;
    line-height: 1.2;
  }
  .spotlight-desc {
    color: var(--text-light);
    font-size: 1.05rem;
    line-height: 1.7;
    margin-bottom: 25px;
  }
  .spotlight-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 25px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 20px 0;
  }
  .spotlight-stat-item h4 {
    font-family: var(--font-mono);
    font-size: 1.6rem;
    color: var(--secondary);
    margin-bottom: 5px;
    font-weight: 700;
  }
  .spotlight-stat-item p {
    font-size: 0.8rem;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
  }
  .spotlight-progress-lbl {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: var(--text-light);
    margin-bottom: 8px;
  }
  .spotlight-progress-track {
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 30px;
  }
  .spotlight-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--accent-red) 0%, var(--secondary) 100%);
    border-radius: 10px;
    width: 78%;
  }

  /* Interactive Category Filtering Section */
  .filter-section {
    padding: 40px 0 20px 0;
    z-index: 3;
    position: relative;
  }
  .filter-tabs {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 40px;
  }
  .filter-btn {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(253, 247, 231, 0.15);
    color: var(--text-light);
    padding: 10px 22px;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  }
  .filter-btn:hover {
    color: var(--cream);
    border-color: var(--secondary);
    background: rgba(212, 175, 55, 0.05);
    transform: translateY(-1px);
  }
  .filter-btn.active {
    background: var(--secondary);
    border-color: var(--secondary);
    color: var(--bg-dark);
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.25);
  }

  /* Premium Bento Grid Structure */
  .bento-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
  }
  .bento-card {
    background: var(--bg-light);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-bottom: 4px solid var(--secondary);
    border-radius: 16px;
    padding: 35px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    z-index: 1;
  }
  .bento-card.double-col {
    grid-column: span 2;
  }
  .bento-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.04) 0%, transparent 100%);
    z-index: -1;
    opacity: 0;
    transition: opacity 0.4s ease;
  }
  .bento-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    border-color: rgba(212, 175, 55, 0.3);
  }
  .bento-card:hover::before {
    opacity: 1;
  }

  .bento-card-image-wrap {
    height: 180px;
    margin-bottom: 25px;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.05);
  }
  .bento-card-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .bento-card:hover .bento-card-image-wrap img {
    transform: scale(1.06);
  }

  /* Card content details */
  .card-category-lbl {
    font-family: var(--font-mono);
    font-size: 0.725rem;
    text-transform: uppercase;
    color: var(--secondary);
    letter-spacing: 0.05em;
    margin-bottom: 15px;
    display: block;
  }
  .card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
  }
  .card-icon-sphere {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    transition: transform 0.3s ease;
  }
  .bento-card:hover .card-icon-sphere {
    transform: scale(1.1) rotate(6deg);
  }
  .card-badge {
    background: rgba(253, 247, 231, 0.06);
    border: 1px solid rgba(253, 247, 231, 0.12);
    color: var(--text-light);
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 50px;
    font-weight: 500;
  }
  .card-title {
    font-family: var(--font-sans);
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--cream);
    margin-bottom: 12px;
  }
  .card-desc {
    color: var(--text-light);
    font-size: 0.925rem;
    line-height: 1.6;
    margin-bottom: 25px;
  }
  
  /* Details & quick metrics on bento cards */
  .card-metrics-row {
    display: flex;
    gap: 20px;
    border-top: 1px solid rgba(255,255,255,0.05);
    padding-top: 15px;
    margin-bottom: 25px;
  }
  .card-metric-col h5 {
    font-family: var(--font-mono);
    font-size: 1.05rem;
    color: var(--secondary);
    margin-bottom: 2px;
    font-weight: 600;
  }
  .card-metric-col p {
    font-size: 0.725rem;
    color: var(--text-light);
    margin: 0;
    text-transform: uppercase;
  }

  .card-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .card-btn-link {
    background: transparent;
    border: none;
    color: var(--secondary);
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0;
    transition: color 0.3s ease;
  }
  .card-btn-link:hover {
    color: var(--cream);
  }
  .card-btn-link svg {
    transition: transform 0.3s ease;
  }
  .card-btn-link:hover svg {
    transform: translateX(4px);
  }

  /* Custom styling borders based on card indexes for variety matching general theme */
  .bento-card.type-red { border-bottom-color: var(--accent-red); }
  .bento-card.type-red .card-icon-sphere { background: rgba(209, 61, 82, 0.12); border-color: rgba(209, 61, 82, 0.25); color: var(--accent-red); }
  .bento-card.type-red .card-category-lbl { color: var(--accent-red); }

  .bento-card.type-blue { border-bottom-color: var(--accent-blue); }
  .bento-card.type-blue .card-icon-sphere { background: rgba(50, 133, 199, 0.12); border-color: rgba(50, 133, 199, 0.25); color: var(--accent-blue); }
  .bento-card.type-blue .card-category-lbl { color: var(--accent-blue); }

  .bento-card.type-gold { border-bottom-color: var(--secondary); }
  .bento-card.type-gold .card-icon-sphere { background: rgba(212, 175, 55, 0.12); border-color: rgba(212, 175, 55, 0.25); color: var(--secondary); }
  .bento-card.type-gold .card-category-lbl { color: var(--secondary); }

  .bento-card.type-green { border-bottom-color: var(--accent-green); }
  .bento-card.type-green .card-icon-sphere { background: rgba(56, 142, 99, 0.12); border-color: rgba(56, 142, 99, 0.25); color: var(--accent-green); }
  .bento-card.type-green .card-category-lbl { color: var(--accent-green); }

  .bento-card.type-purple { border-bottom-color: var(--accent-purple); }
  .bento-card.type-purple .card-icon-sphere { background: rgba(144, 85, 188, 0.1); border-color: rgba(144, 85, 188, 0.2); color: var(--accent-purple); }
  .bento-card.type-purple .card-category-lbl { color: var(--accent-purple); }

  .bento-card.type-orange { border-bottom-color: var(--accent-orange); }
  .bento-card.type-orange .card-icon-sphere { background: rgba(234, 133, 69, 0.1); border-color: rgba(234, 133, 69, 0.2); color: var(--accent-orange); }
  .bento-card.type-orange .card-category-lbl { color: var(--accent-orange); }

  /* Filtering Logic Classes */
  .bento-card.hidden {
    opacity: 0;
    transform: scale(0.9) translateY(20px);
    position: absolute;
    pointer-events: none;
    visibility: hidden;
    width: 0;
    height: 0;
    padding: 0;
    margin: 0;
    border: none;
    overflow: hidden;
  }

  /* Visual details on empty matching cases */
  .empty-category-msg {
    grid-column: span 3;
    text-align: center;
    padding: 60px 20px;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 12px;
    border: 1px dashed rgba(255, 255, 255, 0.08);
    display: none;
  }

  /* Interaction Overlay details popup (Modal) */
  .project-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(4, 9, 19, 0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    padding: 20px;
  }
  .project-modal.active {
    opacity: 1;
    pointer-events: auto;
  }
  .project-modal-content {
    background: var(--bg-light);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 16px;
    max-width: 650px;
    width: 100%;
    position: relative;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
    transform: scale(0.95) translateY(15px);
    transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    overflow: hidden;
  }
  .project-modal.active .project-modal-content {
    transform: scale(1) translateY(0);
  }
  .modal-header {
    padding: 30px 40px 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }
  .modal-close-btn {
    background: rgba(255,255,255,0.05);
    border: none;
    color: var(--text-light);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
  }
  .modal-close-btn:hover {
    background: rgba(209, 61, 82, 0.2);
    color: var(--accent-red);
  }
  .modal-body {
    padding: 0 40px 35px 40px;
    max-height: 480px;
    overflow-y: auto;
  }
  .modal-body p {
    color: var(--text-light);
    font-size: 0.975rem;
    line-height: 1.7;
    margin-bottom: 20px;
  }
  .modal-meta-box {
    background: rgba(255,255,255,0.02);
    border-radius: 10px;
    padding: 20px;
    border: 1px solid rgba(255,255,255,0.05);
    margin: 20px 0;
  }
  .modal-meta-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.04);
  }
  .modal-meta-item:last-child {
    border-bottom: none;
  }
  .modal-meta-item span.lbl {
    color: var(--text-light);
    font-size: 0.85rem;
  }
  .modal-meta-item span.val {
    color: var(--cream);
    font-size: 0.9rem;
    font-weight: 600;
  }

  /* Responsive breakpoints for clean scaling */
  @media (max-width: 992px) {
    .spotlight-card {
      grid-template-columns: 1fr;
      padding: 30px;
      gap: 25px;
    }
    .bento-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    .bento-card.double-col {
      grid-column: span 2;
    }
  }
  @media (max-width: 768px) {
    .bento-grid {
      grid-template-columns: 1fr;
    }
    .bento-card.double-col {
      grid-column: span 1;
    }
    .spotlight-title {
      font-size: 1.8rem;
    }
    .filter-tabs {
      gap: 8px;
    }
    .filter-btn {
      padding: 8px 16px;
      font-size: 0.85rem;
    }
  }
</style>

<div class="projects-page">
  
  <section class="hero" style="padding: 40px 0 35px 0;">
    <div class="hero-overlay"></div>
    <div class="container scroll-reveal" style="text-align: center; position: relative; z-index: 2;">
      <!-- Centered Logo same as home page -->
      <div class="hero-logo-wrapper" style="display: flex; justify-content: center; margin-bottom: 25px; margin-top: 10px;">
        <img
          src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>"
          alt="Tatkhalsa Foundation Logo"
          class="hero-gurbani-logo"
          width="240"
          height="240"
          style="width: 240px; height: 240px; object-fit: contain;"
        />
      </div>
      <h1 style="font-family: var(--font-serif); font-size: 2.8rem; color: var(--cream); margin-bottom: 10px; letter-spacing: -0.01em;">
        Our Seva Projects
      </h1>
      <p style="color: var(--secondary); font-family: var(--font-serif); font-style: italic; font-size: 1.05rem; margin-bottom: 20px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.5;">
        "ਘਾਲਿ ਖਾਇ ਕਿਛੁ ਹਥਹੁ ਦੇਇ ॥ ਨਾਨਕ ਰਾਹੁ ਪਛਾਣਹਿ ਸੇਇ ॥"<br />
        <span style="font-size: 0.8rem; color: var(--text-light); font-style: normal; display: block; margin-top: 5px;">One who works for what they eat, and gives some of what they have - O Nanak, they know the Path.</span>
      </p>
      <div style="width: 45px; height: 2px; background: var(--secondary); margin: 0 auto;"></div>
    </div>
  </section>

  <!-- WordPress Editor Page Content Section -->
  <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
          <?php if ( ! empty( get_the_content() ) ) : ?>
              <section class="wp-page-editor-content-section" style="padding: 40px 0; background: var(--bg-shade-1);">
                  <div class="container" style="max-width: 800px; color: var(--text-dark); line-height: 1.8; font-size: 1.05rem;">
                      <div class="entry-content">
                          <?php the_content(); ?>
                      </div>
                  </div>
              </section>
          <?php endif; ?>
      <?php endwhile; ?>
      <?php rewind_posts(); ?>
  <?php endif; ?>

  <!-- Section 1: Hot Active Seva Spotlight -->
  <section class="spotlight-section">
    <div class="container scroll-reveal">
      <div class="spotlight-card">
        <div>
          <div class="spotlight-label">
            <span class="sos-pulse"></span>
            Critical Emergency SOS Active
          </div>
          <h2 class="spotlight-title">Punjab Flood Relief 2025</h2>
          <p class="spotlight-desc">
            Vast regions are currently affected by emergency flood crisis levels. Our rapid-action volunteer squads are mobilized directly across affected villages in Punjab, delivering daily immediate emergency intervention including:
          </p>
          <ul style="color: var(--text-light); font-size: 0.95rem; line-height: 1.8; margin-bottom: 25px; padding-left: 20px;">
            <li>Freshly prepared nutritious Langar meals distributed twice daily</li>
            <li>Critical survival packs (dry non-perishable food, water bottles, and hygiene kits)</li>
            <li>Specially organized temporary medical relief stations with qualified doctors</li>
            <li>Clean livestock fodder and animal welfare aid</li>
          </ul>
          
          <div class="spotlight-progress-lbl">
            <span>Mobilization Target Covered</span>
            <span style="color: var(--secondary); font-weight: bold; font-family: var(--font-mono);">78% Complete</span>
          </div>
          <div class="spotlight-progress-track">
            <div class="spotlight-progress-bar"></div>
          </div>

          <div class="hero-buttons" style="margin-top: 25px;">
            <button class="btn" style="background: var(--accent-red); color: var(--primary); font-size: 0.95rem;" onclick="openModal()">
              Support Flood Relief SOS
            </button>
          </div>
        </div>
        
        <div style="display: flex; flex-direction: column; justify-content: space-between;">
          <div style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(212, 175, 55, 0.2); box-shadow: 0 10px 30px rgba(0,0,0,0.5); margin-bottom: 20px;">
            <img 
              src="https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg" 
              alt="Punjab flood volunteer preparation" 
              style="width: 100%; height: 200px; object-fit: cover;"
            />
          </div>
          
          <div class="spotlight-stats">
            <div class="spotlight-stat-item">
              <h4>12k+</h4>
              <p>Meals Served</p>
            </div>
            <div class="spotlight-stat-item">
              <h4>45+</h4>
              <p>Villages Met</p>
            </div>
            <div class="spotlight-stat-item">
              <h4>150+</h4>
              <p>Active Sevadaars</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 2: Interactive Bento Seva Explorer -->
  <section class="filter-section">
    <div class="container scroll-reveal">
      <h2 class="section-title">Explore Our Ongoing Initiatives</h2>
      
      <div class="filter-tabs">
        <button class="filter-btn active" data-filter="all">All Seva</button>
        <button class="filter-btn" data-filter="relief">Humanitarian Relief</button>
        <button class="filter-btn" data-filter="healthcare">Healthcare Seva</button>
        <button class="filter-btn" data-filter="heritage">Heritage & History</button>
        <button class="filter-btn" data-filter="youth">Youth & Sports</button>
      </div>

      <div class="bento-grid" id="bentoGrid">
        
        <!-- Project 1: General Charity (Relief) -->
        <div class="bento-card double-col type-red" data-cat="relief">
          <div>
            <span class="card-category-lbl">General Charity</span>
            <div class="card-top">
              <h3 class="card-title">General Charity & Rehabilitation Relief</h3>
              <div class="card-icon-sphere">🤝</div>
            </div>

            <div class="bento-card-image-wrap">
              <img src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_charity_support_img', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&auto=format&fit=crop' ) ); ?>" alt="General Charity Support" />
            </div>

            <p class="card-desc">
              We provide essential baseline support for vulnerable low-income families and single parent households. Our charity framework distributes winter blankets, hot weather relief items, monthly groceries, and covers basic shelter restoration in times of acute distress.
            </p>
            <div class="card-metrics-row">
              <div class="card-metric-col">
                <h5>18,000+</h5>
                <p>Blankets Distributed</p>
              </div>
              <div class="card-metric-col">
                <h5>350+</h5>
                <p>Families Sustained</p>
              </div>
              <div class="card-metric-col">
                <h5>Punjab Wide</h5>
                <p>Coverage Scope</p>
              </div>
            </div>
          </div>
          <div class="card-actions">
            <span class="card-badge">Continuous Support</span>
            <button class="card-btn-link" onclick="openDetailsModal('charity')">
              Read Details 
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>

        <!-- Project 2: Healthcare (Healthcare) -->
        <div class="bento-card type-blue" data-cat="healthcare">
          <div>
            <span class="card-category-lbl">Healthcare Seva</span>
            <div class="card-top">
              <h3 class="card-title">Blood On Call</h3>
              <div class="card-icon-sphere">❤️</div>
            </div>

            <div class="bento-card-image-wrap">
              <img src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_blood_contribution_img', 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?q=80&w=800&auto=format&fit=crop' ) ); ?>" alt="Blood On Call Logo" />
            </div>

            <p class="card-desc">
              A meticulously structured 24/7 rapid helpline matching blood donors with patients experiencing emergency health crises, surgeries, or cancer treatments across major Punjab hospitals.
            </p>
          </div>
          <div class="card-actions">
            <span class="card-badge">24/7 Active Helpline</span>
            <button class="card-btn-link" onclick="openDetailsModal('blood')">
              Read Details
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>

        <!-- Project 3: Disaster Relief (Relief) -->
        <div class="bento-card type-red" data-cat="relief">
          <div>
            <span class="card-category-lbl">Emergency Response</span>
            <div class="card-top">
              <h3 class="card-title">Disaster Rapid Response Squad</h3>
              <div class="card-icon-sphere">🚨</div>
            </div>

            <div class="bento-card-image-wrap">
              <img src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_punjab_flood_img', 'https://images.unsplash.com/photo-1547683905-f686c993aae5?q=80&w=800&auto=format&fit=crop' ) ); ?>" alt="Punjab flood response team" />
            </div>

            <p class="card-desc">
              Our trained, local youth team standing ready to handle natural calamities, severe weather events, and community evacuations, working safely in coordination with local authorities.
            </p>
          </div>
          <div class="card-actions">
            <span class="card-badge">100% Mobilized</span>
            <button class="card-btn-link" onclick="openDetailsModal('disaster')">
              Read Details
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>

        <!-- Project 4: Sikh Heritage (Heritage) -->
        <div class="bento-card double-col type-gold" data-cat="heritage">
          <div>
            <span class="card-category-lbl">Preservation Board</span>
            <div class="card-top">
              <h3 class="card-title">Sikh History & Heritage Review Board</h3>
              <div class="card-icon-sphere">🏛️</div>
            </div>

            <div class="bento-card-image-wrap">
              <img src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_sikh_heritage_img', 'https://upload.wikimedia.org/wikipedia/commons/e/ee/Group_of_Nihang_Singhs.jpg' ) ); ?>" alt="Preserving Sikh Heritage" />
            </div>

            <p class="card-desc">
              Dedicated to academic preservation, historical accuracy, and community education. We host dedicated panel roundtables, build peer-reviewed literature references, and archive ancient manuscripts to guarantee the exact and authentic representation of rich Sikh history and core principles globally.
            </p>
            <div class="card-metrics-row">
              <div class="card-metric-col">
                <h5>12+</h5>
                <p>Scholars Engaged</p>
              </div>
              <div class="card-metric-col">
                <h5>85%</h5>
                <p>Archiving Catalogued</p>
              </div>
              <div class="card-metric-col">
                <h5>Global</h5>
                <p>Academic Reach</p>
              </div>
            </div>
          </div>
          <div class="card-actions">
            <span class="card-badge">Aesthetic Preservation</span>
            <button class="card-btn-link" onclick="openDetailsModal('heritage')">
              Read Details
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>

        <!-- Project 5: Youth & Sports (Youth) -->
        <div class="bento-card type-orange" data-cat="youth">
          <div>
            <span class="card-category-lbl">Development</span>
            <div class="card-top">
              <h3 class="card-title">Youth Kabaddi & Sports Champions</h3>
              <div class="card-icon-sphere">🏆</div>
            </div>

            <div class="bento-card-image-wrap">
              <img src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_kabaddi_athletic_img', 'https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=800&auto=format&fit=crop' ) ); ?>" alt="Kabaddi and Athletic support" />
            </div>

            <p class="card-desc">
              Steering Punjab's bright youth away from drug abuse and screen isolation. We build local sports clubs, distribute fitness gears, and organize traditional Shashtar & sports championships.
            </p>
          </div>
          <div class="card-actions">
            <span class="card-badge">Healthy Punjab</span>
            <button class="card-btn-link" onclick="openDetailsModal('youth')">
              Read Details
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>

        <!-- Project 6: Eco Welfare (Youth / Welfare) -->
        <div class="bento-card type-green" data-cat="youth">
          <div>
            <span class="card-category-lbl">Community Welfare</span>
            <div class="card-top">
              <h3 class="card-title">Eco-Sikh Environmental Stewardship</h3>
              <div class="card-icon-sphere">🌱</div>
            </div>

            <div class="bento-card-image-wrap">
              <img src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_tree_planting_img', 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=800&auto=format&fit=crop' ) ); ?>" alt="Tree planting stewardship" />
            </div>

            <p class="card-desc">
              Upholding the Gurbani vision of 'Pavan Guru Paani Pita' (Air as Guru, Water as Father). We carry out systematic local tree plantation drives, seed sharing, and pure clean-water filtration.
            </p>
          </div>
          <div class="card-actions">
            <span class="card-badge">Sustainable Lifestyle</span>
            <button class="card-btn-link" onclick="openDetailsModal('eco')">
              Read Details
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>

        <!-- No data notification container -->
        <div class="empty-category-msg" id="emptyCategoryMsg">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--text-light); margin-bottom: 12px; display: inline-block;">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <h4 style="color: var(--cream); font-size: 1.15rem; margin-bottom: 5px;">Initiative category coming soon</h4>
          <p style="color: var(--text-light); font-size: 0.9rem; margin: 0;">We are currently finalizing structured blueprints for this specific seva department.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- Section 3: Gilded Impact Gallery -->
  <section id="gallery" style="background-color: var(--bg-shade-4); padding: 80px 0;">
    <div class="container scroll-reveal">
      <h2 class="section-title">Our Seva in Action</h2>
      <div class="gallery-grid">
        <div class="gallery-item" style="border: 1px solid rgba(212, 175, 55, 0.15); border-radius: 12px; overflow: hidden; position: relative;">
          <img
            src="https://upload.wikimedia.org/wikipedia/commons/e/ee/Group_of_Nihang_Singhs.jpg"
            alt="Group of Nihang Singhs"
            style="width: 100%; transition: transform 0.4s ease;"
          />
          <div class="gallery-overlay" style="background: linear-gradient(to top, rgba(8, 17, 35, 0.9) 0%, rgba(8, 17, 35, 0.3) 100%); transition: all 0.3s ease;">
            <span class="gallery-text" style="font-family: var(--font-serif); color: var(--secondary); font-size: 1.15rem; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">Preserving Sikh Heritage</span>
          </div>
        </div>
        <div class="gallery-item" style="border: 1px solid rgba(212, 175, 55, 0.15); border-radius: 12px; overflow: hidden; position: relative;">
          <img
            src="https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg"
            alt="Langar Preparation"
            style="width: 100%; transition: transform 0.4s ease;"
          />
          <div class="gallery-overlay" style="background: linear-gradient(to top, rgba(8, 17, 35, 0.9) 0%, rgba(8, 17, 35, 0.3) 100%); transition: all 0.3s ease;">
            <span class="gallery-text" style="font-family: var(--font-serif); color: var(--secondary); font-size: 1.15rem; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">Active Langar Sewa</span>
          </div>
        </div>
        <div class="gallery-item" style="border: 1px solid rgba(212, 175, 55, 0.15); border-radius: 12px; overflow: hidden; position: relative;">
          <img
            src="https://upload.wikimedia.org/wikipedia/commons/d/de/Sikhs_gathered_at_Hola_Mohalla_Holi_festival_in_Anandpur_Sahib.jpg"
            alt="Sikhs at Hola Mohalla"
            style="width: 100%; transition: transform 0.4s ease;"
          />
          <div class="gallery-overlay" style="background: linear-gradient(to top, rgba(8, 17, 35, 0.9) 0%, rgba(8, 17, 35, 0.3) 100%); transition: all 0.3s ease;">
            <span class="gallery-text" style="font-family: var(--font-serif); color: var(--secondary); font-size: 1.15rem; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">Hola Mohalla Welfare</span>
          </div>
        </div>
        <div class="gallery-item" style="border: 1px solid rgba(212, 175, 55, 0.15); border-radius: 12px; overflow: hidden; position: relative;">
          <img
            src="https://upload.wikimedia.org/wikipedia/commons/7/73/The_Camp_of_Bhai_Bir_Singh_Naurangabad%2C_Punjab%2C_ca.1850.jpg"
            alt="Historic Sikh Camp"
            style="width: 100%; transition: transform 0.4s ease;"
          />
          <div class="gallery-overlay" style="background: linear-gradient(to top, rgba(8, 17, 35, 0.9) 0%, rgba(8, 17, 35, 0.3) 100%); transition: all 0.3s ease;">
            <span class="gallery-text" style="font-family: var(--font-serif); color: var(--secondary); font-size: 1.15rem; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">Preserving Sikh Archives</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section: interactive "Partnership & World Championship" slide deck -->
  <section id="partnership-championship-deck" class="scroll-reveal" style="background: linear-gradient(180deg, var(--bg-shade-2) 0%, var(--bg-shade-3) 100%); padding: 80px 0; border-top: 1px solid rgba(212, 175, 55, 0.15); border-bottom: 1px solid rgba(212, 175, 55, 0.15); position: relative; z-index: 2;">
    <div style="position: absolute; top:0; left:0; right:0; bottom:0; background-image: radial-gradient(circle at 80% 30%, rgba(212, 175, 55, 0.03) 0%, transparent 50%); pointer-events: none; z-index: 1;"></div>
    
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px; position: relative; z-index: 2;">
      <!-- Section Header -->
      <div style="text-align: center; margin-bottom: 45px;">
        <span style="display: inline-block; background: rgba(212, 175, 55, 0.12); border: 1.5px solid var(--secondary); color: var(--secondary); font-family: var(--font-mono); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; padding: 5px 14px; border-radius: 50px; letter-spacing: 0.1em; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(212, 175, 55, 0.1);">
          🏆 GLOBAL CHAMPIONSHIP DECK
        </span>
        <h2 style="font-family: var(--font-serif); font-size: 2.3rem; color: var(--cream); margin: 0 0 12px 0; letter-spacing: -0.5px; line-height: 1.2;">
          Partnerships &amp; Grand World Championship
        </h2>
        <div style="width: 50px; height: 3px; background: var(--secondary); margin: 0 auto 15px auto; border-radius: 2px;"></div>
        <p style="color: var(--text-light); font-size: 1rem; max-width: 750px; margin: 0 auto; line-height: 1.6;">
          Tatkhalsa Foundation presents our preeminent international heritage sports ecosystem, combining institutional Indian CSR governance with global commercial ad inventory.
        </p>
      </div>

      <!-- Slide Deck Layout Container -->
      <div class="partnership-layout" style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <!-- Left Side: Interactive Navigation Track (7 Tabs) -->
        <div class="partnership-nav-col" style="flex: 0 0 310px; min-width: 270px; display: flex; flex-direction: column; gap: 8px;">
          <h3 style="color: var(--cream); font-size: 0.95rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 10px; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px; display: flex; align-items: center; gap: 8px;">
            <span>📋</span> STRATEGIC CHAPTERS
          </h3>
          
          <?php 
          $tabs = [
            1 => ["Title", "Heritage Showcase & Scope"],
            2 => ["Institutional Vehicle", "Section 8 MCAs verified"],
            3 => ["Reach &amp; Impact", "Direct audience indicators"],
            4 => ["Safety &amp; Regulatory", "Audited field structures"],
            5 => ["Digital Transparency", "Real-time live registry sync"],
            6 => ["Monetization Matrix", "CSR &amp; Commercial Banner slots"],
            7 => ["Global Desk &amp; CTA", "Audited filings &amp; callbacks"]
          ];
          foreach($tabs as $id => $data):
            $activeClass = $id === 1 ? 'active-tab' : '';
          ?>
          <button class="partnership-tab-btn <?php echo $activeClass; ?>" onclick="setPartnershipSlide(<?php echo $id; ?>)" style="text-align: left; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06); padding: 12px 18px; border-radius: 10px; cursor: pointer; transition: all 0.2s ease; width: 100%; font-family: var(--font-sans);" id="partner-tab-<?php echo $id; ?>">
            <div style="display:flex; align-items:center; gap:12px;">
              <span class="tab-badge" style="width: 26px; height: 26px; background: <?php echo $id === 1 ? 'var(--secondary)' : 'rgba(255,255,255,0.05)'; ?>; color: <?php echo $id === 1 ? 'var(--bg-dark)' : 'var(--text-light)'; ?>; font-weight:bold; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; border: 1px solid <?php echo $id === 1 ? 'var(--secondary)' : 'rgba(255,255,255,0.1)'; ?>; font-size: 0.82rem;"><?php echo $id; ?></span>
              <div>
                <h4 style="margin:0 0 2px 0; font-size:0.88rem; color:<?php echo $id === 1 ? 'var(--secondary)' : 'var(--text-light)'; ?>; font-weight:700; transition: color 0.2s;" class="tab-title"><?php echo $data[0]; ?></h4>
                <p style="margin:0; font-size:0.72rem; color:var(--text-light);"><?php echo $data[1]; ?></p>
              </div>
            </div>
          </button>
          <?php endforeach; ?>
        </div>

        <!-- Right Side: Active Presentation Stage -->
        <div class="partnership-content-col" style="flex: 1; min-width: 300px; background: var(--bg-light); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 14px; padding: 35px; box-shadow: 0 15px 40px rgba(0,0,0,0.3); min-height: 480px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;">
          
          <!-- Subtle watermark decorative element -->
          <div style="position: absolute; bottom:-15px; right:-15px; font-size: 11vw; line-height:1; font-weight:900; color: rgba(255,255,255,0.01); pointer-events:none; font-family: var(--font-sans); user-select:none;">KHALSA</div>
          
          <!-- SLIDE 1: CHAMPIONSHIP TITLE BLOCK -->
          <div class="partner-slide-element" id="partner-slide-1" style="display: block;">
            <div>
              <span style="font-family: var(--font-mono); font-size: 0.7rem; background: rgba(209, 61, 82, 0.12); color: var(--accent-red); border: 1px solid rgba(209, 61, 82, 0.3); padding: 3px 8px; border-radius: 4px; font-weight:700; letter-spacing: 0.05em; display: inline-block; margin-bottom: 15px;">
                ✦ WORLD CHAMPIONSHIP
              </span>
              <h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--cream); margin: 0 0 12px 0; line-height: 1.30;">
                Mahakal Akaali Baba Fateh Singh Ji Khalsa Worrier Fest
              </h3>
              <p style="color: var(--secondary); font-family: var(--font-sans); font-size: 1rem; font-weight: 500; margin-bottom: 15px; border-left: 2px solid var(--secondary); padding-left: 12px;">
                Preeminent International Heritage Martial Arts &amp; Traditional Sports Championship
              </p>
              <p style="color: var(--text-light); line-height: 1.6; font-size: 0.9rem; margin-bottom: 20px;">
                The grand <strong>Worrier Fest</strong> mobilizes premier international athlete squads and teams from around the globe. By anchoring its central arena operations on-ground in Punjab, India, we establish a majestic interface between elite performance and international media, aligned with MCA <strong>Schedule VII Heritage &amp; Traditional Sports promotion rules</strong>.
              </p>
              
              <h4 style="color: var(--secondary); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; margin: 20px 0 12px 0; font-family: var(--font-sans); font-weight: 700; display:flex; align-items:center; gap:6px;">
                🗡️ The 5 Core International Championship Disciplines
              </h4>
              <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 10px;">
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block;">1. Gatka (Fari Soti only)</strong>
                  <span style="font-size:0.72rem; color: var(--text-light);">Traditional combat drills</span>
                </div>
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block;">2. Traditional Archery</strong>
                  <span style="font-size:0.72rem; color: var(--text-light);">Static range archery targets</span>
                </div>
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block;">3. Archery on Horseback</strong>
                  <span style="font-size:0.72rem; color: var(--text-light);">Elite horse archery drills</span>
                </div>
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block;">4. Javelin Throw</strong>
                  <span style="font-size:0.72rem; color: var(--text-light);">Heavy-spear distance throw</span>
                </div>
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block;">5. Cemp Pit / Qeela Puttna</strong>
                  <span style="font-size:0.72rem; color: var(--text-light);">Traditional horse peg pulling</span>
                </div>
              </div>
            </div>
            
            <div class="slide-footer-cta" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <span style="font-size: 0.8rem; color: var(--text-light);">Category: Schedule VII traditional sports promotion</span>
              <button onclick="setPartnershipSlide(2)" class="btn" style="padding: 8px 14px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                Legal Vehicle ➔
              </button>
            </div>
          </div>

          <!-- SLIDE 2: THE INSTITUTIONAL VEHICLE -->
          <div class="partner-slide-element" id="partner-slide-2" style="display: none;">
            <div>
              <span style="font-family: var(--font-mono); font-size: 0.7rem; background: rgba(56, 142, 99, 0.12); color: var(--accent-green); border: 1px solid rgba(56, 142, 99, 0.3); padding: 3px 8px; border-radius: 4px; font-weight:700; letter-spacing: 0.05em; display: inline-block; margin-bottom: 15px;">
                🛡️ COMPLIANCE &amp; CREDENTIALS
              </span>
              <h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--cream); margin: 0 0 12px 0; line-height: 1.30;">
                Our Vetted Institutional Structure
              </h3>
              <p style="color: var(--text-light); line-height: 1.6; font-size: 0.9rem; margin-bottom: 20px;">
                Corporations checking compliance parameters find that the entire championship is steered under direct professional management by the <strong>Tatkhalsa Foundation</strong>. Your funding will traverse transparently through our fully cleared legal vehicle.
              </p>

              <!-- Credentials Grid -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div style="background: rgba(255,255,255,0.015); border: 1px solid rgba(212, 175, 55, 0.12); border-radius: 10px; padding: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                  <div style="font-size: 1.2rem; margin-bottom: 6px;">🏢</div>
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block; margin-bottom:2px;">Legal Registration</strong>
                  <p style="margin: 0; font-size: 0.78rem; color: var(--text-light); line-height: 1.4;">Section 8 Corporate NGO registered natively under the Ministry of Corporate Affairs, India.</p>
                </div>

                <div style="background: rgba(255,255,255,0.015); border: 1px solid rgba(212, 175, 55, 0.12); border-radius: 10px; padding: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                  <div style="font-size: 1.2rem; margin-bottom: 6px;">🆔</div>
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block; margin-bottom:2px;">Corporate ID (CIN)</strong>
                  <code style="font-family: var(--font-mono); font-size: 0.8rem; color: var(--secondary); background: rgba(212,175,55,0.1); padding: 2px 5px; border-radius: 4px; display:inline-block; margin-bottom: 2px;">U88900PB2023NPL059225</code>
                  <p style="margin: 0; font-size: 0.78rem; color: var(--text-light); line-height: 1.4;">Formally incorporated and recognized section compliance registry.</p>
                </div>

                <div style="background: rgba(255,255,255,0.015); border: 1px solid rgba(212, 175, 55, 0.12); border-radius: 10px; padding: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                  <div style="font-size: 1.2rem; margin-bottom: 6px;">🧾</div>
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block; margin-bottom:2px;">Tax Clearance &amp; Compliance</strong>
                  <span style="font-size: 0.75rem; color: var(--text-light); display:block;"><b style="color:var(--secondary);">Form 12A &amp; 80G:</b> Fully active tax clearances</span>
                  <span style="font-size: 0.75rem; color: var(--text-light); display:block;"><b style="color:var(--secondary);">Form CSR-1:</b> Registered with the MCA Portal</span>
                </div>
              </div>
            </div>

            <div class="slide-footer-cta" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <span style="font-size: 0.8rem; color: var(--text-light);">Audited Status: 100% compliant section governance</span>
              <button onclick="setPartnershipSlide(3)" class="btn" style="padding: 8px 14px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                Reach &amp; Impact ➔
              </button>
            </div>
          </div>

          <!-- SLIDE 3: GLOBAL REACH & AUDIENCE IMPACT -->
          <div class="partner-slide-element" id="partner-slide-3" style="display: none;">
            <div>
              <span style="font-family: var(--font-mono); font-size: 0.7rem; background: rgba(144, 85, 188, 0.1); color: var(--accent-purple); border: 1px solid rgba(144, 85, 188, 0.25); padding: 3px 8px; border-radius: 4px; font-weight:700; letter-spacing: 0.05em; display: inline-block; margin-bottom: 15px;">
                📈 BROADCAST &amp; TRAFFIC REACH
              </span>
              <h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--cream); margin: 0 0 12px 0; line-height: 1.30;">
                Championship Global Footprint
              </h3>
              <p style="color: var(--text-light); line-height: 1.6; font-size: 0.9rem; margin-bottom: 20px;">
                Projected performance indicators tracking our preeminent tournament metrics, mobilizing the international sports diaspora and on-ground viewers in Punjab.
              </p>

              <!-- Projected Stats Grid -->
              <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 20px;">
                <div style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px 15px; display: flex; align-items: center; gap: 12px;">
                  <div style="font-family: var(--font-mono); font-size: 1.8rem; font-weight: 700; color: var(--secondary); line-height: 1; min-width: 70px;">15+</div>
                  <div>
                    <h5 style="margin: 0; color: var(--cream); font-size: 0.82rem;">Nations Mobilized</h5>
                    <p style="margin:0; font-size:0.7rem; color:var(--text-light); line-height:1.2;">Elite diaspora squads from Canada, UK, USA, Australia etc.</p>
                  </div>
                </div>

                <div style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px 15px; display: flex; align-items: center; gap: 12px;">
                  <div style="font-family: var(--font-mono); font-size: 1.8rem; font-weight: 700; color: var(--secondary); line-height: 1; min-width: 70px;">5.2M+</div>
                  <div>
                    <h5 style="margin: 0; color: var(--cream); font-size: 0.82rem;">Global Broadcast</h5>
                    <p style="margin:0; font-size:0.7rem; color:var(--text-light); line-height:1.2;">Stream portal views, dynamic live feeds, and satellite logs.</p>
                  </div>
                </div>

                <div style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px 15px; display: flex; align-items: center; gap: 12px;">
                  <div style="font-family: var(--font-mono); font-size: 1.8rem; font-weight: 700; color: var(--secondary); line-height: 1; min-width: 70px;">150K+</div>
                  <div>
                    <h5 style="margin: 0; color: var(--cream); font-size: 0.82rem;">Stadium Footfall</h5>
                    <p style="margin:0; font-size:0.7rem; color:var(--text-light); line-height:1.2;">Cumulative live spectators across a majestic 3-day run.</p>
                  </div>
                </div>

                <div style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px 15px; display: flex; align-items: center; gap: 12px;">
                  <div style="font-family: var(--font-mono); font-size: 1.8rem; font-weight: 700; color: var(--secondary); line-height: 1; min-width: 70px;">850+</div>
                  <div>
                    <h5 style="margin: 0; color: var(--cream); font-size: 0.82rem;">Pro Athletes</h5>
                    <p style="margin:0; font-size:0.7rem; color:var(--text-light); line-height:1.2;">High-performance traditional registered sports roster.</p>
                  </div>
                </div>
              </div>

              <!-- Extra Impact Info -->
              <div style="background: rgba(212,175,55,0.03); border: 1px dashed rgba(212,175,55,0.2); border-radius: 8px; padding: 12px; font-size: 0.8rem; color: var(--text-light); display:flex; align-items:center; gap:10px;">
                <span style="font-size:1.2rem;">🌱</span>
                <span><strong>Sports Seva Youth Engagement:</strong> Securing 25,000+ substance-prevention clean pledges locally.</span>
              </div>
            </div>

            <div class="slide-footer-cta" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <span style="font-size: 0.8rem; color: var(--text-light);">Audience audit coordinates compiled live</span>
              <button onclick="setPartnershipSlide(4)" class="btn" style="padding: 8px 14px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                Safety &amp; Rules ➔
              </button>
            </div>
          </div>

          <!-- SLIDE 4: STRICT SAFETY & REGULATORY INFRASTRUCTURE -->
          <div class="partner-slide-element" id="partner-slide-4" style="display: none;">
            <div>
              <span style="font-family: var(--font-mono); font-size: 0.7rem; background: rgba(50, 133, 199, 0.12); color: var(--accent-blue); border: 1px solid rgba(50, 133, 199, 0.25); padding: 3px 8px; border-radius: 4px; font-weight:700; letter-spacing: 0.05em; display: inline-block; margin-bottom: 15px;">
                🛡️ CERTIFICATION &amp; SAFETY
              </span>
              <h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--cream); margin: 0 0 12px 0; line-height: 1.30;">
                Arena Safety &amp; Regulatory Integrity
              </h3>
              <p style="color: var(--text-light); line-height: 1.6; font-size: 0.9rem; margin-bottom: 20px;">
                We enforce elite championship protocols to protect competitors, maintain sport authority, and execute zero-accident events.
              </p>

              <!-- Core Regulatory Pillars -->
              <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                <div style="display: flex; gap: 12px; background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.04); padding: 12px; border-radius: 8px;">
                  <span style="font-size: 1.2rem; line-height: 1;">🏟️</span>
                  <div>
                    <h5 style="margin: 0 0 3px 0; color: var(--cream); font-size: 0.88rem; font-weight:700;">Multi-Zone Fields</h5>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.78rem; line-height: 1.4;">Professional stadium grids mapped with strict safety buffers and secure perimeters.</p>
                  </div>
                </div>

                <div style="display: flex; gap: 12px; background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.04); padding: 12px; border-radius: 8px;">
                  <span style="font-size: 1.2rem; line-height: 1;">⚖️</span>
                  <div>
                    <h5 style="margin: 0 0 3px 0; color: var(--cream); font-size: 0.88rem; font-weight:700;">Certified Officiary Registry</h5>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.78rem; line-height: 1.4;">Certified international referees ensuring neutral and completely vetted score logs.</p>
                  </div>
                </div>

                <div style="display: flex; gap: 12px; background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.04); padding: 12px; border-radius: 8px;">
                  <span style="font-size: 1.2rem; line-height: 1;">📝</span>
                  <div>
                    <h5 style="margin: 0 0 3px 0; color: var(--cream); font-size: 0.88rem; font-weight:700;">Legal Indemnity Bonds</h5>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.78rem; line-height: 1.4;">Mandatory participant Indemnity Bond workflows alongside dynamic medic triage stations checkups.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="slide-footer-cta" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <span style="font-size: 0.8rem; color: var(--text-light);">Safety checklist verified by audit</span>
              <button onclick="setPartnershipSlide(5)" class="btn" style="padding: 8px 14px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                Transparency Sync ➔
              </button>
            </div>
          </div>

          <!-- SLIDE 5: DIGITAL GROUND TRANSPARENCY LOOP -->
          <div class="partner-slide-element" id="partner-slide-5" style="display: none;">
            <div>
              <span style="font-family: var(--font-mono); font-size: 0.7rem; background: rgba(212, 175, 55, 0.12); color: var(--secondary); border: 1px solid rgba(212, 175, 55, 0.3); padding: 3px 8px; border-radius: 4px; font-weight:700; letter-spacing: 0.05em; display: inline-block; margin-bottom: 15px;">
                💻 tatkhalsa.in TECHNICAL INTEGRATION
              </span>
              <h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--cream); margin: 0 0 12px 0; line-height: 1.30;">
                Web Platform &amp; Real-Time Registry Sync
              </h3>
              <p style="color: var(--text-light); line-height: 1.6; font-size: 0.9rem; margin-bottom: 20px;">
                We connect physical on-field operations directly with active digital records of <strong>tatkhalsa.in</strong> for peak administrative transparency.
              </p>

              <!-- Tech Flow steps -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div style="background: rgba(8,17,35,0.5); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; position:relative;">
                  <span style="font-size: 0.7rem; font-family: var(--font-mono); color: var(--secondary); font-weight:bold; position:absolute; top:8px; right:10px;">01</span>
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block; margin-bottom:4px;">Online Registry</strong>
                  <p style="margin:0; font-size:0.75rem; color:var(--text-light); line-height:1.4;">Athletes configure credentials and upload legal waivers directly online.</p>
                </div>

                <div style="background: rgba(8,17,35,0.5); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; position:relative;">
                  <span style="font-size: 0.7rem; font-family: var(--font-mono); color: var(--secondary); font-weight:bold; position:absolute; top:8px; right:10px;">02</span>
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block; margin-bottom:4px;">ID Cryptography</strong>
                  <p style="margin:0; font-size:0.75rem; color:var(--text-light); line-height:1.4;">Receive a verified Competitor ID card containing secure security QR checks.</p>
                </div>

                <div style="background: rgba(8,17,35,0.5); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; position:relative;">
                  <span style="font-size: 0.7rem; font-family: var(--font-mono); color: var(--secondary); font-weight:bold; position:absolute; top:8px; right:10px;">03</span>
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block; margin-bottom:4px;">On-Ground Scans</strong>
                  <p style="margin:0; font-size:0.75rem; color:var(--text-light); line-height:1.4;">On-field digital checklist validation protects game rings from credential fraud.</p>
                </div>
              </div>
            </div>

            <div class="slide-footer-cta" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <span style="font-size: 0.8rem; color: var(--text-light);">Dynamic sync logs active on-site</span>
              <button onclick="setPartnershipSlide(6)" class="btn" style="padding: 8px 14px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                Ad Slots &amp; CSR ➔
              </button>
            </div>
          </div>

          <!-- SLIDE 6: DUAL MONETIZATION MATRIX (Web wrapper style banner mocks) -->
          <div class="partner-slide-element" id="partner-slide-6" style="display: none;">
            <div>
              <span style="font-family: var(--font-mono); font-size: 0.7rem; background: rgba(209, 61, 82, 0.12); color: var(--accent-red); border: 1px solid rgba(209, 61, 82, 0.3); padding: 3px 8px; border-radius: 4px; font-weight:700; letter-spacing: 0.05em; display: inline-block; margin-bottom: 12px;">
                💰 ADVERTISING INVENTORY &amp; CSR ALLOCATIONS
              </span>
              <h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--cream); margin: 0 0 10px 0; line-height: 1.30;">
                Dual Financing &amp; Sponsorship Matrix
              </h3>
              <p style="color: var(--text-light); line-height: 1.5; font-size: 0.88rem; margin-bottom: 15px;">
                Our dual-lane sponsorship enables eligible corporate CSR funds to align with high-exposure commercial brand ad spots.
              </p>

              <!-- Partition Grid -->
              <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 20px;">
                <div style="flex: 1; min-width: 230px; background: rgba(56, 142, 99, 0.04); border: 1px solid rgba(56, 142, 99, 0.25); border-radius: 10px; padding: 15px;">
                  <strong style="color: var(--accent-green); font-size: 0.8rem; font-family: var(--font-mono); display:block; margin-bottom:4px;">🟢 LANE A: CORPORATE CSR</strong>
                  <h4 style="margin: 0 0 4px 0; color: var(--cream); font-size:1rem; font-weight:700;">Section 8 Heritage Pillars</h4>
                  <p style="margin: 0; font-size: 0.75rem; color: var(--text-light); line-height: 1.4;">Schedule VII traditional sports conservation allocations. Includes audited certifications.</p>
                </div>

                <div style="flex: 1; min-width: 230px; background: rgba(209, 61, 82, 0.04); border: 1px solid rgba(209, 61, 82, 0.25); border-radius: 10px; padding: 15px;">
                  <strong style="color: var(--accent-red); font-size: 0.8rem; font-family: var(--font-mono); display:block; margin-bottom:4px;">🔴 LANE B: COMMERCIAL BANNERS</strong>
                  <h4 style="margin: 0 0 4px 0; color: var(--cream); font-size:1rem; font-weight:700;">High-Visibility Brand Exposure</h4>
                  <p style="margin: 0; font-size: 0.75rem; color: var(--text-light); line-height: 1.4;">Interactive ad placements across live stream overlays, sidebars, and stadium slots.</p>
                </div>
              </div>

              <!-- Banner Preview Workspace -->
              <h4 style="color: var(--secondary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; font-family: var(--font-mono); font-weight: 700;">
                🖥️ Live Web Page Ad Banner Container Slots
              </h4>
              
              <!-- Webpage Mockup showing placement grids -->
              <div style="background: rgba(6,12,24,0.7); border: 1.5px solid rgba(212,175,55,0.25); border-radius: 10px; padding: 15px; font-family: var(--font-sans); position: relative; overflow: hidden; box-shadow: inset 0 0 15px rgba(0,0,0,0.8);">
                
                <!-- 1. Header Leaderboard Ad Slot -->
                <div class="ad-mock-slot hover-neon-green" style="background: rgba(212, 175, 55, 0.03); border: 1.2px dashed rgba(212, 175, 55, 0.4); border-radius: 5px; padding: 6px; text-align: center; margin-bottom: 10px;" id="ad-header-728">
                  <span style="font-family: var(--font-mono); font-size: 0.65rem; display:block; color: var(--secondary); font-weight: 700;">🌐 HEADER LEADERBOARD BANNER SLOT [728x90]</span>
                  <span style="font-size: 0.6rem; color: var(--text-light); display:block;">Elite Top-Of-Portal Web Header - Broad Diaspora Reach</span>
                </div>

                <!-- Content Area Grid -->
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                  <!-- Main Live Stream Screen mockup with overlay -->
                  <div style="flex: 2; min-width: 200px; background: #000; border: 1px solid rgba(255,255,255,0.08); border-radius: 6px; height: 120px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 8px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                      <span style="background:#ff334b; color:#fff; font-size:0.55rem; padding: 1px 4px; border-radius:3px; font-weight:bold;">● BROADCAST FEED</span>
                      <span style="color:#fff; font-size:0.55rem; background:rgba(0,0,0,0.6); padding:1px 4px; border-radius:3px; font-family:var(--font-mono);">Ring 01</span>
                    </div>

                    <!-- 3. Inline Live-Stream Overlay Ad Slot at bottom -->
                    <div class="ad-mock-slot hover-neon-red" style="background: rgba(209, 61, 82, 0.16); border: 1px dashed var(--accent-red); border-radius: 3px; padding: 3px; text-align: center;" id="ad-overlay-468">
                      <span style="font-family: var(--font-mono); font-size: 0.62rem; color: var(--accent-red); font-weight:700;">📺 LIVE-STREAM INLINE OVERLAY BANNER [468x60]</span>
                    </div>
                  </div>

                  <!-- 2. Sidebar Medium Rectangle Ad Slot -->
                  <div class="ad-mock-slot hover-neon-blue" style="flex: 1; min-width: 130px; background: rgba(50, 133, 199, 0.03); border: 1.2px dashed rgba(50, 133, 199, 0.4); border-radius: 6px; padding: 10px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;" id="ad-sidebar-300">
                    <span style="font-family: var(--font-mono); font-size: 0.65rem; color: var(--accent-blue); font-weight:700; display:block;">📰 SIDEBAR REC [300x250]</span>
                    <span style="font-size:0.58rem; color:var(--text-light); margin-top:2px;">Targeted Sidebar</span>
                  </div>
                </div>

                <!-- 4. Stadium Arena printed billboard -->
                <div class="ad-mock-slot hover-neon-purple" style="background: rgba(144, 85, 188, 0.03); border: 1.2px dashed rgba(144, 85, 188, 0.4); border-radius: 5px; padding: 6px; text-align: center; margin-top: 10px;" id="ad-stadium-custom">
                  <span style="font-family: var(--font-mono); font-size: 0.65rem; display:block; color: var(--accent-purple); font-weight: 700;">🏟️ ON-FIELD STADIUM ARENA BANNER [CUSTOM BILLBOARD]</span>
                  <span style="font-size: 0.6rem; color: var(--text-light); display:block;">Ground Venue Branding Placement - Featured in TV coverage broadcast</span>
                </div>
              </div>
            </div>

            <div class="slide-footer-cta" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <span style="font-size: 0.8rem; color: var(--text-light);">Directly hover ad units to check slot parameters</span>
              <button onclick="setPartnershipSlide(7)" class="btn" style="padding: 8px 14px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                Strategic Desk ➔
              </button>
            </div>
          </div>

          <!-- SLIDE 7: GLOBAL BOOKING DESK & ACCOUNTABILITY -->
          <div class="partner-slide-element" id="partner-slide-7" style="display: none;">
            <div>
              <span style="font-family: var(--font-mono); font-size: 0.7rem; background: rgba(56, 142, 99, 0.12); color: var(--accent-green); border: 1px solid rgba(56, 142, 99, 0.3); padding: 3px 8px; border-radius: 4px; font-weight:700; letter-spacing: 0.05em; display: inline-block; margin-bottom: 15px;">
                📬 DESK DIRECT CTAs
              </span>
              <h3 style="font-family: var(--font-serif); font-size: 1.7rem; color: var(--cream); margin: 0 0 12px 0; line-height: 1.30;">
                Stewardship Desk &amp; Instant Booking Liaison
              </h3>
              <p style="color: var(--text-light); line-height: 1.6; font-size: 0.9rem; margin-bottom: 20px;">
                We maintain standard audited disclosures for our partners. Secure your brand's presence or lock down institutional CSR commitments today.
              </p>

              <!-- Governance Deliverables -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 15px; margin-bottom: 25px;">
                <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.04); border-radius: 8px; padding: 15px;">
                  <div style="font-size: 1.2rem; margin-bottom: 5px;">📑</div>
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block; margin-bottom:2px;">CA Audited Utilization Certificates</strong>
                  <p style="margin: 0; font-size: 0.78rem; color: var(--text-light); line-height: 1.4;">Provided upon project deployment to satisfy Section 8 MCA reporting metrics perfectly.</p>
                </div>

                <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.04); border-radius: 8px; padding: 15px;">
                  <div style="font-size: 1.2rem; margin-bottom: 5px;">📊</div>
                  <strong style="color: var(--cream); font-size: 0.85rem; display:block; margin-bottom:2px;">Analytics Performance Registry</strong>
                  <p style="margin: 0; font-size: 0.78rem; color: var(--text-light); line-height: 1.4;">Commercial brands receive full metric logs, traffic impression figures, and broadcast records.</p>
                </div>
              </div>

              <!-- High Impact Booking Panel -->
              <div style="background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(8,17,35,0.8) 100%); border: 1.2px solid var(--secondary); border-radius: 10px; padding: 22px; text-align: center;">
                <h4 style="margin: 0 0 8px 0; color: var(--cream); font-size: 1.1rem; font-weight: 700;">Secure Your Championship Slot</h4>
                <p style="margin: 0 0 15px 0; color: var(--text-light); font-size: 0.82rem; line-height: 1.4; max-width: 500px; margin-left:auto; margin-right:auto;">
                  Connect with our global booking desk. Our coordinators will reach out to schedule a planning liaison session directly.
                </p>
                
                <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
                  <a href="mailto:info@tatkhalsa.in?subject=World Championship Partnership Enquiry" style="border-radius:30px; text-decoration:none; padding:8px 18px; font-size:0.8rem;" class="btn">
                    📧 Email Desk: info@tatkhalsa.in
                  </a>
                  <button onclick="if(typeof openModal === 'function') openModal();" class="btn-outline" style="border-color: var(--secondary); color: var(--secondary); border-radius:30px; font-size: 0.8rem; padding: 8px 18px; font-weight:600;">
                    💝 General Donation Seva
                  </button>
                </div>
              </div>
            </div>

            <div class="slide-footer-cta" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <span style="font-size: 0.8rem; color: var(--text-light);">Strategic liaison routing active</span>
              <button onclick="setPartnershipSlide(1)" class="btn-outline" style="padding: 6px 12px; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px; border-radius:5px; border-color: rgba(255,255,255,0.1);">
                Restart 🔁
              </button>
            </div>
          </div>

          <!-- Bottom Control Arrow bar -->
          <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px; margin-top: 25px;">
            <button class="partnership-arrow-btn" onclick="offsetPartnershipSlide(-1)" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 6px 12px; border-radius: 6px; color: var(--text-light); text-transform: uppercase; font-size: 0.68rem; letter-spacing:0.05em; font-family: var(--font-mono); cursor: pointer; display: flex; align-items: center; gap: 4px; font-weight:bold; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
              ◀ Prev
            </button>
            
            <div style="display:flex; gap:5px;">
              <?php for($i = 1; $i <= 7; $i++): ?>
              <span class="slide-indicator-dot <?php echo $i === 1 ? 'active-dot' : ''; ?>" onclick="setPartnershipSlide(<?php echo $i; ?>)"></span>
              <?php endfor; ?>
            </div>

            <button class="partnership-arrow-btn" onclick="offsetPartnershipSlide(1)" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 6px 12px; border-radius: 6px; color: var(--text-light); text-transform: uppercase; font-size: 0.68rem; letter-spacing:0.05em; font-family: var(--font-mono); cursor: pointer; display: flex; align-items: center; gap: 4px; font-weight:bold; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
              Next ▶
            </button>
          </div>

        </div>

      </div>

    </div>
  </section>

  <!-- Scoped Styles & Interactivity Handlers -->
  <style>
    /* Scoped styling classes to isolate slide deck */
    .partnership-tab-btn {
      transition: all 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .partnership-tab-btn:hover {
      background: rgba(212, 175, 55, 0.04) !important;
      border-color: rgba(212, 175, 55, 0.3) !important;
    }
    .partnership-tab-btn.active-tab {
      background: rgba(212, 175, 55, 0.07) !important;
      border-color: var(--secondary) !important;
      box-shadow: 0 4px 15px rgba(212, 175, 55, 0.05);
    }
    .partnership-tab-btn.active-tab .tab-badge {
      background: var(--secondary) !important;
      color: var(--bg-dark) !important;
      border-color: var(--secondary) !important;
    }
    .partnership-tab-btn.active-tab h4 {
      color: var(--secondary) !important;
    }
    
    .slide-indicator-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255,255,255,0.15);
      cursor: pointer;
      transition: all 0.2s;
    }
    .slide-indicator-dot:hover {
      background: rgba(255,255,255,0.4);
    }
    .slide-indicator-dot.active-dot {
      background: var(--secondary);
      box-shadow: 0 0 8px var(--secondary);
      width: 18px;
      border-radius: 6px;
    }

    /* Ad slots visual interactive indications */
    .ad-mock-slot {
      cursor: pointer;
      transition: all 0.2s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
    }
    .ad-mock-slot:hover {
      transform: translateY(-2px);
    }
    .ad-mock-slot.hover-neon-green:hover {
      background: rgba(212, 175, 55, 0.07) !important;
      border-color: var(--secondary) !important;
      box-shadow: 0 0 12px rgba(212, 175, 55, 0.2);
    }
    .ad-mock-slot.hover-neon-blue:hover {
      background: rgba(50, 133, 199, 0.07) !important;
      border-color: var(--accent-blue) !important;
      box-shadow: 0 0 12px rgba(50, 133, 199, 0.2);
    }
    .ad-mock-slot.hover-neon-red:hover {
      background: rgba(209, 61, 82, 0.20) !important;
      border-color: #ff334b !important;
      box-shadow: 0 0 12px rgba(209, 61, 82, 0.25);
    }
    .ad-mock-slot.hover-neon-purple:hover {
      background: rgba(144, 85, 188, 0.07) !important;
      border-color: var(--accent-purple) !important;
      box-shadow: 0 0 12px rgba(144, 85, 188, 0.2);
    }

    @media (max-width: 900px) {
      .partnership-layout {
        flex-direction: column;
      }
      .partnership-nav-col {
        flex: 1 1 auto !important;
        width: 100% !important;
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)) !important;
        gap: 10px !important;
      }
    }
  </style>

  <script>
    // Keep track of the active slide index
    let currentPartnershipSlide = 1;
    const totalPartnershipSlides = 7;

    function setPartnershipSlide(index) {
      if (index < 1 || index > totalPartnershipSlides) return;
      currentPartnershipSlide = index;

      // Hide all slides
      const slides = document.querySelectorAll('.partner-slide-element');
      slides.forEach(slide => {
        slide.style.display = 'none';
      });

      // Show active slide
      const activeSlide = document.getElementById('partner-slide-' + index);
      if (activeSlide) {
        activeSlide.style.display = 'block';
      }

      // Update tab active classes
      const tabs = document.querySelectorAll('.partnership-tab-btn');
      tabs.forEach(tab => {
        tab.classList.remove('active-tab');
        
        // Reset non-active badge styles
        const badge = tab.querySelector('.tab-badge');
        if (badge) {
          badge.style.background = 'rgba(255,255,255,0.05)';
          badge.style.color = 'var(--text-light)';
          badge.style.borderColor = 'rgba(255,255,255,0.1)';
        }
        const title = tab.querySelector('.tab-title');
        if (title) title.style.color = 'var(--text-light)';
      });

      const activeTab = document.getElementById('partner-tab-' + index);
      if (activeTab) {
        activeTab.classList.add('active-tab');
        const activeBadge = activeTab.querySelector('.tab-badge');
        if (activeBadge) {
          activeBadge.style.background = 'var(--secondary)';
          activeBadge.style.color = 'var(--bg-dark)';
          activeBadge.style.borderColor = 'var(--secondary)';
        }
        const activeTitle = activeTab.querySelector('.tab-title');
        if (activeTitle) activeTitle.style.color = 'var(--secondary)';
      }

      // Update navigation dots
      const dots = document.querySelectorAll('.slide-indicator-dot');
      dots.forEach(dot => {
        dot.classList.remove('active-dot');
      });
      const activeDot = dots[index - 1];
      if (activeDot) {
        activeDot.classList.add('active-dot');
      }
    }

    function offsetPartnershipSlide(offset) {
      let targetIndex = currentPartnershipSlide + offset;
      if (targetIndex < 1) targetIndex = totalPartnershipSlides;
      if (targetIndex > totalPartnershipSlides) targetIndex = 1;
      setPartnershipSlide(targetIndex);
    }
  </script>

  <!-- Section 4: Gurbani Quote Section (Integrated) -->
  <section class="gurbani-quote-section scroll-reveal" style="background-color: var(--bg-shade-5); padding: 80px 0;">
    <div class="gurbani-quote-container">
      <div class="gurbani-ornament">✧ ✦ ✧</div>
      <div class="gurbani-gurmukhi">ਸੇਵਾ ਕਰਤ ਹੋਇ ਨਿਹਕਾਮੀ ॥ ਤਿਸ ਕੋ ਹੋਤ ਪਰਾਪਤਿ ਸੁਆਮੀ ॥</div>
      <div class="gurbani-translit">seva karat hoi nihkaamee || tis ko hot paraapat suaamee ||</div>
      <div class="gurbani-english">"One who performs selfless service without thought of reward, shall attain the Lord Master."</div>
      <div class="gurbani-source">Sri Guru Granth Sahib Ji — Ang 286</div>
    </div>
  </section>

</div>

<!-- Modal Element for Detail Popups -->
<div class="project-modal" id="projectModal" onclick="closeDetailsModal(event)">
  <div class="project-modal-content" onclick="event.stopPropagation()">
    <div class="modal-header">
      <div>
        <span class="card-category-lbl" id="modalCategory">Category</span>
        <h3 class="spotlight-title" id="modalTitle" style="font-size: 1.6rem; margin-bottom: 0;">Project Title</h3>
      </div>
      <button class="modal-close-btn" onclick="closeDetailsModal(null)">&times;</button>
    </div>
    <div class="modal-body">
      <!-- Dynamic modal image alignment -->
      <div id="modalImageWrap" style="border-radius: 12px; overflow: hidden; margin-bottom: 25px; border: 1px solid rgba(255, 255, 255, 0.08); display: none;">
        <img id="modalImage" src="" alt="Campaign" style="width: 100%; height: 260px; object-fit: cover;" />
      </div>
      <p id="modalDesc1">Detailed description paragraph 1.</p>
      <p id="modalDesc2">Detailed description paragraph 2.</p>
      
      <div class="modal-meta-box">
        <h4 style="color: var(--secondary); font-size: 0.95rem; margin-bottom: 15px; text-transform: uppercase; font-family: var(--font-sans); letter-spacing: 0.05em;">Project Metadata & Integrity Info:</h4>
        <div class="modal-meta-item">
          <span class="lbl">Sikh Principles Basis</span>
          <span class="val" id="modalBasis">Vand Chhako (Sharing) & Sarbat da Bhala</span>
        </div>
        <div class="modal-meta-item">
          <span class="lbl">Relief Tracking Auditing</span>
          <span class="val">100% Inspected & Direct Distribution</span>
        </div>
        <div class="modal-meta-item">
          <span class="lbl">Funding Source</span>
          <span class="val" id="modalFunding">Dasvandh & Public Charitable Donations</span>
        </div>
      </div>

      <div style="display: flex; gap: 15px; margin-top: 30px;">
        <button class="btn" style="background: var(--accent-red); color: var(--primary); font-size: 0.9rem;" onclick="closeAndOpenDonation()">
          Support This Seva Project
        </button>
        <button class="btn-outline" style="font-size: 0.9rem;" onclick="closeDetailsModal(null)">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Category filtering and Modal script -->
<script>
  // Complete client-side filtering script
  document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const bentoCards = document.querySelectorAll('#bentoGrid .bento-card');
    const emptyMsg = document.getElementById('emptyCategoryMsg');

    filterButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        // Toggle active status
        filterButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filterValue = btn.getAttribute('data-filter');
        let displayedCount = 0;

        bentoCards.forEach(card => {
          const cardCat = card.getAttribute('data-cat');
          if (filterValue === 'all' || cardCat === filterValue) {
            card.classList.remove('hidden');
            displayedCount++;
          } else {
            card.classList.add('hidden');
          }
        });

        // Toggle Empty Category message
        if (displayedCount === 0) {
          emptyMsg.style.display = 'block';
        } else {
          emptyMsg.style.display = 'none';
        }
      });
    });
  });

  // Modal dataset for different projects
  const projectDetails = {
    charity: {
      category: "General Charity & Support",
      title: "Charitable Rehabilitation Seva",
      image: "<?php echo esc_url( get_theme_mod( 'tatkhalsa_charity_support_img', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&auto=format&fit=crop' ) ); ?>",
      desc1: "At Tatkhalsa, we strictly believe in raising up families in sustained difficulty through targeted rehabilitation. Rather than giving just a transient token of help, we analyze long-term constraints. This includes helping underprivileged widows achieve self-employment by sponsoring sewing equipment, or setting up small community stores.",
      desc2: "Furthermore, during hostile extreme winter nights and summer heatwaves, our active teams travel directly into remote areas and urban pockets distributing thousands of premium quality warm blankets and water/electrolyte kits to those sleeping on pavements and underprotected huts.",
      basis: "Daya (Compassion), Vand Chhako, and Sarbat da Bhala",
      funding: "100% Transparent Citizen Contributions"
    },
    blood: {
      category: "Healthcare Support",
      title: "Blood On Call",
      image: "<?php echo esc_url( get_theme_mod( 'tatkhalsa_blood_contribution_img', 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?q=80&w=800&auto=format&fit=crop' ) ); ?>",
      desc1: "When immediate urgent health crises occur, finding the exact blood type becomes a frantic race against the clock. Our Tatkhalsa Blood On Call service lists vetted, registered, non-commercial donors across major cities who stand fully mobilized to travel immediately to save a patient.",
      desc2: "We maintain close regular audits of patients in need to prevent exploitation, while regularly hosting community blood collection drives in coordination with municipal hospitals and approved medical practitioners.",
      basis: "Tan-Man-Dhan Seva (Physical & Spiritual service)",
      funding: "Direct Local Donations & Corporate Volunteers"
    },
    disaster: {
      category: "Emergency Response",
      title: "Disaster Rapid Response Squad",
      image: "<?php echo esc_url( get_theme_mod( 'tatkhalsa_punjab_flood_img', 'https://images.unsplash.com/photo-1547683905-f686c993aae5?q=80&w=800&auto=format&fit=crop' ) ); ?>",
      desc1: "Our trained local response team is structured for peak speed and efficiency. Ready to act synchronously during seasonal floods, earthquakes, and emergency calamities, the team moves swiftly with essential rescue assets, inflatables, and temporary food storage setups.",
      desc2: "By coordinating directly with public officials, we bypass bureaucratic gaps to immediately establish functional Langars, field healthcare cabins, and supply lines to safely feed, house, and protect local residents.",
      basis: "Nirbhau Nirvair (Without Fear, Without Hatred)",
      funding: "Emergency Reserve Fund & Rapid CSR Sponsorships"
    },
    heritage: {
      category: "Preservation Board",
      title: "Sikh History & Heritage Board",
      image: "<?php echo esc_url( get_theme_mod( 'tatkhalsa_sikh_heritage_img', 'https://upload.wikimedia.org/wikipedia/commons/e/ee/Group_of_Nihang_Singhs.jpg' ) ); ?>",
      desc1: "Preserving ancient Sikh values, original historical documents, and correct theological analysis requires active and continuous scholarly oversight. Our Review Board consists of respected academic consultants, language experts, and research historians working in tandem.",
      desc2: "We digitize fragile, ancient Gurmukhi scripts, translate historical manuscripts into correct modern English, and correct any distorted mainstream narratives to keep the brilliant, inspiring historical legacy of the Sikh panth authentic and universally accessible.",
      basis: "Dharam de Rakha (Preservation of Righteousness)",
      funding: "Dedicated Educational Endowments & Sponsorships"
    },
    youth: {
      category: "Development Programs",
      title: "Kabaddi, Gatka & Youth Athletic Clubs",
      image: "<?php echo esc_url( get_theme_mod( 'tatkhalsa_kabaddi_athletic_img', 'https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=800&auto=format&fit=crop' ) ); ?>",
      desc1: "With modern digital distractions and the devastating rise of substance abuse, we engage and vitalize local youth through robust traditional athletic sports. By creating well-equipped local wrestling arenas (Alkhadas), Gatka training centers, and sports fields, we channel youthful energy productively.",
      desc2: "We organize annual grand level sports championships, giving rewarding awards and encouraging healthy eating habits, clean living, and absolute mental toughness aligned with top moral disciplines.",
      basis: "Charhdi Kala (Dynamic high spirits and positivity)",
      funding: "Youth Development Grants & Community Donors"
    },
    eco: {
      category: "Community Welfare",
      title: "Eco-Sikh Environmental Stewardship",
      image: "<?php echo esc_url( get_theme_mod( 'tatkhalsa_tree_planting_img', 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=800&auto=format&fit=crop' ) ); ?>",
      desc1: "The eternal teachings of Guru Nanak Dev Ji state 'Air as Guru, Water as Father, and the Earth as Great Mother'. Our Eco-Sikh stewardship operates specifically to restore and respect soil health and pure resource access.",
      desc2: "Our campaigns plant massive indigenous trees across degraded school grounds and sterile village edges, while implementing durable micro-reverse-osmosis clean water filtration systems in central public zones deficient of clean water resources.",
      basis: "Sarbat Da Bhala (Universal Peace & Well-being)",
      funding: "Green Community Grants & Individual Sponsors"
    }
  };

  // Open detail modal filled with specific data
  function openDetailsModal(projectId) {
    const data = projectDetails[projectId];
    if (!data) return;

    document.getElementById('modalCategory').textContent = data.category;
    document.getElementById('modalTitle').textContent = data.title;
    document.getElementById('modalDesc1').textContent = data.desc1;
    document.getElementById('modalDesc2').textContent = data.desc2;
    document.getElementById('modalBasis').textContent = data.basis;
    document.getElementById('modalFunding').textContent = data.funding;

    const modalImgWrap = document.getElementById('modalImageWrap');
    if (data.image) {
      const modalImg = document.getElementById('modalImage');
      modalImg.src = data.image;
      modalImg.alt = data.title;
      modalImgWrap.style.display = 'block';
    } else {
      modalImgWrap.style.display = 'none';
    }

    document.getElementById('projectModal').classList.add('active');
    document.body.style.overflow = 'hidden'; // Lock background scroll
  }

  // Close details modal
  function closeDetailsModal(event) {
    // If event is null, it's called by close button click
    if (event === null || event.target.id === 'projectModal' || event.target.className === 'modal-close-btn') {
      document.getElementById('projectModal').classList.remove('active');
      document.body.style.overflow = ''; // Restore background scroll
    }
  }

  // Close modal and reveal main donation modal from footer
  function closeAndOpenDonation() {
    closeDetailsModal(null);
    // Call the global openModal function that controls the donation popup in footer.php
    if (typeof openModal === 'function') {
      setTimeout(() => {
        openModal();
      }, 350);
    }
  }
</script>

<?php
get_footer();
