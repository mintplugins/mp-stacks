<?php 

/**
 * Filter Media Output for text
 */
function mp_stacks_media_output_text($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be an image	
	if ($mp_stacks_media_type == 'Text'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;	
 
		//Show title?
		$stack_show_title = get_post_meta($post_id, 'stack_show_title', true);
		
		//Set $text
		//$text = get_the_content($post_id);
				
		$text = do_shortcode( get_the_content($post_id) );
		
		//Action hook to add changes to the text
		has_action('mp_stacks_text_action') ? do_action( 'mp_stacks_text_action', $post_id) : NULL;
		
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

