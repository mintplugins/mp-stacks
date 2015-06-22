jQuery(document).ready(function($){
	
	/**
	 * Creating a trigger that we can hook to when the screen is done resizing - rather than firing hundreds of times upon resize
	 *
	 */
	var mp_stacks_resize_timer;
	
	$(window).resize(function(){

		clearTimeout(mp_stacks_resize_timer);
		mp_stacks_resize_timer = setTimeout(mp_stacks_resize_end, 100);
		
	});
	
	/**
	 * This function fires when the screen is done resizing. Hook jquery functions to it.
	 *
	 */
	function mp_stacks_resize_end(){
	
		$(document).trigger( 'mp_stacks_resize_complete' );
		$('.mfp-content').trigger( 'mp_stacks_resize_complete' );
		
	}
	
	/**
	 * Modify the Magnific Popup to open using the popup source - and set sized and behaviours for Media like YouTube, JPGs, etc
	 *
	 */
	function mp_stacks_magnific_editor( popup_source ){
		
		var lightbox_type;
		var lightbox_main_class;
		var overflow_y = 'auto';
		var extension = popup_source.split('.').pop();
		
		//If the width of the screen is smaller than 600px, open the link in the parent window so it uses more of the screen. 
		//Popup content should utilize some sort of "Back Button" for these cases.
		if ( window.innerWidth <= 600 ){
			window.location.href = popup_source;
		}
		
		//Set the type of lightbox this is based on the content				
		switch(extension) {
			case 'jpg':
			case 'png':
			case 'gif':
			case 'jpeg':
			lightbox_type = 'image';
			break;
			case 'mp4':
			lightbox_type = 'iframe';
			lightbox_main_class = 'mp-stacks-iframe-16x9-video';
			break;
			default:
			lightbox_type = 'iframe';
			if ( ( popup_source.indexOf("youtube.com/watch") > -1 ) || ( popup_source.indexOf("vimeo.com/") > -1 ) || ( popup_source.indexOf("mp-stacks-direct-video-link") > -1 ) ){
				lightbox_main_class = 'mp-stacks-iframe-16x9-video';
				fixedContentPos = true;
				overflow_y = 'hidden';
			}
			else{
				lightbox_main_class = 'mp-stacks-iframe-full-screen';
			}
		}
		
		$.magnificPopup.open({
			
			items: {
				src: popup_source
			},
  
			type: lightbox_type,
			
			callbacks: {
				
				open: function() {
					// Will fire when popup is opened
				},
				close: function() {
					//Will fire the popup is closed
				},
			
			},
			iframe: {
				patterns: {
								
					youtube: {
						index: 'youtube.com/watch', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
						
						id: 'v=', // String that splits URL in a two parts, second part should be %id%
						// Or null - full URL will be returned
						// Or a function that should return %id%, for example:
						// id: function(url) { return 'parsed id'; } 
						
						src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0&modestbranding=1&showinfo=0&wmode=transparent&autohide=1' // URL that will be set as a source for iframe. 
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
				
				}
			},
			
			mainClass: lightbox_main_class,
			
			srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
			
			preloader: true,
			
			overflowY: overflow_y,
	
		}, 0);
	
	}

	/**
	 * Set the class names of links which should open a full-size magnific popup
	 *
	 */
	$(document).on('click', '.mp-brick-edit-link, .mp-brick-add-before-link, .mp-brick-add-after-link, .mp-brick-reorder-bricks, .mp-brick-add-new-link, .mp-stacks-lightbox-link', function(event){ 
		event.preventDefault();
		
		if ( $(this).attr('mp_lightbox_alternate_url') ){
			var lightbox_url = $(this).attr('mp_lightbox_alternate_url');
		}
		else{
			var lightbox_url = $(this).attr('href');	
		}
		
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_editor( lightbox_url );
		
		//If the user just clicked on the edit-brick button
		if ( $(this).hasClass('mp-brick-edit-link') ){
			//Put the Brick's ID in an Attribute for the popup window so that ajax can refresh the Brick upon save.
			$( 'body' ).attr( 'mp-brick-current-id', $(this).attr( 'mp-brick-id' ) );
		}
		
	});	
	
	/**
	 * Modify the Magnific Popup to open using the popup source - and set sized for height of content
	 *
	 */
	function mp_stacks_magnific_height_match( popup_source, width ){
		
		//If the width of the screen is smaller than 600px, open the link in the parent window so it uses more of the screen. 
		//Popup content should utilize some sort of "Back Button" for these cases.
		if ( window.innerWidth <= 600 ){
			window.location.href = popup_source;
		}
		else{
			$.magnificPopup.open({
				
				items: {
					src: popup_source
				},
				type: 'iframe',
				iframe: {
					markup: '<div class="mfp-iframe-height-match" style="width:100%; height:100%; max-width:' + width + ';">'+
					'<iframe class="mfp-iframe" frameborder="0" onload="javascript:mp_stacks_mfp_match_height(this);" style="width:100%; height:100%;" allowfullscreen></iframe>'+
					'<div class="mfp-close"></div>'+
					'</div>',
					patterns: {
									
						youtube: {
							index: 'youtube.com/watch', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
							
							id: 'v=', // String that splits URL in a two parts, second part should be %id%
							// Or null - full URL will be returned
							// Or a function that should return %id%, for example:
							// id: function(url) { return 'parsed id'; } 
							
							src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0&modestbranding=1&showinfo=0&wmode=transparent&autohide=1' // URL that will be set as a source for iframe. 
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
					
					}
				
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
	
	}
	
	/**
	 * Set items with the class 'mp-stacks-iframe-height-match-lightbox-link' to open a lightbox iframe matching the height of its contents 
	 * and at the width defined in its 'mfp-width' attribute
	 */		
	$(document).on( 'click', '.mp-stacks-iframe-height-match-lightbox-link', function( event ){
		
		event.preventDefault();
		
		if ( $(this).attr('mp_lightbox_alternate_url') ){
			var lightbox_url = $(this).attr('mp_lightbox_alternate_url');
		}
		else{
			var lightbox_url = $(this).attr('href');	
		}
		
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_height_match( lightbox_url, $( this ).attr( 'mfp-width' ) );
		
		//Set the mfp-content div to be the width we want for this popup
		$( '.mp-stacks-iframe-height-match .mfp-content' ).css( 'width', $( this ).attr( 'mfp-width' ) );
		
	});
	
	/**
	 * Modify the Magnific Popup to open at a custom set size
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
				'</div>',
				
				patterns: {
								
					youtube: {
						index: 'youtube.com/watch', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
						
						id: 'v=', // String that splits URL in a two parts, second part should be %id%
						// Or null - full URL will be returned
						// Or a function that should return %id%, for example:
						// id: function(url) { return 'parsed id'; } 
						
						src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0&modestbranding=1&showinfo=0&wmode=transparent&autohide=1' // URL that will be set as a source for iframe. 
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
				
				}
			
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
		
		if ( $(this).attr('mp_lightbox_alternate_url') ){
			var lightbox_url = $(this).attr('mp_lightbox_alternate_url');
		}
		else{
			var lightbox_url = $(this).attr('href');	
		}
		
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_custom_width_height( lightbox_url, $( this ).attr( 'mfp-width' ), $( this ).attr( 'mfp-height' ) );
		
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
	 * Upon Double Click, Open the Brick Editor
	 *
	 */
	$(document).on('dblclick', '.mp-brick', function(){ 
		//Call the function which opens our customized magnific popup for mp stacks
		mp_stacks_magnific_editor($(this).find('.mp-brick-edit-link').attr('href'));
		
		//Put the Brick's ID in an Attribute for the popup window so that ajax can refresh the Brick upon save.
		$( 'body' ).attr( 'mp-brick-current-id', $(this).find( '.mp-brick-edit-link' ).attr( 'mp-brick-id' ) );
	});	
	
	/**
	 * Perform smooth scroll when brick's achored are linked to
	 *
	 */
	$(function() {
	  $('a[href*=#]:not([href=#])').click(function() {
		var href = $.attr(this, 'href');
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
		  var target = $(this.hash);
		  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
		  if (target.length) {
			$('html,body').animate({
			  scrollTop: target.offset().top
			}, 500, function(){
				 window.location.hash = href;	
			});
			return false;
		  }
		}
	  });
	});
		
	/**
	 * jQuery Plugin to Change an Element Type
	 *
	 */
	$.fn.mp_stacks_changeElementType = function(newType) {
		
		var attrs = {};
	
		$.each(this[0].attributes, function(idx, attr) {
			attrs[attr.nodeName] = attr.value;
		});
	
		var newelement = $("<" + newType + "/>", attrs).append($(this).contents());
		this.replaceWith(newelement);
		return newelement;
	};

});

//Close the lightbox when the update button is clicked and either refresh the page - or rfresh the updated Brick using ajax.
function mp_stacks_close_lightbox(){
		
	//Close the iframe and reload the window
	jQuery(document).ready(function($){
		
		//Hide the Brick Editor		
		$('.mfp-iframe, .mfp-close').hide();
		
		$('.mfp-content').css( 'width', 'initial' );
		$('.mfp-content').css( 'height', 'initial' );
		
		$('.mfp-content').prepend('<div class="mp-stacks-brick-updating" style="color:#fff; visibility:hidden; text-align: center;"><div class="mp-stacks-updating-message">' + mp_stacks_frontend_vars.updating_message + '</div><img width="100px" src="' + mp_stacks_frontend_vars.stacks_plugin_url + 'assets/images/Stacks-Icon-Gif.gif" /></div>');
		
		$('.mfp-content .mp-stacks-brick-updating').css('visibility', 'visible');
			
		var brick_id = $( 'body' ).attr( 'mp-brick-current-id' ) ? $( 'body' ).attr( 'mp-brick-current-id' ) : false;
		
		var this_brick_css_string = '#mp-brick-css-' + brick_id;
		var this_brick_string = '#mp-brick-' + brick_id;
					
		//If we added a new brick - it doesn't exist on the page yet so refresh the whole thing
		if ( !brick_id ){
			
			//Close iframe and refresh
			$('.mfp-iframe').load(function(){
				location.reload();	
			})
			
		}
		else{
		
			//If we got this far, we just edited a Brick that was already in existence on the current page.
			$('.mfp-iframe').load(function(){
				
				//Reload the Brick that was updated using ajax
				var postData = {
					action: 'mp_stacks_update_brick',
					mp_stacks_update_brick_id: brick_id,
				}
				
				//Ajax load more posts
				$.ajax({
					type: "POST",
					data: postData,
					dataType:"json",
					url: mp_stacks_frontend_vars.ajaxurl,
					success: function (response) {
						
						console.log( 'Brick Updated' );
						//console.log(response.brick_html );
						
						if ( response.success ){
							
							//Put the Brick's CSS into the <head> of this document
							$( this_brick_css_string ).replaceWith( response.brick_css );
							
							//Replace the Brick's HTML 
							$( this_brick_string ).replaceWith( response.brick_html );
						
						}
						
						//Close the lightbox
						$.magnificPopup.instance.close();
						
						//Remove the current brick id from the body
						$( 'body' ).removeAttr( 'mp-brick-current-id' );
						
						//jQuery Trigger which Add-Ons can use to update themselves when a Brick is updated.
						$( document ).trigger( 'mp_stacks_brick_loaded_via_ajax', [brick_id] );
						
					}
				}).fail(function (data) {
					console.log(data);
				});	
					
			});
		}
	});
}

//This function can be used to set an iframe's height to match the height of its contents.
function mp_stacks_mfp_match_height(iframe) {
	
	jQuery(document).ready(function($){
		
		var iframeWin = iframe.contentWindow || iframe.contentDocument.parentWindow;
		if (iframeWin.document.body) {
			
			//We use an interval to loop the resize function several times to make sure the iframe's content is fully loaded
			var iframe_height_interval_counter = 1;
			var iframe_height_interval = setInterval( function(){
				
				iframe.height = iframeWin.document.documentElement.scrollHeight || iframeWin.document.body.scrollHeight;
				iframe_height_string = iframe.height + 'px';
				if ( iframe.height > 0 ){
					$('.mfp-iframe-height-match').animate( { 'height': iframe_height_string }, 200 );
					$('.mfp-content').animate( { 'height': iframe_height_string }, 200 );
				}
	
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

//Check for old versions of Browsers that suck
if(  !document.addEventListener  ){
	alert("Your Internet Browser is out of date and is at risk for being hacked and your personal information stolen. Please upgrade to a secure browser like Google Chrome or Firefox.");
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