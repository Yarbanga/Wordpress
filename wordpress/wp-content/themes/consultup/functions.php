<?php define( 'CONSULTUP_THEME_DIR', get_template_directory() . '/' );
	define( 'CONSULTUP_THEME_URI', get_template_directory_uri() . '/' );
	define( 'CONSULTUP_THEME_SETTINGS', 'consultup-settings' );
	
	
	$consultup_theme_path = get_template_directory() . '/inc/icy/';

	require( $consultup_theme_path . '/consultup-custom-navwalker.php' );
	require( $consultup_theme_path . '/default_menu_walker.php' );
	require( $consultup_theme_path . '/font/font.php');
	require( $consultup_theme_path . '/customize/plugin_recommend.php');
	
	/*-----------------------------------------------------------------------------------*/
	/*	Enqueue scripts and styles.
	/*-----------------------------------------------------------------------------------*/
	require( $consultup_theme_path .'/enqueue.php');
	/* ----------------------------------------------------------------------------------- */
	/* Customizer */
	/* ----------------------------------------------------------------------------------- */
	
	require( $consultup_theme_path  . '/customize/consultup_customize_home.php');
	require( $consultup_theme_path . '/customize/consultup_customize_copyright.php');
	require( $consultup_theme_path  . '/customize/consultup_customize_header.php');
	require_once trailingslashit( get_template_directory()).'inc/init.php';
	require_once( trailingslashit( get_template_directory() ) . 'inc/icy/customize-pro/class-customize.php' );
	require_once( trailingslashit( get_template_directory() ) . 'inc/icy/customize/consultup_customize_partials.php' );
	
if ( ! function_exists( 'consultup_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function consultup_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on consultup, use a find and replace
	 * to change 'consultup' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'consultup', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary menu', 'consultup' ),
        'social' => __( 'Social Links Menu', 'consultup' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'consultup_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

    // Set up the woocommerce feature.
    add_theme_support( 'woocommerce');

    // Woocommerce Gallery Support
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

    // Added theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/* Add theme support for gutenberg block */
	add_theme_support( 'align-wide' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );
	
	//Custom logo
	
	//Custom logo
	add_theme_support(
    'custom-logo',
    array(
        'unlink-homepage-logo' => true, // Add Here!
    	)
	);
	
	// custom header Support
			$args = array(
			'default-image'		=>  get_template_directory_uri() .'/images/sub-header.jpg',
			'width'			=> '1600',
			'height'		=> '600',
			'flex-height'		=> false,
			'flex-width'		=> false,
			'header-text'		=> true,
			'default-text-color'	=> 'fff',
			'wp-head-callback'       => 'consultup_header_color',
		);
		add_theme_support( 'custom-header', $args );
	

}
endif;
add_action( 'after_setup_theme', 'consultup_setup' );


	function consultup_the_custom_logo() {
	
		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}

	}

	add_filter('get_custom_logo','consultup_logo_class');


	function consultup_logo_class($html)
	{
	$html = str_replace('custom-logo-link', 'navbar-brand', $html);
	return $html;
	}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function consultup_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'consultup_content_width', 640 );
}
add_action( 'after_setup_theme', 'consultup_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function consultup_widgets_init() {
	
	$consultup_footer_column_layout = get_theme_mod('consultup_footer_column_layout',3);
	
	$consultup_footer_column_layout = 12 / $consultup_footer_column_layout;
	
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Widget Area', 'consultup' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="consultup-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h6>',
		'after_title'   => '</h6>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area', 'consultup' ),
		'id'            => 'footer_widget_area',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="col-md-'.$consultup_footer_column_layout.' col-sm-6 rotateInDownLeft animated consultup-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h6>',
		'after_title'   => '</h6>',
	) );

}
add_action( 'widgets_init', 'consultup_widgets_init' );

//Editor Styling 
add_editor_style( array( 'css/editor-style.css') );

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Fire the wp_body_open action.
	 *
	 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
	 *
	 * @since Twenty Nineteen 1.4
	 */
	function wp_body_open() {
		/**
		 * Triggered after the opening <body> tag.
		 *
		 * @since Twenty Nineteen 1.4
		 */
		do_action( 'wp_body_open' );
	}
endif;

add_filter( 'woocommerce_show_page_title', 'consultup_hide_shop_page_title' );

function consultup_hide_shop_page_title( $title ) {
    if ( is_shop() ) $title = false;
    return $title;
}