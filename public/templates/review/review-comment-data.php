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
<div class="review-listing-wrapper">
    <?php 
	$file_path = plugin_dir_path( __DIR__ ) . 'review/total_review.php';
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}
	?>
	<?php if ( ! empty( $getallComments ) && is_array( $getallComments ) ) { ?>
	    <div class="dropdown-divider"></div>
		    <div class="sinlgle-review-items-wrapper">
			    <ul class="list-unstyled ml-0 ersrvr_comment_message_box_view">
					<?php echo ersrvr_html_comment_message_box( $getallComments ); ?>
				</ul>
			</div>
	<?php } ?>
</div>