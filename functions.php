<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	
	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_filter( 'wp_check_filetype_and_ext', array($this, 'add_svg_to_allowed_filetypes'));
		add_filter( 'upload_mimes', array($this, 'cc_mime_types') );
		// add_filter( 'nav_menu_css_class', array( $this, 'custom_menu_item_classes') );
		// add_filter( 'nav_menu_css_class', array( $this, 'fix_blog_menu_css_class', 10, 2 ) );
		// add_action('nav_menu_css_class', array($this, 'add_current_nav_class') );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_resources' ) );
		add_action( 'admin_head', array($this, 'fix_svg') );
		parent::__construct();
	}

	// Allow SVG
	function add_svg_to_allowed_filetypes($data, $file, $filename, $mimes) {

		global $wp_version;
		if ( $wp_version !== '4.7.1' ) {
			return $data;
		}

		$filetype = wp_check_filetype( $filename, $mimes );

		return [
			'ext'             => $filetype['ext'],
			'type'            => $filetype['type'],
			'proper_filename' => $data['proper_filename']
		];

	}

	function cc_mime_types( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	function fix_svg() {
		echo '<style type="text/css">
				.attachment-266x266, .thumbnail img {
					width: 100% !important;
					height: auto !important;
				}
			</style>';
	}




	function enqueue_resources() {
		// Styles
		wp_enqueue_style( 'site-style', get_template_directory_uri().'/assets/css/style.css' );
		// Scripts
		// wp_enqueue_script( 'lettering', get_template_directory_uri().'/bower_components/letteringjs/jquery.lettering.js', array('jquery'));
		wp_enqueue_script( 'site-js', get_template_directory_uri().'/assets/js/script.js', array('jquery'));
	}

	function register_post_types() {
		//this is where you can register custom post types
		include('functions/project.cpt.php');
		include('functions/sketchbook.cpt.php');
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
		include('functions/project_categories.tax.php');
		include('functions/project_keywords.tax.php');
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

}

new StarterSite();



add_action('nav_menu_css_class', 'add_current_nav_class', 10, 2 );

	function add_current_nav_class($classes, $item) {
	
	// Getting the current post details
	global $post;
	
	// Getting the post type of the current post
	$current_post_type = get_post_type_object(get_post_type($post->ID));
	$current_post_type_slug = $current_post_type->rewrite['slug'];
		
	// Getting the URL of the menu item
	$menu_slug = strtolower(trim($item->url));
	
	// If the menu item URL contains the current post types slug add the current-menu-item class
	if (strpos($menu_slug,$current_post_type_slug) !== false) {
	
	   $classes[] = 'current-menu-item';
	
	} else {
		
		$classes = array_diff( $classes, array( 'current_page_parent' ) );
	}
	
	// Return the corrected set of classes to be added to the menu item
	return $classes;

}

function default_comments_on( $data ) {
    if( $data['post_type'] == 'project' ) {
        $data['comment_status'] = 1;
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'default_comments_on' );