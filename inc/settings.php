<?php
/**
 * Contact information settings.
 *
 * Adds a "Informações de Contato" page under Settings where the e-mail,
 * WhatsApp, address, map and social links are edited. The contact page,
 * footer and structured data read these values via mage_contact().
 *
 * @package mage
 */

if ( ! function_exists( 'mage_contact_defaults' ) ) {
	function mage_contact_defaults() {
		return array(
			'empresa'       => 'Matheus Felipe Marques',
			'cnpj'          => '39.944.754/0001-35',
			'email'         => 'contato@magesystems.com.br',
			'whatsapp_num'  => '',
			'whatsapp_show' => '',
			'address_line1' => 'Rua Venezuela, 74 – Bairro das Nações',
			'address_line2' => 'Concórdia – SC · CEP 89.708-130',
			'map_query'     => 'Rua Venezuela, 74, Concórdia - SC, 89708-130',
			'instagram'     => 'https://instagram.com/magesystems',
			'linkedin'      => 'https://linkedin.com/company/magesystems',
			'facebook'      => '',
			'youtube'       => '',
			'twitter'       => '',
			'tiktok'        => '',
		);
	}
}

if ( ! function_exists( 'mage_contact' ) ) {
	/**
	 * Get a contact-info value.
	 *
	 * Before the settings are ever saved, the theme defaults are used. Once
	 * saved, the stored value is returned as-is (an empty value is respected,
	 * so a field can be intentionally cleared).
	 *
	 * @param string      $key      Field key.
	 * @param string|null $fallback Optional explicit fallback.
	 * @return string
	 */
	function mage_contact( $key, $fallback = null ) {
		$opts = get_option( 'mage_contact', null );
		if ( is_array( $opts ) && array_key_exists( $key, $opts ) ) {
			return (string) $opts[ $key ];
		}
		if ( null !== $fallback ) {
			return $fallback;
		}
		$defaults = mage_contact_defaults();
		return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	}
}

// ── Register the settings page ────────────────────────────────────────────────
add_action( 'admin_menu', function () {
	add_options_page(
		__( 'Informações de Contato', 'mage' ),
		__( 'Informações de Contato', 'mage' ),
		'manage_options',
		'mage-contato',
		'mage_contact_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'mage_contact_group', 'mage_contact', array(
		'type'              => 'array',
		'sanitize_callback' => 'mage_contact_sanitize',
		'default'           => array(),
	) );

	add_settings_section( 'mage_contact_main', '', '__return_false', 'mage-contato' );

	$fields = array(
		'empresa'       => array( __( 'Razão social / Nome', 'mage' ), 'text' ),
		'cnpj'          => array( __( 'CNPJ', 'mage' ), 'text' ),
		'email'         => array( __( 'E-mail', 'mage' ), 'email' ),
		'whatsapp_num'  => array( __( 'WhatsApp — número (55 + DDD + número, só dígitos)', 'mage' ), 'text' ),
		'whatsapp_show' => array( __( 'WhatsApp — como exibir', 'mage' ), 'text' ),
		'address_line1' => array( __( 'Endereço — linha 1', 'mage' ), 'text' ),
		'address_line2' => array( __( 'Endereço — linha 2', 'mage' ), 'text' ),
		'map_query'     => array( __( 'Endereço do mapa (Google Maps)', 'mage' ), 'text' ),
		'instagram'     => array( __( 'Instagram (URL)', 'mage' ), 'url' ),
		'linkedin'      => array( __( 'LinkedIn (URL)', 'mage' ), 'url' ),
		'facebook'      => array( __( 'Facebook (URL)', 'mage' ), 'url' ),
		'youtube'       => array( __( 'YouTube (URL)', 'mage' ), 'url' ),
		'twitter'       => array( __( 'X / Twitter (URL)', 'mage' ), 'url' ),
		'tiktok'        => array( __( 'TikTok (URL)', 'mage' ), 'url' ),
	);

	foreach ( $fields as $key => $field ) {
		add_settings_field(
			'mage_contact_' . $key,
			esc_html( $field[0] ),
			'mage_contact_field_cb',
			'mage-contato',
			'mage_contact_main',
			array( 'key' => $key, 'type' => $field[1], 'label_for' => 'mage_contact_' . $key )
		);
	}
} );

if ( ! function_exists( 'mage_contact_field_cb' ) ) {
	function mage_contact_field_cb( $args ) {
		$key   = $args['key'];
		$type  = ( 'email' === $args['type'] ) ? 'email' : ( ( 'url' === $args['type'] ) ? 'url' : 'text' );
		$value = mage_contact( $key );
		printf(
			'<input type="%1$s" id="mage_contact_%2$s" name="mage_contact[%2$s]" value="%3$s" class="regular-text">',
			esc_attr( $type ),
			esc_attr( $key ),
			esc_attr( $value )
		);
		if ( 'whatsapp_num' === $key ) {
			echo '<p class="description">' . esc_html__( 'Deixe em branco para ocultar o WhatsApp. Ex.: 5549999999999', 'mage' ) . '</p>';
		}
	}
}

if ( ! function_exists( 'mage_contact_sanitize' ) ) {
	function mage_contact_sanitize( $input ) {
		$input = is_array( $input ) ? $input : array();
		return array(
			'empresa'       => sanitize_text_field( $input['empresa'] ?? '' ),
			'cnpj'          => sanitize_text_field( $input['cnpj'] ?? '' ),
			'email'         => sanitize_email( $input['email'] ?? '' ),
			'whatsapp_num'  => preg_replace( '/\D/', '', (string) ( $input['whatsapp_num'] ?? '' ) ),
			'whatsapp_show' => sanitize_text_field( $input['whatsapp_show'] ?? '' ),
			'address_line1' => sanitize_text_field( $input['address_line1'] ?? '' ),
			'address_line2' => sanitize_text_field( $input['address_line2'] ?? '' ),
			'map_query'     => sanitize_text_field( $input['map_query'] ?? '' ),
			'instagram'     => esc_url_raw( $input['instagram'] ?? '' ),
			'linkedin'      => esc_url_raw( $input['linkedin'] ?? '' ),
			'facebook'      => esc_url_raw( $input['facebook'] ?? '' ),
			'youtube'       => esc_url_raw( $input['youtube'] ?? '' ),
			'twitter'       => esc_url_raw( $input['twitter'] ?? '' ),
			'tiktok'        => esc_url_raw( $input['tiktok'] ?? '' ),
		);
	}
}

if ( ! function_exists( 'mage_social_links' ) ) {
	/**
	 * Configured social links (only those with a URL), each with an SVG icon.
	 *
	 * @return array<string,array{url:string,label:string,icon:string}>
	 */
	function mage_social_links() {
		$networks = array(
			'instagram' => array( 'Instagram', '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>' ),
			'linkedin'  => array( 'LinkedIn', '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 1 1 0-4.124 2.062 2.062 0 0 1 0 4.124zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>' ),
			'facebook'  => array( 'Facebook', '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.48 1.48-3.85 3.74-3.85 1.08 0 2.21.19 2.21.19v2.43h-1.24c-1.23 0-1.61.76-1.61 1.54V12h2.74l-.44 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg>' ),
			'youtube'   => array( 'YouTube', '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23 12s0-3.2-.4-4.7a2.5 2.5 0 0 0-1.7-1.7C19.3 5.2 12 5.2 12 5.2s-7.3 0-8.9.4A2.5 2.5 0 0 0 1.4 7.3C1 8.8 1 12 1 12s0 3.2.4 4.7a2.5 2.5 0 0 0 1.7 1.7c1.6.4 8.9.4 8.9.4s7.3 0 8.9-.4a2.5 2.5 0 0 0 1.7-1.7C23 15.2 23 12 23 12zM9.8 15.3V8.7l6 3.3-6 3.3z"/></svg>' ),
			'twitter'   => array( 'X', '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-7.5 8.6L23 22h-6.8l-5.3-6.93L4.8 22H1.7l8-9.2L1 2h7l4.77 6.3L18.9 2zm-1.2 18h1.7L7.1 3.8H5.3L17.7 20z"/></svg>' ),
			'tiktok'    => array( 'TikTok', '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.5 3c.32 2.02 1.53 3.63 3.5 3.9v2.62c-1.28 0-2.47-.4-3.5-1.08v5.7a5.62 5.62 0 1 1-5.62-5.62c.3 0 .6.03.88.08v2.7a2.92 2.92 0 1 0 2.05 2.79V3h2.69z"/></svg>' ),
		);

		$out = array();
		foreach ( $networks as $key => $info ) {
			$url = mage_contact( $key );
			if ( $url ) {
				$out[ $key ] = array( 'url' => $url, 'label' => $info[0], 'icon' => $info[1] );
			}
		}
		return $out;
	}
}

if ( ! function_exists( 'mage_contact_settings_page' ) ) {
	function mage_contact_settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Informações de Contato', 'mage' ); ?></h1>
			<p><?php esc_html_e( 'Esses dados alimentam a página de Contato, o rodapé e os dados estruturados do site.', 'mage' ); ?></p>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'mage_contact_group' );
				do_settings_sections( 'mage-contato' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
