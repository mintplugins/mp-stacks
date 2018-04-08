<?php
/**
 * Standardized Common Functions used by Theme Bundles during installation
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
 * This function, upon Theme Bundle activation, will set up a theme bundle's Stacks, Pages, Post, etc
 *
 * @since 1.0
 * @param $context The context (plugin name with underscores) for which this action is running.
 * @return void
 */
function mp_stacks_theme_bundle_custom_installation_settings(){

	global $mp_core_options;

	if ( !function_exists('mp_core_textdomain') ){
		return;
	}

	//If a theme bundle is being installed
	if( isset( $mp_core_options['mp_stacks_theme_bundle_being_installed'] ) ){
		$theme_bundle_slug = $mp_core_options['mp_stacks_theme_bundle_being_installed']['theme_bundle_slug'];
	}
	else{
		return false;
	}

	//Set default
	$setup_success = false;

	//If we should set up the default stuff for this theme bundle
	if( isset( $mp_core_options['setup_default_' . $theme_bundle_slug . '_items'] ) && $mp_core_options['setup_default_' . $theme_bundle_slug. '_items'] ){

		//Activate our Theme Of Choice - just in case it hasn't been yet
		switch_theme( $mp_core_options['mp_stacks_theme_bundle_being_installed']['required_theme_dirname'] );

		if ( function_exists( 'mp_backup_customizer' ) ){
			//Backup the Customizer Theme Mods now
			mp_backup_customizer( __( 'This backup was created for you by the ', 'mp_stacks' ) . $mp_core_options['mp_stacks_theme_bundle_being_installed']['fancy_title'] . __( ' just before it was activated.', 'mp_stacks' ) );
		}
		else{
		 	?>
   			 <div class="error">
       			 <p><?php echo __( 'There was a problem installing the Customizer Backups plugin so your Theme Mods were not backed up. You may need to manually install it', 'mp_stacks' ) . ' <a href="https://wordpress.org/" target="_blank">' . __( 'here', 'mp_stacks' ) . '</a>' ?></p>

                 <?php

				 //Encode the current theme Mods into a json array
				 $json_theme_mods_array = json_encode( get_theme_mods() );

				 ?>

                 <p><?php echo __( "Copy and save this text to backup your current Customizer Theme Mods so you don\'t lose them:", 'mp_stacks' ); ?></p>
                 <p><textarea style="width:100%; height:150px;"><?php echo $json_theme_mods_array; ?></textarea></p>
   			 </div>
    		<?php
		}

		//Filter in the customizer array that mp-stacks-developer spits out on the "Appearance" > "Export Customizer" page.
		$required_theme_mods = apply_filters( 'mp_stacks_required_theme_mods_for_' . $theme_bundle_slug,  array() );

		//Loop through each Theme Mod and save it to the current WP
		foreach( $required_theme_mods as $name => $value ){

			set_theme_mod( $name, $value );

		}

		//Set up the default Stacks and corresponsing posts. This will refresh the page after each item is set-up (Required Stacks, pages, posts, etc).
		$setup_success = mp_stacks_theme_bundle_create_default_pages( $theme_bundle_slug );

		//Reset 'setup_default_' . $theme_bundle_slug. '_items' to false - we don't need to setup these options anymore because we are doing it now
		$mp_core_options['setup_default_' . $theme_bundle_slug. '_items'] = false;
		//While we are still setting up these options, set this to true. Only change it false when all the settings have actually been carried out.
		$mp_core_options['setting_default_' . $theme_bundle_slug. '_items'] = true;
		update_option( 'mp_core_options', $mp_core_options );

	}
	elseif( isset( $mp_core_options['setting_default_' . $theme_bundle_slug . '_items'] ) && $mp_core_options['setting_default_' . $theme_bundle_slug . '_items'] ){

		//Continue Setting up the default Stacks and corresponsing posts
		$setup_success = mp_stacks_theme_bundle_create_default_pages( $theme_bundle_slug );
	}

	if ( $setup_success ){

		//Additional Custom settings for plugins go here:
		do_action( 'mp_stacks_additional_installation_actions', $theme_bundle_slug );

		$mp_core_options['mp_theme_bundle_default_items_setup_success'] = true;

	}
}
add_action( 'admin_init', 'mp_stacks_theme_bundle_custom_installation_settings' );

/**
 * Default items setup notice. This shows the user a "Success" message if all items have just been successfully set up.
 *
 * @since 1.0
 * @param void
 * @return void
 */
function mp_stacks_theme_bundle_default_items_notice(){

	global $mp_core_options;

	//If all default items for this theme bundle have been setup successfully, show the admin notice about the success!
	if ( isset( $mp_core_options['mp_theme_bundle_default_items_setup_success'] ) && $mp_core_options['mp_theme_bundle_default_items_setup_success'] ){

		$theme_bundle_slug = $theme_bundle_slug = $mp_core_options['mp_stacks_theme_bundle_being_installed']['theme_bundle_slug'];

		?>

		<div class="welcome-panel">
			<div class="welcome-panel-content">
				<h2><?php echo __( 'Installation Successful!', 'mp_stacks' ); ?></h2>
				<p class="about-description"><?php echo __( 'You\'re all ready to go with the ', 'mp_stacks' ) . $mp_core_options['mp_stacks_theme_bundle_being_installed']['fancy_title']; ?></p>
				<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
								<h4><?php echo __( 'Check it out:', 'mp_stacks' ); ?></h4>
								<a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo get_bloginfo( 'wpurl' ); ?>"><?php echo __( 'View your new Home Page', 'launchstack_theme_bundle' ); ?></a>
						</div>
				<div class="welcome-panel-column">
					<h4><?php echo __( 'Next Steps', 'mp_stacks' ); ?></h4>
					<ul>
						<li><a href="<?php echo $mp_core_options['mp_stacks_theme_bundle_being_installed']['support_url']; ?>" class="welcome-icon dashicons-media-interactive"><?php echo __( 'Read Documentation', 'mp_stacks' ); ?></a></li>
						<li><a href="<?php echo $mp_core_options['mp_stacks_theme_bundle_being_installed']['support_url']; ?>" class="welcome-icon dashicons-email-alt"><?php echo __( 'Support: ', 'mp_stacks' ) . $mp_core_options['mp_stacks_theme_bundle_being_installed']['support_email']; ?></a></li>
					</ul>
				</div>
				</div>
			</div>
		</div>

		<div id="dashboard-widgets-wrap">

		</div><!-- dashboard-widgets-wrap -->
		<?php

		//Now that all settings are complete, we can officially stop the installation process by un-setting these:
		unset( $mp_core_options['parent_plugin_activation_status'] );
		unset( $mp_core_options['setup_default_' . $theme_bundle_slug. '_items'] );
		unset( $mp_core_options['setting_default_' . $theme_bundle_slug . '_items'] );
		unset( $mp_core_options['mp_theme_bundle_default_items_setup_success'] );
		unset( $mp_core_options['mp_stacks_theme_bundle_being_installed'] );
		update_option( 'mp_core_options', $mp_core_options );
	}
}
add_action( 'admin_notices', 'mp_stacks_theme_bundle_default_items_notice' );

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

	global $mp_core_options;

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

			//Was a Significant change made? If so, we need to refresh to we don't hit any PHP limits.
			$significant_change_made = false;

			//If a default stack doesn't exist for this template
			if ( !isset( $previously_created_default_stacks[$stack_template_slug]['stack_id'] ) || !get_term_by('id', $previously_created_default_stacks[$stack_template_slug]['stack_id'], 'mp_stacks') ){

				$stack_template_function_name = 'mp_stacks_' . $stack_template_slug . '_array';

				//Create a new stack using this stack template.
				$new_stack_id = mp_stacks_create_stack_from_template( $stack_template_function_name(), isset( $other_info['title'] ) ? $other_info['title'] : ucwords( str_replace( '_', ' ', $stack_template_slug ) ) );

				//Add this stack to the list of default stacks we've created for this stack template
				$previously_created_default_stacks[$stack_template_slug]['stack_id'] = $new_stack_id;

				//This is a significant change
				$significant_change_made = true;

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

				//If this corresponding post is supposed to be on the Primary Navigation Menu
				if ( isset( $other_info['add_to_primary_menu'] ) && $other_info['add_to_primary_menu'] ){

					//Add this post_id to the list of items that should be added to the menu
					$mp_core_options['new_menu_items'][$previously_created_default_stacks[$stack_template_slug]['post_id']] = array(
						'menu_item_object_id' => $previously_created_default_stacks[$stack_template_slug]['post_id'],
						'menu_item_object' => $post_type,
						'menu_item_type' => 'post_type',

					);

					update_option( 'mp_core_options', $mp_core_options );

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

			//Update the option which tells us which default stacks have been created and their corresponding ids
			update_option( 'mp_stacks_default_stacks_created', $previously_created_default_stacks );

			//If a significant change has been made for this loop, refresh the page so we can run the next iteration without hitting any PHP limitations
			//We do this with JS instead of with wp_redirect because it could give a "Too Many redirects" error.
			if ( $significant_change_made ){

				//If we are doing ajax right now, return false here so we dont run double ajax
				if ( defined( 'DOING_AJAX' ) && DOING_AJAX ){
					 return false;
				}

				 ?>
                <div class="updated" style="margin: 19px 0px 19px 0px;">
                    <p><div class="spinner is-active" style="float:left; margin-top: 11px;"></div><h1><?php echo __( 'Please wait', 'mp_stacks' ); ?></h1> <?php echo '<strong>' . $mp_core_options['mp_stacks_theme_bundle_being_installed']['fancy_title'] . '</strong> ' . __( 'is setting up Default Content. This may take a couple of minutes. Thanks for your patience.', 'mp_stacks' ); ?></p>
                </div>

                <script type="text/javascript" src="<?php echo get_bloginfo( 'wpurl' ); ?>/wp-includes/js/jquery/jquery.js"></script>
				<script type="text/javascript" src="<?php echo MP_CORE_PLUGIN_URL; ?>includes/js/utility/velocity.min.js"></script>

				<script type="text/javascript">

					jQuery(document).ready(function($){

						$(".small, .small-shadow").velocity({
							rotateZ: [0,-360]},{
							loop:true,
							duration: 2000
						});
						$(".medium, .medium-shadow").velocity({
							rotateZ: -240},{
							loop:true,
							duration: 2000
						});
						$(".large, .large-shadow").velocity({
							rotateZ: 180},{
							loop:true,
							duration: 2000
						});

						function mp_stacks_set_up_theme_bundle_default_content_ajax(){

							var postData = {
								action: 'mp_stacks_set_up_theme_bundle_default_content',
								mp_theme_bundle_slug: '<?php echo $theme_bundle_slug; ?>'
							};

							//Run the Ajax
							$.ajax({
								type: "POST",
								data: postData,
								dataType:"json",
								url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
								success: function (response) {

									//If all items have been set up
									if ( response.setup_complete ){
										//redirect the user to their dashboard
										window.location = '<?php echo admin_url(); ?>?redirected=true';
									}
									//If we still need to set up more things
									else{
										//Re-run this function
										mp_stacks_set_up_theme_bundle_default_content_ajax();

										console.log( 're-running ajax setup function' );
									}

								}
							}).fail(function (data) {
								console.log(data);
							});

						}

						//Start running the ajax setups
						mp_stacks_set_up_theme_bundle_default_content_ajax();

					});


				</script>

				<style type="text/css">
					body, html {
					  width: 100%;
					  height: 100%;
					  background-color:#222222;
					  text-align:center;
					  color:#fff;
					  font-family:Verdana, Geneva, sans-serif;
					  margin:0px;
					}

					.container {
					  height: 100%;
					  display: -webkit-box;
					  display: -webkit-flex;
					  display: -ms-flexbox;
					  display: flex;
					  -webkit-box-pack: center;
					  -webkit-justify-content: center;
						  -ms-flex-pack: center;
							  justify-content: center;
					  -webkit-box-align: center;
					  -webkit-align-items: center;
						  -ms-flex-align: center;
							  align-items: center; }

					.machine {
					  width: 60vmin;
					  fill: #fff; }

					.small-shadow, .medium-shadow, .large-shadow {
					  fill: rgba(0, 0, 0, 0.05); }

					.small {
					  -webkit-transform-origin: 100.136px 225.345px;
						  -ms-transform-origin: 100.136px 225.345px;
							  transform-origin: 100.136px 225.345px; }

					.small-shadow {
					  -webkit-transform-origin: 110.136px 235.345px;
						  -ms-transform-origin: 110.136px 235.345px;
							  transform-origin: 110.136px 235.345px; }

					.medium {
					  -webkit-transform-origin: 254.675px 379.447px;
						  -ms-transform-origin: 254.675px 379.447px;
							  transform-origin: 254.675px 379.447px; }

					.medium-shadow {
					  -webkit-transform-origin: 264.675px 389.447px;
						  -ms-transform-origin: 264.675px 389.447px;
							  transform-origin: 264.675px 389.447px; }

					.large {
					  -webkit-transform-origin: 461.37px 173.694px;
						  -ms-transform-origin: 461.37px 173.694px;
							  transform-origin: 461.37px 173.694px; }

					.large-shadow {
					  -webkit-transform-origin: 471.37px 183.694px;
						  -ms-transform-origin: 471.37px 183.694px;
							  transform-origin: 471.37px 183.694px; }
				</style>

                <div id="notify_user_of_happenings">
						<div class="container" style="width:200px; margin: 0px auto;">
							<svg class="machine" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 645 526">
							  <defs/>
							  <g>
								<path  x="-173,694" y="-173,694" class="large-shadow" d="M645 194v-21l-29-4c-1-10-3-19-6-28l25-14 -8-19 -28 7c-5-8-10-16-16-24L602 68l-15-15 -23 17c-7-6-15-11-24-16l7-28 -19-8 -14 25c-9-3-18-5-28-6L482 10h-21l-4 29c-10 1-19 3-28 6l-14-25 -19 8 7 28c-8 5-16 10-24 16l-23-17L341 68l17 23c-6 7-11 15-16 24l-28-7 -8 19 25 14c-3 9-5 18-6 28l-29 4v21l29 4c1 10 3 19 6 28l-25 14 8 19 28-7c5 8 10 16 16 24l-17 23 15 15 23-17c7 6 15 11 24 16l-7 28 19 8 14-25c9 3 18 5 28 6l4 29h21l4-29c10-1 19-3 28-6l14 25 19-8 -7-28c8-5 16-10 24-16l23 17 15-15 -17-23c6-7 11-15 16-24l28 7 8-19 -25-14c3-9 5-18 6-28L645 194zM471 294c-61 0-110-49-110-110S411 74 471 74s110 49 110 110S532 294 471 294z"/>
							  </g>
							  <g>
								<path x="-136,996" y="-136,996" class="medium-shadow" d="M402 400v-21l-28-4c-1-10-4-19-7-28l23-17 -11-18L352 323c-6-8-13-14-20-20l11-26 -18-11 -17 23c-9-4-18-6-28-7l-4-28h-21l-4 28c-10 1-19 4-28 7l-17-23 -18 11 11 26c-8 6-14 13-20 20l-26-11 -11 18 23 17c-4 9-6 18-7 28l-28 4v21l28 4c1 10 4 19 7 28l-23 17 11 18 26-11c6 8 13 14 20 20l-11 26 18 11 17-23c9 4 18 6 28 7l4 28h21l4-28c10-1 19-4 28-7l17 23 18-11 -11-26c8-6 14-13 20-20l26 11 11-18 -23-17c4-9 6-18 7-28L402 400zM265 463c-41 0-74-33-74-74 0-41 33-74 74-74 41 0 74 33 74 74C338 430 305 463 265 463z"/>
							  </g>
							  <g >
								<path x="-100,136" y="-100,136" class="small-shadow" d="M210 246v-21l-29-4c-2-10-6-18-11-26l18-23 -15-15 -23 18c-8-5-17-9-26-11l-4-29H100l-4 29c-10 2-18 6-26 11l-23-18 -15 15 18 23c-5 8-9 17-11 26L10 225v21l29 4c2 10 6 18 11 26l-18 23 15 15 23-18c8 5 17 9 26 11l4 29h21l4-29c10-2 18-6 26-11l23 18 15-15 -18-23c5-8 9-17 11-26L210 246zM110 272c-20 0-37-17-37-37s17-37 37-37c20 0 37 17 37 37S131 272 110 272z"/>
							  </g>
							  <g>
								<path x="-100,136" y="-100,136" class="small" d="M200 236v-21l-29-4c-2-10-6-18-11-26l18-23 -15-15 -23 18c-8-5-17-9-26-11l-4-29H90l-4 29c-10 2-18 6-26 11l-23-18 -15 15 18 23c-5 8-9 17-11 26L0 215v21l29 4c2 10 6 18 11 26l-18 23 15 15 23-18c8 5 17 9 26 11l4 29h21l4-29c10-2 18-6 26-11l23 18 15-15 -18-23c5-8 9-17 11-26L200 236zM100 262c-20 0-37-17-37-37s17-37 37-37c20 0 37 17 37 37S121 262 100 262z"/>
							  </g>
							  <g>
								<path x="-173,694" y="-173,694" class="large" d="M635 184v-21l-29-4c-1-10-3-19-6-28l25-14 -8-19 -28 7c-5-8-10-16-16-24L592 58l-15-15 -23 17c-7-6-15-11-24-16l7-28 -19-8 -14 25c-9-3-18-5-28-6L472 0h-21l-4 29c-10 1-19 3-28 6L405 9l-19 8 7 28c-8 5-16 10-24 16l-23-17L331 58l17 23c-6 7-11 15-16 24l-28-7 -8 19 25 14c-3 9-5 18-6 28l-29 4v21l29 4c1 10 3 19 6 28l-25 14 8 19 28-7c5 8 10 16 16 24l-17 23 15 15 23-17c7 6 15 11 24 16l-7 28 19 8 14-25c9 3 18 5 28 6l4 29h21l4-29c10-1 19-3 28-6l14 25 19-8 -7-28c8-5 16-10 24-16l23 17 15-15 -17-23c6-7 11-15 16-24l28 7 8-19 -25-14c3-9 5-18 6-28L635 184zM461 284c-61 0-110-49-110-110S401 64 461 64s110 49 110 110S522 284 461 284z"/>
							  </g>
							  <g>
								<path x="-136,996" y="-136,996" class="medium" d="M392 390v-21l-28-4c-1-10-4-19-7-28l23-17 -11-18L342 313c-6-8-13-14-20-20l11-26 -18-11 -17 23c-9-4-18-6-28-7l-4-28h-21l-4 28c-10 1-19 4-28 7l-17-23 -18 11 11 26c-8 6-14 13-20 20l-26-11 -11 18 23 17c-4 9-6 18-7 28l-28 4v21l28 4c1 10 4 19 7 28l-23 17 11 18 26-11c6 8 13 14 20 20l-11 26 18 11 17-23c9 4 18 6 28 7l4 28h21l4-28c10-1 19-4 28-7l17 23 18-11 -11-26c8-6 14-13 20-20l26 11 11-18 -23-17c4-9 6-18 7-28L392 390zM255 453c-41 0-74-33-74-74 0-41 33-74 74-74 41 0 74 33 74 74C328 420 295 453 255 453z"/>
							  </g>
							</svg>
						</div>
					</div>

                <?php

				die();

				return false;
			}

		}

	}

	return true;
}

/**
 * This is the ajax callback that will make default content get set up for Theme Bundles.
 *
 * @since 1.0
 * @param void
 * @return void
 */
function mp_stacks_set_up_theme_bundle_default_content_via_ajax(){

	global $mp_core_options;
	$mp_core_options = get_option('mp_core_options');

	$theme_bundle_slug = $_POST['mp_theme_bundle_slug'];

	//Continue Setting up the default Stacks and corresponsing posts
	$setup_success = mp_stacks_theme_bundle_create_default_pages( $theme_bundle_slug );

	if ( $setup_success ){

		echo json_encode( array(
			'setup_complete' => true
		) );

		die();

	}
	else{

		echo json_encode( array(
			'setup_complete' => false
		) );

		die();
	}


}
add_action( 'wp_ajax_mp_stacks_set_up_theme_bundle_default_content', 'mp_stacks_set_up_theme_bundle_default_content_via_ajax' );

/**
 * This will set up a Primary Menu for a Theme Bundle of any Stack Templates have been set to 'add_to_primary_menu'
 *
 * @since 1.0
 * @param void
 * @return void
 */
function mp_stacks_theme_bundle_setup_primary_menu( $theme_bundle_slug ){

	global $mp_core_options;

	//Check if we should set up a new menu
	if ( isset( $mp_core_options['new_menu_items'] ) && is_array( $mp_core_options['new_menu_items'] ) ){

		//Filter to allow for custom menu items added by the custom-install-functions.php file in the Theme Bundle
		$mp_core_options['new_menu_items'] = apply_filters( 'mp_stacks_theme_bundle_install_menu_items', $mp_core_options['new_menu_items'], $theme_bundle_slug );

		//Check if a menu with this name already exists
		$menu_name = $mp_core_options['mp_stacks_theme_bundle_being_installed']['fancy_title'] . ' ' . __( 'Menu', 'mp_stacks' );
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		//If the menu does not exist
		if ( !$menu_exists ){

			//Create a new menu
			$menu_id = wp_create_nav_menu( $menu_name );

			//Loop through each item that should be on the menu.
			foreach( $mp_core_options['new_menu_items'] as $new_menu_item_id => $new_menu_item_data ){

				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $new_menu_item_data['menu_item_object_id'],
						'menu-item-object' => $new_menu_item_data['menu_item_object'],
						'menu-item-type' => $new_menu_item_data['menu_item_type'],
						'menu-item-status' => 'publish'
					)
				);

			}

			//Make this menu the primary one
			set_theme_mod( 'nav_menu_locations', array (
					'primary' => $menu_id,
				)
			);
		}
	}

	unset( $mp_core_options['new_menu_items'] );

}
add_action( 'mp_stacks_additional_installation_actions', 'mp_stacks_theme_bundle_setup_primary_menu' );

/**
 * If the user uploaded this plugin as a theme, delete that theme copy now.
 *
 * @since 1.0
 * @global $wpdb
 * @global $mp_core_options
 * @return void
 */
function mp_stacks_remove_matching_theme( $theme_bundle_slug ){

	global $mp_core_options;

	$hyphen_slug = str_replace("_", "-", $theme_bundle_slug );

	//Set the method for the wp filesystem
	$method = ''; // Normally you leave this an empty string and it figures it out by itself, but you can override the filesystem method here

	//Get credentials for wp filesystem
	if (false === ($creds = request_filesystem_credentials( admin_url(), $method, false, false) ) ) {

		// if we get here, then we don't have credentials yet,
		// but have just produced a form for the user to fill in,
		// so stop processing for now

		return true; // stop the normal page form from displaying
	}

	//Now we have some credentials, try to get the wp_filesystem running
	if ( ! WP_Filesystem($creds) ) {
		// our credentials were no good, ask the user for them again
		request_filesystem_credentials($url, $method, true, false);
		return true;
	}

	//By this point, the $wp_filesystem global should be working, so let's use it get our plugin.
	global $wp_filesystem;

	$themes_dir = $wp_filesystem->wp_themes_dir();
	$corresponding_theme_dir = $themes_dir . $hyphen_slug . '/';

	if($wp_filesystem->is_dir($corresponding_theme_dir)){
		//Remove the theme duplicate of this plugin
		mp_core_remove_directory( $corresponding_theme_dir );
	}

}
add_action( 'mp_stacks_additional_installation_actions', 'mp_stacks_remove_matching_theme' );
