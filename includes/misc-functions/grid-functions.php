<?php
/**
 * This file contains various functions which can be used by addons to create grid layouts as content types
 *
 * @since 1.0.0
 *
 * @package    MP Core
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * If we are using pagination in a grid, make that pagination pretty by setting up some rewrite rules
 *
 * @access   public
 * @since    1.0.0
 * @param    $rules array - The array of rewrite rules for WordPress
 * @return   $rules array - The array of rewrite rules for WordPress
 */
function mp_stacks_grid_pagination_rewrites($rules){
			
		$new_rules = array(
			
			/**
			 * Here we make 2 URL variables which are arrays.
			 * The 1st contains all the brick slugs which have pagination. array( 'brick-1' )
			 * The 2nd contains all the page numbers of the above bricks. array( '2' )
			 * To break them into arrays use explode( '|||', array )
			 */
			 
			//page-slug/brick/brick-slug/page/2
			"([^/]*)/brick/([^/]*)/page/([^/]*)/?$" => 'index.php?mp_stacks_page_slug=$matches[1]&mp_brick_pagination_slugs=$matches[2]&mp_brick_pagination_page_numbers=$matches[3]',
			
			/**
			 * Here we make 2 URL variables which are arrays.
			 * The 1st contains all the brick slugs which have pagination. array( 'brick-1' )
			 * The 2nd contains all the page numbers of the above bricks. array( '2' )
			 * To break them into arrays use explode( '|||', array )
			 */
			 
			//post-type/page-slug/brick/brick-slug/page/2
			//"([^/]*)/([^/]*)/brick/([^/]*)/page/([^/]*)/?$" => 'index.php?mp_brick_pagination_slugs=$matches[3]&mp_brick_pagination_page_numbers=$matches[4]',
			
			/**
			 * Here we make 2 URL variables which are arrays:
			 * The 1st contains all the brick slugs which have pagination. array( 'brick-1', 'brick-2' )
			 * The 2nd contains all the page numbers of the above bricks. array( '2', '4' )
			 * To break them into arrays use explode( '|||', array )
			 */
			 
			//post-type/page-slug/brick/brick-slug/page/2/brick/brick-slug-2/page/4
			//"([^/]*)/([^/]*)/brick/([^/]*)/page/([^/]*)/brick/([^/]*)/page/([^/]*)/?$" => 'index.php?mp_brick_pagination_slugs=$matches[3]|||$matches[5]&mp_brick_pagination_page_numbers=$matches[4]|||$matches[6]',

		);
		
		$new_rules = array_merge($new_rules, $rules);
		
		return $new_rules;
}
add_filter('rewrite_rules_array', 'mp_stacks_grid_pagination_rewrites');

/**
 * If we are using pagination in a grid, we need to tell wordpress which page to load
 *
 * @access   public
 * @since    1.0.0
 * @param    $query_vars array - The array WordPress uses to setup the Query
 * @return   $query_vars array - The array WordPress uses to setup the Query
 */
function mp_stacks_show_the_right_post( $query_vars ){
	
	//If a page slug has been set with brick in the URL
	if ( isset($query_vars['mp_stacks_page_slug']) ){
		
		//Get the id of the post which has this slug
		$page = get_page_by_path( $query_vars['mp_stacks_page_slug'] );
		
		//Tell WordPress we are on the post/page with that id
		$query_vars['page_id'] = $page->ID;
	}
	
	return $query_vars;
}
add_filter( 'request', 'mp_stacks_show_the_right_post' );	

/**
 * Get ALL the CSS for text in a Grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $placement_string String - A string chosen by the user to specify the position of the title
 * @param    $args Array - An associative array with additional options like image width and height, etc
 * @return   $css_output String - A string containing the CSS for the titles in this grid
 */
function mp_stacks_grid_text_css( $post_id, $meta_prefix, $css_class, $css_defaults = array() ){
	
	//Set up defaults for CSS if none were provided to the function		
	$css_defaults_if_none_provided = array(
		'color' => NULL,
		'size' => 20,
		'lineheight' => 20,
		'bold' => false,
		'italic' => false,
		'background_padding' => 5,
		'background_color' => '#fff',
		'background_opacity' => 100,
		'placement_string' => 'below_image_left',
	);
	$css_defaults = wp_parse_args( $css_defaults, $css_defaults_if_none_provided );
				
	//Text placement
	$placement = mp_core_get_post_meta($post_id, $meta_prefix . '_placement', $css_defaults['placement_string']);
	
	//Text Color and size
	$color = mp_core_get_post_meta($post_id, $meta_prefix . '_color', $css_defaults['color']);
	$size = mp_core_get_post_meta($post_id, $meta_prefix . '_size', $css_defaults['size']);
	$lineheight = mp_core_get_post_meta($post_id, $meta_prefix . '_lineheight', $css_defaults['lineheight']);
	$bold = mp_core_get_post_meta($post_id, $meta_prefix . '_bold', $css_defaults['bold']);
	$italic = mp_core_get_post_meta($post_id, $meta_prefix . '_italic', $css_defaults['italic']);
	
	//Show Text Backgrounds?
	$background_show = mp_core_get_post_meta($post_id, $meta_prefix . '_background_show');
	
	//If we should show the text backgrounds
	if ( $background_show ){
		
		//Text background spacing (padding)
		$background_padding = mp_core_get_post_meta($post_id, $meta_prefix . '_background_padding', $css_defaults['background_padding']);	
		
			//Calculate Minimum Line Height with Padding
			$min_line_height_with_padding = ( $background_padding * 3 ) + $size;
			//If the line height with padding is greater than the lineheight, we need to make the lineheight match or the layout gets thrown off
			$lineheight = $min_line_height_with_padding  > $lineheight ? $min_line_height_with_padding : $lineheight;
		
		//Text background color 
		$background_color = mp_core_get_post_meta($post_id, $meta_prefix . '_background_color', $css_defaults['background_color'] );	
		
		//Text background opacity 
		$background_opacity = mp_core_get_post_meta($post_id, $meta_prefix . '_background_opacity', $css_defaults['background_opacity']);	
	}
	else{
		//Text background spacing (padding)
		$background_padding = '0';	
		//Text background color - defaults to white
		$background_color = '#FFFFFF';	
		//Text background opacity 
		$background_opacity = '0';	
	}
	
	$css_output = '#mp-brick-' . $post_id . ' .' . $css_class . '-holder, 
		#mp-brick-' . $post_id . ' .' . $css_class . '-holder a{
			' . mp_stacks_grid_get_text_placement_css( $placement, array( 
					'line_height' => ($size),
				) ) . '; ' .
			mp_core_css_line( 'color', $color ) . 
			mp_core_css_line( 'font-weight', $bold ? '700' : 'normal' ) . 
			mp_core_css_line( 'font-style', $italic ? 'italic;' : 'normal' ) . 
			mp_core_css_line( 'font-size', $size, 'px' ) .
			mp_core_css_line( 'line-height', $lineheight, 'px' ) . 
		'}' . 
		mp_stacks_grid_highlight_text_css( array( 
				'brick_id' => $post_id,
				'class_name' => $css_class,
				'highlight_padding' => $background_padding, 
				'highlight_color' => $background_color, 
				'highlight_opacity' => $background_opacity
		) );
		
	return $css_output;
				
}

/**
 * Get the CSS lines for a text div based on the placement string the user has chosen
 *
 * @access   public
 * @since    1.0.0
 * @param    $placement_string String - A string chosen by the user to specify the position of the title
 * @param    $args Array - An associative array with additional options like image width and height, etc
 * @return   $css_output String - A string containing the CSS for the titles in this grid
 */
function mp_stacks_grid_get_text_placement_css( $placement_string, $args ){
	
	$css_output = NULL;
	
	$text_line_height = $args['line_height'] . 'px';
	
	if( $placement_string == 'below_image_left' ){
		
		$css_output = 'text-align:left; padding-top:' . $text_line_height . '';
	}
	else if(  $placement_string == 'below_image_right' ){
		$css_output = 'text-align:right; padding-top:' . $text_line_height . '';
	}
	else if(  $placement_string == 'below_image_centered' ){
		$css_output = 'text-align:center; padding-top:' . $text_line_height . '';
	}
	else if(  $placement_string == 'over_image_top_left' ){
		$css_output = 'text-align:left; ';
	}
	else if(  $placement_string == 'over_image_top_right' ){
		$css_output = 'text-align:right; ';
	}
	else if(  $placement_string == 'over_image_top_centered' ){
		$css_output = 'text-align:center; ';
	}
	else if(  $placement_string == 'over_image_middle_left' ){
		$css_output = 'text-align:left; ';
	}
	else if(  $placement_string == 'over_image_middle_right' ){
		$css_output = 'text-align:right; ';
	}
	else if(  $placement_string == 'over_image_middle_centered' ){
		$css_output = 'text-align:center; ';
	}
	else if(  $placement_string == 'over_image_bottom_left' ){
		$css_output = 'text-align:left; ';
	}
	else if(  $placement_string == 'over_image_bottom_right' ){
		$css_output = 'text-align:right; ';
	}
	else if(  $placement_string == 'over_image_bottom_centered' ){
		$css_output = 'text-align:center; ';
	}
	
	return $css_output;
		
}

/**
 * Return the CSS needed for a highlighted background color for text
 *
 * Note that for this css to work as expected, the text element must be wrapped in a parent which has its padding set to: 
 * padding: 0px ' . $highlight_padding . 'px;
 *
 * @access   public
 * @link     http://css-tricks.com/multi-line-padded-text/
 * @since    1.0.0
 * @param	 $args                Array - An associative array containing information needed to customize the highlight output with these values:
 * 		@param    $brick_id   		   Int - The id of the brick containing these values
 *		@param 	  $class_name		   String - The name of the class we want to use for this highlited text. Match it to the class passed to the mp_stacks_highlight_text_html function.
 *		@param    $highlight_padding   Int - A number value to use for the amount of padding for the highlight
 * 		@param    $highlight_color     String - A color hex code such as #FFFFFF
 * 		@param    $highlight_opacity   Int - A number value from 1 to 100 representing the percentage value for opacity
 * @return   $css_output          String - A string holding the css needed for highlighted text
 */
function mp_stacks_grid_highlight_text_css( $args ){
	
	//Default args
	$default_args = array( 
		'brick_id' => NULL,
		'class_name' => NULL,
		'highlight_padding' => NULL, 
		'highlight_color' => NULL, 
		'highlight_opacity' => NULL
	);
	
	wp_parse_args( $default_args, $args );
	
	extract( $args, EXTR_SKIP );
	
	//Convert hex color to rgb color array
	$highlight_color = mp_core_hex2rgb($highlight_color);
	
	$css_output = '#mp-brick-' . $brick_id . ' .' . $class_name . '-holder .' . $class_name . '{
		padding: 0px ' . $highlight_padding . 'px;
	}
	#mp-brick-' . $brick_id . ' .' . $class_name . '-holder .' . $class_name . ' .' . $class_name . '-highlight{
		padding:' . $highlight_padding . 'px;
			background-color: rgba(' . $highlight_color[0] . ', ' . $highlight_color[1] . ', ' . $highlight_color[2] . ', ' . $highlight_opacity / 100 . ');
			box-shadow: 
    			 	' . $highlight_padding . 'px 0 0 rgba(' . $highlight_color[0] . ', ' . $highlight_color[1] . ', ' . $highlight_color[2] . ', ' . $highlight_opacity / 100 . '), 
      		 		-' . $highlight_padding . 'px 0 0 rgba(' . $highlight_color[0] . ', ' . $highlight_color[1] . ', ' . $highlight_color[2] . ', ' . $highlight_opacity / 100 . ');
	}';
	
	return $css_output;
						
}

/**
 * Return the HTML needed for a highlighted background color for text
 *
 * @access   public
 * @link     http://css-tricks.com/multi-line-padded-text/
 * @since    1.0.0
 * @param	 $args                Array - An associative array containing information needed to customize the highlight output with these values:
 * 		@param    $brick_id   		   Int - The id of the brick containing these values
 *		@param 	  $class_name		   String - The name of the class we want to use for this highlited text. Match it to the class passed to the mp_stacks_highlight_text_html function.
 *		@param    $highlight_padding   Int - A number value to use for the amount of padding for the highlight
 * 		@param    $highlight_color     String - A color hex code such as #FFFFFF
 * 		@param    $highlight_opacity   Int - A number value from 1 to 100 representing the percentage value for opacity
 * @return   $css_output          String - A string holding the css needed for highlighted text
 */
function mp_stacks_grid_highlight_text_html( $args ){
	
	//Default args
	$default_args = array( 
		'class_name' => NULL,
		'output_string' => NULL,
		'position_absolute' => false
	);
	
	$args = wp_parse_args( $args, $default_args);
	
	extract( $args, EXTR_SKIP );
	
	if ( $position_absolute ){
		$container_style = 'style="position:absolute;"';
	}
	else{
		$container_style = NULL;	
	}
	
	//Add clear div to bump this below and clear floats
	$html_output = '<div class="mp-stacks-clearedfix"></div>';
	
	$html_output .= '<div class="' . $class_name . '-holder" ' . $container_style . '>';
		
		$html_output .= '<div class="' . $class_name . '">';
		
			$html_output .= '<span class="' . $class_name . '-highlight">' . $output_string . '</span>';
		
		$html_output .= '</div>';
			
	$html_output .= '</div>';
	
	return $html_output;
						
}

/**
 * Return the array of text pacement options a user can choose from
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function mp_stacks_get_text_position_options(){
	
	return array( 
		'below_image_left' => __( 'Below Image, Left', 'mp_stacks' ),
		'below_image_right' => __( 'Below Image, Right', 'mp_stacks' ),
		'below_image_centered' => __( 'Below Image, Centered', 'mp_stacks' ),
		
		'over_image_top_left' => __( 'Over Image, Top-Left', 'mp_stacks' ),
		'over_image_top_right' => __( 'Over Image, Top-Right', 'mp_stacks' ),
		'over_image_top_centered' => __( 'Over Image, Top-Centered', 'mp_stacks' ),
		
		'over_image_middle_left' => __( 'Over Image, Middle-Left', 'mp_stacks' ),
		'over_image_middle_right' => __( 'Over Image, Middle-Right', 'mp_stacks' ),
		'over_image_middle_centered' => __( 'Over Image, Middle-Centered', 'mp_stacks' ),
		
		'over_image_bottom_left' => __( 'Over Image, Bottom-Left', 'mp_stacks' ),
		'over_image_bottom_right' => __( 'Over Image, Bottom-Right', 'mp_stacks' ),
		'over_image_bottom_centered' => __( 'Over Image, Bottom-Centered', 'mp_stacks' ),
	);
	
}

/**
 * This class is used to create the meta options and CSS output for the "Load More" for a grid
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $post_id Int - The ID of the Brick
 * @param    $load_more_args array - Values needed to output the load more/pagination
 * @return   String - The HTML needed to make the load more button/pagination.
 */
class MP_Stacks_Grid_Load_More{
	
	/**
	 * Constructor
	 *
	 * @access   public
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/MP_Stacks_Grid_Load_More/
	 * @author   Philip Johnston
	 * @see      wp_parse_args()
	 * @param    array $meta_prefix (required) See link for description.
	 * @return   void
	 */	
	public function __construct( $meta_prefix ){
		
		$this->_meta_prefix = $meta_prefix;
		
		add_action( 'mp_stacks_' . $meta_prefix . '_css', array( $this, 'mp_stacks_grid_load_more_css_output' ), 10, 2 );
		add_filter( 'mp_stacks_' . $meta_prefix . '_items_array', array( $this, 'mp_stacks_grid_load_more_meta' ) );
		add_filter( 'mp_stacks_' . $meta_prefix . '_load_more_html_output', array( $this, 'mp_stacks_grid_load_more_html_output' ), 10, 3 );
	}
	
	 /**
	 * Add the meta options for the "Load More" to the DownloadGrid Metabox
	 *
	 * @access   public
	 * @since    1.0.0
	 * @param    Void
	 * @param    $items_array Array - The existing Meta Options in this Array
	 * @return   Array - All of the placement optons needed for Excerpt
	 */
	function mp_stacks_grid_load_more_meta( $items_array ){
		//Ajax Load More
		$new_fields = array(
			'dg_load_more_showhider' => array(
				'field_id'			=> $this->_meta_prefix . '_load_more_settings',
				'field_title' 	=> __( '"Load More" Settings', 'mp_stacks'),
				'field_description' 	=> __( '', 'mp_stacks' ),
				'field_type' 	=> 'showhider',
				'field_value' => '',
			),
			'dg_load_more_behaviour' => array(
				'field_id'			=> $this->_meta_prefix . '_load_more_behaviour',
				'field_title' 	=> __( '"Load More" Behaviour', 'mp_stacks'),
				'field_description' 	=> __( 'How should more items be loaded?', 'mp_stacks' ),
				'field_type' 	=> 'select',
				'field_value' => 'ajax_load_more',
				'field_select_values' => apply_filters( 'mp_stacks_grid_' . $this->_meta_prefix . '_load_more_values', array(
					'ajax_load_more' => __( '"Load More" button.', 'mp_stacks' ),
					'infinite_scroll' => __( 'Infinite Scroll with "Load More" button.', 'mp_stacks' ),
					'pagination' => __( 'Page Number Buttons. Example: "Page 1, 2, 3"', 'mp_stacks' ),
					'none' => __( 'None. Do not allow the user to load any more items.', 'mp_stacks' ),
				) ),
				'field_showhider' => $this->_meta_prefix . '_load_more_settings',
			),
			'dg_load_more_float' => array(
				'field_id'			=> $this->_meta_prefix . '_load_more_float',
				'field_title' 	=> __( '"Load More" Position', 'mp_stacks'),
				'field_description' 	=> __( 'Where should the "Load More" action be positioned? Default: "Center"', 'mp_stacks' ),
				'field_type' 	=> 'select',
				'field_value' => 'center',
				'field_select_values' => array(
					'left' => __( 'Left', 'mp_stacks' ),
					'center' => __( 'Center', 'mp_stacks' ),
					'right' => __( 'Right', 'mp_stacks' ),
				),
				'field_showhider' => $this->_meta_prefix . '_load_more_settings',
			),
			'dg_load_more_color' => array(
				'field_id'			=> $this->_meta_prefix . '_load_more_button_color',
				'field_title' 	=> __( '"Load More" Button Background Color', 'mp_stacks'),
				'field_description' 	=> __( 'What color should the "Load More" button be? (Leave blank for theme default)', 'mp_stacks' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => $this->_meta_prefix . '_load_more_settings',
			),
			'dg_load_more_text_color' => array(
				'field_id'			=> $this->_meta_prefix . '_load_more_button_text_color',
				'field_title' 	=> __( '"Load More" Button Text Color', 'mp_stacks'),
				'field_description' 	=> __( 'What color should the "Load More" button\'s text be? (Leave blank for theme defaults)', 'mp_stacks' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => $this->_meta_prefix . '_load_more_settings',
			),
			'dg_load_more_hover_color' => array(
				'field_id'			=> $this->_meta_prefix . '_load_more_button_color_mouse_over',
				'field_title' 	=> __( '"Load More" Button Background Color when Mouse Over.', 'mp_stacks'),
				'field_description' 	=> __( 'What color should the "Load More" button be when the Mouse is over it? (Leave blank for theme defaults)', 'mp_stacks' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => $this->_meta_prefix . '_load_more_settings',
			),
			'dg_load_more_hover_text_color' => array(
				'field_id'			=> $this->_meta_prefix . '_load_more_button_text_color_mouse_over',
				'field_title' 	=> __( '"Load More" Button Text Color when Mouse Over.', 'mp_stacks'),
				'field_description' 	=> __( 'What color should the "Load More" button\'s text be when the Mouse is over it? (Leave blank for theme defaults)', 'mp_stacks' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => $this->_meta_prefix . '_load_more_settings',
			),
			'dg_load_more_text' => array(
				'field_id'			=> $this->_meta_prefix . '_load_more_button_text',
				'field_title' 	=> __( '"Load More" Text', 'mp_stacks'),
				'field_description' 	=> __( 'What should the "Load More" button say? Default: "Load More"', 'mp_stacks' ),
				'field_type' 	=> 'textbox',
				'field_value' => __( 'Load More', 'mp_stacks' ),
				'field_showhider' => $this->_meta_prefix . '_load_more_settings',
			)
		);
		
		return mp_core_insert_meta_fields( $items_array, $new_fields, $this->_meta_prefix . '_meta_hook_anchor_3' );
	}
	
	/**
	 * Create the CSS for the Load More Button and hook it to the grid css
	 *
	 * @access   public
	 * @since    1.0.0
	 * @param    Void
	 * @param    $post_id Int - The ID of the Brick
	 * @return   Array - All of the placement optons needed for Excerpt
	 */
	function mp_stacks_grid_load_more_css_output( $css_output, $post_id ){
		
		//Load More Meta Info
		$load_more_float = mp_core_get_post_meta($post_id, $this->_meta_prefix . '_load_more_float', 'center');
		$load_more_button_color = mp_core_get_post_meta($post_id, $this->_meta_prefix . '_load_more_button_color', 'NULL');
		$load_more_button_text_color = mp_core_get_post_meta($post_id, $this->_meta_prefix . '_load_more_button_text_color', 'NULL');
		$load_more_button_color_mouse_over = mp_core_get_post_meta($post_id, $this->_meta_prefix . '_load_more_button_color_mouse_over', 'NULL');
		$load_more_button_text_color_mouse_over = mp_core_get_post_meta($post_id, $this->_meta_prefix . '_load_more_button_text_color_mouse_over', 'NULL');
		
		$css_output .= '
		#mp-brick-' . $post_id . ' .mp-stacks-grid-load-more-container{' . 
			mp_core_css_line( 'text-align', $load_more_float ) . 	
		'}
		#mp-brick-' . $post_id . ' .mp-stacks-grid-load-more-button{' . 
			mp_core_css_line( 'color', $load_more_button_text_color ) . 
			mp_core_css_line( 'background-color', $load_more_button_color ) . 
		'}
		#mp-brick-' . $post_id . ' .mp-stacks-grid-load-more-button:hover{' . 
			mp_core_css_line( 'color', $load_more_button_text_color_mouse_over ) . 
			mp_core_css_line( 'background-color', $load_more_button_color_mouse_over ) . 
		'}';
		
		return $css_output;
		
	}
	
	/**
	 * The "Load More" button, "Infinite Scroll", or "Pagination" depending on what the user has decided.
	 *
	 * @access   public
	 * @since    1.0.0
	 * @param    Void
	 * @param    $post_id Int - The ID of the Brick
	 * @param    $load_more_args array - Values needed to output the load more/pagination
	 * @return   String - The HTML needed to make the load more button/pagination.
	 */
	function mp_stacks_grid_load_more_html_output( $load_more_html_output, $post_id, $load_more_args ){
		
		//Assemble args for the load more output
		$default_load_more_args = array(
			 'meta_prefix' => NULL,
			 'total_posts' => 0, 
			 'posts_per_page' => 0, 
			 'paged' => 0, 
			 'post_counter' => 0, 
			 'post_offset' => 0
		);
		
		$args = wp_parse_args( $load_more_args, $default_load_more_args );
	
		//Load More Behaviour?
		$load_more_behaviour = mp_core_get_post_meta($post_id, $args['meta_prefix']  . '_load_more_behaviour', 'ajax_load_more' );
		
		//Load More Button Text
		$load_more_button_text = mp_core_get_post_meta($post_id, $args['meta_prefix']  . '_load_more_button_text', __( 'Load More', 'mp_stacks' ) );
			
		//Check spacing (padding) around posts to see if we need to add some specially to our load more button(s)
		$post_spacing = mp_core_get_post_meta($post_id, $args['meta_prefix'] . '_post_spacing', '20');
		$load_more_spacing = $post_spacing == 0 ? 'padding:20px;' : 'padding-bottom:' . $post_spacing . 'px';
			
		//If we are using the ajax_load_more_style
		if ( $load_more_behaviour == 'ajax_load_more' ){
			
			//If there are no more posts in this taxonomy
			if ( $args['total_posts'] <= $args['post_offset'] ){
				
				//Return without a button
				return NULL;		
			}
			
			//Button
			return '<div class="mp-stacks-grid-load-more-container" style="' . $load_more_spacing . '"><a mp_stacks_grid_post_id="' . $post_id . '" mp_stacks_grid_brick_offset="' . $args['post_offset'] . '" mp_stacks_grid_post_counter="' . $args['post_counter'] . '" mp_stacks_grid_loading_text="' . __('Loading...', 'mp_stacks' ) . '" mp_stacks_grid_ajax_prefix=' . $args['meta_prefix'] . ' class="button mp-stacks-grid-load-more-button">' . $load_more_button_text . '</a></div>';	
				
		
		}
		//If we are using infinite scroll
		else if ( $load_more_behaviour == 'infinite_scroll' ){
			
			//If there are no more posts in this taxonomy
			if ( $args['total_posts'] <= $args['post_offset'] ){
				
				//Return without a button
				return NULL;		
			}
		
			//JS for the Load More Button
			$load_more_output = '<div class="mp-stacks-grid-load-more-container" style="' . $load_more_spacing . '"><a mp_stacks_grid_post_id="' . $post_id . '" mp_stacks_grid_brick_offset="' . $args['post_offset'] . '" mp_stacks_grid_post_counter="' . $args['post_counter'] . '" mp_stacks_grid_loading_text="' . __('Loading...', 'mp_stacks' ) . '" mp_stacks_grid_ajax_prefix=' . $args['meta_prefix'] . ' class="button mp-stacks-grid-load-more-button">' . $load_more_button_text . '</a></div>';	
				
			//If we are not doing ajax
			if ( !defined('DOING_AJAX') ){
				
				//Add the JS Which activates Infinite Scroll using Waypoints
				$load_more_output .= '<script type="text/javascript">
				jQuery(document).ready(function($){ 
					
					$(\'#mp-brick-' . $post_id . ' .mp-stacks-grid-after\').waypoint(function( direction ) {
		
						if (direction === \'up\') return;
				
						$(this).parent().parent().find(\'.mp-stacks-grid-load-more-button\').trigger(\'click\' );
						
					}, {
						offset: \'100%\'
					});
					
				});
				</script>';
				
			}
				
			return $load_more_output;
			
		}
		//If we are using pagination
		else if ( $load_more_behaviour == 'pagination' ){
			
			//Figure out how many pages there based on the count and posts per page
			$pages = ceil( $args['total_posts'] / $args['posts_per_page'] );
			
			$page_counter = 1;
			
			$load_more_output = '<div class="mp-stacks-grid-load-more-container" style="' . $load_more_spacing . '"><nav id="posts-navigation" class="row pagination mp-core-pagination" style="float:none;">
				<ul class="page-numbers">';
			
			//Set the base URL based on the current url by removing any brick pagination	
			$base_url = explode( 'brick/', mp_core_get_current_url() );
			$base_url = $base_url[0];
			
			//Create the page number links by looping through each posts per page
			while( $page_counter <= $pages ){
				
				//If we have more than 4 pages (and it is't just 5 where this isn't useful), use show the first 3 page numbers, then show a ..., then show the last page number
				if ( $pages > 4 && $pages != 5){
					//If we aren't on the last page or the first page
					if ( $page_counter != $pages &&  $page_counter != 1 ){
						//If we are more than 3 pages before the current page and we are not on the first or last page
						if ( $page_counter == ($args['paged'] - 3) ){
							//Just show 3 dots
							$load_more_output .= '<li><span class="page-numbers dots">...</span></li>';
							$page_counter = $page_counter + 1;
							continue;
						}
						//If we are 3 pages after the current page AND we aren't on the first or last page
						if ( $page_counter == ($args['paged'] + 3) ){
							//Just show 3 dots
							$load_more_output .= '<li><span class="page-numbers dots">...</span></li>';
							$page_counter = $page_counter + 1;
							continue;
						}
						//If we are higher than page 4 and not on the first or last page, skip to the next loop
						else if( $page_counter > ($args['paged'] + 3) ){
							$page_counter = $page_counter + 1;
							continue;	
						}
					}
				}
				
				if ( $page_counter == $args['paged'] ){
					$load_more_output .= '<li><span class="page-numbers current">' . $page_counter . '</span></li>';
				}
				else{
					$load_more_output .= '<li><a class="page-numbers" href="' . $base_url . 'brick/' . $args['brick_slug'] . '/page/' . $page_counter . '/">' . $page_counter . '</a></li>';
				}
				$page_counter = $page_counter + 1;
				
			}
			
			$load_more_output .= '</ul>
			</nav></div>';
			
			return $load_more_output;
			
		}
		//If we are not using load more at all
		else if ( $load_more_behaviour == 'none' ){
			return NULL;
		}
		
	}
}