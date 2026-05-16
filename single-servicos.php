<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="single-hero">
	<div class="container">
		<span class="tag"><?php esc_html_e( 'Serviço', 'mage' ); ?></span>
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

<section class="cta-strip">
	<div class="container">
		<h2><?php esc_html_e( 'Interessado neste serviço?', 'mage' ); ?></h2>
		<p><?php esc_html_e( 'Fale com nossa equipe e vamos criar algo incrível juntos.', 'mage' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/contato' ) ); ?>" class="btn btn-white"><?php esc_html_e( 'Solicitar Orçamento', 'mage' ); ?></a>
	</div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
