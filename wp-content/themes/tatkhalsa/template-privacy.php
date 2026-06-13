<?php
/**
 * Template Name: Privacy Policy Page
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
          Privacy Policy
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
            <?php 
            $has_custom_content = false;
            if ( have_posts() ) {
                while ( have_posts() ) {
                    the_post();
                    if ( ! empty( get_the_content() ) ) {
                        $has_custom_content = true;
                        ?>
                        <div class="entry-content" style="color: var(--text-light); line-height: 1.8;">
                            <?php the_content(); ?>
                        </div>
                        <?php
                    }
                }
                rewind_posts();
            }
            
            if ( ! $has_custom_content ) :
            ?>
            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">1. Information We Collect</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                We collect information you provide directly to us, such as when you volunteer, donate, register as a blood donor, submit an emergency blood request, or contact us. The types of personal information we may collect include your name, email address, physical address, and phone number.
            </p>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                <strong>IP Address Logging & Anti-Spam Security:</strong> To prevent spam, fraudulent entries, and misuse of our community Blood On Call, we securely log the IP address and registration timestamp associated with all blood donor registrations and emergency blood requests. To protect user privacy, IP address data is automatically and permanently purged or anonymized after exactly 30 days.
            </p>

            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">2. How We Use Information</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                We use the information we collect to provide, maintain, and improve our services. We use this information to:
                <ul style="list-style: disc; margin-left: 20px; margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                    <li>Send you technical notices, updates, security alerts, and support and administrative messages.</li>
                    <li>Respond to your comments, questions, and requests and provide customer service.</li>
                    <li>Facilitate Tatkhalsa Blood On Call, including notifying matched donors about nearby emergency blood requests and allowing individuals in need to reach out to available local donors.</li>
                    <li>Process donations securely.</li>
                </ul>
            </p>

            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">3. Privacy & Blood On Call</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                When you register as a blood donor, your explicit contact details and exact location are protected and remain private. Contact information is only shared when an Emergency Blood Request is explicitly submitted through our portal. We prioritize your privacy and anonymity wherever possible. Donor details can be removed from our systems anytime by using the "Remove My Name" functionality.
            </p>

            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">4. Data Sharing</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                We do not share your personal information with third parties except as necessary to provide our services (e.g., payment processing for donations) or as required by law.
            </p>
            
            <h2 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary);">5. Contact Us</h2>
            <p style="margin-bottom: 20px; color: var(--text-light); line-height: 1.8;">
                If you have any questions about this Privacy Policy, please contact us at: <a href="mailto:info@tatkhalsa.in" style="color: var(--secondary);">info@tatkhalsa.in</a>.
            </p>
            <?php endif; ?>
        </div>
      </div>
    </section>

<?php get_footer(); ?>
