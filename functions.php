<?php
/**
 * Theme functions and definitions.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'DONDOG_THEME_VERSION', '1.0.64' );

require_once __DIR__ . '/inc/languages.php';
require_once __DIR__ . '/inc/cookieyes-i18n.php';
require_once __DIR__ . '/inc/bookingpress-i18n.php';
require_once __DIR__ . '/inc/theme-assets.php';
require_once __DIR__ . '/inc/site-visibility.php';
require_once __DIR__ . '/inc/shortcodes.php';
require_once __DIR__ . '/inc/login-branding.php';
