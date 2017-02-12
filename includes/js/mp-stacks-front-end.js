//Create a global scoped variable that will contain the magnific popup instance.
var mp_stacks_magnificPopup;

//When the document is ready...
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

	mp_stacks_magnificPopup = $.magnificPopup.instance;

	/**
	 * Any Stacks that aren't added as Shortcodes (widgets, other, etc) need to have their CSS moved from the footer into the page <head>
	 *
	 */
	$( '#mp-stacks-extra-stacks-css' ).appendTo( $( 'head' ) );

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
		if ( window.innerWidth <= 600 && popup_source.indexOf("mp-stacks-minimal-admin") == 0 ){
			window.location.href = popup_source;
			return;
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

		mp_stacks_magnificPopup.open({

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
			$( 'body' ).attr( 'mp-stack-current-id', $(this).attr( 'mp-stack-id' ) );
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
			mp_stacks_magnificPopup.open({

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
		mp_stacks_magnificPopup.open({

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
		if( $(this).find('.mp-brick-edit-link').attr('href') ){
			//Call the function which opens our customized magnific popup for mp stacks
			mp_stacks_magnific_editor($(this).find('.mp-brick-edit-link').attr('href'));

			//Put the Brick's ID in an Attribute for the popup window so that ajax can refresh the Brick upon save.
			$( 'body' ).attr( 'mp-brick-current-id', $(this).find( '.mp-brick-edit-link' ).attr( 'mp-brick-id' ) );
			$( 'body' ).attr( 'mp-stack-current-id', $(this).find( '.mp-brick-edit-link' ).attr( 'mp-stack-id' ) );
		}
	});

	/**
	 * Upon hash change, perform smooth scroll upon page load when a Brick's URL (hash) is in the page URL
	 *
	 */
	$(window).on( 'load hashchange', function() {
    	var mp_stacks_hash = window.location.hash.replace("#", "");
		mp_stacks_hash = mp_stacks_hash ? mp_stacks_hash : 'NULL';

		var target = $('[mp_stacks_brick_target=' + mp_stacks_hash +']');

		if (target.length) {

			$('html,body').animate({
				scrollTop: target.offset().top
			}, 500, function(){
				//window.location.hash = href;
			});

		return false;

		}
		else{
			//console.log('No bricks found with this hash:' + mp_stacks_hash +']');
		}
	})

	/**
	 * Perform smooth scroll when brick's achored are clicked to
	 *
	 */
	 $( 'a[href*="#"]:not([href="#"])' ).click( function() {
		var href = $.attr(this, 'href');
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var mp_stacks_hash = this.hash.slice(1);
			var target = $('[mp_stacks_brick_target=' + this.hash.slice(1) +']');
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

//Close the lightbox when the update button is clicked and either refresh the page - or refresh the updated Brick using ajax.
function mp_stacks_close_lightbox( action_name ){

	//Close the iframe and reload the window
	jQuery(document).ready(function($){

		//Hide the Brick Editor
		$('.mfp-iframe, .mfp-close').hide();

		$('.mfp-content').css( 'width', 'initial' );
		$('.mfp-content').css( 'height', 'initial' );

		$('.mfp-content').prepend('<div class="mp-stacks-brick-updating" style="color:#fff; visibility:hidden; text-align: center;"><div class="mp-stacks-updating-message">' + mp_stacks_frontend_vars.updating_message + '</div><img width="100px" src="' + mp_stacks_frontend_vars.stacks_plugin_url + 'assets/images/Stacks-Icon-Gif.gif" /></div>');

		$('.mfp-content .mp-stacks-brick-updating').css('visibility', 'visible');

		//If we just deleted a brick
		if( action_name == 'brick_deleted' ){
			//Close iframe and refresh
			$('.mfp-iframe').load(function(){
				location.reload();
			});
		}

		//Check if a brick ID attribute exists in the body tag
		var brick_id = $( 'body' ).attr( 'mp-brick-current-id' ) ? $( 'body' ).attr( 'mp-brick-current-id' ) : false;
		var stack_id = $( 'body' ).attr( 'mp-stack-current-id' ) ? $( 'body' ).attr( 'mp-stack-current-id' ) : false;

		//If we added a new brick - it doesn't exist on the page yet so refresh the whole thing
		if ( !brick_id ){

			//Close iframe and refresh
			$('.mfp-iframe').load(function(){
				location.reload();
			});

		}
		else{

			//If we got this far, we just edited a Brick that was already in existence on the current page.
			$('.mfp-iframe').load(function(){

				mp_stacks_queried_object_id = $('body').attr('class').match(/\bmp-stacks-queried-object-id-(\d+)\b/) ? $('body').attr('class').match(/\bmp-stacks-queried-object-id-(\d+)\b/)[1] : false;

				//Reload the Brick that was updated using ajax
				var postData = {
					action: 'mp_stacks_ajax_brick',
					mp_stacks_stack_id_of_brick: stack_id,
					mp_stacks_ajax_brick_id: brick_id,
					mp_stacks_queried_object_id: mp_stacks_queried_object_id
				}

				//console.log( mp_stacks_queried_object_id );

				//Ajax load more posts
				$.ajax({
					type: "POST",
					data: postData,
					dataType:"json",
					url: mp_stacks_frontend_vars.ajaxurl,
					success: function (response) {

						//console.log( 'Brick Updated' );

						if ( response.success ){
							
							//Update the brick on the page by passing the ajax response to our JS function.
							mp_stacks_load_ajax_brick( response, brick_id, false, false );

						}

						//Close the lightbox
						mp_stacks_magnificPopup.close();

						//Remove the current brick id from the body
						$( 'body' ).removeAttr( 'mp-brick-current-id' );
						$( 'body' ).removeAttr( 'mp-stack-current-id' );

					}
				}).fail(function (data) {
					console.log(data);
				});

			});
		}
	});

}

/**
 * Function which, when called, loads a brick with the ajax information returned containing the new brick's HTML, JS, and CSS.
 * The loaded brick will be placed on the page using the "load_container_id" argument. If given it will
 * load the Brick into the Container ID passed. If false, it will try and replace the Brick with the loaded Brick (or "Update" it).
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      function_name()
 * @param    array $ajax_response The response from the 'mp_stacks_brick_ajax' function.
 * @param    int   $brick_id The ID of the brick that is being loaded
 * @param    string $load_container_id If this is not empty, it will load the brick into this container
 * @param    string $load_style This is the way that the Brick will be loaded into the $load_container_id. Options are "append", "prepend", "html".
 * @return   void
 */

function mp_stacks_load_ajax_brick( ajax_response, brick_id, load_container_id, load_style ){

	jQuery(document).ready(function($){

		var this_brick_css_string = '#mp-brick-css-' + brick_id;
		var this_brick_string = '#mp-brick-' + brick_id;

		$( document ).off( '.mp-brick-' + brick_id, "**" );

		$( '.mp-brick-' + brick_id ).each( function(){
			$( this ).off();
		});

		//This trigger is used to fire any functions that need to happen before a brick is updated via ajax
		$( document ).trigger( 'mp_stacks_before_ajax_brick_update', [this_brick_string] );

		//Loop through all the enqueued css stylesheets that were passed back from ajax
		$.each( ajax_response.enqueued_css_styles_array, function( stylesheet_counter, stylesheet_href ) {

			//If this stylesheet has not already been output to the page
			if ( $('link[href="' + stylesheet_href + '"]').length === 0 ){

				//Output this stylesheet link into the document head so the browser loads it
				$( 'head' ).append( '<link rel="stylesheet" href="' + stylesheet_href + '" />' );

			}

		});

		//Put the Brick's CSS into the <head> of this document
		if ( $( this_brick_css_string ) ){
			$( this_brick_css_string ).replaceWith( ajax_response.brick_css );
		}
		else{
			$( 'head' ).append( ajax_response.brick_css );
		}

		//Loop through all inline css scripts
		if ( ajax_response.inline_css_styles_array ){
			$.each( ajax_response.inline_css_styles_array, function( css_id, css_output ) {

				//If this inline css has not already been output to the page
				if ( $('style[mp_stacks_inline_style="' + css_id + '"]').length === 0 ){

					//Add this inline css to the <head> using the updated, ajax-passed code
					$( 'head' ).append( css_output );

				}


			});
		}

		//If we should load this Brick's HTML into a container on the page
		if ( load_container_id ){
			if ( load_style == 'append' ){
				$( load_container_id ).append( ajax_response.brick_html );
			}
			else if( load_style == 'prepend' ){
				$( load_container_id ).prepend( ajax_response.brick_html );
			}
			else if( load_style == 'html' ){
				$( load_container_id ).html( ajax_response.brick_html );
			}
		}
		//If we should replace a Brick that is already on the page
		else{
			$( this_brick_string ).replaceWith( ajax_response.brick_html );
		}

		//Loop through all the enqueued scripts that were passed back from ajax
		if ( ajax_response.footer_enqueued_scripts_array ){

			$.each( ajax_response.footer_enqueued_scripts_array, function( script_counter, script_output_src ) {

				//If this script has not already been output to the page
				if ( $('script[src="' + script_output_src + '"]').length === 0 ){

					//Output this script into the footer so the browser loads it
					$( 'head' ).append( '<script type="text/javascript" src="' + script_output_src + '"></script>' );

				}
				//if this script has already been output to the page
				else{

					//If this script should be refreshed when a brick gets updated via ajax
					if ( $('script[src*="mp_stacks_refresh_this_script_upon_brick_update"]').length !== 0 ){
						//Replace the script that already exists with itself so that it re-runs
						$('script[src="' + script_output_src + '"]').replaceWith( '<script type="text/javascript" src="' + script_output_src + '">    /script>' );
					}

				}

			});
		}

		//Loop through all inline js scripts
		if ( ajax_response.footer_inline_scripts_array ){
			$.each( ajax_response.footer_inline_scripts_array, function( script_id, script_output ) {

				//Remove this inline script if it already existed on this page previously
				$( 'body #' + script_id ).remove();

				//Add this inline script back using the updated, ajax-passed code
				$( 'body' ).append( script_output );

			});
		}

		//This trigger is used to fire any functions that need to happen after a brick is updated via ajax
		$( document ).trigger( 'mp_stacks_after_ajax_brick_update', [this_brick_string] );

		//jQuery Trigger which Add-Ons can use to update themselves when a Brick is updated.
		$( document ).trigger( 'mp_stacks_brick_loaded_via_ajax', [brick_id] );

	});
}

/**
 * Function which, when called, loads a Stack with the ajax information returned containing the Stacks's HTML, JS, and CSS.
 * The loaded Stack will be placed on the page using the "load_container_id" argument. If given it will
 * load the Stack into the Container ID passed. If false, it will try and replace the Stack with the loaded Stack (or "Update" it).
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      function_name()
 * @param    array $ajax_response The response from the 'mp_stacks_brick_ajax' function.
 * @param    int   $stack_id The ID of the Stack that is being loaded
 * @param    string $load_container_id If this is not empty, it will load the brick into this container
 * @param    string $load_style This is the way that the Brick will be loaded into the $load_container_id. Options are "append", "prepend", "html".
 * @return   void
 */

function mp_stacks_load_ajax_stack( ajax_response, stack_id, load_container_id, load_style ){

	jQuery(document).ready(function($){

		var this_stack_css_string = '.mp-stack-css-' + stack_id;
		var this_stack_string = '#mp_stack_' + stack_id;

		//This trigger is used to fire any functions that need to happen before a stack is updated via ajax
		$( document ).trigger( 'mp_stacks_before_ajax_stack_update', [this_stack_string] );

		//Loop through all the enqueued css stylesheets that were passed back from ajax
		$.each( ajax_response.enqueued_css_styles_array, function( stylesheet_counter, stylesheet_href ) {

			//If this stylesheet has not already been output to the page
			if ( $('link[href="' + stylesheet_href + '"]').length === 0 ){

				//Output this stylesheet link into the document head so the browser loads it
				$( 'head' ).append( '<link rel="stylesheet" href="' + stylesheet_href + '" />' );

			}

		});

		//Remove any style tags that are already assigned to this stack.
		$( this_stack_css_string ).remove();
		//Put the Stacks's CSS into the <head> of this document
		$( 'head' ).append( ajax_response.stack_css );


		//Loop through all inline css scripts
		if ( ajax_response.inline_css_styles_array ){
			$.each( ajax_response.inline_css_styles_array, function( css_id, css_output ) {

				//If this inline css has not already been output to the page
				if ( $('style[mp_stacks_inline_style="' + css_id + '"]').length === 0 ){

					//Add this inline css to the <head> using the updated, ajax-passed code
					$( 'head' ).append( css_output );

				}


			});
		}

		//If we should load this Brick's HTML into a container on the page
		if ( load_container_id ){
			if ( load_style == 'append' ){
				$( load_container_id ).append( ajax_response.stack_html );
			}
			else if( load_style == 'prepend' ){
				$( load_container_id ).prepend( ajax_response.stack_html );
			}
			else if( load_style == 'html' ){
				$( load_container_id ).html( ajax_response.stack_html );
			}
		}
		//If we should replace a Brick that is already on the page
		else{
			$( this_stack_string ).replaceWith( ajax_response.stack_html );
		}

		//Loop through all the enqueued scripts that were passed back from ajax
		if ( ajax_response.footer_enqueued_scripts_array ){

			$.each( ajax_response.footer_enqueued_scripts_array, function( script_counter, script_output_src ) {

				//If this script has not already been output to the page
				if ( $('script[src="' + script_output_src + '"]').length === 0 ){

					//Output this script into the footer so the browser loads it
					$( 'head' ).append( '<script type="text/javascript" src="' + script_output_src + '"></script>' );

				}
				//if this script has already been output to the page
				else{

					//If this script should be refreshed when a brick gets updated via ajax
					if ( $('script[src*="mp_stacks_refresh_this_script_upon_brick_update"]').length !== 0 ){
						//Replace the script that already exists with itself so that it re-runs
						$('script[src="' + script_output_src + '"]').replaceWith( '<script type="text/javascript" src="' + script_output_src + '">    /script>' );
					}

				}

			});
		}

		//Loop through all inline js scripts
		if ( ajax_response.footer_inline_scripts_array ){
			$.each( ajax_response.footer_inline_scripts_array, function( script_id, script_output ) {

				//Remove this inline script if it already existed on this page previously
				$( 'body #' + script_id ).remove();

				//Add this inline script back using the updated, ajax-passed code
				$( 'body' ).append( script_output );

			});
		}

		//jQuery Trigger which Add-Ons can use to update themselves when a Brick is updated.
		$( document ).trigger( 'mp_stacks_brick_loaded_via_ajax', [stack_id] );

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
