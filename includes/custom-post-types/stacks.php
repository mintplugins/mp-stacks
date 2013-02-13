<?php
/**
 * Custom Post Types
 *
 * @package mp_stacks
 * @since mp_stacks 1.0
 */

/**
 * Stack Custom Post Type
 */
function mp_stacks_post_type() {
	
	if (mp_core_get_option( 'mp_stacks_settings_general',  'enable_disable' ) != 'disabled' ){
		$slide_labels =  apply_filters( 'mp_stacks_slide_labels', array(
			'name' 				=> 'Stacks',
			'singular_name' 	=> 'Stack Item',
			'add_new' 			=> __('Add New', 'mp_stacks'),
			'add_new_item' 		=> __('Add New Stack', 'mp_stacks'),
			'edit_item' 		=> __('Edit Stack', 'mp_stacks'),
			'new_item' 			=> __('New Stack', 'mp_stacks'),
			'all_items' 		=> __('All Stacks', 'mp_stacks'),
			'view_item' 		=> __('View Stacks', 'mp_stacks'),
			'search_items' 		=> __('Search Stacks', 'mp_stacks'),
			'not_found' 		=>  __('No Stacks found', 'mp_stacks'),
			'not_found_in_trash'=> __('No Stacks found in Trash', 'mp_stacks'), 
			'parent_item_colon' => '',
			'menu_name' 		=> __('Stacks', 'mp_stacks')
		) );
		
			
		$slide_args = array(
			'labels' 			=> $slide_labels,
			'public' 			=> true,
			'publicly_queryable'=> true,
			'show_ui' 			=> true, 
			'show_in_menu' 		=> true, 
			'menu_position'		=> 5,
			'query_var' 		=> true,
			'rewrite' 			=> array( 'slug' => 'stack' ),
			'capability_type' 	=> 'post',
			'has_archive' 		=> true, 
			'hierarchical' 		=> false,
			'supports' 			=> apply_filters('mp_stacks_slide_supports', array( 'title', 'editor' ) ),
		); 
		register_post_type( 'mp_stack', apply_filters( 'mp_stacks_slide_post_type_args', $slide_args ) );
	}
}
add_action( 'init', 'mp_stacks_post_type', 100 );

/**
 * Stack Taxonomy
 */
 
 /**
 * Stack Cat taxonomy
 */
function mp_stacks_taxonomy() {  
	if (mp_core_get_option( 'mp_stacks_settings_general',  'enable_disable' ) != 'disabled' ){
		
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'                => __( 'Stack Groups', 'mp_core' ),
			'singular_name'       => __( 'Stack Group', 'mp_core' ),
			'search_items'        => __( 'Search Stack Groups', 'mp_core' ),
			'all_items'           => __( 'All Stack Groups', 'mp_core' ),
			'parent_item'         => __( 'Parent Stack Group', 'mp_core' ),
			'parent_item_colon'   => __( 'Parent Stack Group:', 'mp_core' ),
			'edit_item'           => __( 'Edit Stack Group', 'mp_core' ), 
			'update_item'         => __( 'Update Stack Group', 'mp_core' ),
			'add_new_item'        => __( 'Add New Stack Group', 'mp_core' ),
			'new_item_name'       => __( 'New Stack Group Name', 'mp_core' ),
			'menu_name'           => __( 'Stack Group', 'mp_core' ),
		); 	
  
		register_taxonomy(  
			'mp_stack_groups',  
			'mp_stack',  
			array(  
				'hierarchical' => true,  
				'label' => 'Stack Groups',  
				'labels' => $labels,  
				'query_var' => true,  
				'with_front' => false, 
				'rewrite' => array('slug' => 'stacks')  
			)  
		);  
	}
}  
add_action( 'init', 'mp_stacks_taxonomy' );  