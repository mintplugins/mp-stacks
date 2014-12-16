<?php
/**
 * This page contains functions that create the mp-brick custom post type and the mp_stacks custom taxonomy
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Brick Custom Post Type
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      mp_core_get_option()
 * @see      register_post_type()
 * @param  	 void
 * @return   void
 */
function mp_brick_post_type() {

	$brick_labels =  apply_filters( 'mp_stacks_brick_labels', array(
		'name' 				=> 'Bricks',
		'singular_name' 	=> 'Brick Item',
		'add_new' 			=> __('Add New Brick', 'mp_stacks'),
		'add_new_item' 		=> __('Add New Brick', 'mp_stacks'),
		'edit_item' 		=> __('Edit Brick', 'mp_stacks'),
		'new_item' 			=> __('New Brick', 'mp_stacks'),
		'all_items' 		=> __('Manage Bricks', 'mp_stacks'),
		'view_item' 		=> __('View Bricks', 'mp_stacks'),
		'search_items' 		=> __('Search Bricks', 'mp_stacks'),
		'not_found' 		=>  __('No Bricks found', 'mp_stacks'),
		'not_found_in_trash'=> __('No Bricks found in Trash', 'mp_stacks'), 
		'parent_item_colon' => '',
		'menu_name' 		=> __('Stacks & Bricks', 'mp_stacks')
	) );
	
		
	$brick_args = array(
		'labels' 			=> $brick_labels,
		'public' 			=> false,
		'publicly_queryable'=> false,
		'show_ui' 			=> true, 
		'show_in_menu' 		=> false, 
		'query_var' 		=> true,
		'rewrite' 			=> array( 'slug' => 'stack' ),
		'capability_type' 	=> 'page',
		'has_archive' 		=> false, 
		'hierarchical' 		=> true,
		'supports' 			=> apply_filters('mp_stacks_brick_supports', array( 'title') ),
	); 
	register_post_type( 'mp_brick', apply_filters( 'mp_stacks_brick_post_type_args', $brick_args ) );

}
add_action( 'init', 'mp_brick_post_type', 1 );

/**
 * MP Stacks Custom Taxonomy
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      mp_core_get_option()
 * @see      register_taxonomy()
 * @param  	 void
 * @return   void
 */
function mp_stacks_taxonomy() {  
		
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'                => __( 'Manage Stacks', 'mp_core' ),
		'singular_name'       => __( 'Stack', 'mp_core' ),
		'search_items'        => __( 'Search Stacks', 'mp_core' ),
		'all_items'           => __( 'All Stacks', 'mp_core' ),
		'parent_item'         => __( 'Parent Stack', 'mp_core' ),
		'parent_item_colon'   => __( 'Parent Stack:', 'mp_core' ),
		'edit_item'           => __( 'Edit Stack', 'mp_core' ), 
		'update_item'         => __( 'Update Stack', 'mp_core' ),
		'add_new_item'        => __( 'Add New Stack', 'mp_core' ),
		'new_item_name'       => __( 'New Stack Name', 'mp_core' ),
		'menu_name'           => __( 'Manage Stacks', 'mp_core' ),
	); 	

	register_taxonomy(  
		'mp_stacks',  
		'mp_brick',  
		array(  
			'hierarchical' => true,  
			'label' => 'Stacks',  
			'labels' => $labels,  
			'query_var' => true,  
			'with_front' => false, 
			'rewrite' => array('slug' => 'stacks')  
		)  
	);  
}  
add_action( 'after_setup_theme', 'mp_stacks_taxonomy', 1 ); 

/**
 * Change the titles of the columns on the "Manage Stacks" page
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param  	 $columns array The titles of each column
 * @return   $columns array The titles of each column
 */
function mp_stacks_custom_taxonomy_columns( $columns ){
	$columns['name'] = 'Stack Name';
	$columns['posts'] = 'Bricks';
	unset($columns['slug']);
	return $columns;
}
add_filter( 'manage_edit-mp_stacks_columns', 'mp_stacks_custom_taxonomy_columns' );

/**
 * Change which rows exist on the Manage Stacks page
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param  	 $columns array Which sortable columns exist
 * @return   $columns array Which sortable columns exist
 */
function mp_stacks_custom_taxonomy_sortable_columns( $columns ){
	unset($columns['slug']);
	return $columns;
}
add_filter( 'manage_edit-mp_stacks_sortable_columns', 'mp_stacks_custom_taxonomy_sortable_columns' );

/**
 * Add ability to filter bricks by stack on "list all bricks" page
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      mp_core_get_option()
 * @see      get_taxonomy()
 * @see      wp_dropdown_categories
 * @param  	 void
 * @return   void
 */
function mp_stacks_restrict_bricks_by_stack() {
	global $typenow;
	$post_type = 'mp_brick'; // change HERE
	$taxonomy = 'mp_stacks'; // change HERE
	if ($typenow == $post_type) {
		$selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy' => $taxonomy,
			'name' => $taxonomy,
			'orderby' => 'name',
			'selected' => $selected,
			'show_count' => true,
			'hide_empty' => true,
		));
	};
}
add_action('restrict_manage_posts', 'mp_stacks_restrict_bricks_by_stack');

/**
 * If the taxonomy in an MP Stacks query is using the ID, change it to use the term
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      get_term_by()
 * @param  	 $query 
 * @return   void
 */
function mp_stacks_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'mp_brick'; // change HERE
	$taxonomy = 'mp_stacks'; // change HERE
	$q_vars = &$query->query_vars;
	if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}
add_filter('parse_query', 'mp_stacks_convert_id_to_term_in_query');

/**
 * Modify the Stack Titles' links on the Manage Stacks Page
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param 	 string $location    The edit link.
 * @param    int    $term_id     Term ID.
 * @param    string $taxonomy    Taxonomy name.
 * @param    string $object_type The object type (eg. the post type).
 * @return   string $location    The edit link.
 */
function mp_stacks_edit_taxonomy_url_for_stacks( $location, $term_id, $taxonomy, $object_type ){
	
	//If this is an mp_stacks taxonomy term
	if ( $taxonomy == 'mp_stacks' ){
		return add_query_arg( array('page' => 'mp-stacks-single-stack-edit-page', 'stack_id' => $term_id ), admin_url('admin.php') );	
	}
	
	return $location;
	
}
add_filter( 'get_edit_term_link', 'mp_stacks_edit_taxonomy_url_for_stacks', 10, 4 );

/**
 * Modify the links that appear on the Manage Stacks page beneath the Stack Titles on the right.
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      add_query_arg()
 * @param  	 $actions 
 * @param  	 $tag 
 * @return   $actions
 */
function mp_stacks_remove_quick_edit( $actions, $tag ) {
		
	unset($actions['inline hide-if-no-js']);
	
	$actions['view'] = '<a href="' . add_query_arg( array('mp_stacks' => $tag->term_id), 'edit.php?post_type=mp_brick' ) . '">' . __("Edit this Stack's Bricks", 'mp_stacks') . '</a>';
	
	//Make the "Manage Stacks" page's "view" links to lists of bricks-per-stack instead of the front end archive page
	$actions['edit'] = '<a href="' . add_query_arg( array('page' => 'mp-stacks-single-stack-edit-page', 'stack_id' => $tag->term_id ), admin_url('admin.php') ) . '">' . __("Edit", 'mp_stacks') . '</a>';

	return $actions;
}
add_filter('mp_stacks_row_actions','mp_stacks_remove_quick_edit',10,2);

/**
 * Hide Permalink output on single edit screen
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param  	 $return 
 * @param  	 $id 
 * @param  	 $new_title 
 * @param  	 $new_slug 
 * @return   $actions
 */
function mp_stacks_perm($return, $id, $new_title, $new_slug){
	global $post;
	if (isset($post->post_type)){
		if($post->post_type == 'mp_brick'){
			
			$return = NULL;
			
		}
	}

	return $return;
}
add_filter('get_sample_permalink_html', 'mp_stacks_perm', '',4);

/**
 * Sort bricks on admin pages by stack order
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param  	 $return 
 * @param  	 $query 
 * @return   $actions
 */
function mp_stacks_order_admin_bricks( $query ){
	
	global $current_screen;
    
	if( !is_admin() )
        return;
		
	$stack_id = !empty($_GET['mp_stacks']) ? $_GET['mp_stacks'] : false;
	
	if ( !$stack_id )
		return;
	
	if (function_exists('get_current_screen')){
    	$screen = get_current_screen();
	}
	else{
		$screen = $current_screen;
	}
	
	if (isset($screen->base) && isset($screen->post_type) ){
		if( 'edit' == $screen->base && 'mp_brick' == $screen->post_type && !isset( $_GET['orderby'] ) ){
			$query->set( 'meta_key', 'mp_stack_order_' . $stack_id );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'ASC' );
		}
	}
}
add_action( 'pre_get_posts', 'mp_stacks_order_admin_bricks' );