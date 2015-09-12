<?php 

/**
 * Filter Content Output for singletext, the "new" way.
 */
function mp_stacks_brick_content_output_singletext($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be text	
	if ($mp_stacks_content_type == 'singletext'){
		
		//Set default value for $content_output to NULL
		$content_output = NULL;	
 		
		//Get text repeater
		$text_areas = get_post_meta($post_id, 'mp_stacks_singletext_content_type_repeater', true);
		
		//If nothing is saved in the repeater
		if ( empty( $text_areas ) ){
			return;	
		}
	
		//Counter
		$counter = 1;
		
		foreach( $text_areas as $text_area ){
			
			//The actual text
			$brick_text = do_shortcode( html_entity_decode( $text_area['brick_text'] ) );
			
			//Desired Font Size
			$default_brick_text_font_size = $text_area['brick_text_font_size'];
								
			//First Output
			$content_output .= !empty($brick_text) ? '<div class="mp-stacks-text-area mp-stacks-text-area-' . $counter . '">' : NULL;
			$content_output .= !empty($brick_text) ? '<div class="mp-brick-text">' . $brick_text . '</div>' : '';
			$content_output .= !empty($brick_text) ? '</div>' : NULL;
			
			//Increment Counter
			$counter = $counter + 1;
			
		}
		
		//Return
		return $content_output;
	}
	else{
		//Return
		return $default_content_output;
	}
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_singletext', 10, 3);

/**
 * Filter Content Output for doubletext, the "old" way. We keep this purely for backwards compatibility.
 */
function mp_stacks_brick_content_output_doubletext($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be text	
	if ($mp_stacks_content_type == 'text'){
		
		//Set default value for $content_output to NULL
		$content_output = NULL;	
 		
		//Get text repeater
		$text_areas = get_post_meta($post_id, 'mp_stacks_text_content_type_repeater', true);
		
		//If nothing is saved in the repeater
		if ( empty( $text_areas ) ){
			return;	
		}
	
		//Counter
		$counter = 1;
		
		foreach( $text_areas as $text_area ){
			
			//First line of text
			$brick_text_line_1 = do_shortcode( html_entity_decode( $text_area['brick_text_line_1'] ) );
			
			//Desired Font Size for Line 1
			$brick_line_1_font_size = $text_area['brick_line_1_font_size'];
			
			//Second line of text
			$brick_text_line_2 = do_shortcode( html_entity_decode( $text_area['brick_text_line_2'] ) );
			
			//Desired Font Size for Line 2
			$brick_line_2_font_size = $text_area['brick_line_2_font_size'];
					
			//Action hook to add changes to the text
			has_action('mp_stacks_text_action') ? do_action( 'mp_stacks_text_action', $post_id) : NULL;
			
			//First Output
			$content_output .= !empty($brick_text_line_1) || !empty($brick_text_line_2) ? '<div class="mp-stacks-text-area mp-stacks-text-area-' . $counter . '">' : NULL;
			$content_output .= !empty($brick_text_line_1) ? '<div class="mp-brick-text-line-1" mp_desired_size="' . $brick_line_1_font_size . '">' . $brick_text_line_1 . '</div>' : '';
			$content_output .= !empty($brick_text_line_2) ? '<div class="mp-brick-text-line-2" mp_desired_size="' . $brick_line_2_font_size . '">' . $brick_text_line_2 . '</div>': NULL;
			$content_output .= !empty($brick_text_line_1) || !empty($brick_text_line_2) ? '</div>' : NULL;
			
			//Increment Counter
			$counter = $counter + 1;
			
		}
		
		//Return
		return $content_output;
	}
	else{
		//Return
		return $default_content_output;
	}
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_doubletext', 10, 3);

/**
 * Filter CSS Output text areas. This deals with the "singletext", "new" style for text.
 */
function mp_stacks_singletext_styles($css_output, $post_id){
	
	//Get text repeater
	$text_areas_vars = get_post_meta($post_id, 'mp_stacks_singletext_content_type_repeater', true);	
	
	//If nothing is saved in the repeater
	if ( empty( $text_areas_vars ) ){
		return $css_output;	
	}
	
	//Create variable for css output
	$brick_text_areas_styles = NULL;
	
	//Counter
	$counter = 1;
	
	foreach( $text_areas_vars as $text_area_vars ){
		
		/**
		 * Filter CSS Output this text
		 */
		 
		//Text Colors
		$default_brick_text_color = isset( $text_area_vars['brick_text_color'] ) ? $text_area_vars['brick_text_color'] : NULL;
		$tablet_brick_text_color = isset( $text_area_vars['brick_text_color_tablet'] ) ? $text_area_vars['brick_text_color_tablet'] : NULL;
		$mobile_brick_text_color = isset( $text_area_vars['brick_text_color_mobile'] ) ? $text_area_vars['brick_text_color_mobile'] : NULL;
		
		//Text Font Size
		$default_brick_text_font_size = isset( $text_area_vars['brick_text_font_size'] ) ? $text_area_vars['brick_text_font_size'] : NULL;
		$tablet_brick_text_font_size = isset( $text_area_vars['brick_text_font_size_tablet'] ) ? $text_area_vars['brick_text_font_size_tablet'] : NULL;
		$mobile_brick_text_font_size = isset( $text_area_vars['brick_text_font_size_mobile'] ) ? $text_area_vars['brick_text_font_size_mobile'] : NULL;
		
		//Text Line Height
		$default_brick_text_line_height = isset( $text_area_vars['brick_text_line_height'] ) ? $text_area_vars['brick_text_line_height'] : NULL;
		$tablet_brick_text_line_height = isset( $text_area_vars['brick_text_line_height_tablet'] ) ? $text_area_vars['brick_text_line_height_tablet'] : NULL;
		$mobile_brick_text_line_height = isset( $text_area_vars['brick_text_line_height_mobile'] ) ? $text_area_vars['brick_text_line_height_mobile'] : NULL;
		
		//Text Paragraph Spacing
		$default_brick_text_paragraph_margin_bottom = isset( $text_area_vars['brick_text_paragraph_margin_bottom'] ) ? $text_area_vars['brick_text_paragraph_margin_bottom'] : NULL;
		$tablet_brick_text_paragraph_margin_bottom = isset( $text_area_vars['brick_text_paragraph_margin_bottom_tablet'] ) ? $text_area_vars['brick_text_paragraph_margin_bottom_tablet'] : NULL;
		$mobile_brick_text_paragraph_margin_bottom = isset( $text_area_vars['brick_text_paragraph_margin_bottom_mobile'] ) ? $text_area_vars['brick_text_paragraph_margin_bottom_mobile'] : NULL;
		
		//Default (Desktop) Text Full Style
		$default_brick_text_style = !empty($default_brick_text_color) ? 'color: ' . $default_brick_text_color . '; '  : NULL;
		$default_brick_text_style .= !empty($default_brick_text_font_size) ? 'font-size:' . $default_brick_text_font_size . 'px; ' : NULL;
		$default_brick_text_style .= !empty($default_brick_text_line_height) ? 'line-height:' . $default_brick_text_line_height . 'px; ' : NULL;
		
		//Tablet Full Style
		$tablet_brick_text_style = !empty($tablet_brick_text_color) ? 'color: ' . $tablet_brick_text_color . '; '  : NULL;
		$tablet_brick_text_style .= !empty($tablet_brick_text_font_size) ? 'font-size:' . $tablet_brick_text_font_size . 'px; ' : NULL;
		$tablet_brick_text_style .= !empty($tablet_brick_text_line_height) ? 'line-height:' . $tablet_brick_text_line_height . 'px; ' : NULL;
		
		//Mobile Full Style
		$mobile_brick_text_style = !empty($mobile_brick_text_color) ? 'color: ' . $mobile_brick_text_color . '; '  : NULL;
		$mobile_brick_text_style .= !empty($mobile_brick_text_font_size) ? 'font-size:' . $mobile_brick_text_font_size . 'px; ' : NULL;
		$mobile_brick_text_style .= !empty($mobile_brick_text_line_height) ? 'line-height:' . $mobile_brick_text_line_height . 'px; ' : NULL;
		
		//Assemble Default (Desktop) CSS
		if ( !empty($default_brick_text_style) ) {
			
			//Desktop CSS
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text *, ';
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text a {' . $default_brick_text_style .'}';
			
			//If there is a paragraph spacing variable
			if ( is_numeric( $default_brick_text_paragraph_margin_bottom ) ){
				$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text p { margin-bottom:' . $default_brick_text_paragraph_margin_bottom .'px; }';
			}
		
		}
		
		//Assemble Tablet CSS
		if ( !empty($tablet_brick_text_style) ) {
			
			//Tablet CSS
			$brick_text_areas_styles .= '@media (max-width: 961px) {';
				$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text *, ';
				$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text a {' . $tablet_brick_text_style .'}';
				
				//If there is a paragraph spacing variable
				if ( is_numeric( $tablet_brick_text_paragraph_margin_bottom ) ){
					$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text p { margin-bottom:' . $tablet_brick_text_paragraph_margin_bottom .'px; }';
				}
			
			$brick_text_areas_styles .= '}';
			
		}
		//Assemble Mobile CSS
		if ( !empty($mobile_brick_text_style) ) {
			
			//Mobile CSS
			$brick_text_areas_styles .= '@media (max-width: 600px) {';
				$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text *, ';
				$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text a {' . $mobile_brick_text_style .'}';
				
				//If there is a paragraph spacing variable
				if ( is_numeric( $mobile_brick_text_paragraph_margin_bottom ) ){
					$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text p { margin-bottom:' . $mobile_brick_text_paragraph_margin_bottom .'px; }';
				}
				
			$brick_text_areas_styles .= '}';
		}
				
		//Increment counter
		$counter = $counter + 1;
		
	}
		
	//Add new CSS to existing CSS passed-in
	$css_output .= $brick_text_areas_styles;
	
	//Return new CSS
	return $css_output;

}
add_filter('mp_brick_additional_css', 'mp_stacks_singletext_styles', 10, 2);


/**
 * Filter CSS Output text areas. This is for the "doubletext", "old" style and remains here purely for backwards compatibility
 */
function mp_stacks_text_styles($css_output, $post_id){
	
	//Get text repeater
	$text_areas_vars = get_post_meta($post_id, 'mp_stacks_text_content_type_repeater', true);	
	
	//If nothing is saved in the repeater
	if ( empty( $text_areas_vars ) ){
		return $css_output;	
	}
	
	//Create variable for css output
	$brick_text_areas_styles = NULL;
	
	//Counter
	$counter = 1;
	
	foreach( $text_areas_vars as $text_area_vars ){
		
		/**
		 * Filter CSS Output for Line 1
		 */
		 
		//Line 1 Color
		$brick_line_1_color = $text_area_vars['brick_line_1_color'];
		
		//Line 1 Font Size
		$brick_line_1_font_size = $text_area_vars['brick_line_1_font_size'];
		
		//Line 1 Line Height
		$brick_line_1_line_height = isset( $text_area_vars['brick_line_1_line_height'] ) ? $text_area_vars['brick_line_1_line_height'] : NULL;
		
		//Line 1 Paragraph Spacing
		$brick_line_1_paragraph_margin_bottom = isset( $text_area_vars['brick_line_1_paragraph_margin_bottom'] ) ? $text_area_vars['brick_line_1_paragraph_margin_bottom'] : NULL;
		
		//Line 1 Full Style
		$brick_line_1_style = !empty($brick_line_1_color) ? 'color: ' . $brick_line_1_color . '; '  : NULL;
		$brick_line_1_style .= !empty($brick_line_1_font_size) ? 'font-size:' . $brick_line_1_font_size . 'px; ' : NULL;
		$brick_line_1_style .= !empty($brick_line_1_line_height) ? 'line-height:' . $brick_line_1_line_height . 'px; ' : NULL;
		
		//Assemble css
		if ( !empty($brick_line_1_style) ) {
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text-line-1 *, ';
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text-line-1 a {' . $brick_line_1_style .'}';
		}
		
		//If there is a paragraph spacing variable
		if ( is_numeric( $brick_line_1_paragraph_margin_bottom ) ){
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text-line-1 p { margin-bottom:' . $brick_line_1_paragraph_margin_bottom .'px; }';
		}
		
		/**
		 * Filter CSS Output for Line 2
		 */
		 
		//Line 2 Color
		$brick_line_2_color = $text_area_vars['brick_line_2_color'];
		
		//Line 2 Font Size
		$brick_line_2_font_size = $text_area_vars['brick_line_2_font_size'];
		
		//Line 2 Line Height
		$brick_line_2_line_height = isset( $text_area_vars['brick_line_2_line_height'] ) ? $text_area_vars['brick_line_2_line_height'] : NULL;
		
		//Line 2 Paragraph Spacing
		$brick_line_2_paragraph_margin_bottom = isset( $text_area_vars['brick_line_2_paragraph_margin_bottom'] ) ? $text_area_vars['brick_line_2_paragraph_margin_bottom'] : NULL;
		
		//Line 2 Full Style
		$brick_line_2_style = !empty($brick_line_2_color) ? 'color: ' . $brick_line_2_color . '; '  : NULL;
		$brick_line_2_style .= !empty($brick_line_2_font_size) ? 'font-size:' . $brick_line_2_font_size . 'px; ' : NULL;
		$brick_line_2_style .= !empty($brick_line_2_line_height) ? 'line-height:' . $brick_line_2_line_height . 'px; ' : NULL;
		
		//Assemble css
		if ( !empty($brick_line_2_style) ) {
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text-line-2 *, ';
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text-line-2 a {' . $brick_line_2_style .'}';
		}
		
		//If there is a paragraph spacing variable
		if ( is_numeric( $brick_line_2_paragraph_margin_bottom ) ){
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text-line-2 p { margin-bottom:' . $brick_line_2_paragraph_margin_bottom .'px; }';
		}
		
		//Increment counter
		$counter = $counter + 1;
		
	}
		
	//Add new CSS to existing CSS passed-in
	$css_output .= $brick_text_areas_styles;
	
	//Return new CSS
	return $css_output;

}
add_filter('mp_brick_additional_css', 'mp_stacks_text_styles', 10, 2);
