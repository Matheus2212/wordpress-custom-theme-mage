<?php
/**
 * Lead form handler for the "servicos" landing page CTA forms.
 *
 * Submits to admin-post.php, validates a nonce, sanitizes the input and
 * emails the site admin, then redirects back with a status flag.
 *
 * @package mage
 */

if ( ! function_exists( 'mage_handle_lead' ) ) {
	function mage_handle_lead() {
		$fallback = home_url( '/' );
		$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : $fallback;

		if ( ! isset( $_POST['mage_lead_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mage_lead_nonce'] ) ), 'mage_lead' ) ) {
			wp_safe_redirect( add_query_arg( 'lead', 'erro', $redirect ) );
			exit;
		}

		$nome     = isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '';
		$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$telefone = isset( $_POST['telefone'] ) ? sanitize_text_field( wp_unslash( $_POST['telefone'] ) ) : '';
		$mensagem = isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '';
		$servico_id = isset( $_POST['servico_id'] ) ? absint( $_POST['servico_id'] ) : 0;

		if ( '' === $nome || ! is_email( $email ) ) {
			wp_safe_redirect( add_query_arg( 'lead', 'erro', $redirect ) );
			exit;
		}

		$servico = $servico_id ? get_the_title( $servico_id ) : '';

		/* translators: %s: service or site name. */
		$subject = sprintf( __( 'Novo contato pelo site — %s', 'mage' ), $servico ? $servico : get_bloginfo( 'name' ) );

		$body  = __( 'Nome:', 'mage' ) . ' ' . $nome . "\n";
		$body .= __( 'E-mail:', 'mage' ) . ' ' . $email . "\n";
		$body .= __( 'Telefone:', 'mage' ) . ' ' . $telefone . "\n";
		if ( $servico ) {
			$body .= __( 'Serviço:', 'mage' ) . ' ' . $servico . "\n";
		}
		$body .= "\n" . __( 'Mensagem:', 'mage' ) . "\n" . $mensagem . "\n";

		$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
		$headers[] = sprintf( 'Reply-To: %s <%s>', $nome, $email );

		$sent = wp_mail( get_option( 'admin_email' ), $subject, $body, $headers );

		wp_safe_redirect( add_query_arg( 'lead', $sent ? 'ok' : 'erro', $redirect ) );
		exit;
	}
}
add_action( 'admin_post_mage_lead', 'mage_handle_lead' );
add_action( 'admin_post_nopriv_mage_lead', 'mage_handle_lead' );

if ( ! function_exists( 'mage_lead_form' ) ) {
	/**
	 * Render a CTA lead form.
	 *
	 * @param array $args Optional. 'heading', 'button', 'compact'.
	 */
	function mage_lead_form( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'heading' => __( 'Chamada para ação', 'mage' ),
			'button'  => __( 'Chamada para ação', 'mage' ),
			'compact' => false,
		) );
		?>
		<form class="lp-form<?php echo $args['compact'] ? ' lp-form--compact' : ''; ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php if ( $args['heading'] ) : ?>
				<p class="lp-form__heading"><?php echo esc_html( $args['heading'] ); ?></p>
			<?php endif; ?>
			<input type="hidden" name="action" value="mage_lead">
			<input type="hidden" name="servico_id" value="<?php the_ID(); ?>">
			<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">
			<?php wp_nonce_field( 'mage_lead', 'mage_lead_nonce' ); ?>
			<label class="sr-only" for="lead-nome-<?php the_ID(); ?>"><?php esc_html_e( 'Nome', 'mage' ); ?></label>
			<input id="lead-nome-<?php the_ID(); ?>" type="text" name="nome" placeholder="<?php esc_attr_e( 'NOME:', 'mage' ); ?>" required>
			<label class="sr-only" for="lead-email-<?php the_ID(); ?>"><?php esc_html_e( 'E-mail', 'mage' ); ?></label>
			<input id="lead-email-<?php the_ID(); ?>" type="email" name="email" placeholder="<?php esc_attr_e( 'E-MAIL:', 'mage' ); ?>" required>
			<label class="sr-only" for="lead-tel-<?php the_ID(); ?>"><?php esc_html_e( 'DDD + Telefone', 'mage' ); ?></label>
			<input id="lead-tel-<?php the_ID(); ?>" type="text" name="telefone" placeholder="<?php esc_attr_e( 'DDD + TELEFONE:', 'mage' ); ?>">
			<label class="sr-only" for="lead-msg-<?php the_ID(); ?>"><?php esc_html_e( 'Como podemos te ajudar?', 'mage' ); ?></label>
			<textarea id="lead-msg-<?php the_ID(); ?>" name="mensagem" rows="3" placeholder="<?php esc_attr_e( 'COMO PODEMOS TE AJUDAR?', 'mage' ); ?>"></textarea>
			<button type="submit" class="btn btn-primary"><?php echo esc_html( $args['button'] ); ?></button>
		</form>
		<?php
	}
}
