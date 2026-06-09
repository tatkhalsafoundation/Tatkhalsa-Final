<?php
/**
 * Template Name: Projects Page
 *
 * @package TatkhalsaTheme
 */

get_header();
?>

    <section
      class="hero"
      style="
        padding: 100px 0 60px 0;
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
        <h1 style="font-size: 3rem; color: var(--cream); margin-bottom: 20px">
          Our Projects
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


    <!-- What We Do -->
    <section
      id="what-we-do"
      style="
        background-color: var(--bg-shade-4);
        position: relative;
        z-index: 2;
      "
    >
      <div class="container scroll-reveal">
        <h2 class="section-title">What We Do</h2>
        <div class="services-grid">
          <div class="service-card">
            <div class="service-icon">🤝</div>
            <h3>General Charity & Relief</h3>
            <p>
              Heatwave relief, distribution of essentials, and support for
              marginalized communities in times of need.
            </p>
          </div>
          <div class="service-card">
            <div class="service-icon">❤️</div>
            <h3>Healthcare Support</h3>
            <p>
              Cancer charity support, organizing regular blood contribution
              drives, and medical assistance.
            </p>
          </div>
          <div class="service-card">
            <div class="service-icon">🏆</div>
            <h3>Youth & Sports</h3>
            <p>
              Organizing sports championships and youth development programs to
              foster physical and mental well-being.
            </p>
          </div>
          <div class="service-card">
            <div class="service-icon">🏛️</div>
            <h3>Sikh History & Heritage</h3>
            <p>
              Review board and initiatives dedicated to the preservation and
              accurate representation of Sikh history.
            </p>
          </div>
          <div class="service-card">
            <div class="service-icon">🚨</div>
            <h3>Disaster & Emergency</h3>
            <p>
              Rapid response teams providing critical aid and support during
              natural disasters and emergencies.
            </p>
          </div>
          <div class="service-card">
            <div class="service-icon">🌱</div>
            <h3>Community Welfare</h3>
            <p>
              Sustainable development projects and environmental initiatives
              focused on long-term positive impact.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Gallery Placeholders -->
    <section id="gallery" style="background-color: var(--bg-shade-4)">
      <div class="container scroll-reveal">
        <h2 class="section-title">Our Impact Gallery</h2>
        <div class="gallery-grid">
          <div class="gallery-item">
            <img
              src="https://upload.wikimedia.org/wikipedia/commons/e/ee/Group_of_Nihang_Singhs.jpg"
              alt="Group of Nihang Singhs"
            />
            <div class="gallery-overlay">
              <span class="gallery-text">Preserving Sikh Heritage</span>
            </div>
          </div>
          <div class="gallery-item">
            <img
              src="https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg"
              alt="Langar Preparation"
            />
            <div class="gallery-overlay">
              <span class="gallery-text">Active Langar Sewa</span>
            </div>
          </div>
          <div class="gallery-item">
            <img
              src="https://upload.wikimedia.org/wikipedia/commons/d/de/Sikhs_gathered_at_Hola_Mohalla_Holi_festival_in_Anandpur_Sahib.jpg"
              alt="Sikhs at Hola Mohalla"
            />
            <div class="gallery-overlay">
              <span class="gallery-text">Hola Mohalla</span>
            </div>
          </div>
          <div class="gallery-item">
            <img
              src="https://upload.wikimedia.org/wikipedia/commons/7/73/The_Camp_of_Bhai_Bir_Singh_Naurangabad%2C_Punjab%2C_ca.1850.jpg"
              alt="Historic Sikh Camp"
            />
            <div class="gallery-overlay">
              <span class="gallery-text">Sikh History</span>
            </div>
          </div>
        </div>
      </div>
    </section>

<?php
get_footer();
