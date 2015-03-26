<?php 
/**
 * Functions for backwards compatibility
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Update any "media_types" to "content_types" - Deprecated
 *
 * @access   public
 * @since     1.0.0
 * @link      http://mintplugins.com/doc/
 * @see      function_name()
 * @return    void
 */
function mp_stacks_migrate_text_to_repeaters(){
 	
	//If this hasn't been done before
	if (!get_option('mp_stacks_migrate_text_to_repeaters')){
		
		//Set the args for the new query
		$mp_brick_args = array(
			'post_type' => "mp_brick",
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);	
			
		//Create new query for stacks
		$mp_brick_query = new WP_Query( apply_filters( 'mp_brick_args', $mp_brick_args ) );
		
		$css_output = NULL;
		
		//Loop through all bricks
		if ( $mp_brick_query->have_posts() ) { 
			
			add_action('admin_notices', 'mp_stacks_migrate_text_to_repeaters_notification');
			
			while( $mp_brick_query->have_posts() ) : $mp_brick_query->the_post(); 
						
				//Build Brick CSS Output
				$post_id = get_the_ID();
								
				//Get Text Area 1's Variables
				$brick_line_1_color = get_post_meta( $post_id, 'brick_line_1_color', true);
				$brick_line_1_font_size = get_post_meta( $post_id, 'brick_line_1_font_size', true);
				$brick_text_line_1 = get_post_meta( $post_id, 'brick_text_line_1', true);
				
				//We'll handle the migration for the google fonts addon here too
				$brick_line_1_google_font = get_post_meta( $post_id, 'brick_line_1_google_font', true);
				
				//Get Text Area 2's Variables
				$brick_line_2_color = get_post_meta( $post_id, 'brick_line_2_color', true);
				$brick_line_2_font_size = get_post_meta( $post_id, 'brick_line_2_font_size', true);
				$brick_text_line_2 = get_post_meta( $post_id, 'brick_text_line_2', true);
				
				//We'll handle the migration for the google fonts addon here too
				$brick_line_2_google_font = get_post_meta( $post_id, 'brick_line_2_google_font', true);
				
				//Complile all the text area variables into a repeater-style array
				$new_brick_repeater_array = array(
					array( 
						'brick_line_1_color' => $brick_line_1_color,
						'brick_line_1_font_size' => $brick_line_1_font_size,
						'brick_line_1_google_font' => $brick_line_1_google_font,
						'brick_text_line_1' => $brick_text_line_1,
						
						'brick_line_2_color' => $brick_line_2_color,
						'brick_line_2_font_size' => $brick_line_2_font_size,
						'brick_line_2_google_font' => $brick_line_2_google_font,
						'brick_text_line_2' => $brick_text_line_2,
					)
				);
						
				//Do the actual post meta update for this brick's text
				if (!empty($new_brick_repeater_array)){
					update_post_meta( $post_id, 'mp_stacks_text_content_type_repeater', $new_brick_repeater_array);
				}
								
							
			endwhile;
									
		}
		
		//Let this function know we don't have to do this again
		update_option( 'mp_stacks_migrate_text_to_repeaters', true );
		
	}
	
}
add_action('admin_init', 'mp_stacks_migrate_text_to_repeaters');

/**
 * Notice about text updates
 *
 * @access   public
 * @since     1.0.0
 * @link      http://mintplugins.com/doc/
 * @see      function_name()
 * @return    void
 */
function mp_stacks_migrate_text_to_repeaters_notification(){
	echo ' <div class="updated">';
	echo "<p>Migrating text types to repeaters...</p>";	
	echo '</div>';
}

 /**
 * Update any "media_types" to "content_types" - Deprecated
 *
 * @access   public
 * @since     1.0.0
 * @link      http://mintplugins.com/doc/
 * @see      function_name()
 * @return    void
 */
function mp_stacks_migrate_media_to_content(){
 	
	//If this hasn't been done before
	if (!get_option('mp_stacks_migrate_media_to_content')){
		
		//Set the args for the new query
		$mp_brick_args = array(
			'post_type' => "mp_brick",
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);	
			
		//Create new query for stacks
		$mp_brick_query = new WP_Query( apply_filters( 'mp_brick_args', $mp_brick_args ) );
		
		$css_output = NULL;
		
		//Loop through all bricks
		if ( $mp_brick_query->have_posts() ) { 
			
			add_action('admin_notices', 'mp_stacks_migrate_media_to_content_notification');
			
			while( $mp_brick_query->have_posts() ) : $mp_brick_query->the_post(); 
						
				//Build Brick CSS Output
				$post_id = get_the_ID();
								
				//Get media types
				$first_media_type = get_post_meta( $post_id, 'brick_first_media_type', true);
				$second_media_type = get_post_meta( $post_id, 'brick_second_media_type', true);
				
				//Reset media types to content types
				if (!empty($first_media_type)){
					update_post_meta( $post_id, 'brick_first_content_type', $first_media_type);
				}
				if (!empty($second_media_type)){
					update_post_meta( $post_id, 'brick_second_content_type', $second_media_type);
				}
								
							
			endwhile;
									
		}
		
		//Let this function know we don't have to do this again
		update_option( 'mp_stacks_migrate_media_to_content', true );
	}
	
}
add_action('admin_init', 'mp_stacks_migrate_media_to_content');

/**
 * Notice about updates
 *
 * @access   public
 * @since     1.0.0
 * @link      http://mintplugins.com/doc/
 * @see      function_name()
 * @return    void
 */
function mp_stacks_migrate_media_to_content_notification(){
	echo ' <div class="updated">';
	echo "Migrating media types to content types...";	
	echo '</div>';
}

/**
 * Update all bricks to reflect Stack Id Orders instead of Menu-Order - Deprecated
 *
 * @access   public
 * @since     1.0.0
 * @link      http://mintplugins.com/doc/
 * @see      function_name()
 * @return    void
 */
function mp_stacks_migrate_menu_order_to_stack_id_order(){
 	
	//If this hasn't been done before
	if (!get_option('mp_stacks_migrate_menu_order_over_to_stack_id_order')){
				
		//Set the args for the new query
		$mp_brick_args = array(
			'post_type' => "mp_brick",
			'posts_per_page' => -1,
		);	
			
		//Create new query for stacks
		$mp_brick_query = new WP_Query( apply_filters( 'mp_brick_args', $mp_brick_args ) );
		
		$css_output = NULL;
		
		//Loop through all bricks
		if ( $mp_brick_query->have_posts() ) { 
			
			add_action('admin_notices', 'mp_stacks_migrate_menu_order_to_stack_id_order_notification');
			
			while( $mp_brick_query->have_posts() ) : $mp_brick_query->the_post(); 
						
				//Build Brick CSS Output
				$post_id = get_the_ID();
								
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
						
						//Update mp_stack_order_ for each stack attached to each brick
						update_post_meta( $post_id, 'mp_stack_order_' . $mp_stack_id, $mp_stack_order_value );
					
					}
												
				}
								
							
			endwhile;
									
		}
		
		//Let this function know we don't have to do this again
		update_option( 'mp_stacks_migrate_menu_order_over_to_stack_id_order', true );
	}
	
}
add_action('admin_init', 'mp_stacks_migrate_menu_order_to_stack_id_order');

/**
 * Notice about updates
 *
 * @access   public
 * @since     1.0.0
 * @link      http://mintplugins.com/doc/
 * @see      function_name()
 * @return    void
 */
function mp_stacks_migrate_menu_order_to_stack_id_order_notification(){
	echo ' <div class="updated">';
	echo "Migrating Stack Orders...Double check your Stack Orders";	
	echo '</div>';
}


/**
 * Deprecated. Output css for all bricks on this page into the footer of the theme
 * Parameter: none
 * Global Variable: array $mp_bricks_on_page This array contains all the ids of every brick previously called on this page
 */
function mp_stacks_footer_css(){
	
	//Get all the stack ids used on this page			
	global $mp_bricks_on_page;
	
	if ( !empty( $mp_bricks_on_page ) ){
		
		echo '<style type="text/css">';
		
		//Show css for each
		foreach ( $mp_bricks_on_page as $brick_id ){
			echo mp_brick_css( $brick_id, 'footer_css' ); 
		}
		
		echo '</style>';
		
		//If there is at least 1 brick on this page, enqueue the stuff we need
		if ( isset( $brick_id ) ){
						
			//Enqueue hook for add-on scripts 
			do_action ( 'mp_stacks_enqueue_scripts', $mp_bricks_on_page );
			
		}
	}
		
}
//add_action( 'wp_footer', 'mp_stacks_footer_css'); 