</div><!-- #primary -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="container">
		<div class="footer-grid">

			<div class="footer-brand">
				<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
					<p style="font-size:1.4rem;font-weight:800;color:#fff;"><?php bloginfo( 'name' ); ?></p>
				<?php endif; ?>
				<p><?php echo esc_html( get_bloginfo( 'description' ) ?: 'Transformamos ideias em soluções digitais que impulsionam o seu negócio.' ); ?></p>
				<div class="footer-social" style="margin-top:20px;">
					<a href="https://instagram.com/magesystems" aria-label="Instagram" rel="noopener noreferrer" target="_blank">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
					</a>
					<a href="https://linkedin.com/company/magesystems" aria-label="LinkedIn" rel="noopener noreferrer" target="_blank">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
					</a>
				</div>
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
					<li><a href="mailto:contato@magesystems.com.br">contato@magesystems.com.br</a></li>
					<li><?php esc_html_e( 'Rua Venezuela, 74 – Bairro das Nações', 'mage' ); ?></li>
					<li><?php esc_html_e( 'Concórdia – SC · CEP 89.708-130', 'mage' ); ?></li>
					<li><?php esc_html_e( 'CNPJ 39.944.754/0001-35', 'mage' ); ?></li>
				</ul>
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php endif; ?>
			</div>

		</div>
	</div>

	<div class="footer-bottom">
		<div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;width:100%">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> — Matheus Felipe Marques · <?php esc_html_e( 'CNPJ 39.944.754/0001-35', 'mage' ); ?>. <?php esc_html_e( 'Todos os direitos reservados.', 'mage' ); ?></p>
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

<?php wp_footer(); ?>
</body>
</html>
