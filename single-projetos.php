<?php
/**
 * Landing-page template for a single "projeto" (digital product/service).
 *
 * The layout mirrors a product landing page but the accent colours come
 * from the project's own colour(s): a solid colour uses --proj, two colours
 * make a gradient in --proj-bg. Content is edited in the "Landing page do
 * projeto" meta box.
 *
 * @package mage
 */

get_header();

while ( have_posts() ) :
	the_post();

	$m = function ( $key, $default = '' ) {
		$value = get_post_meta( get_the_ID(), $key, true );
		return ( '' !== $value && null !== $value ) ? $value : $default;
	};

	$proj    = mage_projeto_color( get_the_ID(), 'var(--color-primary)' );
	$cor2    = sanitize_hex_color( (string) get_post_meta( get_the_ID(), 'projeto_cor_2', true ) );
	$proj2   = $cor2 ? $cor2 : $proj;
	$proj_bg = mage_projeto_background( get_the_ID(), 'linear-gradient(135deg,#2b2b3a,#000)' );

	$cta_url   = $m( 'proj_cta_url' ) ? $m( 'proj_cta_url' ) : '#proj-contato';
	$cta_label = $m( 'proj_cta_label', __( 'Quero contratar', 'mage' ) );
	$bullets   = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $m( 'proj_bullets' ) ) ) );

	$benefit_icon = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	?>

	<article <?php post_class( 'lp lp-proj' ); ?> style="--proj:<?php echo esc_attr( $proj ); ?>;--proj-2:<?php echo esc_attr( $proj2 ); ?>;--proj-bg:<?php echo esc_attr( $proj_bg ); ?>;">

		<?php /* ── Hero ─────────────────────────────────────────────────────── */ ?>
		<header class="lp-proj-hero">
			<div class="container">
				<div class="lp-proj-hero__grid">
					<div class="lp-proj-hero__media">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'mage-hero' );
						} else {
							$mage_icon = mage_projeto_icon( get_the_ID(), array( 240, 240 ) );
							echo $mage_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
					<div class="lp-proj-hero__content">
						<?php if ( function_exists( 'mage_breadcrumbs' ) ) { mage_breadcrumbs(); } ?>
						<h1><?php echo esc_html( $m( 'proj_headline', get_the_title() ) ); ?></h1>
						<?php if ( $bullets ) : ?>
							<ul class="lp-proj-hero__bullets">
								<?php foreach ( $bullets as $bullet ) : ?>
									<li><?php echo esc_html( $bullet ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php elseif ( has_excerpt() ) : ?>
							<p><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
						<?php
						$store_badges = mage_projeto_store_badges( get_the_ID() );
						if ( $store_badges ) {
							echo $store_badges; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo '<a href="' . esc_url( $cta_url ) . '" class="btn btn-cta">' . esc_html( $cta_label ) . '</a>';
						}
						?>
					</div>
				</div>
			</div>
		</header>

		<?php /* ── Apresentação / vídeo ─────────────────────────────────────── */ ?>
		<section class="section lp-service">
			<div class="container lp-split">
				<div class="lp-media">
					<?php
					$video = $m( 'proj_video_url' );
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
					<h2 class="section-title"><?php echo esc_html( $m( 'proj_conheca_titulo', __( 'Conheça a solução', 'mage' ) ) ); ?></h2>
					<div class="lp-richtext">
						<?php
						if ( trim( get_the_content() ) ) {
							the_content();
						} else {
							echo '<p>' . esc_html__( 'Descreva aqui a sua solução digital, o problema que ela resolve e o valor que entrega para o cliente.', 'mage' ) . '</p>';
						}
						?>
					</div>
				</div>
			</div>
		</section>

		<?php
		/* ── Benefícios ────────────────────────────────────────────────────── */
		$benefits = array();
		for ( $i = 1; $i <= 6; $i++ ) {
			$bt = $m( "proj_beneficio_{$i}_titulo" );
			$bd = $m( "proj_beneficio_{$i}_desc" );
			if ( '' !== $bt || '' !== $bd ) {
				$benefits[] = array( $bt, $bd );
			}
		}
		if ( $benefits ) :
			?>
			<section class="section section-light lp-benefits">
				<div class="container">
					<h2 class="section-title lp-center"><?php echo esc_html( $m( 'proj_beneficios_titulo', __( 'Com esta solução você:', 'mage' ) ) ); ?></h2>
					<div class="lp-benefits__grid">
						<?php foreach ( $benefits as $benefit ) : ?>
							<div class="lp-benefit">
								<span class="lp-benefit__icon"><?php echo $benefit_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<div>
									<h3><?php echo esc_html( '' !== $benefit[0] ? $benefit[0] : __( 'Benefício', 'mage' ) ); ?></h3>
									<p><?php echo esc_html( $benefit[1] ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="lp-center lp-mt"><a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-cta"><?php echo esc_html( $cta_label ); ?></a></div>
				</div>
			</section>
		<?php endif; ?>

		<?php
		/* ── Planos ────────────────────────────────────────────────────────── */
		$planos = array();
		for ( $i = 1; $i <= 3; $i++ ) {
			$nome = $m( "proj_plano_{$i}_nome" );
			if ( '' === $nome ) {
				continue;
			}
			$planos[] = array(
				'nome'  => $nome,
				'preco' => $m( "proj_plano_{$i}_preco" ),
				'specs' => array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $m( "proj_plano_{$i}_specs" ) ) ) ),
			);
		}
		if ( $planos ) :
			?>
			<section class="section lp-planos">
				<div class="container">
					<h2 class="section-title lp-center"><?php echo esc_html( $m( 'proj_planos_titulo', __( 'Planos disponíveis', 'mage' ) ) ); ?></h2>
					<div class="lp-planos__grid">
						<?php foreach ( $planos as $plano ) : ?>
							<div class="lp-plano">
								<div class="lp-plano__head">
									<p class="lp-plano__nome"><?php echo esc_html( $plano['nome'] ); ?></p>
									<?php if ( $plano['preco'] ) : ?><p class="lp-plano__preco"><?php echo esc_html( $plano['preco'] ); ?></p><?php endif; ?>
								</div>
								<?php if ( $plano['specs'] ) : ?>
									<ul class="lp-plano__specs">
										<?php foreach ( $plano['specs'] as $spec ) : ?>
											<li><?php echo esc_html( $spec ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
								<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-cta lp-plano__cta"><?php echo esc_html( $cta_label ); ?></a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php
		/* ── Depoimentos (CPT) ─────────────────────────────────────────────── */
		if ( '0' !== get_post_meta( get_the_ID(), 'proj_depoimentos_mostrar', true ) && function_exists( 'mage_testimonials_carousel' ) ) {
			echo mage_testimonials_carousel( array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'title' => $m( 'proj_depoimentos_titulo', __( 'Quem conhece, recomenda.', 'mage' ) ),
				'theme' => 'light',
			) );
		}

		/* ── Garantia ──────────────────────────────────────────────────────── */
		$gar_dias  = $m( 'proj_garantia_dias' );
		$gar_texto = $m( 'proj_garantia_texto' );
		if ( $gar_dias || $gar_texto ) :
			?>
			<section class="lp-garantia">
				<div class="container">
					<?php if ( $gar_dias ) : ?>
						<div class="lp-garantia__badge"><span><?php echo esc_html( $gar_dias ); ?></span></div>
					<?php endif; ?>
					<div class="lp-garantia__text">
						<h2>
							<?php
							if ( $gar_texto ) {
								echo esc_html( $gar_texto );
							} else {
								/* translators: %s: number of days. */
								printf( esc_html__( 'Garantia incondicional de %s dias', 'mage' ), esc_html( $gar_dias ) );
							}
							?>
						</h2>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php
		/* ── FAQ ───────────────────────────────────────────────────────────── */
		$faqs = array();
		for ( $i = 1; $i <= 6; $i++ ) {
			$q = $m( "proj_faq_{$i}_pergunta" );
			if ( '' !== $q ) {
				$faqs[] = array( $q, $m( "proj_faq_{$i}_resposta" ) );
			}
		}
		if ( $faqs ) :
			?>
			<section class="section lp-faq">
				<div class="container">
					<h2 class="section-title lp-center"><?php echo esc_html( $m( 'proj_faq_titulo', __( 'Perguntas Frequentes', 'mage' ) ) ); ?></h2>
					<div class="lp-faq__list">
						<?php foreach ( $faqs as $index => $faq ) : ?>
							<details class="lp-faq__item"<?php echo 0 === $index ? ' open' : ''; ?>>
								<summary><?php echo esc_html( $faq[0] ); ?></summary>
								<div class="lp-faq__answer"><?php echo wp_kses_post( wpautop( $faq[1] ) ); ?></div>
							</details>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php /* ── CTA final ────────────────────────────────────────────────── */ ?>
		<section class="lp-final" id="proj-contato">
			<div class="container lp-split">
				<div class="lp-final__text">
					<h2><?php echo esc_html( $m( 'proj_final_titulo', __( 'Ficou interessado?', 'mage' ) ) ); ?></h2>
					<p><?php echo esc_html( $m( 'proj_final_texto', __( 'Fale com a gente e vamos tirar o seu projeto do papel.', 'mage' ) ) ); ?></p>
					<?php
					if ( $store_badges ) {
						echo $store_badges; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</div>
				<div class="lp-form-card lp-form-card--final">
					<?php mage_lead_form( array( 'heading' => '', 'button' => $cta_label ) ); ?>
				</div>
			</div>
		</section>

	</article>

	<?php
endwhile;

get_footer();
