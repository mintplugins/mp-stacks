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
	
});