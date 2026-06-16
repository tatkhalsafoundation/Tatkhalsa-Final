<?php
/**
 * Template Name: Identity Verification
 *
 * This file acts as both the administrative dashboard for managing Tatkhalsa Foundation personnel verification
 * and the public-facing landing page for scanning ID cards.
 */

// Initialize WordPress Environment
global $wpdb;
$table_name = $wpdb->prefix . 'tkf_verifications';

// 1. DATABASE SETUP & ARCHITECTURE: Automatically build the table if it does not exist
$charset_collate = $wpdb->get_charset_collate();
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    member_id varchar(100) NOT NULL,
    full_name varchar(255) NOT NULL,
    designation varchar(255) NOT NULL,
    photo_url text,
    expiry_date date DEFAULT NULL,
    gov_id varchar(100),
    email varchar(255),
    mobile varchar(50),
    blood_group varchar(10),
    status varchar(50) DEFAULT 'Active' NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY  (id),
    UNIQUE KEY member_id (member_id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

// Fallback: manually add columns if dbDelta fails
$columns = $wpdb->get_col("DESC {$table_name}", 0);
if ( is_array($columns) ) {
    if ( ! in_array('expiry_date', $columns) ) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN expiry_date date DEFAULT NULL");
    }
    if ( ! in_array('gov_id', $columns) ) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN gov_id varchar(100)");
    }
    if ( ! in_array('email', $columns) ) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN email varchar(255)");
    }
    if ( ! in_array('mobile', $columns) ) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN mobile varchar(50)");
    }
    if ( ! in_array('blood_group', $columns) ) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN blood_group varchar(10)");
    }
}

// Process Form Submissions for Admin View
$message = '';
$message_type = '';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['tkf_verify_action'] ) ) {
    // CSRF Protection via Nonce
    if ( ! isset( $_POST['tkf_verify_nonce'] ) || ! wp_verify_nonce( $_POST['tkf_verify_nonce'], 'tkf_verify_admin_action' ) ) {
        wp_die( 'Security check failed. Nonce verification failed.' );
    }

    // Role verification check
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized Access. Administrator rights are required.' );
    }

    $action = sanitize_text_field( $_POST['tkf_verify_action'] );

    if ( $action === 'add_member' ) {
        // Enforce strict database input escaping
        $member_id   = sanitize_text_field( wp_unslash( $_POST['member_id'] ) );
        $full_name   = sanitize_text_field( wp_unslash( $_POST['full_name'] ) );
        $designation = sanitize_text_field( wp_unslash( $_POST['designation'] ) );
        $photo_url   = esc_url_raw( wp_unslash( $_POST['photo_url'] ) );
        $expiry_date = sanitize_text_field( wp_unslash( $_POST['expiry_date'] ) );
        $gov_id      = sanitize_text_field( wp_unslash( $_POST['gov_id'] ) );
        $email       = sanitize_email( wp_unslash( $_POST['email'] ) );
        $mobile      = sanitize_text_field( wp_unslash( $_POST['mobile'] ) );
        $blood_group = sanitize_text_field( wp_unslash( $_POST['blood_group'] ) );

        // Protect against SQL Injection: Check if member ID exists securely
        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE member_id = %s", $member_id ) );

        if ( $exists ) {
            $message = 'Member ID already exists.';
            $message_type = 'error';
        } else {
            $insert_data = array(
                'member_id'   => $member_id,
                'full_name'   => $full_name,
                'designation' => $designation,
                'photo_url'   => $photo_url,
                'gov_id'      => $gov_id,
                'email'       => $email,
                'mobile'      => $mobile,
                'blood_group' => $blood_group,
                'status'      => 'Active'
            );
            $insert_format = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

            if ( ! empty( $expiry_date ) ) {
                $insert_data['expiry_date'] = $expiry_date;
                $insert_format[] = '%s';
            }

            $inserted = $wpdb->insert(
                $table_name,
                $insert_data,
                $insert_format
            );

            if ( $inserted ) {
                $message = 'New personnel record added successfully.';
                $message_type = 'success';
            } else {
                $message = 'Database error: Failed to add member. ' . $wpdb->last_error;
                $message_type = 'error';
            }
        }
    } elseif ( $action === 'toggle_status' ) {
        $id = intval( $_POST['id'] );
        $current_status = sanitize_text_field( wp_unslash( $_POST['current_status'] ) );
        $new_status = ( $current_status === 'Active' ) ? 'Inactive' : 'Active';

        $wpdb->update(
            $table_name,
            array( 'status' => $new_status ),
            array( 'id' => $id ),
            array( '%s' ),
            array( '%d' )
        );
        $message = "Personnel status securely updated to $new_status.";
        $message_type = 'success';
    } elseif ( $action === 'delete_member' ) {
        $id = intval( $_POST['id'] );
        $wpdb->delete(
            $table_name,
            array( 'id' => $id ),
            array( '%d' )
        );
        $message = 'Personnel record permanently deleted.';
        $message_type = 'success';
    }
}

    // Intercept routing logic for printing ID Card
$download_id = isset( $_GET['download_id'] ) ? sanitize_text_field( wp_unslash( $_GET['download_id'] ) ) : '';
if ( ! empty( $download_id ) ) {
    // Current user can check
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized Access. Administrator rights are required to print ID cards.' );
    }

    $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE member_id = %s", $download_id ) );
    if ( ! $member ) {
        wp_die( 'Member not found.' );
    }

    $logo_url = 'https://tatkhalsa.in/wp-content/uploads/2026/06/cropped-Logo.png'; 
    $verify_url = esc_url( home_url('/verify/?member_id=' . $member->member_id) );
    $qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode( $verify_url ) . '&margin=0';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - <?php echo esc_html( $member->member_id ); ?></title>
    <!-- CSS for ID Card Print -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap');
        
        body {
            background: #e0e4e8;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .id-card-wrapper {
            background: #F8F9FA;
            width: 324px;
            height: 204px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        @media print {
            body { 
                background: #ffffff; 
                margin: 0; 
                padding: 0; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                height: 100vh; 
            }
            .id-card-wrapper { 
                box-shadow: none; 
                border: none; 
                width: 8.56cm;
                height: 5.40cm;
            }
            .no-print { display: none !important; }
        }
        
        /* High Resolution Watermark */
        .id-watermark-overlay {
            position: absolute;
            top: 48px;
            right: -10px;
            width: 140px;
            height: 140px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.08;
            z-index: 2;
            pointer-events: none;
        }
        
        /* Header styling from the magnificent template image */
        .id-header {
            height: 52px;
            position: relative;
            color: #ffffff;
            padding: 0 10.5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
            z-index: 12;
        }
        
        .id-header-left {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: -3px;
        }
        
        .id-header-logo {
            height: 25px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 1.5px 3px rgba(0,0,0,0.35));
        }
        
        .id-header-text {
            display: flex;
            flex-direction: column;
        }
        
        .id-org-title {
            margin: 0;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1;
        }
        
        .id-org-motto {
            margin: 2px 0 0 0;
            font-size: 5px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: #E1A92A;
            text-transform: uppercase;
            line-height: 1;
        }
        
        .id-header-right {
            display: flex;
            align-items: center;
            margin-top: -3px;
        }
        
        .secured-badge {
            display: flex;
            align-items: center;
            background: rgba(225, 169, 42, 0.08);
            border: 0.75px solid rgba(225, 169, 42, 0.45);
            border-radius: 4px;
            padding: 2.5px 4.5px;
            gap: 4px;
        }
        
        .lock-icon-svg {
            width: 9.5px;
            height: 9.5px;
            color: #E1A92A;
        }
        
        .secured-text {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 0.95;
        }
        
        .secured-text span {
            font-size: 3.5px;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        
        .secured-text strong {
            font-size: 4.5px;
            color: #E1A92A;
            font-weight: 800;
            letter-spacing: 0.2px;
        }
        
        /* Main Body Content with flex */
        .id-content-main {
            display: flex;
            flex-direction: row;
            height: 137px;
            width: 100%;
            background: transparent;
            box-sizing: border-box;
            overflow: hidden;
            position: relative;
            z-index: 5;
        }

        /* Left Column Section: Photo & Navy Badge ID Area */
        .id-col-left {
            width: 96px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: transparent;
            padding-top: 8px;
            box-sizing: border-box;
            flex-shrink: 0;
        }
        
        .id-photo-container {
            width: 78px;
            height: 84px;
            border: 1px solid #0A327D;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 3px 6px rgba(10, 50, 125, 0.08);
        }
        
        .id-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .id-badge-info-navy {
            width: 100%;
            background: #0A327D;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4px 0 3px 0;
            margin-top: auto;
            box-sizing: border-box;
            border-top: 1px solid #E1A92A;
        }
        
        .badge-label {
            font-size: 4px;
            font-weight: 700;
            color: #ffffff;
            opacity: 0.85;
            letter-spacing: 0.3px;
            line-height: 1;
        }
        
        .badge-value {
            font-size: 6.8px;
            font-weight: 800;
            color: #E1A92A;
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: 0.2px;
            margin-top: 1.5px;
            line-height: 1;
        }
        
        /* Realistic barcode */
        .barcode-box {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 10px;
            width: 78px;
            background: #ffffff;
            padding: 1px;
            border-radius: 2px;
            margin-top: 3px;
            gap: 1.2px;
            box-sizing: border-box;
        }
        
        .barcode-bar {
            background: #1a202c;
            height: 100%;
        }
        
        .barcode-bar.thin { width: 0.8px; }
        .barcode-bar.med { width: 1.4px; }
        .barcode-bar.thick { width: 2.2px; }
        
        /* Right Column layout */
        .id-col-right {
            flex: 1;
            padding: 7px 10px 4px 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            position: relative;
            background: transparent;
            z-index: 5;
        }
        
        .profile-title-block {
            width: 100%;
        }
        
        .profile-fullname {
            margin: 0;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13.5px;
            font-weight: 800;
            color: #0A327D;
            text-transform: uppercase;
            letter-spacing: 0.1px;
            line-height: 1.15;
        }
        
        .profile-designation {
            font-size: 7.2px;
            font-weight: 700;
            color: #E1A92A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1.5px;
            line-height: 1.1;
        }
        
        .title-divider-line {
            width: 100%;
            height: 0.75px;
            background: #cbd5e0;
            margin-top: 3.5px;
        }
        
        /* Detail Rows using Circle Icons */
        .profile-meta-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            margin-top: 4px;
        }
        
        .meta-item-row {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .meta-icon-circle {
            width: 13px;
            height: 13px;
            background: #0A327D;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .meta-svg-icon {
            width: 6.5px;
            height: 6.5px;
            color: #ffffff;
            stroke-width: 2.5;
        }
        
        .meta-content-wrapper {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }
        
        .meta-row-label {
            font-size: 3.8px;
            font-weight: 800;
            color: #0A327D;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.5px;
        }
        
        .meta-row-val {
            font-size: 7px;
            font-weight: 600;
            color: #4a5568;
            word-break: break-all;
        }
        
        /* Footer Area of the Right Column */
        .right-column-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            width: 100%;
            margin-top: auto;
            border-top: 1px solid #eef2f6;
            padding-top: 4px;
            box-sizing: border-box;
        }
        
        .signature-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 74px;
            line-height: 1;
        }
        
        .signature-image-wrapper {
            height: 14px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        
        .signature-image-wrapper img {
            height: 14px;
            width: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
        }
        
        .signature-underline {
            width: 100%;
            border-bottom: 0.5px dashed #0A327D;
            margin-top: 1px;
            margin-bottom: 2px;
        }
        
        .signature-title {
            font-size: 4px;
            font-weight: 800;
            color: #0A327D;
            letter-spacing: 0.2px;
            text-transform: uppercase;
            text-align: center;
        }
        
        .vertical-dash-divider {
            height: 18px;
            border-left: 0.75px solid #cbd5e0;
            margin: 0 4px;
        }
        
        .validity-block {
            display: flex;
            flex-direction: column;
            line-height: 1;
            margin-right: auto;
            padding-left: 2px;
        }
        
        .validity-label {
            font-size: 4px;
            font-weight: 800;
            color: #0A327D;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }
        
        .validity-date {
            font-size: 7.2px;
            font-weight: 700;
            color: #4a5568;
            margin-top: 1.5px;
        }
        
        /* QR Code & Scan badge */
        .qrcode-badge-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1;
        }
        
        .qr-code-box {
            padding: 1.5px;
            border: 0.75px solid #0A327D;
            border-radius: 3.5px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .qr-code-box img {
            width: 22px;
            height: 22px;
            display: block;
        }
        
        .scan-verify-pill {
            font-size: 3.5px;
            font-weight: 900;
            background: #E1A92A;
            color: #0A327D;
            padding: 1px 3.5px;
            border-radius: 2px;
            margin-top: 2px;
            letter-spacing: 0.2px;
            text-transform: uppercase;
            box-shadow: 0 1px 2px rgba(225, 169, 42, 0.2);
            text-align: center;
        }
        
        /* Bottom Navy Banner */
        .id-bottom-navy-banner {
            height: 15px;
            background: #0A327D;
            color: #E1A92A;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5.5px;
            font-weight: 800;
            letter-spacing: 0.8px;
            border-top: 1.5px solid #E1A92A;
            box-sizing: border-box;
            text-transform: uppercase;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #0A327D, #051a44);
            color: #E1A92A;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.2s;
            z-index: 100;
        }
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <button class="no-print print-btn" onclick="window.print()">Print ID Card</button>
    <div class="id-card-wrapper">
        <!-- SVG background curves inside the card wrapper for wavy header effect -->
        <svg class="id-header-curve-svg" viewBox="0 0 324 204" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="position: absolute; top:0; left:0; width:324px; height:204px; z-index:1; pointer-events:none;">
            <!-- Gold ribbon wavy background separator -->
            <path d="M 0,0 L 324,0 L 324,45 C 270,51 210,31 150,43 C 90,55 50,42 0,46 Z" fill="#E1A92A" />
            <!-- Deep Navy background curve header -->
            <path d="M 0,0 L 324,0 L 324,43 C 270,48 210,28 150,40 C 90,52 50,40 0,43 Z" fill="#0A327D" />
        </svg>

        <!-- Watermark Medallion overlay behind info list -->
        <div class="id-watermark-overlay" style="background-image: url('<?php echo esc_url($logo_url); ?>');"></div>

        <!-- Top Header Part -->
        <div class="id-header">
            <div class="id-header-left">
                <img src="<?php echo esc_url($logo_url); ?>" class="id-header-logo" alt="Logo">
                <div class="id-header-text">
                    <h3 class="id-org-title">TATKHALSA</h3>
                    <p class="id-org-motto">Sewa Main Parma Dharam</p>
                </div>
            </div>
            <div class="id-header-right">
                <div class="secured-badge">
                    <svg class="lock-icon-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 10px; height: 10px;">
                        <circle cx="12" cy="12" r="11" stroke="#E1A92A" stroke-width="2"/>
                        <rect x="8" y="11" width="8" height="6" rx="1.5" fill="#E1A92A"/>
                        <path d="M10 11V9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9V11" stroke="#E1A92A" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <div class="secured-text">
                        <span>VERIFIED</span>
                        <strong>& SECURED</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="id-content-main">
            <!-- Left Column -->
            <div class="id-col-left">
                <div class="id-photo-container">
                    <?php if ( ! empty( $member->photo_url ) ) : ?>
                        <img src="<?php echo esc_url( $member->photo_url ); ?>" alt="Member Photo">
                    <?php else: ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="Default Logo" style="object-fit: contain; padding: 6px; background:#f4f6f9;">
                    <?php endif; ?>
                </div>
                
                <div class="id-badge-info-navy">
                    <span class="badge-label">MEMBER ID</span>
                    <span class="badge-value"><?php echo esc_html( $member->member_id ); ?></span>
                    
                    <!-- Realistic barcode container -->
                    <div class="barcode-box">
                        <div class="barcode-bar thick"></div>
                        <div class="barcode-bar thin"></div>
                        <div class="barcode-bar med"></div>
                        <div class="barcode-bar thick"></div>
                        <div class="barcode-bar thin"></div>
                        <div class="barcode-bar med"></div>
                        <div class="barcode-bar thick"></div>
                        <div class="barcode-bar thin"></div>
                        <div class="barcode-bar thick"></div>
                        <div class="barcode-bar med"></div>
                        <div class="barcode-bar thin"></div>
                        <div class="barcode-bar med"></div>
                        <div class="barcode-bar thick"></div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="id-col-right">
                <!-- Name and Designation -->
                <div class="profile-title-block">
                    <h2 class="profile-fullname"><?php echo esc_html( $member->full_name ); ?></h2>
                    <div class="profile-designation"><?php echo esc_html( $member->designation ); ?></div>
                    <div class="title-divider-line"></div>
                </div>
                
                <!-- Information rows -->
                <div class="profile-meta-list">
                    <!-- Row 1: DEPARTMENT -->
                    <div class="meta-item-row">
                        <div class="meta-icon-circle">
                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div class="meta-content-wrapper">
                            <span class="meta-row-label">DEPARTMENT / MISSION</span>
                            <span class="meta-row-val">Social Service & Relief Operations</span>
                        </div>
                    </div>
                    
                    <!-- Row 2: AUTHORITY -->
                    <div class="meta-item-row">
                        <div class="meta-icon-circle">
                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                <path d="M9 11l2 2 4-4" />
                            </svg>
                        </div>
                        <div class="meta-content-wrapper">
                            <span class="meta-row-label">VERIFICATION AUTHORITY</span>
                            <span class="meta-row-val">Approved Volunteer Personnel of TKF</span>
                        </div>
                    </div>
                    
                    <!-- Row 3: ACCESS & SECURITY -->
                    <div class="meta-item-row">
                        <div class="meta-icon-circle">
                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                            </svg>
                        </div>
                        <div class="meta-content-wrapper">
                            <span class="meta-row-label">ACCESS LEVEL / EXTRA DATA</span>
                            <span class="meta-row-val">Active Duty • Blood Group: <?php echo esc_html( $member->blood_group ?: 'N/A' ); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Signature and Expiry details row -->
                <div class="right-column-footer">
                    <div class="signature-block">
                        <div class="signature-image-wrapper">
                            <img src="https://tatkhalsa.in/wp-content/uploads/2026/06/aba819ad-1c8e-4d21-9849-ef03729a0cc5_removalai_preview-e1781624417937.png" alt="Signature">
                        </div>
                        <div class="signature-underline"></div>
                        <span class="signature-title">AUTHORIZED SIGNATURE</span>
                    </div>
                    
                    <div class="vertical-dash-divider"></div>
                    
                    <div class="validity-block">
                        <span class="validity-label">ISSUE DATE</span>
                        <span class="validity-date"><?php echo $member->created_at ? esc_html( date('d M Y', strtotime($member->created_at)) ) : '16 JUN 2026'; ?></span>
                    </div>
                    
                    <div class="qrcode-badge-container">
                        <div class="qr-code-box">
                            <img src="<?php echo esc_url($qr_code_url); ?>" alt="QR Code">
                        </div>
                        <div class="scan-verify-pill">SCAN TO VERIFY</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Navy Footer strip -->
        <div class="id-bottom-navy-banner">
            INTEGRITY &nbsp;&nbsp;&bull;&nbsp;&nbsp; TRANSPARENCY &nbsp;&nbsp;&bull;&nbsp;&nbsp; SECURITY &nbsp;&nbsp;&bull;&nbsp;&nbsp; TRUST
        </div>
    </div>
</body>
</html>
<?php
    exit;
}

// Intercept routing logic: If member_id query variable is present, show public validation scan view; otherwise, restrict and show admin dashboard
$query_member_id = isset( $_GET['member_id'] ) ? sanitize_text_field( wp_unslash( $_GET['member_id'] ) ) : '';

if ( ! empty( $query_member_id ) ) {
    // 3. PUBLIC VERIFICATION LANDING VIEW (Unauthenticated Scans)
    
    // Fetch matching layout securely from header
    get_header();

    // Fetch the matching record safely using prepared statements protect against SQL Injection
    $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE member_id = %s", $query_member_id ) );

    // Fetch the official logo as watermark
    $logo_url = 'https://tatkhalsa.in/wp-content/uploads/2026/06/cropped-Logo.png'; 
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap');

        .verify-page-wrapper {
            background-color: #F0F2F5;
            min-height: 85vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }
        .verify-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 600px;
            opacity: 0.05;
            pointer-events: none;
            z-index: 0;
            filter: grayscale(100%);
        }
        .verify-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(10, 50, 125, 0.12);
            max-width: 500px;
            width: 100%;
            text-align: center;
            overflow: hidden;
            position: relative;
            z-index: 1;
            box-sizing: border-box;
        }
        
        /* Verification Status Banners */
        .verify-card-active {
            border: 2px solid #E1A92A;
        }
        .verify-banner-active {
            background: linear-gradient(135deg, #28a745, #218838);
            color: #ffffff;
            padding: 16px;
            font-weight: 800;
            letter-spacing: 0.75px;
            font-size: 13.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
        }
        
        /* Responsive Card Scaling Viewport Wrapper */
        .card-viewport-scaler {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 24px 0;
            overflow: visible;
            box-sizing: border-box;
        }
        
        /* 1:1 Horizontal security card visual replication classes */
        .id-card-wrapper {
            background: #F8F9FA;
            width: 324px;
            height: 204px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            transform: scale(1.0);
            transform-origin: center;
            transition: transform 0.25s ease;
        }

        @media (max-width: 380px) {
            .id-card-wrapper {
                transform: scale(0.85);
            }
            .card-viewport-scaler {
                padding: 10px 0;
                height: 180px;
            }
        }
        
        @media (min-width: 480px) {
            .id-card-wrapper {
                transform: scale(1.35);
                box-shadow: 0 15px 45px rgba(10, 50, 125, 0.16);
            }
            .card-viewport-scaler {
                padding: 55px 0;
            }
        }
        
        .id-watermark-overlay {
            position: absolute;
            top: 48px;
            right: -10px;
            width: 140px;
            height: 140px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.08;
            z-index: 2;
            pointer-events: none;
        }
        
        .id-header {
            height: 52px;
            position: relative;
            color: #ffffff;
            padding: 0 10.5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
            z-index: 12;
        }
        
        .id-header-left {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: -3px;
        }
        
        .id-header-logo {
            height: 25px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 1.5px 3px rgba(0,0,0,0.35));
        }
        
        .id-header-text {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        
        .id-org-title {
            margin: 0;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1;
        }
        
        .id-org-motto {
            margin: 2px 0 0 0;
            font-size: 5px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: #E1A92A;
            text-transform: uppercase;
            line-height: 1;
        }
        
        .id-header-right {
            display: flex;
            align-items: center;
            margin-top: -3px;
        }
        
        .secured-badge {
            display: flex;
            align-items: center;
            background: rgba(225, 169, 42, 0.08);
            border: 0.75px solid rgba(225, 169, 42, 0.45);
            border-radius: 4px;
            padding: 2.5px 4.5px;
            gap: 4px;
        }
        
        .lock-icon-svg {
            width: 9.5px;
            height: 9.5px;
            color: #E1A92A;
        }
        
        .secured-text {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 0.95;
        }
        
        .secured-text span {
            font-size: 3.5px;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        
        .secured-text strong {
            font-size: 4.5px;
            color: #E1A92A;
            font-weight: 800;
            letter-spacing: 0.2px;
        }
        
        .id-content-main {
            display: flex;
            flex-direction: row;
            height: 137px;
            width: 100%;
            background: transparent;
            box-sizing: border-box;
            overflow: hidden;
            position: relative;
            z-index: 5;
        }

        .id-col-left {
            width: 96px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: transparent;
            padding-top: 8px;
            box-sizing: border-box;
            flex-shrink: 0;
        }
        
        .id-photo-container {
            width: 78px;
            height: 84px;
            border: 1px solid #0A327D;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 3px 6px rgba(10, 50, 125, 0.08);
        }
        
        .id-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .id-badge-info-navy {
            width: 100%;
            background: #0A327D;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4px 0 3px 0;
            margin-top: auto;
            box-sizing: border-box;
            border-top: 1px solid #E1A92A;
        }
        
        .badge-label {
            font-size: 4px;
            font-weight: 700;
            color: #ffffff;
            opacity: 0.85;
            letter-spacing: 0.3px;
            line-height: 1;
        }
        
        .badge-value {
            font-size: 6.8px;
            font-weight: 800;
            color: #E1A92A;
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: 0.2px;
            margin-top: 1.5px;
            line-height: 1;
        }
        
        .barcode-box {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 10px;
            width: 78px;
            background: #ffffff;
            padding: 1px;
            border-radius: 2px;
            margin-top: 3px;
            gap: 1.2px;
            box-sizing: border-box;
        }
        
        .barcode-bar {
            background: #1a202c;
            height: 100%;
        }
        
        .barcode-bar.thin { width: 0.8px; }
        .barcode-bar.med { width: 1.4px; }
        .barcode-bar.thick { width: 2.2px; }
        
        .id-col-right {
            flex: 1;
            padding: 7px 10px 4px 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            position: relative;
            background: transparent;
            z-index: 5;
        }
        
        .profile-title-block {
            width: 100%;
            text-align: left;
        }
        
        .profile-fullname {
            margin: 0;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13.5px;
            font-weight: 800;
            color: #0A327D;
            text-transform: uppercase;
            letter-spacing: 0.1px;
            line-height: 1.15;
        }
        
        .profile-designation {
            font-size: 7.2px;
            font-weight: 700;
            color: #E1A92A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1.5px;
            line-height: 1.1;
        }
        
        .title-divider-line {
            width: 100%;
            height: 0.75px;
            background: #cbd5e0;
            margin-top: 3.5px;
        }
        
        .profile-meta-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            margin-top: 4px;
        }
        
        .meta-item-row {
            display: flex;
            align-items: center;
            gap: 5px;
            text-align: left;
        }
        
        .meta-icon-circle {
            width: 13px;
            height: 13px;
            background: #0A327D;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .meta-svg-icon {
            width: 6.5px;
            height: 6.5px;
            color: #ffffff;
            stroke-width: 2.5;
        }
        
        .meta-content-wrapper {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }
        
        .meta-row-label {
            font-size: 3.8px;
            font-weight: 800;
            color: #0A327D;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.5px;
        }
        
        .meta-row-val {
            font-size: 7px;
            font-weight: 600;
            color: #4a5568;
            word-break: break-all;
        }
        
        .right-column-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            width: 100%;
            margin-top: auto;
            border-top: 1px solid #eef2f6;
            padding-top: 4px;
            box-sizing: border-box;
        }
        
        .signature-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 74px;
            line-height: 1;
        }
        
        .signature-image-wrapper {
            height: 14px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        
        .signature-image-wrapper img {
            height: 14px;
            width: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
        }
        
        .signature-underline {
            width: 100%;
            border-bottom: 0.5px dashed #0A327D;
            margin-top: 1px;
            margin-bottom: 2px;
        }
        
        .signature-title {
            font-size: 4px;
            font-weight: 800;
            color: #0A327D;
            letter-spacing: 0.2px;
            text-transform: uppercase;
            text-align: center;
        }
        
        .vertical-dash-divider {
            height: 18px;
            border-left: 0.75px solid #cbd5e0;
            margin: 0 4px;
        }
        
        .validity-block {
            display: flex;
            flex-direction: column;
            line-height: 1;
            margin-right: auto;
            padding-left: 2px;
            text-align: left;
        }
        
        .validity-label {
            font-size: 4px;
            font-weight: 800;
            color: #0A327D;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }
        
        .validity-date {
            font-size: 7.2px;
            font-weight: 700;
            color: #4a5568;
            margin-top: 1.5px;
        }
        
        .qrcode-badge-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1;
        }
        
        .qr-code-box {
            padding: 1.5px;
            border: 0.75px solid #0A327D;
            border-radius: 3.5px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .qr-code-box img {
            width: 22px;
            height: 22px;
            display: block;
        }
        
        .scan-verify-pill {
            font-size: 3.5px;
            font-weight: 900;
            background: #E1A92A;
            color: #0A327D;
            padding: 1px 3.5px;
            border-radius: 2px;
            margin-top: 2px;
            letter-spacing: 0.2px;
            text-transform: uppercase;
            box-shadow: 0 1px 2px rgba(225, 169, 42, 0.2);
            text-align: center;
        }
        
        .id-bottom-navy-banner {
            height: 15px;
            background: #0A327D;
            color: #E1A92A;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5.5px;
            font-weight: 800;
            letter-spacing: 0.8px;
            border-top: 1.5px solid #E1A92A;
            box-sizing: border-box;
            text-transform: uppercase;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        
        /* Invalid Card Specific Styles */
        .verify-card-invalid {
            border: 3px solid #dc3545;
            box-shadow: 0 15px 45px rgba(220, 53, 69, 0.2);
        }
        .verify-banner-invalid {
            background: linear-gradient(135deg, #dc3545, #bd2130);
            color: #ffffff;
            padding: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            font-size: 14px;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
        }
        .verify-invalid-icon {
            font-size: 56px;
            margin: 25px 0 15px;
            display: block;
        }
        .verify-invalid-text {
            padding: 0 30px 30px 30px;
            color: #4a5568;
            font-size: 14.5px;
            line-height: 1.7;
        }
        .verify-invalid-text strong {
            color: #1a202c;
            font-size: 16px;
        }
        .verify-report-email {
            font-weight: 800;
            color: #dc3545;
            text-decoration: none;
            border-bottom: 2px solid #dc3545;
            transition: all 0.2s;
        }
        .verify-report-email:hover {
            color: #bd2130;
            border-color: #bd2130;
        }
        
        /* Verified Meta Table details list below card for desktop scans */
        .post-card-verification-details {
            background: #ffffff;
            border-top: 1px solid #eef2f6;
            padding: 20px 25px;
            text-align: left;
            box-sizing: border-box;
        }
        .post-card-title {
            margin: 0 0 12px 0;
            font-size: 12px;
            font-weight: 800;
            color: #0A327D;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-left: 3px solid #E1A92A;
            padding-left: 8px;
        }
        .post-card-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }
        @media (min-width: 480px) {
            .post-card-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
        }
        .post-card-row {
            display: flex;
            flex-direction: column;
            line-height: 1.4;
        }
        .post-card-label {
            font-size: 9px;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .post-card-val {
            font-size: 12px;
            font-weight: 600;
            color: #1A202C;
            margin-top: 1px;
        }
    </style>

    <div class="verify-page-wrapper">
        <!-- Background Medallion Watermark -->
        <img src="<?php echo esc_url($logo_url); ?>" class="verify-watermark" alt="">
        
        <?php if ( $member && $member->status === 'Active' ) : ?>
            
            <div class="verify-card verify-card-active">
                <div class="verify-banner-active">
                    ✓ VERIFIED OFFICIAL REPRESENTATIVE OF TATKHALSA FOUNDATION
                </div>
                
                <!-- Scaler wrapper containing the horizontal luxury ID card replica -->
                <div class="card-viewport-scaler">
                    <div class="id-card-wrapper">
                        <!-- Wavy background vector curves inside card -->
                        <svg class="id-header-curve-svg" viewBox="0 0 324 204" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="position: absolute; top:0; left:0; width:324px; height:204px; z-index:1; pointer-events:none;">
                            <path d="M 0,0 L 324,0 L 324,45 C 270,51 210,31 150,43 C 90,55 50,42 0,46 Z" fill="#E1A92A" />
                            <path d="M 0,0 L 324,0 L 324,43 C 270,48 210,28 150,40 C 90,52 50,40 0,43 Z" fill="#0A327D" />
                        </svg>

                        <!-- Watermark Overlay behind card profile details -->
                        <div class="id-watermark-overlay" style="background-image: url('<?php echo esc_url($logo_url); ?>');"></div>

                        <!-- Card Top Header -->
                        <div class="id-header">
                            <div class="id-header-left">
                                <img src="<?php echo esc_url($logo_url); ?>" class="id-header-logo" alt="Logo">
                                <div class="id-header-text">
                                    <h3 class="id-org-title">TATKHALSA</h3>
                                    <p class="id-org-motto">Sewa Main Parma Dharam</p>
                                </div>
                            </div>
                            <div class="id-header-right">
                                <div class="secured-badge">
                                    <svg class="lock-icon-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 10px; height: 10px;">
                                        <circle cx="12" cy="12" r="11" stroke="#E1A92A" stroke-width="2"/>
                                        <rect x="8" y="11" width="8" height="6" rx="1.5" fill="#E1A92A"/>
                                        <path d="M10 11V9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9V11" stroke="#E1A92A" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>
                                    <div class="secured-text">
                                        <span>VERIFIED</span>
                                        <strong>& SECURED</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Main Body -->
                        <div class="id-content-main">
                            <!-- Left Column -->
                            <div class="id-col-left">
                                <div class="id-photo-container">
                                    <?php if ( ! empty( $member->photo_url ) ) : ?>
                                        <img src="<?php echo esc_url( $member->photo_url ); ?>" alt="Member Photo">
                                    <?php else: ?>
                                        <img src="<?php echo esc_url($logo_url); ?>" alt="Default Logo" style="object-fit: contain; padding: 6px; background:#f4f6f9;">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="id-badge-info-navy">
                                    <span class="badge-label">MEMBER ID</span>
                                    <span class="badge-value"><?php echo esc_html( $member->member_id ); ?></span>
                                    
                                    <!-- Code 128 / Code 39 simulated vector barcode -->
                                    <div class="barcode-box">
                                        <div class="barcode-bar thick"></div>
                                        <div class="barcode-bar thin"></div>
                                        <div class="barcode-bar med"></div>
                                        <div class="barcode-bar thick"></div>
                                        <div class="barcode-bar thin"></div>
                                        <div class="barcode-bar med"></div>
                                        <div class="barcode-bar thick"></div>
                                        <div class="barcode-bar thin"></div>
                                        <div class="barcode-bar thick"></div>
                                        <div class="barcode-bar med"></div>
                                        <div class="barcode-bar thin"></div>
                                        <div class="barcode-bar med"></div>
                                        <div class="barcode-bar thick"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="id-col-right">
                                <!-- Name and Designation -->
                                <div class="profile-title-block">
                                    <h2 class="profile-fullname"><?php echo esc_html( $member->full_name ); ?></h2>
                                    <div class="profile-designation"><?php echo esc_html( $member->designation ); ?></div>
                                    <div class="title-divider-line"></div>
                                </div>
                                
                                <!-- Meta Rows list -->
                                <div class="profile-meta-list">
                                    <!-- Row 1 -->
                                    <div class="meta-item-row">
                                        <div class="meta-icon-circle">
                                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                        </div>
                                        <div class="meta-content-wrapper">
                                            <span class="meta-row-label">DEPARTMENT / MISSION</span>
                                            <span class="meta-row-val">Social Service & Relief Operations</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Row 2 -->
                                    <div class="meta-item-row">
                                        <div class="meta-icon-circle">
                                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                                <path d="M9 11l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div class="meta-content-wrapper">
                                            <span class="meta-row-label">VERIFICATION AUTHORITY</span>
                                            <span class="meta-row-val">Approved Volunteer Personnel of TKF</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Row 3 -->
                                    <div class="meta-item-row">
                                        <div class="meta-icon-circle">
                                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                            </svg>
                                        </div>
                                        <div class="meta-content-wrapper">
                                            <span class="meta-row-label">ACCESS LEVEL / EXTRA DATA</span>
                                            <span class="meta-row-val">Active Duty • Blood Group: <?php echo esc_html( $member->blood_group ?: 'N/A' ); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Block footer -->
                                <div class="right-column-footer">
                                    <div class="signature-block">
                                        <div class="signature-image-wrapper">
                                            <img src="https://tatkhalsa.in/wp-content/uploads/2026/06/aba819ad-1c8e-4d21-9849-ef03729a0cc5_removalai_preview-e1781624417937.png" alt="Signature">
                                        </div>
                                        <div class="signature-underline"></div>
                                        <span class="signature-title">AUTHORIZED SIGNATURE</span>
                                    </div>
                                    
                                    <div class="vertical-dash-divider"></div>
                                    
                                    <div class="validity-block">
                                        <span class="validity-label">ISSUE DATE</span>
                                        <span class="validity-date"><?php echo $member->created_at ? esc_html( date('d M Y', strtotime($member->created_at)) ) : '16 JUN 2026'; ?></span>
                                    </div>
                                    
                                    <?php 
                                    $verify_self_url = esc_url( home_url('/verify/?member_id=' . $member->member_id) );
                                    $qr_self_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode( $verify_self_url ) . '&margin=0';
                                    ?>
                                    <div class="qrcode-badge-container">
                                        <div class="qr-code-box">
                                            <img src="<?php echo esc_url($qr_self_url); ?>" alt="QR Code">
                                        </div>
                                        <div class="scan-verify-pill">SCAN TO VERIFY</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer bar -->
                        <div class="id-bottom-navy-banner">
                            INTEGRITY &nbsp;&nbsp;&bull;&nbsp;&nbsp; TRANSPARENCY &nbsp;&nbsp;&bull;&nbsp;&nbsp; SECURITY &nbsp;&nbsp;&bull;&nbsp;&nbsp; TRUST
                        </div>
                    </div>
                </div>

                <!-- Structured meta list under card for quick reading -->
                <div class="post-card-verification-details">
                    <h4 class="post-card-title">Credential Verification Audit Logs</h4>
                    <div class="post-card-grid">
                        <div class="post-card-row">
                            <span class="post-card-label">Verified Full Name</span>
                            <span class="post-card-val"><?php echo esc_html( $member->full_name ); ?></span>
                        </div>
                        <div class="post-card-row">
                            <span class="post-card-label">Official Designation</span>
                            <span class="post-card-val"><?php echo esc_html( $member->designation ); ?></span>
                        </div>
                        <div class="post-card-row">
                            <span class="post-card-label">Registration Status</span>
                            <span class="post-card-val">Section 8 NGO rep (Tatkhalsa Foundation)</span>
                        </div>
                        <div class="post-card-row">
                            <span class="post-card-label">Secure Member ID</span>
                            <span class="post-card-val"><?php echo esc_html( $member->member_id ); ?></span>
                        </div>
                        <?php if ( ! empty( $member->blood_group ) ) : ?>
                            <div class="post-card-row">
                                <span class="post-card-label">Medical Blood Group</span>
                                <span class="post-card-val"><?php echo esc_html( $member->blood_group ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $member->expiry_date ) ) : ?>
                            <div class="post-card-row">
                                <span class="post-card-label">Card Valid Till</span>
                                <span class="post-card-val"><?php echo esc_html( date('d M Y', strtotime($member->expiry_date)) ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $member->email ) ) : ?>
                            <div class="post-card-row">
                                <span class="post-card-label">Official Email ID</span>
                                <span class="post-card-val"><?php echo esc_html( $member->email ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $member->mobile ) ) : ?>
                            <div class="post-card-row">
                                <span class="post-card-label">Secure Contact No</span>
                                <span class="post-card-val"><?php echo esc_html( '******' . substr( $member->mobile, -4 ) ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php else: ?>
            
            <div class="verify-card verify-card-invalid">
                <div class="verify-banner-invalid">
                    🚨 UNSANCTIONED IDENTITY DETECTED
                </div>
                
                <span class="verify-invalid-icon">🚫</span>
                
                <div class="verify-invalid-text">
                    <strong>This ID card is expired, inactive, or counterfeit.</strong><br><br>
                    Please deny authorization privileges immediately.<br><br>
                    Report this security incident instantly to:<br>
                    <a href="mailto:info@tatkhalsa.in" class="verify-report-email">info@tatkhalsa.in</a>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <?php
    get_footer();
    exit;

} else {
    // 2. PRIVATE ADMINISTRATIVE VIEW
    
    get_header();

    // Check if the current user has global 'manage_options' capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        echo '<div style="padding: 120px 20px; text-align: center; min-height: 60vh; font-family: sans-serif;">
                <h2 style="color: #dc3545; margin-bottom: 15px;">Restricted Access</h2>
                <p style="font-size: 1.1em; color: #555;">You must be authenticated as an Administrator to view this secure dashboard.</p>
              </div>';
        get_footer();
        exit;
    }

    // Fetch all members to populate table directory
    $members = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC" );
    ?>
    <style>
        .admin-dashboard {
            max-width: 1100px;
            margin: 60px auto;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 70vh;
        }
        .admin-dashboard h1 {
            color: #0A327D;
            border-bottom: 2px solid #E1A92A;
            padding-bottom: 15px;
            margin-bottom: 30px;
            font-weight: 800;
        }
        .admin-form-container {
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            margin-bottom: 40px;
            border: 1px solid #eef0f2;
        }
        .admin-form-container h3 {
            margin-top: 0;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.25em;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        @media (max-width: 768px) {
            .form-row { flex-direction: column; gap: 15px; }
        }
        .form-group {
            flex: 1;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
            font-size: 0.9em;
        }
        .form-group input[type="text"],
        .form-group input[type="url"],
        .form-group input[type="email"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0A327D;
        }
        .admin-btn {
            background: #0A327D;
            color: #ffffff;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 700;
            transition: background 0.2s;
            font-size: 1rem;
            margin-top: 10px;
        }
        .admin-btn:hover {
            background: #061e4d;
        }
        .admin-table-container {
            overflow-x: auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            border: 1px solid #eef0f2;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eef0f2;
        }
        .admin-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: 700;
            font-size: 0.9em;
            text-transform: uppercase;
        }
        .admin-table tr:hover {
            background-color: #fafbfc;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 700;
            display: inline-block;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .action-td-flex {
            display: flex;
            gap: 8px;
        }
        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85em;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .action-btn:hover {
            opacity: 0.9;
        }
        .btn-toggle {
            background: #6c757d;
        }
        .btn-delete {
            background: #dc3545;
        }
        .btn-view {
            background: #E1A92A;
            color: #000;
        }
        .admin-notice {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 6px;
            font-weight: 600;
        }
        .notice-success {
            background: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }
        .notice-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }
    </style>

    <div class="admin-dashboard">
        <h1>Tatkhalsa Verification Administration</h1>

        <?php if ( ! empty( $message ) ) : ?>
            <div class="admin-notice notice-<?php echo esc_attr( $message_type ); ?>">
                <?php echo esc_html( $message ); ?>
            </div>
        <?php endif; ?>

        <div class="admin-form-container">
            <h3>Register Authentic Personnel</h3>
            <!-- Clean input-sanitized HTML Form -->
            <form method="POST" action="">
                <?php wp_nonce_field( 'tkf_verify_admin_action', 'tkf_verify_nonce' ); ?>
                <input type="hidden" name="tkf_verify_action" value="add_member">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="member_id">Unique Member ID *</label>
                        <input type="text" id="member_id" name="member_id" required placeholder="e.g. TKF-VOL-2601">
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label for="full_name">Legal Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required placeholder="e.g. Gurpreet Singh">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="designation">Department Designation *</label>
                        <input type="text" id="designation" name="designation" required placeholder="e.g. Field Medical Coordinator">
                    </div>
                    <div class="form-group">
                        <label for="photo_url">Secure Portrait URL (Optional)</label>
                        <input type="url" id="photo_url" name="photo_url" placeholder="https://tatkhalsa.in/secure/portrait.jpg">
                        <small style="color: #666; font-size: 0.85em; display: block; margin-top: 5px;">Tip: Upload the photo to your WordPress Media Library (Dashboard > Media > Add New), click on the image, copy the "File URL", and paste it here.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date</label>
                        <input type="date" id="expiry_date" name="expiry_date">
                    </div>
                    <div class="form-group">
                        <label for="gov_id">Government ID Number</label>
                        <input type="text" id="gov_id" name="gov_id" placeholder="e.g. Aadhaar or PAN">
                    </div>
                    <div class="form-group">
                        <label for="blood_group">Blood Group</label>
                        <input type="text" id="blood_group" name="blood_group" placeholder="e.g. B+">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="e.g. info@domain.com">
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="text" id="mobile" name="mobile" placeholder="e.g. +91 9876543210">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="admin-btn">Onboard Secure Identity</button>
                </div>
            </form>
        </div>

        <h3 style="color: #333; margin-top: 40px; margin-bottom: 20px;">Secured Record Manifest</h3>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th width="15%">Member Ledger</th>
                        <th width="25%">Full Personnel Name</th>
                        <th width="20%">Role Capacity</th>
                        <th width="15%">Security Status</th>
                        <th width="25%">Management Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( $members ) : ?>
                        <?php foreach ( $members as $mem ) : ?>
                            <tr>
                                <td><strong><?php echo esc_html( $mem->member_id ); ?></strong></td>
                                <td style="font-weight: 500;"><?php echo esc_html( $mem->full_name ); ?></td>
                                <td><?php echo esc_html( $mem->designation ); ?></td>
                                <td>
                                    <!-- Dynamic Validation Banner Status -->
                                    <span class="status-badge <?php echo $mem->status === 'Active' ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $mem->status === 'Active' ? '🟢 Active Official' : '🔴 Inactive / Expired'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-td-flex">
                                        <!-- Update Trigger Nonce Form -->
                                        <form method="POST" action="" style="margin:0;">
                                            <?php wp_nonce_field( 'tkf_verify_admin_action', 'tkf_verify_nonce' ); ?>
                                            <input type="hidden" name="tkf_verify_action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?php echo esc_attr( $mem->id ); ?>">
                                            <input type="hidden" name="current_status" value="<?php echo esc_attr( $mem->status ); ?>">
                                            <button type="submit" class="action-btn btn-toggle" title="Toggle Clearance">Toggle Status</button>
                                        </form>
                                        
                                        <!-- Complete Purge Operation -->
                                        <form method="POST" action="" style="margin:0;" onsubmit="return confirm('Warning: You are about to permanently purge this identity record from the security database. Proceed?');">
                                            <?php wp_nonce_field( 'tkf_verify_admin_action', 'tkf_verify_nonce' ); ?>
                                            <input type="hidden" name="tkf_verify_action" value="delete_member">
                                            <input type="hidden" name="id" value="<?php echo esc_attr( $mem->id ); ?>">
                                            <button type="submit" class="action-btn btn-delete" title="Purge Record">Purge</button>
                                        </form>

                                        <!-- Public Audit Deep-link -->
                                        <a href="<?php echo esc_url( home_url('/verify/?member_id=' . $mem->member_id) ); ?>" target="_blank" class="action-btn btn-view" style="display: flex; align-items: center; justify-content: center;">Scan Test</a>
                                        
                                        <!-- Download ID Button -->
                                        <a href="<?php echo esc_url( home_url('/verify/?download_id=' . $mem->member_id) ); ?>" target="_blank" class="action-btn" style="background:#28a745; display: flex; align-items: center; justify-content: center;">Print ID</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #666;">No official identity records found in the database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    get_footer();
}
?>
