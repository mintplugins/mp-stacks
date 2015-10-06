<?php
/**
 * This file contains various functions
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
 * Ajax callback for updating a brick
 *
 * @since    1.0.0
 * @link     http://codex.wordpress.org/Function_Reference/add_editor_style
 * @see      get_bloginfo()
 * @param    array $wp See link for description.
 * @return   void
 */
function mp_stacks_update_brick() {	
	
	global $wp_scripts;
	
	$brick_id = sanitize_text_field( $_POST['mp_stacks_update_brick_id'] );
	$brick_css = mp_brick_css( $brick_id );
	$brick_html = mp_brick( $brick_id );
	
	//Remove emojis from the enqueued scripts and styles for this ajax call.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	
	//Get the needed css stylesheets
	ob_start();
	wp_print_styles();
	$footer_css_string = ob_get_clean();
	
	//Explode the styles so we can get the url of each stylesheet
	$footer_css_explode = explode( "href='", $footer_css_string );
	foreach( $footer_css_explode as $style_id_chunk ){
		$temp_styles_explode_holder = explode( "'", $style_id_chunk );
		$footer_styles_array[]  = $temp_styles_explode_holder[0];
	}
	unset( $footer_styles_array[0] );
	
	//Get the needed js scripts
	ob_start();
	wp_print_scripts();
	$footer_scripts_string = ob_get_clean();
	
	//Explode the footer scripts so we can get the actual url for each one
	$footer_scripts_explode = explode( "<script type='text/javascript' src='", $footer_scripts_string );
	foreach( $footer_scripts_explode as $script_url_chunk ){
		$temp_explode_holder = explode( "'", $script_url_chunk );
		$footer_scripts_array[] = htmlspecialchars_decode( $temp_explode_holder[0] );
	}
	unset( $footer_scripts_array[0] );
	
	//Create the array that will be returned to the front-end js
	$return_array['success'] = true;
	$return_array['success_type'] = !empty( $brick_html ) ? 'brick_updated' : 'brick_deleted';
	$return_array['brick_css'] = $brick_css ? $brick_css : '';
	$return_array['brick_html'] = $brick_html ? $brick_html : '';
	$return_array['brick_footer_enqueued_scripts_array'] = $footer_scripts_array;
	$return_array['brick_footer_inline_scripts_array'] = mp_stacks_get_inline_js();
	$return_array['brick_enqueued_css_styles_array'] = $footer_styles_array;
	$return_array['brick_inline_css_styles_array'] = mp_stacks_get_inline_css();
	
	echo json_encode( $return_array );
	die();
			
}
add_action( 'wp_ajax_mp_stacks_update_brick', 'mp_stacks_update_brick' );
add_action( 'wp_ajax_nopriv_mp_stacks_update_brick', 'mp_stacks_update_brick' );

/**
 * Ajax callback for Create New Stack button
 *
 * @since    1.0.0
 * @link     http://codex.wordpress.org/Function_Reference/add_editor_style
 * @see      get_bloginfo()
 * @param    array $wp See link for description.
 * @return   void
 */
function mp_stacks_add_new_stack_ajax() {	

	//Check nonce
	if ( !check_ajax_referer( 'mp-stacks-nonce-action-name', 'mp_stacks_nonce', false ) ){
		echo __('Ajax Security Check', 'mp_stacks');
		die();
	}

	$new_stack_name = $_POST['mp_stacks_new_stack_name'];
	$mp_stacks_new_stack_source_type = $_POST['mp_stacks_new_stack_source_type'];
	$new_stack_duplicate_id = $_POST['mp_stacks_new_stack_duplicate_id'];
	$new_stack_template_slug = $_POST['mp_stacks_new_stack_template_slug'];

	if( !empty( $new_stack_name ) && current_user_can('edit_theme_options') ) {
		
		//If the user wants to duplicate an existing stack
		if ( $mp_stacks_new_stack_source_type == 'duplicate-stack-option' ){
			
			//Duplicate the stack id passed to ajax
			$new_stack_id = mp_stacks_duplicate_stack( $new_stack_duplicate_id, $new_stack_name );
			
		}
		//If the user want to make a stack from a template
		else if ( $mp_stacks_new_stack_source_type == 'template-stack-option' ){
			$new_stack_id = mp_stacks_create_stack_from_template( $new_stack_template_slug(), $new_stack_name );
		}
		//If the user wants to make a blank stack
		else{
			//Make new stack
			$new_stack_array = wp_insert_term(
				$new_stack_name, // the term 
				'mp_stacks' // the taxonomy
			);
			
			//If there was an error making the stack, echo that error
			if ( is_wp_error( $new_stack_array ) ){
				$error_string = $new_stack_array->get_error_message();
   				echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
				die();
			}
			
			$new_stack_id = $new_stack_array['term_id'];
		}
		
		//Get the updated Stacks Table
		$wp_list_table = _get_list_table('WP_Terms_List_Table', array( 'screen' => 'edit-mp_stacks' ) );
		
		ob_start();
		
		$wp_list_table->display_rows_or_placeholder();
		
		$updated_stacks_table = ob_get_clean();
		
		echo json_encode( array(
			'new_stack_id' => $new_stack_id,
			'updated_stacks_table' => $updated_stacks_table
		) );
			
	}

	die(); // this is required to return a proper result
	
}
add_action( 'wp_ajax_mp_stacks_make_new_stack', 'mp_stacks_add_new_stack_ajax' );

/**
 * Ajax callback for links to Bricks in the TinyMCE Link Button
 *
 * @since    1.0.0
 * @param    void
 * @return   void
 */
function mp_stacks_link_to_bricks_ajax() {	

	//Check nonce
	if ( !check_ajax_referer( 'mp-stacks-nonce-action-name', 'mp_stacks_nonce', false ) ){
		echo __('Ajax Security Check', 'mp_stacks');
		die();
	}
	
	//If a stack id has been passed to the URL
	if ( isset( $_POST['mp_stack_id'] ) ){
				
		//Get all the brick titles in this stack
		$brick_titles_in_stack = mp_stacks_get_brick_titles_in_stack( $_POST['mp_stack_id'] );
		
	}
	else{
		
		$brick_titles_in_stack = array();
	}	
	
	ob_start(); 
	?>
	<p class="howto"><a href="#" id="wp-link-search-toggle"><?php echo __( 'Link to Bricks in this Stack', 'mp_stacks' ); ?></a></p>
    <div id="search-panel">
        <div id="most-recent-results" class="query-results" tabindex="0">
            <ul>
            	<?php
				foreach ( $brick_titles_in_stack as $brick_in_stack ){
					echo '<li class="mp-stacks-brick-url">' . $brick_in_stack . '</li>';
				}
				?>
            </ul>
        </div>
    </div>
    
	<?php
	
	echo json_encode( array( 'output' => ob_get_clean() ) ); 
	
	die();
	
}
add_action( 'wp_ajax_mp_stacks_link_to_bricks_ajax', 'mp_stacks_link_to_bricks_ajax' );

/**
 * Ajax callback Importing a Brick via Ajax
 *
 * @since    1.0.0
 * @param    void
 * @return   void
 */
function mp_stacks_import_brick_via_ajax() {	

	if ( !isset( $_POST['mp_brick_id'] ) ){
		echo json_encode( array(
			'error' => __( 'No Brick ID Passed', 'mp_stacks' )
		));
		die();
	}
	
	if ( !isset( $_POST['mp_brick_json_to_import'] ) ){
		echo json_encode( array(
			'error' => __( 'No Brick JSON Passed', 'mp_stacks' )
		));
		die();
	}
	
	//Check nonce
	if ( !check_ajax_referer( 'mp-stacks-nonce-action-name', 'mp_stacks_nonce', false ) ){
		echo __('Ajax Security Check', 'mp_stacks');
		die();
	}
	
	$success_array = array();
	
	//Decode the JSON provided by the user
	$mp_brick_settings = json_decode( stripslashes( $_POST['mp_brick_json_to_import'] ), true );
	
	//If we weren't able to decode the JSON correctly
	if ( empty( $mp_brick_settings ) ){
		echo json_encode( array(
			'error' => __( 'Error with Brick Code. No changes have been made. Please try again.', 'mp_stacks' )
		));
		die();
	}
	
	//Does this brick post exist yet?
	if ( get_post_status( $_POST['mp_brick_id'] ) != 'publish' ){
		
		//If we didn't get all the things we need to create this post
		if ( !isset( $_POST['mp_stack_id'] ) || !isset( $_POST['mp_stack_order'] ) ){
			echo json_encode( array(
				'error' => __( 'Error: No Stack ID or Order were passed. Please refresh and try again.', 'mp_stacks' )
			));
			die();
		}
		
		//If it's not, Publish this post (brick)
		$this_new_brick = array(
			'ID'          => $_POST['mp_brick_id'],
			'post_status' => 'publish'
		);
		wp_update_post( $this_new_brick );	 
		
		//Add the MP Stacks taxonomy term to this post (or "brick").
		wp_set_object_terms(  $_POST['mp_brick_id'], intval( $_POST['mp_stack_id'] ), 'mp_stacks' );
		//Make sure this new brick is in the right stack
		update_post_meta( $_POST['mp_brick_id'], 'mp_stack_order_' . $_POST['mp_stack_id'], $_POST['mp_stack_order'] );
		//This custom meta value for the mp_stack_id was added in Version 1.0.2.9
		update_post_meta( $_POST['mp_brick_id'], 'mp_stack_id', $_POST['mp_stack_id'] );
		
	}
	
	//Loop through each meta option provided for this brick
	foreach( $mp_brick_settings as $meta_key => $brick_meta_value ){
		
		//Sanitize the key
		$meta_key = sanitize_text_field( $meta_key );
		
		//If this is the stack order, we don't need to save it because it will be handled by the Stack
		if ( stripos( $meta_key, 'mp_stack_order' ) !== false ){ 
			//Make sure this new brick is in the right stack
			update_post_meta( $_POST['mp_brick_id'], 'mp_stack_order_' . $_POST['mp_stack_id'], $_POST['mp_stack_order'] );
		}
		else{
		
			//If this is a repeater, sanitize each value and key
			if ( is_array( $brick_meta_value['value'] ) ){
				
				//Reset our checked meta variable
				$brick_meta_checked_value = array();
				
				$repeat_counter = 0;
								
				//Loop through each repeat in this repeater
				foreach( $brick_meta_value['value'] as $repeat ){
					
					//Loop through each field in this repeat
					foreach( $repeat as $field_id => $field_value_array ){
						
						//If this should be an imported attachment
						if ( isset( $field_value_array['attachment'] ) && $field_value_array['attachment'] ){
							$brick_meta_checked_value[$repeat_counter][$field_id] = mp_stack_check_value_for_attachment( sanitize_text_field( $field_value_array['value'] ) );
						}
						else{
							$brick_meta_checked_value[$repeat_counter][$field_id] = sanitize_text_field( $field_value_array['value'] );
						}
						
					}
					
					$repeat_counter = $repeat_counter + 1;
					
				}
				
				
			}
			//If this is not a repeater
			else{
				
				//If this should be an imported attachment
				if ( isset( $brick_meta_value['attachment'] ) && $brick_meta_value['attachment'] ){
					$brick_meta_checked_value = mp_stack_check_value_for_attachment( sanitize_text_field( $brick_meta_value['value'] ));
				}
				else{
					$brick_meta_checked_value = isset( $brick_meta_value['value'] ) ? sanitize_text_field( $brick_meta_value['value'] ) : NULL;
				}
				
			}
		
		}
		
		//Add the meta key/value to the post	
		update_post_meta( $_POST['mp_brick_id'], $meta_key, $brick_meta_checked_value );
			 
	}
	
	//Make sure the MP Stack ID is correct
	update_post_meta( $_POST['mp_brick_id'], 'mp_stack_id', $_POST['mp_stack_id'] );
	
	$success_array['success'] = __( 'Brick Import Successful', 'mp_stacks' ) ;
	
	echo json_encode( $success_array );
	
	die();

}
add_action( 'wp_ajax_mp_stacks_import_brick_via_ajax', 'mp_stacks_import_brick_via_ajax' );