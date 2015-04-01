<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_admin_enqueue(){
	
	//css
	wp_enqueue_style( 'mp_stacks_admin_style', plugins_url('css/mp-stacks-admin-style.css', dirname(__FILE__)), MP_STACKS_VERSION );
	
	if ( !empty( $_GET['mp-stacks-minimal-admin'] ) ){
		//Hide admin items for edit brick screen - css
		wp_enqueue_style( 'mp_stacks_minimal-admin-css', plugins_url('css/mp-stacks-minimal-admin.css', dirname(__FILE__)), MP_STACKS_VERSION );
		
		//Append minimal admin variable to all urls if set
		wp_enqueue_script( 'mp_stacks_minimal-admin-js', plugins_url('js/mp-stacks-minimal-admin.js', dirname(__FILE__)), array('jquery'), MP_STACKS_VERSION, true );	
	}
	
	//lightbox
	wp_enqueue_script( 'mp_stacks_lightbox', plugins_url('js/lightbox.js', dirname(__FILE__) ), array( 'jquery' ), MP_STACKS_VERSION, true );
	
	//lightbox css
	wp_enqueue_style( 'mp_stacks_lightbox_css', plugins_url('css/lightbox.css', dirname(__FILE__) ), MP_STACKS_VERSION );
	
	//mp stacks admin js
	wp_enqueue_script( 'mp_stacks_admin_js', plugins_url('js/mp-stacks-admin.js', dirname(__FILE__) ), array( 'jquery', 'mp_stacks_lightbox' ), MP_STACKS_VERSION, true );
		
	wp_localize_script( 'mp_stacks_admin_js', 'mp_stacks_vars', 
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajax_nonce_value' => wp_create_nonce( 'mp-stacks-nonce-action-name' ), 
			'stack_successful_message_from_shortcode' => '<div class="mp-stacks-successful-image"><img class="mp-stacks-icon-250" src="' . plugins_url('assets/icon-256x256.png', dirname(dirname(__FILE__) ) ) . '" /></div><div class="mp-stacks-successful-text">' . __('Stack successfully created! Inserting...', 'mp_stacks')  . '</div>',
			'stack_successful_message_from_manage_page' => '<div class="mp-stacks-successful-image"><img class="mp-stacks-icon-250" src="' . plugins_url('assets/icon-256x256.png', dirname(dirname(__FILE__) ) ) . '" /></div><div class="mp-stacks-successful-text">' . __('Stack successfully created! See it listed on the right:', 'mp_stacks')  . '</div>',
			'stack_needs_title_alert' => __( 'Please enter a name to identify the new Stack', 'mp_stack' ),
			'stack_creating_message' => __( 'Please wait while your new Stack is created...', 'mp_stacks' ),
			'stack_insert_confirmation_phrase' => __( 'WARNING: I understand this is NOT a new Stack and changes made to it will reflect on ALL pages containing this Stack', 'mp_stacks' ),
			'stack_confirmation_incorrect' => __( 'Make sure to type exactly "WARNING: I understand this is NOT a new Stack and changes made to it will reflect on ALL pages containing this Stack"', 'mp_stacks' ),
			'add_new_brick_title' => __( 'Add New Brick', 'mp_stacks' ),
			'edit_brick_title' => __( 'Edit Brick', 'mp_stacks' ),
			'more_content_types' => admin_url( 'admin.php?page=mp_stacks_plugin_directory')
		) 
	);	
	
}
add_action('admin_enqueue_scripts', 'mp_stacks_admin_enqueue');

/**
 * Enqueue scripts used in front end. 
 */
function mp_stacks_frontend_enqueue(){
		
		//Main MP Stacks CSS
		wp_enqueue_style( 'mp_stacks_style', plugins_url('css/mp-stacks-style.css', dirname(__FILE__)), MP_STACKS_VERSION );
	
		//element size detection - media queries for divs
		wp_enqueue_script( 'mp_stacks_element_queries', plugins_url('js/elementQuery.min.js', dirname(__FILE__) ), array( 'jquery' ), MP_STACKS_VERSION, true );
		
		//lightbox
		wp_enqueue_script( 'mp_stacks_lightbox', plugins_url('js/lightbox.js', dirname(__FILE__) ), array( 'jquery' ), MP_STACKS_VERSION, true );
		
		//front end js
		wp_enqueue_script( 'mp_stacks_front_end_js', plugins_url('js/mp-stacks-front-end.js', dirname(__FILE__) ), array( 'jquery', 'mp_stacks_lightbox' ), MP_STACKS_VERSION, true );
		
		wp_localize_script( 'mp_stacks_front_end_js', 'mp_stacks_frontend_vars', 
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'ajax_nonce_value' => wp_create_nonce( 'mp-stacks-nonce-action-name' ), 
				'stacks_plugin_url' => MP_STACKS_PLUGIN_URL,
				'updating_message' => 'Updating brick and refreshing...'
			) 
		);	
		
		//lightbox css
		wp_enqueue_style( 'mp_stacks_lightbox_css', plugins_url('css/lightbox.css', dirname(__FILE__) ), MP_STACKS_VERSION );
}
add_action( 'wp_enqueue_scripts', 'mp_stacks_frontend_enqueue', 1 );