jQuery( document ).ready( function( $ ) {
	'use strict';

	// Localized variables.
	var review_criterias                = ERSRVR_Reviews_Script_Vars.review_criterias;
	var add_criterias_promptbox_text    = ERSRVR_Reviews_Script_Vars.add_criterias_promptbox_text;
	var remove_criterias_promptbox_text = ERSRVR_Reviews_Script_Vars.add_criterias_promptbox_text;
	var review_criterias_arr = [];
	for( var i in review_criterias ) {
		review_criterias_arr.push( review_criterias[i] );
	}
	/**
	 * Append a new criteria for review.
	 */
	jQuery( document ).on( 'click', '.ersrv_add_more_criterias', function( evt ) {
		evt.preventDefault();
		// Get the criteria.
		var criteria_name = prompt( add_criterias_promptbox_text );

		// Exit, if the criteria is invalid.
		if ( -1 === is_valid_string( criteria_name ) ) {
			return false;
		}

		var criteria_slug     = criteria_name.toLowerCase();
		criteria_slug         = criteria_slug.replace(/ /g, "-");
		review_criterias_arr.push(criteria_slug);
		// console.log( 'review_criterias_arr', review_criterias_arr );

		// Append the select option.
		$( '#ersrv_submit_review_criterias' ).append( '<option value="' + criteria_slug + '">' + criteria_name + '</option>' );
		$("#ersrv_submit_review_criterias").val(review_criterias_arr); 
	} );
	/**
	 * Remove  criteria for review.
	 */
	jQuery( document ).on( 'click', '.ersrv_remove_criterias', function( evt ) {
		evt.preventDefault();
		// Get the criteria.
		var criteria_name = prompt( remove_criterias_promptbox_text );
		// Exit, if the criteria is invalid.
		if ( -1 === is_valid_string( criteria_name ) ) {
			return false;
		}
		var criteria_slug     = criteria_name.toLowerCase();
		criteria_slug         = criteria_slug.replace(/ /g, "-");
		$('#ersrv_submit_review_criterias option[value="' + criteria_slug + '"]').remove();
		
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
