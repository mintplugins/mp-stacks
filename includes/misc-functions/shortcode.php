<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_stylesheet(){
	//css
	wp_enqueue_style( 'mp_stacks_style', plugins_url('css/mp-stacks-style.css', dirname(__FILE__)) );
}
add_action('wp_enqueue_scripts', 'mp_stacks_stylesheet');

/**
 * Shortcode which is used to display the HTML content on a post
 */
function mp_stacks_display_mp_stack( $atts ) {
	global $mp_stacks_meta_box;
	$vars =  shortcode_atts( array('stack' => NULL), $atts );
		
	//Return the stack HTML output - pass the function the stack id
	return mp_stack( $vars['stack'] );
}
add_shortcode( 'mp_stack', 'mp_stacks_display_mp_stack' );

/**
 * Show "Insert Shortcode" above posts
 */
function mp_stacks_show_insert_shortcode(){
		
	$args = array(
		'shortcode_id' => 'mp_stack',
		'shortcode_title' => __('Stack', 'mp_stacks'),
		'shortcode_description' => __( 'Use the form below to insert the shortcode for a Stack ', 'mp_stacks' ),
		'shortcode_options' => array(
			array(
				'option_id' => 'stack',
				'option_title' => 'Stack',
				'option_description' => 'Choose a stack',
				'option_type' => 'select',
				'option_value' => mp_core_get_all_terms_by_tax('mp_stacks'),
			),
		)
	); 
		
	//Shortcode args filter
	$args = has_filter('mp_stacks_insert_shortcode_args') ? apply_filters('mp_stacks_insert_shortcode_args', $args) : $args;
	
	new MP_CORE_Shortcode_Insert($args);	
}
add_action('init', 'mp_stacks_show_insert_shortcode');