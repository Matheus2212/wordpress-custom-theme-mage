<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="single-hero">
		<div class="container">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="single-content">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="single-thumbnail"><?php the_post_thumbnail( 'mage-hero' ); ?></div>
		<?php endif; ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</div>

</article>

<?php
if ( comments_open() || get_comments_number() ) :
	comments_template();
endif;
?>

<?php endwhile; ?>

<?php get_footer(); ?>
