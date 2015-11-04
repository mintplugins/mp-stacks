<?php
/**
 * This file contains various functions which can be used by grids to handle isotope filtering
 *
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Add the isotope filtering navigation metaoptions to the metabox array for the Grid Add-On in question.
 *
 * @access   public
 * @since    1.0.0
 * @param    $css_output          String - The incoming CSS output coming from other things using this filter
 * @param    $meta_prefix  		  String - The prefix to put before each meta key
 * @return   $css_output          String - The CSS needed to properly style the isotope navigation buttons
 */
function mp_stacks_grid_isotope_meta( $meta_prefix ){
	
	$new_fields = array(
		$meta_prefix . '_isotope_showhider' => array(
			'field_id'			=> $meta_prefix . '_isotope_settings',
			'field_title' 	=> __( 'Isotope & Masonry Settings', 'mp_stacks'),
			'field_description' 	=> __( '', 'mp_stacks' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
			$meta_prefix . '_masonry' => array(
				'field_id'			=> $meta_prefix . '_masonry',
				'field_title' 	=> __( 'Use Masonry?', 'mp_stacks'),
				'field_description' 	=> __( 'Would you like to use Masonry for the layout? Masonry is similar to how Pinterest posts are laid out.', 'mp_stacks' ),
				'field_type' 	=> 'checkbox',
				'field_value' => $meta_prefix . '_masonry',
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
		
			$meta_prefix . '_isotope' => array(
				'field_id'			=> $meta_prefix . '_isotope',
				'field_title' 	=> __( 'Enable Isotope Filtering?', 'mp_stacks'),
				'field_description' 	=> __( 'Would you like to enable Isotope for filtering this grid?', 'mp_stacks' ),
				'field_type' 	=> 'checkbox',
				'field_value' => '',
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
		);
		//Check if we should add the behavior options based on the addon plugin (on by default - disable in the plugin by returning false to this filter.)
		$include_behavior_options = apply_filters( $meta_prefix . '_isotope_include_behavior_options', true );
		
		//If there are orderby options available to choose from (assigned by addon plugins)
		if ( $include_behavior_options ){	
			
			$new_fields[$meta_prefix . '_isotope_filtering_behavior'] = array(
			
				'field_id'			=> $meta_prefix . '_isotope_filtering_behavior',
				'field_title' 	=> __( 'Filtering Behavior?', 'mp_stacks'),
				'field_description' 	=> __( 'How should the filtering behave?', 'mp_stacks' ),
				'field_type' 	=> 'select',
				'field_value' => 'default_isotope',
				'field_select_values' => array( 
					'default_isotope' => __( 'Default Isotope: Sort ONLY items on page. (Good if you have a small number of items)', 'mp_stacks' ),
					'reload_from_scratch' => __( 'Reload all items when clicked. (Better if you have a lot of items).', 'mp_stacks' ),
				),
				'field_conditional_id' => $meta_prefix . '_isotope',
				'field_conditional_values' => array( $meta_prefix . '_isotope' ), 
				'field_showhider' => $meta_prefix . '_isotope_settings',
			);
		}
		
		$new_fields[$meta_prefix . '_isotope_filter_groups'] = array(
				'field_id'			=> $meta_prefix . '_isotope_filter_groups',
				'field_title' 	=> __( 'Filtering Groups:', 'mp_stacks'),
				'field_description' 	=> __( 'Which filter groups should be included?', 'mp_stacks' ),
				'field_type' 	=> 'multiple_checkboxes',
				'field_conditional_id' => $meta_prefix . '_isotope',
				'field_conditional_values' => array( $meta_prefix . '_isotope' ), 
				'field_value' => '',
				'field_select_values' => apply_filters( $meta_prefix . '_isotope_filter_groups', array() ),
				'field_showhider' => $meta_prefix . '_isotope_settings',
		);
		
		//Check if we should add the orderby options based on the addon plugin
		$order_by_options = apply_filters( $meta_prefix . '_isotope_orderby_options', array(), $meta_prefix );
		
		//If there are orderby options available to choose from (assigned by addon plugins)
		if ( !empty( $order_by_options ) && is_array( $order_by_options ) ){
			$new_fields[$meta_prefix . '_isotope_orderby_options'] = array(
					'field_id'			=> $meta_prefix . '_isotope_orderby_options',
					'field_title' 	=> __( 'Include Ordering Options for the User?', 'mp_stacks'),
					'field_description' 	=> __( 'Which ordering options should be included?', 'mp_stacks' ),
					'field_type' 	=> 'multiple_checkboxes',
					'field_conditional_id' => $meta_prefix . '_isotope',
					'field_conditional_values' => array( $meta_prefix . '_isotope' ), 
					'field_value' => '',
					'field_select_values' => apply_filters( $meta_prefix . '_isotope_orderby_options', array(), $meta_prefix ),		
					'field_showhider' => $meta_prefix . '_isotope_settings',
			);
			
		}
						
		$more_new_fields = array(
			$meta_prefix . '_isotope_navigation' => array(
				'field_id'			=> $meta_prefix . '_isotope_navigation',
				'field_title' 	=> __( 'Isotope Navigation', 'mp_stacks'),
				'field_description' 	=> __( 'What type of Navigation should Isotope use?', 'mp_stacks' ),
				'field_type' 	=> 'select',
				'field_conditional_id' => $meta_prefix . '_isotope',
				'field_conditional_values' => array( $meta_prefix . '_isotope' ), 
				'field_value' => 'buttons',
				'field_select_values' => array( 
					'buttons' => __( 'Row of Isotope Filter Buttons', 'mp_stacks' ),
					'dropdown' => __( 'Dropdown Menu', 'mp_stacks' ),
				),
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
			$meta_prefix . '_isotope_navigation_align' => array(
				'field_id'			=> $meta_prefix . '_isotope_navigation_align',
				'field_title' 	=> __( 'Isotope Navigation Alignment', 'mp_stacks'),
				'field_description' 	=> __( 'How should the Isotope Navigation be aligned above the grid?', 'mp_stacks' ),
				'field_type' 	=> 'select',
				'field_conditional_id' => $meta_prefix . '_isotope',
				'field_conditional_values' => array( $meta_prefix . '_isotope' ), 
				'field_value' => 'center',
				'field_select_values' => array( 
					'center' => __( 'Center', 'mp_stacks' ),
					'left' => __( 'Left', 'mp_stacks' ),
					'right' => __( 'Right', 'mp_stacks' ),
				),
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
			
			$meta_prefix . '_isotope_filter_by_showhider' => array(
				'field_id'			=> $meta_prefix . '_isotope_filter_by_showhider',
				'field_title' 	=> __( '"Filter By" Text', 'mp_stacks'),
				'field_description' 	=> __( '', 'mp_stacks' ),
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
				$meta_prefix . '_isotope_filter_by_text' => array(
					'field_id'			=> $meta_prefix . '_isotope_filter_by_text',
					'field_title' 	=> __( '"Filter By" Text', 'mp_stacks'),
					'field_description' 	=> __( 'What should the "Filter By" Text be? Default: "Filter By:".', 'mp_stacks' ),
					'field_type' 	=> 'textbox',
					'field_value' => __( 'Filter By:', 'mp_stacks' ),
					'field_showhider' => $meta_prefix . '_isotope_filter_by_showhider',
				),
				$meta_prefix . '_isotope_filter_by_color' => array(
					'field_id'			=> $meta_prefix . '_isotope_filter_by_color',
					'field_title' 	=> __( '"Filter By" Text Color', 'mp_stacks'),
					'field_description' 	=> __( 'What color should the "Filter By" Text be?', 'mp_stacks' ),
					'field_type' 	=> 'colorpicker',
					'field_value' => '',
					'field_showhider' => $meta_prefix . '_isotope_filter_by_showhider',
				),
				$meta_prefix . '_isotope_filter_by_textsize' => array(
					'field_id'			=> $meta_prefix . '_isotope_filter_by_textsize',
					'field_title' 	=> __( '"Filter By" Text Size', 'mp_stacks'),
					'field_description' 	=> __( 'What size should the "Filter By" Text be?', 'mp_stacks' ),
					'field_type' 	=> 'number',
					'field_value' => '15',
					'field_showhider' => $meta_prefix . '_isotope_filter_by_showhider',
				),
				$meta_prefix . '_isotope_filter_by_position' => array(
					'field_id'			=> $meta_prefix . '_isotope_filter_by_position',
					'field_title' 	=> __( '"Filter By" Position', 'mp_stacks'),
					'field_description' 	=> __( 'How should the "Filter By" Text be positioned in relation to the buttons?', 'mp_stacks' ),
					'field_type' 	=> 'select',
					'field_value' => 'left',
					'field_select_values' => array( 
						'left' => __( 'Left', 'mp_stacks' ),
						'above' => __( 'Above', 'mp_stacks' ),
					),
					'field_showhider' => $meta_prefix . '_isotope_filter_by_showhider',
				),
			$meta_prefix . '_isotope_all_btn_showhider' => array(
				'field_id'			=> $meta_prefix . '_isotope_all_btn_showhider',
				'field_title' 	=> __( '"All" Button Settings', 'mp_stacks'),
				'field_description' 	=> __( '', 'mp_stacks' ),
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_conditional_id' => $meta_prefix . '_isotope_navigation',
				'field_conditional_values' => array( 'buttons' ), 
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
				$meta_prefix . '_isotope_all_btn_text' => array(
					'field_id'			=> $meta_prefix . '_isotope_all_btn_text',
					'field_title' 	=> __( '"All" Button Text', 'mp_stacks'),
					'field_description' 	=> __( 'What should the "All" Button say?', 'mp_stacks' ),
					'field_type' 	=> 'textbox',
					'field_conditional_id' => $meta_prefix . '_isotope_navigation',
					'field_conditional_values' => array( 'buttons' ), 
					'field_value' => __( 'All', 'mp_stacks' ),
					'field_showhider' => $meta_prefix . '_isotope_all_btn_showhider',
				),
			$meta_prefix . '_isotope_btn_bg_showhider' => array(
				'field_id'			=> $meta_prefix . '_isotope_btn_bg_showhider',
				'field_title' 	=> __( 'Isotope Filter Button Background Settings', 'mp_stacks'),
				'field_description' 	=> __( '', 'mp_stacks' ),
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_conditional_id' => $meta_prefix . '_isotope_navigation',
				'field_conditional_values' => array( 'buttons' ), 
				
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
				$meta_prefix . '_isotope_nav_btn_bg' => array(
					'field_id'			=> $meta_prefix . '_isotope_nav_btn_bg',
					'field_title' 	=> __( 'Enable Button Background?', 'mp_stacks'),
					'field_description' 	=> __( 'Show the buttons have a background? (if not they are transparent)', 'mp_stacks' ),
					'field_type' 	=> 'checkbox',
					'field_conditional_id' => $meta_prefix . '_isotope_navigation',
					'field_conditional_values' => array( 'buttons' ), 
					'field_value' => $meta_prefix . '_isotope_nav_btn_bg',
					'field_showhider' => $meta_prefix . '_isotope_btn_bg_showhider',
				),
					$meta_prefix . '_isotope_nav_btn_bg_color' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_bg_color',
						'field_title' 	=> __( 'Button Background Color', 'mp_stacks'),
						'field_description' 	=> __( 'Set a background color for the filter buttons.', 'mp_stacks' ),
						'field_type' 	=> 'colorpicker',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_bg',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_bg' ), 
						'field_value' => '',
						'field_showhider' => $meta_prefix . '_isotope_btn_bg_showhider',
					),
					$meta_prefix . '_isotope_nav_btn_bg_color_hover' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_bg_color_hover',
						'field_title' 	=> __( 'Mouse-Over Background Color', 'mp_stacks'),
						'field_description' 	=> __( 'What color should the background of the Isotope Nav Buttons be when the user\'s mouse is over it?', 'mp_stacks' ),
						'field_type' 	=> 'colorpicker',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_bg',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_bg' ), 
						'field_value' => '',
						'field_showhider' => $meta_prefix . '_isotope_btn_bg_showhider',
					),
			$meta_prefix . '_isotope_btn_text_showhider' => array(
				'field_id'			=> $meta_prefix . '_isotope_btn_text_showhider',
				'field_title' 	=> __( 'Isotope Filter Button Text Settings', 'mp_stacks'),
				'field_description' 	=> __( '', 'mp_stacks' ),
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_conditional_id' => $meta_prefix . '_isotope_navigation',
				'field_conditional_values' => array( 'buttons' ), 
				
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
				$meta_prefix . '_isotope_nav_btn_text' => array(
					'field_id'			=> $meta_prefix . '_isotope_nav_btn_text',
					'field_title' 	=> __( 'Enable Button Text?', 'mp_stacks'),
					'field_description' 	=> __( 'Show the buttons have text?', 'mp_stacks' ),
					'field_type' 	=> 'checkbox',
					'field_conditional_id' => $meta_prefix . '_isotope_navigation',
					'field_conditional_values' => array( 'buttons' ), 
					'field_value' => $meta_prefix . '_isotope_nav_btn_text',
					'field_showhider' => $meta_prefix . '_isotope_btn_text_showhider',
				),	
					$meta_prefix . '_isotope_nav_btn_text_size' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_text_size',
						'field_title' 	=> __( 'Isotope Filter Button Text Size', 'mp_stacks'),
						'field_description' 	=> __( 'What size should the text be on the Isotope Nav Buttons?', 'mp_stacks' ),
						'field_type' 	=> 'number',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_text',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_text' ), 
						'field_value' => '20',
						'field_showhider' => $meta_prefix . '_isotope_btn_text_showhider',
					),
					$meta_prefix . '_isotope_nav_btn_text_color' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_text_color',
						'field_title' 	=> __( 'Isotope Filter Button Text Color', 'mp_stacks'),
						'field_description' 	=> __( 'What color should the text on the Isotope Nav Buttons be?', 'mp_stacks' ),
						'field_type' 	=> 'colorpicker',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_text',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_text' ), 
						'field_value' => '',
						'field_showhider' => $meta_prefix . '_isotope_btn_text_showhider',
					),
					$meta_prefix . '_isotope_nav_btn_text_color_hover' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_text_color_hover',
						'field_title' 	=> __( 'Mouse-Over Text Color', 'mp_stacks'),
						'field_description' 	=> __( 'What color should the text on the Isotope Nav Buttons be when the user\'s mouse is over it?', 'mp_stacks' ),
						'field_type' 	=> 'colorpicker',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_text',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_text' ), 
						'field_value' => '',
						'field_showhider' => $meta_prefix . '_isotope_btn_text_showhider',
					),
			$meta_prefix . '_isotope_btn_icons_showhider' => array(
				'field_id'			=> $meta_prefix . '_isotope_btn_icons_showhider',
				'field_title' 	=> __( 'Isotope Filter Button Icon Settings', 'mp_stacks'),
				'field_description' 	=> __( '', 'mp_stacks' ),
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_conditional_id' => $meta_prefix . '_isotope_navigation',
				'field_conditional_values' => array( 'buttons' ), 
				
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
				$meta_prefix . '_isotope_nav_btn_icons' => array(
					'field_id'			=> $meta_prefix . '_isotope_nav_btn_icons',
					'field_title' 	=> __( 'Enable Button Icons?', 'mp_stacks'),
					'field_description' 	=> __( 'Show the buttons have icons? (icons pulled from feeds above)', 'mp_stacks' ),
					'field_type' 	=> 'checkbox',
					'field_conditional_id' => $meta_prefix . '_isotope_navigation',
					'field_conditional_values' => array( 'buttons' ), 
					'field_value' => $meta_prefix . '_isotope_nav_btn_icons',
					'field_showhider' => $meta_prefix . '_isotope_btn_icons_showhider',
				),	
					$meta_prefix . '_isotope_nav_btn_icons_size' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_icons_size',
						'field_title' 	=> __( 'Icon Size', 'mp_stacks'),
						'field_description' 	=> __( 'What size should the button icons be', 'mp_stacks' ),
						'field_type' 	=> 'number',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_icons',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_icons' ), 
						'field_value' => '14',
						'field_showhider' => $meta_prefix . '_isotope_btn_icons_showhider',
					),
					$meta_prefix . '_isotope_nav_btn_icons_alignment' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_icons_alignment',
						'field_title' 	=> __( 'Icon Alignment', 'mp_stacks'),
						'field_description' 	=> __( 'Where should the icon sit in relation to the text?', 'mp_stacks' ),
						'field_type' 	=> 'select',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_icons',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_icons' ), 
						'field_value' => 'left',
						'field_select_values' => array( 
							'left' => __( 'Left', 'mp_stacks' ),
							'right' => __( 'Right', 'mp_stacks' ),
						),
						'field_showhider' => $meta_prefix . '_isotope_btn_icons_showhider',
					),
					$meta_prefix . '_isotope_nav_btn_icons_color' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_icons_color',
						'field_title' 	=> __( 'Custom Icon Icon Color', 'mp_stacks'),
						'field_description' 	=> __( 'What color should the icons on the Isotope Nav Buttons be?', 'mp_stacks' ),
						'field_type' 	=> 'colorpicker',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_icons',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_icons' ), 
						'field_value' => '#fff',
						'field_showhider' => $meta_prefix . '_isotope_btn_icons_showhider',
					),
					$meta_prefix . '_isotope_nav_btn_icons_color_hover' => array(
						'field_id'			=> $meta_prefix . '_isotope_nav_btn_icons_color_hover',
						'field_title' 	=> __( 'Mouse-Over Icon Color', 'mp_stacks'),
						'field_description' 	=> __( 'What color should the icons on the Isotope Nav Buttons be when the user\'s mouse is over it?', 'mp_stacks' ),
						'field_type' 	=> 'colorpicker',
						'field_conditional_id' => $meta_prefix . '_isotope_nav_btn_icons',
						'field_conditional_values' => array( $meta_prefix . '_isotope_nav_btn_icons' ), 
						'field_value' => '',
						'field_showhider' => $meta_prefix . '_isotope_btn_icons_showhider',
					),
			$meta_prefix . '_isotope_hidden_buttons_showhider' => array(
				'field_id'			=> $meta_prefix . '_isotope_hidden_buttons_showhider',
				'field_title' 	=> __( 'Hide Filter Buttons', 'mp_stacks'),
				'field_description' 	=> __( '', 'mp_stacks' ),
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_conditional_id' => $meta_prefix . '_isotope',
				'field_conditional_values' => array( $meta_prefix . '_isotope' ), 
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
				$meta_prefix . '_isotope_hidden_buttons' => array(
						'field_id'			=> $meta_prefix . '_isotope_hidden_buttons',
						'field_title' 	=> __( 'Hidden Filter Buttons', 'mp_stacks'),
						'field_description' 	=> __( 'If you want to skip showing a Filter Button and you know the "slug" for that group, enter it here (comma separated)', 'mp_stacks' ),
						'field_type' 	=> 'textarea',
						'field_value' => '',
						'field_placeholder' => 'tag1, tag2, etc',
						'field_showhider' => $meta_prefix . '_isotope_hidden_buttons_showhider',
					),
					
			$meta_prefix . '_isotope_layout_mode' => array(
				'field_id'			=> $meta_prefix . '_isotope_layout_mode',
				'field_title' 	=> __( 'Isotope Layout Mode', 'mp_stacks'),
				'field_description' 	=> __( 'Which layout mode should Isotope use? For a description of these options see ', 'mp_stacks' ) . '<a href="http://isotope.metafizzy.co/layout-modes.html" target="_blank">' . __( 'this page.', 'mp_stacks' ) . '</a>',
				'field_type' 	=> 'select',
				'field_conditional_id' => $meta_prefix . '_isotope',
				'field_conditional_values' => array( $meta_prefix . '_isotope' ), 
				'field_value' => 'masonry',
				'field_select_values' => array( 
					'masonry' => __( 'Masonry', 'mp_stacks' ),
					'fitRows' => __( 'Fit Rows', 'mp_stacks' ),
					'cellByRow' => __( 'Cells By Row', 'mp_stacks' ),
					'vertical' => __( 'Vertical', 'mp_stacks' ),
					'masonryHorizontal' => __( 'Masonry Horizontal', 'mp_stacks' ),
					'fitColumns' => __( 'Fit Columns', 'mp_stacks' ),
					'cellsByColumn' => __( 'Cells By Column', 'mp_stacks' ),
					'horizontal' => __( 'Horizontal', 'mp_stacks' ),
				),
				'field_showhider' => $meta_prefix . '_isotope_settings',
			),
	);
	
	$new_fields = array_merge( $new_fields, $more_new_fields );
			
	return $new_fields;
						
}

/**
 * Process and return the HTML needed for Isotope Navigation Buttons.
 *
 * @access   public
 * @since    1.0.0
 * @param    $css_output          	 String - The incoming CSS output coming from other things using this filter
 * @param    $post_id             	 Int - The post ID of the brick containing the grid settings.
 * @param    $meta_prefix  		  	 string - The prefix to put before each meta key when get_post_meta-ing
 * @param    $sources_array  	  	 array - The repeater array containing each "feed" or source information
 * @return   $css_output          	 string - The CSS needed to properly style the isotope navigation buttons
 */
function mp_stacks_grids_isotope_filtering_html( $post_id, $meta_prefix, $sources_array ){
	
	$return_html = NULL;
	
	$isotope_filter_groups_function_name = 'mp_stacks_' . $meta_prefix . '_isotope_filter_groups';
					 
	//Get the list of isotope filter_groups given to this current source
	$all_isotope_filter_groups = $isotope_filter_groups_function_name();
		
	//Taxonomies to include
	$filter_groups_to_include = mp_core_get_post_meta_multiple_checkboxes( $post_id, $meta_prefix . '_isotope_filter_groups', array() );	
	
	//Get isotope settings
	$isotope_filtering_on = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope', false );
	
	//Get the array containing all taxonomies and tax terms by which we should do Isotope Filtering. Each Tax is a dropdown or button group.
	$master_filter_groups_array = mp_stacks_grids_isotope_set_master_filter_groups_array( $post_id, $meta_prefix, $sources_array, $filter_groups_to_include, $all_isotope_filter_groups );
	
	//Buttons to skip
	$buttons_to_skip = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_hidden_buttons' );
	$buttons_to_skip = str_replace( ' ,', ',', explode( ',', trim( $buttons_to_skip ) ) );
	
	//Filtering Behavior
	$filtering_behavior = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_filtering_behavior', 'default_isotope' );
	$load_more_behaviour = mp_core_get_post_meta($post_id, $meta_prefix  . '_load_more_behaviour', 'ajax_load_more' );
	$filtering_behavior = $load_more_behaviour == 'pagination' ? 'default_isotope' : $filtering_behavior;
		
	if ( !$master_filter_groups_array ){
		return false;	
	}
	
	//If we should show the isotope navigation for sorting these items
	if ( $isotope_filtering_on ){
				
		$isotope_sorting_type = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_navigation', 'buttons' );
						
		//Output the "Filter By" text
		$filter_by_text = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_filter_by_text', 'Filter By:' );
		$filter_by_position = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_filter_by_position', 'left' );
		$filter_by_html = !empty( $filter_by_text ) ? '<div class="mp-stacks-grid-isotope-filterby-text mp-stacks-grid-isotope-filterby-text-' . $filter_by_position . '">' . $filter_by_text . '</div>' : NULL;
		//We have at least one filtering option so add the "Filter By" html now that we know that.
		foreach( $master_filter_groups_array as $taxonomy_name => $taxonomy ){
			foreach( $taxonomy as $tax_term ){
				if( count( $taxonomy ) == 1 ){
					continue;	
				}
				else{
					$return_html .= $filter_by_html;
					break;
				}
			}
			break;
		}
				
		//If we should use a dropdown for sorting
		if ( $isotope_sorting_type == 'dropdown' ){
			
			$return_html .= '<div id="mp-stacks-grid-isotope-sort-container-' . $post_id . '" class="mp-stacks-grid-isotope-sort-container" mp_stacks_grid_isotope_behavior="' . $filtering_behavior . '">';
			
				//Hook any additional code that should appear before the first filtering dropdown
				$return_html = apply_filters( 'mp_stacks_isotopes_before_first_dropdown', $return_html, $post_id );
									
				//Now that we have all the "taxonomies" separated out into an array, loop through them and only export Isotope Filter Groups for each
				$tax_counter = 0;
				foreach( $master_filter_groups_array as $taxonomy_name => $taxonomy ){
					
					//If this filter group has only the "All" Category, skip it
					if( count( $taxonomy ) == 1 ){
						continue;
					}
					
					$return_html .= '<select id="mp-isotope-sort-select-' . $post_id . '-' . $tax_counter . '" class="button mp-stacks-grid-isotope-sort-select" value="*" mp_stacks_grid_post_id="' . $post_id . '" mp_stacks_grid_ajax_prefix="' . $meta_prefix . '">';
											
						//Loop through each tax term in this taxonomy
						$tax_term_counter = 0;
						foreach( $taxonomy as $tax_term ){
							
							//If this is the "All" Filter
							if ( $tax_term_counter == 0 ){
								$return_html .= '<option value="*" mp_stacks_grid_post_id="' . $post_id . '" mp_stacks_grid_ajax_prefix="' . $meta_prefix . '">' . $tax_term['name'] . '</option>';
								
								//Hook any additional items to the start - after the "All" Button
								$return_html = apply_filters( 'mp_stacks_isotopes_additional_options_start', $return_html, $post_id, 'select' );
													
							}
							else{
								if ( !in_array( $tax_term['slug'], $buttons_to_skip ) ){
									
									//Get the number of posts in this term
									$term_info = get_term_by('name', $tax_term['name'], $taxonomy_name);
									$term_count = $term_info ? ' (' . $term_info->count . ')' : NULL;
									
									$return_html .= '<option ' . ( $filtering_behavior == 'default_isotope' ? 'disabled' : NULL ) . ' value="[mp_stacks_grid_isotope_taxonomy_' . $taxonomy_name . '*=\'' . $tax_term['slug'] . ',\']" tax="' . $taxonomy_name  . '" term="' . $tax_term['slug'] . '" mp_stacks_grid_post_id="' . $post_id . '" mp_stacks_grid_ajax_prefix="' . $meta_prefix . '">' . $tax_term['name'] . $term_count . '</option>';		
								}
							}
							
							$tax_term_counter = $tax_term_counter  + 1;
						}
						
						//Hook any additional items to the end
						$return_html = apply_filters( 'mp_stacks_isotopes_additional_options_end', $return_html, $post_id );
					
					$return_html .= '</select>';
				
					$tax_counter = $tax_counter + 1;
					
				}
			
			$return_html .= '</div>';
			
		}
		//If we should use buttons for sorting
		else{
			
			//If the button icons should be shown
			$enable_button_icons = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope_nav_btn_icons', true );	
			//Isotope Icon Alignment on the button - how should the icon sit in relation to the text?
			$button_icon_alignment = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_nav_btn_icons_alignment', 'left' );
			
			//Now that we have all the "taxonomies" separated out into an array, loop through them and only export Isotope Filter Groups for each
			$tax_counter = 0;
			foreach( $master_filter_groups_array as $taxonomy_name => $taxonomy ){
								
				//If this filter group has only the "All" Category, skip it
				if( count( $taxonomy ) == 1 ){					
					continue;
				}
				
				$return_html .= '<div id="mp-stacks-grid-isotope-sort-container-' . $post_id . '" taxonomy="' . $taxonomy_name . '" class="mp-stacks-grid-isotope-sort-container" mp_stacks_grid_isotope_behavior="' . $filtering_behavior . '">';
					
					$source_counter = 0;
					
					//Loop through each tax term in this taxonomy
					$tax_term_counter = 0;
					foreach( $taxonomy as $tax_term ){
						
						//If this is one of the "All" buttons - but not the first "All" Button, skip it.
						if ( $tax_counter > 0 && $tax_term_counter == 0 ){
							$tax_term_counter = $tax_term_counter + 1;
							continue;	
						}
												
						//If this is the "All" Filter Button
						if ( $tax_term_counter == 0 ){
														 							
							$return_html .= '<div class="button mp-stacks-grid-isotope-button mp-stacks-grid-isotope-button-all mp-stacks-isotope-filter-button-active" data-filter="*" mp_stacks_grid_post_id="' . $post_id . '" mp_stacks_grid_ajax_prefix="' . $meta_prefix . '">';					
							
								//If icons are enabled and we're on the "All" button
								if ( $enable_button_icons ){	
									//Set up the HTML icon for "ALL"
									$all_icon = '<div class="mp-stacks-grid-isotope-icon-all-1"></div>';
									$all_icon .= '<div class="mp-stacks-grid-isotope-icon-all-2"></div>';
									$all_icon .= '<div class="mp-stacks-grid-isotope-icon-all-3"></div>';
									$all_icon .= '<div class="mp-stacks-grid-isotope-icon-all-4"></div>';
									$all_icon_html = apply_filters( 'mp_stacks_grid_isotope_all_icon_html', $all_icon, $meta_prefix );
									
									//Set up the icon font icon for "ALL"
									$all_icon_class = apply_filters( 'mp_stacks_grid_isotope_all_icon_font_class', '', $meta_prefix );
																
									//If an Icon Font string has been passed for the "All" button
									if ( !empty( $all_icon_class ) ){						
										$return_html .= '<div class="mp-stacks-grid-isotope-icon mp-stacks-grid-isotope-icon-all ' . $all_icon_class . '"></div>';
									}
									//If we should use an image or the dedault squares as the "All" Button icon
									else{
										$return_html .= '<div class="mp-stacks-grid-isotope-icon mp-stacks-grid-isotope-icon-all">' . $isotope_icon_html  . ' </div>';	
									}
								}
								
								$all_button_text = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_all_btn_text', __( 'All', 'mp_stacks' ) );
								
								$return_html .= '<div class="mp-stacks-grid-isotope-btn-text">' . $all_button_text . '</div>';
							
							$return_html .= '</div>';
							
							
			
							//Hook any additional items to the start - after the "All" Button
							$return_html = apply_filters( 'mp_stacks_isotopes_additional_options_start', $return_html, $post_id, 'buttons' );
		
						}
						//If this is not the "All" filter button
						else{
							
							//If we should skip this button
							if ( in_array( $tax_term['slug'], $buttons_to_skip ) ){
								continue;
							}
							
							$button_text = $tax_term['name'];
							
							$return_html .= '<div class="button mp-stacks-grid-isotope-button" data-filter="[mp_stacks_grid_isotope_taxonomy_' . $taxonomy_name . '*=\'' . $tax_term['slug'] . ',\']" mp_stacks_grid_post_id="' . $post_id . '" mp_stacks_grid_ajax_prefix="' . $meta_prefix . '" tax="' . $taxonomy_name  . '" term="' . $tax_term['slug'] . '">';
									
						
							if ( $enable_button_icons ){
								
								$icon_font_html = NULL;
								$icon_image_html = NULL;
								
								//If an icon font string exists
								if ( !empty( $tax_term['icon_font_string'] ) || !empty( $tax_term['default_icon_font_string'] ) ){
									
									//Check if there is a unique icon or if we just use the default fallback icon
									$icon_font_string = !empty( $tax_term['icon_font_string'] ) ? $tax_term['icon_font_string'] : $tax_term['default_icon_font_string'];
									
									//Filter the icon font class name
									$icon_font_string = apply_filters( 'mp_stacks_grid_isotope_icon_font_class', $icon_font_string, $meta_prefix );
									
									//Icon font class might be "mp-stacks-socialgrid-icon-twitter"
									$icon_font_html = !empty( $icon_font_string ) ? '<div class="mp-stacks-grid-isotope-icon ' . $icon_font_string . '"></div>' : NULL;
									
								}
								
								//If an icon image url exists
								if ( !empty( $tax_term['icon_image_url'] ) || !empty( $tax_term['default_icon_image_url'] ) ){
									
									//Check if there is a unique icon or if we just use the default fallback icon
									$icon_image_url = !empty( $tax_term['icon_image_url'] ) ? $tax_term['icon_image_url'] : $tax_term['default_icon_image_url'];
									//Output the icon - if there is one
									$icon_image_html = !empty( $icon_image_url ) ? '<div class="mp-stacks-grid-isotope-icon"><img src="' . $icon_image_url . '" /></div>' : NULL;
									
								}
								
								//Set default for icon html
								$icon_html = NULL;	
								
								//If we have a preference for whether to use an image or an icon font
								if ( !empty( $tax_term['icon_type_preference'] ) ){
									
									//If we prefer to use an icon font over an image
									if ( $tax_term['icon_type_preference'] == 'icon' ){
										$icon_html = !empty( $icon_font_html ) ? $icon_font_html : $icon_image_html;
									}
									//If we prefer to use an image over an icon font
									else if ( !empty(  $tax_term['icon_type_preference'] ) && $tax_term['icon_type_preference'] == 'image' ){
										$icon_html = !empty( $icon_image_html ) ? $icon_image_html : $icon_font_html;
									}
									
								}
								else{
									//Prefer to use an icon font over an image, with fall back to image
									$icon_html = !empty( $icon_font_html ) ? $icon_font_html : $icon_image_html;
								}
							
									
							}
						
							//If the icon should be on the left of the isotope button
							if ( $button_icon_alignment == 'left' && $enable_button_icons){
															
								$return_html .= $icon_html;
								
							}
							
							//Button Text
							$return_html .= '<div class="mp-stacks-grid-isotope-btn-text">' . $button_text . '</div>';
							
							//If the icon should be on the right of the isotope button
							if ( $button_icon_alignment == 'right' && $enable_button_icons){
								
								$return_html .= $icon_html;
								
							}
							
							$return_html .= '</div>';
							
							//Hook any additional items to the start - after the "All" Button
							$return_html = apply_filters( 'mp_stacks_isotopes_additional_options_end', $return_html, $post_id, 'buttons' );
						}
												
						$tax_term_counter = $tax_term_counter + 1;
					
					}
				
				$return_html .= '</div>';
			
				$tax_counter = $tax_counter + 1;
				
			}
			
		}
		
		//Output the Order By Options
		$return_html .= mp_stacks_grid_orderby_output( $post_id, $meta_prefix );
				
		return $return_html;
	}
	
}

/**
 * Create a string containing an html attribute for each isotope filtering taxonomy. This is used in "The Loop" on the Grid HTML Output.
 *
 * @access   public
 * @since    1.0.0
 * @param    $sources_array Array - The Sources Repeater Array from which the Grid is populated.
 * @param    $grid_post_id int - The id of the WP post for which this function was called.
 * @param    $brick_id int - The id of the Brick from which this grid was created.
 * @param    $meta_prefix string - The meta prefix used by this grid. EG "socialgrid" or "postgrid"..etc
 * @param    $source_parent_num int - The source iteration number (in the sources_array) that the post in question was "born" from.
 * @return   $string String - A string that contains all of the attributes needed to match isotope sorting for all categories for this single grid item.
 */
function mp_stacks_grid_item_isotope_filter_attributes( $sources_array, $grid_post_id = NULL, $brick_id, $meta_prefix, $source_parent_num ){
	
	$isotope_taxonomy_attributes = NULL;
	
	//Taxonomies to include
	$filter_groups_to_include = mp_core_get_post_meta_multiple_checkboxes( $brick_id, $meta_prefix . '_isotope_filter_groups', array() );	
		
	//If a post id has been passed, we will be creating the filter groups based on WORDPRESS taxonomies and terms
	if ( $grid_post_id != 'grid_item_not_a_wordpress_post' ){
		
		
		if ( !is_array( $filter_groups_to_include ) ){
			return false;	
		}
		
		//Loop through each taxonomy we should include
		foreach( $filter_groups_to_include as $tax_slug ){
			
			$tax_terms = get_the_terms( $grid_post_id, $tax_slug );
						
			if ( !$tax_terms ){
				continue;
			}
		
			$attr_value = NULL;
			
			//Loop through each tax term
			foreach( $tax_terms as $tax_term ){
				$attr_value .= $tax_term->slug . ', ';
			}
			
			$isotope_taxonomy_attributes .= ' mp_stacks_grid_isotope_taxonomy_' . $tax_slug . '="' . $attr_value . '" ';
			
		}
		
	}
	//If a grid post id has NOT been passed, we will be creating the filter group attributes>values based on categories passed by the grid addon
	else{
					
		$filter_group_counter = 0;
		
		$isotope_filter_groups_function_name = 'mp_stacks_' . $meta_prefix . '_isotope_filter_groups';
				 
		//Get the list of isotope_categories given to this current grid
		$isotope_filter_groups = $isotope_filter_groups_function_name();
		
		//Loop through each Isotope Filter Group 
		foreach( $isotope_filter_groups as $isotope_filter_group_id => $isotope_filter_group ){
																
				//Add the current filter group as an attribute to the current grid post
				
				//This might be "Social Network"
				$filter_group_name = $isotope_filter_group['filter_group_name'];
				
				//This might be "social-network"
				$filter_group_slug = $isotope_filter_group_id;
				
				//Reset the group value
				$filter_group_value = NULL;
				
				//This might be "twitter", or a combination of meta values like twitter+mintplugins
				foreach( $isotope_filter_group['meta_field_ids_representing_tax_term'] as $source_meta_key => $source_meta_key_info ){
					
					//If the value of the meta field matches what we want for this filtering ( EG: feed_type == 'user' ) or (  EG: feed_type == 'hashtag' )
					//Or if the meta value is allowed to be anything (using the "*")
					if ( $sources_array[$source_parent_num][$source_meta_key] == $source_meta_key_info['include_if_set_to'] || $source_meta_key_info['include_if_set_to'] == '*' ){
						$filter_group_value .= $sources_array[$source_parent_num][$source_meta_key] . '+';
					}
					else{
						$filter_group_value = NULL;
						break;
					}	
					
						
				}
				
				if ( $filter_group_value ){										
					//Here we add each Isotope Category Attribute to a string
					$isotope_taxonomy_attributes .= ' mp_stacks_grid_isotope_taxonomy_' . $filter_group_slug . '="' . $filter_group_value . '," ';	
				}
			
			
			$filter_group_counter = $filter_group_counter + 1;	
				
		}
	}
	
	return $isotope_taxonomy_attributes;
}

/**
 * Make Isotope Filter buttons magically "appear" when they have something to filter. This is done by a js function being added to the post with new ajax posts which triggers display:block css for the buttons which were previously hidden.
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $sources_array Array - The Sources Repeater Array from which the Grid is populated.
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @param    $grid_post_id int - The id of the WP post for which this function was called.
 * @param    $source_counter int - The source iteration number (in the sources_array) that the post in question was "born" from.
 * @return   $js_output String The JS Output which makes the correct buttons "appear" when any corresponding post appears.
*/
function mp_stacks_grid_isotope_show_buttons_with_posts( $sources_array, $brick_id, $meta_prefix, $grid_post_id, $source_parent_num ){
				
	//Taxonomies to include
	$filter_groups_to_include = mp_core_get_post_meta_multiple_checkboxes( $brick_id, $meta_prefix . '_isotope_filter_groups', array() );	
	
	$isotope_button_selectors = NULL;
	$css = NULL;
	
	if ( !is_array( $filter_groups_to_include ) ){
		return false;	
	}
	
	//Loop through each taxonomy we should include
	foreach( $filter_groups_to_include as $tax_slug ){
		
		//If this is a WordPress post
		if ( $grid_post_id != 'grid_item_not_a_wordpress_post' ){
			$tax_terms = get_the_terms( $grid_post_id, $tax_slug );
						
			if ( !$tax_terms ){
				continue;
			}
		
			$attr_value = NULL;
			
			//Loop through each tax term
			foreach( $tax_terms as $tax_term ){
								
				$isotope_button_selectors .= '
				$( "#mp-brick-' . $brick_id . ' .mp-stacks-grid-isotope-button[data-filter*=\"\'' . $tax_term->slug . ',\'\"]").css( "display", "inline-block" );';
				
				$isotope_button_selectors .= '
				$( "#mp-brick-' . $brick_id . ' .mp-stacks-grid-isotope-sort-select option[value*=\"\'' . $tax_term->slug . ',\'\"]").attr( "disabled", false );';
				
							
			}	
		}
		//If this is not a WordPress Post
		else{
			
			$isotope_filter_groups_function_name = 'mp_stacks_' . $meta_prefix . '_isotope_filter_groups';
				
			//Get the list of isotope_categories given to this current grid
			$isotope_filter_groups = $isotope_filter_groups_function_name();
			
			//Loop through each Isotope Filter Group 
			foreach( $isotope_filter_groups as $isotope_filter_group_id => $isotope_filter_group ){
				
				$filter_group_value = NULL;
				
				//This might be "twitter", or a combination of meta values like twitter+mintplugins
				foreach( $isotope_filter_group['meta_field_ids_representing_tax_term'] as $source_meta_key => $source_meta_key_info ){
					
					//If the value of the meta field matches what we want for this filtering ( EG: feed_type == 'user' ) or (  EG: feed_type == 'hashtag' )
					//Or if the meta value is allowed to be anything (using the "*")
					if ( $sources_array[$source_parent_num][$source_meta_key] == $source_meta_key_info['include_if_set_to'] || $source_meta_key_info['include_if_set_to'] == '*' ){
						$filter_group_value .= $sources_array[$source_parent_num][$source_meta_key] . '+';			
					}
					else{
						$filter_group_value = NULL;
						break;
					}	
					
					$isotope_button_selectors .= '
						$( "#mp-brick-' . $brick_id . ' .mp-stacks-grid-isotope-button[data-filter*=\"\'' . $filter_group_value . ',\'\"]").css( "display", "inline-block" );';
					
						
				}
							
			}	
			
		}
		
	}
	
	return '<script type="text/javascript">jQuery(document).ready(function($){' . $isotope_button_selectors . '});</script>';
}
		
		
/**
 * Create a "Master" array containing all of the taxonomies for a Grid. This includes both WP taxonomies AND Pseudo taxonomies entered using CSV in the "Feeds" or "Sources" by the site owner.
 *
 * @access   public
 * @since    1.0.0
 * @param    $post_id String - The id of the brick where these settings are configured.
 * @param    $meta_prefix String - TThe prefix of the gird. IE 'postgird', 'eventgrid' etc.
 * @param    $sources_array Array - The repeater array from which the grid is populated.
 * @param    $filter_groups_to_include Array 
 * @param    $all_isotope_filter_groups Array 
 * @return   $master_filter_groups_array Array - An Array that contains each key being the taxonomy and the value being an array of tax_terms in that taxonomy
 */
function mp_stacks_grids_isotope_set_master_filter_groups_array( $post_id, $meta_prefix, $sources_array, $filter_groups_to_include, $all_isotope_filter_groups ){
	
	$master_filter_groups_array = array();
	
	$sources_counter = 0;
		
	if ( !is_array( $sources_array ) ) {
		return false;	
	}
																	
	//Loop through each taxonomy that should be included as a filter group
	foreach( $all_isotope_filter_groups as $filter_group_slug => $filter_group_array ){	
		
		if ( !is_array( $filter_groups_to_include ) ){
			return false;	
		}
		
		//If the site-owner decided not to include this taxonomy  as a filter group, skip it.
		if ( !in_array( $filter_group_slug, $filter_groups_to_include ) ){
			continue;	
		}
		
		//Add the "All" option for each Filter Group to the master array. EG: All Social Networks, All Users, etc
		$master_filter_groups_array[$filter_group_slug][0] = array( 
			'name' => __( 'All', 'mp_stacks' ) . ' ' . $filter_group_array['filter_group_name'],
			'slug' => '*',
			'icon_font_string' => 'all',
		);
		
		//If this Filter Group is a WordPress Taxonomy
		if ( $filter_group_array['is_wordpress_taxonomy'] ){			
						
			$args = array(
				'orderby'           => apply_filters( 'mp_stacks_isotope_controls_orderby', 'slug' ), 
				'order'             => apply_filters( 'mp_stacks_isotope_controls_order', 'ASC' ),
			); 

			$wp_tax_terms = get_terms( $filter_group_slug, $args );			
			
			//Loop through each tax term
			foreach( $wp_tax_terms as $wp_tax_term ){
				
				//If there are no posts in this term, skip it
				if ( $wp_tax_term->count == 0 ){
					continue;	
				}
				
				//Forumlate the meta field id where the tax term if is stored for this source array
				$full_meta_field_id  = NULL;
				foreach( $filter_group_array['meta_field_ids_representing_tax_term'] as $meta_field_id => $meta_field_info ){
					$full_meta_field_id .= $meta_field_id;
				}
								
				//If there is only 1 WP source and that source is this tax term, skip it because the "all" button will be the exact same thing.
				if ( count( $sources_array ) == 1 && ( isset( $sources_array[0][$full_meta_field_id] ) && $wp_tax_term->term_id == $sources_array[0][$full_meta_field_id] ) ){
					continue;
				}
				
				//Here we add each tax term to the master taxonomy array
				$master_filter_groups_array[$filter_group_slug][] = array( 
					'name' => $wp_tax_term->name,
					'slug' => $wp_tax_term->slug,
					'icon_font_string' => NULL, //We might add icon pickers for taxonomy terms in the future. This would be picked by going to "posts" > "categories" > "edit"
					'icon_image_url' => NULL, //We might add icon pickers for taxonomy terms in the future. This would be picked by going to "posts" > "categories" > "edit"
					'icon_type_preference' => NULL,
					'default_icon_font_string' => isset( $filter_group_array['default_icon_font_string'] ) ? $filter_group_array['default_icon_font_string'] : NULL,
					'default_icon_image_url' => isset( $filter_group_array['default_icon_image_url'] ) ? $filter_group_array['default_icon_image_url'] : NULL,
				);
										
			}
		}
		//If this Filter Group is not a WordPress source (eg Twitter, YouTube, Instagram feeds)
		else{
			
			$sources_counter = 0;
			
			$added_filter_group_values = array();
										
			//Loop through each Source Array
			foreach( $sources_array as $source_array ){
					
					//Reset the group values
					$filter_group_value_name = NULL;
					$filter_group_value_slug = NULL;
					$filter_group_value_icon_font_string = NULL;
					$filter_group_value_default_icon_font_string = NULL;
					$filter_group_value_icon_image_url = NULL;
					$filter_group_value_default_icon_image_url = NULL;
				
					//This might be "twitter", or a combination of meta values like twitter+mintplugins
					foreach( $filter_group_array['meta_field_ids_representing_tax_term'] as $source_meta_key => $source_meta_key_info ){
						
						//If the value of the meta field matches what we want for this filtering ( EG: feed_type == 'user' ) or (  EG: feed_type == 'hashtag' )
						//Or if the meta value is allowed to be anything (using the "*")
						if ( $source_meta_key_info['include_if_set_to'] == $source_array[$source_meta_key] || $source_meta_key_info['include_if_set_to'] == '*' ){
							
							//If we should include this source_meta_key in the display name
							if ( $source_meta_key_info['include_in_final_display_name'] ){
							
								$filter_group_value_name .= $source_meta_key_info['capitalize_first_letter'] ? ucfirst( $source_array[$source_meta_key] ) : $source_array[$source_meta_key];
								
							}
					
							//Set the slug for this filter group value
							$filter_group_value_slug .= $source_array[$source_meta_key] . '+';
							
							
						}
						else{
							$filter_group_value_name = NULL;
							$filter_group_value_slug = NULL;
							break;
						}
					}
					
					//Maybe set the icon font string
					if ( isset( $filter_group_array['icon_font_string_meta_key'] ) ){
						$filter_group_value_icon_font_string = isset( $source_array[$filter_group_array['icon_font_string_meta_key']] ) ? $source_array[$filter_group_array['icon_font_string_meta_key']] : NULL;
					}
					
					//Maybe set a default icon font string
					$filter_group_value_default_icon_font_string = isset( $filter_group_array['default_icon_font_string'] ) ? $filter_group_array['default_icon_font_string'] : NULL;
					
					//Maybe set the image url for an image icon
					if ( isset( $filter_group_array['icon_image_url_meta_key'] ) ){
						$filter_group_value_icon_image_url = isset( $source_array[$filter_group_array['icon_image_url_meta_key']] ) ? $source_array[$filter_group_array['icon_font_string_meta_key']] : NULL;
					}
					
					//Maybe set a default image url for a default image icon
					$filter_group_value_default_icon_image_url = isset( $filter_group_array['default_icon_image_url'] ) ? $filter_group_array['default_icon_image_url'] : NULL;
					
					//If the filter group value (we just built by combining meta values) isn't blank
					if ( $filter_group_value_slug ){
						
						//Make sure the filter group value is unique
						if ( !in_array( $filter_group_value_slug, $added_filter_group_values ) ){
							
							//Make a list of all the values we've already added to make sure we don't double them up
							$added_filter_group_values[] = $filter_group_value_slug;
							
							//Here we add each filter group to the master filter groups array
							$master_filter_groups_array[$filter_group_slug][] = array( 
								'name' => $filter_group_value_name,
								'slug' => $filter_group_value_slug,
								'icon_font_string' => $filter_group_value_icon_font_string,
								'icon_image_url' => $filter_group_value_icon_image_url,
								'icon_type_preference' => isset( $filter_group_array['icon_type_meta_key'] ) ? $source_array[$filter_group_array['icon_type_meta_key']] : 'icon',
								'default_icon_font_string' => $filter_group_value_default_icon_font_string,
								'default_icon_image_url' => $filter_group_value_default_icon_image_url,
							);
							
						}
					}
		
								
				$sources_counter = $sources_counter + 1;	
					
			}
														
		}
						
	}	
	
	return $master_filter_groups_array;

}

/**
 * Process and return the CSS needed for Isotope Navigation Buttons. The function decides if the buttons have a background, text, and an icon. 
 * It also processes CSS for the icon size and alignment as well as colors for all button elements.
 *
 * @access   public
 * @since    1.0.0
 * @param    $css_output          String - The incoming CSS output coming from other things using this filter
 * @param    $post_id             Int - The post ID of the brick
 * @param    $meta_prefix  		  String - The prefix to put before each meta key when get_post_meta-ing
 * @return   $css_output          String - The CSS needed to properly style the isotope navigation buttons
 */
function mp_stacks_grid_isotope_nav_btns_css( $post_id, $meta_prefix ){
	
	$css_output = NULL;
	
	//Get isotope settings
	$isotope_filtering_on = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope', false );
	
	//If Isotope sorting is ON
	if ( $isotope_filtering_on ){	
			
		//Post Spacing (padding) - we match the post spacing for isotope buttons/dropdowns
		$post_spacing = mp_core_get_post_meta($post_id, $meta_prefix . '_post_spacing', '20');
	
		$button_text_size = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_nav_btn_text_size', 20 );	
		
		//If the behavior is 'load from scratch', show all filter buttons by default.
		$isotope_behavior = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_filtering_behavior', 'default_isotope' );
		$load_more_behaviour = mp_core_get_post_meta($post_id, $meta_prefix  . '_load_more_behaviour', 'ajax_load_more' );
		$isotope_behavior = $load_more_behaviour == 'pagination' ? 'default_isotope' : $isotope_behavior;
		
		if ( $isotope_behavior == 'reload_from_scratch' ){
			$css_output .= '#mp-brick-' . $post_id . ' .button.mp-stacks-grid-isotope-button{
				display:inline-block!important;
			}';
		}
	
		//Isotope Nav Container
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-sort-container{';
			 $isotope_nav_alignment = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_navigation_align', 'center' );
			 $css_output .= mp_core_css_line( 'text-align', $isotope_nav_alignment );
			 if ( $isotope_nav_alignment == 'left' ){
			 	$css_output .= mp_core_css_line( 'margin-left', $post_spacing, 'px' );
			 }
			 if ( $isotope_nav_alignment == 'right' ){
			 	$css_output .= mp_core_css_line( 'margin-right', $post_spacing, 'px' );
			 }
			 
			 //Line height for the container
			 $css_output .= mp_core_css_line( 'line-height', $button_text_size*3.5, 'px' );
			 
		$css_output .= '}';
		
		//Isotope Button BACKGROUND
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-button, #mp-brick-' . $post_id . ' .mp-stacks-grid-orderby-select, #mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-sort-select{';
		
			//Button Background
			$enable_button_background = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope_nav_btn_bg', true );	
			if ( $enable_button_background ){
				
				$button_bg_color = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_nav_btn_bg_color' );	
				$css_output .= mp_core_css_line( 'background-color', $button_bg_color );								
			}
			//If no button backgound
			else{
				$css_output .= 'background:transparent;';
				$css_output .= 'padding:0px;';
			}
			
		$css_output .= '}';
		
		//LAST Isotope Button with a BACKGROUND
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-button:last-child, #mp-brick-' . $post_id . ' .mp-stacks-grid-orderby-select:last-child, #mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-sort-select:last-child{';
			
			//Remove the margin on the right
			$css_output .= 'margin-right: 0px';
			
		$css_output .= '}';
		
		
		//Isotope Button BACKGROUND Hover
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-button:hover, #mp-brick-' . $post_id . ' .mp-stacks-grid-orderby-select:hover, #mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-sort-select:hover{';	
			
			//If the button should have a background
			if ( $enable_button_background ){
				
				//Set the background color upon hover
				$button_bg_color_hover = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope_nav_btn_bg_color_hover' );	
				$css_output .= mp_core_css_line( 'background-color', $button_bg_color_hover );		
						
			}
			//If the button background should be hidden
			else{
				$css_output .= 'background:transparent;';
			}
							
		$css_output .= '}';	
		
		//Isotope Button TEXT
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-button .mp-stacks-grid-isotope-btn-text, #mp-brick-' . $post_id . ' .mp-stacks-grid-orderby-select, #mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-sort-select{';	
			
			//If the button text should be shown
			$enable_button_text = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope_nav_btn_text', true );	
			if ( $enable_button_text ){
				
				$button_text_color = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_nav_btn_text_color' );	
				$css_output .= mp_core_css_line( 'color', $button_text_color );		
				
				$css_output .= mp_core_css_line( 'font-size', $button_text_size, 'px' );		
						
			}
			//If the button text should be hidden
			else{
				$css_output .= mp_core_css_line( 'text-indent', '-99999999px' );		
				$css_output .= mp_core_css_line( 'overflow', 'hidden' );		
			}
	
		$css_output .= '}';
		
		//Isotope Button TEXT HOVER
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-button:hover .mp-stacks-grid-isotope-btn-text, #mp-brick-' . $post_id . ' .mp-stacks-grid-orderby-select:hover .mp-stacks-grid-isotope-btn-text, #mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-sort-select:hover .mp-stacks-grid-isotope-btn-text{';	
			
			//If the button text should be shown
			if ( $enable_button_text ){
				
				//Text hover color
				$button_text_color_hover = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope_nav_btn_text_color_hover' );	
				$css_output .= mp_core_css_line( 'color', $button_text_color_hover );		
						
			}
	
		$css_output .= '}';
			
		//Isotope Button ICONS
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-icon{';	
			
			//If the button icons should be shown
			$enable_button_icons = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope_nav_btn_icons', true );	
			if ( $enable_button_icons ){
				
				//Isotope Icon Alignment on the button - how should the icon sit in relation to the text?
				$button_icon_alignment = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_nav_btn_icons_alignment', 'left' );
				
				//If the icon should be on the left, put a margin on the right
				if ( $button_icon_alignment == 'left' && $enable_button_text){
					$css_output .= 'margin-right:10px;';
				}
				//If the icon should be on the right, put a margin on the left
				else if( $button_icon_alignment == 'right'&& $enable_button_text ){
					$css_output .= 'margin-left:10px;';
				}
				
				//Icon Color
				$button_icon_color = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_nav_btn_icons_color', '#fff' );	
				$css_output .= mp_core_css_line( 'color', $button_icon_color );		
				
				//Icon Size
				$button_icon_size = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_nav_btn_icons_size', 14 );	
				$css_output .= mp_core_css_line( 'font-size', $button_icon_size, 'px' );
				
				//Icon Width and height
				$css_output .= mp_core_css_line( 'width', $button_icon_size, 'px' );	
				$css_output .= mp_core_css_line( 'height', $button_icon_size, 'px' );	
						
						
			}
				
		$css_output .= '}';	
		//Isotope "All" Icon styling
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-icon-all{';	
		
			//If the button icons should be shown
			if ( $enable_button_icons ){
				//Icon Size
				$css_output .= mp_core_css_line( 'width', $button_icon_size, 'px' );	
				$css_output .= mp_core_css_line( 'height', $button_icon_size, 'px' );			
			}
			
		$css_output .= '}';	
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-icon-all .mp-stacks-grid-isotope-icon-all-1,';
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-icon-all .mp-stacks-grid-isotope-icon-all-2,';
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-icon-all .mp-stacks-grid-isotope-icon-all-3,';
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-icon-all .mp-stacks-grid-isotope-icon-all-4{';
			//If the button icons should be shown
			if ( $enable_button_icons ){
					
				$css_output .= mp_core_css_line( 'background-color', $button_icon_color );
			
			}
		$css_output .= '}';	
		
		//"Filter By" Color and Font Size
		$filter_by_color = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_filter_by_color' );
		$filter_by_size = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_filter_by_textsize', 15 );
		
		$css_output .= '#mp-brick-' . $post_id . ' .mp-stacks-grid-isotope-filterby-text{';	
			$css_output .= mp_core_css_line( 'color', $filter_by_color );	
			$css_output .= mp_core_css_line( 'font-size', $filter_by_size, 'px' );	
		$css_output .= '}';	
		
		
		
		return $css_output;
						
	}
		
}

/**
 * Output the "masonry_grid_postid = true Javascript in the footer for each grid.
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $existing_grid_output String - All the output currently in the variable for this grid
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @return   $new_grid_output - the existing grid output with additional thigns added by this function.
*/
function mp_stacks_grid_masonry_isotope_js( $existing_grid_output, $post_id, $meta_prefix ){
			
	$additional_output = NULL;
	
	//Check if we should apply Masonry to this grid
	$postgrid_masonry = mp_core_get_post_meta( $post_id, $meta_prefix . '_masonry' );
	//Check whether Isotope Sorting should be on
	$postgrid_isotope = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope', false );

	//If we should apply Masonry to this grid
	if ( $postgrid_masonry || $postgrid_isotope ){
						
		$additional_output .= '
		<script type="text/javascript">
			var masonry_grid_' . $post_id . ' = true;
		</script>';
					
	}
	else{
		
		//Set Masonry Variable to False so we know not to refresh masonry upon ajax
		$additional_output .= '
		<script type="text/javascript">
			var masonry_grid_' . $post_id . ' = false;
		</script>';	
	}	
	
	
	return $existing_grid_output . $additional_output;
}
add_filter( 'mp_stacks_grid_js', 'mp_stacks_grid_masonry_isotope_js', 10, 3 );

/**
 * Output the isotope/masonry class name in the grid main container if needed.
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $html_output_so_far String - The HTML output that has been appending for the grid at this point
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @return   $html_output_so_far String The Class names for the main grid containers with the isotope/masonry class name added
*/
function mp_stacks_grid_add_isotope_class( $classes, $post_id, $meta_prefix ){
		
	//Check if we should apply Masonry to this grid
	$masonry = mp_core_get_post_meta( $post_id, $meta_prefix . '_masonry' );
	//Check whether Isotope Sorting should be on
	$isotope = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope', false );
	
	if ( $isotope || $masonry ) {
			
		$classes .= ' mp-stacks-grid-isotope';
	}
	
	return $classes;
}
add_filter( 'mp_stacks_grid_classes', 'mp_stacks_grid_add_isotope_class', 10, 3 );

/**
 * Output the isotope/masonry layout mode so the grid knows how to layout the items. This uses isotope's "layoutMode'.
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $attributes String - any attributes that have been created thus far for the grid div. 
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @return   $attributes String - any attributes that have been created thus far for the grid div. 
*/
function mp_stacks_grid_layout_mode( $attributes, $post_id, $meta_prefix ){
		
	//Get the layout mode 
	$layout_mode = mp_core_get_post_meta( $post_id, $meta_prefix . '_isotope_layout_mode', 'masonry' );
	$layout_mode = apply_filters( 'mp_stacks_grid_isotope_layout_mode', $layout_mode, $post_id );
	
	//Check if we should apply Masonry to this grid
	$masonry = mp_core_get_post_meta( $post_id, $meta_prefix . '_masonry' );
	//Check whether Isotope Sorting should be on
	$isotope = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope', false );
	
	if ( $isotope || $masonry ) {
		
		return $attributes . ' layout_mode="' . $layout_mode . '" ';
	}
	else{
		return $attributes;
	}
}
add_filter( 'mp_stacks_grid_attributes', 'mp_stacks_grid_layout_mode', 10, 3 );

/**
 * Output the isotope/masonry class name in the grid main container if needed.
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $grid_item_attribute_string String - The string containing all additional attributes for each grid item.
 * @param    $sources_array Array - The Sources Repeater Array from which the Grid is populated.
 * @param    $grid_post_id int - The id of the WP post for which this function was called.
 * @param    $brick_id int - The id of the Brick from which this grid was created.
 * @param    $meta_prefix string - The meta prefix used by this grid. EG "socialgrid" or "postgrid"..etc
 * @param    $source_counter int - The source iteration number (in the sources_array) that the post in question was "born" from.
 * @return   $string String - A string that contains all of the attributes needed to match isotope sorting for all categories for this single grid item.
*/ 
function mp_stacks_grid_item_isotope_attributes( $grid_item_attribute_string, $sources_array, $grid_post_id, $post_id, $meta_prefix, $source_counter ){
										
	$grid_item_attribute_string .= ' ' . mp_stacks_grid_item_isotope_filter_attributes( $sources_array, $grid_post_id, $post_id, $meta_prefix, $source_counter );
	
	return $grid_item_attribute_string;
	
}
add_filter( 'mp_stacks_grid_attribute_string', 'mp_stacks_grid_item_isotope_attributes', 10, 6 );

/**
 * Output the isotope html filter buttons/dropdown menu  before the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $html_output_so_far String - The HTML output that has been appending for the grid at this point
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @param    $sources_array Array - The Sources Repeater Array from which the Grid is populated.
 * @return   $html_output_so_far String The output for the grid before this hook, with the isotope filtering HTML added.
*/
function mp_stacks_grid_isotope_filtering_buttons( $html_output_so_far, $post_id, $meta_prefix, $sources_array ){
				
	return $html_output_so_far . mp_stacks_grids_isotope_filtering_html( $post_id, $meta_prefix, $sources_array );

}
add_filter( 'mp_stacks_grid_before', 'mp_stacks_grid_isotope_filtering_buttons', 10, 4 );

/**
 * Make Isotope Filter buttons magically "appear" when they have something to filter. This is done by a js function being added to the post with new ajax posts which triggers display:block css for the buttons which were previously hidden.
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $html_output_so_far String - The HTML output that has been appending for the grid at this point
 * @param    $sources_array Array - The Sources Repeater Array from which the Grid is populated.
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @param    $grid_post_id int - The id of the WP post for which this function was called.
 * @param    $source_counter int - The source iteration number (in the sources_array) that the post in question was "born" from.
 * @return   $html_output_so_far String The output for the grid before this hook, with some javascript which shows any isotope filter buttons that are hidden and can control this grid item.
*/
function mp_stacks_grid_isotope_show_buttons_with_post_js( $html_output_so_far, $sources_array, $post_id, $meta_prefix, $grid_post_id, $source_counter ){
		
	return $html_output_so_far . mp_stacks_grid_isotope_show_buttons_with_posts( $sources_array, $post_id, $meta_prefix, $grid_post_id, $source_counter );	
							
}
add_filter( 'mp_stacks_grid_inside_grid_item_top', 'mp_stacks_grid_isotope_show_buttons_with_post_js', 10, 6 );

/**
 * Add the masonry item class to grid items that should have it.
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $grid_item_class_string String - The string containing all the classes for this grid item.
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @return   $html_output_so_far String The classes for the grid item with masonry/isotope classes added (if needed).
*/
function mp_stacks_isotope_masonry_grid_item_class( $grid_item_class_string, $post_id, $meta_prefix ){
	
	//Check if we should apply Masonry to this grid
	$masonry = mp_core_get_post_meta( $post_id, $meta_prefix . '_masonry' );
	//Check whether Isotope Sorting should be on
	$isotope = mp_core_get_post_meta_checkbox( $post_id, $meta_prefix . '_isotope', false );
	
	if ( $isotope || $masonry ) {

		$grid_item_class_string .= ' mp-stacks-grid-item-masonry';
		
	}
	
	return $grid_item_class_string;

}
add_filter( 'mp_stacks_grid_item_classes', 'mp_stacks_isotope_masonry_grid_item_class', 10, 3 );

/**
 * Set the orderby string based on the URL or the Brick's Meta settings.
 *
 * @access   public
 * @since    1.0.0
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @return   $orderby String The string which tells us how to order the posts in a grid
*/
function mp_stacks_grid_order_by( $post_id, $meta_prefix ){
	//If we are not doing ajax
	if ( !defined( 'DOING_AJAX' ) ){
		//Get any orderby params from the URL that might exist
		$orderby = isset( $_GET['mp_orderby_' . $post_id] ) ? sanitize_text_field( $_GET['mp_orderby_' . $post_id] ) : NULL;
		
		//If no orderby is set in the URL, get the default orderby setting saved to the brick
		$orderby = empty( $orderby ) ? mp_core_get_post_meta($post_id, $meta_prefix . '_default_orderby', 'date') : $orderby;	
	}
	//If we are doing ajax
	else{
		//Get the order by from the ajax POST
		$orderby = isset( $_POST['mp_stacks_grid_orderby'] ) ? $_POST['mp_stacks_grid_orderby'] : NULL;
		
		//If no orderby is set in the URL, get the default orderby setting saved to the brick
		$orderby = empty( $orderby ) ? mp_core_get_post_meta($post_id, $meta_prefix . '_default_orderby', 'date') : $orderby;	
		
	}
	
	return $orderby;
}

/**
 * Set the orderby string based on the URL or the Brick's Meta settings.
 *
 * @access   public
 * @since    1.0.0
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "postgrid"
 * @return   $return_html String The html for the dropdown where the user chooses the orderby options
*/
function mp_stacks_grid_orderby_output( $post_id, $meta_prefix ){
			
	$return_html = NULL;
	
	//Before we get to the isotope output, check if there are any orderby options first
	$orderby_options = mp_core_get_post_meta_multiple_checkboxes( $post_id, $meta_prefix . '_isotope_orderby_options', array() );
	
	//If orderby options have been set
	if ( is_array( $orderby_options ) && !empty( $orderby_options )){
		
		//Get any orderby params from the URL that might exist
		$url_order_by = isset( $_GET['mp_orderby_' . $post_id] ) ? sanitize_text_field( $_GET['mp_orderby_' . $post_id] ) : NULL;
		
		//If there isn't a URL orderby param, get the default orderby setting
		$default_orderby = empty( $url_order_by ) ? mp_core_get_post_meta($post_id, $meta_prefix . '_default_orderby', 'date') : $url_order_by;
			
		//Add an orderby dropdown menu before the first isotope dropdown
		$return_html .= '<select class="button mp-stacks-grid-orderby-select" id="mp-isotope-sort-select-' . $post_id . '" class="mp-stacks-grid-orderby-select" value="' . $default_orderby . '">';
			
			//Nicely named orderby options
			$nicelynamed_orderby_options = apply_filters( $meta_prefix . '_isotope_orderby_options', array(), $meta_prefix );
			
			//Add each orderby option to the output
			foreach( $orderby_options as $orderby_option ){
				
				$return_html .= '<option orderby_url="' . mp_core_add_query_arg( array( 'mp_orderby_' . $post_id => $orderby_option ), mp_core_get_current_url() ) . '" value="' . $orderby_option . '" ' . ( $default_orderby == $orderby_option ? ' selected' : NULL ) . ' >' . __( 'Order By: ', 'mp_stacks' ) . $nicelynamed_orderby_options[$orderby_option] . '</option>';	
				
			}
		
		$return_html .= '</select>';

	}	
	
	return $return_html;
}