<?php

/**
 * MP Stacks Template Shop Header Stuff
 * http://mintplugins.com/doc/plugin-directory-class/
 */
function mp_stacks_addon_directory_header(){
	?>
	<div class="mp-stacks-directory-header" width="100%" height="300px">
		<div class="mp-stacks-directory-title">
			<?php echo __( 'One License To Install Them All.', 'mp_stacks' ); ?>
		</div>
		<div class="mp-stacks-directory-subtitle">
			<?php echo __( 'Save hundreds of dollars and time by getting the Master License. It works to install every single product from MintPlugins.com for 1 year.', 'mp_stacks' ); ?>
		</div>
		<div class="mp-stacks-directory-master-license-button">
			<a href="http://mintplugins.com/plugins/master-license/" class="button" target="_blank"><?php echo __( 'Get the Master License', 'mp_stacks' ); ?> - $110</a>
		</div>
	</div>
    <?php
}
add_action( 'mp_core_directory_header_' . 'mp_stacks_plugin_directory', 'mp_stacks_addon_directory_header' );

/**
 * MP Stacks Template Shop Page Title
 * http://mintplugins.com/doc/plugin-directory-class/
 */
function mp_stacks_template_packs_title(){
	return __( 'MP Stacks Template Packs', 'mp_stacks' );
}
add_filter( 'mp_core_directory_' . 'mp_stacks_template_packs_plugin_directory' . '_title', 'mp_stacks_template_packs_title' );

/**
 * MP Stacks Add On Shop - Plugin Directory Class for the mp_stacks Plugin by Mint Plugins
 * http://mintplugins.com/doc/plugin-directory-class/
 */
function mp_stacks_add_on_plugin_directory(){
	
	$args = array (
		'parent_slug' => 'mp-stacks-about',
		'menu_title' => __( 'Add-Ons', 'mp_stacks' ),
		'page_title' => __( 'MP Stacks Add-On Shop', 'mp_stacks' ),
		'slug' => 'mp_stacks_plugin_directory',
		'directory_list_urls' => array(
			'content_types' => array(
				'title' => __( '"Content-Type" Add-Ons', 'mp_stacks' ),
				'description' => __( 'A "Content-Type" is the main content a Brick displays in MP Stacks.', 'mp_stacks' ),
				'directory_list_url' => 'http://mintplugins.com/repo-group/mp-stacks-content-types/',
			),
			'utility' => array(
				'title' => __('"Utility" Add-Ons', 'mp_stacks' ),
				'description' => __( 'Utility Add-Ons extend the overall functionality of MP Stacks.', 'mp_stacks' ),
				'directory_list_url' => 'http://mintplugins.com/repo-group/mp-stacks-utility-add-ons/',
			),
		),
		'search_api_url' => 'https://mintplugins.com/',
		'limit_search_to_repo_group_slug' => 'mp-stacks',
		'plugin_success_link' => add_query_arg( array('page' => 'mp_stacks_plugin_directory' ), admin_url('admin.php') )
	);
	
	new MP_CORE_Plugin_Directory( $args );
}
add_action( '_admin_menu', 'mp_stacks_add_on_plugin_directory' );

/**
 * MP Stacks Stack Packs - Plugin Directory Class for the mp_stacks Plugin by Mint Plugins
 * http://mintplugins.com/doc/plugin-directory-class/
 */
function mp_stacks_template_packs_plugin_directory(){
	
	$args = array (
		'parent_slug' => 'mp-stacks-about',
		'page_title' => 'Stack Templates',
		'slug' => 'mp_stacks_template_packs_plugin_directory',
		'directory_list_url' => 'http://mintplugins.com/repo-group/mp-stacks-template-packs/',
		'plugin_success_link' => add_query_arg( array('page' => 'mp_stack_template_packs_plugin_directory' ), admin_url('admin.php') )
	);
	
	new MP_CORE_Plugin_Directory( $args );
}
//add_action( '_admin_menu', 'mp_stacks_template_packs_plugin_directory' );