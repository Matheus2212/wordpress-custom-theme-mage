<?php get_header(); ?>

<section class="archive-header">
	<div class="container">
		<?php mage_breadcrumbs(); ?>
		<span class="tag"><?php esc_html_e( 'Busca', 'mage' ); ?></span>
		<h1><?php printf( esc_html__( 'Resultados para: %s', 'mage' ), '<em>' . esc_html( get_search_query() ) . '</em>' ); ?></h1>
		<?php if ( have_posts() ) : ?>
			<p>
				<?php
				global $wp_query;
				printf(
					esc_html( _n( '%s resultado encontrado.', '%s resultados encontrados.', (int) $wp_query->found_posts, 'mage' ) ),
					esc_html( number_format_i18n( (int) $wp_query->found_posts ) )
				);
				?>
			</p>
		<?php endif; ?>
		<div class="search-form-top"><?php get_search_form(); ?></div>
	</div>
</section>

<div class="container">
	<div class="blog-wrap<?php echo is_active_sidebar( 'sidebar-blog' ) ? ' has-sidebar' : ''; ?>">
		<main class="blog-main">
			<?php if ( have_posts() ) : ?>
				<div class="posts-grid posts-grid--flush">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/card', 'post' ); ?>
					<?php endwhile; ?>
				</div>
				<?php the_posts_pagination( array( 'class' => 'pagination', 'mid_size' => 1 ) ); ?>
			<?php else : ?>
				<div class="no-results">
					<p><?php esc_html_e( 'Nenhum resultado encontrado. Tente outras palavras-chave.', 'mage' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</main>
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>
