<?php
/**
 * Theme shortcode loader.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load a shortcode file if it exists.
 *
 * @param string $file Shortcode file name.
 * @return void
 */
function dondog_load_shortcode_file( $file ) {
	$path = __DIR__ . '/shortcodes/' . $file;

	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

dondog_load_shortcode_file( 'hero.php' );
dondog_load_shortcode_file( 'header.php' );
dondog_load_shortcode_file( 'before-after.php' );
dondog_load_shortcode_file( 'button.php' );
dondog_load_shortcode_file( 'footer.php' );
dondog_load_shortcode_file( 'language-switcher.php' );
