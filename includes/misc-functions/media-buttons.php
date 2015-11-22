<?php
/**
 * Wecome Page for Mp Stacks
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

/**
 * Add a media button to let the user easily install the MP Buttons plugin (if the plugin isn't installed)
 *
 * Returns the "Insert Shortcode" TinyMCE button.
 *
 * @access     public
 * @since      1.0.0
 * @global     $pagenow
 * @global     $typenow
 * @global     $wp_version
 * @param      string $context The string of buttons that already exist
 * @return     string The HTML output for the media buttons
*/
function mp_stacks_install_mp_buttons_shortcode_btn( $context ){
		
	global $pagenow, $typenow, $wp_version;
		
	/** Only run in post/page creation and edit screens */
	if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) || ( defined('DOING_AJAX') && DOING_AJAX ) ) {
		
		//Only run if MP Buttons plugin is not installed 
		if ( !function_exists( 'mp_buttons_textdomain' ) 
			//And
			&& 
			( 
				//If we are on a brick editor page
				( isset( $type_now ) && $type_now == 'mp_brick') 
				||
				//Or if we are doing ajax
				( defined('DOING_AJAX') && DOING_AJAX )
			)
		){
				
				
			//Output shortcode button
			echo '<a target="_blank" href="' . admin_url( sprintf( 'options-general.php?page=mp_core_install_plugin_page_mp-buttons&action=install-plugin&mp-source=mp_core_directory&plugin=mp-buttons&plugin_api_url=' . base64_encode( 'http://mintplugins.com' ) . '&mp_core_directory_page=mp_stacks_plugin_directory&mp_core_directory_tab=content_types&_wpnonce=%s', wp_create_nonce( 'install-plugin'  ) ) ) . '" class="button" title="' . __('Install Button Creator', 'mp_core') . '">' . __( 'Install Button Creator from Mint Plugins (free)', 'mp_stacks' ) . '</a>';
							
		}
			
	}
	
}
add_filter( 'media_buttons', 'mp_stacks_install_mp_buttons_shortcode_btn', 11 );
