<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_stylesheet(){
	//css
	wp_enqueue_style( 'mp_stacks_style', plugins_url('css/mp-stacks-style.css', dirname(__FILE__)) );
}
add_action('wp_enqueue_scripts', 'mp_stacks_stylesheet');

/**
 * Shortcode which is used to display the HTML content on a post
 */
function mp_stacks_display_mp_stack( $atts ) {
	global $mp_stacks_meta_box;
	$vars =  shortcode_atts( array('stack' => NULL), $atts );
	$html_output = NULL;
	
	//Get the mp_stacks (taxonomy term) from the shortcode arguments
	$mp_stack = get_term_by('slug', $vars['stack'], 'mp_stacks');
	
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => 0,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'mp_stacks',
				'field'    => 'id',
				'terms'    => array( $mp_stack->term_id ),
				'operator' => 'IN'
			)
		)
	);	
		
	//Create new query for stacks
	$mp_stack_query = new WP_Query( apply_filters( 'mp_stacks_args', $mp_stacks_args ) );
		
	//Loop through the stack group		
	if ( $mp_stack_query->have_posts() ) : 
		
		//Add_action hook 
		do_action( 'mp_stacks_before_stack' );
						
		$html_output .= '<div id="mp_stack_' . $mp_stack->term_id . '" class="mp-stack">';
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
    		
			//Default outputs back to null
			$first_output = NULL;
			$second_output = NULL;
			$content_output = NULL;
			
			$post_id = get_the_ID();
	
			//Min stack height
			$brick_min_height = get_post_meta($post_id, 'brick_min_height', true);
			$brick_min_height = !empty($brick_min_height) ? $brick_min_height : '50';
			
			//Get background image URL
			$brick_bg_image = get_post_meta($post_id, 'brick_bg_image', true);
			
			//Alignment
			$post_specific_alignment = get_post_meta($post_id, 'brick_alignment', true);
			
			//First Media Type
			$mp_stacks_first_media_type = get_post_meta($post_id, 'brick_first_media_type', true);
			
			//Second Media Type
			$mp_stacks_second_media_type = get_post_meta($post_id, 'brick_second_media_type', true);
			
			//First Output
			$first_output = has_filter('mp_stacks_brick_media_output') ? apply_filters( 'mp_stacks_brick_media_output', $first_output, $mp_stacks_first_media_type, $post_id) : NULL;
			
			//Second Output	
			$second_output = has_filter('mp_stacks_brick_media_output') ? apply_filters( 'mp_stacks_brick_media_output', $second_output, $mp_stacks_second_media_type, $post_id) : NULL;
			
			//Centered - dont use left and right
			if ($post_specific_alignment == "centered"){
				$content_output .= '<div class="mp-brick-inner mp-brick-centered">';
				$content_output .= $first_output;
				$content_output .= $second_output;
				$content_output .= '</div>';
			//Set left and right outputs
			}else{
				//Left side HTML output
				$content_output .= '<div class="mp-brick-inner">';
					$content_output .= '<div class="mp-brick-left">';
						$content_output .= $first_output;
					$content_output .= '</div>';
				$content_output .= '</div>';
					
				//Right side HTML output
				$content_output .= '<div class="mp-brick-inner">';
					$content_output .= '<div class="mp-brick-right">';
						$content_output .= $second_output;
					$content_output .= '</div>';
				$content_output .= '</div>';
			}
			
			//CSS
			$content_output .= '<style scoped>';
			$content_output .= '#brick-' . $post_id . ' {min-height:' . $brick_min_height . 'px; height:' . $brick_min_height . 'px; background-image: url(\'' . $brick_bg_image . '\');}';
			$content_output .= '</style>';
			
			//Tablet sized CSS
			$content_output .= '<style scoped>';
			$content_output .= '@media screen and (max-width: 980px){#brick-' . $post_id . ' {min-height:' . ($brick_min_height*.70) . 'px; height:' . ($brick_min_height*.70) . 'px; }}';
			$content_output .= '</style>';
			
			//Mobile Sized CSS
			$content_output .= '<style scoped>';
			$content_output .= '@media screen and (max-width: 420px){#brick-' . $post_id . ' {min-height:' . ($brick_min_height*.30) . 'px; height:' . ($brick_min_height*.30) . 'px; }}';
			$content_output .= '</style>';
			
			//Post class for this brick
			$post_class_string = 'mp-brick';
			$post_class_array = get_post_class( array( 'mp-brick', $post_id ) );
			
			foreach ( $post_class_array as $class ){
				$post_class_string .=  ' ' . $class;
			}
		       
			//Actual output
			$html_output .= '<div id="brick-' . $post_id . '" class=" ' . $post_class_string . '">';
				$html_output .= $content_output;
			$html_output .= '</div>';
			
		endwhile;
		$html_output .= '</div>';
	endif;
	
	//Filter for adding output to the html output 
	$html_output = apply_filters( 'mp_stacks_html_output', $html_output );
	
	//Return
	return $html_output;
}
add_shortcode( 'mp_stacks', 'mp_stacks_display_mp_stack' );