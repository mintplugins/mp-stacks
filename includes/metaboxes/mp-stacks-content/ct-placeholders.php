<?php
/**
 * Function which creates new Meta Box for the Content Type 1 Slot. Contents of this metabox will be loaded-in via ajax.
 *
 */
function mp_stacks_content_type_1_create_meta_box(){	
	add_meta_box( 
		'mp_stacks_content_type_1_metabox',
		__( '1st Content-Type\'s Controls', 'mp_stacks'),
		'mp_core_metabox_content_ajax_placeholder',
		'mp_brick',
		'advanced',
		'low'
	);
}
add_action('mp_brick_ajax_metabox', 'mp_stacks_content_type_1_create_meta_box', 1);

/**
 * Function which creates new Meta Box for the Content Type 1 Slot. Contents of this metabox will be loaded-in via ajax.
 *
 */
function mp_stacks_content_type_2_create_meta_box(){	
	add_meta_box( 
		'mp_stacks_content_type_2_metabox',
		__( '2nd Content-Type\'s Controls', 'mp_stacks'),
		'mp_core_metabox_content_ajax_placeholder',
		'mp_brick',
		'advanced',
		'low'
	);
}
add_action('mp_brick_ajax_metabox', 'mp_stacks_content_type_2_create_meta_box', 2);