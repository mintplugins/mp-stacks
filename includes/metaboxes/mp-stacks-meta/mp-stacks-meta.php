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
		'metabox_id' => 'mp_stacks_size_metabox', 
		'metabox_title' => __( 'Brick Size Settings', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'side', 
		'metabox_priority' => 'default' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_items_array = array(
		array(
			'field_id'			=> 'brick_min_height',
			'field_title' 	=> __( 'Min Height: <br />', 'mp_stacks'),
			'field_description' 	=> 'Enter the minimum height (in pixels) for this brick. Default: 50',
			'field_type' 	=> 'number',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'brick_max_width',
			'field_title' 	=> __( 'Content-Types Area - Max Width: <br />', 'mp_stacks'),
			'field_description' 	=> 'Enter the maximum width for the Content-Types Area (in pixels) for this brick',
			'field_type' 	=> 'number',
			'field_value' => ''
		),
		array(
			'field_id'			=> 'brick_min_above_below',
			'field_title' 	=> __( 'Content-Types Area - Min Height Above/Below: <br />', 'mp_stacks'),
			'field_description' 	=> 'Enter the minimum height above/below the media area (in pixels) for this brick. Default: 10',
			'field_type' 	=> 'number',
			'field_value' => '',
		),
		array(
			'field_id'  => 'brick_centered_inner_margins_showhider',
			'field_title'  =>  __('Content-Type Margins','mp_stacks_parallax' ),
			'field_description'  => __( '<br />Click to open the controls for inner margins. These controls only affect Content Types when "centered" (which automatically happens on mobile devices)','mp_stacks_parallax' ),
			'field_value'  => '',
			'field_type'  => 'showhider',
		),
			array(
				'field_id'			=> 'brick_min_above_c1',
				'field_title' 	=> __( '1st Content-Type - Min Height Above: <br />', 'mp_stacks'),
				'field_description' 	=> 'Enter the minimum height above/below the 1st Content-Type (in pixels) for this brick. Default: 10',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_min_below_c1',
				'field_title' 	=> __( '1st Content-Type - Min Height Below: <br />', 'mp_stacks'),
				'field_description' 	=> 'Enter the minimum height below the 1st Content-Type (in pixels) for this brick. Default: 10',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_min_above_c2',
				'field_title' 	=> __( '2nd Content-Type - Min Height Above: <br />', 'mp_stacks'),
				'field_description' 	=> 'Enter the minimum height above/below the 2nd Content-Type (in pixels) for this brick. Default: 10',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_min_below_c2',
				'field_title' 	=> __( '2nd Content-Type - Min Height Below: <br />', 'mp_stacks'),
				'field_description' 	=> 'Enter the minimum height below the 2nd Content-Type (in pixels) for this brick. Default: 10',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_no_borders',
				'field_title' 	=> __( 'Full Width Content-Types <br />', 'mp_stacks'),
				'field_description' 	=> 'If you want the content types to be able to touch the left and right sides, check this option.',
				'field_type' 	=> 'checkbox',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
		array(
			'field_id'  => 'brick_content_type_widths_and_floats',
			'field_title'  =>  __('Content-Type Widths','mp_stacks_parallax' ),
			'field_description'  => __( '<br />Click to open the controls content-type widths.','mp_stacks' ),
			'field_value'  => '',
			'field_type'  => 'showhider',
		),
			array(
				'field_id'			=> 'brick_max_width_c1',
				'field_title' 	=> __( '1st Content-Type\'s Max-Width: <br />', 'mp_stacks'),
				'field_description' 	=> 'Enter the maximum width the 1st Content-Type can be:',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
			array(
				'field_id'			=> 'brick_float_c1',
				'field_title' 	=> __( '1st Content-Type - Float: <br />', 'mp_stacks'),
				'field_description' 	=> 'Should this sit all the way to the left, right, or center?',
				'field_type' 	=> 'select',
				'field_value' => '',
				'field_select_values' => array('center' => __('Center', 'mp_stacks'), 'left' => __('Left', 'mp_stacks'), 'right' => __('Right', 'mp_stacks') ),
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
			array(
				'field_id'			=> 'brick_max_width_c2',
				'field_title' 	=> __( '2nd Content-Type\'s Max-Width: <br />', 'mp_stacks'),
				'field_description' 	=> 'Enter the maximum width the 2nd Content-Type can be:',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
			array(
				'field_id'			=> 'brick_float_c2',
				'field_title' 	=> __( '2nd Content-Type - Float: <br />', 'mp_stacks'),
				'field_description' 	=> 'Should this sit all the way to the left, right, or center?',
				'field_type' 	=> 'select',
				'field_value' => '',
				'field_select_values' => array('center' => __('Center', 'mp_stacks'), 'left' => __('Left', 'mp_stacks'), 'right' => __('Right', 'mp_stacks') ),
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
			array(
				'field_id'			=> 'brick_float_c2',
				'field_title' 	=> __( '2nd Content-Type - Float: <br />', 'mp_stacks'),
				'field_description' 	=> 'Should this sit all the way to the left, right, or center?',
				'field_type' 	=> 'select',
				'field_value' => '',
				'field_select_values' => array('center' => __('Center', 'mp_stacks'), 'left' => __('Left', 'mp_stacks'), 'right' => __('Right', 'mp_stacks') ),
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
		array(
			'field_id'	 => 'brick_content_type_help',
			'field_title' => 'Content Types',
			'field_description' => NULL,
			'field_type' => 'help',
			'field_value' => '',
			'field_select_values' => array(
				array( 
					'type' => 'oembed',
					'link' => 'https://mintplugins.com/embed/?post_id=3880',
					'link_text' => __( 'Brick Size Settings Tutorial', 'mp_stacks'),
					'target' => NULL
				),
			)
		),
		array(
			'field_id'			=> 'brick_class_name',
			'field_title' 	=> __( 'Brick Class Name: <br />', 'mp_stacks'),
			'field_description' 	=> 'This field will allow you to attach a unique classname to this brick which travels with it in templates',
			'field_type' 	=> 'hidden',
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