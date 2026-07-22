<?php
/**
 * Template Name: Blood Donors
 *
 * @package Tatkhalsa_Theme
 */
?>
<?php get_header(); ?>
<?php
// Initialize standard WordPress page post object for proper SEO metadata and Yoast support
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
    }
    rewind_posts();
}

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

<style>
/* Dark Mode Enhancements for Reg Forms */
[data-theme="dark"] .modal-content {
    background: #111827 !important;
    color: #f3f4f6 !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
}

[data-theme="dark"] .modal-content h2,
[data-theme="dark"] .modal-content h3,
[data-theme="dark"] .modal-content label {
    color: #f3f4f6 !important;
}

[data-theme="dark"] .modal-content input,
[data-theme="dark"] .modal-content select,
[data-theme="dark"] .modal-content textarea {
    background: #1f2937 !important;
    color: #f3f4f6 !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

[data-theme="dark"] .modal-close {
    color: #f3f4f6 !important;
}
</style>

<div class="blood-donors-page" style="padding: 60px 0; background: var(--body-bg); min-height: 80vh;">
    <div class="container">
        
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="color: var(--text-dark); font-size: 2.5rem; margin-bottom: 10px;">Blood On Call</h1>
            <p class="scroll-reveal" style="color: var(--text-light); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Connect with verified blood donors in your area or register yourself to save lives.
            </p>
            <div style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; margin-top: 18px; padding: 6px 14px; background: rgba(255, 51, 75, 0.08); border: 1px solid rgba(255, 51, 75, 0.25); border-radius: 20px; box-shadow: 0 4px 12px rgba(255, 51, 75, 0.05);">
                <span style="font-size: 0.75rem; background: #ff334b; color: #fff; font-weight: bold; padding: 2px 8px; border-radius: 12px; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 6px rgba(255,51,75,0.3);">Beta</span>
                <span style="color: var(--text-light); font-size: 0.85rem; font-weight: 500;">This section is in public beta. Real-time notifications and features are currently being verified and enhanced.</span>
            </div>
        </div>

        <!-- WordPress Editor Page Content Section -->
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php if ( ! empty( get_the_content() ) ) : ?>
                    <div class="wp-page-editor-content-section" style="max-width: 800px; margin: 0 auto; margin-bottom: 40px; color: var(--text-light); line-height: 1.8; text-align: left; padding: 25px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; font-size: 1.05rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
            <?php rewind_posts(); ?>
        <?php endif; ?>

        <!-- Accept Request Status Banner -->
        <div id="acceptRequestBanner" style="display: none; max-width: 800px; margin: 0 auto 30px auto; padding: 22px; border-radius: 12px; text-align: left; background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.08); box-shadow: 0 10px 25px rgba(0,0,0,0.25); backdrop-filter: blur(8px); overflow: hidden;">
            <div style="display: flex; gap: 18px; align-items: flex-start; flex-wrap: wrap;">
                <div id="acceptRequestIcon" style="font-size: 2.2rem; line-height: 1; min-width: 40px; text-align: center;">✨</div>
                <div style="flex: 1; min-width: 250px;">
                    <h3 id="acceptRequestTitle" style="margin: 0 0 6px 0; font-size: 1.25rem; font-weight: bold; color: #fff;">Processing Request Acceptance...</h3>
                    <p id="acceptRequestMsg" style="margin: 0; font-size: 0.95rem; line-height: 1.5; color: rgba(255,255,255,0.73);"></p>
                </div>
                <button onclick="document.getElementById('acceptRequestBanner').style.display='none'" style="background: rgba(255,255,255,0.05); border: none; color: #fff; font-size: 0.85rem; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: background 0.2s; white-space: nowrap; margin-left: auto;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                    Dismiss
                </button>
            </div>
        </div>

        <div class="blood-actions-menu">
            <button onclick="openDonorRegistrationModal()" class="btn-donor-register">
                🩸 Register as a Donor
            </button>
            <button onclick="openBloodRequestModal()" class="btn-donor-request">
                🚨 Request Blood
            </button>
            <button onclick="openUpdateStatusModal()" class="btn-donor-status">
                🔄 Update My Status
            </button>
            <button onclick="openRemoveDonorModal()" class="btn-donor-remove">
                🗑️ Remove My Name
            </button>
            <button id="btnDonorAdmin" onclick="toggleMasterDataView()" class="btn-donor-admin" style="background: #111111; color: #ffd275; border: 1.5px solid rgba(212,175,55,0.5); padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 15px rgba(212,175,55,0.15); transition: all 0.3s ease; display: none; align-items: center; gap: 8px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.background='#d4af37'; this.style.color='#000'; this.style.boxShadow='0 6px 20px rgba(212,175,55,0.45)';" onmouseout="this.style.transform='translateY(0)'; this.style.background='#111111'; this.style.color='#ffd275'; this.style.boxShadow='0 4px 15px rgba(212,175,55,0.15)';">
                ⚙️ Admin Master Data
            </button>
        </div>

        <!-- Admin Master Data Panel -->
        <div id="masterDataPanel" style="display: none; min-height: 200px; padding: 25px; background: #1a1a1a; border: 2px solid #ff334b; border-radius: 12px; margin-bottom: 45px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">
                <div>
                    <h2 style="color: var(--secondary); margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <span>📂</span> Blood Directory - Administrative Master Data
                    </h2>
                    <p style="color: var(--text-light); font-size: 0.85rem; margin: 5px 0 0 0;">
                        Full donor credentials and live emergency request records.
                    </p>
                </div>
                <button onclick="toggleMasterDataView()" style="background: rgba(255,255,255,0.1); color: #fff; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 0.85rem; font-weight: bold;">
                    ✕ Close Panel
                </button>
            </div>

            <!-- Tabs & Backup Header -->
            <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <div style="display: flex; gap: 10px;">
                    <button id="tabDonorsBtn" onclick="switchAdminTab('donors')" style="padding: 10px 18px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer; font-size: 0.85rem; background: var(--secondary); color: #000; transition: all 0.2s;">
                        🩸 Registered Donors List (<span id="countDonors">0</span>)
                    </button>
                    <button id="tabRequestsBtn" onclick="switchAdminTab('requests')" style="padding: 10px 18px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer; font-size: 0.85rem; background: rgba(255,255,255,0.05); color: var(--text-light); transition: all 0.2s;">
                        🚨 Urgent Blood Requests (<span id="countRequests">0</span>)
                    </button>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <button onclick="window.exportMasterDataBackup()" style="background: rgba(46,204,113,0.15); color: #2ecc71; border: 1px solid rgba(46,204,113,0.3); padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#2ecc71'; this.style.color='#000';" onmouseout="this.style.background='rgba(46,204,113,0.15)'; this.style.color='#2ecc71';">
                        📥 Export JSON Backup
                    </button>
                    <button onclick="document.getElementById('adminImportFileInput').click()" style="background: rgba(52,152,219,0.15); color: #3498db; border: 1px solid rgba(52,152,219,0.3); padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#3498db'; this.style.color='#fff';" onmouseout="this.style.background='rgba(52,152,219,0.15)'; this.style.color='#3498db';">
                        📤 Import JSON Backup
                    </button>
                    <input type="file" id="adminImportFileInput" style="display: none;" accept=".json" onchange="window.handleAdminImport(event)" />
                </div>
            </div>

            <!-- API Integrations Box -->
            <div id="thirdPartyConfigBox" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 16px; border-radius: 8px; margin-bottom: 25px; display: flex; flex-wrap: wrap; gap: 20px; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 1.4rem;">🔗</span>
                    <div>
                        <h4 style="margin: 0; color: var(--secondary); font-size: 0.95rem; font-weight: bold;">API Integrations</h4>
                        <p style="margin: 3px 0 0 0; color: var(--text-light); font-size: 0.78rem;">Configure external API keys and authorization endpoints.</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <button onclick="window.openNewsletterModal();" style="background: rgba(230,126,34,0.15); color: #e67e22; border: 1px solid rgba(230,126,34,0.3); padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#e67e22'; this.style.color='#fff';" onmouseout="this.style.background='rgba(230,126,34,0.15)'; this.style.color='#e67e22';">
                        ✉️ Send Newsletter to Donors
                    </button>
                    <button onclick="alert('WhatsApp API Configuration options are currently set to mock mode.')" style="background: rgba(37,211,102,0.15); color: #25d366; border: 1px solid rgba(37,211,102,0.3); padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#25d366'; this.style.color='#fff';" onmouseout="this.style.background='rgba(37,211,102,0.15)'; this.style.color='#25d366';">
                        💬 WhatsApp API Configuration
                    </button>
                    <button onclick="alert('OAuth configuration will allow third-party logins.')" style="background: rgba(255,184,0,0.15); color: #FFB800; border: 1px solid rgba(255,184,0,0.3); padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#FFB800'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,184,0,0.15)'; this.style.color='#FFB800';">
                        🔑 OAuth Configuration
                    </button>
                </div>
            </div>

            <!-- Auto IP Purging Settings Box -->
            <div id="ipPurgeSettingsBox" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 16px; border-radius: 8px; margin-bottom: 25px; display: flex; flex-wrap: wrap; gap: 20px; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 1.4rem;">🔒</span>
                    <div>
                        <h4 style="margin: 0; color: var(--secondary); font-size: 0.95rem; font-weight: bold;">Auto IP Address Purging</h4>
                        <p style="margin: 3px 0 0 0; color: var(--text-light); font-size: 0.78rem;">Scrub recorded client IP metrics from active records based on custom duration retention thresholds.</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; gap: 8px; color: #fff; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                        <input type="checkbox" id="chkIpPurgeEnabled" onchange="window.savePurgeSettings()" style="transform: scale(1.15); cursor: pointer;" />
                        Enable Auto Purge
                    </label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: rgba(255,255,255,0.6); font-size: 0.8rem;">Threshold:</span>
                        <select id="selIpPurgeDuration" onchange="window.savePurgeSettings()" style="background: #111; color: #fff; border: 1px solid rgba(255,255,255,0.2); padding: 6px 10px; border-radius: 6px; font-size: 0.8rem; outline: none; cursor: pointer;">
                            <option value="7_days">7 Days</option>
                            <option value="15_days">15 Days</option>
                            <option value="30_days">1 Month (30 Days)</option>
                            <option value="never">Never (Keep Forever)</option>
                        </select>
                    </div>
                    <span id="purgeStatusTick" style="color: #2ed573; font-size: 0.82rem; font-weight: bold; opacity: 0; transition: opacity 0.3s; display: flex; align-items: center; gap: 4px;">
                        ✓ Saved
                    </span>
                </div>
            </div>

            <div id="adminLoading" style="text-align: center; padding: 40px; color: var(--text-light);">
                Loading secure administrator records...
            </div>

            <!-- Bulk Action Bar -->
            <div id="bulkActionBar" style="display: none; align-items: center; justify-content: space-between; background: rgba(255,51,75,0.12); border: 1px solid rgba(255,51,75,0.25); padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 1.1rem; filter: drop-shadow(0 2px 4px rgba(255,51,75,0.25));">🗑️</span>
                    <span id="bulkActionText" style="color: #ff4d61; font-weight: bold; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">0 items selected</span>
                </div>
                <button onclick="window.performBulkDelete()" style="background: #ff334b; color: #fff; border: none; padding: 6px 14px; border-radius: 6px; font-weight: bold; font-size: 0.82rem; cursor: pointer; display: flex; align-items: center; gap: 5px; box-shadow: 0 2px 6px rgba(255,51,75,0.3); transition: all 0.2s;" onmouseover="this.style.background='#ff4d61'" onmouseout="this.style.background='#ff334b'">
                    Delete Selected
                </button>
            </div>

            <!-- Active Donors Master Data -->
            <div id="tblDonorsContainer" style="display: none; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 850px; text-align: left; font-size: 0.85rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.1); color: var(--secondary);">
                            <th style="padding: 12px 10px; width: 45px; text-align: center; vertical-align: middle;">
                                <input type="checkbox" id="chkSelectAllDonors" onchange="window.toggleSelectAll(this, 'donors')" style="cursor: pointer; transform: scale(1.15);" />
                            </th>
                            <th style="padding: 12px 10px;">Name</th>
                            <th style="padding: 12px 10px; text-align: center;">Group</th>
                            <th style="padding: 12px 10px;">Email</th>
                            <th style="padding: 12px 10px;">Contact Number</th>
                            <th style="padding: 12px 10px;">Address</th>
                            <th style="padding: 12px 10px;">Status</th>
                            <th style="padding: 12px 10px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tblDonorsBody" style="color: #fff;">
                        <!-- Dynamic Rows -->
                    </tbody>
                </table>
            </div>

            <!-- Active Requests Master Data -->
            <div id="tblRequestsContainer" style="display: none; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 950px; text-align: left; font-size: 0.85rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.1); color: #ff334b;">
                            <th style="padding: 12px 10px; width: 45px; text-align: center; vertical-align: middle;">
                                <input type="checkbox" id="chkSelectAllRequests" onchange="window.toggleSelectAll(this, 'requests')" style="cursor: pointer; transform: scale(1.15);" />
                            </th>
                            <th style="padding: 12px 10px;">Patient Name</th>
                            <th style="padding: 12px 10px; text-align: center;">Group</th>
                            <th style="padding: 12px 10px;">Hospital Name</th>
                            <th style="padding: 12px 10px;">Location Details</th>
                            <th style="padding: 12px 10px;">Contact Details</th>
                            <th style="padding: 12px 10px;">Units Required</th>
                            <th style="padding: 12px 10px;">Urgency</th>
                            <th style="padding: 12px 10px; text-align: center; width: 100px;">Doctor's Slip</th>
                            <th style="padding: 12px 10px; text-align: center;">Status</th>
                            <th style="padding: 12px 10px;">Accepted Volunteer</th>
                            <th style="padding: 12px 10px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tblRequestsBody" style="color: #fff;">
                        <!-- Dynamic Rows -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="donorListAnchor">
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
                    <div class="scroll-reveal" style="background: var(--bg-dark); border-radius: 10px; padding: 15px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); position: relative; border-top: 3px solid #ff334b;">
                        <div style="position: absolute; top: 15px; right: 15px; background: #ff334b; color: #fff; font-weight: bold; padding: 4px 10px; border-radius: 15px; font-size: 0.9rem; box-shadow: 0 2px 6px rgba(255,51,75,0.4);">
                            <?php echo esc_html( $bg ); ?>
                        </div>
                        <h3 style="color: var(--text-dark); margin-bottom: 5px; padding-right: 40px; font-size: 1.1rem; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; justify-content: flex-start;">
                            <?php echo esc_html( get_post_meta( $post_id, 'donor_name', true ) ); ?>
                        </h3>
                        
                        <!-- Visual Verification Badge for Sevadar status -->
                        <?php $is_verified = get_post_meta( $post_id, 'is_verified', true ); if ( $is_verified ) : ?>
                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 8px; flex-wrap: wrap;">
                            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.72rem; font-weight: 700; background: rgba(212, 175, 55, 0.12); color: #d4af37; padding: 3px 8px; border-radius: 12px; border: 1.1px solid rgba(212, 175, 55, 0.4); text-transform: uppercase; letter-spacing: 0.5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 12px; height: 12px; color: #d4af37; flex-shrink: 0;">
                                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12c0 1.357-.6 2.573-1.549 3.397a4.49 4.49 0 01-1.307 3.498 4.49 4.49 0 01-3.497 1.307A4.491 4.491 0 0112 21.75c-1.357 0-2.573-.6-3.397-1.549a4.49 4.49 0 01-3.498-1.307a4.49 4.49 0 01-1.307-3.497A4.491 4.491 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.498a4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 00-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                </svg>
                                Verified Sevadar ✓
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <div style="margin-bottom: 10px; font-size: 0.8rem; color: var(--text-dark); font-weight: 500;">
                            <?php 
                                if ( $availability === 'On Standby' ) echo '🟡 On Standby';
                                elseif ( $availability === 'Resting Phase' ) echo '🔴 Resting Phase';
                                else echo '🟢 Available Now';
                            ?>
                        </div>

                        <!-- Location Section -->
                        <div style="margin-bottom: 10px; font-size: 0.85rem; color: var(--text-dark); line-height: 1.4; display: block !important; visibility: visible !important;">
                            📍 <strong>Location:</strong> 
                            <?php 
                                $country = get_post_meta( $post_id, 'country', true );
                                $state = get_post_meta( $post_id, 'state', true );
                                $district = get_post_meta( $post_id, 'district', true );
                                $loc_parts = array_filter( array_map( 'trim', array( $district, $state, $country ) ) );
                                if ( ! empty( $loc_parts ) ) {
                                    echo esc_html( implode( ', ', $loc_parts ) );
                                } else {
                                    $parts = array_filter( array_map( 'trim', explode( ',', $address ) ) );
                                    if ( count( $parts ) > 3 ) {
                                        $parts = array_slice( $parts, -3 );
                                    }
                                    echo esc_html( implode( ', ', $parts ) );
                                }
                            ?>
                        </div>

                        <div style="margin-bottom: 10px; font-size: 0.8rem; color: var(--text-light); line-height: 1.4; background: rgba(0,0,0,0.03); padding: 8px; border-radius: 6px; text-align: center;">
                            🔒 Privacy Protected
                        </div>
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
            <div style="text-align: center; padding: 50px; background: rgba(0,0,0,0.02); border-radius: 12px; grid-column: 1 / -1;">
                <p style="font-size: 1.2rem; color: var(--text-light); margin-bottom: 20px;">No donors found matching your criteria.</p>
                <button onclick="window.location.href='<?php echo esc_url( get_permalink() ); ?>'" style="background: var(--bg-dark); color: var(--text-dark); border: 1px solid var(--text-dark); padding: 10px 20px; border-radius: 6px; cursor: pointer;">
                    Clear Filters
                </button>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
        </div>

        <div class="scroll-reveal" style="background: var(--bg-dark); padding: 20px; border-radius: 12px; margin-bottom: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);">
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
                <div style="margin-top: 28px;">
                    <button type="submit" style="background: var(--primary); color: var(--white); border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Search</button>
                    <?php if ( isset($_GET['blood_group']) ): ?>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" style="margin-left: 10px; color: var(--text-light); text-decoration: none; font-size: 0.9rem;">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Video Tutorial Section -->
        <div class="scroll-reveal" style="max-width: 800px; margin: 40px auto; padding: 30px; background: rgba(255, 255, 255, 0.02); border: 1.5px dashed rgba(255, 51, 75, 0.2); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; overflow: hidden; background-image: radial-gradient(circle at 10% 20%, rgba(255, 51, 75, 0.02) 0%, transparent 40%);">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; background: rgba(255, 51, 75, 0.1); border-radius: 50%; margin-bottom: 12px; color: #ff334b; font-size: 1.3rem; font-weight: bold;">🎥</div>
                <h2 style="color: var(--text-dark); margin: 0 0 8px 0; font-size: 1.6rem; font-weight: 800; letter-spacing: -0.5px;">Blood On Call: Video Guide</h2>
                <p style="color: var(--text-light); font-size: 0.95rem; max-width: 550px; margin: 0 auto; line-height: 1.5;">
                    Master the system in seconds. Learn how to securely register as a donor, post urgent requisition logs, or verify registry keys.
                </p>
            </div>

            <div style="display: flex; gap: 35px; align-items: center; justify-content: center; flex-wrap: wrap;">
                
                <!-- Premium Smartphone Container (Mobile Portrait Aspect Ratio) -->
                <div style="position: relative; width: 260px; height: 462px; background: #07090e; border: 12px solid #161a23; border-radius: 38px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); overflow: hidden; flex-shrink: 0; outline: 2px solid rgba(255,255,255,0.05);">
                    <!-- Dual notch design bar -->
                    <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 110px; height: 18px; background: #161a23; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px; z-index: 10; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <span style="width: 5px; height: 5px; background: #222530; border-radius: 50%;"></span>
                        <span style="width: 30px; height: 3px; background: #0c0e12; border-radius: 1.5px;"></span>
                    </div>

                    <!-- Custom looping video segment representing mobile tutorial -->
                    <video id="tutorialVideo" loop muted playsinline style="width: 100%; height: 100%; object-fit: cover; background: #000;" onclick="toggleTutorialVideo()">
                        <source src="<?php echo esc_url( get_theme_mod( 'tatkhalsa_blood_video_url', 'https://assets.mixkit.co/videos/preview/mixkit-hand-holding-a-smartphone-with-a-yellow-background-41712-large.mp4' ) ); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <!-- Persistent Sound Control Button (Mute/Unmute option) -->
                    <button id="soundToggleButton" onclick="toggleVideoSound(event)" style="position: absolute; top: 18px; right: 18px; width: 36px; height: 36px; background: rgba(13, 27, 42, 0.85); border: 1.5px solid rgba(255, 51, 75, 0.4); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #fff; font-size: 14px; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); z-index: 20; padding: 0; outline: none; box-shadow: 0 4px 12px rgba(255, 51, 75, 0.25);" title="Toggle Sound (Mute/Unmute)" onmouseover="this.style.transform='scale(1.1)'; this.style.background='rgba(255, 51, 75, 0.15)'" onmouseout="this.style.transform='scale(1)'; this.style.background='rgba(13, 27, 42, 0.85)'">
                        <span id="soundVolIcon" style="display: inline-flex; align-items: center; justify-content: center;">🔇</span>
                    </button>

                    <!-- Interactive overlay on play state -->
                    <div id="videoOverlay" onclick="toggleTutorialVideo()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.45); display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; z-index: 5;">
                        <div id="playBtnCircle" style="width: 54px; height: 54px; background: #ff334b; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 20px rgba(255, 51, 75, 0.4); transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);">
                            <!-- Play Icon Inline -->
                            <svg id="playIconSVG" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 22px; height: 22px; color: #fff; margin-left: 2px;">
                                <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                            </svg>
                            <!-- Pause Icon Inline -->
                            <svg id="pauseIconSVG" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 22px; height: 22px; color: #fff; display: none;">
                                <path fill-rule="evenodd" d="M6.75 5.25a.75.75 0 01.75-.75H9a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H7.5a.75.75 0 01-.75-.75V5.25zm7.5 0A.75.75 0 0115 4.5h1.5a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75V5.25z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span id="playLabel" style="color: #fff; font-size: 0.8rem; font-weight: bold; margin-top: 14px; letter-spacing: 0.5px; text-transform: uppercase; background: rgba(0, 0, 0, 0.65); padding: 5px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">Tap to Watch</span>
                    </div>

                    <!-- Watermark Indicator -->
                    <div style="position: absolute; bottom: 15px; left: 15px; background: rgba(0,0,0,0.7); padding: 4px 10px; border-radius: 6px; display: flex; align-items: center; gap: 6px; font-size: 0.68rem; color: #fff; font-weight: bold; z-index: 4;">
                        <span style="display:inline-block; width:6px; height:6px; background:#ff334b; border-radius:50%; animation: pulse 1.5s infinite;"></span>
                        LIVE PREVIEW
                    </div>
                </div>

                <!-- Step-by-Step Interactive Guide Sidebar -->
                <div style="flex: 1; min-width: 260px;">
                    <h3 style="color: var(--text-dark); font-size: 1.15rem; font-weight: bold; margin-top: 0; margin-bottom: 18px; display:flex; align-items:center; gap:8px;">
                        <span>📝</span> How to Register as a Donor
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        
                        <!-- Step 1 -->
                        <div class="tutorial-timeline-card" style="display: flex; gap: 12px; padding: 12px; border-radius: 10px; transition: all 0.25s ease; border: 1px solid rgba(255,255,255,0.03); background: rgba(255,255,255,0.015);" onmouseover="this.style.background='rgba(255,51,75,0.04)'; this.style.borderColor='rgba(255,51,75,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.015)'; this.style.borderColor='rgba(255,255,255,0.03)'">
                            <span style="font-size: 0.85rem; width: 26px; height: 26px; background: rgba(255, 51, 75, 0.12); color: #ff334b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; border: 1px solid rgba(255,51,75,0.2);">1</span>
                            <div>
                                <h4 style="margin: 0 0 3px 0; color: var(--text-dark); font-size: 0.92rem; font-weight: bold;">
                                    Open the Registration Form
                                </h4>
                                <p style="margin: 0; color: var(--text-light); font-size: 0.8rem; line-height: 1.45;">Click the red <strong style="color: #ff334b;">"Become a Donor"</strong> button located at the top of the Blood Directory tab.</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="tutorial-timeline-card" style="display: flex; gap: 12px; padding: 12px; border-radius: 10px; transition: all 0.25s ease; border: 1px solid rgba(255,255,255,0.03); background: rgba(255,255,255,0.015);" onmouseover="this.style.background='rgba(255,51,75,0.04)'; this.style.borderColor='rgba(255,51,75,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.015)'; this.style.borderColor='rgba(255,255,255,0.03)'">
                            <span style="font-size: 0.85rem; width: 26px; height: 26px; background: rgba(255, 51, 75, 0.12); color: #ff334b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; border: 1px solid rgba(255,51,75,0.2);">2</span>
                            <div>
                                <h4 style="margin: 0 0 3px 0; color: var(--text-dark); font-size: 0.92rem; font-weight: bold;">
                                    Fill in Your Profile Details
                                </h4>
                                <p style="margin: 0; color: var(--text-light); font-size: 0.8rem; line-height: 1.45;">Enter your name, select your blood group, fill in contact info, and select your state/district location.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="tutorial-timeline-card" style="display: flex; gap: 12px; padding: 12px; border-radius: 10px; transition: all 0.25s ease; border: 1px solid rgba(255,255,255,0.03); background: rgba(255,255,255,0.015);" onmouseover="this.style.background='rgba(255,51,75,0.04)'; this.style.borderColor='rgba(255,51,75,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.015)'; this.style.borderColor='rgba(255,255,255,0.03)'">
                            <span style="font-size: 0.85rem; width: 26px; height: 26px; background: rgba(255, 51, 75, 0.12); color: #ff334b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; border: 1px solid rgba(255,51,75,0.2);">3</span>
                            <div>
                                <h4 style="margin: 0 0 3px 0; color: var(--text-dark); font-size: 0.92rem; font-weight: bold;">
                                    Secure Verification & ID
                                </h4>
                                <p style="margin: 0; color: var(--text-light); font-size: 0.8rem; line-height: 1.45;">Verify your registration credentials. Upon successful signup, you will instantly receive your unique <strong style="color: #ff334b;">Secure Donor ID</strong>.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Meta Action bar below step list -->
                    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <span id="videoStatusMessage" style="color: var(--text-light); font-size: 0.8rem; font-style: italic; display: flex; align-items: center; gap: 4px;">
                            <span style="font-size:1rem; line-height:1;">🔊</span> Toggle sound overlay to hear guide audio
                        </span>
                        <button onclick="restartTutorialVideo()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.08); color: var(--text-dark); font-weight: bold; font-size: 0.78rem; padding: 5px 12px; border-radius: 6px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'">
                            🔁 Restart
                        </button>
                    </div>

                </div>

            </div>
        </div>

        <!-- Gurbani Quote Section -->
        <section class="gurbani-quote-section scroll-reveal" style="background: transparent; border: none; padding: 40px 0 20px; margin-top: 20px;">
          <div class="gurbani-quote-container">
            <div class="gurbani-ornament">✧ ✦ ✧</div>
            <div class="gurbani-gurmukhi">ਮਾਨਸ ਕੀ ਜਾਤ ਸਬੈ ਏਕੈ ਪਹਿਚਾਨਬੋ ॥</div>
            <div class="gurbani-translit">maanas kee jaat sabai aekai pehichaanabo ||</div>
            <div class="gurbani-english">"Recognize all of the human race as one."</div>
          </div>
        </section>

        <!-- Disclaimer Section -->
        <div class="scroll-reveal" style="margin-top: 60px; padding: 25px; background: rgba(0,0,0,0.03); border-radius: 12px; border-left: 4px solid #ff334b; font-size: 0.85rem; color: var(--text-light); line-height: 1.6;">
            <strong>Disclaimer:</strong> Tatkhalsa Foundation operates purely as a voluntary community coordination network. We do not run physical blood banks or commercialize medical supplies. All verifications of donor eligibility must be independently validated by certified hospital practitioners at the time of transfusion.
        </div>

        <!-- Technical Support & Bug Reporting -->
        <div style="margin-top: 20px; padding: 15px; background: rgba(0,0,0,0.02); border-radius: 10px; border: 1px dashed rgba(255, 51, 75, 0.2); text-align: center; font-size: 0.85rem; color: var(--text-light); line-height: 1.5;">
            🐛 Encountered any bug or problem? Please tell us at <strong><a href="mailto:tech-team@tatkhalsa.in" style="color: #ff334b; text-decoration: none;">tech-team@tatkhalsa.in</a></strong>
        </div>
    </div>
</div>

<!-- Donor Registration Modal -->
<div class="modal-overlay" id="donorRegModal" style="display: none;">
  <div class="modal-content">
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
        <input type="tel" name="contactDetails" id="contactDetails" required placeholder="e.g. +91 9876543210" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
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

<!-- Newsletter Modal -->
<div class="modal-overlay" id="newsletterModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
  <div class="modal-content" style="max-height: 90vh; overflow-y: auto; background: var(--bg-light); border-radius: 12px; padding: 25px; width: 90%; max-width: 500px; position: relative;">
    <button class="modal-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark);" onclick="document.getElementById('newsletterModal').style.display='none';">&times;</button>
    <h3 style="color: var(--primary); margin-top: 0; display: flex; align-items: center; gap: 8px;">✉️ Send Newsletter to Donors</h3>
    <p style="font-size: 0.9rem; color: var(--text-dark);">Compose a newsletter that will be sent from <strong>info@tatkhalsa.in</strong> to all verified donors in the directory.</p>
    
    <div class="alert" id="newsletterAlert" style="display: none;"></div>

    <form id="newsletterForm" onsubmit="window.sendNewsletter(event)">
      <div class="form-group" style="margin-bottom: 15px;">
        <label for="newsletterTo">To <span id="newsletterToCount" style="font-size: 0.8em; color: #666; font-weight: normal;">(0 Donors)</span></label>
        <textarea id="newsletterTo" name="newsletterTo" readonly placeholder="Fetching donor emails..." style="width: 100%; padding: 12px; background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; font-size: 0.9rem; color: #555; resize: vertical; cursor: not-allowed;" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label for="newsletterSubject">Subject</label>
        <input type="text" id="newsletterSubject" name="newsletterSubject" required placeholder="Newsletter Subject" style="width: 100%; padding: 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; font-size: 1rem; color: #000;" />
      </div>
      <div class="form-group" style="margin-top: 15px;">
        <label for="newsletterBody">Message</label>
        <textarea id="newsletterBody" name="newsletterBody" rows="6" required placeholder="Write your newsletter message here..." style="width: 100%; padding: 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; font-size: 1rem; color: #000; resize: vertical;"></textarea>
      </div>
      <button type="submit" id="newsletterSubmitBtn" style="margin-top: 20px; width: 100%; background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); color: #fff; border: none; font-size: 1rem; font-weight: bold; padding: 14px; border-radius: 8px; cursor: pointer; box-shadow: 0 5px 15px rgba(230,126,34,0.35);">
        Send to All Donors
      </button>
    </form>
  </div>
</div>

<!-- Edit Donor Modal (Master Data Administrative Option) -->
<div class="modal-overlay" id="editDonorModal" style="display: none;">
  <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
    <button class="modal-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark);" onclick="window.closeEditDonorModal()">&times;</button>
    
    <h2 style="color: var(--text-dark); margin-bottom: 20px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <span>📝</span> Edit Registered Donor Details
    </h2>
    
    <form id="editDonorForm" onsubmit="window.saveEditedDonor(event)">
      <input type="hidden" id="editDonorId" />
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Full Name *</label>
        <input type="text" id="editDonorName" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Blood Group *</label>
        <select id="editDonorBloodGroup" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
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
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Email Address *</label>
        <input type="email" id="editDonorEmail" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Contact Number *</label>
        <input type="text" id="editDonorContact" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Street Address / Area *</label>
        <textarea id="editDonorAddress" required rows="2" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333; font-family: inherit;"></textarea>
      </div>

      <div style="margin-bottom: 25px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Availability Status</label>
        <select id="editDonorAvailability" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
            <option value="Available Now">🟢 Available Now</option>
            <option value="On Standby">🟡 On Standby</option>
            <option value="Resting Phase">🔴 Resting Phase</option>
        </select>
      </div>

      <div id="editDonorStatus" style="margin-bottom: 15px; font-size: 0.9rem; padding: 10px; border-radius: 6px; display: none;"></div>
      
      <button type="submit" id="editDonorSaveBtn" style="width: 100%; background: linear-gradient(135deg, #d4af37 0%, #ffd275 100%); color: #000; border: none; font-size: 1rem; font-weight: bold; padding: 14px; border-radius: 8px; cursor: pointer; box-shadow: 0 5px 15px rgba(212,175,55,0.35); transition: opacity 0.2s;">
        Save Changes
      </button>
    </form>
  </div>
</div>

<!-- Edit Request Modal (Master Data Administrative Option) -->
<div class="modal-overlay" id="editRequestModal" style="display: none;">
  <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
    <button class="modal-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark);" onclick="window.closeEditRequestModal()">&times;</button>
    
    <h2 style="color: var(--text-dark); margin-bottom: 20px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <span>📝</span> Edit Urgent Blood Request
    </h2>
    
    <form id="editRequestForm" onsubmit="window.saveEditedRequest(event)">
      <input type="hidden" id="editRequestId" />
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Patient Name *</label>
        <input type="text" id="editReqPatientName" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Blood Group *</label>
        <select id="editReqBloodGroup" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
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
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Hospital Name *</label>
        <input type="text" id="editReqHospital" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Patient Location / Address *</label>
        <input type="text" id="editReqLocation" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Contact Details *</label>
        <input type="text" id="editReqContact" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Units Required *</label>
        <input type="number" id="editReqUnits" min="1" max="50" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Urgency Level *</label>
        <select id="editReqUrgency" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
            <option value="Normal">Normal</option>
            <option value="Urgent">Urgent</option>
            <option value="Critical">Critical (Immediate Seva Need)</option>
        </select>
      </div>

      <div style="margin-bottom: 25px;">
        <label style="display: block; margin-bottom: 6px; color: var(--text-dark); font-weight: bold;">Request Status</label>
        <select id="editReqStatus" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
            <option value="pending">Pending</option>
            <option value="accepted">Accepted (Volunteer Claimed)</option>
            <option value="fulfilled">Fulfilled</option>
        </select>
      </div>

      <div id="editRequestStatus" style="margin-bottom: 15px; font-size: 0.9rem; padding: 10px; border-radius: 6px; display: none;"></div>
      
      <button type="submit" id="editRequestSaveBtn" style="width: 100%; background: linear-gradient(135deg, #ff334b 0%, #ff5d73 100%); color: #fff; border: none; font-size: 1rem; font-weight: bold; padding: 14px; border-radius: 8px; cursor: pointer; box-shadow: 0 5px 15px rgba(255,51,75,0.35); transition: opacity 0.2s;">
        Save Changes
      </button>
    </form>
  </div>
</div>

<!-- Remove Donor Modal -->
<div class="modal-overlay" id="removeDonorModal" style="display: none;">
  <div class="modal-content">
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

<!-- Update Status Modal -->
<div class="modal-overlay" id="updateStatusModal" style="display: none;">
  <div class="modal-content">
    <button class="modal-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark);" onclick="closeUpdateStatusModal()">&times;</button>
    
    <h2 style="color: var(--text-dark); margin-bottom: 20px; text-align: center;">Update My Availability</h2>
    
    <form id="updateStatusForm" method="POST" action="">
      <input type="hidden" name="action" value="update_donor_status">
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">Registered Contact Number</label>
        <p style="font-size: 0.82rem; color: var(--text-light); margin-bottom: 8px;">Enter the exact contact number you used while registering.</p>
        <input type="tel" name="contactNumber" required placeholder="e.g. +91 9876543210" style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
      </div>

      <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; color: var(--text-dark); font-weight: bold;">New Availability Status</label>
        <select name="availabilityStatus" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.2); background: #fff; color: #333;">
            <option value="Available Now">🟢 Available Now</option>
            <option value="On Standby">🟡 On Standby</option>
            <option value="Resting Phase">🔴 Resting Phase</option>
        </select>
      </div>

      <div id="updateStatusStatus" style="margin-bottom: 15px; font-size: 0.9rem; border-radius: 6px; display: none;"></div>
      
      <button type="submit" id="updateStatusBtn" style="width: 100%; background: linear-gradient(135deg, var(--secondary) 0%, #ffdf79 100%); color: #000; border: none; font-size: 1rem; font-weight: bold; padding: 12px; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4); transition: all 0.3s;">
        Update Status
      </button>
    </form>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Certificate Modal -->
<div class="modal-overlay" id="certificateModal" style="display: none;">
  <div class="modal-content">
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
                    TATKHALSA BLOOD ON CALL
                </div>
                
                <p style="color:#555; font-size:18px; font-style: italic; margin:0 0 15px 0;">This certificate is proudly presented to</p>
                
                <h2 id="certDonorName" style="color:#0a2342; font-family: 'Georgia', serif; font-size:52px; font-weight:bold; border-bottom:3px solid #FFB800; display:inline-block; padding:0 40px 10px 40px; margin:0 0 25px 0;">[Name]</h2>
                
                <p style="color:#444; font-size: 18px; margin:0 auto 40px auto; line-height:1.7; max-width: 650px;">
                    in profound recognition of your selfless commitment to saving lives. Your blood donation through the Tatkhalsa Blood On Call stands as a true testament to humanity, compassion, and the spirit of selfless service.
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
/**
 * Video Walkthrough Handlers
 */
function toggleVideoSound(event) {
    if (event) {
        event.stopPropagation();
    }
    const video = document.getElementById('tutorialVideo');
    const soundIcon = document.getElementById('soundVolIcon');
    const btn = document.getElementById('soundToggleButton');
    if (!video || !soundIcon) return;

    if (video.muted) {
        video.muted = false;
        soundIcon.innerText = '🔊';
        if (btn) {
            btn.style.border = '1.5px solid #ff334b';
            btn.style.boxShadow = '0 0 15px rgba(255, 51, 75, 0.6)';
        }
    } else {
        video.muted = true;
        soundIcon.innerText = '🔇';
        if (btn) {
            btn.style.border = '1.5px solid rgba(255, 51, 75, 0.4)';
            btn.style.boxShadow = '0 4px 12px rgba(255, 51, 75, 0.25)';
        }
    }
}

function toggleTutorialVideo() {
    const video = document.getElementById('tutorialVideo');
    const overlay = document.getElementById('videoOverlay');
    const playCircle = document.getElementById('playBtnCircle');
    const playSVG = document.getElementById('playIconSVG');
    const pauseSVG = document.getElementById('pauseIconSVG');
    const label = document.getElementById('playLabel');
    
    if (!video) return;

    if (video.paused) {
        video.play().then(() => {
            if (overlay) overlay.style.background = 'rgba(0,0,0,0.15)';
            if (playCircle) {
                playCircle.style.transform = 'scale(0.8)';
                playCircle.style.opacity = '0';
            }
            if (playSVG) playSVG.style.display = 'none';
            if (pauseSVG) pauseSVG.style.display = 'block';
            if (label) label.style.display = 'none';
        }).catch(e => {
            console.error("Video playback interaction error:", e);
        });
    } else {
        video.pause();
        if (overlay) overlay.style.background = 'rgba(0,0,0,0.45)';
        if (playCircle) {
            playCircle.style.transform = 'scale(1)';
            playCircle.style.opacity = '1';
        }
        if (playSVG) playSVG.style.display = 'block';
        if (pauseSVG) pauseSVG.style.display = 'none';
        if (label) {
            label.style.display = 'block';
            label.innerText = 'Paused';
        }
    }
}

function seekTutorialTo(seconds) {
    const video = document.getElementById('tutorialVideo');
    if (!video) return;
    
    video.currentTime = seconds;
    const statusMsg = document.getElementById('videoStatusMessage');
    
    if (statusMsg) {
        if (seconds === 0) statusMsg.innerText = "Chapter 1: Volunteer Registration (0s)";
        else if (seconds === 15) statusMsg.innerText = "Chapter 2: Emergency Post Request (15s)";
        else if (seconds === 30) statusMsg.innerText = "Chapter 3: Directory Audit & Registry (30s)";
    }

    if (video.paused) {
        toggleTutorialVideo();
    }
}

function restartTutorialVideo() {
    const video = document.getElementById('tutorialVideo');
    if (!video) return;
    
    video.currentTime = 0;
    const statusMsg = document.getElementById('videoStatusMessage');
    if (statusMsg) {
        statusMsg.innerText = "Restarted from beginning (0s)";
    }
    
    if (video.paused) {
        toggleTutorialVideo();
    }
}

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

function openUpdateStatusModal() {
    const modal = document.getElementById("updateStatusModal");
    if(modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }
}

function closeUpdateStatusModal() {
    const modal = document.getElementById("updateStatusModal");
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

    // For Update Status modal background click
    const uModal = document.getElementById("updateStatusModal");
    if(uModal) {
        uModal.addEventListener("click", (e) => {
            if (e.target === uModal) {
                closeUpdateStatusModal();
            }
        });
    }

    // For Edit Donor modal background click
    const edModal = document.getElementById("editDonorModal");
    if(edModal) {
        edModal.addEventListener("click", (e) => {
            if (e.target === edModal) {
                window.closeEditDonorModal();
            }
        });
    }

    // For Edit Request modal background click
    const erModal = document.getElementById("editRequestModal");
    if(erModal) {
        erModal.addEventListener("click", (e) => {
            if (e.target === erModal) {
                window.closeEditRequestModal();
            }
        });
    }

    // Update Status Form Ajax Submission
    const updateForm = document.getElementById("updateStatusForm");
    if(updateForm) {
        updateForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById("updateStatusBtn");
            const statusBox = document.getElementById("updateStatusStatus");
            const originalText = btn.innerHTML;
            
            btn.innerHTML = "Updating...";
            btn.disabled = true;
            statusBox.style.display = "none";
            
            const formData = new FormData(updateForm);
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
                    updateForm.reset();
                    setTimeout(() => {
                        closeUpdateStatusModal();
                        if (typeof window.loadPublicDirectory === "function") {
                            window.loadPublicDirectory();
                            window.fetchMasterData();
                        } else {
                            window.location.reload();
                        }
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

     // Ajax Submission
    const form = document.getElementById("donorRegForm");
    if(form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById("donorRegBtn");
            const statusBox = document.getElementById("donorRegStatus");
            const originalText = btn.innerHTML;
            
            const nameVal = form.querySelector('[name="donorName"]').value;
            const emailVal = form.querySelector('[name="donorEmail"]').value;
            const phoneVal = form.querySelector('[name="contactDetails"]').value;

            const validationErr = typeof window.validateCommonFormInput === "function" 
                ? window.validateCommonFormInput(nameVal, emailVal, phoneVal) 
                : true;

            if (validationErr !== true) {
                statusBox.style.display = "block";
                statusBox.style.padding = "10px";
                statusBox.style.backgroundColor = "rgba(220, 53, 69, 0.1)";
                statusBox.style.borderColor = "rgba(220, 53, 69, 0.2)";
                statusBox.style.color = "#dc3545";
                statusBox.innerHTML = "⚠️ " + validationErr;
                btn.innerHTML = originalText;
                btn.disabled = false;
                return;
            }

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
    let indiaData = null;

    async function loadResources() {
        try {
            // Load countries
            const cRes = await fetch('https://countriesnow.space/api/v0.1/countries/states');
            const cData = await cRes.json();
            if(!cData.error) {
                cachedCountries = cData.data;
            }
        } catch (e) {
            console.error('Error fetching countries:', e);
        }

        try {
            // Load India pure district data
            const iRes = await fetch('https://raw.githubusercontent.com/sab99r/Indian-States-And-Districts/master/states-and-districts.json');
            const iData = await iRes.json();
            if(iData && iData.states) {
                indiaData = iData.states;
            }
        } catch (e) {
            console.error('Error fetching India districts:', e);
        }
        
        populateCountries();
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
        const currentLang = localStorage.getItem('tatkhalsa_lang');
        stateSelect.innerHTML = '<option value="">Any State</option>';
        if (districtSelect) districtSelect.innerHTML = '<option value="">Any District</option>';
        const countryName = countrySelect.value;
        
        if (countryName === 'India' && indiaData) {
            indiaData.forEach(state => {
                const option = document.createElement("option");
                option.value = state.state;
                option.textContent = state.state;
                stateSelect.appendChild(option);
            });
        } else {
            const countryData = cachedCountries.find(c => c.name === countryName);
            if (countryData && countryData.states) {
                countryData.states.forEach(state => {
                    const option = document.createElement("option");
                    option.value = state.name;
                    option.textContent = state.name;
                    stateSelect.appendChild(option);
                });
            }
        }
        if (currentLang === 'pa' && typeof translateNode === 'function') {
            translateNode(stateSelect, 'pa');
            if (districtSelect) translateNode(districtSelect, 'pa');
        }
    };

    window.updateDistricts = async function() {
        if (!districtSelect) return;
        const currentLang = localStorage.getItem('tatkhalsa_lang');
        districtSelect.innerHTML = '<option value="">Any District</option>';
        const country = countrySelect.value;
        const state = stateSelect.value;
        
        if (country === 'India' && indiaData) {
            const stateData = indiaData.find(s => s.state === state);
            if (stateData && stateData.districts) {
                stateData.districts.forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
            if (currentLang === 'pa' && typeof translateNode === 'function') translateNode(districtSelect, 'pa');
            return;
        }

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
            if (currentLang === 'pa' && typeof translateNode === 'function') translateNode(districtSelect, 'pa');
        }
    };

    window.updateRegStates = function() {
        if (!regStateSelect) return;
        const currentLang = localStorage.getItem('tatkhalsa_lang');
        regStateSelect.innerHTML = '<option value="">Select State</option>';
        if (regDistrictSelect) regDistrictSelect.innerHTML = '<option value="">Select District</option>';
        const countryName = regCountrySelect.value;
        
        if (countryName === 'India' && indiaData) {
            indiaData.forEach(state => {
                const option = document.createElement("option");
                option.value = state.state;
                option.textContent = state.state;
                regStateSelect.appendChild(option);
            });
        } else {
            const countryData = cachedCountries.find(c => c.name === countryName);
            if (countryData && countryData.states) {
                countryData.states.forEach(state => {
                    const option = document.createElement("option");
                    option.value = state.name;
                    option.textContent = state.name;
                    regStateSelect.appendChild(option);
                });
            }
        }
        if (currentLang === 'pa' && typeof translateNode === 'function') {
            translateNode(regStateSelect, 'pa');
            if (regDistrictSelect) translateNode(regDistrictSelect, 'pa');
        }
    };

    window.updateRegDistricts = async function() {
        if (!regDistrictSelect) return;
        const currentLang = localStorage.getItem('tatkhalsa_lang');
        regDistrictSelect.innerHTML = '<option value="">Select District</option>';
        const country = regCountrySelect.value;
        const state = regStateSelect.value;
        
        if (country === 'India' && indiaData) {
            const stateData = indiaData.find(s => s.state === state);
            if (stateData && stateData.districts) {
                stateData.districts.forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    regDistrictSelect.appendChild(option);
                });
            }
            if (currentLang === 'pa' && typeof translateNode === 'function') translateNode(regDistrictSelect, 'pa');
            return;
        }

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
            if (currentLang === 'pa' && typeof translateNode === 'function') translateNode(regDistrictSelect, 'pa');
        }
    };

    // --- Blood Network Custom Admin Portal & dynamic UI ---
    let adminTab = 'donors';

    window.toggleMasterDataView = function() {
        const panel = document.getElementById('masterDataPanel');
        if (!panel) return;
        if (panel.style.display === 'none') {
            panel.style.display = 'block';
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            window.fetchMasterData();
        } else {
            panel.style.display = 'none';
        }
    };

    window.selectedIds = [];

    window.toggleSelectAll = function(masterCheckbox, type) {
        const containerId = type === 'donors' ? 'tblDonorsBody' : 'tblRequestsBody';
        const container = document.getElementById(containerId);
        if (!container) return;
        const checkboxes = container.querySelectorAll('.row-select-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
        window.updateSelectedIds();
    };

    window.updateSelectedIds = function() {
        const activeContainerId = adminTab === 'donors' ? 'tblDonorsBody' : 'tblRequestsBody';
        const container = document.getElementById(activeContainerId);
        const selected = [];
        if (container) {
            const checkboxes = container.querySelectorAll('.row-select-checkbox');
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    selected.push(cb.getAttribute('data-id'));
                }
            });
        }
        window.selectedIds = selected;
        
        const bar = document.getElementById('bulkActionBar');
        const txt = document.getElementById('bulkActionText');
        if (bar && txt) {
            if (window.selectedIds.length > 0) {
                bar.style.display = 'flex';
                txt.innerText = `${window.selectedIds.length} ${adminTab === 'donors' ? 'donor(s)' : 'request(s)'} selected`;
            } else {
                bar.style.display = 'none';
            }
        }
        
        const masterDonorCb = document.getElementById('chkSelectAllDonors');
        const masterRequestCb = document.getElementById('chkSelectAllRequests');
        if (container) {
            const checkboxes = container.querySelectorAll('.row-select-checkbox');
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            const isAllChecked = checkboxes.length > 0 && checkedCount === checkboxes.length;
            if (adminTab === 'donors' && masterDonorCb) {
                masterDonorCb.checked = isAllChecked;
            } else if (adminTab === 'requests' && masterRequestCb) {
                masterRequestCb.checked = isAllChecked;
            }
        }
    };

    window.performBulkDelete = async function() {
        if (window.selectedIds.length === 0) return;
        const count = window.selectedIds.length;
        const confirmMsg = adminTab === 'donors' 
            ? `Are you sure you want to permanently delete all ${count} selected registered donors?`
            : `Are you sure you want to permanently delete all ${count} selected emergency blood requests?`;
            
        if (confirm(confirmMsg)) {
            const endpoint = adminTab === 'donors' ? '/api/admin/delete-donor' : '/api/admin/delete-request';
            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: window.selectedIds })
                });
                const r = await res.json();
                if (r.success) {
                    window.selectedIds = [];
                    const bar = document.getElementById('bulkActionBar');
                    if (bar) bar.style.display = 'none';
                    
                    const mDonors = document.getElementById('chkSelectAllDonors');
                    const mRequests = document.getElementById('chkSelectAllRequests');
                    if (mDonors) mDonors.checked = false;
                    if (mRequests) mRequests.checked = false;
                    
                    window.fetchMasterData();
                    window.loadPublicDirectory();
                } else {
                    alert(r.message || "Bulk deletion failed.");
                }
            } catch(e) {
                console.error("Bulk deletion error:", e);
                alert("An error occurred during bulk deletion.");
            }
        }
    };

    window.switchAdminTab = function(tab) {
        // Clear selection state before switching
        window.selectedIds = [];
        const bar = document.getElementById('bulkActionBar');
        if (bar) bar.style.display = 'none';
        
        const mDonors = document.getElementById('chkSelectAllDonors');
        const mRequests = document.getElementById('chkSelectAllRequests');
        if (mDonors) mDonors.checked = false;
        if (mRequests) mRequests.checked = false;
        
        // Reset row checkboxes
        const allCheckboxes = document.querySelectorAll('.row-select-checkbox');
        allCheckboxes.forEach(cb => { cb.checked = false; });

        adminTab = tab;
        const btnDonors = document.getElementById('tabDonorsBtn');
        const btnRequests = document.getElementById('tabRequestsBtn');
        const divDonors = document.getElementById('tblDonorsContainer');
        const divRequests = document.getElementById('tblRequestsContainer');

        if (!btnDonors || !btnRequests || !divDonors || !divRequests) return;

        if (tab === 'donors') {
            btnDonors.style.background = 'var(--secondary)';
            btnDonors.style.color = '#000';
            btnRequests.style.background = 'rgba(255,255,255,0.05)';
            btnRequests.style.color = 'var(--text-light)';
            divDonors.style.display = 'block';
            divRequests.style.display = 'none';
        } else {
            btnRequests.style.background = '#ff334b';
            btnRequests.style.color = '#fff';
            btnDonors.style.background = 'rgba(255,255,255,0.05)';
            btnDonors.style.color = 'var(--text-light)';
            divDonors.style.display = 'none';
            divRequests.style.display = 'block';
        }
    };

    window.getRetentionBadge = function(timestamp) {
        const ageMs = Date.now() - timestamp;
        const daysLeft = Math.max(0, Math.ceil((30 * 24 * 3600 * 1000 - ageMs) / (24 * 3600 * 1000)));
        if (daysLeft <= 0) {
            return `<span style="display:inline-block; font-size:0.75rem; color:#ee5253; padding:2px 8px; border-radius:10px; background:rgba(238,82,83,0.15); font-weight:bold;">Anonymized</span>`;
        } else if (daysLeft < 5) {
            return `<span style="display:inline-block; font-size:0.75rem; color:#ff9f43; padding:2px 8px; border-radius:10px; background:rgba(255,159,67,0.15); font-weight:bold; margin-top:4px;">Expires: ${daysLeft} days</span>`;
        } else {
            return `<span style="display:inline-block; font-size:0.75rem; color:#10ac84; padding:2px 8px; border-radius:10px; background:rgba(16,172,132,0.15); font-weight:bold; margin-top:4px;">Safe: ${daysLeft} days</span>`;
        }
    };

    window.fetchMasterData = async function() {
        const loading = document.getElementById('adminLoading');
        const countDonorsEl = document.getElementById('countDonors');
        const countRequestsEl = document.getElementById('countRequests');
        const tblDonorsBody = document.getElementById('tblDonorsBody');
        const tblRequestsBody = document.getElementById('tblRequestsBody');

        if (!loading) return;
        loading.style.display = 'block';
        document.getElementById('tblDonorsContainer').style.display = 'none';
        document.getElementById('tblRequestsContainer').style.display = 'none';

        try {
            const formData = new FormData(); formData.append('action', 'admin_master_data'); const res = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", { method: 'POST', body: formData });
            const data = await res.json();
            
            if (data.success) {
                loading.style.display = 'none';
                if (countDonorsEl) countDonorsEl.innerText = data.donors.length;
                if (countRequestsEl) countRequestsEl.innerText = data.requests.length;
                
                // Cache records for instantaneous edit-modal population
                window.adminDonorsCache = data.donors;
                window.adminRequestsCache = data.requests;

                // Sync Auto IP Purge settings UI elements loaded from backend state
                if (data.purgeSettings) {
                    const chk = document.getElementById('chkIpPurgeEnabled');
                    const sel = document.getElementById('selIpPurgeDuration');
                    if (chk) chk.checked = !!data.purgeSettings.enabled;
                    if (sel) sel.value = data.purgeSettings.duration || '15_days';
                }

                // Build Donors Rows
                if (tblDonorsBody) {
                    tblDonorsBody.innerHTML = '';
                    if (data.donors.length === 0) {
                        tblDonorsBody.innerHTML = `<tr><td colspan="8" style="padding: 24px; text-align: center; color: var(--text-light);">No registered blood donors found.</td></tr>`;
                    } else {
                        data.donors.forEach(donor => {
                            tblDonorsBody.innerHTML += `
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.01)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 14px 10px; text-align: center; vertical-align: middle;">
                                        <input type="checkbox" class="row-select-checkbox animate-pulse" data-id="${donor.id}" onchange="window.updateSelectedIds()" style="cursor: pointer; transform: scale(1.1);" />
                                    </td>
                                    <td style="padding: 14px 10px; font-weight: bold; color: #fff;">${donor.name}</td>
                                    <td style="padding: 14px 10px; text-align: center;"><span style="background: #ff334b; color:#fff; font-weight:bold; padding:3px 10px; border-radius:12px; font-size:0.75rem;">${donor.bloodGroup}</span></td>
                                    <td style="padding: 14px 10px;"><a href="mailto:${donor.email}" style="color:var(--secondary); text-decoration:none;">${donor.email}</a></td>
                                    <td style="padding: 14px 10px;">
                                        <code>${donor.contact}</code>
                                        <div style="font-size: 0.72rem; color: rgba(255,255,255,0.4); margin-top: 3px; display: flex; align-items: center; gap: 3px;">
                                            <span>💻 IP:</span> <code style="color: rgba(255,255,255,0.55);">${donor.ip || 'N/A'}</code>
                                        </div>
                                    </td>
                                    <td style="padding: 14px 10px; max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${donor.address}">${donor.address}</td>
                                    <td style="padding: 14px 10px; font-size:0.8rem;"><span style="color:#2ed573;">🟢 ${donor.availabilityStatus || 'Available Now'}</span></td>
                                    <td style="padding: 14px 10px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: wrap;">
                                            <button onclick="window.verifyDonorWhatsApp('${donor.id}', '${donor.contact}', '${donor.name}', ${!!donor.isVerified})" style="background: ${donor.isVerified ? 'rgba(37, 211, 102, 1)' : 'rgba(37, 211, 102, 0.15)'}; color: ${donor.isVerified ? '#fff' : '#25D366'}; border: 1px solid rgba(37, 211, 102, 0.5); padding: 5px 10px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: bold; transition: all 0.2s;" onmouseover="this.style.background='#25D366'; this.style.color='#fff';" onmouseout="this.style.background='${donor.isVerified ? 'rgba(37, 211, 102, 1)' : 'rgba(37, 211, 102, 0.15)'}'; this.style.color='${donor.isVerified ? '#fff' : '#25D366'}';">${donor.isVerified ? 'Verified ✓' : 'Verify (WA)'}</button>
                                            <button onclick="window.openEditDonorModal('${donor.id}')" style="background: rgba(212,175,55,0.15); color: #ffd275; border: 1px solid rgba(212,175,55,0.3); padding: 5px 10px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: bold; transition: all 0.2s;" onmouseover="this.style.background='#d4af37'; this.style.color='#000';" onmouseout="this.style.background='rgba(212,175,55,0.15)'; this.style.color='#ffd275';">Edit</button>
                                            <button onclick="window.deleteDonor('${donor.id}')" style="background: rgba(255,51,75,0.1); color: #ff334b; border: 1px solid rgba(255,51,75,0.25); padding: 5px 10px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: bold; transition: all 0.2s;" onmouseover="this.style.background='#ff334b'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,51,75,0.1)'; this.style.color='#ff334b';">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                }

                // Build Requests Rows
                if (tblRequestsBody) {
                    tblRequestsBody.innerHTML = '';
                    if (data.requests.length === 0) {
                        tblRequestsBody.innerHTML = `<tr><td colspan="12" style="padding: 24px; text-align: center; color: var(--text-light);">No emergency blood requests found.</td></tr>`;
                    } else {
                        const maskPhone = (phone) => {
                            if (!phone) return 'N/A';
                            const trimmed = phone.trim();
                            const match = trimmed.match(/^(\+91|91)?\s*(\d{10})$/);
                            if (match) {
                                const prefix = match[1] || '+91';
                                const digits = match[2];
                                return `${prefix} ******${digits.slice(-4)}`;
                            }
                            if (trimmed.length > 7) {
                                return trimmed.slice(0, 3) + ' ******' + trimmed.slice(-4);
                            }
                            return '******' + trimmed.slice(-4);
                        };

                        data.requests.forEach(req => {
                            const statusVal = req.status || 'pending';
                            let statusHtml = '';
                            if (statusVal === 'pending') {
                                statusHtml = `<span style="background: #2ced73; color: #0a2342; font-weight: bold; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; display: inline-block; box-shadow: 0 2px 4px rgba(46,213,115,0.15);">Pending</span>`;
                            } else if (statusVal === 'accepted') {
                                statusHtml = `<span style="background: #0A327D; color: #ffffff; font-weight: bold; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; display: inline-block; box-shadow: 0 2px 4px rgba(10,50,125,0.15);">Accepted</span>`;
                            } else if (statusVal === 'fulfilled') {
                                statusHtml = `<span style="background: #555555; color: #ffffff; font-weight: bold; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">fulfilled</span>`;
                            }

                            let volunteerHtml = `<span style="color: rgba(255,255,255,0.4); font-style: italic; font-size: 0.8rem;">No active claim</span>`;
                            if (statusVal === 'accepted' && req.acceptedByDonorId) {
                                const volunteer = data.donors.find(d => d.id === req.acceptedByDonorId);
                                if (volunteer) {
                                    volunteerHtml = `
                                        <div style="font-size: 0.8rem; line-height: 1.4;">
                                            <strong style="color: var(--secondary);">👤 ${volunteer.name}</strong><br>
                                            <code style="color: rgba(255,255,255,0.73); font-size: 0.75rem;">📞 ${maskPhone(volunteer.contact)}</code>
                                        </div>
                                    `;
                                }
                            }

                            tblRequestsBody.innerHTML += `
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.01)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 14px 10px; text-align: center; vertical-align: middle;">
                                        <input type="checkbox" class="row-select-checkbox" data-id="${req.id}" onchange="window.updateSelectedIds()" style="cursor: pointer; transform: scale(1.1);" />
                                    </td>
                                    <td style="padding: 14px 10px; font-weight: bold; color: #fff;">${req.patientName}</td>
                                    <td style="padding: 14px 10px; text-align: center;"><span style="background: #ff334b; color:#fff; font-weight:bold; padding:3px 10px; border-radius:12px; font-size:0.75rem;">${req.bloodGroup}</span></td>
                                    <td style="padding: 14px 10px;">${req.hospitalName}</td>
                                    <td style="padding: 14px 10px; max-width:180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${req.patientLocation}">${req.patientLocation}</td>
                                    <td style="padding: 14px 10px;">
                                        <code>${req.contactDetails}</code>
                                        <div style="font-size: 0.72rem; color: rgba(255,255,255,0.4); margin-top: 3px; display: flex; align-items: center; gap: 3px;">
                                            <span>💻 IP:</span> <code style="color: rgba(255,255,255,0.55);">${req.ip || 'N/A'}</code>
                                        </div>
                                    </td>
                                    <td style="padding: 14px 10px; font-weight:bold;">${req.unitsRequired} Unit(s)</td>
                                    <td style="padding: 14px 10px;"><span style="color:#ff334b; font-weight:bold; font-size:0.8rem;">🚨 ${req.urgency}</span></td>
                                    <td style="padding: 14px 10px; text-align: center; vertical-align: middle;">
                                        ${req.doctorSlipUrl ? `
                                            <a href="${req.doctorSlipUrl}" target="_blank" title="Click to view physician request slip photo">
                                                <img src="${req.doctorSlipUrl}" style="width: 42px; height: 42px; border-radius: 6px; object-fit: cover; border: 1.5px solid #ff334b; cursor: pointer; transition: transform 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.3);" onmouseover="this.style.transform='scale(1.25)'" onmouseout="this.style.transform='scale(1)'" />
                                            </a>
                                        ` : `
                                            <span style="color: var(--text-light); font-size: 0.75rem; font-style: italic;">No attachment</span>
                                        `}
                                    </td>
                                    <td style="padding: 14px 10px; text-align: center; vertical-align: middle;">${statusHtml}</td>
                                    <td style="padding: 14px 10px; vertical-align: middle;">${volunteerHtml}</td>
                                    <td style="padding: 14px 10px;">
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: stretch; justify-content: center;">
                                            <button onclick="window.openEditRequestModal('${req.id}')" style="background: rgba(212,175,55,0.15); color: #ffd275; border: 1px solid rgba(212,175,55,0.3); padding: 5px 8px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: bold; transition: all 0.2s; text-align: center;" onmouseover="this.style.background='#d4af37'; this.style.color='#000';" onmouseout="this.style.background='rgba(212,175,55,0.15)'; this.style.color='#ffd275';">Edit</button>
                                            ${statusVal !== 'fulfilled' ? `
                                                <button onclick="window.fulfillRequest('${req.id}')" style="background: rgba(46,213,115,0.1); color: #2ced73; border: 1px solid rgba(46,213,115,0.3); padding: 5px 8px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: bold; transition: all 0.2s; text-align: center;" onmouseover="this.style.background='#2ced73'; this.style.color='#0a2342';" onmouseout="this.style.background='rgba(46,213,115,0.1)'; this.style.color='#2ced73';">Fulfill</button>
                                            ` : ''}
                                            <button onclick="window.deleteRequest('${req.id}')" style="background: rgba(255,51,75,0.1); color: #ff334b; border: 1px solid rgba(255,51,75,0.25); padding: 5px 8px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: bold; transition: all 0.2s; text-align: center;" onmouseover="this.style.background='#ff334b'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,51,75,0.1)'; this.style.color='#ff334b';">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                }

                window.switchAdminTab(adminTab);
            }
        } catch(err) {
            console.error(err);
            if (loading) loading.innerText = 'Failed to load credentials directory. Please try again.';
        }
    };

    // --- Auto IP Purging Settings Save Function ---
    window.savePurgeSettings = async function() {
        const enabled = document.getElementById('chkIpPurgeEnabled').checked;
        const duration = document.getElementById('selIpPurgeDuration').value;
        const tick = document.getElementById('purgeStatusTick');

        try {
            const res = await fetch('/api/admin/purge-settings', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ enabled, duration })
            });
            const data = await res.json();
            if (data.success) {
                if (tick) {
                    tick.style.opacity = '1';
                    setTimeout(() => {
                        tick.style.opacity = '0';
                    }, 2000);
                }
                // Instantly fetch master data so that if the list was reduced/purged, the state updates in-place.
                window.fetchMasterData();
            }
        } catch(e) {
            console.error('Failed to update purging settings:', e);
        }
    };

    // --- Edit Donor Modal Interactions & Save Option ---
    window.openEditDonorModal = function(id) {
        if (!window.adminDonorsCache) return;
        const donor = window.adminDonorsCache.find(d => d.id === id);
        if (!donor) return;

        document.getElementById('editDonorId').value = donor.id;
        document.getElementById('editDonorName').value = donor.name || '';
        document.getElementById('editDonorBloodGroup').value = donor.bloodGroup || '';
        document.getElementById('editDonorEmail').value = donor.email || '';
        document.getElementById('editDonorContact').value = donor.contact || '';
        document.getElementById('editDonorAddress').value = donor.address || '';
        document.getElementById('editDonorAvailability').value = donor.availabilityStatus || 'Available Now';

        const statusDiv = document.getElementById('editDonorStatus');
        if (statusDiv) statusDiv.style.display = 'none';

        const modal = document.getElementById('editDonorModal');
        if (modal) modal.style.display = 'flex';
    };

    window.closeEditDonorModal = function() {
        const modal = document.getElementById('editDonorModal');
        if (modal) modal.style.display = 'none';
    };

    window.openNewsletterModal = function() {
        document.getElementById('newsletterModal').style.display = 'flex';
        const verifiedDonors = window.adminDonorsCache ? window.adminDonorsCache.filter(d => d.email && d.email.trim() !== '') : [];
        const emails = verifiedDonors.map(d => d.email).join(', ');
        const count = verifiedDonors.length;
        document.getElementById('newsletterTo').value = emails || 'No verified emails found in directory.';
        document.getElementById('newsletterToCount').innerText = `(${count} Donor${count !== 1 ? 's' : ''})`;
    };

    window.sendNewsletter = async function(e) {
        if (e) e.preventDefault();
        const btn = document.getElementById('newsletterSubmitBtn');
        const alertBox = document.getElementById('newsletterAlert');
        const subject = document.getElementById('newsletterSubject').value;
        const message = document.getElementById('newsletterBody').value;

        btn.disabled = true;
        btn.innerText = 'Sending...';
        alertBox.style.display = 'none';

        try {
            const formData = new FormData();
            formData.append('action', 'send_donor_newsletter');
            formData.append('subject', subject);
            formData.append('message', message);

            const response = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            alertBox.style.display = 'block';
            if (data.success) {
                alertBox.className = 'alert alert-success';
                alertBox.innerText = data.data.message || 'Newsletter sent successfully.';
                document.getElementById('newsletterForm').reset();
                setTimeout(() => {
                    document.getElementById('newsletterModal').style.display = 'none';
                    alertBox.style.display = 'none';
                }, 3000);
            } else {
                alertBox.className = 'alert alert-danger';
                alertBox.innerText = data.data.message || 'Failed to send newsletter.';
            }
        } catch (err) {
            alertBox.style.display = 'block';
            alertBox.className = 'alert alert-danger';
            alertBox.innerText = 'Network error occurred.';
        } finally {
            btn.disabled = false;
            btn.innerText = 'Send to All Donors';
        }
    };

    window.saveEditedDonor = async function(e) {
        if (e) e.preventDefault();
        
        const saveBtn = document.getElementById('editDonorSaveBtn');
        const statusDiv = document.getElementById('editDonorStatus');
        
        if (saveBtn) saveBtn.disabled = true;
        if (statusDiv) {
            statusDiv.style.display = 'block';
            statusDiv.style.background = 'rgba(255,255,255,0.05)';
            statusDiv.style.color = '#fff';
            statusDiv.innerText = 'Synchronizing modification with database...';
        }

        const payload = {
            id: document.getElementById('editDonorId').value,
            name: document.getElementById('editDonorName').value,
            bloodGroup: document.getElementById('editDonorBloodGroup').value,
            email: document.getElementById('editDonorEmail').value,
            contact: document.getElementById('editDonorContact').value,
            contactDetails: document.getElementById('editDonorContact').value,
            address: document.getElementById('editDonorAddress').value,
            availabilityStatus: document.getElementById('editDonorAvailability').value
        };

        try {
            const res = await fetch('/api/admin/edit-donor', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                if (statusDiv) {
                    statusDiv.style.background = 'rgba(46,213,115,0.15)';
                    statusDiv.style.color = '#2ced73';
                    statusDiv.innerText = '✓ Donor entry successfully synchronized!';
                }
                setTimeout(() => {
                    window.closeEditDonorModal();
                    window.fetchMasterData();
                    if (window.loadPublicDirectory) window.loadPublicDirectory();
                }, 1000);
            } else {
                throw new Error(data.message || 'Synchronization failure.');
            }
        } catch(err) {
            if (statusDiv) {
                statusDiv.style.background = 'rgba(255,51,75,0.15)';
                statusDiv.style.color = '#ff334b';
                statusDiv.innerText = 'Error: ' + err.message;
            }
        } finally {
            if (saveBtn) saveBtn.disabled = false;
        }
    };

    // --- Edit Request Modal Interactions & Save Option ---
    window.openEditRequestModal = function(id) {
        if (!window.adminRequestsCache) return;
        const req = window.adminRequestsCache.find(r => r.id === id);
        if (!req) return;

        document.getElementById('editRequestId').value = req.id;
        document.getElementById('editReqPatientName').value = req.patientName || '';
        document.getElementById('editReqBloodGroup').value = req.bloodGroup || '';
        document.getElementById('editReqHospital').value = req.hospitalName || '';
        document.getElementById('editReqLocation').value = req.patientLocation || '';
        document.getElementById('editReqContact').value = req.contactDetails || '';
        document.getElementById('editReqUnits').value = req.unitsRequired || 1;
        document.getElementById('editReqUrgency').value = req.urgency || 'Normal';
        document.getElementById('editReqStatus').value = req.status || 'pending';

        const statusDiv = document.getElementById('editRequestStatus');
        if (statusDiv) statusDiv.style.display = 'none';

        const modal = document.getElementById('editRequestModal');
        if (modal) modal.style.display = 'flex';
    };

    window.closeEditRequestModal = function() {
        const modal = document.getElementById('editRequestModal');
        if (modal) modal.style.display = 'none';
    };

    window.saveEditedRequest = async function(e) {
        if (e) e.preventDefault();
        
        const saveBtn = document.getElementById('editRequestSaveBtn');
        const statusDiv = document.getElementById('editRequestStatus');
        
        if (saveBtn) saveBtn.disabled = true;
        if (statusDiv) {
            statusDiv.style.display = 'block';
            statusDiv.style.background = 'rgba(255,255,255,0.05)';
            statusDiv.style.color = '#fff';
            statusDiv.innerText = 'Synchronizing modification with database...';
        }

        const payload = {
            id: document.getElementById('editRequestId').value,
            patientName: document.getElementById('editReqPatientName').value,
            bloodGroup: document.getElementById('editReqBloodGroup').value,
            hospitalName: document.getElementById('editReqHospital').value,
            patientLocation: document.getElementById('editReqLocation').value,
            contactDetails: document.getElementById('editReqContact').value,
            unitsRequired: parseInt(document.getElementById('editReqUnits').value, 10),
            urgency: document.getElementById('editReqUrgency').value,
            status: document.getElementById('editReqStatus').value
        };

        try {
            const res = await fetch('/api/admin/edit-request', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                if (statusDiv) {
                    statusDiv.style.background = 'rgba(46,213,115,0.15)';
                    statusDiv.style.color = '#2ced73';
                    statusDiv.innerText = '✓ Blood request entry successfully synchronized!';
                }
                setTimeout(() => {
                    window.closeEditRequestModal();
                    window.fetchMasterData();
                }, 1000);
            } else {
                throw new Error(data.message || 'Synchronization failure.');
            }
        } catch(err) {
            if (statusDiv) {
                statusDiv.style.background = 'rgba(255,51,75,0.15)';
                statusDiv.style.color = '#ff334b';
                statusDiv.innerText = 'Error: ' + err.message;
            }
        } finally {
            if (saveBtn) saveBtn.disabled = false;
        }
    };

    window.verifyDonorWhatsApp = async function(id, contact, name, isVerified) {
        if (!contact) {
            alert('No contact number available for this donor.');
            return;
        }

        // Setup WhatsApp contact
        let phone = contact.replace(/\D/g, ''); // Remove non-numeric characters
        if (phone.length === 10) {
            phone = '91' + phone; // Default to India prefix if 10 digits
        }

        const message = encodeURIComponent(`Sat Sri Akal ${name} Ji, this is a volunteer verification message from the Blood On Call network (Tatkhalsa Foundation). Please confirm your availability and blood group. Waheguru Ji Ka Khalsa, Waheguru Ji Ki Fateh.`);
        const waLink = `https://api.whatsapp.com/send?phone=${phone}&text=${message}`;

        // Open WA immediately to avoid popup blocker on async delayed execution
        window.open(waLink, '_blank');

        // Toggle verification in the backend if admin desires
        if (confirm(`Do you want to toggle the verification status for ${name} in the system database?\n\n(Current: ${isVerified ? 'Verified' : 'Unverified'})`)) {
            try {
                const res = await fetch('/api/admin/verify-donor', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, isVerified: !isVerified })
                });
                const r = await res.json();
                if (r.success) {
                    window.fetchMasterData();
                    window.loadPublicDirectory();
                }
            } catch(e) {
                console.error("Error verifying donor.", e);
            }
        }
    };

    window.deleteDonor = async function(id) {
        if (confirm('Are you sure you want to permanently delete this donor from directory?')) {
            try {
                const res = await fetch('/api/admin/delete-donor', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const r = await res.json();
                if (r.success) {
                    window.fetchMasterData();
                    window.loadPublicDirectory();
                }
            } catch(e) { alert("Error deleting donor."); }
        }
    };

    window.deleteRequest = async function(id) {
        if (confirm('Are you sure you want to permanently delete this blood request log?')) {
            try {
                const res = await fetch('/api/admin/delete-request', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const r = await res.json();
                if (r.success) {
                    window.fetchMasterData();
                }
            } catch(e) { alert("Error deleting request."); }
        }
    };

    window.fulfillRequest = async function(id) {
        if (confirm('Are you sure you want to mark this blood request as Fulfilled?')) {
            try {
                const res = await fetch('/api/admin/fulfill-request', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const r = await res.json();
                if (r.success) {
                    window.fetchMasterData();
                }
            } catch(e) { alert("Error marking request as fulfilled."); }
        }
    };

    window.exportMasterDataBackup = async function() {
        try {
            const formData = new FormData(); formData.append('action', 'admin_master_data'); const res = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                const exportObj = {
                    version: "1.0",
                    exported_at: new Date().toISOString(),
                    donors: data.donors,
                    requests: data.requests
                };
                const blob = new Blob([JSON.stringify(exportObj, null, 2)], { type: "application/json" });
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = `tatkhalsa-blood-data-backup-${new Date().toISOString().slice(0,10)}.json`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            } else {
                alert("Failed to fetch fresh records for export.");
            }
        } catch(e) {
            alert("Error exporting master data backup.");
        }
    };

    window.handleAdminImport = function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = async function(e) {
            try {
                const rawJson = e.target.result;
                const importObj = JSON.parse(rawJson);

                if (!importObj.donors || !importObj.requests) {
                    alert("Invalid backup file. The JSON must contain 'donors' and 'requests' arrays.");
                    return;
                }

                if (!confirm(`Are you sure you want to import ${importObj.donors.length} donors and ${importObj.requests.length} requests into the database? Existing identical entries will be kept up to date.`)) {
                    event.target.value = '';
                    return;
                }

                const res = await fetch('/api/admin/import-data', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        donors: importObj.donors,
                        requests: importObj.requests
                    })
                });
                const r = await res.json();
                if (r.success) {
                    alert(`Backup file imported successfully!\n- Donors Loaded: ${r.donors_imported || importObj.donors.length}\n- Requests Loaded: ${r.requests_imported || importObj.requests.length}`);
                    window.fetchMasterData();
                    if (typeof window.loadPublicDirectory === "function") {
                        window.loadPublicDirectory();
                    }
                } else {
                    alert(r.message || "Failed to import data.");
                }
            } catch(err) {
                alert("Error parsing backup JSON file. Make sure it is a valid JSON exported backup.");
            }
            event.target.value = '';
        };
        reader.readAsText(file);
    };

    window.loadPublicDirectory = async function() {
        const urlParams = new URLSearchParams(window.location.search);
        const filterGroup = urlParams.get('blood_group') || '';
        const filterDistrict = urlParams.get('district') || '';
        const filterAddress = (urlParams.get('address') || '').toLowerCase();

        try {
            const formData = new FormData(); formData.append('action', 'admin_master_data'); const res = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", { method: 'POST', body: formData });
            const data = await res.json();
            
            if (data.success && data.donors) {
                let filtered = data.donors;
                
                if (filterGroup) {
                    filtered = filtered.filter(d => d.bloodGroup === filterGroup);
                }
                if (filterDistrict) {
                    filtered = filtered.filter(d => d.address.includes(filterDistrict));
                }
                if (filterAddress) {
                    filtered = filtered.filter(d => d.address.toLowerCase().includes(filterAddress) || d.name.toLowerCase().includes(filterAddress));
                }

                const anchor = document.getElementById('donorListAnchor');
                if (anchor) {
                    anchor.innerHTML = '';
                    if (filtered.length === 0) {
                        anchor.style.display = 'block';
                        anchor.innerHTML = `
                            <div style="text-align: center; padding: 50px; background: rgba(0,0,0,0.02); border-radius: 12px; grid-column: 1 / -1; width:100%;">
                                <p style="font-size: 1.2rem; color: var(--text-light); margin-bottom: 20px;">No donors found matching your criteria.</p>
                                <button onclick="window.location.href='/blood-on-call'" style="background: var(--bg-dark); color: var(--text-dark); border: 1px solid var(--text-dark); padding: 10px 20px; border-radius: 6px; cursor: pointer;">
                                    Reset Search
                                </button>
                            </div>
                        `;
                    } else {
                        anchor.style.display = 'grid';
                        anchor.style.gridTemplateColumns = 'repeat(auto-fit, minmax(280px, 1fr))';
                        anchor.style.gap = '15px';
                        anchor.style.maxWidth = '800px';
                        anchor.style.margin = '0 auto';
                        
                        filtered.forEach(donor => {
                            let statusText = '🟢 Available Now';
                            if (donor.availabilityStatus === 'On Standby') {
                                statusText = '🟡 On Standby';
                            } else if (donor.availabilityStatus === 'Resting Phase') {
                                statusText = '🔴 Resting Phase';
                            }
                            
                            let displayAddress = 'Punjab, India';
                            if (donor.address) {
                                const parts = donor.address.split(',').map(p => p.trim()).filter(Boolean);
                                if (parts.length > 3) {
                                    displayAddress = parts.slice(-3).join(', ');
                                } else {
                                    displayAddress = parts.join(', ');
                                }
                            }

                            const verifiedBadge = donor.isVerified ? `
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 8px; flex-wrap: wrap;">
                                    <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.72rem; font-weight: 700; background: rgba(212, 175, 55, 0.12); color: #d4af37; padding: 3px 8px; border-radius: 12px; border: 1.1px solid rgba(212, 175, 55, 0.4); text-transform: uppercase; letter-spacing: 0.5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 12px; height: 12px; color: #d4af37; flex-shrink: 0;">
                                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12c0 1.357-.6 2.573-1.549 3.397a4.49 4.49 0 01-1.307 3.498 4.49 4.49 0 01-3.497 1.307A4.491 4.491 0 0112 21.75c-1.357 0-2.573-.6-3.397-1.549a4.49 4.49 0 01-3.498-1.307a4.49 4.49 0 01-1.307-3.497A4.491 4.491 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.498a4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 00-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                        </svg>
                                        Verified Sevadar ✓
                                    </span>
                                </div>
                            ` : '';

                            anchor.innerHTML += `
                                <div class="scroll-reveal" style="background: var(--bg-dark); border-radius: 10px; padding: 15px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); position: relative; border-top: 3px solid #ff334b;">
                                    <div style="position: absolute; top: 15px; right: 15px; background: #ff334b; color: #fff; font-weight: bold; padding: 4px 10px; border-radius: 15px; font-size: 0.9rem; box-shadow: 0 2px 6px rgba(255,51,75,0.4);">
                                        ${donor.bloodGroup}
                                    </div>
                                    <h3 style="color: var(--text-dark); margin-bottom: 5px; padding-right: 40px; font-size: 1.1rem;">${donor.name}</h3>
                                    ${verifiedBadge}
                                    <div style="margin-bottom: 10px; font-size: 0.8rem; color: var(--text-dark); font-weight: 500;">
                                        ${statusText}
                                    </div>

                                    <div style="margin-bottom: 10px; font-size: 0.85rem; color: var(--text-dark); line-height: 1.4; display: block !important; visibility: visible !important;">
                                        📍 <strong>Location:</strong> ${displayAddress}
                                    </div>

                                    <div style="margin-bottom: 10px; font-size: 0.8rem; color: var(--text-light); line-height: 1.4; background: rgba(0,0,0,0.03); padding: 8px; border-radius: 6px; text-align: center;">
                                        🔒 Privacy Protected
                                    </div>
                                </div>
                            `;
                        });
                    }
                    
                    // Re-trigger scroll reveal for newly added elements
                    if (typeof reveal === 'function') {
                        setTimeout(reveal, 50);
                    }
                    
                    // Apply translations to newly loaded content
                    const currentLang = localStorage.getItem('tatkhalsa_lang');
                    if (currentLang === 'pa' && typeof translateNode === 'function') {
                        translateNode(anchor, 'pa');
                    }
                }
            }
        } catch(e) {
            console.error("Error loading directory", e);
        }
    };

    async function checkAcceptRequestQuery() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('accept') || urlParams.has('accept_request')) {
            const req_id = urlParams.get('req_id') || urlParams.get('req') || urlParams.get('id');
            const donor_id = urlParams.get('donor_id') || urlParams.get('donor') || 'general';

            if (!req_id) {
                return;
            }

            const banner = document.getElementById('acceptRequestBanner');
            const icon = document.getElementById('acceptRequestIcon');
            const title = document.getElementById('acceptRequestTitle');
            const msg = document.getElementById('acceptRequestMsg');

            if (!banner) return;

            banner.style.display = 'block';
            banner.style.borderLeft = '5px solid #0A327D';
            banner.style.background = 'rgba(10, 50, 125, 0.08)';
            if (icon) icon.innerText = '⌛';
            if (title) title.innerText = 'Verifying Request Fulfillments...';
            if (msg) msg.innerText = 'Connecting to blood network registry to confirm your request acceptance. Please standby.';

            try {
                let responseData;
                const isLocalMock = window.location.hostname.includes('localhost') || 
                                    window.location.hostname.includes('.run.app') || 
                                    window.location.hostname.includes('aistudio');

                if (isLocalMock) {
                    const res = await fetch('/api/admin/accept-request', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ req_id, donor_id })
                    });
                    responseData = await res.json();
                } else {
                    const formData = new FormData();
                    formData.append('action', 'accept_blood_request');
                    formData.append('req_id', req_id);
                    formData.append('donor_id', donor_id);
                    
                    const res = await fetch('/wp-admin/admin-ajax.php', {
                        method: 'POST',
                        body: formData
                    });
                    responseData = await res.json();
                }

                const isSuccess = responseData.success || (responseData.data && responseData.data.success);
                const isAlreadyAccepted = responseData.already_accepted_by_you || (responseData.data && responseData.data.already_accepted_by_you);
                const messageText = responseData.message || (responseData.data && responseData.data.message) || responseData.error || '';

                // Normalize message text based on the user's specific request
                let popupMessage = messageText;
                if (isAlreadyAccepted || messageText.toLowerCase().includes('already') || !isSuccess) {
                    popupMessage = "its already accepted thanks for your efforts We appreciate your time";
                } else {
                    popupMessage = "thank you not accepting request please get in touch with the one who required";
                }

                if (isSuccess && !isAlreadyAccepted) {
                    banner.style.borderLeft = '5px solid #2ced73';
                    banner.style.background = 'rgba(46, 213, 115, 0.08)';
                    if (icon) icon.innerText = '❤️';
                    if (title) title.innerText = 'Noble Response Registered!';
                    if (msg) msg.innerHTML = `<strong>${popupMessage}</strong>`;
                } else {
                    banner.style.borderLeft = '5px solid #ff334b';
                    banner.style.background = 'rgba(255, 51, 75, 0.08)';
                    if (icon) icon.innerText = '🛡️';
                    if (title) title.innerText = 'Blood Request Handled';
                    if (msg) msg.innerHTML = `<strong>${popupMessage}</strong>`;
                }

                // Auto-refresh admin panel data if active
                if (typeof window.fetchMasterData === 'function') {
                    window.fetchMasterData();
                }
            } catch (err) {
                banner.style.borderLeft = '5px solid #ff9f43';
                banner.style.background = 'rgba(255, 159, 67, 0.08)';
                if (icon) icon.innerText = '⚠️';
                if (title) title.innerText = 'Sync Timeout';
                if (msg) msg.innerText = 'Request was not authenticated. It may have already been claimed or completed. Contact support as fallback.';
                console.error(err);
            }
        }
    }

    // Override public submission refresh logic
    const oldOnSubmit = window.submitDonorRegistration;
    // We run loadPublicDirectory on load
    setTimeout(() => {
        window.loadPublicDirectory();
        checkAcceptRequestQuery();
        
        // Auto open master data administrative panel if requested via query parameter
        const urlParams = window.location ? new URLSearchParams(window.location.search) : null;
        if (urlParams && (urlParams.get('admin') === 'true' || urlParams.get('openModal') === 'admin')) {
            const btnAdmin = document.getElementById('btnDonorAdmin');
            if (btnAdmin) {
                btnAdmin.style.setProperty('display', 'inline-flex', 'important');
            }
            if (typeof window.toggleMasterDataView === 'function') {
                window.toggleMasterDataView();
            }
        }
    }, 500);

    loadResources();
});
</script>

<?php get_footer(); ?>
