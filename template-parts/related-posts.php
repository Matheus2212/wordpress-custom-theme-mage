<?php
/**
 * Related posts, matched by the current post's categories.
 *
 * @package mage
 */

$mage_current_id = get_the_ID();
$mage_cats       = wp_get_post_categories( $mage_current_id );

if ( empty( $mage_cats ) ) {
	return;
}

$mage_related = new WP_Query( array(
	'category__in'        => $mage_cats,
	'post__not_in'        => array( $mage_current_id ),
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'orderby'             => 'date',
	'order'               => 'DESC',
) );

if ( ! $mage_related->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="related-posts section-light" aria-label="<?php esc_attr_e( 'Posts relacionados', 'mage' ); ?>">
	<div class="container">
		<h2 class="section-title"><?php esc_html_e( 'Leia também', 'mage' ); ?></h2>
		<div class="posts-grid posts-grid--flush">
			<?php
			while ( $mage_related->have_posts() ) :
				$mage_related->the_post();
				get_template_part( 'template-parts/card', 'post' );
			endwhile;
			?>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
