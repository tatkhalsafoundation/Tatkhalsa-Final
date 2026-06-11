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
            <button onclick="openDonorRegistrationModal()" class="btn-primary" style="background: linear-gradient(135deg, var(--secondary) 0%, #ffdf79 100%); color: #000; border: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);">
                🩸 Register as a Donor
            </button>
            <button onclick="openBloodRequestModal()" class="btn-secondary" style="background: linear-gradient(135deg, #ff334b 0%, #ff5d73 100%); color: #fff; border: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 15px rgba(255,51,75,0.3);">
                🚨 Request Blood
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
        <input type="text" name="donorName" required placeholder="e.g. John Doe" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Certificate Modal -->
<div class="modal-overlay" id="certificateModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; overflow-y: auto; align-items: center; justify-content: center; padding: 20px;">
  <div class="modal-content" style="background: var(--bg-shade-1); padding: 30px; border-radius: 16px; width: 100%; max-width: 400px; position: relative;  margin: auto;">
    <button class="modal-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark);" onclick="closeCertificateModal()">&times;</button>
    
    <div id="claimCertContainer">
      <div style="text-align: center; margin-bottom: 20px;">
          <span style="font-size: 3rem; display: block; margin-bottom: 10px;">🏆</span>
          <h2 style="color: var(--text-dark); margin-bottom: 10px;">Claim Certificate</h2>
          <p style="font-size: 0.85rem; color: var(--text-light); line-height: 1.4;">Did you recently donate blood? Enter your registered email address to receive your official Certificate of Appreciation.</p>
      </div>
      
      <form id="certificateForm" method="POST" action="">
        <div style="margin-bottom: 20px;">
          <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Registered Email Address</label>
          <input type="email" id="donorEmail" name="donorEmail" required placeholder="name@example.com" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
        </div>

        <div id="certificateStatus" style="margin-bottom: 15px; font-size: 0.9rem; border-radius: 6px; display: none;"></div>
        
        <button type="submit" id="certificateBtn" style="width: 100%; background: linear-gradient(135deg, #FFB800 0%, #F59E0B 100%); color: #fff; border: none; font-size: 1rem; font-weight: bold; padding: 12px; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
          View My Certificate
        </button>
      </form>
    </div>

    <div id="previewCertContainer" style="display: none; text-align: center;">
        <h2 style="color: var(--text-dark); margin-bottom: 15px;">Your Certificate</h2>
        <img id="certPreviewImg" src="" style="width: 100%; max-width: 800px; height: auto; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 10px; justify-content: center; margin-bottom: 15px;">
            <a id="downloadCertBtn" download="Tatkhalsa-Certificate.jpg" href="#" style="flex:1; background: #0a2342; color: #fff; text-decoration: none; padding: 12px; border-radius: 6px; font-weight: bold; text-align: center; display: inline-block;">⬇ Download</a>
            <button id="sendEmailCertBtn" style="flex:1; background: #FFB800; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer;">✉ Email to Me</button>
        </div>
        <div id="emailCertStatus" style="margin-top: 10px; font-size: 0.9rem; border-radius: 6px; display: none; padding: 10px; text-align: center;"></div>
    </div>
  </div>
</div>

<div id="pdfCertTemplate" style="position: absolute; top: -9999px; left: -9999px; opacity: 0; pointer-events: none;">
    <div style="width: 800px; height: 600px; background: linear-gradient(135deg, #0a2342 0%, #173d6b 100%); padding:25px; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; text-align:center; box-sizing: border-box; display: flex; flex-direction: column;">
        <div style="background:#fff; border:8px solid #FFB800; padding:4px; border-radius:15px; height:100%; box-sizing:border-box; position: relative;">
            <div style="border: 2px solid rgba(10,35,66,0.1); border-radius: 8px; height: 100%; padding: 40px 30px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden; background: #ffffff;">
                
                <!-- Decorative Corner Accents -->
                <div style="position: absolute; top: 15px; left: 15px; width: 60px; height: 60px; border-top: 4px solid #0a2342; border-left: 4px solid #0a2342;"></div>
                <div style="position: absolute; top: 15px; right: 15px; width: 60px; height: 60px; border-top: 4px solid #0a2342; border-right: 4px solid #0a2342;"></div>
                <div style="position: absolute; bottom: 15px; left: 15px; width: 60px; height: 60px; border-bottom: 4px solid #0a2342; border-left: 4px solid #0a2342;"></div>
                <div style="position: absolute; bottom: 15px; right: 15px; width: 60px; height: 60px; border-bottom: 4px solid #0a2342; border-right: 4px solid #0a2342;"></div>

                <!-- Logo at Top -->
                <div style="margin-bottom: 20px; text-align: center; position: relative; z-index: 2;">
                    <img id="certLogoImg" src="<?php echo esc_url( tatkhalsa_get_theme_logo_url() ); ?>" alt="Tatkhalsa Logo" crossorigin="anonymous" style="height: 90px; width: auto; object-fit: contain;">
                </div>

                <h1 style="color:#0a2342; font-family: 'Georgia', serif; font-size:46px; text-transform:uppercase; letter-spacing:4px; margin:0 0 15px 0;">Certificate of Appreciation</h1>
                
                <div style="background: #e31837; color: #fff; display: inline-block; padding: 6px 20px; border-radius: 30px; font-size: 16px; font-weight: bold; letter-spacing: 2px; margin: 0 auto 30px auto;">
                    TATKHALSA FOUNDATION BLOOD NETWORK
                </div>
                
                <p style="color:#555; font-size:18px; font-style: italic; margin:0 0 15px 0;">This certificate is proudly presented to</p>
                
                <h2 id="certDonorName" style="color:#0a2342; font-family: 'Georgia', serif; font-size:52px; font-weight:bold; border-bottom:3px solid #FFB800; display:inline-block; padding:0 40px 10px 40px; margin:0 0 25px 0;">[Name]</h2>
                
                <p style="color:#444; font-size: 18px; margin:0 auto 40px auto; line-height:1.7; max-width: 650px;">
                    in profound recognition of your selfless commitment to saving lives. Your blood donation through the Tatkhalsa Blood Network stands as a true testament to humanity, compassion, and the spirit of selfless service.
                </p>
                
                <div style="display:flex; justify-content:space-between; align-items:flex-end; padding: 0 40px;">
                    <div style="text-align:center; flex:1;">
                        <span style="font-size:18px; color:#333; display: block; margin-bottom: 5px; font-weight: bold;" id="certDate">[Date]</span>
                        <div style="width:140px; border-bottom:2px solid #ccc; margin:0 auto 5px auto;"></div>
                        <span style="font-size:13px; color:#777; text-transform: uppercase;">Date of Issue</span>
                    </div>
                    
                    <div style="text-align:center; flex:1;">
                        <div style="width:110px; height:110px; background:#0a2342; border-radius:50%; margin:0 auto; display:flex; align-items:center; justify-content:center; color:#FFB800; font-weight:bold; font-size:12px; border:3px solid #FFB800; position: relative;">
                            <div style="position: absolute; inset: 6px; border: 1px dashed rgba(255,184,0,0.8); border-radius: 50%;"></div>
                            <span style="z-index: 2; text-align: center; line-height: 1.3;">OFFICIAL<br>NETWORK<br>MEMBER<br>★</span>
                        </div>
                    </div>
                    
                    <div style="text-align:center; flex:1;">
                        <div style="font-family:'Georgia', serif; font-size:26px; font-style: italic; color:#0a2342; margin-bottom:5px;">S. Prabhjot Singh</div>
                        <div style="width:180px; border-bottom:2px solid #ccc; margin:0 auto 5px auto;"></div>
                        <span style="font-size:13px; color:#777; text-transform: uppercase;">President, Tatkhalsa</span>
                    </div>
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
        
        const claimContainer = document.getElementById("claimCertContainer");
        const previewContainer = document.getElementById("previewCertContainer");
        const emailStatus = document.getElementById("emailCertStatus");
        const certBtn = document.getElementById("certificateBtn");
        
        if (claimContainer) claimContainer.style.display = "block";
        if (previewContainer) previewContainer.style.display = "none";
        if (emailStatus) {
            emailStatus.style.display = "none";
            emailStatus.innerText = "";
        }
        if (certBtn) {
            certBtn.innerHTML = "View My Certificate";
            certBtn.disabled = false;
        }
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
                const response = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", {
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
                const response = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", {
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

                const verifyRes = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: verifyParams.toString()
                }).then(r => r.json());

                if (!verifyRes.success) {
                    throw new Error(verifyRes.data.message || "Email verification failed.");
                }

                // Step 2: Generate PDF
                btn.innerHTML = "Generating Certificate...";
                document.getElementById('certDonorName').innerText = verifyRes.data.name;
                document.getElementById('certDate').innerText = verifyRes.data.date;

                // Convert the logo to base64 to ensure it renders in html2canvas without CORS/loading issues
                const logoImg = document.getElementById('certLogoImg');
                if (logoImg && !logoImg.src.startsWith('data:')) {
                    try {
                        const response = await fetch(logoImg.src, { mode: 'cors' });
                        const blob = await response.blob();
                        const b64 = await new Promise(r => {
                            const reader = new FileReader();
                            reader.onload = () => r(reader.result);
                            reader.readAsDataURL(blob);
                        });
                        logoImg.src = b64;
                    } catch(e) {
                        console.warn("Failed to convert logo to base64: ", e);
                    }
                }

                const element = document.getElementById('pdfCertTemplate');
                const origCssText = element.style.cssText;
                
                // Make it visible and fixed at top so html2canvas can capture it perfectly
                element.style.cssText = 'position: fixed; top: 0; left: 0; width: 800px; height: 600px; z-index: 10000; background: white; overflow: hidden;';
                
                // Wait for all fonts and elements to settle
                await document.fonts.ready;
                await new Promise(r => setTimeout(r, 1000));
                
                const canvas = await html2canvas(element.firstElementChild, {
                    scale: 4,
                    useCORS: true,
                    scrollX: 0,
                    scrollY: 0,
                    width: 800,
                    height: 600,
                    windowWidth: 800,
                    windowHeight: 600
                });
                
                const imageBase64 = canvas.toDataURL('image/jpeg', 1.0);
                
                element.style.cssText = origCssText;

                // Step 3: Show Preview and Update Variables
                document.getElementById('claimCertContainer').style.display = 'none';
                
                const previewContainer = document.getElementById('previewCertContainer');
                previewContainer.style.display = 'block';
                
                const certPreviewImg = document.getElementById('certPreviewImg');
                certPreviewImg.src = imageBase64;
                
                const downloadBtn = document.getElementById('downloadCertBtn');
                downloadBtn.href = imageBase64;
                downloadBtn.setAttribute('download', `Tatkhalsa-Certificate-${verifyRes.data.name.replace(/\s+/g, '-')}.jpg`);
                
                // Set up Send Email button
                const sendEmailBtn = document.getElementById('sendEmailCertBtn');
                sendEmailBtn.onclick = async () => {
                    const emailStatus = document.getElementById('emailCertStatus');
                    try {
                        sendEmailBtn.innerHTML = "Sending...";
                        sendEmailBtn.disabled = true;
                        emailStatus.style.display = 'none';
                        
                        const sendParams = new URLSearchParams();
                        sendParams.append('action', 'send_pdf_certificate');
                        sendParams.append('donorEmail', donorEmail);
                        sendParams.append('pdfData', imageBase64);

                        const sendRes = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: sendParams.toString()
                        }).then(r => r.json());

                        if (!sendRes.success) {
                            throw new Error(sendRes.data.message || "Failed to send email.");
                        }

                        emailStatus.style.display = "block";
                        emailStatus.style.backgroundColor = "rgba(40, 167, 69, 0.1)";
                        emailStatus.style.color = "#28a745";
                        emailStatus.innerText = "Certificate sent to your email!";
                        sendEmailBtn.innerHTML = "✉ Sent Successfully";
                    } catch (err) {
                        emailStatus.style.display = "block";
                        emailStatus.style.backgroundColor = "rgba(220, 53, 69, 0.1)";
                        emailStatus.style.color = "#dc3545";
                        emailStatus.innerText = err.message || "An error occurred.";
                        sendEmailBtn.innerHTML = "✉ Try Again";
                        sendEmailBtn.disabled = false;
                    }
                };
                
                certForm.reset();
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const countrySelect = document.getElementById("donorCountry");
    const stateSelect = document.getElementById("donorState");
    const districtSelect = document.getElementById("donorDistrict");

    // Pre-selected values from PHP GET query
    const selCountry = "<?php echo isset($_GET['country']) ? esc_js($_GET['country']) : ''; ?>";
    const selState = "<?php echo isset($_GET['state']) ? esc_js($_GET['state']) : ''; ?>";
    const selDistrict = "<?php echo isset($_GET['district']) ? esc_js($_GET['district']) : ''; ?>";

    const regCountrySelect = document.getElementById("regCountry");
    const regStateSelect = document.getElementById("regState");
    const regDistrictSelect = document.getElementById("regDistrict");

    let cachedCountries = [];

    async function loadCountries() {
        try {
            const response = await fetch('https://countriesnow.space/api/v0.1/countries/states');
            const data = await response.json();
            if(!data.error) {
                cachedCountries = data.data;
                populateCountries();
            }
        } catch (e) {
            console.error('Error fetching countries:', e);
        }
    }

    function populateCountries() {
        cachedCountries.forEach(countryData => {
            const countryName = countryData.name;
            
            // Search options
            const optionSearch = document.createElement("option");
            optionSearch.value = countryName;
            optionSearch.textContent = countryName;
            if (selCountry) {
                if (countryName === selCountry) optionSearch.selected = true;
            } else {
                if (countryName === 'India') optionSearch.selected = true;
            }
            if (countrySelect) countrySelect.appendChild(optionSearch);

            // Registration options
            const optionReg = document.createElement("option");
            optionReg.value = countryName;
            optionReg.textContent = countryName;
            if (countryName === 'India') optionReg.selected = true;
            if (regCountrySelect) regCountrySelect.appendChild(optionReg);
        });

        if (selCountry) {
            updateStates();
            if (selState) {
                stateSelect.value = selState;
                // Async update for districts
                updateDistricts().then(() => {
                    if (selDistrict) {
                        districtSelect.value = selDistrict;
                    }
                });
            }
        } else {
            if (countrySelect) {
                countrySelect.value = 'India';
                updateStates();
            }
        }

        if (regCountrySelect) {
            regCountrySelect.value = 'India';
            updateRegStates();
        }
    }

    window.updateStates = function() {
        if (!stateSelect) return;
        stateSelect.innerHTML = '<option value="">Any State</option>';
        if (districtSelect) districtSelect.innerHTML = '<option value="">Any District</option>';
        const countryName = countrySelect.value;
        const countryData = cachedCountries.find(c => c.name === countryName);
        if (countryData && countryData.states) {
            countryData.states.forEach(state => {
                const option = document.createElement("option");
                option.value = state.name;
                option.textContent = state.name;
                stateSelect.appendChild(option);
            });
        }
    };

    window.updateDistricts = async function() {
        if (!districtSelect) return;
        districtSelect.innerHTML = '<option value="">Any District</option>';
        const country = countrySelect.value;
        const state = stateSelect.value;
        if (country && state) {
            try {
                const response = await fetch('https://countriesnow.space/api/v0.1/countries/state/cities', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ country: country, state: state })
                });
                const data = await response.json();
                if (!data.error && data.data) {
                    data.data.forEach(city => {
                        const option = document.createElement("option");
                        option.value = city;
                        option.textContent = city;
                        districtSelect.appendChild(option);
                    });
                }
            } catch (e) {
                console.error("Error fetching cities", e);
            }
        }
    };

    window.updateRegStates = function() {
        if (!regStateSelect) return;
        regStateSelect.innerHTML = '<option value="">Select State</option>';
        if (regDistrictSelect) regDistrictSelect.innerHTML = '<option value="">Select District</option>';
        const countryName = regCountrySelect.value;
        const countryData = cachedCountries.find(c => c.name === countryName);
        if (countryData && countryData.states) {
            countryData.states.forEach(state => {
                const option = document.createElement("option");
                // Country API states sometimes end in "State", we keep the exact string returned
                option.value = state.name;
                option.textContent = state.name;
                regStateSelect.appendChild(option);
            });
        }
    };

    window.updateRegDistricts = async function() {
        if (!regDistrictSelect) return;
        regDistrictSelect.innerHTML = '<option value="">Select District</option>';
        const country = regCountrySelect.value;
        const state = regStateSelect.value;
        if (country && state) {
            try {
                const response = await fetch('https://countriesnow.space/api/v0.1/countries/state/cities', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ country: country, state: state })
                });
                const data = await response.json();
                if (!data.error && data.data) {
                    data.data.forEach(city => {
                        const option = document.createElement("option");
                        option.value = city;
                        option.textContent = city;
                        regDistrictSelect.appendChild(option);
                    });
                }
            } catch (e) {
                console.error("Error fetching cities", e);
            }
        }
    };

    loadCountries();
});
</script>

<?php get_footer(); ?>
