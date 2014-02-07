<?php
/**
 * This file contains various functions
 *
 * @since 1.0.0
 *
 * @package    MP Core
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Ajax callback for Create New Stack button
 *
 * @since    1.0.0
 * @link     http://codex.wordpress.org/Function_Reference/add_editor_style
 * @see      get_bloginfo()
 * @param    array $wp See link for description.
 * @return   void
 */
function mp_stacks_add_new_stack_ajax() {	

	$new_stack_name = $_POST['mp_stacks_new_stack_name'];

	if( !empty( $new_stack_name ) ) {
				
		//Make new stack
		$new_stack_array = wp_insert_term(
			$new_stack_name, // the term 
			'mp_stacks' // the taxonomy
		);
		
		echo '<option value="' . $new_stack_array['term_id'] . '" selected>' . $new_stack_name . '</option>';
	
	}

	die(); // this is required to return a proper result
	
}
add_action( 'wp_ajax_mp_stacks_make_new_stack', 'mp_stacks_add_new_stack_ajax' );