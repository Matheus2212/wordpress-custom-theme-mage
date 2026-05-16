<?php get_header(); ?>

<section class="archive-header">
	<div class="container">
		<span class="tag"><?php esc_html_e( 'O que fazemos', 'mage' ); ?></span>
		<h1><?php esc_html_e( 'Nossos Serviços', 'mage' ); ?></h1>
		<p><?php esc_html_e( 'Soluções digitais completas para impulsionar o seu negócio.', 'mage' ); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="grid-3">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article class="service-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="card-thumbnail" style="border-radius:8px;margin-bottom:20px;aspect-ratio:16/9;overflow:hidden;">
							<?php the_post_thumbnail( 'mage-card', array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?>
						</div>
					<?php else : ?>
						<div class="service-icon">&#x2728;</div>
					<?php endif; ?>
					<h2 style="font-size:1.15rem;margin-bottom:10px;"><?php the_title(); ?></h2>
					<p><?php echo esc_html( get_the_excerpt() ); ?></p>
					<a href="<?php the_permalink(); ?>" class="card-link"><?php esc_html_e( 'Saiba mais', 'mage' ); ?></a>
				</article>
			<?php endwhile; else : ?>
				<p><?php esc_html_e( 'Nenhum serviço cadastrado ainda.', 'mage' ); ?></p>
			<?php endif; ?>
		</div>
		<?php the_posts_pagination( array( 'class' => 'pagination' ) ); ?>
	</div>
</section>

<section class="cta-strip">
	<div class="container">
		<h2><?php esc_html_e( 'Vamos trabalhar juntos?', 'mage' ); ?></h2>
		<p><?php esc_html_e( 'Entre em contato e descubra o serviço ideal para o seu projeto.', 'mage' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/contato' ) ); ?>" class="btn btn-white"><?php esc_html_e( 'Fale Conosco', 'mage' ); ?></a>
	</div>
</section>

<?php get_footer(); ?>
