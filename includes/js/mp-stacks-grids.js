jQuery(document).ready(function($){
	
	/**
	 * Load more posts into a Grid.
	 *
	 */
	$( document ).on( 'click', '.mp-stacks-grid-load-more-button', function(event){
		
		event.preventDefault();
			
		// Use ajax to load more posts
		var postData = {
			action: 'mp_stacks_' + $(this).attr( 'mp_stacks_grid_ajax_prefix' ) + '_load_more',
			mp_stacks_grid_post_id: $(this).attr( 'mp_stacks_grid_post_id' ),
			mp_stacks_grid_offset: $(this).attr( 'mp_stacks_grid_brick_offset' ),
		}
		
		//Are we using Masonry?
		var masonry_on = eval( 'masonry_grid_' + $(this).attr( 'mp_stacks_grid_post_id' ) );
				
		var the_grid_container = $(this).parent().prev();
		var the_button_container = $(this).parent();
		var the_after_container = $(this).parent().parent().find('.mp-stacks-grid-after');
		
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
					
						the_grid_container.append(newitems).isotope( 'appended', newitems );
					
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
	
	//Initialize Grid Isotopes
	$( '.mp-stacks-grid-isotope' ).imagesLoaded(function(){
		$('.mp-stacks-grid-isotope').isotope({
		  // main isotope options
		  itemSelector: '.mp-stacks-grid-item-masonry',
		  layoutMode: 'masonry',
		  gutterWidth: 0
		});
	});
	
	//Re-Initialize Grid Isotopes when Ajax is done
	$( document ).ajaxComplete(function() {
		$('.mp-stacks-grid-isotope').isotope({
		  // main isotope options
		  itemSelector: '.mp-stacks-grid-item-masonry',
		  layoutMode: 'masonry',
		  gutterWidth: 0
		});
		
		$( '.mp-stacks-grid-isotope-button-all' ).trigger( 'click' );
		
		var wait_for_isotope_filter_to_kick_in = setInterval( function(){ 
			clearInterval( wait_for_isotope_filter_to_kick_in );
			//Refresh waypoints to reflect new page size
			Waypoint.refreshAll();
		}, 500);
		
	});
	
	//Filter grid items when filter button is clicked		
	$( document ).on( 'click', '.mp-stacks-grid-isotope-sort-container > div', function() {
		var filterValue = $(this).attr('data-filter');
		$(this).parent().parent().find( '.mp-stacks-grid-isotope' ).isotope({ filter: filterValue } );
	  
		var wait_for_isotope_filter_to_kick_in = setInterval( function(){ 
			clearInterval( wait_for_isotope_filter_to_kick_in );
			//Refresh waypoints to reflect new page size
			Waypoint.refreshAll();
		}, 500);

	});
	
	//Filter grid items when filter dropdown is changed
	$(document).on( 'change', '.mp-stacks-grid-isotope-sort-select', function(){
		var selector = $(this).val();
		$(this).parent().parent().find( '.mp-stacks-grid-isotope' ).isotope({ filter: selector });
		
		var wait_for_isotope_filter_to_kick_in = setInterval( function(){ 
			clearInterval( wait_for_isotope_filter_to_kick_in );
			//Refresh waypoints to reflect new page size
			Waypoint.refreshAll();
		}, 500);
		
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
	
});