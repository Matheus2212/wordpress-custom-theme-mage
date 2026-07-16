<?php
/**
 * Template for the Contact page (slug: contato).
 *
 * Shows a header, the company contact details and a working lead form
 * (handled by mage_lead_form() — saves the submission and e-mails it).
 *
 * @package mage
 */

get_header();

$lead_status = isset( $_GET['lead'] ) ? sanitize_key( wp_unslash( $_GET['lead'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

// WhatsApp: troque pelo número real. Formato internacional só com dígitos (55 + DDD + número).
$mage_whatsapp_num  = '5549999999999';
$mage_whatsapp_show = '(49) 9 9999-9999';

while ( have_posts() ) :
	the_post();
	?>

	<section class="archive-header">
		<div class="container">
			<?php if ( function_exists( 'mage_breadcrumbs' ) ) { mage_breadcrumbs(); } ?>
			<span class="tag"><?php esc_html_e( 'Contato', 'mage' ); ?></span>
			<h1><?php the_title(); ?></h1>
			<p><?php esc_html_e( 'Vamos conversar sobre o seu projeto. Preencha o formulário ou fale com a gente pelos canais abaixo — respondemos o quanto antes.', 'mage' ); ?></p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="contato-grid">

				<div class="contato-info">
					<?php if ( trim( get_the_content() ) ) : ?>
						<div class="lp-richtext"><?php the_content(); ?></div>
					<?php endif; ?>

					<ul class="contato-list">
						<li>
							<span class="contato-list__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none"><path d="M4 6h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.8"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
							</span>
							<span class="contato-list__body">
								<strong><?php esc_html_e( 'E-mail', 'mage' ); ?></strong>
								<a href="mailto:contato@magesystems.com.br">contato@magesystems.com.br</a>
							</span>
						</li>
						<li>
							<span class="contato-list__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none"><path d="M20 12a8 8 0 0 1-11.9 6.96L4 20l1.1-4A8 8 0 1 1 20 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5.6 0 1-.5 1-1 0-.3-.2-.6-.5-.7l-1.3-.5-.9.9c-1-.4-1.8-1.2-2.2-2.2l.9-.9-.5-1.3c-.1-.3-.4-.5-.7-.5-.5 0-1 .4-1 1Z" fill="currentColor"/></svg>
							</span>
							<span class="contato-list__body">
								<strong><?php esc_html_e( 'WhatsApp', 'mage' ); ?></strong>
								<a href="https://wa.me/<?php echo esc_attr( $mage_whatsapp_num ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $mage_whatsapp_show ); ?></a>
							</span>
						</li>
						<li>
							<span class="contato-list__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none"><path d="M12 21s7-5.686 7-11a7 7 0 1 0-14 0c0 5.314 7 11 7 11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.8"/></svg>
							</span>
							<span class="contato-list__body">
								<strong><?php esc_html_e( 'Endereço', 'mage' ); ?></strong>
								<?php esc_html_e( 'Rua Venezuela, 74 – Bairro das Nações', 'mage' ); ?><br>
								<?php esc_html_e( 'Concórdia – SC · CEP 89.708-130', 'mage' ); ?>
							</span>
						</li>
						<li>
							<span class="contato-list__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none"><rect x="4" y="4" width="16" height="16" rx="4" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.8"/><circle cx="17" cy="7" r="1" fill="currentColor"/></svg>
							</span>
							<span class="contato-list__body">
								<strong><?php esc_html_e( 'Redes sociais', 'mage' ); ?></strong>
								<a href="https://instagram.com/magesystems" target="_blank" rel="noopener noreferrer">Instagram</a>
								·
								<a href="https://linkedin.com/company/magesystems" target="_blank" rel="noopener noreferrer">LinkedIn</a>
							</span>
						</li>
					</ul>
				</div>

				<div class="contato-form">
					<div class="lp-form-card">
						<?php if ( 'ok' === $lead_status ) : ?>
							<p class="lp-alert lp-alert--ok"><?php esc_html_e( 'Recebemos sua mensagem! Em breve nossa equipe retornará.', 'mage' ); ?></p>
						<?php elseif ( 'erro' === $lead_status ) : ?>
							<p class="lp-alert lp-alert--erro"><?php esc_html_e( 'Não foi possível enviar. Verifique os dados e tente novamente.', 'mage' ); ?></p>
						<?php endif; ?>
						<?php
						mage_lead_form( array(
							'heading' => __( 'Envie uma mensagem', 'mage' ),
							'button'  => __( 'Enviar mensagem', 'mage' ),
						) );
						?>
					</div>
				</div>

			</div>
		</div>
	</section>

	<section class="contato-map-wrap" aria-label="<?php esc_attr_e( 'Nossa localização', 'mage' ); ?>">
		<iframe
			class="contato-map"
			title="<?php esc_attr_e( 'Mapa da localização da Mage Systems', 'mage' ); ?>"
			src="https://www.google.com/maps?q=<?php echo rawurlencode( 'Rua Venezuela, 74, Concórdia - SC, 89708-130' ); ?>&output=embed"
			loading="lazy"
			referrerpolicy="no-referrer-when-downgrade"
			allowfullscreen></iframe>
	</section>

	<?php
endwhile;

get_footer();
