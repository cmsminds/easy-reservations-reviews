jQuery( document ).ready( function( $ ) {
	'use strict';
	
	// Localized variables.
	var ajaxurl                                = ERSRVR_Reviews_Public_Script_Vars.ajaxurl;
	var user_logged_in                         = ERSRVR_Reviews_Public_Script_Vars.user_logged_in;
	var user_email                             = ERSRVR_Reviews_Public_Script_Vars.user_email;
	var current_post_id                        = ERSRVR_Reviews_Public_Script_Vars.current_post_id;
	var toast_error_heading                    = ERSRVR_Reviews_Public_Script_Vars.toast_error_heading;
	var invalid_reviews_message_error_text     = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_message_error_text;
	var invalid_reviews_email_error_text       = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_email_error_text;
	var invalid_reviews_phone_error_text       = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_phone_error_text;
	var invalid_reviews_name_error_text        = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_name_error_text;
	var invalid_reviews_email_regex_error_text = ERSRVR_Reviews_Public_Script_Vars.invalid_reviews_email_regex_error_text;
	var toast_success_heading                  = ERSRVR_Reviews_Public_Script_Vars.toast_success_heading;
	var review_file_allowed_extensions         = ERSRVR_Reviews_Public_Script_Vars.review_file_allowed_extensions;
	var review_file_invalid_file_error         = ERSRVR_Reviews_Public_Script_Vars.review_file_invalid_file_error;
	var user_criteria_ratings = [];
	var file_array = [];
	// console.log('useremail', user_email);
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

	/**
	 * Validate the file.
	 */
	 $( document ).on( 'change', 'input[name="ersrvr_actual_btn[]"]', function( evt ) {
		// evt.preventDefault();
		var file = $( this ).val();
		var file_name = evt.target.files[0].name;
		var ext  = file.split( '.' ).pop();
		ext      = '.' + ext;
		file_array.push( {
			'files': evt.target.files[0],
		});
		// this.files[0].name
		
		$('.ersrvr_file_chosen').text(file_name);

		// Check if this extension is among the extensions allowed.
		if ( 0 < review_file_allowed_extensions.length && -1 === $.inArray( ext, review_file_allowed_extensions ) ) {
			ersrvr_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, review_file_invalid_file_error );
			// $( '.file-preview' ).remove();
			// $( '.fileinput-remove' ).remove();
			// $( '.fileinput-upload' ).remove();
			// $( '.file-input .file-caption-name' ).text('');
			// Reset the file input type.
			$('input[name="ersrvr_actual_btn"]').val('');
			$('.ersrvr_file_chosen').text('No file chosen');
			return false;
		}
	} );

	

	// submit revie form.
	jQuery(document).on( 'click', '.ersrvr_btn_submit', function( evt ) {
		evt.preventDefault();
		var this_button             = $( this );
		var this_button_text        = this_button.text();
		var useremail               = user_email;
		useremail                   = ( -1 === is_valid_string( useremail ) ) ? $( '#ersrvr_email' ).val() : useremail;
		var username                = $( '#ersrvr_name'  ).val();
		var phone                   = $( '#ersrvr_phone' ).val();
		var review_message          = $( '#ersrvr_message' ).val();
		if( file_array.length > 0 ) {
			var oFReader                = new FileReader();
			oFReader.readAsDataURL( file_array[0]['files'] );
		}
		// console.log( given_rating_star_array );
		// Prepare the form data.
		var fd                      = new FormData();
		if( 'no' === user_logged_in ) {
			if ( -1 === is_valid_string( username ) ) {
				ersrvr_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, invalid_reviews_name_error_text );
				return false;
			} 
			if( -1 === is_valid_string( useremail ) ) {
				ersrvr_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, invalid_reviews_email_error_text );	
				return false;
			} else if ( -1 === is_valid_email( useremail ) ) {
				ersrvr_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, invalid_reviews_email_regex_error_text );	
				return false;
			}
			if( -1 === is_valid_number( phone ) ) {
				ersrvr_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, invalid_reviews_phone_error_text );
				return false;
			}
		}
		if ( -1 === is_valid_string( review_message ) ) {
			ersrvr_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, invalid_reviews_message_error_text );
			return false;
		}
		if( file_array.length > 0 ) {
			fd.append( 'files',file_array[0]['files'] );
		}
		
		// return false;
		var given_user_criteria_ratings = JSON.stringify( user_criteria_ratings );
		console.log( user_criteria_ratings );
		// return false;
		fd.append( 'action', 'ersrvr_submit_reviews' );
		fd.append( 'useremail', useremail );
		fd.append( 'user_criteria_ratings', given_user_criteria_ratings );
		fd.append( 'current_post_id', current_post_id );
		fd.append( 'username', username );
		fd.append( 'phone', phone );
		fd.append( 'review_message', review_message );
		this_button.html( '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' );
		block_element( this_button );
		$.ajax( {
			type: 'POST',
			url: ajaxurl,
			data: fd,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function ( response ) {
				// Check for invalid ajax request.
				if ( 0 === response ) {
					console.log( 'easy reservations: invalid ajax request' );
					return false;
				}
				unblock_element( this_button );
				this_button.text( this_button_text );
				if( 'ersrvr_submit_reviews_success' === response.data.code ) {
					// Show the toast now.
					ersrvr_show_toast( 'bg-success', 'fa-check-circle', toast_success_heading, response.data.toast_message );
					$( 'input[type="radio"]' ).removeClass( 'fill_star_click' );
					$( '.file-preview' ).remove();
					$( '.file-input .file-caption-name' ).text( '' );
					$( '.ersrvr_comment_message_box_view' ).html( '' );
					$( '.ersrvr_comment_message_box_view' ).html( response.data.html );
					$( '.ersrvr_total_review_html' ).html( response.data.total_review_html );
					$('#ersrvr_message').val( '' );
				}
			},
		} );
	} );
	// delete comments.
	jQuery( document ).on( 'click', '.ersrvr_delete_review', function( evt ) {
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