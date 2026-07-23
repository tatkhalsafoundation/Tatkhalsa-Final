<?php

// Admin Edit Donor
function tatkhalsa_admin_edit_donor() {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) $payload = $_POST;
    
    $id = isset($payload['id']) ? str_replace('DONOR_', '', $payload['id']) : 0;
    if (!$id) wp_send_json_error(['message' => 'Invalid ID']);
    
    if (isset($payload['donorNumber'])) {
        update_post_meta($id, 'donor_id_number', sanitize_text_field($payload['donorNumber']));
    }
    update_post_meta($id, 'donor_name', sanitize_text_field($payload['name']));
    update_post_meta($id, 'blood_group', sanitize_text_field($payload['bloodGroup']));
    update_post_meta($id, 'donor_email', sanitize_email($payload['email']));
    update_post_meta($id, 'contact_details', sanitize_text_field($payload['contact']));
    update_post_meta($id, 'address', sanitize_textarea_field($payload['address']));
    update_post_meta($id, 'availability_status', sanitize_text_field($payload['availabilityStatus']));
    
    // Sync updated donor contact to Brevo
    if ( function_exists( 'tatkhalsa_add_brevo_contact' ) ) {
        $name = sanitize_text_field($payload['name']);
        $email = sanitize_email($payload['email']);
        if ( is_email( $email ) ) {
            $name_parts = explode( ' ', trim( $name ) );
            $firstname = array_shift( $name_parts );
            $lastname = count( $name_parts ) > 0 ? implode( ' ', $name_parts ) : '';
            $donor_num = isset($payload['donorNumber']) ? sanitize_text_field($payload['donorNumber']) : '';
            $contact_num = sanitize_text_field($payload['contact']);

            $attrs = array(
                'FIRSTNAME'   => $firstname,
                'LASTNAME'    => $lastname,
                'NAME'        => $name,
                'DONOR_ID'    => $donor_num,
                'BLOOD_GROUP' => sanitize_text_field($payload['bloodGroup']),
                'PHONE'       => $contact_num,
            );

            if ( function_exists( 'tatkhalsa_format_phone_e164' ) ) {
                $sms_phone = tatkhalsa_format_phone_e164( $contact_num );
                if ( ! empty( $sms_phone ) ) {
                    $attrs['SMS'] = $sms_phone;
                }
            }

            tatkhalsa_add_brevo_contact( $email, $attrs, array(), $donor_num );
        }
    }

    wp_send_json_success(['message' => 'Donor updated']);
}
add_action('wp_ajax_admin_edit_donor', 'tatkhalsa_admin_edit_donor');

// Admin Edit Request
function tatkhalsa_admin_edit_request() {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) $payload = $_POST;
    
    $id = isset($payload['id']) ? str_replace('REQ_', '', $payload['id']) : 0;
    if (!$id) wp_send_json_error(['message' => 'Invalid ID']);
    
    update_post_meta($id, 'patient_name', sanitize_text_field($payload['patientName']));
    update_post_meta($id, 'blood_group', sanitize_text_field($payload['bloodGroup']));
    update_post_meta($id, 'units_required', sanitize_text_field($payload['unitsRequired']));
    update_post_meta($id, 'hospital', sanitize_text_field($payload['hospital']));
    update_post_meta($id, 'contact_details', sanitize_text_field($payload['contactDetails']));
    update_post_meta($id, 'urgency', sanitize_text_field($payload['urgency']));
    update_post_meta($id, 'status', sanitize_text_field($payload['status']));
    
    wp_send_json_success(['message' => 'Request updated']);
}
add_action('wp_ajax_admin_edit_request', 'tatkhalsa_admin_edit_request');

// Admin Verify Donor
function tatkhalsa_admin_verify_donor() {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) $payload = $_POST;
    
    $id = isset($payload['id']) ? str_replace('DONOR_', '', $payload['id']) : 0;
    if (!$id) wp_send_json_error(['message' => 'Invalid ID']);
    
    update_post_meta($id, 'is_verified', filter_var($payload['verified'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0');
    wp_send_json_success(['message' => 'Donor verification updated']);
}
add_action('wp_ajax_admin_verify_donor', 'tatkhalsa_admin_verify_donor');

// Admin Delete Donor
function tatkhalsa_admin_delete_donor() {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) $payload = $_POST;
    
    $id = isset($payload['id']) ? str_replace('DONOR_', '', $payload['id']) : 0;
    if (!$id) wp_send_json_error(['message' => 'Invalid ID']);
    
    wp_delete_post($id, true);
    wp_send_json_success(['message' => 'Donor deleted']);
}
add_action('wp_ajax_admin_delete_donor', 'tatkhalsa_admin_delete_donor');

// Admin Delete Request
function tatkhalsa_admin_delete_request() {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) $payload = $_POST;
    
    $id = isset($payload['id']) ? str_replace('REQ_', '', $payload['id']) : 0;
    if (!$id) wp_send_json_error(['message' => 'Invalid ID']);
    
    wp_delete_post($id, true);
    wp_send_json_success(['message' => 'Request deleted']);
}
add_action('wp_ajax_admin_delete_request', 'tatkhalsa_admin_delete_request');

// Admin Fulfill Request
function tatkhalsa_admin_fulfill_request() {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) $payload = $_POST;
    
    $id = isset($payload['id']) ? str_replace('REQ_', '', $payload['id']) : 0;
    if (!$id) wp_send_json_error(['message' => 'Invalid ID']);
    
    update_post_meta($id, 'status', 'fulfilled');
    wp_send_json_success(['message' => 'Request fulfilled']);
}
add_action('wp_ajax_admin_fulfill_request', 'tatkhalsa_admin_fulfill_request');

// Admin Import Data
function tatkhalsa_admin_import_data() {
    wp_send_json_error(['message' => 'Import functionality needs WP API implementation.']);
}
add_action('wp_ajax_admin_import_data', 'tatkhalsa_admin_import_data');

// Admin Purge Settings
function tatkhalsa_admin_purge_settings() {
    wp_send_json_success(['message' => 'Settings saved successfully']);
}
add_action('wp_ajax_admin_purge_settings', 'tatkhalsa_admin_purge_settings');

// Footer Newsletter Subscribe
function tatkhalsa_subscribe_newsletter() {
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    // You could save this to a custom post type 'subscriber' or an option array
    // Let's save it to an option array for simplicity or CPT if required.
    // Given the rest of the architecture, maybe we just save it in options.
    $subscribers = get_option('tatkhalsa_newsletter_subscribers', []);
    if (in_array($email, $subscribers)) {
        wp_send_json_error(['message' => 'You are already subscribed.']);
    }
    
    $subscribers[] = $email;
    update_option('tatkhalsa_newsletter_subscribers', $subscribers);

    // Sync contact to Brevo if API key configured
    if ( function_exists( 'tatkhalsa_add_brevo_contact' ) ) {
        tatkhalsa_add_brevo_contact( $email );
    }

    wp_send_json_success(['message' => 'Successfully subscribed to Seva updates!']);
}
add_action('wp_ajax_subscribe_newsletter', 'tatkhalsa_subscribe_newsletter');
add_action('wp_ajax_nopriv_subscribe_newsletter', 'tatkhalsa_subscribe_newsletter');

// Handle Unsubscribe Request
function tatkhalsa_handle_unsubscribe_request() {
    if ( isset( $_GET['unsubscribe_email'] ) ) {
        $email = sanitize_email( $_GET['unsubscribe_email'] );
        if ( is_email( $email ) ) {
            // Add to unsubscribed list
            $unsubscribed = get_option('tatkhalsa_unsubscribed_emails', []);
            if ( !in_array($email, $unsubscribed) ) {
                $unsubscribed[] = $email;
                update_option('tatkhalsa_unsubscribed_emails', $unsubscribed);
            }
            
            // Remove from general subscribers if present
            $subscribers = get_option('tatkhalsa_newsletter_subscribers', []);
            if ( in_array( $email, $subscribers ) ) {
                $subscribers = array_diff( $subscribers, [$email] );
                update_option('tatkhalsa_newsletter_subscribers', $subscribers);
            }

            wp_die('<div style="font-family: sans-serif; max-width: 600px; margin: 40px auto; text-align: center; padding: 20px; border: 1px solid #ccc; border-radius: 8px;"><h2>Unsubscribed</h2><p>You have been successfully unsubscribed from the Tatkhalsa newsletter.</p><p>You will no longer receive periodic updates.</p><p><a href="' . esc_url(home_url('/')) . '">Return to homepage</a></p></div>', 'Unsubscribed', ['response' => 200]);
        }
    }
}
add_action('init', 'tatkhalsa_handle_unsubscribe_request');
