<?php
/**
 * Tatkhalsa Official Theme Functions and Definitions
 *
 * @package TatkhalsaTheme
 */

if ( ! function_exists( 'tatkhalsa_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function tatkhalsa_setup() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * This replaces the hardcoded title in the head dynamically for SEO.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 */
		add_theme_support( 'post-thumbnails' );

		// Register primary navigation menu.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'tatkhalsa-theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, comment-list, gallery, caption, and script/style to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add support for responsive embeds
		add_theme_support( 'responsive-embeds' );

		// Custom logo support
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 240,
				'width'       => 240,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'tatkhalsa_setup' );

/**
 * Safely get the Tatkhalsa Logo URL, supporting Customizer Uploaded custom logo with an SSL-safe fallback to the theme's Logo.png
 */
function tatkhalsa_get_logo_url() {
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		if ( $logo ) {
			return set_url_scheme( $logo[0] );
		}
	}
	return set_url_scheme( get_stylesheet_directory_uri() . '/Logo.png' );
}

/**
 * Enqueue scripts and styles.
 */
function tatkhalsa_scripts() {
	// Enqueue main Theme Theme-Stylesheet.
	wp_enqueue_style( 'tatkhalsa-theme-style', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'tatkhalsa_scripts' );

/**
 * Handle Volunteer Form Submission & Direct Email Delivery to tatkhalsafoundation@gmail.com
 */
function tatkhalsa_submit_volunteer() {
	// Sanitize form inputs
	$name    = isset( $_POST['vName'] ) ? sanitize_text_field( wp_unslash( $_POST['vName'] ) ) : '';
	$email   = isset( $_POST['vEmail'] ) ? sanitize_email( wp_unslash( $_POST['vEmail'] ) ) : '';
	$phone   = isset( $_POST['vPhone'] ) ? sanitize_text_field( wp_unslash( $_POST['vPhone'] ) ) : '';
	$message = isset( $_POST['vMessage'] ) ? sanitize_textarea_field( wp_unslash( $_POST['vMessage'] ) ) : '';

	if ( empty( $name ) || empty( $email ) || empty( $phone ) || empty( $message ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please fill in all layout fields.', 'tatkhalsa-theme' ) ) );
	}

	// Email config
	$to      = 'tatkhalsafoundation@gmail.com';
	$subject = 'New Volunteer Registration - ' . $name;
	
	$body  = "<h2>New Tatkhalsa Volunteer Application</h2>";
	$body .= "<p><strong>Name:</strong> " . esc_html( $name ) . "</p>";
	$body .= "<p><strong>Email:</strong> " . esc_html( $email ) . "</p>";
	$body .= "<p><strong>Phone:</strong> " . esc_html( $phone ) . "</p>";
	$body .= "<p><strong>Skills & Message:</strong><br />" . nl2br( esc_html( $message ) ) . "</p>";

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Tatkhalsa Foundation <info@tatkhalsa.in>',
		'Reply-To: ' . $email
	);

	// Try sending email
	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => esc_html__( 'Application submitted successfully! We will contact you soon.', 'tatkhalsa-theme' ) ) );
	} else {
		wp_send_json_error( array( 'message' => esc_html__( 'Failed to send direct email. Please email us directly at tatkhalsafoundation@gmail.com', 'tatkhalsa-theme' ) ) );
	}
}
add_action( 'wp_ajax_submit_volunteer', 'tatkhalsa_submit_volunteer' );
add_action( 'wp_ajax_nopriv_submit_volunteer', 'tatkhalsa_submit_volunteer' );
?>
