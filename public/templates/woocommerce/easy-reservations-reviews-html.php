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
$user_info                     = ersrvr_user_logged_in_data();
$user_email                    = $user_info['user_email'];
$username                      = $user_info['username'];
$user_phone_number             = $user_info['user_phone_number'];
$get_guest_user_enable_Setting = ersrvr_get_plugin_settings( 'ersrvr_enable_reservation_reviews_guest_users' ); ?>

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
		<!-- form start here -->
		<div class="review-form-wrapper">
			<?php 
			/**
			* Function ( ersrvr_prepare_reviews_form_html() ) Map from the file of includes/easy-reservations-reviews-functions.php Line no: 224
			*/
			if( empty( $user_info ) ) {
				if( ! empty( $get_guest_user_enable_Setting ) && 'yes' === $get_guest_user_enable_Setting ) { 
					echo ersrvr_prepare_reviews_form_html( $user_email, $username, $user_phone_number ); 
				}
			} else { 
				echo ersrvr_prepare_reviews_form_html( $user_email, $username, $user_phone_number ); 
			} ?>
		</div>
		<div class="review-listing-wrapper">
			<div class="list-Of-Review-title">
				<h2 class="font-popins font-size-24 font-weight-bold">2 Reviews</h2>
			</div>
			<div class="total-of-star-rating">
				<div class="rating-item d-flex flex-wrap align-items-center">
					<div class="col-12 col-sm-12 rating-group px-0">
						<input disabled checked class="rating__input rating__input--none" name="rating3" id="rating3-none" value="0" type="radio">
						<label aria-label="1 star" class="rating__label" for="rating3-1"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-1" value="1" type="radio">
						<label aria-label="2 stars" class="rating__label" for="rating3-2"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-2" value="2" type="radio">
						<label aria-label="3 stars" class="rating__label" for="rating3-3"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-3" value="3" type="radio">
						<label aria-label="4 stars" class="rating__label" for="rating3-4"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-4" value="4" type="radio">
						<label aria-label="5 stars" class="rating__label" for="rating3-5"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-5" value="5" type="radio">
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<div id="full-stars-example-two" class="rating-group-wrapper">
				<div class="rating-item d-flex flex-wrap align-items-center">
					<div class="col-4 col-sm-3"><label class="font-Poppins font-weight-semibold text-black font-size-14"><?php esc_html_e( 'Communication', 'easy-reservations-reviews' ); ?> </label></div>
					<div class="col-8 col-sm-9 rating-group">
						<input disabled checked class="rating__input rating__input--none" name="rating3" id="rating3-none" value="0" type="radio">
						<label aria-label="1 star" class="rating__label" for="rating3-1"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-1" value="1" type="radio">
						<label aria-label="2 stars" class="rating__label" for="rating3-2"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-2" value="2" type="radio">
						<label aria-label="3 stars" class="rating__label" for="rating3-3"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-3" value="3" type="radio">
						<label aria-label="4 stars" class="rating__label" for="rating3-4"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-4" value="4" type="radio">
						<label aria-label="5 stars" class="rating__label" for="rating3-5"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
						<input class="rating__input" name="rating3" id="rating3-5" value="5" type="radio">
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<div class="sinlgle-review-items-wrapper">
				<ul class="list-unstyled">
					<li class="media">
						<img src="http://localhost/cmsminds/easy-reservations-system/wp-content/uploads/2021/08/pexels-jason-boyd-3423147-scaled.jpg" class="mr-3 rounded-circle" alt="user-photo">
						<div class="media-body">
							<h5 class="mt-0 mb-1">List-based media object</h5>
							<p>All my girls vintage Chanel baby. So you can have your cake. Tonight, tonight, tonight, I'm walking on air. Slowly swallowing down my fear, yeah yeah. Growing fast into a bolt of lightning. So hot and heavy, 'Til dawn. That fairy tale ending with a knight in shining armor. Heavy is the head that wears the crown.</p>
						</div>
					</li>
					<li class="media my-4">
						<img src="http://localhost/cmsminds/easy-reservations-system/wp-content/uploads/2021/08/pexels-jason-boyd-3423147-scaled.jpg" class="mr-3 rounded-circle" alt="user-photo">
						<div class="media-body">
							<h5 class="mt-0 mb-1">List-based media object</h5>
							<p>Maybe a reason why all the doors are closed. Cause once you’re mine, once you’re mine. Be your teenage dream tonight. Heavy is the head that wears the crown. It's not even a holiday, nothing to celebrate. A perfect storm, perfect storm.</p>
						</div>
					</li>
					<li class="media">
						<img src="http://localhost/cmsminds/easy-reservations-system/wp-content/uploads/2021/08/pexels-jason-boyd-3423147-scaled.jpg" class="mr-3 rounded-circle" alt="user-photo">
						<div class="media-body">
							<h5 class="mt-0 mb-1">List-based media object</h5>
							<p>Are you brave enough to let me see your peacock? There’s no going back. This is the last time you say, after the last line you break. At the eh-end of it all.</p>
						</div>
					</li>
				</ul>
			</div>

		</div>
	</div>
</div>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->