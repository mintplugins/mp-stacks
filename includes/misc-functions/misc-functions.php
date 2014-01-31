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
            #adminmenu #menu-posts-mp_brick .wp-menu-image:before {
                content: '<?php echo $menu_icon; ?>';
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

/**
 * Ajax callback...
 *
 * @since   1.0.0
 * @link    http://moveplugins.com/doc/
 * @return  array $plugin_array
 */
function mp_stacks_tinyMCE_shortcode_callback() {

	$whatever = $_POST['whatever'];

	$whatever .=  "Hooligans!@";

        echo $whatever;

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mp_stacks_tinyMCE_shortcode', 'mp_stacks_tinyMCE_shortcode_callback' );