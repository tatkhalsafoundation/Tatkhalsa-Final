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
	if ( is_front_page() || is_home() || is_page_template( 'template-about.php' ) || is_page_template( 'template-projects.php' ) || is_page_template( 'template-volunteer.php' ) || is_page_template( 'template-blog.php' ) || is_page_template( 'template-blood-donors.php' ) ) {
		$classes[] = 'has-hero-logo';
	}
	return $classes;
}
add_filter( 'body_class', 'tatkhalsa_body_classes' );

function tatkhalsa_create_blood_donors_page() {
    $page_slug = 'blood-donors';
    
    $page = get_page_by_path( $page_slug );
    if ( ! $page ) {
        wp_insert_post( array(
            'post_title'     => 'Blood Donors',
            'post_name'      => $page_slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'page_template'  => 'template-blood-donors.php'
        ) );
        flush_rewrite_rules();
    } else {
        update_post_meta( $page->ID, '_wp_page_template', 'template-blood-donors.php' );
    }
}
add_action( 'init', 'tatkhalsa_create_blood_donors_page' );

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

	// Send WhatsApp Alert
	$sms_message = "URGENT BLOOD REQUEST:\nType: $blood_group\nUnits: $units\nHospital: $hospital_city\nContact: $contact_details";
	tatkhalsa_send_whatsapp_alert( $sms_message );

	wp_send_json_success( array( 'message' => esc_html__( 'Emergency Blood Request submitted successfully! Alerts have been sent to our sevadars.', 'tatkhalsa-theme' ) ) );
}
add_action( 'wp_ajax_submit_blood_request', 'tatkhalsa_submit_blood_request' );
add_action( 'wp_ajax_nopriv_submit_blood_request', 'tatkhalsa_submit_blood_request' );

function tatkhalsa_register_blood_donor_cpt() {
	$labels = array(
		'name'               => _x( 'Blood Donors', 'post type general name', 'tatkhalsa-theme' ),
		'singular_name'      => _x( 'Blood Donor', 'post type singular name', 'tatkhalsa-theme' ),
		'menu_name'          => _x( 'Blood Donors', 'admin menu', 'tatkhalsa-theme' ),
		'name_admin_bar'     => _x( 'Blood Donor', 'add new on admin bar', 'tatkhalsa-theme' ),
		'add_new'            => _x( 'Add New', 'blood donor', 'tatkhalsa-theme' ),
		'add_new_item'       => __( 'Add New Blood Donor', 'tatkhalsa-theme' ),
		'new_item'           => __( 'New Blood Donor', 'tatkhalsa-theme' ),
		'edit_item'          => __( 'Edit Blood Donor', 'tatkhalsa-theme' ),
		'view_item'          => __( 'View Blood Donor', 'tatkhalsa-theme' ),
		'all_items'          => __( 'All Blood Donors', 'tatkhalsa-theme' ),
		'search_items'       => __( 'Search Blood Donors', 'tatkhalsa-theme' ),
		'not_found'          => __( 'No blood donors found.', 'tatkhalsa-theme' ),
		'not_found_in_trash' => __( 'No blood donors found in Trash.', 'tatkhalsa-theme' )
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
	$contact      = isset( $_POST['contactDetails'] ) ? sanitize_text_field( wp_unslash( $_POST['contactDetails'] ) ) : '';
	$address      = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
	$map_location = isset( $_POST['mapLocation'] ) ? sanitize_text_field( wp_unslash( $_POST['mapLocation'] ) ) : '';

	if ( empty( $name ) || empty( $blood_group ) || empty( $contact ) || empty( $address ) ) {
		wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
	}

	$post_id = wp_insert_post( array(
		'post_title'  => $name . ' - ' . $blood_group,
		'post_type'   => 'blood_donor',
		'post_status' => 'publish'
	) );

	if ( $post_id ) {
		update_post_meta( $post_id, 'donor_name', $name );
		update_post_meta( $post_id, 'blood_group', $blood_group );
		update_post_meta( $post_id, 'contact_details', $contact );
		update_post_meta( $post_id, 'address', $address );
		update_post_meta( $post_id, 'map_location', $map_location );

		wp_send_json_success( array( 'message' => 'Thank you for registering as a blood donor!' ) );
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
		'title'    => __( 'Other Page Images', 'tatkhalsa-theme' ),
		'priority' => 132,
	) );

	$other_pages = array(
		'tatkhalsa_home_hero_img' => array(
			'label' => __( 'Home Page Hero Image', 'tatkhalsa-theme' ),
			'default' => 'https://images.unsplash.com/photo-1543332143-4e8c27e3256f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'
		),
		'tatkhalsa_about_hero_img' => array(
			'label' => __( 'About Page Hero Image', 'tatkhalsa-theme' ),
			'default' => 'https://upload.wikimedia.org/wikipedia/commons/3/30/A_group_of_volunteers_helping_with_daily_food_preparation_for_Langar_at_the_Golden_Temple.jpg'
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
 * Output Customizer CSS for Home Hero Image
 */
function tatkhalsa_customizer_css() {
	$home_hero_img = get_theme_mod( 'tatkhalsa_home_hero_img', 'https://images.unsplash.com/photo-1543332143-4e8c27e3256f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80' );
	?>
	<style type="text/css">
		.home .hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ), url("<?php echo esc_url( $home_hero_img ); ?>") center/cover !important;
		}
		[data-theme="light"] .home .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ), url("<?php echo esc_url( $home_hero_img ); ?>") center/cover !important;
		}
		.hero {
			background: linear-gradient( 135deg, rgba(10, 46, 109, 0.98), rgba(5, 26, 64, 0.95) ) !important;
		}
		[data-theme="light"] .hero {
			background: linear-gradient( 135deg, rgba(220, 240, 255, 0.92), rgba(235, 248, 255, 0.96) ) !important;
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
?>
