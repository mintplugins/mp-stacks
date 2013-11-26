jQuery(document).ready(function($){
	
	function reset_media_types(){
		//Hide media type metaboxes by looping through each item in the drodown
		var values = $("#mp_stacks_media_metabox .brick_first_media_type>option").map(function() { 
			
			//Hide metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'none');	
				
		});
	
		//Show correct media type metaboxes by looping through each item in the 1st drodown
		var values = $("#mp_stacks_media_metabox .brick_first_media_type>option:selected").map(function() { 
			
			//Hide metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
			
		});
		
		//Show correct media type metaboxes by looping through each item in the 2nd drodown
		var values = $("#mp_stacks_media_metabox .brick_second_media_type>option:selected").map(function() { 
			
			//Hide metaboxes with the matching name to this select item
			$('#mp_stacks_' + $(this).val() + '_metabox').css('display', 'block');	
			
		});
	
	}
	
	reset_media_types();
	
	$('#mp_stacks_media_metabox .brick_first_media_type').change(function() {
		reset_media_types();
	});
	
	
	$('#mp_stacks_media_metabox .brick_second_media_type').change(function() {
		reset_media_types();
	});
	
	$('#brick_line_1_font_size').on( 'keyup click blur focus change paste', function() {
		//Set TinyMCE default font size dynamically	
		
		//Text area 1	
		$('#brick_text_line_1_ifr').contents().find('body').css( 'font-size', $('#brick_line_1_font_size').val() + 'px' );
		$('#brick_text_line_1_ifr').contents().find('body').css( 'line-height', $('#brick_line_1_font_size').val() + 'px' );	
		//Text area 2
		$('#brick_text_line_2_ifr').contents().find('body').css( 'font-size', $('#brick_line_2_font_size').val() + 'px' );
		$('#brick_text_line_2_ifr').contents().find('body').css( 'line-height', $('#brick_line_2_font_size').val() + 'px' );	
		
	});
	
	$( document ).on( 'mousemove', function() {
		//Set TinyMCE default font size dynamically		
		
		//Text area 1
		$('#brick_text_line_1_ifr').contents().find('body').css( 'font-size', $('#brick_line_1_font_size').val() + 'px' );
		$('#brick_text_line_1_ifr').contents().find('body').css( 'line-height', $('#brick_line_1_font_size').val() + 'px' );	
		
		//Text area 2
		$('#brick_text_line_2_ifr').contents().find('body').css( 'font-size', $('#brick_line_2_font_size').val() + 'px' );
		$('#brick_text_line_2_ifr').contents().find('body').css( 'line-height', $('#brick_line_2_font_size').val() + 'px' );	
		
	});
	
	$( '#post' ).on( 'submit', function(event) {
		//this.submit();
		//Close lightbox on update if we are loaded in a lightbox	
		parent.close_lightbox();
		
	});
	
});