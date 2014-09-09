<?php
/**
 * Installaion hooks for MP Stacks
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package     MP Stacks
 * @subpackage  Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//Set up our Global Options for MP Stacks
mp_stacks_global_options_init();

/**
 * Set up the global $mp_stacks_options
 *
 * @since 1.0
 * @global $wpdb
 * @global $mp_stacks_options
 * @return void
 */
function mp_stacks_global_options_init(){
	
	global $mp_stacks_options;
	
	$mp_stacks_options = get_option('mp_stacks_options');
	
}

/**
 * Install
 *
 * Runs on plugin install by setting up the sample stack page,
 * flushing rewrite rules to initiate the new 'stacks' slug.<br />
 * After successful install, the user is redirected to the MP Stacks Welcome
 * screen.
 *
 * @since 1.0
 * @global $wpdb
 * @global $mp_stacks_options
 * @global $wp_version
 * @return void
 */
function mp_stacks_install() {
	global $wpdb, $mp_stacks_options, $wp_version;
	
	//Tell the mp_stacks_options that we just activated
	$mp_stacks_options['just_activated'] = true;
	
	//When we've built this feature, take this out 
	$sample_page_feature_built = false;
	
	// Checks if the mp stacks sample page exists
	if ( ! isset( $mp_stacks_options['sample_stack_page'] ) && $sample_page_feature_built == true ) {
	  // Sample Stack Page
		$sample_stack_page = wp_insert_post(
			array(
				'post_title'     => __( 'Sample Stack Page', 'mp_stacks' ),
				'post_content'   => '[mp_stack="NULL"]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		// Store our sample stack page ID
		$mp_stacks_options['sample_stack_page'] = $sample_stack_page;

	}
	
	//Save our mp_stacks_options - since we've just activated and changed some of them
	update_option( 'mp_stacks_options', $mp_stacks_options );

}
register_activation_hook( MP_STACKS_PLUGIN_FILE, 'mp_stacks_install' );

/**
 * Redirect to Welcome page upon shutdown if we just activated MP Stacks
 *
 * @since 1.0
 * @global $wpdb
 * @global $mp_stacks_options
 * @return void
 */
function mp_stacks_redirect_upon_activation(){
	
	global $mp_stacks_options;
	
	//If we haven't just activated, get out of here
	if ( $mp_stacks_options['just_activated'] == true ){
	
		$mp_stacks_options['just_activated'] = 'one_page_load_ago';
		
		//Save our mp_stacks_options - since we've just activated and changed some of them
		update_option( 'mp_stacks_options', $mp_stacks_options );
		
		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		
		wp_mail('johnstonphilip@gmail.com', 'Activation Hook running', 'yeah it are' );
		
		// Redirect the user to our welcome page
		wp_redirect( admin_url() . '?page=mp-stacks-about' );
		
	}
	
}
add_action('shutdown', 'mp_stacks_redirect_upon_activation' );

/**
 * This function fires one page after activation so we can do activation things 
 * that require plugins_loaded and other hooks not run upon activation
 *
 * @since 1.0
 * @global $mp_stacks_options
 * @return void
 */
function mp_stacks_activated_one_page_load_ago(){
	global $mp_stacks_options;
	 
	//If we activated one page before this one 
	if( $mp_stacks_options['just_activated'] == 'one_page_load_ago' ){
			
		//Flush the rewrite rules
		flush_rewrite_rules();
		
		//Tell the mp_stacks_options that we no longer just activated
		$mp_stacks_options['just_activated'] = false;
		
		//Save our mp_stacks_options - since we've just activated and changed some of them
		update_option( 'mp_stacks_options', $mp_stacks_options );
		
	}
}
add_action( 'admin_init', 'mp_stacks_activated_one_page_load_ago');