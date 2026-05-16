<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link sr-only" href="#primary"><?php esc_html_e( 'Ir para o conteúdo', 'mage' ); ?></a>

<header id="masthead" class="site-header" role="banner">
	<div class="container">
		<div class="header-inner">

			<div class="site-branding">
				<?php if ( has_custom_logo() ) :
					the_custom_logo();
				else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title">
						<?php bloginfo( 'name' ); ?>
					</a>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Menu principal', 'mage' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => function() {
						echo '<ul><li><a href="' . esc_url( home_url( '/' ) ) . '">Início</a></li>'
							. '<li><a href="' . esc_url( home_url( '/servicos' ) ) . '">Serviços</a></li>'
							. '<li><a href="' . esc_url( home_url( '/projetos' ) ) . '">Projetos</a></li>'
							. '<li><a href="' . esc_url( home_url( '/blog' ) ) . '">Blog</a></li>'
							. '<li class="menu-cta"><a href="' . esc_url( home_url( '/contato' ) ) . '">Fale Conosco</a></li>'
							. '</ul>';
					},
				) );
				?>
			</nav>

			<button class="menu-toggle" aria-controls="mobile-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menu', 'mage' ); ?>">
				<span></span>
				<span></span>
				<span></span>
			</button>

		</div>
	</div>
</header>

<nav id="mobile-navigation" class="mobile-nav" aria-label="<?php esc_attr_e( 'Menu mobile', 'mage' ); ?>" aria-hidden="true">
	<?php
	wp_nav_menu( array(
		'theme_location' => 'menu-primary',
		'menu_id'        => 'mobile-menu',
		'container'      => false,
		'fallback_cb'    => function() {
			echo '<ul><li><a href="' . esc_url( home_url( '/' ) ) . '">Início</a></li>'
				. '<li><a href="' . esc_url( home_url( '/servicos' ) ) . '">Serviços</a></li>'
				. '<li><a href="' . esc_url( home_url( '/projetos' ) ) . '">Projetos</a></li>'
				. '<li><a href="' . esc_url( home_url( '/blog' ) ) . '">Blog</a></li>'
				. '<li class="menu-cta"><a href="' . esc_url( home_url( '/contato' ) ) . '">Fale Conosco</a></li>'
				. '</ul>';
		},
	) );
	?>
</nav>

<div id="primary" class="site-content">
