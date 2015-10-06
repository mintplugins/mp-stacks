<?php
/**
 * This file contains functions which allow mp_brick posts in WordPress Admin to be re-ordered by dragging and dropping
 *
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
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
			
	}
	
}
add_action( 'admin_enqueue_scripts', 'mp_stacks_admin_enqueue_reorder_scripts' );

/**
 * If Menu Order URL variable is set, Make a new field for it on edit pages
 *
 * @since   1.0.0
 * @link    http://mintplugins.com/doc/
 * @return  array $plugin_array
 */
function mp_stacks_display_brick_mp_stack_order_input_field() {
	
	global $post;
		
	if ( $post->post_type == 'mp_brick' ){
		
		//When a new brick is being saved.
		if ( isset($_GET['mp_stack_order_new']) && isset($_GET['mp_stack_id']) ){
					
			// Set up nonce for verification
			wp_nonce_field( plugin_basename( __FILE__ ), 'mp_stacks_mp_stack_order_nonce' );	
						
			//Create a field for the new brick's stack and its order position within that stack
			echo '<input type="hidden" class="mp_stack_order" name="mp_stack_order[' . $_GET['mp_stack_id'] . ']" value="' . $_GET['mp_stack_order_new'] . '">';
										
		}
		//When editing an existing brick. I'm leaving this here so I can check the order by inspecting the source code for troubleshooting purposes for now.
		//Notice the field has no "name" attribute and no nonce.
		elseif( isset( $_GET['post'] ) && isset( $_GET['mp_stack_id'] ) ){
			
			$post_id = 	$_GET['post'];
			$stack_id = $_GET['mp_stack_id'];
			$stack_order = mp_core_get_post_meta( $post_id, 'mp_stack_order_' . $stack_id );
									
			//Create a field for the new brick's stack and its order position within that stack
			echo '<input type="hidden" class="mp_stack_order" value="' . $stack_order . '">';
		}
	
	}
	
}
add_action( 'edit_form_top', 'mp_stacks_display_brick_mp_stack_order_input_field' );

/**
 * Set Menu Order of Bricks upon save
 *
 * @since   1.0.0
 * @link    http://mintplugins.com/doc/
 * @return  array $plugin_array
 */
function mp_stacks_save_brick_mp_stack_order( $post_id ) {
		
	//This is only run if this is a new brick
	if ( isset( $_POST['mp_stack_order'] ) && isset( $_POST['mp_stacks_mp_stack_order_nonce'] ) ){
			
		if ( ! wp_verify_nonce( $_POST['mp_stacks_mp_stack_order_nonce'], plugin_basename( __FILE__ ) )  ) {
		
			//We won't kill the page, but we at least will only run the function if the nonce passes
			// die( 'Security check' ); 
		
		} else {
		
			global $wpdb;
			
			//Save this posts's order for each stack (this will only be 1 Stack here and 1 loop)
			foreach($_POST['mp_stack_order'] as $mp_stack_id => $mp_stack_order_value){
				
				if ( !empty( $mp_stack_id ) ){
					update_post_meta( $post_id, 'mp_stack_order_' . $mp_stack_id, $mp_stack_order_value );
					
					//This custom meta value for the mp_stack_id was added in Version 1.0.2.9
					update_post_meta( $post_id, 'mp_stack_id', $mp_stack_id );
				}
			}
			
			//Make sure this new brick is set to be published - Firefox was saving them as drafts for some reason...
			if ( get_post_status( $post_id ) != 'publish' ){
				
				//If it's not, Publish this post (brick)
				$this_new_brick = array(
					'ID'           => $post_id,
					'post_status' => 'publish'
				);
				wp_update_post( $this_new_brick );	 
			}
			
			//Reset the order for all Bricks in this Stack so they sit 10 number apart. This guarantees the order is always correct.
			
			//Set the args for the new query
			$mp_stacks_args = array(
				'post_type' => "mp_brick",
				'posts_per_page' => -1,
				'meta_key' => 'mp_stack_order_' . $mp_stack_id,
				'orderby' => 'meta_value_num',
				'order' => 'ASC',
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'mp_stacks',
						'field'    => 'id',
						'terms'    => array( $mp_stack_id ),
						'operator' => 'IN'
					)
				)
			);	
		
			//Create new query for stacks
			$mp_stack_query = new WP_Query( apply_filters( 'mp_stacks_args', $mp_stacks_args ) );
			
			//Start Brick order at 1000
			$brick_counter = 1000;
			
			//Loop through all bricks
			if ( $mp_stack_query->have_posts() ) { 
				
				while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
				
					$brick_id = get_the_ID(); 
										
					//Add 10 to the order so this brick is 10 greater than the previous brick
					update_post_meta( $brick_id, 'mp_stack_order_' . $mp_stack_id, $brick_counter );
					
					//Add 10 to the order so this brick is 10 greater than the previous brick
					$brick_counter = $brick_counter + 10;
				
				endwhile;
				
			}
			
		}
		
	}
}
add_action( 'save_post', 'mp_stacks_save_brick_mp_stack_order', 11 );

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