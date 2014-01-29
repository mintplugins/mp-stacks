jQuery(document).ready(function($){
	
	function mp_stacks_reset_media_types( args ){		
		
		default_args = new Array();
		default_args['reset_tinymce'] = true;
		
		args = args || default_args;
			
		//Hide media type metaboxes by looping through each item in the drodown
		var values = $("#mp_stacks_media_metabox .brick_first_media_type>option").map(function() { 
			
			//Hide metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'none');	
				
		});
	
		//Show correct media type metaboxes by looping through each item in the 1st drodown
		var values = $("#mp_stacks_media_metabox .brick_first_media_type>option:selected").map(function() { 
		
			
			//Show metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
						
			//Move metabox to the top of the metaboxes
			$('#mp_stacks_media_metabox').after($('#mp_stacks_' + $(this).val() + '_metabox'));
			
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
					mp_stacks_size_text_previews();		
				
				});	
			}
		});
		
		//Show correct media type metaboxes by looping through each item in the 2nd drodown
		var values = $("#mp_stacks_media_metabox .brick_second_media_type>option:selected").map(function() { 
			
			
			//Show metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
									
			//Move metabox to the second-from-the-top of the metaboxes
			$('#mp_stacks_media_metabox').next().after($('#mp_stacks_' + $(this).val() + '_metabox'));
			
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
					mp_stacks_size_text_previews();
				});
				
			}
			
		});
	
	}
	
	//We dont want to reset all tinymces on page load so set it to false
	var args = new Array();
	args['reset_tinymce'] = false;
	mp_stacks_reset_media_types(args);
	
	$('#mp_stacks_media_metabox .brick_first_media_type').change(function() {
		mp_stacks_reset_media_types();
	});
	
	
	$('#mp_stacks_media_metabox .brick_second_media_type').change(function() {
		mp_stacks_reset_media_types();
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
	
	$('#brick_line_1_font_size').on( 'keyup click blur focus change paste', function() {
		//Set TinyMCE default font size dynamically	
		mp_stacks_size_text_previews();	
	});
	
	$('#brick_line_2_font_size').on( 'keyup click blur focus change paste', function() {
		//Set TinyMCE default font size dynamically	
		mp_stacks_size_text_previews();	
	});
	
	var mp_stacks_interval_counter = 0;
	
	//Because TinyMCE is absolute garbage, we have no event to hook into that works for 'loaded' with WordPress so have to check every few seconds which is total, necessary crap.	
	function mp_stacks_intervalTrigger() {
	  	  
		return window.setInterval(function(){
		
			mp_stacks_size_text_previews();
			
			mp_stacks_interval_counter = mp_stacks_interval_counter + 100;
	  
			if (mp_stacks_interval_counter > 500){
				window.clearInterval(mp_stacks_interval_trigger_id);
			}
		
		}, 100);
	};
	
	var mp_stacks_interval_trigger_id = mp_stacks_intervalTrigger();
				
	$( '#post' ).on( 'submit', function(event) {
	
		//Close lightbox on update if we are loaded in a lightbox	
		parent.mp_stacks_close_lightbox();
		
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