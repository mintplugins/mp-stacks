<?php 

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
 * Filter Content Output for the Document <head> for video based on this brick's settings
 */
function mp_stacks_brick_head_output_video($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be an image	
	if ($mp_stacks_content_type == 'video'){
				
		//Get video URL
		$brick_video = get_post_meta($post_id, 'brick_video_url', true);
						
		//Add the Open Graph (Facebook) output for video to the head
		return mp_core_open_graph_video_meta_tags( $brick_video );
		
	}
	else{
		//Return
		return $default_content_output;	
	}
}
add_filter('mp_stacks_brick_head_output', 'mp_stacks_brick_head_output_video', 10, 3);