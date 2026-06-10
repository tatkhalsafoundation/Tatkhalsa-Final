<?php
/**
 * Template Name: Blog Page
 *
 * @package TatkhalsaTheme
 */

get_header();
?>

<style>
  .blogs-page {
    background-color: var(--bg-shade-2);
    font-family: var(--font-sans);
    color: var(--primary);
    position: relative;
    padding-bottom: 80px;
    min-height: 100vh;
  }

  .blogs-grid-container {
    padding: 40px 0 60px 0;
    position: relative;
    z-index: 2;
  }

  /* Filter Styling */
  .blogs-filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 40px;
    background: rgba(12, 25, 51, 0.4);
    border: 1px solid rgba(212, 175, 55, 0.15);
    padding: 20px 30px;
    border-radius: 12px;
  }

  .blogs-categories {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .blog-category-btn {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-light);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .blog-category-btn:hover, .blog-category-btn.active {
    background: var(--secondary);
    color: var(--primary);
    border-color: var(--secondary);
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.25);
  }

  .blogs-search-wrapper {
    position: relative;
    width: 320px;
    max-width: 100%;
  }

  .blogs-search-input {
    width: 100%;
    background: rgba(5, 10, 21, 0.6);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 20px;
    padding: 10px 20px 10px 45px;
    color: var(--cream);
    font-size: 0.9rem;
    transition: all 0.3s ease;
  }

  .blogs-search-input:focus {
    outline: none;
    border-color: var(--secondary);
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
    background: rgba(5, 10, 21, 0.82);
  }

  .blogs-search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary);
    pointer-events: none;
  }

  /* Grid of Blog Cards */
  .blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
  }

  @media (max-width: 1024px) {
    .blog-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .blog-grid {
      grid-template-columns: 1fr;
    }
    .blogs-filter-bar {
      flex-direction: column;
      align-items: stretch;
    }
    .blogs-search-wrapper {
      width: 100%;
    }
  }

  .blog-card {
    background: linear-gradient(135deg, var(--bg-light) 0%, rgba(12, 25, 51, 0.7) 100%);
    border: 1px solid rgba(212, 175, 55, 0.15);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    opacity: 1;
    transform: translateY(0);
  }

  .blog-card:hover {
    transform: translateY(-8px);
    border-color: var(--secondary);
    box-shadow: 0 15px 35px rgba(212, 175, 55, 0.15);
  }

  .blog-card-img-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #050a15;
  }

  .blog-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .blog-card:hover .blog-card-img {
    transform: scale(1.08);
  }

  .blog-card-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(5,10,21,0.6) 0%, transparent 100%);
    pointer-events: none;
  }

  .blog-card-tag {
    position: absolute;
    top: 15px;
    left: 15px;
    background: var(--primary);
    color: var(--secondary);
    border: 1px solid rgba(212, 175, 55, 0.4);
    font-size: 0.72rem;
    font-family: var(--font-mono);
    text-transform: uppercase;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 4px;
    letter-spacing: 0.05em;
  }

  .blog-card-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }

  .blog-card-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 0.8rem;
    color: var(--text-light);
    font-family: var(--font-mono);
    margin-bottom: 12px;
  }

  .blog-card-meta-dot {
    width: 4px;
    height: 4px;
    background-color: var(--secondary);
    border-radius: 50%;
  }

  .blog-card-title {
    font-family: var(--font-serif);
    font-size: 1.35rem;
    color: var(--cream);
    margin: 0 0 12px 0;
    line-height: 1.3;
    transition: color 0.3s ease;
  }

  .blog-card:hover .blog-card-title {
    color: var(--secondary);
  }

  .blog-card-excerpt {
    color: var(--text-light);
    font-size: 0.92rem;
    line-height: 1.6;
    margin-bottom: 25px;
    flex-grow: 1;
  }

  .blog-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    padding-top: 15px;
    margin-top: auto;
  }

  .blog-author-block {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .blog-author-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1px solid rgba(212, 175, 55, 0.3);
    object-fit: cover;
  }

  .blog-author-name {
    font-size: 0.8rem;
    color: var(--cream);
    font-weight: 500;
  }

  .blog-read-more-btn {
    color: var(--secondary);
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
    transition: gap 0.3s ease;
  }

  .blog-read-more-btn:hover {
    gap: 10px;
  }

  /* No Results Mode */
  .blog-no-results {
    text-align: center;
    padding: 60px 20px;
    background: rgba(12, 25, 51, 0.4);
    border: 1px solid rgba(212, 175, 55, 0.15);
    border-radius: 12px;
    display: none;
  }

  .blog-no-results svg {
    margin-bottom: 15px;
    color: var(--secondary);
  }

  .blog-no-results h3 {
    font-family: var(--font-serif);
    color: var(--cream);
    font-size: 1.5rem;
    margin-bottom: 10px;
  }

  .blog-no-results p {
    color: var(--text-light);
    max-width: 400px;
    margin: 0 auto;
  }

  /* Interactive Modal System */
  .blog-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(5, 10, 21, 0.85);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.4s ease;
  }

  .blog-modal-backdrop.open {
    opacity: 1;
    pointer-events: auto;
  }

  .blog-modal-box {
    background: linear-gradient(135deg, var(--bg-light) 0%, rgba(5, 10, 21, 0.98) 100%);
    border: 1.5px solid rgba(212, 175, 55, 0.3);
    border-radius: 16px;
    width: 100%;
    max-width: 800px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 40px rgba(212,175,55,0.08);
    position: relative;
    transform: scale(0.9) translateY(20px);
    transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .blog-modal-backdrop.open .blog-modal-box {
    transform: scale(1) translateY(0);
  }

  .blog-modal-close-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(5, 10, 21, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: var(--cream);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: all 0.3s ease;
  }

  .blog-modal-close-btn:hover {
    background: rgba(212, 175, 55, 0.2);
    border-color: var(--secondary);
    color: var(--secondary);
  }

  .blog-modal-header-img {
    height: 320px;
    width: 100%;
    object-fit: cover;
    border-bottom: 2px solid rgba(212,175,55,0.2);
  }

  .blog-modal-body {
    padding: 40px;
  }

  @media (max-width: 640px) {
    .blog-modal-body {
      padding: 24px;
    }
    .blog-modal-header-img {
      height: 200px;
    }
  }

  .blog-modal-tag {
    background: rgba(212, 175, 55, 0.12);
    color: var(--secondary);
    border: 1px solid rgba(12, 25, 51, 0.3);
    font-size: 0.75rem;
    font-family: var(--font-mono);
    text-transform: uppercase;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 4px;
    display: inline-block;
    margin-bottom: 15px;
    letter-spacing: 0.05em;
  }

  .blog-modal-title {
    font-family: var(--font-serif);
    font-size: 2.2rem;
    color: var(--cream);
    margin: 0 0 15px 0;
    line-height: 1.25;
  }

  @media (max-width: 640px) {
    .blog-modal-title {
      font-size: 1.6rem;
    }
  }

  .blog-modal-meta-row {
    display: flex;
    align-items: center;
    gap: 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 20px;
    margin-bottom: 25px;
  }

  .blog-modal-text {
    color: var(--text-light);
    font-size: 1.05rem;
    line-height: 1.8;
  }

  .blog-modal-text h3 {
    font-family: var(--font-serif);
    color: var(--cream);
    font-size: 1.5rem;
    margin: 30px 0 15px 0;
  }

  .blog-modal-text h4 {
    font-family: var(--font-serif);
    color: var(--secondary);
    font-size: 1.2rem;
    margin: 25px 0 10px 0;
  }

  .blog-modal-text p {
    margin-bottom: 20px;
  }

  .blog-modal-text ol, .blog-modal-text ul {
    margin-left: 20px;
    margin-bottom: 20px;
  }

  .blog-modal-text li {
    margin-bottom: 8px;
  }

  .blog-modal-text strong {
    color: var(--secondary);
  }
</style>

<div class="blogs-page">
  <!-- Page Hero Header -->
  <section
    class="hero"
    style="
      padding: 40px 0 60px 0;
      background: linear-gradient(
        135deg,
        rgba(10, 20, 40, 0.95),
        rgba(5, 10, 20, 0.98)
      );
    "
  >
    <div class="hero-overlay" style="background: none"></div>
    <div
      class="container scroll-reveal"
      style="position: relative; z-index: 2; text-align: center"
    >
      <!-- Centered Logo supporting scroll-driven merge -->
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
      <h1 style="font-size: 3rem; color: var(--cream); margin-bottom: 20px">
        Tatkhalsa Insights & Blog
      </h1>
      <div
        style="
          width: 60px;
          height: 3px;
          background: var(--secondary);
          margin: 0 auto;
          margin-bottom: 12px;
        "
      ></div>
      <p style="color: var(--text-light); max-width: 600px; margin: 0 auto; font-size: 1.05rem;">
        Keep updated with our latest Seva projects, historical research, and spiritual reflections from Punjab.
      </p>
    </div>
  </section>

  <!-- Main Blogs Container -->
  <div class="container blogs-grid-container">
    
    <!-- Interactive Search and Category Filters -->
    <div class="blogs-filter-bar scroll-reveal">
      <div class="blogs-categories">
        <button class="blog-category-btn active" data-category="all">All Seva</button>
        <button class="blog-category-btn" data-category="Philosophy">Philosophy</button>
        <button class="blog-category-btn" data-category="Relief Mission">Relief SOS</button>
        <button class="blog-category-btn" data-category="Heritage">Heritage</button>
        <button class="blog-category-btn" data-category="Environment">Environment</button>
      </div>

      <div class="blogs-search-wrapper">
        <span class="blogs-search-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </span>
        <input 
          type="text" 
          id="blogsSearch" 
          class="blogs-search-input" 
          placeholder="Search articles & stories..." 
          aria-label="Search articles"
        />
      </div>
    </div>

    <!-- Blogs List Grid -->
    <div class="blog-grid scroll-reveal" id="blogsGrid">
      
      <!-- Article 1 -->
      <article 
        class="blog-card" 
        data-id="seeded_1"
        data-category="Philosophy"
        data-title="the essence of seva: selfless service as a spiritual path"
        data-excerpt="explore the deep spiritual roots of seva in the sikh tradition and how it acts as a powerful beacon for modern-day community transformation and selflessness."
      >
        <div class="blog-card-img-wrapper">
          <img 
            class="blog-card-img" 
            src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_blog_img_1', 'https://images.unsplash.com/photo-1544027993-37dbfe43562a?auto=format&fit=crop&q=80&w=600' ) ); ?>" 
            alt="The Essence of Seva: Selfless Service as a Spiritual Path" 
            loading="lazy" 
          />
          <div class="blog-card-image-overlay"></div>
          <span class="blog-card-tag">Philosophy</span>
        </div>

        <div class="blog-card-content">
          <div class="blog-card-meta">
            <span>Jun 08, 2026</span>
            <span class="blog-card-meta-dot"></span>
            <span>5 min read</span>
          </div>

          <h2 class="blog-card-title">The Essence of Seva: Selfless Service as a Spiritual Path</h2>
          <p class="blog-card-excerpt">Explore the deep spiritual roots of Seva in the Sikh tradition and how it acts as a powerful beacon for modern-day community transformation and selflessness.</p>

          <div class="blog-card-footer">
            <div class="blog-author-block">
              <img 
                class="blog-author-avatar" 
                src="https://secure.gravatar.com/avatar/fb000000000000000000000000000001?s=96&d=mp" 
                alt="Bhai Jagjit Singh" 
              />
              <span class="blog-author-name">Bhai Jagjit Singh</span>
            </div>

            <button class="blog-read-more-btn" onclick="openBlogModal('seeded_1')">
              Read More
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>
      </article>

      <!-- Article 2 -->
      <article 
        class="blog-card" 
        data-id="seeded_2"
        data-category="Relief Mission"
        data-title="flood response diaries: restoring hope in inundated villages"
        data-excerpt="go inside tatkhalsa’s continuous flood relief operations in punjab. learn how emergency relief, portable water filtration kits, and medical aid saved lives."
      >
        <div class="blog-card-img-wrapper">
          <img 
            class="blog-card-img" 
            src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_blog_img_2', 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=600' ) ); ?>" 
            alt="Flood Response Diaries: Restoring Hope in Inundated Villages" 
            loading="lazy" 
          />
          <div class="blog-card-image-overlay"></div>
          <span class="blog-card-tag">Relief Mission</span>
        </div>

        <div class="blog-card-content">
          <div class="blog-card-meta">
            <span>May 25, 2026</span>
            <span class="blog-card-meta-dot"></span>
            <span>7 min read</span>
          </div>

          <h2 class="blog-card-title">Flood Response Diaries: Restoring Hope in Inundated Villages</h2>
          <p class="blog-card-excerpt">Go inside Tatkhalsa’s continuous flood relief operations in Punjab. Learn how emergency relief, portable water filtration kits, and medical aid saved lives.</p>

          <div class="blog-card-footer">
            <div class="blog-author-block">
              <img 
                class="blog-author-avatar" 
                src="https://secure.gravatar.com/avatar/fb000000000000000000000000000002?s=96&d=mp" 
                alt="Bibi Harpreet Kaur" 
              />
              <span class="blog-author-name">Bibi Harpreet Kaur</span>
            </div>

            <button class="blog-read-more-btn" onclick="openBlogModal('seeded_2')">
              Read More
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>
      </article>

      <!-- Article 3 -->
      <article 
        class="blog-card" 
        data-id="seeded_3"
        data-category="Heritage"
        data-title="preserving sikh heritage: digitizing silent ancient manuscripts"
        data-excerpt="how tatkhalsa is leveraging non-destructive high-definition imaging techniques to archive rare gurmukhi leaf scripts and preserve historical sikh history."
      >
        <div class="blog-card-img-wrapper">
          <img 
            class="blog-card-img" 
            src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_blog_img_3', 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=600' ) ); ?>" 
            alt="Preserving Sikh Heritage: Digitizing Silent Ancient Manuscripts" 
            loading="lazy" 
          />
          <div class="blog-card-image-overlay"></div>
          <span class="blog-card-tag">Heritage</span>
        </div>

        <div class="blog-card-content">
          <div class="blog-card-meta">
            <span>Apr 12, 2026</span>
            <span class="blog-card-meta-dot"></span>
            <span>6 min read</span>
          </div>

          <h2 class="blog-card-title">Preserving Sikh Heritage: Digitizing Ancient Manuscripts</h2>
          <p class="blog-card-excerpt">How Tatkhalsa is leveraging non-destructive high-definition imaging techniques to archive rare Gurmukhi leaf scripts and preserve historical Sikh history.</p>

          <div class="blog-card-footer">
            <div class="blog-author-block">
              <img 
                class="blog-author-avatar" 
                src="https://secure.gravatar.com/avatar/fb000000000000000000000000000003?s=96&d=mp" 
                alt="Dr. Gurmukh Singh" 
              />
              <span class="blog-author-name">Dr. Gurmukh Singh</span>
            </div>

            <button class="blog-read-more-btn" onclick="openBlogModal('seeded_3')">
              Read More
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>
      </article>

      <!-- Article 4 -->
      <article 
        class="blog-card" 
        data-id="seeded_4"
        data-category="Environment"
        data-title="nurturing our mother earth: replanting green canopies"
        data-excerpt="air is the guru, water is the father, earth is the great mother. discover tatkhalsa’s collaborative local tree-plantation drives to heal ecosystems."
      >
        <div class="blog-card-img-wrapper">
          <img 
            class="blog-card-img" 
            src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_blog_img_4', 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=600' ) ); ?>" 
            alt="Nurturing Our Mother Earth: Replanting Green Canopies" 
            loading="lazy" 
          />
          <div class="blog-card-image-overlay"></div>
          <span class="blog-card-tag">Environment</span>
        </div>

        <div class="blog-card-content">
          <div class="blog-card-meta">
            <span>Mar 22, 2026</span>
            <span class="blog-card-meta-dot"></span>
            <span>4 min read</span>
          </div>

          <h2 class="blog-card-title">Nurturing Our Mother Earth: Replanting Green Canopies</h2>
          <p class="blog-card-excerpt">Air is the Guru, Water is the Father, Earth is the Great Mother. Discover Tatkhalsa’s collaborative local tree-plantation drives to heal ecosystems.</p>

          <div class="blog-card-footer">
            <div class="blog-author-block">
              <img 
                class="blog-author-avatar" 
                src="https://secure.gravatar.com/avatar/fb000000000000000000000000000004?s=96&d=mp" 
                alt="Prof. Balwinder Singh" 
              />
              <span class="blog-author-name">Prof. Balwinder Singh</span>
            </div>

            <button class="blog-read-more-btn" onclick="openBlogModal('seeded_4')">
              Read More
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
          </div>
        </div>
      </article>

    </div>

    <!-- Elegant Empty State -->
    <div class="blog-no-results" id="blogsNoResults">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 15px auto;">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        <line x1="8" y1="11" x2="14" y2="11"></line>
      </svg>
      <h3>No Articles Found</h3>
      <p>We couldn't find any articles matching your search query. Try setting the filter to "All Seva" or typing a different keyword.</p>
    </div>

  </div>

  <!-- Inspiratory Gurbani Section -->
  <section class="gurbani-quote-section scroll-reveal" style="background: rgba(12, 25, 51, 0.45); border-top: 1px solid rgba(212, 175, 55, 0.15);">
    <div class="container" style="text-align: center;">
      <div class="gurbani-verse" style="font-family: var(--font-serif); font-size: 1.8rem; color: var(--secondary); margin-bottom: 12px; line-height: 1.4;">
        ਵਿਚਿ ਦੁਨੀਆ ਸੇਵ ਕਮਾਈਐ ॥ ਤਾ ਦਰਗਹ ਬੈਸਣੁ ਪਾਈਐ ॥
      </div>
      <p style="color: var(--text-light); max-width: 700px; margin: 0 auto; font-style: italic; line-height: 1.6;">
        "In the midst of this world, perform Seva (selfless service), and you shall obtain a seat of honor in the Divine Court of the Creator."
      </p>
    </div>
  </section>
</div>

<!-- Blog Lightbox/Reader Modal Markup -->
<div class="blog-modal-backdrop" id="blogDetailModal" onclick="handleBackdropClick(event)">
  <div class="blog-modal-box">
    <button class="blog-modal-close-btn" onclick="closeBlogModal()" aria-label="Close article">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>
    
    <img id="modalImg" class="blog-modal-header-img" src="" alt="Blog Cover" />
    
    <div class="blog-modal-body">
      <span id="modalTag" class="blog-modal-tag">Philosophy</span>
      <h2 id="modalTitle" class="blog-modal-title">Blog Title</h2>
      
      <div class="blog-modal-meta-row">
        <div class="blog-author-block">
          <img id="modalAuthorAvatar" class="blog-author-avatar" src="" alt="Author Avatar" />
          <span id="modalAuthorName" class="blog-author-name" style="font-size: 0.9rem;">Author Name</span>
        </div>
        <div class="blog-card-meta-dot"></div>
        <span id="modalDate" style="font-size: 0.85rem; color: var(--text-light); font-family: var(--font-mono);">Date</span>
        <div class="blog-card-meta-dot"></div>
        <span id="modalReadTime" style="font-size: 0.85rem; color: var(--text-light); font-family: var(--font-mono);">Read Time</span>
      </div>
      
      <div id="modalContent" class="blog-modal-text">
        <!-- Dyn content populated by JS -->
      </div>
    </div>
  </div>
</div>

<script>
  // Complete JSON dataset of high-quality default blogs
  const blogsData = [
    {
      id: 'seeded_1',
      title: 'The Essence of Seva: Selfless Service as a Spiritual Path',
      excerpt: 'Explore the deep spiritual roots of Seva in the Sikh tradition and how it acts as a powerful beacon for modern-day community transformation and selflessness.',
      content: `<h3>Understanding the Spiritual bedrock of Seva</h3><p>In the heart of the Sikh ethos lies the eternal doctrine of <strong>Seva</strong> (selfless duty) — service done without any expectation of reward, validation, or personal gain. Guru Nanak Dev Ji, the first Sikh Guru, engineered this philosophy to dismantle pride (Ahamkar) and bring humanity together around one universal table.</p><h4>Langar: Servitude in Action</h4><p>The practice of Langar (the free community kitchen) serves as the ultimate expression of Seva and Equality. In our modern context, the Tatkhalsa Foundation strives to preserve this holy heritage. We organize free community kitchens and medical camps designed specifically for the underserved, carrying forward Guru Nanak Dev Ji’s eternal call of <em>Vand Chhako</em> (Share what you earn with the needy).</p><h4>Overcoming Ego to Find Divine Light</h4><p>Seva is not merely physical assistance; it is a profound spiritual cleansing. By engaging in active, humble service — whether by cleaning floors, cooking meals, distributing medicine, or preserving historical Gurmukhi transcripts — one humbles the self. In this state of quiet humility, the heart is primed to experience the Divine Light present inside all living creation.</p><p>We welcome you to join our active volunteer network. Whether you contribute half an hour of your time, some specialized medical skills, or financial sponsorships, you are actively facilitating real humanitarian transformation on the ground.</p>`,
      date: 'Jun 08, 2026',
      author: 'Bhai Jagjit Singh',
      avatar: 'https://secure.gravatar.com/avatar/fb000000000000000000000000000001?s=96&d=mp',
      category: 'Philosophy',
      read_time: '5 min read',
      image: '<?php echo esc_url( get_theme_mod( 'tatkhalsa_blog_img_1', 'https://images.unsplash.com/photo-1544027993-37dbfe43562a?auto=format&fit=crop&q=80&w=600' ) ); ?>'
    },
    {
      id: 'seeded_2',
      title: 'Flood Response Diaries: Restoring Hope in Inundated Villages',
      excerpt: 'Go inside Tatkhalsa’s continuous flood relief operations in Punjab. Learn how emergency relief, portable water filtration kits, and medical aid saved lives.',
      content: `<h3>On the Front Lines of Nature’s Fury</h3><p>When monsoons breached the embankments of Punjab’s primary river basins, entire villages in districts such as Ferozepur, Jalandhar, and Kapurthala lay submerged under feet of dangerous, muddy water. Crops were ruined, livestock lost, and families stranded in roof-high isolations. In this hour of darkness, the Tatkhalsa Foundation was mobilized instantly.</p><h4>The SOS Strategy: Rapid Intervention</h4><p>Our disaster action protocol was split into three critical phases:</p><ol><li><strong>Emergency Evacuation & Survival Rations:</strong> Deploying volunteer rescue inflatable boats and supplying high-density nutrient kits (wheat, pulses, oil, baby formula, and clean bottled water).</li><li><strong>Water Purification & Health Guards:</strong> Because biological contaminants pose the highest post-flood threat, we distributed over 1,500 portable water filtration kits and established 24-hour medical diagnostic tents on high grounds.</li><li><strong>Long-Term Rehabilitation:</strong> Rebuilding dry-walls, disinfecting wells, and restoring ruined local primary school structures to prepare communities for a return to education and normal life.</li></ol><h4>Stories of Resilience and Unity</h4><p>One of our lead volunteers recalls: "In a small hamlet near Ferozepur, we found an elderly grandmother and her grandchildren stranded on their terrace with no food for 3 days. When we handed them warm Langar meals and medicine, she didn’t ask where we were from — she simply raised her hands in profound blessing. That moment of shared connection contains the heart of our mission."</p><p>This campaign is funded purely by compassionate, transparent donations. Support the Tatkhalsa Punjab Flood Relief SOS today and empower us to keep standing between disaster and vulnerable families.</p>`,
      date: 'May 25, 2026',
      author: 'Bibi Harpreet Kaur',
      avatar: 'https://secure.gravatar.com/avatar/fb000000000000000000000000000002?s=96&d=mp',
      category: 'Relief Mission',
      read_time: '7 min read',
      image: '<?php echo esc_url( get_theme_mod( 'tatkhalsa_blog_img_2', 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=600' ) ); ?>'
    },
    {
      id: 'seeded_3',
      title: 'Preserving Sikh Heritage: Digitizing Silent Ancient Manuscripts',
      excerpt: 'How Tatkhalsa is leveraging non-destructive high-definition imaging techniques to archive rare Gurmukhi leaf scripts and preserve historical Sikh history.',
      content: `<h3>Rescuing Our Sacred History</h3><p>Beyond our visual physical bodies, a community lives and breathes through its recorded history, its visual expressions, and its sacred manuscripts. Over centuries, Punjab has experienced immense geopolitical unrest, leading to the destruction and decay of priceless Gurmukhi scripts, manuscripts (Birhs), and historical scrolls. Tatkhalsa’s Heritage Preservation Wing is addressing this critical issue head-on.</p><h4>Digital Seva: Merging Heritage with Science</h4><p>We have introduced state-of-the-art non-destructive imaging studios on-site in historic Gurdwaras and private repositories. Using temperature-controlled high-resolution digital cameras and specialized white-light spectra, we successfully digitize aging manuscripts without stressing the ancient fiber pages.</p><h4>Why Gurmukhi Archiving Is Crucial</h4><p>Our work focuses not only on preservation but on democratic accessibility. Once indexed, these historical archives are cataloged into open-access online repositories. These allow international scholars, researchers, and Sikh youth to interact with authentic 18th and 19th-century scripts directly.</p><p><em>"A nation that forgets its history loses its roots,"</em> says Dr. Gurmukh Singh. <em>"By archiving these pages, we make sure our future generations hear the authentic, beautiful voices of our ancestors."</em></p><p>This massive task is ongoing. If you possess information about rare historical artifacts, manuscripts, or scrolls in Punjab in need of active preservation, contact our specialized preservation wing today.</p>`,
      date: 'Apr 12, 2026',
      author: 'Dr. Gurmukh Singh',
      avatar: 'https://secure.gravatar.com/avatar/fb000000000000000000000000000003?s=96&d=mp',
      category: 'Heritage',
      read_time: '6 min read',
      image: '<?php echo esc_url( get_theme_mod( 'tatkhalsa_blog_img_3', 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=600' ) ); ?>'
    },
    {
      id: 'seeded_4',
      title: 'Nurturing Our Mother Earth: Replanting Green Canopies',
      excerpt: 'Air is the Guru, Water is the Father, Earth is the Great Mother. Discover Tatkhalsa’s collaborative local tree-plantation drives to heal ecosystems.',
      content: `<h3>Pawan Guru Paani Pita, Mata Dharat Mahat</h3><p>In accordance with the sacred Gurbani composition of Guru Nanak Dev Ji, our natural environment is elevated to the level of divine guidance: <em>"Air is the Guru, Water the Father, and Earth the Great Mother."</em> Yet, modern Punjab suffers from deep industrial deforestation, water table reduction, and intensive pesticide runoff. Tatkhalsa Foundation’s <strong>Green Seva Initiative</strong> was born to restore this sacred balance.</p><h4>The Micro-Forest Revolution Let’s Go</h4><p>Our environmental team utilizes the Japanese Miyawaki afforestation methodology. By planting highly dense, native multi-tier saplings closely together, we grow forests that develop 10 times faster and become 30 times denser than standard plantations. Areas that were barren dumps are transformed into oxygen-rich biodiversity spaces within months.</p><h4>Empowering Farmers toward Organic Stewardship</h4><p>Furthermore, we conduct regular educational outreach programs for local farmers. We provide organic seed varieties and practical workshops detailing bio-enzyme farming, soil conservation, and drip irrigation. This minimizes rely-on toxic agrochemicals, protecting Punjab’s groundwater for future generations.</p><p>We dream of a Punjab where the streams run clear and the air is pristine. Our local micro-forest planting is powered entirely by volunteers. Join our next weekend planting drive and leave a legacy that grows for centuries!</p>`,
      date: 'Mar 22, 2026',
      author: 'Prof. Balwinder Singh',
      avatar: 'https://secure.gravatar.com/avatar/fb000000000000000000000000000004?s=96&d=mp',
      category: 'Environment',
      read_time: '4 min read',
      image: '<?php echo esc_url( get_theme_mod( 'tatkhalsa_blog_img_4', 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=600' ) ); ?>'
    }
  ];

  function openBlogModal(blogId) {
    const blog = blogsData.find(item => String(item.id) === String(blogId));
    if (!blog) return;

    // Set modal elements
    document.getElementById('modalImg').src = blog.image;
    document.getElementById('modalImg').alt = blog.title;
    document.getElementById('modalTag').textContent = blog.category;
    document.getElementById('modalTitle').textContent = blog.title;
    document.getElementById('modalAuthorAvatar').src = blog.avatar;
    document.getElementById('modalAuthorName').textContent = blog.author;
    document.getElementById('modalDate').textContent = blog.date;
    document.getElementById('modalReadTime').textContent = blog.read_time;
    document.getElementById('modalContent').innerHTML = blog.content;

    // Body scroll freeze
    document.body.style.overflow = 'hidden';

    // Fade on modal
    const modal = document.getElementById('blogDetailModal');
    modal.classList.add('open');
  }

  function closeBlogModal() {
    const modal = document.getElementById('blogDetailModal');
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }

  function handleBackdropClick(event) {
    const box = document.querySelector('.blog-modal-box');
    if (box && !box.contains(event.target)) {
      closeBlogModal();
    }
  }

  // Live client-side interactive search and filtering animation
  document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('blogsSearch');
    const categoryBtns = document.querySelectorAll('.blog-category-btn');
    const cards = document.querySelectorAll('.blog-card');
    const noResults = document.getElementById('blogsNoResults');
    const grid = document.getElementById('blogsGrid');

    let currentCategory = 'all';
    let searchQuery = '';

    function filterBlogs() {
      let visibleCount = 0;

      cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        const title = card.getAttribute('data-title');
        const excerpt = card.getAttribute('data-excerpt');

        const matchesCategory = (currentCategory === 'all' || cat === currentCategory);
        const matchesSearch = (!searchQuery || title.includes(searchQuery) || excerpt.includes(searchQuery));

        if (matchesCategory && matchesSearch) {
          card.style.display = 'flex';
          // Smooth fade in animation trigger
          setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 50);
          visibleCount++;
        } else {
          card.style.opacity = '0';
          card.style.transform = 'translateY(15px)';
          card.style.display = 'none';
        }
      });

      if (visibleCount === 0) {
        grid.style.display = 'none';
        noResults.style.display = 'block';
      } else {
        grid.style.display = 'grid';
        noResults.style.display = 'none';
      }
    }

    // Category button interactive clicks
    categoryBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        categoryBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentCategory = btn.getAttribute('data-category');
        filterBlogs();
      });
    });

    // Search input responsive keyup events
    if (searchInput) {
      searchInput.addEventListener('keyup', (e) => {
        searchQuery = e.target.value.toLowerCase().trim();
        filterBlogs();
      });
      searchInput.addEventListener('search', (e) => {
        searchQuery = e.target.value.toLowerCase().trim();
        filterBlogs();
      });
    }
  });
</script>

<?php
get_footer();
