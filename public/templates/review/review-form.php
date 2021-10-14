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
$user_email_id                 = ! empty( $user_info ) ? $user_info['user_email'] : '';
$username                      = ! empty( $user_info ) ? $user_info['username'] : '';
$user_phone_number             = ! empty( $user_info ) ? $user_info['user_phone_number'] : '';
$get_guest_user_enable_setting = ersrvr_get_plugin_settings( 'ersrvr_enable_reservation_reviews_guest_users' );
?>
<div class="dropdown-divider ersrvr_total_review_divider"></div>
<!-- form start here -->
<div class="review-form-wrapper">
	<?php
	if ( empty( $user_info ) ) {
		if ( ! empty( $get_guest_user_enable_setting ) && 'yes' === $get_guest_user_enable_setting ) {
			echo ersrvr_prepare_reviews_form_html( $user_email_id, $username, $user_phone_number );
		}
	} else {
		echo ersrvr_prepare_reviews_form_html( $user_email_id, $username, $user_phone_number );
	}
	?>
</div>
