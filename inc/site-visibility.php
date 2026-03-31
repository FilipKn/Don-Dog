<?php
/**
 * Front-end visibility rules.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hide the public site from visitors who are not logged in.
 *
 * @return void
 */
function dondog_render_404_for_external_visitors() {
	if ( is_user_logged_in() || is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return;
	}

	$request_uri   = $_SERVER['REQUEST_URI'] ?? '';
	$allowed_paths = [
		'/wp-login.php',
		'/wp-register.php',
		'/favicon.ico',
		'/robots.txt',
	];

	if ( in_array( wp_parse_url( $request_uri, PHP_URL_PATH ), $allowed_paths, true ) ) {
		return;
	}

	global $wp_query;

	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();

	include get_query_template( '404' );
	exit;
}
add_action( 'template_redirect', 'dondog_render_404_for_external_visitors', 0 );
