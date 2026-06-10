<?php
/**
 * Template Name: Projects Page
 *
 * @package TatkhalsaTheme
 */

get_header();
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
  
  <section class="hero" style="padding: 40px 0 35px 0; background: linear-gradient(135deg, rgba(8, 17, 35, 0.96) 0%, rgba(5, 10, 20, 0.99) 100%);">
    <div class="container scroll-reveal" style="text-align: center">
      <!-- Centered Logo same as home page -->
      <div class="hero-logo-wrapper" style="display: flex; justify-content: center; margin-bottom: 25px; margin-top: 10px;">
        <img
          src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>"
          alt="Tatkhalsa Foundation Logo"
          class="hero-gurbani-logo"
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
              <h3 class="card-title">Sikh Blood Contribution Network</h3>
              <div class="card-icon-sphere">❤️</div>
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
      desc1: "At Tatkhalsa, we strictly believe in raising up families in sustained difficulty through targeted rehabilitation. Rather than giving just a transient token of help, we analyze long-term constraints. This includes helping underprivileged widows achieve self-employment by sponsoring sewing equipment, or setting up small community stores.",
      desc2: "Furthermore, during hostile extreme winter nights and summer heatwaves, our active teams travel directly into remote areas and urban pockets distributing thousands of premium quality warm blankets and water/electrolyte kits to those sleeping on pavements and underprotected huts.",
      basis: "Daya (Compassion), Vand Chhako, and Sarbat da Bhala",
      funding: "100% Transparent Citizen Contributions"
    },
    blood: {
      category: "Healthcare Support",
      title: "Sikh Blood Contribution Network",
      desc1: "When immediate urgent health crises occur, finding the exact blood type becomes a frantic race against the clock. Our Tatkhalsa Sikh Blood network lists vetted, registered, non-commercial donors across major cities who stand fully mobilized to travel immediately to save a patient.",
      desc2: "We maintain close regular audits of patients in need to prevent exploitation, while regularly hosting community blood collection drives in coordination with municipal hospitals and approved medical practitioners.",
      basis: "Tan-Man-Dhan Seva (Physical & Spiritual service)",
      funding: "Direct Local Donations & Corporate Volunteers"
    },
    disaster: {
      category: "Emergency Response",
      title: "Disaster Rapid Response Squad",
      desc1: "Our trained local response team is structured for peak speed and efficiency. Ready to act synchronously during seasonal floods, earthquakes, and emergency calamities, the team moves swiftly with essential rescue assets, inflatables, and temporary food storage setups.",
      desc2: "By coordinating directly with public officials, we bypass bureaucratic gaps to immediately establish functional Langars, field healthcare cabins, and supply lines to safely feed, house, and protect local residents.",
      basis: "Nirbhau Nirvair (Without Fear, Without Hatred)",
      funding: "Emergency Reserve Fund & Rapid CSR Sponsorships"
    },
    heritage: {
      category: "Preservation Board",
      title: "Sikh History & Heritage Board",
      desc1: "Preserving ancient Sikh values, original historical documents, and correct theological analysis requires active and continuous scholarly oversight. Our Review Board consists of respected academic consultants, language experts, and research historians working in tandem.",
      desc2: "We digitize fragile, ancient Gurmukhi scripts, translate historical manuscripts into correct modern English, and correct any distorted mainstream narratives to keep the brilliant, inspiring historical legacy of the Sikh panth authentic and universally accessible.",
      basis: "Dharam de Rakha (Preservation of Righteousness)",
      funding: "Dedicated Educational Endowments & Sponsorships"
    },
    youth: {
      category: "Development Programs",
      title: "Kabaddi, Gatka & Youth Athletic Clubs",
      desc1: "With modern digital distractions and the devastating rise of substance abuse, we engage and vitalize local youth through robust traditional athletic sports. By creating well-equipped local wrestling arenas (Alkhadas), Gatka training centers, and sports fields, we channel youthful energy productively.",
      desc2: "We organize annual grand level sports championships, giving rewarding awards and encouraging healthy eating habits, clean living, and absolute mental toughness aligned with top moral disciplines.",
      basis: "Charhdi Kala (Dynamic high spirits and positivity)",
      funding: "Youth Development Grants & Community Donors"
    },
    eco: {
      category: "Community Welfare",
      title: "Eco-Sikh Environmental Stewardship",
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
