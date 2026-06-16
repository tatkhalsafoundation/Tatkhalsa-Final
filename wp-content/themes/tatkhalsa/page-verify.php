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
            background: #ffffff;
            width: 8.56cm;
            height: 5.40cm;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(10, 50, 125, 0.15);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        @media print {
            body { background: #fff; }
            .id-card-wrapper { box-shadow: none; border: 1px solid #0A327D; }
            .no-print { display: none; }
        }
        
        /* Header style inspired by template image */
        .id-header {
            height: 1.35cm;
            background: #0A327D;
            position: relative;
            color: #ffffff;
            padding: 0 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
            z-index: 10;
        }
        
        .id-header-left {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .id-header-logo {
            height: 24px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));
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
            line-height: 1.1;
        }
        
        .id-org-title span {
            color: #E1A92A;
        }
        
        .id-org-motto {
            margin: 0;
            font-size: 5.5px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.85);
            text-transform: uppercase;
            line-height: 1;
        }
        
        .id-header-right {
            display: flex;
            align-items: center;
        }
        
        .secured-badge {
            display: flex;
            align-items: center;
            background: rgba(225, 169, 42, 0.12);
            border: 0.75px solid rgba(225, 169, 42, 0.4);
            border-radius: 4px;
            padding: 2px 5px;
            gap: 4px;
        }
        
        .lock-icon-svg {
            width: 10px;
            height: 10px;
            color: #E1A92A;
        }
        
        .secured-text {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1;
        }
        
        .secured-text span {
            font-size: 4px;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        .secured-text strong {
            font-size: 5px;
            color: #E1A92A;
            font-weight: 800;
            letter-spacing: 0.3px;
        }
        
        /* Dynamic Wave Separator */
        .id-header-wave-svg {
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 100%;
            height: 8px;
            z-index: 11;
        }
        
        /* Card Body Layout */
        .id-body {
            flex: 1;
            display: flex;
            flex-direction: row;
            position: relative;
            box-sizing: border-box;
            padding: 10px 12px 0 12px;
            height: calc(5.40cm - 1.35cm - 0.4cm); /* accounting for footer and header */
            z-index: 5;
            background: #ffffff;
        }
        
        .id-body::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 60%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background-image: url('https://tatkhalsa.in/wp-content/uploads/2026/06/cropped-Logo.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.04;
            filter: grayscale(100%);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Left Section: Photo, Member ID box, Barcode */
        .id-left-col {
            width: 2.1cm;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }
        
        .id-photo-container {
            width: 2.1cm;
            height: 2.25cm;
            border: 1.5px solid #0A327D;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(10, 50, 125, 0.15);
            background: #ffffff;
        }
        
        .id-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .id-member-badge {
            width: 100%;
            background: #0A327D;
            border-radius: 4px;
            margin-top: 4px;
            padding: 2px 0;
            text-align: center;
            color: #ffffff;
            box-shadow: 0 2px 5px rgba(10, 50, 125, 0.2);
        }
        
        .id-member-badge .badge-label {
            font-size: 4px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: block;
            line-height: 1;
        }
        
        .id-member-badge .badge-value {
            font-size: 6.5px;
            font-weight: 800;
            color: #E1A92A;
            display: block;
            line-height: 1;
            margin-top: 1px;
            font-family: 'Space Grotesk', sans-serif;
        }
        
        /* Authentic Barcode style */
        .id-barcode-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 14px;
            gap: 1.2px;
            margin-top: auto;
            margin-bottom: 2px;
            width: 100%;
        }
        
        .barcode-bar {
            background: #1a202c;
            height: 100%;
        }
        
        .barcode-bar.thin { width: 0.6px; }
        .barcode-bar.med { width: 1.2px; }
        .barcode-bar.thick { width: 2px; }
        
        /* Right Section details panel */
        .id-right-col {
            flex: 1;
            padding-left: 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 2;
        }
        
        .id-profile-header {
            border-bottom: 1px solid rgba(10, 50, 125, 0.08);
            padding-bottom: 2px;
            margin-bottom: 4px;
        }
        
        .id-member-name {
            margin: 0;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            color: #0A327D;
            text-transform: uppercase;
            letter-spacing: 0.1px;
            line-height: 1.1;
        }
        
        .id-member-designation {
            font-size: 7.5px;
            font-weight: 700;
            color: #d49514;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 1px;
            line-height: 1.1;
        }
        
        /* List with Icons matching the template */
        .id-info-rows {
            display: flex;
            flex-direction: column;
            gap: 3.5px;
            flex: 1;
            margin-top: 2px;
        }
        
        .id-info-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .info-icon-circle {
            width: 14px;
            height: 14px;
            background: #0A327D;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .info-icon {
            width: 8px;
            height: 8px;
            color: #ffffff;
            stroke-width: 2.5;
        }
        
        .info-text-stack {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }
        
        .info-field-label {
            font-size: 4.5px;
            font-weight: 800;
            color: #8892b0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1px;
        }
        
        .info-field-value {
            font-size: 7.5px;
            font-weight: 700;
            color: #2d3748;
        }
        
        /* Bottom elements: Signature and Expiry */
        .id-meta-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            padding-bottom: 2px;
        }
        
        .id-sign-section {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80px;
        }
        
        .signature-img-wrap {
            height: 16px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            margin-bottom: 1px;
        }
        
        .signature-img {
            height: 16px;
            width: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
        }
        
        .signature-dashed-line {
            width: 100%;
            border-bottom: 0.5px dashed #0A327D;
            margin-bottom: 1.5px;
        }
        
        .signature-caption {
            font-size: 4.5px;
            font-weight: 800;
            color: #0A327D;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1;
        }
        
        /* Expiry block */
        .id-meta-divider {
            height: 22px;
            border-left: 0.75px solid rgba(10, 50, 125, 0.15);
            margin: 0 8px;
        }
        
        .id-expiry-section {
            display: flex;
            flex-direction: column;
            line-height: 1;
            margin-right: auto;
        }
        
        .expiry-label {
            font-size: 4.5px;
            font-weight: 800;
            color: #8892b0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }
        
        .expiry-value {
            font-size: 7.5px;
            font-weight: 700;
            color: #1a202c;
        }
        
        /* QR Code Section with SCAN TO VERIFY label */
        .id-qrcode-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-left: auto;
        }
        
        .qr-code-holder {
            padding: 1.5px;
            border: 0.75px solid #0A327D;
            border-radius: 4px;
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(10, 50, 125, 0.08);
        }
        
        .qr-code-img {
            width: 25px;
            height: 25px;
            display: block;
        }
        
        .scan-verify-btn {
            font-size: 3.5px;
            font-weight: 900;
            background: #E1A92A;
            color: #04122d;
            padding: 1px 4px;
            border-radius: 2px;
            margin-top: 2px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            line-height: 1;
            box-shadow: 0 1px 2px rgba(225, 169, 42, 0.3);
        }
        
        /* Bottom Strip */
        .id-footer-strip {
            background: #0A327D;
            color: #E1A92A;
            text-align: center;
            font-size: 5.5px;
            padding: 3px 0;
            font-weight: 800;
            letter-spacing: 0.6px;
            width: 100%;
            z-index: 20;
            border-top: 1px solid #E1A92A;
            text-transform: uppercase;
            font-family: 'Space Grotesk', sans-serif;
            line-height: 1;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #0A327D, #1345a3);
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
        <!-- Top Header Wave-Ribbon layout from template -->
        <div class="id-header">
            <div class="id-header-left">
                <img src="<?php echo esc_url($logo_url); ?>" class="id-header-logo" alt="Logo">
                <div class="id-header-text">
                    <h3 class="id-org-title">TATKHALSA <span>FOUNDATION</span></h3>
                    <p class="id-org-motto">Sewa Main Parma Dharam</p>
                </div>
            </div>
            <div class="id-header-right">
                <div class="secured-badge">
                    <svg class="lock-icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <div class="secured-text">
                        <span>VERIFIED</span>
                        <strong>& SECURED</strong>
                    </div>
                </div>
            </div>
            
            <!-- White wave background divider masked over the rest -->
            <svg class="id-header-wave-svg" viewBox="0 0 100 8" preserveAspectRatio="none">
                <path d="M0,0 Q50,6 100,0 V8 H0 Z" fill="#ffffff" />
                <path d="M0,0 Q50,6 100,0" fill="none" stroke="#E1A92A" stroke-width="0.8" />
            </svg>
        </div>
        
        <!-- Main Card Body -->
        <div class="id-body">
            <!-- Left Panel -->
            <div class="id-left-col">
                <div class="id-photo-container">
                    <?php if ( ! empty( $member->photo_url ) ) : ?>
                        <img src="<?php echo esc_url( $member->photo_url ); ?>" alt="Member Photo">
                    <?php else: ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="Default" style="object-fit: contain; padding: 6px; background:#f4f6f9;">
                    <?php endif; ?>
                </div>
                
                <div class="id-member-badge">
                    <span class="badge-label">MEMBER ID</span>
                    <span class="badge-value"><?php echo esc_html( $member->member_id ); ?></span>
                </div>
                
                <!-- Autogenerated realistic custom SVG-barcode -->
                <div class="id-barcode-container">
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
                    <div class="barcode-bar thick"></div>
                    <div class="barcode-bar med"></div>
                    <div class="barcode-bar thin"></div>
                </div>
            </div>
            
            <!-- Right Panel -->
            <div class="id-right-col">
                <!-- Name and Title -->
                <div class="id-profile-header">
                    <h2 class="id-member-name"><?php echo esc_html( $member->full_name ); ?></h2>
                    <div class="id-member-designation"><?php echo esc_html( $member->designation ); ?></div>
                </div>
                
                <!-- Info Grid Block with Icons -->
                <div class="id-info-rows">
                    <!-- BLOOD GROUP -->
                    <div class="id-info-row">
                        <div class="info-icon-circle">
                            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </div>
                        <div class="info-text-stack">
                            <span class="info-field-label">Blood Group</span>
                            <span class="info-field-value"><?php echo esc_html( $member->blood_group ?: 'N/A' ); ?></span>
                        </div>
                    </div>
                    
                    <!-- CONTACT -->
                    <div class="id-info-row">
                        <div class="info-icon-circle">
                            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="info-text-stack">
                            <span class="info-field-label">Contact No</span>
                            <span class="info-field-value"><?php echo esc_html( $member->mobile ?: 'N/A' ); ?></span>
                        </div>
                    </div>
                    
                    <!-- EMAIL ADDRESS -->
                    <div class="id-info-row">
                        <div class="info-icon-circle">
                            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="info-text-stack">
                            <span class="info-field-label">Email Address</span>
                            <span class="info-field-value"><?php echo esc_html( $member->email ?: 'N/A' ); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom: Signatures, Validity, QR Code -->
                <div class="id-meta-footer">
                    <!-- Auth Signature area -->
                    <div class="id-sign-section">
                        <div class="signature-img-wrap">
                            <img src="https://tatkhalsa.in/wp-content/uploads/2026/06/aba819ad-1c8e-4d21-9849-ef03729a0cc5_removalai_preview-e1781624417937.png" alt="Signature" class="signature-img">
                        </div>
                        <div class="signature-dashed-line"></div>
                        <span class="signature-caption">Auth. Signatory</span>
                    </div>
                    
                    <div class="id-meta-divider"></div>
                    
                    <!-- Valid Till -->
                    <div class="id-expiry-section">
                        <span class="expiry-label">VALID TILL</span>
                        <span class="expiry-value"><?php echo $member->expiry_date ? esc_html( date('d M Y', strtotime($member->expiry_date)) ) : 'N/A'; ?></span>
                    </div>
                    
                    <!-- QR Code & Scan badge -->
                    <div class="id-qrcode-section">
                        <div class="qr-code-holder">
                            <img src="<?php echo esc_url($qr_code_url); ?>" alt="QR Code" class="qr-code-img">
                        </div>
                        <div class="scan-verify-btn">SCAN TO VERIFY</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Banner Footer -->
        <div class="id-footer-strip">
            WWW.TATKHALSA.IN &nbsp;|&nbsp; INFO@TATKHALSA.IN &nbsp;|&nbsp; SECTION 8 NGO
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
        .verify-page-wrapper {
            background-color: #F8F9FA;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }
        .verify-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 600px;
            opacity: 0.08;
            pointer-events: none;
            z-index: 0;
            filter: grayscale(100%);
        }
        .verify-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(10, 50, 125, 0.12);
            max-width: 420px;
            width: 100%;
            text-align: center;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        /* Active View Themes */
        .verify-card-active {
            border: 2px solid #E1A92A; /* Metallic Gold */
            border-top: 8px solid #0A327D; /* Deep Royal Corporate Blue */
        }
        .verify-banner-active {
            background-color: #28a745; /* High Visibility Green Validation */
            color: #ffffff;
            padding: 16px;
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 14px;
            text-transform: uppercase;
        }
        .verify-profile-pic {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 4px solid #E1A92A; /* Round border frame in gold */
            object-fit: cover;
            margin: 25px auto 15px;
            display: block;
            background-color: #f8f9fa;
        }
        .verify-name {
            color: #0A327D;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 5px;
            text-transform: capitalize;
            padding: 0 15px;
        }
        .verify-role {
            color: #555;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 25px;
            text-transform: uppercase;
            padding: 0 15px;
        }
        .verify-id {
            background: #f8f9fa;
            border-top: 1px solid #eee;
            padding: 18px;
            font-family: monospace;
            color: #555;
            font-size: 15px;
            letter-spacing: 0.5px;
        }
        
        /* Invalid/Fake View Themes */
        .verify-card-invalid {
            border: 4px solid #dc3545; /* Stark red border */
            border-top: 10px solid #dc3545;
        }
        .verify-banner-invalid {
            background-color: #dc3545;
            color: #ffffff;
            padding: 16px;
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 15px;
            text-transform: uppercase;
        }
        .verify-invalid-icon {
            font-size: 70px;
            margin: 25px 0;
            display: block;
        }
        .verify-invalid-text {
            padding: 0 25px 30px 25px;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
        .verify-report-email {
            font-weight: 800;
            color: #dc3545;
            text-decoration: underline;
            font-size: 1.1em;
            display: inline-block;
            margin-top: 10px;
        }
    </style>

    <div class="verify-page-wrapper">
        <!-- Background Medallion Watermark -->
        <img src="<?php echo esc_url($logo_url); ?>" class="verify-watermark" alt="">
        
        <?php if ( $member && $member->status === 'Active' ) : ?>
            
            <div class="verify-card verify-card-active">
                <div class="verify-banner-active">
                    ✓ VERIFIED OFFICIAL PERSONNEL OF TATKHALSA FOUNDATION
                </div>
                
                <?php if ( ! empty( $member->photo_url ) ) : ?>
                    <img src="<?php echo esc_url( $member->photo_url ); ?>" alt="Authorized Personnel Photo" class="verify-profile-pic">
                <?php else: ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Tatkhalsa Foundation Corporate Seal" class="verify-profile-pic" style="object-fit: contain; padding: 15px;">
                <?php endif; ?>
                
                <h2 class="verify-name"><?php echo esc_html( $member->full_name ); ?></h2>
                <div class="verify-role"><?php echo esc_html( $member->designation ); ?></div>
                
                <div class="verify-details" style="text-align: left; background: #fff; padding: 15px 25px; font-size: 14px; color: #444; border-top: 1px solid #eef0f2; line-height: 1.8;">
                    <?php if ( ! empty( $member->blood_group ) ) : ?>
                    <strong>BLOOD GROUP:</strong> <span style="color: #dc3545; font-weight: bold;"><?php echo esc_html( $member->blood_group ); ?></span><br>
                    <?php endif; ?>
                    <?php if ( ! empty( $member->expiry_date ) ) : ?>
                    <strong>VALID UNTIL:</strong> <?php echo esc_html( date('d M Y', strtotime($member->expiry_date)) ); ?><br>
                    <?php endif; ?>
                    <?php if ( ! empty( $member->gov_id ) ) : ?>
                    <strong>GOV ID:</strong> <?php echo esc_html( substr($member->gov_id, 0, 2) . '******' . substr($member->gov_id, -3) ); ?><br>
                    <?php endif; ?>
                    <?php if ( ! empty( $member->mobile ) ) : ?>
                    <strong>CONTACT:</strong> <?php echo esc_html( '******' . substr($member->mobile, -4) ); ?>
                    <?php endif; ?>
                </div>

                <div class="verify-id">
                    <strong>MEMBER ID:</strong> <?php echo esc_html( $member->member_id ); ?>
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
