</div><!-- #primary -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="container">
		<div class="footer-grid">

			<div class="footer-brand">
				<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
					<p style="font-size:1.4rem;font-weight:800;color:#fff;"><?php bloginfo( 'name' ); ?></p>
				<?php endif; ?>
				<p><?php echo esc_html( get_bloginfo( 'description' ) ?: 'Transformamos ideias em soluções digitais que impulsionam o seu negócio.' ); ?></p>
				<?php $mage_socials = mage_social_links(); if ( $mage_socials ) : ?>
					<div class="footer-social" style="margin-top:20px;">
						<?php foreach ( $mage_socials as $mage_s ) : ?>
							<a href="<?php echo esc_url( $mage_s['url'] ); ?>" aria-label="<?php echo esc_attr( $mage_s['label'] ); ?>" rel="noopener noreferrer" target="_blank"><?php echo $mage_s['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted inline SVG. ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div>
				<p class="footer-heading"><?php esc_html_e( 'Serviços', 'mage' ); ?></p>
				<ul class="footer-links">
					<?php
					$servicos_query = new WP_Query( array( 'post_type' => 'servicos', 'posts_per_page' => 5, 'orderby' => 'title', 'order' => 'ASC' ) );
					if ( $servicos_query->have_posts() ) :
						while ( $servicos_query->have_posts() ) : $servicos_query->the_post();
							echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
						endwhile;
						wp_reset_postdata();
					else :
						echo '<li><a href="' . esc_url( home_url( '/servicos' ) ) . '">Ver todos os serviços</a></li>';
					endif;
					?>
				</ul>
			</div>

			<div>
				<p class="footer-heading"><?php esc_html_e( 'Empresa', 'mage' ); ?></p>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Início', 'mage' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/projetos' ) ); ?>"><?php esc_html_e( 'Projetos', 'mage' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Blog', 'mage' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/sobre' ) ); ?>"><?php esc_html_e( 'Sobre', 'mage' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contato' ) ); ?>"><?php esc_html_e( 'Contato', 'mage' ); ?></a></li>
				</ul>
			</div>

			<div>
				<p class="footer-heading"><?php esc_html_e( 'Contato', 'mage' ); ?></p>
				<ul class="footer-links">
					<?php $mage_femail = mage_contact( 'email' ); if ( $mage_femail ) : ?>
						<li><a href="mailto:<?php echo esc_attr( $mage_femail ); ?>"><?php echo esc_html( $mage_femail ); ?></a></li>
					<?php endif; ?>
					<?php if ( mage_contact( 'whatsapp_num' ) ) : ?>
						<li><a href="https://wa.me/<?php echo esc_attr( mage_contact( 'whatsapp_num' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( mage_contact( 'whatsapp_show' ) ? mage_contact( 'whatsapp_show' ) : mage_contact( 'whatsapp_num' ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( mage_contact( 'address_line1' ) ) : ?><li><?php echo esc_html( mage_contact( 'address_line1' ) ); ?></li><?php endif; ?>
					<?php if ( mage_contact( 'address_line2' ) ) : ?><li><?php echo esc_html( mage_contact( 'address_line2' ) ); ?></li><?php endif; ?>
					<?php if ( mage_contact( 'cnpj' ) ) : ?><li><?php printf( esc_html__( 'CNPJ %s', 'mage' ), esc_html( mage_contact( 'cnpj' ) ) ); ?></li><?php endif; ?>
				</ul>
			</div>

		</div>
	</div>

	<div class="footer-bottom">
		<div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;width:100%">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?><?php if ( mage_contact( 'empresa' ) ) { echo ' — ' . esc_html( mage_contact( 'empresa' ) ); } if ( mage_contact( 'cnpj' ) ) { echo ' · ' . esc_html( sprintf( __( 'CNPJ %s', 'mage' ), mage_contact( 'cnpj' ) ) ); } ?>. <?php esc_html_e( 'Todos os direitos reservados.', 'mage' ); ?></p>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-footer',
				'container'      => false,
				'depth'          => 1,
				'items_wrap'     => '<ul class="footer-links" style="display:flex;gap:20px;flex-wrap:wrap;">%3$s</ul>',
				'fallback_cb'    => function () {
					echo '<ul class="footer-links" style="display:flex;gap:20px;flex-wrap:wrap;">'
						. '<li><a href="' . esc_url( home_url( '/politica-de-privacidade/' ) ) . '">' . esc_html__( 'Política de Privacidade', 'mage' ) . '</a></li>'
						. '<li><a href="' . esc_url( home_url( '/termos-de-uso/' ) ) . '">' . esc_html__( 'Termos de Uso', 'mage' ) . '</a></li>'
						. '</ul>';
				},
			) );
			?>
		</div>
	</div>
</footer>

<button type="button" class="back-to-top" aria-label="<?php esc_attr_e( 'Voltar ao topo', 'mage' ); ?>" hidden>
	<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
		<path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>
</button>

<div class="cookie-consent" role="dialog" aria-live="polite" aria-label="<?php esc_attr_e( 'Aviso de cookies', 'mage' ); ?>" hidden>
	<p class="cookie-consent__text">
		<?php esc_html_e( 'Usamos cookies para melhorar sua experiência e analisar o tráfego do site. Ao continuar navegando, você concorda com a nossa', 'mage' ); ?>
		<a href="<?php echo esc_url( home_url( '/politica-de-privacidade/' ) ); ?>"><?php esc_html_e( 'Política de Privacidade', 'mage' ); ?></a>.
	</p>
	<div class="cookie-consent__actions">
		<button type="button" class="btn cookie-consent__accept"><?php esc_html_e( 'Aceitar', 'mage' ); ?></button>
		<button type="button" class="cookie-consent__decline"><?php esc_html_e( 'Recusar', 'mage' ); ?></button>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
