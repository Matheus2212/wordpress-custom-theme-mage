<?php
/**
 * Breadcrumbs for the Mage Systems theme.
 *
 * Builds the trail once and renders both an accessible <nav> and a
 * schema.org BreadcrumbList JSON-LD block in <head>, keeping the visible
 * markup and the structured data perfectly in sync.
 *
 * @package mage
 */

if ( ! function_exists( 'mage_get_breadcrumb_trail' ) ) {
	/**
	 * Build the breadcrumb trail as an array of [ 'name', 'url' ] items.
	 * The last item represents the current page and has an empty url.
	 *
	 * @return array
	 */
	function mage_get_breadcrumb_trail() {
		if ( is_front_page() ) {
			return array();
		}

		$trail = array(
			array( 'name' => __( 'Início', 'mage' ), 'url' => home_url( '/' ) ),
		);

		$blog_page_id = (int) get_option( 'page_for_posts' );

		if ( is_home() ) {
			$name    = $blog_page_id ? get_the_title( $blog_page_id ) : __( 'Blog', 'mage' );
			$trail[] = array( 'name' => $name, 'url' => '' );

		} elseif ( is_singular() ) {
			$post_type = get_post_type();

			if ( 'post' === $post_type ) {
				if ( $blog_page_id ) {
					$trail[] = array( 'name' => get_the_title( $blog_page_id ), 'url' => get_permalink( $blog_page_id ) );
				}
				$cats = get_the_category();
				if ( $cats ) {
					$trail[] = array( 'name' => $cats[0]->name, 'url' => get_category_link( $cats[0]->term_id ) );
				}
			} elseif ( 'page' === $post_type ) {
				foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
					$trail[] = array( 'name' => get_the_title( $ancestor ), 'url' => get_permalink( $ancestor ) );
				}
			} else {
				$pto = get_post_type_object( $post_type );
				if ( $pto && $pto->has_archive ) {
					$trail[] = array( 'name' => $pto->labels->name, 'url' => get_post_type_archive_link( $post_type ) );
				}
			}

			$trail[] = array( 'name' => get_the_title(), 'url' => '' );

		} elseif ( is_category() ) {
			$trail[] = array( 'name' => single_cat_title( '', false ), 'url' => '' );
		} elseif ( is_tag() ) {
			$trail[] = array( 'name' => single_tag_title( '', false ), 'url' => '' );
		} elseif ( is_post_type_archive() ) {
			$trail[] = array( 'name' => post_type_archive_title( '', false ), 'url' => '' );
		} elseif ( is_author() ) {
			$trail[] = array( 'name' => get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) ), 'url' => '' );
		} elseif ( is_search() ) {
			/* translators: %s: search query. */
			$trail[] = array( 'name' => sprintf( __( 'Busca: %s', 'mage' ), get_search_query() ), 'url' => '' );
		} elseif ( is_404() ) {
			$trail[] = array( 'name' => __( 'Página não encontrada', 'mage' ), 'url' => '' );
		} elseif ( is_archive() ) {
			$trail[] = array( 'name' => wp_strip_all_tags( get_the_archive_title() ), 'url' => '' );
		}

		return $trail;
	}
}

if ( ! function_exists( 'mage_breadcrumbs' ) ) {
	/**
	 * Output the visible breadcrumb navigation.
	 */
	function mage_breadcrumbs() {
		$trail = mage_get_breadcrumb_trail();
		if ( count( $trail ) < 2 ) {
			return;
		}

		$last = count( $trail ) - 1;
		echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Trilha de navegação', 'mage' ) . '"><ol>';
		foreach ( $trail as $i => $item ) {
			if ( $i === $last || empty( $item['url'] ) ) {
				echo '<li><span aria-current="page">' . esc_html( $item['name'] ) . '</span></li>';
			} else {
				echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a></li>';
			}
		}
		echo '</ol></nav>';
	}
}

if ( ! function_exists( 'mage_breadcrumbs_jsonld' ) ) {
	/**
	 * Output BreadcrumbList structured data in <head>.
	 */
	function mage_breadcrumbs_jsonld() {
		$trail = mage_get_breadcrumb_trail();
		if ( count( $trail ) < 2 ) {
			return;
		}

		$items    = array();
		$position = 1;
		foreach ( $trail as $item ) {
			$entry = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => wp_strip_all_tags( $item['name'] ),
			);
			if ( ! empty( $item['url'] ) ) {
				$entry['item'] = esc_url_raw( $item['url'] );
			}
			$items[] = $entry;
			$position++;
		}

		$schema = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		);

		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'mage_breadcrumbs_jsonld' );
