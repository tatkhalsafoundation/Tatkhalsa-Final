<?php
/**
 * Template Name: Blood Donors
 *
 * @package Tatkhalsa_Theme
 */

get_header();

// Fetch Blood Donors
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
    'post_type'      => 'blood_donor',
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => $paged,
);

// Filter by Blood Group if requested
if ( isset( $_GET['blood_group'] ) && ! empty( $_GET['blood_group'] ) ) {
    $args['meta_query'] = array(
        array(
            'key'     => 'blood_group',
            'value'   => sanitize_text_field( $_GET['blood_group'] ),
            'compare' => '='
        )
    );
}

// Search by address
if ( isset( $_GET['address'] ) && ! empty( $_GET['address'] ) ) {
    $address_query = array(
        'key'     => 'address',
        'value'   => sanitize_text_field( $_GET['address'] ),
        'compare' => 'LIKE'
    );
    if ( isset( $args['meta_query'] ) ) {
        $args['meta_query']['relation'] = 'AND';
        $args['meta_query'][] = $address_query;
    } else {
        $args['meta_query'] = array( $address_query );
    }
}

$donors_query = new WP_Query( $args );
?>

<div class="blood-donors-page" style="padding: 60px 0; background: var(--body-bg); min-height: 80vh;">
    <div class="container">
        
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="color: var(--text-dark); font-size: 2.5rem; margin-bottom: 10px;">Blood Donors Directory</h1>
            <p style="color: var(--text-light); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Connect with verified blood donors in your area or register yourself to save lives.
            </p>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-bottom: 40px;">
            <button onclick="openDonorRegistrationModal()" class="btn-primary" style="background: linear-gradient(135deg, #ff334b 0%, #ff5d73 100%); color: #fff; border: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 15px rgba(255,51,75,0.3);">
                🩸 Register as a Donor
            </button>
            <button onclick="openBloodRequestModal()" class="btn-secondary" style="background: var(--bg-dark); color: var(--text-dark); border: 1px solid var(--text-dark); padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                🚨 Request Blood
            </button>
        </div>

        <div style="background: var(--bg-dark); padding: 20px; border-radius: 12px; margin-bottom: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);">
            <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: bold; color: var(--text-dark);">Blood Group</label>
                    <select name="blood_group" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1); background: #fff; color: #333;">
                        <option value="">All Blood Groups</option>
                        <option value="A+" <?php echo (isset($_GET['blood_group']) && $_GET['blood_group'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo (isset($_GET['blood_group']) && $_GET['blood_group'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo (isset($_GET['blood_group']) && $_GET['blood_group'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo (isset($_GET['blood_group']) && $_GET['blood_group'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                        <option value="O+" <?php echo (isset($_GET['blood_group']) && $_GET['blood_group'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo (isset($_GET['blood_group']) && $_GET['blood_group'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                        <option value="AB+" <?php echo (isset($_GET['blood_group']) && $_GET['blood_group'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo (isset($_GET['blood_group']) && $_GET['blood_group'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                    </select>
                </div>
                <div style="flex: 2; min-width: 250px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: bold; color: var(--text-dark);">Location / Address</label>
                    <input type="text" name="address" value="<?php echo isset($_GET['address']) ? esc_attr($_GET['address']) : ''; ?>" placeholder="Search by city, area, or zip code" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1); background: #fff; color: #333;">
                </div>
                <div style="margin-top: 28px;">
                    <button type="submit" style="background: var(--primary); color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Search</button>
                    <?php if ( isset($_GET['blood_group']) || isset($_GET['address']) ): ?>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" style="margin-left: 10px; color: var(--text-light); text-decoration: none; font-size: 0.9rem;">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if ( $donors_query->have_posts() ) : ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php while ( $donors_query->have_posts() ) : $donors_query->the_post(); 
                    $post_id = get_the_ID();
                    $bg = get_post_meta( $post_id, 'blood_group', true );
                    $address = get_post_meta( $post_id, 'address', true );
                    $contact = get_post_meta( $post_id, 'contact_details', true );
                    $map = get_post_meta( $post_id, 'map_location', true );
                ?>
                    <div style="background: var(--bg-dark); border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); position: relative; border-top: 4px solid #ff334b;">
                        <div style="position: absolute; top: 20px; right: 20px; background: #ff334b; color: #fff; font-weight: bold; padding: 5px 12px; border-radius: 20px; font-size: 1.1rem; box-shadow: 0 2px 8px rgba(255,51,75,0.4);">
                            <?php echo esc_html( $bg ); ?>
                        </div>
                        <h3 style="color: var(--text-dark); margin-bottom: 15px; padding-right: 50px;"><?php echo esc_html( get_post_meta( $post_id, 'donor_name', true ) ); ?></h3>
                        
                        <div style="margin-bottom: 10px; display: flex; align-items: flex-start; gap: 10px;">
                            <span style="font-size: 1.2rem;">📍</span>
                            <span style="color: var(--text-light); font-size: 0.95rem;"><?php echo esc_html( $address ); ?></span>
                        </div>
                        
                        <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 1.2rem;">📞</span>
                            <a href="tel:<?php echo esc_attr( $contact ); ?>" style="color: var(--text-dark); font-weight: bold; text-decoration: none; font-size: 0.95rem;"><?php echo esc_html( $contact ); ?></a>
                        </div>

                        <?php if ( ! empty( $map ) ) : ?>
                            <a href="<?php echo esc_url( $map ); ?>" target="_blank" style="display: block; text-align: center; background: rgba(0,0,0,0.05); padding: 8px; border-radius: 6px; font-size: 0.9rem; color: var(--text-dark); text-decoration: none; border: 1px solid rgba(0,0,0,0.1); transition: background 0.2s;">
                                🗺️ View on Google Maps
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 40px; display: flex; justify-content: center; gap: 10px;">
                <?php 
                echo paginate_links( array(
                    'total' => $donors_query->max_num_pages,
                    'prev_text' => '&laquo; Prev',
                    'next_text' => 'Next &raquo;'
                ) );
                ?>
            </div>

        <?php else : ?>
            <div style="text-align: center; padding: 50px; background: rgba(0,0,0,0.02); border-radius: 12px;">
                <p style="font-size: 1.2rem; color: var(--text-light); margin-bottom: 20px;">No donors found matching your criteria.</p>
                <button onclick="window.location.href='<?php echo esc_url( get_permalink() ); ?>'" style="background: var(--bg-dark); color: var(--text-dark); border: 1px solid var(--text-dark); padding: 10px 20px; border-radius: 6px; cursor: pointer;">
                    Clear Filters
                </button>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</div>

<!-- Donor Registration Modal -->
<div class="modal-overlay" id="donorRegModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; overflow-y: auto; align-items: center; justify-content: center; padding: 20px;">
  <div class="modal-content" style="background: var(--bg-dark); padding: 30px; border-radius: 16px; width: 100%; max-width: 500px; position: relative;  margin: auto;">
    <button class="modal-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark);" onclick="closeDonorRegistrationModal()">&times;</button>
    
    <h2 style="color: var(--text-dark); margin-bottom: 20px; text-align: center;">Register as Blood Donor</h2>
    
    <form id="donorRegForm" method="POST" action="">
      <input type="hidden" name="action" value="submit_blood_donor">
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Full Name *</label>
        <input type="text" name="donorName" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Blood Group *</label>
        <select name="bloodGroup" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
            <option value="">Select Blood Group</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Contact Number *</label>
        <input type="tel" name="contactDetails" required placeholder="e.g. +91 9876543210" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Full Address *</label>
        <textarea name="address" required rows="2" placeholder="City, Area, Pin Code" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;"></textarea>
      </div>

      <div style="margin-bottom: 25px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Google Maps Link (Optional)</label>
        <div style="display: flex; gap: 10px;">
          <input type="url" name="mapLocation" id="mapLocation" placeholder="Paste link or click icon to get location" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
          <button type="button" onclick="getCurrentLocation()" style="background: rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.2); border-radius: 6px; padding: 0 15px; cursor: pointer; color: var(--text-dark);" title="Get Current Location">
             📍
          </button>
        </div>
        <small id="locStatus" style="color: var(--text-light); font-size: 0.85rem; display: block; margin-top: 5px;"></small>
      </div>

      <div id="donorRegStatus" style="margin-bottom: 15px; font-size: 0.9rem; border-radius: 6px; display: none;"></div>
      
      <button type="submit" id="donorRegBtn" style="width: 100%; background: linear-gradient(135deg, #ff334b 0%, #ff5d73 100%); color: #fff; border: none; font-size: 1rem; font-weight: bold; padding: 14px; border-radius: 8px; cursor: pointer; box-shadow: 0 5px 15px rgba(255,51,75,0.35);">
        Register Donor
      </button>
    </form>
  </div>
</div>

<script>
function openDonorRegistrationModal() {
    const modal = document.getElementById("donorRegModal");
    if(modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }
}

function closeDonorRegistrationModal() {
    const modal = document.getElementById("donorRegModal");
    if(modal) {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }
}

function getCurrentLocation() {
    const status = document.getElementById("locStatus");
    const input = document.getElementById("mapLocation");
    
    if (!navigator.geolocation) {
        status.innerText = "Geolocation is not supported by your browser";
        return;
    }

    status.innerText = "Locating...";
    
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            input.value = `https://www.google.com/maps?q=${lat},${lng}`;
            status.innerText = "Location mapped successfully";
            status.style.color = "green";
        },
        () => {
            status.innerText = "Unable to retrieve your location";
            status.style.color = "red";
        }
    );
}

document.addEventListener("DOMContentLoaded", () => {
    // For Donor Registration modal background click
    const dModal = document.getElementById("donorRegModal");
    if(dModal) {
        dModal.addEventListener("click", (e) => {
            if (e.target === dModal) {
                closeDonorRegistrationModal();
            }
        });
    }

    // Ajax Submission
    const form = document.getElementById("donorRegForm");
    if(form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById("donorRegBtn");
            const statusBox = document.getElementById("donorRegStatus");
            const originalText = btn.innerHTML;
            
            btn.innerHTML = "Registering...";
            btn.disabled = true;
            statusBox.style.display = "none";
            
            const formData = new FormData(form);
            const params = new URLSearchParams();
            for(const pair of formData.entries()) {
                params.append(pair[0], pair[1]);
            }
            
            try {
                const response = await fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: params.toString()
                });
                
                const res = await response.json();
                statusBox.style.display = "block";
                statusBox.style.padding = "10px";
                
                if(res.success) {
                    statusBox.style.backgroundColor = "rgba(40, 167, 69, 0.1)";
                    statusBox.style.borderColor = "rgba(40, 167, 69, 0.2)";
                    statusBox.style.color = "#28a745";
                    statusBox.innerHTML = res.data.message;
                    form.reset();
                    setTimeout(() => {
                        closeDonorRegistrationModal();
                        window.location.reload(); // Reload to see the new entry
                    }, 2000);
                } else {
                    statusBox.style.backgroundColor = "rgba(220, 53, 69, 0.1)";
                    statusBox.style.borderColor = "rgba(220, 53, 69, 0.2)";
                    statusBox.style.color = "#dc3545";
                    statusBox.innerHTML = res.data.message || "An error occurred.";
                }
            } catch (err) {
                console.error(err);
                statusBox.style.display = "block";
                statusBox.style.padding = "10px";
                statusBox.style.backgroundColor = "rgba(220, 53, 69, 0.1)";
                statusBox.style.color = "#dc3545";
                statusBox.innerHTML = "Network error. Please try again.";
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }
});
</script>

<?php get_footer(); ?>
