<?php get_header(); ?>

<section class="archive-header">
	<div class="container">
		<span class="tag"><?php esc_html_e( 'Busca', 'mage' ); ?></span>
		<h1><?php printf( esc_html__( 'Resultados para: %s', 'mage' ), '<em>' . esc_html( get_search_query() ) . '</em>' ); ?></h1>
		<div class="search-form-wrap">
			<?php get_search_form(); ?>
		</div>
	</div>
</section>

<div class="container">
	<div class="posts-grid">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/card', 'post' ); ?>
		<?php endwhile; else : ?>
			<p><?php esc_html_e( 'Nenhum resultado encontrado. Tente outra busca.', 'mage' ); ?></p>
		<?php endif; ?>
	</div>
	<?php the_posts_pagination( array( 'class' => 'pagination' ) ); ?>
</div>

<?php get_footer(); ?>
