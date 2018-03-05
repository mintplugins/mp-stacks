<?php
/*
Plugin Name: MP Stacks
Plugin URI: http://mintplugins.com
Description: An amazing Page Builder for WordPress. Content-Types go in a Brick, Bricks go in a Stack, Stacks go on a page.
Version: 1.0.6.2
Author: Mint Plugins
Author URI: http://mintplugins.com
Text Domain: mp_stacks
Domain Path: languages
License: GPL2
*/

/*  Copyright 2016  Phil Johnston  (email : support@mintplugins.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Mint Plugins Core.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Mint Plugins Core, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
|--------------------------------------------------------------------------
| CONSTANTS
|--------------------------------------------------------------------------
*/
// Plugin version
if( !defined( 'MP_STACKS_VERSION' ) )
	define( 'MP_STACKS_VERSION', '1.0.6.2' );

// Plugin Folder URL
if( !defined( 'MP_STACKS_PLUGIN_URL' ) )
	define( 'MP_STACKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Plugin Folder Path
if( !defined( 'MP_STACKS_PLUGIN_DIR' ) )
	define( 'MP_STACKS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Plugin Root File
if( !defined( 'MP_STACKS_PLUGIN_FILE' ) )
	define( 'MP_STACKS_PLUGIN_FILE', __FILE__ );

/*
|--------------------------------------------------------------------------
| GLOBALS
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| INTERNATIONALIZATION
|--------------------------------------------------------------------------
*/

function mp_stacks_textdomain() {

	// Set filter for plugin's languages directory
	$mp_stacks_lang_dir = dirname( plugin_basename( MP_STACKS_PLUGIN_FILE ) ) . '/languages/';
	$mp_stacks_lang_dir = apply_filters( 'mp_stacks_languages_directory', $mp_stacks_lang_dir );


	// Traditional WordPress plugin locale filter
	$locale        = apply_filters( 'plugin_locale',  get_locale(), 'mp-stacks' );
	$mofile        = sprintf( '%1$s-%2$s.mo', 'mp-stacks', $locale );

	// Setup paths to current locale file
	$mofile_local  = $mp_stacks_lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/mp-stacks/' . $mofile;

	if ( file_exists( $mofile_global ) ) {
		// Look in global /wp-content/languages/mp_stacks folder
		load_textdomain( 'mp_stacks', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) {
		// Look in local /wp-content/plugins/mp_stacks/languages/ folder
		load_textdomain( 'mp_stacks', $mofile_local );
	} else {
		// Load the default language files
		load_plugin_textdomain( 'mp_stacks', false, $mp_stacks_lang_dir );
	}

}
add_action( 'init', 'mp_stacks_textdomain', 1 );

/*
|--------------------------------------------------------------------------
| INCLUDES
|--------------------------------------------------------------------------
*/

/**
 * Activation Hook Function - Sets up Rewrite Rules, Sample Stack Page, User Roles and more
 */
require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/install.php' );

/**
 * Load files dependant on MP Core - if no MP Core, output a button to install it first
 */
function mp_stacks_include_files(){
	/**
	 * If mp_core isn't active, stop and install it now
	 */
	if (!function_exists('mp_core_textdomain')){

		/**
		 * Include Plugin Checker
		 */
		require( MP_STACKS_PLUGIN_DIR . '/includes/plugin-checker/class-plugin-checker.php' );

		/**
		 * Include Plugin Installer
		 */
		require( MP_STACKS_PLUGIN_DIR . '/includes/plugin-checker/class-plugin-installer.php' );

		/**
		 * Check if wp_core in installed
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-core-check.php' );

	}
	/**
	 * Otherwise, if mp_core is active, carry out the plugin's functions
	 */
	else{

		/**
		 * Update script - keeps this plugin up to date
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/updater/mp-stacks-update.php' );

		/**
		 * Shows AddOn Directory
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/plugin-directory/mp-stacks-directory.php' );

		/**
		 * Size Settings Metabox for mp_stacks
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-size/mp-stacks-size.php' );

		/**
		 * Background Settings Metabox for mp_stacks
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-bg/mp-stacks-bg.php' );

		/**
		 * Content Type Chooser Metabox for mp_stacks
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-content/mp-stacks-content.php' );
		require( MP_STACKS_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-content/ct-placeholders.php' );

		/**
		 * Text metabox for mp_stacks
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-text/mp-stacks-singletext.php' );
		require( MP_STACKS_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-text/mp-stacks-doubletext.php' );

		/**
		 * Image metabox for mp_stacks
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-image/mp-stacks-image.php' );

		/**
		 * Video metabox for mp_stacks
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-video/mp-stacks-video.php' );

		/**
		 * Stacks Custom Post Type
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/custom-post-types/bricks.php' );

		/**
		 * Stack function
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/mp-stack.php' );

		/**
		 * Create Stack Forms
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/create-stack-forms.php' );

		/**
		 * Shortcodes
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/shortcodes.php' );

		/**
		 * Additional Media Buttons
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/media-buttons.php' );

		/**
		 * Media Filters
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/content-filters/image-content-filters.php' );
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/content-filters/video-content-filters.php' );
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/content-filters/text-content-filters.php' );

		/**
		 * Enqueue Scripts
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/enqueue-scripts.php' );

		/**
		 * Misc Functions
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/misc-functions.php' );

		/**
		 * Grid Functions
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/grid-functions/grid-functions.php' );
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/grid-functions/grid-isotope-functions.php' );

		/**
		 * Ajax Callback Functions
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/ajax-callbacks.php' );

		/**
		 * Backwards Compatibility Functions
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/backwards-compatibility.php' );

		/**
		 * Allow re-ordering of bricks
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/re-order-bricks.php' );

		/**
		 * Stack Templater
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/stack-templater.php' );

		/**
		 * Stack Only Page
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/stack-only-page.php' );

		/**
		 * Welcome Page
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/welcome.php' );

		/**
		 * MP Stacks Page Template
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/page-template/page-template.php' );

		/**
		 * Edit Stack Settings
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/single-stack-edit-page.php' );

		/**
		 * Default Stack Template
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/stack-templates/default-template/default-template.php' );

		/**
		 * Stack Widget
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/widgets/class-stack-widget.php' );

		/**
		 * Stack Theme Bundle-Related Functions
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/theme-bundle-installation.php' );
		/**
		 * Compatible Plugin functions
		 */
		require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/compatible-plugins/compatible-plugins.php' );


	}
}
add_action('plugins_loaded', 'mp_stacks_include_files', 9);
