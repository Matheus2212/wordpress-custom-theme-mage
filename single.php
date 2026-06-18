<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="single-hero">
		<div class="container">
			<?php mage_breadcrumbs(); ?>
			<?php
			$mage_cats = get_the_category();
			if ( $mage_cats ) :
				?>
				<a class="tag" href="<?php echo esc_url( get_category_link( $mage_cats[0]->term_id ) ); ?>"><?php echo esc_html( $mage_cats[0]->name ); ?></a>
			<?php endif; ?>

			<h1 class="entry-title"><?php the_title(); ?></h1>

			<div class="post-meta">
				<?php mage_posted_by(); ?>
				<?php mage_posted_on(); ?>
				<?php mage_reading_time_display(); ?>
			</div>
		</div>
	</header>

	<div class="single-content">
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="single-thumbnail"><?php the_post_thumbnail( 'mage-hero' ); ?></figure>
		<?php endif; ?>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Páginas:', 'mage' ),
				'after'  => '</div>',
			) );
			?>
		</div>

		<?php if ( has_tag() ) : ?>
			<div class="entry-tags">
				<span class="entry-tags__label"><?php esc_html_e( 'Tags:', 'mage' ); ?></span>
				<?php the_tags( '', '', '' ); ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/post-share' ); ?>

		<?php get_template_part( 'template-parts/author-box' ); ?>

		<?php
		the_post_navigation( array(
			'class'               => 'post-nav',
			'prev_text'           => '<span class="post-nav__dir">' . esc_html__( 'Anterior', 'mage' ) . '</span><span class="post-nav__title">%title</span>',
			'next_text'           => '<span class="post-nav__dir">' . esc_html__( 'Próximo', 'mage' ) . '</span><span class="post-nav__title">%title</span>',
			'screen_reader_text'  => esc_html__( 'Navegação entre posts', 'mage' ),
			'aria_label'          => esc_attr__( 'Posts', 'mage' ),
		) );
		?>
	</div>

	<?php get_template_part( 'template-parts/related-posts' ); ?>

	<?php
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
	?>

</article>

<?php endwhile; ?>

<?php get_footer(); ?>
