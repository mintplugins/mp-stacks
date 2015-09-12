<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_size_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_size_add_meta_box = array(
		'metabox_id' => 'mp_stacks_size_metabox', 
		'metabox_title' => __( 'Brick Size Settings', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'side', 
		'metabox_priority' => 'default' ,
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_size_items_array = array(
		array(
			'field_id'			=> 'brick_max_width',
			'field_title' 	=> __( 'Maximum Content Width:', 'mp_stacks'),
			'field_description' 	=> 'Enter the maximum width for Content in this Brick (in pixels) ',
			'field_type' 	=> 'number',
			'field_value' => '1000'
		),
		array(
			'field_id'			=> 'brick_min_above_below',
			'field_title' 	=> __( 'Space Above Content:', 'mp_stacks'),
			'field_description' 	=> __( 'Enter the amount of space above the Content in this Brick. (in pixels)', 'mp_stacks' ),
			'field_type' 	=> 'number',
			'field_value' => '50',
		),
		array(
			'field_id'			=> 'brick_min_below',
			'field_title' 	=> __( 'Space Below Content:', 'mp_stacks'),
			'field_description' 	=> __( 'If blank, this matches the "Space Above".', 'mp_stacks' ),
			'field_type' 	=> 'number',
			'field_value' => '',
		),
		array(
			'field_id'  => 'brick_centered_inner_margins_showhider',
			'field_title'  =>  __('Content-Type Margins','mp_stacks' ),
			'field_description'  => __( 'Click to open the controls for inner margins. These controls only affect Content Types when "centered" (which automatically happens on mobile devices)','mp_stacks' ),
			'field_value'  => '',
			'field_type'  => 'showhider',
		),
			array(
				'field_id'			=> 'brick_min_above_c1',
				'field_title' 	=> __( '1st Content-Type - Space Above:', 'mp_stacks'),
				'field_description' 	=> 'Enter the space above the 1st Content-Type (in pixels) for this brick. Default: 0',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_min_below_c1',
				'field_title' 	=> __( '1st Content-Type - Space Below:', 'mp_stacks'),
				'field_description' 	=> 'Enter the space below the 1st Content-Type (in pixels) for this brick. Default: 20',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_min_above_c2',
				'field_title' 	=> __( '2nd Content-Type - Space Above:', 'mp_stacks'),
				'field_description' 	=> 'Enter the space above the 2nd Content-Type (in pixels) for this brick. Default: 0',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_min_below_c2',
				'field_title' 	=> __( '2nd Content-Type - Space Below:', 'mp_stacks'),
				'field_description' 	=> 'Enter the space below the 2nd Content-Type (in pixels) for this brick. Default: 0',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_min_height',
				'field_title' 	=> __( 'Minimum Height:', 'mp_stacks'),
				'field_description' 	=> 'Enter the smallest height this brick could ever be. (in pixels)',
				'field_type' 	=> 'number',
				'field_value' => '50',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
			array(
				'field_id'			=> 'brick_no_borders',
				'field_title' 	=> __( 'Full Width Content-Types', 'mp_stacks'),
				'field_description' 	=> 'If you want the content types to be able to touch the left and right sides, check this option.',
				'field_type' 	=> 'checkbox',
				'field_value' => '',
				'field_showhider'  => 'brick_centered_inner_margins_showhider',
			),
		array(
			'field_id'  => 'brick_content_type_widths_and_floats',
			'field_title'  =>  __('Content-Type Widths','mp_stacks' ),
			'field_description'  => __( 'Click to open the controls content-type widths.','mp_stacks' ),
			'field_value'  => '',
			'field_type'  => 'showhider',
		),
			array(
				'field_id'			=> 'brick_max_width_c1',
				'field_title' 	=> __( '1st Content-Type\'s Max-Width:', 'mp_stacks'),
				'field_description' 	=> 'Enter the maximum width the 1st Content-Type can be:',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
			array(
				'field_id'			=> 'brick_float_c1',
				'field_title' 	=> __( '1st Content-Type - Float:', 'mp_stacks'),
				'field_description' 	=> 'Should this sit all the way to the left, right, or center?',
				'field_type' 	=> 'select',
				'field_value' => '',
				'field_select_values' => array('center' => __('Center', 'mp_stacks'), 'left' => __('Left', 'mp_stacks'), 'right' => __('Right', 'mp_stacks') ),
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
			array(
				'field_id'			=> 'brick_max_width_c2',
				'field_title' 	=> __( '2nd Content-Type\'s Max-Width:', 'mp_stacks'),
				'field_description' 	=> 'Enter the maximum width the 2nd Content-Type can be:',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
			array(
				'field_id'			=> 'brick_float_c2',
				'field_title' 	=> __( '2nd Content-Type - Float:', 'mp_stacks'),
				'field_description' 	=> 'Should this sit all the way to the left, right, or center?',
				'field_type' 	=> 'select',
				'field_value' => '',
				'field_select_values' => array('center' => __('Center', 'mp_stacks'), 'left' => __('Left', 'mp_stacks'), 'right' => __('Right', 'mp_stacks') ),
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
			array(
				'field_id'			=> 'brick_split_percentage',
				'field_title' 	=> __( 'Split Percentage:', 'mp_stacks'),
				'field_description' 	=> __( 'What should the split percentage be? By default, it splits down the middle by using 50%. <br /> (NOTE: Does not apply if this brick is Centered)', 'mp_stacks' ) . '<br /><img width="129px" style="margin-top:10px;" src="' . plugins_url('assets/images/left-right.png', dirname( dirname( dirname(__FILE__) ) ) ) . '" />',
				'field_type' 	=> 'input_range',
				'field_value' => '50',
				'field_showhider'  => 'brick_content_type_widths_and_floats',
			),
		/*array(
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
		),*/
		array(
			'field_id'			=> 'brick_class_name',
			'field_title' 	=> __( 'Brick Class Name:', 'mp_stacks'),
			'field_description' 	=> 'This field will allow you to attach a unique classname to this brick which travels with it in templates',
			'field_type' 	=> 'hidden',
			'field_value' => '',
		),
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_size_add_meta_box = has_filter('mp_stacks_size_meta_box_array') ? apply_filters( 'mp_stacks_size_meta_box_array', $mp_stacks_size_add_meta_box) : $mp_stacks_size_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_size_items_array = has_filter('mp_stacks_size_items_array') ? apply_filters( 'mp_stacks_size_items_array', $mp_stacks_size_items_array) : $mp_stacks_size_items_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_size_meta_box;
	$mp_stacks_size_meta_box = new MP_CORE_Metabox($mp_stacks_size_add_meta_box, $mp_stacks_size_items_array);
}
add_action('mp_brick_ajax_metabox', 'mp_stacks_size_create_meta_box');