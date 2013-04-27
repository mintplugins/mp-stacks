<?php
/**
 * Install Theme Updater Plugin
 *
 */
 if (!function_exists('mp_core_plugin_check')){
	function mp_core_plugin_check() {
		$args = array(
			'plugin_name' => 'Move Plugins Core',  //<--Make sure this matches the name of the plugin and the name of the plugin in mp_repo
			'plugin_message' => __('You require the Move Plugins Core plugin. Install it here.', 'mp_links'), 
			'plugin_slug' => 'mp-core', //<--This will be the name of the subdirectory where the plugin is located
			'plugin_filename' => 'mp-core.php',
			'plugin_required' => true,
			'plugin_download_link' => 'http://moveplugins.com/repo/move-plugins-core/?download=true'
		);
		$mp_core_plugin_check = new MP_CORE_Plugin_Checker($args);
	}
 }
add_action( 'init', 'mp_core_plugin_check' );

