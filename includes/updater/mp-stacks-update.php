<?php
/**
 * Check for updates for this Theme
 *
 */
 if (!function_exists('mp_stacks_update')){
	function mp_stacks_update() {
		$args = array(
			'software_name' => 'MP Stacks', //<- The exact name of this Plugin. Make sure it matches the title in your mp_stacks, edd, and the WP.org repo
			'software_api_url' => 'http://moveplugins.com',//The URL where EDD and mp_stacks are installed and checked
			'software_filename' => 'mp-stacks.php',
			'software_licenced' => false, //<-Boolean
		);
		
		//Since this is a theme, call the Plugin Updater class
		$mp_stacks_plugin_updater = new MP_CORE_Plugin_Updater($args);
	}
 }
add_action( 'init', 'mp_stacks_update' );
