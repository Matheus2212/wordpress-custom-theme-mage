<?php get_header(); ?>

<?php if ( is_home() && ! is_front_page() ) : ?>
	<section class="archive-header">
		<div class="container">
			<?php mage_breadcrumbs(); ?>
			<span class="tag"><?php esc_html_e( 'Blog', 'mage' ); ?></span>
			<h1><?php esc_html_e( 'Últimas Publicações', 'mage' ); ?></h1>
			<p><?php esc_html_e( 'Dicas, novidades e conteúdo sobre tecnologia e marketing digital.', 'mage' ); ?></p>
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
					<p class="no-results"><?php esc_html_e( 'Nenhum post encontrado.', 'mage' ); ?></p>
				<?php endif; ?>
			</main>
			<?php get_sidebar(); ?>
		</div>
	</div>

<?php elseif ( is_front_page() ) : ?>

	<section class="hero">
		<div class="container">
			<div class="hero-content">
				<p class="hero-eyebrow"><?php esc_html_e( 'Agência Digital', 'mage' ); ?></p>
				<h1><?php esc_html_e( 'Soluções digitais que', 'mage' ); ?> <span><?php esc_html_e( 'impulsionam seu negócio', 'mage' ); ?></span></h1>
				<p><?php esc_html_e( 'Desenvolvimento web, design e estratégias digitais para transformar a sua presença online e gerar resultados reais.', 'mage' ); ?></p>
				<div class="hero-actions">
					<a href="<?php echo esc_url( home_url( '/servicos' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Nossos Serviços', 'mage' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/contato' ) ); ?>" class="btn btn-white"><?php esc_html_e( 'Fale Conosco', 'mage' ); ?></a>
				</div>
			</div>
			<div class="hero-visual">
				<?php get_template_part( 'template-parts/mage-emblem' ); ?>
			</div>
		</div>
	</section>

	<?php
	$servicos = new WP_Query( array( 'post_type' => 'servicos', 'posts_per_page' => 6, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	if ( $servicos->have_posts() ) :
	?>
	<section class="section section-light">
		<div class="container">
			<span class="tag"><?php esc_html_e( 'O que fazemos', 'mage' ); ?></span>
			<h2 class="section-title"><?php esc_html_e( 'Nossos Serviços', 'mage' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Oferecemos soluções completas para levar sua empresa ao próximo nível digital.', 'mage' ); ?></p>
			<div class="grid-3">
				<?php while ( $servicos->have_posts() ) : $servicos->the_post(); ?>
					<article class="service-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="card-thumbnail" style="border-radius:8px;margin-bottom:20px;aspect-ratio:16/9;overflow:hidden;">
								<?php the_post_thumbnail( 'mage-card', array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?>
							</div>
						<?php else : ?>
							<div class="service-icon">&#x1F680;</div>
						<?php endif; ?>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
						<a href="<?php the_permalink(); ?>" class="card-link"><?php esc_html_e( 'Saiba mais', 'mage' ); ?></a>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<div style="text-align:center;margin-top:40px;">
				<a href="<?php echo esc_url( home_url( '/servicos' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Ver todos os serviços', 'mage' ); ?></a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	$projetos = new WP_Query( array( 'post_type' => 'projetos', 'posts_per_page' => 3 ) );
	if ( $projetos->have_posts() ) :
	?>
	<section class="section section-dark">
		<div class="container">
			<span class="tag"><?php esc_html_e( 'Portfólio', 'mage' ); ?></span>
			<h2 class="section-title"><?php esc_html_e( 'Projetos Recentes', 'mage' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Conheça alguns dos projetos que desenvolvemos com dedicação e excelência.', 'mage' ); ?></p>
			<div class="grid-3">
				<?php while ( $projetos->have_posts() ) : $projetos->the_post(); ?>
					<article class="card card-dark">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="card-thumbnail"><?php the_post_thumbnail( 'mage-card' ); ?></div>
						<?php endif; ?>
						<div class="card-body">
							<h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<a href="<?php the_permalink(); ?>" class="card-link"><?php esc_html_e( 'Ver projeto', 'mage' ); ?></a>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<div style="text-align:center;margin-top:40px;">
				<a href="<?php echo esc_url( home_url( '/projetos' ) ); ?>" class="btn btn-outline" style="color:#fff;border-color:#fff;"><?php esc_html_e( 'Ver todos os projetos', 'mage' ); ?></a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	$blog_posts = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3 ) );
	if ( $blog_posts->have_posts() ) :
	?>
	<section class="section">
		<div class="container">
			<span class="tag"><?php esc_html_e( 'Blog', 'mage' ); ?></span>
			<h2 class="section-title"><?php esc_html_e( 'Últimas do Blog', 'mage' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Conteúdo relevante sobre tecnologia, marketing e inovação.', 'mage' ); ?></p>
			<div class="posts-grid">
				<?php while ( $blog_posts->have_posts() ) : $blog_posts->the_post(); ?>
					<?php get_template_part( 'template-parts/card', 'post' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<div style="text-align:center;margin-top:8px;">
				<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Ver todos os posts', 'mage' ); ?></a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	// Testimonials carousel (renders only when there are "depoimentos").
	echo mage_testimonials_carousel( array( 'theme' => 'dark', 'title' => __( 'Depoimentos', 'mage' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<section class="cta-strip">
		<div class="container">
			<h2><?php esc_html_e( 'Pronto para transformar seu negócio?', 'mage' ); ?></h2>
			<p><?php esc_html_e( 'Fale com nossa equipe e descubra como podemos ajudar.', 'mage' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/contato' ) ); ?>" class="btn btn-white"><?php esc_html_e( 'Entrar em Contato', 'mage' ); ?></a>
		</div>
	</section>

<?php else : ?>
	<div class="container">
		<div class="posts-grid" style="padding-top:120px;">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/card', 'post' ); ?>
			<?php endwhile; else : ?>
				<p><?php esc_html_e( 'Nenhum post encontrado.', 'mage' ); ?></p>
			<?php endif; ?>
		</div>
		<?php the_posts_pagination( array( 'class' => 'pagination' ) ); ?>
	</div>
<?php endif; ?>

<?php get_footer(); ?>
