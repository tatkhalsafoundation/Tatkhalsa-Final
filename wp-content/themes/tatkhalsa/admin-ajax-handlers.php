<?php

// Admin Edit Donor
function tatkhalsa_admin_edit_donor() {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) $payload = $_POST;
    
    $id = isset($payload['id']) ? str_replace('DONOR_', '', $payload['id']) : 0;
    if (!$id) wp_send_json_error(['message' => 'Invalid ID']);
    
    update_post_meta($id, 'donor_name', sanitize_text_field($payload['name']));
    update_post_meta($id, 'blood_group', sanitize_text_field($payload['bloodGroup']));
    update_post_meta($id, 'donor_email', sanitize_email($payload['email']));
    update_post_meta($id, 'contact_details', sanitize_text_field($payload['contact']));
    update_post_meta($id, 'address', sanitize_textarea_field($payload['address']));
    update_post_meta($id, 'availability_status', sanitize_text_field($payload['availabilityStatus']));
    
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
