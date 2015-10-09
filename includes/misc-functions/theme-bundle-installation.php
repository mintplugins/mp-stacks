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
	
		?>
							
		<div class="welcome-panel">
			<div class="welcome-panel-content">
				<h3><?php echo __( 'Installation Successful!', 'mp_stacks' ); ?></h3>
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
		unset( $mp_core_options['mp_stacks_theme_bundle_being_installed'] );
		update_option( 'mp_core_options', $mp_core_options );	
		
	}
}
add_action( 'admin_notices', 'mp_stacks_theme_bundle_custom_installation_settings' );

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
				
				 ?>
                <div class="updated" style="margin: 19px 0px 19px 0px;">
                    <p><div class="spinner is-active" style="float:left; margin-top: 11px;"></div><h1><?php echo __( 'Please wait', 'mp_stacks' ); ?></h1> <?php echo '<strong>' . $mp_core_options['mp_stacks_theme_bundle_being_installed']['fancy_title'] . '</strong> ' . __( 'is setting up Default Content. This may take a couple of minutes. Thanks for your patience.', 'mp_stacks' ); ?></p>
                </div>
                <?php
	
				echo '<script type="text/javascript">';
					echo "window.location = '" . admin_url() . "';";
				echo '</script>';
				
				return false;
			}
				
		}
			
	}
	
	return true;
}