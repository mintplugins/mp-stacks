<?php
/**
 * This file contains various functions
 *
 * @since 1.0.0
 *
 * @package    MP Core
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
* Show the "Font Size" in the TinyMCE editor 
*
* @since    1.0.0
* @link     http://moveplugins.com/doc/
* @see      function_name()
* @param    array $options See link for description.
* @return   array $options
*/
function mp_stacks_wp_editor_fontsize_filter( $options ) {
        array_shift( $options );
        array_unshift( $options, 'fontsizeselect');
		array_unshift( $options, 'font_size_style_values' );		
	    return $options;
}
add_filter('mce_buttons_2', 'mp_stacks_wp_editor_fontsize_filter');


/**
 * Add custom text sizes in the font size drop down list of the rich text editor (TinyMCE) in WordPress
 * $initArray is a variable of type array that contains all default TinyMCE parameters.
 * Value 'theme_advanced_font_sizes' needs to be added, if an overwrite to the default font sizes in the list, is needed.
 *
 * @since   1.0.0
 * @link    http://moveplugins.com/doc/
 * @param   array $$initArray See link for description.
 * @return  array $initArray
 */
function customize_text_sizes($initArray){
	
	$number_string = NULL;
	
	for ($i = 1; $i <= 100; $i++) {
		$number_string .= $i . '%,';
	}
	
   $initArray['theme_advanced_font_sizes'] = $number_string;
   
   //$initArray['font_size_style_values'] = $number_string;
   
   //Show the "Kitchen Sink" in the TinyMCE editor by default
   $initArray['wordpress_adv_hidden'] = false;
      
   return $initArray;
}
// Assigns customize_text_sizes() to "tiny_mce_before_init" filter
add_filter('tiny_mce_before_init', 'customize_text_sizes');
