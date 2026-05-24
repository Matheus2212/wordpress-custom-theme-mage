<?php
// Template tag helpers for Mage Systems theme.

if ( ! function_exists( 'mage_posted_on' ) ) {
	function mage_posted_on() {
		echo '<time class="entry-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
	}
}

if ( ! function_exists( 'mage_posted_by' ) ) {
	function mage_posted_by() {
		echo '<span class="byline">' . esc_html( get_the_author() ) . '</span>';
	}
}
