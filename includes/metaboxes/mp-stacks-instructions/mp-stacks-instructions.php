<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_instructions_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_instructions_add_meta_box = array(
		'metabox_id' => 'mp_stacks_instructions_metabox', 
		'metabox_title' => __( 'Stack Instructions', 'mp_stacks'), 
		'metabox_posttype' => 'mp_stack', 
		'metabox_context' => 'side', 
		'metabox_priority' => 'high' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_instructions_items_array = array(
		array(
			'field_id'			=> 'stack_url',
			'field_title' 	=> __( 'Stack Instructions', 'mp_stacks'),
			'field_description' 	=> '<br /> 1. Put the stack in a group. <br /><br /> 2. Put this shortcode on any page or post: [stack_group group="slug"] <br /><br /> 3. To find the slug for a group, click <a href="">here.</a>',
			'field_type' 	=> 'basictext',
			'field_value' => ''
		)
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_instructions_add_meta_box = has_filter('mp_stacks_instructions_meta_box_array') ? apply_filters( 'mp_stacks_instructions_meta_box_array', $mp_stacks_instructions_add_meta_box) : $mp_stacks_instructions_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_instructions_items_array = has_filter('mp_stacks_instructions_items_array') ? apply_filters( 'mp_stacks_instructions_items_array', $mp_stacks_instructions_items_array) : $mp_stacks_instructions_items_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_instructions_meta_box;
	$mp_stacks_instructions_meta_box = new MP_CORE_Metabox($mp_stacks_instructions_add_meta_box, $mp_stacks_instructions_items_array);
}
add_action('plugins_loaded', 'mp_stacks_instructions_create_meta_box');