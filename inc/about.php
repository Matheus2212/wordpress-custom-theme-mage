<?php
/**
 * "Sobre Nós" ("sobre") custom post type + reusable section.
 *
 * Like the testimonials CPT, this is admin-only: no public single page,
 * not publicly queryable, excluded from search and not in REST/sitemaps.
 * A "Sobre" entry is embedded into other content (e.g. a service landing
 * page) via mage_about_section() or the [sobre] shortcode.
 *
 * @package mage
 */

// ── Register the CPT ──────────────────────────────────────────────────────────
add_action( 'init', function () {
	register_post_type( 'sobre', array(
		'labels' => array(
			'name'          => __( 'Sobre Nós', 'mage' ),
			'singular_name' => __( 'Seção Sobre', 'mage' ),
			'add_new_item'  => __( 'Adicionar Seção Sobre', 'mage' ),
			'edit_item'     => __( 'Editar Seção Sobre', 'mage' ),
			'all_items'     => __( 'Todas as Seções', 'mage' ),
			'menu_name'     => __( 'Sobre Nós', 'mage' ),
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
		'menu_icon'           => 'dashicons-groups',
		'menu_position'       => 8,
	) );
} );

// ── Section renderer ──────────────────────────────────────────────────────────
if ( ! function_exists( 'mage_about_section' ) ) {
	/**
	 * Render a "Sobre Nós" section.
	 *
	 * @param int   $id   Specific "sobre" post ID. 0 = use the most recent.
	 * @param array $args 'class' for the wrapping section.
	 * @return string Markup (empty if nothing to show).
	 */
	function mage_about_section( $id = 0, $args = array() ) {
		$args = wp_parse_args( $args, array( 'class' => 'section section-light' ) );

		if ( $id ) {
			$post = get_post( $id );
			if ( ! $post || 'sobre' !== $post->post_type || 'publish' !== $post->post_status ) {
				return '';
			}
		} else {
			$found = get_posts( array(
				'post_type'      => 'sobre',
				'posts_per_page' => 1,
				'orderby'        => 'menu_order date',
				'order'          => 'ASC',
			) );
			if ( empty( $found ) ) {
				return '';
			}
			$post = $found[0];
		}

		$title   = get_the_title( $post );
		$content = apply_filters( 'the_content', $post->post_content );
		$image   = get_the_post_thumbnail( $post->ID, 'large', array( 'alt' => esc_attr( $title ) ) );

		ob_start();
		?>
		<section class="lp-about <?php echo esc_attr( $args['class'] ); ?>">
			<div class="container lp-split">
				<div class="lp-media">
					<?php
					if ( $image ) {
						echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						echo '<span class="lp-media__pic" aria-hidden="true"></span>';
					}
					?>
				</div>
				<div class="lp-split__text">
					<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
					<div class="lp-richtext"><?php echo wp_kses_post( $content ); ?></div>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}

// ── Shortcode: [sobre id="123"] ───────────────────────────────────────────────
add_shortcode( 'sobre', function ( $atts ) {
	$atts = shortcode_atts( array( 'id' => 0 ), $atts, 'sobre' );
	return mage_about_section( (int) $atts['id'] );
} );
