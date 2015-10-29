<?php		
/**
 * Function which returns the CSS output for the bricks in a stack
 * Parameter: Stack ID
 */
function mp_stack_css( $stack_id, $echo = false, $include_style_tags = true ) {		
	
	global $mp_stacks_on_page;
	
	//This array has 2 main parts: css_required, and css_complete. Here, we add this stack to the css_required list so we know the CSS has been taken care of.
	$mp_stacks_on_page['css_complete'][$stack_id] = $stack_id;
	
	$css_output = NULL;
	
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
		'meta_key' => 'mp_stack_order_' . $stack_id,
		'orderby' => 'meta_value_num',
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
	
	$head_output = NULL;
	
	//Loop through the stack group		
	if ( $mp_stack_query->have_posts() ) { 
		
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
			
			$post_id = get_the_ID();
			
			//Build Brick CSS Output (minified)
			$css_output .= preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', mp_brick_css( $post_id, $stack_id ) );
		
		endwhile;
		
	}
	
	if ( $echo == true ){
		echo $css_output;
	}
	else{
		return $css_output;
	}
	
}

/**
 * Function which returns the CSS output for a brick
 * Parameter: Brick ID
 */
function mp_brick_css( $post_id, $stack_id = NULL ){
		
		$css_output = NULL;
		
		$first_content_type = get_post_meta($post_id, 'brick_first_content_type', true);	
		$second_content_type = get_post_meta($post_id, 'brick_second_content_type', true);			
				
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
			$css_output .= '#mp-brick-' . $post_id .' .mp-brick-bg, #mp-brick-' . $post_id .' .mp-brick-bg-inner {';
			$css_output .= $css_brick_bg_filter;
			$css_output .= '}';
		}
		
		//Brick Background:after CSS
		$mp_brick_bg_after_css_filter = apply_filters( 'mp_brick_bg_after_css', '', $post_id );
		if ( !empty($mp_brick_bg_after_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id .' .mp-brick-bg-inner:after {';
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
		
		//Brick First Container CSS
		$mp_brick_first_container_css_filter = apply_filters( 'mp_brick_first_container_css', '', $post_id );
		if ( !empty($mp_brick_first_container_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types-inner .mp-brick-content-type-container:first-child{';
			$css_output .= $mp_brick_first_container_css_filter;
			$css_output .= '}';
		}
		
		//Brick Second Container CSS
		$mp_brick_second_container_css_filter = apply_filters( 'mp_brick_second_container_css', '', $post_id );
		if ( !empty($mp_brick_second_container_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types-inner .mp-brick-content-type-container:nth-child(2){';
			$css_output .= $mp_brick_second_container_css_filter;
			$css_output .= '}';
		}
		
		//Brick First Content Type CSS
		$mp_brick_first_content_type_css_filter = apply_filters( 'mp_brick_first_content_type_css_filter', '', $post_id );
		if ( !empty($mp_brick_first_content_type_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-first-content-type{';
			$css_output .= $mp_brick_first_content_type_css_filter;
			$css_output .= '}';
		}
		
		//Brick First Content Type Mobile CSS
		$mp_brick_first_content_type_mobile_css_filter = apply_filters( 'mp_brick_first_content_type_mobile_css_filter', '', $post_id );
		if ( !empty($mp_brick_first_content_type_mobile_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . '[max-width~=\'600px\'] .mp-brick-first-content-type{';
			$css_output .= $mp_brick_first_content_type_mobile_css_filter;
			$css_output .= '}';
		}
		
		//Brick Second Content Type CSS
		$mp_brick_second_content_type_css_filter = apply_filters( 'mp_brick_second_content_type_css_filter', '', $post_id );
		if ( !empty($mp_brick_second_content_type_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-second-content-type{';
			$css_output .= $mp_brick_second_content_type_css_filter;
			$css_output .= '}';
		}
		
		//Brick Second Content Type Mobile CSS
		$mp_brick_second_content_type_mobile_css_filter = apply_filters( 'mp_brick_second_content_type_mobile_css_filter', '', $post_id );
		if ( !empty($mp_brick_second_content_type_mobile_css_filter) ) {
			$css_output .= '#mp-brick-' . $post_id . '[max-width~=\'600px\'] .mp-brick-second-content-type{';
			$css_output .= $mp_brick_second_content_type_mobile_css_filter;
			$css_output .= '}';
		}
		
		//Additional CSS
		$css_output .= apply_filters( 'mp_brick_additional_css', '', $post_id, $first_content_type, $second_content_type );
				
		return '<style id="mp-brick-css-' . $post_id . '" type="text/css">' . $css_output .'</style>';	
				
}

/**
 * Function which return the HTML output for a stack
 * Parameter: Stack ID
 */
function mp_stack( $stack_id ){		
	
	global $mp_stacks_on_page;
	
	//This array has 2 main parts: css_required, and css_complete. Here, we add this stack to the css_required list. When the CSS is output, we add it to to the css_complete list.
	$mp_stacks_on_page['css_required'][$stack_id] = $stack_id;
	
	$html_output = NULL;
		
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
		'meta_key' => 'mp_stack_order_' . $stack_id,
		'orderby' => 'meta_value_num',
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
	
	$total_bricks = $mp_stack_query->found_posts;
	
	$html_output .= '<div id="mp_stack_' . $stack_id . '" class="mp-stack">';
	
	$term_exists = get_term_by('id', $stack_id, 'mp_stacks');
	
	//If this stack doesn't exist (only show error to logged-in users) 
	if ( !$term_exists ){
		
		if ( is_user_logged_in() && current_user_can('edit_theme_options') ) {
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
	}
	//If there are bricks in this stack
	elseif ( $mp_stack_query->have_posts() ) {
		
		//Add_action hook 
		do_action( 'mp_stacks_before_stack' );
					
		//Set the default for the brick counter
		$brick_number = 1;
					
		//Loop through the stack group		
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
			
			//Build Brick Output
			$html_output .= mp_brick( get_the_ID(), $stack_id, $brick_number );
			
			$brick_number = $brick_number + 1;
			
		endwhile;
	
	}
	//If there aren't any bricks in this stack
	else{
		
		$html_output .= '<div class="mp-brick no-brick" >';
			$html_output .= '<div class="mp-brick-inner">';
				$html_output .= '<div class="mp-brick-content-types">';
						$html_output .= '<div class="mp-brick-content-types-inner">';
							$html_output .= '<div class="mp-brick-content-type-container mp-brick-centered">';
			
								$html_output .=  __('No Bricks are currently in this Stack. ', 'mp_stacks') . '<br /><a class="mp-brick-add-new-link" href="' . mp_core_add_query_arg( array( 'post_type' => 'mp_brick', 'mp-stacks-minimal-admin' => 'true', 'mp_stack_id' => $stack_id, 'mp_stack_order_new' => '1000' ), admin_url( 'post-new.php' ) ) . '" >' . __( '+ Add A Brick To this Stack', 'mp_stacks' ) . '</a>';
							$html_output .= '</div>';		
					$html_output .= '</div>';		
				$html_output .= '</div>';		
			$html_output .= '</div>';
		$html_output .= '</div>';
		
	};
	
	$html_output .= '</div>';
	
	//Filter for adding output to the html output 
	$html_output = apply_filters( 'mp_stacks_html_output', $html_output, $stack_id );
	
	//Reset query
	wp_reset_query();
	
	//Return
	return $html_output;	
}

/**
 * Function which return the HTML output for a brick
 * Parameter: Post ID - The post id for this mp_brick post.
 * Parameter: Stack ID - The Stack which is calling this brick
 * Parameter: Brick Number - This is brick number X in this Stack
 * Parameter: Args - Additional Arguments for the function.
 */
function mp_brick( $post_id, $stack_id = NULL, $brick_number = NULL, $args = array() ){
	
	global $mp_stacks_active_bricks, $wpdb;
	
	//Check if the brick being called even exists
	if ( !mp_core_post_exists( $post_id ) ){
		return NULL;	
	}
	
	//Setup the default args
	$default_args = array(
		'show_stack_controls_for_admin' => true	
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	//Get the ID of the Stack this Brick belongs to. 
	$saved_stack_id = mp_core_get_post_meta( $post_id, 'mp_stack_id', 'no_stack_id_saved_yet' );
			
	//If there is no Stack ID saved to the meta of this Brick yet (Backwards Compatibility)
	if ( $saved_stack_id == 'no_stack_id_saved_yet' || empty( $saved_stack_id ) ){
		//Originally, Bricks could be part of multiple Stacks and thus, didn't save a Stack ID.
		//Now, as of Version 1.0.2.9, Bricks can only belong to one Stack - so we'll save the $stack_id using the one passed into this function if one wasn't added to the post meta when this brick was created.
		
		if ( !empty( $stack_id ) ){

			//Now that we have the Stack ID, save it to the Brick under the meta_key "mp_stack_id"
			update_post_meta( $post_id, 'mp_stack_id', intval( $stack_id ) );
			$saved_stack_id = $stack_id;
			
		}
	}
	
	//If, for some strange fringe-style reason, the Stack ID passed to this function doesn't match up with the saved one, settle that discrepancy by using the one passed to this function.
	if ( ( !empty( $stack_id ) && $stack_id != false && $stack_id != 'false' ) && $stack_id != $saved_stack_id ){
		update_post_meta( $post_id, 'mp_stack_id', intval( $stack_id ) );	
	}
	
	//If no Stack ID was passed (in ajax brick-loads for example), use the saved one.
	if ( empty( $stack_id ) || !$stack_id || $stack_id == 'false' ){
		$stack_id = $saved_stack_id;
	}
					
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
	
	//Add this brick id and content types to the global list of active bricks
	$mp_stacks_active_bricks[$post_id] = array( $mp_stacks_first_content_type, $mp_stacks_second_content_type );
	
	//Make sure all the default scripts are properly enqueued
	mp_stacks_frontend_enqueue();
	
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
	
	//If there is a first output
	$first_content_type_display = empty( $first_output ) ? 'style="display:block;"' : NULL;	
	
	//If there is a second output
	$second_content_type_display = empty( $second_output ) ? 'style="display:block;"' : NULL;				
	
	//First Content Type HTML output
	$content_output .= '<div id="mp-brick-' . $post_id . '-first-content-type-container" class="mp-brick-content-type-container ' . $brick_container_classes . '">';
		$content_output .= '<div id="mp-brick-' . $post_id . '-first-content-type" class="mp-brick-first-content-type" ' . $first_content_type_display . '>';
			$content_output .= $first_output;
		$content_output .= '</div>';
	$content_output .= '</div>';
	
	//Second Content Type HTML output
	$content_output .= '<div id="mp-brick-' . $post_id . '-second-content-type-container" class="mp-brick-content-type-container ' . $brick_container_classes . '">';
		$content_output .= '<div id="mp-brick-' . $post_id . '-second-content-type" class="mp-brick-second-content-type" ' . $second_content_type_display . '>';
			$content_output .= $second_output;
		$content_output .= '</div>';
	$content_output .= '</div>';
	
	//Extra Brick Attributes
	$extra_brick_attributes = apply_filters( 'mp_stacks_extra_brick_attributes', NULL, $post_id );
	
	//Extra Brick Background Attributes
	$extra_brick_bg_attributes = apply_filters( 'mp_stacks_extra_brick_bg_attributes', NULL, $post_id );
	
	//Extra Brick Outer Attributes
	$extra_brick_outer_attributes = apply_filters( 'mp_stacks_extra_brick_outer_attributes', NULL, $post_id );
	
	//Post class for this brick
	$post_class_string = get_post_meta($post_id, 'brick_class_name', true);
	$post_class_string = apply_filters( 'mp_stacks_brick_class', $post_class_string, $post_id );
	
	//Make sure main class names for mp-bricks are added to the brick class.
	$post_class_string .=  ' mp_brick mp-brick';
									   
	//Actual output
	$html_output .= '<div id="mp-brick-' . $post_id . '" mp-stack-id="' . $stack_id . '" class="' . $post_class_string . '" ' . $extra_brick_attributes . '>';
		
		//HTML Anchor for this brick
		$html_output .= '<a class="brick-anchor" mp_stacks_brick_target="' . sanitize_title( get_the_title($post_id) ) . '"></a>';
		
		//Brick Meta Div
		$html_output .= '<div class="mp-brick-meta">';
			
			//Action hook to run actions in the meta area for a brick
			//do_action( 'mp_stacks_brick_meta_action', $post_id );
			
			//Hook custom output to the meta div for this brick
			$html_output .= apply_filters( 'mp_stacks_brick_meta_output', NULL, $post_id );
				
			//Edit Brick Link
			if ( is_user_logged_in() && current_user_can('edit_theme_options') ) {
				
				$html_output .= '<a class="mp-brick-edit-link" mp-brick-id="' . $post_id . '" mp-stack-id="' . $stack_id . '" href="' . mp_core_add_query_arg( array( 
					'mp-stacks-minimal-admin' => 'true',
					'mp_stack_id' => $stack_id, 
					'containing_page_url' => mp_core_get_current_url()
				), get_edit_post_link( $post_id ) )  . '" >' . __( 'Edit This Brick (Or Double-Click)', 'mp_stacks' ) . '</a>';
				
				//If we want to show the Stack-Related controls for admins (actions like re-order, add brick before/after. Edit Brick always remains.)
				if ( !empty( $args['show_stack_controls_for_admin'] ) && $args['show_stack_controls_for_admin'] ){
					
					//Get Menu Order Info for this Brick						
					$mp_stack_order = get_post_meta( $post_id, 'mp_stack_order_' . $stack_id, true);
					$mp_stack_order = !empty($mp_stack_order) ? $mp_stack_order : 1000;
				
					if ( !empty( $brick_number ) ){
						//Tell the user which stack and brick they are editing
						$stack_info = get_term( $stack_id, 'mp_stacks' );
						
						//If the Stack Id has been found and it has a name we can show
						if ( isset( $stack_info->name ) ){
							$html_output .= '<div class="mp-brick-title-container-top"><div class="mp-brick-title">' . __( 'Brick #', 'mp_stacks' ) . $brick_number . ' in the Stack called "' . $stack_info->name . '".</div></div>';
							
							$html_output .= '<div class="mp-brick-title-container-bottom"><div class="mp-brick-title">' . __( 'Brick #', 'mp_stacks' ) . $brick_number . ' in the Stack called "' . $stack_info->name . '".</div></div>';
						}
					}
		
					//Show buttons to add new bricks above/below
					$html_output .= '<a class="mp-brick-add-before-link" href="' . mp_core_add_query_arg( array( 
						'post_type' => 'mp_brick', 
						'mp-stacks-minimal-admin' => 'true', 
						'mp_stack_id' => $stack_id, 
						'mp_stack_order_new' => $mp_stack_order - 1 ,
						'containing_page_url' => mp_core_get_current_url()
					), admin_url( 'post-new.php' ) ) . '" >' . __( '+ Add Brick Before', 'mp_stacks' ) . '</a>';
					
					$html_output .= '<a class="mp-brick-add-after-link" href="' . mp_core_add_query_arg( array( 
						'post_type' => 'mp_brick',
						'mp-stacks-minimal-admin' => 'true', 
						'mp_stack_id' => $stack_id, 
						'mp_stack_order_new' => $mp_stack_order + 1,
						'containing_page_url' => mp_core_get_current_url()
					), admin_url( 'post-new.php' ) )  . '" >' . __( '+ Add Brick After', 'mp_stacks' ) . '</a>';
	
					//Get number of bricks in this stack
					if ( !empty( $stack_id ) ){
						$number_of_bricks = mp_core_number_postpercat( $stack_id );
					}
					else{
						$number_of_bricks = 0;
					}
					
					//If this brick is being shown as part of a stack and there is more than 1 brick in that stack
					if ( $number_of_bricks > 1 ){
						
						//Show buttons to add new bricks above/below
						$html_output .= '<a class="mp-brick-reorder-bricks" href="' . mp_core_add_query_arg( array( 
							'post_type' => 'mp_brick', 
							'mp-stacks-minimal-admin' => 'true', 
							'mp_stacks' => $stack_id 
						), admin_url( 'edit.php' ) ) . '" >' . __( 'Re-Order Bricks', 'mp_stacks' ) . '</a>';
						
					}
											
				}
			}
		$html_output .= '</div>';
		
		//Brick BG Div
		$html_output .= '<div class="mp-brick-bg" ' . $extra_brick_bg_attributes . '>';
			$html_output .= '<div class="mp-brick-bg-inner">' . apply_filters( 'mp_brick_background_content', '', $post_id ) . '</div>';
		$html_output .= '</div>';
		
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
	
	if ( !empty( $brick_bg_image ) ){
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
	}
	
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
	else{
		
		//Outer style lines
		$css_output .= 'max-width:1000px;';
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
	
	//Min content-type margin Above
	$brick_content_type_margin_above_below = mp_core_get_post_meta($post_id, 'brick_min_above_below', 50);
	
	//Min content-type margin below - if empty, it defaults to above_below value
	$brick_content_type_margin_below = mp_core_get_post_meta($post_id, 'brick_min_below', $brick_content_type_margin_above_below);
	
	//Inner styles
	$css_output .= 'min-height:' . $brick_min_height . 'px; height:' . $brick_min_height . 'px;';
	$css_output .= 'margin-top:' . $brick_content_type_margin_above_below . 'px; margin-bottom:' . $brick_content_type_margin_below . 'px;';
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_inner_css', 'mp_stacks_default_brick_inner_css', 10, 2);

/**
 * Filter Function which returns the css style lines for the First Content Type div
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_first_content_type_css( $css_output, $post_id ){
	
	//1st Content Type max width
	$first_content_type_max_width = get_post_meta($post_id, 'brick_max_width_c1', true);
	
	//If there is a max width value
	if ( !empty( $first_content_type_max_width ) ){
		$css_output .= 'max-width:' . $first_content_type_max_width . 'px; ';
	}
	
	//1st Content Type Float
	$first_content_type_float = mp_core_get_post_meta($post_id, 'brick_float_c1' );
	$first_content_type_float = $first_content_type_float == 'center' || empty( $first_content_type_float) ? 'margin: 0px auto; float: none;' : 'float:' . $first_content_type_float . ';';
	
	//If there is a float value
	if ( !empty( $first_content_type_float ) ){
		$css_output .= $first_content_type_float;
	}
	
	//Content Type Left Right Padding
	$first_content_type_padding = get_post_meta($post_id, 'brick_no_borders', 'padding:0 10px 0 10px;');
	if ( empty( $first_content_type_padding ) ){
		$css_output .= 'padding:0 10px 0 10px;';
	}
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_first_content_type_css_filter', 'mp_stacks_first_content_type_css', 10, 2);

/**
 * Filter Function which returns the css style lines for the First Content Type div
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_first_content_type_mobile_css( $css_output, $post_id ){
	
	$css_output .= 'max-width:inherit; ';
	$css_output .= 'float:inherit; ';
	
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_first_content_type_mobile_css_filter', 'mp_stacks_first_content_type_mobile_css', 10, 2);


/**
 * Filter Function which returns the css style lines for the Second Content Type div
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_second_content_type_mobile_css( $css_output, $post_id ){
	
	$css_output .= 'max-width:inherit; ';
	$css_output .= 'float:inherit; ';
	
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_second_content_type_mobile_css_filter', 'mp_stacks_second_content_type_mobile_css', 10, 2);

/**
 * Filter Function which returns the css style lines for the Second Content Type div
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_second_content_type_css( $css_output, $post_id ){
	
	//2nd Content Type max width
	$second_content_type_max_width = get_post_meta($post_id, 'brick_max_width_c2', true);
	
	//If there is a max width value
	if ( !empty( $second_content_type_max_width ) ){
		$css_output .= 'max-width:' . $second_content_type_max_width . 'px; ';
	}
	
	//2nd Content Type Float
	$second_content_type_float = get_post_meta($post_id, 'brick_float_c2', true);
	$second_content_type_float = $second_content_type_float == 'center' || empty( $second_content_type_float) ? 'margin: 0px auto; float: none;' : 'float:' . $second_content_type_float . ';';
	
	//If there is a float value
	if ( !empty( $second_content_type_float ) ){
		$css_output .= $second_content_type_float;
	}
	
	//Content Type Left Right Padding
	$second_content_type_padding = get_post_meta($post_id, 'brick_no_borders', true);
	if ( empty( $second_content_type_padding ) ){
		$css_output .= 'padding:0 10px 0 10px;';
	}
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_second_content_type_css_filter', 'mp_stacks_second_content_type_css', 10, 2);

/**
 * Filter Function which returns the css style lines for the First Container's CSS
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_first_container_css( $css_output, $post_id ){
	
	//Get the alignment
	$alignment = mp_core_get_post_meta($post_id, 'brick_alignment', 'leftright' );
		
	//If alignment in centered, we don't apply the split percentage
	if ( $alignment != 'leftright' ){
		return $css_output;	
	}
	
	//Split Percentage
	$brick_split_percentage = get_post_meta($post_id, 'brick_split_percentage', true);
	
	//If there split percentage value
	if ( !empty( $brick_split_percentage ) ){
		$css_output .= 'width:' . $brick_split_percentage . '%; ';
	}
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_first_container_css', 'mp_stacks_first_container_css', 10, 2);

/**
 * Filter Function which returns the css style lines for the Second Container's CSS
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_second_container_css( $css_output, $post_id ){
	
	//Get the alignment
	$alignment = get_post_meta($post_id, 'brick_alignment', true);
	
	//If alignment in centered, we don't apply the split percentage
	if ( $alignment != 'leftright' ){
		return $css_output;	
	}
	
	//Split Percentage
	$brick_split_percentage = get_post_meta($post_id, 'brick_split_percentage', true);
	
	//Find what's left for our right brick by subtracting the percentage of the first brick
	$brick_split_percentage = 100 - $brick_split_percentage;
	
	//If there split percentage value
	if ( !empty( $brick_split_percentage ) ){
		$css_output .= 'width:' . $brick_split_percentage . '%; ';
	}
			
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_second_container_css', 'mp_stacks_second_container_css', 10, 2);

/**
 * Filter Function which returns conditional responsive css margins for a brick
 * Parameter: CSS output
 * Parameter: Post ID
 */
function mp_stacks_default_brick_margins( $css_output, $post_id ){
	
	//Check alignment
	$brick_alignment = mp_core_get_post_meta( $post_id, 'brick_alignment', 'leftright' );
	
	//Check if there is a content type in slot #2
	$mp_stacks_second_content_type = mp_core_get_post_meta($post_id, 'brick_second_content_type', 'none');
	
	//Check c1's above margins
	$brick_min_above_c1 = mp_core_get_post_meta( $post_id, 'brick_min_above_c1', 0 );
	
	//If there is no second content-type OR this is a left/right alignmnet, don't apply the 20px bottom margin
	if ( $mp_stacks_second_content_type == 'none' || $brick_alignment == 'leftright' ){
		//Check c1's below margins (default to 0)
		$brick_min_below_c1 = mp_core_get_post_meta( $post_id, 'brick_min_below_c1', 0 );
	}
	else{
		//Check c1's below margins (default to 10)
		$brick_min_below_c1 = mp_core_get_post_meta( $post_id, 'brick_min_below_c1', 20 );	
	}
	
	//Check c2's above margins
	$brick_min_above_c2 = mp_core_get_post_meta( $post_id, 'brick_min_above_c2', 0 );

	//Check c2's below margins
	$brick_min_below_c2 = mp_core_get_post_meta( $post_id, 'brick_min_below_c2', 0 );
		
	//Add custom margin to the 1st content type 
	$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types > .mp-brick-content-types-inner > .mp-brick-content-type-container .mp-brick-first-content-type{';
		$css_output .= 'margin-top:' . $brick_min_above_c1 . 'px;';
		$css_output .= 'margin-bottom:' . $brick_min_below_c1 . 'px; ';
	$css_output .= '}';
	
	//If there is a second content type
	if ( $mp_stacks_second_content_type != 'none' ){
		
		//For Mobile, add custom margin below the 1st content type - if none, default to 20
		$css_output .= '#mp-brick-' . $post_id . '[max-width~=\'600px\'] .mp-brick-content-types > .mp-brick-content-types-inner > .mp-brick-content-type-container .mp-brick-first-content-type{';
			
			//If there is no value to add below the first content-type, default to 20
			$css_output .= !empty( $brick_min_below_c1 ) ? 'margin-bottom:' . $brick_min_below_c1 . 'px;' : 'margin-bottom:20px';	
		
		$css_output .= '}';
	}
	
	//Add custom margin to the 2nd content type 
	$css_output .= '#mp-brick-' . $post_id . ' .mp-brick-content-types > .mp-brick-content-types-inner > .mp-brick-content-type-container .mp-brick-second-content-type{';
		$css_output .= 'margin-top:' . $brick_min_above_c2 . 'px;';
		$css_output .= 'margin-bottom:' . $brick_min_below_c2 . 'px;';	
	$css_output .= '}';
					
	//Return CSS Output
	return $css_output;
	
}
add_filter( 'mp_brick_additional_css', 'mp_stacks_default_brick_margins', 10, 2);

/**
 * Output css for all Stacks on this page in shortcodes into the Document Head
 * Parameter: none
 */
function mp_stacks_header_css(){
		
	//Loop through the query
	if (have_posts()) : 
    	while (have_posts()) : the_post(); 
			
			$content = get_the_content();
			
			//Execute all MP Stack shortcodes
			if ( has_shortcode( $content, 'mp_stack' ) ){
				
				//Find all mp_stack short codes
				preg_match_all("/(\[mp_stack stack=\")(.*?)(\"\])/", get_the_content(), $matches, PREG_SET_ORDER);
				
				//Loop through each stack shortcode
				foreach ($matches as $val) {
					
					//Output CSS for this stack
					mp_stack_css( $val[2], true );
					
					//Enqueue the CSS for this stack
					//wp_enqueue_style( 'mp_stacks_css_' . $val[2], mp_core_add_query_arg( array( 'mp_stacks_css_page' => $val[2] ), get_bloginfo( 'wpurl') ), false, mp_stack_last_modified($val[2]) ); 
					
				}
			}
			
		endwhile; // end of the loop.
	
    endif;
	
	//Reset the main loop
	wp_reset_postdata();
		
}
add_action( 'wp_head', 'mp_stacks_header_css', -1); 

/**
 * Output css and js for all Stacks that haven't been output (for whatever reason). This also covers the css and js for Stack widgets.
 * Parameter: none
 */
function mp_stacks_extra_stacks_css_and_js(){
	
	global $mp_stacks_on_page;
	
	if ( isset( $mp_stacks_on_page['css_required'] ) ){
		
		//Output a container for this CSS so our JS can move the contents into the head
		echo '<div id="mp-stacks-extra-stacks-css">';
		
			//Create an array using the stacks that are in the css_required list but not in the css_complete list
			foreach( $mp_stacks_on_page['css_required'] as $stack_id => $css_required ){
				
				//Check if this Stack id is in the css_complete list
				if ( !in_array( $stack_id, $mp_stacks_on_page['css_complete'] ) ){
					
					//Output CSS for this stack
					mp_stack_css( $stack_id, true ); 
					
				}
				
			}
			
			//Output any style that were enqueued in the footer after priority 10.
			wp_print_styles();
			
			//Output any inline CSS that was enqueued by the mp_stack_css calls.
			mp_stacks_inline_css();
		
		echo '</div>';
		
		echo '<div id="mp-stacks-extra-stacks-js">';
			//Output any scripts that were enqueued in the footer after priority 10.
			wp_print_footer_scripts();
			
			//Output any inline JS that was enqueued by the mp_stack function.
			mp_stacks_inline_js();
		echo '</div>';
		
	}
	
}
add_action( 'wp_footer', 'mp_stacks_extra_stacks_css_and_js', 99 );

/**
 * Output all inline js for all Stacks late in the footer. We use the global variable $mp_stacks_inline_js inside content-type filters to generate this output string.
 * Parameter: none
 */
function mp_stacks_inline_js(){
	
	$mp_stacks_footer_inline_js  = mp_stacks_get_inline_js();
	
	if ( !empty( $mp_stacks_footer_inline_js ) ){
		echo '<!-- MP Stacks Inline Js Output -->
';
		
		//Loop through each inline js script added by MP Stacks Content-Types
		foreach( $mp_stacks_footer_inline_js as $script_id => $script_output ){
			echo $script_output;
		}
	}
		
}
add_action( 'wp_footer', 'mp_stacks_inline_js', 99 );

/**
 * Get a string of all inline js for all Stacks. We use the global array variable $mp_stacks_inline_js inside content-type filters to generate this output string.
 * Parameter: none
 */
function mp_stacks_get_inline_js(){
	
	global $mp_stacks_footer_inline_js;
	
	if ( is_array( $mp_stacks_footer_inline_js ) ){
			
		//Loop through each inline js script added by MP Stacks Content-Types
		foreach( $mp_stacks_footer_inline_js as $script_id => $script_output ){
			//Add the <script before and after the outputs with proper id tagging.
			$inline_js_script_array[$script_id . '-inline-js'] = '<div id="' . $script_id . '-inline-js"><script type="text/javascript">' . $script_output . '</script></div>';
			
			//Unset this value from the global var
			unset( $mp_stacks_footer_inline_js[$script_id] );
			
		}
		
		return !empty( $inline_js_script_array ) ? $inline_js_script_array : array();
	
	}
	else{
		return array();	
	}
		
}

/**
 * Output all inline css for all Stacks late in the wp_head. We use the global variable $mp_stacks_inline_css inside content-type filters to generate this output string.
 * Parameter: none
 */
function mp_stacks_inline_css(){
	
	$mp_stacks_footer_inline_css  = mp_stacks_get_inline_css();
	
	if ( !empty( $mp_stacks_footer_inline_css ) ){
		echo '<!-- MP Stacks Inline CSS Output -->
';
		
		//Loop through each inline js script added by MP Stacks Content-Types
		foreach( $mp_stacks_footer_inline_css as $style_id => $style_output ){
			echo $style_output;
		}
	}
		
}
add_action( 'wp_head', 'mp_stacks_inline_css', 99 );

/**
 * Get a string of all inline css for all Stacks. We use the global array variable $mp_stacks_inline_css inside content-type filters to generate this output string.
 * Parameter: none
 */
function mp_stacks_get_inline_css(){
	
	global $mp_stacks_footer_inline_css;
	
	if ( is_array( $mp_stacks_footer_inline_css ) ){
			
		//Loop through each inline js script added by MP Stacks Content-Types
		foreach( $mp_stacks_footer_inline_css as $style_id => $script_output ){
			//Add the <script before and after the outputs with proper id tagging.
			$inline_css_script_array[ sanitize_title( $style_id . '-inline-css' ) ] = '<style id="' . sanitize_title( $style_id . '-inline-css' ) . '" mp_stacks_inline_style="' . sanitize_title( $style_id . '-inline-css' ) . '">' . $script_output . '</style>';
			
			//Unset this value from the global var
			unset( $mp_stacks_footer_inline_css[$style_id] );
			
		}
		
		return !empty( $inline_css_script_array ) ? $inline_css_script_array : array();
	
	}
	else{
		return array();	
	}
		
}