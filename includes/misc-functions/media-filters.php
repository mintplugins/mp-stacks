<?php 

/**
 * Filter Media Output for text
 */
function mp_stacks_brick_media_output_text($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be text	
	if ($mp_stacks_media_type == 'text'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;	
 
		//First line of text
		$brick_text_line_1 = do_shortcode( html_entity_decode( get_post_meta($post_id, 'brick_text_line_1', true) ) );
		
		//Second line of text
		$brick_text_line_2 = do_shortcode( html_entity_decode( get_post_meta($post_id, 'brick_text_line_2', true) ) );
				
		//Action hook to add changes to the text
		has_action('mp_stacks_text_action') ? do_action( 'mp_stacks_text_action', $post_id) : NULL;
		
		//First Output
		$media_output .= !empty($brick_text_line_1) || !empty($brick_text_line_2) ? '<div class="text">' : NULL;
		$media_output .= !empty($brick_text_line_1) ? '<div class="mp-brick-text-line-1">' . $brick_text_line_1 . '</div>' : '';
		$media_output .= !empty($brick_text_line_2) ? '<div class="mp-brick-text-line-2">' . $brick_text_line_2 . '</div>': NULL;
		$media_output .= !empty($brick_text_line_1) || !empty($brick_text_line_2) ? '</div>' : NULL;
		
		//Return
		return $media_output;
	}
	else{
		//Return
		return $default_media_output;
	}
}
add_filter('mp_stacks_brick_media_output', 'mp_stacks_brick_media_output_text', 10, 3);
			
/**
 * Filter Media Output for image
 */
function mp_stacks_brick_media_output_image($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be an image	
	if ($mp_stacks_media_type == 'image'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;
		
		//Get main image URL
		$brick_main_image = get_post_meta($post_id, 'brick_main_image', true);			
	
		//Get Link URL
		$brick_url = get_post_meta($post_id, 'brick_url', true);
		
		//Get image height settings
		$brick_image_height = get_post_meta($post_id, 'brick_image_max_height', true);
		
		//Set Image URL
		//$brick_main_image = !empty($brick_image_width) ? mp_aq_resize( $brick_main_image, $brick_image_width, $brick_image_height ) : $brick_main_image;
		
		//Media output
		$media_output .= '<div class="mp-stacks-image">';
		$media_output .= !empty($brick_url) ? '<a href="' . $brick_url . '" class="mp-brick-main-link">' : '';
		$media_output .= !empty($brick_main_image) ? '<img title="' . get_the_title($post_id) . '" class="mp-brick-main-image" src="' . $brick_main_image . '"' : NULL;
		$media_output .= !empty($brick_image_height) ? ' style="max-height:' . $brick_image_height . 'px;" ' : NULL;
		$media_output .= ' />';
		$media_output .= !empty($brick_url) ? '</a>' : '';
		$media_output .= '</div>';
		
		//Return
		return $media_output;
	}
	else{
		//Return
		return $default_media_output;
	}
}
add_filter('mp_stacks_brick_media_output', 'mp_stacks_brick_media_output_image', 10, 3);

/**
 * Filter Media Output for video
 */
function mp_stacks_brick_media_output_video($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be an image	
	if ($mp_stacks_media_type == 'video'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;
		
		//Get video URL
		$brick_video = get_post_meta($post_id, 'brick_video_url', true);
				
		//Get video min width
		$brick_video_min_width = get_post_meta($post_id, 'brick_video_min_width', true);
		$brick_video_min_width = !empty( $brick_video_min_width ) ? $brick_video_min_width : NULL;
		
		//Get video max width
		$brick_video_max_width = get_post_meta($post_id, 'brick_video_max_width', true);
		$brick_video_max_width = !empty( $brick_video_max_width ) ? $brick_video_max_width : NULL;
			
		//Media output
		if (!empty($brick_video)){
			
			$media_output .= '<div class="mp-brick-video">' . mp_core_oembed_get( $brick_video, $brick_video_min_width, $brick_video_max_width ) . '</div>'; 
		}
		
		//Return
		return $media_output;
	}
	else{
		//Return
		return $default_media_output;	
	}
}
add_filter('mp_stacks_brick_media_output', 'mp_stacks_brick_media_output_video', 10, 3);

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
	
	//Tablet sized css
	$brick_line_1_font_size = !empty($brick_line_1_font_size) ? '@media screen and (max-width: 980px){#mp-brick-' . $post_id . ' .mp-brick-text-line-1 { font-size: ' . ($brick_line_1_font_size*.70) . 'px; }}' : NULL;
	
	//Add new CSS to existing CSS passed-in
	$css_output .= $brick_line_1_style . $brick_line_1_font_size;
	
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
		
	//Tablet sized css
	$brick_line_2_font_size = !empty($brick_line_2_font_size) ? '@media screen and (max-width: 980px){#mp-brick-' . $post_id . ' .mp-brick-text-line-2 { font-size: ' . ($brick_line_2_font_size*.70) . 'px; }}' : NULL;
	
	//Add new CSS to existing CSS passed-in
	$css_output .= $brick_line_2_style . $brick_line_2_font_size;
	
	//Return new CSS
	return $css_output;
		
}
add_filter('mp_brick_additional_css', 'mp_stacks_text_line_2_style', 10, 2);
