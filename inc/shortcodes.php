<?php
/**
 * Theme shortcode loader.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/shortcodes/hero.php';
require_once __DIR__ . '/shortcodes/before-after.php';
require_once __DIR__ . '/shortcodes/button.php';
require_once __DIR__ . '/shortcodes/footer.php';
require_once __DIR__ . '/shortcodes/language-switcher.php';
