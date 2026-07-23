<?php
/**
 * Brevo (Sendinblue) Integration for Tatkhalsa Foundation
 */

// Add Admin Menu for Brevo Settings
function tatkhalsa_add_brevo_menu() {
    add_submenu_page(
        'blood-master-data',
        'Brevo Email Settings',
        'Brevo Email Setup',
        'manage_options',
        'tatkhalsa-brevo-settings',
        'tatkhalsa_render_brevo_settings_page'
    );
}
add_action( 'admin_menu', 'tatkhalsa_add_brevo_menu' );

// Render Brevo Settings Page
function tatkhalsa_render_brevo_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    if ( isset($_POST['tatkhalsa_save_brevo']) && check_admin_referer('tatkhalsa_brevo_nonce') ) {
        update_option( 'tatkhalsa_brevo_api_key', sanitize_text_field( $_POST['brevo_api_key'] ) );
        update_option( 'tatkhalsa_brevo_sender_email', sanitize_email( $_POST['brevo_sender_email'] ) );
        update_option( 'tatkhalsa_brevo_sender_name', sanitize_text_field( $_POST['brevo_sender_name'] ) );
        echo '<div class="updated" style="padding: 10px; margin: 15px 0; border-left: 4px solid #46b450; background: #fff;"><p><strong>Brevo settings saved successfully!</strong></p></div>';
    }

    $api_key = get_option( 'tatkhalsa_brevo_api_key', defined('BREVO_API_KEY') ? BREVO_API_KEY : '' );
    $sender_email = get_option( 'tatkhalsa_brevo_sender_email', 'info@tatkhalsa.in' );
    $sender_name = get_option( 'tatkhalsa_brevo_sender_name', 'Tatkhalsa Foundation' );
    ?>
    <div class="wrap">
        <h1>Brevo Email & Newsletter Setup</h1>
        <p>Integrate Brevo (formerly Sendinblue) to deliver newsletters, donor registration receipts, and urgent blood request notifications with maximum inbox deliverability.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('tatkhalsa_brevo_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="brevo_api_key">Brevo API Key (v3)</label></th>
                    <td>
                        <input type="password" id="brevo_api_key" name="brevo_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" style="width: 100%; max-width: 500px;" autocomplete="off" placeholder="xkeysib-..." />
                        <p class="description">Get your API Key from Brevo Dashboard > Settings > SMTP & API > API Keys.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="brevo_sender_email">Verified Sender Email</label></th>
                    <td>
                        <input type="email" id="brevo_sender_email" name="brevo_sender_email" value="<?php echo esc_attr($sender_email); ?>" class="regular-text" style="width: 100%; max-width: 500px;" />
                        <p class="description">Must be a verified sender email in Brevo (e.g., <code>info@tatkhalsa.in</code> or <code>bloodoncall@tatkhalsa.in</code>).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="brevo_sender_name">Sender Name</label></th>
                    <td>
                        <input type="text" id="brevo_sender_name" name="brevo_sender_name" value="<?php echo esc_attr($sender_name); ?>" class="regular-text" style="width: 100%; max-width: 500px;" />
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" name="tatkhalsa_save_brevo" class="button button-primary">Save Settings</button>
            </p>
        </form>

        <hr style="margin: 30px 0;">

        <h2>Bulk Sync All Existing Records to Brevo</h2>
        <p>Sync all registered blood donors and newsletter subscribers from your website database into your Brevo Contact list:</p>
        <div id="syncBrevoAlert" style="display: none; padding: 12px; margin-bottom: 15px; border-left: 4px solid #46b450; background: #fff; box-shadow: 0 1px 1px rgba(0,0,0,.04);"></div>
        <button type="button" id="syncBrevoBtn" onclick="window.syncAllBrevoContacts()" class="button button-primary" style="background: #0A327D; border-color: #0A327D;">
            ⚡ Sync All Donors & Subscribers to Brevo Now
        </button>

        <hr style="margin: 30px 0;">

        <h2>Test Brevo Email Connection</h2>
        <div id="testBrevoAlert" style="display: none; padding: 12px; margin-bottom: 15px; border-left: 4px solid #46b450; background: #fff; box-shadow: 0 1px 1px rgba(0,0,0,.04);"></div>
        <form onsubmit="window.sendBrevoTestEmail(event)">
            <p>Send a test email to verify your API key and sender configuration:</p>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="email" id="testBrevoEmail" placeholder="Enter recipient test email" required class="regular-text" style="width: 320px; padding: 8px;" />
                <button type="submit" id="testBrevoBtn" class="button button-secondary">Send Test Email</button>
            </div>
        </form>
    </div>

    <script>
    window.syncAllBrevoContacts = async function() {
        const btn = document.getElementById('syncBrevoBtn');
        const alertBox = document.getElementById('syncBrevoAlert');
        btn.disabled = true;
        btn.innerText = 'Syncing Records to Brevo...';
        alertBox.style.display = 'none';

        try {
            const formData = new FormData();
            formData.append('action', 'sync_all_brevo_contacts');

            const res = await fetch(ajaxurl, { method: 'POST', body: formData });
            const data = await res.json();
            alertBox.style.display = 'block';
            if (data.success) {
                alertBox.style.borderLeftColor = '#46b450';
                alertBox.innerText = data.data.message;
            } else {
                alertBox.style.borderLeftColor = '#dc3232';
                alertBox.innerText = data.data.message;
            }
        } catch (err) {
            alertBox.style.display = 'block';
            alertBox.style.borderLeftColor = '#dc3232';
            alertBox.innerText = 'Sync request failed.';
        } finally {
            btn.disabled = false;
            btn.innerText = '⚡ Sync All Donors & Subscribers to Brevo Now';
        }
    };

    window.sendBrevoTestEmail = async function(e) {
        if (e) e.preventDefault();
        const email = document.getElementById('testBrevoEmail').value;
        const btn = document.getElementById('testBrevoBtn');
        const alertBox = document.getElementById('testBrevoAlert');
        btn.disabled = true;
        btn.innerText = 'Sending...';
        alertBox.style.display = 'none';

        try {
            const formData = new FormData();
            formData.append('action', 'test_brevo_email');
            formData.append('test_email', email);

            const res = await fetch(ajaxurl, { method: 'POST', body: formData });
            const data = await res.json();
            alertBox.style.display = 'block';
            if (data.success) {
                alertBox.style.borderLeftColor = '#46b450';
                alertBox.innerText = data.data.message;
            } else {
                alertBox.style.borderLeftColor = '#dc3232';
                alertBox.innerText = data.data.message;
            }
        } catch (err) {
            alertBox.style.display = 'block';
            alertBox.style.borderLeftColor = '#dc3232';
            alertBox.innerText = 'Network request failed.';
        } finally {
            btn.disabled = false;
            btn.innerText = 'Send Test Email';
        }
    };
    </script>
    <?php
}

// Hook pre_wp_mail to intercept and route through Brevo REST API
add_filter( 'pre_wp_mail', 'tatkhalsa_brevo_wp_mail_override', 10, 2 );
function tatkhalsa_brevo_wp_mail_override( $null, $atts ) {
    $api_key = get_option( 'tatkhalsa_brevo_api_key', defined('BREVO_API_KEY') ? BREVO_API_KEY : '' );
    if ( empty( $api_key ) ) {
        // Fallback to standard wp_mail / server mailer if Brevo API key is not configured
        return null;
    }

    $to          = isset( $atts['to'] ) ? $atts['to'] : '';
    $subject     = isset( $atts['subject'] ) ? $atts['subject'] : '';
    $message     = isset( $atts['message'] ) ? $atts['message'] : '';
    $attachments = isset( $atts['attachments'] ) ? $atts['attachments'] : array();

    // Convert $to to array
    if ( ! is_array( $to ) ) {
        $to = array_filter( array_map( 'trim', explode( ',', $to ) ) );
    }

    $recipients = array();
    foreach ( $to as $recipient ) {
        if ( is_email( $recipient ) ) {
            $recipients[] = array( 'email' => $recipient );
        }
    }

    if ( empty( $recipients ) ) {
        return false;
    }

    $sender_email = get_option( 'tatkhalsa_brevo_sender_email', 'info@tatkhalsa.in' );
    $sender_name  = get_option( 'tatkhalsa_brevo_sender_name', 'Tatkhalsa Foundation' );

    // Build payload
    $payload = array(
        'sender'      => array( 'name' => $sender_name, 'email' => $sender_email ),
        'to'          => $recipients,
        'subject'     => $subject,
        'htmlContent' => is_string( $message ) ? ( strpos( $message, '<' ) !== false ? $message : nl2br( $message ) ) : '',
    );

    // Handle attachments if present
    if ( ! empty( $attachments ) ) {
        if ( ! is_array( $attachments ) ) {
            $attachments = array( $attachments );
        }
        $brevo_atts = array();
        foreach ( $attachments as $file_path ) {
            if ( file_exists( $file_path ) ) {
                $content = base64_encode( file_get_contents( $file_path ) );
                $brevo_atts[] = array(
                    'name'    => basename( $file_path ),
                    'content' => $content,
                );
            }
        }
        if ( ! empty( $brevo_atts ) ) {
            $payload['attachment'] = $brevo_atts;
        }
    }

    $response = wp_remote_post( 'https://api.brevo.com/v3/smtp/email', array(
        'method'    => 'POST',
        'headers'   => array(
            'api-key'      => $api_key,
            'content-type' => 'application/json',
            'accept'       => 'application/json',
        ),
        'body'      => json_encode( $payload ),
        'timeout'   => 15,
    ) );

    if ( is_wp_error( $response ) ) {
        error_log( 'Brevo API Error: ' . $response->get_error_message() );
        return false;
    }

    $code = wp_remote_retrieve_response_code( $response );
    if ( $code >= 200 && $code < 300 ) {
        return true; // Email handled successfully by Brevo API
    } else {
        $body = wp_remote_retrieve_body( $response );
        error_log( "Brevo API Failed ($code): " . $body );
        return false;
    }
}

// AJAX handler to test Brevo connection
add_action( 'wp_ajax_test_brevo_email', 'tatkhalsa_test_brevo_email_handler' );
function tatkhalsa_test_brevo_email_handler() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $test_email = isset( $_POST['test_email'] ) ? sanitize_email( $_POST['test_email'] ) : '';
    if ( ! is_email( $test_email ) ) {
        wp_send_json_error( array( 'message' => 'Invalid test email address.' ) );
    }

    $api_key = get_option( 'tatkhalsa_brevo_api_key', defined('BREVO_API_KEY') ? BREVO_API_KEY : '' );
    if ( empty( $api_key ) ) {
        wp_send_json_error( array( 'message' => 'Brevo API Key is missing. Please save your Brevo API Key above first.' ) );
    }

    $sender_email = get_option( 'tatkhalsa_brevo_sender_email', 'info@tatkhalsa.in' );
    $sender_name  = get_option( 'tatkhalsa_brevo_sender_name', 'Tatkhalsa Foundation' );

    $payload = array(
        'sender'      => array( 'name' => $sender_name, 'email' => $sender_email ),
        'to'          => array( array( 'email' => $test_email ) ),
        'subject'     => 'Test Email from Tatkhalsa Brevo Integration',
        'htmlContent' => '<h3>Brevo Connection Test Successful!</h3><p>Your Tatkhalsa Foundation website is now configured to send emails via Brevo.</p>',
    );

    $response = wp_remote_post( 'https://api.brevo.com/v3/smtp/email', array(
        'method'    => 'POST',
        'headers'   => array(
            'api-key'      => $api_key,
            'content-type' => 'application/json',
            'accept'       => 'application/json',
        ),
        'body'      => json_encode( $payload ),
        'timeout'   => 15,
    ) );

    if ( is_wp_error( $response ) ) {
        wp_send_json_error( array( 'message' => 'Network Error: ' . $response->get_error_message() ) );
    }

    $code = wp_remote_retrieve_response_code( $response );
    $body = wp_remote_retrieve_body( $response );

    if ( $code >= 200 && $code < 300 ) {
        wp_send_json_success( array( 'message' => '✓ Test email sent successfully via Brevo! Please check your inbox.' ) );
    } else {
        $error_details = 'Unknown error';
        $decoded = json_decode( $body, true );
        if ( $decoded && isset( $decoded['message'] ) ) {
            $error_details = $decoded['message'];
        }
        wp_send_json_error( array( 'message' => 'Failed (' . $code . '): ' . $error_details ) );
    }
}

// Helper function to sync subscriber or donor contacts directly into Brevo Contact Lists
function tatkhalsa_add_brevo_contact( $email, $attributes = array(), $list_ids = array() ) {
    $api_key = get_option( 'tatkhalsa_brevo_api_key', defined('BREVO_API_KEY') ? BREVO_API_KEY : '' );
    if ( empty( $api_key ) || ! is_email( $email ) ) {
        return 'Missing API key or invalid email';
    }

    $payload = array(
        'email'         => $email,
        'updateEnabled' => true,
    );

    if ( ! empty( $attributes ) ) {
        $payload['attributes'] = $attributes;
    }

    if ( ! empty( $list_ids ) ) {
        $payload['listIds'] = $list_ids;
    }

    $response = wp_remote_post( 'https://api.brevo.com/v3/contacts', array(
        'method'  => 'POST',
        'headers' => array(
            'api-key'      => $api_key,
            'content-type' => 'application/json',
            'accept'       => 'application/json',
        ),
        'body'    => json_encode( $payload ),
        'timeout' => 10,
    ) );

    if ( is_wp_error( $response ) ) {
        return $response->get_error_message();
    }

    $code = wp_remote_retrieve_response_code( $response );
    $body = wp_remote_retrieve_body( $response );

    if ( $code >= 200 && $code < 300 ) {
        return true;
    }

    $decoded = json_decode( $body, true );
    if ( $decoded && isset( $decoded['message'] ) ) {
        return $decoded['message'];
    }

    return 'Error ' . $code;
}

// AJAX handler to sync all donors and newsletter subscribers to Brevo
add_action( 'wp_ajax_sync_all_brevo_contacts', 'tatkhalsa_sync_all_brevo_contacts_handler' );
function tatkhalsa_sync_all_brevo_contacts_handler() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $api_key = get_option( 'tatkhalsa_brevo_api_key', defined('BREVO_API_KEY') ? BREVO_API_KEY : '' );
    if ( empty( $api_key ) ) {
        wp_send_json_error( array( 'message' => 'Brevo API Key is missing. Please save your Brevo API Key above first.' ) );
    }

    $count = 0;
    $errors = array();

    // 1. Sync Blood Donors
    $donors = get_posts( array(
        'post_type'      => 'blood_donor',
        'posts_per_page' => -1,
        'post_status'    => 'any'
    ) );

    foreach ( $donors as $d ) {
        $email = get_post_meta( $d->ID, 'donor_email', true );
        $name = get_post_meta( $d->ID, 'donor_name', true );
        $blood_group = get_post_meta( $d->ID, 'blood_group', true );
        $contact = get_post_meta( $d->ID, 'contact_details', true );
        $donor_id_string = function_exists('tatkhalsa_get_or_create_donor_id') ? tatkhalsa_get_or_create_donor_id( $d->ID ) : get_post_meta( $d->ID, 'donor_id_number', true );

        if ( is_email( $email ) ) {
            $name_parts = explode( ' ', trim( $name ) );
            $firstname = array_shift( $name_parts );
            $lastname = count( $name_parts ) > 0 ? implode( ' ', $name_parts ) : '';

            $res = tatkhalsa_add_brevo_contact( $email, array(
                'FIRSTNAME' => $firstname,
                'LASTNAME' => $lastname,
                'NAME' => $name,
                'DONOR_ID' => $donor_id_string,
                'BLOOD_GROUP' => $blood_group,
                'PHONE' => $contact
            ) );
            if ( $res === true ) {
                $count++;
            } else {
                $errors[] = "$email: $res";
            }
        }
    }

    // 2. Sync Newsletter Subscribers
    $subscribers = get_option('tatkhalsa_newsletter_subscribers', []);
    foreach ( $subscribers as $sub_email ) {
        if ( is_email( $sub_email ) ) {
            $res = tatkhalsa_add_brevo_contact( $sub_email );
            if ( $res === true ) {
                $count++;
            } else {
                $errors[] = "$sub_email: $res";
            }
        }
    }

    if ( ! empty( $errors ) ) {
        wp_send_json_error( array( 'message' => "Synced $count records. Errors: " . implode( ', ', array_slice($errors, 0, 3) ) . (count($errors) > 3 ? "..." : "") ) );
    }

    wp_send_json_success( array( 'message' => "✓ Successfully synced " . $count . " records (donors & newsletter subscribers) to Brevo!" ) );
}
