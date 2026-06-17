<?php get_header(); ?>

<section class="archive-header">
	<div class="container">
		<?php mage_breadcrumbs(); ?>
		<?php if ( is_category() ) : ?>
			<span class="tag"><?php esc_html_e( 'Categoria', 'mage' ); ?></span>
			<h1><?php single_cat_title(); ?></h1>
			<?php if ( category_description() ) : ?>
				<p><?php echo wp_kses_post( category_description() ); ?></p>
			<?php endif; ?>
		<?php elseif ( is_tag() ) : ?>
			<span class="tag"><?php esc_html_e( 'Tag', 'mage' ); ?></span>
			<h1><?php single_tag_title(); ?></h1>
			<?php if ( tag_description() ) : ?>
				<p><?php echo wp_kses_post( tag_description() ); ?></p>
			<?php endif; ?>
		<?php elseif ( is_author() ) : ?>
			<span class="tag"><?php esc_html_e( 'Autor', 'mage' ); ?></span>
			<h1><?php echo esc_html( get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) ) ); ?></h1>
			<?php if ( get_the_author_meta( 'description', (int) get_query_var( 'author' ) ) ) : ?>
				<p><?php echo esc_html( get_the_author_meta( 'description', (int) get_query_var( 'author' ) ) ); ?></p>
			<?php endif; ?>
		<?php elseif ( is_date() ) : ?>
			<span class="tag"><?php esc_html_e( 'Arquivo', 'mage' ); ?></span>
			<h1><?php echo esc_html( get_the_date( 'F Y' ) ); ?></h1>
		<?php else : ?>
			<h1><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<p>', '</p>' ); ?>
		<?php endif; ?>
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
				<p class="no-results"><?php esc_html_e( 'Nenhum post encontrado nesta seção.', 'mage' ); ?></p>
			<?php endif; ?>
		</main>
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>
