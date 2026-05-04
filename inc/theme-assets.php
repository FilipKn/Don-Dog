<?php
/**
 * Theme asset loading.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the shared Playfair Display font family.
 *
 * @return void
 */
function dondog_enqueue_playfair_display() {
	wp_enqueue_style(
		'dondog-playfair-display',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap',
		[],
		null
	);
}

/**
 * Enqueue the hero font with reliable Slovenian character support.
 *
 * @return void
 */
function dondog_enqueue_hero_font() {
	wp_enqueue_style(
		'dondog-lora',
		'https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&display=swap',
		[],
		null
	);
}

/**
 * Enqueue child theme styles.
 *
 * @return void
 */
function dondog_enqueue_theme_styles() {
	dondog_enqueue_playfair_display();

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
			'dondog-playfair-display',
		],
		DONDOG_THEME_VERSION
	);

	if ( is_404() ) {
		wp_enqueue_style(
			'dondog-404-style',
			get_stylesheet_directory_uri() . '/assets/css/404.css',
			[
				'hello-elementor-child-style',
			],
			DONDOG_THEME_VERSION
		);
	}

	if ( dondog_current_page_has_shortcode( 'dondog_hero' ) ) {
		dondog_enqueue_hero_font();

		wp_enqueue_style(
			'dondog-hero-shortcode-style',
			get_stylesheet_directory_uri() . '/assets/css/hero-shortcode.css',
			[
				'hello-elementor-child-style',
				'dondog-lora',
			],
			DONDOG_THEME_VERSION
		);

		wp_enqueue_script(
			'dondog-hero-shortcode',
			get_stylesheet_directory_uri() . '/assets/js/hero-shortcode.js',
			[],
			DONDOG_THEME_VERSION,
			true
		);
	}

	if ( dondog_current_page_has_shortcode( 'dondog_before_after' ) ) {
		wp_enqueue_style(
			'dondog-before-after-shortcode-style',
			get_stylesheet_directory_uri() . '/assets/css/before-after-shortcode.css',
			[
				'hello-elementor-child-style',
			],
			DONDOG_THEME_VERSION
		);
	}

	if ( dondog_current_page_has_shortcode( 'dondog_button' ) ) {
		wp_enqueue_style(
			'dondog-button-shortcode-style',
			get_stylesheet_directory_uri() . '/assets/css/button-shortcode.css',
			[
				'hello-elementor-child-style',
			],
			DONDOG_THEME_VERSION
		);
	}

	if ( dondog_current_page_has_shortcode( 'dondog_footer' ) ) {
		wp_enqueue_style(
			'dondog-footer-shortcode-style',
			get_stylesheet_directory_uri() . '/assets/css/footer-shortcode.css',
			[
				'hello-elementor-child-style',
			],
			DONDOG_THEME_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'dondog_enqueue_theme_styles', 20 );

/**
 * Check if the current page uses one of the theme shortcodes.
 *
 * Checks normal post content and Elementor page data because Elementor stores
 * shortcode widgets inside JSON meta instead of plain post content.
 *
 * @param string $shortcode Shortcode tag without brackets.
 * @return bool
 */
function dondog_current_page_has_shortcode( $shortcode ) {
	if ( ! is_singular() ) {
		return false;
	}

	$post = get_post();

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	if ( has_shortcode( $post->post_content, $shortcode ) || false !== strpos( $post->post_content, '[' . $shortcode ) ) {
		return true;
	}

	$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );

	return is_string( $elementor_data ) && false !== strpos( $elementor_data, $shortcode );
}
