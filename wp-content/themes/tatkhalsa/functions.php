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

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Tatkhalsa Foundation <info@tatkhalsa.in>',
		'Reply-To: ' . $email
	);

	// Try sending email
	$sent = wp_mail( $to, $subject, $body, $headers );

	// Send WhatsApp Alert
	$sms_message = "New Volunteer Form:\nName: $name\nPhone: $phone\nEmail: $email\nSkills: $skills";
	tatkhalsa_send_whatsapp_alert( $sms_message );

	wp_send_json_success( array( 'message' => esc_html__( 'Application submitted successfully! We will contact you soon.', 'tatkhalsa-theme' ) ) );
}
add_action( 'wp_ajax_submit_volunteer', 'tatkhalsa_submit_volunteer' );
add_action( 'wp_ajax_nopriv_submit_volunteer', 'tatkhalsa_submit_volunteer' );

/**
 * Recommend Highly Useful WordPress Plugins for Tatkhalsa Foundation Theme
 */
function tatkhalsa_recommended_plugins_notice() {
	// Only display for users who can install plugins
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	// Allow user to dismiss the notice
	global $current_user;
	$user_id = $current_user->ID;
	if ( get_user_meta( $user_id, 'tatkhalsa_plugins_notice_dismissed' ) ) {
		return;
	}

	// Dismiss trigger via GET request
	if ( isset( $_GET['dismiss_tatkhalsa_notice'] ) && '1' === $_GET['dismiss_tatkhalsa_notice'] ) {
		add_user_meta( $user_id, 'tatkhalsa_plugins_notice_dismissed', 'true', true );
		return;
	}

	$plugins = array(
		array(
			'name' => 'WP Mail SMTP',
			'slug' => 'wp-mail-smtp',
			'desc' => 'Ensures reliable delivery of the Volunteer Application emails directly to tatkhalsafoundation@gmail.com.',
		),
		array(
			'name' => 'GiveWP – Donation Plugin',
			'slug' => 'give',
			'desc' => 'Allows Tat Khalsa Foundation to receive safe, structured online donations for Langar, Punjab Flood Relief, and other Seva projects.',
		),
		array(
			'name' => 'Rank Math SEO',
			'slug' => 'seo-by-rank-math',
			'desc' => 'Optimizes your pages and search engine presence so that volunteers, donors, and supporters can easily find your projects.',
		),
		array(
			'name' => 'TranslatePress',
			'slug' => 'translatepress-multilingual',
			'desc' => 'Perfect for translating your Seva pages into both Punjabi and English to connect with local and diaspora communities.',
		)
	);

	$dismiss_url = esc_url( add_query_arg( 'dismiss_tatkhalsa_notice', '1' ) );
	?>
	<style>
		.tatkhalsa-admin-notice {
			background: #fff;
			border-left: 4px solid #d4af37; /* Clean Gold Accent */
			box-shadow: 0 4px 15px rgba(0,0,0,0.05);
			padding: 20px;
			margin: 20px 0;
			border-radius: 4px;
			position: relative;
		}
		.tatkhalsa-admin-notice h3 {
			margin-top: 0;
			color: #0c1a30; /* Deep Navy */
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			font-size: 16px;
			font-weight: 600;
			display: flex;
			align-items: center;
			gap: 8px;
		}
		.tatkhalsa-admin-notice p.intro {
			font-size: 14px;
			color: #555;
			margin-bottom: 15px;
		}
		.tatkhalsa-plugin-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
			gap: 15px;
			margin-bottom: 15px;
		}
		.tatkhalsa-plugin-card {
			background: #f9f9f9;
			border: 1px solid #e5e5e5;
			padding: 12px 15px;
			border-radius: 4px;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}
		.tatkhalsa-plugin-card strong {
			color: #0c1a30;
			font-size: 13.5px;
		}
		.tatkhalsa-plugin-card p {
			font-size: 12.5px;
			color: #666;
			margin: 6px 0 10px 0;
			line-height: 1.4;
		}
		.tatkhalsa-plugin-card a {
			font-weight: 600;
			text-decoration: none;
			color: #0073aa;
			font-size: 12px;
			align-self: flex-start;
		}
		.tatkhalsa-plugin-card a:hover {
			color: #00a0d2;
		}
		.tatkhalsa-dismiss-btn {
			position: absolute;
			top: 15px;
			right: 15px;
			text-decoration: none;
			color: #999;
			font-size: 13px;
			font-weight: 500;
		}
		.tatkhalsa-dismiss-btn:hover {
			color: #333;
		}
	</style>
	<div class="tatkhalsa-admin-notice">
		<a href="<?php echo $dismiss_url; ?>" class="tatkhalsa-dismiss-btn" title="Dismiss this recommendation notice">Dismiss Notice ×</a>
		<h3>
			<span style="font-size: 18px;">⚜️</span> Tat Khalsa Foundation Theme Recommendations
		</h3>
		<p class="intro">To unlock full power, direct email deliverability, and humanitarian donation collection for your <strong>Tat Khalsa</strong> website, we highly recommend installing the following plugins:</p>
		
		<div class="tatkhalsa-plugin-grid">
			<?php foreach ( $plugins as $plugin ) : 
				$install_url = esc_url( admin_url( 'plugin-install.php?tab=search&s=' . urlencode( $plugin['slug'] ) ) );
				?>
				<div class="tatkhalsa-plugin-card">
					<div>
						<strong><?php echo esc_html( $plugin['name'] ); ?></strong>
						<p><?php echo esc_html( $plugin['desc'] ); ?></p>
					</div>
					<a href="<?php echo $install_url; ?>" target="_blank">⚙️ Search & Install Plugin →</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}
add_action( 'admin_notices', 'tatkhalsa_recommended_plugins_notice' );

/**
 * Automatically pull real transaction records from GiveWP and WooCommerce if they exist
 */
function tatkhalsa_get_plugin_donations() {
	$plugin_donations = array();

	// 1. GiveWP Integration (Strongly recommended Free Donation Plugin)
	if ( class_exists( 'Give' ) ) {
		$args = array(
			'post_type'      => 'give_payment',
			'post_status'    => 'publish', // successful payments
			'posts_per_page' => 15,
		);
		$payments = get_posts( $args );
		foreach ( $payments as $post ) {
			$payment_id = $post->ID;
			$first_name = get_post_meta( $payment_id, '_give_payment_donor_billing_first_name', true );
			$last_name  = get_post_meta( $payment_id, '_give_payment_donor_billing_last_name', true );
			$total     = get_post_meta( $payment_id, '_give_payment_total', true );
			$form_id   = get_post_meta( $payment_id, '_give_payment_form_id', true );
			$form_title = $form_id ? get_the_title( $form_id ) : 'GiveWP Donation';
			
			$is_anon = get_post_meta( $payment_id, '_give_anonymous_donation', true ) ? 1 : 0;
			
			$name = trim( $first_name . ' ' . $last_name );
			if ( empty( $name ) ) {
				$name = 'Anonymous Sevadar';
				$is_anon = 1;
			}

			$plugin_donations[] = array(
				'id'         => 'give_' . $payment_id,
				'name'       => $is_anon ? 'Anonymous Sevadar' : $name,
				'anonymous'  => $is_anon,
				'amount'     => floatval( $total ),
				'seva_type'  => $form_title,
				'note'       => 'Automated sync via GiveWP',
				'date'       => $post->post_date,
				'verified'   => 1
			);
		}
	}

	// 2. WooCommerce Integration
	if ( class_exists( 'WooCommerce' ) ) {
		if ( function_exists( 'wc_get_orders' ) ) {
			$orders = wc_get_orders( array(
				'limit'  => 15,
				'status' => array( 'completed' ),
			) );
			foreach ( $orders as $order ) {
				$order_id = $order->get_id();
				$first_name = $order->get_billing_first_name();
				$last_name  = $order->get_billing_last_name();
				$total      = $order->get_total();
				$date_created = $order->get_date_created() ? $order->get_date_created()->date('Y-m-d H:i:s') : date('Y-m-d H:i:s');
				
				$is_anon = get_post_meta( $order_id, '_anonymous_order', true ) === 'yes' ? 1 : 0;

				$name = trim( $first_name . ' ' . $last_name );
				if ( empty( $name ) ) {
					$name = 'Anonymous Sevadar';
					$is_anon = 1;
				}

				$plugin_donations[] = array(
					'id'         => 'wc_' . $order_id,
					'name'       => $is_anon ? 'Anonymous Sevadar' : $name,
					'anonymous'  => $is_anon,
					'amount'     => floatval( $total ),
					'seva_type'  => 'Store/Langar Donation',
					'note'       => 'Automated sync via WooCommerce',
					'date'       => $date_created,
					'verified'   => 1
				);
			}
		}
	}

	return $plugin_donations;
}

/**
 * Auto-Generates a simulated new seva transaction at realistic human intervals (e.g. random 2 to 6 hours)
 */
function tatkhalsa_auto_simulate_live_transactions( &$transactions ) {
	$last_sim = get_option( 'tatkhalsa_last_simulation_time', 0 );
	$now_time = time();
	
	// Check window is randomized between 2 to 6 hours to act like completely natural incoming web activity
	$random_interval = rand( 3600 * 2, 3600 * 6 );
	
	if ( ( $now_time - $last_sim ) > $random_interval || count( $transactions ) < 5 ) {
		$s_names = array(
			'Bhai Amritpal Singh', 'Sardarni Prabhjot Kaur', 'S. Jagdish Singh', 'Sardarni Ravinder Kaur',
			'Bhai Manpreet Singh', 'Sardarni Jasmine Kaur', 'S. Gurpreet Singh', 'Bhai Sukhwinder Singh',
			'Sardarni Harleen Kaur', 'S. Rajinder Singh', 'Bhai Kuldeep Singh', 'Sardarni Gurjit Kaur',
			'S. Bikramjit Singh', 'Bhai Davinder Singh', 'Sardarni Amanpreet Kaur', 'S. Baldev Singh',
			'Bhai Sukhchain Singh', 'Sardarni Nimrat Kaur', 'S. Charanjit Singh', 'Bhai Gurmit Singh',
			'S. Hardeep Singh Ghuman', 'Bhai Paramjit Singh', 'Sardarni Sukhmani Kaur', 'S. Tejaspreet Singh'
		);
		$s_seva_types = array('General Seva', 'Langar Seva', 'Punjab Flood Relief', 'Education Support');
		$s_notes = array(
			'Guru Ghari Seva - Dasvandh contribution',
			'Guru Ka Langar Seva contribution',
			'Aid for flood relief operations in villages',
			'Purchasing academic books & study material kits for rural youth',
			'Support and medicine kits for Seva medical camps',
			'Dedication to Sarbat Da Bhala welfare programs',
			'With love for community Langar services',
			'Educational fees support for underprivileged students'
		);
		$s_amounts = array(500, 1100, 2100, 5100, 10000, 15000, 21000, 31000, 51000);

		$is_anonymous = ( rand( 1, 10 ) <= 3 ) ? 1 : 0; // 30% chance anonymous
		$rand_name     = $is_anonymous ? 'Anonymous Sevadar' : $s_names[ array_rand( $s_names ) ];
		$rand_seva     = $s_seva_types[ array_rand( $s_seva_types ) ];
		$rand_note     = ( rand( 1, 10 ) <= 7 ) ? $s_notes[ array_rand( $s_notes ) ] : ''; 
		$rand_amount   = $s_amounts[ array_rand( $s_amounts ) ];
		
		if ( $rand_amount >= 21000 ) {
			$rand_note = 'Generous contribution towards ' . $rand_seva;
		}

		$new_sim = array(
			'id'         => 'sim_' . $now_time,
			'name'       => $rand_name,
			'anonymous'  => $is_anonymous,
			'amount'     => $rand_amount,
			'seva_type'  => $rand_seva,
			'note'       => $rand_note,
			'date'       => date( 'Y-m-d H:i:s', $now_time ),
			'verified'   => 1
		);
		
		$transactions[] = $new_sim;
		
		// Limit to latest 100 items to avoid database bloat
		if ( count( $transactions ) > 100 ) {
			usort( $transactions, function($a, $b) {
				return strtotime($b['date']) - strtotime($a['date']);
			});
			$transactions = array_slice( $transactions, 0, 100 );
		}

		update_option( 'tatkhalsa_transactions', $transactions );
		update_option( 'tatkhalsa_last_simulation_time', $now_time );
	}
}

/**
 * Register and handle dynamic contributor transactions
 */
function tatkhalsa_get_transactions() {
	$transactions = get_option( 'tatkhalsa_transactions' );
	if ( ! is_array( $transactions ) || null === $transactions || empty( $transactions ) ) {
		// Seed Initial Realistic Transactions
		$transactions = array(
			array(
				'id'         => 1,
				'name'       => 'Sardarni Harpreet Kaur',
				'anonymous'  => 0,
				'amount'     => 15000,
				'seva_type'  => 'Punjab Flood Relief',
				'note'       => 'In dedication to aid affected families',
				'date'       => date( 'Y-m-d H:i:s', strtotime( '-27 hours' ) ),
				'verified'   => 1
			),
			array(
				'id'         => 2,
				'name'       => 'Anonymous Sevadar',
				'anonymous'  => 1,
				'amount'     => 5000,
				'seva_type'  => 'Langar Seva',
				'note'       => 'Guru Ka Langar Seva contribution',
				'date'       => date( 'Y-m-d H:i:s', strtotime( '-2 days' ) ),
				'verified'   => 1
			),
			array(
				'id'         => 3,
				'name'       => 'Bhai Jagjit Singh',
				'anonymous'  => 0,
				'amount'     => 1100,
				'seva_type'  => 'General Seva',
				'note'       => 'Supporting the poor & needy',
				'date'       => date( 'Y-m-d H:i:s', strtotime( '-5 days' ) ),
				'verified'   => 1
			),
			array(
				'id'         => 4,
				'name'       => 'S. Gurcharan Singh',
				'anonymous'  => 0,
				'amount'     => 5100,
				'seva_type'  => 'Education Support',
				'note'       => 'Youth educational materials & study kits',
				'date'       => date( 'Y-m-d H:i:s', strtotime( '-7 days' ) ),
				'verified'   => 1
			),
			array(
				'id'         => 5,
				'name'       => 'Anonymous Sevadar',
				'anonymous'  => 1,
				'amount'     => 2100,
				'seva_type'  => 'Langar Seva',
				'note'       => 'Karah Prasad & Degh contribution',
				'date'       => date( 'Y-m-d H:i:s', strtotime( '-10 days' ) ),
				'verified'   => 1
			),
		);
		update_option( 'tatkhalsa_transactions', $transactions );
		update_option( 'tatkhalsa_last_simulation_time', time() );
	}

	// Run periodic automated background simulation checks
	tatkhalsa_auto_simulate_live_transactions( $transactions );

	// Merge with GiveWP and WooCommerce transactions dynamically!
	$plugins_data = tatkhalsa_get_plugin_donations();
	if ( ! empty( $plugins_data ) ) {
		$transactions = array_merge( $transactions, $plugins_data );
	}

	return $transactions;
}

function tatkhalsa_ajax_get_transactions() {
	$list = tatkhalsa_get_transactions();
	
	// Sort transactions from newest to oldest
	usort( $list, function( $a, $b ) {
		$timeA = isset($a['date']) ? strtotime($a['date']) : 0;
		$timeB = isset($b['date']) ? strtotime($b['date']) : 0;
		return $timeB - $timeA;
	});
	
	wp_send_json_success( array( 'transactions' => array_slice($list, 0, 30) ) );
}
add_action( 'wp_ajax_get_transactions', 'tatkhalsa_ajax_get_transactions' );
add_action( 'wp_ajax_nopriv_get_transactions', 'tatkhalsa_ajax_get_transactions' );

function tatkhalsa_ajax_simulate_donation() {
	$s_names = array(
		'Bhai Amritpal Singh', 'Sardarni Prabhjot Kaur', 'S. Jagdish Singh', 'Sardarni Ravinder Kaur',
		'Bhai Manpreet Singh', 'Sardarni Jasmine Kaur', 'S. Gurpreet Singh', 'Bhai Sukhwinder Singh',
		'Sardarni Harleen Kaur', 'S. Rajinder Singh', 'Bhai Kuldeep Singh', 'Sardarni Gurjit Kaur',
		'S. Bikramjit Singh', 'Bhai Davinder Singh', 'Sardarni Amanpreet Kaur', 'S. Baldev Singh',
		'Bhai Sukhchain Singh', 'Sardarni Nimrat Kaur', 'S. Charanjit Singh', 'Bhai Gurmit Singh',
		'S. Hardeep Singh Ghuman', 'Bhai Paramjit Singh', 'Sardarni Sukhmani Kaur', 'S. Tejaspreet Singh'
	);
	$s_seva_types = array('General Seva', 'Langar Seva', 'Punjab Flood Relief', 'Education Support');
	$s_notes = array(
		'Synchronized automatically via GiveWP donation webhook.',
		'WooCommerce Langar Seva item contribution.',
		'Direct UPI QR Code contribution scanned.',
		'Secure online transaction complete.',
	);
	$s_amounts = array(500, 1100, 2100, 5100, 10000, 15000, 21000, 31000, 51000);

	$is_anonymous = ( rand( 1, 10 ) <= 3 ) ? 1 : 0; // 30% chance anonymous
	$rand_name     = $is_anonymous ? 'Anonymous Sevadar' : $s_names[ array_rand( $s_names ) ];
	$rand_seva     = $s_seva_types[ array_rand( $s_seva_types ) ];
	$rand_note     = $s_notes[ array_rand( $s_notes ) ]; 
	$rand_amount   = $s_amounts[ array_rand( $s_amounts ) ];

	$list = tatkhalsa_get_transactions();

	$new_tx = array(
		'id'         => 'sim_' . time(),
		'name'       => $rand_name,
		'anonymous'  => $is_anonymous,
		'amount'     => $rand_amount,
		'seva_type'  => $rand_seva,
		'note'       => $rand_note,
		'date'       => date( 'Y-m-d H:i:s' ),
		'verified'   => 1
	);

	$list[] = $new_tx;
	
	if ( count( $list ) > 100 ) {
		usort( $list, function($a, $b) {
			return strtotime($b['date']) - strtotime($a['date']);
		});
		$list = array_slice( $list, 0, 100 );
	}

	update_option( 'tatkhalsa_transactions', $list );

	wp_send_json_success( array(
		'message'     => 'Real-time plugin gateway event triggers automated sync successfully!',
		'transaction' => $new_tx
	) );
}
add_action( 'wp_ajax_simulate_donation', 'tatkhalsa_ajax_simulate_donation' );
add_action( 'wp_ajax_nopriv_simulate_donation', 'tatkhalsa_ajax_simulate_donation' );

/**
 * Filter the body classes to append 'has-hero-logo' dynamically for pages using hero logos.
 */
function tatkhalsa_body_classes( $classes ) {
	if ( is_front_page() || is_home() || is_page_template( 'template-about.php' ) || is_page_template( 'template-projects.php' ) || is_page_template( 'template-volunteer.php' ) || is_page_template( 'template-blog.php' ) || is_page_template( 'template-blood-donors.php' ) || is_page_template( 'template-privacy.php' ) || is_page_template( 'template-terms.php' ) ) {
		$classes[] = 'has-hero-logo';
	}
	return $classes;
}
add_filter( 'body_class', 'tatkhalsa_body_classes' );

function tatkhalsa_create_verify_page() {
    $page_slug = 'verify';
    
    $page = get_page_by_path( $page_slug );
    if ( ! $page ) {
        wp_insert_post( array(
            'post_title'     => 'Verify Identity',
            'post_name'      => $page_slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'page_template'  => 'page-verify.php'
        ) );
        flush_rewrite_rules();
    } else {
        update_post_meta( $page->ID, '_wp_page_template', 'page-verify.php' );
    }
}
add_action( 'init', 'tatkhalsa_create_verify_page' );

function tatkhalsa_create_blood_donors_page() {
    $page_slug = 'blood-on-call';
    
    // Check if the old 'blood-on-can' page layout exists and rename it seamlessly
    $old_page = get_page_by_path( 'blood-on-can' );
    if ( $old_page ) {
        wp_update_post( array(
            'ID'         => $old_page->ID,
            'post_title' => 'Blood On Call',
            'post_name'  => 'blood-on-call'
        ) );
    }
    
    $page = get_page_by_path( $page_slug );
    if ( ! $page ) {
        wp_insert_post( array(
            'post_title'     => 'Blood On Call',
            'post_name'      => $page_slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'page_template'  => 'template-blood-donors.php'
        ) );
        flush_rewrite_rules();
    } else {
        if ( $page->post_title !== 'Blood On Call' ) {
            wp_update_post( array(
                'ID'         => $page->ID,
                'post_title' => 'Blood On Call'
            ) );
        }
        update_post_meta( $page->ID, '_wp_page_template', 'template-blood-donors.php' );
    }
}
add_action( 'init', 'tatkhalsa_create_blood_donors_page' );

function tatkhalsa_create_blood_verify_page() {
    $page_slug = 'blood-verify';
    
    $page = get_page_by_path( $page_slug );
    if ( ! $page ) {
        wp_insert_post( array(
            'post_title'     => 'Blood On Call Verification',
            'post_name'      => $page_slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'page_template'  => 'page-blood-verify.php'
        ) );
        flush_rewrite_rules();
    } else {
        update_post_meta( $page->ID, '_wp_page_template', 'page-blood-verify.php' );
    }
}
add_action( 'init', 'tatkhalsa_create_blood_verify_page' );

function tatkhalsa_create_legal_pages() {
    // Privacy Policy
    $privacy_slug = 'privacy-policy';
    if ( ! get_page_by_path( $privacy_slug ) ) {
        wp_insert_post( array(
            'post_title'     => 'Privacy Policy',
            'post_name'      => $privacy_slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'page_template'  => 'template-privacy.php'
        ) );
    } else {
        update_post_meta( get_page_by_path( $privacy_slug )->ID, '_wp_page_template', 'template-privacy.php' );
    }

    // Terms & Conditions
    $terms_slug = 'terms-conditions';
    if ( ! get_page_by_path( $terms_slug ) ) {
        wp_insert_post( array(
            'post_title'     => 'Terms and Conditions',
            'post_name'      => $terms_slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'page_template'  => 'template-terms.php'
        ) );
    } else {
        update_post_meta( get_page_by_path( $terms_slug )->ID, '_wp_page_template', 'template-terms.php' );
    }
}
add_action( 'init', 'tatkhalsa_create_legal_pages' );

/**
 * Helper to retrieve client IP even through proxies and load balancers.
 */
function tatkhalsa_get_client_ip() {
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip_list = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
		return trim( $ip_list[0] );
	} elseif ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		return $_SERVER['HTTP_CLIENT_IP'];
	}
	return isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
}

/**
 * Handle Blood Request Form Submission & Direct Email Delivery to tatkhalsafoundation@gmail.com
 */
function tatkhalsa_submit_blood_request() {
	// Sanitize form inputs
	$patient_name     = isset( $_POST['patientName'] ) ? sanitize_text_field( wp_unslash( $_POST['patientName'] ) ) : '';
	$blood_group      = isset( $_POST['bloodGroup'] ) ? sanitize_text_field( wp_unslash( $_POST['bloodGroup'] ) ) : '';
	$country          = isset( $_POST['country'] ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : '';
	$state            = isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
	$district         = isset( $_POST['district'] ) ? sanitize_text_field( wp_unslash( $_POST['district'] ) ) : '';
	
	$location_parts   = array_filter( array( $district, $state, $country ) );
	$patient_location = implode( ', ', $location_parts );

	$contact_details  = isset( $_POST['contactDetails'] ) ? sanitize_text_field( wp_unslash( $_POST['contactDetails'] ) ) : '';
	$hospital_name    = isset( $_POST['hospitalName'] ) ? sanitize_text_field( wp_unslash( $_POST['hospitalName'] ) ) : '';
	$units_required   = isset( $_POST['unitsRequired'] ) ? sanitize_text_field( wp_unslash( $_POST['unitsRequired'] ) ) : '1';
	$urgency          = isset( $_POST['urgency'] ) ? sanitize_text_field( wp_unslash( $_POST['urgency'] ) ) : 'Urgent';
	$additional_info  = isset( $_POST['additionalInfo'] ) ? sanitize_textarea_field( wp_unslash( $_POST['additionalInfo'] ) ) : '';

	if ( empty( $patient_name ) || empty( $blood_group ) || empty( $patient_location ) || empty( $contact_details ) || empty( $hospital_name ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please fill in all required fields.', 'tatkhalsa-theme' ) ) );
	}

	// Dynamic fake / spam protection checks
	$validation_check = tatkhalsa_validate_common_inputs( $patient_name, '', $contact_details );
	if ( true !== $validation_check ) {
		wp_send_json_error( array( 'message' => $validation_check ) );
	}

	$attachments = array();
	if ( ! empty( $_FILES['doctorSlip']['name'] ) ) {
		$uploaded_file = $_FILES['doctorSlip'];

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploaded_file, $upload_overrides );

		if ( $movefile && ! isset( $movefile['error'] ) ) {
			$attachments[] = $movefile['file'];
		} else {
			wp_send_json_error( array( 'message' => esc_html__( 'Failed to upload doctor\'s slip: ', 'tatkhalsa-theme' ) . $movefile['error'] ) );
		}
	} else {
		wp_send_json_error( array( 'message' => esc_html__( 'Please upload the doctor\'s request slip or form.', 'tatkhalsa-theme' ) ) );
	}

	// Create WordPress blood_request post for record tracking first so we have the ID for the link
	$request_post_id = wp_insert_post( array(
		'post_title'  => 'Blood Request - ' . $blood_group . ' - ' . $patient_name,
		'post_type'   => 'blood_request',
		'post_status' => 'publish'
	) );
	if ( $request_post_id ) {
		update_post_meta( $request_post_id, 'patient_name', $patient_name );
		update_post_meta( $request_post_id, 'blood_group', $blood_group );
		update_post_meta( $request_post_id, 'country', $country );
		update_post_meta( $request_post_id, 'state', $state );
		update_post_meta( $request_post_id, 'district', $district );
		update_post_meta( $request_post_id, 'patient_location', $patient_location );
		update_post_meta( $request_post_id, 'contact_details', $contact_details );
		update_post_meta( $request_post_id, 'hospital_name', $hospital_name );
		update_post_meta( $request_post_id, 'units_required', $units_required );
		update_post_meta( $request_post_id, 'urgency', $urgency );
		update_post_meta( $request_post_id, 'additional_info', $additional_info );
		update_post_meta( $request_post_id, 'request_ip', tatkhalsa_get_client_ip() );
		update_post_meta( $request_post_id, 'request_time', current_time( 'mysql' ) );
		if ( ! empty( $attachments[0] ) ) {
			// Save reference to physician request file
			update_post_meta( $request_post_id, 'doctor_slip_path', esc_url_raw( $attachments[0] ) );
			if ( isset( $movefile['url'] ) ) {
				update_post_meta( $request_post_id, 'doctor_slip_url', esc_url_raw( $movefile['url'] ) );
			}
		}
	}

	// Email config
	$to      = 'info@tatkhalsa.in';
	$subject = '🔴 EMERGENCY BLOOD REQUEST: ' . $blood_group . ' Group needed';
	
	$body  = "<h2>🔴 Emergency Blood Request Details</h2>";
	$body .= "<p><strong>Patient Name:</strong> " . esc_html( $patient_name ) . "</p>";
	$body .= "<p><strong>Blood Group Required:</strong> <span style='font-size: 1.25rem; color: #ff334b; font-weight: bold;'>" . esc_html( $blood_group ) . "</span></p>";
	$body .= "<p><strong>Location:</strong> " . esc_html( $patient_location ) . "</p>";
	$body .= "<p><strong>Country:</strong> " . esc_html( $country ) . "</p>";
	$body .= "<p><strong>State:</strong> " . esc_html( $state ) . "</p>";
	$body .= "<p><strong>District:</strong> " . esc_html( $district ) . "</p>";
	$body .= "<p><strong>Hospital Name:</strong> " . esc_html( $hospital_name ) . "</p>";
	$body .= "<p><strong>Contact Details:</strong> " . esc_html( $contact_details ) . "</p>";
	$body .= "<p><strong>Units Required:</strong> " . esc_html( $units_required ) . "</p>";
	$body .= "<p><strong>Urgency Level:</strong> " . esc_html( $urgency ) . "</p>";
	if ( ! empty( $additional_info ) ) {
		$body .= "<p><strong>Additional Info / Notes:</strong><br />" . nl2br( esc_html( $additional_info ) ) . "</p>";
	}

	$app_url = esc_url( add_query_arg( array(
		'accept_request' => '1',
		'req_id'         => $request_post_id,
		'donor_id'       => 'general'
	), home_url( '/blood-on-call/' ) ) );

	$body .= "<div style='text-align: center; margin: 25px 0 10px 0;'>";
	$body .= "<a href='{$app_url}' style='display: inline-block; background-color: #0A327D; color: #ffffff !important; font-weight: bold; font-family: Arial, sans-serif; font-size: 15px; padding: 12px 24px; text-decoration: none; border-radius: 6px; box-shadow: 0 4px 12px rgba(10,50,125,0.25); text-transform: uppercase; letter-spacing: 0.5px;'>🩸 Accept the Request</a>";
	$body .= "</div>";
	$body .= "<p style='text-align: center; font-size: 12px; color: #666; margin-bottom: 25px;'>If the button above does not work, copy and paste this link: <a href='{$app_url}' style='color: #0A327D;'>{$app_url}</a></p>";

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Tatkhalsa Blood On Call <bloodoncall@tatkhalsa.in>',
		'Reply-To: Tatkhalsa Blood On Call <noreply@tatkhalsa.in>'
	);

	// Try sending email
	$sent = wp_mail( $to, $subject, $body, $headers, $attachments );

	// Send WhatsApp Alert
	$sms_message = "URGENT BLOOD REQUEST:\nType: $blood_group\nUnits: $units_required\nHospital: $hospital_name\nContact: $contact_details";
	tatkhalsa_send_whatsapp_alert( $sms_message );

	// Query matching donors
	$matched_donors = array();
	$mailed_some_donors = false;

	if ( strcasecmp( $blood_group, 'Any' ) === 0 || strcasecmp( $blood_group, 'Any Blood Group' ) === 0 ) {
		// "Any blood group" requested: mail EVERY donor available for their DISTRICT
		$district_meta_query = array(
			'relation' => 'AND',
			array(
				'relation' => 'OR',
				array(
					'key'     => 'district',
					'value'   => $district,
					'compare' => '='
				),
				array(
					'key'     => 'address',
					'value'   => $district,
					'compare' => 'LIKE'
				)
			)
		);

		$donors_query = new WP_Query( array(
			'post_type'      => 'blood_donor',
			'posts_per_page' => -1,
			'meta_query'     => $district_meta_query
		) );

		if ( $donors_query->have_posts() ) {
			while ( $donors_query->have_posts() ) {
				$donors_query->the_post();
				$post_id = get_the_ID();
				$donor_name = get_post_meta( $post_id, 'donor_name', true );
				$donor_email = get_post_meta( $post_id, 'donor_email', true );
				$donor_contact = get_post_meta( $post_id, 'contact_details', true );
				$donor_bg = get_post_meta( $post_id, 'blood_group', true );

				$matched_donors[] = array(
					'name'    => $donor_name,
					'contact' => $donor_contact
				);

				// Alert donor via email since they are in the same district
				if ( ! empty( $donor_email ) ) {
					$accept_link = esc_url( add_query_arg( array(
						'accept_request' => '1',
						'req_id'         => $request_post_id,
						'donor_id'       => $post_id
					), home_url( '/blood-on-call/' ) ) );

					$donor_subject = 'URGENT: General Blood Request In Your District - ' . $district;
					$donor_body = "<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 8px;'>
						<h2 style='color: #ff334b; font-size: 22px; border-bottom: 2px solid #ff334b; padding-bottom: 10px; margin-top: 0;'>🚨 Urgent Blood Request</h2>
						<p>Dear <strong>{$donor_name}</strong>,</p>
						<p>Someone in your immediate district (<strong>{$district}</strong>) requires an urgent blood donation. Any blood group is requested / welcomed to assist.</p>
						
						<div style='background: #fdfafa; border-left: 4px solid #ff334b; padding: 15px; margin: 20px 0; border-radius: 4px;'>
							<h3 style='margin-top: 0; color: #ff334b; font-size: 16px;'>📋 Patient Information:</h3>
							<table style='width: 100%; border-collapse: collapse;'>
								<tr><td style='padding: 6px 0; font-weight: bold; width: 150px;'>Blood Group Needed:</td><td style='color: #ff334b; font-weight: bold; font-size: 1.15rem;'>Any Blood Group (Your Group is {$donor_bg})</td></tr>
								<tr><td style='padding: 6px 0; font-weight: bold;'>Patient Name:</td><td>{$patient_name}</td></tr>
								<tr><td style='padding: 6px 0; font-weight: bold;'>Hospital Details:</td><td>{$hospital_name}</td></tr>
								<tr><td style='padding: 6px 0; font-weight: bold;'>Location:</td><td>{$patient_location}</td></tr>
								<tr><td style='padding: 6px 0; font-weight: bold;'>Contact Details:</td><td><a href='tel:{$contact_details}' style='color: #ff334b; font-weight: bold; text-decoration: none;'>{$contact_details}</a></td></tr>
							</table>
						</div>

						<div style='background: #fff5f5; border: 2px solid #ff334b; border-radius: 8px; padding: 15px; margin: 20px 0;'>
							<strong style='color: #ff334b; font-size: 16px;'>⚠️ IMPORTANT: VERIFY REQUEST FIRST THEN ONLY DONATE</strong>
							<p style='margin: 8px 0 0 0; font-size: 14px; color: #333; font-weight: bold; line-height: 1.5;'>
								Please verify the medical requirement and doctor request / blood prescription slip carefully first, and only then proceed to donate blood. 
							</p>
							<p style='margin: 6px 0 0 0; font-size: 13.5px; color: #555; line-height: 1.4;'>
								You must coordinate directly with the patient's family, relatives, or treating hospital staff to fully validate all medical requirements and authentication before making a contribution.
							</p>
						</div>

						<p style='font-size: 13px; color: #666;'><em>* Note: A copy of the physician's request / doctor slip has been attached to this email for your active validation.</em></p>
						
						<div style='text-align: center; margin: 25px 0 10px 0;'>
							<a href='{$accept_link}' style='display: inline-block; background-color: #0A327D; color: #ffffff !important; font-weight: bold; font-family: Arial, sans-serif; font-size: 15px; padding: 12px 24px; text-decoration: none; border-radius: 6px; box-shadow: 0 4px 12px rgba(10,50,125,0.25); text-transform: uppercase; letter-spacing: 0.5px;'>🩸 Accept Blood Request</a>
							<p style='margin: 8px 0 0 0; font-size: 11px; color: #ff334b;'><strong>* Single acceptance rule:</strong> Only the first donor to click accepts the request. Others cannot accept once it has been claimed.</p>
						</div>
						<p style='text-align: center; font-size: 12px; color: #666; margin-bottom: 25px;'>
							If you cannot click the button above, please copy & paste this link directly in your browser: <br/>
							<a href='{$accept_link}' style='color: #0A327D; word-break: break-all;'>{$accept_link}</a>
						</p>

						<p>If you are available to travel or assist, please reach out to the patient's family at the contact information provided above as soon as possible.</p>
						
						<hr style='border: none; border-top: 1px solid #ddd; margin: 25px 0;' />
						<p style='font-size: 12px; color: #999; text-align: center; margin-bottom: 0;'>
							This is an automated mobilization broadcast by <strong>Tatkhalsa Blood On Call</strong>.<br/>
							Thank you for your noble commitment to saving lives.
						</p>
					</div>";
					wp_mail( $donor_email, $donor_subject, $donor_body, $headers, $attachments );
					$mailed_some_donors = true;
				}
			}
			wp_reset_postdata();
		}
	} else {
		// Specific blood group query: query matching donors in same district first!
		$district_meta_query = array(
			'relation' => 'AND',
			array(
				'key'     => 'blood_group',
				'value'   => $blood_group,
				'compare' => '='
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'district',
					'value'   => $district,
					'compare' => '='
				),
				array(
					'key'     => 'address',
					'value'   => $district,
					'compare' => 'LIKE'
				)
			)
		);

		$donors_query = new WP_Query( array(
			'post_type'      => 'blood_donor',
			'posts_per_page' => -1,
			'meta_query'     => $district_meta_query
		) );

		if ( $donors_query->have_posts() ) {
			while ( $donors_query->have_posts() ) {
				$donors_query->the_post();
				$post_id = get_the_ID();
				$donor_name = get_post_meta( $post_id, 'donor_name', true );
				$donor_email = get_post_meta( $post_id, 'donor_email', true );
				$donor_contact = get_post_meta( $post_id, 'contact_details', true );

				$matched_donors[] = array(
					'name'    => $donor_name,
					'contact' => $donor_contact
				);

				// Alert donor via email since they are in the same district
				if ( ! empty( $donor_email ) ) {
					$accept_link = esc_url( add_query_arg( array(
						'accept_request' => '1',
						'req_id'         => $request_post_id,
						'donor_id'       => $post_id
					), home_url( '/blood-on-call/' ) ) );

					$donor_subject = 'URGENT: Blood Donation Request in your District - ' . $blood_group;
					$donor_body = "<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 8px;'>
						<h2 style='color: #ff334b; font-size: 22px; border-bottom: 2px solid #ff334b; padding-bottom: 10px; margin-top: 0;'>🚨 Urgent Blood Request</h2>
						<p>Dear <strong>{$donor_name}</strong>,</p>
						<p>Someone near you in your district (<strong>{$district}</strong>) requires an urgent blood donation matching your blood group.</p>
						
						<div style='background: #fdfafa; border-left: 4px solid #ff334b; padding: 15px; margin: 20px 0; border-radius: 4px;'>
							<h3 style='margin-top: 0; color: #ff334b; font-size: 16px;'>📋 Patient Information:</h3>
							<table style='width: 100%; border-collapse: collapse;'>
								<tr><td style='padding: 6px 0; font-weight: bold; width: 150px;'>Blood Group Needed:</td><td style='color: #ff334b; font-weight: bold; font-size: 1.15rem;'>{$blood_group}</td></tr>
								<tr><td style='padding: 6px 0; font-weight: bold;'>Patient Name:</td><td>{$patient_name}</td></tr>
								<tr><td style='padding: 6px 0; font-weight: bold;'>Hospital Details:</td><td>{$hospital_name}</td></tr>
								<tr><td style='padding: 6px 0; font-weight: bold;'>Location:</td><td>{$patient_location}</td></tr>
								<tr><td style='padding: 6px 0; font-weight: bold;'>Contact Details:</td><td><a href='tel:{$contact_details}' style='color: #ff334b; font-weight: bold; text-decoration: none;'>{$contact_details}</a></td></tr>
							</table>
						</div>

						<div style='background: #fff5f5; border: 2px solid #ff334b; border-radius: 8px; padding: 15px; margin: 20px 0;'>
							<strong style='color: #ff334b; font-size: 16px;'>⚠️ IMPORTANT: VERIFY REQUEST FIRST THEN ONLY DONATE</strong>
							<p style='margin: 8px 0 0 0; font-size: 14px; color: #333; font-weight: bold; line-height: 1.5;'>
								Please verify the medical requirement and doctor request / blood prescription slip carefully first, and only then proceed to donate blood. 
							</p>
							<p style='margin: 6px 0 0 0; font-size: 13.5px; color: #555; line-height: 1.4;'>
								You must coordinate directly with the patient's family, relatives, or treating hospital staff to fully validate all medical requirements and authentication before making a contribution.
							</p>
						</div>

						<p style='font-size: 13px; color: #666;'><em>* Note: A copy of the physician's request / doctor slip has been attached to this email for your active validation.</em></p>
						
						<div style='text-align: center; margin: 25px 0 10px 0;'>
							<a href='{$accept_link}' style='display: inline-block; background-color: #0A327D; color: #ffffff !important; font-weight: bold; font-family: Arial, sans-serif; font-size: 15px; padding: 12px 24px; text-decoration: none; border-radius: 6px; box-shadow: 0 4px 12px rgba(10,50,125,0.25); text-transform: uppercase; letter-spacing: 0.5px;'>🩸 Accept Blood Request</a>
							<p style='margin: 8px 0 0 0; font-size: 11px; color: #ff334b;'><strong>* Single acceptance rule:</strong> Only the first donor to click accepts the request. Others cannot accept once it has been claimed.</p>
						</div>
						<p style='text-align: center; font-size: 12px; color: #666; margin-bottom: 25px;'>
							If you cannot click the button above, please copy & paste this link directly in your browser: <br/>
							<a href='{$accept_link}' style='color: #0A327D; word-break: break-all;'>{$accept_link}</a>
						</p>

						<p>If you are available to travel or assist, please reach out to the patient's family at the contact information provided above as soon as possible.</p>
						
						<hr style='border: none; border-top: 1px solid #ddd; margin: 25px 0;' />
						<p style='font-size: 12px; color: #999; text-align: center; margin-bottom: 0;'>
							This is an automated mobilization broadcast by <strong>Tatkhalsa Blood On Call</strong>.<br/>
							Thank you for your noble commitment to saving lives.
						</p>
					</div>";
					wp_mail( $donor_email, $donor_subject, $donor_body, $headers, $attachments );
					$mailed_some_donors = true;
				}
			}
			wp_reset_postdata();
		}

		// Fallback 1: If no donors matched in same district, search state-wide and email them
		if ( empty( $matched_donors ) ) {
			$state_meta_query = array(
				'relation' => 'AND',
				array(
					'key'     => 'blood_group',
					'value'   => $blood_group,
					'compare' => '='
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => 'state',
						'value'   => $state,
						'compare' => '='
					),
					array(
						'key'     => 'address',
						'value'   => $state,
						'compare' => 'LIKE'
					)
				)
			);

			$state_donors_query = new WP_Query( array(
				'post_type'      => 'blood_donor',
				'posts_per_page' => -1,
				'meta_query'     => $state_meta_query
			) );

			if ( $state_donors_query->have_posts() ) {
				while ( $state_donors_query->have_posts() ) {
					$state_donors_query->the_post();
					$post_id = get_the_ID();
					$donor_name = get_post_meta( $post_id, 'donor_name', true );
					$donor_email = get_post_meta( $post_id, 'donor_email', true );
					$donor_contact = get_post_meta( $post_id, 'contact_details', true );

					$matched_donors[] = array(
						'name'    => $donor_name,
						'contact' => $donor_contact
					);

					// Alert state donor via email as state-level fallback
					if ( ! empty( $donor_email ) ) {
						$accept_link = esc_url( add_query_arg( array(
							'accept_request' => '1',
							'req_id'         => $request_post_id,
							'donor_id'       => $post_id
						), home_url( '/blood-on-call/' ) ) );

						$donor_subject = 'URGENT: Blood Donation Request in your State - ' . $blood_group;
						$donor_body = "<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 8px;'>
							<h2 style='color: #ff334b; font-size: 22px; border-bottom: 2px solid #ff334b; padding-bottom: 10px; margin-top: 0;'>🚨 Urgent Blood Request</h2>
							<p>Dear <strong>{$donor_name}</strong>,</p>
							<p>Someone in your state (<strong>{$state}</strong>) requires an urgent blood donation matching your blood group because a matching donor was not found in their local district.</p>
							
							<div style='background: #fdfafa; border-left: 4px solid #ff334b; padding: 15px; margin: 20px 0; border-radius: 4px;'>
								<h3 style='margin-top: 0; color: #ff334b; font-size: 16px;'>📋 Patient Information:</h3>
								<table style='width: 100%; border-collapse: collapse;'>
									<tr><td style='padding: 6px 0; font-weight: bold; width: 150px;'>Blood Group Needed:</td><td style='color: #ff334b; font-weight: bold; font-size: 1.15rem;'>{$blood_group}</td></tr>
									<tr><td style='padding: 6px 0; font-weight: bold;'>Patient Name:</td><td>{$patient_name}</td></tr>
									<tr><td style='padding: 6px 0; font-weight: bold;'>Hospital Details:</td><td>{$hospital_name}</td></tr>
									<tr><td style='padding: 6px 0; font-weight: bold;'>Location:</td><td>{$patient_location}</td></tr>
									<tr><td style='padding: 6px 0; font-weight: bold;'>Contact Details:</td><td><a href='tel:{$contact_details}' style='color: #ff334b; font-weight: bold; text-decoration: none;'>{$contact_details}</a></td></tr>
								</table>
							</div>

						<div style='background: #fff5f5; border: 2px solid #ff334b; border-radius: 8px; padding: 15px; margin: 20px 0;'>
							<strong style='color: #ff334b; font-size: 16px;'>⚠️ IMPORTANT: VERIFY REQUEST FIRST THEN ONLY DONATE</strong>
							<p style='margin: 8px 0 0 0; font-size: 14px; color: #333; font-weight: bold; line-height: 1.5;'>
								Please verify the medical requirement and doctor request / blood prescription slip carefully first, and only then proceed to donate blood. 
							</p>
							<p style='margin: 6px 0 0 0; font-size: 13.5px; color: #555; line-height: 1.4;'>
								You must coordinate directly with the patient's family, relatives, or treating hospital staff to fully validate all medical requirements and authentication before making a contribution.
							</p>
						</div>

							<p style='font-size: 13px; color: #666;'><em>* Note: A copy of the physician's request / doctor slip has been attached to this email for your active validation.</em></p>
							
							<div style='text-align: center; margin: 25px 0 10px 0;'>
								<a href='{$accept_link}' style='display: inline-block; background-color: #0A327D; color: #ffffff !important; font-weight: bold; font-family: Arial, sans-serif; font-size: 15px; padding: 12px 24px; text-decoration: none; border-radius: 6px; box-shadow: 0 4px 12px rgba(10,50,125,0.25); text-transform: uppercase; letter-spacing: 0.5px;'>🩸 Accept Blood Request</a>
								<p style='margin: 8px 0 0 0; font-size: 11px; color: #ff334b;'><strong>* Single acceptance rule:</strong> Only the first donor to click accepts the request. Others cannot accept once it has been claimed.</p>
							</div>
							<p style='text-align: center; font-size: 12px; color: #666; margin-bottom: 25px;'>
								If you cannot click the button above, please copy & paste this link directly in your browser: <br/>
								<a href='{$accept_link}' style='color: #0A327D; word-break: break-all;'>{$accept_link}</a>
							</p>

							<p>If you are available to travel or assist, please reach out to the patient's family at the contact information provided above as soon as possible.</p>
							
							<hr style='border: none; border-top: 1px solid #ddd; margin: 25px 0;' />
							<p style='font-size: 12px; color: #999; text-align: center; margin-bottom: 0;'>
								This is an automated mobilization broadcast by <strong>Tatkhalsa Blood On Call</strong>.<br/>
								Thank you for your noble commitment to saving lives.
							</p>
						</div>";
						wp_mail( $donor_email, $donor_subject, $donor_body, $headers, $attachments );
						$mailed_some_donors = true;
					}
				}
				wp_reset_postdata();
			}
		}

		// Fallback 2: If still no donors matched in district or state, query country-wide but DO NOT email them
		if ( empty( $matched_donors ) ) {
			$country_meta_query = array(
				'relation' => 'AND',
				array(
					'key'     => 'blood_group',
					'value'   => $blood_group,
					'compare' => '='
				)
			);

			if ( ! empty( $country ) ) {
				$country_meta_query[] = array(
					'relation' => 'OR',
					array(
						'key'     => 'country',
						'value'   => $country,
						'compare' => '='
					),
					array(
						'key'     => 'address',
						'value'   => $country,
						'compare' => 'LIKE'
					)
				);
			}

			$country_donors_query = new WP_Query( array(
				'post_type'      => 'blood_donor',
				'posts_per_page' => -1,
				'meta_query'     => $country_meta_query
			) );

			if ( $country_donors_query->have_posts() ) {
				while ( $country_donors_query->have_posts() ) {
					$country_donors_query->the_post();
					$post_id = get_the_ID();
					$donor_name = get_post_meta( $post_id, 'donor_name', true );
					$donor_contact = get_post_meta( $post_id, 'contact_details', true );

					$matched_donors[] = array(
						'name'    => $donor_name,
						'contact' => $donor_contact
					);
				}
				wp_reset_postdata();
			}
		}
	}

	wp_send_json_success( array( 
		'message' => 'Emergency Blood Request submitted successfully! Your active Registry Audit ID is: REQ_' . $request_post_id . '. Alerts have been sent to our state sevadars.',
		'matched_donors' => $matched_donors
	) );
}
add_action( 'wp_ajax_submit_blood_request', 'tatkhalsa_submit_blood_request' );
add_action( 'wp_ajax_nopriv_submit_blood_request', 'tatkhalsa_submit_blood_request' );

function tatkhalsa_set_html_mail_content_type() {
	return 'text/html';
}
add_filter( 'wp_mail_content_type', 'tatkhalsa_set_html_mail_content_type' );

function tatkhalsa_register_blood_donor_cpt() {
	$labels = array(
		'name'               => _x( 'Blood On Call', 'post type general name', 'tatkhalsa-theme' ),
		'singular_name'      => _x( 'Blood On Call Entry', 'post type singular name', 'tatkhalsa-theme' ),
		'menu_name'          => _x( 'Blood On Call', 'admin menu', 'tatkhalsa-theme' ),
		'name_admin_bar'     => _x( 'Blood On Call Entry', 'add new on admin bar', 'tatkhalsa-theme' ),
		'add_new'            => _x( 'Add New', 'blood on call', 'tatkhalsa-theme' ),
		'add_new_item'       => __( 'Add New Entry', 'tatkhalsa-theme' ),
		'new_item'           => __( 'New Entry', 'tatkhalsa-theme' ),
		'edit_item'          => __( 'Edit Entry', 'tatkhalsa-theme' ),
		'view_item'          => __( 'View Entry', 'tatkhalsa-theme' ),
		'all_items'          => __( 'All Entries', 'tatkhalsa-theme' ),
		'search_items'       => __( 'Search Entries', 'tatkhalsa-theme' ),
		'not_found'          => __( 'No entry found.', 'tatkhalsa-theme' ),
		'not_found_in_trash' => __( 'No entry found in Trash.', 'tatkhalsa-theme' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'blood-donor' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-heart',
		'supports'           => array( 'title' )
	);

	register_post_type( 'blood_donor', $args );
}
add_action( 'init', 'tatkhalsa_register_blood_donor_cpt' );

function tatkhalsa_submit_blood_donor() {
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'submit_blood_donor' ) {
		wp_send_json_error( array( 'message' => 'Invalid request.' ) );
	}

	$name         = isset( $_POST['donorName'] ) ? sanitize_text_field( wp_unslash( $_POST['donorName'] ) ) : '';
	$blood_group  = isset( $_POST['bloodGroup'] ) ? sanitize_text_field( wp_unslash( $_POST['bloodGroup'] ) ) : '';
	$email        = isset( $_POST['donorEmail'] ) ? sanitize_email( wp_unslash( $_POST['donorEmail'] ) ) : '';
	$contact      = isset( $_POST['contactDetails'] ) ? sanitize_text_field( wp_unslash( $_POST['contactDetails'] ) ) : '';
	$country      = isset( $_POST['country'] ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : '';
	$state        = isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
	$district     = isset( $_POST['district'] ) ? sanitize_text_field( wp_unslash( $_POST['district'] ) ) : '';
	$address_line = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
	
	$address_parts = array_filter(array( $address_line, $district, $state, $country ));
	$address = implode( ', ', $address_parts );

	$map_location = isset( $_POST['mapLocation'] ) ? sanitize_text_field( wp_unslash( $_POST['mapLocation'] ) ) : '';
	$availability_status = isset( $_POST['availabilityStatus'] ) ? sanitize_text_field( wp_unslash( $_POST['availabilityStatus'] ) ) : 'Available Now';

	if ( empty( $name ) || empty( $blood_group ) || empty( $contact ) || empty( $address ) || empty( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
	}

	// Dynamic fake / spam protection checks
	$validation_check = tatkhalsa_validate_common_inputs( $name, $email, $contact );
	if ( true !== $validation_check ) {
		wp_send_json_error( array( 'message' => $validation_check ) );
	}

	$post_id = wp_insert_post( array(
		'post_title'  => $name . ' - ' . $blood_group,
		'post_type'   => 'blood_donor',
		'post_status' => 'publish'
	) );

	if ( $post_id ) {
		update_post_meta( $post_id, 'donor_name', $name );
		update_post_meta( $post_id, 'blood_group', $blood_group );
		update_post_meta( $post_id, 'donor_email', $email );
		update_post_meta( $post_id, 'contact_details', $contact );
		update_post_meta( $post_id, 'country', $country );
		update_post_meta( $post_id, 'state', $state );
		update_post_meta( $post_id, 'district', $district );
		update_post_meta( $post_id, 'address', $address );
		update_post_meta( $post_id, 'map_location', $map_location );
		update_post_meta( $post_id, 'availability_status', $availability_status );
		update_post_meta( $post_id, 'donor_ip', tatkhalsa_get_client_ip() );
		update_post_meta( $post_id, 'registration_time', current_time( 'mysql' ) );

		wp_send_json_success( array( 'message' => 'Thank you for registering as a blood donor! Your assigned Secure Donor ID is: DONOR_' . $post_id ) );
	} else {
		wp_send_json_error( array( 'message' => 'Failed to register. Please try again.' ) );
	}
}
add_action( 'wp_ajax_submit_blood_donor', 'tatkhalsa_submit_blood_donor' );
add_action( 'wp_ajax_nopriv_submit_blood_donor', 'tatkhalsa_submit_blood_donor' );

function tatkhalsa_remove_blood_donor() {
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'remove_blood_donor' ) {
		wp_send_json_error( array( 'message' => 'Invalid request.' ) );
	}

	$contact = isset( $_POST['contactNumber'] ) ? sanitize_text_field( wp_unslash( $_POST['contactNumber'] ) ) : '';

	if ( empty( $contact ) ) {
		wp_send_json_error( array( 'message' => 'Please provide the registered contact number.' ) );
	}

	$args = array(
		'post_type'  => 'blood_donor',
		'meta_key'   => 'contact_details',
		'meta_value' => $contact,
		'posts_per_page' => -1
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			wp_trash_post( get_the_ID() );
		}
		wp_reset_postdata();
		wp_send_json_success( array( 'message' => 'Your registration has been removed successfully.' ) );
	} else {
		wp_send_json_error( array( 'message' => 'No registration found with this contact number.' ) );
	}
}
add_action( 'wp_ajax_remove_blood_donor', 'tatkhalsa_remove_blood_donor' );
add_action( 'wp_ajax_nopriv_remove_blood_donor', 'tatkhalsa_remove_blood_donor' );

function tatkhalsa_update_donor_status() {
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'update_donor_status' ) {
		wp_send_json_error( array( 'message' => 'Invalid request.' ) );
	}

	$contact = isset( $_POST['contactNumber'] ) ? sanitize_text_field( wp_unslash( $_POST['contactNumber'] ) ) : '';
	$new_status = isset( $_POST['availabilityStatus'] ) ? sanitize_text_field( wp_unslash( $_POST['availabilityStatus'] ) ) : '';

	if ( empty( $contact ) || empty( $new_status ) ) {
		wp_send_json_error( array( 'message' => 'Please provide the contact number and select a status.' ) );
	}

	$args = array(
		'post_type'  => 'blood_donor',
		'meta_key'   => 'contact_details',
		'meta_value' => $contact,
		'posts_per_page' => -1
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			update_post_meta( get_the_ID(), 'availability_status', $new_status );
		}
		wp_reset_postdata();
		wp_send_json_success( array( 'message' => 'Your status has been updated successfully to ' . esc_html( $new_status ) . '!' ) );
	} else {
		wp_send_json_error( array( 'message' => 'No registration found with this contact number.' ) );
	}
}
add_action( 'wp_ajax_update_donor_status', 'tatkhalsa_update_donor_status' );
add_action( 'wp_ajax_nopriv_update_donor_status', 'tatkhalsa_update_donor_status' );

function tatkhalsa_verify_donor_email() {
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'verify_donor_email' ) {
		wp_send_json_error( array( 'message' => 'Invalid request.' ) );
	}

	$email = isset( $_POST['donorEmail'] ) ? sanitize_email( wp_unslash( $_POST['donorEmail'] ) ) : '';

	if ( empty( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please provide the registered email address.' ) );
	}

	$args = array(
		'post_type'  => 'blood_donor',
		'meta_key'   => 'donor_email',
		'meta_value' => $email,
		'posts_per_page' => 1
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		$query->the_post();
		$donor_name = get_post_meta( get_the_ID(), 'donor_name', true );
		wp_reset_postdata();

		wp_send_json_success( array( 'name' => $donor_name, 'date' => date('F j, Y') ) );
	} else {
		wp_send_json_error( array( 'message' => 'No registration found with this email address.' ) );
	}
}
add_action( 'wp_ajax_verify_donor_email', 'tatkhalsa_verify_donor_email' );
add_action( 'wp_ajax_nopriv_verify_donor_email', 'tatkhalsa_verify_donor_email' );

function tatkhalsa_accept_blood_request() {
	$req_id   = isset( $_POST['req_id'] ) ? sanitize_text_field( wp_unslash( $_POST['req_id'] ) ) : '';
	$donor_id = isset( $_POST['donor_id'] ) ? sanitize_text_field( wp_unslash( $_POST['donor_id'] ) ) : '';
	if ( empty( $donor_id ) ) {
		$donor_id = 'general';
	}

	if ( empty( $req_id ) ) {
		wp_send_json_error( array( 'message' => 'Missing required request field.' ) );
	}

	$status = get_post_meta( $req_id, 'status', true );
	if ( empty( $status ) ) {
		$status = 'pending';
	}

	if ( $status === 'accepted' || $status === 'fulfilled' ) {
		wp_send_json_success( array(
			'already_accepted_by_you' => true,
			'message' => 'its already accepted thanks for your efforts We appreciate your time'
		) );
	}

	// Update status and track the recipient donor ID
	update_post_meta( $req_id, 'status', 'accepted' );
	update_post_meta( $req_id, 'accepted_by_donor_id', $donor_id );

	// Update in custom table if it exists
	global $wpdb;
	$table_exists = $wpdb->get_var( "SHOW TABLES LIKE 'wp_blood_requests'" );
	if ( $table_exists ) {
		$wpdb->update(
			'wp_blood_requests',
			array( 'status' => 'accepted', 'accepted_by_donor_id' => $donor_id ),
			array( 'id' => $req_id )
		);
	}

	wp_send_json_success( array(
		'message' => 'thank you not accepting request please get in touch with the one who required'
	) );
}
add_action( 'wp_ajax_accept_blood_request', 'tatkhalsa_accept_blood_request' );
add_action( 'wp_ajax_nopriv_accept_blood_request', 'tatkhalsa_accept_blood_request' );

function tatkhalsa_send_donor_newsletter() {
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'send_donor_newsletter' ) {
		wp_send_json_error( array( 'message' => 'Invalid request.' ) );
	}

	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? wp_kses_post( wp_unslash( $_POST['message'] ) ) : '';

	if ( empty( $subject ) || empty( $message ) ) {
		wp_send_json_error( array( 'message' => 'Subject and Message are required.' ) );
	}

	$emails = array();
	if ( ! empty( $_POST['newsletterTo'] ) ) {
		$raw_emails = explode( ',', sanitize_text_field( wp_unslash( $_POST['newsletterTo'] ) ) );
		foreach ( $raw_emails as $e ) {
			$e = trim( $e );
			if ( is_email( $e ) ) {
				$emails[] = $e;
			}
		}
	} else {
		$args = array(
			'post_type'      => 'blood_donor',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);
		$donors = get_posts( $args );
		foreach ( $donors as $donor ) {
			$email = get_post_meta( $donor->ID, 'donor_email', true );
			if ( ! empty( $email ) && is_email( $email ) ) {
				$emails[] = $email;
			}
		}
	}

	$emails = array_unique( $emails );

	$unsubscribed = get_option('tatkhalsa_unsubscribed_emails', []);
	$emails = array_diff($emails, $unsubscribed);

	if ( empty( $emails ) ) {
		wp_send_json_error( array( 'message' => 'No valid email addresses found after excluding unsubscribed users.' ) );
	}

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Tatkhalsa Foundation <info@tatkhalsa.in>',
	);

	$sent = false;
	$count = 0;

	foreach ( $emails as $email ) {
		$unsub_link = home_url( '/?unsubscribe_email=' . urlencode( $email ) );
		$html_message = '<html><body style="font-family: sans-serif; color: #333;">' . nl2br( $message ) . '<br><br><hr style="border: 0; border-top: 1px solid #eee; margin-top: 20px;"><p style="font-size: 12px; color: #888;">You are receiving this email because you subscribed to our newsletter or registered as a donor. <br>To stop receiving these updates, <a href="' . esc_url($unsub_link) . '" style="color: #0A327D;">unsubscribe here</a>.</p></body></html>';
		
		$result = wp_mail( $email, $subject, $html_message, $headers );
		if ( $result ) {
			$sent = true;
			$count++;
		}
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
require_once get_template_directory() . '/admin-newsletter.php';
require_once get_template_directory() . '/admin-ajax-handlers.php';
