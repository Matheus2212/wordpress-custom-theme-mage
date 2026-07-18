<?php
/**
 * Structured-data helpers for services and projects.
 *
 * @package mage
 */

if ( ! function_exists( 'mage_schema_faq_node' ) ) {
	/**
	 * Build a FAQPage node from "{prefix}{n}_pergunta/_resposta" post meta.
	 *
	 * @param string $prefix Meta key prefix (e.g. 'lp_faq_' or 'proj_faq_').
	 * @param int    $count  How many slots to check.
	 * @return array|null    FAQPage node or null when empty.
	 */
	function mage_schema_faq_node( $prefix, $count = 6 ) {
		$items = array();
		for ( $i = 1; $i <= $count; $i++ ) {
			$q = get_post_meta( get_the_ID(), $prefix . $i . '_pergunta', true );
			$a = get_post_meta( get_the_ID(), $prefix . $i . '_resposta', true );
			if ( '' === trim( (string) $q ) ) {
				continue;
			}
			$items[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( (string) $q ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( (string) $a ),
				),
			);
		}

		if ( empty( $items ) ) {
			return null;
		}

		return array(
			'@type'      => 'FAQPage',
			'@id'        => get_permalink() . '#faq',
			'mainEntity' => $items,
		);
	}
}

if ( ! function_exists( 'mage_schema_parse_price' ) ) {
	/**
	 * Extract a numeric price from a free-text price string.
	 * Handles BR formats like "R$ 1.997,00" → "1997.00". Returns '' if none.
	 *
	 * @param string $str Raw price text.
	 * @return string
	 */
	function mage_schema_parse_price( $str ) {
		$str = (string) $str;
		if ( ! preg_match( '/\d[\d.,]*/', $str, $m ) ) {
			return '';
		}
		$num = $m[0];
		if ( false !== strpos( $num, ',' ) ) {
			$num = str_replace( '.', '', $num ); // thousands separator
			$num = str_replace( ',', '.', $num ); // decimal separator
		}
		$num = preg_replace( '/[^0-9.]/', '', $num );
		return is_numeric( $num ) ? $num : '';
	}
}
