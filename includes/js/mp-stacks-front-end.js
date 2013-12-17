jQuery(document).ready(function($){
	$('.mp-stack-edit-link').magnificPopup({ 
	  type: 'iframe', 		
	});	
});

//Close the lightbox when the update button is clicked
function mp_stacks_close_lightbox(){
	
	//Close the iframe and reload the window
	jQuery(document).ready(function($){
		
		//Put bg over the iframe
		$('.mfp-bg').css('z-index', '9999999999999');
		
		//Close iframe and refresh
		$('.mfp-iframe').load(function(){
			$.magnificPopup.instance.close();
			location.reload();
		});
	});
}