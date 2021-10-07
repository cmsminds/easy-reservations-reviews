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
$user_email                    = $user_info['user_email'];
$username                      = $user_info['username'];
$user_phone_number             = $user_info['user_phone_number'];
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
			<div class="list-Of-Review-title">
				<h2 class="font-popins font-size-24 font-weight-bold">2 Reviews</h2>
			</div>
			<div class="total-of-star-rating">
				<div class="rating-item d-flex flex-wrap align-items-center">
					<div class="col-12 col-sm-12 rating-group px-0">
						<input class="rating__input" name="rating3" id="rating3-1" value="1" type="radio">
						<label aria-label="1 star" class="rating__label" for="rating3-1"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-2" value="2" type="radio">
						<label aria-label="2 stars" class="rating__label" for="rating3-2"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-3" value="3" type="radio">
						<label aria-label="3 stars" class="rating__label" for="rating3-3"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-4" value="4" type="radio">
						<label aria-label="4 stars" class="rating__label" for="rating3-4"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-5" value="5" type="radio">
						<label aria-label="5 stars" class="rating__label" for="rating3-5"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<div id="full-stars-example-two" class="rating-group-wrapper">
				<div class="rating-item d-flex flex-wrap align-items-center">
					<div class="col-4 col-sm-3">
						<label class="font-Poppins font-weight-semibold text-black font-size-14"><?php esc_html_e( 'Communication', 'easy-reservations-reviews' ); ?> </label>
					</div>
					<div class="col-8 col-sm-9 rating-group">
						<input class="rating__input" name="rating3" id="rating3-1" value="1" type="radio">
						<label aria-label="1 star" class="rating__label" for="rating3-1"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-2" value="2" type="radio">
						<label aria-label="2 stars" class="rating__label" for="rating3-2"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-3" value="3" type="radio">
						<label aria-label="3 stars" class="rating__label" for="rating3-3"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-4" value="4" type="radio">
						<label aria-label="4 stars" class="rating__label" for="rating3-4"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-5" value="5" type="radio">
						<label aria-label="5 stars" class="rating__label" for="rating3-5"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
					</div>
				</div>
			</div>
			<?php if ( ! empty( $getallComments ) && is_array( $getallComments ) ) { ?>
				<div class="dropdown-divider"></div>
				<div class="sinlgle-review-items-wrapper">
					<ul class="list-unstyled ml-0">
						<?php 
						foreach( $getallComments as $getallComment ) {
							$commnet_id      = $getallComment->comment_ID;
							$user_id         = $getallComment->user_id;
							$comment_date    = $getallComment->comment_date;
							$comment_date    = date( "d M Y", strtotime( $comment_date ) );
							$user_obj        = get_userdata( $user_id );
							$first_name      = ! empty( get_user_meta( $user_id, 'first_name', true ) ) ? get_user_meta( $user_id, 'first_name', true ) : '';
							$last_name       = ! empty( get_user_meta( $user_id, 'last_name', true ) ) ? get_user_meta( $user_id, 'last_name', true ) : '';
							$display_name    = ! empty( $user_obj->data->display_name ) ? $user_obj->data->display_name : '';
							$username        = $first_name . ' ' . $last_name;
							$username        = ( ' ' !== $username ) ? $username : $display_name; 
							$comment_content = $getallComment->comment_content;
							$average_rating  = get_comment_meta( $commnet_id, 'average_ratings', true );
							$average_rating  = (int) $average_rating;
							$criteria        = get_comment_meta( $commnet_id, 'user_criteria_ratings', true );
							
							?>
							<li class="media mb-4">
								<img src="http://localhost:8888/woocom-learning/wp-content/uploads/2021/08/pexels-jason-boyd-3423147-scaled.jpg" class="mr-3 rounded-circle" alt="user-photo">
								<div class="media-body">
									<div class="media-title">
										<div id="full-stars-example-two" class="rating-group-wrapper">
											<div class="rating-item d-flex flex-wrap align-items-center">
												<div class="col-auto rating-group px-0">
													<?php for ( $i = 1; $i <= 5; $i++ ) {
															$filled_star_class = ( $average_rating >= $i ) ? 'fill_star_click' : '';
														?>
														<input class="rating__input" name="rating3" id="rating3-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
														<label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label <?php echo esc_attr( $filled_star_class ); ?>" for="rating3-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
													<?php } ?>
												</div>
												<div class="col-auto"><label class="font-Poppins font-weight-semibold text-muted font-size-14"><?php esc_html_e( '( 5 of 5 )', 'easy-reservations-reviews' ); ?> </label></div>
											</div>
										</div>
										<h5 class="mt-2 mb-1 font-popins font-size-16 font-weight-semibold">
											<?php esc_html_e( $username, 'easy-reservations-reviews' ); ?>
											<span class="text-muted font-lato font-weight-normal font-size-14">- <?php esc_html_e( $comment_date, 'easy-reservations-reviews' ); ?></span>
										</h5>
									</div>
									<p class="font-lato font-size-14 font-weight-normal mb-0"><?php echo $comment_content;?></p>
								</div>
							</li>
						<?php } ?>
						
					</ul>
				</div>
			<?php } ?>
			

		</div>
		<div class="dropdown-divider"></div>
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
