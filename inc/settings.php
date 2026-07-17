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
		);
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
