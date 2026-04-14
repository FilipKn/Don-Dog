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
 * Enqueue child theme styles.
 *
 * @return void
 */
function dondog_enqueue_theme_styles() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
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

	wp_enqueue_style(
		'dondog-hero-shortcode-style',
		get_stylesheet_directory_uri() . '/assets/css/hero-shortcode.css',
		[
			'hello-elementor-child-style',
		],
		DONDOG_THEME_VERSION
	);

	wp_enqueue_style(
		'dondog-before-after-shortcode-style',
		get_stylesheet_directory_uri() . '/assets/css/before-after-shortcode.css',
		[
			'hello-elementor-child-style',
		],
		DONDOG_THEME_VERSION
	);

	wp_enqueue_style(
		'dondog-button-shortcode-style',
		get_stylesheet_directory_uri() . '/assets/css/button-shortcode.css',
		[
			'hello-elementor-child-style',
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
add_action( 'wp_enqueue_scripts', 'dondog_enqueue_theme_styles', 20 );
