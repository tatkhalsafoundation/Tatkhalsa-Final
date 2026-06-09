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
	}
endif;
add_action( 'after_setup_theme', 'tatkhalsa_setup' );

/**
 * Enqueue scripts and styles.
 */
function tatkhalsa_scripts() {
	// Enqueue main Theme Theme-Stylesheet.
	wp_enqueue_style( 'tatkhalsa-theme-style', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'tatkhalsa_scripts' );
?>
