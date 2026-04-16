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
 * Prevent page-cache plugins from storing the hidden public front-end.
 *
 * This only affects requests that reach WordPress. Already cached pages still
 * need to be purged in the cache plugin/server cache.
 *
 * @return void
 */
function dondog_disable_public_cache_when_hidden() {
	if ( dondog_allow_current_request() ) {
		return;
	}

	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}

	if ( ! defined( 'LSCACHE_NO_CACHE' ) ) {
		define( 'LSCACHE_NO_CACHE', true );
	}

	if ( ! headers_sent() ) {
		header( 'X-LiteSpeed-Cache-Control: no-cache' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
	}
}
add_action( 'init', 'dondog_disable_public_cache_when_hidden', 0 );

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

	if ( ! headers_sent() ) {
		header( 'X-LiteSpeed-Cache-Control: no-cache' );
	}

	include get_query_template( '404' );
	exit;
}
add_action( 'template_redirect', 'dondog_show_404_to_public_visitors', 0 );
