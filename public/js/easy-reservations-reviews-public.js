jQuery( document ).ready( function( $ ) {
	'use strict';
	jQuery(document).on( 'click', '.rating__input', function( evt ) {
		evt.preventDefault();
		alert("1");
        $(this).prop("checked", true);
    });
	jQuery(document).on( 'click', '.ersrvr_btn_submit', function( evt ) {
		evt.preventDefault();
		var favorite = [];
		var closest_criteria;
		var criteria_radio_value;
        $.each($(".rating__input:checked"), function(i, value){
			closest_criteria = $(this).closest('.rating-group').data('criteria');
			criteria_radio_value = $(this).val()
			favorite.push({
				closest_criteria: closest_criteria, 
				radio_value:  criteria_radio_value,
			});
        });
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