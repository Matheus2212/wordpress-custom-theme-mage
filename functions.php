<?php
defined( '_S_VERSION' ) || define( '_S_VERSION', '2.0.0' );

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
	wp_enqueue_style( 'mage-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'mage-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null );

	wp_enqueue_script( 'mage-main', get_template_directory_uri() . '/js/main.js', array(), _S_VERSION, true );

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
	$site_url  = home_url();

	if ( is_singular() ) {
		$title       = get_the_title( $post );
		$description = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( get_the_content( null, false, $post ), 25 );
		$image       = get_the_post_thumbnail_url( $post, 'large' );
		$url         = get_permalink( $post );
	} else {
		$title       = get_bloginfo( 'name' );
		$description = get_bloginfo( 'description' );
		$image       = '';
		$url         = $site_url;
	}

	$description = esc_attr( wp_strip_all_tags( $description ) );

	echo '<meta name="description" content="' . $description . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . $description . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . $description . '">' . "\n";

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
			'@type'    => 'Organization',
			'name'     => $site_name,
			'url'      => $site_url,
			'logo'     => $logo_url,
		);
	} elseif ( is_singular( 'post' ) ) {
		$schema = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'BlogPosting',
			'headline'         => get_the_title( $post ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'author'           => array( '@type' => 'Person', 'name' => get_the_author_meta( 'display_name', $post->post_author ) ),
			'publisher'        => array( '@type' => 'Organization', 'name' => $site_name, 'logo' => array( '@type' => 'ImageObject', 'url' => $logo_url ) ),
			'description'      => wp_trim_words( get_the_excerpt( $post ), 25 ),
			'url'              => get_permalink( $post ),
		);
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
	register_sidebar( array(
		'name'          => __( 'Rodapé – Coluna 1', 'mage' ),
		'id'            => 'footer-1',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
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
