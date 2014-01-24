<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_media_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_media_add_meta_box = array(
		'metabox_id' => 'mp_stacks_media_metabox', 
		'metabox_title' => __( 'Media Settings', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'high' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_media_items_array = array(
		array(
			'field_id'	 => 'brick_first_media_type',
			'field_title' => __( '1st Media Type', 'mp_stacks'),
			'field_description' => 'Select the first media type to use for this stack.',
			'field_type' => 'select',
			'field_value' => '',
			'field_select_values' => array('none' => 'None', 'text' => 'Text', 'image' => 'Image', 'video' => 'Video')
		),
		array(
			'field_id'	 => 'brick_second_media_type',
			'field_title' => __( '2nd Media Type', 'mp_stacks'),
			'field_description' => 'Select the second media type to use for this stack.',
			'field_type' => 'select',
			'field_value' => '',
			'field_select_values' => array('none' => 'None', 'text' => 'Text', 'image' => 'Image', 'video' => 'Video')
		),
		array(
			'field_id'	 => 'brick_alignment',
			'field_title' => __( 'Alignment', 'mp_stacks'),
			'field_description' => 'How would you like these media types to be aligned?',
			'field_type' => 'select',
			'field_value' => '',
			'field_select_values' => array('leftright' => 'Left/Right', 'centered' => 'Centered', 'allleft' => 'All on left', 'allright' => 'All on right')
		),
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_media_add_meta_box = has_filter('mp_stacks_media_meta_box_array') ? apply_filters( 'mp_stacks_media_meta_box_array', $mp_stacks_media_add_meta_box) : $mp_stacks_media_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_media_items_array = has_filter('mp_stacks_media_items_array') ? apply_filters( 'mp_stacks_media_items_array', $mp_stacks_media_items_array) : $mp_stacks_media_items_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_media_meta_box;
	$mp_stacks_media_meta_box = new MP_CORE_Metabox($mp_stacks_media_add_meta_box, $mp_stacks_media_items_array);
}
add_action('plugins_loaded', 'mp_stacks_media_create_meta_box');