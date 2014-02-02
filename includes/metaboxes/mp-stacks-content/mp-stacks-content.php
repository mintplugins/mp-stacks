<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_content_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_content_add_meta_box = array(
		'metabox_id' => 'mp_stacks_content_metabox', 
		'metabox_title' => __( 'Brick\'s Content-Types', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'high' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_content_types_array = array(
		array(
			'field_id'	 => 'brick_first_content_type',
			'field_title' => __( '1st Content Type', 'mp_stacks'),
			'field_description' => 'Select the first content type to use for this brick.',
			'field_type' => 'select',
			'field_value' => '',
			'field_select_values' => array('none' => 'None', 'text' => 'Text', 'image' => 'Image', 'video' => 'Video')
		),
		array(
			'field_id'	 => 'brick_second_content_type',
			'field_title' => __( '2nd Content Type', 'mp_stacks'),
			'field_description' => 'Select the second content type to use for this brick.',
			'field_type' => 'select',
			'field_value' => '',
			'field_select_values' => array('none' => 'None', 'text' => 'Text', 'image' => 'Image', 'video' => 'Video')
		),
		array(
			'field_id'	 => 'brick_alignment',
			'field_title' => __( 'Alignment', 'mp_stacks'),
			'field_description' => 'How would you like these content types to be aligned?',
			'field_type' => 'radio',
			'field_value' => '',
			'field_select_values' => array('leftright' => 'Left/Right', 'centered' => 'Centered', 'allleft' => 'All on left', 'allright' => 'All on right')
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
					'link' => 'http://www.youtube.com/watch?v=BuTJbBElU-A',
					'link_text' => __( 'Watch Tutorial Video for Content Types', 'mp_stacks'),
					'target' => NULL
				),
				array( 
					'type' => 'directory',
					'link' => admin_url( 'edit.php?post_type=mp_brick&page=mp_stacks_plugin_directory'),
					'link_text' => __( 'Get more Content Types', 'mp_stacks'),
					'target' => '_blank'
				)
			)
		),
		*/
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_content_add_meta_box = has_filter('mp_stacks_content_meta_box_array') ? apply_filters( 'mp_stacks_content_meta_box_array', $mp_stacks_content_add_meta_box) : $mp_stacks_content_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_content_types_array = has_filter('mp_stacks_content_types_array') ? apply_filters( 'mp_stacks_content_types_array', $mp_stacks_content_types_array) : $mp_stacks_content_types_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_content_meta_box;
	$mp_stacks_content_meta_box = new MP_CORE_Metabox($mp_stacks_content_add_meta_box, $mp_stacks_content_types_array);
}
add_action('plugins_loaded', 'mp_stacks_content_create_meta_box');

function mp_stacks_alignment_radio_leftright_before(){
	echo '<img width="50px" src="' . plugins_url('assets/images/left-right.png', dirname( dirname( dirname(__FILE__) ) ) ) . '" />';	
}
add_action('mp_core_metabox_before_' . 'leftright' . '_radio_description', 'mp_stacks_alignment_radio_leftright_before'); 

function mp_stacks_alignment_radio_centered_before(){
	echo '<img width="50px" src="' . plugins_url('assets/images/centered.png', dirname( dirname( dirname(__FILE__) ) ) ) . '" />';	
}
add_action('mp_core_metabox_before_' . 'centered' . '_radio_description', 'mp_stacks_alignment_radio_centered_before'); 

function mp_stacks_alignment_radio_allonleft_before(){
	echo '<img width="50px" src="' . plugins_url('assets/images/all-on-left.png', dirname( dirname( dirname(__FILE__) ) ) ) . '" />';	
}
add_action('mp_core_metabox_before_' . 'allleft' . '_radio_description', 'mp_stacks_alignment_radio_allonleft_before'); 

function mp_stacks_alignment_radio_allonright_before(){
	echo '<img width="50px" src="' . plugins_url('assets/images/all-on-right.png', dirname( dirname( dirname(__FILE__) ) ) ) . '" />';	
}
add_action('mp_core_metabox_before_' . 'allright' . '_radio_description', 'mp_stacks_alignment_radio_allonright_before'); 