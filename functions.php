<?php
/**
 * simple_grey functions and definitions
 *
 * @package simple_grey
 */

 if ( ! class_exists( 'Timber' ) ) {
 	add_action( 'admin_notices', function() {
 			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
 		} );
 	return;
 }

 Timber::$dirname = array('templates', 'views');

 /**
  * Extend Timber's user object.
  */

 class SimpleGreyUser extends TimberUser {
   public function can( $capability ) {
     if ( $this->ID ) {
       return user_can( $this->ID, $capability );
     }
     return false;
  }
 }


 class TwigEnabledSite extends TimberSite {

 	function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
 		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
 		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
 		add_action( 'init', array( $this, 'register_post_types' ) );
 		add_action( 'init', array( $this, 'register_taxonomies' ) );
 		add_action( 'wp_enqueue_scripts', array( $this, 'add_theme_scripts' ) );
    add_action( 'widgets_init', array( $this, 'define_sidebars' ) );

 		parent::__construct();
 	}

 	function register_post_types() {
 		//this is where you can register custom post types
 	}

 	function register_taxonomies() {
 		//this is where you can register custom taxonomies
 	}

 	function add_to_context( $context ) {

    // current user id
    $uid = get_current_user_id();
    $context['current_user'] = new SimpleGreyUser($uid);

    // site branding
		$brand_classes = array( 'site-branding', 'row');
		if ( get_theme_mod( 'simple_grey_logo' ) ) :
		    $brand_classes[] = 'with-logo';
		endif;
		if ( get_theme_mod( 'simple_grey_header_drop_shadow' ) ) :
		    $brand_classes[] = 'drop-shadow';
		endif;
		$context['brand_classes'] = $brand_classes;

		// site logo
		$logo_image_id = get_theme_mod( 'simple_grey_logo' );
		$context['logo'] = new TimberImage($logo_image_id);

		$logo_classes = array( 'site-logo' );
    if ( '' != get_theme_mod( 'simple_grey_logo_style' ) ) :
      $logo_classes[] = get_theme_mod( 'simple_grey_logo_style' );
    endif;

		$context['logo_classes'] = $logo_classes;

    // primary menu
 		$context['menu'] = new TimberMenu('primary');
    $menu_classes = array('nav-menu');
    if ( get_theme_mod( 'simple_grey_nav_style' ) ) :
		  $menu_classes[] = get_theme_mod( 'simple_grey_nav_style' );
		endif;
    $context['menu_classes'] = $menu_classes;
    $context['nav_style'] = get_theme_mod( 'simple_grey_nav_style' );

    // footer
    if ( get_theme_mod( 'simple_grey_footer_text_top' ) ){
      $context['footer_text_top'] = get_theme_mod( 'simple_grey_footer_text_top' );
    }
    if ( get_theme_mod( 'simple_grey_footer_text_bottom' ) ){
      $context['footer_text_bottom'] = get_theme_mod( 'simple_grey_footer_text_bottom' );
    }
    if ( get_theme_mod( 'simple_grey_show_footer_credits' ) ){
      $context['footer_credits'] = true;
    }

    // sidebars (widget areas)
    $context['sidebar'] = Timber::get_widgets('sidebar-secondary');
    $context['sidebar_id'] = 'secondary';
    $context['sidebar_footer'] = Timber::get_widgets('sidebar-footer');


 		$context['site'] = $this;
 		return $context;
 	}

 	function add_to_twig( $twig ) {
 		/* this is where you can add your own fuctions to twig */
 		$twig->addExtension( new Twig_Extension_StringLoader() );
 		return $twig;
 	}

 	function add_theme_scripts() {
    // load fonts
  	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic', false );
  	wp_enqueue_style( 'dashicons' );

    // load theme stylesheets
  	if ( is_rtl() )
  	{
  		wp_enqueue_style( 'simple-grey-style-rtl', get_template_directory_uri() . '/css/simple-grey-rtl.css' );
  	}
  	else {
  		wp_enqueue_style( 'simple-grey-style', get_template_directory_uri() . '/css/simple-grey.css' );
  	}

  	wp_enqueue_script( 'simple-grey-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20140817', true );

  	wp_enqueue_script( 'simple-grey-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

  	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
  		wp_enqueue_script( 'comment-reply' );
  	}

  	// fix issues with oEmbeds
    wp_enqueue_script ( 'simple-grey-oembed-adjust', get_template_directory_uri() . '/js/oembed-adjust.js', array( 'jquery' ), null, true );

  	// accessibility features
    wp_enqueue_script ( 'accessibility', get_template_directory_uri() . '/js/accessibility.js', array( 'jquery' ), null, true );

 	}

	function theme_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'simple-grey', get_template_directory() . '/languages' );

		// Add theme feature support.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-formats' );
 		add_theme_support( 'menus' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		// set thumbnail size
	  set_post_thumbnail_size( 220, 220 );

    // Add theme support for Custom Header
  	$header_args = array(
  		'header-text'            => false,
  		'default-text-color'     => '',
  		'default-image'          => '',
  		'width'                  => 1000,
  		'height'                 => 250,
  		'flex-height'            => true,
  		'wp-head-callback'       => 'simple_grey_header_style',
  		'admin-head-callback'    => 'simple_grey_admin_header_style',
  	);
  	add_theme_support( 'custom-header', $header_args );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio' ) );

	/*
	 * Remove support for unused theme features.
	 * See https://codex.wordpress.org/Theme_Features
	 * See https://codex.wordpress.org/Function_Reference/remove_theme_support
	 */
	 remove_theme_support( 'custom-background' );

	}

  function define_sidebars() {

      register_sidebar( array(
  		'name'          => __( 'Secondary', 'simple-grey' ),
  		'id'            => 'sidebar-secondary',
  		'description'   => '',
  		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  		'after_widget'  => '</aside>',
  		'before_title'  => '<h1 class="widget-title">',
  		'after_title'   => '</h1>',
  	) );

  	register_sidebar( array(
  		'name'          => __( 'Featured', 'simple-grey' ),
  		'id'            => 'sidebar-featured',
  		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  		'after_widget'  => '</aside>',
  		'before_title'  => '<h1 class="widget-title">',
  		'after_title'   => '</h1>',
  	) );

  	register_sidebar( array(
  		'name'          => __( 'Footer', 'simple-grey' ),
  		'id'            => 'sidebar-footer',
  		'before_widget' => '<div id="%1$s" class="widget %2$s">',
  		'after_widget'  => '</div>',
  		'before_title'  => '<h1 class="widget-title">',
  		'after_title'   => '</h1>',
  	) );

  }


 } // end class TwigEnabledSite

 new TwigEnabledSite();


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

/**
* Apply theme's stylesheet to the visual editor.
*
* @uses add_editor_style() Links a stylesheet to visual editor
* @uses get_stylesheet_uri() Returns URI of theme stylesheet
*/
function simple_grey_add_editor_styles() {
	add_editor_style( 'css/editor.css' );
}
add_action( 'init', 'simple_grey_add_editor_styles' );

/**
 * Post Format customizations
 */
require get_template_directory() . '/inc/post-formats.php';

/**
 * Implement the Custom Header and Custom Background features.
 */
require get_template_directory() . '/inc/custom-theme-features.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Menu functions and walkers
 */
 require get_template_directory() . '/inc/menu.php';
require get_template_directory() . '/inc/aria-walker-nav-menu.php';
