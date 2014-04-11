<?php 
/**
 * Functions Stack Template
 *
 * @link http://moveplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
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
	$args_defaults = array(
		
	);
	
	//Get and parse args
	$args = wp_parse_args( $args, $args_defaults );
	
	//Set default for stack array
	$mp_stack_template_array = array();
		
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
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
				
				'other_meta_data' => NULL
			),
			'brick_2' => array(
				'brick_title' => "Brick's Title",
				'mp_stack_order_' . $stack_id => '1010',
				
				'other_meta_data' => NULL
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
						
			//Loop through all meta fields attached to this brick
			foreach ( $brick_meta_keys as $meta_key ){
				
				//Add post meta fields to the array for this brick
				$mp_stack_template_array['stack_bricks']['brick_' . $brick_counter][$meta_key] = get_post_meta( $post_id, $meta_key, true );
				
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
 * Function which return an array containting all the content types used in a stack
 * Parameter: Stack ID
 * Parameter: $args
 */
function mp_stack_template_required_content_types( $stack_id ){

	//mail( 'johnstonphilip@gmail.com', 'Stack template', json_encode( $mp_stack_template_array ) );
	
	//Get the template array for this stack 
	$mp_stack_template_array = mp_stack_template_array($stack_id);
	
	//Set default for required content types array
	$required_content_types = array(); 
	
	//Loop through each brick
	foreach ( $mp_stack_template_array['stack_bricks'] as $brick ){
		
		//If this is not already in this array
		if(!in_array($brick['brick_first_content_type'], $required_content_types, true) && !empty($brick['brick_first_content_type']) ){
			
			//Add each content type for this brick to the required content types array
			array_push( $required_content_types, $brick['brick_first_content_type'] );
			
		}
		//If this is not already in this array
		if(!in_array($brick['brick_second_content_type'], $required_content_types, true) && !empty($brick['brick_second_content_type']) ){
		
			array_push( $required_content_types, $brick['brick_second_content_type'] );
		}
		
	}
	
	return $required_content_types;
	
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
					
					//Save the metadata to the new brick
					update_post_meta( $new_brick_id, $brick_meta_id, $brick_meta_value );
						
				}
			}
			
		}
		
	}
	
	return $new_stack_id;
	
}
