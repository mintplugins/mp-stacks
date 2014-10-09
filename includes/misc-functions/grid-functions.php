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
 * Get ALL the CSS for text in a Grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $placement_string String - A string chosen by the user to specify the position of the title
 * @param    $args Array - An associative array with additional options like image width and height, etc
 * @return   $css_output String - A string containing the CSS for the titles in this grid
 */
function mp_stacks_grid_text_css( $post_id, $meta_prefix, $css_class, $css_defaults = array() ){
	
	//Set up defaults for CSS if none were provided to the function		
	$css_defaults_if_none_provided = array(
		'color' => NULL,
		'size' => 20,
		'lineheight' => 20,
		'background_padding' => 5,
		'background_color' => '#fff',
		'background_opacity' => 100,
		'placement_string' => 'below_image_left',
	);
	$css_defaults = wp_parse_args( $css_defaults, $css_defaults_if_none_provided );
				
	//Text placement
	$placement = mp_core_get_post_meta($post_id, $meta_prefix . '_placement', $css_defaults['placement_string']);
	
	//Text Color and size
	$color = mp_core_get_post_meta($post_id, $meta_prefix . '_color', $css_defaults['color']);
	$size = mp_core_get_post_meta($post_id, $meta_prefix . '_size', $css_defaults['size']);
	$lineheight = mp_core_get_post_meta($post_id, $meta_prefix . '_lineheight', $css_defaults['lineheight']);
	
	//Show Text Backgrounds?
	$background_show = mp_core_get_post_meta($post_id, $meta_prefix . '_background_show');
	
	//If we should show the text backgrounds
	if ( $background_show ){
		
		//Text background spacing (padding)
		$background_padding = mp_core_get_post_meta($post_id, $meta_prefix . '_background_padding', $css_defaults['background_padding']);	
		
			//Calculate Minimum Line Height with Padding
			$min_line_height_with_padding = ( $background_padding * 3 ) + $size;
			//If the line height with padding is greater than the lineheight, we need to make the lineheight match or the layout gets thrown off
			$lineheight = $min_line_height_with_padding  > $lineheight ? $min_line_height_with_padding : $lineheight;
		
		//Text background color 
		$background_color = mp_core_get_post_meta($post_id, $meta_prefix . '_background_color', $css_defaults['background_color'] );	
		
		//Text background opacity 
		$background_opacity = mp_core_get_post_meta($post_id, $meta_prefix . '_background_opacity', $css_defaults['background_opacity']);	
	}
	else{
		//Text background spacing (padding)
		$background_padding = '0';	
		//Text background color - defaults to white
		$background_color = '#FFFFFF';	
		//Text background opacity 
		$background_opacity = '0';	
	}
	
	$css_output = '#mp-brick-' . $post_id . ' .' . $css_class . '-holder, 
		#mp-brick-' . $post_id . ' .' . $css_class . '-holder a{
			' . mp_stacks_grid_get_text_placement_css( $placement, array( 
					'line_height' => ($size),
				) ) . '; ' .
			mp_core_css_line( 'color', $color ) . 
			mp_core_css_line( 'font-size', $size, 'px' ) .
			mp_core_css_line( 'line-height', $lineheight, 'px' ) . 
		'}' . 
		mp_stacks_grid_highlight_text_css( array( 
				'brick_id' => $post_id,
				'class_name' => $css_class,
				'highlight_padding' => $background_padding, 
				'highlight_color' => $background_color, 
				'highlight_opacity' => $background_opacity
		) );
		
	return $css_output;
				
}

/**
 * Get the CSS lines for a text div based on the placement string the user has chosen
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
	
	//Add clear div to bump this below and clear floats
	$html_output = '<div class="mp-stacks-clearedfix"></div>';
	
	$html_output .= '<div class="' . $class_name . '-holder">';
		
		$html_output .= '<div class="' . $class_name . '">';
		
			$html_output .= '<span class="' . $class_name . '-highlight">' . $output_string . '</span>';
		
		$html_output .= '</div>';
			
	$html_output .= '</div>';
	
	return $html_output;
						
}

/**
 * Return the array of text pacement options a user can choose from
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function mp_stacks_get_text_position_options(){
	
	return array( 
		'below_image_left' => __( 'Below Image, Left', 'mp_stacks' ),
		'below_image_right' => __( 'Below Image, Right', 'mp_stacks' ),
		'below_image_centered' => __( 'Below Image, Centered', 'mp_stacks' ),
		
		'over_image_top_left' => __( 'Over Image, Top-Left', 'mp_stacks' ),
		'over_image_top_right' => __( 'Over Image, Top-Right', 'mp_stacks' ),
		'over_image_top_centered' => __( 'Over Image, Top-Centered', 'mp_stacks' ),
		
		'over_image_middle_left' => __( 'Over Image, Middle-Left', 'mp_stacks' ),
		'over_image_middle_right' => __( 'Over Image, Middle-Right', 'mp_stacks' ),
		'over_image_middle_centered' => __( 'Over Image, Middle-Centered', 'mp_stacks' ),
		
		'over_image_bottom_left' => __( 'Over Image, Bottom-Left', 'mp_stacks' ),
		'over_image_bottom_right' => __( 'Over Image, Bottom-Right', 'mp_stacks' ),
		'over_image_bottom_centered' => __( 'Over Image, Bottom-Centered', 'mp_stacks' ),
	);
	
}