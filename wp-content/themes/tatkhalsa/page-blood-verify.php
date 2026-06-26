<?php
/**
 * Template Name: Blood On Call Verification
 *
 * This template acts as the secure public lookup and verification registry
 * for Tatkhalsa Blood On Call donors and emergency blood requests, mirroring
 * the style, visual theme, and responsiveness of page-verify.php.
 */

// Force cache-busting headers to prevent stale, cached data display
if ( ! defined( 'DONOTCACHEPAGE' ) ) {
    define( 'DONOTCACHEPAGE', true );
}
nocache_headers();

get_header();

// Initialize WordPress Environment
global $wpdb;
$table_name = $wpdb->prefix . 'tkf_verifications'; // Standard table fallback if needed

// Fetch official logo for branding
$logo_url = 'https://tatkhalsa.in/wp-content/uploads/2026/06/cropped-Logo.png';
$blood_verify_base_url = esc_url( home_url('/blood-verify/') );
$admin_mode = current_user_can( 'manage_options' );
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&family=JetBrains+Mono:wght@400;700&display=swap');

    :root {
        --boc-navy: #052054;
        --boc-gold: #E1A92A;
        --boc-crimson: #ff334b;
        --boc-crimson-grad: linear-gradient(135deg, #ff334b 0%, #d61c33 100%);
        --boc-green: #10ac84;
        --boc-green-grad: linear-gradient(135deg, #10ac84 0%, #0d8c6b 100%);
        --boc-dark: #111111;
        --boc-slate: #1f2937;
        --boc-light: #f3f4f6;
    }

    .boc-verify-wrapper {
        min-height: 85vh;
        background-color: #F8F9FA;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 50px 20px;
        position: relative;
        overflow: hidden;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    .boc-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-15deg);
        width: 80%;
        max-width: 650px;
        opacity: 0.03;
        pointer-events: none;
        z-index: 0;
        filter: grayscale(100%);
    }

    /* Core Card Containers */
    .boc-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 15px 50px rgba(10, 50, 125, 0.08);
        max-width: 550px;
        width: 100%;
        overflow: hidden;
        position: relative;
        z-index: 1;
        box-sizing: border-box;
        margin-bottom: 30px;
        border-top: 5px solid var(--boc-crimson);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .boc-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 60px rgba(10, 50, 125, 0.12);
    }

    /* Header Styling */
    .boc-header {
        background: var(--boc-navy);
        color: #ffffff;
        padding: 25px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-align: center;
    }

    .boc-header img {
        height: 52px;
        width: auto;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));
    }

    .boc-header h2 {
        margin: 5px 0 0 0;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #ffffff;
    }

    .boc-header .subtitle {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--boc-gold);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Tab Controls */
    .boc-tabs {
        display: flex;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .boc-tab {
        flex: 1;
        background: transparent;
        border: none;
        padding: 16px;
        font-size: 13.5px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
    }

    .boc-tab.active {
        color: var(--boc-crimson);
        border-bottom: 3px solid var(--boc-crimson);
        background: #ffffff;
    }

    .boc-tab:hover:not(.active) {
        color: #334155;
        background: #f1f5f9;
    }

    /* Form Fields */
    .boc-form-group {
        text-align: left;
        margin-bottom: 20px;
    }

    .boc-form-group label {
        font-size: 12px;
        font-weight: 800;
        color: var(--boc-navy);
        text-transform: uppercase;
        letter-spacing: 0.75px;
        display: block;
        margin-bottom: 6px;
    }

    .boc-input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #cbd5e0;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        color: #1a202c;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .boc-input:focus {
        border-color: var(--boc-crimson);
        box-shadow: 0 0 0 3px rgba(255, 51, 75, 0.15);
        outline: none;
    }

    .boc-btn-submit {
        width: 100%;
        background: var(--boc-crimson-grad);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 14px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        transition: all 0.2s;
        box-shadow: 0 4px 15px rgba(255, 51, 75, 0.25);
    }

    .boc-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(255, 51, 75, 0.35);
    }

    /* Result Panel Styles */
    .boc-result-card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(10,50,125,0.12);
        max-width: 950px;
        width: 100%;
        overflow: hidden;
        z-index: 10;
        box-sizing: border-box;
        margin-bottom: 40px;
        border: 1px solid #e2e8f0;
        display: none; /* Controlled by JS */
    }

    .boc-result-banner {
        color: #ffffff;
        padding: 20px;
        font-weight: 800;
        letter-spacing: 1px;
        font-size: 14px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .boc-result-banner.verified {
        background: var(--boc-green-grad);
    }

    .boc-result-banner.unverified {
        background: var(--boc-crimson-grad);
    }

    /* Double Column layout for high-fidelity certificate view */
    .boc-result-columns {
        display: flex;
        flex-direction: column;
    }

    @media (min-width: 768px) {
        .boc-result-columns {
            flex-direction: row;
        }
    }

    /* Left panel: Secure identity profile details */
    .boc-profile-pane {
        flex: 1.2;
        padding: 40px 35px;
        text-align: left;
        border-right: 1px solid #f1f5f9;
        box-sizing: border-box;
    }

    .boc-profile-section-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 12px;
        font-weight: 800;
        color: var(--boc-navy);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin: 0 0 20px 0;
        border-left: 3px solid var(--boc-gold);
        padding-left: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .boc-profile-name {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: #0c1a30;
        margin: 0 0 10px 0;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    .boc-registry-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(5, 32, 84, 0.05);
        color: var(--boc-navy);
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-family: 'JetBrains Mono', monospace;
        margin-bottom: 25px;
    }

    /* Meta info grid */
    .boc-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .boc-meta-item-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        display: block;
        margin-bottom: 4px;
    }

    .boc-meta-item-value {
        font-size: 14.5px;
        font-weight: 600;
        color: #1e293b;
    }

    .boc-meta-item-value.highlight {
        color: var(--boc-crimson);
        font-weight: 800;
        font-size: 1.25rem;
    }

    /* Status badge style */
    .boc-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .boc-status-pill.available {
        background: rgba(16, 172, 132, 0.1);
        color: var(--boc-green);
        border: 1px solid rgba(16, 172, 132, 0.2);
    }

    .boc-status-pill.on-standby {
        background: rgba(225, 169, 42, 0.1);
        color: #c2931a;
        border: 1px solid rgba(225, 169, 42, 0.2);
    }

    .boc-status-pill.resting {
        background: rgba(100, 116, 139, 0.1);
        color: #475569;
        border: 1px solid rgba(100, 116, 139, 0.2);
    }

    .boc-status-pill.critical {
        background: rgba(255, 51, 75, 0.1);
        color: var(--boc-crimson);
        border: 1px solid rgba(255, 51, 75, 0.2);
    }

    /* Right panel: Security certificate card visual */
    .boc-graphic-pane {
        flex: 1;
        background: radial-gradient(circle at 10% 20%, rgba(247, 249, 252, 1) 0%, rgba(239, 244, 249, 1) 100%);
        padding: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        position: relative;
    }

    /* Golden sikh security card representation */
    .boc-emblem-card {
        background: #111111;
        color: #ffffff;
        border-radius: 14px;
        width: 100%;
        max-width: 340px;
        aspect-ratio: 1.58 / 1; /* Credit Card Standard */
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        padding: 20px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        border: 1.5px solid rgba(225, 169, 42, 0.45);
        z-index: 1;
    }

    .boc-emblem-card::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 160px;
        height: 160px;
        background-image: url('<?php echo esc_url($logo_url); ?>');
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        opacity: 0.05;
        pointer-events: none;
        z-index: 0;
    }

    .boc-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1.5px solid rgba(225, 169, 42, 0.2);
        padding-bottom: 8px;
        z-index: 2;
    }

    .boc-card-logo-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .boc-card-logo-group img {
        height: 22px;
        width: auto;
    }

    .boc-card-logo-group span {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: var(--boc-gold);
    }

    .boc-card-chip {
        width: 25px;
        height: 18px;
        background: linear-gradient(135deg, #ffd275 0%, #b8860b 100%);
        border-radius: 3px;
        box-shadow: inset 0 1px 3px rgba(255,255,255,0.4);
    }

    .boc-card-body {
        margin: 15px 0;
        z-index: 2;
        text-align: left;
    }

    .boc-card-holder-name {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.2px;
        margin: 0 0 4px 0;
    }

    .boc-card-id {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        color: rgba(255,255,255,0.7);
        letter-spacing: 0.5px;
    }

    .boc-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        z-index: 2;
    }

    .boc-card-meta-col {
        text-align: left;
    }

    .boc-card-meta-lbl {
        font-size: 7.5px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .boc-card-meta-val {
        font-size: 10.5px;
        font-weight: 700;
        color: #ffffff;
    }

    .boc-card-group-medallion {
        background: var(--boc-crimson-grad);
        color: #ffffff;
        font-weight: 800;
        font-size: 14px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--boc-gold);
        box-shadow: 0 2px 8px rgba(255,51,75,0.4);
    }

    /* Live QR Validation Badge */
    .boc-qr-badge {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        margin-top: 25px;
        width: 100%;
        max-width: 340px;
        box-sizing: border-box;
        text-align: left;
    }

    .boc-qr-badge img {
        width: 75px;
        height: 75px;
        border-radius: 4px;
        border: 1px solid #f1f5f9;
        padding: 2px;
        background: #fff;
    }

    .boc-qr-badge-txt h4 {
        margin: 0 0 3px 0;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 11px;
        font-weight: 800;
        color: var(--boc-navy);
        text-transform: uppercase;
    }

    .boc-qr-badge-txt p {
        margin: 0;
        font-size: 9.5px;
        line-height: 1.4;
        color: #64748b;
    }

    /* Unverified / Error display styles */
    .boc-invalid-pane {
        padding: 50px 30px;
        text-align: center;
    }

    .boc-invalid-icon {
        font-size: 4rem;
        display: block;
        margin-bottom: 20px;
    }

    .boc-invalid-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: var(--boc-crimson);
        margin: 0 0 12px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .boc-invalid-desc {
        color: #475569;
        font-size: 14.5px;
        line-height: 1.6;
        max-width: 500px;
        margin: 0 auto 25px auto;
    }

    .boc-invalid-btn {
        display: inline-block;
        background: var(--boc-navy);
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: bold;
        text-decoration: none;
        transition: background 0.2s;
        border: none;
        cursor: pointer;
    }

    .boc-invalid-btn:hover {
        background: #031230;
    }

    /* Sub-Navigation Buttons */
    .subnav-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        width: 100%;
        max-width: 950px;
        justify-content: flex-start;
    }

    .subnav-link {
        color: #fff;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .subnav-link:hover {
        transform: translateY(-2px);
    }

    /* Document Preserver Overlay Modal (Doctor Slip Previewer) */
    .doc-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(5, 14, 30, 0.83);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 100000;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 16px;
        box-sizing: border-box;
    }

    .doc-modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .doc-modal {
        background: #ffffff;
        width: 100%;
        max-width: 600px;
        border-radius: 14px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.45);
        overflow: hidden;
        transform: translateY(20px) scale(0.95);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .doc-modal-overlay.active .doc-modal {
        transform: translateY(0) scale(1);
    }

    /* Action triggers */
    .btn-action-doc {
        background: var(--boc-navy);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 18px;
        font-weight: bold;
        cursor: pointer;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-action-doc:hover {
        background: #031230;
    }

    /* Administrative quick directory list nested */
    .admin-directory-sec {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        max-width: 950px;
        width: 100%;
        padding: 30px;
        box-sizing: border-box;
        text-align: left;
        border-top: 5px solid var(--boc-gold);
        margin-top: 15px;
    }

    .admin-directory-sec h3 {
        margin: 0 0 8px 0;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--boc-navy);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-directory-sec p {
        margin: 0 0 25px 0;
        color: #64748b;
        font-size: 13.5px;
    }

    .boc-table-wrapper {
        overflow-x: auto;
    }

    .boc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    .boc-table th {
        background: #f8fafc;
        color: var(--boc-navy);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        text-align: left;
        border-bottom: 2px solid #edf2f7;
    }

    .boc-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #edf2f7;
        color: #334155;
    }

    .boc-table tr:hover {
        background: #fdfdfd;
    }
</style>

<div class="boc-verify-wrapper">
    <!-- Sikh Medallion Watermark -->
    <img src="<?php echo esc_url($logo_url); ?>" class="boc-watermark" alt="">

    <!-- Admin Tools Quick Navigation -->
    <div class="subnav-bar">
        <a href="<?php echo esc_url( home_url('/verify/') ); ?>" class="subnav-link" style="background: var(--boc-navy); box-shadow: 0 4px 12px rgba(5,32,84,0.15);">
            <span>🪪</span> Official Identity Verification
        </a>
        <a href="<?php echo esc_url( home_url('/blood-on-call/') ); ?>" class="subnav-link" style="background: linear-gradient(135deg, #ff334b 0%, #ff5d73 100%);">
            <span>🩸</span> Open Blood Directory / Donors
        </a>
        <?php if ( $admin_mode ) : ?>
            <a href="<?php echo esc_url( home_url('/verify/') ); ?>?admin=true" class="subnav-link" style="background: var(--boc-gold); color: #000; box-shadow: 0 4px 12px rgba(225,169,42,0.15);">
                <span>⚖️</span> Identity Admin Desk
            </a>
        <?php endif; ?>
    </div>

    <!-- 1. SEARCH/LOOKUP INPUT CONTAINER -->
    <div class="boc-card" id="lookupCard">
        <div class="boc-header">
            <img src="<?php echo esc_url($logo_url); ?>" alt="Logo">
            <h2>TATKHALSA FOUNDATION</h2>
            <div class="subtitle">
                <span>🩸</span> Blood Registry Audit Portal
            </div>
        </div>

        <!-- Segmented Tab Controls -->
        <div class="boc-tabs">
            <button class="boc-tab active" onclick="switchBocTab('donor')" id="tabDonor">Verify Donor</button>
            <button class="boc-tab" onclick="switchBocTab('request')" id="tabRequest">Verify Request</button>
        </div>

        <div style="padding: 30px 25px;">
            <p style="color: #475569; font-size: 13.5px; line-height: 1.6; margin: 0 0 25px 0; font-weight: 500; text-align: left;">
                Enter a registered Blood Donor Tracking ID or an Emergency Blood Request ID received via SMS or Email to perform an instant credentials integrity audit.
            </p>

            <form id="frmBocLookup" onsubmit="handleBocSearch(event)" style="display: flex; flex-direction: column; gap: 15px;">
                <div class="boc-form-group">
                    <label id="lblInputId" for="bocInputId">Secure Donor Registry ID</label>
                    <input type="text" id="bocInputId" placeholder="e.g. donor_1" required class="boc-input" style="font-family: monospace;">
                </div>
                <button type="submit" class="boc-btn-submit">
                    Audit Registry Credentials
                </button>
            </form>
        </div>
    </div>

    <!-- 2. RESOLVED & VERIFIED STATUS CONTAINER -->
    <div class="boc-result-card" id="resultCard">
        <!-- Banner filled dynamically -->
        <div class="boc-result-banner verified" id="resultBanner">
            <span>🛡️</span> AUTHENTIC REGISTRY ENTRY CONFIRMED
        </div>

        <div class="boc-result-columns" id="resultMainBody">
            <!-- Left Profile Pane -->
            <div class="boc-profile-pane">
                <div class="boc-profile-section-title" id="secTitle">
                    <span>🩸</span> Registered Blood Donor Verification
                </div>
                <h3 class="boc-profile-name" id="resName">--</h3>
                <div class="boc-registry-tag">
                    Registry Code: <span id="resTagCode" style="font-weight: 800; font-family: monospace;">--</span>
                </div>

                <div class="boc-meta-grid" id="resMetaGrid">
                    <!-- Loaded dynamically -->
                </div>

                <div style="border-top: 1px solid #e2e8f0; padding-top: 25px; margin-top: 25px; display: flex; gap: 12px; flex-wrap: wrap;" id="resActionsRow">
                    <!-- Actions Row dynamically populated -->
                </div>
            </div>

            <!-- Right Visual Graphic Pane -->
            <div class="boc-graphic-pane">
                <div class="boc-emblem-card" id="resCardEmblem">
                    <div class="boc-card-header">
                        <div class="boc-card-logo-group">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="">
                            <span>TATKHALSA</span>
                        </div>
                        <div class="boc-card-chip"></div>
                    </div>
                    <div class="boc-card-body">
                        <h4 class="boc-card-holder-name" id="resCardName">--</h4>
                        <div class="boc-card-id" id="resCardId">--</div>
                    </div>
                    <div class="boc-card-footer">
                        <div class="boc-card-meta-col">
                            <div class="boc-card-meta-lbl" id="resCardFootLabel">Availability</div>
                            <div class="boc-card-meta-val" id="resCardFootValue">Available Now</div>
                        </div>
                        <div class="boc-card-group-medallion" id="resCardGroupBox">O+</div>
                    </div>
                </div>

                <!-- QR Validation Badge -->
                <div class="boc-qr-badge">
                    <img id="resQrCodeImg" src="" alt="Live QR">
                    <div class="boc-qr-badge-txt">
                        <h4 id="resQrCodeType">Donor QR System</h4>
                        <p id="resQrCodeDesc">Scan this cryptographic code to verify live credential authenticity on tatkhalsa.in secure registers.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invalid State Template Embedded inside resultCard -->
        <div class="boc-invalid-pane" id="invalidPane" style="display: none;">
            <span class="boc-invalid-icon">🚫</span>
            <h3 class="boc-invalid-title" id="invalidTitle">UNAUTHORIZED CREDENTIAL IDENTIFIED</h3>
            <p class="boc-invalid-desc" id="invalidDesc">
                This donor profile or emergency request ID is expired, deleted, or unverified. Please do not offer authorization privileges. If you suspect fraud, report immediately to info@tatkhalsa.in.
            </p>
            <button onclick="resetPortalSearch()" class="boc-invalid-btn">Search Another ID</button>
        </div>
    </div>

    <!-- Administrative Registry Directory Table (Visible always for Admin, helping debug & verify easily just like page-verify.php lists!) -->
    <div class="admin-directory-sec" id="adminDirSec" style="display: none;">
        <h3>
            <span>📊</span> Secure Registry Analytics & Admin Desk
        </h3>
        <p>This section is shown to Tatkhalsa administrators only to audit registries instantly.</p>

        <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
            <div style="background: rgba(255, 51, 75, 0.05); padding: 15px 25px; border-radius: 8px; border: 1.5px solid rgba(255, 51, 75, 0.15); flex:1; min-width: 140px;">
                <span style="font-size: 11px; font-weight:700; color:#ff334b; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:4px;">Total Donors</span>
                <strong style="font-size: 24px; color:#ff334b; font-family:'Space Grotesk',sans-serif;" id="statTotalDonors">0</strong>
            </div>
            <div style="background: rgba(5, 32, 84, 0.05); padding: 15px 25px; border-radius: 8px; border: 1.5px solid rgba(5,32,84,0.1); flex:1; min-width: 140px;">
                <span style="font-size: 11px; font-weight:700; color:var(--boc-navy); text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:4px;">Emergency Requests</span>
                <strong style="font-size: 24px; color:var(--boc-navy); font-family:'Space Grotesk',sans-serif;" id="statTotalRequests">0</strong>
            </div>
        </div>

        <!-- Toggle Tables -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <button id="adminTblTabDonor" onclick="switchAdminDirTable('donor')" style="background: var(--boc-navy); color: #fff; border:none; padding: 10px 18px; border-radius: 6px; font-weight:bold; cursor:pointer; font-size:13px;">
                🩸 Registered Donors List
            </button>
            <button id="adminTblTabRequest" onclick="switchAdminDirTable('request')" style="background: #e2e8f0; color: #475569; border:none; padding: 10px 18px; border-radius: 6px; font-weight:bold; cursor:pointer; font-size:13px;">
                🚨 Urgent Request Trackers
            </button>
        </div>

        <div class="boc-table-wrapper" id="adminTblDonorsWrap">
            <table class="boc-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Donor Name</th>
                        <th>Blood Group</th>
                        <th>Availability</th>
                        <th>Location</th>
                        <th>Verification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="adminTblDonorsBody">
                    <!-- Loaded dynamically -->
                </tbody>
            </table>
        </div>

        <div class="boc-table-wrapper" id="adminTblRequestsWrap" style="display: none;">
            <table class="boc-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>Blood Group</th>
                        <th>Units</th>
                        <th>Urgency</th>
                        <th>Hospital</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="adminTblRequestsBody">
                    <!-- Loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DOCTOR prescription Slip Previewer Modal -->
<div class="doc-modal-overlay" id="docModal" onclick="closeDocModal()">
    <div class="doc-modal" onclick="event.stopPropagation()">
        <button class="doc-modal-overlay" style="display:none;"></button>
        <div style="background: var(--boc-navy); color:#fff; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin:0; font-family:'Space Grotesk',sans-serif; font-size:16px;" id="docModalTitle">Prescription Slip / Doctor's Note</h3>
            <button onclick="closeDocModal()" style="background:none; border:none; color:#fff; font-size:20px; cursor:pointer; font-weight:bold;">×</button>
        </div>
        <div style="padding: 20px; text-align: center; max-height:75vh; overflow-y:auto;" id="docModalContent">
            <!-- Filled dynamically -->
        </div>
    </div>
</div>

<script>
    // System variables and cache
    let activeBocTab = 'donor'; // 'donor' or 'request'
    let masterDonors = [];
    let masterRequests = [];
    const isAdminMode = <?php echo $admin_mode ? 'true' : 'false'; ?>;

    /**
     * Set active lookup tab (Donor registry or Emergency Request)
     */
    function switchBocTab(tab) {
        activeBocTab = tab;
        document.getElementById('tabDonor').classList.toggle('active', tab === 'donor');
        document.getElementById('tabRequest').classList.toggle('active', tab === 'request');
        
        const lbl = document.getElementById('lblInputId');
        const inp = document.getElementById('bocInputId');
        
        if (tab === 'donor') {
            lbl.innerText = 'Secure Donor Registry ID';
            inp.placeholder = 'e.g. donor_1';
        } else {
            lbl.innerText = 'Emergency Blood Request ID';
            inp.placeholder = 'e.g. req_1';
        }
    }

    /**
     * Load blood directory lists globally on loading page
     */
    async function loadBloodVerifyRegistry() {
        try {
            const res = await fetch('/api/admin/master-data');
            const data = await res.json();
            
            if (data.success) {
                masterDonors = data.donors || [];
                masterRequests = data.requests || [];
                
                // Set stats in Desk
                document.getElementById('statTotalDonors').innerText = masterDonors.length;
                document.getElementById('statTotalRequests').innerText = masterRequests.length;
                
                if (isAdminMode) {
                    document.getElementById('adminDirSec').style.display = 'block';
                    renderAdminDeskTables();
                }

                // Check URL params to auto-verify
                handleUrlParameters();
            }
        } catch (e) {
            console.error("Failed to fetch Blood On Call Master Data: ", e);
        }
    }

    /**
     * Parse and route URL parameters for quick QR lookups
     */
    function handleUrlParameters() {
        const params = new URLSearchParams(window.location.search);
        const donorId = params.get('donor_id') || params.get('donor');
        const requestId = params.get('request_id') || params.get('request') || params.get('req') || params.get('req_id');
        const bloodId = params.get('blood_id') || params.get('id');

        if (donorId) {
            switchBocTab('donor');
            document.getElementById('bocInputId').value = donorId;
            verifyBocRecord('donor', donorId);
        } else if (requestId) {
            switchBocTab('request');
            document.getElementById('bocInputId').value = requestId;
            verifyBocRecord('request', requestId);
        } else if (bloodId) {
            // Auto detect standard prefixes
            if (bloodId.startsWith('req') || bloodId.startsWith('request')) {
                switchBocTab('request');
                document.getElementById('bocInputId').value = bloodId;
                verifyBocRecord('request', bloodId);
            } else {
                switchBocTab('donor');
                document.getElementById('bocInputId').value = bloodId;
                verifyBocRecord('donor', bloodId);
            }
        }
    }

    /**
     * Submit search handler
     */
    function handleBocSearch(e) {
        e.preventDefault();
        const rawId = document.getElementById('bocInputId').value.trim();
        if (rawId) {
            verifyBocRecord(activeBocTab, rawId);
        }
    }

    /**
     * Core lookup matches logic
     */
    function verifyBocRecord(type, id) {
        document.getElementById('lookupCard').style.display = 'none';
        
        const resCard = document.getElementById('resultCard');
        const resMainBody = document.getElementById('resultMainBody');
        const invalidPane = document.getElementById('invalidPane');
        const resBanner = document.getElementById('resultBanner');
        
        resCard.style.display = 'block';
        invalidPane.style.display = 'none';
        resMainBody.style.display = 'none';

        if (type === 'donor') {
            const donor = masterDonors.find(d => d.id.toLowerCase() === id.toLowerCase() || d.name.toLowerCase().includes(id.toLowerCase()));
            if (donor) {
                renderVerifiedDonor(donor);
            } else {
                renderUnverified("Blood Donor Profile Not Found", "The provided registry code/identifier has expired, been updated, or is offline. Please audit credentials thoroughly.");
            }
        } else {
            const request = masterRequests.find(r => r.id.toLowerCase() === id.toLowerCase() || r.patientName.toLowerCase().includes(id.toLowerCase()));
            if (request) {
                renderVerifiedRequest(request);
            } else {
                renderUnverified("Emergency Blood Request Not Found", "No matching active blood request tracking records found in our live registry. Ensure tracking keys are accurate.");
            }
        }
    }

    /**
     * Beautiful Verified Donor Card Renderer
     */
    function renderVerifiedDonor(donor) {
        const main = document.getElementById('resultMainBody');
        const banner = document.getElementById('resultBanner');
        main.style.display = 'flex';

        banner.className = 'boc-result-banner verified';
        banner.innerHTML = '<span>🛡️</span> TATKHALSA CERTIFIED ACTIVE DONOR';

        document.getElementById('secTitle').innerHTML = '<span>🩸</span> Verified Blood Donor Profile';
        document.getElementById('resName').innerText = donor.name;
        document.getElementById('resTagCode').innerText = donor.id.toUpperCase();

        const formatRegDate = new Date(donor.timestamp || Date.now()).toLocaleDateString('en-US', {
            day: 'numeric', month: 'short', year: 'numeric'
        });

        // Set up availability badge
        let statusHtml = '';
        let statusVal = donor.availabilityStatus || 'Available Now';
        if (statusVal.toLowerCase().includes('now') || statusVal.toLowerCase().includes('available')) {
            statusHtml = `<span class="boc-status-pill available">🟢 Active & Available</span>`;
        } else if (statusVal.toLowerCase().includes('standby')) {
            statusHtml = `<span class="boc-status-pill on-standby">🟡 Standby Coordination</span>`;
        } else {
            statusHtml = `<span class="boc-status-pill resting">⚪ Inactive / Resting Phase</span>`;
        }

        const emailCoord = donor.email ? donor.email : 'N/A';
        const phoneDisplay = isAdminMode ? donor.contact : maskContactNumber(donor.contact);

        document.getElementById('resMetaGrid').innerHTML = `
            <div>
                <span class="boc-meta-item-label">Blood Group</span>
                <span class="boc-meta-item-value highlight" style="color:var(--boc-crimson);">${donor.bloodGroup}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Status</span>
                <span class="boc-meta-item-value">${statusHtml}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">District Location</span>
                <span class="boc-meta-item-value">📍 ${donor.address}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Registration Date</span>
                <span class="boc-meta-item-value">📅 ${formatRegDate}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Contact Coordinates</span>
                <span class="boc-meta-item-value">📞 ${phoneDisplay}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Official Email</span>
                <span class="boc-meta-item-value">✉️ ${emailCoord}</span>
            </div>
        `;

        // Direct action button triggers
        let actionBtnHtml = `
            <button onclick="resetPortalSearch()" class="boc-invalid-btn" style="background:#555;">← Return to Audit Search</button>
        `;
        if (isAdminMode) {
            actionBtnHtml += `
                <a href="/blood-on-call/?admin=true" class="btn-action-doc">⚙️ Manage Profile</a>
            `;
        } else {
            actionBtnHtml += `
                <a href="/blood-on-call/" class="btn-action-doc" style="background:var(--boc-crimson-grad);">🩸 Reach Out to Help</a>
            `;
        }
        document.getElementById('resActionsRow').innerHTML = actionBtnHtml;

        // Populate Right Card Visual Graphic
        document.getElementById('resCardName').innerText = donor.name;
        document.getElementById('resCardId').innerText = donor.id.toUpperCase();
        document.getElementById('resCardFootLabel').innerText = 'Availability';
        document.getElementById('resCardFootValue').innerText = donor.availabilityStatus || 'Available Now';
        document.getElementById('resCardGroupBox').innerText = donor.bloodGroup;

        // Update QR
        const verifyUrl = `${window.location.origin}/blood-verify/?donor_id=${donor.id}`;
        document.getElementById('resQrCodeImg').src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(verifyUrl)}&margin=1`;
        document.getElementById('resQrCodeType').innerText = 'Verified Donor Pass';
    }

    /**
     * Beautiful Verified Emergency Request Renderer
     */
    function renderVerifiedRequest(req) {
        const main = document.getElementById('resultMainBody');
        const banner = document.getElementById('resultBanner');
        main.style.display = 'flex';

        banner.className = 'boc-result-banner verified';
        banner.innerHTML = '<span>🚨</span> EMERGENCY BLOOD REQUEST CONFIRMED';

        document.getElementById('secTitle').innerHTML = '<span>📋</span> Active Medical Request Audit';
        document.getElementById('resName').innerText = req.patientName;
        document.getElementById('resTagCode').innerText = req.id.toUpperCase();

        const formatReqDate = new Date(req.timestamp || Date.now()).toLocaleDateString('en-US', {
            day: 'numeric', month: 'short', year: 'numeric'
        });

        // Urgency badge
        let levelHtml = '';
        let levelVal = req.urgency || 'Urgent';
        if (levelVal.toLowerCase().includes('critical') || levelVal.toLowerCase().includes('emergency')) {
            levelHtml = `<span class="boc-status-pill critical">🚨 CRITICAL EMERGENCY</span>`;
        } else {
            levelHtml = `<span class="boc-status-pill on-standby">⚡ URGENT PREPARATION</span>`;
        }

        // Status flow state
        let flowHtml = '';
        let flowState = req.status || 'pending';
        if (flowState === 'pending') {
            flowHtml = `<span class="boc-status-pill on-standby">🔄 PENDING DIRECT DONATION</span>`;
        } else if (flowState === 'accepted') {
            flowHtml = `<span class="boc-status-pill available">🤝 DESIGNATED (CLAIMED)</span>`;
        } else {
            flowHtml = `<span class="boc-status-pill resting">🟢 FULFILLED & EXPIRED</span>`;
        }

        const phoneDisplay = isAdminMode ? req.contactDetails : maskContactNumber(req.contactDetails);

        document.getElementById('resMetaGrid').innerHTML = `
            <div>
                <span class="boc-meta-item-label">Blood Group Needed</span>
                <span class="boc-meta-item-value highlight" style="color:var(--boc-crimson);">${req.bloodGroup}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Priority Scale</span>
                <span class="boc-meta-item-value">${levelHtml}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Medical Location</span>
                <span class="boc-meta-item-value">🏥 ${req.hospitalName}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Treatment Centre</span>
                <span class="boc-meta-item-value">📍 ${req.patientLocation}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Required Amount</span>
                <span class="boc-meta-item-value">💉 ${req.unitsRequired || '1'} Units</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Submission Date</span>
                <span class="boc-meta-item-value">📅 ${formatReqDate}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Patient Guardian Phone</span>
                <span class="boc-meta-item-value">📞 ${phoneDisplay}</span>
            </div>
            <div>
                <span class="boc-meta-item-label">Request Progress</span>
                <span class="boc-meta-item-value">${flowHtml}</span>
            </div>
        `;

        // Action row buttons: support viewing Doctor slip attachment
        let actionBtnHtml = `
            <button onclick="resetPortalSearch()" class="boc-invalid-btn" style="background:#555;">← Return to Audit Search</button>
        `;
        
        if (req.doctorSlipUrl) {
            actionBtnHtml += `
                <button onclick="previewDoctorSlip('${req.doctorSlipUrl}', '${req.patientName}')" class="btn-action-doc" style="background:var(--boc-navy);">📄 View Doctor Slip</button>
            `;
        } else {
            actionBtnHtml += `
                <span style="font-size:11px; font-weight:700; color:#999; font-style:italic; padding-top:10px;">* Doctor verification slip logged on server files</span>
            `;
        }
        
        document.getElementById('resActionsRow').innerHTML = actionBtnHtml;

        // Populate Right Card Visual Graphic
        document.getElementById('resCardName').innerText = req.patientName;
        document.getElementById('resCardId').innerText = req.id.toUpperCase();
        document.getElementById('resCardFootLabel').innerText = 'Urgency Level';
        document.getElementById('resCardFootValue').innerText = req.urgency || 'Urgent';
        document.getElementById('resCardGroupBox').innerText = req.bloodGroup;

        // Update QR
        const verifyUrl = `${window.location.origin}/blood-verify/?request_id=${req.id}`;
        document.getElementById('resQrCodeImg').src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(verifyUrl)}&margin=1`;
        document.getElementById('resQrCodeType').innerText = 'Verified Medical Request';
    }

    /**
     * Unverified display fallback
     */
    function renderUnverified(title, description) {
        const root = document.getElementById('resultCard');
        const invalid = document.getElementById('invalidPane');
        const main = document.getElementById('resultMainBody');
        const banner = document.getElementById('resultBanner');

        root.style.display = 'block';
        main.style.display = 'none';
        invalid.style.display = 'block';

        banner.className = 'boc-result-banner unverified';
        banner.innerHTML = '<span>🚫</span> FAILED AUDIT: UNREGISTERED SIGNALS DETECTED';

        document.getElementById('invalidTitle').innerText = title;
        document.getElementById('invalidDesc').innerText = description;
    }

    /**
     * Mask contact phone output helper for general public to safeguard donor privacy
     */
    function maskContactNumber(phone) {
        if (!phone) return 'N/A';
        const str = phone.trim();
        if (str.length < 5) return '******';
        return str.substring(0, 4) + ' *** **' + str.substring(str.length - 3);
    }

    /**
     * Return to look up card
     */
    function resetPortalSearch() {
        document.getElementById('resultCard').style.display = 'none';
        document.getElementById('lookupCard').style.display = 'block';
        document.getElementById('bocInputId').value = '';
        
        // Return clear URL
        window.history.pushState({}, document.title, window.location.pathname);
    }

    /**
     * Dynamic Preview of Doctor slip
     */
    function previewDoctorSlip(url, patient) {
        const modal = document.getElementById('docModal');
        const content = document.getElementById('docModalContent');
        document.getElementById('docModalTitle').innerText = `Doctor prescription Slip - Patient: ${patient}`;

        content.innerHTML = `
            <img src="${url}" alt="Physician Request Form" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 8px;" onerror="this.src='https://placehold.co/400x500/eaeaea/333333?text=Physician+Medical+Slip+Loaded'">
            <div style="margin-top: 15px;">
                <a href="${url}" target="_blank" class="btn-action-doc">📥 Download / Link Original File</a>
            </div>
        `;
        modal.classList.add('active');
    }

    function closeDocModal() {
        document.getElementById('docModal').classList.remove('active');
    }

    /**
     * Render the table arrays inside Admin Desk
     */
    function renderAdminDeskTables() {
        // Render Donors
        const donorsBody = document.getElementById('adminTblDonorsBody');
        donorsBody.innerHTML = '';
        if (masterDonors.length === 0) {
            donorsBody.innerHTML = '<tr><td colspan="7" style="text-align:center; color:#999; padding:20px;">No donors listed.</td></tr>';
        } else {
            masterDonors.forEach(donor => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>${donor.id.toUpperCase()}</strong></td>
                    <td style="font-weight:600;">${donor.name}</td>
                    <td><span style="background:rgba(255, 51, 75, 0.1); color:#ff334b; font-weight:bold; padding:2px 8px; border-radius:12px; font-size:11px;">${donor.bloodGroup}</span></td>
                    <td>${donor.availabilityStatus}</td>
                    <td>${donor.address}</td>
                    <td>${donor.isVerified ? '🟢 Verified' : '🔴 Unverified'}</td>
                    <td>
                        <button onclick="selectAndVerify('donor', '${donor.id}')" style="background:var(--boc-navy); color:#fff; font-size:11px; border:none; padding:4px 10px; border-radius:4px; font-weight:bold; cursor:pointer;">Select & Audit</button>
                    </td>
                `;
                donorsBody.appendChild(tr);
            });
        }

        // Render Requests
        const requestsBody = document.getElementById('adminTblRequestsBody');
        requestsBody.innerHTML = '';
        if (masterRequests.length === 0) {
            requestsBody.innerHTML = '<tr><td colspan="7" style="text-align:center; color:#999; padding:20px;">No requests found.</td></tr>';
        } else {
            masterRequests.forEach(req => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>${req.id.toUpperCase()}</strong></td>
                    <td style="font-weight:600;">${req.patientName}</td>
                    <td><span style="background:rgba(255, 51, 75, 0.1); color:#ff334b; font-weight:bold; padding:2px 8px; border-radius:12px; font-size:11px;">${req.bloodGroup}</span></td>
                    <td>${req.unitsRequired} Units</td>
                    <td>${req.urgency}</td>
                    <td>${req.hospitalName}</td>
                    <td>
                        <button onclick="selectAndVerify('request', '${req.id}')" style="background:var(--boc-navy); color:#fff; font-size:11px; border:none; padding:4px 10px; border-radius:4px; font-weight:bold; cursor:pointer;">Select & Audit</button>
                    </td>
                `;
                requestsBody.appendChild(tr);
            });
        }
    }

    function selectAndVerify(type, id) {
        switchBocTab(type);
        document.getElementById('bocInputId').value = id;
        verifyBocRecord(type, id);
        window.scrollTo({ top: 300, behavior: 'smooth' });
    }

    function switchAdminDirTable(type) {
        document.getElementById('adminTblDonorsWrap').style.display = type === 'donor' ? 'block' : 'none';
        document.getElementById('adminTblRequestsWrap').style.display = type === 'request' ? 'block' : 'none';
        
        const donorTabBtn = document.getElementById('adminTblTabDonor');
        const reqTabBtn = document.getElementById('adminTblTabRequest');
        
        if (type === 'donor') {
            donorTabBtn.style.background = 'var(--boc-navy)';
            donorTabBtn.style.color = '#fff';
            reqTabBtn.style.background = '#e2e8f0';
            reqTabBtn.style.color = '#475569';
        } else {
            reqTabBtn.style.background = 'var(--boc-navy)';
            reqTabBtn.style.color = '#fff';
            donorTabBtn.style.background = '#e2e8f0';
            donorTabBtn.style.color = '#475569';
        }
    }

    // Initialize triggers on loading document
    document.addEventListener('DOMContentLoaded', loadBloodVerifyRegistry);
</script>

<?php
get_footer();
