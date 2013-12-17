<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_admin_enqueue(){
	
	//enqueue js after tiny mce 
	function custom_after_wp_tiny_mce() {
		 printf( '<script type="text/javascript" src="%s"></script>', plugins_url('js/mp-stacks-admin.js', dirname(__FILE__)), array( 'jquery') );
	}
	add_action( 'after_wp_tiny_mce', 'custom_after_wp_tiny_mce' );
	
	//css
	wp_enqueue_style( 'mp_stacks_admin_style', plugins_url('css/mp-stacks-admin-style.css', dirname(__FILE__)) );
	
	if ( !empty( $_GET['mp-stacks-edit-link'] ) ){
		//Hide admin items for edit brick screen - css
		wp_enqueue_style( 'mp_stacks_admin_brick', plugins_url('css/mp-stacks-admin-brick.css', dirname(__FILE__)) );
	}
	
}
add_action('admin_enqueue_scripts', 'mp_stacks_admin_enqueue');


function mp_stacks_frontend_enqueue(){
	
		//lightbox
		wp_enqueue_script( 'mp_stacks_lightbox', plugins_url('js/lightbox.js', dirname(__FILE__) ), array( 'jquery' ) );
		
		//front end js
		wp_enqueue_script( 'mp_stacks_front_end_js', plugins_url('js/mp-stacks-front-end.js', dirname(__FILE__) ), array( 'jquery', 'mp_stacks_lightbox' ) );
		
		//lightbox css
		wp_enqueue_style( 'mp_stacks_lightbox_css', plugins_url('css/lightbox.css', dirname(__FILE__) ) );
}
add_action( 'wp_enqueue_scripts', 'mp_stacks_frontend_enqueue' );