<?php
/*
Plugin Name: StartUp CPT Catalog
Description: Le plugin pour activer le Custom Post Catalog
Author: Yann Caplain
Version: 1.0.0
Text Domain: startup-cpt-catalog
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Include this to check if a plugin is activated with is_plugin_active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//Include this to check dependencies
include_once( 'inc/dependencies.php' );

//GitHub Plugin Updater
function startup_cpt_catalog_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-catalog',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-catalog',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-catalog/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-catalog',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-catalog/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

//add_action( 'init', 'startup_cpt_catalog_updater' );

//CPT
function startup_cpt_catalog() {
	$labels = array(
		'name'                => _x( 'Catalog', 'Post Type General Name', 'startup-cpt-catalog' ),
		'singular_name'       => _x( 'Catalog', 'Post Type Singular Name', 'startup-cpt-catalog' ),
		'menu_name'           => __( 'Catalog', 'startup-cpt-catalog' ),
		'name_admin_bar'      => __( 'Catalog', 'startup-cpt-catalog' ),
		'parent_item_colon'   => __( 'Parent Catalog Index:', 'startup-cpt-catalog' ),
		'all_items'           => __( 'All Catalog Indexes', 'startup-cpt-catalog' ),
		'add_new_item'        => __( 'Add New Catalog Index', 'startup-cpt-catalog' ),
		'add_new'             => __( 'Add New', 'startup-cpt-catalog' ),
		'new_item'            => __( 'New Catalog Index', 'startup-cpt-catalog' ),
		'edit_item'           => __( 'Edit Catalog Index', 'startup-cpt-catalog' ),
		'update_item'         => __( 'Update Catalog Index', 'startup-cpt-catalog' ),
		'view_item'           => __( 'View Catalog Index', 'startup-cpt-catalog' ),
		'search_items'        => __( 'Search Catalog Index', 'startup-cpt-catalog' ),
		'not_found'           => __( 'Not found', 'startup-cpt-catalog' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-catalog' )
	);
	$args = array(
		'label'               => __( 'catalog', 'startup-cpt-catalog' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions' ),
		//'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-index-card',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
        //'rewrite' => array('slug' => 'catalog-items', 'with_front' => true), //Je teste ici le conflict archive/page
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
        'capability_type'     => array('catalog_item','catalog_items'),
        'map_meta_cap'        => true
	);
	register_post_type( 'catalog', $args );

}

add_action( 'init', 'startup_cpt_catalog', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_cpt_catalog_rewrite_flush() {
    startup_cpt_catalog();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_cpt_catalog_rewrite_flush' );

// Capabilities

function startup_cpt_catalog_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_catalog_item' );
	$role_admin->add_cap( 'read_catalog_item' );
	$role_admin->add_cap( 'delete_catalog_item' );
	$role_admin->add_cap( 'edit_others_catalog_items' );
	$role_admin->add_cap( 'publish_catalog_items' );
	$role_admin->add_cap( 'edit_catalog_items' );
	$role_admin->add_cap( 'read_private_catalog_items' );
	$role_admin->add_cap( 'delete_catalog_items' );
	$role_admin->add_cap( 'delete_private_catalog_items' );
	$role_admin->add_cap( 'delete_published_catalog_items' );
	$role_admin->add_cap( 'delete_others_catalog_items' );
	$role_admin->add_cap( 'edit_private_catalog_items' );
	$role_admin->add_cap( 'edit_published_catalog_items' );
}

register_activation_hook( __FILE__, 'startup_cpt_catalog_caps' );

// Catalog season taxonomy
function startup_cpt_catalog_season() {
	$labels = array(
		'name'                       => _x( 'Catalog Seasons', 'Taxonomy General Name', 'startup-cpt-catalog' ),
		'singular_name'              => _x( 'Catalog Season', 'Taxonomy Singular Name', 'startup-cpt-catalog' ),
		'menu_name'                  => __( 'Seasons', 'startup-cpt-catalog' ),
		'all_items'                  => __( 'All Seasons', 'startup-cpt-catalog' ),
		'parent_item'                => __( 'Parent Season', 'startup-cpt-catalog' ),
		'parent_item_colon'          => __( 'Parent Season:', 'startup-cpt-catalog' ),
		'new_item_name'              => __( 'New Season Name', 'startup-cpt-catalog' ),
		'add_new_item'               => __( 'Add New Season', 'startup-cpt-catalog' ),
		'edit_item'                  => __( 'Edit Season', 'startup-cpt-catalog' ),
		'update_item'                => __( 'Update Season', 'startup-cpt-catalog' ),
		'view_item'                  => __( 'View Season', 'startup-cpt-catalog' ),
		'separate_items_with_commas' => __( 'Separate seasons with commas', 'startup-cpt-catalog' ),
		'add_or_remove_items'        => __( 'Add or remove seasons', 'startup-cpt-catalog' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-catalog' ),
		'popular_items'              => __( 'Popular Seasons', 'startup-cpt-catalog' ),
		'search_items'               => __( 'Search Seasons', 'startup-cpt-catalog' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-catalog' )
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false
	);
	register_taxonomy( 'catalog-season', array( 'catalog' ), $args );

}

add_action( 'init', 'startup_cpt_catalog_season', 0 );

// Catalog types taxonomy
function startup_cpt_catalog_types() {
	$labels = array(
		'name'                       => _x( 'Catalog Types', 'Taxonomy General Name', 'startup-cpt-catalog' ),
		'singular_name'              => _x( 'Catalog Type', 'Taxonomy Singular Name', 'startup-cpt-catalog' ),
		'menu_name'                  => __( 'Types', 'startup-cpt-catalog' ),
		'all_items'                  => __( 'All Types', 'startup-cpt-catalog' ),
		'parent_item'                => __( 'Parent Type', 'startup-cpt-catalog' ),
		'parent_item_colon'          => __( 'Parent Type:', 'startup-cpt-catalog' ),
		'new_item_name'              => __( 'New Type Name', 'startup-cpt-catalog' ),
		'add_new_item'               => __( 'Add New Type', 'startup-cpt-catalog' ),
		'edit_item'                  => __( 'Edit Type', 'startup-cpt-catalog' ),
		'update_item'                => __( 'Update Type', 'startup-cpt-catalog' ),
		'view_item'                  => __( 'View Type', 'startup-cpt-catalog' ),
		'separate_items_with_commas' => __( 'Separate types with commas', 'startup-cpt-catalog' ),
		'add_or_remove_items'        => __( 'Add or remove types', 'startup-cpt-catalog' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-catalog' ),
		'popular_items'              => __( 'Popular Types', 'startup-cpt-catalog' ),
		'search_items'               => __( 'Search Types', 'startup-cpt-catalog' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-catalog' )
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false
	);
	register_taxonomy( 'catalog-type', array( 'catalog' ), $args );

}

add_action( 'init', 'startup_cpt_catalog_types', 0 );

// Catalog company taxonomy
function startup_cpt_catalog_company() {
	$labels = array(
		'name'                       => _x( 'Catalog Companies', 'Taxonomy General Name', 'startup-cpt-catalog' ),
		'singular_name'              => _x( 'Catalog Company', 'Taxonomy Singular Name', 'startup-cpt-catalog' ),
		'menu_name'                  => __( 'Companies', 'startup-cpt-catalog' ),
		'all_items'                  => __( 'All Companies', 'startup-cpt-catalog' ),
		'parent_item'                => __( 'Parent Company', 'startup-cpt-catalog' ),
		'parent_item_colon'          => __( 'Parent Company:', 'startup-cpt-catalog' ),
		'new_item_name'              => __( 'New Company Name', 'startup-cpt-catalog' ),
		'add_new_item'               => __( 'Add New Company', 'startup-cpt-catalog' ),
		'edit_item'                  => __( 'Edit Company', 'startup-cpt-catalog' ),
		'update_item'                => __( 'Update Company', 'startup-cpt-catalog' ),
		'view_item'                  => __( 'View Company', 'startup-cpt-catalog' ),
		'separate_items_with_commas' => __( 'Separate companies with commas', 'startup-cpt-catalog' ),
		'add_or_remove_items'        => __( 'Add or remove companies', 'startup-cpt-catalog' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-catalog' ),
		'popular_items'              => __( 'Popular Companies', 'startup-cpt-catalog' ),
		'search_items'               => __( 'Search Companies', 'startup-cpt-catalog' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-catalog' )
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false
	);
	register_taxonomy( 'catalog-company', array( 'catalog' ), $args );

}

add_action( 'init', 'startup_cpt_catalog_company', 0 );

// Catalog cities taxonomy
function startup_cpt_catalog_cities() {
	$labels = array(
		'name'                       => _x( 'Catalog Cities', 'Taxonomy General Name', 'startup-cpt-catalog' ),
		'singular_name'              => _x( 'Catalog City', 'Taxonomy Singular Name', 'startup-cpt-catalog' ),
		'menu_name'                  => __( 'Cities', 'startup-cpt-catalog' ),
		'all_items'                  => __( 'All Cities', 'startup-cpt-catalog' ),
		'parent_item'                => __( 'Parent City', 'startup-cpt-catalog' ),
		'parent_item_colon'          => __( 'Parent City:', 'startup-cpt-catalog' ),
		'new_item_name'              => __( 'New City Name', 'startup-cpt-catalog' ),
		'add_new_item'               => __( 'Add New City', 'startup-cpt-catalog' ),
		'edit_item'                  => __( 'Edit City', 'startup-cpt-catalog' ),
		'update_item'                => __( 'Update City', 'startup-cpt-catalog' ),
		'view_item'                  => __( 'View City', 'startup-cpt-catalog' ),
		'separate_items_with_commas' => __( 'Separate cities with commas', 'startup-cpt-catalog' ),
		'add_or_remove_items'        => __( 'Add or remove cities', 'startup-cpt-catalog' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-catalog' ),
		'popular_items'              => __( 'Popular Cities', 'startup-cpt-catalog' ),
		'search_items'               => __( 'Search Cities', 'startup-cpt-catalog' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-catalog' )
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false
	);
	register_taxonomy( 'catalog-city', array( 'catalog' ), $args );

}

add_action( 'init', 'startup_cpt_catalog_cities', 0 );

// Retirer la boite de la taxonomie sur le coté
function startup_cpt_catalog_categories_metabox_remove() {
	remove_meta_box( 'tagsdiv-catalog-type', 'catalog', 'side' );
    remove_meta_box( 'tagsdiv-catalog-company', 'catalog', 'side' );
    remove_meta_box( 'tagsdiv-catalog-season', 'catalog', 'side' );
    remove_meta_box( 'tagsdiv-catalog-city', 'catalog', 'side' );
    // tagsdiv-product_types pour les taxonomies type tags
    // custom_taxonomy_slugdiv pour les taxonomies type categories
}

add_action( 'admin_menu' , 'startup_cpt_catalog_categories_metabox_remove' );

// Metaboxes
function startup_cpt_catalog_meta() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_cpt_catalog_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Catalog Index Content', 'startup-cpt-catalog' ),
		'object_types'  => array( 'catalog' )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Main picture', 'startup-cpt-catalog' ),
//		'desc' => __( 'Main image of the menu, may be different from the thumbnail. i.e. 5-course diner', 'startup-cpt-menus' ),
		'id'   => $prefix . 'main_pic',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false
        )
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Short description', 'startup-cpt-catalog' ),
		'desc'       => __( 'i.e. "A journey on the River."', 'startup-cpt-catalog' ),
		'id'         => $prefix . 'short',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Content picture', 'startup-cpt-catalog' ),
		'desc' => __( 'The catalog item picture inside content.', 'startup-cpt-catalog' ),
		'id'   => $prefix . 'content-pic',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );
    
    // Pull all the menus into an array
    $args = array(
        'sort_order' => 'asc',
        'sort_column' => 'post_title',
        'hierarchical' => 0,
        'post_type'        => 'menus',
    ); 
    
	$menus = array();
	$menus_obj = get_posts( $args );
	foreach ($menus_obj as $menu) {
		$menus[$menu->ID] = $menu->post_title;
	}
    
    $cmb_box->add_field( array(
        'name'             => __( 'Menu', 'startup-cpt-catalog' ),
        'id'               => $prefix . 'menu',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => $menus
    ) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Description', 'startup-cpt-catalog' ),
		'id'   => $prefix . 'description',
		'type' => 'wysiwyg',
        'options' => array(
            'wpautop' => true, // use wpautop?
            'media_buttons' => false, // show insert/upload button(s)
            'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
            'tabindex' => '',
            'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
            'editor_class' => '', // add extra class(es) to the editor textarea
            'teeny' => false, // output the minimal editor config used in Press This
            'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Dates', 'startup-cpt-catalog' ),
		'id'   => $prefix . 'dates',
		'type' => 'wysiwyg',
        'options' => array(
            'wpautop' => true, // use wpautop?
            'media_buttons' => false, // show insert/upload button(s)
            'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
            'tabindex' => '',
            'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
            'editor_class' => '', // add extra class(es) to the editor textarea
            'teeny' => false, // output the minimal editor config used in Press This
            'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Cruise', 'startup-cpt-catalog' ),
		'id'   => $prefix . 'cruise',
		'type' => 'wysiwyg',
        'options' => array(
            'wpautop' => true, // use wpautop?
            'media_buttons' => false, // show insert/upload button(s)
            'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
            'tabindex' => '',
            'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
            'editor_class' => '', // add extra class(es) to the editor textarea
            'teeny' => false, // output the minimal editor config used in Press This
            'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Price', 'startup-cpt-catalog' ),
		'id'   => $prefix . 'price',
		'type' => 'wysiwyg',
        'options' => array(
            'wpautop' => true, // use wpautop?
            'media_buttons' => false, // show insert/upload button(s)
            'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
            'tabindex' => '',
            'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
            'editor_class' => '', // add extra class(es) to the editor textarea
            'teeny' => false, // output the minimal editor config used in Press This
            'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
        ),
	) );
    
    $cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox2',
		'title'         => __( 'Footer notes', 'startup-cpt-catalog' ),
		'object_types'  => array( 'catalog' )
	) );
    
    $cmb_box->add_field( array(
        'id'         => $prefix . 'notes',
		'type'       => 'textarea_small'
	) );

    $cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox3',
		'title'         => __( 'Details', 'startup-cpt-catalog' ),
		'object_types'  => array( 'catalog' ),
        'context'      => 'side',
		'priority'     => 'high',
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Season', 'startup-cpt-catalog' ),
		'id'       => $prefix . 'season',
		'type'     => 'taxonomy_multicheck',
        'select_all_button' => false,
		'taxonomy' => 'catalog-season', // Taxonomy Slug
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Type', 'startup-cpt-catalog' ),
		'id'       => $prefix . 'type',
		'type'     => 'taxonomy_multicheck',
        'select_all_button' => false,
		'taxonomy' => 'catalog-type', // Taxonomy Slug
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Company', 'startup-cpt-catalog' ),
		'id'       => $prefix . 'company',
		'type'     => 'taxonomy_multicheck',
        'select_all_button' => false,
		'taxonomy' => 'catalog-company', // Taxonomy Slug
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'City', 'startup-cpt-catalog' ),
		'id'       => $prefix . 'city',
		'type'     => 'taxonomy_multicheck',
        'select_all_button' => false,
		'taxonomy' => 'catalog-city', // Taxonomy Slug
	) );
}

add_action( 'cmb2_admin_init', 'startup_cpt_catalog_meta' );

// Shortcode
function startup_cpt_catalog_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => ''
        ), $atts);
    
	// Code
    ob_start();
    if ( function_exists( 'startup_reloaded_setup' ) || function_exists( 'startup_revolution_setup' ) ) {
        require get_template_directory() . '/template-parts/content-catalog.php';
     } else {
        echo 'You should install <a href="https://github.com/yozzi/startup-reloaded" target="_blank">StartUp Reloaded</a> or <a href="https://github.com/yozzi/startup-revolution" target="_blank">StartUp Revolution</a> theme to make things happen...';
     }
     return ob_get_clean();    
}
add_shortcode( 'catalog', 'startup_cpt_catalog_shortcode' );

// Shortcode UI
function startup_cpt_catalog_shortcode_ui() {

    shortcode_ui_register_for_shortcode(
        'catalog',
        array(
            'label' => esc_html__( 'Catalog', 'startup-cpt-catalog' ),
            'listItemImage' => 'dashicons-art',
            'attrs' => array(
                array(
                    'label' => esc_html__( 'Background', 'startup-cpt-catalog' ),
                    'attr'  => 'bg',
                    'type'  => 'color',
                ),
            ),
        )
    );
};

if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
    add_action( 'init', 'startup_cpt_catalog_shortcode_ui');
}

// Enqueue scripts and styles.
function startup_cpt_catalog_scripts() {
    wp_enqueue_style( 'startup-cpt-catalog-style', plugins_url( '/css/startup-cpt-catalog.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_catalog_scripts', 15 );
?>