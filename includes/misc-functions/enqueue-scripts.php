<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_admin_enqueue(){
	
	//js
	wp_enqueue_script( 'mp_stacks_show_media_types', plugins_url('js/show-media-types.js', dirname(__FILE__)) );
	
	//css
	wp_enqueue_style( 'mp_stacks_admin_style', plugins_url('css/mp-stacks-admin-style.css', dirname(__FILE__)) );
}
add_action('admin_enqueue_scripts', 'mp_stacks_admin_enqueue');
