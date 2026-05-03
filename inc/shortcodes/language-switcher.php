<?php
/**
 * Polylang language switcher shortcode.
 *
 * Usage:
 * [dondog_language_switcher]
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the language switcher shortcode.
 *
 * @return void
 */
function dondog_register_language_switcher_shortcode() {
	add_shortcode( 'dondog_language_switcher', 'dondog_render_language_switcher_shortcode' );
	add_shortcode( 'dondog_lang_switcher', 'dondog_render_language_switcher_shortcode' );
}
add_action( 'init', 'dondog_register_language_switcher_shortcode' );

/**
 * Render a compact language switcher that uses Polylang translation URLs.
 *
 * @param array<string,string> $atts Shortcode attributes.
 * @return string
 */
function dondog_render_language_switcher_shortcode( $atts ) {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return '';
	}

	$atts = shortcode_atts(
		[
			'show_names' => 'false',
		],
		$atts,
		'dondog_language_switcher'
	);

	$languages = pll_the_languages(
		[
			'raw'           => 1,
			'hide_if_empty' => 0,
			'hide_current'  => 0,
		]
	);

	if ( ! is_array( $languages ) || [] === $languages ) {
		return '';
	}

	$languages  = dondog_sort_language_switcher_items( $languages );
	$show_names = filter_var( $atts['show_names'], FILTER_VALIDATE_BOOLEAN );
	$current    = dondog_get_current_language();
	$classes    = [
		'dondog-language-switcher',
		$show_names ? 'dondog-language-switcher--names' : 'dondog-language-switcher--codes',
	];

	ob_start();
	?>
	<nav class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" aria-label="<?php echo esc_attr( dondog_lang_text( 'Izbira jezika', 'Sprachauswahl' ) ); ?>">
		<?php foreach ( $languages as $language ) : ?>
			<?php
			$slug       = isset( $language['slug'] ) ? sanitize_html_class( $language['slug'] ) : '';
			$name       = isset( $language['name'] ) ? (string) $language['name'] : strtoupper( $slug );
			$url        = isset( $language['url'] ) ? (string) $language['url'] : '';
			$is_current = isset( $language['current_lang'] ) ? (bool) $language['current_lang'] : $slug === $current;
			$is_missing = ! empty( $language['no_translation'] );
			$label      = $show_names ? $name : strtoupper( $slug );
			$link_class = [
				'dondog-language-switcher__link',
				'dondog-language-switcher__link--' . $slug,
				$is_current ? 'is-active' : '',
				$is_missing ? 'is-fallback' : '',
			];

			if ( '' === $url && function_exists( 'pll_home_url' ) ) {
				$url = pll_home_url( $slug );
			}

			if ( '' === $slug || '' === $url ) {
				continue;
			}
			?>
			<a class="<?php echo esc_attr( trim( implode( ' ', $link_class ) ) ); ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $is_current ? ' aria-current="page"' : ''; ?> aria-label="<?php echo esc_attr( sprintf( dondog_lang_text( 'Preklopi na %s', 'Zu %s wechseln' ), $name ) ); ?>">
				<span class="dondog-language-switcher__text"><?php echo esc_html( $label ); ?></span>
			</a>
		<?php endforeach; ?>
	</nav>
	<?php

	return ob_get_clean();
}

/**
 * Keep Slovenian and German in a stable order.
 *
 * @param array<int|string,array<string,mixed>> $languages Polylang language data.
 * @return array<int,array<string,mixed>>
 */
function dondog_sort_language_switcher_items( $languages ) {
	$items = array_values( $languages );
	$order = [
		'sl' => 0,
		'de' => 1,
	];

	usort(
		$items,
		static function ( $first, $second ) use ( $order ) {
			$first_slug  = isset( $first['slug'] ) ? (string) $first['slug'] : '';
			$second_slug = isset( $second['slug'] ) ? (string) $second['slug'] : '';

			return ( $order[ $first_slug ] ?? 50 ) <=> ( $order[ $second_slug ] ?? 50 );
		}
	);

	return $items;
}
