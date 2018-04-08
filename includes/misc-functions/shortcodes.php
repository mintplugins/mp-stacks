<?php
/**
 * Shortcode which is used to display a Stack from MP Stacks
 *
 * @access   public
 * @since    1.0.0
 * @return   string The HTML output for a Stack
 */
function mp_stacks_display_mp_stack( $atts ) {

	$vars = shortcode_atts( array('stack' => NULL), $atts );

	//Return the stack HTML output - pass the function the stack id
	return mp_stack( absint( str_replace( '"', '', $vars['stack'] ) ) );
}
add_shortcode( 'mp_stack', 'mp_stacks_display_mp_stack' );

/**
 * A shortcode which will return the title of the page so it can be used in a Stack (parent page).
 *
 * @access   public
 * @since    1.0.0
 * @return   string The title of the queried post
 */
function mp_stacks_the_title_shortcode(){

	global $wp_query;

	//If we are NOT doing ajax get the parent's post id from the wp_query.
	if ( !defined( 'DOING_AJAX' ) ){
		$queried_id = $wp_query->queried_object_id;
	}
	//If we are doing ajax, get the parent's post id from the AJAX-passed $POST['mp_stacks_queried_object_id']
	else{
		$queried_id = isset( $_POST['mp_stacks_queried_object_id'] ) ? $_POST['mp_stacks_queried_object_id'] : '';
	}

    return get_the_title( $queried_id );
}
add_shortcode( 'mps_the_title', 'mp_stacks_the_title_shortcode' );

/**
 * A shortcode which will return the content of the page so it can be used in a Stack (parent page).
 *
 * @access   public
 * @since    1.0.0
 * @return   string The title of the queried post
 */
function mp_stacks_the_content_shortcode(){

	global $wp_query;

	//If we are NOT doing ajax get the parent's post id from the wp_query.
	if ( !defined( 'DOING_AJAX' ) ){
		$queried_id = $wp_query->queried_object_id;
	}
	//If we are doing ajax, get the parent's post id from the AJAX-passed $POST['mp_stacks_queried_object_id']
	else{
		$queried_id = isset( $_POST['mp_stacks_queried_object_id'] ) ? $_POST['mp_stacks_queried_object_id'] : '';
	}

    return apply_filters('the_content', get_post_field('post_content', $queried_id ) );
}
add_shortcode( 'mps_the_content', 'mp_stacks_the_content_shortcode' );


/**
 * A shortcode which will return the content of the page so it can be used in a Stack (parent page).
 *
 * @access   public
 * @since    1.0.0
 * @return   string The title of the queried post
 */
function mp_stacks_custom_meta_shortcode( $atts ){

	global $wp_query;

	$vars = shortcode_atts( array('meta_key' => NULL), $atts );

	//If we are NOT doing ajax get the parent's post id from the wp_query.
	if ( !defined( 'DOING_AJAX' ) ){
		$queried_id = $wp_query->queried_object_id;
	}
	//If we are doing ajax, get the parent's post id from the AJAX-passed $POST['mp_stacks_queried_object_id']
	else{
		$queried_id = isset( $_POST['mp_stacks_queried_object_id'] ) ? $_POST['mp_stacks_queried_object_id'] : '';
	}

    return get_post_meta( $queried_id, $vars['meta_key'], true );
}
add_shortcode( 'mps_custom_meta', 'mp_stacks_custom_meta_shortcode' );
