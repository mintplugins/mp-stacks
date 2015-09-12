<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_singletext_create_meta_box(){	
		
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_singletext_add_meta_box = array(
		'metabox_id' => 'mp_stacks_singletext_metabox', 
		'metabox_title' => __( '"Text" Content-Type', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low',
		'metabox_content_via_ajax' => true,
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_singletext_items_array = array(
		'brick_text_title' => array(
			'field_id'	 => 'brick_text_title',
			'field_title' => __( 'Text Area', 'mp_stacks'),
			'field_description' => __( 'Add as many Text Areas as you want. Each new text area can have its own font, text-size, color, and spacing.', 'mp_stacks' ),
			'field_type' => 'repeatertitle',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
		),
			
			//Desktop Font Controls - These controls existed before any others - so they don't have the extension - and are the defaults if no other sizes are set.
			'brick_text_color' => array(
				'field_id'	 => 'brick_text_color',
				'field_title' => __( 'Text Color', 'mp_stacks'),
				'field_description' => 'Select the color for this text.',
				'field_type' => 'colorpicker',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_font_size' => array(
				'field_id'	 => 'brick_text_font_size',
				'field_title' => __( 'Text Size', 'mp_stacks'),
				'field_description' => 'Enter the size (in pixels).',
				'field_type' => 'number',
				'field_value' => '35',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_line_height' => array(
				'field_id'	 => 'brick_text_line_height',
				'field_title' => __( 'Line-Height', 'mp_stacks'),
				'field_description' => 'Enter the line-height in pixels. By default this matches the Text Size.',
				'field_type' => 'number',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_paragraph_margin_bottom' => array(
				'field_id'	 => 'brick_text_paragraph_margin_bottom',
				'field_title' => __( 'Paragraph Spacing', 'mp_stacks'),
				'field_description' => 'Enter the number of pixels separating each paragraph in this text area. Default: 15px',
				'field_type' => 'number',
				'field_value' => '15',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			
			//Tablet Font Controls
			'brick_text_color_tablet' => array(
				'field_id'	 => 'brick_text_color_tablet',
				'field_title' => __( 'Text Color', 'mp_stacks'),
				'field_description' => 'Select the color for this text.',
				'field_type' => 'colorpicker',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_font_size_tablet' => array(
				'field_id'	 => 'brick_text_font_size_tablet',
				'field_title' => __( 'Text Size', 'mp_stacks'),
				'field_description' => 'Enter the size (in pixels).',
				'field_type' => 'number',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_line_height_tablet' => array(
				'field_id'	 => 'brick_text_line_height_tablet',
				'field_title' => __( 'Line-Height', 'mp_stacks'),
				'field_description' => 'Enter the line-height in pixels. By default this matches the Text Size.',
				'field_type' => 'number',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_paragraph_margin_bottom_tablet' => array(
				'field_id'	 => 'brick_text_paragraph_margin_bottom_tablet',
				'field_title' => __( 'Paragraph Spacing', 'mp_stacks'),
				'field_description' => 'Enter the number of pixels separating each paragraph in this text area. Default: 15px',
				'field_type' => 'number',
				'field_value' => '15',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			
			//Laptop Font Controls
			'brick_text_color_laptop' => array(
				'field_id'	 => 'brick_text_color_laptop',
				'field_title' => __( 'Text Color', 'mp_stacks'),
				'field_description' => 'Select the color for this text.',
				'field_type' => 'colorpicker',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			
			//Mobile Font Controls
			'brick_text_color_mobile' => array(
				'field_id'	 => 'brick_text_color_mobile',
				'field_title' => __( 'Text Color', 'mp_stacks'),
				'field_description' => 'Select the color for this text.',
				'field_type' => 'colorpicker',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_font_size_mobile' => array(
				'field_id'	 => 'brick_text_font_size_mobile',
				'field_title' => __( 'Text Size', 'mp_stacks'),
				'field_description' => 'Enter the size (in pixels).',
				'field_type' => 'number',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_line_height_mobile' => array(
				'field_id'	 => 'brick_text_line_height_mobile',
				'field_title' => __( 'Line-Height', 'mp_stacks'),
				'field_description' => 'Enter the line-height in pixels. By default this matches the Text Size.',
				'field_type' => 'number',
				'field_value' => '',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
			'brick_text_paragraph_margin_bottom_mobile' => array(
				'field_id'	 => 'brick_text_paragraph_margin_bottom_mobile',
				'field_title' => __( 'Paragraph Spacing', 'mp_stacks'),
				'field_description' => 'Enter the number of pixels separating each paragraph in this text area. Default: 15px',
				'field_type' => 'number',
				'field_value' => '15',
				'field_container_class' => 'mp_brick_text_option',
				'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
			),
		
		'brick_text_screen_size_controller' => array(
			'field_id'	 => 'brick_text_screen_size_controller',
			'field_title' => '<div class="brick_screen_size_picker" mp-area-active="closed">
					<div class="brick_screen_size desktop" mp_stacks_textsize_device="desktop"></div>
					<div class="brick_screen_size tablet" mp_stacks_textsize_device="tablet"></div>
					<div class="brick_screen_size mobile" mp_stacks_textsize_device="mobile"></div>
				</div>',
			'field_description' => '',
			'field_type' => 'basictext',
			'field_container_class' => 'mp_brick_text_option',
			'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
		),
		'brick_text' => array(
			'field_id'	 => 'brick_text',
			'field_title' => '',
			'field_description' => 'Enter the text to display in this text area',
			'field_type' => 'wp_editor',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
		),
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_singletext_add_meta_box = has_filter('mp_stacks_singletext_meta_box_array') ? apply_filters( 'mp_stacks_singletext_meta_box_array', $mp_stacks_singletext_add_meta_box) : $mp_stacks_singletext_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_singletext_items_array = has_filter('mp_stacks_singletext_items_array') ? apply_filters( 'mp_stacks_singletext_items_array', $mp_stacks_singletext_items_array) : $mp_stacks_singletext_items_array;
		
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_singletext_meta_box;
	$mp_stacks_singletext_meta_box = new MP_CORE_Metabox($mp_stacks_singletext_add_meta_box, $mp_stacks_singletext_items_array);
}
add_action('mp_brick_ajax_metabox', 'mp_stacks_singletext_create_meta_box');
add_action('wp_ajax_mp_stacks_singletext_metabox_content', 'mp_stacks_singletext_create_meta_box');