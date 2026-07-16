<?php
/**
 * Admin meta boxes for the "servicos" landing-page fields.
 *
 * Exposes every section of the service landing page (hero, video,
 * benefits, testimonials toggle, "Sobre Nós" picker, FAQ and final CTA)
 * as proper fields in the editor, instead of the hidden custom-fields box.
 *
 * @package mage
 */

/**
 * Use the classic editor for the structured landing-page CPTs so their
 * meta-box fields are always visible when adding/editing an item — without
 * requiring the Classic Editor plugin. Posts and Pages keep the block
 * editor. REST stays enabled for these types.
 */
add_filter( 'use_block_editor_for_post_type', function ( $use_block, $post_type ) {
	if ( in_array( $post_type, array( 'servicos', 'sobre', 'depoimentos' ), true ) ) {
		return false;
	}
	return $use_block;
}, 10, 2 );

if ( ! function_exists( 'mage_lp_field' ) ) {
	/**
	 * Render a single labelled meta field.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $key     Meta key.
	 * @param string $label   Field label.
	 * @param string $type    text|textarea|url|checkbox.
	 * @param string $ph      Placeholder (or checkbox label).
	 */
	function mage_lp_field( $post_id, $key, $label, $type = 'text', $ph = '' ) {
		$val = get_post_meta( $post_id, $key, true );
		echo '<p style="margin:0 0 14px;">';
		if ( 'checkbox' === $type ) {
			echo '<label style="font-weight:600;"><input type="checkbox" name="' . esc_attr( $key ) . '" value="1" ' . checked( $val, '1', false ) . '> ' . esc_html( $ph ) . '</label>';
		} else {
			echo '<label for="' . esc_attr( $key ) . '" style="display:block;font-weight:600;margin-bottom:4px;">' . esc_html( $label ) . '</label>';
			if ( 'textarea' === $type ) {
				echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="3" class="widefat" placeholder="' . esc_attr( $ph ) . '">' . esc_textarea( $val ) . '</textarea>';
			} else {
				$input_type = ( 'url' === $type ) ? 'url' : 'text';
				echo '<input type="' . esc_attr( $input_type ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" class="widefat" value="' . esc_attr( $val ) . '" placeholder="' . esc_attr( $ph ) . '">';
			}
		}
		echo '</p>';
	}
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'mage_servico_lp', __( 'Landing page do serviço', 'mage' ), 'mage_servico_lp_box', 'servicos', 'normal', 'high' );
} );

if ( ! function_exists( 'mage_servico_lp_box' ) ) {
	function mage_servico_lp_box( $post ) {
		wp_nonce_field( 'mage_servicos_meta', 'mage_servicos_nonce' );
		$id = $post->ID;
		echo '<style>.mage-lp-admin h3{margin:20px 0 10px;padding-top:14px;border-top:1px solid #e0e0e0;font-size:12px;text-transform:uppercase;letter-spacing:.6px;color:#646970}.mage-lp-admin h3:first-child{border-top:0;padding-top:0}.mage-lp-cols{display:grid;grid-template-columns:1fr 1fr;gap:0 24px}@media(max-width:782px){.mage-lp-cols{grid-template-columns:1fr}}</style>';
		echo '<div class="mage-lp-admin">';

		echo '<h3>' . esc_html__( 'Topo (Hero)', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'lp_telefone', __( 'Telefone', 'mage' ), 'text', '(48) 9 9999-9999' );
		mage_lp_field( $id, 'lp_headline', __( 'Headline (título principal)', 'mage' ), 'text', get_the_title( $id ) );
		mage_lp_field( $id, 'lp_subtitle', __( 'Subtítulo', 'mage' ), 'textarea' );
		mage_lp_field( $id, 'lp_form_titulo', __( 'Título do formulário (Hero)', 'mage' ), 'text', __( 'Solicite seu orçamento', 'mage' ) );

		echo '<h3>' . esc_html__( 'Serviço (vídeo / descrição)', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'lp_servico_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Título sobre o serviço', 'mage' ) );
		mage_lp_field( $id, 'lp_video_url', __( 'URL do vídeo (YouTube/Vimeo)', 'mage' ), 'url', 'https://...' );
		echo '<p class="description">' . esc_html__( 'A descrição é o conteúdo principal do editor acima. Se não houver vídeo, é usada a imagem destacada.', 'mage' ) . '</p>';

		echo '<h3>' . esc_html__( 'Benefícios', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'lp_beneficios_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Com este serviço você:', 'mage' ) );
		echo '<div class="mage-lp-cols">';
		for ( $i = 1; $i <= 6; $i++ ) {
			echo '<div>';
			/* translators: %d: benefit number. */
			mage_lp_field( $id, "lp_beneficio_{$i}_titulo", sprintf( __( 'Benefício %d — título', 'mage' ), $i ), 'text' );
			/* translators: %d: benefit number. */
			mage_lp_field( $id, "lp_beneficio_{$i}_desc", sprintf( __( 'Benefício %d — descrição', 'mage' ), $i ), 'textarea' );
			echo '</div>';
		}
		echo '</div>';
		echo '<p class="description">' . esc_html__( 'Preencha apenas os que quiser exibir.', 'mage' ) . '</p>';

		echo '<h3>' . esc_html__( 'Depoimentos', 'mage' ) . '</h3>';
		$dep_val = get_post_meta( $id, 'lp_depoimentos_mostrar', true );
		echo '<p style="margin:0 0 14px;"><label style="font-weight:600;"><input type="checkbox" name="lp_depoimentos_mostrar" value="1" ' . checked( '0' !== $dep_val, true, false ) . '> ' . esc_html__( 'Exibir o carrossel de depoimentos nesta página', 'mage' ) . '</label></p>';
		mage_lp_field( $id, 'lp_depoimentos_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Quem conhece, recomenda.', 'mage' ) );
		echo '<p class="description">' . esc_html__( 'Os depoimentos são cadastrados no menu “Depoimentos”.', 'mage' ) . '</p>';

		echo '<h3>' . esc_html__( 'Quem Somos', 'mage' ) . '</h3>';
		$sobres = get_posts( array( 'post_type' => 'sobre', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
		$sel    = get_post_meta( $id, 'lp_sobre_id', true );
		echo '<p><label for="lp_sobre_id" style="display:block;font-weight:600;margin-bottom:4px;">' . esc_html__( 'Seção “Quem Somos” a exibir', 'mage' ) . '</label>';
		echo '<select id="lp_sobre_id" name="lp_sobre_id" class="widefat">';
		echo '<option value="0">' . esc_html__( '— Nenhuma —', 'mage' ) . '</option>';
		foreach ( $sobres as $s ) {
			echo '<option value="' . esc_attr( $s->ID ) . '" ' . selected( $sel, $s->ID, false ) . '>' . esc_html( get_the_title( $s ) ) . '</option>';
		}
		echo '</select></p>';
		echo '<p class="description">' . esc_html__( 'Crie e edite as seções no menu “Sobre Nós”.', 'mage' ) . '</p>';

		echo '<h3>' . esc_html__( 'Perguntas Frequentes (FAQ)', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'lp_faq_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Perguntas Frequentes', 'mage' ) );
		for ( $i = 1; $i <= 6; $i++ ) {
			/* translators: %d: FAQ number. */
			mage_lp_field( $id, "lp_faq_{$i}_pergunta", sprintf( __( 'Pergunta %d', 'mage' ), $i ), 'text' );
			/* translators: %d: FAQ number. */
			mage_lp_field( $id, "lp_faq_{$i}_resposta", sprintf( __( 'Resposta %d', 'mage' ), $i ), 'textarea' );
		}

		echo '<h3>' . esc_html__( 'Chamada final (CTA)', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'lp_final_titulo', __( 'Título', 'mage' ), 'text', __( 'Faça uma chamada final', 'mage' ) );
		mage_lp_field( $id, 'lp_final_texto', __( 'Texto', 'mage' ), 'textarea' );
		mage_lp_field( $id, 'lp_cta_label', __( 'Texto dos botões de CTA', 'mage' ), 'text', __( 'Chamada para ação', 'mage' ) );

		echo '</div>';
	}
}

add_action( 'save_post_servicos', function ( $post_id ) {
	if ( ! isset( $_POST['mage_servicos_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mage_servicos_nonce'] ) ), 'mage_servicos_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_keys = array( 'lp_telefone', 'lp_headline', 'lp_form_titulo', 'lp_servico_titulo', 'lp_beneficios_titulo', 'lp_depoimentos_titulo', 'lp_faq_titulo', 'lp_final_titulo', 'lp_cta_label' );
	$area_keys = array( 'lp_subtitle', 'lp_final_texto' );
	for ( $i = 1; $i <= 6; $i++ ) {
		$text_keys[] = "lp_beneficio_{$i}_titulo";
		$area_keys[] = "lp_beneficio_{$i}_desc";
		$text_keys[] = "lp_faq_{$i}_pergunta";
		$area_keys[] = "lp_faq_{$i}_resposta";
	}

	foreach ( $text_keys as $k ) {
		update_post_meta( $post_id, $k, isset( $_POST[ $k ] ) ? sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) : '' );
	}
	foreach ( $area_keys as $k ) {
		update_post_meta( $post_id, $k, isset( $_POST[ $k ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $k ] ) ) : '' );
	}
	update_post_meta( $post_id, 'lp_video_url', isset( $_POST['lp_video_url'] ) ? esc_url_raw( wp_unslash( $_POST['lp_video_url'] ) ) : '' );
	update_post_meta( $post_id, 'lp_sobre_id', isset( $_POST['lp_sobre_id'] ) ? absint( $_POST['lp_sobre_id'] ) : 0 );
	update_post_meta( $post_id, 'lp_depoimentos_mostrar', isset( $_POST['lp_depoimentos_mostrar'] ) ? '1' : '0' );
} );
