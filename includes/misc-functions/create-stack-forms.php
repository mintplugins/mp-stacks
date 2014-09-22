<?php
/**
 * Shortcode which is used to display the HTML content on a post
 */
function mp_stacks_display_mp_stack( $atts ) {
	
	$vars = shortcode_atts( array('stack' => NULL), $atts );
		
	//Return the stack HTML output - pass the function the stack id
	return mp_stack( $vars['stack'] );
}
add_shortcode( 'mp_stack', 'mp_stacks_display_mp_stack' );

/**
 * Show "Insert Shortcode" above posts
 */
function mp_stacks_show_insert_shortcode(){
		
	$args = array(
		'shortcode_id' => 'mp_stack',
		'shortcode_title' => __('Stack', 'mp_stacks'),
		'shortcode_description' => __( 'Use the form below to insert the shortcode for your Stack:', 'mp_stacks' ),
		'shortcode_icon_spot' => true,
		'shortcode_options' => array(
			array(
				'option_id' => 'stack',
				'option_title' => 'Choose an existing Stack',
				'option_description' => 'Select the stack you wish to display and then click "Insert Stack"',
				'option_type' => 'select',
				'option_value' => mp_core_get_all_terms_by_tax('mp_stacks'),
			),
		)
	); 
		
	//Shortcode args filter
	$args = has_filter('mp_stacks_insert_shortcode_args') ? apply_filters('mp_stacks_insert_shortcode_args', $args) : $args;
	
	new MP_CORE_Shortcode_Insert($args);	
}
add_action('init', 'mp_stacks_show_insert_shortcode');

/**
 * Add "Make New Stack" button before shortcode inserter
 */
function mp_stacks_shortcode_make_new_stack(){
	?>
    <div class="mp-stacks-shortcode-container">
        <div class="mp-stacks-shortcode-choose-action">
            <div class="mp-stacks-shortcode-action-choice new-stack">
                <div class="mp-stacks-new-stack-action-icon"></div>
                <div class="mp-stacks-new-stack-action"><?php echo __( 'Make A New Stack', 'mp_stacks' ); ?></div>
            </div>
            <div class="mp-stacks-shortcode-action-choice existing-stack">
                <div class="mp-stacks-existing-stack-action-icon"></div>
                <div class="mp-stacks-existing-stack-action"><?php echo __( 'Insert an Existing Stack', 'mp_stacks' ); ?></div>
            </div>
        </div>
        <div class="mp-stacks-shortcode-new-stack-div">
            <h1><?php echo __('Make A New Stack.', 'mp_stacks'); ?></h1>
            
            <div class="mp-stacks-shortcode-option">
                    
                <div class="mp_title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('1. New Stack\'s Name', 'mp_stacks'); ?></strong> <em><?php echo __('Enter a name for your new stack to create one now', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <input class="mp-stacks-new-stack-input" name="new_stack_name" placeholder="<?php echo __('Your new Stack\'s name', 'mp_stacks'); ?>"/>
            
            </div>
            
            <div class="mp-stacks-shortcode-option">
            
                <div class="mp_title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('2. Creation Options', 'mp_stacks'); ?></strong> <em><?php echo __('Choose one of the following options', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <div class="mp-stacks-new-stack-source-type-container">
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value=""><?php echo __( 'New - Fresh, Blank Stack.', 'mp_stacks' ); ?></div>
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value="duplicate-stack-option"><?php echo __( 'New - Exact Duplicate of Existing Stack.', 'mp_stacks' ); ?></div>
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value="template-stack-option"><?php echo __( 'New - Create Stack from Template.', 'mp_stacks' ); ?></div>
                </div>
                
            </div>
            
            <div class="mp-stacks-shortcode-option duplicate-stack-option">
             
                <div class="mp_title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('3. Choose which Stack to Duplicate', 'mp_stacks'); ?></strong> <em><?php echo __('Choose one of the following options', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <select class="mp-stacks-new-stack-duplicate-stack" name="new_stack_duplicate_stack" />
                    <?php 
                    //Get all Stacks	
                    $all_stacks = mp_core_get_all_terms_by_tax('mp_stacks');
                    
                    //Populate the dropdown with a list of all stacks
                    foreach( $all_stacks as $stack_id => $stack_value ){
                        ?><option value="<?php echo $stack_id; ?>"><?php echo $stack_value; ?></option><?php
                    }
                    ?>
                </select>
            
            </div>
           
            <div class="mp-stacks-shortcode-option template-stack-option">
            
                <div class="mp_title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('3. Choose A Stack Template', 'mp_stacks'); ?></strong> <em><?php echo __('Your installed templates:', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <?php //When the user selects one of the following options, we add "mp-stacks-new-stack-template" as a class to that li through js ?>
                <ul class="mp-stacks-installed-templates" name="new_stack_stack_template_select" />
                    <?php 
                    
                    //Get all Stack Template
                    $stack_templates = apply_filters( 'mp_stacks_installed_templates', array() );
                    
                    //Populate the dropdown with a list of all stacks
                    foreach( $stack_templates as $stack_template_function_name => $stack_template ){
                        ?><li stack-template-slug="<?php echo $stack_template['template_slug'] ; ?>" template_preview_img="<?php echo $stack_template['template_preview_img']; ?>">						
                        	<div class="mp-stacks-installed-template-function-name"><?php echo $stack_template_function_name; ?></div>	
                        	<div class="mp-stacks-installed-template-preview-img"><img src="<?php echo $stack_template['template_preview_img']; ?>"/></div>
							<div class="mp-stacks-installed-template-title"><?php echo $stack_template['template_title']; ?></div>
                            
                            <div class="mp-stacks-installed-template-tag">
								<?php $template_tags = explode( ',', $stack_template['template_tags'] ); 
                                
                                foreach( $template_tags as $template_tag ){?>
                                    <div class="template-tag-container">
                                        <div class="template-tag-title"><?php echo $template_tag ?></div>
                                    </div>
                                <?php } ?>
                           </div>
                            
                          </li>
						<?php
                    }
                    ?>
                </ul>
            
            </div>
            
            <a class="button mp-stacks-new-stack-button button-primary"><?php echo __('Make A New Stack', 'mp_stacks'); ?></a>
            <a id="mp_stack_cancel_download_insert" class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'mp_stacks' ); ?>"><?php _e( 'Cancel', 'mp_stacks' ); ?></a>
        </div>
        <div class="mp-stacks-shortcode-existing-stack-div">
            <h1><?php echo __('Already have the stack you wish to use?', 'mp_stacks'); ?></h1>
                
                <div class="mp_title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('Confirm Insert', 'mp_stacks'); ?></strong> <em><?php echo __('Please note: You are not creating a new stack. Rather, you are inserting an existing one. This is really only useful if you have removed a stack from a page and need to re-add it. To confirm you understand, please type exactly "This is NOT a new Stack" (case sensitive) into the box below.', 'mp_stacks'); ?></em>
                    </label>
                </div>
              
                <input class="mp-stacks-insert-stack-confirmation" name="insert_stack_confirmation" placeholder="<?php echo __('Type confirmation message listed above...', 'mp_stacks'); ?>"/>
        
            <?php
}
add_action('mp_core_before_' . 'mp_stack' . '_shortcode_output' , 'mp_stacks_shortcode_make_new_stack');

function mp_stacks_shortcode_after(){
		echo '</div>';
	echo '</div>';
}
add_action('mp_core_after_' . 'mp_stack' . '_shortcode_output' , 'mp_stacks_shortcode_after');
