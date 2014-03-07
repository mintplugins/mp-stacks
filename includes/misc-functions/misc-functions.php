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
 * Admin Stacks Icon
 *
 * Echoes the CSS for the downloads post type icon.
 *
 * @since 1.0
 * @global $post_type
 * @global $wp_version
 * @return void
*/
function mp_stacks_admin_stacks_and_bricks_icon() {
	global $post_type, $wp_version;

    $menu_icon   = '\f214';
	?>
    <style type="text/css" media="screen">
			#adminmenu #toplevel_page_mp-stacks-about .wp-menu-image:before {
				background: url("<?php echo plugins_url('assets/images/mp_stack-icon-2x.png', dirname(dirname(__FILE__) ) ); ?>") no-repeat scroll;
				content: '';
				background-size: 20px;
				background-position-y: 6px;
			}
			#mp_stack-media-button {
				background: url("<?php echo plugins_url('assets/images/mp_stack-icon-2x.png', dirname(dirname(__FILE__) ) ); ?>") no-repeat scroll;
				content: '';
				background-size: 16px;
				background-position-y: -1px;
			}
	</style>
	<?php
}
add_action( 'admin_head','mp_stacks_admin_stacks_and_bricks_icon' );

/**
 * Make the mp_stacks shortcode display the stack editor for TinyMCE
 *
 * @since   1.0.0
 * @link    http://moveplugins.com/doc/
 * @param   array $plugin_array See link for description.
 * @return  array $plugin_array
 */
function mp_stacks_add_stacks_tinymce_plugin($plugin_array) {
 	if ( get_user_option('rich_editing') == 'true') {
		$plugin_array['mpstacks'] =  plugins_url( '/js/', dirname(__FILE__) ) . 'mp-stacks-tinymce.js';
	}
    return $plugin_array;
}
add_filter("mce_external_plugins", "mp_stacks_add_stacks_tinymce_plugin");

/**
 * Set the Shortcode "representor" in TINYMCE upon insert
 *
 * @since   1.0.0
 * @link    http://moveplugins.com/doc/
 * @param   array $plugin_array See link for description.
 * @return  array $plugin_array
 */
function mp_stacks_call_stacks_tiny_mce_plugin_on_insert($plugin_array) {
	echo "tinyMCE.activeEditor.execCommand('MP_Stacks');";
}
add_action('mp_core_shortcode_' . 'mp_stack' . '_insert_event', 'mp_stacks_call_stacks_tiny_mce_plugin_on_insert' );

/**
 * Add mp_stack stylesheet to the TinyMCE styles
 *
 * @since    1.0.0
 * @link     http://codex.wordpress.org/Function_Reference/add_editor_style
 * @see      get_bloginfo()
 * @param    array $wp See link for description.
 * @return   void
 */
function mp_stacks_addTinyMCELinkClasses( $wp ) {	
	add_editor_style( plugins_url( '/css/', dirname(__FILE__) ) . 'mp-stacks-tinyMCE-style.css' ); 
}
add_action( 'mp_core_editor_styles', 'mp_stacks_addTinyMCELinkClasses' );