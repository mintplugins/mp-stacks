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
    		
			//Build Brick Output
			$html_output .= mp_brick( get_the_ID() );
			
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

/**
 * Function which return the HTML output for a brick
 * Parameter: Post ID
 */
function mp_brick( $post_id ){
	
			//Default outputs back to null
			$first_output = NULL;
			$second_output = NULL;
			$content_output = NULL;
			$html_output = NULL;			
			
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
				$content_output .= '<div class="mp-brick-centered-first">';
				$content_output .= $first_output;
				$content_output .= '</div>';
				$content_output .= '<div class="mp-brick-centered-second">';
				$content_output .= $second_output;
				$content_output .= '</div>';
				if ( is_user_logged_in() ) {
					$html_output .= '<a class="mp-stack-edit-link" href="' . add_query_arg( array( 'mp-stacks-edit-link' => 'true' ), get_edit_post_link( $post_id ) ) . '" >' . __( 'Edit This Brick', 'mp_stacks' ) . '</a>';
				}
				$content_output .= '</div>';
			}
			//All Left - all on left
			else if ($post_specific_alignment == "allleft"){
				//Left side HTML output
				$content_output .= '<div class="mp-brick-media-container">';
					$content_output .= '<div class="mp-brick-left">';
						$content_output .= $first_output;
					$content_output .= '</div>';
					$content_output .= '<div class="mp-brick-left">';
						$content_output .= $second_output;
						if ( is_user_logged_in() ) {
							$html_output .= '<a class="mp-stack-edit-link" href="' . add_query_arg( array( 'mp-stacks-edit-link' => 'true' ), get_edit_post_link( $post_id ) )  . '" >' . __( 'Edit This Brick', 'mp_stacks' ) . '</a>';
						}
					$content_output .= '</div>';
				$content_output .= '</div>';
					
				//Right side HTML output
				$content_output .= '<div class="mp-brick-media-container">';
					$content_output .= '<div class="mp-brick-right">';
					$content_output .= '</div>';
				$content_output .= '</div>';
			}
			//All Right - all on right
			else if ($post_specific_alignment == "allright"){
				//Left side HTML output
				$content_output .= '<div class="mp-brick-media-container">';
					$content_output .= '<div class="mp-brick-left">';
					$content_output .= '</div>';
				$content_output .= '</div>';
					
				//Right side HTML output
				$content_output .= '<div class="mp-brick-media-container">';
					$content_output .= '<div class="mp-brick-right">';
						$content_output .= $first_output;
					$content_output .= '</div>';
					$content_output .= '<div class="mp-brick-right">';
						$content_output .= $second_output;
						if ( is_user_logged_in() ) {
							$html_output .= '<a class="mp-stack-edit-link" href="' . add_query_arg( array( 'mp-stacks-edit-link' => 'true' ), get_edit_post_link( $post_id ) )  . '" >' . __( 'Edit This Brick', 'mp_stacks' ) . '</a>';
						}
					$content_output .= '</div>';
				$content_output .= '</div>';
			}
			//Set left and right outputs
			else{
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
						if ( is_user_logged_in() ) {
							$html_output .= '<a class="mp-stack-edit-link" href="' . add_query_arg( array( 'mp-stacks-edit-link' => 'true' ), get_edit_post_link( $post_id ) )  . '" >' . __( 'Edit This Brick', 'mp_stacks' ) . '</a>';
						}
					$content_output .= '</div>';
				$content_output .= '</div>';
			}
			
			//Post class for this brick
			$post_class_string = apply_filters( 'mp_stacks_brick_class', 'mp-brick', $post_id );
			$post_class_array = get_post_class( array( 'mp-brick', $post_id ) );
			
			//Extra Brick Attributes
			$extra_brick_attributes = apply_filters( 'mp_stacks_extra_brick_attributes', NULL, $post_id );
			
			//Extra Brick Background Attributes
			$extra_brick_bg_attributes = apply_filters( 'mp_stacks_extra_brick_bg_attributes', NULL, $post_id );
			
			//Extra Brick Outer Attributes
			$extra_brick_outer_attributes = apply_filters( 'mp_stacks_extra_brick_outer_attributes', NULL, $post_id );
						
			foreach ( $post_class_array as $class ){
				$post_class_string .=  ' ' . $class;
			}
		    
			//CSS
			$css_output = '<style scoped>';
			
			//Brick CSS
			$css_output .= '#mp-brick-' . $post_id .'{';
			$css_output = apply_filters( 'mp_brick_css', $css_output, $post_id );
			$css_output .= '}';
			
			//Brick Background CSS
			$css_output .= '#mp-brick-' . $post_id .' .mp-brick-bg {';
			$css_output = apply_filters( 'mp_brick_bg_css', $css_output, $post_id );
			$css_output .= '}';
			
			//Brick Background:after CSS
			$css_output .= '#mp-brick-' . $post_id .' .mp-brick-bg:after {';
			$css_output = apply_filters( 'mp_brick_bg_after_css', $css_output, $post_id );
			$css_output .= '}';
			
			//Brick Outer CSS
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-outer{';
			$css_output = apply_filters( 'mp_brick_outer_css', $css_output, $post_id );
			$css_output .= '}';
			
			//Brick Inner CSS
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-inner{';
			$css_output = apply_filters( 'mp_brick_inner_css', $css_output, $post_id );
			$css_output .= '}';
			
			//Brick Container CSS
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-media-container>div{';
			$css_output = apply_filters( 'mp_brick_container_css', $css_output, $post_id );
			$css_output .= '}';
						
			//Additional CSS
			$css_output .= apply_filters( 'mp_brick_additional_css', $css_output, $post_id );
			
			//End CSS
			$css_output .= '</style>';			
									   
			//Actual output
			$html_output .= $css_output;
			$html_output .= '<div id="mp-brick-' . $post_id . '" class=" ' . $post_class_string . '" ' . $extra_brick_attributes . '>';
				$html_output .= '<div class="mp-brick-bg" ' . $extra_brick_bg_attributes . '></div>';
				$html_output .= '<div class="mp-brick-outer"' . $extra_brick_outer_attributes . ' >';
					$html_output .= '<div class="mp-brick-inner">';
						$html_output .= $content_output; 
					$html_output .= '</div>';
				$html_output .= '</div>';
			$html_output .= '</div>';
			
			//Return the brick
			return $html_output;
				
}

/**
 * Filter Function which returns the css style lines for a brick
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_css( $css_output, $post_id ){
	
	//No styles currently
	return $css_output;
	
}
//add_filter( 'mp_brick_css', 'mp_stacks_default_brick_css', 10, 2);

/**
 * Filter Function which returns the css style lines for a brick background
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_bg_css( $css_output, $post_id ){
	
	//Get background color
	$brick_bg_color = get_post_meta($post_id, 'brick_bg_color', true);
	
	//Add style lines to css output
	$css_output .= 'background-color:' . $brick_bg_color . ';';
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_bg_css', 'mp_stacks_default_brick_bg_css', 10, 2);

/**
 * Filter Function which returns the css style lines for a brick background:after
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_bg_after_css( $css_output, $post_id ){
	
	//Get background image URL
	$brick_bg_image = get_post_meta($post_id, 'brick_bg_image', true);
	$brick_bg_image = is_ssl() ? str_replace( 'http://', 'https://', $brick_bg_image ) : $brick_bg_image;
	
	//Get background image opacity
	$brick_bg_opacity = get_post_meta( $post_id, 'brick_bg_opacity', true );
	$brick_bg_opacity = empty($brick_bg_opacity) ? 1 : $brick_bg_opacity/100;
	
	//Get background display type
	$brick_display_type = get_post_meta($post_id, 'brick_display_type', true);
	
	//Add style lines to css output
	$css_output .= 'background-image: url(\'' . $brick_bg_image . '\');';
	$css_output .= $brick_display_type == 'fill' || empty( $brick_display_type ) ? 'background-size: cover;' : NULL;
	$css_output .= 'opacity:' . $brick_bg_opacity . ';';
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_bg_after_css', 'mp_stacks_default_brick_bg_after_css', 10, 2);

/**
 * Filter Function which returns the css style lines for a brick's outer div
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_outer_css( $css_output, $post_id ){
	
	//Max brick width
	$brick_max_width = get_post_meta($post_id, 'brick_max_width', true);
	$brick_max_width = !empty($brick_max_width) ? $brick_max_width : '5000';
				
	//Outer style lines
	$css_output .= 'max-width:' . $brick_max_width . 'px;';
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_outer_css', 'mp_stacks_default_brick_outer_css', 10, 2);

/**
 * Filter Function which returns the css style lines for a brick's media container div
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_container_css( $css_output, $post_id ){
	
	//Min media margin
	$brick_media_margin = get_post_meta($post_id, 'brick_min_above_below', true);
	$brick_media_margin = is_numeric($brick_media_margin) ? $brick_media_margin : '10';
			
	//Outer style lines
	$css_output .= 'margin-top:' . $brick_media_margin . 'px; margin-bottom:' . $brick_media_margin . 'px;';
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_container_css', 'mp_stacks_default_brick_container_css', 10, 2);

/**
 * Filter Function which returns the css style lines for a brick's inner div
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_inner_css( $css_output, $post_id ){
	
	//Min brick height
	$brick_min_height = get_post_meta($post_id, 'brick_min_height', true);
	$brick_min_height = !empty($brick_min_height) ? $brick_min_height : '50';
	
	//Inner styles
	$css_output .= 'min-height:' . $brick_min_height . 'px; height:' . $brick_min_height . 'px;';
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_inner_css', 'mp_stacks_default_brick_inner_css', 10, 2);

/**
 * Filter Function which returns the tablet css for a brick
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_tablet_css( $css_output, $post_id ){
	
	//Min brick height
	$brick_min_height = get_post_meta($post_id, 'brick_min_height', true);
	$brick_min_height = !empty($brick_min_height) ? $brick_min_height : '50';
	
	//Tablet sized CSS
	$css_output .= '@media screen and (max-width: 980px){#mp-brick-' . $post_id . ' {min-height:' . ($brick_min_height*.70) . 'px; height:' . ($brick_min_height*.70) . 'px; }}';
	$css_output .= '@media screen and (max-width: 980px){#mp-brick-' . $post_id . ' .mp-brick-inner {min-height:' . ($brick_min_height*.70) . 'px; height:' . ($brick_min_height*.70) . 'px; }}';
				
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_additional_css', 'mp_stacks_default_brick_tablet_css', 10, 2);