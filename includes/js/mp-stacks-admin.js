jQuery(document).ready(function($){
	
	//Close up the side metaboxes upon page load
	$( '.post-type-mp_brick #side-sortables .postbox:not( #submitdiv, #mp_stacks_bg_metabox )' ).addClass( 'closed' );
	
	//Show only the content types the user has selected for this brick
	function mp_stacks_reset_content_types( args ){		
				
		default_args = new Array();
		default_args['content_type_num'] = false;
		
		args = args || default_args;
		
		//Show correct content type metaboxes by looping through each item in the 1st drodown
		var values = $("#mp_stacks_content_metabox .brick_first_content_type>option:selected").map(function() { 
			
			//If this is the "More Content Types..." option, open the Add-On Shop in a new tab
			if ( $(this).val() == 'more_content_types' ){
				$('.mp-stacks-more-content-types').remove();
				$(this).parent().after( '<a href="' + mp_stacks_vars.more_content_types + '" target="_blank" class="mp-stacks-more-content-types button" style="margin-left:10px">' + mp_stacks_vars.more_content_types_text + '</a>' );
			}
			else{
								
				//Jquery trigger which allows add-ons to make things happen upon change
				if ( args['content_type_num'] == 1 || !args['content_type_num'] ){	
					$(document).trigger( 'mp_stacks_content_type_change', [ content_type = $(this).val(), post_id = mp_stacks_getQueryVariable('post'), content_type_num = 1 ] );
					//Set this Content-Type to be un-pickable for the Second Content-Type
					if ( $(this).val() != 'none' && $(this).val() != '' ){
						$("#mp_stacks_content_metabox .brick_second_content_type")
							.children('option[value=' + $(this).val() + ']')
							.attr('disabled', true)
							.siblings().removeAttr('disabled');
					}
					else{
						$("#mp_stacks_content_metabox .brick_second_content_type")
							.children().removeAttr('disabled');
					}
					
					//Add the name of this content type to the metabox placeholder
					$( '#content_type_1_title' ).remove();
					$( '#mp_stacks_content_type_1_metabox h3' ).append( ' <span id="content_type_1_title">(' + $(this).html().trim() + ')</span>' );
	
				}
						
			}
		});
		
		//Show correct content type metaboxes by looping through each item in the 2nd drodown
		var values = $("#mp_stacks_content_metabox .brick_second_content_type>option:selected").map(function() { 
			
			//If this is the "More Content Types..." option, open the Add-On Shop in a new tab
			if ( $(this).val() == 'more_content_types' ){
				$('.mp-stacks-more-content-types').remove();
				$(this).parent().after( '<a href="' + mp_stacks_vars.more_content_types + '" target="_blank" class="mp-stacks-more-content-types button" style="margin-left:10px">Click here to get more Content-Types</a>' );
			}
			else{
	
				//Jquery trigger which allows add-ons to make things happen upon change
				if ( args['content_type_num'] == 2 || args['content_type_num'] == false ){
					$(document).trigger( 'mp_stacks_content_type_change', [ content_type = $(this).val(), post_id = mp_stacks_getQueryVariable('post'), content_type_num = 2 ] );
					//Set this Content-Type to be un-pickable for the First Content-Type
					if ( $(this).val() != 'none' && $(this).val() != '' ){
						$("#mp_stacks_content_metabox .brick_first_content_type")
							.children('option[value=' + $(this).val() + ']')
							.attr('disabled', true)
							.siblings().removeAttr('disabled');
					}
					else{
						$("#mp_stacks_content_metabox .brick_first_content_type")
							.children().removeAttr('disabled');
					}
					
					//Add the name of this content type to the metabox placeholder
					$( '#content_type_2_title' ).remove();
					$( '#mp_stacks_content_type_2_metabox h3' ).append( ' <span id="content_type_2_title">(' + $(this).html().trim() + ')</span>' );
		
				}
				
			}
			
		});
		
	}
	
	/**
	 * Set the Content Types that should be chosen when the page loads.
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */	
	$(document).ready(function(){
		var args = new Array();
		args['content_type_num'] = false;
		mp_stacks_reset_content_types(args);
	});
	
	//When the First Content-Type option is changed
	$('#mp_stacks_content_metabox .brick_first_content_type').change(function() {
		var args = new Array();
		args['content_type_num'] = 1;
		mp_stacks_reset_content_types( args );
		
		//Auto change to "centered" for Content-Types that should be centered by default
		if ( $(this).val() in mp_stacks_vars.centered_content_types ){
			$(".brick_alignment[value='centered']").prop("checked", true);
		}
	});
	
	//When the Second Content-Type option is changed
	$('#mp_stacks_content_metabox .brick_second_content_type').change(function() {
		var args = new Array();
		args['content_type_num'] = 2;
		mp_stacks_reset_content_types( args );
		
		//Auto change to "centered" for Content-Types that should be centered by default
		if ( $(this).val() in mp_stacks_vars.centered_content_types ){
			$(".brick_alignment[value='centered']").prop("checked", true);
		}
	});
	
	//Load in metabox fields/content through ajax 
	$( document ).on( 'mp_stacks_content_type_change', function( event, content_type, post_id, content_type_num ){
		
		//console.log( 'running ajax' + content_type );
		
		//Get the "other" content type
		if ( content_type_num == 1 ){
			var other_content_type = $('#mp_stacks_content_metabox .brick_second_content_type').val();	
			var other_content_type_num = 2;
		}
		else{
			var other_content_type = $('#mp_stacks_content_metabox .brick_first_content_type').val();
			var other_content_type_num = 1;	
		}
		
		//If no Content Type has been selected, or it is set to 'none'
		if ( content_type.length == 0 || content_type == 'none' ){
			//Write "None" In the placeholder metabox
			$( '#mp_stacks_content_type_' + content_type_num + '_metabox .inside' ).html( '(None)' );
		}
		//If a content-type has been selected
		else{
					
			//Put the loading animation into the placeholder
			$( '#mp_stacks_content_type_' + content_type_num + '_metabox .inside' ).html( '<div class="mp-core-loading-spinner"></div>' );
			
			var ajax_metabox_function_name = 'mp_stacks_' + content_type + '_metabox_content';					
			if ( ajax_metabox_function_name ){
				//Load in the metabox content via ajax
				var postData = {
					action: ajax_metabox_function_name,
					mp_core_metabox_ajax: true,
					mp_core_metabox_id_ajax: 'mp_stacks_' + content_type + '_metabox',
					mp_core_metabox_post_id: post_id
				};
				
				//Run the Ajax
				$.ajax({
					type: "POST",
					data: postData,
					dataType:"json",
					url: mp_stacks_vars.ajaxurl,
					success: function (response) {
																		
						//If the response is false (or "0"), The user likely needs to update their add-on
						if ( response == 0 ){
							$( '#mp_stacks_content_type_' + content_type_num + '_metabox .inside' ).html( 'You might need to update the Add-On for ' + content_type + '. Go to "Dashboard" > "Updates" in your WordPress.' );
						}
						//If we got a good response from ajax for this metabox
						else{
							
							//Replace the metabox content with the ajax response. This also includes the needed CSS and JS scripts for the metabox fields.
							mp_core_load_ajax_metabox_contents( response, '#mp_stacks_content_type_' + content_type_num + '_metabox' );
														
							//Trigger which allows add-ons to further maniulate metaboxes once the content-type has loaded.
							$(document).trigger( 'mp_stacks_content_type_change_complete', [ content_type, post_id, content_type_num ] );
						}
						
					}
				}).fail(function (data) {
					console.log(data);
				});
			}
		}
	});
	
	//Show the notice about which brick we are editing (if we don't show it this way, it does a weird "jump in" thing. Probably should create a better fix than this but it appears to be an issue with admin_notices in WordPress
	$('.mp-stacks-editor-title-notice').css('display', '');
			
	//Now that the page is loaded and our content types are reset properly, lets show the body div (hidden by css)
	$('.post-type-mp_brick #poststuff').css('opacity', '1');
	
	//Change the title from "Loading..." to "Add New Brick" or "Edit Brick"
	$('.post-new-php.post-type-mp_brick .wrap h2:first-child').html( mp_stacks_vars.add_new_brick_title );
	$('.post-php.post-type-mp_brick .wrap h2:first-child').html( mp_stacks_vars.edit_brick_title );
	
	/**
	 * When someone changes the "Screen Size" controller for the text Content-Type
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */	
	$( document ).on( 'click', 'div[class*="brick_text_screen_size_controllerBBBBB"] .brick_screen_size', function(){
				
		var screen_size = $(this).attr( 'mp_stacks_textsize_device' );
		var repeat_num_array = $(this).parent().parent().parent().parent().parent().attr( 'class' ).split("AAAAA");		
		var repeat_num = repeat_num_array[1].split("BBBBB");
		repeat_num = repeat_num[0];
		
		//Show the other "device" icon buttons (desktop, tablet, mobile etc) when clicked
		if ( $(this).parent().attr('mp-area-active' ) == 'closed' ){
			
			//Show all the device icons
			$(this).parent().attr('mp-area-active', 'open' );
			$( this ).parent().find( '.brick_screen_size' ).css( 'display', 'inline-block' );
			
		}
		//Hide the other "device" icon buttons (desktop, tablet, mobile etc) when clicked
		else{
			$(this).parent().attr('mp-area-active', 'closed' );
		
			//Show only the icon the user just picked
			$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_screen_size_controllerBBBBB .brick_screen_size' ).css( 'display', 'none' );
		
			$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_screen_size_controllerBBBBB .' + screen_size ).css( 'display', 'inline-block' );
			
			
			//Hide all text options temporarily
			$( this ).parent().parent().parent().parent().parent().parent().find( '.mp_brick_text_option' ).css( 'display', 'none' );
		
			//If the screen size is "desktop", that's our default so it's a bit different
			if ( screen_size == 'desktop' ){
				
				//Show the text controls for the selected screen size
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_color' + 'BBBBB' ).css( 'display', 'inline-block' );
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_font_size' + 'BBBBB' ).css( 'display', 'inline-block' );
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_line_height' + 'BBBBB' ).css( 'display', 'inline-block' );
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_paragraph_margin_bottom' + 'BBBBB' ).css( 'display', 'inline-block' );
				
				//Highlight those controls
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_colorBBBBB, ' + 				
				   '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_font_sizeBBBBB, ' + 
				   '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_line_heightBBBBB, ' + 
				   '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_paragraph_margin_bottom' + 'BBBBB '
				).animate({
					'background-color': '#12ff00',
				}, 500, function() {
					// Animation complete.
					$(this).animate({
						'background-color': '',
					}, 1000);
				});
			
			}
			else{
					
				//Show the text controls for the selected screen size
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_color_' + screen_size + 'BBBBB' ).css( 'display', 'inline-block' );
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_font_size_' + screen_size + 'BBBBB' ).css( 'display', 'inline-block' );
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_line_height_' + screen_size + 'BBBBB' ).css( 'display', 'inline-block' );
				$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_paragraph_margin_bottom_' + screen_size + 'BBBBB' ).css( 'display', 'inline-block' );
				
			}
		
			//Highlight those controls
			$( '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_color_' + screen_size + 'BBBBB, ' + 				
			   '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_font_size_' + screen_size + 'BBBBB, ' + 
			   '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_line_height_' + screen_size + 'BBBBB, ' + 
			   '.mp_field_mp_stacks_singletext_content_type_repeaterAAAAA' + repeat_num + 'BBBBBAAAAAbrick_text_paragraph_margin_bottom_' + screen_size + 'BBBBB '
			).animate({
				'background-color': '#12ff00',
			}, 500, function() {
				// Animation complete.
				$(this).animate({
					'background-color': '',
				}, 1000);
			});
			
			
		}
		
	});
	
	/**
	 * Prevent brick editor form from submitting when Enter-Key is hit.
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */		
	$(document).on("keypress", "#post :input[type=submit]", function(event) {
		return event.keyCode != 13;
	});

	/**
	 * Close lightbox on update if we are loaded in a lightbox
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */				
	$( document ).on( 'mp_core_post_submitted', function(event) {
	
		parent.mp_stacks_close_lightbox( 'brick_updated' );
		
	});
	
	/**
	 * Close the lightbox if we have deleted a brick post
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	$( '#delete-action .submitdelete' ).on( 'click', function(event) {
		parent.mp_stacks_close_lightbox( 'brick_deleted' );
	});
		
	/**
	 * Close the lightbox if we just re-ordered some bricks
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	$( '#posts-filter' ).on( 'submit', function(event) {
		parent.mp_stacks_close_lightbox();
	});
	
	/**
	 * Auto select the stack we want this brick to be in
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	var stack_id = mp_stacks_getQueryVariable('mp_stack_id');

	stack_id_checkbox = '#in-mp_stacks-' + stack_id;
	
	$(stack_id_checkbox).prop('checked', true);	
	
	$('#mp_stackschecklist li input').on('change', function(){
		
		if ( $('.mp_stack_order[name="mp_stack_order[' + $(this).val() + ']"]').length == 0 ){
		$( '<input type="hidden" class="mp_stack_order" name="mp_stack_order[' + $(this).val() + ']" value="1000">' ).prependTo( "#post" );
		}
	});
	
	/**
	 * Shortcode Insertor: Hide 1st options for "Add Stack" and show Buttons to Make new Stack
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $( '.mp-stacks-shortcode-action-choice.new-stack' ).on( 'click', function(event){
		 
		 $( '.mp-stacks-shortcode-choose-action' ).hide(); 
		 
		 //Show options to create new stack
		 $('.mp-stacks-shortcode-new-stack-div').css('display', 'block');
		 
		 //Add the default JS action to insert the stack when the user click on #mp_stack
		 $('#mp_stack').attr('onclick', 'insert_mp_stack_Shortcode();');
	 });
	
	/**
	 * Shortcode Insertor: Hide 1st options for "Add Stack" and show Buttons to use existing stack
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $( '.mp-stacks-shortcode-action-choice.existing-stack' ).on( 'click', function(event){
		 
		 $( '.mp-stacks-shortcode-choose-action' ).hide(); 
		 
		 //Show options to use existing stack
		 $('.mp-stacks-shortcode-existing-stack-div').show();
		 
		 //Remove the default JS action to insert the stack when the user click on #mp_stack
		 $('#mp_stack').attr('onclick', '');
	
	 });
	  
	 /**
	 * Stack Creation: When the user changes the dropdown for Stack Options, show corresponding Options for "Duplicate", or "Template".
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $( document ).on( 'change', '.mp-stacks-new-stack-source-type > input', function(event){
			
		 var source_type = $(this).val();
		  	 
		 //Hide any options that may have already been selected
		 $( '.duplicate-stack-option, .template-stack-option' ).hide(); 
		 
		 //Show options to create new stack
		 if ( source_type ){
		 	$('.' + source_type ).show();
		 }
		 
		 //If this is the template option or the duplicate option, we don't need the "Create Stack" button because it fires when they select a template/stack-to-duplicate
		 if ( source_type == "template-stack-option" || source_type == "duplicate-stack-option" ){
			$('.mp-stacks-new-stack-button').hide(); 
			$('#mp_stack_cancel_download_insert').hide();
		 }
		 //Otherwise if its one of the other options, keep the "Create Stack" button
		 else{
			$('.mp-stacks-new-stack-button').show(); 
			$('#mp_stack_cancel_download_insert').show();
		 }
		 
	 });
	 
	 /**
	 * Stack Creation: When the user clicks on a Stack template they want to DUPLICATE
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $('.mp-stacks-new-stack-duplicate-stack > li > .mp-stacks-duplicate-button').on('click', function(event){
		 
		event.preventDefault();
		 
		//Add the class which lets us know this one was selected
		$(this).addClass('mp-stacks-selected-stack-for-duplication');	
		
		//Create the new stack
		mp_stacks_make_new_stack();
		 
	 });
	 
	 /**
	 * Stack Creation: When the user clicks on a Stack template they want to use
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $('.mp-stacks-installed-templates > li').on('click', function(event){
		 
		event.preventDefault();
		
		//Remove the "blue" styling class from all templates if one was previously clicked
		$('.mp-stacks-installed-templates > li').removeClass('mp-stacks-selected-template');	
		 
		//Apply the "blue" styling class to this chosen template
		$(this).addClass('mp-stacks-selected-template');	
		
		//Create the new stack
		mp_stacks_make_new_stack();
		 
	 });
	 
	 /**
	 * Stack Creation: When the user rolls over a stack template, show a popup image preview of the template
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $( '.mp-stacks-installed-templates li' ).on( 'mouseenter', function( event ){
			$('body').append( '<div class="mp_stacks_template_popup_preview"><img class="mp_stacks_template_popup_preview_img" src="' + $(this).attr( 'template_preview_img' ) + '" /></div>' );
	 }).on( 'mouseleave', function( event ){
		 	$('body').find( '.mp_stacks_template_popup_preview' ).remove();
	 });
	 
	 /**
	 * Stack Creation: Make the template preview image follow the cursor
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $(document).on('mousemove', function(e){
		$('.mp_stacks_template_popup_preview').css({
		   left:  e.pageX + 20,
		   top:   e.pageY
		});
	 });
	 
	/**
	 * Make New Stack Button Ajax
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $('.mp-stacks-new-stack-button').on('click', function(event){
		
		event.preventDefault(); 
		
		//Run the "Make New Stack" ajax
		mp_stacks_make_new_stack();
		
	 });
	 
	 /**
	 * Function which takes available data on screen, runs ajax, and makes new stack
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 function mp_stacks_make_new_stack(){
	 	 
		//Scroll to the top of the page - where all of the feedback is shown.
		window.scrollTo(0, 0);
		 
		//Hide Step 2 Stack Options once the user has clicked "Create New Stack"
		$('.mp-stacks-shortcode-existing-stack-div').hide();
		$('.mp-stacks-shortcode-new-stack-div').hide();
		
		//Hide everything in the mp-stacks-manage-page-new-stack-div
		$('.mp-stacks-manage-page-new-stack-div').hide();
		
		//If the shortcode container div exists this will be 'true' otherwise 'false' ( or 0 )
		var using_shortcode = $('.mp-stacks-shortcode-container').length;
		
		// Get the title the user entered
		var stack_title = $('.mp-stacks-new-stack-input').val();
		
		// Get the stack source type
		var stack_source_type = $('.mp-stacks-new-stack-source-type > input:checked').val();
		
		// Get the stack to duplicate
		var stack_duplicate_id = $('.mp-stacks-selected-stack-for-duplication .mp-stacks-id').html();
		stack_duplicate_id = stack_duplicate_id ? stack_duplicate_id : '';
		
		//Get the stack template the user has chosen
		var stack_template_slug = $('.mp-stacks-selected-template .mp-stacks-installed-template-function-name').html();
		stack_template_slug = stack_template_slug ? stack_template_slug : '';
		
		//Only send for ajax if there is a value
		if ( stack_title != '' ){
			
			//If we are doing this using the shortcode popup, insert into TinyMCE
			 if ( using_shortcode ){
				 //Show that stack is being created
				 $('.mp-stacks-shortcode-container').append('<div class="mp_stack_creating">'+mp_stacks_vars.stack_creating_message+'</div>');
			 }
			 else{
				//Show that stack is being created
				$('.mp-stacks-manage-page-new-stack-div').before('<div class="mp_stack_creating">'+mp_stacks_vars.stack_creating_message+'</div>'); 
			 }
				
			// Form the array to pass to the wp_ajax_mp_stacks_make_new_stacks php function
			var postData = {
				action: 'mp_stacks_make_new_stack',
				mp_stacks_nonce: mp_stacks_vars.ajax_nonce_value,
				mp_stacks_new_stack_name: stack_title,
				mp_stacks_new_stack_source_type: stack_source_type,
				mp_stacks_new_stack_duplicate_id: stack_duplicate_id,
				mp_stacks_new_stack_template_slug: stack_template_slug
			};
			
			//Ajax to make new stack
			$.ajax({
				type: "POST",
				data: postData,
				dataType:"json",
				url: mp_stacks_vars.ajaxurl,
				success: function (response) {
					
					//If we are doing this using the shortcode popup, insert into TinyMCE
					if ( using_shortcode ){
						
						//Add new stack TinyMCE/ActiveEditor
						window.send_to_editor('[mp_stack stack="' + response.new_stack_id + '"]');
					
						//Show that stack was successfuly created
						$('.mp-stacks-shortcode-container').append('<div class="mp_stack_successful">'+mp_stacks_vars.stack_successful_message_from_shortcode+'</div>');
						$('.mp_stack_creating').remove();
						
						tb_remove();
						
						//Make tinyMce run the function in the tinyMCE plugin for MP Stacks which swaps the shortcode for an image
						//tinyMCE.activeEditor.execCommand('MP_Stacks');
					
					}
					else{
							
						//Show that stack was successfuly created
						$('.mp-stacks-manage-page-new-stack-div	').before('<div class="mp_stack_successful">'+mp_stacks_vars.stack_successful_message_from_manage_page+'</div>');
						
						//Remove the "Creating..." message.
						$('.mp_stack_creating').remove();
						
						//Refresh the list of Stacks on the right of the Manage Stacks Page
						$('.taxonomy-mp_stacks #col-right .wp-list-table #the-list').html(response.updated_stacks_table);
				
					}
					
					//Once the user inserts a new/existing stack into the active text area, reset the options so they can insert another stack if needed
					setTimeout(function(){
						
						//If we are doing this using the shortcode popup, insert into TinyMCE
						if ( using_shortcode ){
							//Show Step 1 Stack Options
							$( '.mp-stacks-shortcode-choose-action' ).show(); 
							 
							//Hide Step 2 Stack Options and ajax messages
							$('.mp-stacks-shortcode-existing-stack-div').hide();
							$('.mp-stacks-shortcode-new-stack-div').hide();
							$('.mp_stack_successful').hide();
							
							//Remove the "Successfully Created" message
							$('.mp_stack_successful').remove();
						}
						else{
						
							//Reset Creation Options by hiding all items in the "Create New Stack" div on the manage page
							$('.mp-stacks-manage-page-new-stack-div > *').hide();
						
							//Re-Show the "Create New Stack" div
							$('.mp-stacks-manage-page-new-stack-div').css('display', '');
						
							//Re-Show the just things the user will want to create another Stack
							$('.mp-stacks-manage-page-new-stack-div .mp-stacks-new-stack-heading').css('display', '');
							$('.mp-stacks-manage-page-new-stack-div .mp-stacks-new-stack-option-name').css('display', '');
							$('.mp-stacks-manage-page-new-stack-div .mp-stacks-new-stack-creation-options').css('display', '');
							$('.mp-stacks-manage-page-new-stack-div .mp-stacks-new-stack-button').css('display', '');
							$('.mp-stacks-manage-page-new-stack-div .mp-stacks-new-stack-input').val('');
							$('.mp-stacks-manage-page-new-stack-div .mp-stacks-new-stack-source-type-container .mp-stacks-new-stack-source-type:first-child input').prop('checked',true);
							
							//Remove the "Successfully Created" message
							$('.mp_stack_successful').remove();
							
						}
						
					
					}, 2000);
					
				}
			}).fail(function (data) {
				console.log(data);
				
				$('.mp_stack_creating').html( data.responseText );
				
				//Once an error comes back, show it for a few seconds, then reset 
				setTimeout(function(){
					mp_stacks_reset_shortcode_choose_options(); 
					$('.mp_stack_creating').html('');	
				}, 2000 );
				
			});
			
			return false;
		 }
		 //If no value is entered, show an alert that the Title field cannot be blank
		 else{
			
			//Hide the Stack template preview if it is showing 
			$('body').find( '.mp_stacks_template_popup_preview' ).remove();
			
			alert(mp_stacks_vars.stack_needs_title_alert); 
			
			//Re-Show Step 2 Stack Options once the user has clicked "Create New Stack"
			$('.mp-stacks-shortcode-new-stack-div').show();
			
			//Reshow Creation Options for Manage Page
			$('.mp-stacks-manage-page-new-stack-div').css('display', '');
			
			//Remove "Creating Stack" Message
			$('.mp_stack_creating').remove();
		 }
	 }
	 	
	//When the user is done typing in the confirmation of inserting new stack,
	$(".mp-stacks-insert-stack-confirmation").on('change', function(event){
	 
	 	event.preventDefault;
		
		//Remove any notices that might have already appeared
		$( '.mp-stacks-confirmation-notice' ).remove();
		
		//Remove the default javascript function to force the user to confirm they understand
		$('#mp_stack').attr('onclick', '');	
		
		//If the user hasn't entered the right confirmation message
		if ( $('.mp-stacks-insert-stack-confirmation').val() != mp_stacks_vars.stack_insert_confirmation_phrase ){
			 
			 //Turn the text area RED!!
			 $('.mp-stacks-insert-stack-confirmation').css('background-color', '#ff7979');
			 $('.mp-stacks-insert-stack-confirmation').after( '<div class="mp-stacks-confirmation-notice">' + mp_stacks_vars.stack_confirmation_incorrect + '</div>' );
			 
		}
		else{
			
			//Turn the text area GREEN!!
			 $('.mp-stacks-insert-stack-confirmation').css('background-color', '#acff7a');
			 
			//Put the onclick function back into the insert button so it works
			$('#mp_stack').attr('onclick', 'insert_mp_stack_Shortcode();');	
		}
	
	});
	 
	 //Once the user inserts a new/existing stack into the active text area, reset the options so they can insert another stack if needed
	 $( window ).on( 'mp_core_shortcode_mp_stack_insert_event', function( event ){
			mp_stacks_reset_shortcode_choose_options(); 
	 });
	 
	 //When the user clicks cancel, reset the options so they can insert another stack if needed
	 $( '#mp_stack_cancel_download_insert, .mp_stack-thickbox' ).on( 'click', function( event ){
			mp_stacks_reset_shortcode_choose_options(); 
	 });
	 
	 /**
	 * Override the appendContent function in magnificPopup
	 *
	 */
	$.magnificPopup.instance.appendContent = function(newContent, type) {
		
		//Get the instance
		var mfp = $.magnificPopup.instance;
        var proto = $.magnificPopup.proto;
	  	
		//Original appendContent function begins here
		mfp.content = newContent;
		
		//Here we add our custom check for youtube or vimeo
		var iframe_src = mfp.content.find('.mfp-iframe').attr('src');	
		if (iframe_src){
			if ( iframe_src.indexOf("youtube.com/embed") > -1 || iframe_src.indexOf("vimeo.com") > -1 ){
				
				//If it matches, add class mfp-video to mfp-content div
				mfp.contentContainer.addClass('mfp-video');
			}
		}
		
		//"Action Hook" trigger so Add-Ons can modify the magnific popup further
		$(window).trigger("mp_stacks_magnific_popup_content_modifier", mfp );
		
		//Continue with original appendContent function
		if(newContent) {
			if(mfp.st.showCloseBtn && mfp.st.closeBtnInside &&
				mfp.currTemplate[type] === true) {
				// if there is no markup, we just append close button element inside
				if(!mfp.content.find('.mfp-close').length) {
					mfp.content.append(_getCloseBtn());
				}
			} else {
				mfp.content = newContent;
			}
		} else {
			mfp.content = '';
		}

		//_mfpTrigger(BEFORE_APPEND_EVENT);
		mfp.container.addClass('mfp-'+type+'-holder');

		mfp.contentContainer.append(mfp.content);
	
	};
	
	/**
	 * Modify the Magnific Popup to open using the popup source - and set sized and behaviours for Media like YouTube, JPGs, etc
	 *
	 */
	function mp_stacks_magnific_editor( popup_source ){
		$.magnificPopup.open({
			
			items: {
				src: popup_source
			},
  
			type: 'iframe', 
			
			callbacks: {
				
				open: function() {
					// Will fire when popup is opened
				},
				close: function() {
					//Will fire the popup is closed
				},
			
				//Change the type of popup this is based on what's in the src
				elementParse: function(item) {
				
					var extension = item.src.split('.').pop();
					
					switch(extension) {
						case 'jpg':
						case 'png':
						case 'gif':
						item.type = 'image';
						break;
						case 'html':
						item.type = 'ajax';
						break;
						default:
						item.type = 'iframe';
					}
				}
			},
			
			patterns: {
							
				youtube: {
					index: 'youtube.com/watch', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
					
					id: 'v=', // String that splits URL in a two parts, second part should be %id%
					// Or null - full URL will be returned
					// Or a function that should return %id%, for example:
					// id: function(url) { return 'parsed id'; } 
					
					src: '//www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe. 
				},
				vimeo: {
					index: 'vimeo.com/',
					id: '/',
					src: '//player.vimeo.com/video/%id%?autoplay=1'
				},
				gmaps: {
					index: '//maps.google.',
					src: '%id%&output=embed'
				}
				
				// you may add here more sources
			
			},
			
			mainClass: 'mp-stacks-iframe-full-screen',
			
			srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
			
			preloader: true
	
		}, 0);
	
	}

	/**
	 * Set the class names of links which should open a full-size magnific popup
	 *
	 */
	$(document).on('click', '.mp-brick-edit-link, .mp-brick-add-before-link, .mp-brick-add-after-link, .mp-brick-reorder-bricks, .mp-brick-add-new-link, .mp-stacks-lightbox-link', function(event){ 
		event.preventDefault();
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_editor( $(this).attr('href') );
	});	
	
	/**
	 * Modify the Magnific Popup to open using the popup source - and set sized for height of content
	 *
	 */
	function mp_stacks_magnific_height_match( popup_source, width ){
		$.magnificPopup.open({
			
			items: {
				src: popup_source
			},
			type: 'iframe',
			iframe: {
				markup: '<div class="mfp-iframe-height-match" style="width:100%; max-width:' + width + ';">'+
				'<iframe class="mfp-iframe" frameborder="0" scrolling="yes" onload="javascript:mp_stacks_mfp_match_height(this);" style="width:100%;" allowfullscreen></iframe>'+
				'<div class="mfp-close"></div>'+
				'</div>'
			},
			callbacks: {
				open: function() {
					// Will fire when popup is opened
				},
				close: function() {
					//Will fire the popup is closed
					$( document ).off( "mp_stacks_mfp_match_height_trigger", '.mfp-content' );
					$( document ).off( "mp_stacks_resize_complete", '.mfp-content' );
				}
				
			},
			mainClass: 'mp-stacks-iframe-height-match',
			preloader: true
		
		}, 0);
	
	}
	
	/**
	 * Set items with the class 'mp-stacks-iframe-height-match-lightbox-link' to open a lightbox iframe matching the height of its contents 
	 * and at the width defined in its 'mfp-width' attribute
	 */		
	$(document).on( 'click', '.mp-stacks-iframe-height-match-lightbox-link', function( event ){
		
		event.preventDefault();
		
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_height_match( $(this).attr('href'), $( this ).attr( 'mfp-width' ) );
		
		//Set the mfp-content div to be the width we want for this popup
		$( '.mp-stacks-iframe-height-match .mfp-content' ).css( 'width', $( this ).attr( 'mfp-width' ) );
		
	});
	
	//When a user click the "Brick Export" icon in the bottom right of the Brick Editor area
	$( '#mp-stacks-main-export-brick-link' ).on( 'click', function( event ){
		
		//This will reset all of the options for exporting a brick so the user can start fresh when the dialog opens
		$( '#mp-stacks-import-brick-form' ).css( 'display', 'none' );
		$( '#mp-stacks-export-brick' ).css( 'display', '' );
		$( '.mp-stacks-brick-export-action-choice.import-brick' ).css( 'width', '50%' ).removeClass( 'mp-stacks-brick-import-selected' ); 
		//remove any errors that may have previously displayed 
		$( '.mp-stacks-import-brick-error' ).remove();
		
	});
	
	/**
	 * When the "Import Brick" link is clicked.
	 */	
	$( '#mp-stacks-import-brick' ).on( 'click', function( event ){
	
		event.preventDefault();
		
		//Show the form to import a Brick's JSON code
		$( '#mp-stacks-import-brick-form' ).css( 'display', 'inline-block' );
				
		//Hide the export Brick Option
		$( '#mp-stacks-export-brick' ).css( 'display', 'none' );
		
		//Make the "Import" side take up all the space
		$( '.mp-stacks-brick-export-action-choice.import-brick' ).css( 'width', '100%' ).addClass( 'mp-stacks-brick-import-selected' ); 
		
		//remove any errors that may have previously displayed 
		$( '.mp-stacks-import-brick-error' ).remove();
	});
	
	/**
	 * When the "Import Brick" form is submitted.
	 */	
	$( '#mp-stacks-import-brick-submit-btn' ).on( 'click', function( event ){
		
		//remove any errors that may have previously displayed 
		$( '.mp-stacks-import-brick-error' ).remove();
		
		//Confirm that when the user hits "Submit" that they actually meant to
		var confirmation = confirm("This will overwrite ALL settings that are currently entered for this Brick. Make sure you only use code from trusted sources. Are you sure you want to do this?");
		if ( !confirmation ){
			return false;
		}
		
		event.preventDefault();
		
		var mp_brick_id = $( this ).attr( 'mp-brick-id' );
		var mp_stack_id = $( this ).attr( 'mp-stack-id' );
		var mp_stack_order = $( this ).attr( 'mp-stack-order' );
		var mp_brick_json_to_import = $( '#mp-brick-json-to-import' ).val();
		
		//Now we will do the actual Brick Import part using ajax
		 var mp_stacks_import_brick_postData = {
			action: 'mp_stacks_import_brick_via_ajax',
			mp_stacks_nonce: mp_stacks_vars.ajax_nonce_value,
			mp_brick_id: mp_brick_id,
			mp_stack_id: mp_stack_id,
			mp_stack_order: mp_stack_order,
			mp_brick_json_to_import: mp_brick_json_to_import
		 };
		
		 //Ajax to make new stack
		 $.ajax({
			type: "POST",
			data: mp_stacks_import_brick_postData,
			dataType:"json",
			url: mp_stacks_vars.ajaxurl,
			success: function (response) {
				
				//If there was  an error, output the error message
				if ( response.error ){
					$( '.mp-stacks-import-brick-action-description').after( '<div class="mp-stacks-import-brick-error">' + response.error + '</div>' );
				}
				//If this was successful
				else{
					$( document ).trigger( 'mp_core_post_submitted' );
					
					var wait_for_brick_to_be_created_before_refreshing = setInterval( function(){
						clearInterval( wait_for_brick_to_be_created_before_refreshing );
						location.reload();
					}, 1000 );
					
				}
				
								
			}
		 }).fail(function (data) {
			console.log(data);
		 });	
				
	});
	
	/**
	 * Modify the Magnific Popup to open using the popup source - and set sized for height of content
	 *
	 */
	function mp_stacks_magnific_custom_width_height( popup_source, width, height ){
		$.magnificPopup.open({
			
			items: {
				src: popup_source
			},
			type: 'iframe',
			iframe: {
				markup: '<div class="mfp-iframe-custom-width-height" style="width:100%; height:100%;">'+
				'<iframe class="mfp-iframe" frameborder="0" scrolling="yes" style="width:100%; height:100%;" allowfullscreen></iframe>'+
				'<div class="mfp-close"></div>'+
				'</div>'
			},
			callbacks: {
				open: function() {
					// Will fire when popup is opened
				},
				close: function() {
					//Will fire the popup is closed
					$( document ).off( "mp_stacks_mfp_match_height_trigger", '.mfp-content' );
					$( document ).off( "mp_stacks_resize_complete", '.mfp-content' );
				}
				
			},
			mainClass: 'mp-stacks-iframe-custom-width-height',
			preloader: true
		
		}, 0);
	
	}
	
	/**
	 * Set items with the class 'mp-stacks-iframe-custom-width-height' to open a lightbox iframe
	 * at the width defined in its 'mfp-width' attribute
	 * at the height defined in its 'mfp-height' attribute
	 */		
	$(document).on( 'click', '.mp-stacks-iframe-custom-width-height', function( event ){
		
		event.preventDefault();
		
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_custom_width_height( $(this).attr('href'), $( this ).attr( 'mfp-width' ), $( this ).attr( 'mfp-height' ) );
		
		//Set the mfp-content div to be the width and height we want for this popup
		$( '.mp-stacks-iframe-custom-width-height .mfp-content' ).css( 'width', $( this ).attr( 'mfp-width' ) );
		$( '.mp-stacks-iframe-custom-width-height .mfp-content' ).css( 'max-width', '100%' );
		$( '.mp-stacks-iframe-custom-width-height .mfp-content' ).css( 'height', $( this ).attr( 'mfp-height' ) );
		$( '.mp-stacks-iframe-custom-width-height .mfp-content' ).css( 'max-height', '100%' );
		
	});
	
	//If the URL variable mplsmh (Stand for: mp lightbox source matched height) is set,
	//open the value of it in a lightbox upon page load at the specified width and mathing the height of the contents
	if ( mp_stacks_getQueryVariable( 'mplsmh' ) ){
		
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_height_match( mp_stacks_getQueryVariable( 'mplsmh' ), mp_stacks_getQueryVariable( 'width' )  );
		
		//Set the mfp-content div to be the width we want for this popup
		$( '.mp-stacks-iframe-height-match .mfp-content' ).css( 'width', mp_stacks_getQueryVariable( 'width' ) );
	}
	
	/**
	 * When a person clicks on the "Add Link" button in TinyMCE while on a Brick page, add the list of bricks in the current stack to the list of options for linking
	 *
	 
	 $(document).on( 'click', '.mp_stacks_links_button', function( event ){
		 
		 event.preventDefault();
		  
		 alert('sdgsgs');
		 
		 return;
		 
		 var stack_id = mp_stacks_getQueryVariable('mp_stack_id');
		 
		 if ( stack_id ){
		 
			 // Form the array to pass to the wp_ajax_mp_stacks_make_new_stacks php function
			 var mp_stacks_link_to_bricks_postData = {
				action: 'mp_stacks_link_to_bricks_ajax',
				mp_stacks_nonce: mp_stacks_vars.ajax_nonce_value,
				mp_stack_id: stack_id
			 };
			
			 //Ajax to make new stack
			 $.ajax({
				type: "POST",
				data: mp_stacks_link_to_bricks_postData,
				dataType:"json",
				url: mp_stacks_vars.ajaxurl,
				success: function (response) {
					
					$( '#mp_stacks_links').html( response.output );
									
					//Make it so that when one of the Birck URLs is clicked, it puts it into the URl field for this link
					$( '#wp-link-wrap .mp-stacks-brick-url').on( 'click', function(){
						
						$( '#url-field' ).val( $(this).html() );
						
					});
				}
			 }).fail(function (data) {
				console.log(data);
			 });	
		 }
	 });
	 */

	 //Add Link to Addons on right side at bottom
	$( '.post-type-mp_brick #side-sortables' ).append( '<div id="extend-mp-stacks-container"><a id="extend-mp-stacks" href="' + mp_stacks_vars.more_content_types + '" target=_blank">' + mp_stacks_vars.extend_mp_stacks_text + '</a></div>' );
	
	//When the extend button is clicked
	$( document ).on( 'click', '#extend-mp-stacks', function(){
		$(this).parent().html( mp_stacks_vars.need_to_refresh_text );
	});	
	
	//When the More content-types button is clicked
	$( document ).on( 'click', '.mp-stacks-more-content-types', function(){
		$(this).replaceWith( '<div>' + mp_stacks_vars.need_to_refresh_text + '</div>' );
	});	
	
	//Check if we are open in an iframe and add a POST field if we are so that MP Stacks knows not to reload the page when saved. mp_stacks_do_not_reload_after_brick_save
	if ( mp_stacks_inIframe() ){
		$( '<input type="hidden" class="mp_stacks_do_not_reload_after_brick_save" name="mp_stacks_do_not_reload_after_brick_save" value="mp_stacks_do_not_reload_after_brick_save">' ).prependTo( "#post" );	
	}
	
});

//Resize an iframe to be as big as its contents
function mp_stacks_resizeIframe(obj) {
	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

//Close the lightbox when the update button is clicked
function mp_stacks_close_lightbox(){
	
	//Close the iframe and reload the window
	jQuery(document).ready(function($){
		
		//Close iframe
		$('.mfp-iframe').load(function(){
			$.magnificPopup.instance.close();
		});
	});
}

//Reset the options for the stack insertshortcode popup
function mp_stacks_reset_shortcode_choose_options(){
		
	jQuery(document).ready(function($){ 
		//Show Step 1 Stack Options
		$( '.mp-stacks-shortcode-choose-action' ).show(); 
		
		//Hide Step 2 Stack Options
		$('.mp-stacks-shortcode-existing-stack-div').hide();
		$('.mp-stacks-shortcode-new-stack-div').hide();
	});
	 
 };

//This function allows us to grab URL variables
function mp_stacks_getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}

//This function can be used to set an iframe's height to match the height of its contents.
function mp_stacks_mfp_match_height(iframe) {
	
	jQuery(document).ready(function($){
		
		var iframeWin = iframe.contentWindow || iframe.contentDocument.parentWindow;
		if (iframeWin.document.body) {
			
			//We use an interval to loop the resize function several times to make sure the iframe's content is fully loaded
			var iframe_height_interval_counter = 1;
			var iframe_height_interval = setInterval( function(){
				iframe.height = iframeWin.document.documentElement.scrollHeight + 'px' || iframeWin.document.body.scrollHeight + 'px';
				iframe_height_interval_counter = iframe_height_interval_counter + 1;
				if ( iframe_height_interval_counter >= 25 ){
					clearInterval(iframe_height_interval);	
				}
			}, 100 );
		}
		
		//This function is fired upon screen resize (and the mp_stacks_mfp_match_height_trigger) so that the height continues to match the contents of the iframe
		$( document ).on( 'mp_stacks_resize_complete mp_stacks_mfp_match_height_trigger', '.mfp-content', function(){
			mp_stacks_mfp_match_height( iframe );
		});

	
	});
};

//Allow events in iframes to trigger the 'mp_stacks_mfp_match_height' event by calling this function using parent.mp_stacks_mfp_match_height_trigger()
function mp_stacks_mfp_match_height_trigger(){
	jQuery(document).ready(function($){
		$( '.mfp-content' ).trigger( 'mp_stacks_mfp_match_height_trigger' );	
	});
}

//This function can be used to check if the current page is open in an iframe
function mp_stacks_inIframe () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}

//Check for old versions of Browsers that suck
if(  !document.addEventListener  ){
	alert("Your Internet Browser is out of date and is at risk for being hacked and your personal information stolen. Please upgrade to a secure browser like Google Chrome or Firefox.");
}