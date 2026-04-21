<?php
/**
 * Custom WordPress login branding.
 *
 * Paste your image URLs into the constants below.
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'DONDOG_LOGIN_IMAGE_URL' ) ) {
	define( 'DONDOG_LOGIN_IMAGE_URL', 'https://dondog.si/wp-content/uploads/2026/03/dondog_promo_januar2025_fotonezaternik-53-scaled.jpg' );
}

if ( ! defined( 'DONDOG_LOGIN_LOGO_URL' ) ) {
	define( 'DONDOG_LOGIN_LOGO_URL', 'https://dondog.si/wp-content/uploads/2026/03/dd-removebg-preview-e1774854354872.png' );
}

/**
 * Enqueue custom login styles.
 *
 * @return void
 */
function dondog_enqueue_login_branding() {
	dondog_enqueue_playfair_display();

	wp_enqueue_style(
		'dondog-login-branding',
		get_stylesheet_directory_uri() . '/assets/css/login.css',
		[
			'dondog-playfair-display',
		],
		DONDOG_THEME_VERSION
	);

	$custom_css = '';

	if ( '' !== DONDOG_LOGIN_IMAGE_URL ) {
		$custom_css .= sprintf(
			'body.login{--dondog-login-image:url("%s");}',
			esc_url( DONDOG_LOGIN_IMAGE_URL )
		);
	}

	if ( '' !== DONDOG_LOGIN_LOGO_URL ) {
		$custom_css .= sprintf(
			'body.login{--dondog-login-logo:url("%s");}',
			esc_url( DONDOG_LOGIN_LOGO_URL )
		);
	}

	if ( '' !== $custom_css ) {
		wp_add_inline_style( 'dondog-login-branding', $custom_css );
	}
}
add_action( 'login_enqueue_scripts', 'dondog_enqueue_login_branding' );

/**
 * Add the left visual panel before the WordPress login box.
 *
 * @return void
 */
function dondog_render_login_visual_panel() {
	?>
	<section class="dondog-login-visual" aria-label="<?php echo esc_attr__( 'Don Dog prijava', 'dondog' ); ?>">
		<div class="dondog-login-visual__content">
			<p class="dondog-login-visual__eyebrow">Nadzorna plošča</p>
			<p class="dondog-login-visual__title">Dobrodošli nazaj v Don Dog.</p>
			<p class="dondog-login-visual__text">Upravljajte svoje termine, stranke in storitve na enem mestu.</p>
		</div>
	</section>
	<?php
}
add_action( 'login_header', 'dondog_render_login_visual_panel' );

/**
 * Add a branded intro above the login form.
 *
 * @param string $message Existing WordPress login message.
 * @return string
 */
function dondog_login_intro_message( $message ) {
	$intro = sprintf(
		'<div class="dondog-login-intro"><p class="dondog-login-intro__eyebrow">%1$s</p><h2>%2$s</h2><p>%3$s</p></div>',
		esc_html__( 'WP-ADMIN', 'dondog' ),
		esc_html__( 'Prijava v upravljanje', 'dondog' ),
		esc_html__( 'Vnesite svoje podatke za dostop do nadzorne plošče.', 'dondog' )
	);

	return $intro . $message;
}
add_filter( 'login_message', 'dondog_login_intro_message' );

/**
 * Make the login logo link back to the public site.
 *
 * @return string
 */
function dondog_login_logo_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'dondog_login_logo_url' );

/**
 * Set accessible login logo text.
 *
 * @return string
 */
function dondog_login_logo_text() {
	return 'Don Dog';
}
add_filter( 'login_headertext', 'dondog_login_logo_text' );

/**
 * Replace the default "Go to site" button on the login screen.
 *
 * @return string
 */
function dondog_login_site_link() {
	return sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Nazaj na Don Dog spletno stran', 'dondog' )
	);
}
add_filter( 'login_site_html_link', 'dondog_login_site_link' );

/**
 * Translate the default WordPress login form strings.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @return string
 */
function dondog_translate_login_text( $translation, $text ) {
	if ( ! dondog_is_login_screen() ) {
		return $translation;
	}

	$translations = [
		'Username or Email Address' => 'Uporabniško ime ali e-pošta',
		'Password'                  => 'Geslo',
		'Remember Me'               => 'Zapomni si me',
		'Log In'                    => 'Prijava',
		'Lost your password?'       => 'Ste pozabili geslo?',
		'You are now logged out.'   => 'Uspešno ste odjavljeni.',
		'Show password'             => 'Prikaži geslo',
		'Hide password'             => 'Skrij geslo',
		'Get New Password'          => 'Pridobi novo geslo',
		'Email Address'             => 'E-poštni naslov',
		'Username'                  => 'Uporabniško ime',
		'Go to DonDog'              => 'Nazaj na Don Dog spletno stran',
		'← Go to DonDog'            => 'Nazaj na Don Dog spletno stran',
		'&larr; Go to DonDog'       => 'Nazaj na Don Dog spletno stran',
	];

	return $translations[ $text ] ?? $translation;
}
add_filter( 'gettext', 'dondog_translate_login_text', 20, 2 );

/**
 * Translate strings that contain placeholders.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @return string
 */
function dondog_translate_login_text_with_context( $translation, $text ) {
	if ( ! dondog_is_login_screen() ) {
		return $translation;
	}

	if ( '&larr; Go to %s' === $text || false !== strpos( $text, 'Go to %s' ) ) {
		return 'Nazaj na %s';
	}

	return $translation;
}
add_filter( 'gettext', 'dondog_translate_login_text_with_context', 21, 2 );

add_filter( 'login_display_language_dropdown', '__return_false' );

/**
 * Keep the customized login form stable after WordPress redirects.
 *
 * Some password managers refill the just-used password into both login fields
 * after WordPress redirects back with loggedout=true. WordPress and security
 * plugins can also re-render the password wrapper slightly later, so the form
 * is normalized a few times after load.
 *
 * @return void
 */
function dondog_stabilize_login_form() {
	$is_logged_out = isset( $_GET['loggedout'] );
	?>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var loggedOut = <?php echo $is_logged_out ? 'true' : 'false'; ?>;
			var form = document.getElementById('loginform');
			var user = document.getElementById('user_login');
			var pass = document.getElementById('user_pass');
			var passwordWrap = document.querySelector('.user-pass-wrap');
			var passwordField = document.querySelector('.wp-pwd');
			var submit = document.getElementById('wp-submit');

			function ensureLoginForm() {
				if (!form) {
					return;
				}

				if (passwordWrap) {
					passwordWrap.style.display = 'block';
					passwordWrap.style.visibility = 'visible';
					passwordWrap.style.opacity = '1';
				}

				if (passwordField) {
					passwordField.style.display = 'block';
					passwordField.style.visibility = 'visible';
					passwordField.style.opacity = '1';
				}

				if (!pass && passwordField) {
					pass = document.createElement('input');
					pass.type = 'password';
					pass.name = 'pwd';
					pass.id = 'user_pass';
					pass.className = 'input password-input';
					pass.size = '20';
					pass.autocomplete = 'current-password';
					pass.spellcheck = false;
					passwordField.insertBefore(pass, passwordField.firstChild);
				}

				if (pass) {
					pass.type = 'password';
					pass.style.display = 'block';
					pass.style.visibility = 'visible';
					pass.style.opacity = '1';
					pass.setAttribute('autocomplete', 'current-password');
				}

				if (!submit) {
					var submitWrap = document.createElement('p');
					submitWrap.className = 'submit';

					submit = document.createElement('input');
					submit.type = 'submit';
					submit.name = 'wp-submit';
					submit.id = 'wp-submit';
					submit.className = 'button button-primary button-large';
					submit.value = 'Prijava';

					submitWrap.appendChild(submit);
					form.appendChild(submitWrap);
				}

				if (submit) {
					submit.value = 'Prijava';
					submit.style.display = 'flex';
					submit.style.visibility = 'visible';
					submit.style.opacity = '1';
				}
			}

			function clearLoginFields() {
				if (user) {
					user.value = '';
					user.setAttribute('autocomplete', 'off');
				}

				if (pass) {
					pass.value = '';
					pass.setAttribute('autocomplete', 'current-password');
				}
			}

			function normalizeLoginForm() {
				ensureLoginForm();

				if (loggedOut) {
					clearLoginFields();
				}
			}

			normalizeLoginForm();
			window.setTimeout(normalizeLoginForm, 120);
			window.setTimeout(normalizeLoginForm, 450);
			window.setTimeout(normalizeLoginForm, 900);
		});
	</script>
	<?php
}
add_action( 'login_footer', 'dondog_stabilize_login_form' );

/**
 * Check whether the current request is the WordPress login screen.
 *
 * @return bool
 */
function dondog_is_login_screen() {
	global $pagenow;

	return 'wp-login.php' === $pagenow;
}
