<?php
/**
 * Template Name: Blood Donors
 *
 * @package Tatkhalsa_Theme
 */
?>
<?php get_header(); ?>
<?php
// Fetch Blood Donors
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
    'post_type'      => 'blood_donor',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
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
$address_terms = array();
if ( isset( $_GET['country'] ) && ! empty( $_GET['country'] ) ) $address_terms[] = sanitize_text_field( $_GET['country'] );
if ( isset( $_GET['state'] ) && ! empty( $_GET['state'] ) ) $address_terms[] = sanitize_text_field( $_GET['state'] );
if ( isset( $_GET['district'] ) && ! empty( $_GET['district'] ) ) $address_terms[] = sanitize_text_field( $_GET['district'] );
if ( isset( $_GET['address'] ) && ! empty( $_GET['address'] ) ) $address_terms[] = sanitize_text_field( $_GET['address'] );

if ( ! empty( $address_terms ) ) {
    if ( ! isset( $args['meta_query'] ) ) {
        $args['meta_query'] = array( 'relation' => 'AND' );
    } else {
        $args['meta_query']['relation'] = 'AND';
    }
    
    foreach ( $address_terms as $term ) {
        $args['meta_query'][] = array(
            'key'     => 'address',
            'value'   => $term,
            'compare' => 'LIKE'
        );
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
            <button onclick="openCertificateModal()" class="btn-secondary" style="background: var(--bg-dark); color: var(--primary); border: 1px solid var(--primary); padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                🏆 Claim Certificate
            </button>
            <button onclick="openRemoveDonorModal()" class="btn-secondary" style="background: transparent; color: var(--text-light); border: 1px dashed rgba(255,51,75,0.5); padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='#ff334b'; this.style.color='#ff334b';" onmouseout="this.style.borderColor='rgba(255,51,75,0.5)'; this.style.color='var(--text-light)';">
                🗑️ Remove My Name
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
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: bold; color: var(--text-dark);">Country</label>
                    <select name="country" id="donorCountry" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1); background: #fff; color: #333;" onchange="updateStates()">
                        <option value="">Any Country</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: bold; color: var(--text-dark);">State</label>
                    <select name="state" id="donorState" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1); background: #fff; color: #333;" onchange="updateDistricts()">
                        <option value="">Any State</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: bold; color: var(--text-dark);">District / City</label>
                    <select name="district" id="donorDistrict" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1); background: #fff; color: #333;">
                        <option value="">Any District</option>
                    </select>
                </div>
                <div style="flex: 2; min-width: 250px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: bold; color: var(--text-dark);">Or Type Location</label>
                    <input type="text" name="address" value="<?php echo isset($_GET['address']) ? esc_attr($_GET['address']) : ''; ?>" placeholder="Area, Zip Code, etc" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1); background: #fff; color: #333;">
                </div>
                <div style="margin-top: 28px;">
                    <button type="submit" style="background: var(--primary); color: var(--white); border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Search</button>
                    <?php if ( isset($_GET['blood_group']) || isset($_GET['address']) ): ?>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" style="margin-left: 10px; color: var(--text-light); text-decoration: none; font-size: 0.9rem;">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if ( $donors_query->have_posts() ) : ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px; max-width: 800px; margin: 0 auto;">
                <?php while ( $donors_query->have_posts() ) : $donors_query->the_post(); 
                    $post_id = get_the_ID();
                    $bg = get_post_meta( $post_id, 'blood_group', true );
                    $address = get_post_meta( $post_id, 'address', true );
                    $contact = get_post_meta( $post_id, 'contact_details', true );
                    $map = get_post_meta( $post_id, 'map_location', true );
                    $availability = get_post_meta( $post_id, 'availability_status', true );
                    if ( ! $availability ) $availability = 'Available Now';
                ?>
                    <div style="background: var(--bg-dark); border-radius: 10px; padding: 15px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); position: relative; border-top: 3px solid #ff334b;">
                        <div style="position: absolute; top: 15px; right: 15px; background: #ff334b; color: #fff; font-weight: bold; padding: 4px 10px; border-radius: 15px; font-size: 0.9rem; box-shadow: 0 2px 6px rgba(255,51,75,0.4);">
                            <?php echo esc_html( $bg ); ?>
                        </div>
                        <h3 style="color: var(--text-dark); margin-bottom: 5px; padding-right: 40px; font-size: 1.1rem;"><?php echo esc_html( get_post_meta( $post_id, 'donor_name', true ) ); ?></h3>
                        
                        <div style="margin-bottom: 10px; font-size: 0.8rem; color: var(--text-dark); font-weight: 500;">
                            <?php 
                                if ( $availability === 'On Standby' ) echo '🟡 On Standby';
                                elseif ( $availability === 'Resting Phase' ) echo '🔴 Resting Phase';
                                else echo '🟢 Available Now';
                            ?>
                        </div>

                        <div style="margin-bottom: 15px; font-size: 0.75rem; color: var(--text-light); line-height: 1.4; background: rgba(0,0,0,0.03); padding: 10px; border-radius: 6px;">
                            <span style="font-size: 0.85rem; display: block; margin-bottom: 3px;">🔒 Privacy Protected</span>
                            Contact details are private. Submit an Emergency Blood Request to view available donor contacts.
                        </div>

                        <button onclick="openBloodRequestModal()" style="display: block; width: 100%; text-align: center; background: rgba(255,51,75,0.1); padding: 8px; border-radius: 6px; font-weight: bold; font-size: 0.8rem; color: #ff334b; text-decoration: none; border: 1px solid rgba(255,51,75,0.2); cursor: pointer; transition: background 0.2s;">
                            🚨 Request to Connect
                        </button>
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

        <!-- Disclaimer Section -->
        <div style="margin-top: 60px; padding: 25px; background: rgba(0,0,0,0.03); border-radius: 12px; border-left: 4px solid #ff334b; font-size: 0.85rem; color: var(--text-light); line-height: 1.6;">
            <strong>Disclaimer:</strong> Tatkhalsa Foundation operates purely as a voluntary community coordination network. We do not run physical blood banks or commercialize medical supplies. All verifications of donor eligibility must be independently validated by certified hospital practitioners at the time of transfusion.
        </div>
    </div>
</div>

<!-- Donor Registration Modal -->
<div class="modal-overlay" id="donorRegModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; overflow-y: auto; align-items: center; justify-content: center; padding: 20px;">
  <div class="modal-content" style="background: var(--bg-shade-1); padding: 30px; border-radius: 16px; width: 100%; max-width: 500px; position: relative;  margin: auto;">
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
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Email Address *</label>
        <p style="font-size: 0.8rem; color: var(--text-light); margin-bottom: 5px;">Required to receive blood request alerts from nearby patients.</p>
        <input type="email" name="donorEmail" required placeholder="e.g. email@example.com" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Contact Number *</label>
        <input type="tel" name="contactDetails" required placeholder="e.g. +91 9876543210" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Country *</label>
        <select name="country" id="regCountry" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;" onchange="updateRegStates()">
            <option value="">Select Country</option>
        </select>
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">State *</label>
        <select name="state" id="regState" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;" onchange="updateRegDistricts()">
            <option value="">Select State</option>
        </select>
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">District / City *</label>
        <select name="district" id="regDistrict" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
            <option value="">Select District</option>
        </select>
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Street Address / Area *</label>
        <textarea name="address" required rows="2" placeholder="Street, Area, Pin Code" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;"></textarea>
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Google Maps Link (Optional)</label>
        <div style="display: flex; gap: 10px;">
          <input type="url" name="mapLocation" id="mapLocation" placeholder="Paste link or click icon to get location" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
          <button type="button" onclick="getCurrentLocation()" style="background: rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.2); border-radius: 6px; padding: 0 15px; cursor: pointer; color: var(--text-dark);" title="Get Current Location">
             📍
          </button>
        </div>
        <small id="locStatus" style="color: var(--text-light); font-size: 0.85rem; display: block; margin-top: 5px;"></small>
      </div>

      <div style="margin-bottom: 25px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Availability Status</label>
        <select name="availabilityStatus" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
            <option value="Available Now">🟢 Available Now (Ready for immediate drives)</option>
            <option value="On Standby">🟡 On Standby (Approaching eligibility/close-range only)</option>
            <option value="Resting Phase">🔴 Resting Phase (Recovery period post-donation)</option>
        </select>
      </div>

      <div id="donorRegStatus" style="margin-bottom: 15px; font-size: 0.9rem; border-radius: 6px; display: none;"></div>
      
      <button type="submit" id="donorRegBtn" style="width: 100%; background: linear-gradient(135deg, #ff334b 0%, #ff5d73 100%); color: #fff; border: none; font-size: 1rem; font-weight: bold; padding: 14px; border-radius: 8px; cursor: pointer; box-shadow: 0 5px 15px rgba(255,51,75,0.35);">
        Register Donor
      </button>
    </form>
  </div>
</div>

<!-- Remove Donor Modal -->
<div class="modal-overlay" id="removeDonorModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; overflow-y: auto; align-items: center; justify-content: center; padding: 20px;">
  <div class="modal-content" style="background: var(--bg-shade-1); padding: 30px; border-radius: 16px; width: 100%; max-width: 400px; position: relative;  margin: auto;">
    <button class="modal-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark);" onclick="closeRemoveDonorModal()">&times;</button>
    
    <h2 style="color: var(--text-dark); margin-bottom: 20px; text-align: center;">Remove My Registration</h2>
    
    <form id="removeDonorForm" method="POST" action="">
      <input type="hidden" name="action" value="remove_blood_donor">
      
      <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Registered Contact Number</label>
        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 10px;">Enter the exact contact number you used while registering.</p>
        <input type="tel" name="contactNumber" required placeholder="e.g. +91 9876543210" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>

      <div id="removeDonorStatus" style="margin-bottom: 15px; font-size: 0.9rem; border-radius: 6px; display: none;"></div>
      
      <button type="submit" id="removeDonorBtn" style="width: 100%; background: transparent; color: #ff334b; border: 2px solid #ff334b; font-size: 1rem; font-weight: bold; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='#ff334b'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#ff334b';">
        Remove My Name
      </button>
    </form>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- Certificate Modal -->
<div class="modal-overlay" id="certificateModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; overflow-y: auto; align-items: center; justify-content: center; padding: 20px;">
  <div class="modal-content" style="background: var(--bg-shade-1); padding: 30px; border-radius: 16px; width: 100%; max-width: 400px; position: relative;  margin: auto;">
    <button class="modal-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark);" onclick="closeCertificateModal()">&times;</button>
    
    <div style="text-align: center; margin-bottom: 20px;">
        <span style="font-size: 3rem; display: block; margin-bottom: 10px;">🏆</span>
        <h2 style="color: var(--text-dark); margin-bottom: 10px;">Claim Certificate</h2>
        <p style="font-size: 0.85rem; color: var(--text-light); line-height: 1.4;">Did you recently donate blood? Enter your registered email address to receive your official Certificate of Appreciation.</p>
    </div>
    
    <form id="certificateForm" method="POST" action="">
      <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Registered Email Address</label>
        <input type="email" name="donorEmail" required placeholder="name@example.com" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>

      <div id="certificateStatus" style="margin-bottom: 15px; font-size: 0.9rem; border-radius: 6px; display: none;"></div>
      
      <button type="submit" id="certificateBtn" style="width: 100%; background: linear-gradient(135deg, #FFB800 0%, #F59E0B 100%); color: #fff; border: none; font-size: 1rem; font-weight: bold; padding: 12px; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
        Email My Certificate
      </button>
    </form>
  </div>
</div>

<div id="pdfCertTemplate" style="position: absolute; top: -9999px; left: -9999px; opacity: 0; pointer-events: none;">
    <div style="width: 800px; height: 600px; background-color:#f4f7f6; padding:40px; font-family:'Arial', sans-serif; text-align:center; box-sizing: border-box;">
        <div style="background:#fff; border:12px solid #0a2342; padding:40px; border-radius:8px; height:100%; box-sizing:border-box;">
            <div style="font-size:50px; margin-bottom:10px; line-height:1;">🏆</div>
            <h1 style="color:#0a2342; font-size:32px; text-transform:uppercase; letter-spacing:2px; margin:bottom:5px; margin-top:0;">Certificate of Appreciation</h1>
            <h3 style="color:#ff334b; font-size:18px; margin-bottom:30px; margin-top:0;">Tatkhalsa Foundation Blood Network</h3>
            
            <p style="color:#555; font-size:16px; margin-bottom:15px;">This certificate is proudly presented to</p>
            <h2 id="certDonorName" style="color:#0a2342; font-size:36px; font-weight:bold; border-bottom:2px solid #ccc; display:inline-block; padding-bottom:5px; margin-bottom:20px; margin-top:0;">[Name]</h2>
            <p style="color:#555; font-size:16px; margin-bottom:40px; line-height:1.6; padding: 0 40px;">
                in profound recognition of your selfless commitment to saving lives. Your donation through the Tatkhalsa Blood Network stands as a testament to humanity and compassion.
            </p>
            
            <div style="display:flex; justify-content:space-between; margin-top:30px; align-items:flex-end;">
                <div style="text-align:center; flex:1;">
                    <div style="width:120px; border-bottom:1px solid #333; margin:0 auto 10px auto;"></div>
                    <span style="font-size:14px; color:#555;">Date of Issue<br><strong id="certDate">[Date]</strong></span>
                </div>
                <div style="text-align:center; flex:1;">
                    <div style="width:90px; height:90px; background:#0a2342; border-radius:50%; margin:0 auto; line-height:90px; color:#fdf7e7; font-weight:bold; font-size:11px; border:4px double #fdf7e7; box-shadow:0 0 0 2px #0a2342; text-align:center;">OFFICIAL SEAL</div>
                </div>
                <div style="text-align:center; flex:1;">
                    <div style="font-family:'Brush Script MT', cursive; font-size:24px; color:#0a2342; margin-bottom:5px;">S. Prabhjot Singh</div>
                    <div style="width:150px; border-bottom:1px solid #333; margin:0 auto 10px auto;"></div>
                    <span style="font-size:14px; color:#555;">Authorized Signatory<br><strong>Tatkhalsa Foundation</strong></span>
                </div>
            </div>
        </div>
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

function openRemoveDonorModal() {
    const modal = document.getElementById("removeDonorModal");
    if(modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }
}

function closeRemoveDonorModal() {
    const modal = document.getElementById("removeDonorModal");
    if(modal) {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }
}

function openCertificateModal() {
    const modal = document.getElementById("certificateModal");
    if(modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }
}

function closeCertificateModal() {
    const modal = document.getElementById("certificateModal");
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

    const removeForm = document.getElementById("removeDonorForm");
    if(removeForm) {
        removeForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById("removeDonorBtn");
            const statusBox = document.getElementById("removeDonorStatus");
            const originalText = btn.innerHTML;
            
            btn.innerHTML = "Removing...";
            btn.disabled = true;
            statusBox.style.display = "none";
            
            const formData = new FormData(removeForm);
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
                    removeForm.reset();
                    setTimeout(() => {
                        closeRemoveDonorModal();
                        window.location.reload(); // Reload to see the changes
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

    const certForm = document.getElementById("certificateForm");
    if(certForm) {
        certForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById("certificateBtn");
            const statusBox = document.getElementById("certificateStatus");
            const originalText = btn.innerHTML;
            const donorEmail = certForm.querySelector('[name="donorEmail"]').value;
            
            btn.innerHTML = "Verifying...";
            btn.disabled = true;
            statusBox.style.display = "none";
            
            try {
                // Step 1: Verify email and get name
                const verifyParams = new URLSearchParams();
                verifyParams.append('action', 'verify_donor_email');
                verifyParams.append('donorEmail', donorEmail);

                const verifyRes = await fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: verifyParams.toString()
                }).then(r => r.json());

                if (!verifyRes.success) {
                    throw new Error(verifyRes.data.message || "Email verification failed.");
                }

                // Step 2: Generate PDF
                btn.innerHTML = "Generating PDF...";
                document.getElementById('certDonorName').innerText = verifyRes.data.name;
                document.getElementById('certDate').innerText = verifyRes.data.date;

                const element = document.getElementById('pdfCertTemplate');
                // Make it visible to html2pdf temporarily
                element.style.opacity = '1';
                
                const opt = {
                    margin:       0,
                    filename:     'certificate.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2 },
                    jsPDF:        { unit: 'in', format: [11.11, 8.33], orientation: 'landscape' } // 800x600 px is roughly 11.11x8.33 in at 72dpi
                };

                const pdfBase64 = await html2pdf().set(opt).from(element).outputPdf('datauristring');
                
                element.style.opacity = '0';

                // Step 3: Send email
                btn.innerHTML = "Sending Email...";
                const sendParams = new URLSearchParams();
                sendParams.append('action', 'send_pdf_certificate');
                sendParams.append('donorEmail', donorEmail);
                sendParams.append('pdfData', pdfBase64);

                const sendRes = await fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: sendParams.toString()
                }).then(r => r.json());

                if (!sendRes.success) {
                    throw new Error(sendRes.data.message || "Failed to send email.");
                }

                statusBox.style.display = "block";
                statusBox.style.padding = "10px";
                statusBox.style.backgroundColor = "rgba(40, 167, 69, 0.1)";
                statusBox.style.borderColor = "rgba(40, 167, 69, 0.2)";
                statusBox.style.color = "#28a745";
                statusBox.innerHTML = sendRes.data.message;
                certForm.reset();
                setTimeout(() => {
                    closeCertificateModal();
                }, 3000);

            } catch (err) {
                console.error(err);
                statusBox.style.display = "block";
                statusBox.style.padding = "10px";
                statusBox.style.backgroundColor = "rgba(220, 53, 69, 0.1)";
                statusBox.style.color = "#dc3545";
                statusBox.innerHTML = err.message || "Network error. Please try again.";
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }
});
</script>

<script src="/assets/js/location-data.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const countrySelect = document.getElementById("donorCountry");
    const stateSelect = document.getElementById("donorState");
    const districtSelect = document.getElementById("donorDistrict");

    // Pre-selected values from PHP GET query
    const selCountry = "<?php echo isset($_GET['country']) ? esc_js($_GET['country']) : ''; ?>";
    const selState = "<?php echo isset($_GET['state']) ? esc_js($_GET['state']) : ''; ?>";
    const selDistrict = "<?php echo isset($_GET['district']) ? esc_js($_GET['district']) : ''; ?>";

    if (typeof locationData !== 'undefined') {
        const regCountrySelect = document.getElementById("regCountry");
        const regStateSelect = document.getElementById("regState");
        const regDistrictSelect = document.getElementById("regDistrict");

        // Populate Countries (Search & Registration)
        Object.keys(locationData).forEach(country => {
            // ... Search options ...
            const optionSearch = document.createElement("option");
            optionSearch.value = country;
            optionSearch.textContent = country;
            if (country === selCountry) optionSearch.selected = true;
            countrySelect.appendChild(optionSearch);

            // ... Registration options
            const optionReg = document.createElement("option");
            optionReg.value = country;
            optionReg.textContent = country;
            regCountrySelect.appendChild(optionReg);
        });

        // Initialize Search lists
        window.updateStates = function() {
            stateSelect.innerHTML = '<option value="">Any State</option>';
            districtSelect.innerHTML = '<option value="">Any District</option>';
            const country = countrySelect.value;
            if (country && locationData[country]) {
                Object.keys(locationData[country]).forEach(state => {
                    const option = document.createElement("option");
                    option.value = state;
                    option.textContent = state;
                    stateSelect.appendChild(option);
                });
            }
        };

        window.updateDistricts = function() {
            districtSelect.innerHTML = '<option value="">Any District</option>';
            const country = countrySelect.value;
            const state = stateSelect.value;
            if (country && state && locationData[country] && locationData[country][state]) {
                locationData[country][state].forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
        };

        // Initialize Reg lists
        window.updateRegStates = function() {
            regStateSelect.innerHTML = '<option value="">Select State</option>';
            regDistrictSelect.innerHTML = '<option value="">Select District</option>';
            const country = regCountrySelect.value;
            if (country && locationData[country]) {
                Object.keys(locationData[country]).forEach(state => {
                    const option = document.createElement("option");
                    option.value = state;
                    option.textContent = state;
                    regStateSelect.appendChild(option);
                });
            }
        };

        window.updateRegDistricts = function() {
            regDistrictSelect.innerHTML = '<option value="">Select District</option>';
            const country = regCountrySelect.value;
            const state = regStateSelect.value;
            if (country && state && locationData[country] && locationData[country][state]) {
                locationData[country][state].forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    regDistrictSelect.appendChild(option);
                });
            }
        };

        // If pre-selected, populate state and district
        if (selCountry) {
            updateStates();
            if (selState) {
                stateSelect.value = selState;
                updateDistricts();
                if (selDistrict) {
                    districtSelect.value = selDistrict;
                }
            }
        }
    }
});
</script>

<?php get_footer(); ?>
