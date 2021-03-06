jQuery( document ).ready( function( $ ) {
	'use strict';
	
	// Localized variables.
	var ajaxurl                                = ERSRVR_Reviews_Public_Script_Vars.ajaxurl;
	var user_logged_in                         = ERSRVR_Reviews_Public_Script_Vars.user_logged_in;
	var toast_error_heading                    = ERSRVR_Reviews_Public_Script_Vars.toast_error_heading;
	var invalid_reviews_message_error_text     = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_message_error_text;
	var invalid_reviews_email_error_text       = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_email_error_text;
	var invalid_reviews_phone_error_text       = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_phone_error_text;
	var invalid_reviews_name_error_text        = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_name_error_text;
	var invalid_reviews_email_regex_error_text = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_email_regex_error_text;
	var toast_success_heading                  = ERSRVR_Reviews_Public_Script_Vars.toast_success_heading;
	var review_file_allowed_extensions         = ERSRVR_Reviews_Public_Script_Vars.review_file_allowed_extensions;
	var review_file_invalid_file_error         = ERSRVR_Reviews_Public_Script_Vars.review_file_invalid_file_error;
	var review_cannot_be_submitted             = ERSRVR_Reviews_Public_Script_Vars.review_cannot_be_submitted;
	var reviews_list_preparing_text            = ERSRVR_Reviews_Public_Script_Vars.reviews_list_preparing_text;

	// Global variables.
	var user_criteria_ratings  = [];
	var review_attachments_arr = [];

	/**
	 * Manage the classes based on which star the mouse is on.
	 */
	$( document ).on( 'mouseout', '#ersrvr_ratings_stars .rating__label', function() {
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().removeClass( 'fill_star_hover' );
		$( 'label.rating__label' ).removeClass( 'fill_star_hover' );
	} );

	/**
	 * Manage the classes based on which star the mouse is on.
	 */
	$( document ).on( 'mouseover', '#ersrvr_ratings_stars .rating__label', function() {
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().addClass( 'fill_star_hover' );
		$( 'label.rating__label' ).removeClass( 'fill_star_hover' );
	} );

	/**
	 * Select the star ratings.
	 */
	$( document ).on( 'click', '#ersrvr_ratings_stars .rating__label', function() {
		$( 'label.rating__label' ).removeClass( 'fill_star_click' );
		var this_label        = $( this );
		var criteria_input    = this_label.prev( 'input[type="radio"]' );
		var criteria_input_id = criteria_input.attr( 'id' );
		var criteria_name     = criteria_input.closest( '.rating-group' ).attr( 'id' );
		var criteria_title    = criteria_input.closest( '.rating-group' ).data( 'criteria' );
		$( '#' + criteria_name + ' .rating__input' ).removeClass( 'fill_star_click' );
		$( '#' + criteria_input_id ).prevUntil( '.rating__input:first' ).addBack().addClass( 'fill_star_click' );
		var rating = parseInt( criteria_input.val() );

		// Check if criteria to add already exists in the array.
		var existing_criteria_key = $.map( user_criteria_ratings, function( val, i ) {
			if ( val.criteria_name === criteria_name ) {
				return i;
			}
		} );

		// If the key is found.
		if ( 0 < existing_criteria_key.length ) {
			user_criteria_ratings.splice( existing_criteria_key[0], 1 );
		}

		// Push the element in the array.
		user_criteria_ratings.push( {
			criteria_name: criteria_name,
			criteria_title: criteria_title,
			rating: rating,
		} );
	} );

	/**
	 * Validate the files selected for submitting review.
	 */
	$( document ).on( 'change', 'input[name="ersrvr_review_attachments[]"]', function( evt ) {
		// Get the selected files.
		var files = evt.target.files;

		// Exit, if there are files selected.
		if ( 0 === files.length ) {
			return false;
		}

		// Collect the file extensions here.
		var file_extensions = [];

		// Iterate through the files to collect the extensions.
		for ( var i in files ) {
			var filename   = files[i].name;

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

			var file_ext = filename.split( '.' ).pop();
			file_ext     = '.' + file_ext;

			// Collect the extension in the array.
			file_extensions.push( file_ext );

			// Collect the files in an array.
			review_attachments_arr.push(
				{
					file: files[i],
				}
			);
		}

		// Unwanted selected files.
		var unwanted_selected_file_extensions = [];

		// Get the unwanted extensions now.
		$.grep( file_extensions, function( el ) {
			if ( -1 === $.inArray( el, review_file_allowed_extensions ) ) unwanted_selected_file_extensions.push( el );
		} );

		// Clear the file selections if there are disallowed files selected.
		if ( 0 < unwanted_selected_file_extensions.length ) {
			ersrvr_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, review_file_invalid_file_error );

			// Reset the file input type.
			setTimeout( function() {
				$( '.fileinput-remove-button' ).click();
			}, 1000 );

			return false; // Exit.
		}
	} );

	/**
	 * Submit the review form.
	 */
	$(document).on( 'click', '.ersrvr_btn_submit', function() {
		var this_button        = $( this );
		var this_button_text   = this_button.text();
		var reviewer_name      = $( '#ersrvr_name'  ).val();
		var reviewer_phone     = $( '#ersrvr_phone' ).val();
		var reviewer_email     = $( '#ersrvr_email' ).val();
		var reviewer_message   = $( '#ersrvr_message' ).val();
		var form_data          = new FormData(); // Prepare the form data.
		var submit_review      = true;

		// Vacate all the errors.
		$( '.ersrv-reservation-error' ).text( '' );

		// Validate the form, if required.
		if ( 1 === is_valid_string( user_logged_in ) && 'no' === user_logged_in ) {
			// Reviewer name validation.
			if ( -1 === is_valid_string( reviewer_name ) ) {
				submit_review = false;
				$( '.ersrv-reservation-error.reviewer-name-error' ).text( invalid_reviews_name_error_text );
			}

			// Reviewer phone validation.
			if ( '' === reviewer_phone ) {
				submit_review = false;
				$( '.ersrv-reservation-error.reviewer-phone-error' ).text( invalid_reviews_phone_error_text );
			}

			// Reviewer email validation.
			if ( -1 === is_valid_string( reviewer_email ) ) {
				submit_review = false;
				$( '.ersrv-reservation-error.reviewer-email-error' ).text( invalid_reviews_email_error_text );
			} else if ( -1 === is_valid_email( reviewer_email ) ) {
				submit_review = false;
				$( '.ersrv-reservation-error.reviewer-email-error' ).text( invalid_reviews_email_regex_error_text );
			}
		}

		// Reviewer message validation.
		if ( -1 === is_valid_string( reviewer_message ) ) {
			submit_review = false;
			$( '.ersrv-reservation-error.reviewer-message-error' ).text( invalid_reviews_message_error_text );
		}

		// Show error notification, if there are errors.
		if ( false === submit_review ) {
			ersrvr_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, review_cannot_be_submitted );
			return false;
		}

		// Iterate through the attachments to attach them to the form data.
		for( var i in review_attachments_arr ) {
			form_data.append( 'review_attachments[]', review_attachments_arr[i]['file'] );
		}

		// Attach all the data to the form data variable.
		form_data.append( 'action', 'submit_review' );
		form_data.append( 'reviewer_message', reviewer_message );
		form_data.append( 'user_ratings', JSON.stringify( user_criteria_ratings ) );
		form_data.append( 'item_id', $( '.single-reserve-page' ).data( 'item' ) );

		// Collect all the data now.
		if ( 1 === is_valid_string( user_logged_in ) && 'no' === user_logged_in ) {
			form_data.append( 'reviewer_name', reviewer_name );
			form_data.append( 'reviewer_phone', reviewer_phone );
			form_data.append( 'reviewer_email', reviewer_email );
		}

		// Change the button HTML.
		this_button.html( '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' );

		// Block the button now.
		block_element( this_button );

		// Fire the AJAX now to submit the review.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: form_data,
			cache: false,
			contentType: false,
			processData: false,
			success: function ( response ) {
				// If the review has been added.
				if( 'review-added' === response.data.code ) {
					// Unblock the button.
					unblock_element( this_button );

					// Revert back the button text.
					this_button.html( this_button_text );

					// Show the toast now.
					ersrvr_show_toast( 'bg-success', 'fa-check-circle', toast_success_heading, response.data.toast_message );
					
					// Reset the form fields.
					$( 'input[type="radio"]' ).removeClass( 'fill_star_click' );
					$( '.fileinput-remove-button' ).click();
					$( '#ersrvr_name, #ersrvr_phone, #ersrvr_email, #ersrvr_message' ).val( '' );

					// Refresh the review listings.
					ersrvr_refresh_item_reviews();
				}
			},
		} );
	} );

	/**
	 * List the reviews now, on the product details page.
	 * Check if the reviews element exists.
	 */
	if ( $( '.review-listing-wrapper' ).length ) {
		var load_comments = $( '.review-listing-wrapper' ).data( 'loadcomments' );

		// If there is a request to load comments.
		if ( 1 === load_comments ) {
			// Send the AJAX to load comments.
			ersrvr_load_comments();
		} else {
			$( '.sinlgle-review-items-wrapper .jumbotron' ).hide();
		}
	}

	/**
	 * Delete comment.
	 */
	$( document ).on( 'click', '.ersrvr_delete_review', function( evt ) {
		evt.preventDefault();
		var this_btn   = $( this );
		var comment_id = this_btn.data('commentid');
		var postid     = this_btn.data('postid');
		var data = {
			comment_id: comment_id,
			postid: postid,
			action: 'ersrvr_delete_review_comment',
		};
		block_element( $('.ersrvr_comment_message_box_view') );
		// $('.ersrvr_comment_message_box_view').html( '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' );
		$.ajax( {
			dataType: 'json',
			url: ajaxurl,
			type: 'POST',
			data: data,
			success: function ( response ) {
				unblock_element( $('.ersrvr_comment_message_box_view') );
				if( 'ersrvr_delete_comments_success' === response.data.code ) {
					$( '.ersrvr_comment_id_' + comment_id  ).remove();
					$( '.ersrvr_total_review_html' ).html( response.data.html );
					if( 'not_available' === response.data.status_zero_comment ) {
						$( '.ersrvr_total_review_divider' ).remove();
					}
					
				}
			},
		} );
	} );

	/**
	 * Show/hide the reservation splitted cost.
	 */
	$( document ).on( 'click', '.ersrvr-review-details-popup', function( evt ) {
		evt.preventDefault();
		var this_anchor = $( this );
		$( '#ersrvr-reservation-reviews-details-id' ).toggleClass( 'show' );

		// Check if the click is from modal.
		if ( ! this_anchor.hasClass( 'is-modal' ) ) {
			// Add a body class if the summary is visible.
			$( 'body' ).removeClass( 'ersrvr-reservation-reviews-details-active' );
			if ( $( '#ersrvr-reservation-reviews-details-id' ).hasClass( 'show' ) ) {
				$( 'body' ).addClass( 'ersrvr-reservation-reviews-details-active' );
			}
		}
	} );

	/**
	 * Load the item reviews.
	 */
	function ersrvr_load_comments() {
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'load_reviews',
				post_id: $( '.single-reserve-page' ).data( 'item' ),
			},
			success: function ( response ) {
				// If there are no reviews available.
				if ( 'no-review-available' === response.data.code ) {
					$( '.review-listing-wrapper' ).hide();
				} else if ( 'reviews-available' === response.data.code ) { // If there are reviews available.
					$( '.ersrvr_total_review_html' ).html( response.data.item_rating_html ); // Paste the rating HTML.
					$( '.review-listing-wrapper' ).show(); // Show the reviews listing div.
					$( '.sinlgle-review-items-wrapper .jumbotron h3.loading-title' ).text( reviews_list_preparing_text ); // Update the loader text.

					// Send the AJAX now to fetch the list.
					$.ajax( {
						dataType: 'JSON',
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'load_reviews_list',
							post_id: $( '.single-reserve-page' ).data( 'item' ),
							reviews: response.data.comments,
						},
						success: function ( response ) {
							$( '.sinlgle-review-items-wrapper .jumbotron' ).hide(); // Update the loader.
							if ( 'reviews-available' === response.data.code ) {
								// If there are reviews and the HTML is ready to be pasted.
								$( '.ersrvr_comment_message_box_view' ).html( response.data.html );
							}
						},
					} );
				}
			},
		} );
	}

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
	 * Check if a email is valid.
	 *
	 * @param {string} email
	 */
	 function is_valid_email( email ) {
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		return ( ! regex.test( email ) ) ? -1 : 1;
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
	/**
	 * Show the notification text.
	 *
	 * @param {string} bg_color Holds the toast background color.
	 * @param {string} icon Holds the toast icon.
	 * @param {string} heading Holds the toast heading.
	 * @param {string} message Holds the toast body message.
	 */
	 function ersrvr_show_toast( bg_color, icon, heading, message ) {
		$( '.ersrv-notification' ).removeClass( 'bg-success bg-warning bg-danger' );
		$( '.ersrv-notification' ).addClass( bg_color ).toast( 'show' );
		$( '.ersrv-notification .ersrv-notification-icon' ).removeClass( 'fa-skull-crossbones fa-check-circle fa-exclamation-circle' );
		$( '.ersrv-notification .ersrv-notification-icon' ).addClass( icon );
		$( '.ersrv-notification .ersrv-notification-heading' ).text( heading );
		$( '.ersrv-notification .ersrv-notification-message' ).html( message );
	}
} );