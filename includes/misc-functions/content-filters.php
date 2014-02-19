<?php 

/**
 * Filter Content Output for text
 */
function mp_stacks_brick_content_output_text($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be text	
	if ($mp_stacks_content_type == 'text'){
		
		//Set default value for $content_output to NULL
		$content_output = NULL;	
 
		//First line of text
		$brick_text_line_1 = do_shortcode( html_entity_decode( get_post_meta($post_id, 'brick_text_line_1', true) ) );
		
		//Second line of text
		$brick_text_line_2 = do_shortcode( html_entity_decode( get_post_meta($post_id, 'brick_text_line_2', true) ) );
				
		//Action hook to add changes to the text
		has_action('mp_stacks_text_action') ? do_action( 'mp_stacks_text_action', $post_id) : NULL;
		
		//First Output
		$content_output .= !empty($brick_text_line_1) || !empty($brick_text_line_2) ? '<div class="text">' : NULL;
		$content_output .= !empty($brick_text_line_1) ? '<div class="mp-brick-text-line-1">' . $brick_text_line_1 . '</div>' : '';
		$content_output .= !empty($brick_text_line_2) ? '<div class="mp-brick-text-line-2">' . $brick_text_line_2 . '</div>': NULL;
		$content_output .= !empty($brick_text_line_1) || !empty($brick_text_line_2) ? '</div>' : NULL;
		
		//Return
		return $content_output;
	}
	else{
		//Return
		return $default_content_output;
	}
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_text', 10, 3);
			
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
			
			$content_output .= '<div class="mp-brick-video">' . mp_core_oembed_get( $brick_video, NULL, $brick_video_max_width ) . '</div>'; 
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
 * Filter CSS Output for Line 1
 */
function mp_stacks_text_line_1_style($css_output, $post_id){
	
	//Title Color
	$brick_line_1_color = get_post_meta($post_id, 'brick_line_1_color', true);
	
	//Title Font Size
	$brick_line_1_font_size = get_post_meta($post_id, 'brick_line_1_font_size', true);
	
	//Title Full Style
	$brick_line_1_style = !empty($brick_line_1_color) ? 'color: ' . $brick_line_1_color . '; '  : NULL;
	$brick_line_1_style .= !empty($brick_line_1_font_size) ? 'font-size:' . $brick_line_1_font_size . 'px; ' : NULL;
	
	//Full sized css
	$brick_line_1_style = !empty($brick_line_1_style) ? '#mp-brick-' . $post_id . ' .mp-brick-text-line-1, #mp-brick-' . $post_id . ' .mp-brick-text-line-1 a {' . $brick_line_1_style .'}' : NULL;
		
	//Add new CSS to existing CSS passed-in
	$css_output .= $brick_line_1_style;
	
	//Return new CSS
	return $css_output;

}
add_filter('mp_brick_additional_css', 'mp_stacks_text_line_1_style', 10, 2);


/**
 * Filter CSS Output for Line 2
 */
function mp_stacks_text_line_2_style($css_output, $post_id){
	
	//Text Color
	$brick_line_2_color = get_post_meta($post_id, 'brick_line_2_color', true);
	
	//Text Font Size
	$brick_line_2_font_size = get_post_meta($post_id, 'brick_line_2_font_size', true);
	
	//Text Style
	$brick_line_2_style = !empty($brick_line_2_color) ? 'color: ' . $brick_line_2_color . '; '  : NULL;
	$brick_line_2_style .= !empty($brick_line_2_font_size) ? 'font-size:' . $brick_line_2_font_size . 'px; ' : NULL;
	
	//Full sized css
	$brick_line_2_style = !empty($brick_line_2_style) ? '#mp-brick-' . $post_id . ' .mp-brick-text-line-2, #mp-brick-' . $post_id . ' .mp-brick-text-line-2 a{' . $brick_line_2_style .'}' : NULL;
			
	//Add new CSS to existing CSS passed-in
	$css_output .= $brick_line_2_style;
	
	//Return new CSS
	return $css_output;
		
}
add_filter('mp_brick_additional_css', 'mp_stacks_text_line_2_style', 10, 2);
