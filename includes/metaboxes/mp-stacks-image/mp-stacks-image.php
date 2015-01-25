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
		'brick_main_image' => array(
			'field_id'			=> 'brick_main_image',
			'field_title' 	=> __( 'Main Image', 'mp_stacks'),
			'field_description' 	=> 'Select an image to display beside the text on this stack item.',
			'field_type' 	=> 'mediaupload',
			'field_value' => ''
		),
		'brick_main_image_max_width' => array(
				'field_id'			=> 'brick_main_image_max_width',
				'field_title' 	=> __( 'Max Image Width (Optional)', 'mp_stacks'),
				'field_description' 	=> 'Set the maximum width (in pixels) this image should show at. Image will shrink to fit smaller screens but never get bigger than this width.',
				'field_type' 	=> 'number',
				'field_value' => '',
			),
		'brick_main_image_link_showhider' => array( 
			'field_id'			=> 'brick_main_image_link_showhider',
			'field_title' 	=> __( 'Image Link Settings', 'mp_stacks'),
			'field_description' 	=> '',
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
			'brick_main_image_link_url' => array(
				'field_id'			=> 'brick_main_image_link_url',
				'field_title' 	=> __( 'Link URL', 'mp_stacks'),
				'field_description' 	=> 'Enter the URL the above image will go to when clicked. EG: http://mylink.com',
				'field_type' 	=> 'url',
				'field_value' => '',
				'field_showhider' => 'brick_main_image_link_showhider',
			),
			'brick_main_image_open_type' => array( 
				'field_id'			=> 'brick_main_image_open_type',
				'field_title' 	=> __( 'Link Open Type', 'mp_stacks'),
				'field_description' 	=> 'Enter the URL the above image will go to when clicked. EG: http://mylink.com',
				'field_type' 	=> 'select',
				'field_value' => '',
				'field_select_values' => array( 'lightbox' => __( 'Open in Lightbox', 'mp_stacks' ), 'parent' => __( 'Open in current Window/Tab', 'mp_stacks' ), 'blank' => __( 'Open in New Window/Tab', 'mp_stacks' ) ),
				'field_showhider' => 'brick_main_image_link_showhider',
			),
			'brick_main_image_lightbox_width' => array( 
				'field_id'			=> 'brick_main_image_lightbox_width',
				'field_title' 	=> __( 'Lightbox Width', 'mp_stacks'),
				'field_description' 	=> 'Enter width the lightbox popup should be. Leave blank for 100% of screen.',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_conditional_id' => 'brick_main_image_open_type',
				'field_conditional_values' => array( 'lightbox' ),
				'field_showhider' => 'brick_main_image_link_showhider',
			),
			'brick_main_image_lightbox_height' => array( 
				'field_id'			=> 'brick_main_image_lightbox_height',
				'field_title' 	=> __( 'Lightbox Height', 'mp_stacks'),
				'field_description' 	=> 'Enter height the lightbox popup should be. Leave blank for 100% of screen.',
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_conditional_id' => 'brick_main_image_open_type',
				'field_conditional_values' => array( 'lightbox' ),
				'field_showhider' => 'brick_main_image_link_showhider',
			),
		'brick_content_type_help' => array(
			'field_id'	 => 'brick_content_type_help',
			'field_title' => 'Content Types',
			'field_description' => NULL,
			'field_type' => 'help',
			'field_value' => '',
			'field_select_values' => array(
				array( 
					'type' => 'oembed',
					'link' => 'https://mintplugins.com/embed/?post_id=3898',
					'link_text' => __( '"Image" Content-Type Tutorial', 'mp_stacks'),
					'target' => NULL
				),
			)
		),
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
add_action('mp_brick_metabox', 'mp_stacks_image_create_meta_box');