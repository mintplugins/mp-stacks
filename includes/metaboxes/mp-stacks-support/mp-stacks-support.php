<?php
/**
 * Function which creates the admin notice for the mp_brick editor
 *
 */
function mp_stacks_support_admin_notice(){
	 
	 //Only load message if mp_stack_id is set
	 if ( !isset( $_GET['mp_stack_id'] ) ){
		return; 
	 }
	 
	 $stack_info = get_term( absint( $_GET['mp_stack_id'] ), 'mp_stacks' );
	 
	 ?>
	 <div class="updated">
        <p><?php echo '<img src="' . plugins_url( 'assets/images/mp_stack-icon-2x.png', dirname(__FILE__) ) . '" />' . __( 'You are editing a "Brick" in the "Stack" called "' . $stack_info->name . '".', 'mp_stacks'); ?>
		<em><?php echo __(' Having trouble? Feel free to email us: support@mintplugins.com. Estimated response time is 12-24 hours.', 'mp_stacks' ); ?></em></p>
     </div>
     <?php
	
	
	
}
add_action('admin_notices', 'mp_stacks_support_admin_notice');

/**
 * Function which create the admin notice for the mp_brick editor
 *
 */
function mp_stacks_double_click_tip(){
	 
	 //Only load message if mp_stack_id is set
	 if ( !isset( $_GET['mp_stack_id'] ) ){
		return; 
	 }
	 
	 $stack_info = get_term( absint( $_GET['mp_stack_id'] ), 'mp_stacks' );
	 
	 ?>
	 <div class="updated">
        <p><?php echo __( 'TIP: You can open this Brick editor at any time by double clicking <em>anywhere</em> on a brick.', 'mp_stacks'); ?>
     </div>
     <?php
	
	
	
}
add_action('admin_notices', 'mp_stacks_double_click_tip');