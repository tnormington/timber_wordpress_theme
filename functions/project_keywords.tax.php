<?php
$labels = array(
		'name'                       => _x( 'Project Keywords', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Project Keyword', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Project Keywords', 'text_domain' ),
		'all_items'                  => __( 'All Project Keywords', 'text_domain' ),
		'parent_item'                => __( 'Parent Project Keyword', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Project Keyword:', 'text_domain' ),
		'new_item_name'              => __( 'New Project Keyword Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Project Keyword', 'text_domain' ),
		'edit_item'                  => __( 'Edit Project Keyword', 'text_domain' ),
		'update_item'                => __( 'Update Project Keyword', 'text_domain' ),
		'view_item'                  => __( 'View Project Keyword', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Project Keywords with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Project Keywords', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Project Keywords', 'text_domain' ),
		'search_items'               => __( 'Search Project Keywords', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No Project Keywords', 'text_domain' ),
		'items_list'                 => __( 'Project Keywords list', 'text_domain' ),
		'items_list_navigation'      => __( 'Project Keywords list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
		'rest_base'                  => 'project_keywords',
		'rest_controller_class'      => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'project_keywords', array( 'project' ), $args );