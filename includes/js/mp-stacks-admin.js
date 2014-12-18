jQuery(document).ready(function($){
	
	//Show only the content types the user has selected for this brick
	function mp_stacks_reset_content_types( args ){		
		
		default_args = new Array();
		default_args['reset_tinymce'] = true;
		
		args = args || default_args;
			
		//Hide content type metaboxes by looping through each item in the drodown
		var values = $("#mp_stacks_content_metabox .brick_first_content_type>option").map(function() { 
			
			//Hide metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'none');	
				
		});
	
		//Show correct content type metaboxes by looping through each item in the 1st drodown
		var values = $("#mp_stacks_content_metabox .brick_first_content_type>option:selected").map(function() { 
			
			//If this is the "More Content Types..." option, open the Add-On Shop in a new tab
			if ( $(this).val() == 'more_content_types' ){
				window.location = mp_stacks_vars.more_content_types;	
			}
			else{
					
				//Show metaboxes with the matching name to this select item
				$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
				
				//Jquery trigger which allows add-ons to make things happen upon change
				$(document).trigger( 'mp_stacks_content_type_change', [content_type = $(this).val(), post_id = mp_stacks_getQueryVariable('post')] );
							
				//Move metabox to the top of the metaboxes
				$('#mp_stacks_content_metabox').after($('#mp_stacks_' + $(this).val() + '_metabox'));
				
				//If we should reset all tinymce objects in this metabox
				if (args['reset_tinymce']){
					
					//Loop through all elements and if they are a tinyMCE, reset them
					$('#mp_stacks_' + $(this).val() + '_metabox').find('.wp-editor-area').each(function() {
						
						//Re-initialize tinymce for each TInyMCE area and remove old one
						tinyMCE.execCommand( 'mceRemoveEditor', true, this.id );
						$('#'+this.id+'_parent').remove();
						
						//Text area must be in view for tinymce to load correctly
						$(this).css('display', 'block');
						tinyMCE.execCommand( 'mceAddEditor', true, this.id );		
					
					});	
				}
			}
		});
		
		//Show correct content type metaboxes by looping through each item in the 2nd drodown
		var values = $("#mp_stacks_content_metabox .brick_second_content_type>option:selected").map(function() { 
			
			//If this is the "More Content Types..." option, open the Add-On Shop in a new tab
			if ( $(this).val() == 'more_content_types' ){
				window.location = mp_stacks_vars.more_content_types;
			}
			else{
				//Show metaboxes with the matching name to this select item
				$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
					
				//Jquery trigger which allows add-ons to make things happen upon change
				$(document).trigger( 'mp_stacks_content_type_change', [content_type = $(this).val(), post_id = mp_stacks_getQueryVariable('post')] );
										
				//Move metabox to the second-from-the-top of the metaboxes
				$('#mp_stacks_content_metabox').next().after($('#mp_stacks_' + $(this).val() + '_metabox'));
				
				//If we should reset all tinymce objects in this metabox
				if (args['reset_tinymce']){
					
					//Loop through all elements and if they are a tinyMCE, reset them
					$('#mp_stacks_' + $(this).val() + '_metabox').find('.wp-editor-area').each(function() {
						
						//Re-initialize tinymce for each TInyMCE area and remove old one
						tinyMCE.execCommand( 'mceRemoveEditor', true, this.id );
						$('#'+this.id+'_parent').remove();
						
						//Text area must be in view for tinymce to load correctly
						$(this).css('display', 'block');
						tinyMCE.execCommand( 'mceAddEditor', true, this.id );
						
					});
					
				}
			}
			
		});
	
	}
	
	/**
	 * We dont want to reset all tinymces on page load so set it to false
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */	
	var args = new Array();
	args['reset_tinymce'] = false;
	mp_stacks_reset_content_types(args);
	
	$('#mp_stacks_content_metabox .brick_first_content_type').change(function() {
		mp_stacks_reset_content_types();
	});
	
	
	$('#mp_stacks_content_metabox .brick_second_content_type').change(function() {
		mp_stacks_reset_content_types();
	});
	
	//Show the notice about which brick we are editing (if we don't show it this way, it does a weird "jump in" thing. Probably should create a better fix than this but it appears to be an issue with admin_notices in WordPress
	$('.mp-stacks-editor-title-notice').css('display', '');
			
	//Now that the page is loaded and our content types are reset properly, lets show the body div (hidden by css)
	$('.post-type-mp_brick #poststuff').css('opacity', '1');
	
	//Change the title from "Loading..." to "Add New Brick" or "Edit Brick"
	$('.post-new-php.post-type-mp_brick .wrap h2:first-child').html( mp_stacks_vars.add_new_brick_title );
	$('.post-php.post-type-mp_brick .wrap h2:first-child').html( mp_stacks_vars.edit_brick_title );
	
	/**
	 * Close lightbox on update if we are loaded in a lightbox
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */				
	$( '#post, #posts-filter' ).on( 'submit', function(event) {
	
		parent.mp_stacks_close_lightbox();
		
	});
	
	/**
	 * Close the lightbox if we have deleted a brick post
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	$( '#delete-action .submitdelete' ).on( 'click', function(event) {
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
		 
		 //If this is the template option, we don't need the "Create Stack" button because it fires when they select a template
		 if ( source_type == "template-stack-option" ){
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
		var stack_duplicate_id = $('.mp-stacks-new-stack-duplicate-stack').val();
		
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
	 
	 /**
	 * Dismiss the double click tip using ajax
	 *
	 * @since    1.0.0
	 * @link     http://mintplugins.com/doc/
	 */
	 $('.mp-stacks-dismiss-double-click').on('click', function(event){
		
		event.preventDefault(); 
				
		// Form the array to pass to the wp_ajax_mp_stacks_dismiss_double_click_tip php function
		var postData = {
			action: 'mp_stacks_dismiss_double_click_tip',
			mp_stacks_nonce: mp_stacks_vars.ajax_nonce_value,
		};
		
		var this_tip = $(this).parent().parent();
		
		//Ajax dismiss double click tip
		$.ajax({
			type: "POST",
			data: postData,
			url: mp_stacks_vars.ajaxurl,
			success: function (response) {
				this_tip.remove();	
			}
		}).fail(function (data) {
			console.log(data);
		});
		
		return false;
	 });
	
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
	 $( '#mp_stack_cancel_download_insert' ).on( 'click', function( event ){
			mp_stacks_reset_shortcode_choose_options(); 
	 });
	 
	 // Override the appendContent function in magnificPopup
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
	
	function mp_stacks_magnific_editor( popup_source ){
		$.magnificPopup.open({
			
			items: {
				src: popup_source
			},
  
			type: 'iframe', 
			
			callbacks: {
				
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
			
			srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
			
		}, 0);
	
	}

	//Set the class names of links which should open magnific popup
	$(document).on('click', '.mp-stacks-lightbox-link', function(event){ 
		event.preventDefault();
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_editor( $(this).attr('href') );
	});	
		
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