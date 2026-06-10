<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <link rel="icon" type="image/png" href="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no, date=no, email=no, address=no" />
    
    <!-- Fallback SEO tags if no custom plugin is installed -->
    <meta name="author" content="Tatkhalsa Foundation" />
    <meta name="robots" content="index, follow" />
    
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
 
    <!-- Side Background Graphics utilizing dynamic WP asset pathing -->
    <div class="side-graphic side-graphic-left" style="background-image: url('<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>');"></div>
    <div class="side-graphic side-graphic-right" style="background-image: url('<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>');"></div>
 
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
          <img src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>" alt="<?php bloginfo( 'name' ); ?> Logo" />
          <span class="logo-text"><?php bloginfo( 'name' ); ?></span>
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
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
                <a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Projects</a>
                <a href="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>">Volunteer</a>
              </div>
              <?php
          }
          ?>
        </nav>
 
        <!-- Hamburger Button for Mobile/Tablet -->
        <button class="hamburger-toggle" id="mobileMenuToggle" aria-label="Open Navigation Drawer">
          <span class="hamburger-bar"></span>
          <span class="hamburger-bar"></span>
          <span class="hamburger-bar"></span>
        </button>
      </div>
    </header>

    <!-- Side-Drawer Overlay for Mobile Nav -->
    <div class="side-menu-overlay" id="sideMenuOverlay"></div>

    <!-- Beautiful Slide-Out Side Drawer Menu for Mobile/Tablet -->
    <div class="side-menu-drawer" id="sideMenuDrawer">
      <div class="side-menu-header">
        <div class="side-menu-logo">
          <img src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>" alt="Tatkhalsa Foundation" />
          <span>Tatkhalsa</span>
        </div>
        <button class="side-menu-close" id="sideMenuClose" aria-label="Close Navigation Drawer">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      <div class="side-menu-body">
        <nav class="side-menu-nav">
          <?php
          if ( has_nav_menu( 'primary' ) ) {
              wp_nav_menu( array(
                  'theme_location' => 'primary',
                  'container'      => false,
                  'items_wrap'     => '<ul class="side-nav-links">%3$s</ul>',
              ) );
          } else {
              ?>
              <ul class="side-nav-links">
                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
                <li><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Projects</a></li>
                <li><a href="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>">Volunteer</a></li>
                <li><a href="<?php echo esc_url( home_url( '/punjab-flood-relief/' ) ); ?>">Flood Relief</a></li>
              </ul>
              <?php
          }
          ?>
        </nav>
      </div>
      <div class="side-menu-footer">
        <div class="side-menu-social">
          <a href="https://www.instagram.com/tatkhalsa.in/" target="_blank" rel="noopener noreferrer">Instagram</a>
          <a href="https://www.facebook.com/tatkhalsain" target="_blank" rel="noopener noreferrer">Facebook</a>
        </div>
        <p>© <?php echo date('Y'); ?> Tatkhalsa Foundation</p>
      </div>
    </div>
 

