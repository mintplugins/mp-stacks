<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_admin_enqueue(){
	
	//css
	wp_enqueue_style( 'mp_stacks_admin_style', plugins_url('css/mp-stacks-admin-style.css', dirname(__FILE__)) );
	
	if ( !empty( $_GET['mp-stacks-minimal-admin'] ) ){
		//Hide admin items for edit brick screen - css
		wp_enqueue_style( 'mp_stacks_minimal-admin-css', plugins_url('css/mp-stacks-minimal-admin.css', dirname(__FILE__)) );
		
		//Append minimal admin variable to all urls if set
		wp_enqueue_script( 'mp_stacks_minimal-admin-js', plugins_url('js/mp-stacks-minimal-admin.js', dirname(__FILE__)), array('jquery') );	
	}
	
	//lightbox
	wp_enqueue_script( 'mp_stacks_lightbox', plugins_url('js/lightbox.js', dirname(__FILE__) ), array( 'jquery' ) );
	
	//lightbox css
	wp_enqueue_style( 'mp_stacks_lightbox_css', plugins_url('css/lightbox.css', dirname(__FILE__) ) );
	
	if(has_action('after_wp_tiny_mce')) {	
	
		//enqueue js after tiny mce 
		function custom_after_wp_tiny_mce() {
			 printf( '<script type="text/javascript" src="%s"></script>', plugins_url('js/mp-stacks-admin.js', dirname(__FILE__) ) );
		}
		add_action( 'after_wp_tiny_mce', 'custom_after_wp_tiny_mce' );	
		
	}else{
		//mp stacks admin js
		wp_enqueue_script( 'mp_stacks_admin_js', plugins_url('js/mp-stacks-admin.js', dirname(__FILE__) ), array( 'jquery' ) );
	
	}
	
	wp_localize_script( 'mp_stacks_admin_js', 'mp_stacks_vars', 
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajax_nonce_value' => wp_create_nonce( 'mp-stacks-nonce-action-name' ), 
			'stack_successful_message' => '<div class="mp-stacks-successful-image"><img class="mp-stacks-icon-250" src="' . plugins_url('assets/images/mp-stacks-icon-250.png', dirname(dirname(__FILE__) ) ) . '" /></div><div class="mp-stacks-successful-text">' . __('Stack successfully created! Insert it below:', 'mp_stacks')  . '</div>',
			'stack_needs_title_alert' => __( 'Please enter a name to identify the new Stack', 'mp_stack' )
		) 
	);	
	
}
add_action('admin_enqueue_scripts', 'mp_stacks_admin_enqueue');

/**
 * Enqueue scripts used in front end. Note the hook name is mp_stacks_enqueue_scripts. This ensures we only enqueue stacks scripts on pages with stack shortcodes
 */
function mp_stacks_frontend_enqueue(){
		
		//element size detection - media queries for divs
		wp_enqueue_script( 'mp_stacks_element_queries', plugins_url('js/elementQuery.min.js', dirname(__FILE__) ), array( 'jquery' ) );
		
		//lightbox
		wp_enqueue_script( 'mp_stacks_lightbox', plugins_url('js/lightbox.js', dirname(__FILE__) ), array( 'jquery' ) );
		
		//front end js
		wp_enqueue_script( 'mp_stacks_front_end_js', plugins_url('js/mp-stacks-front-end.js', dirname(__FILE__) ), array( 'jquery', 'mp_stacks_lightbox' ) );
		
		wp_localize_script( 'mp_stacks_front_end_js', 'mp_stacks_frontend_vars', 
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'ajax_nonce_value' => wp_create_nonce( 'mp-stacks-nonce-action-name' ), 
				'stacks_plugin_url' => MP_STACKS_PLUGIN_URL,
				'updating_message' => 'Updating brick and refreshing...'
			) 
		);	
		
		//lightbox css
		wp_enqueue_style( 'mp_stacks_lightbox_css', plugins_url('css/lightbox.css', dirname(__FILE__) ) );
}
add_action( 'mp_stacks_enqueue_scripts', 'mp_stacks_frontend_enqueue' );