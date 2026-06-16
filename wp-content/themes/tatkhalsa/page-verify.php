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
    status varchar(50) DEFAULT 'Active' NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY  (id),
    UNIQUE KEY member_id (member_id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

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

        // Protect against SQL Injection: Check if member ID exists securely
        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE member_id = %s", $member_id ) );

        if ( $exists ) {
            $message = 'Member ID already exists.';
            $message_type = 'error';
        } else {
            $inserted = $wpdb->insert(
                $table_name,
                array(
                    'member_id'   => $member_id,
                    'full_name'   => $full_name,
                    'designation' => $designation,
                    'photo_url'   => $photo_url,
                    'status'      => 'Active'
                ),
                array( '%s', '%s', '%s', '%s', '%s' )
            );

            if ( $inserted ) {
                $message = 'New personnel record added successfully.';
                $message_type = 'success';
            } else {
                $message = 'Database error: Failed to add member.';
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

// Intercept routing logic: If member_id query variable is present, show public validation scan view; otherwise, restrict and show admin dashboard
$query_member_id = isset( $_GET['member_id'] ) ? sanitize_text_field( wp_unslash( $_GET['member_id'] ) ) : '';

if ( ! empty( $query_member_id ) ) {
    // 3. PUBLIC VERIFICATION LANDING VIEW (Unauthenticated Scans)
    
    // Fetch matching layout securely from header
    get_header();

    // Fetch the matching record safely using prepared statements protect against SQL Injection
    $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE member_id = %s", $query_member_id ) );

    // Fetch the official logo as watermark
    $logo_url = get_template_directory_uri() . '/Logo.png'; 
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
        .form-group input[type="url"] {
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
