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
 * Check whether the current request should stay visible.
 *
 * @return bool
 */
function dondog_allow_current_request() {
	if ( is_user_logged_in() ) {
		return true;
	}

	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return true;
	}

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return true;
	}

	return false;
}

/**
 * Show the custom 404 page to every public front-end visitor.
 *
 * @return void
 */
function dondog_show_404_to_public_visitors() {
	if ( dondog_allow_current_request() ) {
		return;
	}

	global $wp_query;

	if ( $wp_query ) {
		$wp_query->set_404();
	}

	status_header( 404 );
	nocache_headers();

	include get_query_template( '404' );
	exit;
}
add_action( 'template_redirect', 'dondog_show_404_to_public_visitors', 0 );
