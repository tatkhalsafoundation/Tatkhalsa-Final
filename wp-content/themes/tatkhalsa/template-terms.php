<?php
/**
 * Template Name: Terms and Conditions Page
 *
 * @package TatkhalsaTheme
 */

get_header();
?>

    <section class="hero" style="padding: 40px 0 60px 0;">
      <div class="hero-overlay"></div>
      <div
        class="container scroll-reveal"
        style="position: relative; z-index: 2; text-align: center"
      >
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
        <h1 style="font-size: 3rem; color: var(--text-dark); margin-bottom: 20px">
          Terms and Conditions
        </h1>
        <div
          style="
            width: 60px;
            height: 3px;
            background: var(--secondary);
            margin: 0 auto;
          "
        ></div>
      </div>
    </section>

    <section style="padding: 60px 0;">
      <div class="container" style="max-width: 800px; color: var(--text-dark);">
        <div style="background: var(--bg-shade-1); padding: 40px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            
            <p style="margin-bottom: 40px; color: var(--text-light); line-height: 1.8; font-style: italic;">
                Last Updated: <?php echo date('F Y'); ?>
            </p>

            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">1. Acceptance of Terms</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. In addition, when using these particular services, you shall be subject to any posted guidelines or rules applicable to such services.
            </p>

            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">2. Blood Network Disclaimer</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                The Tatkhalsa Blood Network is a voluntary platform designed to connect blood donors with those in need. We do not guarantee the availability of donors, nor do we perform medical screening of donors. It is the responsibility of the medical professionals at the respective hospitals to screen and verify any blood donation for safety. We accept no liability for any medical complications or issues that arise from donations facilitated through this platform.
            </p>

            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">3. Donations & Contributions</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                All financial contributions are voluntary and non-refundable. Funds are utilized directly for our Seva projects as deemed appropriate by the Tatkhalsa Foundation trust. We ensure complete transparency, but direct tracking of specific micro-funds to singular activities is not possible.
            </p>

            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">4. Code of Conduct</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                Our platform and community operate on the principles of Seva. Any misuse of the contact information provided (such as spamming donors), abusive behavior, or fraudulent requests will result in an immediate and permanent ban from accessing our services.
            </p>

            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">5. Changes to Terms</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                We reserve the right to modify these terms at any time. We do so by posting and drawing attention to the updated terms on this site. Your decision to continue to visit and make use of the site after such changes have been made constitutes your formal acceptance of the new Terms of Service.
            </p>
            
            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">6. Contact Us</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                If you have any questions about these Terms, please contact us at: <a href="mailto:tatkhalsafoundation@gmail.com" style="color: var(--secondary);">tatkhalsafoundation@gmail.com</a>.
            </p>
        </div>
      </div>
    </section>

<?php get_footer(); ?>
