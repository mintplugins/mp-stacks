<?php
/**
 * This file contains functions for adding and saving settings on each Single Stack's Edit page.
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package     MP Stacks
 * @subpackage  Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */

/**
 * Create the WP Admin page where Stacks will be Managed: The Single Stack Edit Page
 *
 * @since    1.0.0
 * @return   void
*/
function mp_stacks_single_stack_edit_page(){
	
	// Single Stack Edit Page
	add_dashboard_page(
		__( 'Single Stack Edit Page', 'mp_stacks' ),
		__( 'Single Stack Edit Page', 'mp_stacks' ),
		'edit_pages',
		'mp-stacks-single-stack-edit-page',
		'mp_stacks_single_stack_edit_page_contents'
	);
}
add_action( 'admin_menu', 'mp_stacks_single_stack_edit_page' );

/**
 * Hide the actual button in the sidebar menu for the Single Stack Edit Page
 *
 * @since    1.0.0
 * @return   void
*/	
function mp_stacks_hide_single_stack_edit_page_from_wp_menu(){
	remove_submenu_page( 'index.php', 'mp-stacks-single-stack-edit-page' );
}
add_action( 'admin_head', 'mp_stacks_hide_single_stack_edit_page_from_wp_menu' );

/**
 * The contents of the Singe Stack Edit Page
 *
 * @since    1.0.0
 * @return   void
*/	
function mp_stacks_single_stack_edit_page_contents(){
	
	//Get the Stack ID we are managing on this page
	$stack_id = isset( $_GET['stack_id'] ) ? $_GET['stack_id'] : false;
	
	//If no Stack ID has been defined, output error and quit
	if ( !$stack_id ){
		?>
		<div class="wrap about-wrap">
        <h2><?php printf( __( 'Oops! You need to select a Stack to Manage it.', 'mp_stacks' ) ); ?></h2>	
        </div>
        <?php
		
		return false;
	}
	
	//If a Stack ID has been defined, continue onwards!
	
	$stack_info = get_term( $stack_id, 'mp_stacks' );
	
	?>
	<div class="wrap">
        <h2><?php printf( __( 'Edit Stack', 'mp_stacks' ) ); ?></h2>
        
        <form id="mp_stack_single_stack_update" method="post">
        <table class="form-table">
            <tr class="form-field form-required">
                <th scope="row"><label for="name"><?php _e('Name', 'mp_stacks'); ?></label></th>
                <td><input name="mp_stack_name" id="mp_stack_name" type="text" value="<?php if ( isset( $stack_info->name ) ) echo esc_attr($stack_info->name); ?>" size="40" aria-required="true" />
                <p class="description"><?php _e('The name is only used to help you remember what the Stack is intended for.'); ?></p></td>
            </tr>
             <tr class="form-field form-required">
                <th scope="row"><label for="mp-stacks-manage"><?php _e('Manage Bricks', 'mp_stacks'); ?></label></th>
                <td>
                	<div class="mp-stacks-permalink">
                   		<div class="mp-stacks-permalink-prefix"><?php echo '<a class="button" href="' . get_bloginfo('wpurl') . '/stack/' . $stack_info->slug . '">' . __( 'Manage Bricks in this Stack', 'mp_stacks' ) . '</a>'; ?></div>
                    </div>
            	</td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="mp_stack_slug"><?php _e('Permalink', 'mp_stacks'); ?></label></th>
                <td>
                	<div class="mp-stacks-permalink">
                   		<div class="mp-stacks-permalink-prefix"><?php echo '<a href="' . get_bloginfo('wpurl') . '/stack/' . $stack_info->slug . '">' . get_bloginfo('wpurl') . '/stack/</a>'; ?></div>
                        <input class="mp-stacks-permalink-slug" name="mp_stack_slug" id="mp_stack_slug" type="text" value="<?php if ( isset( $stack_info->slug ) ) echo esc_attr($stack_info->slug); ?>" size="40" aria-required="true" />
                    </div>
                <p class="description"><?php _e('Along with being placed inside pages and posts, each Stack also has its own unique URL.'); ?></p></td>
            </tr>
        	<tr class="form-field">
                <th scope="row"><label for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
                <td><textarea name="mp_stack_description" id="mp_stack_description" rows="5" cols="50" class="large-text"><?php echo $stack_info->description; // textarea_escaped ?></textarea><br />
                <p class="description"><?php _e('The description is not prominent by default, but is useful in remembering what a Stack is intended for.'); ?></p></td>
            </tr>
      		<tr class="form-field">
                <th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Refresh Imported Stack'); ?></label></th>
                <td>
                    <input type="text" name="old_stack_id" id="old_stack_id" size="3" style="width:60%;" value="" placeholder="<?php echo __( 'Old Stack ID', 'mp_stacks' ); ?>"><br />
                    <span class="description"><?php _e('If you imported this stack using the WordPress importer, enter the old stack ID to refresh the bricks in this stack:'); ?></span>
                    
                    <input type="hidden" name="new_stack_id" id="new_stack_id" size="3" style="width:60%;" value="<?php echo $stack_info->term_id; ?>"><br />
                </td>
            </tr>
        </table>
      	
        <input type="hidden" name="mp_stack_id" id="mp_stack_id" size="3" value="<?php echo $stack_info->term_id; ?>">
        
        <?php 
		wp_nonce_field('mp_stacks_single_stack_edit_page_update','mp_stack_single_stack_edit_page_nonce_field');
		submit_button( __( 'Update Stack', 'mp_stacks' ) ); 
		?>
        </form>
        
    </div>
    <?php
}

/**
 * This notice shows when we've updated a Stack from the Single Stack Edit Page.
 *
 * @since    1.0.0
 * @return   void
*/
function mp_stacks_stack_updated_notice(){
	
	global $mp_stacks_options;
	
	if ( isset( $mp_stacks_options['just_updated_stack'] ) ){
		?>
        <div class="updated">
        	<p>
			<?php echo __( 'Successfully updated', 'mp_stacks' ) . '"' .  $mp_stacks_options['just_updated_stack'] . '"'; ?>
            </p>
        </div><?php
	}
}
add_action( 'admin_notices', 'mp_stacks_stack_updated_notice' );

/**
 * Upon saving the Single Stack Edit Page, this fires. Lets save the updated data for the Stack.
 *
 * @since    1.0.0
 * @return   void
*/
function mp_stacks_update_stack(){
	
	global $mp_stacks_options;
	
	//If we are saving a Stack
	if ( 
		(isset( $_POST['mp_stack_name'] ) || isset( $_POST['mp_stack_slug'] ) || isset( $_POST['mp_stack_description'] ) ) 
		&& check_admin_referer( 'mp_stacks_single_stack_edit_page_update', 'mp_stack_single_stack_edit_page_nonce_field' )
		&& isset( $_POST['mp_stack_id'] ) ) 
	{
		
		//Update the Stack
		wp_update_term( $_POST['mp_stack_id'], 'mp_stacks', array(
		  'name' => sanitize_text_field( $_POST['mp_stack_name'] ),
		  'slug' => sanitize_title( $_POST['mp_stack_slug'] ),
		  'description' => sanitize_text_field( $_POST['mp_stack_description'] )
		));
		
		$mp_stacks_options['just_updated_stack'] = $_POST['mp_stack_name'];
		
	}
}
add_action( 'admin_init', 'mp_stacks_update_stack' );

/**
 * Upon saving the Single Stack Edit Page, this fires specifically to handle the "Refresh Imported Stack" option.
 *
 * @since    1.0.0
 * @return   void
*/
function mp_stacks_change_stack_id(){
	
	if ( !isset( $_POST['new_stack_id'] ) || !isset( $_POST['old_stack_id'] ) ){
		return;	
	}
	
	//Set the args for the new query
	$mp_stacks_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
		'order' => 'ASC',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'mp_stacks',
				'field'    => 'id',
				'terms'    => array( $_POST['new_stack_id'] ),
				'operator' => 'IN'
			)
		)
	);	
		
	//Create new query for stacks
	$mp_stack_query = new WP_Query( apply_filters( 'mp_stacks_args', $mp_stacks_args ) );
	
	//Loop through the stack group		
	if ( $mp_stack_query->have_posts() ) { 
		
		while( $mp_stack_query->have_posts() ) : $mp_stack_query->the_post(); 
			
			$post_id = get_the_ID();
			
			//Find the old stack order value
			$old_stack_order_value = get_post_meta( $post_id, 'mp_stack_order_' . $_POST['old_stack_id'], true );
			
			//Update stack order to use the new stack id and old stack order value
			update_post_meta( $post_id, 'mp_stack_order_' . $_POST['new_stack_id'], $old_stack_order_value );
			
			//Delete old stack order meta value
			delete_post_meta( $post_id, 'mp_stack_order_' . $_POST['old_stack_id'] );
			
			
		endwhile;
		
	}
	
}
add_action( 'admin_init', 'mp_stacks_change_stack_id' );