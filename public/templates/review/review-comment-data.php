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
$get_all_comments = get_comments(
	array(
		'post_id' => $post->ID,
	),
); ?>
<div class="review-listing-wrapper">
	<?php
	$file_path = plugin_dir_path( __DIR__ ) . 'review/total-review.php';
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}
	?>
	<?php if ( ! empty( $get_all_comments ) && is_array( $get_all_comments ) ) { ?>
		<div class="dropdown-divider"></div>
	<?php } ?>
		<div class="sinlgle-review-items-wrapper">
			<ul class="list-unstyled ml-0 ersrvr_comment_message_box_view">
				<?php if ( ! empty( $get_all_comments ) && is_array( $get_all_comments ) ) { ?>
					<?php echo ersrvr_html_comment_message_box( $get_all_comments ); ?>
				<?php } ?>
			</ul>
		</div>
</div>
