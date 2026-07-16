<?php
/**
 * Stores contact/lead form submissions in the database.
 *
 * Submissions are saved as a private, admin-only "mage_lead" post type.
 * Input is sanitized (tags stripped) before storage, persisted through the
 * WordPress data API (which uses prepared statements — safe against SQL
 * injection), and escaped on output in the admin (safe against stored XSS).
 *
 * @package mage
 */

// ── Register the storage CPT ──────────────────────────────────────────────────
add_action( 'init', function () {
	register_post_type( 'mage_lead', array(
		'labels' => array(
			'name'          => __( 'Contatos', 'mage' ),
			'singular_name' => __( 'Contato', 'mage' ),
			'menu_name'     => __( 'Contatos', 'mage' ),
			'all_items'     => __( 'Todos os Contatos', 'mage' ),
			'edit_item'     => __( 'Ver Contato', 'mage' ),
			'search_items'  => __( 'Buscar Contatos', 'mage' ),
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => false,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'has_archive'         => false,
		'rewrite'             => false,
		'query_var'           => false,
		'supports'            => array( 'title' ),
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'capabilities'        => array( 'create_posts' => 'do_not_allow' ), // received via the form only
		'menu_icon'           => 'dashicons-email-alt',
		'menu_position'       => 26,
	) );
} );

// ── Store one submission ──────────────────────────────────────────────────────
if ( ! function_exists( 'mage_store_lead' ) ) {
	/**
	 * Persist a (raw) submission. All values are sanitized here.
	 *
	 * @param array $data nome, email, telefone, mensagem, origem.
	 * @return int Post ID (0 on failure).
	 */
	function mage_store_lead( $data ) {
		$nome     = isset( $data['nome'] ) ? sanitize_text_field( $data['nome'] ) : '';
		$email    = isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '';
		$telefone = isset( $data['telefone'] ) ? sanitize_text_field( $data['telefone'] ) : '';
		$mensagem = isset( $data['mensagem'] ) ? sanitize_textarea_field( $data['mensagem'] ) : '';
		$origem   = isset( $data['origem'] ) ? sanitize_text_field( $data['origem'] ) : '';

		$title = ( '' !== $nome ) ? $nome : __( '(sem nome)', 'mage' );
		if ( '' !== $origem ) {
			$title .= ' — ' . $origem;
		}

		$post_id = wp_insert_post( array(
			'post_type'    => 'mage_lead',
			'post_status'  => 'publish',
			'post_title'   => wp_strip_all_tags( $title ),
			'post_content' => $mensagem,
		), true );

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return 0;
		}

		update_post_meta( $post_id, '_lead_nome', $nome );
		update_post_meta( $post_id, '_lead_email', $email );
		update_post_meta( $post_id, '_lead_telefone', $telefone );
		update_post_meta( $post_id, '_lead_origem', $origem );

		return $post_id;
	}
}

// ── Admin list columns ────────────────────────────────────────────────────────
add_filter( 'manage_mage_lead_posts_columns', function ( $cols ) {
	$new = array();
	if ( isset( $cols['cb'] ) ) {
		$new['cb'] = $cols['cb'];
	}
	$new['title']         = __( 'Nome', 'mage' );
	$new['lead_email']    = __( 'E-mail', 'mage' );
	$new['lead_telefone'] = __( 'Telefone', 'mage' );
	$new['lead_origem']   = __( 'Origem', 'mage' );
	$new['date']          = __( 'Recebido em', 'mage' );
	return $new;
} );

add_action( 'manage_mage_lead_posts_custom_column', function ( $col, $post_id ) {
	if ( 'lead_email' === $col ) {
		$email = get_post_meta( $post_id, '_lead_email', true );
		echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '—';
	} elseif ( 'lead_telefone' === $col ) {
		$tel = get_post_meta( $post_id, '_lead_telefone', true );
		echo esc_html( $tel ? $tel : '—' );
	} elseif ( 'lead_origem' === $col ) {
		$origem = get_post_meta( $post_id, '_lead_origem', true );
		echo esc_html( $origem ? $origem : '—' );
	}
}, 10, 2 );

// ── Read-only details on the edit screen ──────────────────────────────────────
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'mage_lead_details', __( 'Detalhes do contato', 'mage' ), function ( $post ) {
		$rows = array(
			__( 'Nome', 'mage' )     => get_post_meta( $post->ID, '_lead_nome', true ),
			__( 'E-mail', 'mage' )   => get_post_meta( $post->ID, '_lead_email', true ),
			__( 'Telefone', 'mage' ) => get_post_meta( $post->ID, '_lead_telefone', true ),
			__( 'Origem', 'mage' )   => get_post_meta( $post->ID, '_lead_origem', true ),
		);
		echo '<table class="widefat striped"><tbody>';
		foreach ( $rows as $label => $value ) {
			echo '<tr><th style="width:150px;text-align:left;">' . esc_html( $label ) . '</th><td>' . esc_html( $value ? $value : '—' ) . '</td></tr>';
		}
		echo '<tr><th style="text-align:left;vertical-align:top;">' . esc_html__( 'Mensagem', 'mage' ) . '</th><td>' . nl2br( esc_html( $post->post_content ) ) . '</td></tr>';
		echo '</tbody></table>';
	}, 'mage_lead', 'normal', 'high' );
} );
