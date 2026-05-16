<?php get_header(); ?>

<section class="archive-header">
	<div class="container">
		<span class="tag"><?php esc_html_e( 'Portfólio', 'mage' ); ?></span>
		<h1><?php esc_html_e( 'Nossos Projetos', 'mage' ); ?></h1>
		<p><?php esc_html_e( 'Conheça os projetos que desenvolvemos com dedicação e excelência.', 'mage' ); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="posts-grid">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="card-thumbnail">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'mage-card' ); ?></a>
						</div>
					<?php endif; ?>
					<div class="card-body">
						<h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<a href="<?php the_permalink(); ?>" class="card-link"><?php esc_html_e( 'Ver projeto', 'mage' ); ?></a>
					</div>
				</article>
			<?php endwhile; else : ?>
				<p><?php esc_html_e( 'Nenhum projeto cadastrado ainda.', 'mage' ); ?></p>
			<?php endif; ?>
		</div>
		<?php the_posts_pagination( array( 'class' => 'pagination' ) ); ?>
	</div>
</section>

<?php get_footer(); ?>
