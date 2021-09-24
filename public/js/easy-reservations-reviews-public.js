jQuery( document ).ready( function( $ ) {
	'use strict';

	var favorite = [];
	jQuery(document).on( 'mouseout', '.rating__label', function( evt ) {
		// evt.preventDefault();
		var this_label        = $( this );
		var criteria_input    = this_label.next( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		var closest_criteria  = $( this ).closest( '.rating-group' ).data( 'criteria' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().removeClass( 'fill_star_hover' );
		$( 'label.rating__label' ).removeClass( 'fill_star_hover' );
	});

	/**
	 * 
	 */
	jQuery( document ).on( 'mouseover', '.rating__label', function( evt ) {
		var this_label        = $( this );
		var criteria_input    = this_label.next( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		var closest_criteria  = $( this ).closest( '.rating-group' ).data( 'criteria' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().addClass( 'fill_star_hover' );
		$( 'label.rating__label' ).removeClass( 'fill_star_hover' );
	} );
	
	jQuery(document).on( 'click', '.rating__label', function( evt ) {
		// evt.preventDefault();
		$( 'label.rating__label' ).removeClass( 'fill_star_click' );
		var this_label        = $( this );
		var criteria_input    = this_label.next( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		var closest_criteria  = $( this ).closest( '.rating-group' ).attr( 'id' );
		$( '#' + closest_criteria + ' .rating__input' ).removeClass( 'fill_star_click' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().addClass( 'fill_star_click' );
		var ratings = criteria_input.val();
		$( 'label.rating__label' ).removeClass( 'fill_star_click' );
		console.log( ratings );
		favorite.push( {
			closest_criteria: closest_criteria, 
			criteria_review_value: ratings,
		} );
	});
	console.log(favorite);
	jQuery(document).on( 'click', '.ersrvr_btn_submit', function( evt ) {
		evt.preventDefault();
		console.log(favorite);
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