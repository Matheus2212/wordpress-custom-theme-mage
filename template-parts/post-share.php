<?php
/**
 * Social share links for the current post (no third-party scripts).
 *
 * @package mage
 */

$mage_url   = rawurlencode( get_permalink() );
$mage_title = rawurlencode( get_the_title() );
?>
<div class="post-share">
	<span class="post-share__label"><?php esc_html_e( 'Compartilhar:', 'mage' ); ?></span>
	<a class="post-share__link" href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $mage_url ); ?>&text=<?php echo esc_attr( $mage_title ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Compartilhar no X (Twitter)', 'mage' ); ?>">X</a>
	<a class="post-share__link" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $mage_url ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Compartilhar no Facebook', 'mage' ); ?>">f</a>
	<a class="post-share__link" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr( $mage_url ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Compartilhar no LinkedIn', 'mage' ); ?>">in</a>
	<a class="post-share__link" href="https://api.whatsapp.com/send?text=<?php echo esc_attr( $mage_title ); ?>%20<?php echo esc_attr( $mage_url ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Compartilhar no WhatsApp', 'mage' ); ?>">wa</a>
</div>
