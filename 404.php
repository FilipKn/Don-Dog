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
	<section class="dondog-404__shell" aria-labelledby="dondog-404-title">
		<div class="dondog-404__masthead">
			
			<p class="dondog-404__stamp">404</p>
		</div>

		<div class="dondog-404__hero">
			<div class="dondog-404__copy">
				<p class="dondog-404__eyebrow">Ups, ta pot se tukaj konca</p>
				<h1 class="dondog-404__title" id="dondog-404-title">Te strani nismo nasli</h1>
				<p class="dondog-404__text">
					Videti je, da je povezava odtavala po svoje. Nasli smo samo nekaj odtisov tack
					in veliko praznega prostora.
				</p>
			</div>

			<div class="dondog-404__visual">
				<p class="dondog-404__scene-label">Izgubljena sled</p>
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
				<p class="dondog-404__scene-card">
					Sniff report: povezava je verjetno stara, prekinjena ali premaknjena.
				</p>
			</div>
		</div>

		<div class="dondog-404__notes">
			<article class="dondog-404__note dondog-404__note--dark">
				<p class="dondog-404__note-label">Pasje porocilo</p>
				<p class="dondog-404__note-text">Sledi se tukaj izgubijo. Nobenih novih vonjav, samo 404.</p>
			</article>

			<article class="dondog-404__note">
				<p class="dondog-404__note-label">Namig</p>
				<p class="dondog-404__note-text">Stran je bila morda odstranjena ali pa je naslov napisan narobe.</p>
			</article>
		</div>
	</section>
</main>
<?php wp_footer(); ?>
</body>
</html>
