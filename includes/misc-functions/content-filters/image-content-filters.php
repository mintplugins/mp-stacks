<?php 			
/**
 * Filter Content Output for image
 */
function mp_stacks_brick_content_output_image($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be an image	
	if ($mp_stacks_content_type == 'image'){
		
		//Set default value for $content_output to NULL
		$content_output = NULL;
		
		//Get main image URL
		$brick_main_image = do_shortcode( get_post_meta($post_id, 'brick_main_image', true) );			
			
		//Get Link URL
		$brick_url = get_post_meta($post_id, 'brick_main_image_link_url', true);
		
		//Set Image Open Type
		$brick_main_image_open_type = get_post_meta($post_id, 'brick_main_image_open_type', true);
		
		//Get image height settings
		$brick_image_width = get_post_meta($post_id, 'brick_main_image_max_width', true);
		
		//Get lightbox size
		$brick_main_image_lightbox_width = get_post_meta($post_id, 'brick_main_image_lightbox_width', true);
		$brick_main_image_lightbox_height = get_post_meta($post_id, 'brick_main_image_lightbox_height', true);
				
		//Default Target and lightbox settings
		$class_name = NULL;
		$target = '_self';
		$lightbox_width = NULL;
		$lightbox_height = NULL;
			
		//If the user has saved an open type
		if ( !empty($brick_main_image_open_type)){
			
			//If that type is a lightbox
			if ( $brick_main_image_open_type == 'lightbox'){
				
				//If there is a width and a height set
				if ( !empty( $brick_main_image_lightbox_width ) ){
					$class_name = 'mp-stacks-iframe-custom-width-height';
					$lightbox_width = ' mfp-width="' . $brick_main_image_lightbox_width . '" ';
					$lightbox_height = ' mfp-height="' . $brick_main_image_lightbox_height . '" ';
				}
				else{
					$class_name = 'mp-stacks-lightbox-link'; 
				}
			}
			//If it's a new window
			else if ( $brick_main_image_open_type == 'blank'){
				$target = '_blank'; 
			}	
	
		}
		
		//Filter the class name		
		$brick_main_image_link_class = apply_filters( 'brick_main_image_link_class', $class_name, $post_id );
		
		//Filter the link target	
		$brick_main_image_link_target = apply_filters( 'brick_main_image_link_target', $target, $post_id );
		
		//Filter other link attributes
		$brick_main_image_link_other_attr = apply_filters( 'brick_main_image_link_other_attr', '', $post_id );
		$brick_main_image_link_other_attr = $brick_main_image_link_other_attr . $lightbox_width . $lightbox_height;
		
		//Set the max-width attr
		$max_width_attr = !empty($brick_image_width) ? ' style="max-width:' . $brick_image_width . 'px;"' : NULL;
				
		//Content output
		$content_output .= '<div class="mp-stacks-image">';
		$content_output .= !empty($brick_url) ? '<a href="' . $brick_url . '" class="mp-brick-main-link ' . $brick_main_image_link_class . '" target="' . $brick_main_image_link_target . '" ' . $brick_main_image_link_other_attr . $max_width_attr . '>' : '';
		$content_output .= !empty($brick_main_image) ? '<img title="' . the_title_attribute( 'echo=0' ) . '" class="mp-brick-main-image" src="' . $brick_main_image . '"' : NULL;
		$content_output .= !empty($brick_image_width) ? $max_width_attr : NULL;
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