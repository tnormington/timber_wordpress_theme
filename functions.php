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

		// Filters
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_filter( 'wp_check_filetype_and_ext', array($this, 'add_svg_to_allowed_filetypes'));
		add_filter( 'upload_mimes', array($this, 'cc_mime_types') );

		// Actions
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_resources' ) );
		add_action( 'admin_head', array( $this, 'fix_svg' ) );

		// Shortcodes
		add_shortcode('hr', array( $this, 'tjn_hr_shortcode' ) );

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
		wp_enqueue_style( 'slick-default', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css' );
		wp_enqueue_style( 'slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css' );
		wp_enqueue_style( 'site-style', get_template_directory_uri().'/assets/css/style.css' );
		// Scripts
		// wp_enqueue_script( 'lettering', get_template_directory_uri().'/bower_components/letteringjs/jquery.lettering.js', array('jquery'));
		wp_enqueue_script( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js', array('jquery'));
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
		global $woocommerce;
		$context['cart_count'] = $woocommerce->cart->get_cart_contents_count();
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

	// Shortcodes

	function tjn_hr_shortcode($attributes) {
		extract( shortcode_atts( array(
			'color' => 'teal',
			'type' => 'short-chubby'
		), $attributes, 'hr' ) );
		$hr_format = '<div class="hr hr-%s hr-%s"></div>';
		return sprintf($hr_format, $color, $type);
	}

}

new StarterSite();



// Fix the .current-menu-item class issue that plagues the WP menu
// :( :( :( :( :( :( :( :( :( :( :( :( :( :( :( :( :( :( 
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


// turn the comments on by default for all projects i think?
function default_comments_on( $data ) {
    if( $data['post_type'] == 'project' ) {
        $data['comment_status'] = 1;
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'default_comments_on' );


// Add WooCommerce Theme Support

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

function timber_set_product( $post ) {
    global $product;
    if ( is_woocommerce() ) {
        // $product = get_product($post->ID);
		$product = get_product($post->get_id());
    }
}


// Remove unecessary Woocommerce checkout fields

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
  
function custom_override_checkout_fields( $fields ) {
	// unset($fields['billing']['billing_first_name']);
	// unset($fields['billing']['billing_last_name']);
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_address_1']);
	unset($fields['billing']['billing_address_2']);
	unset($fields['billing']['billing_city']);
	unset($fields['billing']['billing_postcode']);
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_state']);
	unset($fields['billing']['billing_phone']);
	unset($fields['order']['order_comments']);
	// unset($fields['billing']['billing_email']);
	unset($fields['account']['account_username']);
	unset($fields['account']['account_password']);
	unset($fields['account']['account_password-2']);


    return $fields;
}

// Hide page title, price, and escerpt on single product page

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );


// Remove image thumbnail on archive page
// remove_action( 'woocommerce_before_shop_loop_item_title', 'action_woocommerce_before_shop_loop_item_title', 10, 0 );


// Add product content to single product page

// define the woocommerce_before_single_product_summary callback 
function action_woocommerce_before_single_product_summary(  ) { 
    // make action magic happen here... 
	global $post;

	$content = $post->post_content;


	echo '<div class="single-product__product-content">'.$content.'</div>';
}; 
         
// add the action 
add_action( 'woocommerce_single_product_summary', 'action_woocommerce_before_single_product_summary', 1, 0 ); 



// Remove product tabs
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;

}


// function add_product_steps() {
// 	// echo file_get_contents( get_stylesheet_directory_uri() . '/inc/product_steps.php' );
// 	echo '<div class="product-steps-track"></div>';
// }

// add_action('wccpf/before/fields/start', 'add_product_steps');


function add_product_results() {
	include(__DIR__ . '/inc/product_results.php' );
}

add_action( 'wccpf/after/fields/end', 'add_product_results');


// hide coupon field on cart page
function hide_coupon_field_on_cart( $enabled ) {
	if ( is_cart() ) {
		$enabled = false;
	}
	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_cart' );

// hide coupon field on checkout page
function hide_coupon_field_on_checkout( $enabled ) {
	if ( is_checkout() ) {
		$enabled = false;
	}
	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_checkout' );

// remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 ); 
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 ); 
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 ); 

remove_action( 'woocommerce_before_main_content' , 'woocommerce_breadcrumb' , 20);
remove_action( 'woocommerce_after_shop_loop_item' , 'woocommerce_template_loop_add_to_cart' , 10);


// add_filter('begin_fetch_post_thumbnail_html', 'change_product_thumbnail_size', 10, 3 );

// function change_product_thumbnail_size($post_id, $post_thumbnail_id, $size)
// {
// 	if(get_post_type($post_id) === 'product') {
// 		$size = 'full';
// 	}
// }

// function woo_remove_all_quantity_fields( $return, $product ) {
// 	return true;
// }

// add_filter( 'woocommerce_is_sold_individually', 'woo_remove_all_quantity_fields', 10, 2 );


// Require preview emails for woocommerce

$preview = get_stylesheet_directory() . '/woocommerce/emails/woo-preview-emails.php';

if(file_exists($preview)) {
    require $preview;
}


