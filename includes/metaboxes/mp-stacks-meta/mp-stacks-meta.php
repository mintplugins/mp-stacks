<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_add_meta_box = array(
		'metabox_id' => 'mp_stacks_metabox', 
		'metabox_title' => __( 'Brick Settings', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'side', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_items_array = array(
		array(
			'field_id'			=> 'brick_bg_image',
			'field_title' 	=> __( 'Background Image (Optional)', 'mp_stacks'),
			'field_description' 	=> 'Select an image to use as your background image for this brick.',
			'field_type' 	=> 'mediaupload',
			'field_value' => ''
		),
		array(
			'field_id'			=> 'brick_bg_color',
			'field_title' 	=> __( 'Background Color (Optional)', 'mp_stacks'),
			'field_description' 	=> 'Select a color as your background color for this brick.',
			'field_type' 	=> 'colorpicker',
			'field_value' => ''
		),
		array(
			'field_id'			=> 'brick_max_width',
			'field_title' 	=> __( 'Maximum Width (Optional)', 'mp_stacks'),
			'field_description' 	=> 'Enter the maximum width (in pixels) for this brick',
			'field_type' 	=> 'number',
			'field_value' => ''
		),
		array(
			'field_id'			=> 'brick_min_height',
			'field_title' 	=> __( 'Minimum Height (Optional)', 'mp_stacks'),
			'field_description' 	=> 'Enter the minimum height (in pixels) for this brick. Default: 50',
			'field_type' 	=> 'number',
			'field_value' => '',
		),
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_add_meta_box = has_filter('mp_stacks_meta_box_array') ? apply_filters( 'mp_stacks_meta_box_array', $mp_stacks_add_meta_box) : $mp_stacks_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_items_array = has_filter('mp_stacks_items_array') ? apply_filters( 'mp_stacks_items_array', $mp_stacks_items_array) : $mp_stacks_items_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_meta_box;
	$mp_stacks_meta_box = new MP_CORE_Metabox($mp_stacks_add_meta_box, $mp_stacks_items_array);
}
add_action('plugins_loaded', 'mp_stacks_create_meta_box');