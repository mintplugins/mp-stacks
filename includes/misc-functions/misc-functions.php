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
 * If Menu Order URL variable is set, Make a new field for it on edit pages
 *
 * @since   1.0.0
 * @link    http://moveplugins.com/doc/
 * @return  array $plugin_array
 */
function mp_stacks_display_brick_menu_order_input_field() {
	
	if ( isset($_GET['post_type']) == 'mp_brick' ){
		
		if ( isset($_GET['menu_order'])){
			
			$menu_order = $_GET['menu_order'];
			
		}
		else{
			
			//Get Menu Order Info for this Brick
			$post = get_post(get_the_ID());						
			$menu_order = $post->menu_order;
			$menu_order = !empty($menu_order) ? $menu_order : 1000;
		
		}
		
		echo '<input type="hidden" name="menu_order" value="' . $menu_order . '">';
		
		// Set up nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'mp_stacks_menu_order_nonce' );	
		
	}
	
}
add_action( 'edit_form_top', 'mp_stacks_display_brick_menu_order_input_field' );

/**
 * Set Menu Order of Bricks upon save
 *
 * @since   1.0.0
 * @link    http://moveplugins.com/doc/
 * @return  array $plugin_array
 */
function mp_stacks_save_brick_menu_order( $post_id ) {
	
	if (isset($_POST['menu_order'])){
			
		if ( ! wp_verify_nonce( 'mp_stacks_menu_order_nonce', plugin_basename( __FILE__ ) )  ) {
		
			 die( 'Security check' ); 
		
		} else {
		
			 global $wpdb;
		
			//Save the menu order for this brick			
			$wpdb->update( 'wp_posts', array( 'menu_order' => $_POST['menu_order'] ), array( 'ID' => $post_id ), $format = null, $where_format = null ); 
	
		}
		
	}
}
add_action( 'save_post', 'mp_stacks_save_brick_menu_order', 100);