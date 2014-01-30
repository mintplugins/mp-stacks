<?php 
/**
 * Functions for backwards compatibility
 *
 * @link http://moveplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
 /**
 * Update any "media_types" to "content_types" - Deprecated
 *
 * @access   public
 * @since     1.0.0
 * @link      http://moveplugins.com/doc/
 * @see      function_name()
 * @return    void
 */
function mp_stacks_migrate_media_to_content(){
 	
	//If this hasn't been done before
	if (!get_option('mp_stacks_migrate_media_to_content')){
		
		//Set the args for the new query
		$mp_brick_args = array(
			'post_type' => "mp_brick",
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);	
			
		//Create new query for stacks
		$mp_brick_query = new WP_Query( apply_filters( 'mp_brick_args', $mp_brick_args ) );
		
		$css_output = NULL;
		
		//Loop through the stack group		
		if ( $mp_brick_query->have_posts() ) { 
			
			add_action('admin_notices', 'mp_stacks_migrate_media_to_content_notification');
			
			while( $mp_brick_query->have_posts() ) : $mp_brick_query->the_post(); 
						
				//Build Brick CSS Output
				$post_id = get_the_ID();
								
				//Get media types
				$first_media_type = get_post_meta( $post_id, 'brick_first_media_type', true);
				$second_media_type = get_post_meta( $post_id, 'brick_second_media_type', true);
				
				//Reset media types to content types
				if (!empty($first_media_type)){
					update_post_meta( $post_id, 'brick_first_content_type', $first_media_type);
				}
				if (!empty($second_media_type)){
					update_post_meta( $post_id, 'brick_second_content_type', $second_media_type);
				}
								
							
			endwhile;
									
		}
		
		//Let this function know we don't have to do this again
		update_option( 'mp_stacks_migrate_media_to_content', true );
	}
	
}
add_action('admin_init', 'mp_stacks_migrate_media_to_content');

/**
 * Notice about updates
 *
 * @access   public
 * @since     1.0.0
 * @link      http://moveplugins.com/doc/
 * @see      function_name()
 * @return    void
 */
function mp_stacks_migrate_media_to_content_notification(){
	echo ' <div class="updated">';
	echo "Migrating media types to content types...";	
	echo '</div>';
}
