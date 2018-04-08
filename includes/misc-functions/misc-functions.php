<?php
/**
 * This file contains various functions
 *
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Action hook which brick metaboxes can use so they only load on brick and brick related admin apges
 *
 * @since 1.0
 * @return void
*/
function mp_brick_metabox() {

	//Get current page
	$current_page = get_current_screen();

	//Only load if we are on an mp_brick page
	if ( $current_page->id == 'mp_brick' || $current_page->id == 'settings_page_mp_stacks_create_template_page' ){

		//Use this action hook to run the metabox creation MP Core class for brick related metaboxes. This exists purely for the name to remind of how the metabox is being loaded.
		do_action( 'mp_brick_non_ajax_metabox' );

		//Hook Ajax-Loading Metaboxes here.
		do_action( 'mp_brick_ajax_metabox' );

		//This is an old hook that metaboxes used to use before ajax-loading metaboxes were built for MP Stacks.
		//It remains here in a commented-out fashion purely as a reminder of the history of these filter names.
		//do_action( 'mp_brick_metabox' );

	}

}
add_action('current_screen', 'mp_brick_metabox');

/**
 * Remove "hentry" from bricks post_class
 *
 * @since 1.0
 * @return void
*/
function mp_stacks_remove_hentry( $classes, $class = '', $post_id = '' ) {

	if ( !$post_id || get_post_type( $post_id ) !== 'mp_brick' ){
		return $classes;
	}

	if ( ( $key = array_search( 'hentry', $classes ) ) !== false ) {
		unset( $classes[ $key ] );
	}

	return $classes;
}
add_filter( 'post_class', 'mp_stacks_remove_hentry', 100, 3 );

/**
 * Remove "hentry" from page post_class if the page template is "Optimize for MP Stacks"
 *
 * @since 1.0
 * @param array  $classes An array of post classes.
 * @param string $class   A comma-separated list of additional classes added to the post.
 * @param int    $post_id The post ID.
 * @return void
*/
function mp_stacks_remove_hentry_from_stack_page_templates( $classes, $class, $post_id ) {

	$class_name_counter = 0;

	//Loop through each class name
	foreach( $classes as $class_name ){

		$page_template_slug = get_page_template_slug( $post_id  );

		//If one of the class names is hentry
		if ( $class_name == 'hentry' ){
			//If we are using the mp-stacks-page-template
			if ( get_page_template_slug( $post_id  ) == 'mp-stacks-page-template.php' ){
				//Remove hentry from the classes array
				$classes[$class_name_counter] = '';
			}

			//If we are using the default page template but it has had its title converted to include the word 'stack'
			else if ( empty( $page_template_slug ) ){

				//Check the title of the default page template - This filter: https://core.trac.wordpress.org/ticket/27178
				$default_page_template_title = apply_filters( 'default_page_template_title', __('Default Template') );

				//If the default page template's title includes the word "Stack"
				if ( strpos( $default_page_template_title, 'Stack' ) !== false ){
					//Remove hentry from the classes array
					$classes[$class_name_counter] = '';
				}
			}

		}

		$class_name_counter = $class_name_counter + 1;
	}

	return $classes;
}
add_filter( 'post_class', 'mp_stacks_remove_hentry_from_stack_page_templates', 10, 3 );

/**
 * If there's no js in admin, let them know that life is too short for that.
 *
 * @since 1.0
 * @return void
*/
function mp_stacks_brick_edit_page_no_js_message() {

	global $post;

	if ( isset( $post->post_type) && $post->post_type == 'mp_brick' ){


		echo '<noscript>
			<style type="text/css">
				.wrap {display:none;}
			</style>
			<div class="noscriptmsg error">
			' . __( "You don't have javascript enabled. Life's too short for that! Turn it on and then let's get cookin'!", 'mp_stacks' ) . '
			</div>
		</noscript>';

	}
}
add_action( 'all_admin_notices', 'mp_stacks_brick_edit_page_no_js_message');

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
 * @link    http://mintplugins.com/doc/
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
 * Add mp_stack stylesheet to the Gutenberg styles, so that the shortcode is replaced with the MP Stack icon
 *
 * @since    1.0.0
 * @link     http://codex.wordpress.org/Function_Reference/add_editor_style
 * @see      get_bloginfo()
 * @param    array $wp See link for description.
 * @return   void
 */
function mp_stacks_gutenbergtheme_editor_styles() {
	wp_enqueue_style( 'mp_stacks_gutenbergthemeblocks_style', plugins_url( '/css/', dirname(__FILE__) ) . 'mp-stacks-tinyMCE-style.css' );
}
add_action( 'enqueue_block_editor_assets', 'mp_stacks_gutenbergtheme_editor_styles' );

/**
 * Get all the brick titles in this stack
 *
 * @since    1.0.0
 * @link     http://codex.wordpress.org/Function_Reference/add_editor_style
 * @see      get_bloginfo()
 * @param    array $wp See link for description.
 * @return   void
 */
function mp_stacks_get_brick_titles_in_stack( $stack_id ) {

	//Set default for the brick titles in this stack
	$brick_titles_in_stack = array();

	//Get all Bricks in the current Stack - if a stack id has been passed to the URL
	if ( isset( $stack_id ) ){

		//Set the args for the new query
		$mp_stacks_args = array(
			'post_type' => "mp_brick",
			'posts_per_page' => -1,
			'meta_key' => 'mp_stack_order_' . $stack_id,
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'mp_stacks',
					'field'    => 'id',
					'terms'    => array( $stack_id ),
					'operator' => 'IN'
				)
			)
		);

		//Create new query for stacks
		$mp_stack_query = new WP_Query( $mp_stacks_args );

		//Loop through the stack group
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post();

			//Add the title of each brick in this stack to the array. This way, we can easily create links to each brick
			array_push( $brick_titles_in_stack, '#' . sanitize_title( get_the_title() ) );

		endwhile;

	}

	return $brick_titles_in_stack;
}

/**
 * Function which create the admin notice for the mp_brick editor
 *
 * @since    1.0.0
 * @param    void
 * @return   void
 */
function mp_stacks_support_admin_notice(){

	 global $pagenow;

	  //Only load message if mp_stack_id is set
	 if ( (isset( $_GET['mp_stack_id'] ) ) && ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) ){

	 }
	 else{
		 return;
	 }

	 $stack_info = get_term( $_GET['mp_stack_id'], 'mp_stacks' );

	 if ( isset( $stack_info->name ) ){

		 ?>
		 <div class="mp-stacks-editor-title-notice updated" style="display:none;">
			<p><?php echo __( 'You are editing a "Brick" in the "Stack" called "' . $stack_info->name . '".', 'mp_stacks'); ?><br />
			<?php echo __(' Having trouble? Feel free to email us:', 'mp_stacks' ) . ' <strong>support@mintplugins.com</strong>'; ?></p>
		 </div>
		 <?php

	 }

}

/**
 * Function which adds extra "safe" styles to wp_kses
 *
 * @since    1.0.0
 * @param    array $safe_styles This is an array of the css style names that are 'safe'
 * @return   void
 */
function mp_stacks_wpkses_safe_styles( $safe_styles ){

	array_push( $safe_styles, 'white-space' );
	return $safe_styles;

}
add_filter( 'safe_style_css', 'mp_stacks_wpkses_safe_styles' );

/**
 * When we delete a Stack, we need to delete all bricks posts attached to that Stack as well
 *
 * @since    1.0.0
 * @param    int $deleted_stack_term_taxonomy_id The term_taxonomy_id (NOTE: not the term id) of the stack we are deleting
 * @return   void
 */
function mp_stacks_delete_stack( $deleted_stack_term_taxonomy_id ){

	$deleted_stack_id = get_term_by( 'term_taxonomy_id', $deleted_stack_term_taxonomy_id, 'mp_stacks' );
	$deleted_stack_id = $deleted_stack_id->term_id;

	//Loop through each brick that was in this stack using the meta_key mp_stack_order_' . $deleted_stack_id

	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
		'meta_key' => 'mp_stack_order_' . $deleted_stack_id,
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
	);

	//Create new query for stacks
	$mp_stack_query = new WP_Query( $mp_stacks_args );

	//Loop through the stack group
	if ( $mp_stack_query->have_posts() ) {

		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post();

			//Delete this brick
			wp_delete_post( get_the_ID(), true );

		endwhile;

	}

}
add_action( 'delete_term_taxonomy', 'mp_stacks_delete_stack' );

/**
 * If we are on an "Add New Brick" or "Edit Brick" page, temporarily set the title to be "Loading Brick..." - we update it later using JS
 *
 * @since    1.0.0
 * @param    void
 * @return   void
 */
function mp_stacks_edit_brick_loading_title(){

	global $title;

	if ( $title == __( 'Add New Brick', 'mp_stacks' ) || $title == __( 'Edit Brick', 'mp_stacks' ) ){
		$title = __( 'Brick Editor', 'mp_stacks' );
	}

}
add_action( 'admin_head', 'mp_stacks_edit_brick_loading_title' );

/**
 * Check if Knapstack exists on this install. If not, add a pseudo version so the user can install it
 *
 * @since    1.0.0
 * @param    void
 * @return   void
 */
function mp_stacks_add_knapstack_to_themes( $themes ){

	 //If knapstack is not in existence on this install
	 if ( !array_key_exists( 'knapstack', $themes) ){

		 //Add a pseudo version so the user can easily install it
		 $knapstack= array(
		 	'knapstack' => array(
				'id' => 'knapstack',
				'name' => 'KnapStack Theme',
				'screenshot' => array( 'https://mintplugins.com/dynamic-images/knapstack-screenshot.png' ),
				'description' => __( 'A Clean, Responsive, Retina, Flat-Styled WordPress theme with Unlimited Page Layout variations controlled using the MP Stacks Plugin.', 'mp_stacks' ),
				'author' => 'Mint Plugins',
				'authorAndUri' => '<a href="https://mintplugins.com/">Mint Plugins</a>',
				'version' => 'Latest',
				'tags' => 'One-Column',
				'parent' => NULL,
				'active' => NULL,
				'hasUpdate' => NULL,
				'update' => NULL,
				'actions' => array(
					'activate' => admin_url( sprintf( 'options-general.php?page=mp_core_install_plugins_page&action=install-plugin&mp_stacks_install_knapstack&_wpnonce=%s', wp_create_nonce( 'install-plugin' ) ) ),
					'preview' => 'http://demo.mintplugins.com/knapstack/'
				)
			)
		 );

		 $themes = $knapstack + $themes;
	}

	return $themes;

}
add_filter( 'wp_prepare_themes_for_js', 'mp_stacks_add_knapstack_to_themes' );

/**
 * If the URL has 'mp_stacks_install_knapstack' in it, hook in the check for Knapstack
 *
 * @since    1.0.0
 * @param    void
 * @return   void
 */
function mp_stacks_prepare_knapstack_for_install(){

	//If knapstack already exists and we don't need to prepare it for installation, get out of here
	if ( !isset( $_GET['mp_stacks_install_knapstack'] ) ){
			return;
	}

	add_filter( 'mp_core_check_plugins', 'mp_knapstack_plugin_check' );

}
add_action( 'init', 'mp_stacks_prepare_knapstack_for_install' );

/**
* Output JUST the css for a stack
*
* @since    1.0.0
* @link     https://mintplugins.com/
* @param    array $rules
* @return   array $rules
*/
function mp_stacks_css_page(){

   if ( !isset( $_GET['mp_stacks_css_page'] )){
	   return false;
   }

   header('Content-Type: text/css');

   //Output CSS for this stack
	echo mp_stack_css( $_GET['mp_stacks_css_page'] );

	die();

}
add_action('init', 'mp_stacks_css_page');


/**
* Output JUST the css for a brick
*
* @since    1.0.0
* @link     https://mintplugins.com/
* @param    array $rules
* @return   array $rules
*/
function mp_stacks_brick_css_page(){

   if ( !isset( $_GET['mp_brick_css_page'] )){
	   return false;
   }

   header('Content-Type: text/css');

   //Output CSS for this stack
	echo mp_brick_css( $_GET['mp_brick_css_page'] );

	die();

}
add_action('init', 'mp_stacks_brick_css_page');

/**
* Get the time() when a Stack was last modified
*
* @since    1.0.0
* @link     http://bullmandesign.com/quick-tips/too-much-of-a-good-thing
* @param    int $stack_id
* @return   string $time when the stack was lasst modified
*/
function mp_stack_last_modified( $stack_id ){

	$stack_meta = get_option( 'mp_stack_meta_' . $stack_id );

	return $stack_meta['last_modified'];

}

/**
* Update Stack Meta Info each time a Brick within that Stack is saved
*
* @since    1.0.0
* @param    int $stack_id
* @return   string $time when the stack was last modified
*/
function mp_stack_update_meta_upon_brick_save(){

	//Check if post type has been set
	$this_post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : NULL;

	//If this is a brick in MP Stacks
	if ( $this_post_type != 'mp_brick' || !isset( $_POST['mp_stack_order'] ) || !is_array( $_POST['mp_stack_order'] ) ){
		return false;
	}

	//Get the Stack ID this brick is in
	foreach($_POST['mp_stack_order'] as $mp_stack_id => $mp_stack_order_value){
		$stack_id = $mp_stack_id;
	}

	//Get the pre-existing Stack Meta
	$stack_meta = get_option( 'mp_stack_meta_' . $stack_id );

	//Set the last modified date
	$stack_meta['last_modified'] = time();

	//Update the Stack Meta
	update_option( 'mp_stack_meta_' . $stack_id, $stack_meta );

}
add_action( 'save_post', 'mp_stack_update_meta_upon_brick_save' );

/**
* After a Brick is saved, and all of its meta has been saved as well, die. This way, the Brick Editor doesn't need to re-load entirely before the Editor Lightbox closes.
* This cuts the  percieved save-time in HALF. Only do this if the window is open in a lightbox using the mp_stacks_do_not_reload_after_brick_save input field added by js.
*
* @since    1.0.0
* @param    void
* @return   void
*/
function mp_stacks_no_brick_editor_after_save(){

	$this_post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : NULL;

	if ( isset( $_POST['mp_stacks_do_not_reload_after_brick_save'] ) && $this_post_type == 'mp_brick' ){
		die();
	}

}
add_action( 'save_post', 'mp_stacks_no_brick_editor_after_save', 999 );

//Add the slug of a brick to the footer
function mp_stacks_brick_footer_slug( $text ){

	global $post;

	if ( isset( $post->post_type ) && $post->post_type == 'mp_brick' ){

		if ( isset( $post->ID ) && !empty( $post->ID ) ){

			$bruck_url_slug = sanitize_title( get_the_title( $post->ID ) );

			if ( !empty( $bruck_url_slug ) ) {
				return __( 'This Brick\'s URL', 'mp_stacks' ) . ': <strong>#' . sanitize_title( get_the_title( $post->ID ) ) . '</strong><br />' . __('To make a browser scroll to this brick, link to that as the URL. For further explanation', 'mp_stacks' ) . ' <a target="_blank" href="https://mintplugins.com/support/brick-urls/">' . __( 'Click Here', 'mp_stacks' ) . '.</a>';
			}
			else{
				return __('To make a browser scroll to this brick, first give it a title at the very top of this page. For further explanation', 'mp_stacks' ) . ' <a target="_blank" href="https://mintplugins.com/support/brick-urls/">' . __( 'Click Here', 'mp_stacks' ) . '.</a>';
			}
		}
		else{
			return __( 'Thank you for creating with WordPress and MP Stacks.', 'mp_stacks' );
		}

	}

	return $text;

}
add_filter( 'admin_footer_text', 'mp_stacks_brick_footer_slug' );

//This function outputs the links to copy and paste a Brick on the bottom right hand side of the Brick Editor. It replaces the WordPress version info on Brick Editor pages.
function mp_stacks_exportimport_brick_link( $text ){

	global $post;

	$return_output = NULL;

	if ( isset( $post->post_type ) && $post->post_type == 'mp_brick' && isset( $post->ID ) ){

		//Add links to copy/paste brick settings
		$return_output .=  '<a id="mp-stacks-main-export-brick-link" href="#TB_inline?width=640&inlineId=mp-stacks-export-brick-thickbox" class="thickbox mp-stacks-export-brick-thickbox" title="' . __('Export/Import Brick Settings', 'mp_core') . '"><span class="dashicons dashicons-external"></span></a>';

		ob_start();
		?>

		<div id="mp-stacks-export-brick-thickbox" style="display: none;">
			<div class="wrap" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;">

            <div class="mp-stacks-brick-export-import-container">

            	<a id="mp-stacks-export-brick-link" href="<?php echo esc_url( add_query_arg( array(
					'mp_stacks_brick_json' => true,
					'mp_brick_id' => $post->ID
					), get_bloginfo( 'wpurl' ) ) ); ?>">
                    <div id="mp-stacks-export-brick" class="mp-stacks-brick-export-action-choice export-brick">
                        <div class="mp-stacks-export-brick-action-icon"></div>
                        <div class="mp-stacks-export-brick-action-title"><?php echo __( 'Export this Brick', 'mp_stacks' ); ?></div>
                        <div class="mp-stacks-export-brick-action-description"><?php echo __( 'Export all the settings and options for this brick to save or use for another Brick.', 'mp_stacks' ); ?></div>
                    </div>
                </a>

                <div id="mp-stacks-import-brick" class="mp-stacks-brick-export-action-choice import-brick">
                    <div class="mp-stacks-import-brick-action-icon"></div>
                    <div class="mp-stacks-import-brick-action-title"><?php echo __( 'Import a Brick', 'mp_stacks' ); ?></div>
                    <div class="mp-stacks-import-brick-action-description"><?php echo __( 'This will overwrite all existing options for this Brick with a different Brick\'s options.', 'mp_stacks' ); ?></div>

                 <div class="mp-stacks-clearedfix"></div>

                 <div id="mp-stacks-import-brick-form">
                 <?php

					//If this is a new brick is being saved.
					if ( isset( $_GET['mp_stack_order_new'] ) && isset( $_GET['mp_stack_id'] ) ){

						$stack_id = $_GET['mp_stack_id'];
						$stack_order = $_GET['mp_stack_order_new'];
					}
					else{

						$stack_id = get_post_meta( $post->ID, 'mp_stack_id', true );
						$stack_order = mp_core_get_post_meta( $post->ID, 'mp_stack_order_' . $stack_id );

					}

				 ?>
                     <textarea id="mp-brick-json-to-import" class="mp-stacks-import-brick-textarea" rows="6" cols="30" placeholder="<?php echo __( 'Copy and Paste the Brick Code you have previously exported', 'mp_stacks' ); ?>"></textarea>
                     <div class="button" id="mp-stacks-import-brick-submit-btn" mp-brick-id="<?php echo $post->ID; ?>" mp-stack-id="<?php echo $stack_id; ?>" mp-stack-order="<?php echo $stack_order; ?>"><?php echo __( 'Overwrite Brick', 'mp_stacks' ); ?></div>
                 </div>

                </div>
            </div>

			<div id="mp-brick-json"></div>

			</div>
		</div>

        <?php

		$return_output .= ob_get_clean();

		return $return_output;
	}

	return $text;

}
add_filter( 'update_footer', 'mp_stacks_exportimport_brick_link', 11 );
add_filter( 'core_update_footer', 'mp_stacks_exportimport_brick_link', 11 );

//Add mp-stacks-wp-queried-id to the body class on any page/post. This way, stacks can reference that ID for things like "related posts" in ajax requests.
function mp_stacks_body_class_queried_object_id($classes) {
       global $wp_query;
	   $queried_object_id = $wp_query->queried_object_id;

       $classes[] = 'mp-stacks-queried-object-id-'. $wp_query->queried_object_id;
       return $classes;
}
add_filter('body_class', 'mp_stacks_body_class_queried_object_id');


//Remove all actions that load content into the admin sidebar if we are on a "Brick Editor" page
function mp_stacks_remove_admin_menu_for_brick_editor(){

	$current_screen = get_current_screen();

	if ( isset( $current_screen->post_type ) && isset( $current_screen->base ) && $current_screen->post_type == 'mp_brick' && $current_screen->base == 'post' ){
		global $menu;
		$menu = array();
		remove_all_actions( 'adminmenu' );
		remove_all_actions( 'admin_menu' );

		//Remove all Admin Notices on Brick Editor pages
		remove_all_actions( 'admin_notices' );

		//Show admin notice about which brick the user is editing.
		//add_action('admin_notices', 'mp_stacks_support_admin_notice');

	}
}
add_action( 'admin_head', 'mp_stacks_remove_admin_menu_for_brick_editor' );

//Add Minimal admin styling for brick editors no matter what
function mp_stacks_remove_admin_bar_for_brick_editor(){

	$current_screen = get_current_screen();

	if ( $current_screen->post_type == 'mp_brick' && $current_screen->base == 'post' ){
		//Hide admin items for edit brick screen - css
		wp_enqueue_style( 'mp_stacks_minimal-admin-css', plugins_url('css/mp-stacks-minimal-admin.css', dirname(__FILE__)), MP_STACKS_VERSION );
	}
}
add_action( 'admin_enqueue_scripts', 'mp_stacks_remove_admin_bar_for_brick_editor' );

/**
 * Output the JSON for a Brick when requested by a POST
 *
 * @since    1.0.0
 * @link     http://codex.wordpress.org/Function_Reference/add_editor_style
 * @see      get_bloginfo()
 * @param    array $wp See link for description.
 * @return   void
 */
function mp_stacks_display_brick_json(){

	//If a stack id has been passed to the URL
	if ( isset( $_GET['mp_stacks_brick_json'] ) && isset( $_GET['mp_brick_id'] ) && current_user_can( 'delete_pages' ) ){

		//Convert this page to a zip file
		header('Content-disposition: attachment; filename='. sanitize_title(get_the_title( $_GET['mp_brick_id'] )) . '-' . $_GET['mp_brick_id'] . '.txt');
		header('Content-type: application/octet-stream');

		//Output the JSON for this brick
		echo mp_stacks_brick_json( intval( $_GET['mp_brick_id'] ) );

		die();

	}
}
add_action( 'init', 'mp_stacks_display_brick_json' );

/**
 * JetPack loads a strange file that can break image sizes called devicepx. This line removes it from loading and fixes those bugs it causes.
 *
 * @since    1.0.0
 * @link     http://mintplugins.com
 * @see      get_bloginfo()
 * @param    void
 * @return   void
 */
function mp_stacks_remove_devicepx(){

	wp_dequeue_script('devicepx');

}
add_action('wp_enqueue_scripts', 'mp_stacks_remove_devicepx', 20);

/**
 * JetPack loads file through their service called photon, but this breaks the resizing we do in MP core.
 * Unfortunately this is the only way to workaround this right now.
 *
 * @since    1.0.0
 * @link     http://mintplugins.com
 * @see      get_bloginfo()
 * @param    void
 * @return   void
 */
function mp_stacks_no_jetpack_photon() {
    add_filter( 'jetpack_photon_development_mode', '__return_true');
}
add_action('wp', 'mp_stacks_no_jetpack_photon');
