<?php
defined( '_S_VERSION' ) || define( '_S_VERSION', '2.3.2' );

// ── Theme Setup ──────────────────────────────────────────────────────────────
function mage_setup() {
	load_theme_textdomain( 'mage', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'menu-primary' => esc_html__( 'Menu Principal', 'mage' ),
		'menu-footer'  => esc_html__( 'Menu Rodapé', 'mage' ),
	) );
}
add_action( 'after_setup_theme', 'mage_setup' );

function mage_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'mage_content_width', 0 );

// ── Custom Post Types ─────────────────────────────────────────────────────────
function mage_register_post_types() {
	// Serviços
	register_post_type( 'servicos', array(
		'labels' => array(
			'name'               => __( 'Serviços', 'mage' ),
			'singular_name'      => __( 'Serviço', 'mage' ),
			'add_new_item'       => __( 'Adicionar Serviço', 'mage' ),
			'edit_item'          => __( 'Editar Serviço', 'mage' ),
			'view_item'          => __( 'Ver Serviço', 'mage' ),
			'all_items'          => __( 'Todos os Serviços', 'mage' ),
		),
		'public'             => true,
		'show_in_rest'       => true,
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'servicos', 'with_front' => false ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon'          => 'dashicons-hammer',
		'menu_position'      => 5,
	) );

	// Projetos
	register_post_type( 'projetos', array(
		'labels' => array(
			'name'               => __( 'Projetos', 'mage' ),
			'singular_name'      => __( 'Projeto', 'mage' ),
			'add_new_item'       => __( 'Adicionar Projeto', 'mage' ),
			'edit_item'          => __( 'Editar Projeto', 'mage' ),
			'view_item'          => __( 'Ver Projeto', 'mage' ),
			'all_items'          => __( 'Todos os Projetos', 'mage' ),
		),
		'public'             => true,
		'show_in_rest'       => true,
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'projetos', 'with_front' => false ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon'          => 'dashicons-portfolio',
		'menu_position'      => 6,
	) );
}
add_action( 'init', 'mage_register_post_types' );

// ── Enqueue Assets ────────────────────────────────────────────────────────────
function mage_scripts() {
	$style_path  = get_stylesheet_directory() . '/style.css';
	$script_path = get_template_directory() . '/js/main.js';
	$style_ver   = file_exists( $style_path ) ? filemtime( $style_path ) : _S_VERSION;
	$script_ver  = file_exists( $script_path ) ? filemtime( $script_path ) : _S_VERSION;

	wp_enqueue_style( 'mage-style', get_stylesheet_uri(), array(), $style_ver );
	wp_enqueue_style( 'mage-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null );

	wp_enqueue_script( 'mage-main', get_template_directory_uri() . '/js/main.js', array(), $script_ver, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mage_scripts' );

// ── Performance: Remove Bloat ─────────────────────────────────────────────────
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
add_filter( 'the_generator', '__return_empty_string' );

// Disable emoji scripts
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Clean up <head>
add_filter( 'style_loader_tag', function( $tag, $handle ) {
	if ( strpos( $handle, 'mage-fonts' ) !== false ) {
		return str_replace( "rel='stylesheet'", "rel='preload' as='style' onload=\"this.rel='stylesheet'\"", $tag )
			. '<noscript>' . str_replace( "rel='preload' as='style' onload=\"this.rel='stylesheet'\"", "rel='stylesheet'", str_replace( "rel='stylesheet'", "rel='preload' as='style' onload=\"this.rel='stylesheet'\"", $tag ) ) . '</noscript>';
	}
	return $tag;
}, 10, 2 );

// ── SEO: Open Graph & Structured Data ────────────────────────────────────────
function mage_head_seo() {
	global $post;
	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url( '/' );
	$is_post   = is_singular( 'post' );
	$image     = '';

	if ( is_singular() ) {
		$title       = get_the_title( $post );
		$description = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( get_the_content( null, false, $post ) ), 30 );
		$image       = get_the_post_thumbnail_url( $post, 'mage-hero' );
		$url         = get_permalink( $post );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term        = get_queried_object();
		$title       = single_term_title( '', false );
		$description = term_description() ? term_description() : sprintf( __( 'Conteúdos sobre %s.', 'mage' ), $title );
		$url         = get_term_link( $term );
	} elseif ( is_home() ) {
		$blog_id     = (int) get_option( 'page_for_posts' );
		$title       = $blog_id ? get_the_title( $blog_id ) : __( 'Blog', 'mage' );
		$description = get_bloginfo( 'description' );
		$url         = $blog_id ? get_permalink( $blog_id ) : $site_url;
	} else {
		$title       = $site_name;
		$description = get_bloginfo( 'description' );
		$url         = $site_url;
	}

	if ( is_wp_error( $url ) ) {
		$url = $site_url;
	}

	$description = esc_attr( wp_strip_all_tags( (string) $description ) );
	$og_type     = $is_post ? 'article' : 'website';

	echo '<meta name="description" content="' . $description . '">' . "\n";

	if ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	}

	// Canonical for non-singular views (core's rel_canonical handles singular).
	if ( ! is_singular() && ! is_search() && ! is_404() && ! is_paged() ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}

	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . $description . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	if ( $is_post ) {
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c', $post ) ) . '">' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c', $post ) ) . '">' . "\n";
		$author = get_the_author_meta( 'display_name', $post->post_author );
		if ( $author ) {
			echo '<meta property="article:author" content="' . esc_attr( $author ) . '">' . "\n";
		}
		$post_cats = get_the_category( $post->ID );
		if ( $post_cats ) {
			echo '<meta property="article:section" content="' . esc_attr( $post_cats[0]->name ) . '">' . "\n";
		}
		foreach ( (array) get_the_tags( $post->ID ) as $post_tag ) {
			echo '<meta property="article:tag" content="' . esc_attr( $post_tag->name ) . '">' . "\n";
		}
	}

	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . $description . '">' . "\n";
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	// Preconnect for performance
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'mage_head_seo', 1 );

// ── Structured Data (JSON-LD) ─────────────────────────────────────────────────
function mage_structured_data() {
	global $post;
	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url();
	$logo      = get_custom_logo();
	preg_match( '/src=[\'"]([^\'"]+)[\'"]/', $logo, $logo_matches );
	$logo_url  = ! empty( $logo_matches[1] ) ? $logo_matches[1] : '';

	if ( is_front_page() || is_home() ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@graph'   => array(
				array(
					'@type' => 'Organization',
					'@id'   => $site_url . '#organization',
					'name'  => $site_name,
					'url'   => $site_url,
					'logo'  => $logo_url,
				),
				array(
					'@type'           => 'WebSite',
					'@id'             => $site_url . '#website',
					'url'             => $site_url,
					'name'            => $site_name,
					'description'     => get_bloginfo( 'description' ),
					'publisher'       => array( '@id' => $site_url . '#organization' ),
					'potentialAction' => array(
						'@type'       => 'SearchAction',
						'target'      => array(
							'@type'       => 'EntryPoint',
							'urlTemplate' => $site_url . '?s={search_term_string}',
						),
						'query-input' => 'required name=search_term_string',
					),
				),
			),
		);
	} elseif ( is_singular( 'post' ) ) {
		$post_cats = get_the_category( $post->ID );
		$post_tags = get_the_tags( $post->ID );

		$schema = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'BlogPosting',
			'mainEntityOfPage' => array( '@type' => 'WebPage', '@id' => get_permalink( $post ) ),
			'headline'         => get_the_title( $post ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', $post->post_author ),
				'url'   => get_author_posts_url( $post->post_author ),
			),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => $site_name,
				'logo'  => array( '@type' => 'ImageObject', 'url' => $logo_url ),
			),
			'description'      => wp_strip_all_tags( get_the_excerpt( $post ) ),
			'url'              => get_permalink( $post ),
			'wordCount'        => str_word_count( wp_strip_all_tags( get_the_content( null, false, $post ) ) ),
		);

		if ( has_post_thumbnail( $post ) ) {
			$schema['image'] = get_the_post_thumbnail_url( $post, 'mage-hero' );
		}
		if ( $post_cats ) {
			$schema['articleSection'] = $post_cats[0]->name;
		}
		if ( $post_tags ) {
			$schema['keywords'] = implode( ', ', wp_list_pluck( $post_tags, 'name' ) );
		}
	} else {
		return;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'mage_structured_data' );

// ── Image Sizes ───────────────────────────────────────────────────────────────
add_image_size( 'mage-card', 800, 500, true );
add_image_size( 'mage-hero', 1920, 800, true );

// ── Excerpt Length ────────────────────────────────────────────────────────────
add_filter( 'excerpt_length', fn() => 25 );
add_filter( 'excerpt_more', fn() => '&hellip;' );

// ── Widgets ───────────────────────────────────────────────────────────────────
function mage_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar do Blog', 'mage' ),
		'id'            => 'sidebar-blog',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'mage_widgets_init' );

// ── Flush Rewrite Rules on Activation ────────────────────────────────────────
function mage_flush_rewrite() {
	mage_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'mage_flush_rewrite' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/breadcrumbs.php';
require get_template_directory() . '/inc/forms.php';
require get_template_directory() . '/inc/testimonials.php';
require get_template_directory() . '/inc/about.php';
require get_template_directory() . '/inc/servicos-meta.php';
require get_template_directory() . '/inc/projetos-meta.php';
require get_template_directory() . '/inc/leads.php';
require get_template_directory() . '/inc/updater.php';
