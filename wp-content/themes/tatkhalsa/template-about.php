<?php
/**
 * Template Name: About Page
 *
 * @package TatkhalsaTheme
 */

get_header();
?>

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
          About Us
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
    <!-- About Us -->
    <section
      id="about"
      style="
        background-image: url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/media_1781128759420_15.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
        padding-bottom: 80px;
        padding-top: 80px;
      "
    >
      <div
        style="
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: rgba(5, 10, 20, 0.85);
          z-index: 1;
        "
      ></div>
      <div
        class="container scroll-reveal"
        style="
          position: relative;
          z-index: 2;
          text-align: center;
          max-width: 900px;
          margin: 0 auto 50px;
        "
      >
        <h2
          style="
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--primary);
            font-family: var(--font-serif);
          "
        >
          Our Humanitarian Core
        </h2>
        <p
          style="
            text-align: center;
            font-size: 1.1rem;
            color: var(--text-light);
            line-height: 1.8;
          "
        >
          Tatkhalsa Foundation is a dedicated Sikh charity and non-profit NGO
          providing comprehensive humanitarian relief. Built on the core
          principles of Seva (selfless service) and compassion, we focus on
          extending a helping hand where it is needed the most. Our ongoing
          initiatives provide vital support across medical emergencies,
          educational empowerment, and daily sustenance to ensure no family is
          left behind.
        </p>
      </div>

      <div
        class="container scroll-reveal"
        style="position: relative; z-index: 2"
      >
        <div class="services-grid" style="gap: 40px">
          <!-- Cancer Patients -->
          <div
            class="service-card"
            style="text-align: center; border-bottom-color: var(--accent-red)"
          >
            <div
              class="service-icon"
              style="
                margin: 0 auto 20px auto;
                background: rgba(209, 61, 82, 0.1);
                color: var(--accent-red);
              "
            >
              <svg
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path
                  d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"
                ></path>
                <path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"></path>
              </svg>
            </div>
            <h3
              style="
                font-size: 1.3rem;
                margin-bottom: 15px;
                color: var(--primary);
              "
            >
              Cancer Patient Support
            </h3>
            <p
              style="
                color: var(--text-light);
                font-size: 0.95rem;
                line-height: 1.6;
              "
            >
              We offer life-saving healthcare assistance and emotional care to
              cancer patients. Combating an illness shouldn't result in
              financial ruin—our NGO provides treatment support, medicine
              supply, and holistic care for affected individuals and their
              families.
            </p>
          </div>

          <!-- Blood on Call -->
          <div
            class="service-card"
            style="text-align: center; border-bottom-color: var(--accent-blue)"
          >
            <div
              class="service-icon"
              style="
                margin: 0 auto 20px auto;
                background: rgba(50, 133, 199, 0.1);
                color: var(--accent-blue);
              "
            >
              <svg
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
              </svg>
            </div>
            <h3
              style="
                font-size: 1.3rem;
                margin-bottom: 15px;
                color: var(--primary);
              "
            >
              Blood on Call
            </h3>
            <p
              style="
                color: var(--text-light);
                font-size: 0.95rem;
                line-height: 1.6;
              "
            >
              Emergency situations demand immediate response. Our 'Blood on
              Call' network ensures that patients receive timely blood
              contributions during critical medical emergencies. A network of
              dedicated volunteers is always ready to step forward and save
              lives.
            </p>
          </div>

          <!-- Sikh Families Groceries -->
          <div
            class="service-card"
            style="text-align: center; border-bottom-color: var(--secondary)"
          >
            <div
              class="service-icon"
              style="
                margin: 0 auto 20px auto;
                background: rgba(212, 175, 55, 0.1);
                color: var(--secondary);
              "
            >
              <svg
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M2 22l5.5-5.5"></path>
                <path
                  d="M8.14 13.14A3 3 0 0 1 12 11h2c-.39 0-.74.15-1 .39"
                ></path>
                <path d="M15 15a4 4 0 0 0-4-4c-.39 0-.74.15-1 .39"></path>
                <path d="M18 18a5 5 0 0 0-5-5c-.39 0-.74.15-1 .39"></path>
                <path d="M22 22a6 6 0 0 0-6-6c-.39 0-.74.15-1 .39"></path>
              </svg>
            </div>
            <h3
              style="
                font-size: 1.3rem;
                margin-bottom: 15px;
                color: var(--primary);
              "
            >
              Grocery Assistance
            </h3>
            <p
              style="
                color: var(--text-light);
                font-size: 0.95rem;
                line-height: 1.6;
              "
            >
              Upholding the langar tradition of feeding the community, we supply
              monthly rations and grocery assistance to disadvantaged Sikh
              families. We strive to provide basic food security so they can
              focus on regaining stability and dignity.
            </p>
          </div>

          <!-- Schooling of Children -->
          <div
            class="service-card"
            style="text-align: center; border-bottom-color: var(--accent-green)"
          >
            <div
              class="service-icon"
              style="
                margin: 0 auto 20px auto;
                background: rgba(56, 142, 99, 0.1);
                color: var(--accent-green);
              "
            >
              <svg
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
              </svg>
            </div>
            <h3
              style="
                font-size: 1.3rem;
                margin-bottom: 15px;
                color: var(--primary);
              "
            >
              Schooling of Children
            </h3>
            <p
              style="
                color: var(--text-light);
                font-size: 0.95rem;
                line-height: 1.6;
              "
            >
              Education is the key to breaking the cycle of poverty. Our
              Children Education NGO program funds schooling, books, and
              uniforms for underprivileged children, empowering the next
              generation with the tools they need to succeed and lead.
            </p>
          </div>
        </div>
      </div>
    </section>

<!-- Gurbani Quote Section -->
<section class="gurbani-quote-section scroll-reveal">
  <div class="gurbani-quote-container">
    <div class="gurbani-ornament">✧ ✦ ✧</div>
    <div class="gurbani-gurmukhi">ਸਭ ਮਹਿ ਜੋਤਿ ਜੋਤਿ ਹੈ ਸੋਇ ॥ ਤਿਸ ਦੈ ਚਾਨਣਿ ਸਭ ਮਹਿ ਚਾਨਣੁ ਹੋਇ ॥</div>
    <div class="gurbani-translit">sabh meh joth joth hai soe || thas dhai chaanan sabh meh chaanan hoi ||</div>
    <div class="gurbani-english">"The Divine Light is within everyone, and everyone is illuminated by that same Light."</div>
    <div class="gurbani-source">Sri Guru Granth Sahib Ji — Ang 13</div>
  </div>
</section>

<?php
get_footer();
