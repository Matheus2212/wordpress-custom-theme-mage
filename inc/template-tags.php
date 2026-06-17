<?php
// Template tag helpers for Mage Systems theme.

if ( ! function_exists( 'mage_posted_on' ) ) {
	function mage_posted_on() {
		$time = '<time class="entry-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time .= '<time class="updated sr-only" datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date() ) . '</time>';
		}
		echo $time; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'mage_posted_by' ) ) {
	function mage_posted_by() {
		echo '<span class="byline"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . esc_html( get_the_author() ) . '</a></span>';
	}
}

if ( ! function_exists( 'mage_reading_time' ) ) {
	/**
	 * Estimated reading time in minutes (≈200 words/min).
	 *
	 * @param int|WP_Post|null $post Optional post.
	 * @return int
	 */
	function mage_reading_time( $post = null ) {
		$content = get_post_field( 'post_content', $post ? $post : get_the_ID() );
		$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
		return max( 1, (int) ceil( $words / 200 ) );
	}
}

if ( ! function_exists( 'mage_reading_time_display' ) ) {
	function mage_reading_time_display( $post = null ) {
		$minutes = mage_reading_time( $post );
		/* translators: %d: number of minutes. */
		echo '<span class="reading-time">' . esc_html( sprintf( _n( '%d min de leitura', '%d min de leitura', $minutes, 'mage' ), $minutes ) ) . '</span>';
	}
}

if ( ! function_exists( 'mage_post_thumbnail' ) ) {
	/**
	 * Display the post thumbnail, linked on archives and full-width on singular.
	 */
	function mage_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) {
			echo '<figure class="post-thumbnail single-thumbnail">';
			the_post_thumbnail( 'mage-hero' );
			echo '</figure>';
		} else {
			echo '<a class="post-thumbnail card-thumbnail" href="' . esc_url( get_permalink() ) . '" aria-hidden="true" tabindex="-1">';
			the_post_thumbnail( 'mage-card' );
			echo '</a>';
		}
	}
}

if ( ! function_exists( 'mage_entry_footer' ) ) {
	/**
	 * Display categories, tags and an edit link for the current post.
	 */
	function mage_entry_footer() {
		if ( 'post' === get_post_type() ) {
			$categories = get_the_category_list( esc_html__( ', ', 'mage' ) );
			if ( $categories ) {
				echo '<span class="cat-links">' . wp_kses_post( $categories ) . '</span>';
			}

			$tags = get_the_tag_list( '', esc_html__( ', ', 'mage' ) );
			if ( $tags ) {
				echo '<span class="tags-links">' . wp_kses_post( $tags ) . '</span>';
			}
		}

		edit_post_link(
			sprintf(
				/* translators: %s: post title (screen-reader only). */
				esc_html__( 'Editar %s', 'mage' ),
				'<span class="sr-only">' . wp_kses_post( get_the_title() ) . '</span>'
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}
