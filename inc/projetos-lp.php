<?php
/**
 * Landing-page fields for the "projetos" CPT.
 *
 * A second meta box ("Landing page do projeto") that powers the rich single
 * project template (hero, video, benefits, plans, guarantee, FAQ, CTA). The
 * accent colours come from the project's own colours (see projetos-meta.php).
 *
 * @package mage
 */

if ( ! function_exists( 'mage_projeto_store_badges' ) ) {
	/**
	 * App-store buttons (Google Play / App Store) for a project, styled with
	 * the project's colour. Returns '' when no store link is set.
	 *
	 * @param int|null $post_id Project ID.
	 * @return string
	 */
	function mage_projeto_store_badges( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$gp = get_post_meta( $post_id, 'proj_google_play', true );
		$as = get_post_meta( $post_id, 'proj_app_store', true );
		if ( ! $gp && ! $as ) {
			return '';
		}

		$play_icon  = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.2 2.6a1 1 0 0 0-.7 1v16.8a1 1 0 0 0 1.5.87l14.4-8.4a1 1 0 0 0 0-1.74L5 2.73a1 1 0 0 0-.8-.13z"/></svg>';
		$apple_icon = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>';

		ob_start();
		echo '<div class="lp-store-badges">';
		if ( $gp ) {
			echo '<a class="lp-store" href="' . esc_url( $gp ) . '" target="_blank" rel="noopener noreferrer">' . $play_icon . '<span>Google Play</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		if ( $as ) {
			echo '<a class="lp-store" href="' . esc_url( $as ) . '" target="_blank" rel="noopener noreferrer">' . $apple_icon . '<span>App Store</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div>';
		return ob_get_clean();
	}
}

if ( ! function_exists( 'mage_projeto_color' ) ) {
	/**
	 * Solid accent colour for a project (colour 1), for buttons/icons.
	 *
	 * @param int|null $post_id  Project ID.
	 * @param string   $fallback Fallback CSS colour.
	 * @return string
	 */
	function mage_projeto_color( $post_id = null, $fallback = 'var(--color-primary)' ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$c1 = sanitize_hex_color( (string) get_post_meta( $post_id, 'projeto_cor_1', true ) );
		return $c1 ? $c1 : $fallback;
	}
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'mage_projeto_lp', __( 'Landing page do projeto', 'mage' ), 'mage_projeto_lp_box', 'projetos', 'normal', 'default' );
} );

if ( ! function_exists( 'mage_projeto_lp_box' ) ) {
	function mage_projeto_lp_box( $post ) {
		wp_nonce_field( 'mage_projeto_lp', 'mage_projeto_lp_nonce' );
		$id = $post->ID;
		echo '<div class="mage-lp-admin"><style>.mage-lp-admin h3{margin:20px 0 10px;padding-top:14px;border-top:1px solid #e0e0e0;font-size:12px;text-transform:uppercase;letter-spacing:.6px;color:#646970}.mage-lp-admin h3:first-child{border-top:0;padding-top:0}.mage-lp-cols{display:grid;grid-template-columns:1fr 1fr;gap:0 24px}@media(max-width:782px){.mage-lp-cols{grid-template-columns:1fr}}</style>';

		if ( function_exists( 'mage_resumo_field' ) ) {
			mage_resumo_field( $id );
		}

		echo '<h3>' . esc_html__( 'Topo (Hero)', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'proj_headline', __( 'Headline', 'mage' ), 'text', get_the_title( $id ) );
		mage_lp_field( $id, 'proj_bullets', __( 'Tópicos / diferenciais (um por linha)', 'mage' ), 'textarea' );
		mage_lp_field( $id, 'proj_cta_label', __( 'Texto dos botões (CTA)', 'mage' ), 'text', __( 'Quero contratar', 'mage' ) );
		mage_lp_field( $id, 'proj_cta_url', __( 'Link do CTA (vazio = rola até o formulário)', 'mage' ), 'url', '' );

		echo '<h3>' . esc_html__( 'Lojas de aplicativos', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'proj_google_play', __( 'Link da Google Play', 'mage' ), 'url', 'https://play.google.com/store/apps/details?id=...' );
		mage_lp_field( $id, 'proj_app_store', __( 'Link da App Store', 'mage' ), 'url', 'https://apps.apple.com/app/...' );
		echo '<p class="description">' . esc_html__( 'Se preenchidos, botões das lojas (com a cor do projeto) aparecem no topo no lugar do botão de CTA.', 'mage' ) . '</p>';

		echo '<h3>' . esc_html__( 'Vídeo / apresentação', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'proj_conheca_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Conheça a solução', 'mage' ) );
		mage_lp_field( $id, 'proj_video_url', __( 'URL do vídeo (YouTube/Vimeo)', 'mage' ), 'url', 'https://...' );
		echo '<p class="description">' . esc_html__( 'A descrição usa o conteúdo do editor acima. Sem vídeo, usa a imagem destacada.', 'mage' ) . '</p>';

		echo '<h3>' . esc_html__( 'Benefícios', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'proj_beneficios_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Com esta solução você:', 'mage' ) );
		echo '<div class="mage-lp-cols">';
		for ( $i = 1; $i <= 6; $i++ ) {
			echo '<div>';
			/* translators: %d: benefit number. */
			mage_lp_field( $id, "proj_beneficio_{$i}_titulo", sprintf( __( 'Benefício %d — título', 'mage' ), $i ), 'text' );
			/* translators: %d: benefit number. */
			mage_lp_field( $id, "proj_beneficio_{$i}_desc", sprintf( __( 'Benefício %d — descrição', 'mage' ), $i ), 'textarea' );
			echo '</div>';
		}
		echo '</div>';

		echo '<h3>' . esc_html__( 'Planos', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'proj_planos_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Planos disponíveis', 'mage' ) );
		echo '<div class="mage-lp-cols">';
		for ( $i = 1; $i <= 3; $i++ ) {
			echo '<div>';
			/* translators: %d: plan number. */
			mage_lp_field( $id, "proj_plano_{$i}_nome", sprintf( __( 'Plano %d — nome', 'mage' ), $i ), 'text' );
			/* translators: %d: plan number. */
			mage_lp_field( $id, "proj_plano_{$i}_preco", sprintf( __( 'Plano %d — preço', 'mage' ), $i ), 'text' );
			/* translators: %d: plan number. */
			mage_lp_field( $id, "proj_plano_{$i}_specs", sprintf( __( 'Plano %d — itens (um por linha)', 'mage' ), $i ), 'textarea' );
			echo '</div>';
		}
		echo '</div>';

		echo '<h3>' . esc_html__( 'Garantia', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'proj_garantia_dias', __( 'Número (ex.: 7, 30)', 'mage' ), 'text', '' );
		mage_lp_field( $id, 'proj_garantia_texto', __( 'Texto da garantia', 'mage' ), 'text', '' );

		echo '<h3>' . esc_html__( 'Depoimentos', 'mage' ) . '</h3>';
		$dep_val = get_post_meta( $id, 'proj_depoimentos_mostrar', true );
		echo '<p style="margin:0 0 14px;"><label style="font-weight:600;"><input type="checkbox" name="proj_depoimentos_mostrar" value="1" ' . checked( '0' !== $dep_val, true, false ) . '> ' . esc_html__( 'Exibir o carrossel de depoimentos', 'mage' ) . '</label></p>';
		mage_lp_field( $id, 'proj_depoimentos_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Quem conhece, recomenda.', 'mage' ) );

		echo '<h3>' . esc_html__( 'Perguntas Frequentes (FAQ)', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'proj_faq_titulo', __( 'Título da seção', 'mage' ), 'text', __( 'Perguntas Frequentes', 'mage' ) );
		for ( $i = 1; $i <= 6; $i++ ) {
			/* translators: %d: FAQ number. */
			mage_lp_field( $id, "proj_faq_{$i}_pergunta", sprintf( __( 'Pergunta %d', 'mage' ), $i ), 'text' );
			/* translators: %d: FAQ number. */
			mage_lp_field( $id, "proj_faq_{$i}_resposta", sprintf( __( 'Resposta %d', 'mage' ), $i ), 'textarea' );
		}

		echo '<h3>' . esc_html__( 'Chamada final (CTA)', 'mage' ) . '</h3>';
		mage_lp_field( $id, 'proj_final_titulo', __( 'Título', 'mage' ), 'text', __( 'Ficou interessado?', 'mage' ) );
		mage_lp_field( $id, 'proj_final_texto', __( 'Texto', 'mage' ), 'textarea' );

		echo '</div>';
	}
}

add_action( 'save_post_projetos', function ( $post_id ) {
	if ( ! isset( $_POST['mage_projeto_lp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mage_projeto_lp_nonce'] ) ), 'mage_projeto_lp' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text = array( 'proj_headline', 'proj_cta_label', 'proj_conheca_titulo', 'proj_beneficios_titulo', 'proj_planos_titulo', 'proj_garantia_dias', 'proj_garantia_texto', 'proj_depoimentos_titulo', 'proj_faq_titulo', 'proj_final_titulo' );
	$area = array( 'proj_bullets', 'proj_final_texto' );
	$url  = array( 'proj_cta_url', 'proj_video_url', 'proj_google_play', 'proj_app_store' );

	for ( $i = 1; $i <= 6; $i++ ) {
		$text[] = "proj_beneficio_{$i}_titulo";
		$area[] = "proj_beneficio_{$i}_desc";
		$text[] = "proj_faq_{$i}_pergunta";
		$area[] = "proj_faq_{$i}_resposta";
	}
	for ( $i = 1; $i <= 3; $i++ ) {
		$text[] = "proj_plano_{$i}_nome";
		$text[] = "proj_plano_{$i}_preco";
		$area[] = "proj_plano_{$i}_specs";
	}

	foreach ( $text as $k ) {
		update_post_meta( $post_id, $k, isset( $_POST[ $k ] ) ? sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) : '' );
	}
	foreach ( $area as $k ) {
		update_post_meta( $post_id, $k, isset( $_POST[ $k ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $k ] ) ) : '' );
	}
	foreach ( $url as $k ) {
		update_post_meta( $post_id, $k, isset( $_POST[ $k ] ) ? esc_url_raw( wp_unslash( $_POST[ $k ] ) ) : '' );
	}
	update_post_meta( $post_id, 'proj_depoimentos_mostrar', isset( $_POST['proj_depoimentos_mostrar'] ) ? '1' : '0' );

	if ( isset( $_POST['mage_resumo'] ) && function_exists( 'mage_update_excerpt' ) ) {
		mage_update_excerpt( $post_id, sanitize_textarea_field( wp_unslash( $_POST['mage_resumo'] ) ) );
	}
} );
