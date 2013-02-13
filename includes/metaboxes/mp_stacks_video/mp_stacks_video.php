<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_video_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_video_add_meta_box = array(
		'metabox_id' => 'mp_stacks_video_metabox', 
		'metabox_title' => __( 'Media Type - Video', 'mp_stacks'), 
		'metabox_posttype' => 'mp_stack', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_video_items_array = array(
		array(
			'field_id'			=> 'stack_video_url',
			'field_title' 	=> __( 'Video URL', 'mp_stacks'),
			'field_description' 	=> 'Enter the URL to the video page.',
			'field_type' 	=> 'url',
			'field_value' => ''
		),
		array(
			'field_id'			=> 'stack_video_width',
			'field_title' 	=> __( 'Video Width', 'mp_stacks'),
			'field_description' 	=> 'Enter the width of the video (in pixels). EG \'300\'',
			'field_type' 	=> 'number',
			'field_value' => ''
		),
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_video_add_meta_box = has_filter('mp_stacks_video_meta_box_array') ? apply_filters( 'mp_stacks_video_meta_box_array', $mp_stacks_video_add_meta_box) : $mp_stacks_video_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_video_items_array = has_filter('mp_stacks_video_items_array') ? apply_filters( 'mp_stacks_video_items_array', $mp_stacks_video_items_array) : $mp_stacks_video_items_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_video_meta_box;
	$mp_stacks_video_meta_box = new MP_CORE_Metabox($mp_stacks_video_add_meta_box, $mp_stacks_video_items_array);
}
add_action('plugins_loaded', 'mp_stacks_video_create_meta_box');