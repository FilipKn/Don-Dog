<?php
/**
 * BookingPress front-end language support.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return exact BookingPress text replacements for German pages.
 *
 * BookingPress stores several front-end labels and service names in its own
 * tables, so Polylang does not translate them automatically.
 *
 * @return array<string,string>
 */
function dondog_bookingpress_i18n_map() {
	static $map = null;

	if ( null !== $map ) {
		return $map;
	}

	$map = [
		'Rezervacije' => 'Buchungen',
		'Rezervacija' => 'Buchung',
		'Rezerviraj termin' => 'Termin buchen',
		'Storitev' => 'Dienstleistung',
		'Storitve' => 'Dienstleistungen',
		'Izberi kategorijo' => 'Kategorie auswählen',
		'Izberi storitev' => 'Dienstleistung auswählen',
		'Kategorija' => 'Kategorie',
		'Datum' => 'Datum',
		'Čas' => 'Uhrzeit',
		'Časovni termin' => 'Uhrzeit',
		'Datum in čas' => 'Datum und Uhrzeit',
		'Dopoldne' => 'Vormittag',
		'Dopoldan' => 'Vormittag',
		'Popoldne' => 'Nachmittag',
		'Zjutraj' => 'Vormittag',
		'Informacije' => 'Informationen',
		'Osebni podatki' => 'Persönliche Daten',
		'Povzetek' => 'Zusammenfassung',
		'Povzetek rezervacije vašega termina' => 'Zusammenfassung Ihrer Terminbuchung',
		'Stranka' => 'Kunde',
		'Plačilo' => 'Zahlung',
		'Naprej' => 'Weiter',
		'Naslednji' => 'Weiter',
		'Nadaljuj' => 'Weiter',
		'Nazaj' => 'Zurück',
		'Prejšnji' => 'Zurück',
		'Potrdi' => 'Bestätigen',
		'Potrdi rezervacijo' => 'Buchung bestätigen',
		'Končaj' => 'Abschließen',
		'Cena' => 'Preis',
		'Okvirna cena' => 'Ungefährer Preis',
		'Okvirna cena:' => 'Ungefährer Preis:',
		'Trajanje' => 'Dauer',
		'Trajanje:' => 'Dauer:',
		'min' => 'Min.',
		'minut' => 'Minuten',
		'Brezplačno' => 'Kostenlos',
		'Hvala' => 'Danke',
		'Hvala za rezervacijo!' => 'Vielen Dank für Ihre Buchung!',
		'Vielen Dank für die Buchung!' => 'Vielen Dank für Ihre Buchung!',
		'Vielen Dank für Ihre Reservierung!' => 'Vielen Dank für Ihre Buchung!',
		'Uspešno' => 'Erfolgreich',
		'ID termina:' => 'Termin-ID:',
		'Die ID des Steckplatz:' => 'Termin-ID:',
		'Appointment ID:' => 'Termin-ID:',
		'Vaš termin je bil uspešno rezerviran!' => 'Ihr Termin wurde erfolgreich gebucht!',
		'Ihre Zeit ist erfolgreich beiseite!' => 'Ihr Termin wurde erfolgreich gebucht!',
		'Your appointment has been booked successfully!' => 'Ihr Termin wurde erfolgreich gebucht!',
		'Informacije o terminu smo poslali na vaš email.' => 'Die Termininformationen wurden an Ihre E-Mail-Adresse gesendet.',
		'Für Informationen über die Zeit, wir haben gesendet, um Ihre E-Mail.' => 'Die Termininformationen wurden an Ihre E-Mail-Adresse gesendet.',
		'Appointment details have been sent to your email.' => 'Die Termininformationen wurden an Ihre E-Mail-Adresse gesendet.',
		'Storitev:' => 'Dienstleistung:',
		'Service provider:' => 'Dienstleistung:',
		'Čas in datum:' => 'Datum und Uhrzeit:',
		'Uhrzeit und Datum:in' => 'Datum und Uhrzeit:',
		'Time and Date:' => 'Datum und Uhrzeit:',
		'Ime stranke:' => 'Kunde:',
		'den Namen der Partei' => 'Kunde:',
		'Customer name:' => 'Kunde:',
		'Dodaj na koledar' => 'Zum Kalender hinzufügen',
		', um zum Kalender Hinzufügen' => 'Zum Kalender hinzufügen',
		'Add to calendar' => 'Zum Kalender hinzufügen',
		'Ni razpoložljivih terminov' => 'Keine freien Termine verfügbar',
		'Termin ni na voljo' => 'Der Termin ist nicht verfügbar',
		'Ta termin ni več na voljo' => 'Dieser Termin ist nicht mehr verfügbar',
		'Vaša rezervacija je bila uspešno oddana.' => 'Ihre Buchung wurde erfolgreich gesendet.',
		'Prosimo, izberite storitev za rezervacijo termina.' => 'Bitte wählen Sie eine Dienstleistung für die Terminbuchung aus.',
		'Prosimo, izberite datum termina za nadaljevanje rezervacije.' => 'Bitte wählen Sie ein Datum aus, um mit der Buchung fortzufahren.',
		'Prosimo, izberite časovni termin za nadaljevanje rezervacije.' => 'Bitte wählen Sie eine Uhrzeit aus, um mit der Buchung fortzufahren.',
		'Ime in priimek' => 'Vor- und Nachname',
		'Vnesite ime in priimek' => 'Vor- und Nachnamen eingeben',
		'Prosim vnesite ime in priimek' => 'Bitte geben Sie Vor- und Nachnamen ein',
		'Email naslov' => 'E-Mail-Adresse',
		'Vnesite email naslov' => 'E-Mail-Adresse eingeben',
		'Prosim vnesite email naslov' => 'Bitte geben Sie eine E-Mail-Adresse ein',
		'Telefon' => 'Telefon',
		'Vnesite telefonsko številko' => 'Telefonnummer eingeben',
		'Prosim vnesite telefonsko številko' => 'Bitte geben Sie eine Telefonnummer ein',
		'Opomba' => 'Notiz',
		'Note' => 'Notiz',
		'Enter note details' => 'Notiz eingeben',
		'Please enter appointment note' => 'Bitte geben Sie eine Notiz zum Termin ein',
		'Username' => 'Benutzername',
		'Enter your username' => 'Benutzernamen eingeben',
		'Please enter your username' => 'Bitte geben Sie einen Benutzernamen ein',
		'Enter your phone number' => 'Telefonnummer eingeben',
		'Vse' => 'Alles',
		'Vse kategorije' => 'Alle Kategorien',
		'Celotna nega - psi' => 'Komplettpflege - Hunde',
		'Posamezne storitve - psi' => 'Einzelleistungen - Hunde',
		'Razna nega - mačke' => 'Weitere Pflege - Katzen',
		'Mali psi (do 10kg) 38-44€' => 'Kleine Hunde (bis 10 kg) 38-44€',
		'Srednji psi (do 20kg) 46-59€' => 'Mittelgroße Hunde (bis 20 kg) 46-59€',
		'Veliki psi (do 40kg) 59-75€' => 'Große Hunde (bis 40 kg) 59-75€',
		'XL psi (nad 40kg) od 75€' => 'XL-Hunde (über 40 kg) ab 75€',
		'Nega obočesne dlake' => 'Pflege der Augenpartie',
		'Higienska nega' => 'Hygienepflege',
		'Krajšanje krempljev' => 'Krallen kürzen',
		'Korekcijsko striženje' => 'Korrekturschnitt',
		'Trimanje' => 'Trimmen',
		'Razčesavanje' => 'Entfilzen',
		'Celotna nega - mačke' => 'Komplettpflege - Katzen',
		'Razčesavanje - mačke' => 'Entfilzen - Katzen',
		'Ponedeljek' => 'Montag',
		'Torek' => 'Dienstag',
		'Sreda' => 'Mittwoch',
		'Četrtek' => 'Donnerstag',
		'Petek' => 'Freitag',
		'Sobota' => 'Samstag',
		'Nedelja' => 'Sonntag',
		'Pon' => 'Mo',
		'Tor' => 'Di',
		'Sre' => 'Mi',
		'Čet' => 'Do',
		'Pet' => 'Fr',
		'Sob' => 'Sa',
		'Ned' => 'So',
		'januar' => 'Januar',
		'februar' => 'Februar',
		'marec' => 'März',
		'april' => 'April',
		'maj' => 'Mai',
		'junij' => 'Juni',
		'julij' => 'Juli',
		'avgust' => 'August',
		'september' => 'September',
		'oktober' => 'Oktober',
		'november' => 'November',
		'december' => 'Dezember',
		'Select Category' => 'Kategorie auswählen',
		'Select Service' => 'Dienstleistung auswählen',
		'Service' => 'Dienstleistung',
		'Services' => 'Dienstleistungen',
		'Date' => 'Datum',
		'Time' => 'Uhrzeit',
		'Date & Time' => 'Datum und Uhrzeit',
		'Date and Time' => 'Datum und Uhrzeit',
		'Morning' => 'Vormittag',
		'Afternoon' => 'Nachmittag',
		'Basic Details' => 'Persönliche Daten',
		'Information' => 'Informationen',
		'Summary' => 'Zusammenfassung',
		'Booking Summary' => 'Zusammenfassung der Buchung',
		'Customer' => 'Kunde',
		'Payment' => 'Zahlung',
		'Next' => 'Weiter',
		'Back' => 'Zurück',
		'Previous' => 'Zurück',
		'Confirm' => 'Bestätigen',
		'Confirm Booking' => 'Buchung bestätigen',
		'Reserve' => 'Buchen',
		'Rezerviraj' => 'Buchen',
		'Book Appointment' => 'Termin buchen',
		'Book Now' => 'Jetzt buchen',
		'Price' => 'Preis',
		'Approximate price' => 'Ungefährer Preis',
		'Approximate Price' => 'Ungefährer Preis',
		'Estimated price' => 'Ungefährer Preis',
		'Estimated Price' => 'Ungefährer Preis',
		'Duration' => 'Dauer',
		'All' => 'Alle',
		'Free' => 'Kostenlos',
		'No service found' => 'Keine Dienstleistung gefunden',
		'No time slots available' => 'Keine freien Termine verfügbar',
		'Please select service to proceed booking.' => 'Bitte wählen Sie eine Dienstleistung aus, um fortzufahren.',
		'Please select appointment date to proceed booking.' => 'Bitte wählen Sie ein Datum aus, um fortzufahren.',
		'Please select appointment time to proceed booking.' => 'Bitte wählen Sie eine Uhrzeit aus, um fortzufahren.',
		'Full name' => 'Vor- und Nachname',
		'Name' => 'Name',
		'First name' => 'Vorname',
		'Last name' => 'Nachname',
		'Email address' => 'E-Mail-Adresse',
		'Email Address' => 'E-Mail-Adresse',
		'Phone' => 'Telefon',
		'Phone number' => 'Telefonnummer',
		'Enter your full name' => 'Vor- und Nachnamen eingeben',
		'Enter your email address' => 'E-Mail-Adresse eingeben',
		'Please enter your full name' => 'Bitte geben Sie Vor- und Nachnamen ein',
		'Please enter your email address' => 'Bitte geben Sie eine E-Mail-Adresse ein',
	];

	return $map;
}

/**
 * Return the German BookingPress thank-you page URL.
 *
 * @return string
 */
function dondog_bookingpress_german_thank_you_url() {
	return home_url( '/vielen-dank/' );
}

/**
 * Replace Slovenian/default BookingPress thank-you URLs on German booking pages.
 *
 * @param string $value URL or text value.
 * @return string
 */
function dondog_bookingpress_rewrite_thank_you_url( $value ) {
	if ( ! dondog_is_german() || ! is_string( $value ) || '' === trim( $value ) ) {
		return $value;
	}

	$trimmed = trim( $value );

	if ( false !== stripos( $trimmed, '/vielen-dank' ) ) {
		return $value;
	}

	$path = wp_parse_url( $trimmed, PHP_URL_PATH );

	if ( ! is_string( $path ) || '' === $path ) {
		$path = $trimmed;
	}

	$normalized_path = '/' . trim( rawurldecode( $path ), '/' ) . '/';
	$thank_you_paths = [
		'/thank-you/',
		'/sl/thank-you/',
		'/thankyou/',
		'/sl/thankyou/',
		'/hvala/',
		'/sl/hvala/',
		'/zahvala/',
		'/sl/zahvala/',
		'/hvala-za-rezervacijo/',
		'/sl/hvala-za-rezervacijo/',
	];

	if ( ! in_array( $normalized_path, $thank_you_paths, true ) ) {
		return $value;
	}

	$replacement = dondog_bookingpress_german_thank_you_url();
	$query       = wp_parse_url( $trimmed, PHP_URL_QUERY );
	$fragment    = wp_parse_url( $trimmed, PHP_URL_FRAGMENT );

	if ( is_string( $query ) && '' !== $query ) {
		$replacement .= '?' . $query;
	}

	if ( is_string( $fragment ) && '' !== $fragment ) {
		$replacement .= '#' . $fragment;
	}

	return str_replace( $trimmed, $replacement, $value );
}

/**
 * Recursively replace BookingPress thank-you URLs in form data.
 *
 * @param mixed $value BookingPress data.
 * @return mixed
 */
function dondog_bookingpress_rewrite_thank_you_urls_in_value( $value ) {
	if ( is_string( $value ) ) {
		return dondog_bookingpress_rewrite_thank_you_url( $value );
	}

	if ( is_array( $value ) ) {
		foreach ( $value as $key => $item ) {
			$value[ $key ] = dondog_bookingpress_rewrite_thank_you_urls_in_value( $item );
		}

		return $value;
	}

	if ( is_object( $value ) ) {
		foreach ( get_object_vars( $value ) as $key => $item ) {
			$value->{$key} = dondog_bookingpress_rewrite_thank_you_urls_in_value( $item );
		}
	}

	return $value;
}

/**
 * Filter BookingPress front-end form data before Vue receives it.
 *
 * @param mixed $bookingpress_booking_form_data BookingPress form data.
 * @return mixed
 */
function dondog_bookingpress_filter_frontend_thank_you_url( $bookingpress_booking_form_data ) {
	return dondog_bookingpress_rewrite_thank_you_urls_in_value( $bookingpress_booking_form_data );
}
add_filter( 'bookingpress_get_booking_form_customize_data_filter', 'dondog_bookingpress_filter_frontend_thank_you_url', 20 );
add_filter( 'bookingpress_customize_add_dynamic_data_fields', 'dondog_bookingpress_filter_frontend_thank_you_url', 20 );

/**
 * Return exact BookingPress text replacements for Slovenian pages.
 *
 * @return array<string,string>
 */
function dondog_bookingpress_slovenian_i18n_map() {
	return [
		'Note' => 'Opomba',
		'Notes' => 'Opombe',
		'Enter note details' => 'Vnesite opombo',
		'Please enter appointment note' => 'Prosimo, vnesite opombo termina.',
	];
}

/**
 * Return BookingPress replacements for the current page language.
 *
 * @return array<string,string>
 */
function dondog_bookingpress_current_i18n_map() {
	return dondog_is_german() ? dondog_bookingpress_i18n_map() : dondog_bookingpress_slovenian_i18n_map();
}

/**
 * Translate exact BookingPress strings generated through WordPress i18n.
 *
 * @param string $translated_text Current translated text.
 * @param string $text            Original text.
 * @param string $domain          Text domain.
 * @return string
 */
function dondog_translate_bookingpress_text( $translated_text, $text, $domain = '' ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $translated_text;
	}

	$map = dondog_bookingpress_current_i18n_map();

	if ( isset( $map[ $text ] ) ) {
		return $map[ $text ];
	}

	if ( isset( $map[ $translated_text ] ) ) {
		return $map[ $translated_text ];
	}

	return $translated_text;
}
add_filter( 'gettext', 'dondog_translate_bookingpress_text', 20, 3 );
add_filter( 'gettext_with_context', 'dondog_translate_bookingpress_text', 20, 3 );

/**
 * Translate plural BookingPress strings generated through WordPress i18n.
 *
 * @param string $translation Current translated text.
 * @param string $single      Singular text.
 * @param string $plural      Plural text.
 * @param int    $number      Number.
 * @param string $domain      Text domain.
 * @return string
 */
function dondog_translate_bookingpress_plural_text( $translation, $single, $plural, $number, $domain = '' ) {
	return dondog_translate_bookingpress_text( $translation, 1 === (int) $number ? $single : $plural, $domain );
}
add_filter( 'ngettext', 'dondog_translate_bookingpress_plural_text', 20, 5 );
add_filter( 'ngettext_with_context', 'dondog_translate_bookingpress_plural_text', 20, 5 );

/**
 * Enqueue the front-end fallback for BookingPress Vue-rendered text.
 *
 * @return void
 */
function dondog_enqueue_bookingpress_i18n() {
	if ( is_admin() ) {
		return;
	}

	$map = dondog_bookingpress_current_i18n_map();

	if ( [] === $map ) {
		return;
	}

	$script_path = get_stylesheet_directory() . '/assets/js/bookingpress-i18n.js';

	if ( ! file_exists( $script_path ) ) {
		return;
	}

	wp_enqueue_script(
		'dondog-bookingpress-i18n',
		get_stylesheet_directory_uri() . '/assets/js/bookingpress-i18n.js',
		[],
		DONDOG_THEME_VERSION,
		true
	);

	wp_add_inline_script(
		'dondog-bookingpress-i18n',
		'window.dondogBookingPressI18n = ' . wp_json_encode( $map ) . ';window.dondogBookingPressI18nConfig = ' . wp_json_encode(
			[
				'language'          => dondog_get_current_language(),
				'thankYouUrl'       => dondog_is_german() ? dondog_bookingpress_german_thank_you_url() : '',
				'germanThankYouUrl' => dondog_bookingpress_german_thank_you_url(),
			]
		) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'dondog_enqueue_bookingpress_i18n', 100 );
