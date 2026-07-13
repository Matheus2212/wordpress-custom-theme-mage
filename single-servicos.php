<?php
/**
 * Landing-page template for the "servicos" custom post type.
 *
 * Sections mirror the provided wireframe (hero + lead form, service,
 * benefits, testimonials, about, FAQ, final CTA) using the theme's brand
 * colors. Content is managed in the editor via the "Landing page do
 * serviço" meta box; testimonials come from the "Depoimentos" CPT and the
 * about block from a selected "Sobre Nós" entry.
 *
 * @package mage
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Small helper: meta value with a fallback.
	$m = function ( $key, $default = '' ) {
		$value = get_post_meta( get_the_ID(), $key, true );
		return ( '' !== $value && null !== $value ) ? $value : $default;
	};

	$lead_status  = isset( $_GET['lead'] ) ? sanitize_key( wp_unslash( $_GET['lead'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$benefit_icon = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M3 9l1.5-5h15L21 9M3 9v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9M3 9h18M8 13h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'lp' ); ?>>

		<?php /* ── Hero + lead form ─────────────────────────────────────────── */ ?>
		<section class="lp-hero">
			<div class="container">
				<div class="lp-hero__topbar">
					<div class="lp-hero__logo">
						<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
							<span class="lp-hero__logotext"><?php bloginfo( 'name' ); ?></span>
						<?php endif; ?>
					</div>
					<a class="lp-hero__phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $m( 'lp_telefone', '(48) 9 9999-9999' ) ) ); ?>">
						<?php echo esc_html( $m( 'lp_telefone', '(48) 9 9999-9999' ) ); ?>
					</a>
				</div>

				<div class="lp-hero__grid">
					<div class="lp-hero__content">
						<h1><?php echo esc_html( $m( 'lp_headline', get_the_title() ) ); ?></h1>
						<p><?php echo esc_html( $m( 'lp_subtitle', get_the_excerpt() ) ); ?></p>
					</div>

					<div class="lp-form-card">
						<?php if ( 'ok' === $lead_status ) : ?>
							<p class="lp-alert lp-alert--ok"><?php esc_html_e( 'Recebemos seu contato! Em breve nossa equipe retornará.', 'mage' ); ?></p>
						<?php elseif ( 'erro' === $lead_status ) : ?>
							<p class="lp-alert lp-alert--erro"><?php esc_html_e( 'Não foi possível enviar. Verifique os dados e tente novamente.', 'mage' ); ?></p>
						<?php endif; ?>
						<?php mage_lead_form(); ?>
					</div>
				</div>
			</div>
		</section>

		<?php /* ── Service overview ─────────────────────────────────────────── */ ?>
		<section class="section lp-service">
			<div class="container lp-split">
				<div class="lp-media">
					<?php
					$video = $m( 'lp_video_url' );
					if ( $video ) :
						echo wp_oembed_get( esc_url( $video ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					elseif ( has_post_thumbnail() ) :
						the_post_thumbnail( 'mage-hero' );
					else :
						echo '<span class="lp-media__play" aria-hidden="true"></span>';
					endif;
					?>
				</div>
				<div class="lp-split__text">
					<h2 class="section-title"><?php echo esc_html( $m( 'lp_servico_titulo', __( 'Título sobre o serviço', 'mage' ) ) ); ?></h2>
					<div class="lp-richtext">
						<?php
						if ( get_the_content() ) :
							the_content();
						else :
							echo '<p>' . esc_html__( 'Uma breve descrição sobre aquilo que você está vendendo ou oferecendo, de forma que você foque nos benefícios que o cliente pode ter ao utilizar o seu produto.', 'mage' ) . '</p>';
						endif;
						?>
					</div>
				</div>
			</div>
		</section>

		<?php /* ── Benefits ─────────────────────────────────────────────────── */ ?>
		<section class="section section-light lp-benefits">
			<div class="container">
				<h2 class="section-title lp-center"><?php echo esc_html( $m( 'lp_beneficios_titulo', __( 'Com este serviço você:', 'mage' ) ) ); ?></h2>
				<div class="lp-benefits__grid">
					<?php
					$default_benefit_desc = __( 'Insira aqui a descrição do benefício que seu produto gera. Mais tempo? Mais dinheiro? Economia? Durabilidade? Prazo de atendimento? Preço?', 'mage' );
					$benefits = array();
					for ( $i = 1; $i <= 6; $i++ ) {
						$b_title = $m( "lp_beneficio_{$i}_titulo" );
						$b_desc  = $m( "lp_beneficio_{$i}_desc" );
						if ( '' !== $b_title || '' !== $b_desc ) {
							$benefits[] = array( $b_title, $b_desc );
						}
					}
					if ( empty( $benefits ) ) {
						for ( $i = 0; $i < 6; $i++ ) {
							$benefits[] = array( __( 'Benefício do serviço', 'mage' ), $default_benefit_desc );
						}
					}
					foreach ( $benefits as $benefit ) :
						?>
						<div class="lp-benefit">
							<span class="lp-benefit__icon"><?php echo $benefit_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<div>
								<h3><?php echo esc_html( '' !== $benefit[0] ? $benefit[0] : __( 'Benefício do serviço', 'mage' ) ); ?></h3>
								<p><?php echo esc_html( $benefit[1] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="lp-center lp-mt">
					<a href="#lp-contato" class="btn btn-primary"><?php echo esc_html( $m( 'lp_cta_label', __( 'Chamada para ação', 'mage' ) ) ); ?></a>
				</div>
			</div>
		</section>

		<?php
		/* ── Testimonials (from the "Depoimentos" CPT) ─────────────────────── */
		if ( $m( 'lp_depoimentos_mostrar', '1' ) && function_exists( 'mage_testimonials_carousel' ) ) {
			echo mage_testimonials_carousel( array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'title' => $m( 'lp_depoimentos_titulo', __( 'Quem conhece, recomenda.', 'mage' ) ),
				'theme' => 'light',
			) );
		}

		/* ── About (from the "Sobre Nós" CPT) ──────────────────────────────── */
		$sobre_id = (int) $m( 'lp_sobre_id', 0 );
		if ( $sobre_id && function_exists( 'mage_about_section' ) ) {
			echo mage_about_section( $sobre_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>

		<?php /* ── FAQ ──────────────────────────────────────────────────────── */ ?>
		<section class="section lp-faq">
			<div class="container">
				<h2 class="section-title lp-center"><?php echo esc_html( $m( 'lp_faq_titulo', __( 'Perguntas Frequentes', 'mage' ) ) ); ?></h2>
				<div class="lp-faq__list">
					<?php
					$default_q = __( 'Quando vou começar a ver o resultado das minhas campanhas?', 'mage' );
					$default_a = __( 'Insira aqui a resposta para esta pergunta frequente do seu cliente.', 'mage' );
					$faqs = array();
					for ( $i = 1; $i <= 6; $i++ ) {
						$q = $m( "lp_faq_{$i}_pergunta" );
						$a = $m( "lp_faq_{$i}_resposta" );
						if ( '' !== $q ) {
							$faqs[] = array( $q, $a );
						}
					}
					if ( empty( $faqs ) ) {
						for ( $i = 0; $i < 3; $i++ ) {
							$faqs[] = array( $default_q, $default_a );
						}
					}
					foreach ( $faqs as $index => $faq ) :
						?>
						<details class="lp-faq__item"<?php echo 0 === $index ? ' open' : ''; ?>>
							<summary><?php echo esc_html( $faq[0] ); ?></summary>
							<div class="lp-faq__answer"><?php echo wp_kses_post( wpautop( '' !== $faq[1] ? $faq[1] : $default_a ) ); ?></div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php /* ── Final CTA ────────────────────────────────────────────────── */ ?>
		<section class="lp-final" id="lp-contato">
			<div class="container lp-split">
				<div class="lp-final__text">
					<h2><?php echo esc_html( $m( 'lp_final_titulo', __( 'Faça uma chamada final', 'mage' ) ) ); ?></h2>
					<p><?php echo esc_html( $m( 'lp_final_texto', __( 'Essa é a chamada para ação final. Chegou até aqui e ainda não cadastrou? Aproveite…', 'mage' ) ) ); ?></p>
				</div>
				<div class="lp-form-card lp-form-card--final">
					<?php mage_lead_form( array( 'heading' => '', 'button' => $m( 'lp_cta_label', __( 'Chamada para ação', 'mage' ) ) ) ); ?>
				</div>
			</div>
		</section>

	</article>

	<?php
endwhile;

get_footer();
