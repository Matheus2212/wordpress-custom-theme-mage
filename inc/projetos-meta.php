<?php
/**
 * Meta box for the "projetos" CPT: highlight colours, icon and details.
 *
 * One colour renders as a solid highlight; two colours render as a gradient
 * in the project templates. Fields are always visible on add/edit (the CPT
 * uses the classic editor).
 *
 * @package mage
 */

// ── Admin assets (colour picker + media uploader) on the projetos screen ──────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	global $post_type;
	if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && 'projetos' === $post_type ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		$path = get_template_directory() . '/js/admin-projetos.js';
		wp_enqueue_script(
			'mage-admin-projetos',
			get_template_directory_uri() . '/js/admin-projetos.js',
			array( 'jquery', 'wp-color-picker' ),
			file_exists( $path ) ? filemtime( $path ) : false,
			true
		);
		wp_localize_script( 'mage-admin-projetos', 'mageAdmin', array(
			'choose' => __( 'Escolher ícone', 'mage' ),
			'use'    => __( 'Usar este ícone', 'mage' ),
		) );
	}
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'mage_projeto', __( 'Detalhes do projeto', 'mage' ), 'mage_projeto_box', 'projetos', 'normal', 'high' );
} );

if ( ! function_exists( 'mage_projeto_box' ) ) {
	function mage_projeto_box( $post ) {
		wp_nonce_field( 'mage_projeto_meta', 'mage_projeto_nonce' );
		$id      = $post->ID;
		$cor1    = get_post_meta( $id, 'projeto_cor_1', true );
		$cor2    = get_post_meta( $id, 'projeto_cor_2', true );
		$icone   = (int) get_post_meta( $id, 'projeto_icone', true );
		$cliente = get_post_meta( $id, 'cliente', true );
		$tec     = get_post_meta( $id, 'tecnologias', true );
		$url     = get_post_meta( $id, 'url_projeto', true );
		?>
		<style>.mage-proj p{margin:0 0 16px}.mage-proj label{font-weight:600;display:block;margin-bottom:5px}</style>
		<div class="mage-proj">
			<p>
				<label for="projeto_cor_1"><?php esc_html_e( 'Cor de destaque 1', 'mage' ); ?></label>
				<input type="text" id="projeto_cor_1" class="mage-color" name="projeto_cor_1" value="<?php echo esc_attr( $cor1 ); ?>" data-default-color="#810AD2">
			</p>
			<p>
				<label for="projeto_cor_2"><?php esc_html_e( 'Cor de destaque 2 (opcional — gera um gradiente)', 'mage' ); ?></label>
				<input type="text" id="projeto_cor_2" class="mage-color" name="projeto_cor_2" value="<?php echo esc_attr( $cor2 ); ?>">
			</p>
			<p>
				<label><?php esc_html_e( 'Ícone do projeto', 'mage' ); ?></label>
				<span class="mage-icon-preview" style="display:inline-block;vertical-align:middle;margin-right:10px;">
					<?php if ( $icone ) { echo wp_get_attachment_image( $icone, array( 64, 64 ) ); } ?>
				</span>
				<input type="hidden" class="mage-icon-id" name="projeto_icone" value="<?php echo esc_attr( $icone ); ?>">
				<button type="button" class="button mage-icon-choose"><?php esc_html_e( 'Escolher ícone', 'mage' ); ?></button>
				<button type="button" class="button mage-icon-remove"<?php echo $icone ? '' : ' style="display:none;"'; ?>><?php esc_html_e( 'Remover', 'mage' ); ?></button>
				<span class="description" style="display:block;margin-top:6px;"><?php esc_html_e( 'Imagem pequena (logo/ícone) usada na listagem. A imagem destacada continua sendo a capa do projeto.', 'mage' ); ?></span>
			</p>
			<hr>
			<p><label for="cliente"><?php esc_html_e( 'Cliente', 'mage' ); ?></label><input type="text" id="cliente" class="widefat" name="cliente" value="<?php echo esc_attr( $cliente ); ?>"></p>
			<p><label for="tecnologias"><?php esc_html_e( 'Tecnologias', 'mage' ); ?></label><input type="text" id="tecnologias" class="widefat" name="tecnologias" value="<?php echo esc_attr( $tec ); ?>" placeholder="<?php esc_attr_e( 'Ex.: WordPress, PHP, React', 'mage' ); ?>"></p>
			<p><label for="url_projeto"><?php esc_html_e( 'URL do projeto', 'mage' ); ?></label><input type="url" id="url_projeto" class="widefat" name="url_projeto" value="<?php echo esc_attr( $url ); ?>" placeholder="https://..."></p>
		</div>
		<?php
	}
}

add_action( 'save_post_projetos', function ( $post_id ) {
	if ( ! isset( $_POST['mage_projeto_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mage_projeto_nonce'] ) ), 'mage_projeto_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, 'projeto_cor_1', isset( $_POST['projeto_cor_1'] ) ? (string) sanitize_hex_color( wp_unslash( $_POST['projeto_cor_1'] ) ) : '' );
	update_post_meta( $post_id, 'projeto_cor_2', isset( $_POST['projeto_cor_2'] ) ? (string) sanitize_hex_color( wp_unslash( $_POST['projeto_cor_2'] ) ) : '' );
	update_post_meta( $post_id, 'projeto_icone', isset( $_POST['projeto_icone'] ) ? absint( $_POST['projeto_icone'] ) : 0 );
	update_post_meta( $post_id, 'cliente', isset( $_POST['cliente'] ) ? sanitize_text_field( wp_unslash( $_POST['cliente'] ) ) : '' );
	update_post_meta( $post_id, 'tecnologias', isset( $_POST['tecnologias'] ) ? sanitize_text_field( wp_unslash( $_POST['tecnologias'] ) ) : '' );
	update_post_meta( $post_id, 'url_projeto', isset( $_POST['url_projeto'] ) ? esc_url_raw( wp_unslash( $_POST['url_projeto'] ) ) : '' );
} );

// ── Template helpers ──────────────────────────────────────────────────────────
if ( ! function_exists( 'mage_projeto_background' ) ) {
	/**
	 * CSS background value for a project: gradient if two colours, solid if one.
	 *
	 * @param int|null $post_id  Project ID.
	 * @param string   $fallback Value when no colour is set.
	 * @return string
	 */
	function mage_projeto_background( $post_id = null, $fallback = '' ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$c1 = sanitize_hex_color( (string) get_post_meta( $post_id, 'projeto_cor_1', true ) );
		$c2 = sanitize_hex_color( (string) get_post_meta( $post_id, 'projeto_cor_2', true ) );
		if ( $c1 && $c2 ) {
			return 'linear-gradient(135deg, ' . $c1 . ', ' . $c2 . ')';
		}
		if ( $c1 ) {
			return $c1;
		}
		return $fallback;
	}
}

if ( ! function_exists( 'mage_projeto_icon' ) ) {
	/**
	 * Icon <img> markup for a project (empty string if none).
	 *
	 * @param int|null     $post_id Project ID.
	 * @param string|array $size    Image size.
	 * @return string
	 */
	function mage_projeto_icon( $post_id = null, $size = 'thumbnail' ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$icone   = (int) get_post_meta( $post_id, 'projeto_icone', true );
		if ( $icone ) {
			return wp_get_attachment_image( $icone, $size, false, array(
				'class' => 'projeto-icone',
				'alt'   => esc_attr( get_the_title( $post_id ) ),
			) );
		}
		return '';
	}
}
