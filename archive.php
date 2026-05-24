<?php get_header(); ?>

<section class="archive-header">
	<div class="container">
		<?php if ( is_category() ) : ?>
			<span class="tag"><?php esc_html_e( 'Categoria', 'mage' ); ?></span>
			<h1><?php single_cat_title(); ?></h1>
			<?php if ( category_description() ) : ?>
				<p><?php echo wp_kses_post( category_description() ); ?></p>
			<?php endif; ?>
		<?php elseif ( is_tag() ) : ?>
			<span class="tag"><?php esc_html_e( 'Tag', 'mage' ); ?></span>
			<h1><?php single_tag_title(); ?></h1>
		<?php elseif ( is_author() ) : ?>
			<span class="tag"><?php esc_html_e( 'Autor', 'mage' ); ?></span>
			<h1><?php the_author(); ?></h1>
		<?php elseif ( is_date() ) : ?>
			<span class="tag"><?php esc_html_e( 'Arquivo', 'mage' ); ?></span>
			<h1><?php echo esc_html( get_the_date( 'F Y' ) ); ?></h1>
		<?php else : ?>
			<h1><?php the_archive_title(); ?></h1>
		<?php endif; ?>
	</div>
</section>

<div class="container">
	<div class="posts-grid">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/card', 'post' ); ?>
		<?php endwhile; else : ?>
			<p><?php esc_html_e( 'Nenhum post encontrado.', 'mage' ); ?></p>
		<?php endif; ?>
	</div>
	<?php the_posts_pagination( array( 'class' => 'pagination' ) ); ?>
</div>

<?php get_footer(); ?>
