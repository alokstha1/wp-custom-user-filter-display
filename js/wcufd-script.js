jQuery( document ).ready( function(){
	//Trigger form submit event for filter
	jQuery('form#wcufd-custom-filter').on('submit', function(e){
		e.preventDefault();
		var formdata	= jQuery(this).serialize();
		trigger_filter( formdata );
	});

	//Trigger click event for pagination url.
	jQuery('body').on( 'click', '#wcufd-pagination a.page-numbers', function(e){
		e.preventDefault();
		var url			= jQuery(this).attr('href'), // get the href attribute on click.
			url_param 	= getUrlParam(url),
			pagedNumber	= (typeof url_param !== 'undefined') ? url_param : 1, // check whether returned value is valid or not.
			paged 		= parseInt(pagedNumber), // parses a string and returns an integer.
			formdata	= jQuery('form#wcufd-custom-filter').serialize(), // serialize form data.
			param		= {
				paged: paged
			};
			param 		= formdata + '&' + jQuery.param(param); // concatenate the formdata with this event parameter.
			trigger_filter(param);
			history.pushState( null, null, url); //updates the url when changing the pagination.
	});

	//Function to retrieve pagenumber from its pagination url.
	function getUrlParam(url) {
		// get query string from url (optional) or window
		var split_array		= url.split('/'), //split url into array with respect to `/`
			last_element	= split_array[split_array.length-1], //get the last element of the split array
			pagedNumber 	= last_element ? last_element : 1;

		return pagedNumber;
	}

	// Function that triggers ajax to filter user
	function trigger_filter(param) {
		if (wcufd_vars.current_user_can) {
			jQuery('.wcufd-loading').show();
			jQuery.ajax({
				url : wcufd_vars.ajaxurl,
				type: 'POST',
				data: param,
				dataType : 'JSON',
				success: function(response) {
					jQuery('.wcufd-loading').hide();
					if ( response.status ) {
						jQuery('.wcufd-table-list').html(response.response_html);
					} else {
						alert(response.response_html);
					}
				},
				error: function() {
					alert('Something went wrong!. Please try again.');
				}
			});
		} else {
			alert('You must be logged in as Admin to perform filter!');
		}
	}
});