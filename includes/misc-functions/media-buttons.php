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
 * Adds an "Add Link" button to TinyMCE because Ajax-Loaded editors don't load WP plugins for some reason. 
 * This may be a temporary fix until we can get tinyMCE to load all WP plugins all the time.
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      function_name()
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_add_link_button_for_wp_editors(){
	
	global $pagenow, $typenow, $wp_version;
		
	/** Only run in post/page creation and edit screens */
	if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) || ( defined('DOING_AJAX') && DOING_AJAX ) ) {
		
		//Only show add link button if 
		if ( 
			//If we are on a brick editor page
			( isset( $typenow ) && $typenow == 'mp_brick') 
			||
			//Or if we are doing ajax
			( defined('DOING_AJAX') && DOING_AJAX )
			
		){
				
			//Output shortcode button
			echo '<a href="#TB_inline?width=640&inlineId=choose-mp_stacks_link" class="thickbox button mp_stacks_link-thickbox mp_stacks_links_button" title="' . __('Add Link', 'mp_core') . '"><span class="wp-media-buttons-icon dashicons dashicons-admin-links" style="font-size:17px;"></span>' . __( 'Add Link', 'mp_stacks' ) . '</a>';
							
		}
			
	}
	
}
add_filter( 'media_buttons', 'mp_stacks_add_link_button_for_wp_editors', 11 );

/**
 * This is for the thickbox popup for the Add Link workaround popup for tinyMCEs. 
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      function_name()
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_admin_footer_link_media_button_thickbox() {
	
	global $pagenow, $typenow;
	
	//Only show add link button if 
	if ( 
		//If we are on a brick editor page
		( isset( $typenow ) && $typenow == 'mp_brick') 
		||
		//Or if we are doing ajax
		( defined('DOING_AJAX') && DOING_AJAX )
		
	){
			
		?>
		
        <script type="text/javascript">
		
				function insert_mp_stacks_link_Shortcode(){
					
					jQuery(document).ready(function($){
							
						// Send the shortcode to the editor ?>
						window.send_to_editor( '<a href="' + $( '#mp_stacks_link_url' ).val() + '" target="' + $( '#mp_stacks_link_open_type' ).val() +'" >' + $( '#mp_stacks_link_text' ).val() + '</a>' );
					
						tb_remove();
					
					});
				}
			
		</script>
		
		<!--Create the hidden div which will display in the Thickbox -->	
		<div id="choose-mp_stacks_link" style="display: none;">
			<div class="wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
				
                <div id="mp_stacks_links">
                	
                	<input type="url" id="mp_stacks_link_url" name="mp_stacks_link_url" placeholder="URL" />
                    
                    <input type="url" id="mp_stacks_link_text" name="mp_stacks_link_text" placeholder="<?php echo __( 'Text', 'mp_stacks' ); ?>" />
                    
                    <select id="mp_stacks_link_open_type" name="mp_stacks_link_open_type">
                      <option selected value="_parent"><?php echo __( 'Open in current browser tab', 'mp_stacks' ); ?></option>
                      <option value="_blank"><?php echo __( 'Open in new browser tab', 'mp_stacks' ); ?></option>
                    </select>
                    
                    <p><?php echo __( 'Tip: You can link to a Brick by using it\'s Brick URL. This will cause it to automatically Scroll to that Brick if it is in the same Stack. For further explanation', 'mp_stacks' ) . ' <a target="_blank" href="https://mintplugins.com/support/brick-urls/">' . __( 'Click Here', 'mp_stacks' ) . '.</a>'; ?></p>
                
                </div>
                
				<p class="submit">
					<input type="button" id="mp_stacks_link" class="button-primary" value="<?php echo __('Insert Link', 'mp_core'); ?>" onclick="insert_mp_stacks_link_Shortcode();" />
					<a id="mp_stacks_link_cancel_download_insert" class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'mp_stacks' ); ?>"><?php _e( 'Cancel', 'mp_stacks' ); ?></a>
				</p>
                
			</div>
		</div>
		<?php
	}
}
add_action( 'admin_footer', 'mp_stacks_admin_footer_link_media_button_thickbox' );


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
