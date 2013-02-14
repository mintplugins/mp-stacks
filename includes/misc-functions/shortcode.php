<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_stylesheet(){
	 wp_enqueue_style( 'mp_stacks_style', plugins_url('css/mp_stacks_style.css', dirname(__FILE__)) );
}
add_action('wp_enqueue_scripts', 'mp_stacks_stylesheet');

/**
 * Shortcode which is used to display the HTML content on a post
 */
function mp_stacks_display_stack_group( $atts ) {
	global $mp_stacks_meta_box;
	$vars =  shortcode_atts( array('group' => NULL), $atts );
	$html_output = NULL;
	
	//Get the stack_group from the shortcode arguments
	$stack_group = get_term_by('slug', $vars['group'], 'mp_stack_groups');
	
	//Set the args for the new query
	$stack_group_args = array(
		'post_type' => "mp_stack",
		'posts_per_page' => 0,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'mp_stack_groups',
				'field'    => 'id',
				'terms'    => array( $stack_group->term_id ),
				'operator' => 'IN'
			)
		)
	);	
	
	//Create new query for stacks
	$stack_group = new WP_Query( apply_filters( 'latest_news_args', $stack_group_args ) );
		
	//Loop through the stack group		
	if ( $stack_group->have_posts() ) : 
		$html_output .= '<div id="mp_stack">';
		while( $stack_group->have_posts() ) : $stack_group->the_post(); 
    		
			//Default outputs back to null
			$first_output = NULL;
			$second_output = NULL;
			$content_output = NULL;
			
			$post_id = get_the_ID();
			$title = get_the_title();
			$text = get_the_content();
	
			//Min stack height
			$stack_min_height = get_post_meta($post_id, 'stack_min_height', true);
			$stack_min_height = !empty($stack_min_height) ? $stack_min_height : '50';
			
			//Get background image URL
			$stack_bg_image = get_post_meta($post_id, 'stack_bg_image', true);
			
			//Alignment
			$post_specific_alignment = get_post_meta($post_id, 'stack_alignment', true);
			
			//First Media Type
			$mp_stacks_first_media_type = get_post_meta($post_id, 'stack_first_media_type', true);
			
			//Second Media Type
			$mp_stacks_second_media_type = get_post_meta($post_id, 'stack_second_media_type', true);
			
			//First Output
			$first_output .= has_filter('mp_stacks_media_output') ? apply_filters( 'mp_stacks_media_output', $first_output, $mp_stacks_first_media_type, $post_id) : NULL;
			
			//Second Output	
			$second_output .= has_filter('mp_stacks_media_output') ? apply_filters( 'mp_stacks_media_output', $second_output, $mp_stacks_second_media_type, $post_id) : NULL;
			
			//Centered - dont use left and right
			if ($post_specific_alignment == "Centered"){
				$content_output .= '<div class="mp_stack_inner mp_stack_centered">';
				$content_output .= $first_output;
				$content_output .= $second_output;
				$content_output .= '</div>';
			//Set left and right outputs
			}else{
				//Left side HTML output
				$content_output .= '<div class="mp_stack_inner">';
					$content_output .= '<div class="mp_stack_left">';
						$content_output .= $first_output;
					$content_output .= '</div>';
				$content_output .= '</div>';
					
				//Right side HTML output
				$content_output .= '<div class="mp_stack_inner">';
					$content_output .= '<div class="mp_stack_right">';
						$content_output .= $second_output;
					$content_output .= '</div>';
				$content_output .= '</div>';
			}
			
			//Actual output
			$html_output .= '<div class="mp_stack" style="min-height:' . $stack_min_height . 'px; height:' . $stack_min_height . 'px; background-image: url(\'' . $stack_bg_image . '\');">';
				$html_output .= $content_output;
			$html_output .= '</div>';
			
		endwhile;
		$html_output .= '</div>';
	endif;
	
	//Return
	return $html_output;
}
add_shortcode( 'stack_group', 'mp_stacks_display_stack_group' );