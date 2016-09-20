<?php
/**
 * This file contains includes calls for compatible plugin functions.
 *
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2016, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
		
//If Easy Digital Downloads is active
if ( class_exists( 'Easy_Digital_Downloads' ) ){
	//require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/compatible-plugins/edd-functions.php' );
}

//If WooCommerce is active
if ( class_exists( 'WooCommerce' ) ){
	//require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/compatible-plugins/woocommerce-functions.php' );
}

//If Yoast WP Seo is active
if ( defined( 'WPSEO_FILE' ) ){
	require( MP_STACKS_PLUGIN_DIR . 'includes/misc-functions/compatible-plugins/wp-seo-functions.php' );
}