<?php
/**
 * Custom 404 template.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

status_header( 404 );
nocache_headers();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'dondog-404-body' ); ?>>
<?php wp_body_open(); ?>
<main class="dondog-404" role="main">
	<div class="dondog-404__art" aria-hidden="true">
		<span class="dondog-404__digit">4</span>
		<span class="dondog-404__paw">
			<span class="dondog-404__toe dondog-404__toe--1"></span>
			<span class="dondog-404__toe dondog-404__toe--2"></span>
			<span class="dondog-404__toe dondog-404__toe--3"></span>
			<span class="dondog-404__toe dondog-404__toe--4"></span>
			<span class="dondog-404__pad"></span>
		</span>
		<span class="dondog-404__digit">4</span>
	</div>

	<p class="dondog-404__eyebrow">Oops</p>
	<h1 class="dondog-404__title">Page not found</h1>
</main>
<?php wp_footer(); ?>
</body>
</html>
