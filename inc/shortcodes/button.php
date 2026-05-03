<?php
/**
 * Button shortcode.
 *
 * Usage:
 * [dondog_button text="Rezerviraj termin" url="https://dondog.si/rezervacije/"]
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the button shortcode.
 *
 * @return void
 */
function dondog_register_button_shortcode() {
	add_shortcode( 'dondog_button', 'dondog_render_button_shortcode' );
}
add_action( 'init', 'dondog_register_button_shortcode' );

/**
 * Render a styled Don Dog button.
 *
 * @param array<string,string> $atts Shortcode attributes.
 * @return string
 */
function dondog_render_button_shortcode( $atts ) {
	$defaults = [
		'text'    => 'Rezerviraj termin',
		'url'     => 'https://dondog.si/rezervacije/',
		'align'   => 'center',
		'new_tab' => 'false',
	];

	$atts        = shortcode_atts( $defaults, $atts, 'dondog_button' );
	$atts        = dondog_apply_shortcode_language_defaults( 'dondog_button', $atts, $defaults );
	$atts['url'] = dondog_translate_url( $atts['url'] );

	$align  = dondog_button_get_align_class( $atts['align'] );
	$target = filter_var( $atts['new_tab'], FILTER_VALIDATE_BOOLEAN ) ? ' target="_blank" rel="noopener noreferrer"' : '';

	return sprintf(
		'<div class="dondog-button-shortcode %1$s"><a class="dondog-button-shortcode__button" href="%2$s"%3$s><span>%4$s</span><span class="dondog-button-shortcode__arrow" aria-hidden="true">&rarr;</span></a></div>',
		esc_attr( $align ),
		esc_url( $atts['url'] ),
		$target,
		esc_html( $atts['text'] )
	);
}

/**
 * Convert shortcode align value to a safe class.
 *
 * @param string $align Alignment value.
 * @return string
 */
function dondog_button_get_align_class( $align ) {
	$align = strtolower( trim( (string) $align ) );

	$allowed = [
		'left'   => 'dondog-button-shortcode--left',
		'center' => 'dondog-button-shortcode--center',
		'right'  => 'dondog-button-shortcode--right',
	];

	return $allowed[ $align ] ?? $allowed['center'];
}
