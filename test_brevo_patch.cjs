const fs = require('fs');
let content = fs.readFileSync('wp-content/themes/tatkhalsa/admin-brevo.php', 'utf8');

const newAddContact = `function tatkhalsa_add_brevo_contact( $email, $attributes = array(), $list_ids = array() ) {
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
}`;

content = content.replace(/function tatkhalsa_add_brevo_contact\([\s\S]*?return ! is_wp_error\( \$response \) && wp_remote_retrieve_response_code\( \$response \) < 300;\n\}/, newAddContact);


const newHandler = `function tatkhalsa_sync_all_brevo_contacts_handler() {
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

        if ( is_email( $email ) ) {
            $res = tatkhalsa_add_brevo_contact( $email, array(
                'NAME' => $name,
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
}`;

content = content.replace(/function tatkhalsa_sync_all_brevo_contacts_handler\([\s\S]*?wp_send_json_success\( array\( 'message' => "✓ Successfully synced " \. \$count \. " records \(donors & newsletter subscribers\) to Brevo!" \) \);\n\}/, newHandler);

fs.writeFileSync('wp-content/themes/tatkhalsa/admin-brevo.php', content);
