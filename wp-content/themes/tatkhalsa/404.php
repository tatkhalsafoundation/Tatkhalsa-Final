<?php
/**
 * The template for displaying robust, highly styled 404 pages (Not Found)
 *
 * @package TatkhalsaTheme
 */

get_header();
?>

    <section class="hero" style="padding: 60px 0 80px 0; background: var(--bg-shade-1); text-align: center; border-bottom: 1px solid rgba(0,0,0,0.05); min-height: 60vh; display: flex; align-items: center; justify-content: center;">
      <div class="hero-overlay"></div>
      <div class="container scroll-reveal" style="position: relative; z-index: 2; text-align: center; padding: 0 20px;">
        <div class="hero-logo-wrapper" style="display: flex; justify-content: center; margin-bottom: 25px; margin-top: 10px;">
          <img
            src="<?php echo esc_url( tatkhalsa_get_logo_url() ); ?>"
            alt="Tatkhalsa Foundation Logo"
            class="hero-gurbani-logo"
            width="180"
            height="180"
            style="width: 180px; height: 180px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.1));"
          />
        </div>
        
        <div style="font-family: 'Inter', sans-serif; max-width: 650px; margin: 0 auto; color: var(--text-dark);">
          <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: var(--secondary); background: rgba(212, 175, 55, 0.15); padding: 6px 16px; border-radius: 20px; display: inline-block; margin-bottom: 15px;">
            Error Code: 404
          </span>
          <h1 style="font-size: 2.8rem; font-weight: 700; margin: 0 0 20px 0; line-height: 1.2; color: #0c1a30; letter-spacing: -0.03em;">
            Seva Page Not Found
          </h1>
          <div style="width: 60px; height: 3px; background: var(--secondary); margin: 20px auto;"></div>
          
          <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-light); margin-bottom: 35px;">
            The page you are looking for has embarked on a different path, or may have been rearranged during our recent updates. We apologize for any inconvenience.
          </p>
          
          <div style="display: flex; flex-direction: column; gap: 15px; justify-content: center; align-items: center; margin-bottom: 40px;">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: inline-block; background: var(--primary); color: #fff; padding: 14px 28px; border-radius: 8px; font-weight: 600; text-decoration: none; box-shadow: 0 4px 15px rgba(12, 26, 48, 0.15); transition: all 0.3s ease; border: 1px solid var(--primary);">
              Return to Home Portal
            </a>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; margin-top: 10px;">
              <a href="<?php echo esc_url( home_url( '/projects' ) ); ?>" style="text-decoration: none; color: var(--primary); font-weight: 500; font-size: 0.95rem; background: #fff; border: 1px solid rgba(0,0,0,0.1); padding: 8px 16px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                📁 Seva Projects
              </a>
              <a href="<?php echo esc_url( home_url( '/blood-donors' ) ); ?>" style="text-decoration: none; color: var(--primary); font-weight: 500; font-size: 0.95rem; background: #fff; border: 1px solid rgba(0,0,0,0.1); padding: 8px 16px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                🩸 Blood Network
              </a>
              <a href="<?php echo esc_url( home_url( '/volunteer' ) ); ?>" style="text-decoration: none; color: var(--primary); font-weight: 500; font-size: 0.95rem; background: #fff; border: 1px solid rgba(0,0,0,0.1); padding: 8px 16px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                🤝 Join as Sevadar
              </a>
            </div>
          </div>
          
          <div style="border-top: 1px solid rgba(0,0,0,0.08); padding-top: 25px; margin-top: 30px;">
            <p style="font-size: 0.85rem; color: #888; font-family: 'JetBrains Mono', monospace; margin: 0;">
              If you believe this is a technical mistake, please contact support: <a href="mailto:info@tatkhalsa.in" style="color: var(--secondary); text-decoration: none; font-weight: 600;">info@tatkhalsa.in</a>
            </p>
          </div>
        </div>
      </div>
    </section>

<?php get_footer(); ?>
