<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="single-hero">
		<div class="container">
			<?php
			$cats = get_the_category();
			if ( $cats ) :
				echo '<span class="tag">' . esc_html( $cats[0]->name ) . '</span>';
			endif;
			?>
			<h1><?php the_title(); ?></h1>
			<div class="post-meta">
				<span><?php echo esc_html( get_the_date() ); ?></span>
				<span><?php the_author(); ?></span>
				<?php if ( has_tag() ) : ?>
					<span><?php the_tags( '', ', ' ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="single-content">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="single-thumbnail"><?php the_post_thumbnail( 'mage-hero' ); ?></div>
		<?php endif; ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Páginas:', 'mage' ),
			'after'  => '</div>',
		) );
		?>
	</div>

</article>

<?php
if ( comments_open() || get_comments_number() ) :
	comments_template();
endif;
?>

<?php endwhile; ?>

<?php get_footer(); ?>
