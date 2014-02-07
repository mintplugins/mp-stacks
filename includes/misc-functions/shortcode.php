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
		'shortcode_description' => __( 'Use the form below to insert the shortcode for your Stack:', 'mp_stacks' ),
		'shortcode_icon_spot' => true,
		'shortcode_options' => array(
			array(
				'option_id' => 'stack',
				'option_title' => 'Choose an existing Stack',
				'option_description' => 'Select the stack you wish to display and then click "Insert Stack"',
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

/**
 * Add "Make New Stack" button to end of shortcode inserter
 */
function mp_stacks_shortcode_make_new_stack(){
	echo '<div class="mp-stacks-shortcode-new-stack-div">';
	echo '<h1>' . __('Need to make a new Stack first?', 'mp_stacks') . '</h1>';
	echo '<div class="mp_title"><label for="mp_stack_stack"><strong>' . __('Create a new Stack', 'mp_stacks') . '</strong> <em>' . __('Enter a name for your new stack to create one now', 'mp_stacks') . '</em></label></div>';
	echo '<input class="mp-stacks-new-stack-input" name="new_stack_name" placeholder="' . __('Your new Stack\'s name', 'mp_stacks') . '"/>';
	echo '<a class="button mp-stacks-new-stack-button">' . __('Make A New Stack', 'mp_stacks') . '</a></div><br /><br />';
	echo '<h1>' . __('Already have the stack you wish to use?', 'mp_stacks') . '</h1>';
}
add_action('mp_core_before_' . 'mp_stack' . '_shortcode_output' , 'mp_stacks_shortcode_make_new_stack');