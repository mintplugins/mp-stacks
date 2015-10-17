jQuery(document).ready(function($){
	
	/**
	 * Load more posts into a Grid.
	 *
	 */
	$( document ).on( 'click', '.mp-stacks-grid-load-more-button', function(event){
		
		event.preventDefault();
		
		var mp_stacks_grid_post_id = $(this).attr( 'mp_stacks_grid_post_id' );
		
		// Use ajax to load more posts
		var postData = {
			action: 'mp_stacks_' + $(this).attr( 'mp_stacks_grid_ajax_prefix' ) + '_load_more',
			mp_stacks_grid_post_id: mp_stacks_grid_post_id,
			mp_stacks_grid_offset: $(this).attr( 'mp_stacks_grid_brick_offset' ),
			mp_stacks_grid_orderby: $(this).attr( 'mp_stacks_grid_orderby' ),
		}
		
		//Are we using Masonry?
		var masonry_on = eval( 'masonry_grid_' + $(this).attr( 'mp_stacks_grid_post_id' ) );
				
		var the_grid_container = $( '#mp-brick-' + mp_stacks_grid_post_id + ' .mp-stacks-grid' );
		var the_button_container = $( '#mp-brick-' + mp_stacks_grid_post_id + ' .mp-stacks-grid-load-more-container' );
		var the_after_container = $( '#mp-brick-' + mp_stacks_grid_post_id + ' .mp-stacks-grid-after' );
		
		//Replace the load more button with an animation to show it is loading more
		$(this).hide();
		the_button_container.find( '.mp-stacks-grid-load-more-animation-container').show();
		
		//Ajax load more posts
		$.ajax({
			type: "POST",
			data: postData,
			dataType:"json",
			url: mp_stacks_frontend_vars.ajaxurl,
			success: function (response) {
				
				var newitems = $(response.items);
				newitems.css('visibility', 'hidden' );
				
				//Add the new items to the page
				if ( masonry_on ){
					the_grid_container.append(newitems).imagesLoaded( function(){ 
					
						the_grid_container.append(newitems).isotope( 'appended', newitems ).isotope('layout');
																	
						//Add the updated "Load More" button to the page
						the_button_container.replaceWith(response.button);
						
						//Add the animation trigger which resets animations on newly added items
						the_after_container.html(response.animation_trigger);
												
						var wait_for_masonry_to_kick_in = setInterval( function(){ 
						
							newitems.css('visibility', 'visible' );
							
							the_grid_container.isotope('layout');
														
							clearInterval( wait_for_masonry_to_kick_in );
							//Refresh waypoints to reflect new page size
							Waypoint.refreshAll();
						}, 500);
												
					});
				}
				else{
					
					the_grid_container.append(newitems);
					newitems.css('visibility', 'visible' );
					//Add the updated "Load More" button to the page
					the_button_container.replaceWith(response.button);
					//Add the animation trigger which resets animations on newly added items
					the_after_container.html(response.animation_trigger);
					
					var wait_for_masonry_to_kick_in = setInterval( function(){ 
						clearInterval( wait_for_masonry_to_kick_in );
						//Refresh waypoints to reflect new page size
						Waypoint.refreshAll();
					}, 500);
						
				}
			
			}
		}).fail(function (data) {
			console.log(data);
		});	
		
	});
	
	var mp_stacks_grid_filter_value = '*';
	
	//Initialize Grid Isotopes
	$( '.mp-stacks-grid-isotope' ).imagesLoaded(function(){
		$('.mp-stacks-grid-isotope').isotope({
		  // main isotope options
		  itemSelector: '.mp-stacks-grid-item-masonry',
		  layoutMode: 'masonry',
		  gutterWidth: 0,
		  filter: mp_stacks_grid_filter_value
		});
		
	});
	
	//Re-Initialize Grid Isotopes when Ajax is done
	$( document ).ajaxComplete(function() {
		
		$( '.mp-stacks-grid-isotope' ).imagesLoaded(function(){

			//Then relayout isotope with the user's currently chosen filter
			$('.mp-stacks-grid-isotope').isotope({
			  // main isotope options
			  itemSelector: '.mp-stacks-grid-item-masonry',
			  layoutMode: 'masonry',
			  gutterWidth: 0,
			  //filter: mp_stacks_grid_filter_value
			});	
				
			var wait_for_isotope_filter_to_kick_in = setInterval( function(){ 
				clearInterval( wait_for_isotope_filter_to_kick_in );
			
				//Refresh waypoints to reflect new page size
				Waypoint.refreshAll();
				var mp_stacks_grid_ajax_trigger_delay = setInterval( function(){ 
					clearInterval( mp_stacks_grid_ajax_trigger_delay );
					$(document).trigger( 'mp_stacks_grid_ajax_complete' );
				}, 500);
			}, 500);
		});
		
	});
	
	//Re-Initialize Grid Isotopes when page is resized
	$( document ).on( 'mp_stacks_resize_complete', function() {
		
		$( '.mp-stacks-grid-isotope' ).imagesLoaded(function(){
			$('.mp-stacks-grid-isotope').isotope({
			  // main isotope options
			  itemSelector: '.mp-stacks-grid-item-masonry',
			  layoutMode: 'masonry',
			  gutterWidth: 0,
			  //filter: mp_stacks_grid_filter_value
			});
				
			var wait_for_isotope_filter_to_kick_in = setInterval( function(){ 
				clearInterval( wait_for_isotope_filter_to_kick_in );
				//Refresh waypoints to reflect new page size
				Waypoint.refreshAll();
				var mp_stacks_grid_ajax_trigger_delay = setInterval( function(){ 
					clearInterval( mp_stacks_grid_ajax_trigger_delay );
					$(document).trigger( 'mp_stacks_grid_ajax_complete' );
				}, 500);
			}, 500);
		});
		
	});
	
	//Filter grid items when filter button is clicked		
	$( document ).on( 'click', '.mp-stacks-grid-isotope-sort-container > div', function() {
		mp_stacks_grid_filter_value = $(this).attr('data-filter');
		$(this).parent().parent().find( '.mp-stacks-grid-isotope' ).isotope('layout').isotope({ filter: mp_stacks_grid_filter_value } );
	  
		var wait_for_isotope_filter_to_kick_in = setInterval( function(){ 
			clearInterval( wait_for_isotope_filter_to_kick_in );
			//Refresh waypoints to reflect new page size
			Waypoint.refreshAll();
		}, 500);

	});
	
	//Filter grid items when filter dropdown is changed
	$(document).on( 'change', '.mp-stacks-grid-isotope-sort-select', function(){
		var mp_stacks_grid_filter_value = $(this).val();
		$(this).parent().parent().find( '.mp-stacks-grid-isotope' ).isotope('layout').isotope({ filter: mp_stacks_grid_filter_value });
		
		var wait_for_isotope_filter_to_kick_in = setInterval( function(){ 
			clearInterval( wait_for_isotope_filter_to_kick_in );
			//Refresh waypoints to reflect new page size
			Waypoint.refreshAll();
		}, 500);
		
	});
	
	//When the orderby dropdown is changed, refresh the page with the new order set
	$(document).on( 'change', '.mp-stacks-grid-orderby-select', function(){
		
		var mp_stacks_order_url = $('option:selected', this).attr('orderby_url');
		
		window.location.href = mp_stacks_order_url;
		
	});
	
	//In Grids, remove the title attributes upon mouse over
    $( document ).on( 'mouseover', '.mp-stacks-grid a', function( event ){

		// Get the current title
		var title = $(this).attr("title");
		
		// Store it in a temporary attribute
		$(this).attr("tmp_title", title);

		// Set the title to nothing so we don't see the tooltips
		$(this).attr("title","");
	});
	//And add it back upon mouse out
	$( document ).on( 'mouseout', '.mp-stacks-grid a', function( event ){

		// Retrieve the title from the temporary attribute
		var title = $(this).attr("tmp_title");

		// Return the title to what it was
		$(this).attr("title", title);
	});
	
	//When any Isotope button is clicked, add an active class so it can be styled. Also remove any existing active classes
	$( document ).on( 'click', '.mp-stacks-grid-isotope-button', function(){
		$( '.mp-stacks-grid-isotope-button' ).removeClass( 'mp-stacks-isotope-filter-button-active' );
		$( this ).addClass( 'mp-stacks-isotope-filter-button-active' );
	});
	
});
