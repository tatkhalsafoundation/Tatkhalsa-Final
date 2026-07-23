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
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$logo_img = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		if ( ! empty( $logo_img[0] ) ) {
			return set_url_scheme( $logo_img[0] );
		}
	}
	return tatkhalsa_get_theme_logo_url();
}

/**
 * Get the direct theme-packaged Logo URL (gold sikh emblem) completely independent of site customizer custom_logo overrides.
 */
function tatkhalsa_get_theme_logo_url() {
	$theme_dir = get_stylesheet_directory();
	$possible_filenames = array(
		'/Logo.png',
		'/logo.png',
		'/Logo.jpg',
		'/logo.jpg',
		'/Logo.jpeg',
		'/logo.jpeg'
	);

	foreach ( $possible_filenames as $filename ) {
		if ( file_exists( $theme_dir . $filename ) ) {
			return set_url_scheme( get_stylesheet_directory_uri() . $filename );
		}
	}

	return set_url_scheme( get_stylesheet_directory_uri() . '/Logo.png' );
}

/**
 * Enqueue scripts and styles.
 */
function tatkhalsa_scripts() {
	// Enqueue main Theme Theme-Stylesheet with cache busting.
	$style_path = get_stylesheet_directory() . '/style.css';
	$version = time();
	wp_enqueue_style( 'tatkhalsa-theme-style', get_stylesheet_uri(), array(), $version );
}
add_action( 'wp_enqueue_scripts', 'tatkhalsa_scripts' );

/**
 * Common Input Anti-Spam / Anti-Fake Data Validator
 */
function tatkhalsa_validate_common_inputs( $name = '', $email = '', $phone = '' ) {
	// 1. Validate full name field
	if ( ! empty( $name ) ) {
		$name = trim( $name );
		if ( strlen( $name ) < 3 ) {
			return 'Please enter a valid full name (minimum 3 characters required).';
		}
		$lower_name = strtolower( $name );
		$fake_names = array( 'test', 'fake', 'dummy', 'none', 'unknown', 'nobody', 'abc', 'xyz', 'qwer', 'asdf', 'zxcv', 'foo', 'bar', 'something', 'placeholder', 'asdfasdf' );
		foreach ( $fake_names as $fn ) {
			if ( $lower_name === $fn || strpos( $lower_name, 'asdf' ) !== false || strpos( $lower_name, 'qwer' ) !== false ) {
				return 'Please enter your real full name. Placeholder or junk text is not permitted.';
			}
		}
		// Regular expression to check repetitive sequential identical letters (e.g. "aaaa")
		if ( preg_match( '/(.)\1{3,}/', $name ) ) {
			return 'Real name cannot contain repetitive sequential identical characters (e.g. "aaaa").';
		}
		// Keyboard mash check (e.g. 5+ consecutive consonants)
		if ( preg_match( '/[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]{5,}/', $name ) ) {
			return 'The name contains an invalid keyboard mashing pattern. Please provide a real name.';
		}
	}

	// 2. Validate email address
	if ( ! empty( $email ) ) {
		$email = trim( $email );
		if ( ! is_email( $email ) ) {
			return 'Please enter a valid, well-formed email address.';
		}
		$parts  = explode( '@', $email );
		$prefix = isset( $parts[0] ) ? strtolower( $parts[0] ) : '';
		$domain = isset( $parts[1] ) ? strtolower( $parts[1] ) : '';

		$fake_prefixes = array( 'test', 'abc', 'xyz', 'fake', 'dummy', 'none', 'noemail', 'null', 'temp', 'admin' );
		if ( in_array( $prefix, $fake_prefixes, true ) || strlen( $prefix ) < 3 ) {
			return 'This email prefix looks invalid or fake. Please use your real email address.';
		}

		$fake_domains = array( 'test.com', 'example.com', 'invalid.com', 'fake.com', 'dummy.com', 'abc.com', 'xyz.com', 'tempmail.com', 'dispostable.com', 'mailinator.com', 'yopmail.com', 'temp-mail.org', 'guerrillamail.com', 'sharklasers.com', '10minutemail.com' );
		if ( in_array( $domain, $fake_domains, true ) || strpos( $domain, 'temp' ) !== false || strpos( $domain, 'disposable' ) !== false || strpos( $domain, 'mailinator' ) !== false ) {
			return 'Temporary, disposable, or test email domains are blocked. Please provide a real, active email address.';
		}
	}

	// 3. Validate contact phone number
	if ( ! empty( $phone ) ) {
		$digits = preg_replace( '/[^0-9]/', '', $phone );
		$len    = strlen( $digits );
		if ( $len < 8 || $len > 15 ) {
			return 'Mobile or phone number format is invalid. Must contain between 8 and 15 digits.';
		}

		// Repetitive identical digit check (e.g. 000000)
		if ( preg_match( '/(.)\1{5,}/', $digits ) ) {
			return 'Your mobile number cannot contain repetitive identical digits (e.g. 000000). Please provide your real active number.';
		}

		// Diverse digits check (avoid patterns with only 2 unique numbers like 1212121212)
		$unique_digits = count( array_unique( str_split( $digits ) ) );
		if ( $unique_digits < 3 ) {
			return 'This number has too few unique digits and looks like placeholder or fake data.';
		}

		// Check for consecutive sequential patterns (e.g. 1234567, 9876543)
		$seq_up_count   = 0;
		$seq_down_count = 0;
		for ( $i = 0; $i < $len - 1; $i++ ) {
			$curr = intval( $digits[ $i ] );
			$next = intval( $digits[ $i + 1 ] );
			if ( $next === $curr + 1 ) {
				$seq_up_count++;
			} else {
				$seq_up_count = 0;
			}
			if ( $next === $curr - 1 ) {
				$seq_down_count++;
			} else {
				$seq_down_count = 0;
			}

			if ( $seq_up_count >= 5 || $seq_down_count >= 5 ) {
				return 'Sequential numbers (e.g., "123456" or "987654") are not accepted. Please provide your actual active number.';
			}
		}

		// Frequently targeted fake lists
		$common_fakes = array( '1234567890', '0987654321', '9876543210', '12345678', '87654321', '0123456789' );
		foreach ( $common_fakes as $cf ) {
			if ( strpos( $digits, $cf ) !== false ) {
				return 'Common placeholder or test phone numbers (e.g., 1234567890) are not allowed.';
			}
		}
	}

	return true;
}

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

	// Dynamic fake / spam protection checks
	$validation_check = tatkhalsa_validate_common_inputs( $name, $email, $phone );
	if ( true !== $validation_check ) {
		wp_send_json_error( array( 'message' => $validation_check ) );
	}

	// Email config
	$to      = 'tatkhalsafoundation@gmail.com';
	$subject = 'New Volunteer Registration - ' . $name;
	
	$body  = "<h2>New Tatkhalsa Volunteer Application</h2>";
	$body .= "<p><strong>Name:</strong> " . esc_html( $name ) . "</p>";
	$body .= "<p><strong>Email:</strong> " . esc_html( $email ) . "</p>";
	$body .= "<p><strong>Phone:</strong> " . esc_html( $phone ) . "</p>";
	$body .= "<p><strong>Skills & Message:</strong><br />" . nl2br( esc_html( $message ) ) . "</p>";

	
	$api_key = get_option( 'tatkhalsa_brevo_api_key', defined('BREVO_API_KEY') ? BREVO_API_KEY : '' );
	
	$logo_url = get_template_directory_uri() . '/Logo.png';
	$html_message = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . esc_html($subject) . '</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #1a1e23; color: #e6e9ef; }
        .wrapper { width: 100%; background-color: #1a1e23; padding: 20px 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #0f1621; overflow: hidden; }
        .header { text-align: center; padding: 40px 20px 20px; background-color: #0f1621; }
        .header img { max-width: 150px; height: auto; }
        .content { padding: 30px; font-size: 15px; line-height: 1.6; color: #e6e9ef; }
        .content h1, .content h2, .content h3 { color: #f0a500; margin-top: 0; }
        .content a { color: #f0a500; text-decoration: underline; }
        .content img { max-width: 100%; height: auto; display: block; margin: 15px 0; }
        .footer { background-color: #1a1e23; padding: 40px 30px; border-top: 1px solid #2a2e33; text-align: left; }
        .footer-social { margin-bottom: 20px; }
        .footer-social a { display: inline-block; margin-right: 10px; background-color: #5b5f64; color: #fff; width: 32px; height: 32px; line-height: 32px; text-align: center; border-radius: 50%; text-decoration: none; font-size: 14px; font-weight: bold; }
        .footer-columns { display: table; width: 100%; }
        .footer-col-left { display: table-cell; vertical-align: middle; width: 150px; }
        .footer-col-left img { max-width: 120px; }
        .footer-col-right { display: table-cell; vertical-align: middle; padding-left: 20px; }
        .footer-text { font-size: 12px; color: #9a9e93; line-height: 1.5; margin: 0 0 10px 0; }
        .footer-links { font-size: 12px; }
        .footer-links a { color: #9a9e93; text-decoration: underline; margin-right: 10px; }
        .share-btn { display: inline-block; background-color: #6c757d; color: #fff; padding: 5px 15px; text-decoration: none; font-size: 12px; border-radius: 3px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <img src="' . esc_url($logo_url) . '" alt="Nihung Santhia" />
            </div>
            <div class="content">
                ' . wpautop($message) . '
            </div>
            <div class="footer">
                <div class="footer-social">
                    <a href="#" title="Facebook">f</a>
                    <a href="#" title="YouTube">y</a>
                    <a href="#" title="Instagram">i</a>
                    <a href="' . esc_url(home_url()) . '" title="Website">w</a>
                </div>
                <div class="footer-columns">
                    <div class="footer-col-left">
                        <img src="' . esc_url($logo_url) . '" alt="Nihung Santhia" />
                    </div>
                    <div class="footer-col-right">
                        <a href="#" class="share-btn">Share</a>
                        <p class="footer-text">Nihung Santhia</p>
                        <p class="footer-text">You are receiving this newsletter because you are a student of Nihung Santhia or you have subscribed via the website.</p>
                        <div class="footer-links">
                            <a href="{{ unsubscribe }}">Preferences</a> | <a href="{{ unsubscribe }}">Unsubscribe</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';

	$sent = false;
	$count = count($emails);

	if ( ! empty( $api_key ) ) {
		// Send via Brevo API
		$messageVersions = array();
		foreach ( $emails as $email ) {
			$messageVersions[] = array(
				'to' => array( array( 'email' => $email ) )
			);
		}

		$payload = array(
			'sender' => array( 'name' => 'Tatkhalsa Foundation', 'email' => 'info@tatkhalsa.in' ),
			'subject' => $subject,
			'htmlContent' => $html_message,
			'messageVersions' => $messageVersions
		);

		$response = wp_remote_post( 'https://api.brevo.com/v3/smtp/email', array(
			'method'  => 'POST',
			'headers' => array(
				'api-key'      => $api_key,
				'content-type' => 'application/json',
				'accept'       => 'application/json',
			),
			'body'    => json_encode( $payload ),
			'timeout' => 30,
		) );

		if ( ! is_wp_error( $response ) ) {
			$code = wp_remote_retrieve_response_code( $response );
			if ( $code == 201 || $code == 200 || $code == 202 ) {
				$sent = true;
			}
		}
	} else {
		// Fallback to wp_mail
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: Tatkhalsa Foundation <info@tatkhalsa.in>',
		);
		foreach ( $emails as $email ) {
			wp_mail( $email, $subject, str_replace('{{ unsubscribe }}', home_url( '/?unsubscribe_email=' . urlencode( $email ) ), $html_message), $headers );
		}
		$sent = true;
	}


	if ( $sent ) {
		wp_send_json_success( array( 'message' => 'Newsletter successfully sent to ' . $count . ' users.' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Failed to send newsletter emails.' ) );
	}
}
add_action( 'wp_ajax_send_donor_newsletter', 'tatkhalsa_send_donor_newsletter' );
add_action( 'wp_ajax_nopriv_send_donor_newsletter', 'tatkhalsa_send_donor_newsletter' );

function tatkhalsa_send_pdf_certificate() {
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'send_pdf_certificate' ) {
		wp_send_json_error( array( 'message' => 'Invalid request.' ) );
	}

	$email = isset( $_POST['donorEmail'] ) ? sanitize_email( wp_unslash( $_POST['donorEmail'] ) ) : '';
	$pdf_data = isset( $_POST['pdfData'] ) ? wp_unslash( $_POST['pdfData'] ) : '';

	if ( empty( $email ) || empty( $pdf_data ) ) {
		wp_send_json_error( array( 'message' => 'Missing email or PDF data.' ) );
	}

	$parts = explode( ',', $pdf_data );
	if ( count( $parts ) < 2 ) {
		wp_send_json_error( array( 'message' => 'Invalid image data format.' ) );
	}
	$image_decoded = base64_decode( $parts[1] );

	$upload_dir = wp_upload_dir();
	$filename = 'Certificate-of-Appreciation-' . time() . '.jpg';
	$filepath = $upload_dir['path'] . '/' . $filename;
	
	file_put_contents( $filepath, $image_decoded );

	$subject = '🏆 Your Certificate of Appreciation | Tatkhalsa Foundation';
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Tatkhalsa Foundation <info@tatkhalsa.in>',
		'Reply-To: noreply@tatkhalsa.in',
		'Bcc: info@tatkhalsa.in'
	);
	
	$body = "
	<div style='background-color:#f4f7f6; padding:40px 20px; font-family:\"Arial\", sans-serif;'>
		<p style='color:#0a2342; font-size:16px;'>Waheguru Ji Ka Khalsa, Waheguru Ji Ki Fateh.</p>
		<p style='color:#555; font-size:16px;'>Thank you for your noble commitment. Please find attached your official Certificate of Appreciation from Tatkhalsa Foundation.</p>
	</div>";

	$attachments = array( $filepath );

	$sent = wp_mail( $email, $subject, $body, $headers, $attachments );

	unlink( $filepath );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => 'Certificate sent! Please check your email inbox.' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Failed to send the email. Please try again later.' ) );
	}
}
add_action( 'wp_ajax_send_pdf_certificate', 'tatkhalsa_send_pdf_certificate' );
add_action( 'wp_ajax_nopriv_send_pdf_certificate', 'tatkhalsa_send_pdf_certificate' );



/**
 * Register Customizer Settings for Images
 */
function tatkhalsa_customize_register( $wp_customize ) {
	// Campaign Images Section
	$wp_customize->add_section( 'tatkhalsa_campaigns', array(
		'title'    => __( 'Campaign Images', 'tatkhalsa-theme' ),
		'priority' => 130,
	) );

	// Settings & Controls for Campaigns
	$campaigns = array(
		'tatkhalsa_nimrat_kaur_img' => array(
			'label' => __( 'Nimrat Kaur Campaign Image', 'tatkhalsa-theme' ),
			'default' => get_stylesheet_directory_uri() . '/assets/images/regenerated_image_1781128512768.jpg'
		),
		'tatkhalsa_punjab_relief_img' => array(
			'label' => __( 'Punjab Relief Support Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1514222134-b57cbb8ce073?auto=format&fit=crop&w=800&q=80'
		),
		'tatkhalsa_grocery_help_img' => array(
			'label' => __( 'Grocery Help Seva Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1609137144813-1d67493fa7b2?auto=format&fit=crop&w=800&q=80'
		),
		'tatkhalsa_1984_victim_img' => array(
			'label' => __( '1984 Victim Families Support Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1605701243007-df5b128caff8?auto=format&fit=crop&w=800&q=80'
		),
		'tatkhalsa_marriages_seva_img' => array(
			'label' => __( 'Underprivileged Marriages Support Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1610030469668-93535c17b6b3?auto=format&fit=crop&w=800&q=80'
		),
	);

	foreach ( $campaigns as $id => $data ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $data['default'],
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array(
			'label'    => $data['label'],
			'section'  => 'tatkhalsa_campaigns',
			'settings' => $id,
		) ) );
	}

	// Project Images Section
	$wp_customize->add_section( 'tatkhalsa_projects', array(
		'title'    => __( 'Project Images', 'tatkhalsa-theme' ),
		'priority' => 131,
	) );

	// Settings & Controls for Projects
	$projects = array(
		'tatkhalsa_charity_support_img' => array(
			'label' => __( 'General Charity Support Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&auto=format&fit=crop'
		),
		'tatkhalsa_blood_contribution_img' => array(
			'label' => __( 'Sikh Blood Contribution Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?q=80&w=800&auto=format&fit=crop'
		),
		'tatkhalsa_punjab_flood_img' => array(
			'label' => __( 'Punjab Flood Response Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?q=80&w=800&auto=format&fit=crop'
		),
		'tatkhalsa_sikh_heritage_img' => array(
			'label' => __( 'Preserving Sikh Heritage Image', 'tatkhalsa-theme' ),
			'default' => 'https://upload.wikimedia.org/wikipedia/commons/e/ee/Group_of_Nihang_Singhs.jpg'
		),
		'tatkhalsa_kabaddi_athletic_img' => array(
			'label' => __( 'Kabaddi and Athletic Support Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=800&auto=format&fit=crop'
		),
		'tatkhalsa_tree_planting_img' => array(
			'label' => __( 'Tree Planting Stewardship Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=800&auto=format&fit=crop'
		),
	);

	foreach ( $projects as $id => $data ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $data['default'],
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array(
			'label'    => $data['label'],
			'section'  => 'tatkhalsa_projects',
			'settings' => $id,
		) ) );
	}
	// Other Page Images Section
	$wp_customize->add_section( 'tatkhalsa_other_pages', array(
		'title'    => __( 'Super Background Images', 'tatkhalsa-theme' ),
		'priority' => 132,
	) );

	$other_pages = array(
		'tatkhalsa_default_hero_bg' => array(
			'label'   => __( 'Default Site-wide Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1543332143-4e8c27e3256f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
		),
		'tatkhalsa_home_hero_img' => array(
			'label'   => __( 'Home Page Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1543332143-4e8c27e3256f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
		),
		'tatkhalsa_about_header_hero_img' => array(
			'label'   => __( 'About Page Header Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg',
		),
		'tatkhalsa_about_hero_img' => array(
			'label'   => __( 'About Page Body/Langar Section Bg', 'tatkhalsa-theme' ),
			'default' => 'https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg',
		),
		'tatkhalsa_blog_hero_img' => array(
			'label'   => __( 'Insights & Blog Header Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=1920&h=1080&q=80',
		),
		'tatkhalsa_blood_donors_bg_img' => array(
			'label'   => __( 'Blood On Call (Donors) Super Background', 'tatkhalsa-theme' ),
			'default' => 'https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg',
		),
		'tatkhalsa_projects_hero_img' => array(
			'label'   => __( 'Seva Projects Header Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://upload.wikimedia.org/wikipedia/commons/d/de/Sikhs_gathered_at_Hola_Mohalla_Holi_festival_in_Anandpur_Sahib.jpg',
		),
		'tatkhalsa_volunteer_hero_img' => array(
			'label'   => __( 'Become a Sevadar Header Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=1920&q=80',
		),
		'tatkhalsa_privacy_hero_img' => array(
			'label'   => __( 'Privacy Policy Header Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1920&h=1080&q=80',
		),
		'tatkhalsa_terms_hero_img' => array(
			'label'   => __( 'Terms & Conditions Header Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1920&h=1080&q=80',
		),
		'tatkhalsa_error404_hero_img' => array(
			'label'   => __( '404 Page Header Hero Background', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&h=1080&q=80',
		),
	);

	foreach ( $other_pages as $id => $data ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $data['default'],
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array(
			'label'    => $data['label'],
			'section'  => 'tatkhalsa_other_pages',
			'settings' => $id,
		) ) );
	}

	// Blood On Call Tutorial Video Upload / URL
	$wp_customize->add_setting( 'tatkhalsa_blood_video_url', array(
		'default'           => 'https://assets.mixkit.co/videos/preview/mixkit-hand-holding-a-smartphone-with-a-yellow-background-41712-large.mp4',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'tatkhalsa_blood_video_url', array(
		'label'       => __( 'Blood On Call Tutorial Video (Upload MP4 or paste URL)', 'tatkhalsa-theme' ),
		'section'     => 'tatkhalsa_other_pages',
		'settings'    => 'tatkhalsa_blood_video_url',
	) ) );
	// Blog Images Section
	$wp_customize->add_section( 'tatkhalsa_blog', array(
		'title'    => __( 'Blog Images', 'tatkhalsa-theme' ),
		'priority' => 133,
	) );

	$blog_images = array(
		'tatkhalsa_blog_img_1' => array(
			'label' => __( 'Blog Post 1 Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1544027993-37dbfe43562a?auto=format&fit=crop&q=80&w=600'
		),
		'tatkhalsa_blog_img_2' => array(
			'label' => __( 'Blog Post 2 Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=600'
		),
		'tatkhalsa_blog_img_3' => array(
			'label' => __( 'Blog Post 3 Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=600'
		),
		'tatkhalsa_blog_img_4' => array(
			'label' => __( 'Blog Post 4 Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=600'
		),
	);

	foreach ( $blog_images as $id => $data ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $data['default'],
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array(
			'label'    => $data['label'],
			'section'  => 'tatkhalsa_blog',
			'settings' => $id,
		) ) );
	}
	// WhatsApp Meta Cloud API Alert Settings
	$wp_customize->add_section( 'tatkhalsa_whatsapp', array(
		'title'       => __( 'WhatsApp Cloud API Alerts', 'tatkhalsa-theme' ),
		'description' => __( 'Configure Meta WhatsApp Cloud API for emergency alerts and volunteer notifications.', 'tatkhalsa-theme' ),
		'priority'    => 140,
	) );

	$whatsapp_settings = array(
		'tatkhalsa_whatsapp_access_token'    => 'System User Access Token',
		'tatkhalsa_whatsapp_phone_number_id' => 'Phone Number ID',
		'tatkhalsa_whatsapp_to_number'       => 'Admin Receiving Number (e.g. 919876543210)'
	);

	foreach ( $whatsapp_settings as $id => $label ) {
		$wp_customize->add_setting( $id, array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $label,
			'section' => 'tatkhalsa_whatsapp',
			'type'    => ( $id === 'tatkhalsa_whatsapp_access_token' ) ? 'password' : 'text',
		) );
	}
}
add_action( 'customize_register', 'tatkhalsa_customize_register' );

/**
 * Get Customizer background image with default site-wide fallback and hardcoded fallback
 */
function tatkhalsa_get_bg_with_fallback( $mod_name, $hardcoded_fallback ) {
	$val = get_theme_mod( $mod_name );
	if ( empty( $val ) ) {
		$val = get_theme_mod( 'tatkhalsa_default_hero_bg' );
	}
	if ( empty( $val ) ) {
		$val = $hardcoded_fallback;
	}
	return $val;
}

/**
 * Output Customizer CSS for Home Hero Image
 */
function tatkhalsa_customizer_css() {
	$default_bg          = 'https://images.unsplash.com/photo-1543332143-4e8c27e3256f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80';
	$home_hero_img       = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_home_hero_img', $default_bg );
	$about_header_hero   = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_about_header_hero_img', 'https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg' );
	$about_body_bg       = get_theme_mod( 'tatkhalsa_about_hero_img', 'https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg' );
	$blog_hero_img       = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_blog_hero_img', 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=1920&h=1080&q=80' );
	$blood_donors_bg     = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_blood_donors_bg_img', 'https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg' );
	$projects_hero_img   = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_projects_hero_img', 'https://upload.wikimedia.org/wikipedia/commons/d/de/Sikhs_gathered_at_Hola_Mohalla_Holi_festival_in_Anandpur_Sahib.jpg' );
	$volunteer_hero_img  = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_volunteer_hero_img', 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=1920&h=1080&q=80' );
	$privacy_hero_img    = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_privacy_hero_img', 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1920&h=1080&q=80' );
	$terms_hero_img      = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_terms_hero_img', 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1920&h=1080&q=80' );
	$error404_hero_img   = tatkhalsa_get_bg_with_fallback( 'tatkhalsa_error404_hero_img', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&h=1080&q=80' );
	?>
	<style type="text/css">
		/* Centralized Super Hero & Background Customizer Overrides */

		/* 1. Home Page Hero */
		.home .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $home_hero_img ); ?>") center/cover !important;
		}
		[data-theme="light"] .home .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $home_hero_img ); ?>") center/cover !important;
		}

		/* 2. About Page Header Hero */
		.page-template-template-about .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $about_header_hero ); ?>") center/cover !important;
		}
		[data-theme="light"] .page-template-template-about .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $about_header_hero ); ?>") center/cover !important;
		}

		/* 3. About Page Body Section */
		#about {
			background-image: url("<?php echo esc_url( $about_body_bg ); ?>") !important;
		}

		/* 4. Blog / Insights Page Header Hero */
		.page-template-template-blog .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $blog_hero_img ); ?>") center/cover !important;
		}
		[data-theme="light"] .page-template-template-blog .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $blog_hero_img ); ?>") center/cover !important;
		}

		/* 5. Blood On Call (Donors) Super Background */
		.blood-donors-page {
			background-image: linear-gradient(135deg, rgba(4, 9, 20, 0.88), rgba(13, 27, 42, 0.92)), url("<?php echo esc_url( $blood_donors_bg ); ?>") !important;
		}
		[data-theme="light"] .blood-donors-page {
			background-image: linear-gradient(135deg, rgba(224, 242, 254, 0.85), rgba(186, 230, 253, 0.85)), url("<?php echo esc_url( $blood_donors_bg ); ?>") !important;
		}

		/* 6. Seva Projects Header Hero */
		.page-template-template-projects .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $projects_hero_img ); ?>") center/cover !important;
		}
		[data-theme="light"] .page-template-template-projects .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $projects_hero_img ); ?>") center/cover !important;
		}

		/* 7. Become a Sevadar Header Hero */
		.page-template-template-volunteer .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $volunteer_hero_img ); ?>") center/cover !important;
		}
		[data-theme="light"] .page-template-template-volunteer .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $volunteer_hero_img ); ?>") center/cover !important;
		}

		/* 8. Privacy Policy Header Hero */
		.page-template-template-privacy .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $privacy_hero_img ); ?>") center/cover !important;
		}
		[data-theme="light"] .page-template-template-privacy .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $privacy_hero_img ); ?>") center/cover !important;
		}

		/* 9. Terms & Conditions Header Hero */
		.page-template-template-terms .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $terms_hero_img ); ?>") center/cover !important;
		}
		[data-theme="light"] .page-template-template-terms .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $terms_hero_img ); ?>") center/cover !important;
		}

		/* 10. 404 Page Header Hero */
		.error404 .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $error404_hero_img ); ?>") center/cover !important;
		}
		[data-theme="light"] .error404 .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $error404_hero_img ); ?>") center/cover !important;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'tatkhalsa_customizer_css' );

/**
 * Send WhatsApp Alert via Meta Cloud API
 */
function tatkhalsa_send_whatsapp_alert( $message_body ) {
	$access_token = get_theme_mod( 'tatkhalsa_whatsapp_access_token' );
	$phone_id     = get_theme_mod( 'tatkhalsa_whatsapp_phone_number_id' );
	$to           = get_theme_mod( 'tatkhalsa_whatsapp_to_number' );

	if ( empty($access_token) || empty($phone_id) || empty($to) ) {
		return false; // Not fully configured
	}

	// Make sure the number doesn't have a + sign
	$to = ltrim($to, '+');

	$api_url = 'https://graph.facebook.com/v19.0/' . $phone_id . '/messages';

	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $access_token,
			'Content-Type'  => 'application/json'
		),
		'body' => wp_json_encode( array(
			'messaging_product' => 'whatsapp',
			'recipient_type'    => 'individual',
			'to'                => $to,
			'type'              => 'text',
			'text'              => array(
				'preview_url' => false,
				'body'        => $message_body
			)
		) )
	);

	$response = wp_remote_post( $api_url, $args );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$response_code = wp_remote_retrieve_response_code( $response );
	return ( $response_code >= 200 && $response_code < 300 );
}

/**
 * Gurbani Search Shortcode via GurbaniNow API
 */
function tatkhalsa_gurbani_search_shortcode() {
    ob_start();
    ?>
    <div class="gurbani-search-container">
        <form id="gurbani-search-form" class="gurbani-search-form">
            <input type="text" id="gurbani-search-input" placeholder="Search Gurbani (e.g. 'm m' or 'tu prabh')" required />
            <button type="submit" class="btn gurbani-search-btn">Search</button>
        </form>
        <div id="gurbani-search-results" class="gurbani-search-results"></div>
    </div>
    
    <style>
        .gurbani-search-container {
            background: var(--bg-shade-1);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin: 20px 0;
            border: 1px solid rgba(212, 175, 55, 0.2);
            width: 100%;
        }
        .gurbani-search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .gurbani-search-form input {
            flex: 1;
            padding: 14px 20px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: var(--cream);
            font-size: 1.1rem;
            outline: none;
            transition: all 0.3s ease;
        }
        .gurbani-search-form input:focus {
            border-color: var(--secondary);
            background: rgba(255, 255, 255, 0.1);
        }
        [data-theme="light"] .gurbani-search-form input {
            background: rgba(0,0,0,0.05);
            color: var(--text-dark);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        [data-theme="light"] .gurbani-search-form input:focus {
            border-color: var(--secondary);
            background: #ffffff;
        }
        .gurbani-search-btn {
            background: var(--secondary);
            color: #fff;
            padding: 0 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, background 0.3s;
        }
        .gurbani-search-btn:hover {
            transform: translateY(-2px);
            background: #e5bf42;
        }
        .gurbani-search-results {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-height: 600px;
            overflow-y: auto;
            padding-right: 10px;
        }
        .shabad-card {
            background: rgba(0,0,0,0.2);
            border-left: 4px solid var(--secondary);
            padding: 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .shabad-card:hover {
            background: rgba(255,255,255,0.05);
        }
        [data-theme="light"] .shabad-card {
            background: rgba(255,255,255,0.6);
            border-left: 4px solid var(--secondary);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        [data-theme="light"] .shabad-card:hover {
            background: #ffffff;
        }
        .shabad-gurmukhi {
            font-size: 1.8rem;
            color: var(--secondary);
            margin-bottom: 15px;
            line-height: 1.4;
            font-weight: 500;
        }
        .shabad-english {
            font-size: 1.15rem;
            color: var(--cream);
            margin-bottom: 5px;
        }
        [data-theme="light"] .shabad-english {
            color: var(--text-dark);
        }
        .shabad-transliteration {
             font-size: 1rem;
             color: var(--text-light);
             margin-bottom: 15px;
             font-style: italic;
        }
        [data-theme="light"] .shabad-transliteration {
             color: rgba(0,0,0,0.6);
        }
        .shabad-meta {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
        }
        [data-theme="light"] .shabad-meta {
            color: rgba(0,0,0,0.5);
        }
        .loading-shabads {
            text-align: center;
            color: var(--secondary);
            padding: 30px;
            font-size: 1.1rem;
        }
        /* Mobile */
        @media(max-width: 768px) {
            .gurbani-search-form {
                flex-direction: column;
            }
            .gurbani-search-btn {
                padding: 14px;
            }
            .shabad-gurmukhi {
                font-size: 1.5rem;
            }
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('gurbani-search-form');
            if(!form) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const input = document.getElementById('gurbani-search-input').value.trim();
                if(!input) return;
                
                const resultsDiv = document.getElementById('gurbani-search-results');
                resultsDiv.innerHTML = '<div class="loading-shabads">Searching Gurbani... <br><small style="opacity:0.7;margin-top:10px;display:block">Fetching from BaniDB via GurbaniNow API</small></div>';
                
                try {
                    // searchtype 1 resolves to 'First letter anywhere' 
                    // Most intuitive for generalized searches (e.g., 't p' -> tu prabh)
                    const res = await fetch(`https://api.gurbaninow.com/v2/search/${encodeURIComponent(input)}/?searchtype=1`);
                    const data = await res.json();
                    
                    if(!data || data.error || !data.shabads || data.shabads.length === 0) {
                        resultsDiv.innerHTML = '<div class="loading-shabads">No shabads found for "'+input+'".<br>Try typing the first letters of each word in English (e.g. "m m" for mere man).</div>';
                        return;
                    }
                    
                    let html = '';
                    data.shabads.forEach(item => {
                        const line = item.shabad;
                        const gurmukhi = line.gurmukhi.unicode;
                        const englishTranslation = line.translation.english.default;
                        const transliteration = line.transliteration.english.text;
                        const writer = line.writer.english;
                        const ang = line.pageno;
                        
                        html += `
                            <div class="shabad-card">
                                <div class="shabad-gurmukhi">${gurmukhi}</div>
                                <div class="shabad-english">${englishTranslation}</div>
                                <div class="shabad-transliteration">${transliteration}</div>
                                <div class="shabad-meta">
                                    <span>${writer}</span>
                                    <span>Ang ${ang}</span>
                                </div>
                            </div>
                        `;
                    });
                    
                    resultsDiv.innerHTML = html;
                } catch(e) {
                    resultsDiv.innerHTML = '<div class="loading-shabads" style="color:#ff6b6b">Error connecting to Gurbani API. Please check your internet connection or try again later.</div>';
                }
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('gurbani_search', 'tatkhalsa_gurbani_search_shortcode');

/**
 * Register Blood Request Custom Post Type
 */
function tatkhalsa_register_blood_request_cpt() {
	$labels = array(
		'name'               => _x( 'Blood Requests', 'post type general name', 'tatkhalsa-theme' ),
		'singular_name'      => _x( 'Blood Request', 'post type singular name', 'tatkhalsa-theme' ),
		'menu_name'          => _x( 'Blood Requests', 'admin menu', 'tatkhalsa-theme' ),
		'all_items'          => __( 'All Blood Requests', 'tatkhalsa-theme' ),
		'view_item'          => __( 'View Blood Request', 'tatkhalsa-theme' ),
		'search_items'       => __( 'Search Blood Requests', 'tatkhalsa-theme' ),
		'not_found'          => __( 'No blood requests found.', 'tatkhalsa-theme' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-warning',
		'supports'           => array( 'title' )
	);

	register_post_type( 'blood_request', $args );
}
add_action( 'init', 'tatkhalsa_register_blood_request_cpt' );

/**
 * Prune blood network IP addresses older than 30 days - DISABLED by Admin Request
 */
function tatkhalsa_prune_expired_ips() {
	// Auto deletion of IP address after 30 days has been removed at administrator request.
}
// add_action( 'wp_loaded', 'tatkhalsa_prune_expired_ips' );

/**
 * Add WP-Admin Master Data Tab
 */
function tatkhalsa_add_blood_master_data_menu() {
	add_menu_page(
		'Blood Master Data',
		'Blood Master Data',
		'manage_options',
		'blood-master-data',
		'tatkhalsa_render_blood_master_data_page',
		'dashicons-clipboard',
		25
	);
}
add_action( 'admin_menu', 'tatkhalsa_add_blood_master_data_menu' );

function tatkhalsa_render_blood_master_data_page() {
	global $wpdb;

	// Automatically upgrade database with missing status/accepted_by_donor_id columns if the custom table exists in live WP environment
	$table_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_requests'" );
	if ( $table_exists ) {
		$col_status_exists = $wpdb->get_results( "SHOW COLUMNS FROM `wp_blood_requests` LIKE 'status'" );
		if ( empty( $col_status_exists ) ) {
			$wpdb->query( "ALTER TABLE `wp_blood_requests` ADD `status` VARCHAR(50) DEFAULT 'pending'" );
		}
		$col_donor_exists = $wpdb->get_results( "SHOW COLUMNS FROM `wp_blood_requests` LIKE 'accepted_by_donor_id'" );
		if ( empty( $col_donor_exists ) ) {
			$wpdb->query( "ALTER TABLE `wp_blood_requests` ADD `accepted_by_donor_id` INT DEFAULT NULL" );
		}
	}

	// Handle backup EXPORT request
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'export_backup' ) {
		if ( current_user_can( 'manage_options' ) ) {
			if ( ob_get_length() ) {
				ob_clean();
			}
			
			// 1. Fetch Donors
			$donors_posts = get_posts( array(
				'post_type'      => 'blood_donor',
				'posts_per_page' => -1,
			) );
			$exported_donors = array();
			foreach ( $donors_posts as $post ) {
				$p_id = $post->ID;
				$exported_donors[] = array(
					'id'                  => $p_id,
					'name'                => get_post_meta( $p_id, 'donor_name', true ),
					'blood_group'         => get_post_meta( $p_id, 'blood_group', true ),
					'donor_email'         => get_post_meta( $p_id, 'donor_email', true ),
					'contact_details'     => get_post_meta( $p_id, 'contact_details', true ),
					'country'             => get_post_meta( $p_id, 'country', true ),
					'state'               => get_post_meta( $p_id, 'state', true ),
					'district'            => get_post_meta( $p_id, 'district', true ),
					'address'             => get_post_meta( $p_id, 'address', true ),
					'map_location'        => get_post_meta( $p_id, 'map_location', true ),
					'availability_status' => get_post_meta( $p_id, 'availability_status', true ),
					'donor_ip'            => get_post_meta( $p_id, 'donor_ip', true ),
					'registration_time'   => get_post_meta( $p_id, 'registration_time', true ),
				);
			}
			
			$table_don_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_donors'" );
			if ( $table_don_exists ) {
				$db_donors = $wpdb->get_results( "SELECT * FROM wp_blood_donors", ARRAY_A );
				if ( ! empty( $db_donors ) ) {
					foreach ( $db_donors as $row ) {
						$exists = false;
						foreach ( $exported_donors as $ed ) {
							if ( (isset($ed['donor_email']) && $ed['donor_email'] === $row['donor_email']) || (isset($ed['contact_details']) && $ed['contact_details'] === $row['contact_details']) ) {
								$exists = true;
								break;
							}
						}
						if ( ! $exists ) {
							$exported_donors[] = $row;
						}
					}
				}
			}

			// 2. Fetch Requests
			$requests_posts = get_posts( array(
				'post_type'      => 'blood_request',
				'posts_per_page' => -1,
			) );
			$exported_requests = array();
			foreach ( $requests_posts as $post ) {
				$p_id = $post->ID;
				$exported_requests[] = array(
					'id'                        => $p_id,
					'patient_name'              => get_post_meta( $p_id, 'patient_name', true ),
					'blood_group'               => get_post_meta( $p_id, 'blood_group', true ),
					'country'                   => get_post_meta( $p_id, 'country', true ),
					'state'                     => get_post_meta( $p_id, 'state', true ),
					'district'                  => get_post_meta( $p_id, 'district', true ),
					'patient_location'          => get_post_meta( $p_id, 'patient_location', true ),
					'contact_details'           => get_post_meta( $p_id, 'contact_details', true ),
					'hospital_name'             => get_post_meta( $p_id, 'hospital_name', true ),
					'units_required'            => get_post_meta( $p_id, 'units_required', true ),
					'urgency'                   => get_post_meta( $p_id, 'urgency', true ),
					'additional_info'           => get_post_meta( $p_id, 'additional_info', true ),
					'request_ip'                => get_post_meta( $p_id, 'request_ip', true ),
					'request_time'              => get_post_meta( $p_id, 'request_time', true ),
					'status'                    => get_post_meta( $p_id, 'status', true ) ? get_post_meta( $p_id, 'status', true ) : 'pending',
					'accepted_by_donor_id'      => get_post_meta( $p_id, 'accepted_by_donor_id', true ),
					'doctor_slip_url'           => get_post_meta( $p_id, 'doctor_slip_url', true ),
				);
			}

			$table_req_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_requests'" );
			if ( $table_req_exists ) {
				$db_reqs = $wpdb->get_results( "SELECT * FROM wp_blood_requests", ARRAY_A );
				if ( ! empty( $db_reqs ) ) {
					foreach ( $db_reqs as $row ) {
						$exists = false;
						foreach ( $exported_requests as $er ) {
							if ( $er['id'] == $row['id'] || (isset($er['patient_name']) && $er['patient_name'] === $row['patient_name'] && isset($er['contact_details']) && $er['contact_details'] === $row['contact_details']) ) {
								$exists = true;
								break;
							}
						}
						if ( ! $exists ) {
							$exported_requests[] = $row;
						}
					}
				}
			}

			$backupObj = array(
				'version'     => '1.0',
				'exported_at' => current_time( 'mysql' ),
				'donors'      => $exported_donors,
				'requests'    => $exported_requests,
			);

			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename="tatkhalsa-blood-data-backup-' . date( 'Y-m-d' ) . '.json"' );
			echo json_encode( $backupObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			exit();
		}
	}

	// Handle backup IMPORT request
	if ( isset( $_POST['import_backup_submit'] ) && current_user_can( 'manage_options' ) ) {
		if ( ! empty( $_FILES['import_backup_file']['tmp_name'] ) ) {
			$file_path = $_FILES['import_backup_file']['tmp_name'];
			$raw_content = file_get_contents( $file_path );
			$imported_data = json_decode( $raw_content, true );

			if ( ! empty( $imported_data ) && ( isset( $imported_data['donors'] ) || isset( $imported_data['requests'] ) ) ) {
				$donors_count = 0;
				$requests_count = 0;

				// Import Donors
				if ( isset( $imported_data['donors'] ) && is_array( $imported_data['donors'] ) ) {
					foreach ( $imported_data['donors'] as $donor ) {
						$email = isset( $donor['donor_email'] ) ? sanitize_email( $donor['donor_email'] ) : (isset($donor['email']) ? sanitize_email($donor['email']) : '');
						$contact = isset( $donor['contact_details'] ) ? sanitize_text_field( $donor['contact_details'] ) : (isset($donor['contact']) ? sanitize_text_field($donor['contact']) : '');
						$name = isset( $donor['donor_name'] ) ? sanitize_text_field( $donor['donor_name'] ) : (isset($donor['name']) ? sanitize_text_field($donor['name']) : 'Anonymous');
						$group = isset( $donor['blood_group'] ) ? sanitize_text_field( $donor['blood_group'] ) : (isset($donor['bloodGroup']) ? sanitize_text_field($donor['bloodGroup']) : 'O+');
						
						$existing_donor = null;
						if ( ! empty( $email ) ) {
							$existing_query = get_posts( array(
								'post_type'      => 'blood_donor',
								'meta_key'       => 'donor_email',
								'meta_value'     => $email,
								'posts_per_page' => 1,
							) );
							if ( ! empty( $existing_query ) ) {
								$existing_donor = $existing_query[0];
							}
						}
						if ( empty( $existing_donor ) && ! empty( $contact ) ) {
							$existing_query = get_posts( array(
								'post_type'      => 'blood_donor',
								'meta_key'       => 'contact_details',
								'meta_value'     => $contact,
								'posts_per_page' => 1,
							) );
							if ( ! empty( $existing_query ) ) {
								$existing_donor = $existing_query[0];
							}
						}

						if ( ! empty( $existing_donor ) ) {
							$post_id = $existing_donor->ID;
						} else {
							$post_id = wp_insert_post( array(
								'post_title'  => $name . ' - ' . $group,
								'post_type'   => 'blood_donor',
								'post_status' => 'publish',
							) );
						}

						if ( $post_id ) {
							update_post_meta( $post_id, 'donor_name', $name );
							update_post_meta( $post_id, 'blood_group', $group );
							update_post_meta( $post_id, 'donor_email', $email );
							update_post_meta( $post_id, 'contact_details', $contact );
							
							$country = isset( $donor['country'] ) ? sanitize_text_field( $donor['country'] ) : '';
							$state = isset( $donor['state'] ) ? sanitize_text_field( $donor['state'] ) : '';
							$district = isset( $donor['district'] ) ? sanitize_text_field( $donor['district'] ) : '';
							$address = isset( $donor['address'] ) ? sanitize_text_field( $donor['address'] ) : '';
							
							update_post_meta( $post_id, 'country', $country );
							update_post_meta( $post_id, 'state', $state );
							update_post_meta( $post_id, 'district', $district );
							update_post_meta( $post_id, 'address', $address );
							
							$map_loc = isset( $donor['map_location'] ) ? sanitize_text_field( $donor['map_location'] ) : '';
							update_post_meta( $post_id, 'map_location', $map_loc );
							
							$avl_status = isset( $donor['availability_status'] ) ? sanitize_text_field( $donor['availability_status'] ) : '';
							if ( empty($avl_status) && isset($donor['availabilityStatus']) ) { $avl_status = sanitize_text_field($donor['availabilityStatus']); }
							update_post_meta( $post_id, 'availability_status', $avl_status ? $avl_status : 'Available Now' );
							
							$donor_ip = isset( $donor['donor_ip'] ) ? sanitize_text_field( $donor['donor_ip'] ) : (isset($donor['ip']) ? sanitize_text_field($donor['ip']) : '127.0.0.1');
							update_post_meta( $post_id, 'donor_ip', $donor_ip );
							
							$reg_time = isset( $donor['registration_time'] ) ? sanitize_text_field( $donor['registration_time'] ) : '';
							if (empty($reg_time) && isset($donor['timestamp'])) { $reg_time = date('Y-m-d H:i:s', $donor['timestamp']/1000); }
							update_post_meta( $post_id, 'registration_time', $reg_time ? $reg_time : current_time( 'mysql' ) );

							$table_don_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_donors'" );
							if ( $table_don_exists ) {
								$table_record = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM wp_blood_donors WHERE id = %d", $post_id ) );
								$db_donor_data = array(
									'id'                  => $post_id,
									'donor_name'          => $name,
									'blood_group'         => $group,
									'donor_email'         => $email,
									'contact_details'     => $contact,
									'country'             => $country,
									'state'               => $state,
									'district'            => $district,
									'address'             => $address,
									'map_location'        => $map_loc,
									'availability_status' => $avl_status ? $avl_status : 'Available Now',
									'donor_ip'            => $donor_ip,
									'registration_time'   => $reg_time ? $reg_time : current_time( 'mysql' ),
								);
								if ( $table_record ) {
									$wpdb->update( 'wp_blood_donors', $db_donor_data, array( 'id' => $post_id ) );
								} else {
									$wpdb->insert( 'wp_blood_donors', $db_donor_data );
								}
							}
							$donors_count++;
						}
					}
				}

				// Import Requests
				if ( isset( $imported_data['requests'] ) && is_array( $imported_data['requests'] ) ) {
					foreach ( $imported_data['requests'] as $req ) {
						$patient_name = isset( $req['patient_name'] ) ? sanitize_text_field( $req['patient_name'] ) : (isset($req['patientName']) ? sanitize_text_field($req['patientName']) : 'Anonymous');
						$group = isset( $req['blood_group'] ) ? sanitize_text_field( $req['blood_group'] ) : (isset($req['bloodGroup']) ? sanitize_text_field($req['bloodGroup']) : 'O+');
						$contact = isset( $req['contact_details'] ) ? sanitize_text_field( $req['contact_details'] ) : (isset($req['contactDetails']) ? sanitize_text_field($req['contactDetails']) : '');
						
						$existing_req = null;
						if ( ! empty( $contact ) ) {
							$existing_query = get_posts( array(
								'post_type'      => 'blood_request',
								'meta_key'       => 'patient_name',
								'meta_value'     => $patient_name,
								'posts_per_page' => 1,
							) );
							if ( ! empty( $existing_query ) ) {
								$existing_req = $existing_query[0];
							}
						}

						if ( ! empty( $existing_req ) ) {
							$post_id = $existing_req->ID;
						} else {
							$post_id = wp_insert_post( array(
								'post_title'  => 'Blood Request - ' . $group . ' - ' . $patient_name,
								'post_type'   => 'blood_request',
								'post_status' => 'publish',
							) );
						}

						if ( $post_id ) {
							update_post_meta( $post_id, 'patient_name', $patient_name );
							update_post_meta( $post_id, 'blood_group', $group );
							update_post_meta( $post_id, 'contact_details', $contact );
							
							$hospital = isset( $req['hospital_name'] ) ? sanitize_text_field( $req['hospital_name'] ) : (isset($req['hospitalName']) ? sanitize_text_field($req['hospitalName']) : '');
							update_post_meta( $post_id, 'hospital_name', $hospital );
							
							$loc = isset( $req['patient_location'] ) ? sanitize_text_field( $req['patient_location'] ) : '';
							update_post_meta( $post_id, 'patient_location', $loc );
							
							$units = isset( $req['units_required'] ) ? sanitize_text_field( $req['units_required'] ) : (isset($req['unitsRequired']) ? sanitize_text_field($req['unitsRequired']) : '1');
							update_post_meta( $post_id, 'units_required', $units );
							
							$urg = isset( $req['urgency'] ) ? sanitize_text_field( $req['urgency'] ) : '';
							update_post_meta( $post_id, 'urgency', $urg ? $urg : 'Urgent' );
							
							$info = isset( $req['additional_info'] ) ? sanitize_textarea_field( $req['additional_info'] ) : '';
							update_post_meta( $post_id, 'additional_info', $info );
							
							$req_ip = isset( $req['request_ip'] ) ? sanitize_text_field( $req['request_ip'] ) : (isset($req['ip']) ? sanitize_text_field($req['ip']) : '127.0.0.1');
							update_post_meta( $post_id, 'request_ip', $req_ip );
							
							$req_time = isset( $req['request_time'] ) ? sanitize_text_field( $req['request_time'] ) : '';
							if (empty($req_time) && isset($req['timestamp'])) { $req_time = date('Y-m-d H:i:s', $req['timestamp']/1000); }
							update_post_meta( $post_id, 'request_time', $req_time ? $req_time : current_time( 'mysql' ) );
							
							$status = isset( $req['status'] ) ? sanitize_text_field( $req['status'] ) : 'pending';
							update_post_meta( $post_id, 'status', $status );
							
							$acc_id = isset( $req['accepted_by_donor_id'] ) ? intval( $req['accepted_by_donor_id'] ) : null;
							update_post_meta( $post_id, 'accepted_by_donor_id', $acc_id );
							
							$slip_url = isset( $req['doctor_slip_url'] ) ? esc_url_raw( $req['doctor_slip_url'] ) : '';
							update_post_meta( $post_id, 'doctor_slip_url', $slip_url );

							$table_req_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_requests'" );
							if ( $table_req_exists ) {
								$table_record = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM wp_blood_requests WHERE id = %d", $post_id ) );
								$db_req_data = array(
									'id'                   => $post_id,
									'patient_name'         => $patient_name,
									'blood_group'          => $group,
									'patient_location'     => $loc,
									'contact_details'      => $contact,
									'hospital_name'        => $hospital,
									'units_required'       => $units,
									'urgency'              => $urg ? $urg : 'Urgent',
									'doctor_slip_url'      => $slip_url,
									'request_ip'           => $req_ip,
									'request_time'         => $req_time ? $req_time : current_time( 'mysql' ),
									'status'               => $status,
									'accepted_by_donor_id' => $acc_id,
								);
								if ( $table_record ) {
									$wpdb->update( 'wp_blood_requests', $db_req_data, array( 'id' => $post_id ) );
								} else {
									$wpdb->insert( 'wp_blood_requests', $db_req_data );
								}
							}
							$requests_count++;
						}
					}
				}

				echo '<div class="notice notice-success is-dismissible" style="padding: 12px; background: #d4edda; color: #155724; border-left: 4px solid #28a745; margin: 15px 0; border-radius: 4px;"><p style="margin: 0; font-weight: bold;">✓ Data backup imported successfully! Loaded ' . $donors_count . ' donors and ' . $requests_count . ' emergency requests safely.</p></div>';
			} else {
				echo '<div class="notice notice-error is-dismissible" style="padding: 12px; background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; margin: 15px 0; border-radius: 4px;"><p style="margin: 0; font-weight: bold;">✕ Failed to process backup format. Verify JSON structures.</p></div>';
			}
		}
	}

	// Handle MARK AS FULFILLED query or localized postback
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'fulfill' && isset( $_GET['id'] ) ) {
		if ( current_user_can( 'manage_options' ) ) {
			$request_id = intval( $_GET['id'] );
			
			// Try updating direct MySQL table if it exists
			$table_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_requests'" );
			if ( $table_exists ) {
				$wpdb->update(
					'wp_blood_requests',
					array( 'status' => 'fulfilled' ),
					array( 'id' => $request_id ),
					array( '%s' ),
					array( '%d' )
				);
			} else {
				// Local dev environment fallback (WordPress custom post meta)
				update_post_meta( $request_id, 'status', 'fulfilled' );
			}
			
			echo '<div class="notice notice-success is-dismissible" style="padding: 12px; background: #d4edda; color: #155724; border-left: 4px solid #00875a; margin: 15px 0; border-radius: 4px;"><p style="margin: 0; font-weight: bold;">✓ Status successfully marked as <strong>Fulfilled</strong> for blood request #' . $request_id . '!</p></div>';
		}
	}

	if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['id'] ) ) {
		if ( current_user_can( 'manage_options' ) ) {
			$post_id = intval( $_GET['id'] );
			$deleted_from_db = false;

			$table_req_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_requests'" );
			$table_don_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_donors'" );
			
			if ( $table_req_exists ) {
				$deleted_req = $wpdb->delete( 'wp_blood_requests', array( 'id' => $post_id ), array( '%d' ) );
				if ( $deleted_req ) {
					$deleted_from_db = true;
				}
			}
			if ( $table_don_exists ) {
				$deleted_don = $wpdb->delete( 'wp_blood_donors', array( 'id' => $post_id ), array( '%d' ) );
				if ( $deleted_don ) {
					$deleted_from_db = true;
				}
			}

			$post_type = get_post_type( $post_id );
			if ( $post_type === 'blood_donor' || $post_type === 'blood_request' ) {
				wp_delete_post( $post_id, true );
				$deleted_from_db = true;
			}

			if ( $deleted_from_db ) {
				echo '<div class="notice notice-success is-dismissible" style="padding: 12px; background: #d4edda; color: #155724; border-left: 4px solid #28a745; margin: 15px 0; border-radius: 4px;"><p style="margin: 0; font-weight: bold;">✓ Record #' . $post_id . ' was permanently deleted from the WordPress Master Data secure database.</p></div>';
			}
		}
	}

	if ( isset( $_POST['bulk_action'] ) && $_POST['bulk_action'] === 'bulk_delete' && isset( $_POST['bulk_ids'] ) && is_array( $_POST['bulk_ids'] ) ) {
		if ( current_user_can( 'manage_options' ) ) {
			$deleted_count = 0;
			$table_req_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_requests'" );
			$table_don_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_donors'" );

			foreach ( $_POST['bulk_ids'] as $id ) {
				$post_id = intval( $id );
				$deleted_this = false;

				if ( $table_req_exists ) {
					$deleted_req = $wpdb->delete( 'wp_blood_requests', array( 'id' => $post_id ), array( '%d' ) );
					if ( $deleted_req ) {
						$deleted_this = true;
					}
				}
				if ( $table_don_exists ) {
					$deleted_don = $wpdb->delete( 'wp_blood_donors', array( 'id' => $post_id ), array( '%d' ) );
					if ( $deleted_don ) {
						$deleted_this = true;
					}
				}

				$post_type = get_post_type( $post_id );
				if ( $post_type === 'blood_donor' || $post_type === 'blood_request' ) {
					wp_delete_post( $post_id, true );
					$deleted_this = true;
				}

				if ( $deleted_this ) {
					$deleted_count++;
				}
			}
			if ( $deleted_count > 0 ) {
				echo '<div class="notice notice-success is-dismissible" style="padding: 12px; background: #d4edda; color: #155724; border-left: 4px solid #28a745; margin: 15px 0; border-radius: 4px;"><p style="margin: 0; font-weight: bold;">✓ Successfully deleted ' . $deleted_count . ' selected record(s) permanently from the secure WordPress database.</p></div>';
			}
		}
	}
	?>
	<div class="wrap" style="font-family: 'Inter', sans-serif;">
		<h1 style="color: #ff334b; font-weight: bold; margin-bottom: 20px;">📌 Tatkhalsa Blood On Call - Master Admin Records</h1>
		<div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 30px;">
			<p>Secure database containing donor credentials, active patient broadcasts, and spam protection metadata. Access is restricted to site managers.</p>
			<p><strong>🔒 IP Logging Status:</strong> Active (Full historic IP retention is enabled for safety audits; 30-day auto-purge has been disabled at administrator request).</p>
			<hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;" />
			<div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
				<div>
					<a href="<?php echo admin_url('admin.php?page=blood-master-data&action=export_backup'); ?>" class="button button-primary" style="background: #2ecc71; border-color: #27ae60; font-weight: bold; text-shadow: none; box-shadow: none; display: inline-flex; align-items: center; gap: 5px;">📥 Download JSON Data Backup</a>
				</div>
				<div style="border-left: 1px solid #ddd; height: 30px; margin: 0 5px;"></div>
				<form method="POST" action="" enctype="multipart/form-data" style="display: inline-flex; align-items: center; gap: 10px; margin: 0;">
					<span style="font-weight: bold;">📤 Import Data Backup:</span>
					<input type="file" name="import_backup_file" accept=".json" required style="max-width: 200px;" />
					<input type="submit" name="import_backup_submit" class="button button-secondary" value="Load Backup" style="font-weight: bold;" />
				</form>
			</div>
		</div>

		<h2 style="margin-top: 30px; display: flex; align-items: center; gap: 8px;">🩸 Registered Donors List</h2>
		<form method="POST" action="" onsubmit="return confirm('Are you sure you want to permanently delete all selected registered donors?');" style="margin-bottom: 40px;">
			<input type="hidden" name="bulk_action" value="bulk_delete" />
			<div style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
				<input type="submit" class="button button-secondary" value="🗑️ Delete Selected Donors" style="color: #ff334b; border-color: #ff334b; background: rgba(255,51,75,0.03); font-weight: bold;" />
			</div>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th style="width: 40px; text-align: center; vertical-align: middle;"><input type="checkbox" onclick="toggleAllCheckboxes(this, 'chk-donor')" /></th>
						<th>Donor Name</th>
						<th>Blood Group</th>
						<th>Email Address</th>
						<th>Contact Details</th>
						<th>Address & Location</th>
						<th>Status</th>
						<th>IP Address</th>
						<th style="width: 100px; text-align: center;">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$donors = get_posts( array(
						'post_type'      => 'blood_donor',
						'posts_per_page' => -1,
					) );
					if ( ! empty( $donors ) ) {
						foreach ( $donors as $donor ) {
							$p_id = $donor->ID;
							$name = get_post_meta( $p_id, 'donor_name', true );
							$group = get_post_meta( $p_id, 'blood_group', true );
							$email = get_post_meta( $p_id, 'donor_email', true );
							$contact = get_post_meta( $p_id, 'contact_details', true );
							$address = get_post_meta( $p_id, 'address', true );
							$status = get_post_meta( $p_id, 'availability_status', true );
							$ip = get_post_meta( $p_id, 'donor_ip', true );
							$purged = get_post_meta( $p_id, 'ip_purged_after_30_days', true );

							if ( empty( $ip ) ) {
								$ip_display = ( $purged === 'yes' ) ? '<span style="color:#aa6666; font-style:italic;">[Purged after 30 days]</span>' : '<span style="color:#777;">unknown</span>';
							} else {
								$ip_display = '<code>' . esc_html( $ip ) . '</code> <span style="font-size:0.8rem; color:#22aa22;">(Active)</span>';
							}
							?>
							<tr>
								<td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="bulk_ids[]" value="<?php echo $p_id; ?>" class="chk-donor" /></td>
								<td><strong><?php echo esc_html( $name ); ?></strong></td>
								<td><span style="background:#ff334b; color:#fff; font-weight:bold; padding:2px 8px; border-radius:10px;"><?php echo esc_html( $group ); ?></span></td>
								<td><?php echo esc_html( $email ); ?></td>
								<td><code><?php echo esc_html( $contact ); ?></code></td>
								<td><?php echo esc_html( $address ); ?></td>
								<td><?php echo esc_html( $status ); ?></td>
								<td><?php echo $ip_display; ?></td>
								<td style="text-align: center;">
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=blood-master-data&action=delete&id=' . $p_id ) ); ?>" class="button button-small" onclick="return confirm('Are you sure you want to permanently delete this donor?');" style="color: #ff334b; border-color: #ff334b; background: rgba(255,51,75,0.05); font-weight: bold; text-decoration: none;">✕ Delete</a>
								</td>
							</tr>
							<?php
						}
					} else {
						echo '<tr><td colspan="9">No registered donors found.</td></tr>';
					}
					?>
				</tbody>
			</table>
		</form>

		<h2 style="margin-top: 30px; display: flex; align-items: center; gap: 8px;">🚨 Urgent Family Blood Requests</h2>
		<form method="POST" action="" onsubmit="return confirm('Are you sure you want to permanently delete all selected blood requests?');">
			<input type="hidden" name="bulk_action" value="bulk_delete" />
			<div style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
				<input type="submit" class="button button-secondary" value="🗑️ Delete Selected Requests" style="color: #ff334b; border-color: #ff334b; background: rgba(255,51,75,0.03); font-weight: bold;" />
			</div>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th style="width: 40px; text-align: center; vertical-align: middle;"><input type="checkbox" onclick="toggleAllCheckboxes(this, 'chk-request')" /></th>
						<th>Patient Name</th>
						<th>Blood Group</th>
						<th>Hospital Name</th>
						<th>Patient Location</th>
						<th>Contact Details</th>
						<th>Required Units / Urgency</th>
						<th style="text-align: center; width: 110px;">Doctor's Slip</th>
						<th style="text-align: center; width: 110px;">Status</th>
						<th>Accepted Volunteer Info</th>
						<th>Request IP</th>
						<th style="width: 155px; text-align: center;">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					global $wpdb;

					// Define helper function inline if not already declared for masking phones
					if ( ! function_exists( 'tatkhalsa_master_mask_phone' ) ) {
						function tatkhalsa_master_mask_phone( $phone ) {
							$phone = trim( $phone );
							if ( empty( $phone ) ) {
								return 'N/A';
							}
							
							// Check standard Indian mobile layout (+91 or 91 prefix)
							if ( preg_match( '/^(\+91|91)?\s*(\d{10})$/', $phone, $matches ) ) {
								$prefix = ! empty( $matches[1] ) ? $matches[1] : '+91';
								$digits = $matches[2];
								$last_4 = substr( $digits, -4 );
								return $prefix . ' ******' . $last_4;
							}
							
							// Fallback if formatting is unexpected, keep first 3 digits and last 4
							if ( strlen( $phone ) >= 7 ) {
								return substr( $phone, 0, 3 ) . ' ******' . substr( $phone, -4 );
							}
							return '******' . substr( $phone, -4 );
						}
					}

					// Verify direct database table presence
					$table_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_requests'" );
					$requests = array();

					if ( $table_exists ) {
						// 1. & 2. SQL JOIN Query reading status, accepted_by_donor_id, and joining wp_blood_donors for accepting volunteers.
						// Note: LEFT JOIN is behaviorally correct here so we don't drop 'pending' or 'fulfilled' requests that aren't actively assigned.
						$results = $wpdb->get_results( "
							SELECT r.*, 
							       d.donor_name AS volunteer_name, 
							       d.contact_details AS volunteer_phone
							FROM wp_blood_requests r
							LEFT JOIN wp_blood_donors d ON r.accepted_by_donor_id = d.id
							ORDER BY r.id DESC
						" );

						if ( ! empty( $results ) ) {
							foreach ( $results as $row ) {
								$req_obj = new stdClass();
								$req_obj->id = isset( $row->id ) ? $row->id : 0;
								$req_obj->patient_name = isset( $row->patient_name ) ? $row->patient_name : '';
								$req_obj->blood_group = isset( $row->blood_group ) ? $row->blood_group : '';
								$req_obj->hospital_name = isset( $row->hospital_name ) ? $row->hospital_name : '';
								$req_obj->patient_location = isset( $row->patient_location ) ? $row->patient_location : '';
								$req_obj->contact_details = isset( $row->contact_details ) ? $row->contact_details : '';
								$req_obj->units_required = isset( $row->units_required ) ? $row->units_required : '1';
								$req_obj->urgency = isset( $row->urgency ) ? $row->urgency : 'Normal';
								$req_obj->request_ip = isset( $row->request_ip ) ? $row->request_ip : '';
								$req_obj->ip_purged_after_30_days = isset( $row->ip_purged_after_30_days ) ? $row->ip_purged_after_30_days : 'no';
								$req_obj->status = isset( $row->status ) ? $row->status : 'pending';
								$req_obj->accepted_by_donor_id = isset( $row->accepted_by_donor_id ) ? $row->accepted_by_donor_id : null;
								$req_obj->volunteer_name = isset( $row->volunteer_name ) ? $row->volunteer_name : '';
								$req_obj->volunteer_phone = isset( $row->volunteer_phone ) ? $row->volunteer_phone : '';
								$req_obj->doctor_slip_url = isset( $row->doctor_slip_url ) ? $row->doctor_slip_url : '';
								$requests[] = $req_obj;
							}
						}
					} else {
						// Local development context fallback using custom post types
						$requests_posts = get_posts( array(
							'post_type'      => 'blood_request',
							'posts_per_page' => -1,
						) );

						if ( ! empty( $requests_posts ) ) {
							foreach ( $requests_posts as $post ) {
								$p_id = $post->ID;
								$req_obj = new stdClass();
								$req_obj->id = $p_id;
								$req_obj->patient_name = get_post_meta( $p_id, 'patient_name', true );
								$req_obj->blood_group = get_post_meta( $p_id, 'blood_group', true );
								$req_obj->hospital_name = get_post_meta( $p_id, 'hospital_name', true );
								$req_obj->patient_location = get_post_meta( $p_id, 'patient_location', true );
								$req_obj->contact_details = get_post_meta( $p_id, 'contact_details', true );
								$req_obj->units_required = get_post_meta( $p_id, 'units_required', true );
								$req_obj->urgency = get_post_meta( $p_id, 'urgency', true );
								$req_obj->request_ip = get_post_meta( $p_id, 'request_ip', true );
								$req_obj->ip_purged_after_30_days = get_post_meta( $p_id, 'ip_purged_after_30_days', true );
								
								// Status and Accepted by Donor logic
								$status_meta = get_post_meta( $p_id, 'status', true );
								$req_obj->status = ! empty( $status_meta ) ? $status_meta : 'pending';
								$donor_id = get_post_meta( $p_id, 'accepted_by_donor_id', true );
								$req_obj->accepted_by_donor_id = $donor_id;
								
								if ( $req_obj->status === 'accepted' && ! empty( $donor_id ) ) {
									$req_obj->volunteer_name = get_post_meta( $donor_id, 'donor_name', true );
									$req_obj->volunteer_phone = get_post_meta( $donor_id, 'contact_details', true );
								} else {
									$req_obj->volunteer_name = '';
									$req_obj->volunteer_phone = '';
								}
								
								// Doctor Slip backward compatible files
								$slip_url = get_post_meta( $p_id, 'doctor_slip_url', true );
								if ( empty( $slip_url ) ) {
									$slip_path = get_post_meta( $p_id, 'doctor_slip_path', true );
									if ( ! empty( $slip_path ) ) {
										if ( filter_var( $slip_path, FILTER_VALIDATE_URL ) ) {
											$slip_url = $slip_path;
										} else {
											$uploads = wp_upload_dir();
											$slip_url = str_replace( $uploads['basedir'], $uploads['baseurl'], $slip_path );
										}
									}
								}
								$req_obj->doctor_slip_url = $slip_url;
								$requests[] = $req_obj;
							}
						}
					}

					if ( ! empty( $requests ) ) {
						foreach ( $requests as $req ) {
							$p_id = $req->id;
							$pat_name = $req->patient_name;
							$group = $req->blood_group;
							$hospital = $req->hospital_name;
							$loc = $req->patient_location;
							$contact = $req->contact_details;
							$units = $req->units_required;
							$urgency = $req->urgency;
							$ip = $req->request_ip;
							$purged = $req->ip_purged_after_30_days;
							$status = $req->status;

							if ( empty( $ip ) ) {
								$ip_display = ( $purged === 'yes' ) ? '<span style="color:#aa6666; font-style:italic;">[Purged after 30 days]</span>' : '<span style="color:#777;">unknown</span>';
							} else {
								$ip_display = '<code>' . esc_html( $ip ) . '</code> <span style="font-size:0.8rem; color:#22aa22;">(Active)</span>';
							}

							$slip_url = $req->doctor_slip_url;

							// 3. Status Visual Guidelines: bright green (pending), brand royal blue (accepted), dark grey (fulfilled)
							if ( $status === 'pending' || $status === '' ) {
								$status_display = '<span style="background: #2ced73; color: #0a2342; font-weight: bold; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; display: inline-block; text-align: center; box-shadow: 0 2px 4px rgba(46,213,115,0.15);">Pending</span>';
							} elseif ( $status === 'accepted' ) {
								$status_display = '<span style="background: #0A327D; color: #ffffff; font-weight: bold; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; display: inline-block; text-align: center; box-shadow: 0 2px 4px rgba(10,50,125,0.15);">Accepted</span>';
							} elseif ( $status === 'fulfilled' ) {
								$status_display = '<span style="background: #555555; color: #ffffff; font-weight: bold; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; display: inline-block; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">fulfilled</span>';
							} else {
								$status_display = '<span style="background: #777777; color: #ffffff; font-weight: bold; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; display: inline-block; text-align: center;">' . esc_html( $status ) . '</span>';
							}

							// 2. Format Accepted Volunteer details (inner joined) and mask phone
							if ( $status === 'accepted' && ! empty( $req->volunteer_name ) ) {
								$accepted_info = '<div style="font-size: 0.85rem; line-height: 1.4;">';
								$accepted_info .= '<strong style="color: #0A327D;">👤 ' . esc_html( $req->volunteer_name ) . '</strong><br>';
								$accepted_info .= '<code style="color: #444; font-size: 0.8rem;">📞 ' . esc_html( tatkhalsa_master_mask_phone( $req->volunteer_phone ) ) . '</code>';
								$accepted_info .= '</div>';
							} else {
								$accepted_info = '<span style="color: #999; font-style: italic; font-size: 0.85rem;">No active claim</span>';
							}
							?>
							<tr>
								<td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="bulk_ids[]" value="<?php echo $p_id; ?>" class="chk-request" /></td>
								<td><strong><?php echo esc_html( $pat_name ); ?></strong></td>
								<td><span style="background:#ff334b; color:#fff; font-weight:bold; padding:2px 8px; border-radius:10px;"><?php echo esc_html( $group ); ?></span></td>
								<td><?php echo esc_html( $hospital ); ?></td>
								<td><?php echo esc_html( $loc ); ?></td>
								<td><code><?php echo esc_html( $contact ); ?></code></td>
								<td><strong><?php echo esc_html( $units ); ?> Units</strong> (<?php echo esc_html( $urgency ); ?>)</td>
								<td style="text-align: center; vertical-align: middle;">
									<?php if ( ! empty( $slip_url ) ) : ?>
										<a href="<?php echo esc_url( $slip_url ); ?>" target="_blank" title="Click to view physician request prescription form">
											<img src="<?php echo esc_url( $slip_url ); ?>" style="width: 48px; height: 48px; border-radius: 6px; object-fit: cover; border: 1.5px solid #ff334b; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.2)';" onmouseout="this.style.transform='scale(1)';" />
										</a>
									<?php else : ?>
										<span style="color: #999; font-size: 0.8rem; font-style: italic;">No attachment</span>
									<?php endif; ?>
								</td>
								<td style="text-align: center; vertical-align: middle;"><?php echo $status_display; ?></td>
								<td><?php echo $accepted_info; ?></td>
								<td><?php echo $ip_display; ?></td>
								<td style="text-align: center;">
									<div style="display: flex; flex-direction: column; gap: 4px; align-items: stretch;">
										<?php if ( $status !== 'fulfilled' ) : ?>
											<!-- 4. Quick-action Mark as Fulfilled localized postback button -->
											<a href="<?php echo esc_url( admin_url( 'admin.php?page=blood-master-data&action=fulfill&id=' . $p_id ) ); ?>" class="button button-small" style="background: #555555; color: #ffffff; border-color: #555555; font-weight: bold; text-decoration: none; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.15);" onclick="return confirm('Are you sure you want to mark request #<?php echo $p_id; ?> as Fulfilled?');">✓ Fulfill</a>
										<?php endif; ?>
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=blood-master-data&action=delete&id=' . $p_id ) ); ?>" class="button button-small" onclick="return confirm('Are you sure you want to permanently delete this blood request?');" style="color: #ff334b; border-color: #ff334b; background: rgba(255,51,75,0.05); font-weight: bold; text-decoration: none; text-align: center;">✕ Delete</a>
									</div>
								</td>
							</tr>
							<?php
						}
					} else {
						echo '<tr><td colspan="12">No active blood requests found.</td></tr>';
					}
					?>
				</tbody>
			</table>
		</form>
	</div>

	<script type="text/javascript">
	function toggleAllCheckboxes(master, targetClass) {
		const checkboxes = document.querySelectorAll('.' + targetClass);
		for (let i = 0; i < checkboxes.length; i++) {
			checkboxes[i].checked = master.checked;
		}
	}
	</script>
	<?php
}

/**
 * =========================================================================
 * TATKHALSA ADVANCED SEO OPTIMIZATION & GOOGLE SEARCH CONSOLE RESOLUTIONS
 * =========================================================================
 * 1. Force Search Engine Indexing (Overrides unintended noindex tags from database settings/plugins)
 * 2. Purges/disables programmatic blockages like 'wp_no_robots' in wp_head
 * 3. Builds a direct dynamic Robots.txt optimizer for sitemaps and indexing
 */

// 1. Overrides the "Discourage search engines from indexing this site" option dynamically
add_filter( 'pre_option_blog_public', '__return_true', 999 );

// 2. Remove standard WP 'noindex' hook functions completely from indexing pipelines
remove_action( 'wp_head', 'wp_no_robots' );

// 3. Dynamically sanitize the output of wp_robots tag to strictly favor indexing
add_filter( 'wp_robots', function( $robots ) {
    $robots['index']  = true;
    $robots['follow'] = true;
    unset( $robots['noindex'] );
    unset( $robots['nofollow'] );
    return $robots;
}, 999 );

// 4. Clean dynamic Robots.txt configuration to bypass file blockage
add_filter( 'robots_txt', function( $output, $public ) {
    $clean_robots  = "User-agent: *\n";
    $clean_robots .= "Allow: /\n";
    $clean_robots .= "Disallow: /wp-admin/\n";
    $clean_robots .= "Disallow: /wp-includes/\n";
    $clean_robots .= "Disallow: /xmlrpc.php\n";
    $clean_robots .= "\n# Sitemaps\n";
    $clean_robots .= "Sitemap: https://tatkhalsa.in/wp-sitemap.xml\n";
    $clean_robots .= "Sitemap: https://tatkhalsa.in/sitemap_index.xml\n";
    return $clean_robots;
}, 999, 2 );

// 5. Dynamic WordPress Page Document Title filter for elegant search result snippets
add_filter( 'pre_get_document_title', function() {
    if ( is_front_page() || is_home() ) {
        return "Tatkhalsa Foundation | Direct Seva, Volunteerism, and Gurbani Heritage";
    } elseif ( is_page_template( 'template-about.php' ) ) {
        return "About Tatkhalsa Foundation | Our Mission, Vision & Values";
    } elseif ( is_page_template( 'template-projects.php' ) ) {
        return "Our Seva Projects | Environmental, Healthcare, and Spiritual Initiatives";
    } elseif ( is_page_template( 'template-blood-donors.php' ) ) {
        return "Blood On Call | Save Lives with Tatkhalsa Foundation";
    } elseif ( is_page_template( 'template-volunteer.php' ) ) {
        return "Become a Sevadar | Join the Tatkhalsa Foundation Volunteer Force";
    } elseif ( is_page_template( 'template-privacy.php' ) ) {
        return "Privacy Policy | Transparent Data & Secure Storage Guidelines";
    } elseif ( is_page_template( 'template-terms.php' ) ) {
        return "Terms and Conditions | Community Code of Conduct";
    } elseif ( is_404() ) {
        return "Page Not Found | Error Code 404 - Tatkhalsa Foundation";
    }
    return ''; // Let WordPress handle dynamic posts or category titles
}, 999 );

/**
 * Handle manual UPI tracking in GiveWP via AJAX
 */
function tatkhalsa_verify_upi_donation() {
    $name = sanitize_text_field( $_POST['name'] );
    $parts = explode(' ', $name, 2);
    $first_name = $parts[0];
    $last_name = isset($parts[1]) ? $parts[1] : '';
    
    $email = sanitize_email( $_POST['email'] );
    $amount = floatval( $_POST['amount'] );
    $utr = sanitize_text_field( $_POST['utr'] );
    $seva_type = sanitize_text_field( $_POST['seva_type'] );

    if ( empty( $name ) || empty( $email ) || empty( $amount ) || empty( $utr ) ) {
        wp_send_json_error( 'Missing fields' );
    }

    // Insert GiveWP Payment Post manually so it appears in the GiveWP dashboard
    $payment_id = wp_insert_post( array(
        'post_type'   => 'give_payment',
        'post_title'  => "GiveWP Donation - $name",
        'post_status' => 'pending', // Pending verification
        'post_author' => 1,
    ) );

    if ( $payment_id && ! is_wp_error( $payment_id ) ) {
        update_post_meta( $payment_id, '_give_payment_donor_email', $email );
        update_post_meta( $payment_id, '_give_payment_donor_billing_first_name', $first_name );
        update_post_meta( $payment_id, '_give_payment_donor_billing_last_name', $last_name );
        update_post_meta( $payment_id, '_give_payment_total', $amount );
        update_post_meta( $payment_id, '_give_payment_currency', 'INR' );
        update_post_meta( $payment_id, '_give_payment_gateway', 'manual' );
        update_post_meta( $payment_id, '_give_payment_utr', $utr );
        update_post_meta( $payment_id, '_give_payment_seva_type', $seva_type );
        update_post_meta( $payment_id, '_give_payment_date', current_time('mysql') );
        
        // GiveWP standard meta
        update_post_meta( $payment_id, '_give_payment_donor_ip', $_SERVER['REMOTE_ADDR'] );
        update_post_meta( $payment_id, '_give_payment_form_title', $seva_type );

        wp_insert_comment( array(
            'comment_post_ID'  => $payment_id,
            'comment_content'  => "User submitted manual payment.\nUTR: $utr\nSeva Type: $seva_type",
            'comment_type'     => 'give_payment_note',
            'comment_approved' => 1,
        ) );
    }

    // Email admin notifier
    $admin_email = get_option('admin_email');
    $subject = "New UPI Donation verification needed ($utr)";
    $message = "Name: $name\nEmail: $email\nAmount: Rs. $amount\nUTR: $utr\nSeva Type: $seva_type\n\nPlease check your bank statement for this UTR. Then go to GiveWP dashboard -> Donations, find this pending record, and mark it as 'Complete' to issue the receipt.";
    wp_mail( $admin_email, $subject, $message, array('Content-Type: text/plain; charset=UTF-8') );
    
    wp_send_json_success( 'Recorded' );
}
add_action( 'wp_ajax_verify_upi_donation', 'tatkhalsa_verify_upi_donation' );
add_action( 'wp_ajax_nopriv_verify_upi_donation', 'tatkhalsa_verify_upi_donation' );

/**
 * Automate 80G Receipt Email when Admin verifies (publishes) the pending donation
 */
function tatkhalsa_send_80g_receipt_on_verification( $new_status, $old_status, $post ) {
    if ( $post->post_type !== 'give_payment' ) return;

    // Trigger when status changes to 'publish' (Completed) or 'give_completed'
    if ( ( $new_status === 'publish' || $new_status === 'give_completed' || $new_status === 'completed' ) && $old_status !== $new_status ) {
        
        // Prevent sending duplicate emails
        $already_sent = get_post_meta( $post->ID, '_80g_receipt_sent', true );
        if ( $already_sent ) return;

        $email = get_post_meta( $post->ID, '_give_payment_donor_email', true );
        $amount = get_post_meta( $post->ID, '_give_payment_total', true );
        $first_name = get_post_meta( $post->ID, '_give_payment_donor_billing_first_name', true );
        $last_name = get_post_meta( $post->ID, '_give_payment_donor_billing_last_name', true );
        $name = trim($first_name . ' ' . $last_name);
        $utr = get_post_meta( $post->ID, '_give_payment_utr', true );
        $seva_type = get_post_meta( $post->ID, '_give_payment_seva_type', true );

        if ( empty( $email ) || empty( $amount ) ) return;

        // Fallback names/utr if missing
        if ( empty( $name ) ) $name = 'Generous Donor';
        if ( empty( $utr ) ) $utr = 'Verified TXN';
        if ( empty( $seva_type ) ) $seva_type = 'General Donation';

        $subject = "Your 80G Tax Exemption Receipt - Tatkhalsa Foundation";
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $receipt_date = date('d-M-Y', strtotime($post->post_date));
        $receipt_no = "TKF-" . date('Y', strtotime($post->post_date)) . "-" . $post->ID;

        $message = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h2 style='color: #d4af37;'>Tatkhalsa Foundation</h2>
                <h3>Official 80G Tax Exemption Receipt</h3>
            </div>
            <p>Dear <strong>{$name}</strong>,</p>
            <p>Thank you for your generous contribution. Your payment has been successfully verified by our team. Please find your official donation receipt below:</p>
            
            <table border='1' cellpadding='12' cellspacing='0' style='border-collapse: collapse; width: 100%; margin: 25px 0; border: 1px solid #eee;'>
                <tr style='background: #f9f9f9;'>
                    <td style='width: 40%;'><strong>Receipt No:</strong></td>
                    <td>{$receipt_no}</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>{$receipt_date}</td>
                </tr>
                <tr style='background: #f9f9f9;'>
                    <td><strong>Donor Name:</strong></td>
                    <td>{$name}</td>
                </tr>
                <tr>
                    <td><strong>Amount Received:</strong></td>
                    <td><strong style='color: #27ae60;'>INR {$amount}/-</strong></td>
                </tr>
                <tr style='background: #f9f9f9;'>
                    <td><strong>Payment Mode:</strong></td>
                    <td>UPI / Bank Transfer (UTR/Ref: {$utr})</td>
                </tr>
                <tr>
                    <td><strong>Seva/Cause:</strong></td>
                    <td>{$seva_type}</td>
                </tr>
            </table>
            
            <p style='font-size: 0.9em; background: #eef7f2; padding: 15px; border-left: 4px solid #27ae60;'>
                <strong>Tax Exemption Note:</strong> Donations made to TATKHALSA FOUNDATION are eligible for tax exemption under section 80G of the Income Tax Act, 1961. This automated email serves as your official receipt for tax filing purposes.
            </p>
            
            <p style='margin-top: 30px;'>May Waheguru bless you,<br><strong>Tatkhalsa Foundation Team</strong></p>
        </body>
        </html>
        ";

        wp_mail( $email, $subject, $message, $headers );
        
        // Mark as sent
        update_post_meta( $post->ID, '_80g_receipt_sent', true );
        
        // Log the action as a comment
        wp_insert_comment( array(
            'comment_post_ID'  => $post->ID,
            'comment_content'  => "Automated 80G receipt officially emailed to donor ($email).",
            'comment_type'     => 'give_payment_note',
            'comment_approved' => 1,
        ) );
    }
}
add_action( 'transition_post_status', 'tatkhalsa_send_80g_receipt_on_verification', 10, 3 );
function tatkhalsa_admin_master_data() {
	$donors_args = array(
		'post_type'      => 'blood_donor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);
	$donors_posts = get_posts( $donors_args );
	$donors = array();
	foreach ( $donors_posts as $post ) {
		$donors[] = array(
			'id'                 => 'DONOR_' . $post->ID,
			'donorNumber'        => tatkhalsa_get_or_create_donor_id( $post->ID ),
			'post_id'            => $post->ID,
			'name'               => get_post_meta( $post->ID, 'donor_name', true ),
			'bloodGroup'         => get_post_meta( $post->ID, 'blood_group', true ),
			'email'              => get_post_meta( $post->ID, 'donor_email', true ),
			'contact'            => get_post_meta( $post->ID, 'contact_details', true ),
			'address'            => get_post_meta( $post->ID, 'address', true ),
			'mapLocation'        => get_post_meta( $post->ID, 'map_location', true ),
			'availabilityStatus' => get_post_meta( $post->ID, 'availability_status', true ),
			'registrationTime'   => get_post_meta( $post->ID, 'registration_time', true ),
			'isVerified'         => true
		);
	}

	$requests_args = array(
		'post_type'      => 'blood_request',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);
	$requests_posts = get_posts( $requests_args );
	$requests = array();
	foreach ( $requests_posts as $post ) {
		$requests[] = array(
			'id'              => 'REQ_' . $post->ID,
			'post_id'         => $post->ID,
			'patientName'     => get_post_meta( $post->ID, 'patient_name', true ),
			'bloodGroup'      => get_post_meta( $post->ID, 'blood_group', true ),
			'unitsRequired'   => get_post_meta( $post->ID, 'units_required', true ),
			'hospital'        => get_post_meta( $post->ID, 'hospital', true ),
			'contactDetails'  => get_post_meta( $post->ID, 'contact_details', true ),
			'urgency'         => get_post_meta( $post->ID, 'urgency', true ),
			'status'          => get_post_meta( $post->ID, 'status', true ),
			'requestTime'     => get_post_meta( $post->ID, 'request_time', true ),
		);
	}

	wp_send_json( array(
		'success'  => true,
		'donors'   => $donors,
		'requests' => $requests,
	) );
}
add_action( 'wp_ajax_admin_master_data', 'tatkhalsa_admin_master_data' );
add_action( 'wp_ajax_nopriv_admin_master_data', 'tatkhalsa_admin_master_data' );
require_once get_template_directory() . '/admin-brevo.php';
require_once get_template_directory() . '/admin-newsletter.php';
require_once get_template_directory() . '/admin-ajax-handlers.php';

// Auto-migrate existing donors to numbered IDs starting at 01
add_action( 'init', 'tatkhalsa_migrate_existing_donors_to_numbered_ids_01' );
function tatkhalsa_migrate_existing_donors_to_numbered_ids_01() {
    // Only run this once
    if ( get_option( 'tatkhalsa_donors_migrated_to_01' ) ) {
        return;
    }

    $donors = get_posts( array(
        'post_type'      => 'blood_donor',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'order'          => 'ASC',
        'orderby'        => 'date'
    ) );

    // If there are no donors, just mark as migrated so we don't keep running it
    if ( empty( $donors ) ) {
        update_option( 'tatkhalsa_donors_migrated_to_01', true );
        return; 
    }

    $count = 1;
    foreach ( $donors as $d ) {
        $donor_id_str = 'TKF-DON-' . str_pad($count, 2, '0', STR_PAD_LEFT);
        update_post_meta( $d->ID, 'donor_id_number', $donor_id_str );
        $count++;
    }

    update_option( 'tatkhalsa_next_donor_id', $count );
    update_option( 'tatkhalsa_donors_migrated_to_01', true );
}
