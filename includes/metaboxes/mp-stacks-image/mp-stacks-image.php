<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_image_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_image_add_meta_box = array(
		'metabox_id' => 'mp_stacks_image_metabox', 
		'metabox_title' => __( '"Image" Content-Type', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_image_items_array = array(
		array(
			'field_id'			=> 'brick_main_image',
			'field_title' 	=> __( 'Main Image', 'mp_stacks'),
			'field_description' 	=> 'Select an image to display beside the text on this stack item.',
			'field_type' 	=> 'mediaupload',
			'field_value' => ''
		),
		array(
			'field_id'			=> 'brick_url',
			'field_title' 	=> __( 'Link URL', 'mp_stacks'),
			'field_description' 	=> 'Enter the URL the above image will go to when clicked. EG: http://mylink.com',
			'field_type' 	=> 'url',
			'field_value' => ''
		),
		array(
			'field_id'			=> 'brick_image_max_height',
			'field_title' 	=> __( 'Max Image Height (Optional)', 'mp_stacks'),
			'field_description' 	=> 'Set the maximum height (in pixels) this image should show at. Image will shrink to fit smaller screens but never get bigger than this height on larger screens.',
			'field_type' 	=> 'number',
			'field_value' => ''
		)
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_image_add_meta_box = has_filter('mp_stacks_image_meta_box_array') ? apply_filters( 'mp_stacks_image_meta_box_array', $mp_stacks_image_add_meta_box) : $mp_stacks_image_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_image_items_array = has_filter('mp_stacks_image_items_array') ? apply_filters( 'mp_stacks_image_items_array', $mp_stacks_image_items_array) : $mp_stacks_image_items_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_image_meta_box;
	$mp_stacks_image_meta_box = new MP_CORE_Metabox($mp_stacks_image_add_meta_box, $mp_stacks_image_items_array);
}
add_action('plugins_loaded', 'mp_stacks_image_create_meta_box');