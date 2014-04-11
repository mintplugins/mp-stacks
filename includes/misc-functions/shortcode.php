<?php
/**
 * Enqueue stylesheet used for shortcode
 */
function mp_stacks_stylesheet(){
	//css
	wp_enqueue_style( 'mp_stacks_style', plugins_url('css/mp-stacks-style.css', dirname(__FILE__)) );
}
add_action('wp_enqueue_scripts', 'mp_stacks_stylesheet');

/**
 * Shortcode which is used to display the HTML content on a post
 */
function mp_stacks_display_mp_stack( $atts ) {
	global $mp_stacks_meta_box;
	$vars =  shortcode_atts( array('stack' => NULL), $atts );
		
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
                        <strong><?php echo __('New Stack\'s Name', 'mp_stacks'); ?></strong> <em><?php echo __('Enter a name for your new stack to create one now', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <input class="mp-stacks-new-stack-input" name="new_stack_name" placeholder="<?php echo __('Your new Stack\'s name', 'mp_stacks'); ?>"/>
            
            </div>
            
            <div class="mp-stacks-shortcode-option">
            
                <div class="mp_title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('Creation Options', 'mp_stacks'); ?></strong> <em><?php echo __('Choose one of the following options', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <select class="mp-stacks-new-stack-source-type" name="new_stack_source_type" />
                    <option value="">Default</option>
                    <option value="duplicate-stack-option">Duplicate Existing Stack</option>
                    <option value="template-stack-option">Create Stack from Template</option>
                </select>
                
            </div>
            
            <div class="mp-stacks-shortcode-option duplicate-stack-option">
             
                <div class="mp_title">
                    <label for="mp_stack_stack">
                        <strong><?php echo __('Choose which Stack to Duplicate', 'mp_stacks'); ?></strong> <em><?php echo __('Choose one of the following options', 'mp_stacks'); ?></em>
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
                        <strong><?php echo __('Choose A Stack Template', 'mp_stacks'); ?></strong> <em><?php echo __('Select the Stack Template', 'mp_stacks'); ?></em>
                    </label>
                </div>
                
                <select class="mp-stacks-new-stack-template" name="new_stack_stack_template_select" />
                    <?php 
                    
                    //Get all Stack Template
                    $stack_templates = apply_filters( 'mp_stacks_installed_templates', array() );
                    
                    //Populate the dropdown with a list of all stacks
                    foreach( $stack_templates as $stack_template_slug => $stack_template_title ){
                        ?><option value="<?php echo $stack_template_slug ; ?>"><?php echo $stack_template_title; ?></option><?php
                    }
                    ?>
                </select>
            
            </div>
            
            <a class="button mp-stacks-new-stack-button button-primary"><?php echo __('Make A New Stack', 'mp_stacks'); ?></a>
            <a id="mp_stack-cancel-download-insert" class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'mp_stacks' ); ?>"><?php _e( 'Cancel', 'mp_stacks' ); ?></a>
        </div>
        <div class="mp-stacks-shortcode-existing-stack-div">
            <h1><?php echo __('Already have the stack you wish to use?', 'mp_stacks'); ?></h1>
            <?php
}
add_action('mp_core_before_' . 'mp_stack' . '_shortcode_output' , 'mp_stacks_shortcode_make_new_stack');

function mp_stacks_shortcode_after(){
		echo '</div>';
	echo '</div>';
}
add_action('mp_core_after_' . 'mp_stack' . '_shortcode_output' , 'mp_stacks_shortcode_after');