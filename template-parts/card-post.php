<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="card-thumbnail">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'mage-card' ); ?></a>
		</div>
	<?php endif; ?>
	<div class="card-body">
		<div class="card-meta">
			<span><?php echo esc_html( get_the_date() ); ?></span>
			<?php
			$cats = get_the_category();
			if ( $cats ) :
				echo '<span class="tag" style="margin:0;">' . esc_html( $cats[0]->name ) . '</span>';
			endif;
			?>
		</div>
		<h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a href="<?php the_permalink(); ?>" class="card-link"><?php esc_html_e( 'Leia mais', 'mage' ); ?></a>
	</div>
</article>
