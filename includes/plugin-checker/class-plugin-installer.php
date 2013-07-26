<?php
/**
 * Plugin Installer Class for the mp_core Plugin by Move Plugins
 * http://moveplugins.com/doc/plugin-installer-class/
 */
if ( !class_exists( 'MP_CORE_Plugin_Installer' ) ){
	class MP_CORE_Plugin_Installer{
		
		public function __construct($args){
			
			//Get args
			$this->_args = $args;
			
			//Plugin Name Slug
			$this->plugin_name_slug = sanitize_title ( $this->_args['plugin_name'] ); //EG move-plugins-core	
			
			// Create update/install plugin page
			add_action('admin_menu', array( $this, 'mp_core_install_plugin_page') );
											
		}
	
		/**
		 * Create mp core install plugin page
		 *
		 */
		public function mp_core_install_plugin_page()
		{
			// This WordPress variable is essential: it stores which admin pages are registered to WordPress
			global $_registered_pages;
		
			// Get the name of the hook for this plugin
			// We use "plugins.php" as the parent as we want our page to appear under "plugins.php?page=mp_core_install_plugin_page"
			$hookname = get_plugin_page_hookname('mp_core_install_plugin_page_' .  $this->_args['plugin_slug'], 'plugins.php');
		
			// Add the callback via the action on $hookname, so the callback function is called when the page "plugins.php?page=mp_core_install_plugin_page" is loaded
			if (!empty($hookname)) {
				add_action($hookname, array( $this, 'mp_core_install_check_callback') );
			}
		
			// Add this page to the registered pages
			$_registered_pages[$hookname] = true;
		}
		
		/**
		 * Callback function for the update plugin page above. This page uses the filesystem api to install a plugin
		 */
		public function mp_core_install_check_callback() {
											
			echo '<div class="wrap">';
			
			screen_icon();
						
			echo '<h2>' . __('Install ', 'mp_core') . $this->_args['plugin_name'] . '</h2>';
			
			//Make sure this user has the cpability to install plugins:
			if (!current_user_can('install_plugins')){ die('<p>' . __('You don\'t have permission to do this. Contact the system administrator for assistance.', 'mp_core') . '</p>'); } 
			
			//Make sure the action is set to install-plugin
			if ($_GET['action'] != 'install-plugin'){ die('<p>' . __('Oops! Something went wrong', 'mp_core') . '</p>'); }
									
			//Get the nonce previously set
			$nonce=$_REQUEST['_wpnonce'];
			
			//Check that nonce to ensure the user wants to do this
			if (! wp_verify_nonce($nonce, 'install-plugin_' . $this->_args['plugin_download_link']) ) die('<p>' . __('Security Check', 'mp_core') . '</p>'); 
			
			//Set the method for the wp filesystem
			$method = ''; // Normally you leave this an empty string and it figures it out by itself, but you can override the filesystem method here
			
			//Get credentials for wp filesystem
			$url = wp_nonce_url('plugins.php?page=mp_core_install_plugin_page_' .  $this->_args['plugin_slug'] . '&action=install-plugin&plugin=' . $this->_args['plugin_slug'], 'install-plugin_' . $this->_args['plugin_download_link'] );
			if (false === ($creds = request_filesystem_credentials($url, $method, false, false) ) ) {
			
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
			
			//By this point, the $wp_filesystem global should be working, so let's use it get our plugin
			global $wp_filesystem;
			
			//Get the plugins directory and name the temp plugin file
			$upload_dir = $wp_filesystem->wp_plugins_dir();
			$filename = trailingslashit($upload_dir).'temp.zip';
						
			//Download the plugin file defined in the passed in array
			//$saved_file = $wp_filesystem->get_contents( $this->_args['plugin_download_link'] ); <-- This requires 'allow_url_fopen' to be on - so instead we'll use curl below
			
			// Initializing curl
			$ch = curl_init();
			 
			//Return Transfer
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			//File to fetch
			curl_setopt($ch, CURLOPT_URL, $this->_args['plugin_download_link']);
			
			
			$file = fopen($upload_dir . "temp.zip", 'w');
			curl_setopt($ch, CURLOPT_FILE, $file ); #output
			
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
			
			//Set User Agent
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5'); //set user agent
									 
			// Getting results
			$result =  curl_exec($ch); // Getting jSON result string
			
			curl_close($ch);
			
			fclose($file);
												
			//If we are unable to find the file, let the user know
			if ( ! $result ) {
				die('<p>' . __('Unable to download file! Your webhost may be blocking cross-server connections. You will have to manually download and install this plugin. <br /><br />It looks like this plugin may be available for download here: <a href="' . $this->_args['plugin_download_link'] . '" target="_blank" >' . $this->_args['plugin_download_link'] . '</a><br /><br /> Download it, and then go to "Plugins > Add New > Upload" to upload the plugin and activate it. <br /><br /> If the plugin link above does not download the plugin for you, contact the author of the plugin for a download link.', 'mp_core') . '</p>');
			}
												
			//Unzip the temp zip file
			unzip_file($filename, trailingslashit($upload_dir) . '/' );
			
			//Delete the temp zipped file
			$wp_filesystem->rmdir($filename);
						
			//Display a successfully installed message
			echo '<p>' . __('Successfully Installed ', 'mp_core') .  $this->_args['plugin_name']  . '</p>';
			
			//Activate button
			echo '<a href="' . wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $this->_args['plugin_slug'] . '/' . $this->_args['plugin_filename'] . '&amp;plugin_status=all&amp;paged=1&amp;s=', 'activate-plugin_' . $this->_args['plugin_slug'] . '/' . $this->_args['plugin_filename']) . '" title="' . esc_attr__('Activate this plugin') . '" class="button">' . __('Activate', 'mp_core') . ' "' . $this->_args['plugin_name'] . '"</a>'; 	
						
			//Display link to plugins page
			echo '<p><a href="' . network_admin_url('plugins.php') . '">' . __('View all Plugins', 'mp_core') . '</a></p>'; 
			
			echo '</div>';
			
			return true;
			
		}
	}
}

