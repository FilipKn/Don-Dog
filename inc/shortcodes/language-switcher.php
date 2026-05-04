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

	try {
		$languages = pll_the_languages(
			[
				'raw'           => 1,
				'hide_if_empty' => 0,
				'hide_current'  => 0,
			]
		);
	} catch ( Throwable $error ) {
		return '';
	}

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
			$url        = dondog_language_switcher_resolve_url( $slug, $url, $is_missing );
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
 * Resolve a language switcher URL with project-specific fallbacks.
 *
 * Polylang sends users to the language home page when the current page/post has
 * no connected translation. For the news section, keep users inside the news
 * section instead of sending them back to home.
 *
 * @param string $target_language Target language slug.
 * @param string $url             Polylang URL.
 * @param bool   $is_missing      Whether Polylang marked the translation as missing.
 * @return string
 */
function dondog_language_switcher_resolve_url( $target_language, $url, $is_missing ) {
	$target_language = sanitize_key( $target_language );

	if ( '' === $target_language ) {
		return $url;
	}

	if ( dondog_is_news_context() && ( $is_missing || dondog_language_switcher_url_is_home( $url, $target_language ) ) ) {
		return dondog_get_news_url_for_language( $target_language );
	}

	return $url;
}

/**
 * Check if the current request belongs to the news/blog area.
 *
 * @return bool
 */
function dondog_is_news_context() {
	if ( is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_date() || is_author() ) {
		return true;
	}

	if ( is_page() ) {
		$post = get_post();

		if ( $post instanceof WP_Post ) {
			$slug = $post->post_name;

			return in_array( $slug, [ 'novice', 'nachricht' ], true );
		}
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = wp_parse_url( $request_uri, PHP_URL_PATH );
	$path = is_string( $path ) ? trim( $path, '/' ) : '';

	return in_array( $path, [ 'sl/novice', 'novice', 'nachricht' ], true );
}

/**
 * Return the news landing page URL for a language.
 *
 * @param string $language Language slug.
 * @return string
 */
function dondog_get_news_url_for_language( $language ) {
	if ( 'de' === $language ) {
		return 'https://dondog.si/nachricht/';
	}

	return 'https://dondog.si/sl/novice/';
}

/**
 * Check if a Polylang URL points to a language home page.
 *
 * @param string $url      URL to check.
 * @param string $language Language slug.
 * @return bool
 */
function dondog_language_switcher_url_is_home( $url, $language ) {
	if ( '' === trim( (string) $url ) || ! function_exists( 'pll_home_url' ) ) {
		return false;
	}

	return untrailingslashit( $url ) === untrailingslashit( pll_home_url( $language ) );
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
