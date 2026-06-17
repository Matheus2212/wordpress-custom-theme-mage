<?php
/**
 * The template for displaying search forms.
 *
 * @package mage
 */

$mage_search_id = 'search-field-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form-wrap" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only" for="<?php echo esc_attr( $mage_search_id ); ?>"><?php esc_html_e( 'Buscar no site:', 'mage' ); ?></label>
	<input
		type="search"
		id="<?php echo esc_attr( $mage_search_id ); ?>"
		class="search-field"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'O que você procura?', 'mage' ); ?>"
		required
	/>
	<button type="submit" class="search-submit"><?php esc_html_e( 'Buscar', 'mage' ); ?></button>
</form>
