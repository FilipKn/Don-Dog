<?php
/**
 * Header shortcode.
 *
 * Usage:
 * [dondog_header logo="123"]
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the header shortcode.
 *
 * @return void
 */
function dondog_register_header_shortcode() {
	add_shortcode( 'dondog_header', 'dondog_render_header_shortcode' );
}
add_action( 'init', 'dondog_register_header_shortcode' );

/**
 * Render the Don Dog header.
 *
 * @param array<string,string> $atts Shortcode attributes.
 * @return string
 */
function dondog_render_header_shortcode( $atts ) {
	$defaults = [
		'logo'                    => '',
		'brand_name'              => 'Don Dog',
		'home_url'                => '/',
		'nav_items'               => 'Domov|/;Cenik|/cenik/;O nas|/o-nas/;Galerija|/galerija/;Kontakt|/kontakt/',
		'cta_text'                => 'Rezerviraj termin',
		'cta_url'                 => 'https://dondog.si/rezervacije/',
		'language_switcher'       => 'true',
		'language_switcher_names' => 'false',
	];

	$atts             = shortcode_atts( $defaults, $atts, 'dondog_header' );
	$atts             = dondog_apply_shortcode_language_defaults( 'dondog_header', $atts, $defaults );
	$atts['home_url'] = dondog_translate_url( $atts['home_url'] );
	$atts['cta_url']  = dondog_translate_url( $atts['cta_url'] );
	$language_switcher = '';

	if ( filter_var( $atts['language_switcher'], FILTER_VALIDATE_BOOLEAN ) && function_exists( 'dondog_render_language_switcher_shortcode' ) ) {
		try {
			$language_switcher = dondog_render_language_switcher_shortcode(
				[
					'show_names' => $atts['language_switcher_names'],
				]
			);
		} catch ( Throwable $error ) {
			$language_switcher = '';
		}
	}

	ob_start();
	?>
	<header class="dondog-header" role="banner">
		<div class="dondog-header__inner">
			<a class="dondog-header__brand" href="<?php echo esc_url( $atts['home_url'] ); ?>" aria-label="<?php echo esc_attr( $atts['brand_name'] ); ?>">
				<?php echo dondog_header_render_logo( $atts['logo'], $atts['brand_name'] ); ?>
			</a>

			<nav class="dondog-header__nav" aria-label="<?php echo esc_attr( dondog_lang_text( 'Glavna navigacija', 'Hauptnavigation' ) ); ?>">
				<?php foreach ( dondog_header_parse_pairs( $atts['nav_items'] ) as $item ) : ?>
					<a class="dondog-header__nav-link" href="<?php echo esc_url( dondog_translate_url( $item['value'] ) ); ?>">
						<?php echo esc_html( $item['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="dondog-header__actions">
				<?php if ( '' !== trim( $language_switcher ) ) : ?>
					<?php echo $language_switcher; ?>
				<?php endif; ?>

				<a class="dondog-header__cta" href="<?php echo esc_url( $atts['cta_url'] ); ?>">
					<?php echo esc_html( $atts['cta_text'] ); ?>
				</a>
			</div>
		</div>
	</header>
	<?php

	return ob_get_clean();
}

/**
 * Render the header logo from attachment ID, URL, or theme default.
 *
 * @param string $source     Image attachment ID or URL.
 * @param string $brand_name Brand fallback text.
 * @return string
 */
function dondog_header_render_logo( $source, $brand_name ) {
	$source = trim( (string) $source );

	if ( '' === $source && defined( 'DONDOG_LOGIN_LOGO_URL' ) ) {
		$source = DONDOG_LOGIN_LOGO_URL;
	}

	if ( is_numeric( $source ) ) {
		$image = wp_get_attachment_image(
			absint( $source ),
			'medium',
			false,
			[
				'class'    => 'dondog-header__logo-img',
				'alt'      => $brand_name,
				'loading'  => 'eager',
				'decoding' => 'async',
			]
		);

		if ( $image ) {
			return $image;
		}
	}

	if ( filter_var( $source, FILTER_VALIDATE_URL ) ) {
		return sprintf(
			'<img class="dondog-header__logo-img" src="%1$s" alt="%2$s" loading="eager" decoding="async">',
			esc_url( $source ),
			esc_attr( $brand_name )
		);
	}

	return sprintf(
		'<span class="dondog-header__logo-fallback">%s</span>',
		esc_html( $brand_name )
	);
}

/**
 * Parse semicolon-separated label/value pairs.
 *
 * @param string $raw Raw pair list like label|value;label|value.
 * @return array<int,array{label:string,value:string}>
 */
function dondog_header_parse_pairs( $raw ) {
	$items = [];
	$pairs = array_filter( array_map( 'trim', explode( ';', (string) $raw ) ) );

	foreach ( $pairs as $pair ) {
		$parts = array_map( 'trim', explode( '|', $pair, 2 ) );

		if ( 2 !== count( $parts ) || '' === $parts[0] ) {
			continue;
		}

		$items[] = [
			'label' => $parts[0],
			'value' => $parts[1],
		];
	}

	return $items;
}
