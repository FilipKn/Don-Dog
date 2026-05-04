<?php
/**
 * Polylang language helpers.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the current site language slug.
 *
 * @return string
 */
function dondog_get_current_language() {
	if ( function_exists( 'pll_current_language' ) ) {
		$language = pll_current_language( 'slug' );

		if ( is_string( $language ) && '' !== $language ) {
			return $language;
		}
	}

	$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();

	if ( is_string( $locale ) && 0 === strpos( strtolower( $locale ), 'de' ) ) {
		return 'de';
	}

	return 'sl';
}

/**
 * Check whether the current request is German.
 *
 * @return bool
 */
function dondog_is_german() {
	return 'de' === dondog_get_current_language();
}

/**
 * Return Slovenian or German text for the current language.
 *
 * @param string $sl Slovenian text.
 * @param string $de German text.
 * @return string
 */
function dondog_lang_text( $sl, $de ) {
	return dondog_is_german() ? $de : $sl;
}

/**
 * Apply translated defaults when shortcode values are still the Slovenian defaults.
 *
 * This keeps manually customized shortcode attributes intact, while duplicated
 * Polylang pages that contain the same shortcode markup get German defaults.
 *
 * @param string               $shortcode       Shortcode name.
 * @param array<string,string> $atts            Resolved shortcode attributes.
 * @param array<string,string> $source_defaults Slovenian defaults.
 * @return array<string,string>
 */
function dondog_apply_shortcode_language_defaults( $shortcode, $atts, $source_defaults ) {
	$language_defaults = dondog_get_shortcode_language_defaults( $shortcode );

	if ( [] === $language_defaults ) {
		return $atts;
	}

	foreach ( $language_defaults as $key => $value ) {
		if ( ! array_key_exists( $key, $source_defaults ) ) {
			continue;
		}

		if ( ! array_key_exists( $key, $atts ) || (string) $atts[ $key ] === (string) $source_defaults[ $key ] ) {
			$atts[ $key ] = $value;
		}
	}

	return $atts;
}

/**
 * Get translated default values for one shortcode.
 *
 * @param string $shortcode Shortcode name.
 * @return array<string,string>
 */
function dondog_get_shortcode_language_defaults( $shortcode ) {
	if ( ! dondog_is_german() ) {
		return [];
	}

	$defaults = [
		'dondog_hero'         => [
			'eyebrow'        => 'Hundesalon',
			'title_top'      => 'Professionelle Pflege für',
			'title_bottom'   => 'Ihren',
			'title_accent'   => 'HUND.',
			'text'           => 'Scheren, Baden und Pflege für Hunde, Katzen und andere Kleintiere.',
			'primary_text'   => 'Termin buchen',
			'secondary_text' => 'Unsere Leistungen',
			'features'       => '33+ zufriedene Kunden|Sanfter Umgang|Professionelle Pflege',
		],
		'dondog_before_after' => [
			'before_label' => 'Vorher',
			'after_label'  => 'Nachher',
			'item_1_breed' => 'Malteser',
			'item_2_breed' => 'Golden Retriever',
			'item_3_breed' => 'Yorkshire Terrier',
		],
		'dondog_button'       => [
			'text' => 'Termin buchen',
		],
		'dondog_header'       => [
			'nav_items' => 'Startseite|/;Preise|/cenik/;Über uns|/o-nas/;Galerie|https://dondog.si/elementor-1158/;Kontakt|/kontakt/',
			'cta_text'  => 'Termin buchen',
		],
		'dondog_footer'       => [
			'description' => 'Premium-Hundesalon in Radlje ob Dravi. Ihr Hund verdient das Beste - und genau das bekommt er bei uns.',
			'nav_title'   => 'Navigation',
			'nav_items'   => 'Startseite|/;Preise|/cenik/;Galerie|/galerija/;Kontakt|/kontakt/',
			'address'     => 'Dobrava 5a, Radlje ob Dravi, Slowenien',
			'hours_title' => 'Öffnungszeiten',
			'hours_items' => 'Mo - Fr|8:00 - 18:00;Sa|Geschlossen;So|Geschlossen',
			'copyright'   => '(c) 2026 Don Dog. Alle Rechte vorbehalten.',
			'privacy_text' => 'Datenschutz',
			'credit'      => 'Mit Liebe zu Hunden gestaltet',
		],
	];

	return $defaults[ $shortcode ] ?? [];
}

/**
 * Translate an internal page URL to the current Polylang language.
 *
 * @param string $url      URL or relative path.
 * @param string $language Optional target language.
 * @return string
 */
function dondog_translate_url( $url, $language = '' ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return $url;
	}

	$scheme = wp_parse_url( $url, PHP_URL_SCHEME );

	if ( in_array( $scheme, [ 'mailto', 'tel' ], true ) ) {
		return $url;
	}

	$language = '' !== $language ? $language : dondog_get_current_language();
	$host     = wp_parse_url( $url, PHP_URL_HOST );

	if ( is_string( $host ) && '' !== $host ) {
		$home_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

		if ( is_string( $home_host ) && '' !== $home_host && dondog_normalize_host( $host ) !== dondog_normalize_host( $home_host ) ) {
			return $url;
		}
	}

	$path = wp_parse_url( $url, PHP_URL_PATH );

	if ( false === $path || null === $path ) {
		return $url;
	}

	$path = trim( rawurldecode( $path ), '/' );

	if ( '' === $path ) {
		return function_exists( 'pll_home_url' ) ? pll_home_url( $language ) : home_url( '/' );
	}

	$path_without_language = dondog_strip_language_prefix_from_path( $path );

	if ( '' === $path_without_language ) {
		return function_exists( 'pll_home_url' ) ? pll_home_url( $language ) : home_url( '/' );
	}

	if ( function_exists( 'pll_get_post' ) ) {
		$page = dondog_get_page_by_translatable_path( $path );

		if ( $page instanceof WP_Post ) {
			$translated_id = pll_get_post( $page->ID, $language );

			if ( $translated_id ) {
				return get_permalink( $translated_id );
			}
		}
	}

	$fallback_page = dondog_get_known_translated_page_for_path( $path, $language );

	if ( $fallback_page instanceof WP_Post ) {
		return get_permalink( $fallback_page );
	}

	return $url;
}

/**
 * Resolve a page from a path, also accepting Polylang language-prefixed URLs.
 *
 * @param string $path URL path without leading/trailing slash.
 * @return WP_Post|null
 */
function dondog_get_page_by_translatable_path( $path ) {
	$candidates = array_unique(
		array_filter(
			[
				trim( (string) $path, '/' ),
				dondog_strip_language_prefix_from_path( $path ),
			]
		)
	);

	foreach ( $candidates as $candidate ) {
		$page = get_page_by_path( $candidate );

		if ( $page instanceof WP_Post ) {
			return $page;
		}
	}

	return null;
}

/**
 * Remove a Polylang language slug from the beginning of a URL path.
 *
 * @param string $path URL path without leading/trailing slash.
 * @return string
 */
function dondog_strip_language_prefix_from_path( $path ) {
	$path = trim( (string) $path, '/' );

	if ( '' === $path ) {
		return '';
	}

	$segments = explode( '/', $path );
	$language_slugs = [ 'sl', 'de' ];

	if ( function_exists( 'pll_languages_list' ) ) {
		$polylang_slugs = pll_languages_list( [ 'fields' => 'slug' ] );

		if ( is_array( $polylang_slugs ) ) {
			$language_slugs = array_merge( $language_slugs, $polylang_slugs );
		}
	}

	$language_slugs = array_unique( array_filter( array_map( 'strval', $language_slugs ) ) );

	if ( in_array( $segments[0], $language_slugs, true ) ) {
		array_shift( $segments );
	}

	return implode( '/', $segments );
}

/**
 * Find known translated pages by slug if Polylang translations are not connected.
 *
 * @param string $path     Source URL path without leading/trailing slash.
 * @param string $language Target language slug.
 * @return WP_Post|null
 */
function dondog_get_known_translated_page_for_path( $path, $language ) {
	$source_path = strtolower( dondog_strip_language_prefix_from_path( $path ) );
	$known_paths = [
		'de' => [
			'cenik'               => [ 'preis', 'preise' ],
			'o-nas'               => [ 'uber-uns', 'ueber-uns' ],
			'galerija'            => [ 'galerie' ],
			'kontakt'             => [ 'kontakt' ],
			'rezervacije'         => [ 'reservierungen', 'termin-buchen', 'buchung' ],
			'politika-zasebnosti' => [ 'datenschutz', 'datenschutzerklaerung', 'privacy-policy' ],
		],
		'sl' => [
			'preis'                 => [ 'cenik' ],
			'preise'                => [ 'cenik' ],
			'uber-uns'              => [ 'o-nas' ],
			'ueber-uns'             => [ 'o-nas' ],
			'galerija'              => [ 'page', 'galerija' ],
			'galerie'               => [ 'page', 'galerija' ],
			'kontakt'               => [ 'kontakt' ],
			'reservierungen'        => [ 'rezervacije' ],
			'termin-buchen'         => [ 'rezervacije' ],
			'buchung'               => [ 'rezervacije' ],
			'datenschutz'           => [ 'politika-zasebnosti' ],
			'datenschutzerklaerung' => [ 'politika-zasebnosti' ],
			'privacy-policy'        => [ 'politika-zasebnosti' ],
		],
	];

	if ( ! isset( $known_paths[ $language ][ $source_path ] ) ) {
		return null;
	}

	foreach ( $known_paths[ $language ][ $source_path ] as $candidate ) {
		$page = get_page_by_path( $candidate );

		if ( $page instanceof WP_Post && dondog_page_matches_language( $page, $language ) ) {
			return $page;
		}
	}

	return null;
}

/**
 * Check if a page belongs to a language when Polylang can tell us.
 *
 * @param WP_Post $page     Page object.
 * @param string  $language Language slug.
 * @return bool
 */
function dondog_page_matches_language( WP_Post $page, $language ) {
	if ( function_exists( 'pll_get_post_language' ) ) {
		$page_language = pll_get_post_language( $page->ID, 'slug' );

		return ! is_string( $page_language ) || '' === $page_language || $language === $page_language;
	}

	return true;
}

/**
 * Normalize a hostname before comparing internal URLs.
 *
 * @param string $host Hostname.
 * @return string
 */
function dondog_normalize_host( $host ) {
	$host = strtolower( trim( $host ) );

	return 0 === strpos( $host, 'www.' ) ? substr( $host, 4 ) : $host;
}
