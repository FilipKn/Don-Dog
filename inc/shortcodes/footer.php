<?php
/**
 * Footer shortcode.
 *
 * Usage:
 * [dondog_footer logo="13" instagram_icon="124" facebook_icon="125"]
 *
 * @package DonDog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the footer shortcode.
 *
 * @return void
 */
function dondog_register_footer_shortcode() {
	add_shortcode( 'dondog_footer', 'dondog_render_footer_shortcode' );
}
add_action( 'init', 'dondog_register_footer_shortcode' );

/**
 * Render the Don Dog footer.
 *
 * @param array<string,string> $atts Shortcode attributes.
 * @return string
 */
function dondog_render_footer_shortcode( $atts ) {
	$defaults = [
		'logo'           => '',
		'brand_name'     => 'Don Dog',
		'description'    => 'Premium pasji salon v Radljah ob Dravi. Vaš pes si zasluži najboljše - mi mu to damo.',
		'instagram_icon' => '',
		'instagram_url'  => 'https://www.instagram.com/pasji_salon_dondog/',
		'facebook_icon'  => '',
		'facebook_url'   => 'https://www.facebook.com/pasjisalonDONDOG/',
		'nav_title'      => 'Navigacija',
		'nav_items'      => 'Domov|/;Storitve|/cenik/;Galerija|/galerija/;Kontakt|/kontakt/',
		'contact_title'  => 'Kontakt',
		'address_icon'   => '',
		'address'        => 'Dobrava 5a, Radlje ob Dravi, Slovenija',
		'phone_icon'     => '',
		'phone'          => '+386 40 659 936',
		'phone_url'      => 'tel:+38640659936',
		'email_icon'     => '',
		'email'          => 'donablaznik@gmail.com',
		'email_url'      => 'mailto:donablaznik@gmail.com',
		'hours_title'    => 'Delovni čas',
		'hours_icon'     => '',
		'hours_items'    => 'Pon - Pet|8:00 - 18:00;Sob|Zaprto;Ned|Zaprto',
		'copyright'      => '(c) 2026 Don Dog. Vse pravice pridržane.',
		'privacy_text'   => 'Politika zasebnosti',
		'privacy_url'    => 'https://dondog.si/sl/politika-zasebnosti/',
		'credit'         => 'Oblikovano z ljubeznijo do psov',
	];

	$atts = shortcode_atts( $defaults, $atts, 'dondog_footer' );
	$atts = dondog_apply_shortcode_language_defaults( 'dondog_footer', $atts, $defaults );
	$atts['privacy_url'] = dondog_translate_url( $atts['privacy_url'] );

	ob_start();
	?>
	<footer class="dondog-footer" aria-label="<?php echo esc_attr( dondog_lang_text( 'Noga strani', 'Fußzeile' ) ); ?>">
		<div class="dondog-footer__main">
			<section class="dondog-footer__brand" aria-label="<?php echo esc_attr__( 'Don Dog', 'dondog' ); ?>">
				<?php echo dondog_footer_render_logo( $atts['logo'], $atts['brand_name'] ); ?>

				<?php if ( '' !== trim( $atts['description'] ) ) : ?>
					<p class="dondog-footer__description"><?php echo esc_html( $atts['description'] ); ?></p>
				<?php endif; ?>

				<div class="dondog-footer__socials" aria-label="<?php echo esc_attr( dondog_lang_text( 'Družabna omrežja', 'Soziale Netzwerke' ) ); ?>">
					<?php
					echo dondog_footer_render_social_link( $atts['instagram_url'], $atts['instagram_icon'], 'Instagram', 'IG' );
					echo dondog_footer_render_social_link( $atts['facebook_url'], $atts['facebook_icon'], 'Facebook', 'FB' );
					?>
				</div>
			</section>

			<nav class="dondog-footer__column" aria-label="<?php echo esc_attr( dondog_lang_text( 'Navigacija', 'Navigation' ) ); ?>">
				<h2 class="dondog-footer__heading"><?php echo esc_html( $atts['nav_title'] ); ?></h2>
				<ul class="dondog-footer__links">
					<?php foreach ( dondog_footer_parse_pairs( $atts['nav_items'] ) as $item ) : ?>
						<li>
							<a href="<?php echo esc_url( dondog_translate_url( $item['value'] ) ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<section class="dondog-footer__column" aria-label="<?php echo esc_attr( dondog_lang_text( 'Kontakt', 'Kontakt' ) ); ?>">
				<h2 class="dondog-footer__heading"><?php echo esc_html( $atts['contact_title'] ); ?></h2>
				<ul class="dondog-footer__contact">
					<li>
						<?php echo dondog_footer_render_icon( $atts['address_icon'], dondog_lang_text( 'Lokacija', 'Standort' ), 'pin' ); ?>
						<span><?php echo esc_html( $atts['address'] ); ?></span>
					</li>
					<li>
						<?php echo dondog_footer_render_icon( $atts['phone_icon'], dondog_lang_text( 'Telefon', 'Telefon' ), 'phone' ); ?>
						<a href="<?php echo esc_url( $atts['phone_url'] ); ?>"><?php echo esc_html( $atts['phone'] ); ?></a>
					</li>
					<li>
						<?php echo dondog_footer_render_icon( $atts['email_icon'], dondog_lang_text( 'Email', 'E-Mail' ), 'mail' ); ?>
						<a href="<?php echo esc_url( $atts['email_url'] ); ?>"><?php echo esc_html( $atts['email'] ); ?></a>
					</li>
				</ul>
			</section>

			<section class="dondog-footer__column" aria-label="<?php echo esc_attr( dondog_lang_text( 'Delovni čas', 'Öffnungszeiten' ) ); ?>">
				<h2 class="dondog-footer__heading"><?php echo esc_html( $atts['hours_title'] ); ?></h2>
				<ul class="dondog-footer__hours">
					<?php foreach ( dondog_footer_parse_pairs( $atts['hours_items'] ) as $item ) : ?>
						<li>
							<?php echo dondog_footer_render_icon( $atts['hours_icon'], dondog_lang_text( 'Čas', 'Zeit' ), 'time' ); ?>
							<span><strong><?php echo esc_html( $item['label'] ); ?></strong> - <?php echo esc_html( $item['value'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		</div>

		<div class="dondog-footer__bottom">
			<p>
				<?php echo esc_html( $atts['copyright'] ); ?>

				<?php if ( '' !== trim( $atts['privacy_text'] ) && '' !== trim( $atts['privacy_url'] ) ) : ?>
					<span class="dondog-footer__separator" aria-hidden="true">|</span>
					<a href="<?php echo esc_url( $atts['privacy_url'] ); ?>"><?php echo esc_html( $atts['privacy_text'] ); ?></a>
				<?php endif; ?>
			</p>
			<p><?php echo esc_html( $atts['credit'] ); ?></p>
		</div>
	</footer>
	<?php

	return ob_get_clean();
}

/**
 * Render footer logo from attachment ID or URL.
 *
 * @param string $logo       WordPress attachment ID or image URL.
 * @param string $brand_name Brand fallback text.
 * @return string
 */
function dondog_footer_render_logo( $logo, $brand_name ) {
	$image = dondog_footer_render_image( $logo, 'dondog-footer__logo-img', $brand_name );

	if ( $image ) {
		return '<div class="dondog-footer__logo">' . $image . '</div>';
	}

	return sprintf(
		'<div class="dondog-footer__logo dondog-footer__logo--fallback"><span>DD</span><strong>%s</strong></div>',
		esc_html( $brand_name )
	);
}

/**
 * Render one social link.
 *
 * @param string $url      Link URL.
 * @param string $icon     WordPress attachment ID or image URL.
 * @param string $label    Accessible label.
 * @param string $fallback Fallback text.
 * @return string
 */
function dondog_footer_render_social_link( $url, $icon, $label, $fallback ) {
	return sprintf(
		'<a class="dondog-footer__social" href="%1$s" aria-label="%2$s">%3$s</a>',
		esc_url( $url ),
		esc_attr( $label ),
		dondog_footer_render_icon( $icon, $label, $fallback )
	);
}

/**
 * Render icon from attachment ID or URL with a text fallback.
 *
 * @param string $icon     WordPress attachment ID or image URL.
 * @param string $label    Accessible label.
 * @param string $fallback Fallback text.
 * @return string
 */
function dondog_footer_render_icon( $icon, $label, $fallback ) {
	$image = dondog_footer_render_image( $icon, 'dondog-footer__icon-img', $label );

	if ( $image ) {
		return '<span class="dondog-footer__icon">' . $image . '</span>';
	}

	return sprintf(
		'<span class="dondog-footer__icon dondog-footer__icon--fallback" aria-hidden="true">%s</span>',
		esc_html( $fallback )
	);
}

/**
 * Render image from attachment ID or URL.
 *
 * @param string $source Image attachment ID or URL.
 * @param string $class  Image class.
 * @param string $alt    Image alt text.
 * @return string
 */
function dondog_footer_render_image( $source, $class, $alt ) {
	$source = trim( (string) $source );

	if ( is_numeric( $source ) ) {
		return wp_get_attachment_image(
			absint( $source ),
			'medium',
			false,
			[
				'class'    => $class,
				'alt'      => $alt,
				'loading'  => 'lazy',
				'decoding' => 'async',
			]
		);
	}

	if ( filter_var( $source, FILTER_VALIDATE_URL ) ) {
		return sprintf(
			'<img class="%1$s" src="%2$s" alt="%3$s" loading="lazy" decoding="async">',
			esc_attr( $class ),
			esc_url( $source ),
			esc_attr( $alt )
		);
	}

	return '';
}

/**
 * Parse semicolon-separated label/value pairs.
 *
 * @param string $raw Raw pair list like label|value;label|value.
 * @return array<int,array{label:string,value:string}>
 */
function dondog_footer_parse_pairs( $raw ) {
	$items = [];
	$pairs = array_filter( array_map( 'trim', explode( ';', (string) $raw ) ) );

	foreach ( $pairs as $pair ) {
		$parts = array_map( 'trim', explode( '|', $pair, 2 ) );

		if ( 2 !== count( $parts ) || '' === $parts[0] ) {
			continue;
		}

		$items[] = [
			'label' => $parts[0],
			'value' => $parts[1],
		];
	}

	return $items;
}
