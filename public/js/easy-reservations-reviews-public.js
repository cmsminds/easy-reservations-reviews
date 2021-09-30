jQuery( document ).ready( function( $ ) {
	'use strict';
	
	// Localized variables.
	var ajaxurl         = ERSRVR_Reviews_Public_Script_Vars.ajaxurl;
	var user_logged_in  = ERSRVR_Reviews_Public_Script_Vars.user_logged_in;
	var user_email      = ERSRVR_Reviews_Public_Script_Vars.user_email;
	var current_post_id = ERSRVR_Reviews_Public_Script_Vars.current_post_id;
	var user_criteria_ratings = [];
	jQuery(document).on( 'mouseout', '.rating__label', function( evt ) {
		// evt.preventDefault();
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		// var closest_criteria  = $( this ).closest( '.rating-group' ).data( 'criteria' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().removeClass( 'fill_star_hover' );
		$( 'label.rating__label' ).removeClass( 'fill_star_hover' );
	});

	// check user hover on which star and add class till starts
	jQuery( document ).on( 'mouseover', '.rating__label', function( evt ) {
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().addClass( 'fill_star_hover' );
		$( 'label.rating__label' ).removeClass( 'fill_star_hover' );
	} );

	// check user click on which star and add class till starts
	jQuery( document ).on( 'click', '.rating__label', function() {
		// evt.preventDefault();
		$( 'label.rating__label' ).removeClass( 'fill_star_click' );
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		var closest_criteria  = criteria_input.closest( '.rating-group' ).attr( 'id' );
		// console.log( $( '#' + closest_criteria ) );
		$( '#' + closest_criteria + ' .rating__input' ).removeClass( 'fill_star_click' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().addClass( 'fill_star_click' );
		var rating = parseInt( criteria_input.val() );
		// $( 'label.rating__label' ).removeClass( 'fill_star_click' );

		// Check if criteria to add already exists in the array.
		var existing_criteria_key = $.map( user_criteria_ratings, function( val, i ) {
			if ( val.closest_criteria === closest_criteria ) {
				return i;
			}
		} );

		// If the key is found.
		if ( 0 < existing_criteria_key.length ) {
			user_criteria_ratings.splice( existing_criteria_key[0], 1 );
		}

		// Push the element in the array.
		user_criteria_ratings.push( {
			closest_criteria: closest_criteria,
			rating: rating,
		} );

		// console.log( 'user_criteria_ratings', user_criteria_ratings );
	} );
	// submit revie form.
	jQuery(document).on( 'click', '.ersrvr_btn_submit', function( evt ) {
		evt.preventDefault();
		var this_button = $( this );
		var useremail = user_email;
		var given_rating_star_array = user_criteria_ratings;
		// Send the AJAX now.
		block_element( this_button );
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'ersrvr_submit_reviews',
				useremail: useremail,
				user_criteria_ratings: given_rating_star_array,
				current_post_id: current_post_id
			},
			success: function ( response ) {
				// Check for invalid ajax request.
				if ( 0 === response ) {
					console.log( 'easy reservations: invalid ajax request' );
					return false;
				}

			},
		} );
	} );
	/**
	 * Check if a string is valid.
	 *
	 * @param {string} $data
	 */
	 function is_valid_string( data ) {
		if ( '' === data || undefined === data || ! isNaN( data ) || 0 === data ) {
			return -1;
		} else {
			return 1;
		}
	}

	/**
	 * Check if a number is valid.
	 *
	 * @param {number} $data
	 */
	function is_valid_number( data ) {
		if ( '' === data || undefined === data || isNaN( data ) || 0 === data ) {
			return -1;
		} else {
			return 1;
		}
	}

	/**
	 * Block element.
	 *
	 * @param {string} element
	 */
	function block_element( element ) {
		element.addClass( 'non-clickable' );
	}

	/**
	 * Unblock element.
	 *
	 * @param {string} element
	 */
	function unblock_element( element ) {
		element.removeClass( 'non-clickable' );
	}

	/**
	 * Get query string parameter value.
	 *
	 * @param {string} string
	 * @return {string} string
	 */
	function get_query_string_parameter_value( param_name ) {
		var url_string = window.location.href;
		var url        = new URL( url_string );
		var val        = url.searchParams.get( param_name );

		return val;
	}
} );