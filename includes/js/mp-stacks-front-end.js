jQuery(document).ready(function($){
	$('.mp-stack-edit-link, .mp-brick-add-before-link, .mp-brick-add-after-link, .mp-stack-reorder-bricks, .mp-brick-add-new-link').magnificPopup({ 
		type: 'iframe', 	
		
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