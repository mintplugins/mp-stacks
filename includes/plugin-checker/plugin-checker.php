<?php
/**
 * Plugin Checker Class for the Foundation Theme by Move Plugins
 * http://moveplugins.com/plugin-checker-class/
 */
if ( !class_exists( 'MP_CORE_Plugin_Checker' ) ){
	class MP_CORE_Plugin_Checker{
		
		public function __construct($args){
			
			//Get args
			$this->_args = $args;
			$this->pluginpath = $this->_args['plugin_subdirectory'] . $this->_args['plugin_filename'];
			
			//If the user has just clicked "Dismiss", than add that to the options table
			add_action( 'admin_init', array( $this, 'mp_core_close_message') );
			
			//Check for plugin in question
			add_action( 'admin_notices', array( $this, 'mp_core_plugin_check_notice') );
					
		}
		
		/**
		 * Show notice that plugin should be installed
		 *
		 */
		public function mp_core_plugin_check_notice() {

			//Check to see if the user has ever dismissed this message
			if (get_option( 'MP_CORE_Plugin_Checker_' . $this->_args['plugin_slug'] ) != "false"){
							
				//Check if plugin exists but is just not active
				if (file_exists('../wp-content/plugins/' . $this->pluginpath) && !in_array( $this->pluginpath, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
					
					echo '<div class="updated fade"><p>';
					
					echo __( $this->_args['plugin_message'] . '</p>');					
					
					//Activate button
					echo '<a href="' . wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $this->pluginpath . '&amp;plugin_status=all&amp;paged=1&amp;s=', 'activate-plugin_' . $this->pluginpath) . '" title="' . esc_attr__('Activate this plugin') . '" class="button">' . __('Activate', 'mp_core') . ' "' . $this->_args['plugin_name'] . '"</a>'; 	
					
					//Dismiss button
					$this->mp_core_dismiss_button();
					
					echo '</p></div>';
				
				//Check if plugin neither exists on this server, nor is it active	 	
				}elseif ( !file_exists('../wp-content/plugins/' . $this->pluginpath) && !in_array( $this->pluginpath, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					
					echo '<div class="updated fade"><p>';
					
					echo __( $this->_args['plugin_message'] . '</p>');
					
					//Install button
					if (isset($this->_args['plugin_slug'])){
						printf( __( '<a class="button" href="%s" style="display:inline-block; margin-right:.7em;"> ' . __('Automatically Install', 'mp_core') . ' "' . $this->_args['plugin_name'] . '"</a>' , 'mp_core' ), admin_url( sprintf( 'update.php?action=install-plugin&plugin=' . $this->_args['plugin_slug'] . '&_wpnonce=%s', wp_create_nonce( 'install-plugin_' . $this->_args['plugin_slug'] ) ) ) );	
					}
					//Download button - If an alternative download link has been supplied, show it as well
					if (isset($this->_args['plugin_download_link'])){
						echo '<a href="' . $this->_args['plugin_download_link'] . '" class="button" target="_blank">' . __('Manually Install', 'mp_core') . ' "' . $this->_args['plugin_name'] . '</a>';
					}
					
					//Dismiss button
					$this->mp_core_dismiss_button();
					
					echo '</p></div>';
					
				}
				
			}
		}
		
		/**
		 * Function to display "Dismiss" message
		 *
		 */
		 public function mp_core_dismiss_button(){
			$this->_args['plugin_required'] = (!isset($this->_args['plugin_required']) ? true : $this->_args['plugin_required']);
			if ($this->_args['plugin_required'] == false){
				echo '<form id="MP_CORE_Plugin_Checker_close_notice" method="post" style="display:inline-block; margin-left:.7em;">
							<input type="hidden" name="MP_CORE_Plugin_Checker_' . $this->_args['plugin_slug'] . '" value="false"/>
							<input type="submit" id="MP_CORE_Plugin_Checker_dismiss" class="button" value="Dismiss" /> 
					   </form>'; 
			}
		 }
		
		/**
		 * Function to fire if the Close button has been clicked
		 *
		 */
		 public function mp_core_close_message(){
			if (isset($_POST['MP_CORE_Plugin_Checker_' . $this->_args['plugin_slug']])){
				update_option( 'MP_CORE_Plugin_Checker_' . $this->_args['plugin_slug'], "false" );
			}
		 }
	
	}
}