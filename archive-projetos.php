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
		<?php if ( have_posts() ) : ?>
			<div class="projetos-mosaico">
				<?php
				while ( have_posts() ) :
					the_post();
					$c1  = sanitize_hex_color( (string) get_post_meta( get_the_ID(), 'projeto_cor_1', true ) );
					$c2  = sanitize_hex_color( (string) get_post_meta( get_the_ID(), 'projeto_cor_2', true ) );
					$acc = $c1 ? $c1 : '#810AD2';
					$c1v = $c1 ? $c1 : '#810AD2';
					$c2v = $c2 ? $c2 : $c1v;
					?>
					<article class="mos-card" style="--proj:<?php echo esc_attr( $acc ); ?>;--proj-c1:<?php echo esc_attr( $c1v ); ?>;--proj-c2:<?php echo esc_attr( $c2v ); ?>;">
						<a class="mos-card__media" href="<?php the_permalink(); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'mage-card' );
							} else {
								$mage_icon = mage_projeto_icon( get_the_ID(), array( 120, 120 ) );
								echo '<span class="mos-card__fallback">' . $mage_icon . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
						</a>
						<div class="mos-card__body">
							<h2 class="mos-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="mos-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
							<a href="<?php the_permalink(); ?>" class="btn mos-card__btn"><?php esc_html_e( 'Ver projeto', 'mage' ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination( array( 'class' => 'pagination', 'mid_size' => 1 ) ); ?>
		<?php else : ?>
			<p class="no-results"><?php esc_html_e( 'Nenhum projeto cadastrado ainda.', 'mage' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
