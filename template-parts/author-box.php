<?php
/**
 * Author bio box shown at the end of a single post.
 *
 * @package mage
 */

$mage_author_id  = get_the_author_meta( 'ID' );
$mage_author_bio = get_the_author_meta( 'description' );

if ( ! $mage_author_bio ) {
	return;
}
?>
<aside class="author-box">
	<div class="author-box__avatar"><?php echo get_avatar( $mage_author_id, 96 ); ?></div>
	<div class="author-box__body">
		<p class="author-box__eyebrow"><?php esc_html_e( 'Escrito por', 'mage' ); ?></p>
		<h2 class="author-box__name">
			<a href="<?php echo esc_url( get_author_posts_url( $mage_author_id ) ); ?>" rel="author"><?php echo esc_html( get_the_author() ); ?></a>
		</h2>
		<p class="author-box__bio"><?php echo esc_html( $mage_author_bio ); ?></p>
	</div>
</aside>
