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
			$brick_text_font_size = $text_area['brick_text_font_size'];
								
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
 * Filter Content Output for image
 */
function mp_stacks_brick_content_output_image($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be an image	
	if ($mp_stacks_content_type == 'image'){
		
		//Set default value for $content_output to NULL
		$content_output = NULL;
		
		//Get main image URL
		$brick_main_image = get_post_meta($post_id, 'brick_main_image', true);			
	
		//Get Link URL
		$brick_url = get_post_meta($post_id, 'brick_main_image_link_url', true);
		
		//Get image height settings
		$brick_image_width = get_post_meta($post_id, 'brick_main_image_max_width', true);
				
		$brick_main_image_link_class = apply_filters( 'brick_main_image_link_class', 'mp-brick-main-link', $post_id );
		
		$brick_main_image_link_target = apply_filters( 'brick_main_image_link_target', '_self', $post_id );
				
		//Content output
		$content_output .= '<div class="mp-stacks-image">';
		$content_output .= !empty($brick_url) ? '<a href="' . $brick_url . '" class="' . $brick_main_image_link_class . '" target="' . $brick_main_image_link_target . '" >' : '';
		$content_output .= !empty($brick_main_image) ? '<img title="' . the_title_attribute( 'echo=0' ) . '" class="mp-brick-main-image" src="' . $brick_main_image . '"' : NULL;
		$content_output .= !empty($brick_image_width) ? ' style="max-width:' . $brick_image_width . 'px;"' : NULL;
		$content_output .= !empty($brick_main_image) ? ' />' : NULL;
		$content_output .= !empty($brick_url) ? '</a>' : '';
		$content_output .= '</div>';
		
		//Return
		return $content_output;
	}
	else{
		//Return
		return $default_content_output;
	}
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_image', 10, 3);

/**
 * Filter Content Output for video
 */
function mp_stacks_brick_content_output_video($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be an image	
	if ($mp_stacks_content_type == 'video'){
		
		//Set default value for $content_output to NULL
		$content_output = NULL;
		
		//Get video URL
		$brick_video = get_post_meta($post_id, 'brick_video_url', true);
						
		//Get video max width
		$brick_video_max_width = get_post_meta($post_id, 'brick_video_max_width', true);
		$brick_video_max_width = !empty( $brick_video_max_width ) ? $brick_video_max_width : NULL;
			
		//Content output
		if (!empty($brick_video)){
			
			$args = array(
				'min_width' => NULL,
				'max_width' => $brick_video_max_width,
				'iframe_css_id' => NULL,
				'iframe_css_class' => NULL,
			);
			
			$content_output .= '<div class="mp-stacks-video">' . mp_core_oembed_get( $brick_video, $args ) . '</div>'; 
		}
		
		//Return
		return $content_output;
	}
	else{
		//Return
		return $default_content_output;	
	}
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_video', 10, 3);

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
		 
		//Text Color
		$brick_text_color = $text_area_vars['brick_text_color'];
		
		//Text Font Size
		$brick_text_font_size = $text_area_vars['brick_text_font_size'];
		
		//Text Line Height
		$brick_text_line_height = isset( $text_area_vars['brick_text_line_height'] ) ? $text_area_vars['brick_text_line_height'] : NULL;
		
		//Text Paragraph Spacing
		$brick_text_paragraph_margin_bottom = isset( $text_area_vars['brick_text_paragraph_margin_bottom'] ) ? $text_area_vars['brick_text_paragraph_margin_bottom'] : NULL;
		
		//Text Full Style
		$brick_text_style = !empty($brick_text_color) ? 'color: ' . $brick_text_color . '; '  : NULL;
		$brick_text_style .= !empty($brick_text_font_size) ? 'font-size:' . $brick_text_font_size . 'px; ' : NULL;
		$brick_text_style .= !empty($brick_text_line_height) ? 'line-height:' . $brick_text_line_height . 'px; ' : NULL;
		
		//Assemble css
		if ( !empty($brick_text_style) ) {
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text *, ';
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text a {' . $brick_text_style .'}';
		}
		
		//If there is a paragraph spacing variable
		if ( is_numeric( $brick_text_paragraph_margin_bottom ) ){
			$brick_text_areas_styles .= '#mp-brick-' . $post_id . ' .mp-stacks-text-area-' . $counter . ' .mp-brick-text p { margin-bottom:' . $brick_text_paragraph_margin_bottom .'px; }';
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
