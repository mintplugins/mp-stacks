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
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_singletext_items_array = array(
		array(
			'field_id'	 => 'brick_text_title',
			'field_title' => __( 'Text Area', 'mp_stacks'),
			'field_description' => __( 'Add as many Text Areas as you want. Each new text area can have its own font, text-size, color, and spacing.', 'mp_stacks' ),
			'field_type' => 'repeatertitle',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
		),
		array(
			'field_id'	 => 'brick_text_color',
			'field_title' => __( 'Text Color', 'mp_stacks'),
			'field_description' => '<br />Select the color of text area 1.',
			'field_type' => 'colorpicker',
			'field_value' => '',
			'field_container_class' => 'mp_brick_text_option',
			'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
		),
		array(
			'field_id'	 => 'brick_text_font_size',
			'field_title' => __( 'Text Size', 'mp_stacks'),
			'field_description' => '<br />Enter the size (in pixels).',
			'field_type' => 'number',
			'field_value' => '35',
			'field_container_class' => 'mp_brick_text_option',
			'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
		),
		array(
			'field_id'	 => 'brick_text_line_height',
			'field_title' => __( 'Line-Height', 'mp_stacks'),
			'field_description' => '<br />Enter the line-height in pixels. By default this matches the Text Size.',
			'field_type' => 'number',
			'field_value' => '',
			'field_container_class' => 'mp_brick_text_option',
			'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
		),
		array(
			'field_id'	 => 'brick_text_paragraph_margin_bottom',
			'field_title' => __( 'Paragraph Spacing', 'mp_stacks'),
			'field_description' => '<br />Enter the number of pixels separating each paragraph. Default: 15px',
			'field_type' => 'number',
			'field_value' => '15',
			'field_container_class' => 'mp_brick_text_option',
			'field_repeater' => 'mp_stacks_singletext_content_type_repeater'
		),
		array(
			'field_id'	 => 'brick_text',
			'field_title' => __( 'Text Area', 'mp_stacks'),
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
add_action('mp_brick_metabox', 'mp_stacks_singletext_create_meta_box');