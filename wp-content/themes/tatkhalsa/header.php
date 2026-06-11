<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link rel="preconnect" href="https://upload.wikimedia.org" crossorigin>
    <link rel="preload" as="image" href="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>" />
    <link rel="icon" type="image/png" href="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no, date=no, email=no, address=no" />
    
    <!-- Fallback SEO tags if no custom plugin is installed -->
    <meta name="author" content="Tatkhalsa Foundation" />
    <meta name="robots" content="index, follow" />
    
    <script>
      (function() {
        const savedTheme = localStorage.getItem("tatkhalsa-theme") || "dark";
        document.documentElement.setAttribute("data-theme", savedTheme);
      })();
    </script>

    <style>
      /* Fallback injected CSS for direct mobile fixes bypassing aggressive WP caching plugins */
      @media (max-width: 768px) {
        .logo::after {
          width: 32px !important;
          height: 32px !important;
          margin-left: 8px !important;
        }
        .header-logo-badge {
          width: 32px !important;
          height: 32px !important;
          margin-right: 8px !important;
          transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), height 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), margin 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
          will-change: width, height, margin;
        }
        .header-logo-img-new {
          width: 20px !important;
          height: 20px !important;
          transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
          will-change: width, height, transform;
        }
        .logo-stack {
          transform-origin: left center;
          transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
          will-change: transform;
        }
        body.scrolled .logo-stack {
          transform: scale(0.7) !important;
        }
        
        /* Scrolled States applied securely via inline CSS */
        body.scrolled .header-logo-badge {
          width: 28px !important;
          height: 28px !important;
          margin-right: 6px !important;
        }
        body.scrolled .header-logo-img-new {
          width: 18px !important;
          height: 18px !important;
        }
        .header {
          transition: padding 0.3s ease, min-height 0.3s ease, background 0.3s ease, transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        }
      }
    </style>

    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
 
    <!-- Side Background Graphics (Removed explicitly due to layout issues) -->

 
    <!-- Top Announcement Bar -->
    <div class="top-bar">
      <div class="container">
        <div class="top-bar-right">

          <div class="top-bar-social">
            <a href="https://www.instagram.com/tatkhalsa.in/" target="_blank" rel="noopener noreferrer" title="Instagram">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
              </svg>
            </a>
            <a href="https://www.facebook.com/tatkhalsain" target="_blank" rel="noopener noreferrer" title="Facebook Page">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
              </svg>
            </a>
            <a href="https://x.com/tatkhalsain" target="_blank" rel="noopener noreferrer" title="X (Twitter)">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
                <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
              </svg>
            </a>
            <a href="https://www.snapchat.com/add/tatkhalsa.in?" target="_blank" rel="noopener noreferrer" title="Snapchat">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.05 2c-1.54 0-3.12 0.38-4.22 1.56-1.02 1.1-.96 2.65-.96 3.86 0 1.2-.5 2.15-1.55 2.58-.28.12-.55.22-.65.5-.1.3-.02.6.2.82.7.67 1.7.9 2.58 1.13.1.03.14.15.08.24-.34.52-.75 1.13-1.6 1.34-.84.2-1.46.06-2.1-.22-.3-.13-.67.06-.58.4.15.57.85.95 1.33 1.18 1.48.73 3.3.47 4.9.47.16 0 .3-.1.35-.24.16-.48.4-.73.74-.73.34 0 .6.25.75.73.06.15.2.24.36.24 1.6 0 3.42.26 4.9-.47.48-.23 1.18-.6 1.33-1.18.1-.34-.26-.53-.58-.4-.64.28-1.26.42-2.1.22-.85-.2-1.26-.82-1.6-1.34-.06-.1-.02-.22.08-.24.88-.23 1.88-.46 2.58-1.13.22-.22.3-.52.2-.82-.1-.28-.37-.38-.65-.5-1.05-.43-1.55-1.38-1.55-2.58 0-1.2.06-2.76-.96-3.86C15.17 2.38 13.6 2 12.05 2z" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
 
    <!-- Header & Nav -->
    <header class="header">
      <div class="container">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
          <div class="header-logo-badge">
            <img class="header-logo-img-new" src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>" alt="Tatkhalsa Logo" />
          </div>
          <div class="logo-stack">
            <span class="logo-text-up">Tatkhalsa</span>
            <span class="logo-text-down">Foundation</span>
          </div>
        </a>
 
        <!-- Dynamic Menu Setup (Desktop/Wide Viewports) -->
        <nav class="nav-menu-container">
          <?php
          if ( has_nav_menu( 'primary' ) ) {
              wp_nav_menu( array(
                  'theme_location' => 'primary',
                  'container'      => false,
                  'items_wrap'     => '<ul class="nav-links">%3$s</ul>',
              ) );
          } else {
              // Custom default semantic layout
              ?>
              <div class="nav-links">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="smooth-scroll">Home</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>#current-campaigns" class="smooth-scroll">Campaigns</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>#transparency" class="smooth-scroll">Transparency</a>
                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
                <a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Projects</a>
                <a href="<?php echo esc_url( home_url( '/blood-donors/' ) ); ?>" style="color: #ff334b; font-weight: 700;">Blood Network</a>
              </div>
              <?php
          }
          ?>
        </nav>
 
        <!-- Header Actions CTA (Desktop/Wide Viewports) -->
        <div class="header-actions">
          <button onclick="openModal()" class="header-cta-btn header-donate-btn">Contribute Now</button>
          <a href="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>" class="header-cta-btn header-volunteer-btn">Join Seva</a>
        </div>
 
        <!-- Hamburger Toggle -->
        <button class="hamburger-btn" id="mobileMenuToggle" aria-label="Toggle Navigation">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hamburger-icon">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
      </div>
    </header>

    <!-- Full-Screen Mobile Navigation Panel -->
    <div class="mobile-nav-panel" id="mobileNavPanel">
      <div class="mobile-nav-links">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-nav-link" onclick="toggleMobileMenu()">Home</a>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>#current-campaigns" class="mobile-nav-link smooth-scroll" onclick="toggleMobileMenu()">Campaigns</a>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>#transparency" class="mobile-nav-link smooth-scroll" onclick="toggleMobileMenu()">Transparency</a>
        <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="mobile-nav-link" onclick="toggleMobileMenu()">About</a>
        <a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>" class="mobile-nav-link" onclick="toggleMobileMenu()">Projects</a>
        <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="mobile-nav-link" onclick="toggleMobileMenu()">Blog</a>
        <a href="<?php echo esc_url( home_url( '/blood-donors/' ) ); ?>" class="mobile-nav-link" style="color: #ff334b !important; font-weight: 700;" onclick="toggleMobileMenu()">Blood Network</a>
        <a href="#footer" class="mobile-nav-link smooth-scroll" onclick="toggleMobileMenu()">Contact Us</a>
        <button onclick="openModal(); toggleMobileMenu();" class="mobile-nav-link" style="color: #ffdf79 !important; font-weight: 700; background:none; border:none; text-align:left; padding:0; cursor:pointer;">Contribute Now</button>
      </div>
    </div>
 

