<?php
// Theme function hooks for Mage Systems.

// Body classes
add_filter( 'body_class', function( $classes ) {
	if ( is_singular() ) {
		$classes[] = 'is-singular';
	}
	return $classes;
} );

// Wrap embeds
add_filter( 'embed_oembed_html', function( $html ) {
	return '<div class="embed-responsive">' . $html . '</div>';
}, 10, 1 );
