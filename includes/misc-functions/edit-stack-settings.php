<?php
/**
 * This file contains functions for adding and saving settings on each Stack's edit page.
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
 * Add extra fields to Mp Stacks Single taxonomy edit form
 *
 * @since    1.0.0
 * @return   void
*/
function mp_stacks_show_custom_stack_id_field($tag) {
   //check for existing taxonomy meta for term ID
    $t_id = $tag->term_id;
	?>
	<tr class="form-field">
        <th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Refresh Imported Stack'); ?></label></th>
        <td>
            <input type="text" name="old_stack_id" id="old_stack_id" size="3" style="width:60%;" value="" placeholder="<?php echo __( 'Old Stack ID', 'mp_stacks' ); ?>"><br />
            <span class="description"><?php _e('If you imported this stack using the WordPress importer, enter the old stack ID to refresh the bricks in this stack:'); ?></span>
            
            <input type="hidden" name="new_stack_id" id="new_stack_id" size="3" style="width:60%;" value="<?php echo $tag->term_id; ?>"><br />
        </td>
	</tr>
	<?php
}
add_action( 'mp_stacks_edit_form_fields', 'mp_stacks_show_custom_stack_id_field', 10, 2);

/**
 * Upon saving the single tax page, this fires - we we save the new stack id
 *
 * @since    1.0.0
 * @return   void
*/
function mp_stacks_change_stack_id(){
	
	global $wpdb;
	
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
add_action( 'admin_init', 'mp_stacks_change_stack_id', 10, 2);