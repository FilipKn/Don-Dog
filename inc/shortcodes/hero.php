<?php
/**
 * Hero shortcode.
 *
 * Usage:
 * [dondog_hero main_image="123" top_image="https://example.com/image.jpg"]
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Don Dog shortcodes.
 *
 * @return void
 */
function dondog_register_hero_shortcode() {
	add_shortcode( 'dondog_hero', 'dondog_render_hero_shortcode' );
}
add_action( 'init', 'dondog_register_hero_shortcode' );

/**
 * Render the hero section.
 *
 * @param array<string,string> $atts Shortcode attributes.
 * @return string
 */
function dondog_render_hero_shortcode( $atts ) {
	$defaults = [
		'eyebrow'        => 'Salon za nego živali',
		'title_top'      => 'Vrhunska nega za',
		'title_bottom'   => 'vašega',
		'title_accent'   => 'PSA.',
		'text'           => 'Striženje, kopanje in nega za pse, mačke in druge male živali.',
		'primary_text'   => 'Rezerviraj termin',
		'primary_url'    => 'https://dondog.si/rezervacije',
		'secondary_text' => 'Naše storitve',
		'secondary_url'  => 'https://dondog.si/cenik/',
		'features'       => '33+ zadovoljnih strank|Nežen pristop|Profesionalna nega',
		'main_image'     => '',
		'top_image'      => '',
		'right_image'    => '',
		'left_image'     => '',
		'bottom_image'   => '',
	];

	$atts                  = shortcode_atts( $defaults, $atts, 'dondog_hero' );
	$atts                  = dondog_apply_shortcode_language_defaults( 'dondog_hero', $atts, $defaults );
	$atts['primary_url']   = dondog_translate_url( $atts['primary_url'] );
	$atts['secondary_url'] = dondog_translate_url( $atts['secondary_url'] );

	$title_id = wp_unique_id( 'dondog-hero-title-' );

	ob_start();
	?>
	<section class="dondog-hero is-ready is-text-ready" data-dondog-animate="hero" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
		<div class="dondog-hero__container">
			<div class="dondog-hero__content">
				<p class="dondog-hero__eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>

				<h1 class="dondog-hero__title" id="<?php echo esc_attr( $title_id ); ?>">
					<span><?php echo esc_html( $atts['title_top'] ); ?></span>
					<span>
						<?php echo esc_html( $atts['title_bottom'] ); ?>
						<em><?php echo esc_html( $atts['title_accent'] ); ?></em>
					</span>
				</h1>

				<p class="dondog-hero__text"><?php echo esc_html( $atts['text'] ); ?></p>

				<div class="dondog-hero__actions">
					<a class="dondog-hero__button dondog-hero__button--primary" href="<?php echo esc_url( $atts['primary_url'] ); ?>">
						<span><?php echo esc_html( $atts['primary_text'] ); ?></span>
						<span class="dondog-hero__arrow" aria-hidden="true">&rarr;</span>
					</a>

					<a class="dondog-hero__button dondog-hero__button--secondary" href="<?php echo esc_url( $atts['secondary_url'] ); ?>">
						<?php echo esc_html( $atts['secondary_text'] ); ?>
					</a>
				</div>

				<ul class="dondog-hero__features" aria-label="<?php echo esc_attr( dondog_lang_text( 'Prednosti', 'Vorteile' ) ); ?>">
					<?php foreach ( dondog_hero_get_features( $atts['features'] ) as $feature ) : ?>
						<li><?php echo esc_html( $feature ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="dondog-hero__visual" aria-label="<?php echo esc_attr( dondog_lang_text( 'Slike salona in živali', 'Bilder aus dem Salon und von Tieren' ) ); ?>">
				<span class="dondog-hero__ring dondog-hero__ring--outer" aria-hidden="true"></span>
				<span class="dondog-hero__ring dondog-hero__ring--inner" aria-hidden="true"></span>
				<span class="dondog-hero__dot dondog-hero__dot--large" aria-hidden="true"></span>
				<span class="dondog-hero__dot dondog-hero__dot--small" aria-hidden="true"></span>

				<?php
				echo dondog_hero_render_image( $atts['main_image'], 'main', dondog_lang_text( 'Glavna slika', 'Hauptbild' ), 'eager' );
				echo dondog_hero_render_image( $atts['top_image'], 'top', dondog_lang_text( 'Zgornja slika', 'Oberes Bild' ) );
				echo dondog_hero_render_image( $atts['right_image'], 'right', dondog_lang_text( 'Desna slika', 'Rechtes Bild' ) );
				echo dondog_hero_render_image( $atts['left_image'], 'left', dondog_lang_text( 'Leva slika', 'Linkes Bild' ) );
				echo dondog_hero_render_image( $atts['bottom_image'], 'bottom', dondog_lang_text( 'Spodnja slika', 'Unteres Bild' ) );
				?>
			</div>
		</div>
	</section>
	<noscript>
		<style>
			.dondog-hero.is-ready .dondog-hero__eyebrow,
			.dondog-hero.is-ready .dondog-hero__title,
			.dondog-hero.is-ready .dondog-hero__text,
			.dondog-hero.is-ready .dondog-hero__actions,
			.dondog-hero.is-ready .dondog-hero__features,
			.dondog-hero.is-text-ready .dondog-hero__eyebrow,
			.dondog-hero.is-text-ready .dondog-hero__title,
			.dondog-hero.is-text-ready .dondog-hero__text,
			.dondog-hero.is-text-ready .dondog-hero__actions,
			.dondog-hero.is-text-ready .dondog-hero__features,
			.dondog-hero.is-ready .dondog-hero__image,
			.dondog-hero.is-ready .dondog-hero__ring,
			.dondog-hero.is-ready .dondog-hero__dot {
				opacity: 1;
				filter: none;
				transform: none;
				animation: none;
				transition: none;
			}

			.dondog-hero.is-ready .dondog-hero__image::before {
				display: none;
			}
		</style>
	</noscript>
	<?php

	return ob_get_clean();
}

/**
 * Convert the feature text attribute into a safe list.
 *
 * @param string $features Pipe-separated feature labels.
 * @return array<int,string>
 */
function dondog_hero_get_features( $features ) {
	$items = array_filter( array_map( 'trim', explode( '|', (string) $features ) ) );

	return array_slice( $items, 0, 3 );
}

/**
 * Render one circular image from a WordPress attachment ID or image URL.
 *
 * @param string|int $image_source WordPress attachment ID or image URL.
 * @param string     $position     Circle position class suffix.
 * @param string     $fallback     Placeholder label.
 * @param string     $loading      Image loading strategy.
 * @return string
 */
function dondog_hero_render_image( $image_source, $position, $fallback, $loading = 'lazy' ) {
	$image_source = trim( (string) $image_source );
	$position     = sanitize_html_class( $position );
	$class_name   = 'dondog-hero__image dondog-hero__image--' . sanitize_html_class( $position );
	$image_meta   = dondog_hero_get_image_meta( $position );

	if ( is_numeric( $image_source ) ) {
		$image_attrs = [
			'class'    => 'dondog-hero__img',
			'loading'  => $loading,
			'decoding' => 'async',
			'sizes'    => $image_meta['sizes'],
		];

		if ( 'eager' === $loading ) {
			$image_attrs['fetchpriority'] = 'high';
		}

		$image = wp_get_attachment_image(
			absint( $image_source ),
			$image_meta['size'],
			false,
			$image_attrs
		);

		if ( $image ) {
			return sprintf(
				'<figure class="%1$s">%2$s</figure>',
				esc_attr( $class_name ),
				$image
			);
		}
	}

	if ( filter_var( $image_source, FILTER_VALIDATE_URL ) ) {
		return sprintf(
			'<figure class="%1$s"><img class="dondog-hero__img" src="%2$s" alt="" width="%3$d" height="%4$d" loading="%5$s" decoding="async"%6$s></figure>',
			esc_attr( $class_name ),
			esc_url( $image_source ),
			absint( $image_meta['width'] ),
			absint( $image_meta['height'] ),
			esc_attr( $loading ),
			'eager' === $loading ? ' fetchpriority="high"' : ''
		);
	}

	return sprintf(
		'<figure class="%1$s dondog-hero__image--placeholder"><span>%2$s</span></figure>',
		esc_attr( $class_name ),
		esc_html( $fallback )
	);
}

/**
 * Return optimized image size metadata for each hero circle.
 *
 * @param string $position Circle position class suffix.
 * @return array{size:string,sizes:string,width:int,height:int}
 */
function dondog_hero_get_image_meta( $position ) {
	$images = [
		'main'   => [
			'size'   => 'medium_large',
			'sizes'  => '(max-width: 640px) 52vw, (max-width: 980px) 44vw, 280px',
			'width'  => 560,
			'height' => 560,
		],
		'top'    => [
			'size'   => 'medium',
			'sizes'  => '(max-width: 640px) 1px, (max-width: 980px) 26vw, 160px',
			'width'  => 320,
			'height' => 320,
		],
		'right'  => [
			'size'   => 'medium',
			'sizes'  => '(max-width: 640px) 1px, (max-width: 980px) 20vw, 120px',
			'width'  => 240,
			'height' => 240,
		],
		'left'   => [
			'size'   => 'medium',
			'sizes'  => '(max-width: 640px) 22vw, (max-width: 980px) 24vw, 140px',
			'width'  => 280,
			'height' => 280,
		],
		'bottom' => [
			'size'   => 'medium',
			'sizes'  => '(max-width: 640px) 16vw, (max-width: 980px) 16vw, 96px',
			'width'  => 192,
			'height' => 192,
		],
	];

	return $images[ $position ] ?? $images['main'];
}
