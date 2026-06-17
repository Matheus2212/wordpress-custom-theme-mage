<?php
// Minimal customizer support — color/logo handled via WordPress core.
add_action( 'customize_register', function( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
} );

// ── Hero emblem ───────────────────────────────────────────────────────────────
// Lets the user choose what sits in the middle of the animated hero emblem:
// the wizard-hat mark (default) or the full site logo (which includes the text).
add_action( 'customize_register', function( $wp_customize ) {
	$wp_customize->add_section( 'mage_emblem', array(
		'title'       => __( 'Emblema do Hero', 'mage' ),
		'description' => __( 'Personalize o emblema animado exibido na página inicial.', 'mage' ),
		'priority'    => 80,
	) );

	$wp_customize->add_setting( 'mage_emblem_center', array(
		'default'           => 'hat',
		'sanitize_callback' => 'mage_sanitize_emblem_center',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'mage_emblem_center', array(
		'label'       => __( 'Imagem no centro do emblema', 'mage' ),
		'description' => __( 'O logo do site precisa estar definido em "Identidade do Site" para usar essa opção.', 'mage' ),
		'section'     => 'mage_emblem',
		'type'        => 'radio',
		'choices'     => array(
			'hat'  => __( 'Chapéu de mago (padrão)', 'mage' ),
			'logo' => __( 'Logo do site (com texto)', 'mage' ),
		),
	) );
} );

if ( ! function_exists( 'mage_sanitize_emblem_center' ) ) {
	function mage_sanitize_emblem_center( $value ) {
		return in_array( $value, array( 'hat', 'logo' ), true ) ? $value : 'hat';
	}
}
