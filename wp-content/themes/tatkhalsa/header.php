<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() . '/Logo.png' ); ?>" />
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
    <div class="side-graphic side-graphic-left" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/Logo.png' ); ?>');"></div>
    <div class="side-graphic side-graphic-right" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/Logo.png' ); ?>');"></div>
 
    <!-- Top Announcement Bar -->
    <div class="top-bar">
      <div class="container">
        <div class="top-bar-right">
          <div class="top-bar-links">
            <a href="https://wa.me/919877038520" target="_blank" rel="noopener noreferrer" class="whatsapp-btn">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="display: inline-block; vertical-align: middle;">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.458 5.704 1.459h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
              <span>WhatsApp Seva Desk</span>
            </a>
          </div>
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
 
        <!-- Elegant Multi-Selection Dropdown for Mobile / Tablet (Instead of Hamburger Menu) -->
        <div class="header-nav-selector">
          <select id="mobileNavSelect" onchange="if(this.value) window.location.href=this.value;" style="display: none;" aria-label="Select Seva Page">
            <option value="" disabled selected>Explore Seva...</option>
            <option value="<?php echo esc_url( home_url( '/' ) ); ?>">Home Page</option>
            <option value="<?php echo esc_url( home_url( '/about/' ) ); ?>">About Tatkhalsa</option>
            <option value="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Our Seva Projects</option>
            <option value="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>">Join as Volunteer</option>
            <option value="<?php echo esc_url( home_url( '/punjab-flood-relief/' ) ); ?>">Flood Relief SOS</option>
          </select>
          
          <div class="custom-select-wrapper" id="customMobileNavWrapper">
            <button class="custom-select-btn" id="customMobileNavBtn" aria-haspopup="listbox" aria-expanded="false" aria-label="Explore Seva Page Menu">
              <span id="customMobileNavLabel">Explore Seva...</span>
              <span class="select-chevron">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
              </span>
            </button>
            <div class="custom-select-dropdown" id="customMobileNavDropdown" role="listbox">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-dropdown-opt" role="option">Home Page</a>
              <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="custom-dropdown-opt" role="option">About Tatkhalsa</a>
              <a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>" class="custom-dropdown-opt" role="option">Our Seva Projects</a>
              <a href="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>" class="custom-dropdown-opt" role="option">Join as Volunteer</a>
              <a href="<?php echo esc_url( home_url( '/punjab-flood-relief/' ) ); ?>" class="custom-dropdown-opt" role="option">Flood Relief SOS</a>
            </div>
          </div>
        </div>
      </div>
    </header>
 

