<?php
/**
 * Template Name: Identity Verification
 *
 * This file acts as both the administrative dashboard for managing Tatkhalsa Foundation personnel verification
 * and the public-facing landing page for scanning ID cards.
 */

// Force cache-busting headers to prevent stale, cached data display
if ( ! defined( 'DONOTCACHEPAGE' ) ) {
    define( 'DONOTCACHEPAGE', true );
}
nocache_headers();

// Initialize WordPress Environment
global $wpdb;
$table_name = $wpdb->prefix . 'tkf_verifications';

// Helper function to safely format dates and avoid database default-fallback issues
if ( ! function_exists( 'tkf_format_date' ) ) {
    function tkf_format_date( $date_str, $fallback = 'N/A' ) {
        if ( empty( $date_str ) || $date_str === '0000-00-00' || $date_str === '0000-00-00 00:00:00' ) {
            return $fallback;
        }
        $ts = strtotime( $date_str );
        if ( ! $ts || $ts <= 0 ) {
            return $fallback;
        }
        return strtoupper( date( 'd M Y', $ts ) );
    }
}

// Interactive Mobile Wallet Integration for Apple Wallet, Google Wallet, and Samsung Wallet
if ( ! function_exists( 'tkf_render_mobile_wallet_hub' ) ) {
    function tkf_render_mobile_wallet_hub( $member, $token = '' ) {
        // Save to Wallet option removed for now per user request
        return;
        if ( ! $member ) return;
        $logo_url = 'https://tatkhalsa.in/wp-content/uploads/2026/06/cropped-Logo.png';
        $verify_url = esc_url( home_url('/verify/?member_id=' . $member->member_id) );
        ?>
        <style>
            /* MOBILE WALLET STYLING */
            .tkf-wallet-container {
                background: #ffffff;
                border-radius: 14px;
                padding: 24px;
                border: 1.5px solid #e2e8f0;
                box-shadow: 0 4px 15px rgba(5,32,84,0.04);
                margin: 20px 0;
                text-align: left;
                width: 100%;
                box-sizing: border-box;
            }
            .tkf-wallet-title {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 14px;
                font-weight: 800;
                color: #052054;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin: 0 0 4px 0;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .tkf-wallet-subtitle {
                font-size: 11px;
                color: #718096;
                margin: 0 0 20px 0;
                line-height: 1.4;
            }
            .tkf-wallet-grid {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: flex-start;
            }
            
            /* Professional Wallet Buttons */
            .tkf-w-btn {
                flex: 1;
                min-width: 165px;
                height: 44px;
                border-radius: 8px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                font-weight: 700;
                font-size: 11.5px;
                transition: all 0.2s ease;
                cursor: pointer;
                border: none;
                margin: 0;
                box-sizing: border-box;
                gap: 8px;
                text-transform: uppercase;
                letter-spacing: 0.2px;
            }
            
            /* Apple Wallet Button Stylings */
            .tkf-w-btn-apple {
                background: #000000;
                color: #ffffff;
                border: 1px solid #ffffff33;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }
            .tkf-w-btn-apple:hover {
                background: #111111;
                border-color: #ffffff55;
                transform: translateY(-1.5px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            }
            
            /* Google Wallet Button Stylings */
            .tkf-w-btn-google {
                background: #000000;
                color: #ffffff;
                border: 1px solid #ffffff22;
                font-family: 'Google Sans', sans-serif;
            }
            .tkf-w-btn-google:hover {
                background: #111111;
                border-color: #ffffff44;
                transform: translateY(-1.5px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }
            
            /* Samsung Wallet Button Stylings */
            .tkf-w-btn-samsung {
                background: #000000;
                color: #ffffff;
                border: 1px solid #ffffff22;
                font-family: 'SamsungOne', sans-serif;
            }
            .tkf-w-btn-samsung:hover {
                background: #0c0c0c;
                border-color: #ffffff44;
                transform: translateY(-1.5px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }

            /* MODAL OVERLAY & CARD CAROUSEL */
            .tkf-wallet-modal-overlay {
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(5, 14, 30, 0.75);
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
                overflow-y: auto;
            }
            .tkf-wallet-modal-overlay.active {
                display: flex;
                opacity: 1;
            }
            .tkf-wallet-modal {
                background: #ffffff;
                width: 100%;
                max-width: 460px;
                border-radius: 18px;
                box-shadow: 0 25px 60px rgba(0,0,0,0.35);
                overflow: hidden;
                transform: translateY(20px) scale(0.95);
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                position: relative;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                max-height: 90vh;
            }
            .tkf-wallet-modal-overlay.active .tkf-wallet-modal {
                transform: translateY(0) scale(1);
            }
            .tkf-wallet-modal-close {
                position: absolute;
                top: 15px; right: 15px;
                width: 30px; height: 30px;
                border-radius: 50%;
                background: rgba(0,0,0,0.06);
                color: #1a202c;
                border: none;
                font-size: 18px;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s;
                z-index: 100;
            }
            .tkf-wallet-modal-close:hover {
                background: rgba(0,0,0,0.12);
                transform: scale(1.05);
            }

            /* SMARTPHONE PASS PREVIEW WRAPPER */
            .tkf-w-phone-body {
                background: #f7fafc;
                padding: 30px 24px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border-bottom: 1px solid #edf2f7;
            }
            /* Digital Wallet Pass Mock Graphics */
            .tkf-w-pass-mock {
                width: 250px;
                background: #052054;
                border-radius: 12px;
                padding: 16px;
                box-shadow: 0 10px 25px rgba(5,32,84,0.3);
                color: #ffffff;
                box-sizing: border-box;
                position: relative;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                overflow: hidden;
            }
            .tkf-w-pass-mock::before {
                content: '';
                position: absolute;
                top: 0; left: 0; width: 100%; height: 3px;
                background: #E1A92A;
            }
            .tkf-w-pass-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 0.5px solid rgba(255,255,255,0.15);
                padding-bottom: 8px;
                margin-bottom: 12px;
            }
            .tkf-w-pass-org {
                font-size: 9px;
                font-weight: 800;
                letter-spacing: 0.6px;
                text-transform: uppercase;
                color: #E1A92A;
            }
            .tkf-w-pass-logo {
                width: 14px;
                height: 14px;
                object-fit: contain;
                filter: brightness(1.1);
            }
            .tkf-w-pass-mid-grid {
                display: flex;
                gap: 12px;
                margin-bottom: 12px;
            }
            .tkf-w-pass-photo {
                width: 54px;
                height: 70px;
                border-radius: 4px;
                object-fit: cover;
                object-position: center top;
                border: 1px solid rgba(225, 169, 42, 0.4);
                background: #ffffff;
            }
            .tkf-w-pass-fields {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 4px;
                justify-content: center;
            }
            .tkf-w-pass-field-lbl {
                font-size: 6px;
                font-weight: 600;
                color: rgba(255,255,255,0.5);
                text-transform: uppercase;
                letter-spacing: 0.3px;
                line-height: 1;
            }
            .tkf-w-pass-field-val {
                font-size: 9px;
                font-weight: 700;
                color: #ffffff;
                line-height: 1.1;
            }
            .tkf-w-pass-name {
                font-size: 11px;
                font-weight: 800;
                color: #E1A92A;
                text-transform: uppercase;
                letter-spacing: 0.1px;
            }
            .tkf-w-pass-barcode-box {
                background: #ffffff;
                padding: 6px;
                border-radius: 6px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                margin-top: 6px;
            }
            .tkf-w-pass-barcode-img {
                width: 150px;
                height: 38px;
                object-fit: fill;
                image-rendering: pixelated;
            }
            .tkf-w-pass-barcode-text {
                font-size: 6.5px;
                font-weight: 800;
                font-family: monospace;
                color: #1a202c;
                margin-top: 3px;
                letter-spacing: 1px;
            }
            
            /* Modal content area */
            .tkf-w-details-area {
                padding: 24px;
                flex: 1;
                overflow-y: auto;
                box-sizing: border-box;
            }
            .tkf-w-modal-title {
                font-size: 16px;
                font-weight: 800;
                color: #052054;
                margin: 0 0 6px 0;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .tkf-w-modal-desc {
                font-size: 12px;
                color: #4a5568;
                line-height: 1.5;
                margin: 0 0 20px 0;
            }
            
            /* Download action buttons inside modal */
            .tkf-modal-actions {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .tkf-btn-primary-action {
                background: linear-gradient(135deg, #052054, #051a44);
                color: #E1A92A;
                border: 1px solid #E1A92A99;
                text-align: center;
                padding: 11px;
                border-radius: 8px;
                font-weight: 800;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                box-shadow: 0 4px 12px rgba(5,32,84,0.15);
            }
            .tkf-btn-primary-action:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 15px rgba(5,32,84,0.25);
                background: linear-gradient(135deg, #07286a, #051e4f);
            }
            
            /* Admin Toggle / Dev Guide */
            .tkf-admin-dev-guide {
                margin-top: 20px;
                border-top: 1px dashed #cbd5e0;
                padding-top: 15px;
            }
            .tkf-guide-toggle-btn {
                background: transparent;
                border: none;
                color: #052054;
                font-size: 11px;
                font-weight: 700;
                text-decoration: underline;
                cursor: pointer;
                padding: 0;
                display: flex;
                align-items: center;
                gap: 4px;
            }
            .tkf-guide-body {
                background: #f7fafc;
                border-left: 3px solid #E1A92A;
                padding: 12px;
                border-radius: 0 6px 6px 0;
                font-size: 10px;
                color: #2d3748;
                line-height: 1.45;
                font-family: monospace;
                margin-top: 8px;
                display: none;
                word-break: break-all;
                white-space: pre-wrap;
            }
        </style>

        <div class="tkf-wallet-container no-print">
            <h4 class="tkf-wallet-title">
                <svg style="width: 14px; height: 14px; fill: currentColor;" viewBox="0 0 24 24">
                    <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                </svg>
                SAVE TO SMARTPHONE WALLET
            </h4>
            <p class="tkf-wallet-subtitle">Keep your secure personnel credentials directly accessible on your mobile phone with cryptographic verification, biometric security, and lightweight offline access.</p>
            
            <div class="tkf-wallet-grid">
                <!-- Apple Wallet Button -->
                <button class="tkf-w-btn tkf-w-btn-apple" onclick="tkfOpenWalletModal('apple')">
                    <svg style="width:13px; height:16px; fill:currentColor;" viewBox="0 0 170 170">
                        <path d="M150.37 130.25c-2.45 5.66-5.35 10.87-8.71 15.66-4.58 6.53-8.33 11.05-11.22 13.56-4.48 4.12-9.28 6.23-14.42 6.35-3.69 0-8.14-1.05-13.32-3.18-5.19-2.12-9.97-3.17-14.34-3.17-4.58 0-9.49 1.05-14.75 3.17-5.26 2.13-9.5 3.24-12.74 3.35-4.34.13-9.13-1.9-14.37-6.08-3.73-3.05-7.73-7.9-12.01-14.54-5.3-8.1-9.45-17.15-12.47-27.15-3.14-10.37-4.71-20.31-4.71-29.8 0-14.54 3.65-26.33 10.96-35.37 7.3-9.04 16.3-13.6 26.97-13.6 5.56 0 11.5 1.5 17.8 4.5 6.3 3 10.23 4.5 11.8 4.5 1.7 0 5.43-1.4 11.19-4.2 5.76-2.8 11.28-4.2 16.56-4.2 12.08 0 22.21 4.3 30.39 12.9 8.18 8.6 12.44 19.1 12.77 31.5-10.97 6.44-16.3 14.8-15.97 25.1.33 10.3 4.11 18.5 11.35 24.6 7.24 6.1 14.9 9.3 22.97 9.6-1.12 4.9-2.6 9.5-4.45 14zM119.22 4.49c0 8.04-2.86 15.22-8.58 21.56-5.72 6.33-12.57 9.87-20.54 10.61-.17-.83-.26-1.72-.26-2.67 0-7.6 2.82-14.65 8.46-21.13 5.64-6.48 12.63-10.23 20.97-11.25.17.83.25 1.79.25 2.88z"/>
                    </svg>
                    Apple Wallet
                </button>

                <!-- Google Wallet Button -->
                <button class="tkf-w-btn tkf-w-btn-google" onclick="tkfOpenWalletModal('google')">
                    <svg style="width:16px; height:16px;" viewBox="0 0 48 48" fill="none">
                        <path d="M36 12 C33 9, 28 8, 24 8 C15.16 8, 8 15.16, 8 24 C8 32.84, 15.16 40, 24 40 C28 40, 33 39, 36 36" stroke="#4285F4" stroke-width="4.5" stroke-linecap="round" fill="none" />
                        <path d="M38 18 C40.2 21, 41 24, 41 27 C41 33.08, 36.08 38, 30 38" stroke="#EA4335" stroke-width="4.5" stroke-linecap="round" fill="none" />
                        <path d="M28 10 C32 10, 36 12, 39 15" stroke="#FBBC05" stroke-width="4.5" stroke-linecap="round" fill="none" />
                    </svg>
                    Google Wallet
                </button>

                <!-- Samsung Wallet Button -->
                <button class="tkf-w-btn tkf-w-btn-samsung" onclick="tkfOpenWalletModal('samsung')">
                    <svg style="width:14px; height:14px; fill:currentColor;" viewBox="0 0 24 24">
                        <path d="M21 11.5a8.38 8.38 0 0 1-1.9 5.3 8.38 8.38 0 0 1-5.3 1.9 8.38 8.38 0 0 1-5.3-1.9 8.38 8.38 0 0 1-1.9-5.3c0-4.6 3.8-8.3 8.3-8.3a8.3 8.3 0 0 1 8.3 8.3zm-8.3-6.2a6.2 6.2 0 1 0 6.2 6.2 6.2 6.2 0 0 0-6.2-6.2z"/>
                    </svg>
                    Samsung Wallet
                </button>
            </div>
        </div>

        <!-- WALLET GATEWAY MODAL OVERLAY -->
        <div id="tkf-wallet-modal-overlay" class="tkf-wallet-modal-overlay no-print" onclick="tkfCloseWalletModal(event)">
            <div class="tkf-wallet-modal" onclick="event.stopPropagation()">
                <button class="tkf-wallet-modal-close" onclick="tkfCloseWalletModal(event)">&times;</button>
                
                <!-- PASS DEVICE VIEW CONTAINER -->
                <div class="tkf-w-phone-body">
                    <div class="tkf-w-pass-mock">
                        <div class="tkf-w-pass-header">
                            <span class="tkf-w-pass-org">Tatkhalsa Foundation</span>
                            <img src="<?php echo esc_url($logo_url); ?>" class="tkf-w-pass-logo" alt="">
                        </div>
                        
                        <div class="tkf-w-pass-mid-grid">
                            <?php if ( ! empty($member->photo_url) ) : ?>
                                <img src="<?php echo esc_url($member->photo_url); ?>" class="tkf-w-pass-photo" alt="">
                            <?php else: ?>
                                <div class="tkf-w-pass-photo" style="background:#f4f6f9; display:flex; align-items:center; justify-content:center; padding:10px; box-sizing:border-box;">
                                    <img src="<?php echo esc_url($logo_url); ?>" style="width:100%; height:100%; object-fit:contain;" alt="">
                                </div>
                            <?php endif; ?>
                            
                            <div class="tkf-w-pass-fields">
                                <div class="tkf-w-pass-field-lbl">Official Personnel</div>
                                <div class="tkf-w-pass-name"><?php echo esc_html($member->full_name); ?></div>
                                
                                <div style="margin-top: 4px;">
                                    <div class="tkf-w-pass-field-lbl">Designation</div>
                                    <div class="tkf-w-pass-field-val" style="font-size: 8px; color: #E1A92A; font-weight:700;"><?php echo esc_html($member->designation); ?></div>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; border-top: 0.5px solid rgba(255,255,255,0.1); padding-top: 6px;">
                            <div>
                                <span class="tkf-w-pass-field-lbl">Personnel ID</span>
                                <div class="tkf-w-pass-field-val" style="font-family:monospace; font-size: 8px;"><?php echo esc_html($member->member_id); ?></div>
                            </div>
                            <div style="text-align: right;">
                                <span class="tkf-w-pass-field-lbl">Pass Status</span>
                                <div class="tkf-w-pass-field-val" style="color:#28a745; font-size: 7.5px; text-transform:uppercase; font-weight:800;">✓ ACTIVE</div>
                            </div>
                        </div>

                        <!-- Barcode Section -->
                        <div class="tkf-w-pass-barcode-box">
                            <img class="tkf-w-pass-barcode-img" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($verify_url); ?>" style="width:85px; height:85px; object-fit:contain;" alt="Barcode">
                            <span class="tkf-w-pass-barcode-text">MEMBERSHIP CARD SCAN</span>
                        </div>
                    </div>
                </div>

                <!-- DETAILS ACTION CONTAINER -->
                <div class="tkf-w-details-area">
                    <h3 id="tkf-wallet-modal-title" class="tkf-w-modal-title">Save to Wallet</h3>
                    <p id="tkf-wallet-modal-desc" class="tkf-w-modal-desc">Your digital personnel pass is ready to package. Download your pass file below or configure live dynamic signing credentials on your WordPress production server.</p>
                    
                    <div class="tkf-modal-actions">
                        <a id="tkf-pass-download-link" href="#" class="tkf-btn-primary-action">
                            <svg style="width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2.5;" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download Digital Pass File
                        </a>
                    </div>

                    <!-- Developer Integration Details -->
                    <div class="tkf-admin-dev-guide">
                        <button class="tkf-guide-toggle-btn" onclick="tkfToggleDevGuide(event)">
                            🛠️ Production Certificate Configuration (For Admin)
                        </button>
                        <div id="tkf-guide-body" class="tkf-guide-body">To publish fully certified, digitally signed wallet passes directly to iOS and Android devices, integrate your production certs:

1. Apple Wallet (.pkpass):
Open 'wp-config.php' and define the Apple Developer keys:
define('APPLE_PASS_TYPE_IDENTIFIER', 'pass.org.tatkhalsa.member');
define('APPLE_TEAM_IDENTIFIER', 'ABC123XYZ45');

2. Google Wallet:
Setup Google Pay API Console, download Google Service Key JSON file and declare:
define('GOOGLE_WALLET_SERVICE_ACCOUNT_EMAIL', 'wallet-service@project.iam.gserviceaccount.com');
define('GOOGLE_WALLET_PRIVATE_KEY', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqh...\n-----END PRIVATE KEY-----');

3. Samsung Wallet:
Samsung Wallet API JWT uses Knox tokens. Declare:
define('SAMSUNG_WALLET_JWT_SECRET', 'your-sec-samsung-knox-jwt-token-string');

Once environment constants are configured, the page-verify backend automatically compiles signed headers!</div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function tkfOpenWalletModal(walletType) {
                var overlay = document.getElementById('tkf-wallet-modal-overlay');
                var title = document.getElementById('tkf-wallet-modal-title');
                var downloadBtn = document.getElementById('tkf-pass-download-link');
                var desc = document.getElementById('tkf-wallet-modal-desc');
                
                if (walletType === 'apple') {
                    title.innerHTML = 'Apple Wallet Pass Setup';
                    desc.innerHTML = '<div style="margin-top:10px; font-size:12px; line-height:1.5; color:#4a5568;">' +
                        '<strong style="color:#052054;"> How to add to Apple Wallet:</strong><br>' +
                        '• <strong>On iPhone (Safari/Safari-based browser)</strong>: Simply click the button below. iOS will natively recognize the .pkpass format and prompt you with an "Add to Wallet" sheet.<br>' +
                        '• <strong>On Mac/Desktop</strong>: Download the .pkpass file, then send it via AirDrop, Email, or iMessage to your iPhone, where it will open instantly in your wallet.<br>' +
                        '• <strong>On Android</strong>: Download the .pkpass file and import it using utility wallet apps such as <em>Pass2U Wallet</em> or <em>WalletPasses</em>.' +
                        '</div>';
                    downloadBtn.innerHTML = 'Download Apple Wallet Pass (.pkpass)';
                    downloadBtn.href = '?download_pass=<?php echo urlencode($member->member_id); ?>&wallet_type=apple&token=<?php echo urlencode($token); ?>';
                } else if (walletType === 'google') {
                    title.innerHTML = 'Google Wallet Save Setup';
                    desc.innerHTML = '<div style="margin-top:10px; font-size:12px; line-height:1.5; color:#4a5568;">' +
                        '<strong style="color:#052054;">🤖 How to add to Google Wallet:</strong><br>' +
                        '• <strong>On Android</strong>: Click the button below to retrieve the .json payload. You can load this into Google Wallet or compatible companion apps (like Wallet Cards).<br>' +
                        '• <strong>Production Note</strong>: Once Google Service Accounts are declared, you can render direct "Save to Google Wallet" deep integration badges.' +
                        '</div>';
                    downloadBtn.innerHTML = 'Save to Google Wallet (.json)';
                    downloadBtn.href = '?download_pass=<?php echo urlencode($member->member_id); ?>&wallet_type=google&token=<?php echo urlencode($token); ?>';
                } else {
                    title.innerHTML = 'Samsung Wallet Pass Setup';
                    desc.innerHTML = '<div style="margin-top:10px; font-size:12px; line-height:1.5; color:#4a5568;">' +
                        '<strong style="color:#052054;">🌌 How to add to Samsung Wallet:</strong><br>' +
                        '• <strong>On Samsung Galaxy devices</strong>: Download the Samsung format file and open it with Samsung Wallet / Samsung Pay.<br>' +
                        '• <strong>Security Note</strong>: Samsung Knox JWT secures production tokens for live card signing.' +
                        '</div>';
                    downloadBtn.innerHTML = 'Add to Samsung Wallet (.json)';
                    downloadBtn.href = '?download_pass=<?php echo urlencode($member->member_id); ?>&wallet_type=samsung&token=<?php echo urlencode($token); ?>';
                }
                
                overlay.classList.add('active');
            }

            function tkfCloseWalletModal(e) {
                var overlay = document.getElementById('tkf-wallet-modal-overlay');
                overlay.classList.remove('active');
            }

            function tkfToggleDevGuide(e) {
                if (e) e.preventDefault();
                var guide = document.getElementById('tkf-guide-body');
                if (guide.style.display === 'block') {
                    guide.style.display = 'none';
                } else {
                    guide.style.display = 'block';
                }
            }
        </script>
        <?php
    }
}

// 1. DATABASE SETUP & ARCHITECTURE: Automatically build the table if it does not exist
$charset_collate = $wpdb->get_charset_collate();
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    member_id varchar(100) NOT NULL,
    full_name varchar(255) NOT NULL,
    designation varchar(255) NOT NULL,
    photo_url text,
    expiry_date date DEFAULT NULL,
    issue_date date DEFAULT NULL,
    gov_id varchar(100),
    email varchar(255),
    mobile varchar(50),
    alt_mobile varchar(50),
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
    if ( ! in_array('issue_date', $columns) ) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN issue_date date DEFAULT NULL");
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
    if ( ! in_array('alt_mobile', $columns) ) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN alt_mobile varchar(50)");
    }
    if ( ! in_array('blood_group', $columns) ) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN blood_group varchar(10)");
    }
}

// Process Form Submissions for Admin View
$message = isset( $_GET['tkf_msg'] ) ? sanitize_text_field( wp_unslash( $_GET['tkf_msg'] ) ) : '';
$message_type = isset( $_GET['tkf_msg_type'] ) ? sanitize_text_field( wp_unslash( $_GET['tkf_msg_type'] ) ) : '';

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
        if ( $expiry_date === '0000-00-00' ) { $expiry_date = ''; }
        $issue_date  = sanitize_text_field( wp_unslash( $_POST['issue_date'] ) );
        if ( $issue_date === '0000-00-00' ) { $issue_date = ''; }
        $gov_id      = sanitize_text_field( wp_unslash( $_POST['gov_id'] ) );
        $email       = sanitize_email( wp_unslash( $_POST['email'] ) );
        $mobile      = sanitize_text_field( wp_unslash( $_POST['mobile'] ) );
        $alt_mobile  = sanitize_text_field( wp_unslash( $_POST['alt_mobile'] ) );
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
                'alt_mobile'  => $alt_mobile,
                'blood_group' => $blood_group,
                'status'      => 'Active'
            );
            $insert_format = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

            if ( ! empty( $expiry_date ) ) {
                $insert_data['expiry_date'] = $expiry_date;
                $insert_format[] = '%s';
            }

            if ( ! empty( $issue_date ) ) {
                $insert_data['issue_date'] = $issue_date;
                $insert_format[] = '%s';
            }

            $inserted = $wpdb->insert(
                $table_name,
                $insert_data,
                $insert_format
            );

            if ( $inserted ) {
                wp_safe_redirect( add_query_arg( array( 'tkf_msg' => 'New personnel record added successfully.', 'tkf_msg_type' => 'success' ), remove_query_arg( array( 'edit_id', 'tkf_msg', 'tkf_msg_type' ) ) ) );
                exit;
            } else {
                $message = 'Database error: Failed to add member. ' . $wpdb->last_error;
                $message_type = 'error';
            }
        }
    } elseif ( $action === 'edit_member' ) {
        $id          = intval( $_POST['id'] );
        $member_id   = sanitize_text_field( wp_unslash( $_POST['member_id'] ) );
        $full_name   = sanitize_text_field( wp_unslash( $_POST['full_name'] ) );
        $designation = sanitize_text_field( wp_unslash( $_POST['designation'] ) );
        $photo_url   = esc_url_raw( wp_unslash( $_POST['photo_url'] ) );
        $expiry_date = sanitize_text_field( wp_unslash( $_POST['expiry_date'] ) );
        if ( $expiry_date === '0000-00-00' ) { $expiry_date = ''; }
        $issue_date  = sanitize_text_field( wp_unslash( $_POST['issue_date'] ) );
        if ( $issue_date === '0000-00-00' ) { $issue_date = ''; }
        $gov_id      = sanitize_text_field( wp_unslash( $_POST['gov_id'] ) );
        $email       = sanitize_email( wp_unslash( $_POST['email'] ) );
        $mobile      = sanitize_text_field( wp_unslash( $_POST['mobile'] ) );
        $alt_mobile  = sanitize_text_field( wp_unslash( $_POST['alt_mobile'] ) );
        $blood_group = sanitize_text_field( wp_unslash( $_POST['blood_group'] ) );

        // Protect against SQL Injection: Check if member ID exists securely on other records
        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE member_id = %s AND id != %d", $member_id, $id ) );

        if ( $exists ) {
            $message = 'Member ID already exists.';
            $message_type = 'error';
        } else {
            $update_data = array(
                'member_id'   => $member_id,
                'full_name'   => $full_name,
                'designation' => $designation,
                'photo_url'   => $photo_url,
                'gov_id'      => $gov_id,
                'email'       => $email,
                'mobile'      => $mobile,
                'alt_mobile'  => $alt_mobile,
                'blood_group' => $blood_group,
            );
            $update_format = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

            $update_data['expiry_date'] = ! empty( $expiry_date ) ? $expiry_date : null;
            $update_format[] = '%s';

            $update_data['issue_date'] = ! empty( $issue_date ) ? $issue_date : null;
            $update_format[] = '%s';

            $updated = $wpdb->update(
                $table_name,
                $update_data,
                array( 'id' => $id ),
                $update_format,
                array( '%d' )
            );

            if ( $updated !== false ) {
                wp_safe_redirect( add_query_arg( array( 'tkf_msg' => 'Personnel record updated successfully.', 'tkf_msg_type' => 'success' ), remove_query_arg( array( 'edit_id', 'tkf_msg', 'tkf_msg_type' ) ) ) );
                exit;
            } else {
                $message = 'Database error: Failed to update member. ' . $wpdb->last_error;
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
        $msg = "Personnel status securely updated to $new_status.";
        wp_safe_redirect( add_query_arg( array( 'tkf_msg' => $msg, 'tkf_msg_type' => 'success' ), remove_query_arg( array( 'edit_id', 'tkf_msg', 'tkf_msg_type' ) ) ) );
        exit;
    } elseif ( $action === 'delete_member' ) {
        $id = intval( $_POST['id'] );
        $wpdb->delete(
            $table_name,
            array( 'id' => $id ),
            array( '%d' )
        );
        $msg = 'Personnel record permanently deleted.';
        wp_safe_redirect( add_query_arg( array( 'tkf_msg' => $msg, 'tkf_msg_type' => 'success' ), remove_query_arg( array( 'edit_id', 'tkf_msg', 'tkf_msg_type' ) ) ) );
        exit;
    } elseif ( $action === 'send_member_email' ) {
        $id = intval( $_POST['id'] );
        $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ) );
        if ( ! $member ) {
            $message = 'Member not found.';
            $message_type = 'error';
        } elseif ( empty( $member->email ) ) {
            $message = 'Error: This member does not have an email address associated with their profile.';
            $message_type = 'error';
        } else {
            // Retrieve values for email
            $to = $member->email;
            $subject = 'Official Identity Verification & ID Card Notification - ' . $member->member_id;
            $verify_url = esc_url( home_url('/verify/?member_id=' . $member->member_id) );
            $email_token = wp_hash( $member->member_id . '|' . $member->email, 'secure' );
            $email_download_url = esc_url( home_url('/verify/?download_id=' . $member->member_id . '&token=' . $email_token) );
            
            // Build a highly professional responsive HTML email body mimicking Tatkhalsa official brand
            $body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
                <title>Secured Identity Information</title>
                <style>
                    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #2d3748; background-color: #f7fafc; margin: 0; padding: 0; }
                    .wrapper { background-color: #f7fafc; width: 100%; padding: 40px 0; }
                    .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #e2e8f0; }
                    .header { background: #052054; padding: 30px 20px; text-align: center; color: #ffffff; border-bottom: 4px solid #E1A92A; }
                    .header h2 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
                    .header .sub { font-size: 11px; font-weight: 600; color: #E1A92A; margin-top: 5px; text-transform: uppercase; letter-spacing: 2px; }
                    .content { padding: 30px; }
                    .content h3 { font-size: 18px; color: #052054; margin-top: 0; font-weight: 700; border-left: 3px solid #E1A92A; padding-left: 10px; }
                    .info-card { background: #f8fafc; border: 1px solid #edf2f7; border-radius: 8px; padding: 20px; margin: 20px 0; }
                    .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #edf2f7; }
                    .info-row:last-child { border-bottom: none; }
                    .info-label { font-weight: 700; color: #718096; font-size: 12px; text-transform: uppercase; }
                    .info-value { font-weight: 600; color: #1a202c; font-size: 13px; text-align: right; }
                    .btn-wrap { text-align: center; margin: 30px 0; }
                    .btn-verify { display: inline-block; background-color: #E1A92A; color: #052054 !important; text-decoration: none; font-weight: 800; font-size: 13px; letter-spacing: 0.5px; padding: 14px 28px; border-radius: 6px; text-transform: uppercase; box-shadow: 0 4px 10px rgba(225, 169, 42, 0.25); border: 1px solid #d49a1f; transition: all 0.2s ease; }
                    .footer { text-align: center; font-size: 11px; color: #718096; margin-top: 30px; border-top: 1px solid #edf2f7; padding: 20px; background: #fafcb4; background-color: #f7fafc; }
                    .footer p { margin: 4px 0; }
                </style>
            </head>
            <body>
                <div class="wrapper">
                    <div class="container">
                        <div class="header">
                            <h2>TATKHALSA FOUNDATION</h2>
                            <div class="sub">Secured Personnel Identity Directory</div>
                        </div>
                        <div class="content">
                            <h3>Official Identity Manifest Activation</h3>
                            <p>Sat Sri Akal, <strong>' . esc_html( $member->full_name ) . '</strong>,</p>
                            <p>Your official credentials and secure registration status have been successfully configured on the Tatkhalsa secure identity verification subsystem.</p>
                            
                            <div class="info-card">
                                <div class="info-row">
                                    <span class="info-label">Personnel Member ID</span>
                                    <span class="info-value" style="font-family: monospace;">' . esc_html( $member->member_id ) . '</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Role Capacity</span>
                                    <span class="info-value">' . esc_html( $member->designation ) . '</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Security Cleared Status</span>
                                    <span class="info-value" style="color: #2f855a;">● ' . esc_html( $member->status ) . '</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Term Expiration</span>
                                    <span class="info-value">' . esc_html( tkf_format_date( $member->expiry_date, 'PERMANENT BENEFICIARY / LIFETIME' ) ) . '</span>
                                </div>
                            </div>
                            
                            <p>To view your official digitized smart ID card, access secure bank remittance details, or self-print your verification pass, tap the buttons below:</p>
                            
                            <div class="btn-wrap">
                                <a href="' . $verify_url . '" class="btn-verify" style="margin: 5px; display: inline-block;">View Verification Page</a>
                                <a href="' . $email_download_url . '" class="btn-verify" style="margin: 5px; display: inline-block; background-color: #052054; color: #ffffff !important; border: 1px solid #031538; box-shadow: 0 4px 10px rgba(5,32,84,0.15);">Print / Download ID Card</a>
                            </div>
                            
                            <p style="font-size: 13px; color: #718096; margin-top: 20px;">If scanning a physical barcoded tag or QR sticker on your card, it will land directly back to this same verified directory status profile for official confirmation.</p>
                        </div>
                        <div class="footer">
                            <p>This notification was securely triggered by the Tatkhalsa Engineering Division on behalf of the foundation trustees.</p>
                            <p>Sender Authority: <strong>tech-team@tatkhalsa.in</strong></p>
                            <p>Security inquiries or correction requests: <a href="mailto:info@tatkhalsa.in" style="color: #052054; text-decoration: underline;">info@tatkhalsa.in</a></p>
                            <p style="margin-top: 15px; font-weight: bold;">&copy; 2023-' . date('Y') . ' Tatkhalsa Foundation. All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>';
            
            // Set Headers to specify HTML content and the precise From address
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: Tatkhalsa Tech Team <tech-team@tatkhalsa.in>',
                'Reply-To: Tatkhalsa Info <info@tatkhalsa.in>'
            );
            
            $sent = wp_mail( $to, $subject, $body, $headers );
            if ( $sent ) {
                $msg = 'Direct official verification email successfully dispatched to ' . $to . ' from tech-team@tatkhalsa.in.';
                wp_safe_redirect( add_query_arg( array( 'tkf_msg' => $msg, 'tkf_msg_type' => 'success' ), remove_query_arg( array( 'edit_id', 'tkf_msg', 'tkf_msg_type' ) ) ) );
                exit;
            } else {
                $message = 'Error: System mail agent failed to deliver email. Please check your server SMTP settings.';
                $message_type = 'error';
            }
        }
    }
}


    // Intercept routing logic for generating/downloading Mobile Wallet Pass
    $download_pass = isset( $_GET['download_pass'] ) ? sanitize_text_field( wp_unslash( $_GET['download_pass'] ) ) : '';
    if ( ! empty( $download_pass ) ) {
        $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE member_id = %s", $download_pass ) );
        if ( ! $member ) {
            wp_die( 'Member not found.' );
        }

        // Secure token check to keep pass generation private matching secure email link
        $token = isset( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : '';
        $is_valid_email_recipient = false;
        if ( ! empty( $token ) && $member && ! empty( $member->email ) ) {
            $expected_token = wp_hash( $member->member_id . '|' . $member->email, 'secure' );
            if ( hash_equals( $expected_token, $token ) ) {
                $is_valid_email_recipient = true;
            }
        }

        // Must be admin or have a valid email token
        if ( ! current_user_can( 'manage_options' ) && ! $is_valid_email_recipient ) {
            wp_die( 'Unauthorized Access. Privately secured pass generation.' );
        }

        $wallet_type = isset( $_GET['wallet_type'] ) ? sanitize_text_field( wp_unslash( $_GET['wallet_type'] ) ) : 'apple';

        // Build the JSON pass descriptor payload
        $pass_data = array(
            'organizationName' => 'Tatkhalsa Foundation',
            'passTypeIdentifier' => 'pass.org.tatkhalsa.member.digital',
            'serialNumber' => 'TKF-' . strtoupper(str_replace('-', '', $member->member_id)),
            'teamIdentifier' => 'TKF98274H23',
            'foregroundColor' => '#E1A92A',
            'backgroundColor' => '#052054',
            'labelColor' => '#ffffff',
            'logoText' => 'Tatkhalsa Foundation',
            'memberInfo' => array(
                'id' => $member->member_id,
                'name' => $member->full_name,
                'designation' => $member->designation,
                'status' => $member->status,
                'issue_date' => tkf_format_date( $member->issue_date, '16 JUN 2026' ),
                'valid_till' => tkf_format_date( $member->expiry_date, 'LIFETIME' ),
                'blood_group' => ! empty($member->blood_group) ? $member->blood_group : 'N/A'
            ),
            'barcode' => array(
                'message' => $member->member_id,
                'format' => 'PKBarcodeFormatQR',
                'messageEncoding' => 'iso-8859-1',
                'altText' => $member->member_id
            ),
            'verificationURL' => esc_url( home_url('/verify/?member_id=' . $member->member_id) )
        );

        if ( $wallet_type === 'apple' ) {
            header('Content-Type: application/vnd.apple.pkpass');
            header('Content-Disposition: attachment; filename="tkf_pass_' . $member->member_id . '.pkpass"');
            
            if ( class_exists('ZipArchive') ) {
                $zip = new ZipArchive();
                $temp_file = tempnam(sys_get_temp_dir(), 'pkpass');
                if ( $zip->open($temp_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE ) {
                    $zip->addFromString('pass.json', json_encode($pass_data, JSON_PRETTY_PRINT));
                    $manifest = array();
                    $manifest['pass.json'] = sha1(json_encode($pass_data, JSON_PRETTY_PRINT));
                    $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
                    $zip->addFromString('signature', 'Self-Signed Prototype Pass Signature Block');
                    $zip->close();
                    
                    readfile($temp_file);
                    unlink($temp_file);
                    exit;
                }
            }
            echo json_encode($pass_data, JSON_PRETTY_PRINT);
            exit;
        } elseif ( $wallet_type === 'google' ) {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="tkf_google_wallet_' . $member->member_id . '.json"');
            echo json_encode(array(
                'classId' => 'tkf_membership_class',
                'id' => 'tkf_member_pass_' . $member->member_id,
                'state' => 'ACTIVE',
                'logo' => 'https://tatkhalsa.in/wp-content/uploads/2026/06/cropped-Logo.png',
                'cardTitle' => 'TATKHALSA FOUNDATION',
                'subheader' => 'SECURE OFFICIAL PERSONNEL',
                'header' => $member->full_name,
                'barcode' => array(
                    'type' => 'QR_CODE',
                    'value' => $member->member_id,
                    'alternateText' => $member->member_id
                ),
                'hexBackgroundColor' => '#052054',
                'heroImage' => $member->photo_url,
                'customFields' => $pass_data['memberInfo']
            ), JSON_PRETTY_PRINT);
            exit;
        } else {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="tkf_samsung_wallet_' . $member->member_id . '.json"');
            echo json_encode(array(
                'version' => '1.0',
                'type' => 'membership',
                'publisher' => 'Tatkhalsa Foundation',
                'id' => $member->member_id,
                'user' => array(
                    'name' => $member->full_name,
                    'designation' => $member->designation
                ),
                'barcode' => $member->member_id,
                'customFields' => $pass_data['memberInfo']
            ), JSON_PRETTY_PRINT);
            exit;
        }
    }

    // Intercept routing logic for printing ID Card
$download_id = isset( $_GET['download_id'] ) ? sanitize_text_field( wp_unslash( $_GET['download_id'] ) ) : '';
if ( ! empty( $download_id ) ) {
    $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE member_id = %s", $download_id ) );
    if ( ! $member ) {
        wp_die( 'Member not found.' );
    }

    $token = isset( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : '';
    $is_valid_email_recipient = false;
    if ( ! empty( $token ) && $member && ! empty( $member->email ) ) {
        $expected_token = wp_hash( $member->member_id . '|' . $member->email, 'secure' );
        if ( hash_equals( $expected_token, $token ) ) {
            $is_valid_email_recipient = true;
        }
    }

    // Must be admin or have a valid email token
    if ( ! current_user_can( 'manage_options' ) && ! $is_valid_email_recipient ) {
        wp_die( 'Unauthorized Access. Only administrators or the verified email recipient with the valid security link can download this ID card.' );
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
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <title>ID Card - <?php echo esc_html( $member->member_id ); ?></title>
    <!-- CSS for ID Card Print -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap');
        
        body {
            background: #e0e4e8;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 30px 20px;
            box-sizing: border-box;
            gap: 25px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .print-cards-container {
            display: flex;
            flex-direction: row;
            gap: 25px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
            max-width: 1200px;
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
            --id-primary: #052054;
            --id-accent: #E1A92A;
            flex-shrink: 0;
        }

        .id-card-wrapper a,
        .id-card-wrapper a:hover,
        .id-card-wrapper a:visited,
        .id-card-wrapper a:active {
            color: inherit !important;
            text-decoration: none !important;
            border-bottom: none !important;
            pointer-events: none !important;
            cursor: default !important;
        }

        .id-card-wrapper.theme-staff {
            --id-primary: #052054;
        }

        .id-card-wrapper.theme-volunteer {
            --id-primary: #2C3591;
        }

        .id-card-wrapper.theme-director {
            --id-primary: #5c0612;
        }

        .id-card-wrapper.theme-member {
            --id-primary: #2e1154;
        }

        .id-card-wrapper.theme-security {
            --id-primary: #1e293b;
        }

        @media print {
            body { 
                background: #ffffff; 
                margin: 0; 
                padding: 0; 
                display: block;
                min-height: auto;
            }
            .print-cards-container {
                display: block;
                width: auto;
                max-width: none;
            }
            .id-card-wrapper { 
                box-shadow: none; 
                border: 0.5px solid #cbd5e0; 
                width: 8.56cm;
                height: 5.40cm;
                page-break-after: always;
                margin: 0 auto;
            }
            .id-card-wrapper:last-child {
                page-break-after: avoid;
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
            height: 34px;
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
            white-space: nowrap;
        }
        
        .id-org-motto {
            margin: 1.5px 0 0 0;
            font-size: 5.5px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: #E1A92A;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
        }
        
        .id-org-reg {
            margin: 1.5px 0 0 0;
            font-size: 5.2px;
            font-weight: 600;
            letter-spacing: 0.3px;
            color: #cbd5e0;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
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
            padding-top: 3px;
            box-sizing: border-box;
            flex-shrink: 0;
        }
        
        .id-photo-container {
            width: 74px;
            height: 95px;
            border: 1px solid var(--id-primary);
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
            position: relative;
        }
        
        .id-photo-container img.id-photo-blur {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(4px) brightness(0.95);
            opacity: 0.65;
            z-index: 1;
            display: block;
        }
        
        .id-photo-container img.id-photo-main {
            position: relative;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            z-index: 2;
            display: block;
        }
        
        .id-badge-info-navy {
            width: 100%;
            background: var(--id-primary);
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
            width: 90px;
            background: #ffffff;
            padding: 1px;
            border-radius: 2px;
            margin-top: 3px;
            gap: 1.8px;
            box-sizing: border-box;
        }
        
        .barcode-bar {
            background: #1a202c;
            height: 100%;
        }
        
        .barcode-bar.thin { width: 1.2px; }
        .barcode-bar.med { width: 2.2px; }
        .barcode-bar.thick { width: 3.5px; }
        
        /* Right Column layout */
        .id-col-right {
            flex: 1;
            padding: 6px 10px 2.5px 10px;
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
            font-size: 13px;
            font-weight: 800;
            color: var(--id-primary);
            text-transform: uppercase;
            letter-spacing: 0.1px;
            line-height: 1.1;
        }
        
        .profile-designation {
            font-size: 7px;
            font-weight: 700;
            color: #E1A92A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
            line-height: 1;
        }
        
        .title-divider-line {
            width: 100%;
            height: 0.75px;
            background: #cbd5e0;
            margin-top: 3px;
        }
        
        /* Detail Rows using Circle Icons */
        .profile-meta-list {
            display: flex;
            flex-direction: column;
            gap: 2.5px;
            flex: 1;
            margin-top: 3px;
        }
        
        .profile-meta-list.has-alt-mobile {
            gap: 1.8px;
            margin-top: 2px;
        }
        
        .meta-item-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .meta-icon-circle {
            width: 12px;
            height: 12px;
            background: var(--id-primary);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .meta-svg-icon {
            width: 6px;
            height: 6px;
            color: #ffffff;
            stroke-width: 2.8;
        }
        
        .meta-content-wrapper {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }
        
        .meta-row-label {
            font-size: 3.8px;
            font-weight: 800;
            color: var(--id-primary);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1.5px;
        }
        
        .meta-row-val {
            font-size: 7px;
            font-weight: 600;
            color: #4a5568;
            word-break: break-all;
        }

        .meta-row-val a {
            color: inherit !important;
            text-decoration: none !important;
            pointer-events: none !important;
        }
        
        /* Footer Area of the Right Column */
        .right-column-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            width: 100%;
            margin-top: auto;
            border-top: 0.75px solid #cbd5e0;
            padding-top: 1px;
            box-sizing: border-box;
        }
        
        .signature-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 92px;
            line-height: 1;
        }
        
        .signature-image-wrapper {
            height: 31px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        
        .signature-image-wrapper img {
            height: 31px;
            width: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
        }
        
        .signature-underline {
            width: 100%;
            border-bottom: 0.5px dashed var(--id-primary);
            margin-top: 1px;
            margin-bottom: 1px;
        }
        
        .signature-title {
            font-size: 4px;
            font-weight: 800;
            color: var(--id-primary);
            letter-spacing: 0.2px;
            text-transform: uppercase;
            text-align: center;
        }
        
        .vertical-dash-divider {
            height: 19px;
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
            color: var(--id-primary);
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
            border: 0.75px solid var(--id-primary);
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
            color: var(--id-primary);
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
            background: var(--id-primary);
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
            background: linear-gradient(135deg, #052054, #051a44);
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
        }        /* NFC / RFID CONTACTLESS STYLING */
        .nfc-badge {
            display: none !important;
            align-items: center;
            background: rgba(225, 169, 42, 0.08);
            border: 0.75px solid rgba(225, 169, 42, 0.45);
            border-radius: 4px;
            padding: 2.5px 5.5px;
            gap: 4px;
            margin-right: 6px;
        }

        .nfc-enabled .nfc-badge {
            display: flex !important;
        }

        .nfc-logo-svg {
            width: 10px;
            height: 10px;
            color: #E1A92A;
            display: block;
        }

        .nfc-text {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 0.95;
        }

        .nfc-text span {
            font-size: 3.5px;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }

        .nfc-text strong {
            font-size: 4.5px;
            color: #E1A92A;
            font-weight: 800;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }

        @media print {
            /* Keep NFC/RFID design visible on print output! */
            .nfc-badge {
                display: none;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .nfc-enabled .nfc-badge {
                display: flex !important;
            }
        }
    </style>
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
</head>
<body class="nfc-enabled">
    <?php
    $mid_upper = strtoupper( $member->member_id );
    $theme_class = 'theme-staff';
    if ( strpos( $mid_upper, 'TKF-VOL' ) !== false ) {
        $theme_class = 'theme-volunteer';
    } elseif ( strpos( $mid_upper, 'TKF-DIR' ) !== false || strpos( $mid_upper, 'TKF-ADM' ) !== false || strpos( $mid_upper, 'TKF-TRU' ) !== false ) {
        $theme_class = 'theme-director';
    } elseif ( strpos( $mid_upper, 'TKF-MEM' ) !== false ) {
        $theme_class = 'theme-member';
    } elseif ( strpos( $mid_upper, 'TKF-SEC' ) !== false ) {
        $theme_class = 'theme-security';
    }
    ?>
    
    <!-- Unified Controls Panel (Interactive Toggle & Print Action Actions) -->
    <div class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
        <button class="print-btn" onclick="window.print()" style="position: static !important; background: linear-gradient(135deg, #052054, #051a44); color: #E1A92A; border: 1px solid #E1A92A99; padding: 12px 24px; border-radius: 6px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.25); text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2.5;" viewBox="0 0 24 24">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <polyline points="6 14 2 14 2 22 22 22 22 14 18 14"></polyline>
                <rect x="6" y="10" width="12" height="8"></rect>
            </svg>
            Print ID Card
        </button>
        <div style="display: none !important;">
            <input type="checkbox" id="nfc-mode-toggle" checked style="width: 15px; height: 15px; accent-color: #052054; cursor: pointer;">
            <label for="nfc-mode-toggle" style="color: #052054; font-family: 'Space Grotesk', sans-serif; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; -webkit-user-select: none; user-select: none; white-space: nowrap;">
                Print with NFC / RFID Logo
            </label>
        </div>
    </div>

    <script style="display:none;">
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('nfc-enabled');
        localStorage.setItem('tkf_nfc_layout', 'true');
        var toggle = document.getElementById('nfc-mode-toggle');
        if (toggle) {
            toggle.checked = true;
        }
    });
    </script>

    <div class="print-cards-container">
        <!-- FRONT SIDE OF CARD -->
        <div class="id-card-wrapper <?php echo esc_attr( $theme_class ); ?>">
        <!-- SVG background curves inside the card wrapper for wavy header effect -->
        <svg class="id-header-curve-svg" viewBox="0 0 324 204" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="position: absolute; top:0; left:0; width:324px; height:204px; z-index:1; pointer-events:none;">
            <!-- Gold ribbon wavy background separator -->
            <path d="M 0,0 L 324,0 L 324,45 C 270,51 210,31 150,43 C 90,55 50,42 0,46 Z" fill="#E1A92A" />
            <!-- Deep Navy background curve header -->
            <path d="M 0,0 L 324,0 L 324,43 C 270,48 210,28 150,40 C 90,52 50,40 0,43 Z" fill="var(--id-primary)" />
        </svg>

        <!-- Watermark Medallion overlay behind info list -->
        <div class="id-watermark-overlay" style="background-image: url('<?php echo esc_url($logo_url); ?>');"></div>

        <!-- Top Header Part -->
        <div class="id-header">
            <div class="id-header-left">
                <img src="<?php echo esc_url($logo_url); ?>" class="id-header-logo" alt="Logo">
                <div class="id-header-text">
                    <h3 class="id-org-title">TATKHALSA</h3>
                    <p class="id-org-motto">FOUNDATION</p>
                    <p class="id-org-reg">CIN: U88900PB2023NPL059225</p>
                </div>
            </div>
            <div class="id-header-right">
                <!-- NFC / RFID Contactless Badge -->
                <div class="nfc-badge">
                    <svg class="nfc-logo-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round">
                        <path d="M12 10A2 2 0 0 1 14 12A2 2 0 0 1 12 14" />
                        <path d="M12 6A6 6 0 0 1 18 12A6 6 0 0 1 12 18" />
                        <path d="M12 2A10 10 0 0 1 22 12A10 10 0 0 1 12 22" />
                    </svg>
                    <div class="nfc-text">
                        <span>NFC / RFID</span>
                        <strong>SECURE</strong>
                    </div>
                </div>
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
                        <img src="<?php echo esc_url( $member->photo_url ); ?>" class="id-photo-blur" alt="Member Photo Background">
                        <img src="<?php echo esc_url( $member->photo_url ); ?>" class="id-photo-main" alt="Member Photo">
                    <?php else: ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="Default Logo" style="object-fit: contain; padding: 6px; background:#f4f6f9; position: relative; z-index: 2; width: 100%; height: 100%;">
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
                    <!-- Row 1: CONTACT -->
                    <div class="meta-item-row">
                        <div class="meta-icon-circle">
                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <div class="meta-content-wrapper">
                                <span class="meta-row-label">CONTACT NO</span>
                                <span class="meta-row-val"><?php echo esc_html( $member->mobile ?: 'N/A' ); ?></span>
                            </div>
                            <?php if ( ! empty( $member->alt_mobile ) ) : ?>
                            <div class="meta-content-wrapper">
                                <span class="meta-row-label">ALT CONTACT NO</span>
                                <span class="meta-row-val"><?php echo esc_html( $member->alt_mobile ); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Row 2: EMAIL -->
                    <div class="meta-item-row">
                        <div class="meta-icon-circle">
                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </div>
                        <div class="meta-content-wrapper">
                            <span class="meta-row-label">EMAIL ADDRESS</span>
                            <span class="meta-row-val"><?php echo esc_html( $member->email ?: 'N/A' ); ?></span>
                        </div>
                    </div>
                    
                    <!-- Row 3: BLOOD GROUP -->
                    <div class="meta-item-row">
                        <div class="meta-icon-circle">
                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </div>
                        <div class="meta-content-wrapper">
                            <span class="meta-row-label">BLOOD GROUP</span>
                            <span class="meta-row-val"><?php echo esc_html( $member->blood_group ?: 'N/A' ); ?></span>
                        </div>
                    </div>

                    <!-- Row 4: EXPIRY DATE -->
                    <div class="meta-item-row">
                        <div class="meta-icon-circle">
                            <svg class="meta-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </div>
                        <div class="meta-content-wrapper">
                            <span class="meta-row-label">EXPIRY DATE</span>
                            <span class="meta-row-val"><?php echo esc_html( tkf_format_date( $member->expiry_date, 'N/A' ) ); ?></span>
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
                        <span class="validity-date"><?php echo esc_html( tkf_format_date( $member->issue_date, tkf_format_date( $member->created_at, '16 JUN 2026' ) ) ); ?></span>
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

    <!-- BACK SIDE OF ID CARD -->
    <div class="id-card-wrapper <?php echo esc_attr( $theme_class ); ?> id-card-back">
        <!-- SVG background curves inside the card wrapper for wavy header effect -->
        <svg class="id-header-curve-svg" viewBox="0 0 324 204" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="position: absolute; top:0; left:0; width:324px; height:204px; z-index:1; pointer-events:none;">
            <!-- Gold ribbon wavy background separator -->
            <path d="M 0,0 L 324,0 L 324,45 C 270,51 210,31 150,43 C 90,55 50,42 0,46 Z" fill="#E1A92A" />
            <!-- Deep Navy background curve header -->
            <path d="M 0,0 L 324,0 L 324,43 C 270,48 210,28 150,40 C 90,52 50,40 0,43 Z" fill="var(--id-primary)" />
        </svg>

        <!-- Watermark Medallion overlay behind info list -->
        <div class="id-watermark-overlay" style="background-image: url('<?php echo esc_url($logo_url); ?>');"></div>

        <!-- Top Header Part -->
        <div class="id-header">
            <div class="id-header-left">
                <img src="<?php echo esc_url($logo_url); ?>" class="id-header-logo" alt="Logo">
                <div class="id-header-text">
                    <h3 class="id-org-title">TATKHALSA</h3>
                    <p class="id-org-motto">FOUNDATION</p>
                    <p class="id-org-reg">CIN: U88900PB2023NPL059225</p>
                </div>
            </div>
            <div class="id-header-right">
                <!-- NFC / RFID Contactless Badge -->
                <div class="nfc-badge" style="margin-right: 6px;">
                    <svg class="nfc-logo-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round">
                        <path d="M12 10A2 2 0 0 1 14 12A2 2 0 0 1 12 14" />
                        <path d="M12 6A6 6 0 0 1 18 12A6 6 0 0 1 12 18" />
                        <path d="M12 2A10 10 0 0 1 22 12A10 10 0 0 1 12 22" />
                    </svg>
                    <div class="nfc-text">
                        <span>NFC / RFID</span>
                        <strong>SECURE</strong>
                    </div>
                </div>
                <div style="font-size: 5px; font-weight: 800; color: #E1A92A; text-align: right; letter-spacing: 0.5px; line-height: 1.1;">
                    OFFICIAL DATA &<br>CONTRIBUTIONS
                </div>
            </div>
        </div>

        <!-- Back Side Content Area -->
        <div class="id-content-main" style="padding: 13px 12px 14px 12px; display: flex; flex-direction: row; gap: 10px; z-index: 10; justify-content: space-between; align-items: center; box-sizing: border-box; height: 137px; width: 100%;">
            <!-- Left Column: Bank Account Details -->
            <div class="back-details-col" style="flex: 1; display: flex; flex-direction: column; gap: 3px; text-align: left;">
                <span style="font-size: 5.5px; font-weight: 800; color: var(--id-primary); letter-spacing: 0.3px; text-transform: uppercase;">DONATIONS & SUPPORT</span>
                
                <div style="width: 25px; height: 1px; background: #E1A92A; margin: 1px 0 2px 0;"></div>
                
                <!-- Key-Value Rows for Bank Info in an elegant card-like container overlay -->
                <div style="display: flex; flex-direction: column; justify-content: space-between; background: rgba(5, 32, 84, 0.03); border: 0.5px solid rgba(5, 32, 84, 0.1); border-radius: 6px; padding: 6px 8px; box-sizing: border-box; width: 210px; height: 110px;">
                    <!-- Beneficiary Details -->
                    <div style="display: flex; flex-direction: column; gap: 3.5px; line-height: 1.3;">
                        <span style="font-size: 4px; font-weight: 800; color: #718096; text-transform: uppercase; letter-spacing: 0.2px;">BENEFICIARY NAME</span>
                        <span style="font-size: 7.5px; font-weight: 700; color: var(--id-primary); letter-spacing: 0.1px;">TATKHALSA FOUNDATION</span>
                    </div>

                    <div style="height: 0.5px; background: rgba(5, 32, 84, 0.06); width: 100%;"></div>

                    <!-- Account Number -->
                    <div style="display: flex; flex-direction: column; gap: 3.5px; line-height: 1.3;">
                        <span style="font-size: 4px; font-weight: 800; color: #718096; text-transform: uppercase; letter-spacing: 0.2px;">ACCOUNT NUMBER</span>
                        <span style="font-size: 10px; font-weight: 700; color: #1a202c; font-family: 'Courier New', monospace; letter-spacing: 0.8px;">9250 1005 7912 966</span>
                    </div>

                    <div style="height: 0.5px; background: rgba(5, 32, 84, 0.06); width: 100%;"></div>

                    <!-- IFSC Code & Bank Name Row -->
                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <div style="display: flex; flex-direction: column; gap: 3.5px; line-height: 1.3;">
                            <span style="font-size: 4px; font-weight: 800; color: #718096; text-transform: uppercase; letter-spacing: 0.2px;">IFSC CODE</span>
                            <span style="font-size: 7.5px; font-weight: 700; color: #1a202c; font-family: 'Courier New', monospace;">UTIB0004354</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 3.5px; line-height: 1.3; align-items: flex-end; text-align: right;">
                            <span style="font-size: 4px; font-weight: 800; color: #718096; text-transform: uppercase; letter-spacing: 0.2px; padding-right: 4px;">BANK NAME</span>
                            <div style="display: flex; align-items: center; gap: 3px; margin-top: 0.5px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/1/1a/Axis_Bank_logo.svg" alt="Axis Bank" style="height: 7px; display: block; object-fit: contain;">
                            </div>
                        </div>
                    </div>

                    <div style="height: 0.5px; background: rgba(5, 32, 84, 0.06); width: 100%;"></div>

                    <!-- BHIM UPI ID Row -->
                    <div style="display: flex; flex-direction: column; gap: 3.5px; line-height: 1.3;">
                        <span style="font-size: 4px; font-weight: 800; color: #718096; text-transform: uppercase; letter-spacing: 0.2px;">BHIM UPI ADDRESS</span>
                        <div style="display: inline-block; background: rgba(225, 169, 42, 0.1); border: 0.5px solid rgba(225, 169, 42, 0.4); padding: 1.5px 4px; border-radius: 3px; width: fit-content;">
                            <span style="font-size: 6.8px; font-weight: 700; color: #2d3748; font-family: 'Courier New', monospace; letter-spacing: -0.1px; display: block;">mab.037215043540097@axisbank</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Contribution QR Code -->
            <div class="back-qr-col" style="width: 65px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; border-left: 0.5px solid rgba(52, 58, 64, 0.15); padding-left: 10px; box-sizing: border-box; height: 110px; margin-top: 11px;">
                <?php 
                $upi_url = 'upi://pay?pa=mab.037215043540097@axisbank&pn=Tatkhalsa%20Foundation&cu=INR';
                $pay_qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode( $upi_url ) . '&margin=0';
                ?>
                <div style="padding: 2.5px; border: 0.75px solid var(--id-primary); border-radius: 4px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); box-sizing: border-box; max-width: 50px; max-height: 50px; display: flex; align-items: center; justify-content: center;">
                    <img src="<?php echo esc_url($pay_qr_url); ?>" alt="Contribution QR" style="width: 42px; height: 42px; display: block; object-fit: contain;">
                </div>
                <div style="font-size: 3.5px; font-weight: 900; background: #E1A92A; color: var(--id-primary); padding: 1.5px 4px; border-radius: 2.5px; letter-spacing: 0.2px; text-transform: uppercase; text-align: center; box-shadow: 0 1px 2px rgba(225, 169, 42, 0.2); white-space: nowrap; margin-top: 2px;">
                    SCAN TO CONTRIBUTE
                </div>
            </div>
        </div>

        <!-- Card Bottom Terms / Policy strip -->
        <div class="id-bottom-navy-banner" style="font-size: 3.3px; height: 15px; padding: 0 10px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; line-height: 1.2; border-top: 1.5px solid #E1A92A; box-sizing: border-box;">
            <span style="letter-spacing: 0.15px; font-weight: 500; color: #fff; text-transform: uppercase; font-size: 3.3px;">Registered Section 8 Non-Profit Organization. All donations are tax exempt under Sec 80G.</span>
            <span style="letter-spacing: 0.1px; font-weight: 400; color: #cbd5e0; font-size: 3.1px;">If found, please return to office or contact: info@tatkhalsa.in.</span>
        </div>
    </div>
</div>

<div class="no-print" style="width: 100%; max-width: 673px; margin: 30px auto; padding: 0 10px; box-sizing: border-box;">
    <?php tkf_render_mobile_wallet_hub( $member, $token ); ?>
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
    echo '<meta name="format-detection" content="telephone=no, date=no, address=no, email=no">';

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
            transition: max-width 0.3s ease;
        }
        @media (min-width: 768px) {
            .verify-card.verify-card-active {
                max-width: 1000px;
            }
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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 24px 0;
            gap: 20px;
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
            --id-primary: #052054;
            --id-accent: #E1A92A;
            flex-shrink: 0;
        }

        .id-card-wrapper a,
        .id-card-wrapper a:hover,
        .id-card-wrapper a:visited,
        .id-card-wrapper a:active {
            color: inherit !important;
            text-decoration: none !important;
            border-bottom: none !important;
            pointer-events: none !important;
            cursor: default !important;
        }

        .id-card-wrapper.theme-staff {
            --id-primary: #052054;
        }

        .id-card-wrapper.theme-volunteer {
            --id-primary: #2C3591;
        }

        .id-card-wrapper.theme-director {
            --id-primary: #5c0612;
        }

        .id-card-wrapper.theme-member {
            --id-primary: #2e1154;
        }

        .id-card-wrapper.theme-security {
            --id-primary: #1e293b;
        }

        @media (max-width: 380px) {
            .id-card-wrapper {
                transform: scale(0.85);
            }
            .card-viewport-scaler {
                padding: 10px 0;
                gap: 10px;
            }
        }
        
        @media (min-width: 480px) and (max-width: 767px) {
            .id-card-wrapper {
                transform: scale(1.15);
                box-shadow: 0 15px 45px rgba(10, 50, 125, 0.16);
            }
            .card-viewport-scaler {
                padding: 40px 0;
                gap: 55px;
            }
        }

        @media (min-width: 768px) {
            .card-viewport-scaler {
                flex-direction: row;
                padding: 55px 0;
                gap: 140px;
            }
            .id-card-wrapper {
                transform: scale(1.35);
                box-shadow: 0 15px 45px rgba(10, 50, 125, 0.16);
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
            height: 34px;
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
            white-space: nowrap;
        }
        
        .id-org-motto {
            margin: 1.5px 0 0 0;
            font-size: 5.5px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: #E1A92A;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
        }
        
        .id-org-reg {
            margin: 1.5px 0 0 0;
            font-size: 5.2px;
            font-weight: 600;
            letter-spacing: 0.3px;
            color: #cbd5e0;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
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
            padding-top: 3px;
            box-sizing: border-box;
            flex-shrink: 0;
        }
        
        .id-photo-container {
            width: 74px;
            height: 95px;
            border: 1px solid var(--id-primary);
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 3px 6px rgba(0,0,0,0.08);
            position: relative;
        }
        
        .id-photo-container img.id-photo-blur {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(4px) brightness(0.95);
            opacity: 0.65;
            z-index: 1;
            display: block;
        }
        
        .id-photo-container img.id-photo-main {
            position: relative;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            z-index: 2;
            display: block;
        }
        
        .id-badge-info-navy {
            width: 100%;
            background: var(--id-primary);
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
            width: 90px;
            background: #ffffff;
            padding: 1px;
            border-radius: 2px;
            margin-top: 3px;
            gap: 1.8px;
            box-sizing: border-box;
        }
        
        .barcode-bar {
            background: #1a202c;
            height: 100%;
        }
        
        .barcode-bar.thin { width: 1.2px; }
        .barcode-bar.med { width: 2.2px; }
        .barcode-bar.thick { width: 3.5px; }
        
        .id-col-right {
            flex: 1;
            padding: 6px 10px 2.5px 10px;
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
            font-size: 13px;
            font-weight: 800;
            color: var(--id-primary);
            text-transform: uppercase;
            letter-spacing: 0.1px;
            line-height: 1.1;
        }
        
        .profile-designation {
            font-size: 7px;
            font-weight: 700;
            color: #E1A92A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
            line-height: 1;
        }
        
        .title-divider-line {
            width: 100%;
            height: 0.75px;
            background: #cbd5e0;
            margin-top: 3px;
        }
        
        .profile-meta-list {
            display: flex;
            flex-direction: column;
            gap: 2.5px;
            flex: 1;
            margin-top: 3px;
        }
        
        .profile-meta-list.has-alt-mobile {
            gap: 1.8px;
            margin-top: 2px;
        }
        
        .meta-item-row {
            display: flex;
            align-items: center;
            gap: 6px;
            text-align: left;
        }
        
        .meta-icon-circle {
            width: 12px;
            height: 12px;
            background: var(--id-primary);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .meta-svg-icon {
            width: 6px;
            height: 6px;
            color: #ffffff;
            stroke-width: 2.8;
        }
        
        .meta-content-wrapper {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }
        
        .meta-row-label {
            font-size: 3.8px;
            font-weight: 800;
            color: var(--id-primary);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1.5px;
        }
        
        .meta-row-val {
            font-size: 7px;
            font-weight: 600;
            color: #4a5568;
            word-break: break-all;
        }

        .meta-row-val a {
            color: inherit !important;
            text-decoration: none !important;
            pointer-events: none !important;
        }
        
        .right-column-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            width: 100%;
            margin-top: auto;
            border-top: 0.75px solid #cbd5e0;
            padding-top: 1px;
            box-sizing: border-box;
        }
        
        .signature-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 92px;
            line-height: 1;
        }
        
        .signature-image-wrapper {
            height: 31px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        
        .signature-image-wrapper img {
            height: 31px;
            width: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
        }
        
        .signature-underline {
            width: 100%;
            border-bottom: 0.5px dashed var(--id-primary);
            margin-top: 1px;
            margin-bottom: 1px;
        }
        
        .signature-title {
            font-size: 4px;
            font-weight: 800;
            color: var(--id-primary);
            letter-spacing: 0.2px;
            text-transform: uppercase;
            text-align: center;
        }
        
        .vertical-dash-divider {
            height: 19px;
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
            color: var(--id-primary);
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
            border: 0.75px solid var(--id-primary);
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
            color: var(--id-primary);
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
            background: var(--id-primary);
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
            color: #052054;
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
                    ✓ SECURE IDENTITY VERIFIED & ACTIVE
                </div>

                <!-- Structured meta list under card for quick reading -->
                <div class="post-card-verification-details" style="padding: 24px 24px 20px 24px; text-align: left;">

                    <!-- Simplified Visual Block showing ONLY Photo and Core Context, NO full card layout, signature or QR for safety -->
                    <div style="display: flex; flex-direction: row; gap: 24px; align-items: center; border-bottom: 1px solid #edf2f7; padding-bottom: 24px; margin-bottom: 24px; flex-wrap: wrap;">

                        <!-- Secure Avatar Border -->
                        <div style="width: 120px; height: 154px; border: 3px solid #E1A92A; border-radius: 10px; overflow: hidden; background: #ffffff; box-shadow: 0 4px 15px rgba(5, 32, 84, 0.08); flex-shrink: 0; position: relative;">
                            <?php if ( ! empty( $member->photo_url ) ) : ?>
                                <img src="<?php echo esc_url( $member->photo_url ); ?>" alt="Member Photo Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; filter: blur(4px) brightness(0.95); opacity: 0.65; z-index: 1; display: block;">
                                <img src="<?php echo esc_url( $member->photo_url ); ?>" alt="Member Photo" style="position: relative; width: 100%; height: 100%; object-fit: cover; object-position: center top; z-index: 2; display: block;">
                            <?php else: ?>
                                <img src="<?php echo esc_url($logo_url); ?>" alt="Default Logo" style="width: 100%; height: 100%; object-fit: contain; padding: 16px; background:#f4f6f9; box-sizing: border-box; display: block; position: relative; z-index: 2;">
                            <?php endif; ?>

                            <!-- Floating Active Indicator -->
                            <div style="position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); background: #28a745; color: #ffffff; font-size: 8px; font-weight: 800; padding: 3px 8px; border-radius: 99px; letter-spacing: 0.5px; text-transform: uppercase; white-space: nowrap; box-shadow: 0 2px 5px rgba(40,167,69,0.3);">
                                Active
                            </div>
                        </div>

                        <!-- Core Identity Context -->
                        <div style="flex: 1; min-width: 200px;">
                            <span style="font-size: 10px; font-weight: 800; color: #E1A92A; letter-spacing: 0.8px; text-transform: uppercase; display: block; margin-bottom: 4px;">TATKHALSA FOUNDATION</span>
                            <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 800; color: #052054; margin: 0; text-transform: uppercase; letter-spacing: -0.3px; line-height: 1.15;"><?php echo esc_html( $member->full_name ); ?></h2>
                            <div style="display: inline-block; background: rgba(5,32,84,0.06); border: 1px solid rgba(5,32,84,0.1); border-radius: 6px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; color: #052054; margin-top: 8px; text-transform: uppercase; letter-spacing: 0.3px;">
                                <?php echo esc_html( $member->designation ); ?>
                            </div>

                            <div style="margin-top: 14px; font-size: 11.5px; color: #4a5568; line-height: 1.5; font-weight: 500;">
                                Secure Personnel ID: <strong style="font-family: monospace; color: #1a202c; font-size: 12.5px; font-weight: 700;"><?php echo esc_html( $member->member_id ); ?></strong>
                            </div>
                        </div>
                    </div>

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
                        <div class="post-card-row">
                            <span class="post-card-label">Card Issue Date</span>
                            <span class="post-card-val"><?php echo esc_html( tkf_format_date( $member->issue_date, tkf_format_date( $member->created_at, '16 JUN 2026' ) ) ); ?></span>
                        </div>
                        <?php if ( ! empty( $member->blood_group ) ) : ?>
                            <div class="post-card-row">
                                <span class="post-card-label">Medical Blood Group</span>
                                <span class="post-card-val"><?php echo esc_html( $member->blood_group ); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="post-card-row">
                            <span class="post-card-label">Card Valid Till</span>
                            <span class="post-card-val"><?php echo esc_html( tkf_format_date( $member->expiry_date, 'PERMANENT BENEFICIARY / LIFETIME' ) ); ?></span>
                        </div>
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
                        <?php if ( ! empty( $member->alt_mobile ) ) : ?>
                            <div class="post-card-row">
                                <span class="post-card-label">Alt Contact No</span>
                                <span class="post-card-val"><?php echo esc_html( '******' . substr( $member->alt_mobile, -4 ) ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $member->gov_id ) ) : ?>
                            <div class="post-card-row">
                                <span class="post-card-label">Aadhaar Card Number</span>
                                <span class="post-card-val" style="letter-spacing: 0.5px; font-family: monospace;">
                                    <?php 
                                    $raw_gov = trim( $member->gov_id );
                                    $clean_gov = preg_replace('/[^A-Za-z0-9]/', '', $raw_gov);
                                    if ( strlen( $clean_gov ) > 4 ) {
                                        $last_digits = substr( $clean_gov, -4 );
                                        echo esc_html( '•••• •••• ' . $last_digits );
                                    } else {
                                        echo esc_html( '•••• ' . $clean_gov );
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Registered NGO Details Block -->
                    <div class="ngo-details-block" style="margin-top: 25px; padding-top: 20px; border-top: 1px dashed #e2e8f0;">
                        <h4 class="post-card-title" style="margin-bottom: 12px; color: #052054; border-left-color: #052054;">Registered NGO Details</h4>
                        <div class="post-card-grid">
                            <div class="post-card-row">
                                <span class="post-card-label">Corporate Identification No (CIN)</span>
                                <span class="post-card-val" style="font-family: monospace; font-size: 11.5px; color: #052054;">U88900PB2023NPL059225</span>
                            </div>
                            <div class="post-card-row">
                                <span class="post-card-label">Official Email</span>
                                <span class="post-card-val"><a href="mailto:info@tatkhalsa.in" style="color: #007bff; text-decoration: none;">info@tatkhalsa.in</a></span>
                            </div>
                            <div class="post-card-row" style="grid-column: 1 / -1;">
                                <span class="post-card-label">Registered Address</span>
                                <span class="post-card-val" style="font-weight: 500; color: #4a5568;">GF 37, Bazidpur, SBS Nagar, Punjab - 144518</span>
                            </div>
                        </div>
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
        // Render the Official Public Verification Portal!
        $home_url = esc_url( home_url('/verify/') );
        ?>
        <div class="verify-page-wrapper" style="min-height: 85vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; font-family: 'Plus Jakarta Sans', sans-serif; background: radial-gradient(circle at 10% 20%, rgba(244, 246, 249, 1) 0%, rgba(235, 239, 244, 1) 100%);">
            
            <!-- Background Watermark medallion -->
            <img src="<?php echo esc_url($logo_url); ?>" class="verify-watermark" alt="" style="position: absolute; width: 450px; opacity: 0.04; pointer-events: none; transform: rotate(-15deg); z-index: 0;">

            <div class="verify-card" style="background: #ffffff; border-radius: 16px; box-shadow: 0 15px 50px rgba(10, 50, 125, 0.08); max-width: 500px; width: 100%; text-align: center; overflow: hidden; position: relative; z-index: 1; border-top: 5px solid #052054; box-sizing: border-box; margin-bottom: 30px;">
                
                <div style="background: #052054; color: #ffffff; padding: 25px 20px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Logo" style="height: 48px; width: auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));">
                    <h2 style="margin: 5px 0 0 0; font-family: 'Space Grotesk', sans-serif; font-size: 19px; font-weight: 700; letter-spacing: 1px; color: #ffffff;">TATKHALSA FOUNDATION</h2>
                    <span style="font-size: 11px; font-weight: 600; color: #E1A92A; letter-spacing: 1px; text-transform: uppercase;">Personnel Verification Portal</span>
                </div>

                <div style="padding: 30px 25px;">
                    <p style="color: #4a5568; font-size: 14px; line-height: 1.6; margin: 0 0 25px 0; font-weight: 500;">
                        Scan the secure QR Code printed on the back of any photo ID card, or enter the secure Member ID below to perform an instant credentials integrity audit.
                    </p>

                    <form method="GET" action="<?php echo $home_url; ?>" style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="text-align: left;">
                            <label style="font-size: 12px; font-weight: 700; color: #052054; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Secure Member ID</label>
                            <input type="text" name="member_id" placeholder="e.g. TKF-VOL-2601" required style="width: 100%; padding: 12px 16px; border: 1.5px solid #cbd5e0; border-radius: 8px; font-size: 15px; font-weight: 600; color: #1a202c; box-sizing: border-box; font-family: monospace; transition: border-color 0.2s;" onfocus="this.style.borderColor='#052054';" onblur="this.style.borderColor='#cbd5e0';">
                        </div>
                        <button type="submit" style="background: #052054; color: #ffffff; border: none; border-radius: 8px; padding: 14px; font-size: 14px; font-weight: 800; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px; transition: background 0.2s; box-shadow: 0 4px 12px rgba(5, 32, 84, 0.2);" onmouseover="this.style.background='#031230';" onmouseout="this.style.background='#052054';">
                            Verify Personnel Identity
                        </button>
                    </form>
                </div>
            </div>

            <!-- NGO Details block beneath the search card for public visibility -->
            <div class="verify-card" style="background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(10, 50, 125, 0.04); max-width: 500px; width: 100%; overflow: hidden; position: relative; z-index: 1; box-sizing: border-box; padding: 25px;">
                <h4 style="margin: 0 0 15px 0; font-size: 11.5px; font-weight: 800; color: #052054; letter-spacing: 0.5px; text-transform: uppercase; border-left: 3px solid #E1A92A; padding-left: 8px;">Registered NGO Details</h4>
                
                <div style="display: flex; flex-direction: column; gap: 12px; text-align: left; font-size: 13px; line-height: 1.5;">
                    <div>
                        <span style="font-size: 11px; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: 0.3px; display: block;">Corporate Identification No (CIN)</span>
                        <span style="font-family: monospace; font-size: 12.5px; color: #052054; font-weight: 700;">U88900PB2023NPL059225</span>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: 0.3px; display: block;">Official Helpline / Email</span>
                        <span><a href="mailto:info@tatkhalsa.in" style="color: #007bff; text-decoration: none; font-weight: 600;">info@tatkhalsa.in</a></span>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: 0.3px; display: block;">Registered Office Address</span>
                        <span style="color: #4a5568; font-weight: 500;">GF 37, Bazidpur, SBS Nagar, Punjab - 144518</span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        get_footer();
        exit;
    }

    // Fetch all members to populate table directory and support dynamic sorting
    $sort_by = isset( $_GET['sort_by'] ) ? sanitize_text_field( wp_unslash( $_GET['sort_by'] ) ) : 'id_asc';
    
    $order_clause = "id ASC"; // Default: Sort data entries number wise (ID: Low to High)
    if ( $sort_by === 'id_desc' ) {
        $order_clause = "id DESC";
    } elseif ( $sort_by === 'member_id_asc' ) {
        $order_clause = "member_id ASC";
    } elseif ( $sort_by === 'member_id_desc' ) {
        $order_clause = "member_id DESC";
    } elseif ( $sort_by === 'created_at_desc' ) {
        $order_clause = "created_at DESC";
    } elseif ( $sort_by === 'created_at_asc' ) {
        $order_clause = "created_at ASC";
    } elseif ( $sort_by === 'name_asc' ) {
        $order_clause = "full_name ASC";
    } elseif ( $sort_by === 'name_desc' ) {
        $order_clause = "full_name DESC";
    }
    
    $members = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY $order_clause" );

    // Fetch member to edit if edit_id is set
    $edit_id = isset( $_GET['edit_id'] ) ? intval( $_GET['edit_id'] ) : 0;
    $edit_member = null;
    if ( $edit_id ) {
        $edit_member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $edit_id ) );
    }
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
            color: #052054;
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
            border-color: #052054;
        }
        .admin-btn {
            background: #052054;
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
            background: #031333;
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
            color: #052054;
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

        <!-- Administrative Quick-Access Sub-Navigation -->
        <div style="display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
            <a href="<?php echo esc_url( home_url('/blood-verify/') ); ?>" style="background: #052054; color: #fff; padding: 12px 24px; border-radius: 6px; font-weight: bold; text-decoration: none; box-shadow: 0 4px 15px rgba(5,32,84,0.2); display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <span>⚖️</span> Audit Blood On Call Registers
            </a>
            <a href="<?php echo esc_url( home_url('/blood-on-call/?admin=true') ); ?>" target="_blank" style="background: linear-gradient(135deg, #ff334b 0%, #ff5d73 100%); color: #fff; padding: 12px 24px; border-radius: 6px; font-weight: bold; text-decoration: none; box-shadow: 0 4px 15px rgba(255,51,75,0.25); display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <span>🩸</span> Manage & Edit Blood On Call Master Data (Donors & Requests)
            </a>
            <a href="<?php echo esc_url( home_url('/volunteer/') ); ?>" style="background: #E1A92A; color: #052054; padding: 12px 24px; border-radius: 6px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; transition: transform 0.2s; box-shadow: 0 4px 15px rgba(225,169,42,0.15);" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <span>🤝</span> View Sevadars / Volunteers
            </a>
        </div>

        <?php if ( ! empty( $message ) ) : ?>
            <div class="admin-notice notice-<?php echo esc_attr( $message_type ); ?>">
                <?php echo esc_html( $message ); ?>
            </div>
        <?php endif; ?>

        <div class="admin-form-container">
            <h3><?php echo $edit_member ? 'Edit Secure Identity: ' . esc_html($edit_member->member_id) : 'Register Authentic Personnel'; ?></h3>
            <!-- Clean input-sanitized HTML Form -->
            <form method="POST" action="">
                <?php wp_nonce_field( 'tkf_verify_admin_action', 'tkf_verify_nonce' ); ?>
                <input type="hidden" name="tkf_verify_action" value="<?php echo $edit_member ? 'edit_member' : 'add_member'; ?>">
                <?php if ( $edit_member ) : ?>
                    <input type="hidden" name="id" value="<?php echo esc_attr( $edit_member->id ); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="member_id">Unique Member ID *</label>
                        <input type="text" id="member_id" name="member_id" required value="<?php echo $edit_member ? esc_attr( $edit_member->member_id ) : ''; ?>" placeholder="e.g. TKF-VOL-2601">
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label for="full_name">Legal Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required value="<?php echo $edit_member ? esc_attr( $edit_member->full_name ) : ''; ?>" placeholder="e.g. Gurpreet Singh">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="designation">Department Designation *</label>
                        <input type="text" id="designation" name="designation" required value="<?php echo $edit_member ? esc_attr( $edit_member->designation ) : ''; ?>" placeholder="e.g. Field Medical Coordinator">
                    </div>
                    <div class="form-group">
                        <label for="photo_url">Secure Portrait URL (Optional)</label>
                        <input type="url" id="photo_url" name="photo_url" value="<?php echo $edit_member ? esc_url( $edit_member->photo_url ) : ''; ?>" placeholder="https://tatkhalsa.in/secure/portrait.jpg">
                        <small style="color: #666; font-size: 0.85em; display: block; margin-top: 5px;">Tip: Upload the photo to your WordPress Media Library (Dashboard > Media > Add New), click on the image, copy the "File URL", and paste it here.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="issue_date">Issue Date</label>
                        <input type="date" id="issue_date" name="issue_date" value="<?php echo $edit_member && !empty($edit_member->issue_date) && $edit_member->issue_date !== '0000-00-00' ? esc_attr( $edit_member->issue_date ) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date</label>
                        <input type="date" id="expiry_date" name="expiry_date" value="<?php echo $edit_member && !empty($edit_member->expiry_date) && $edit_member->expiry_date !== '0000-00-00' ? esc_attr( $edit_member->expiry_date ) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gov_id">Government ID Number</label>
                        <input type="text" id="gov_id" name="gov_id" value="<?php echo $edit_member ? esc_attr( $edit_member->gov_id ) : ''; ?>" placeholder="e.g. Aadhaar or PAN">
                    </div>
                    <div class="form-group">
                        <label for="blood_group">Blood Group</label>
                        <select id="blood_group" name="blood_group" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; cursor: pointer; background: #fff; height: 48px; outline: none; transition: border-color 0.2s;">
                            <option value="" <?php selected( $edit_member ? $edit_member->blood_group : '', '' ); ?>>Select Blood Group</option>
                            <option value="A+" <?php selected( $edit_member ? $edit_member->blood_group : '', 'A+' ); ?>>A+</option>
                            <option value="A-" <?php selected( $edit_member ? $edit_member->blood_group : '', 'A-' ); ?>>A-</option>
                            <option value="B+" <?php selected( $edit_member ? $edit_member->blood_group : '', 'B+' ); ?>>B+</option>
                            <option value="B-" <?php selected( $edit_member ? $edit_member->blood_group : '', 'B-' ); ?>>B-</option>
                            <option value="O+" <?php selected( $edit_member ? $edit_member->blood_group : '', 'O+' ); ?>>O+</option>
                            <option value="O-" <?php selected( $edit_member ? $edit_member->blood_group : '', 'O-' ); ?>>O-</option>
                            <option value="AB+" <?php selected( $edit_member ? $edit_member->blood_group : '', 'AB+' ); ?>>AB+</option>
                            <option value="AB-" <?php selected( $edit_member ? $edit_member->blood_group : '', 'AB-' ); ?>>AB-</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo $edit_member ? esc_attr( $edit_member->email ) : ''; ?>" placeholder="e.g. info@domain.com">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="mobile">Primary Mobile Number</label>
                        <input type="text" id="mobile" name="mobile" value="<?php echo $edit_member ? esc_attr( $edit_member->mobile ) : ''; ?>" placeholder="e.g. +91 9876543210">
                    </div>
                    <div class="form-group">
                        <label for="alt_mobile">Alternative Contact Number</label>
                        <input type="text" id="alt_mobile" name="alt_mobile" value="<?php echo $edit_member ? esc_attr( $edit_member->alt_mobile ) : ''; ?>" placeholder="e.g. +91 9876543211">
                    </div>
                </div>

                <div class="form-group" style="display: flex; align-items: center;">
                    <button type="submit" class="admin-btn"><?php echo $edit_member ? 'Update Secure Identity' : 'Onboard Secure Identity'; ?></button>
                    <?php if ( $edit_member ) : ?>
                        <a href="<?php echo esc_url( remove_query_arg('edit_id') ); ?>" class="action-btn" style="background: #6c757d; margin-left: 10px; display: inline-flex; align-items: center; justify-content: center; height: 42px; padding: 0 20px; border-radius: 6px; text-decoration: none; font-weight: 700; color: #fff; margin-top: 10px;">Cancel Edit</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 40px; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
            <h3 style="color: #333; margin: 0;">Secured Record Manifest</h3>
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="sort_by" style="font-size: 13px; font-weight: 700; color: #4a5568;">Sort Manifest:</label>
                <select id="sort_by" onchange="window.location.href = this.value;" style="padding: 6px 12px; border-radius: 6px; border: 1.5px solid #cbd5e0; font-size: 13px; font-weight: 600; color: #052054; background-color: #fff; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.05); outline: none;">
                    <option value="<?php echo esc_url( add_query_arg( 'sort_by', 'id_asc' ) ); ?>" <?php selected( $sort_by, 'id_asc' ); ?>>Number Wise (ID: Low to High)</option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort_by', 'id_desc' ) ); ?>" <?php selected( $sort_by, 'id_desc' ); ?>>Number Wise (ID: High to Low)</option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort_by', 'member_id_asc' ) ); ?>" <?php selected( $sort_by, 'member_id_asc' ); ?>>Member ID (A-Z)</option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort_by', 'member_id_desc' ) ); ?>" <?php selected( $sort_by, 'member_id_desc' ); ?>>Member ID (Z-A)</option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort_by', 'name_asc' ) ); ?>" <?php selected( $sort_by, 'name_asc' ); ?>>Name (A-Z)</option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort_by', 'name_desc' ) ); ?>" <?php selected( $sort_by, 'name_desc' ); ?>>Name (Z-A)</option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort_by', 'created_at_desc' ) ); ?>" <?php selected( $sort_by, 'created_at_desc' ); ?>>Registration Date (Newest)</option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort_by', 'created_at_asc' ) ); ?>" <?php selected( $sort_by, 'created_at_asc' ); ?>>Registration Date (Oldest)</option>
                </select>
            </div>
        </div>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th width="15%">Member Ledger</th>
                        <th width="25%">Full Personnel Name</th>
                        <th width="15%">Role Capacity</th>
                        <th width="10%">Blood Group</th>
                        <th width="15%">Security Status</th>
                        <th width="20%">Management Actions</th>
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
                                    <?php if ( ! empty( $mem->blood_group ) ) : ?>
                                        <span style="background: rgba(255,51,75,0.1); color: #ff334b; font-weight: bold; border: 1px solid rgba(255,51,75,0.25); padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; display: inline-block;">
                                            <?php echo esc_html( $mem->blood_group ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span style="color: #999; font-size: 0.75rem; font-style: italic;">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Dynamic Validation Banner Status -->
                                    <span class="status-badge <?php echo $mem->status === 'Active' ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $mem->status === 'Active' ? '🟢 Active Official' : '🔴 Inactive / Expired'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-td-flex">
                                        <!-- Edit Personnel Record -->
                                        <a href="<?php echo esc_url( add_query_arg( 'edit_id', $mem->id ) ); ?>" class="action-btn" style="background: #007bff; display: flex; align-items: center; justify-content: center;" title="Edit Personnel Record">Edit</a>

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

                                        <!-- Direct Mail to them Button (From tech-team@tatkhalsa.in) -->
                                        <?php if ( ! empty( $mem->email ) ) : ?>
                                            <form method="POST" action="" style="margin:0;" onsubmit="return confirm('Are you sure you want to directly mail this ID card and credential information to: <?php echo esc_attr($mem->email); ?> from tech-team@tatkhalsa.in?');">
                                                <?php wp_nonce_field( 'tkf_verify_admin_action', 'tkf_verify_nonce' ); ?>
                                                <input type="hidden" name="tkf_verify_action" value="send_member_email">
                                                <input type="hidden" name="id" value="<?php echo esc_attr( $mem->id ); ?>">
                                                <button type="submit" class="action-btn" style="background: #e1a92a; color: #052054; border: none; font-weight: 700;" title="Send Credentials to <?php echo esc_attr($mem->email); ?> from tech-team@tatkhalsa.in">
                                                    Send
                                                </button>
                                            </form>
                                        <?php else : ?>
                                            <button class="action-btn" style="background: #cbd5e0; color: #718096; border: none; cursor: not-allowed; opacity: 0.65;" disabled title="No Email associated with this identity">
                                                Send
                                            </button>
                                        <?php endif; ?>
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
