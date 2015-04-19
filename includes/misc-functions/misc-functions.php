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
		
		//Use this action hook to run the metabox creation MP Core class for brick related metaboxes
		do_action( 'mp_brick_metabox' );
		
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
 * @return void
*/
function mp_stacks_remove_hentry_from_stack_page_templates( $classes ) {
	global $post;
	
	$class_name_counter = 0;
	
	//Loop through each class name
	foreach( $classes as $class_name ){
		
		$page_template_slug = get_page_template_slug( $post->ID );
		
		//If one of the class names is hentry
		if ( $class_name == 'hentry' ){
			//If we are using the mp-stacks-page-template
			if ( get_page_template_slug( $post->ID ) == 'mp-stacks-page-template.php' ){
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
add_filter( 'post_class', 'mp_stacks_remove_hentry_from_stack_page_templates' );

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
			<p><?php echo __( 'You are editing a "Brick" in the "Stack" called "' . $stack_info->name . '".', 'mp_stacks'); ?>
			<?php echo __(' Having trouble? Feel free to email us:', 'mp_stacks' ) . ' <strong>support@mintplugins.com</strong> ' . __( 'and we\'ll be glad to help you out!', 'mp_stacks' ); ?></p>
		 </div>
		 <?php
	
	 }
	
}
add_action('admin_notices', 'mp_stacks_support_admin_notice');

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
		$title = __( 'Loading Brick Editor...', 'mp_stacks' );
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
* Check to make sure the KnapStack Theme is installed.
*
* @since    1.0.0
* @link     http://mintplugins.com/doc/plugin-checker-class/
* @return   array $plugins An array of plugins to be installed. This is passed in through the mp_core_check_plugins filter.
* @return   array $plugins An array of plugins to be installed. This is passed to the mp_core_check_plugins filter. (see link).
*/
if (!function_exists('mp_knapstack_plugin_check')){
	function mp_knapstack_plugin_check( $plugins ) {
		
		$add_plugins = array(
			array(
				'plugin_name' => 'KnapStack Theme',
				'plugin_message' => __('You require the KnapStack Theme. Install it here.', 'mp_knapstack'),
				'plugin_filename' => '',
				'plugin_download_link' => 'http://mintplugins.com/repo/knapstack-theme/?downloadfile=true',
				'plugin_api_url' => 'https://mintplugins.com/',
				'plugin_info_link' => 'http://mintplugins.com/knapstack-theme',
				'plugin_group_install' => true,
				'plugin_licensed' => false,
				'plugin_licensed_parent_name' => NULL,
				'plugin_required' => true,
				'plugin_wp_repo' => true,
				'plugin_is_theme' => true,
			)
		);
		
		return array_merge( $plugins, $add_plugins );
	}
}

/**
* There is a potential that some webhosts might have a limit on the numbver of meta options a single post can save at any given time. Here, we make sure that number is 5000
*
* @since    1.0.0
* @link     http://bullmandesign.com/quick-tips/too-much-of-a-good-thing
* @param    array $rules 
* @return   array $rules 
*/
function mp_stacks_htaccess_contents( $rules ){
   	
    return $rules . "
# Allow more custom fields - Added by MP Stacks.
php_value max_input_vars 5000";

}
//add_filter('mod_rewrite_rules', 'mp_stacks_htaccess_contents');


/**
* Output JUST the css for a stack 
*
* @since    1.0.0
* @link     http://bullmandesign.com/quick-tips/too-much-of-a-good-thing
* @param    array $rules 
* @return   array $rules 
*/
function mp_stacks_css_page(){
   	
   if ( !isset( $_GET['mp_stacks_css_page'] )){
	   return false;
   }
   
   header('Content-Type: text/css');
   
   //Output CSS for this stack
	echo mp_stack_css( $_GET['mp_stacks_css_page'], false, false );
	
	die();
					
}
add_action('init', 'mp_stacks_css_page');

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
 * Theme Bundle Installation Function: This function will check if we've created this Theme Bundle's Default Stacks and corresponding Pages/Posts
 * If they haven't been created - or just don't exist (they've been deleted), re-create them. 
 
 * Additionally we can apply Stacks to certain roles. If a page is supposed to be the 'home' page, set that as well.
 * If a stack is supposed to be the 'footer' stack, set that as well.
 
 * @since 1.0
 * @param $theme_bundle_slug The slug of the theme bundle using underscores.
 * @return void
 */
function mp_stacks_theme_bundle_create_default_pages( $theme_bundle_slug ){
		
	/*The $default_stacks_to_create filtered array is formatted like so:
	//array(
		'post_type (we'll create a post for each item in this array and put the corresponding Stack onto it)' => array(
			'stack's template_slug' => array( 'is_home' => true ),
			'stack's template_slug' => array( 'is_footer' => true ),
			'stack's template_slug' => array(),
		),
		'post' => array(
			'stack's template_slug' => array(),
			'stack's template_slug' => array(),
			'stack's template_slug' => array(),
		),
		'page' => array(
			'stack's template_slug' => array(),
			'stack's template_slug' => array(),
			'stack's template_slug' => array(),
		),
		'download' => array(
			'stack's template_slug' => array(),
			'stack's template_slug' => array(),
			'stack's template_slug' => array(),
		),	
	);
	*/
	
	//Set up a default empty array for us to begin filtering
	$default_stacks_to_create = array(
		'post' => array(),
		'page' => array(),
	);
	
	$default_stacks_to_create = apply_filters( $theme_bundle_slug . '_default_stacks', $default_stacks_to_create );
	
	//Get the option where we save all default-created stacks
	$previously_created_default_stacks = get_option( 'mp_stacks_default_stacks_created' );
	
	//Loop through each post type in the $default_stacks_to_create
	foreach( $default_stacks_to_create as $post_type => $stacks_to_create ){
		
		//Loop through each stack to create for this post type
		foreach( $stacks_to_create as $stack_template_slug => $other_info ){
			
			//If a default stack doesn't exist for this template
			if ( !isset( $previously_created_default_stacks[$stack_template_slug]['stack_id'] ) || !get_term_by('id', $previously_created_default_stacks[$stack_template_slug]['stack_id'], 'mp_stacks') ){
				
				$stack_template_function_name = 'mp_stacks_' . $stack_template_slug . '_array';
				
				//Create a new stack using this stack template.
				$new_stack_id = mp_stacks_create_stack_from_template( $stack_template_function_name(), isset( $other_info['title'] ) ? $other_info['title'] : ucwords( str_replace( '_', ' ', $stack_template_slug ) ) );
									
				//Add this stack to the list of default stacks we've created for this stack template
				$previously_created_default_stacks[$stack_template_slug]['stack_id'] = $new_stack_id;		
				
			}
			
			//If there should be a corresponding post for this stack
			if ( $post_type != 'none' ){
			
				//If a default corresponding post doesn't exist for this template (it has never been created before, OR it has been deleted) 
				if ( !isset( $previously_created_default_stacks[$stack_template_slug]['post_id'] ) || ( isset( $previously_created_default_stacks[$stack_template_slug]['post_id'] ) && !mp_core_post_exists( $previously_created_default_stacks[$stack_template_slug]['post_id'] ) ) ){
					
					//Set up the new post to use
					$default_post = array(
					  'post_title'    => isset( $other_info['title'] ) ? $other_info['title'] : ucwords( str_replace( '_', ' ', $stack_template_slug ) ),
					  'post_content'  => '[mp_stack stack="' . $previously_created_default_stacks[$stack_template_slug]['stack_id'] . '"]',
					  'post_type'	  => $post_type,	
					  'post_status'   => 'publish',
					  'post_author'   => 1,
					  'comment_status' => 'closed'
					);
					
					//Creat the new default post and assign the Stack to be on the page
					$new_post_id = wp_insert_post( $default_post );
					
					//Add this post to the list of default posts we've created for this stack template
					$previously_created_default_stacks[$stack_template_slug]['post_id'] = $new_post_id;					
				
				}
				//If a default corresponding post does exist, make sure it has the current default stack too.
				else{
					
					$default_post = array(
						'ID'           => $previously_created_default_stacks[$stack_template_slug]['post_id'],
						'post_content' => '[mp_stack stack="' . $previously_created_default_stacks[$stack_template_slug]['stack_id'] . '"]',
					);
					
					// Update the post into the database
					wp_update_post( $default_post );
  				
				}
				
			}
			
			//If this stack template/corresponding post is supposed to be the homepage
			if ( isset( $other_info['is_home'] ) && $other_info['is_home'] ){
				
				//Set the home page to be this Stack Template/Corresponding Post
				update_option( 'page_on_front', $previously_created_default_stacks[$stack_template_slug]['post_id'] );
				update_option( 'show_on_front', 'page' );
				
			}
			
			//If this stack template/corresponding post is supposed to be the footer
			if ( isset( $other_info['is_footer'] ) && $other_info['is_footer'] ){		

				//Set the footer to be this Stack Template/Corresponding Post
				set_theme_mod( 'mp_stacks_footer_stack', $previously_created_default_stacks[$stack_template_slug]['stack_id'] );
				
			}
			
			//If this stack template/corresponding post requires a page template
			if ( isset( $other_info['page_template'] ) ){		

				//Set the page template of this post
				update_post_meta( $previously_created_default_stacks[$stack_template_slug]['post_id'], '_wp_page_template', $other_info['page_template'] );
				
			}
			
			//If this stack template/corresponding post requires a post format
			if ( isset( $other_info['post_format'] ) ){		

				//Set the post format of this post
				set_post_format( $previously_created_default_stacks[$stack_template_slug]['post_id'], $other_info['post_format'] );	
				
			}
			
			//If a corresponding post exists for this stack
			if ( isset( $previously_created_default_stacks[$stack_template_slug]['post_id'] ) ){
				//Disable Comments on this corresponding post
				wp_update_post( array( 
					'ID' => $previously_created_default_stacks[$stack_template_slug]['post_id'], 
					'comment_status' => 'closed'
					)
				);
			}
				
		}
			
	}
	
	//Update the option which tells us which default stacks have been created and their corresponding ids
	update_option( 'mp_stacks_default_stacks_created', $previously_created_default_stacks );
	
	return true;
}

//Add the slug of a brick to the footer
function mp_stacks_brick_footer_slug( $text ){
	
	global $post;
	
	if ( isset( $post->post_type ) && $post->post_type == 'mp_brick' ){
		
		if ( isset( $post->post_name ) && !empty( $post->post_name ) ){
			return __( 'This Brick\'s URL', 'mp_stacks' ) . ': <strong>#' . $post->post_name . '</strong><br />' . __('To make a browser scroll to this brick, link to that as the URL. For further explanation', 'mp_stacks' ) . ' <a target="_blank" href="https://mintplugins.com/support/brick-urls/">' . __( 'Click Here', 'mp_stacks' ) . '.</a>';	
		}
		else{
			return __( 'Thank you for creating with WordPress and MP Stacks.', 'mp_stacks' );	
		}
		
	}
	
	return $text;
}
add_filter( 'admin_footer_text', 'mp_stacks_brick_footer_slug' );

//Add a media button to let the user easily install the MP Buttons plugin (if the plugin isn't installed)
function mp_stacks_install_mp_buttons_shortcode_btn( $context ){
		
	global $pagenow, $typenow, $wp_version;
		
	//Only run if MP Buttons plugin is not installed AND in Brick Editor pages
	if ( !function_exists( 'mp_buttons_textdomain' ) && $type_now == 'mp_brick' ){
			
		//Output old style button
		$context .= '<a target="_blank" href="' . admin_url( sprintf( 'options-general.php?page=mp_core_install_plugin_page_mp-buttons&action=install-plugin&mp-source=mp_core_directory&plugin=mp-buttons&plugin_api_url=' . base64_encode( 'http://mintplugins.com' ) . '&mp_core_directory_page=mp_stacks_plugin_directory&mp_core_directory_tab=content_types&_wpnonce=%s', wp_create_nonce( 'install-plugin'  ) ) ) . '" class="button" title="' . __('Install Button Creator', 'mp_core') . '">' . __( 'Install Button Creator from Mint Plugins (free)', 'mp_stacks' ) . '</a>';
						
	}
	
	//Add new button to list of buttons to output
	return $context;
}
add_filter( 'media_buttons_context', 'mp_stacks_install_mp_buttons_shortcode_btn' );