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
), );
// debug( $getallComments );
// die;
?>

<div class="ship-reviews info-box">
	<a class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none" data-toggle="collapse" href="#ship-reviews-collapse" role="button" aria-expanded="true" aria-controls="ship-reviews-collapse">
		<span class=""><?php esc_html_e( 'Reviews', 'easy-reservations-reviews' ); ?></span>
	</a>
	<div class="collapse show" id="ship-reviews-collapse">
		<div class="dropdown-divider"></div>
		<?php 
		if( is_array( $user_info ) && ! empty( $user_info ) ) { ?>
			<div class="alert alert-warning" role="alert">
				<?php 
				echo wp_kses(
					sprintf( __( 'You are logged in as %1$s %2$s %3$s %4$s Logout %5$s.', 'easy-reservations-reviews' ), '<span class="font-weight-bold">', $user_email, '</span>', '<a href="'. wp_logout_url( home_url() ) .'" class="">','</a>' ),
					array(
						'span'   => array(
							'class' => array(),
						),
						'a'      => array(
							'href'          => array(),
							'class'         => array(),
						),
					),
				); ?>
			</div>
		<?php } ?>
		<div class="review-listing-wrapper">
			<div class="ersrvr_total_review_html">
				<?php echo ersrvr_html_of_total_review(); ?>
			</div>
			<?php if ( ! empty( $getallComments ) && is_array( $getallComments ) ) { ?>
				<div class="dropdown-divider"></div>
				<div class="sinlgle-review-items-wrapper">
					<ul class="list-unstyled ml-0 ersrvr_comment_message_box_view">
					<?php echo ersrvr_html_comment_message_box( $getallComments ); ?>
					</ul>
				</div>
			<?php } ?>
			

		</div>
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
	</div>
</div>
