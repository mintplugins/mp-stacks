<?php 
/**
 * Functions Stack Only Page
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */

/**
 * Create Rewrite permalink rules for MP Stacks
 * Parameter: $rules the current rewrite rules from WP
 */
function mp_stacks_rewrites($rules){
			
		$new_rules = array(
	
			//stack/stack-slug
			"stack/([^/]*)/?$" => 'index.php?mp_stack_only_page=$matches[1]',

		);
		
		$new_rules = array_merge($new_rules, $rules);
		
		return $new_rules;
}
add_filter('rewrite_rules_array', 'mp_stacks_rewrites');

/**
 * Add Rewrite Tags for MP Stacks
 * Parameter: $rules the current rewrite rules from WP
 */
function mp_stacks_rewrite_tags(){
	
	add_rewrite_tag('%mp_stack_only_page%','([^/]*)');
	
	add_rewrite_tag('%mp_stacks_page_slug%','([^/]*)');
	
	add_rewrite_tag('%mp_brick_pagination_slugs%','([^/]*)');
	
	add_rewrite_tag('%mp_brick_pagination_page_numbers%','([^/]*)');
		
}
add_action('init', 'mp_stacks_rewrite_tags');

/**
 * Show the link on the "Manage Stacks" page to view this stack
 *
 * @since    1.0.0
 * @link       http://mintplugins.com/doc/
 * @see      function_name()
 * @param  array $args See link for description.
 * @return   void
 */
function mp_stacks_show_view_stack( $actions, $tag ){
	
	//Get the stack id
	$stack_slug = $tag->slug;
	 
	 //Add our new link to the array of row-actions
	$actions['view_stack'] = '<a target="_blank" href="' . get_bloginfo('wpurl') . '/stack/' . $stack_slug . '">' . __( 'View Stack', 'mp_stacks' ) . '</a>';
	 
	return $actions;
}
add_action( "mp_stacks_row_actions", 'mp_stacks_show_view_stack', 10, 2 );
 
/**
 * Function which return a template array for a stack
 * Parameter: Stack ID
 * Parameter: $args
 */
function mp_stack_only_page(){		
	
	global $wp_query;
	
	if ( isset($wp_query->query_vars['mp_stack_only_page']) ){
		
		$stack = get_term_by('slug', $wp_query->query_vars['mp_stack_only_page'], 'mp_stacks');
		
		?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo( 'charset' ); ?>" />
            <title><?php wp_title( '|', true, 'right' ); ?></title>
            <link rel="profile" href="//gmpg.org/xfn/11" />
            <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
            <!--[if lt IE 9]>
            <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
            <![endif]-->
            
            <?php mp_stack_css( $stack->term_id, true ); ?>
            
            <?php wp_head(); ?>
            
        </head>
        
        <body class="body-mp-stack-only-<?php echo $stack->term_id; ?>">
                
			<?php 
            
            	echo mp_stack( $stack->term_id );
            
            ?>

        <div style="display:none;">
            <?php wp_footer(); ?>
        </div>
        
        </body>
	</html>
    
    <?php
	
	die();
	
	}
}
add_action ('wp', 'mp_stack_only_page' );