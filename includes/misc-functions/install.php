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
 * @copyright   Copyright (c) 2015, Mint Plugins
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
	
	//Save our mp_stacks_options - since we've just activated and changed some of them
	update_option( 'mp_stacks_options', $mp_stacks_options );
	
	$active_theme = wp_get_theme();
	
	//Notify
	wp_remote_post( 'http://tracking.mintplugins.com', array(
		'method' => 'POST',
		'timeout' => 1,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => array( 
			'mp_track_event' => true, 
			'event_product_title' => 'MP Stacks', 
			'event_action' => 'activation', 
			'event_url' => get_bloginfo( 'wpurl'),
			'wp_version' => $wp_version,
			'active_plugins' => json_encode( get_option('active_plugins') ),
			'active_theme' => $active_theme->get( 'Name' ),
		),
		'cookies' => array()
		)
	);

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
	
	global $mp_stacks_options, $mp_core_options;
	
	//If we haven't just activated, get out of here
	if ( $mp_stacks_options['just_activated'] == true ){
	
		$mp_stacks_options['just_activated'] = 'one_page_load_ago';
		
		//Save our mp_stacks_options - since we've just activated and changed some of them
		update_option( 'mp_stacks_options', $mp_stacks_options );
		
		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) || ( isset( $mp_core_options['parent_plugin_activation_status'] ) && $mp_core_options['parent_plugin_activation_status'] != 'complete' ) ) {
			
			//Flush the rewrite rules
			flush_rewrite_rules();
			
			//Tell the mp_stacks_options that we no longer just activated so no redirects happen.
			$mp_stacks_options['just_activated'] = false;	
			
			//Save our mp_stacks_options - since we've just activated and changed some of them
			update_option( 'mp_stacks_options', $mp_stacks_options );
		
			return;
		}
				
		//If the core IS active, redirect to the welcome page
		if (function_exists('mp_core_textdomain')){
			// Redirect the user to our welcome page - or other page if an add-on filters this redirect
			wp_redirect( apply_filters( 'mp_stacks_install_redirect', admin_url() . '?page=mp-stacks-about' ) );
			exit();
		}
		//If the mp-core is NOT active, redirect to the mp-core intaller and install any other needed plugins too.
		else{
			wp_redirect( admin_url( sprintf( 'options-general.php?page=mp_core_install_plugins_page&action=install-plugin&_wpnonce=%s', wp_create_nonce( 'install-plugin' ) ) ) );	
			exit();
		}
		
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
	global $mp_stacks_options, $mp_core_options;
	 
	//If we activated one page before this one 
	if( $mp_stacks_options['just_activated'] == 'one_page_load_ago' ){
			
		//Flush the rewrite rules
		flush_rewrite_rules();
		
		//If we were redirected to install mp-core and other required plugins
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'mp_core_install_plugins_page' ){
			
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
			
			// Bail if activating from network, or bulk
			if ( is_network_admin() || isset( $_GET['activate-multi'] ) || ( isset( $mp_core_options['parent_plugin_activation_status'] ) && $mp_core_options['parent_plugin_activation_status'] != 'complete' ) ) {
				
				//Flush the rewrite rules
				flush_rewrite_rules();
				
				//Tell the mp_stacks_options that we no longer just activated so no redirects happen.
				$mp_stacks_options['just_activated'] = false;	
				
				//Save our mp_stacks_options - since we've just activated and changed some of them
				update_option( 'mp_stacks_options', $mp_stacks_options );
			
				return;
			}
		
			// We haven't been to the welcome page yet so Redirect the user to our welcome page - or other page if an add-on filters this redirect
			echo '<script type="text/javascript">';
				echo "window.location = '" . apply_filters( 'mp_stacks_install_redirect', admin_url() . '?page=mp-stacks-about' ) . "';";
			echo '</script>';
			
			echo '</div>';
				
				
		}
		//If we have now installed all needed plugins and the redirections to the welcome page etc have taken place.
		else{
			//Tell the mp_stacks_options that we no longer just activated
			$mp_stacks_options['just_activated'] = false;	
		}
		
		//Save our mp_stacks_options - since we've just activated and changed some of them
		update_option( 'mp_stacks_options', $mp_stacks_options );
		
	}
}
add_action( 'admin_footer', 'mp_stacks_activated_one_page_load_ago');