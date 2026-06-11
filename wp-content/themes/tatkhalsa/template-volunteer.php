<?php
/**
 * Template Name: Volunteer Page
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
        <h1 style="font-size: 3rem; color: var(--cream); margin-bottom: 20px">
          Volunteer With Us
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
    <!-- Volunteer & Contact Forms -->
    <section id="volunteer" style="background-color: var(--bg-shade-3)">
      <div class="container scroll-reveal">
        <h2 class="section-title">Get Involved</h2>
        <div
          class="forms-grid"
          style="grid-template-columns: 1fr; max-width: 700px; margin: 0 auto"
        >
          <!-- Volunteer Form -->
          <div class="form-container">
            <h3 style="color: var(--primary)">Join Our Volunteer Network</h3>
            <p style="margin-bottom: 20px; color: var(--text-light)">
              Become part of a dedicated team committed to serving communities
              through blood contribution drives, relief missions, and
              humanitarian projects.
            </p>
            <form id="volunteerForm">
              <div class="form-group">
                <label>Name</label>
                <input type="text" id="vName" required />
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" id="vEmail" required />
              </div>
              <div class="form-group">
                <label>Phone</label>
                <input type="tel" id="vPhone" required />
              </div>
              <div class="form-group">
                <label>Message / Skills</label>
                <textarea
                  id="vMessage"
                  rows="4"
                  required
                  placeholder="How would you like to help?"
                ></textarea>
              </div>
              <button type="submit" class="btn" style="width: 100%">
                Become A Volunteer
              </button>
              <p id="vStatus" style="margin-top: 15px; font-weight: bold"></p>
            </form>
          </div>
        </div>
      </div>
    </section>

<!-- Gurbani Quote Section -->
<section class="gurbani-quote-section scroll-reveal">
  <div class="gurbani-quote-container">
    <div class="gurbani-ornament">✧ ✦ ✧</div>
    <div class="gurbani-gurmukhi">ਨਾ ਕੋ ਬੈਰੀ ਨਹੀ ਬਿਗਾਨਾ ਸਗਲ ਸੰਗਿ ਹਮ ਕਉ ਬਨਿ ਆਈ ॥</div>
    <div class="gurbani-translit">Na Ko Bairee Nahee Bigaanaa Sagal Sang Ham Kau Ban Aaee ||</div>
    <div class="gurbani-english">"No one is my enemy, and no one is a stranger to us; we are in harmony with everyone."</div>
    <div class="gurbani-source">Sri Guru Granth Sahib Ji — Ang 1299</div>
  </div>
</section>

<?php
get_footer();
