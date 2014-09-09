<?php
/**
 * This file contains various functions which can be used by addons to create grid layouts as content types
 *
 * @since 1.0.0
 *
 * @package    MP Core
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Get the CSS for a text div based on the placement string the user has chosen
 *
 * @access   public
 * @since    1.0.0
 * @param    $placement_string String - A string chosen by the user to specify the position of the title
 * @param    $args Array - An associative array with additional options like image width and height, etc
 * @return   $css_output String - A string containing the CSS for the titles in this grid
 */
function mp_stacks_grid_get_text_placement_css( $placement_string, $args ){
	
	$css_output = NULL;
	
	$text_line_height = $args['line_height'] . 'px';
	
	if( $placement_string == 'below_image_left' ){
		
		$css_output = 'text-align:left; padding-top:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'below_image_right' ){
		$css_output = 'text-align:right; padding-top:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'below_image_centered' ){
		$css_output = 'text-align:center; padding-top:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_top_left' ){
		$css_output = 'text-align:left; padding-bottom:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_top_right' ){
		$css_output = 'text-align:right; padding-bottom:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_top_centered' ){
		$css_output = 'text-align:center; padding-bottom:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_middle_left' ){
		$css_output = 'text-align:left; padding-bottom:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_middle_right' ){
		$css_output = 'text-align:right; padding-bottom:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_middle_centered' ){
		$css_output = 'text-align:center; padding-bottom:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_bottom_left' ){
		$css_output = 'text-align:left; padding-top:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_bottom_right' ){
		$css_output = 'text-align:right; padding-top:' . $text_line_height . ';';
	}
	else if(  $placement_string == 'over_image_bottom_centered' ){
		$css_output = 'text-align:center; padding-top:' . $text_line_height . ';';
	}
	
	return $css_output;
		
}

/**
 * Return the CSS needed for a highlighted background color for text
 *
 * Note that for this css to work as expected, the text element must be wrapped in a parent which has its padding set to: 
 * padding: 0px ' . $highlight_padding . 'px;
 *
 * @access   public
 * @link     http://css-tricks.com/multi-line-padded-text/
 * @since    1.0.0
 * @param	 $args                Array - An associative array containing information needed to customize the highlight output with these values:
 * 		@param    $brick_id   		   Int - The id of the brick containing these values
 *		@param 	  $class_name		   String - The name of the class we want to use for this highlited text. Match it to the class passed to the mp_stacks_highlight_text_html function.
 *		@param    $highlight_padding   Int - A number value to use for the amount of padding for the highlight
 * 		@param    $highlight_color     String - A color hex code such as #FFFFFF
 * 		@param    $highlight_opacity   Int - A number value from 1 to 100 representing the percentage value for opacity
 * @return   $css_output          String - A string holding the css needed for highlighted text
 */
function mp_stacks_grid_highlight_text_css( $args ){
	
	//Default args
	$default_args = array( 
		'brick_id' => NULL,
		'class_name' => NULL,
		'highlight_padding' => NULL, 
		'highlight_color' => NULL, 
		'highlight_opacity' => NULL
	);
	
	wp_parse_args( $default_args, $args );
	
	extract( $args, EXTR_SKIP );
	
	//Convert hex color to rgb color array
	$highlight_color = mp_core_hex2rgb($highlight_color);
	
	$css_output = '#mp-brick-' . $brick_id . ' .' . $class_name . '-holder .' . $class_name . '{
		padding: 0px ' . $highlight_padding . 'px;
	}
	#mp-brick-' . $brick_id . ' .' . $class_name . '-holder .' . $class_name . ' .' . $class_name . '-highlight{
		padding:' . $highlight_padding . 'px;
			background-color: rgba(' . $highlight_color[0] . ', ' . $highlight_color[1] . ', ' . $highlight_color[2] . ', ' . $highlight_opacity / 100 . ');
			box-shadow: 
    			 	' . $highlight_padding . 'px 0 0 rgba(' . $highlight_color[0] . ', ' . $highlight_color[1] . ', ' . $highlight_color[2] . ', ' . $highlight_opacity / 100 . '), 
      		 		-' . $highlight_padding . 'px 0 0 rgba(' . $highlight_color[0] . ', ' . $highlight_color[1] . ', ' . $highlight_color[2] . ', ' . $highlight_opacity / 100 . ');
	}';
	
	return $css_output;
						
}

/**
 * Return the HTML needed for a highlighted background color for text
 *
 * @access   public
 * @link     http://css-tricks.com/multi-line-padded-text/
 * @since    1.0.0
 * @param	 $args                Array - An associative array containing information needed to customize the highlight output with these values:
 * 		@param    $brick_id   		   Int - The id of the brick containing these values
 *		@param 	  $class_name		   String - The name of the class we want to use for this highlited text. Match it to the class passed to the mp_stacks_highlight_text_html function.
 *		@param    $highlight_padding   Int - A number value to use for the amount of padding for the highlight
 * 		@param    $highlight_color     String - A color hex code such as #FFFFFF
 * 		@param    $highlight_opacity   Int - A number value from 1 to 100 representing the percentage value for opacity
 * @return   $css_output          String - A string holding the css needed for highlighted text
 */
function mp_stacks_grid_highlight_text_html( $args ){
	
	//Default args
	$default_args = array( 
		'class_name' => NULL,
		'output_string' => NULL
	);
	
	wp_parse_args( $default_args, $args );
	
	extract( $args, EXTR_SKIP );
	
	$html_output = '<div class="' . $class_name . '-holder">';
		
		$html_output .= '<div class="' . $class_name . '">';
		
			$html_output .= '<span class="' . $class_name . '-highlight">' . $output_string . '</span>';
		
		$html_output .= '</div>';
			
	$html_output .= '</div>';
	
	return $html_output;
						
}