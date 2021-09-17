jQuery( document ).ready( function( $ ) {
	'use strict';

	/**
	 * Append a new criteria for review.
	 */
	jQuery( document ).on( 'click', '.ersrv_add_more_criterias', function( evt ) {
		evt.preventDefault();
		// Get the criteria.
		var criteria_name = prompt( 'Criteria:' );

		// Exit, if the criteria is invalid.
		if ( -1 === is_valid_string( criteria_name ) ) {
			return false;
		}

		// Check if the criteria to be added doesn't already exist.

		// Push the slug into the array.
		var new_criteria = new Option( criteria_name, criteria_name, true, true );

		// Append the select option.
		$( '#ersrv_submit_review_criterias' ).append( new_criteria ).trigger( 'change' );
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
	
} );
