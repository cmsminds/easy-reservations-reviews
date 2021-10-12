<?php 
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://cmsminds.com
 * @since      1.0.0
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/public/templates
 */
global $post;
$user_info                     = ersrvr_user_logged_in_data();
$user_email                    = ! empty( $user_info ) ? $user_info['user_email'] : '';
$username                      = ! empty( $user_info ) ? $user_info['username'] : '';
$user_phone_number             = ! empty( $user_info ) ? $user_info['user_phone_number'] : '';
$get_guest_user_enable_Setting = ersrvr_get_plugin_settings( 'ersrvr_enable_reservation_reviews_guest_users' );
$getallComments                = get_comments( array(
	'post_id' => $post->ID,
), ); ?>
<div class="dropdown-divider ersrvr_total_review_divider"></div>
<!-- form start here -->
<div class="review-form-wrapper">
    <?php 
	/**
	* Function ersrvr_prepare_reviews_form_html() Map from the file of includes/easy-reservations-reviews-functions.php Line no: 224
	*/
	if( empty( $user_info ) ) {
	    if( ! empty( $get_guest_user_enable_Setting ) && 'yes' === $get_guest_user_enable_Setting ) { 
		    echo ersrvr_prepare_reviews_form_html( $user_email, $username, $user_phone_number ); 
		}
	} else { 
	    echo ersrvr_prepare_reviews_form_html( $user_email, $username, $user_phone_number ); 
	} ?>
</div>