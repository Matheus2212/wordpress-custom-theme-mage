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
				<article <?php post_class( 'card projeto-card' ); ?>>
					<a href="<?php the_permalink(); ?>" class="projeto-card__tile" style="background:<?php echo esc_attr( mage_projeto_background( get_the_ID(), 'var(--color-primary)' ) ); ?>;">
						<?php
						$mage_icon = mage_projeto_icon( get_the_ID(), array( 128, 128 ) );
						if ( $mage_icon ) {
							echo $mage_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image output is safe.
						} elseif ( has_post_thumbnail() ) {
							the_post_thumbnail( 'mage-card', array( 'class' => 'projeto-card__cover' ) );
						}
						?>
					</a>
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
