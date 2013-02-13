<?php
/**
 * Install Theme Updater Plugin
 *
 */
 if (!function_exists('mp_core_plugin_check')){
	function mp_core_plugin_check() {
		$args = array(
			'plugin_name' => 'Move Plugins Core', 
			'plugin_message' => 'You require the Move Plugins Core plugin. Install it here.', 
			'plugin_slug' => 'mp_core', 
			'plugin_subdirectory' => 'mp_core/', 
			'plugin_filename' => 'mp_core.php',
			'plugin_required' => true,
			'plugin_download_link' => 'http://moveplugins.com'
		);
		$mp_core_theme_updater = new mp_core_plugin_checker($args);
	}
 }
add_action( 'admin_init', 'mp_core_plugin_check' );

