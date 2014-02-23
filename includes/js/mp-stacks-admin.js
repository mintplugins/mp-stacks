jQuery(document).ready(function($){
	
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
		
			
			//Show metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
						
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
					
					//Set font size in TinyMCEs
					//mp_stacks_size_text_previews();		
				
				});	
			}
		});
		
		//Show correct content type metaboxes by looping through each item in the 2nd drodown
		var values = $("#mp_stacks_content_metabox .brick_second_content_type>option:selected").map(function() { 
			
			
			//Show metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
									
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
					
					//Set font size in TinyMCEs
					//mp_stacks_size_text_previews();
				});
				
			}
			
		});
	
	}
	
	//We dont want to reset all tinymces on page load so set it to false
	var args = new Array();
	args['reset_tinymce'] = false;
	mp_stacks_reset_content_types(args);
	
	$('#mp_stacks_content_metabox .brick_first_content_type').change(function() {
		mp_stacks_reset_content_types();
	});
	
	
	$('#mp_stacks_content_metabox .brick_second_content_type').change(function() {
		mp_stacks_reset_content_types();
	});
	
	//Set TinyMCE default font size dynamically		
	function mp_stacks_size_text_previews(){				
		//Text area 1	
		$('#mp_core_wp_editor_brick_text_line_1_ifr').contents().find('body').css( 'font-size', $('#brick_line_1_font_size').val() + 'px' );
		$('#mp_core_wp_editor_brick_text_line_1_ifr').contents().find('body').css( 'line-height', $('#brick_line_1_font_size').val() + 'px' );	
		//Text area 2
		$('#mp_core_wp_editor_brick_text_line_2_ifr').contents().find('body').css( 'font-size', $('#brick_line_2_font_size').val() + 'px' );
		$('#mp_core_wp_editor_brick_text_line_2_ifr').contents().find('body').css( 'line-height', $('#brick_line_2_font_size').val() + 'px' );	
	}
	
	if ( $('#brick_line_1_font_size').val() ){
		$('#brick_line_1_font_size').on( 'keyup click blur focus change paste', function() {
			//Set TinyMCE default font size dynamically	
			//mp_stacks_size_text_previews();	
		});
		
		$('#brick_line_2_font_size').on( 'keyup click blur focus change paste', function() {
			//Set TinyMCE default font size dynamically	
			//mp_stacks_size_text_previews();	
		});
	
	
		var mp_stacks_interval_counter = 0;
		
		//Because TinyMCE is absolute garbage, we have no event to hook into that works for 'loaded' with WordPress so have to check every few seconds which is total, necessary crap.	
		function mp_stacks_intervalTrigger() {
			  
			return window.setInterval(function(){
			
				//mp_stacks_size_text_previews();
				
				mp_stacks_interval_counter = mp_stacks_interval_counter + 100;
		  
				if (mp_stacks_interval_counter > 500){
					window.clearInterval(mp_stacks_interval_trigger_id);
				}
			
			}, 100);
		};
		
		var mp_stacks_interval_trigger_id = mp_stacks_intervalTrigger();
		
	}
				
	$( '#post, #posts-filter' ).on( 'submit', function(event) {
	
		//Close lightbox on update if we are loaded in a lightbox	
		parent.mp_stacks_close_lightbox();
		
	});
	
	//Auto select the stack we want this brick to be in
	var stack_id = mp_stacks_getQueryVariable('mp_stack_id_new');

	stack_id_checkbox = '#in-mp_stacks-' + stack_id;
	
	$(stack_id_checkbox).prop('checked', true);	
	
	$('#mp_stackschecklist li input').on('change', function(){
		
		if ( $('.mp_stack_order[name="mp_stack_order[' + $(this).val() + ']"]').length == 0 ){
		$( '<input type="hidden" class="mp_stack_order" name="mp_stack_order[' + $(this).val() + ']" value="1000">' ).prependTo( "#post" );
		}
	});
	
	/**
	 * Make New Stack Button Ajax
	 *
	 * @since    1.0.0
	 * @link     http://moveplugins.com/doc/
	 */
	 $('.mp-stacks-shortcode-new-stack-div .mp-stacks-new-stack-button').on('click', function(event){
		
		event.preventDefault(); 
		
		// Get the title the user entered
		var stack_title = $('.mp-stacks-new-stack-input').val();
		
		//Only send for ajax if there is a value
		if ( stack_title != '' ){
				
			// If the country field has changed, we need to update the state/provice field
			var postData = {
				action: 'mp_stacks_make_new_stack',
				mp_stacks_nonce: mp_stacks_vars.ajax_nonce_value,
				mp_stacks_new_stack_name: stack_title
			};
			
			//Ajax to make new stack
			$.ajax({
				type: "POST",
				data: postData,
				url: mp_stacks_vars.ajaxurl,
				success: function (response) {
					
					//Add new stack to dropdown selected state
					$('#mp_stack_stack').prepend(response);
					
					//Remove original form for new stack
					$('.mp-stacks-new-stack-input').remove();
					$('.mp-stacks-shortcode-new-stack-div .mp-stacks-new-stack-button').remove();
					
					//Show that stack was successfuly created
					$('.mp-stacks-shortcode-new-stack-div').append('<div class="mp_stack_successful">'+mp_stacks_vars.stack_successful_message+'</div>');
					
				}
			}).fail(function (data) {
				console.log(data);
			});
			
			return false;
		 }
		 //If no value is entered, show an alert that the field cannot be blank
		 else{
			alert(mp_stacks_vars.stack_needs_title_alert); 
		 }
	 });
		
});

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