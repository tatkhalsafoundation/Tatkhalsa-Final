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
    
    <?php
    // Dynamically resolve SEO metadata for Tatkhalsa Foundation Pages
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    // Default Fallbacks
    $seo_title       = "Tatkhalsa Foundation | Uplifting Humanity via Modern Seva & Heritage";
    $seo_description = "Tatkhalsa Foundation is a non-profit registered organization dedicated to human education, community relief, emergency gurbani access, tree planting stewardship, volunteer networks, and blood donor systems.";
    $seo_keywords    = "Tatkhalsa, Tatkhalsa Foundation, Tat Khalsa, Sikh Seva, Volunteer Punjab, Blood Donor Network India, Gurbani Search API, Tree Planting Punjab, Sikh Charity, Human Upliftment, Punjab NGO, Seva Updates";
    $seo_image       = esc_url( tatkhalsa_get_logo_url() );

    if ( is_front_page() || is_home() ) {
        $seo_title       = "Tatkhalsa Foundation | Direct Seva, Volunteerism, and Gurbani Heritage";
        $seo_description = "Tatkhalsa Foundation powers direct humanitarian aid. Register as a blood donor, join volunteer missions, search Gurbani Scriptures, and participate in active community tree planting stewardship.";
    } elseif ( is_page_template( 'template-about.php' ) ) {
        $seo_title       = "About Tatkhalsa Foundation | Our Mission, Vision & Values";
        $seo_description = "Learn about the core values of Tatkhalsa Foundation. Discover our history of transparency, and how we empower Punjab communities through organized volunteerism and trust.";
        $seo_keywords    = "About Tatkhalsa, Tatkhalsa Mission, Sikh volunteers, Gurmat principles, transparent NGO Punjab, human service";
    } elseif ( is_page_template( 'template-projects.php' ) ) {
        $seo_title       = "Our Seva Projects | Environmental, Healthcare, and Spiritual Initiatives";
        $seo_description = "Explore our active global programs: Emergency Medical Aid, Dynamic Gurbani Search API, Tree Planting Stewardship, and educational support systems for underrepresented youths.";
        $seo_keywords    = "Tatkhalsa projects, Tree planting initiative Punjab, Gurbani Search Engine, Gurbani API, Medical Seva, NGO tasks";
    } elseif ( is_page_template( 'template-blood-donors.php' ) ) {
        $seo_title       = "Blood On Call | Save Lives with Tatkhalsa Foundation";
        $seo_description = "Join Blood On Call, our secure and rapid response blood donor directory. Real-time availability tracking for all blood groups (A+, O-, B+, etc.) in Punjab and across India.";
        $seo_keywords    = "Blood On Call, Blood Donor India, Registered Blood Donor Punjab, Emergency Blood Group A B O, Sikh blood drive, donor directory, request blood";
    } elseif ( is_page_template( 'template-volunteer.php' ) ) {
        $seo_title       = "Become a Sevadar | Join the Tatkhalsa Foundation Volunteer Force";
        $seo_description = "Uplift humanity through physical seva. Register as a certified Tatkhalsa volunteer to assist with medical help desk setup, administrative tasks, and local plantation drives.";
        $seo_keywords    = "Sikh Volunteer Registration, Live Sevadar registration, volunteering in India, local tree planting team, community seva";
    } elseif ( is_page_template( 'template-blog.php' ) || is_singular('post') ) {
        $seo_title       = ( get_the_title() ? get_the_title() : "Insights & Updates" ) . " | Tatkhalsa Foundation";
        $seo_description = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : "Read the latest news, detailed updates, and informative articles from our volunteer networks and global project trackers.";
        $seo_keywords    = "Tatkhalsa blog, Sikh updates, NGO Punjab posts, Seva articles, volunteer news, foundation blog";
    } elseif ( is_page_template( 'template-privacy.php' ) ) {
        $seo_title       = "Privacy Policy | Transparent Data & Secure Storage Guidelines";
        $seo_description = "Read our strict rolling 30-day security storage policies, fully GDPR client-anonymizing systems, and cryptographic user security standards on tatkhalsa.in.";
        $seo_keywords    = "privacy policy Tatkhalsa, data protection, secure server, 30-day purge, local secure cache";
    } elseif ( is_page_template( 'template-terms.php' ) ) {
        $seo_title       = "Terms and Conditions | Community Code of Conduct";
        $seo_description = "Understand our user guidelines, interactive volunteer registry standards, emergency medical aid validation requirements, and acceptable usage rules.";
        $seo_keywords    = "Terms of service, usage code, volunteer terms, donation safety, medical aid validation";
    } elseif ( is_404() ) {
        $seo_title       = "Page Not Found | Error Code 404 - Tatkhalsa Foundation";
        $seo_description = "We apologize, but the requested Seva portal route has moved or changed. Search for volunteer roles, active blood directories, and Gurbani databases.";
        $seo_keywords    = "404 not found, Seva portal, Tatkhalsa error, invalid URL, page missing";
    }
    ?>

    <?php if ( ! defined( 'WPSEO_VERSION' ) && ! class_exists( 'AIOSEO_Base' ) && ! class_exists( 'RankMath' ) ) : ?>
    <!-- Primary Search Engine Optimization Tags -->
    <meta name="description" content="<?php echo esc_attr( $seo_description ); ?>" />
    <meta name="keywords" content="<?php echo esc_attr( $seo_keywords ); ?>" />
    <meta name="author" content="Tatkhalsa Foundation" />
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
    <link rel="canonical" href="<?php echo esc_url( $current_url ); ?>" />

    <!-- Open Graph Meta Tags (Facebook & LinkedIn Optimizations) -->
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="<?php echo (is_singular('post') ? 'article' : 'website'); ?>" />
    <meta property="og:title" content="<?php echo esc_attr( $seo_title ); ?>" />
    <meta property="og:description" content="<?php echo esc_attr( $seo_description ); ?>" />
    <meta property="og:url" content="<?php echo esc_url( $current_url ); ?>" />
    <meta property="og:site_name" content="Tatkhalsa Foundation" />
    <meta property="og:image" content="<?php echo esc_url( $seo_image ); ?>" />
    <meta property="og:image:secure_url" content="<?php echo esc_url( $seo_image ); ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:type" content="image/jpeg" />

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr( $seo_title ); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr( $seo_description ); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url( $seo_image ); ?>" />
    <meta name="twitter:site" content="@tatkhalsain" />
    <meta name="twitter:creator" content="@tatkhalsain" />
    <?php endif; ?>

    <!-- Semantic Schema.org Structured Markup JSON-LD for rich SEO results -->
    <script type="application/ld+json">
    <?php
    $schema_graph = array(
        "@context" => "https://schema.org",
        "@graph" => array(
            array(
                "@type" => "NGO",
                "@id" => esc_url( home_url( '/' ) ) . "#organization",
                "name" => "Tatkhalsa Foundation",
                "url" => esc_url( home_url( '/' ) ),
                "logo" => array(
                    "@type" => "ImageObject",
                    "@id" => esc_url( home_url( '/' ) ) . "#logo",
                    "url" => esc_url( tatkhalsa_get_logo_url() ),
                    "caption" => "Tatkhalsa Foundation Logo"
                ),
                "image" => array(
                    "@id" => esc_url( home_url( '/' ) ) . "#logo"
                ),
                "sameAs" => array(
                    "https://instagram.com/tatkhalsa.in/",
                    "https://www.facebook.com/tatkhalsain",
                    "https://x.com/tatkhalsain"
                ),
                "description" => "Tatkhalsa Foundation is an NGO registered non-profit organization dedicated to human upliftment, tree planting, volunteer mobilizations, Gurbani scripture search tools, and emergency blood networks."
            ),
            array(
                "@type" => "WebSite",
                "@id" => esc_url( home_url( '/' ) ) . "#website",
                "url" => esc_url( home_url( '/' ) ),
                "name" => "Tatkhalsa Foundation",
                "description" => "Modern Seva, Heritage and Humanitarian Relief Networks",
                "publisher" => array(
                    "@id" => esc_url( home_url( '/' ) ) . "#organization"
                )
            ),
            array(
                "@type" => "WebPage",
                "@id" => esc_url( $current_url ) . "#webpage",
                "url" => esc_url( $current_url ),
                "name" => esc_attr( $seo_title ),
                "description" => esc_attr( $seo_description ),
                "isPartOf" => array(
                    "@id" => esc_url( home_url( '/' ) ) . "#website"
                )
            )
        )
    );
    echo json_encode( $schema_graph, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
    ?>
    </script>
    
    <script>
      (function() {
        const savedTheme = localStorage.getItem("tatkhalsa-theme") || "dark";
        document.documentElement.setAttribute("data-theme", savedTheme);
      })();

      function tatkhalsaSetLanguage(lang) {
          localStorage.setItem('tatkhalsa_lang', lang);
          window.location.reload();
      }
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
            <a href="https://instagram.com/tatkhalsa.in/" target="_blank" rel="noopener noreferrer" title="Instagram">
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
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
                <a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Projects</a>
                <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
                <a href="<?php echo esc_url( home_url( '/blood-on-can/' ) ); ?>" style="color: #ff334b; font-weight: 700;">Blood On Call</a>
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
 
        <!-- Elegant Multi-Selection Dropdown for Mobile / Tablet (Instead of Hamburger Menu) -->
        <div class="header-nav-selector">
            <select id="mobileNavSelect" onchange="if(this.value === 'translate_pa') { tatkhalsaSetLanguage('pa'); } else if(this.value === 'translate_en') { tatkhalsaSetLanguage('en'); } else if(this.value === 'donate') { openModal(); } else if(this.value === 'contact') { document.getElementById('footer').scrollIntoView({ behavior: 'smooth' }); } else if(this.value) { window.location.href=this.value; }" style="display: none;" aria-label="Select Seva Page">
              <option value="" disabled selected>Explore Seva...</option>
              <option value="<?php echo esc_url( home_url( '/' ) ); ?>">Home Page</option>
              <option value="<?php echo esc_url( home_url( '/about/' ) ); ?>">About Tatkhalsa</option>
              <option value="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Our Seva Projects</option>
              <option value="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>">Join as Volunteer</option>
              <option value="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Insights & Blog</option>
              <option value="<?php echo esc_url( home_url( '/blood-on-can/' ) ); ?>">Blood On Call</option>
              <option value="contact">Contact Us</option>
              <option value="donate">Contribute Now</option>
              <option value="translate_pa">🌐 Translate to Punjabi</option>
              <option value="translate_en">🌐 View in English</option>
            </select>
            
            <div class="custom-select-wrapper" id="customMobileNavWrapper">
              <button class="custom-select-btn icon-only-btn" id="customMobileNavBtn" aria-haspopup="listbox" aria-expanded="false" aria-label="Explore Seva Page Menu">
                <span id="customMobileNavLabel" style="display:none;"></span>
                <span class="select-chevron icon-circle">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                  </svg>
                </span>
              </button>
              <div class="custom-select-dropdown" id="customMobileNavDropdown" role="listbox">
                <div style="display: flex; gap: 5px; padding: 5px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 5px;">
                   <a href="#" onclick="tatkhalsaSetLanguage('pa'); document.getElementById('customMobileNavWrapper').classList.remove('open'); return false;" class="custom-dropdown-opt" role="option" style="flex:1; text-align:center; padding: 5px; color: #d4af37 !important; font-weight: 700; font-size: 0.75rem; border:1px solid #d4af37; border-radius:5px;">ਪੰਜਾਬੀ</a>
                   <a href="#" onclick="tatkhalsaSetLanguage('en'); document.getElementById('customMobileNavWrapper').classList.remove('open'); return false;" class="custom-dropdown-opt" role="option" style="flex:1; text-align:center; padding: 5px; color: #4da6ff !important; font-weight: 700; font-size: 0.75rem; border:1px solid #4da6ff; border-radius:5px;">EN</a>
                </div>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-dropdown-opt" role="option">Home Page</a>
                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="custom-dropdown-opt" role="option">About Tatkhalsa</a>
                <a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>" class="custom-dropdown-opt" role="option">Our Seva Projects</a>
                <a href="<?php echo esc_url( home_url( '/volunteer/' ) ); ?>" class="custom-dropdown-opt" role="option">Join as Volunteer</a>
                <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="custom-dropdown-opt" role="option">Insights & Blog</a>
                <a href="<?php echo esc_url( home_url( '/blood-on-can/' ) ); ?>" class="custom-dropdown-opt" role="option" style="color: #ff334b !important; font-weight: 700;">Blood On Call</a>
                <a href="#" onclick="document.getElementById('footer').scrollIntoView({ behavior: 'smooth' }); document.getElementById('customMobileNavWrapper').classList.remove('open'); return false;" class="custom-dropdown-opt" role="option" style="color: #4da6ff !important; font-weight: 700;">Contact Us</a>
                <a href="#" onclick="openModal(); document.getElementById('customMobileNavWrapper').classList.remove('open'); return false;" class="custom-dropdown-opt header-mobile-contrib-opt" role="option" style="color: #ff5d73 !important; font-weight: 700;">Contribute Now</a>
                <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 5px 0;"></div>
              </div>
            </div>
        </div>
      </div>
    </header>
 

