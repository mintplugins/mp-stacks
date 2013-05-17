<?php 

/**
 * Filter Media Output for text
 */
function mp_stacks_brick_media_output_text($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be an image	
	if ($mp_stacks_media_type == 'text'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;	
 
		//Show title?
		$brick_show_title = get_post_meta($post_id, 'brick_show_title', true);
		
		//Set $text
		//$text = get_the_content($post_id);
			
		$text = do_shortcode( get_the_content($post_id) );
		
		//Action hook to add changes to the text
		has_action('mp_stacks_text_action') ? do_action( 'mp_stacks_text_action', $post_id) : NULL;
		
		//First Output
		$media_output .= !empty($brick_show_title) || !empty($text) ? '<div class="text">' : NULL;
		$media_output .= !empty($brick_show_title) ? '<h2 class="mp_title">' . get_the_title($post_id) . '</h2>' : '';
		$media_output .= !empty($text) ? '<h3 class="mp_text">' . $text . '</h3>': NULL;
		$media_output .= !empty($brick_show_title) || !empty($text) ? '</div>' : NULL;
		
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
		
		//Media output
		$media_output .= !empty($brick_url) ? '<a href="' . $brick_url . '" class="mp-brick-main-link">' : '';
		$media_output .= !empty($brick_main_image) ? '<img title="' . get_the_title($post_id) . '" class="mp-brick-main-image" src="' . $brick_main_image . '" />' : NULL;
		$media_output .= !empty($brick_url) ? '</a>' : '';
		
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
			$media_output .= '<div class="margin-twenty">';
			$media_output .= mp_core_oembed_get( $brick_video, $brick_video_min_width, $brick_video_max_width ); 
			$media_output .= '</div>';
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

