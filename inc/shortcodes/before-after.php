<?php
/**
 * Before / after gallery shortcode.
 *
 * Usage:
 * [dondog_before_after item_1_before="123" item_1_after="124"]
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the before / after shortcode.
 *
 * @return void
 */
function dondog_register_before_after_shortcode() {
	add_shortcode( 'dondog_before_after', 'dondog_render_before_after_shortcode' );
}
add_action( 'init', 'dondog_register_before_after_shortcode' );

/**
 * Render the before / after gallery.
 *
 * @param array<string,string> $atts Shortcode attributes.
 * @return string
 */
function dondog_render_before_after_shortcode( $atts ) {
	$defaults = [
		'before_label'  => 'Pred',
		'after_label'   => 'Po',
		'item_1_before' => '',
		'item_1_after'  => '',
		'item_1_name'   => 'Bella',
		'item_1_breed'  => 'Maltezan',
		'item_2_before' => '',
		'item_2_after'  => '',
		'item_2_name'   => 'Max',
		'item_2_breed'  => 'Zlati retriver',
		'item_3_before' => '',
		'item_3_after'  => '',
		'item_3_name'   => 'Luka',
		'item_3_breed'  => 'Yorkshire terier',
	];

	$atts = shortcode_atts( $defaults, $atts, 'dondog_before_after' );
	$atts = dondog_apply_shortcode_language_defaults( 'dondog_before_after', $atts, $defaults );

	$items = dondog_before_after_get_items( $atts );

	ob_start();
	?>
	<section class="dondog-before-after" aria-label="<?php echo esc_attr( dondog_lang_text( 'Pred in po galerija', 'Vorher-nachher-Galerie' ) ); ?>">
		<div class="dondog-before-after__grid">
			<?php foreach ( $items as $index => $item ) : ?>
				<article class="dondog-before-after__card">
					<div class="dondog-before-after__images">
						<?php
						echo dondog_before_after_render_image(
							$item['before'],
							'before',
							$atts['before_label'],
							$item['name']
						);

						echo dondog_before_after_render_image(
							$item['after'],
							'after',
							$atts['after_label'],
							$item['name']
						);
						?>
					</div>

					<div class="dondog-before-after__content">
						<h3 class="dondog-before-after__name"><?php echo esc_html( $item['name'] ); ?></h3>
						<?php if ( '' !== $item['breed'] ) : ?>
							<p class="dondog-before-after__breed"><?php echo esc_html( $item['breed'] ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php

	return ob_get_clean();
}

/**
 * Build a fixed three-item gallery from shortcode attributes.
 *
 * @param array<string,string> $atts Shortcode attributes.
 * @return array<int,array<string,string>>
 */
function dondog_before_after_get_items( $atts ) {
	$items = [];

	for ( $i = 1; $i <= 3; $i++ ) {
		$items[] = [
			'before' => trim( (string) $atts[ 'item_' . $i . '_before' ] ),
			'after'  => trim( (string) $atts[ 'item_' . $i . '_after' ] ),
			'name'   => trim( (string) $atts[ 'item_' . $i . '_name' ] ),
			'breed'  => trim( (string) $atts[ 'item_' . $i . '_breed' ] ),
		];
	}

	return $items;
}

/**
 * Render one image panel from a WordPress attachment ID or image URL.
 *
 * @param string $image_source WordPress attachment ID or image URL.
 * @param string $type         Panel type: before or after.
 * @param string $label        Visible badge label.
 * @param string $name         Pet name used for alt text.
 * @param string $loading      Image loading strategy.
 * @return string
 */
function dondog_before_after_render_image( $image_source, $type, $label, $name, $loading = 'lazy' ) {
	$type       = sanitize_html_class( $type );
	$class_name = 'dondog-before-after__image dondog-before-after__image--' . $type;
	$alt        = trim( $name . ' ' . $label );

	if ( is_numeric( $image_source ) ) {
		$image = wp_get_attachment_image(
			absint( $image_source ),
			'medium_large',
			false,
			[
				'class'    => 'dondog-before-after__img',
				'alt'      => $alt,
				'loading'  => $loading,
				'decoding' => 'async',
				'sizes'    => '(max-width: 720px) 50vw, 205px',
			]
		);

		if ( $image ) {
			return sprintf(
				'<figure class="%1$s">%2$s<span class="dondog-before-after__badge">%3$s</span></figure>',
				esc_attr( $class_name ),
				$image,
				esc_html( $label )
			);
		}
	}

	if ( filter_var( $image_source, FILTER_VALIDATE_URL ) ) {
		return sprintf(
			'<figure class="%1$s"><img class="dondog-before-after__img" src="%2$s" alt="%3$s" width="410" height="660" loading="%4$s" decoding="async"><span class="dondog-before-after__badge">%5$s</span></figure>',
			esc_attr( $class_name ),
			esc_url( $image_source ),
			esc_attr( $alt ),
			esc_attr( $loading ),
			esc_html( $label )
		);
	}

	return sprintf(
		'<figure class="%1$s dondog-before-after__image--placeholder"><span class="dondog-before-after__placeholder-text">%2$s</span><span class="dondog-before-after__badge">%3$s</span></figure>',
		esc_attr( $class_name ),
		esc_html( dondog_lang_text( 'Slika', 'Bild' ) ),
		esc_html( $label )
	);
}
