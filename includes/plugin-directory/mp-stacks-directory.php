<?php
/**
 * Plugin Directory Class for the mp_stacks Plugin by Move Plugins
 * http://moveplugins.com/doc/plugin-directory-class/
 */
function mp_stacks_plugin_directory(){
	
	$args = array (
		'parent_slug' => 'edit.php?post_type=mp_brick',
		'page_title' => 'Add Ons',
		'slug' => 'mp_stacks_plugin_directory',
		'directory_list_url' => 'http://moveplugins.com/repo-group/mp-stacks/'
	);
	
	new MP_CORE_Plugin_Directory( $args );
}
add_action( '_admin_menu', 'mp_stacks_plugin_directory' );