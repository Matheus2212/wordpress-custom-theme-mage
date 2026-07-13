<?php
/**
 * Testimonials ("Depoimentos") custom post type + reusable carousel.
 *
 * The CPT is admin-only: it has no public single page, is excluded from
 * search and is not publicly queryable (so it isn't indexable and has no
 * URL of its own). Content is surfaced through a carousel that can be
 * dropped anywhere via mage_testimonials_carousel() or the [depoimentos]
 * shortcode.
 *
 * @package mage
 */

// ── Register the CPT ──────────────────────────────────────────────────────────
add_action( 'init', function () {
	register_post_type( 'depoimentos', array(
		'labels' => array(
			'name'          => __( 'Depoimentos', 'mage' ),
			'singular_name' => __( 'Depoimento', 'mage' ),
			'add_new_item'  => __( 'Adicionar Depoimento', 'mage' ),
			'edit_item'     => __( 'Editar Depoimento', 'mage' ),
			'all_items'     => __( 'Todos os Depoimentos', 'mage' ),
			'menu_name'     => __( 'Depoimentos', 'mage' ),
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
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'menu_icon'           => 'dashicons-format-quote',
		'menu_position'       => 7,
	) );
} );

// ── Meta box for the author's role/subtitle ───────────────────────────────────
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'mage_depoimento_meta', __( 'Detalhes do depoimento', 'mage' ), 'mage_depoimento_meta_box', 'depoimentos', 'normal', 'high' );
} );

if ( ! function_exists( 'mage_depoimento_meta_box' ) ) {
	function mage_depoimento_meta_box( $post ) {
		wp_nonce_field( 'mage_depoimento_meta', 'mage_depoimento_nonce' );
		$cargo = get_post_meta( $post->ID, 'depoimento_cargo', true );
		echo '<p><label for="depoimento_cargo"><strong>' . esc_html__( 'Cargo / Subtítulo', 'mage' ) . '</strong></label></p>';
		echo '<input type="text" id="depoimento_cargo" name="depoimento_cargo" class="widefat" value="' . esc_attr( $cargo ) . '" placeholder="' . esc_attr__( 'Ex.: SEO Specialist | LinkedIn Top Voice', 'mage' ) . '">';
		echo '<p class="description">' . esc_html__( 'O título é o nome da pessoa; o conteúdo é o texto do depoimento; a imagem destacada é a foto.', 'mage' ) . '</p>';
	}
}

add_action( 'save_post_depoimentos', function ( $post_id ) {
	if ( ! isset( $_POST['mage_depoimento_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mage_depoimento_nonce'] ) ), 'mage_depoimento_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$cargo = isset( $_POST['depoimento_cargo'] ) ? sanitize_text_field( wp_unslash( $_POST['depoimento_cargo'] ) ) : '';
	update_post_meta( $post_id, 'depoimento_cargo', $cargo );
} );

// ── Carousel renderer ─────────────────────────────────────────────────────────
if ( ! function_exists( 'mage_testimonials_carousel' ) ) {
	/**
	 * Render the testimonials carousel.
	 *
	 * @param array $args 'title', 'limit', 'theme' (light|dark).
	 * @return string Markup (empty if there are no testimonials).
	 */
	function mage_testimonials_carousel( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'title' => __( 'Depoimentos', 'mage' ),
			'limit' => 10,
			'theme' => 'light',
		) );

		$query = new WP_Query( array(
			'post_type'           => 'depoimentos',
			'posts_per_page'      => (int) $args['limit'],
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
			'orderby'             => 'menu_order date',
			'order'               => 'ASC',
		) );

		if ( ! $query->have_posts() ) {
			wp_reset_postdata();
			return '';
		}

		$dark = ( 'dark' === $args['theme'] ) ? ' mage-testimonials--dark' : '';

		ob_start();
		?>
		<section class="mage-testimonials<?php echo esc_attr( $dark ); ?>" aria-label="<?php echo esc_attr( $args['title'] ? $args['title'] : __( 'Depoimentos', 'mage' ) ); ?>">
			<div class="container">
				<?php if ( $args['title'] ) : ?>
					<h2 class="section-title lp-center"><?php echo esc_html( $args['title'] ); ?></h2>
				<?php endif; ?>
				<div class="mage-carousel" data-mage-carousel>
					<button type="button" class="mage-carousel__nav mage-carousel__prev" aria-label="<?php esc_attr_e( 'Depoimento anterior', 'mage' ); ?>">&#8249;</button>
					<div class="mage-carousel__viewport">
						<div class="mage-carousel__track">
							<?php
							while ( $query->have_posts() ) :
								$query->the_post();
								$cargo = get_post_meta( get_the_ID(), 'depoimento_cargo', true );
								?>
								<article class="mage-testimonial">
									<blockquote class="mage-testimonial__quote"><?php echo wp_kses_post( apply_filters( 'the_content', get_the_content() ) ); ?></blockquote>
									<div class="mage-testimonial__author">
										<?php if ( has_post_thumbnail() ) : ?>
											<span class="mage-testimonial__avatar"><?php the_post_thumbnail( 'thumbnail' ); ?></span>
										<?php endif; ?>
										<span class="mage-testimonial__meta">
											<strong><?php the_title(); ?></strong>
											<?php if ( $cargo ) : ?>
												<span class="mage-testimonial__role"><?php echo esc_html( $cargo ); ?></span>
											<?php endif; ?>
										</span>
									</div>
								</article>
							<?php endwhile; ?>
						</div>
					</div>
					<button type="button" class="mage-carousel__nav mage-carousel__next" aria-label="<?php esc_attr_e( 'Próximo depoimento', 'mage' ); ?>">&#8250;</button>
				</div>
			</div>
		</section>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}

// ── Shortcode: [depoimentos title="Depoimentos" limit="10" theme="dark"] ───────
add_shortcode( 'depoimentos', function ( $atts ) {
	$atts = shortcode_atts( array(
		'title' => __( 'Depoimentos', 'mage' ),
		'limit' => 10,
		'theme' => 'light',
	), $atts, 'depoimentos' );

	return mage_testimonials_carousel( $atts );
} );
