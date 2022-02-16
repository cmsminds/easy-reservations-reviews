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

$comments_count_obj = wp_count_comments( get_the_ID() );
?>
<div class="review-listing-wrapper" data-loadcomments="<?php echo esc_attr( ( ! empty( $comments_count_obj->approved ) && 0 < $comments_count_obj->approved ) ? 1 : -1 ); ?>">
	<!-- ITEM STAR RATING ELEMENT -->
	<div class="ersrvr_total_review_html"></div>
	<div class="dropdown-divider divider_list_comments available"></div>
	<div class="sinlgle-review-items-wrapper">
		<div class="jumbotron text-center w-100 bg-transparent">
			<h3 class="loading-title"><?php esc_html_e( 'Please wait while the reviews are loading...', 'easy-reservations-reviews' ); ?></h3>
			<div class="loading-icon">
				<i class="fa fa-circle-notch fa-spin fa-3x fa-fw"></i>
				<span class="sr-only"><?php esc_html_e( 'Loading...', 'easy-reservations-reviews' ); ?></span>
			</div>
		</div>
		<!-- ITEM REVIEWS LIST HTML -->
		<ul class="list-unstyled ml-0 ersrvr_comment_message_box_view"></ul>
	</div>
</div>
