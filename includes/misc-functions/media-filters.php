<?php 

/**
 * Filter Media Output for text
 */
function mp_stacks_media_output_text($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be an image	
	if ($mp_stacks_media_type == 'Text'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;	
		
		/**
		 * Title Settings
		 */
 
		//Show title?
		$stack_show_title = get_post_meta($post_id, 'stack_show_title', true);
				
		//Title Style Filter
		$stack_title_style = NULL;
		$stack_title_style = has_filter('mp_stacks_title_style') ? apply_filters( 'mp_stacks_title_style', $stack_title_style, $post_id) : NULL;
		
		//Echo style tag for title into <head>
		echo !empty($stack_title_style) ? '<style>#post-' . $post_id . ' .mp_title {' . $stack_title_style .'} </style>' : NULL;
		
		/**
		 * Text Settings
		 */
		 
		//Set $text
		$text = get_the_content($post_id);
		
		//Text Style
		$stack_text_style = NULL;
		$stack_text_style = has_filter('mp_stacks_text_style') ? apply_filters( 'mp_stacks_text_style', $stack_text_style, $post_id) : NULL;
		
		//Put css into <head>
		echo !empty($stack_text_style) ? '<style>#post-' . $post_id . ' .mp_text {' . $stack_text_style .'} </style>' : NULL;
		
		/**
		 * Create Output
		 */
		 
		//First Output
		$media_output .= !empty($stack_show_title) || !empty($text) ? '<div class="text">' : NULL;
		$media_output .= !empty($stack_show_title) ? '<h2 class="mp_title">' . get_the_title($post_id) . '</h2>' : '';
		$media_output .= !empty($text) ? '<h3 class="mp_text">' . $text . '</h3>': NULL;
		$media_output .= !empty($stack_show_title) || !empty($text) ? '</div>' : NULL;
		
		//Return
		return $media_output;
	}
	else{
		//Return
		return $default_media_output;
	}
}
add_filter('mp_stacks_media_output', 'mp_stacks_media_output_text', 10, 3);
			
/**
 * Filter Media Output for image
 */
function mp_stacks_media_output_image($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be an image	
	if ($mp_stacks_media_type == 'Image'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;
		
		//Get main image URL
		$stack_main_image = get_post_meta($post_id, 'stack_main_image', true);			
	
		//Get Link URL
		$stack_url = get_post_meta($post_id, 'stack_url', true);
		
		//Media output
		$media_output .= !empty($stack_url) ? '<a href="' . $stack_url . '" class="mp_stack_main_link">' : '';
		$media_output .= !empty($stack_main_image) ? '<img title="' . get_the_title($post_id) . '" class="mp_stack_main_image" src="' . $stack_main_image . '" />' : NULL;
		$media_output .= !empty($stack_url) ? '</a>' : '';
		
		//Return
		return $media_output;
	}
	else{
		//Return
		return $default_media_output;
	}
}
add_filter('mp_stacks_media_output', 'mp_stacks_media_output_image', 10, 3);

/**
 * Filter Media Output for video
 */
function mp_stacks_media_output_video($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be an image	
	if ($mp_stacks_media_type == 'Video'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;
		
		//Get video URL
		$stack_video = get_post_meta($post_id, 'stack_video_url', true);
		
		//Get video width
		$stack_video_width = get_post_meta($post_id, 'stack_video_width', true);
			
		//Media output
		if (!empty($stack_video)){
			$media_output .= '<div class="margin-twenty">';
			$media_output .= wp_oembed_get( esc_url( $stack_video ), array('width' => $stack_video_width) );
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
add_filter('mp_stacks_media_output', 'mp_stacks_media_output_video', 10, 3);

