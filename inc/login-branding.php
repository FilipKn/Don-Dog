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
	wp_enqueue_style(
		'dondog-login-branding',
		get_stylesheet_directory_uri() . '/assets/css/login.css',
		[],
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
			<p class="dondog-login-visual__eyebrow">Nadzorna plosca</p>
			<p class="dondog-login-visual__title">Dobrodosli nazaj v Don Dog.</p>
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
		esc_html__( 'Vnesite svoje podatke za dostop do nadzorne plosce.', 'dondog' )
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

add_filter( 'login_display_language_dropdown', '__return_false' );
