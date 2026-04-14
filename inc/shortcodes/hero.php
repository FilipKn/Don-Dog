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
	$atts = shortcode_atts(
		[
			'eyebrow'        => 'Salon za nego živali',
			'title_top'      => 'Vrhunska nega za',
			'title_bottom'   => 'vašega',
			'title_accent'   => 'PSA.',
			'text'           => 'Striženje, kopanje in nega za pse, macke in druge male Živali.',
			'primary_text'   => 'Rezerviraj termin',
			'primary_url'    => '#',
			'secondary_text' => 'Naše storitve',
			'secondary_url'  => '#',
			'features'       => '33+ zadovoljnih strank|Nežen pristop|Profesionalna nega',
			'main_image'     => '',
			'top_image'      => '',
			'right_image'    => '',
			'left_image'     => '',
			'bottom_image'   => '',
		],
		$atts,
		'dondog_hero'
	);

	$title_id = wp_unique_id( 'dondog-hero-title-' );

	ob_start();
	?>
	<section class="dondog-hero is-ready" data-dondog-animate="hero" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
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

				<ul class="dondog-hero__features" aria-label="<?php echo esc_attr__( 'Prednosti', 'hello-elementor-child' ); ?>">
					<?php foreach ( dondog_hero_get_features( $atts['features'] ) as $feature ) : ?>
						<li><?php echo esc_html( $feature ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="dondog-hero__visual" aria-label="<?php echo esc_attr__( 'Slike salona in zivali', 'hello-elementor-child' ); ?>">
				<span class="dondog-hero__ring dondog-hero__ring--outer" aria-hidden="true"></span>
				<span class="dondog-hero__ring dondog-hero__ring--inner" aria-hidden="true"></span>
				<span class="dondog-hero__dot dondog-hero__dot--large" aria-hidden="true"></span>
				<span class="dondog-hero__dot dondog-hero__dot--small" aria-hidden="true"></span>

				<?php
				echo dondog_hero_render_image( $atts['main_image'], 'main', __( 'Glavna slika', 'hello-elementor-child' ), 'eager' );
				echo dondog_hero_render_image( $atts['top_image'], 'top', __( 'Zgornja slika', 'hello-elementor-child' ) );
				echo dondog_hero_render_image( $atts['right_image'], 'right', __( 'Desna slika', 'hello-elementor-child' ) );
				echo dondog_hero_render_image( $atts['left_image'], 'left', __( 'Leva slika', 'hello-elementor-child' ) );
				echo dondog_hero_render_image( $atts['bottom_image'], 'bottom', __( 'Spodnja slika', 'hello-elementor-child' ) );
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
			.dondog-hero.is-ready .dondog-hero__image,
			.dondog-hero.is-ready .dondog-hero__ring,
			.dondog-hero.is-ready .dondog-hero__dot {
				opacity: 1;
				filter: none;
				transform: none;
				animation: none;
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
	$class_name   = 'dondog-hero__image dondog-hero__image--' . sanitize_html_class( $position );

	if ( is_numeric( $image_source ) ) {
		$image = wp_get_attachment_image(
			absint( $image_source ),
			'large',
			false,
			[
				'class'    => 'dondog-hero__img',
				'loading'  => $loading,
				'decoding' => 'async',
			]
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
			'<figure class="%1$s"><img class="dondog-hero__img" src="%2$s" alt="" loading="%3$s" decoding="async"></figure>',
			esc_attr( $class_name ),
			esc_url( $image_source ),
			esc_attr( $loading )
		);
	}

	return sprintf(
		'<figure class="%1$s dondog-hero__image--placeholder"><span>%2$s</span></figure>',
		esc_attr( $class_name ),
		esc_html( $fallback )
	);
}
