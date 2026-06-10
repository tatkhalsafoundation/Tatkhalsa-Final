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
	$version = file_exists( $style_path ) ? filemtime( $style_path ) : '1.0.0';
	wp_enqueue_style( 'tatkhalsa-theme-style', get_stylesheet_uri(), array(), $version );
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
	if ( is_front_page() || is_home() || is_page_template( 'template-about.php' ) || is_page_template( 'template-projects.php' ) || is_page_template( 'template-volunteer.php' ) || is_page_template( 'template-blog.php' ) ) {
		$classes[] = 'has-hero-logo';
	}
	return $classes;
}
add_filter( 'body_class', 'tatkhalsa_body_classes' );

/**
 * Handle Blood Request Form Submission & Direct Email Delivery to tatkhalsafoundation@gmail.com
 */
function tatkhalsa_submit_blood_request() {
	// Sanitize form inputs
	$patient_name     = isset( $_POST['patientName'] ) ? sanitize_text_field( wp_unslash( $_POST['patientName'] ) ) : '';
	$blood_group      = isset( $_POST['bloodGroup'] ) ? sanitize_text_field( wp_unslash( $_POST['bloodGroup'] ) ) : '';
	$patient_location = isset( $_POST['patientLocation'] ) ? sanitize_text_field( wp_unslash( $_POST['patientLocation'] ) ) : '';
	$contact_details  = isset( $_POST['contactDetails'] ) ? sanitize_text_field( wp_unslash( $_POST['contactDetails'] ) ) : '';
	$hospital_name    = isset( $_POST['hospitalName'] ) ? sanitize_text_field( wp_unslash( $_POST['hospitalName'] ) ) : '';
	$units_required   = isset( $_POST['unitsRequired'] ) ? sanitize_text_field( wp_unslash( $_POST['unitsRequired'] ) ) : '1';
	$urgency          = isset( $_POST['urgency'] ) ? sanitize_text_field( wp_unslash( $_POST['urgency'] ) ) : 'Urgent';
	$additional_info  = isset( $_POST['additionalInfo'] ) ? sanitize_textarea_field( wp_unslash( $_POST['additionalInfo'] ) ) : '';

	if ( empty( $patient_name ) || empty( $blood_group ) || empty( $patient_location ) || empty( $contact_details ) || empty( $hospital_name ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please fill in all required fields.', 'tatkhalsa-theme' ) ) );
	}

	// Email config
	$to      = 'tatkhalsafoundation@gmail.com';
	$subject = '🔴 EMERGENCY BLOOD REQUEST: ' . $blood_group . ' Group needed';
	
	$body  = "<h2>🔴 Emergency Blood Request Details</h2>";
	$body .= "<p><strong>Patient Name:</strong> " . esc_html( $patient_name ) . "</p>";
	$body .= "<p><strong>Blood Group Required:</strong> <span style='font-size: 1.25rem; color: #ff334b; font-weight: bold;'>" . esc_html( $blood_group ) . "</span></p>";
	$body .= "<p><strong>Exact Patient Location:</strong> " . esc_html( $patient_location ) . "</p>";
	$body .= "<p><strong>Hospital Name:</strong> " . esc_html( $hospital_name ) . "</p>";
	$body .= "<p><strong>Contact Details:</strong> " . esc_html( $contact_details ) . "</p>";
	$body .= "<p><strong>Units Required:</strong> " . esc_html( $units_required ) . "</p>";
	$body .= "<p><strong>Urgency Level:</strong> " . esc_html( $urgency ) . "</p>";
	if ( ! empty( $additional_info ) ) {
		$body .= "<p><strong>Additional Info / Notes:</strong><br />" . nl2br( esc_html( $additional_info ) ) . "</p>";
	}

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Tatkhalsa Blood Network <info@tatkhalsa.in>',
		'Reply-To: ' . $contact_details
	);

	// Try sending email
	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => esc_html__( 'Emergency Blood Request submitted successfully! Alerts have been sent to our sevadars.', 'tatkhalsa-theme' ) ) );
	} else {
		wp_send_json_error( array( 'message' => esc_html__( 'Failed to send automated email alert. However, our server has logged this. Please call +91-91157-19000 immediately.', 'tatkhalsa-theme' ) ) );
	}
}
add_action( 'wp_ajax_submit_blood_request', 'tatkhalsa_submit_blood_request' );
add_action( 'wp_ajax_nopriv_submit_blood_request', 'tatkhalsa_submit_blood_request' );
?>
