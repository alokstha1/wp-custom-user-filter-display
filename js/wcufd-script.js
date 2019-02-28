jQuery( document ).ready( function(){

	/**
	* Trigger form submit event for filter
	*/
	jQuery('form#wcufd-custom-filter').on( 'submit', function(e){
		e.preventDefault();
		var formdata	= jQuery(this).serialize();

		trigger_filter( formdata );
	});

	/**
	* Trigger click event for pagination url.
	*/
	jQuery('body').on( 'click', '#wcufd-pagination a.page-numbers', function(e){
		e.preventDefault();
		var url			= jQuery(this).attr('href'), // get the href attribute on click.
			url_param 	= getUrlParam(url),
			pagedNumber	= ( typeof url_param !== 'undefined') ? url_param : 1, // check whether returned value is valid or not.
			paged 		= parseInt(pagedNumber), // parses a string and returns an integer.
			formdata	= jQuery('form#wcufd-custom-filter').serialize(), // serialize form data.
			param		= {
				paged: paged
			};

			param 		= formdata + '&' + jQuery.param( param ); // concatenate the formdata with this event parameter.

			trigger_filter( param );
	});

	/**
	* Function to retrieve pagenumber from its pagination url.
	*/
	function getUrlParam(url) {
		// get query string from url (optional) or window
		var pagedNumber = url ? url.split('?paged=')[1] : window.location.search.slice(1);
		return pagedNumber;
	}

	/**
	* Function that triggers ajax to filter user
	*/
	function trigger_filter( param ) {

		var action = {
			'action': 'filter_user'
		};
		jQuery('.wcufd-loading').show();
		param 		= param + '&' + jQuery.param( action );
		jQuery.ajax({
			url : wcufd_vars.ajaxurl,
			type: 'POST',
			data: param,
			dataType : 'JSON',
			success: function( response ) {
				jQuery('.wcufd-loading').hide();
				if ( response.status ) {
					jQuery('.wcufd-table-list').html( response.response_html );
				} else {
					alert( response.response_html );
				}
			},
			error: function() {
				alert('Something went wrong!. Please try again.');
			}
		});
	}
});