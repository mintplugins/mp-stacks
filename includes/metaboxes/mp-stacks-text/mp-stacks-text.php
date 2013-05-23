<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_text_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_text_add_meta_box = array(
		'metabox_id' => 'mp_stacks_text_metabox', 
		'metabox_title' => __( 'Media Type - Text', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_text_items_array = array(
		array(
			'field_id'	 => 'brick_text_line_1',
			'field_title' => __( 'Text Line 1', 'mp_stacks'),
			'field_description' => 'Enter the text to display on line 1',
			'field_type' => 'textarea',
			'field_value' => ''
		),
		array(
			'field_id'	 => 'brick_text_line_2',
			'field_title' => __( 'Text Line 2', 'mp_stacks'),
			'field_description' => 'Enter the text to display on line 2',
			'field_type' => 'textarea',
			'field_value' => ''
		)
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_text_add_meta_box = has_filter('mp_stacks_text_meta_box_array') ? apply_filters( 'mp_stacks_text_meta_box_array', $mp_stacks_text_add_meta_box) : $mp_stacks_text_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_text_items_array = has_filter('mp_stacks_text_items_array') ? apply_filters( 'mp_stacks_text_items_array', $mp_stacks_text_items_array) : $mp_stacks_text_items_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_text_meta_box;
	$mp_stacks_text_meta_box = new MP_CORE_Metabox($mp_stacks_text_add_meta_box, $mp_stacks_text_items_array);
}
add_action('plugins_loaded', 'mp_stacks_text_create_meta_box');