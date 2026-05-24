<?php get_header(); ?>

<div class="page-404">
	<div>
		<span class="big-num">404</span>
		<h1><?php esc_html_e( 'Página não encontrada', 'mage' ); ?></h1>
		<p><?php esc_html_e( 'A página que você procura não existe ou foi movida.', 'mage' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Voltar ao Início', 'mage' ); ?></a>
	</div>
</div>

<?php get_footer(); ?>
