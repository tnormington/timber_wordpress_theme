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
		// add_filter( 'nav_menu_css_class', array( $this, 'fix_blog_menu_css_class', 10, 2 ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_resources' ) );
		parent::__construct();
	}

	function fix_blog_menu_css_class( $classes, $item ) {
		if ( is_singular( 'project' ) || is_post_type_archive( 'project' ) ) {
			if ( $item->object_id == get_option('page_for_posts') ) {
				$key = array_search( 'current_page_parent', $classes );
				if ( false !== $key )
					unset( $classes[ $key ] );
			}
		}

		return $classes;
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
		include('functions/projects.cpt.php');
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