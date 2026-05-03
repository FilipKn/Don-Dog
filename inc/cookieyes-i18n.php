<?php
/**
 * CookieYes front-end language support.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return exact CookieYes text replacements for German pages.
 *
 * @return array<string,string>
 */
function dondog_cookieyes_i18n_map() {
	static $map = null;

	if ( null !== $map ) {
		return $map;
	}

	$map = [
		'Customise Consent Preferences' => 'Einwilligungseinstellungen anpassen',
		'Customize Consent Preferences' => 'Einwilligungseinstellungen anpassen',
		'Consent Preferences' => 'Einwilligungseinstellungen',
		'Cookie Settings' => 'Cookie-Einstellungen',
		'Cookie preferences' => 'Cookie-Einstellungen',
		'Privacy Overview' => 'Datenschutzübersicht',
		'Cenimo vašo zasebnost' => 'Wir respektieren Ihre Privatsphäre',
		'We value your privacy' => 'Wir respektieren Ihre Privatsphäre',
		'This website uses cookies' => 'Diese Website verwendet Cookies',
		'To spletno mesto uporablja piškotke' => 'Diese Website verwendet Cookies',
		'Piškotke uporabljamo za zagotavljanje učinkovite navigacije in izvajanje določenih funkcij. Podrobne informacije o vseh piškotkih najdete pod posameznimi kategorijami privolitve spodaj.' => 'Wir verwenden Cookies, um eine effiziente Navigation zu gewährleisten und bestimmte Funktionen auszuführen. Detaillierte Informationen zu allen Cookies finden Sie unten in den jeweiligen Einwilligungskategorien.',
		'Piškotki, ki so razvrščeni kot »Nujni«, se shranijo v vašem brskalniku, saj so bistveni za omogočanje osnovnih funkcij spletnega mesta.' => 'Cookies, die als „notwendig“ eingestuft sind, werden in Ihrem Browser gespeichert, da sie für die grundlegenden Funktionen der Website erforderlich sind.',
		'Piškotki, ki so razvrščeni kot »Nujni«, se shranijo v vašem brskalniku, saj so bistveni za omogočanje osnovnih funkcij spletnega mesta....' => 'Cookies, die als „notwendig“ eingestuft sind, werden in Ihrem Browser gespeichert, da sie für die grundlegenden Funktionen der Website erforderlich sind....',
		'Necessary' => 'Notwendig',
		'Nujni' => 'Notwendig',
		'Functional' => 'Funktional',
		'Funkcionalni' => 'Funktional',
		'Analytics' => 'Analyse',
		'Analitika' => 'Analyse',
		'Performance' => 'Leistung',
		'Zmogljivost' => 'Leistung',
		'Advertisement' => 'Werbung',
		'Oglaševanje' => 'Werbung',
		'Other' => 'Sonstige',
		'Drugo' => 'Sonstige',
		'Uncategorized' => 'Nicht kategorisiert',
		'Nekategorizirano' => 'Nicht kategorisiert',
		'Always Active' => 'Immer aktiv',
		'Vedno aktivno' => 'Immer aktiv',
		'Enabled' => 'Aktiviert',
		'Disabled' => 'Deaktiviert',
		'Show more' => 'Mehr anzeigen',
		'Prikaži več' => 'Mehr anzeigen',
		'Show less' => 'Weniger anzeigen',
		'Prikaži manj' => 'Weniger anzeigen',
		'Accept All' => 'Alle akzeptieren',
		'Sprejmi vse' => 'Alle akzeptieren',
		'Reject All' => 'Alle ablehnen',
		'Zavrni vse' => 'Alle ablehnen',
		'Customize' => 'Anpassen',
		'Customise' => 'Anpassen',
		'Prilagodi' => 'Anpassen',
		'Save My Preferences' => 'Meine Einstellungen speichern',
		'Shrani moje nastavitve' => 'Meine Einstellungen speichern',
		'Close' => 'Schließen',
		'Zapri' => 'Schließen',
		'Cookie List' => 'Cookie-Liste',
		'Seznam piškotkov' => 'Cookie-Liste',
		'Cookie' => 'Cookie',
		'Duration' => 'Dauer',
		'Description' => 'Beschreibung',
		'Provider' => 'Anbieter',
		'Type' => 'Typ',
		'Powered by' => 'Bereitgestellt von',
		'Necessary cookies are required to enable the basic features of this site, such as providing secure log-in or adjusting your consent preferences. These cookies do not store any personally identifiable data.' => 'Notwendige Cookies sind erforderlich, um die grundlegenden Funktionen dieser Website zu ermöglichen, zum Beispiel sichere Anmeldung oder das Speichern Ihrer Einwilligungseinstellungen. Diese Cookies speichern keine personenbezogenen Daten.',
		'Functional cookies help perform certain functionalities like sharing the content of the website on social media platforms, collecting feedback, and other third-party features.' => 'Funktionale Cookies helfen dabei, bestimmte Funktionen auszuführen, zum Beispiel Inhalte der Website auf sozialen Medien zu teilen, Feedback zu sammeln und weitere Funktionen von Drittanbietern bereitzustellen.',
		'Analytical cookies are used to understand how visitors interact with the website. These cookies help provide information on metrics such as the number of visitors, bounce rate, traffic source, etc.' => 'Analyse-Cookies helfen uns zu verstehen, wie Besucher mit der Website interagieren. Diese Cookies liefern Informationen zu Kennzahlen wie Besucherzahl, Absprungrate und Traffic-Quelle.',
		'Performance cookies are used to understand and analyse the key performance indexes of the website which helps in delivering a better user experience for the visitors.' => 'Leistungs-Cookies werden verwendet, um wichtige Leistungskennzahlen der Website zu verstehen und zu analysieren. Dadurch können wir den Besuchern eine bessere Nutzererfahrung bieten.',
		'Advertisement cookies are used to provide visitors with customised advertisements based on the pages you visited previously and to analyse the effectiveness of the ad campaigns.' => 'Werbe-Cookies werden verwendet, um Besuchern personalisierte Werbung basierend auf zuvor besuchten Seiten bereitzustellen und die Wirksamkeit von Werbekampagnen zu analysieren.',
		'Other uncategorized cookies are those that are being analyzed and have not been classified into a category as yet.' => 'Sonstige nicht kategorisierte Cookies werden derzeit analysiert und wurden noch keiner Kategorie zugeordnet.',
		'We use cookies to enhance your browsing experience, serve personalized ads or content, and analyze our traffic. By clicking "Accept All", you consent to our use of cookies.' => 'Wir verwenden Cookies, um Ihr Surferlebnis zu verbessern, personalisierte Inhalte bereitzustellen und unseren Datenverkehr zu analysieren. Mit einem Klick auf „Alle akzeptieren“ stimmen Sie der Verwendung von Cookies zu.',
		'We use cookies to improve your browsing experience, personalize content and ads, provide social media features, and analyze our traffic.' => 'Wir verwenden Cookies, um Ihr Surferlebnis zu verbessern, Inhalte und Anzeigen zu personalisieren, Social-Media-Funktionen bereitzustellen und unseren Datenverkehr zu analysieren.',
		'Uporabljamo piškotke za izboljšanje vaše izkušnje brskanja, prikaz prilagojenih oglasov ali vsebine in analizo našega prometa.' => 'Wir verwenden Cookies, um Ihr Surferlebnis zu verbessern, personalisierte Inhalte bereitzustellen und unseren Datenverkehr zu analysieren.',
		'By clicking “Accept All”, you consent to our use of cookies.' => 'Mit einem Klick auf „Alle akzeptieren“ stimmen Sie der Verwendung von Cookies zu.',
		'By clicking "Accept All", you consent to our use of cookies.' => 'Mit einem Klick auf „Alle akzeptieren“ stimmen Sie der Verwendung von Cookies zu.',
		'Z izbiro »Sprejmi vse« soglašate z uporabo vseh piškotkov.' => 'Mit einem Klick auf „Alle akzeptieren“ stimmen Sie der Verwendung aller Cookies zu.',
		'Read More' => 'Mehr erfahren',
		'Preberi več' => 'Mehr erfahren',
		'Privacy Policy' => 'Datenschutzerklärung',
		'Politika zasebnosti' => 'Datenschutzerklärung',
	];

	return $map;
}

/**
 * Enqueue CookieYes front-end text translations.
 *
 * @return void
 */
function dondog_enqueue_cookieyes_i18n() {
	if ( is_admin() || ! dondog_is_german() ) {
		return;
	}

	$script_path = get_stylesheet_directory() . '/assets/js/cookieyes-i18n.js';

	if ( ! file_exists( $script_path ) ) {
		return;
	}

	wp_enqueue_script(
		'dondog-cookieyes-i18n',
		get_stylesheet_directory_uri() . '/assets/js/cookieyes-i18n.js',
		[],
		DONDOG_THEME_VERSION,
		true
	);

	wp_add_inline_script(
		'dondog-cookieyes-i18n',
		'window.dondogCookieYesI18n = ' . wp_json_encode( dondog_cookieyes_i18n_map() ) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'dondog_enqueue_cookieyes_i18n', 110 );
