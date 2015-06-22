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
function mp_stacks_show_insert_shortcode( $current_screen = false ){
		
	if ( !empty( $current_screen ) && $current_screen->post_type == 'mp_brick' ){
		return;
	}
	
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
add_action('current_screen', 'mp_stacks_show_insert_shortcode');
add_action('mp_core_metabox_ajax_shortcode_init', 'mp_stacks_show_insert_shortcode');

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
                 <div class="mp-stacks-new-stack-action-description"><?php echo __( '(New Blank, Duplicate Existing, or use a Stack Template)', 'mp_stacks' ); ?></div>
            </div>
            <div class="mp-stacks-shortcode-action-choice existing-stack">
                <div class="mp-stacks-existing-stack-action-icon"></div>
                <div class="mp-stacks-existing-stack-action"><?php echo __( 'Insert an Existing Stack', 'mp_stacks' ); ?></div>
            </div>
        </div>
        <div class="mp-stacks-shortcode-new-stack-div">
            <h1><?php echo __('Make A New Stack.', 'mp_stacks'); ?></h1>
            
            <div class="mp-stacks-new-stack-option">
                    
                <div class="mp-stacks-new-stack-option-title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('1. New Stack\'s Name', 'mp_stacks'); ?></strong> <em><?php echo __('Enter a name for your new stack to create one now:', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <input class="mp-stacks-new-stack-input" name="new_stack_name" placeholder="<?php echo __('Your new Stack\'s name', 'mp_stacks'); ?>"/>
            
            </div>
            
            <div class="mp-stacks-new-stack-option">
            
                <div class="mp-stacks-new-stack-option-title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('2. Creation Options', 'mp_stacks'); ?></strong> <em><?php echo __('Choose one of the following options', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <div class="mp-stacks-new-stack-source-type-container">
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value="" checked="checked"><?php echo __( 'New - Fresh, Blank Stack.', 'mp_stacks' ); ?></div>
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value="duplicate-stack-option"><?php echo __( 'New - Exact Duplicate of Existing Stack.', 'mp_stacks' ); ?></div>
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value="template-stack-option"><?php echo __( 'New - Create Stack from Template.', 'mp_stacks' ); ?></div>
                </div>
                
            </div>
            
            <div class="mp-stacks-new-stack-option duplicate-stack-option">
             
                <div class="mp-stacks-new-stack-option-title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('3. Choose which Stack to Duplicate', 'mp_stacks'); ?></strong> <em><?php echo __('Here are your existing Stacks:', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <?php //When the user selects one of the following options, we add "mp-stacks-selected-stack-for-duplication" as a class to that li through js ?>
                <ul class="mp-stacks-new-stack-duplicate-stack" />
                    <?php
					                   
              		//Get all Stacks	
                    $all_stacks = mp_core_get_all_terms_by_tax('mp_stacks');
                    
                    //Populate the dropdown with a list of all stacks
                    foreach( $all_stacks as $stack_id => $stack_value ){
                        
						$this_stack_info = get_term( $stack_id, 'mp_stacks' );					
					
						?><li>	
                        	<div class="mp-stacks-duplicate-button">					
                                <div class="mp-stacks-id"><?php echo $stack_id; ?></div>	
                                <div class="mp-stacks-duplicate-icon"></div>
                                <div class="mp-stacks-installed-template-title"><?php echo $stack_value; ?></div>
                            </div>
                            
                            <div class="mp-stacks-duplicate-preview-container">
                             	<a href="<?php echo get_bloginfo( 'wpurl' ) . '/stack/' . $this_stack_info->slug; ?>" class="mp-stacks-iframe-custom-width-height" mfp-width="90%" mfp-height="90%">
                                    <div class="mp-stacks-duplicate-preview">Preview</div>
                                    <div class="mp-stacks-duplicate-preview-icon"></div>
                                </a>
                            </div>
                                    
                          </li>
                          
                          <div class="mp-stacks-clearedfix"></div>
						<?php
                    }
                    ?>
                </ul>
            
            </div>
           
            <div class="mp-stacks-new-stack-option template-stack-option">
            
                <div class="mp-stacks-new-stack-option-title">
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
                
                <div class="mp-stacks-new-stack-option-title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('Confirm Insert', 'mp_stacks'); ?></strong> <em><?php echo __('WARNING: You are not creating a new Stack. Rather, you are inserting an existing one. This is really only useful if you have removed a stack from a page and need to re-add it. To confirm you understand, please type exactly "WARNING: I understand this is NOT a new Stack and changes made to it will reflect on ALL pages containing this Stack" (case sensitive) into the box below.', 'mp_stacks'); ?></em>
                    </label>
                </div>
              
                <textarea class="mp-stacks-insert-stack-confirmation" name="insert_stack_confirmation" placeholder="<?php echo __('Type confirmation message listed above...', 'mp_stacks'); ?>"/></textarea>
        
            <?php
}
add_action('mp_core_before_' . 'mp_stack' . '_shortcode_output' , 'mp_stacks_shortcode_make_new_stack');

function mp_stacks_shortcode_after(){
		echo '</div>';
	echo '</div>';
}
add_action('mp_core_after_' . 'mp_stack' . '_shortcode_output' , 'mp_stacks_shortcode_after');

/**
 * Show "Create New Stack" on "Manage Stacks" page
 */
function mp_stacks_create_new_stack_form(){
	$current_screen = get_current_screen();
	//print_r($current_screen);
	?>
	<div class="mp-stacks-manage-page-new-stack-div">
            <h3 class="mp-stacks-new-stack-heading"><?php echo __('Make A New Stack.', 'mp_stacks'); ?></h3>
            
            <div class="mp-stacks-new-stack-option mp-stacks-new-stack-option-name">
                    
                <div class="mp-stacks-new-stack-option-title">
                    <label for="mp_stack_stack">
                        <?php echo __('1. New Stack\'s Name', 'mp_stacks'); ?> <em><?php echo __('Enter a name for your new stack to create one now:', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <input class="mp-stacks-new-stack-input" name="new_stack_name" placeholder="<?php echo __('Your new Stack\'s name', 'mp_stacks'); ?>"/>
            
            </div>
            
            <div class="mp-stacks-new-stack-option mp-stacks-new-stack-creation-options">
            
                <div class="mp-stacks-new-stack-option-title">
                    <label for="mp_stack_stack">
                        <?php echo __('2. Creation Options', 'mp_stacks'); ?> <em><?php echo __('Choose one of the following options:', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <div class="mp-stacks-new-stack-source-type-container">
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value="" checked="checked"><?php echo __( 'New - Fresh, Blank Stack.', 'mp_stacks' ); ?></div>
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value="duplicate-stack-option"><?php echo __( 'New - Exact Duplicate of Existing Stack.', 'mp_stacks' ); ?></div>
                    <div class="mp-stacks-new-stack-source-type"><input type="radio" name="new_stack_source_type" value="template-stack-option"><?php echo __( 'New - Create Stack from Template.', 'mp_stacks' ); ?></div>
                </div>
                
            </div>
            
            <div class="mp-stacks-new-stack-option duplicate-stack-option">
             
                <div class="mp-stacks-new-stack-option-title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('3. Choose which Stack to Duplicate', 'mp_stacks'); ?></strong> <em><?php echo __('Here are your existing Stacks:', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <?php //When the user selects one of the following options, we add "mp-stacks-selected-stack-for-duplication" as a class to that li through js ?>
                <ul class="mp-stacks-new-stack-duplicate-stack" />
                    <?php 
                    
              		//Get all Stacks	
                    $all_stacks = mp_core_get_all_terms_by_tax('mp_stacks');
                    
                    //Populate the dropdown with a list of all stacks
                    foreach( $all_stacks as $stack_id => $stack_value ){
						
						$this_stack_info = get_term( $stack_id, 'mp_stacks' );				
						
                        ?><li>	
                        	<div class="mp-stacks-duplicate-button">					
                                <div class="mp-stacks-id"><?php echo $stack_id; ?></div>	
                                <div class="mp-stacks-duplicate-icon"></div>
                                <div class="mp-stacks-installed-template-title"><?php echo $stack_value; ?></div>
                            </div>
                            
                            <div class="mp-stacks-duplicate-preview-container">
                             	<a href="<?php echo get_bloginfo( 'wpurl' ) . '/stack/' . $this_stack_info->slug; ?>" class="mp-stacks-iframe-custom-width-height" mfp-width="90%" mfp-height="90%">
                                    <div class="mp-stacks-duplicate-preview">Preview</div>
                                    <div class="mp-stacks-duplicate-preview-icon"></div>
                                </a>
                            </div>
                                    
                          </li>
                          
                          <div class="mp-stacks-clearedfix"></div>
						<?php
                    }
                    ?>
                </ul>
            
            </div>
           
            <div class="mp-stacks-new-stack-option template-stack-option">
            
                <div class="mp-stacks-new-stack-option-title">
                    <label for="mp_stack_stack">
                        <?php echo __('3. Choose A Stack Template', 'mp_stacks'); ?> <em><?php echo __('Your installed templates:', 'mp_stacks'); ?></em>
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
        
        </div>
        <?php
}
add_action( 'mp_stacks_pre_add_form', 'mp_stacks_create_new_stack_form' );