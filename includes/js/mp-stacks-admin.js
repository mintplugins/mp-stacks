jQuery(document).ready(function($){
	
	function mp_stacks_reset_media_types(){
		//Hide media type metaboxes by looping through each item in the drodown
		var values = $("#mp_stacks_media_metabox .brick_first_media_type>option").map(function() { 
			
			//Hide metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'none');	
				
		});
	
		//Show correct media type metaboxes by looping through each item in the 1st drodown
		var values = $("#mp_stacks_media_metabox .brick_first_media_type>option:selected").map(function() { 
			
			//Hide metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
			
			//Move metabox to the top of the metaboxes
			$('#mp_stacks_media_metabox').after($('#mp_stacks_' + $(this).val() + '_metabox'));
			
		});
		
		//Show correct media type metaboxes by looping through each item in the 2nd drodown
		var values = $("#mp_stacks_media_metabox .brick_second_media_type>option:selected").map(function() { 
			
			//Show metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
			
			//Move metabox to the second-from-the-top of the metaboxes
			$('#mp_stacks_media_metabox').next().after($('#mp_stacks_' + $(this).val() + '_metabox'));
			
		});
	
	}
	
	mp_stacks_reset_media_types();
	
	$('#mp_stacks_media_metabox .brick_first_media_type').change(function() {
		mp_stacks_reset_media_types();
	});
	
	
	$('#mp_stacks_media_metabox .brick_second_media_type').change(function() {
		mp_stacks_reset_media_types();
	});
	
	$('#brick_line_1_font_size').on( 'keyup click blur focus change paste', function() {
		//Set TinyMCE default font size dynamically	
				
		//Text area 1	
		$('#mp_core_wp_editor_brick_text_line_1_ifr').contents().find('body').css( 'font-size', $('#brick_line_1_font_size').val() + 'px' );
		$('#mp_core_wp_editor_brick_text_line_1_ifr').contents().find('body').css( 'line-height', $('#brick_line_1_font_size').val() + 'px' );	
		//Text area 2
		$('#mp_core_wp_editor_brick_text_line_2_ifr').contents().find('body').css( 'font-size', $('#brick_line_2_font_size').val() + 'px' );
		$('#mp_core_wp_editor_brick_text_line_2_ifr').contents().find('body').css( 'line-height', $('#brick_line_2_font_size').val() + 'px' );	
		
	});
	
	$( document ).on( 'mousemove', function() {
		//Set TinyMCE default font size dynamically		
		
		//Text area 1
		$('#mp_core_wp_editor_brick_text_line_1_ifr').contents().find('body').css( 'font-size', $('#brick_line_1_font_size').val() + 'px' );
		$('#mp_core_wp_editor_brick_text_line_1_ifr').contents().find('body').css( 'line-height', $('#brick_line_1_font_size').val() + 'px' );	
		
		//Text area 2
		$('#mp_core_wp_editor_brick_text_line_2_ifr').contents().find('body').css( 'font-size', $('#brick_line_2_font_size').val() + 'px' );
		$('#mp_core_wp_editor_brick_text_line_2_ifr').contents().find('body').css( 'line-height', $('#brick_line_2_font_size').val() + 'px' );	
		
	});
	
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