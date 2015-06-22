<?php
/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_content_type_1_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_content_add_meta_box = array(
		'metabox_id' => 'mp_stacks_content_type_1_metabox', 
		'metabox_title' => __( '1st Content-Type', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' ,
		'metabox_container_needed' => true,
		'metabox_content_via_ajax' => true,
		'doing_ajax_callback' => false,
		'ajax_post_id' => NULL
	);
	
	
	$mp_stacks_content_type_one_meta_box = new MP_CORE_Metabox($mp_stacks_content_add_meta_box, array() );
}
add_action('mp_brick_ajax_metabox', 'mp_stacks_content_type_1_create_meta_box', 1);

/**
 * Function which creates new Meta Box
 *
 */
function mp_stacks_content_type_2_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_content_add_meta_box = array(
		'metabox_id' => 'mp_stacks_content_type_2_metabox', 
		'metabox_title' => __( '2nd Content-Type', 'mp_stacks'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' ,
		'metabox_container_needed' => true,
		'metabox_content_via_ajax' => true,
		'doing_ajax_callback' => false,
		'ajax_post_id' => NULL
	);
	
	
	$mp_stacks_content_type_one_meta_box = new MP_CORE_Metabox($mp_stacks_content_add_meta_box, array() );
}
add_action('mp_brick_ajax_metabox', 'mp_stacks_content_type_2_create_meta_box', 1);