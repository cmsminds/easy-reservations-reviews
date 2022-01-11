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

defined( 'ABSPATH' ) || exit;

global $post;
$user_info             = ersrvr_user_logged_in_data();
$user_email_id         = ! empty( $user_info ) ? $user_info['email'] : '';
$username              = ! empty( $user_info ) ? $user_info['name'] : '';
$user_phone_number     = ! empty( $user_info ) ? $user_info['phone'] : '';
$guest_reviews_allowed = ersrvr_get_plugin_settings( 'ersrvr_enable_reservation_reviews_guest_users' );
?>
<div class="review-form-wrapper">
	<?php
	if ( empty( $user_info ) ) {
		if ( ! empty( $guest_reviews_allowed ) && 'yes' === $guest_reviews_allowed ) {
			echo ersrvr_prepare_reviews_form_html( $user_email_id, $username, $user_phone_number );
		}
	} else {
		echo ersrvr_prepare_reviews_form_html( $user_email_id, $username, $user_phone_number );
	}
	?>
</div>
