<?php
/*
Plugin Name: StartUp CPT Home
Description: Le plugin pour activer le Custom Post Home
Author: Yann Caplain
Version: 1.0.0
Text Domain: startup-cpt-home
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
function startup_cpt_home_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-home',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-home',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-home/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-home',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-home/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

//add_action( 'init', 'startup_cpt_home_updater' );

//CPT
function startup_cpt_home() {
	$labels = array(
        'name'                => _x( 'Home sections', 'Post Type General Name', 'startup-cpt-home' ),
		'singular_name'       => _x( 'Home section', 'Post Type Singular Name', 'startup-cpt-home' ),
		'menu_name'           => __( 'Home', 'startup-cpt-home' ),
		'name_admin_bar'      => __( 'Home', 'startup-cpt-home' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-home' ),
		'all_items'           => __( 'All Items', 'startup-cpt-home' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-home' ),
		'add_new'             => __( 'Add New', 'startup-cpt-home' ),
		'new_item'            => __( 'New Item', 'startup-cpt-home' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-home' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-home' ),
		'view_item'           => __( 'View Item', 'startup-cpt-home' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-home' ),
		'not_found'           => __( 'Not found', 'startup-cpt-home' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-home' )
	);
	$args = array(
        'label'               => __( 'home', 'startup-cpt-home' ),
        'description'         => __( '', 'startup-cpt-home' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-grid-view',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
        'capability_type'     => array('home_section','home_sections'),
        'map_meta_cap'        => true
	);
	register_post_type( 'home', $args );

}

add_action( 'init', 'startup_cpt_home', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_cpt_home_rewrite_flush() {
    startup_cpt_home();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_cpt_home_rewrite_flush' );

// Capabilities
function startup_cpt_home_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_home_section' );
	$role_admin->add_cap( 'read_home_section' );
	$role_admin->add_cap( 'delete_home_section' );
	$role_admin->add_cap( 'edit_others_home_sections' );
	$role_admin->add_cap( 'publish_home_sections' );
	$role_admin->add_cap( 'edit_home_sections' );
	$role_admin->add_cap( 'read_private_home_sections' );
	$role_admin->add_cap( 'delete_home_sections' );
	$role_admin->add_cap( 'delete_private_home_sections' );
	$role_admin->add_cap( 'delete_published_home_sections' );
	$role_admin->add_cap( 'delete_others_home_sections' );
	$role_admin->add_cap( 'edit_private_home_sections' );
	$role_admin->add_cap( 'edit_published_home_sections' );
}

register_activation_hook( __FILE__, 'startup_cpt_home_caps' );

// Metaboxes
function startup_cpt_home_meta() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_cpt_home_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Home section details', 'startup-cpt-home' ),
		'object_types'  => array( 'home' )
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Display title', 'startup-cpt-home' ),
		'id'               => $prefix . 'title',
		'type'             => 'checkbox'
	) );
        
    $cmb_box->add_field( array(
		'name'       => __( 'Button text', 'startup-cpt-home' ),
		'id'         => $prefix . 'button_text',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button url', 'startup-cpt-home' ),
		'id'         => $prefix . 'button_url',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Button target', 'startup-cpt-home' ),
        'desc'             => __( '_blank', 'startup-cpt-home' ),
		'id'               => $prefix . 'blank',
		'type'             => 'checkbox'
	) );
}

add_action( 'cmb2_admin_init', 'startup_cpt_home_meta' );

// Shortcode
function startup_cpt_home_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => '',
            'id' => ''
        ), $atts);
    
	// Code
    ob_start();
    if ( function_exists( 'startup_reloaded_setup' ) || function_exists( 'startup_revolution_setup' ) ) {
        require get_template_directory() . '/template-parts/content-home.php';
     } else {
        echo 'You should install <a href="https://github.com/yozzi/startup-reloaded" target="_blank">StartUp Reloaded</a> or <a href="https://github.com/yozzi/startup-revolution" target="_blank">StartUp Revolution</a> theme to make things happen...';
     }
     return ob_get_clean();       
}
add_shortcode( 'home', 'startup_cpt_home_shortcode' );

// Shortcode UI
function startup_cpt_home_shortcode_ui() {

    shortcode_ui_register_for_shortcode(
        'home',
        array(
            'label' => esc_html__( 'Home', 'startup-cpt-home' ),
            'listItemImage' => 'dashicons-grid-view',
            'attrs' => array(
                array(
                    'label' => esc_html__( 'Background', 'startup-cpt-home' ),
                    'attr'  => 'bg',
                    'type'  => 'color',
                ),
                array(
                    'label'       => esc_html__( 'ID', 'startup-cpt-home' ),
                    'attr'        => 'id',
					'type' => 'post_select',
					'query' => array( 'post_type' => 'home' ),
					'multiple' => false,
                ),
            ),
        )
    );
};

if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
    add_action( 'init', 'startup_cpt_home_shortcode_ui');
}

// Enqueue scripts and styles.
function startup_cpt_home_scripts() {
    wp_enqueue_style( 'startup-cpt-home-style', plugins_url( '/css/startup-cpt-home.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_home_scripts', 15 );
?>