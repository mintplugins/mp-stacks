<?php
/**
 * This file contains functions which allow mp_brick posts in WordPress Admin to be re-ordered by dragging and dropping
 *
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Load scripts needed for re-ordering hierarchical posts in WP Admin
 *
 * @since    1.0.0
 * @see      get_current_screen()
 * @see      wp_enqueue_script()
 * @see      wp_enqueue_style()
 * @return   void
 */
function mp_stacks_admin_enqueue_reorder_scripts(){
	
	//Get current page
	$current_page = get_current_screen();
	
	//Only load if we are on an edit based page
	if ( $current_page->base == 'edit' ){
 			
		//Allows posts to be reordered by dragging and dropping if the 'mp_stack_order' column has been added to the post type
		wp_enqueue_script( 'mp-stacks-sortable-bricks-js', plugins_url( 'js/mp-stacks-sortable-bricks.js', dirname(__FILE__)),  array( 'jquery', 'jquery-ui-sortable') );
		
		//Style the 'mp_stack_order' column
		wp_enqueue_style( 'mp-stacks-sortable-bricks-css', plugins_url( 'css/mp-stacks-sortable-bricks.css', dirname(__FILE__) ) );
		
	}
	
}
add_action( 'admin_enqueue_scripts', 'mp_stacks_admin_enqueue_reorder_scripts' );

/**
 * If Menu Order URL variable is set, Make a new field for it on edit pages
 *
 * @since   1.0.0
 * @link    http://moveplugins.com/doc/
 * @return  array $plugin_array
 */
function mp_stacks_display_brick_mp_stack_order_input_field() {
	
	global $post;
		
	if ( $post->post_type == 'mp_brick' ){
				
		// Set up nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'mp_stacks_mp_stack_order_nonce' );	
				
		if ( isset($_GET['mp_stack_order_new']) && isset($_GET['mp_stack_id_new']) ){
							
			//Create a field for the new brick's stack and its order position within that stack
			echo '<input type="hidden" class="mp_stack_order" name="mp_stack_order[' . $_GET['mp_stack_id_new'] . ']" value="' . $_GET['mp_stack_order_new'] . '">';
										
		}
		else{
			
			//Get ids of all stacks this post is in
			$mp_stack_ids = wp_get_post_terms( get_the_ID(), 'mp_stacks', array("fields" => "ids") );
						
			if (!empty($mp_stack_ids)){
				//Loop through all stacks ids attached to this post
				foreach ($mp_stack_ids as $mp_stack_id){
					//Get the stack order saved for this stack to this post
					$mp_stack_order_array[$mp_stack_id] = get_post_meta( get_the_ID(), 'mp_stack_order_' . $mp_stack_id, true);	
					//If none have been saved, use 1000 as a starting number
					$mp_stack_order_array[$mp_stack_id] = !empty($mp_stack_order_array[$mp_stack_id]) ? $mp_stack_order_array[$mp_stack_id] : 1000;
				}
				
				//Loop through each stack_id => order_value
				foreach ($mp_stack_order_array as $mp_stack_id => $mp_stack_order_value){
					
					//Create a field for each stack and this post's order position within it
					echo '<input type="hidden" class="mp_stack_order" name="mp_stack_order[' . $mp_stack_id . ']" value="' . $mp_stack_order_value . '">';
				
				}
						
			}
					
		}
		
	}
	
}
add_action( 'edit_form_top', 'mp_stacks_display_brick_mp_stack_order_input_field' );

/**
 * Set Menu Order of Bricks upon save
 *
 * @since   1.0.0
 * @link    http://moveplugins.com/doc/
 * @return  array $plugin_array
 */
function mp_stacks_save_brick_mp_stack_order( $post_id ) {
	
	if (isset($_POST['mp_stack_order'] ) && isset( $_POST['mp_stacks_mp_stack_order_nonce'] ) ){
			
		if ( ! wp_verify_nonce( $_POST['mp_stacks_mp_stack_order_nonce'], plugin_basename( __FILE__ ) )  ) {
		
			 //We won't kill the page, but we at least will only run the function if the nonce passes
			 //die( 'Security check' ); 
		
		} else {
		
			global $wpdb;
			
			//Save this posts's order for each stack
			foreach($_POST['mp_stack_order'] as $mp_stack_id => $mp_stack_order_value){
				update_post_meta( $post_id, 'mp_stack_order_' . $mp_stack_id, $mp_stack_order_value );
			}
		}
		
	}
}
add_action( 'save_post', 'mp_stacks_save_brick_mp_stack_order', 100);

/**
 * Save new menu order for each post
 * When a post is reordered, this function fires to loop through each of the values in the GET variable with the prefix 'mp_order'
 * It then updates the post in the database
 *
 * @since    1.0.0
 * @see      wp_update_post()
 * @return   void
 */
function mp_stacks_reorder_posts_on_submit(){
	
	
	//Only do this if the mp_submitted_order field has been submitted
	if ( isset( $_GET['mp_submitted_order'] ) ){
					
		//No hooks are available to do a custom "nonce" check here as best we can!
		if ( strpos($_SERVER['HTTP_REFERER'], admin_url() ) === false ) {
			
			 //We can't kill the page, but we at least will only run the function if it was submitted from our current url
			 die( 'Security check' ); 	
			 
		}
		else{
			
			//Loop through each value in the GET variable
			foreach ($_GET as $key => $value) { 
				//If this value starts with 'mp_order'
				if ( strpos($key, 'mp_stack_order') !== false ){
					
					//Extract the post id 
					$exploded = explode( '_mp_brick_', $key );
					$post_id = $exploded[1];
					
					//Extract the stack id 
					$stack_id = explode( 'mp_stack_order_', $exploded[0] );
					$stack_id = $stack_id[1];
					
					update_post_meta( $post_id, 'mp_stack_order_' . $stack_id, $value );
				}
			}
		}
	}
}
add_action('admin_init', 'mp_stacks_reorder_posts_on_submit' );

/**
 * Add order column to admin listing screen for header text
 *
 * @since    1.0.0
 * @return   array This is passed to the manage_posts_columns filter and contains a new column with no title and a draggable area called 'mp_stack_order'
 */
function mp_stacks_add_new_post_column($columns) {
	
	$new_column = array('mp_stack_order' => '' );
	
	return array_merge( $new_column, $columns );
					
}

/**
 * show custom order column values
 *
 * @since    1.0.0
 * @global   array $post The global post variable in WP. Its the whole post object.
 * @param    array $name An array of column name ⇒ label
 * @return   void
*/
function mp_stacks_show_order_column($name){
   global $post;
  
  //Get this stack id
  $stack_id = !empty($_GET['mp_stacks']) ? $_GET['mp_stacks'] : 0;
  
  if ( !$stack_id )
		return;
  
  switch ($name) {
    case 'mp_stack_order':
      $order = get_post_meta( $post->ID, 'mp_stack_order_' . $stack_id, true);
	  $order = empty($order) || $order == 'NaN' ? 1000 : $order;
      echo '<input type="hidden" class="mp_stack_order_input" name="mp_stack_order_' . $stack_id . '_mp_brick_' . $post->ID . '" value="' . $order . '">';
	  echo '<input type="hidden" name="mp_submitted_order" value="true">';
	  echo '<div class="menu-order-drag-button"><img src="' . plugins_url( 'assets/images/grippy_large.png', dirname(dirname(__FILE__))) . '"/></div>';
      break;
   default:
      break;
   }
}

/**
 * Make mp_brick post types sortable
 *
 * @since    1.0.0
 * @return   void
*/
function mp_stacks_make_mp_brick_posts_sortable(){
	
		add_filter('manage_' . 'mp_brick' . '_posts_columns', 'mp_stacks_add_new_post_column');
		add_action('manage_' . 'mp_brick' . '_posts_custom_column','mp_stacks_show_order_column');
		
}
add_action( 'init', 'mp_stacks_make_mp_brick_posts_sortable' );

/**
* To make any post type sortable, use the code below and sub in your posttype
*/
//add_filter('manage_CUSTOMPOSTTYPE_posts_columns', 'mp_stacks_add_new_post_column');
//add_action('manage_CUSTOMPOSTTYPE_posts_custom_column','mp_stacks_show_order_column');