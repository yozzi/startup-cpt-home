<?php
/*
Plugin Name: StartUp Home
Description: Le plugin pour activer le Custom Post Home
Author: Yann Caplain
Version: 1.0.0
*/

//GitHub Plugin Updater
function startup_reloaded_home_updater() {
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

add_action( 'init', 'startup_reloaded_home_updater' );

//CPT
function startup_reloaded_home() {
	$labels = array(
		'name'                => 'Home sections',
		'singular_name'       => 'Home section',
		'menu_name'           => 'Home',
		'name_admin_bar'      => 'Home',
		'parent_item_colon'   => 'Parent Item:',
		'all_items'           => 'All Items',
		'add_new_item'        => 'Add New Item',
		'add_new'             => 'Add New',
		'new_item'            => 'New Item',
		'edit_item'           => 'Edit Item',
		'update_item'         => 'Update Item',
		'view_item'           => 'View Item',
		'search_items'        => 'Search Item',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash'
	);
	$args = array(
		'label'               => 'home',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 3,
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

add_action( 'init', 'startup_reloaded_home', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_reloaded_home_rewrite_flush() {
    startup_reloaded_home();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_reloaded_home_rewrite_flush' );

// Capabilities
function startup_reloaded_home_caps() {
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

register_activation_hook( __FILE__, 'startup_reloaded_home_caps' );

// Metaboxes

function startup_reloaded_home_meta() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_home_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Home section details', 'cmb2' ),
		'object_types'  => array( 'home' )
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Background color', 'cmb2' ),
        'id'      => $prefix . 'background_color',
        'type'    => 'colorpicker',
        'default' => '#fff'
    ) );
    

    
    $cmb_box->add_field( array(
		'name'       => __( 'Button text', 'cmb2' ),
		'id'         => $prefix . 'button_text',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button url', 'cmb2' ),
		'id'         => $prefix . 'button_url',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Button target', 'cmb2' ),
        'desc'             => __( '_blank', 'cmb2' ),
		'id'               => $prefix . 'blank',
		'type'             => 'checkbox'
	) );
}

add_action( 'cmb2_init', 'startup_reloaded_home_meta' );
?>