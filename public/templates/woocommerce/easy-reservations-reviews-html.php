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
$get_guest_user_enable_Setting = ersrvr_get_plugin_settings( 'ersrvr_enable_reservation_reviews_guest_users' );

?>

<div class="ship-reviews info-box">
	<a class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none" data-toggle="collapse" href="#ship-reviews-collapse" role="button" aria-expanded="true" aria-controls="ship-reviews-collapse">
		<span class="">Reviews</span>
	</a>
	<div class="collapse show" id="ship-reviews-collapse">
		<div class="dropdown-divider"></div>
		<?php 
		if( is_array( $user_info ) && ! empty( $user_info ) ) { ?>
			<div class="alert alert-warning" role="alert">
				<?php 
				echo sprintf( __( 'You are logged in as %1$s %2$s %3$s %4$s Logout %5$s.', 'easy-reservations-reviews' ), '<span class="font-weight-bold">', $user_email, '</span>', '<a href="'. wp_logout_url( home_url() ) .'" class="">','</a>' ); ?>
			</div>
		<?php } ?>
		<!-- form start here -->
		<!-- name, email, phone, file upload button, review message textarea ye fields chaiye hongi hume -->
		<div class="review-form-wrapper">
			<?php 
			// debug($user_info);
			// die;
			if( empty( $user_info ) ) {
				if( ! empty( $get_guest_user_enable_Setting ) && 'yes' === $get_guest_user_enable_Setting ) { ?>
					<form action="#" method="post" enctype="multipart/form-data">
						<div class="form-row">
							<div class="col-12">
								<label class="sr-only" for="name">Name</label>
								<input type="text" class="form-control mb-2" id="name" placeholder="Name" />
							</div>
							<div class="col-12">
								<label class="sr-only" for="email">Email</label>
								<input type="email" class="form-control mb-2" id="email" placeholder="E-mail" />
							</div>
						</div>
						<div class="form-row">
							<div class="col-12">
								<label class="sr-only" for="phone">Phone Number</label>
								<input type="text" class="form-control mb-2" id="phone" placeholder="Phone Number" />
							</div>
							<div class="col-12">
								<label class="sr-only" for="message">Message</label>
								<textarea name="message" id="message" class="form-control mb-2"  placeholder="Message"></textarea>
							</div>
						</div>
					</form>    
				<?php 
				}
			} else { ?>
				<form action="#" method="post" enctype="multipart/form-data">
					<div class="form-row">
						<div class="col-12">
							<label class="sr-only" for="name">Name</label>
							<input type="text" class="form-control mb-2" id="name" placeholder="Name" value="<?php esc_html_e( $username, 'easy-reservations-reviews' ); ?>" />
						</div>
						<div class="col-12">
							<label class="sr-only" for="email">Email</label>
							<input type="email" class="form-control mb-2" id="email" placeholder="E-mail" value="<?php esc_html_e( $user_email, 'easy-reservations-reviews' ); ?>" />
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<label class="sr-only" for="phone">Phone Number</label>
							<input type="text" class="form-control mb-2" id="phone" placeholder="Phone Number" />
						</div>
						<div class="col-12">
							<label class="sr-only" for="message">Message</label>
							<textarea name="message" id="message" class="form-control mb-2"  placeholder="Message"></textarea>
						</div>
					</div>
				</form>
			<?php } ?>
		</div>
	</div>
</div>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->