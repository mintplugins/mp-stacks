<?php		
/**
 * Function which returns the CSS output for the bricks in a stack
 * Parameter: Stack ID
 */
function mp_stack_css( $stack_id, $echo = false ) {		
		
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
		'meta_key' => 'mp_stack_order_' . $stack_id,
		'orderby' => 'meta_value_num menu_order',
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
	
	$css_output = '<style type="text/css">';
	
	//Loop through the stack group		
	if ( $mp_stack_query->have_posts() ) { 
		
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
		
			//Build Brick CSS Output
			$css_output .= mp_brick_css( get_the_ID(), $stack_id );
			
		endwhile;
		
		$css_output .= '</style>';
		
		if ( $echo == true ){
			echo $css_output;
		}
		else{
			return $css_output;
		}
		
	}
}

/**
 * Function which returns the CSS output for a brick
 * Parameter: Brick ID
 */
function mp_brick_css( $post_id, $stack_id = NULL ){
	
		//If we are getting this as part of a stack
		if ( !empty( $stack_id ) ){
			$css_output = '';
		}
		//If this is just a single brick
		else{
			$css_output = '<style type="text/css">';
		}
		
		//Brick CSS
		$css_brick_filter = apply_filters( 'mp_brick_css', '', $post_id );
		if ( !empty($css_brick_filter) ) {
			$css_output .= '#mp-brick-' . $post_id .'{';
			$css_output .= $css_brick_filter;
			$css_output .= '}';
		}
		
		//Brick Background CSS
		$css_brick_bg_filter = apply_filters( 'mp_brick_bg_css', '', $post_id );
		if ( !empty($css_brick_bg_filter) ) {
			$css_output .= '#mp-brick-' . $post_id .' .mp-brick-bg {';
			$css_output .= $css_brick_bg_filter;
			$css_output .= '}';
		}
		
		//Brick Background:after CSS
		$mp_brick_bg_after_css_filter = apply_filters( 'mp_brick_bg_after_css', '', $post_id );
		if ( !empty($mp_brick_bg_after_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id .' .mp-brick-bg:after {';
			$css_output .= $mp_brick_bg_after_css_filter;
			$css_output .= '}';
		}
		
		//Brick Outer CSS
		$mp_brick_outer_css_filter = apply_filters( 'mp_brick_outer_css', '', $post_id );
		if ( !empty($mp_brick_outer_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-outer{';
			$css_output .= $mp_brick_outer_css_filter;
			$css_output .= '}';
		}
		
		//Brick Inner CSS
		$mp_brick_inner_css_filter = apply_filters( 'mp_brick_inner_css', '', $post_id );
		if ( !empty($mp_brick_inner_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-inner{';
			$css_output .= $mp_brick_inner_css_filter;
			$css_output .= '}';
		}
		
		//Brick Container CSS
		$mp_brick_container_css_filter = apply_filters( 'mp_brick_container_css', '', $post_id );
		if ( !empty($mp_brick_container_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-type-container>div{';
			$css_output .= $mp_brick_container_css_filter;
			$css_output .= '}';
		}
					
		//Additional CSS
		$css_output .= apply_filters( 'mp_brick_additional_css', $css_output, $post_id );
		
		//If this is a single brick
		if ( empty( $stack_id ) ){
			$css_output .= '</style>';
		}
		
		return $css_output;	
				
}

/**
 * Function which return the HTML output for a stack
 * Parameter: Stack ID
 */
function mp_stack( $stack_id ){		
	
	$html_output = NULL;
		
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
		'meta_key' => 'mp_stack_order_' . $stack_id,
		'orderby' => 'meta_value_num menu_order',
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
	
	$html_output .= '<div id="mp_stack_' . $stack_id . '" class="mp-stack">';
	
	$term_exists = get_term_by('id', $stack_id, 'mp_stacks');
	
	//If this stack doesn't exist
	if (!$term_exists){
		$html_output .= '<div class="mp-brick no-brick">';
			$html_output .= '<div class="mp-brick-inner">';
				$html_output .= '<div class="mp-brick-content-types">';
						$html_output .= '<div class="mp-brick-content-types-inner">';
							$html_output .= '<div class="mp-brick-content-type-container mp-brick-centered">';
								$html_output .= '<p style="padding:20px;">';
									$html_output .=  __('Oops! This stack doesn\'t exist. It may have been deleted. You should remove it by editing this:', 'mp_stacks') . '<br /><a class="mp-brick-add-new-link" href="' . get_edit_post_link() . '" >' . __( 'Edit', 'mp_stacks' ) . '</a>';
								$html_output .= '</p>';
							$html_output .= '</div>';		
					$html_output .= '</div>';		
				$html_output .= '</div>';		
			$html_output .= '</div>';
		$html_output .= '</div>';		
	}
	//If there are bricks in this stack
	elseif ( $mp_stack_query->have_posts() ) {
		
		//Add_action hook 
		do_action( 'mp_stacks_before_stack' );
		
		//Loop through the stack group		
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
    		
			//Build Brick Output
			$html_output .= mp_brick( get_the_ID(), $stack_id );
			
		endwhile;
	
	}
	//If there aren't any bricks in this stack
	else{
		
		$html_output .= '<div class="mp-brick no-brick" >';
			$html_output .= '<div class="mp-brick-inner">';
				$html_output .= '<div class="mp-brick-content-types">';
						$html_output .= '<div class="mp-brick-content-types-inner">';
							$html_output .= '<div class="mp-brick-content-type-container mp-brick-centered">';
			
								$html_output .=  __('No Bricks are currently in this Stack. ', 'mp_stacks') . '<br /><a class="mp-brick-add-new-link" href="' . add_query_arg( array( 'post_type' => 'mp_brick', 'mp-stacks-minimal-admin' => 'true', 'mp_stack_id_new' => $stack_id, 'mp_stack_order_new' => '1000' ), admin_url( 'post-new.php' ) ) . '" >' . __( '+ Add A Brick To this Stack', 'mp_stacks' ) . '</a>';
							$html_output .= '</div>';		
					$html_output .= '</div>';		
				$html_output .= '</div>';		
			$html_output .= '</div>';
		$html_output .= '</div>';
		
	};
	
	$html_output .= '</div>';
	
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
 * Parameter: Stack ID - The Stack which is calling this brick
 */
function mp_brick( $post_id, $stack_id = NULL ){
			
			//Handle CSS for this brick
			global $mp_bricks_on_page;
			$mp_bricks_on_page[$post_id] = $post_id;
	
			//Default outputs back to null
			$first_output = NULL;
			$second_output = NULL;
			$content_output = NULL;
			$html_output = NULL;			
			
			//Alignment
			$post_specific_alignment = get_post_meta($post_id, 'brick_alignment', true);
			
			//First Media Type
			$mp_stacks_first_content_type = get_post_meta($post_id, 'brick_first_content_type', true);
			
			//Second Media Type
			$mp_stacks_second_content_type = get_post_meta($post_id, 'brick_second_content_type', true);
			
			//First Output
			$first_output = has_filter('mp_stacks_brick_content_output') ? apply_filters( 'mp_stacks_brick_content_output', $first_output, $mp_stacks_first_content_type, $post_id) : NULL;
			
			//Second Output	
			$second_output = has_filter('mp_stacks_brick_content_output') ? apply_filters( 'mp_stacks_brick_content_output', $second_output, $mp_stacks_second_content_type, $post_id) : NULL;
			
			//Centered - dont use left and right
			if ($post_specific_alignment == "centered"){
				$brick_container_classes = 'mp-brick-centered';
			}
			//All Left - all on left
			else if ($post_specific_alignment == "allleft"){
				$brick_container_classes = 'mp-brick-allleft';
			}
			//All Right - all on right
			else if ($post_specific_alignment == "allright"){
				$brick_container_classes = 'mp-brick-allright';
			}
			//Set left and right outputs
			else{
				$brick_container_classes = NULL;
			}
				
			//First Content Type HTML output
			$content_output .= '<div class="mp-brick-content-type-container ' . $brick_container_classes . '">';
				$content_output .= '<div class="mp-brick-first-content-type">';
					$content_output .= $first_output;
				$content_output .= '</div>';
			$content_output .= '</div>';
			
			//Second Content Type HTML output
			$content_output .= '<div class="mp-brick-content-type-container ' . $brick_container_classes . '">';
				$content_output .= '<div class="mp-brick-second-content-type">';
					$content_output .= $second_output;
				$content_output .= '</div>';
			$content_output .= '</div>';
			
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
		    
											   
			//Actual output
			$html_output .= '<div id="mp-brick-' . $post_id . '" class=" ' . $post_class_string . '" ' . $extra_brick_attributes . '>';
				
				//Brick Meta Div
				$html_output .= '<div class="mp-brick-meta">';
					//Edit Brick Link
					if ( is_user_logged_in() && current_user_can('edit_theme_options') ) {
						$html_output .= '<a class="mp-brick-edit-link" href="' . add_query_arg( array( 'mp-stacks-minimal-admin' => 'true' ), get_edit_post_link( $post_id ) )  . '" >' . __( 'Edit This Brick', 'mp_stacks' ) . '</a>';
						
						//Get Menu Order Info for this Brick						
						$mp_stack_order = get_post_meta( $post_id, 'mp_stack_order_' . $stack_id, true);
						$mp_stack_order = !empty($mp_stack_order) ? $mp_stack_order : 1000;
						
						//If this brick is being shown as part of a stack
						if ( !empty( $stack_id ) ){
							
							//Show buttons to add new bricks above/below
							$html_output .= '<a class="mp-brick-add-before-link" href="' . add_query_arg( array( 'post_type' => 'mp_brick', 'mp-stacks-minimal-admin' => 'true', 'mp_stack_id_new' => $stack_id, 'mp_stack_order_new' => $mp_stack_order - 1 ), admin_url( 'post-new.php' ) ) . '" >' . __( '+ Add Brick Before', 'mp_stacks' ) . '</a>';
							$html_output .= '<a class="mp-brick-add-after-link" href="' . add_query_arg( array( 'post_type' => 'mp_brick', 'mp-stacks-minimal-admin' => 'true', 'mp_stack_id_new' => $stack_id, 'mp_stack_order_new' => $mp_stack_order + 1  ), admin_url( 'post-new.php' ) )  . '" >' . __( '+ Add Brick After', 'mp_stacks' ) . '</a>';
						
							//Get number of bricks in this stack
							$number_of_bricks = mp_core_number_postpercat( $stack_id );
							
							//If this brick is being shown as part of a stack and there is more than 1 brick in that stack
							if ( $number_of_bricks > 1 ){
								
								//Show buttons to add new bricks above/below
								$html_output .= '<a class="mp-brick-reorder-bricks" href="' . add_query_arg( array( 'post_type' => 'mp_brick', 'mp-stacks-minimal-admin' => 'true', 'mp_stacks' => $stack_id ), admin_url( 'edit.php' ) ) . '" >' . __( 'Re-Order Bricks', 'mp_stacks' ) . '</a>';
								
							}						
						}
					}
				$html_output .= '</div>';
				
				//Brick BG Div
				$html_output .= '<div class="mp-brick-bg" ' . $extra_brick_bg_attributes . '></div>';
				
				//Brick Content Divs
				$html_output .= '<div class="mp-brick-outer"' . $extra_brick_outer_attributes . ' >';
					$html_output .= '<div class="mp-brick-inner">';
						$html_output .= '<div class="mp-brick-content-types">';
								$html_output .= '<div class="mp-brick-content-types-inner">';
									$html_output .= $content_output; 
								$html_output .= '</div>';
						$html_output .= '</div>';
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
	
	//Get background color opacity
	$brick_bg_color_opacity = get_post_meta($post_id, 'brick_bg_color_opacity', true);
	$brick_bg_color_opacity = !empty($brick_bg_color_opacity) ? $brick_bg_color_opacity/100 : 1;
	
	//Get background color
	$brick_bg_color = get_post_meta($post_id, 'brick_bg_color', true);
	
	//Convert to rgb from hex
	$brick_bg_color_rgb_array = mp_core_hex2rgb($brick_bg_color);
	
	//If a color has truly been set
	if ( !empty( $brick_bg_color_rgb_array ) ){
	
		//Add style lines to css output
		$css_output .= 'background-color:rgba(' . $brick_bg_color_rgb_array[0] . ', ' . $brick_bg_color_rgb_array[1] . ' , ' . $brick_bg_color_rgb_array[2] . ', ' . $brick_bg_color_opacity . ');';
	}
			
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
	$brick_bg_image_opacity = get_post_meta( $post_id, 'brick_bg_image_opacity', true );
	$brick_bg_image_opacity = empty($brick_bg_image_opacity) ? 1 : $brick_bg_image_opacity/100;
	
	//Get background display type
	$brick_display_type = get_post_meta($post_id, 'brick_display_type', true);
	
	//Add style lines to css output
	$css_output .= 'background-image: url(\'' . $brick_bg_image . '\');';
	$css_output .= $brick_display_type == 'fill' || empty( $brick_display_type ) ? 'background-size: cover;' : NULL;
	$css_output .= 'opacity:' . $brick_bg_image_opacity . ';';
			
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
	
	if (!empty($brick_max_width)){
		
		//Outer style lines
		$css_output .= 'max-width:' . $brick_max_width . 'px;';
			
	}
	
	//Return CSS Output
	return $css_output;	
	
}
add_filter( 'mp_brick_outer_css', 'mp_stacks_default_brick_outer_css', 10, 2);

/**
 * Filter Function which returns the css style lines for a brick's inner div
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_inner_css( $css_output, $post_id ){
	
	//Min brick height
	$brick_min_height = get_post_meta($post_id, 'brick_min_height', true);
	$brick_min_height = !empty($brick_min_height) ? $brick_min_height : '50';
	
	//Min content-type margin
	$brick_content_type_margin = get_post_meta($post_id, 'brick_min_above_below', true);
	$brick_content_type_margin = is_numeric($brick_content_type_margin) ? $brick_content_type_margin : '10';
	
	//Inner styles
	$css_output .= 'min-height:' . $brick_min_height . 'px; height:' . $brick_min_height . 'px;';
	$css_output .= 'margin-top:' . $brick_content_type_margin . 'px; margin-bottom:' . $brick_content_type_margin . 'px;';
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_inner_css', 'mp_stacks_default_brick_inner_css', 10, 2);

/**
 * Filter Function which returns conditional responsive css margins for a brick
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_margins( $css_output, $post_id ){
	
	//Check which content types have been set
	$second_type_set = get_post_meta( $post_id, 'brick_second_content_type', true );
	$second_type_set = get_post_meta( $post_id, 'brick_second_content_type', true );
	
	//Check alignment
	$brick_alignment = get_post_meta( $post_id, 'brick_alignment', true );
	
	//Check c1's above margins
	$brick_min_above_c1 = get_post_meta( $post_id, 'brick_min_above_c1', true );
	$brick_min_above_c1 = is_numeric($brick_min_above_c1) ? $brick_min_above_c1 : '0';
	//Check c1's below margins
	$brick_min_below_c1 = get_post_meta( $post_id, 'brick_min_below_c1', true );
	$brick_min_below_c1 = is_numeric($brick_min_below_c1) ? $brick_min_below_c1 : '0';
	
	//Check c2's above margins
	$brick_min_above_c2 = get_post_meta( $post_id, 'brick_min_above_c2', true );
	$brick_min_above_c2 = is_numeric($brick_min_above_c2) ? $brick_min_above_c2 : '0';
	//Check c2's below margins
	$brick_min_below_c2 = get_post_meta( $post_id, 'brick_min_below_c2', true );
	$brick_min_below_c2 = is_numeric($brick_min_below_c2) ? $brick_min_below_c2 : '0';
	
	//If the second type has been set
	//if ( !empty($second_type_set) && $second_type_set != 'none' ){
		
		//If the alignment is centered
		if ( $brick_alignment == 'centered' ){
			
			//Add custom margin to the 1st content type when centered
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types > .mp-brick-content-types-inner > .mp-brick-centered:first-child
			{
				margin-top:' . $brick_min_above_c1 . 'px;	
				margin-bottom:' . $brick_min_below_c1 . 'px;	
			}';
			
			//Add custom margin to the 2nd content type when centered
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types > .mp-brick-content-types-inner  > .mp-brick-centered:nth-child(2)	
			{
				margin-top:' . $brick_min_above_c2 . 'px;	
				margin-bottom:' . $brick_min_below_c2 . 'px;	
			}';
			
		}		
		/**
		 * Left Right Alignment.
		 * We have to do some extra stuff to left-right because of the table layout and how it changes when we go to centered on mobile
		 */
		else{
			//Add custom margin to the 1st content type when leftright
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types > .mp-brick-content-types-inner > .mp-brick-content-type-container .mp-brick-first-content-type
			{
				margin-top:' . $brick_min_above_c1 . 'px;	
				margin-bottom:' . $brick_min_below_c1 . 'px;	
			}';
			
			//Add custom margin to the 2nd content type when centered
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types > .mp-brick-content-types-inner  > .mp-brick-content-type-container .mp-brick-second-content-type
			{
				margin-top:' . $brick_min_above_c2 . 'px;	
				margin-bottom:' . $brick_min_below_c2 . 'px;	
			}';
			
			/**
			 * Left Right Alignment goes centered upon mobile below. Change margins from content type divs to their containers so the margins overlap nicely
			 */
			 
			//Remove Margin from content type 1
			$css_output .= '#mp-brick-' . $post_id . '[max-width~=\'600px\'] .mp-brick-content-types > .mp-brick-content-types-inner > .mp-brick-content-type-container .mp-brick-first-content-type
			{
				margin-top:0px;	
				margin-bottom:0px;	
			}';
			
			//Remove Margin from content type 2
			$css_output .= '#mp-brick-' . $post_id . '[max-width~=\'600px\'] .mp-brick-content-types > .mp-brick-content-types-inner  > .mp-brick-content-type-container .mp-brick-second-content-type
			{
				margin-top:0px;	
				margin-bottom:0px;	
			}';
			
			//Add custom margin to the 1st content type when centered
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types > .mp-brick-content-types-inner > .mp-brick-content-type-container:first-child
			{
				margin-top:' . $brick_min_above_c1 . 'px;	
				margin-bottom:' . $brick_min_below_c1 . 'px;	
			}';
			
			//Add custom margin to the 2nd content type when centered
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types > .mp-brick-content-types-inner  > .mp-brick-content-type-container:nth-child(2)	
			{
				margin-top:' . $brick_min_above_c2 . 'px;	
				margin-bottom:' . $brick_min_below_c2 . 'px;	
			}';
		}
	
	//}
					
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_additional_css', 'mp_stacks_default_brick_margins', 10, 2);

/**
 * Output css for all bricks on this page into the footer of the theme
 * Parameter: none
 * Global Variable: array $mp_bricks_on_page This array contains all the ids of every brick previously called on this page
 */
function mp_stacks_footer_css(){
	
	//Get all the stack ids used on this page			
	global $mp_bricks_on_page;
	
	if ( !empty( $mp_bricks_on_page ) ){
		
		echo '<style type="text/css">';
		
		//Show css for each
		foreach ( $mp_bricks_on_page as $brick_id ){
			echo mp_brick_css( $brick_id, 'footer_css' ); 
		}
		
		echo '</style>';
		
		//If there is at least 1 brick on this page, enqueue the stuff we need
		if ( isset( $brick_id ) ){
						
			//Enqueue hook for add-on scripts 
			do_action ( 'mp_stacks_enqueue_scripts', $mp_bricks_on_page );
			
		}
	}
		
}
add_action( 'wp_footer', 'mp_stacks_footer_css'); 