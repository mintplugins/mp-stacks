<?php
/**
 * This file contains various functions related to Yoast SEO and MP Stacks.
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

/**
 * Make Bricks act as if we were logged out when Yoast SEO does it scrape of the content.
 *
 * @since 1.0
 * @param  bool $is_user_logged_in See link for description.
 * @param  string $parent_filter See link for description.
 * @return void
*/
function mp_stacks_wp_seo_act_logged_out( $is_user_logged_in, $parent_filter ){	
		
	//If we are running the Bricks for WP Seo's scraper
	if ( $parent_filter == 'wp_ajax_wpseo_filter_shortcodes' ){
		
		return false;
	}
	
	return $is_user_logged_in;
}
add_filter( 'mp_stacks_act_as_logged_in', 'mp_stacks_wp_seo_act_logged_out', 10, 2 );

/**
 * Make Stacks not show CSS if doing a scrape by Yoast SEO
 *
 * @since 1.0
 * @param  bool $css_required See link for description.
 * @param  string $parent_filter See link for description.
 * @return void
*/
function mp_stacks_wp_seo_hide_css( $css_required, $parent_filter ){	
			
	//If we are running the Bricks for WP Seo's scraper
	if ( $parent_filter == 'wp_ajax_wpseo_filter_shortcodes' ){
		
		return false;
	}
	
	return $css_required;
}
add_filter( 'mp_stacks_css_required', 'mp_stacks_wp_seo_hide_css', 10, 2 );