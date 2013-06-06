<?php
/**
 * Function which return the HTML output for a stack
 * Parameter: Stack ID
 */
function mp_stack( $stack_id ){
	
	$html_output = NULL;
		
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => 0,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'mp_stacks',
				'field'    => 'id',
				'terms'    => array( $stack_id ),
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
						
		$html_output .= '<div id="mp_stack_' . $stack_id . '" class="mp-stack">';
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
    		
			//Default outputs back to null
			$first_output = NULL;
			$second_output = NULL;
			$content_output = NULL;
			
			$post_id = get_the_ID();
	
			//Min brick height
			$brick_max_width = get_post_meta($post_id, 'brick_max_width', true);
			$brick_max_width = !empty($brick_max_width) ? $brick_max_width : '5000';
			
			//Min brick height
			$brick_min_height = get_post_meta($post_id, 'brick_min_height', true);
			$brick_min_height = !empty($brick_min_height) ? $brick_min_height : '50';
			
			//Get background image URL
			$brick_bg_image = get_post_meta($post_id, 'brick_bg_image', true);
			
			//Get background color
			$brick_bg_color = get_post_meta($post_id, 'brick_bg_color', true);
			
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
				$content_output .= '<div class="mp-brick-media-container mp-brick-centered">';
				$content_output .= $first_output;
				$content_output .= $second_output;
				$html_output .= '<a class="mp-stack-edit-link" href="' . get_edit_post_link( $post_id ) . '" >' . __( 'Edit', 'mp_knapstack' ) . '</a>';
				$content_output .= '</div>';
			//Set left and right outputs
			}else{
				//Left side HTML output
				$content_output .= '<div class="mp-brick-media-container">';
					$content_output .= '<div class="mp-brick-left">';
						$content_output .= $first_output;
					$content_output .= '</div>';
				$content_output .= '</div>';
					
				//Right side HTML output
				$content_output .= '<div class="mp-brick-media-container">';
					$content_output .= '<div class="mp-brick-right">';
						$content_output .= $second_output;
						$html_output .= '<a class="mp-stack-edit-link" href="' . get_edit_post_link( $post_id ) . '" >' . __( 'Edit', 'mp_knapstack' ) . '</a>';
					$content_output .= '</div>';
				$content_output .= '</div>';
			}
			
			//CSS
			$content_output .= '<style scoped>';
			$content_output .= '#mp-brick-' . $post_id . ' {background-image: url(\'' . $brick_bg_image . '\'); background-color:' . $brick_bg_color . ';}';
			$content_output .= '#mp-brick-' . $post_id . ' .mp-brick-outer{max-width:' . $brick_max_width . 'px;}';
			$content_output .= '#mp-brick-' . $post_id . ' .mp-brick-inner{min-height:' . $brick_min_height . 'px; height:' . $brick_min_height . 'px;}';
			$content_output .= '</style>';
			
			//Tablet sized CSS
			$content_output .= '<style scoped>';
			$content_output .= '@media screen and (max-width: 980px){#mp-brick-' . $post_id . ' {min-height:' . ($brick_min_height*.70) . 'px; height:' . ($brick_min_height*.70) . 'px; }}';
			$content_output .= '@media screen and (max-width: 980px){#mp-brick-' . $post_id . ' .mp-brick-inner {min-height:' . ($brick_min_height*.70) . 'px; height:' . ($brick_min_height*.70) . 'px; }}';
			$content_output .= '</style>';
			
			//Mobile Sized CSS
			$content_output .= '<style scoped>';
			$content_output .= '@media screen and (max-width: 420px){#mp-brick-' . $post_id . ' {min-height:' . ($brick_min_height*.30) . 'px; height:' . ($brick_min_height*.30) . 'px; }}';
			$content_output .= '@media screen and (max-width: 420px){#mp-brick-' . $post_id . ' .mp-brick-inner {min-height:' . ($brick_min_height*.30) . 'px; height:' . ($brick_min_height*.30) . 'px; }}';
			$content_output .= '</style>';
			
			//Post class for this brick
			$post_class_string = 'mp-brick';
			$post_class_array = get_post_class( array( 'mp-brick', $post_id ) );
			
			foreach ( $post_class_array as $class ){
				$post_class_string .=  ' ' . $class;
			}
		       
			//Actual output
			$html_output .= '<div id="mp-brick-' . $post_id . '" class=" ' . $post_class_string . '">';
				$html_output .= '<div class="mp-brick-outer">';
					$html_output .= '<div class="mp-brick-inner">';
						$html_output .= $content_output; 
					$html_output .= '</div>';
				$html_output .= '</div>';
			$html_output .= '</div>';
			
		endwhile;
		$html_output .= '</div>';
	endif;
	
	//Filter for adding output to the html output 
	$html_output = apply_filters( 'mp_stacks_html_output', $html_output );
	
	//Reset query
	wp_reset_query();
	
	//Return
	return $html_output;	
}