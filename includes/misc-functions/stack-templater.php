<?php 
/**
 * Functions Stack Template
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */

 
/**
 * Function which return a template array for a stack
 * Parameter: Stack ID
 * Parameter: $args
 */
function mp_stack_template_array( $stack_id, $args = array() ){		
	
	//Set defaults for args		
	$args_defaults = array();
	
	//Get and parse args
	$args = wp_parse_args( $args, $args_defaults );
	
	//Set default for stack array
	$mp_stack_template_array = array();
		
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_key' => 'mp_stack_order_' . $stack_id,
		'orderby' => 'meta_value_num menu_order',
		'order' => 'ASC',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'mp_stacks',
				'field'    => 'id',
				'terms'    => array( $stack_id ),
				'operator' => 'IN'
			)
		)
	);	
		
	//Create new query for stacks
	$mp_stack_query = new WP_Query( apply_filters( 'mp_stacks_args', $mp_stacks_args ) );
	
	/*
	//This is a what a stack template array looks like 
	$mp_stack_template_array = array(
		'stack_title' => "My Test Stack",
		'stack_bricks' => array(
			'brick_1' => array(
				'brick_title' => "Brick's Title",
				'mp_stack_order_' . $stack_id => '1000',
								
				'other_normal_meta_key' => array( 
					'value' => $meta_value,
					'attachment' => false,
					'required_add_on' => 'mp_stacks_features' 
				),
				
				'repeater_meta_key' => array( 
					'value' => array(
						'key1' => array( 
							'value' => 'Key Value 1',
							'attachment' => false,
							'required_add_on' => 'mp_stacks_features' 
						),
						'key2' => array( 
							'value' => 'Key Value 2',
							'attachment' => false,
							'required_add_on' => 'mp_stacks_features' 
						),
					),
					'attachment' => false,
					'required_add_on' => 'mp_stacks_features' 
				)
			),
			'brick_2' => array(
				'brick_title' => "Brick's Title",
				'mp_stack_order_' . $stack_id => '1010',
								
				'other_normal_meta_data' => array( 
					'value' => $meta_value,
					'attachment' => false,
					'required_add_on' => 'mp_stacks_features' 
				)
			)
		)	
	);
	*/
	
	$term_exists = get_term_by('id', $stack_id, 'mp_stacks');
	
	//If this stack doesn't exist
	if (!$term_exists){
		//Do nothing
	}
	//If there are bricks in this stack
	elseif ( $mp_stack_query->have_posts() ) {
			
		//Build Brick Output
		$mp_stack_template_array = array(
			'stack_title' => get_the_title("New Stack Template"),
		);
		
		//Brick Counter		
		$brick_counter = 1;
		
		//Mp Stack Order
		$mp_stack_order = 1000;
			
		//For each brick in this stack
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
    		
			//Get this brick's post id
			$post_id = get_the_ID();
			
			//Build Default Brick Output
			$mp_stack_template_array['stack_bricks']['brick_' . $brick_counter] = array(
				'brick_title' => get_the_title( $post_id ),
				'mp_stack_order' => $mp_stack_order,
			);
			
			//Get all meta field keys for this brick
			$brick_meta_keys = get_post_custom_keys( $post_id ); 
			
			//reset required add-on
			$required_add_on = NULL;
						
			//Loop through all meta fields attached to this brick
			foreach ( $brick_meta_keys as $meta_key ){
				
				//If this is the stack order, we don't need to save it because we already have above
				if ( stripos( $meta_key, 'mp_stack_order' ) === false ){ 
				
					//Get the value of this meta key
					$meta_value = get_post_meta( $post_id, $meta_key, true );
					
					//If this is a repeater	
					if ( is_array( $meta_value ) ){
						
						$meta_value_array = array();
						$repeat_counter = 0;
						
						//Loop through each repeat in this repeater
						foreach( $meta_value as $repeat ){
														
							//Loop through each field in this repeat
							foreach( $repeat as $field_id => $field_value ){
							
								//Add this field_value_array to the parent's meta_value_array
								$meta_value_array['value'][$repeat_counter][$field_id] = array( 
									'value' => $field_value,
									'attachment' => false
								);								
								
							}
							
							//Increment Repeat Counter
							$repeat_counter = $repeat_counter+1;
						}

					}
					else{
					
						//Set up the standard meta_value_array
						$meta_value_array = array( 
							'value' => $meta_value,
							'attachment' => false
						);	
					}
					
					//Add post meta fields to the array for this brick
					$mp_stack_template_array['stack_bricks']['brick_' . $brick_counter][$meta_key] = $meta_value_array;
				}
				
			}
			
			//Increment brick counter
			$brick_counter = $brick_counter + 1;
			
			//Increment mp stack order
			$mp_stack_order = $mp_stack_order + 10;
			
		endwhile;
	
	}
	//If there aren't any bricks in this stack
	else{
		
		//Do Nothing here either.
		
	};
	
	//Reset query
	wp_reset_query();
	
	//Return
	return $mp_stack_template_array;	
}

/**
 * Function which duplicates a stack and all bricks within it
 * Parameter: Stack ID
 * Parameter: $args
 * Returns New Stack's ID
 */
function mp_stacks_duplicate_stack( $original_stack_id, $new_stack_name ){
	
	//Get the template array for the stack we want to duplicate 
	$mp_stack_template_array = mp_stack_template_array( $original_stack_id );
	
	//Create the new stack using the stack template and name
	$new_stack_id = mp_stacks_create_stack_from_template( $mp_stack_template_array, $new_stack_name );
			
	//Return the new stack id
	return $new_stack_id;
	
}

/**
 * Function which creates a new stack from a stack template array
 * Parameter: Stack Template Array
 * Parameter: Stack Name
 * Returns New Stack's ID
 */
function mp_stacks_create_stack_from_template( $mp_stack_template_array, $new_stack_name ){
	
	//Make new stack
	$new_stack_array = wp_insert_term(
		$new_stack_name, // the term 
		'mp_stacks', // the taxonomy
		array(
			'description'=> '',
			'slug' => wp_unique_term_slug( sanitize_title($new_stack_name), (object) array( 'parent' => 0, 'taxonomy' => 'mp_stacks' ) ),
		)
	);
	
	//The new stack's id
	$new_stack_id = $new_stack_array['term_id'];
	
	//Get the wp upload dir
	$wp_upload_dir = wp_upload_dir();
	
	//Loop through each brick in the original stack
	foreach ( $mp_stack_template_array['stack_bricks'] as $original_brick ){
					
		//New Brick Setup
		$new_brick = array(
		  'post_title'     => $original_brick['brick_title'],
		  'post_status'    => 'publish',
		  'post_type'      => 'mp_brick',
		);  
		
		//Create a new brick 
		$new_brick_id = wp_insert_post( $new_brick, true );
		
		//Apply the new Stack ID (Taxonomy Term) to this Brick (Post)
		wp_set_object_terms( $new_brick_id, $new_stack_id, 'mp_stacks' );
		
		//Loop through the meta in the original brick
		foreach ( $original_brick as $brick_meta_id => $brick_meta_value ){
			
			//Don't save the brick title as a meta field
			if ( $brick_meta_id != 'brick_title' ){
				
				//If this meta field is the stack order one
				if ( $brick_meta_id == 'mp_stack_order' ){
					
					//Set the Stack Order
					update_post_meta( $new_brick_id, 'mp_stack_order_' . $new_stack_id, $brick_meta_value );
					
				}
				//If this meta field is not the stack order one
				else{
					
					//If this is a repeater
					if ( is_array( $brick_meta_value['value'] ) ) {
						
						//Reset our checked meta variable
						$brick_meta_checked_value = array();
						
						$repeat_counter = 0;
						
						//Loop through each repeat in this repeater
						foreach( $brick_meta_value['value'] as $repeat ){
							
							//Loop through each field in this repeat
							foreach( $repeat as $field_id => $field_value_array ){
								
								//If this should be an imported attachment
								if ( isset( $field_value_array['attachment'] ) && $field_value_array['attachment'] ){
									$brick_meta_checked_value[$repeat_counter][$field_id] = mp_stack_check_value_for_attachment( $field_value_array['value'] );
								}
								else{
									$brick_meta_checked_value[$repeat_counter][$field_id] = $field_value_array['value'];
								}
								
							}
							
							$repeat_counter = $repeat_counter + 1;
							
						}
							
					}
					//If this is not a repeater
					else{
						//If this should be an imported attachment
						if ( isset( $brick_meta_value['attachment'] ) && $brick_meta_value['attachment'] ){
							$brick_meta_checked_value = mp_stack_check_value_for_attachment($brick_meta_value['value']);
						}
						else{
							$brick_meta_checked_value = $brick_meta_value['value'];
						}
						
					}//End of: If $brick_meta_value['value'] is not a repeater
					
					//Save the metadata to the new brick
					update_post_meta( $new_brick_id, $brick_meta_id, $brick_meta_checked_value );
						
				}
			}
			
		}
		
	}
	
	return $new_stack_id;
	
}

/**
 * Function which checks if a value is an attachment, and whether it needs to be created
 * Parameter: String - The value to check for an attachment
 * Returns String - The checked value - matches the incoming value
 */
function mp_stack_check_value_for_attachment( $meta_value ){
		
	//Get the wp upload dir
	$wp_upload_dir = wp_upload_dir();
	
	//Check if this attachment has been created or not	
	$attachment_already_created = mp_core_get_attachment_id_from_url( $wp_upload_dir['url'] . '/' . basename( $meta_value ) );
	
	//If this attachment has NOT already been created
	if ( !$attachment_already_created ){
			
		//Create this to be an attachment by taking it from the template folder
		
		if (false === ($creds = request_filesystem_credentials('', '', false, false) ) ) {

			// if we get here, then we don't have credentials yet,
			// but have just produced a form for the user to fill in, 
			// so stop processing for now
			
			return true; // stop the normal page form from displaying
		}
		
		//Now we have some credentials, try to get the wp_filesystem running
		if ( ! WP_Filesystem($creds) ) {
			// our credentials were no good, ask the user for them again
			request_filesystem_credentials('', '', true, false);
			return true;
		}
		
		//By this point, the $wp_filesystem global should be working, so let's use it get our plugin
		global $wp_filesystem;
												
		//Get the image
		$saved_file = $wp_filesystem->get_contents( $meta_value );
		
		$wp_upload_dir = wp_upload_dir();
			
		//Get the filename only of this attachment
		$attachment_path = parse_url( $meta_value, PHP_URL_PATH);
		$attachment_parts = pathinfo($attachment_path);
		
		//Move the image to the uploads directory
		$wp_filesystem->put_contents( $wp_upload_dir['path'] . '/' . $attachment_parts['basename'], $saved_file, FS_CHMOD_FILE);
			
		//Check filetype	
		$wp_filetype = wp_check_filetype($attachment_parts['basename'], NULL );
		
		//Create attachment array
		$attachment = array(
			'guid' => $wp_upload_dir['url'] . '/' . $attachment_parts['basename'], 
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => preg_replace( '/\.[^.]+$/', '', $attachment_parts['basename'] ),
			'post_content' => '',
			'post_status' => 'inherit'
		);
		
		$attach_id = wp_insert_attachment( $attachment, $wp_upload_dir['path'] . '/' . $attachment_parts['basename'] );
			
		// we must first include the image.php file
		// for the function wp_generate_attachment_metadata() to work
		
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		
		$attach_data = wp_generate_attachment_metadata( $attach_id, $wp_upload_dir['path'] . '/' . $attachment_parts['basename']  );
		
		wp_update_attachment_metadata( $attach_id, $attach_data );
														
	}//End of: If this attachment has NOT already been created
	
	//Tell the meta key where the new attachment is located. 
	//This works whether this is a new or old attachment - as the attachments are not overwritten so the URL should always be this:
	$meta_value = $wp_upload_dir['url'] . '/' . basename( $meta_value );
	
	return $meta_value;
							
}