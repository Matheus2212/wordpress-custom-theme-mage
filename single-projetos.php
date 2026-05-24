<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="single-hero">
	<div class="container">
		<span class="tag"><?php esc_html_e( 'Projeto', 'mage' ); ?></span>
		<h1><?php the_title(); ?></h1>
	</div>
</div>

<div class="single-content">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="single-thumbnail"><?php the_post_thumbnail( 'mage-hero' ); ?></div>
	<?php endif; ?>

	<?php
	$url_projeto = get_post_meta( get_the_ID(), 'url_projeto', true );
	$cliente     = get_post_meta( get_the_ID(), 'cliente', true );
	$tecnologias = get_post_meta( get_the_ID(), 'tecnologias', true );
	if ( $url_projeto || $cliente || $tecnologias ) :
	?>
	<div style="display:flex;gap:24px;flex-wrap:wrap;background:var(--color-gray-100);border-radius:var(--radius);padding:24px;margin-bottom:32px;">
		<?php if ( $cliente ) : ?>
			<div><strong><?php esc_html_e( 'Cliente:', 'mage' ); ?></strong><br><?php echo esc_html( $cliente ); ?></div>
		<?php endif; ?>
		<?php if ( $tecnologias ) : ?>
			<div><strong><?php esc_html_e( 'Tecnologias:', 'mage' ); ?></strong><br><?php echo esc_html( $tecnologias ); ?></div>
		<?php endif; ?>
		<?php if ( $url_projeto ) : ?>
			<div><strong><?php esc_html_e( 'URL:', 'mage' ); ?></strong><br><a href="<?php echo esc_url( $url_projeto ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_url( $url_projeto ); ?></a></div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</div>

<section class="cta-strip">
	<div class="container">
		<h2><?php esc_html_e( 'Gostou do projeto?', 'mage' ); ?></h2>
		<p><?php esc_html_e( 'Podemos criar algo assim para o seu negócio.', 'mage' ); ?></p>
		<div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
			<a href="<?php echo esc_url( home_url( '/contato' ) ); ?>" class="btn btn-white"><?php esc_html_e( 'Fale Conosco', 'mage' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/projetos' ) ); ?>" class="btn btn-outline" style="color:#fff;border-color:#fff;"><?php esc_html_e( 'Ver Mais Projetos', 'mage' ); ?></a>
		</div>
	</div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
