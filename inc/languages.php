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
			'nav_items' => 'Startseite|/;Preise|/cenik/;Über uns|/o-nas/;Galerie|/galerija/;Kontakt|/kontakt/',
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

	if ( function_exists( 'pll_get_post' ) ) {
		$page = get_page_by_path( $path );

		if ( $page instanceof WP_Post ) {
			$translated_id = pll_get_post( $page->ID, $language );

			if ( $translated_id ) {
				return get_permalink( $translated_id );
			}
		}
	}

	return $url;
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
