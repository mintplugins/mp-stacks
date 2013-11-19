<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_admin_enqueue(){
	
	//enqueue js after tiny mce 
	function custom_after_wp_tiny_mce() {
		 printf( '<script type="text/javascript" src="%s"></script>', plugins_url('js/show-media-types.js', dirname(__FILE__)), array( 'jquery') );
	}
	add_action( 'after_wp_tiny_mce', 'custom_after_wp_tiny_mce' );
	
	//css
	wp_enqueue_style( 'mp_stacks_admin_style', plugins_url('css/mp-stacks-admin-style.css', dirname(__FILE__)) );
}
add_action('admin_enqueue_scripts', 'mp_stacks_admin_enqueue');
