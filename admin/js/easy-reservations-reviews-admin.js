jQuery( document ).ready( function( $ ) {
	'use strict';

	// Localized variables.
	var add_criteria_button_text         = ERSRVR_Reviews_Script_Vars.add_criteria_button_text;
	var add_criterias_promptbox_text     = ERSRVR_Reviews_Script_Vars.add_criterias_promptbox_text;
	var add_same_criteria_error          = ERSRVR_Reviews_Script_Vars.add_same_criteria_error;
	var existing_criteria_result         = ERSRVR_Reviews_Script_Vars.existing_criteria_result;
	var ajaxurl                          = ERSRVR_Reviews_Script_Vars.ajaxurl;
	var is_reservation_comment           = ERSRVR_Reviews_Script_Vars.is_reservation_comment;
	var reviewer_phone_field_label       = ERSRVR_Reviews_Script_Vars.reviewer_phone_field_label;
	var reviewer_phone                   = ERSRVR_Reviews_Script_Vars.reviewer_phone;
	var remove_attachment_confirm_text   = ERSRVR_Reviews_Script_Vars.remove_attachment_confirm_text;
	var media_uploader_modal_header      = ERSRVR_Reviews_Script_Vars.media_uploader_modal_header;
	var review_attachments_allowed_types = ERSRVR_Reviews_Script_Vars.review_attachments_allowed_types;

	// Global vars.
	var user_criteria_ratings = [];

	// Add phone field to the review form.
	if ( '1' === is_reservation_comment ) {
		var reviewer_email_td = $( 'input[name="newcomment_author_email"]' ).parent( 'td' );
		var reviewer_email_tr = reviewer_email_td.parent( 'tr' );
		reviewer_email_tr.addClass( 'reviewer-email-tr' );

		// Prepare the html now.
		var reviewer_phone_html = '<tr>';
		reviewer_phone_html    += '<td class="first"><label for="phone">' + reviewer_phone_field_label + '</label></td>';
		reviewer_phone_html    += '<td><input type="text" name="newcomment_author_phone" size="30" value="' + reviewer_phone + '" id="phone" required /></td>';
		reviewer_phone_html    += '</tr>';

		// Insert the HTML now.
		$( reviewer_phone_html ).insertAfter( 'tr.reviewer-email-tr' );
	}

	// Get the current section.
	var current_section = get_query_string_parameter_value( 'section' );
	if ( 1 === is_valid_string( current_section ) && 'reviews' === current_section ) {
		// Add a button just after the review criterias.
		var criteria_select2_class = $( '#ersrvr_submit_review_criterias' ).next( 'span.select2' ).attr( 'class' );
		criteria_select2_class     = '.' + criteria_select2_class.replace( / /g, '.' );
		$( '<a href="#" class="ersrv_add_more_criterias button">' + add_criteria_button_text + '</a>' ).insertAfter( criteria_select2_class );
		$( '.ersrv_add_more_criterias' ).css( 'margin-left', '5px' );
	}
	
	// Append a new criteria for review.
	$( document ).on( 'click', '.ersrv_add_more_criterias', function( evt ) {
		evt.preventDefault();
		// Get the criteria.
		var criteria_name = prompt( add_criterias_promptbox_text );

		// Exit, if the criteria is invalid.
		if ( -1 === is_valid_string( criteria_name ) ) {
			return false;
		}

		// Check if the criteria to be added doesn't already exist.
		var existing_cirterias_count = $( '#ersrvr_submit_review_criterias > option' ).length;
		var has_similar_criteria     = false;
		if ( 1 <= existing_cirterias_count ) {
			$( '#ersrvr_submit_review_criterias > option' ).each( function() {
				var this_option      = $( this );
				var this_option_text = this_option.text();

				if ( this_option_text.toLowerCase() === criteria_name.toLowerCase() ) {
					has_similar_criteria = true;
					return false;
				}
			} );
		}

		// Check if there was any similar criteria.
		if ( has_similar_criteria ) {
			alert( add_same_criteria_error );
			return false;
		}

		// Push the slug into the array.
		var new_criteria = new Option( criteria_name, criteria_name, true, true );

		// Append the select option.
		$( '#ersrvr_submit_review_criterias' ).append( new_criteria ).trigger( 'change' );
	} );

	// Delete the review attachment from the edit comment screen.
	$( document ).on( 'click', '.ersrvr-gallery-image-item a.delete-link', function() {
		var confirm_remove = confirm( remove_attachment_confirm_text );
		var this_link      = $( this );

		// Exit, if the user denies.
		if ( false === confirm_remove ) {
			return false;
		}

		// Block the image.
		block_element( this_link.parents( '.ersrvr-gallery-image-item' ) );

		// Send the AJAX to remove the image.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'remove_review_attachment',
				review_id: $( 'input[name="comment_ID"]' ).val(),
				attachment_id: this_link.parent( 'div' ).data( 'imageid' ),
			},
			success: function ( response ) {
				if( 'review-attachment-removed' === response.data.code ) {
					// Remove the image block.
					this_link.parents( '.ersrvr-gallery-image-item' ).remove();
				}
			},
		} );
	} );

	// Media uploader for adding attachments to the reviews.
	$( document ).on( 'click', '.ersrvr-add-more-attachments-to-review a', function() {
		var attachments_html = '';
		var images = wp.media( {
			title: media_uploader_modal_header,
			multiple: true,
		} ).open()
		.on( 'select', function( e ) {
			var images_arr      = images.state().get( 'selection' ).toJSON();
			var new_attachments = [];

			// Iterate through the selected files.
			for ( var i in images_arr ) {
				var image_id  = images_arr[i].id;
				var image_url = images_arr[i].url;
				var filename  = images_arr[i].filename;

				// If the filename is invalid, skip.
				if ( -1 === is_valid_string( filename ) ) {
					continue;
				}

				// Check if the filename has a dot in it.
				var dot_index = filename.indexOf( '.' );

				// If there is no dot, skip.
				if ( -1 === dot_index ) {
					continue;
				}

				// Get the extension from the image URL and see if that is within the allowed types.
				var file_ext = filename.split( '.' ).pop();
				file_ext     = '.' + file_ext;

				// Skip the iteration, if the file extension doesn't match the allowed ones.
				if ( -1 === $.inArray( file_ext, review_attachments_allowed_types ) ) {
					continue;
				}

				// Collect the image data into an array.
				new_attachments.push( image_id );

				// Prepare the attachments HTML.
				attachments_html += '<div class="ersrvr-gallery-image-item" data-imageid="' + image_id + '">';
				attachments_html += '<img alt="legacy-system-notice-2.png" src="' + image_url + '" class="ersrvr_attached_files">';
				attachments_html += '<a href="javascript:void(0)" class="delete-link">';
				attachments_html += '<span class="icon"><span class="dashicons dashicons-dismiss"></span></span>';
				attachments_html += '<span class="text sr-only">Delete</span>';
				attachments_html += '</a>';
				attachments_html += '</div>';
			}

			// Exit, if there are no attachments to upload.
			if ( 0 === new_attachments.length ) {
				return false;
			}

			// Block the gallery section now.
			block_element( $( '.ersrvr-review-attachments-container' ) );
			block_element( $( '.ersrvr-add-more-attachments-to-review' ) );

			// Send the AJAX now to update the review attachments.
			$.ajax( {
				dataType: 'JSON',
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'add_review_attachments',
					review_id: $( 'input[name="comment_ID"]' ).val(),
					new_attachments: new_attachments,
				},
				success: function ( response ) {
					if( 'review-attachments-added' === response.data.code ) {
						// Unblock the elements.
						unblock_element( $( '.ersrvr-review-attachments-container' ) );
						unblock_element( $( '.ersrvr-add-more-attachments-to-review' ) );

						// Paste the HTML now.
						$( '.ersrvr-review-attachments-container .gallery-images' ).append( attachments_html );
					}
				},
			} );
		} );
	} );

	// Manage the star classes on mouse hover.
	$( document ).on( 'mouseout', '.rating__label', function() {
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().removeClass( 'fill_star_hover' );
		$( 'label.rating__label' ).removeClass( 'fill_star_hover' );
	} );

	// Sheck user hover on which star and add class till starts
	$( document ).on( 'mouseover', '.rating__label', function() {
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().addClass( 'fill_star_hover' );
		$( 'label.rating__label' ).removeClass( 'fill_star_hover' );
	} );

	// check user click on which star and add class till starts
	$( document ).on( 'click', '.rating__label', function() {
		$( 'label.rating__label' ).removeClass( 'fill_star_click' );
		block_element( $( '.comment-php #publishing-action input[type="submit"]' ) );
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		var closest_criteria  = criteria_input.closest( '.rating-group' ).attr( 'id' );
		$( '#' + closest_criteria + ' .rating__input' ).removeClass( 'fill_star_click' );
		$( '#' + criteria_input_id ).prevUntil( 'input[type="radio"]:first' ).addBack().addClass( 'fill_star_click' );
		var rating = parseInt( criteria_input.val() );

		// Check if criteria to add already exists in the array.
		var existing_criteria_key = $.map( existing_criteria_result, function( val, i ) {
			if ( val.closest_criteria === closest_criteria ) {
				return i;
			}
		} );

		// If the key is found.
		if ( 0 < existing_criteria_key.length ) {
			existing_criteria_result.splice( existing_criteria_key[0], 1 );
		}

		// Push the element in the array.
		existing_criteria_result.push( {
			closest_criteria: closest_criteria,
			rating: parseInt( rating ),
		} );

		console.log( 'existing_criteria_result', existing_criteria_result );
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
