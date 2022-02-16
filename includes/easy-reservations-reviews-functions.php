<?php
/**
 * The Common file functions.
 *
 * @link       https://cmsminds.com
 * @since      1.0.0
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/public
 */

/**
 * Check if the function exists.
 */

if ( ! function_exists( 'ersrvr_plugin_settings_fields' ) ) {
	/**
	 * Configure the plugin settings.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrvr_plugin_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'Reservations Reviews Setting', 'easy-reservations-reviews' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrvr_reviews',
				'name'  => 'ersrvr_reviews',
			),
			array(
				'name'        => __( 'Submit Review Button Text', 'easy-reservations-reviews' ),
				'type'        => 'text',
				'desc'        => __( 'This holds the submit reviews button text. Default: Submit', 'easy-reservations-reviews' ),
				'desc_tip'    => true,
				'id'          => 'ersrvr_submit_review_button_text',
				'placeholder' => __( 'Default: Submit', 'easy-reservations-reviews' ),
			),
			array(
				'name'     => __( 'Review Criteria', 'easy-reservations-reviews' ),
				'type'     => 'multiselect',
				'options'  => ersrvr_get_plugin_settings( 'ersrvr_submit_review_criterias' ),
				'class'    => 'wc-enhanced-select',
				'desc'     => __( 'This holds the review criteria. If you want to add some more, click on the button besides the selectbox.', 'easy-reservations-reviews' ),
				'desc_tip' => true,
				'default'  => '',
				'id'       => 'ersrvr_submit_review_criterias',
			),
			array(
				'name' => __( 'Allow Guest Reviews?', 'easy-reservations-reviews' ),
				'type' => 'checkbox',
				'desc' => __( 'Check this to allow guest users to post reviews.', 'easy-reservations-reviews' ),
				'id'   => 'ersrvr_enable_reservation_reviews_guest_users',
			),
			array(
				'name' => __( 'Before The Review Appears', 'easy-reservations-reviews' ),
				'type' => 'checkbox',
				'desc' => __( 'The review needs to be manually approved.', 'easy-reservations-reviews' ),
				'id'   => 'ersrvr_review_needs_manual_approval',
			),
			array(
				'name' => __( 'Edit Review', 'easy-reservations-reviews' ),
				'type' => 'checkbox',
				'desc' => __( 'Check this to allow users to edit their posted reviews. This works only for "loggedin" users.', 'easy-reservations-reviews' ),
				'id'   => 'ersrvr_enable_edit_reservation_reviews',
			),
			array(
				'name' => __( 'Delete Review', 'easy-reservations-reviews' ),
				'type' => 'checkbox',
				'desc' => __( 'Check this to allow users to delete their posted reviews. This works only for "loggedin" users.', 'easy-reservations-reviews' ),
				'id'   => 'ersrvr_enable_delete_reservation_reviews',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrvr_reviews',
			),
		);

		/**
		 * This hook fires on the admin panel.
		 *
		 * This filter helps you modify the admin settings fields.
		 *
		 * @param array $fields Settings fields.
		 * @return array
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_review_section_plugin_settings', $fields );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_plugin_settings' ) ) {
	/**
	 * Get plugin setting by setting index.
	 *
	 * @param string $setting Holds the setting index.
	 * @return boolean|string|array|int
	 * @since 1.0.0
	 */
	function ersrvr_get_plugin_settings( $setting ) {
		switch ( $setting ) {

			case 'ersrvr_submit_review_criterias':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
				$data = ( ! empty( $data ) ) ? ersrvr_prepare_criterias_array( $data ) : array();
				break;

			case 'ersrvr_submit_review_button_text':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Submit', 'easy-reservations-reviews' );
				break;

			case 'ersrvr_enable_reservation_reviews_guest_users':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && 'yes' === $data ) ? 'yes' : 'no';
				break;

			case 'ersrvr_enable_edit_reservation_reviews':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && 'yes' === $data ) ? 'yes' : 'no';
				break;

			case 'ersrvr_enable_delete_reservation_reviews':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && 'yes' === $data ) ? 'yes' : 'no';
				break;

			case 'ersrvr_review_needs_manual_approval':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && 'yes' === $data ) ? 'yes' : 'no';
				break;

			default:
				$data = -1;
		}

		return $data;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_prepare_criterias_array' ) ) {
	/**
	 * Get plugin setting by setting index.
	 *
	 * @param array $data Holds the setting index.
	 * @return boolean|string|array|int
	 * @since 1.0.0
	 */
	function ersrvr_prepare_criterias_array( $data ) {
		// Return, if the data is blank.
		if ( empty( $data ) || ! is_array( $data ) ) {
			return $data;
		}

		$criterias = array();

		// Iterate through the data items to prepare as per the need.
		foreach ( $data as $criteria_name ) {
			$criterias[ $criteria_name ] = $criteria_name;
		}

		return $criterias;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_item_rating' ) ) {
	/**
	 * Get the average reviews html for the item.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $comments Comment IDs array.
	 * @return float
	 * @since 1.0.0
	 */
	function ersrvr_get_item_rating( $post_id, $comments ) {
		// Return blank if there are no comments.
		if ( empty( $comments ) || ! is_array( $comments ) ) {
			return;
		}

		$average_rating_total = 0; // Sum of all the average ratings.

		// Iterate through the comments to get their rating values.
		foreach ( $comments as $comment_id ) {
			$total_rating   = 0; // All rating sum.
			$user_ratings   = get_comment_meta( $comment_id, 'user_ratings', true );
			$criterias      = get_comment_meta( $comment_id, 'criterias', true );
			$criteria_count = ( ! empty( $criterias ) ) ? count( $criterias ) : 0;

			// Skip, if there are no ratings available.
			if ( empty( $user_ratings ) || ! is_array( $user_ratings ) || 0 === $criteria_count ) {
				continue;
			}

			// Iterate through the user ratings.
			foreach ( $user_ratings as $rating ) {
				$total_rating += ( ! empty( $rating['rating'] ) ) ? (int) $rating['rating'] : 0;
			}

			$review_avg_rating     = (float) number_format( ( $total_rating / $criteria_count ), 2 ); // Item avg. rating.
			$average_rating_total += $review_avg_rating; // Add the average rating.
		}

		// Calculate the average rating of the item.
		$reviews_count = count( $comments );

		return (float) number_format( ( $average_rating_total / $reviews_count ), 1 );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_item_rating_html' ) ) {
	/**
	 * Return the item rating HTML.
	 *
	 * @param float $item_rating Item rating.
	 * @param array $comments Item comments.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_get_item_rating_html( $item_rating, $comments ) {
		$reviews_count = count( $comments );
		ob_start();
		?>
		<div class="list-Of-Review-title">
			<h2 class="font-popins font-size-24 font-weight-bold">
				<?php
				/* translators: 1: %d: reviews count */
				echo sprintf( __( '%1$d reviews', 'easy-reservations-reviews' ), $reviews_count );
				?>
			</h2>
		</div>
		<div class="total-of-star-rating">
			<div class="rating-item d-flex flex-wrap align-items-center">
				<div class="col-12 col-sm-12 rating-group px-0">
					<?php for ( $i = 1; $i <= 5; $i++ ) {
						// $filled_star_class = ( $total_rating_star >= $i ) ? 'fill_star_click' : '';
						// $filled_icons      = ( is_float( $reviews_count ) ) ? 'fa-star-half' : 'fa-star';
						$filled_star_class = 'fill_star_click';
						?>
						<input class="rating__input <?php echo esc_attr( $filled_star_class ); ?>" name="rating3" id="rating<?php echo esc_attr( $i ); ?>-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
						<label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label" for="rating<?php echo esc_attr( $i ); ?>-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_prepare_reviews_form_html' ) ) {
	/**
	 * Make Review Form HTML.
	 *
	 * @param string $user_email Holds the user email.
	 * @param string $username Holds the username.
	 * @param string $user_phone_number Holds the user phone number.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_prepare_reviews_form_html( $user_email, $username, $user_phone_number ) {
		$criterias = ersrvr_get_plugin_settings( 'ersrvr_submit_review_criterias' );
		$btn_text  = ersrvr_get_plugin_settings( 'ersrvr_submit_review_button_text' );

		// Prepare the html now.
		ob_start();
		?>
		<form action="" method="POST" enctype="multipart/form-data" id="item-review-form">
			<?php if ( ! empty( $criterias ) && is_array( $criterias ) ) { ?>
				<div class="form-row">
					<div class="col-12">
						<label class="font-Poppins font-weight-semibold text-black font-size-14"><?php esc_html_e( 'Please reate us 1 (bad) to 5 (excellent)', 'easy-reservations-reviews' ); ?></label>
						<div id="ersrvr_ratings_stars" class="rating-group-wrapper border py-2 px-1 rounded-xl">
							<?php $k = 1; ?>
							<?php foreach ( $criterias as $criteria ) { ?>
								<?php
								$criteria_slug = strtolower( $criteria );
								$criteria_slug = str_replace( ' ', '-', $criteria_slug );
								?>
								<div class="rating-item d-flex flex-wrap align-items-center mb-3 mb-md-0">
									<div class="col-12 col-sm-6 col-md-9 mb-1 mb-sm-0"><label class="font-Poppins font-weight-semibold text-black font-size-14"><?php echo esc_html( $criteria ); ?> </label></div>
									<div class="col-12 col-sm-6 col-md-3 rating-group" id="<?php echo esc_attr( $criteria_slug ); ?>" data-criteria="<?php echo esc_attr( $criteria ); ?>">
										<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
											<input class="rating__input" name="rating3" id="<?php echo esc_attr( $criteria_slug ); ?>-star-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
											<label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label" for="<?php echo esc_attr( $criteria_slug ); ?>-star-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
											<?php $k++; ?>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if ( ! is_user_logged_in() ) { ?>
				<div class="form-row">
					<div class="col-12 col-md-6">
						<label class="font-Poppins font-weight-semibold text-black font-size-16" for="name"><?php echo esc_html( 'Name' ); ?> <span class="text-danger">*</span></label>
						<input type="text" class="form-control mb-2" id="ersrvr_name" placeholder="<?php esc_html_e( 'Eg: John Doe', 'easy-reservations-reviews' ); ?>" value="<?php echo esc_attr( $username ); ?>" />
						<p class="ersrv-reservation-error reviewer-name-error"></p>
					</div>
					<div class="col-12 col-md-6">
						<label class="font-Poppins font-weight-semibold text-black font-size-16" for="email"><?php echo esc_html( 'Email' ); ?> <span class="text-danger">*</span></label>
						<input type="email" class="form-control mb-2" id="ersrvr_email" placeholder="<?php esc_html_e( 'Eg: john.doe@example.com', 'easy-reservations-reviews' ); ?>" value="<?php echo esc_html( $user_email ); ?>" />
						<p class="ersrv-reservation-error reviewer-email-error"></p>
					</div>
				</div>
			<?php } ?>
			<div class="form-row">
				<?php if ( ! is_user_logged_in() ) { ?>
				<div class="col-12 col-md-6">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="phone"><?php esc_html_e( 'Phone Number', 'easy-reservations-reviews' ); ?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control mb-2" id="ersrvr_phone" placeholder="<?php esc_html_e( 'Eg: +1 XXX XXX XXXX', 'easy-reservations-reviews' ); ?>" value="<?php echo esc_html( $user_phone_number ); ?>" />
					<p class="ersrv-reservation-error reviewer-phone-error"></p>
				</div>
				<?php } ?>
				<div class="col-12 col-md-<?php echo esc_attr( ( ! is_user_logged_in() ) ? 6 : 12 ); ?>">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="message"><?php esc_html_e( 'Upload Something Here', 'easy-reservations-reviews' ); ?></label>
					<div class="upload-btn-wrapper">
						<span class="btn btn-block btn-file p-0">
							<input id="actual-btn" name="ersrvr_review_attachments[]" type="file" class="file"  data-show-upload="true" data-show-caption="true" accept="<?php echo esc_attr( implode( ',', ersrvr_get_review_file_allowed_file_types() ) ); ?>" multiple >
						</span>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-12">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="message"><?php esc_html_e( 'Review', 'easy-reservations-reviews' ); ?> <span class="text-danger">*</span></label>
					<textarea name="message" id="ersrvr_message" class="form-control mb-2"  placeholder="<?php esc_html_e( 'Eg: I really enjoyed....', 'easy-reservations-reviews' ); ?>"></textarea>
					<p class="ersrv-reservation-error reviewer-message-error"></p>
				</div>
				<div class="col-12 text-center">
					<button type="button" class="btn ersrvr_btn_submit btn-primary px-4 py-2 font-lato font-size-18 font-weight-bold"><?php echo esc_html( $btn_text ); ?></button>
				</div>
			</div>
		</form>
		<?php
		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_active_stylesheet' ) ) {
	/**
	 * Get the active stysheet URL.
	 *
	 * @param string $current_theme Holds the current theme slug.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_get_active_stylesheet( $current_theme ) {
		switch ( $current_theme ) {
			case 'twentysixteen':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentysixteen.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentysixteen.css',
				);

			case 'twentyseventeen':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentyseventeen.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentyseventeen.css',
				);

			case 'twentynineteen':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentynineteen.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentynineteen.css',
				);

			case 'twentytwenty':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentytwenty.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentytwenty.css',
				);

			case 'twentytwentyone':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentytwentyone.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentytwentyone.css',
				);

			case 'storefront':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-storefront.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-storefront.css',
				);

			default:
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-other.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-other.css',
				);
		}
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_user_logged_in_data' ) ) {
	/**
	 * Get User loggedin Data
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrvr_user_logged_in_data() {
		$user_id = get_current_user_id();

		// Return blank if the user ID is 0.
		if ( 0 === $user_id ) {
			return array();
		}

		$user_data  = get_userdata( $user_id );
		$first_name = get_user_meta( $user_id, 'first_name', true );
		$last_name  = get_user_meta( $user_id, 'last_name', true );
		
		// Prepare the user full name.
		if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
			$name = "{$first_name} {$last_name}";
		} elseif ( ! empty( $first_name ) ) {
			$name = $first_name;
		} elseif ( ! empty( $last_name ) ) {
			$name = $last_name;
		} else {
			$name = ( ! empty( $user_data->data->display_name ) ) ? $user_data->data->display_name : '';
		}

		$user_information = array(
			'name'    => $name,
			'email'   => ( ! empty( $user_data->data->user_email ) ) ? $user_data->data->user_email : '',
			'phone'   => get_user_meta( $user_id, 'billing_phone', true ),
			'user_id' => $user_id,
		);

		/**
		 * This filter helps on the public end fetching user details.
		 *
		 * This filter helps in modifying the user contact details.
		 *
		 * @param array $user_information Current loggedin user information.
		 * @return array
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrvr_add_user_information', $user_information );
	}
}
/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_review_file_allowed_file_types' ) ) {
	/**
	 * Get the allowed file types for driving license.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrvr_get_review_file_allowed_file_types() {
		$file_types = array( '.jpeg', '.jpg', '.png' );

		/**
		 * This hook runs on the checkout page and the order edit page.
		 *
		 * This filter helps in managing the allowed file types for the driving license.
		 *
		 * @param array $file_types File types array.
		 * @return array
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrvr_allowed_file_types_review_file', $file_types );
	}
}
/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_total_average_ratings' ) ) {
	/**
	 * Get total average ratings
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrvr_get_total_average_ratings() {
		global $wpdb;
		$wc_commentmeta        = "{$wpdb->prefix}commentmeta";
		$get_commentmeta_query = "SELECT `meta_value` FROM `{$wc_commentmeta}` WHERE `meta_key` = 'average_ratings'";
		$get_average_ratings   = $wpdb->get_results( $get_commentmeta_query );
		$get_average_ratings   = ! empty( $get_average_ratings ) ? $get_average_ratings : array();
		return $get_average_ratings;

	}
}
/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_html_of_total_review' ) ) {
	/**
	 * Get HTML of total ratings
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_html_of_total_review() {
		ob_start();
		$get_avrage_ratings = ersrvr_get_total_average_ratings();
		if ( ! empty( $get_avrage_ratings ) && is_array( $get_avrage_ratings ) ) {
			foreach ( $get_avrage_ratings as $get_avrage_rating ) {
				$avrage_ratings[] = $get_avrage_rating->meta_value;
			}
		}
		$avrage_ratings      = ! empty( $avrage_ratings ) ? $avrage_ratings : array();
		$total_rating_sum    = ! empty( $avrage_ratings ) ? array_sum( $avrage_ratings ) : 0;
		$total_rating_star   = (int) ( 0 !== $total_rating_sum ) ? round( $total_rating_sum / count( $avrage_ratings ) ) : 0;
		$total_rating_amount = (int) ( 0 !== $total_rating_sum ) ? round( $total_rating_sum / count( $avrage_ratings ), 2 ) : 0;
		?>
		<div class="list-Of-Review-title">
			<h2 class="font-popins font-size-24 font-weight-bold"><?php echo esc_html( $total_rating_amount ); ?> Reviews</h2>
		</div>
		<div class="total-of-star-rating">
			<div class="rating-item d-flex flex-wrap align-items-center">
				<div class="col-12 col-sm-12 rating-group px-0">
					<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
						<?php
						$filled_star_class = ( $total_rating_star >= $i ) ? 'fill_star_click' : '';
						$filled_icons      = ( is_float( $total_rating_amount ) ) ? 'fa-star-half' : 'fa-star';
						?>
					<input class="rating__input <?php echo esc_attr( $filled_star_class ); ?>" name="rating3" id="rating<?php echo esc_attr( $i ); ?>-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
					<label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label" for="rating<?php echo esc_attr( $i ); ?>-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_review_item_html' ) ) {
	/**
	 * Get HTML of total ratings
	 *
	 * @param array $allcomments holds the all comments array.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_get_review_item_html( $comment_id ) {
		$user_id         = (int) $get_all_comment->user_id;
		$current_user    = ersrvr_user_logged_in_data();
		$current_user_id = ! empty( $current_user['user_id'] ) ? $current_user['user_id'] : '';
		$current_user_id = (int) $current_user_id;
		$comment_date    = $get_all_comment->comment_date;
		$comment_date    = gmdate( 'd M Y', strtotime( $comment_date ) );
		$user_obj        = get_userdata( $user_id );
		$first_name      = ! empty( get_user_meta( $user_id, 'first_name', true ) ) ? get_user_meta( $user_id, 'first_name', true ) : '';
		$last_name       = ! empty( get_user_meta( $user_id, 'last_name', true ) ) ? get_user_meta( $user_id, 'last_name', true ) : '';
		$display_name    = ! empty( $user_obj->data->display_name ) ? $user_obj->data->display_name : '';
		$username        = $first_name . ' ' . $last_name;
		$username        = ( ' ' !== $username ) ? $username : $display_name;
		$comment_content = $get_all_comment->comment_content;
		$average_rating  = get_comment_meta( $comment_id, 'average_ratings', true );
		$average_rating  = (int) $average_rating;
		$criteria        = get_comment_meta( $comment_id, 'user_criteria_ratings', true );
		$post_id         = (int) $get_all_comment->comment_post_ID;

		// Prepare the HTML now.
		ob_start();
		?>
		<li class="media mb-4 ersrvr_comment_id_<?php echo esc_attr( $comment_id ); ?>">
			<img class="mr-3 rounded-circle" alt="user-photo" src="<?php echo esc_attr( site_url() ); ?>/wp-content/uploads/2021/08/pexels-jason-boyd-3423147-scaled.jpg" />
			<div class="media-body">
				<div class="media-title">
					<div id="full-stars-example-two" class="rating-group-wrapper">
						<div class="rating-item d-flex flex-wrap align-items-center">
						<?php if ( ! empty( $criteria ) && is_array( $criteria ) ) { ?>
							<a class="text-decoration-none ersrvr-review-details-popup" href="javascript:void(0);">
						<?php } ?>
							<div class="col-auto rating-group px-0">
								<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
									<?php
									$filled_star_class = ( $average_rating >= $i ) ? 'fill_star_click' : '';
									?>
									<input class="rating__input <?php echo esc_attr( $filled_star_class ); ?>" name="rating3" id="rating3-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
									<label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label" for="rating3-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
								<?php } ?>
							</div>
							<?php if ( ! empty( $criteria ) && is_array( $criteria ) ) { ?>
								</a>
							<?php } ?>
							<?php if ( ! empty( $criteria ) && is_array( $criteria ) ) { ?>
								<div class="ersrvr-reservation-reviews-details" id="ersrvr-reservation-reviews-details-id">
									<div class="ersrvr-reservation-reviews-details-summary-wrapper p-3">
										<table class="table table-borderless">
											<tbody>
												<?php $k = 1; ?>
												<?php $rating_by_criteria = array(); ?>
												<?php
												foreach ( $criteria as $criteria_data ) {
													$rating_by_criteria[] = array(
														$criteria_data['closest_criteria'] => $criteria_data['rating'],
													);
													$final_ratings_array  = array();
													foreach ( $rating_by_criteria as $key => $formatted_data ) {
														foreach ( $formatted_data as $key => $value ) {
															$final_ratings_array[ $key ] = $value;
														}
													}
												}
												foreach ( $final_ratings_array as $criteria_key => $criteria_rating ) {
													$criteria_rating = (int) $criteria_rating;
													$criteria_name   = ucfirst( $criteria_key );
													$criteria_name   = str_replace( '-', ' ', $criteria_name );
													$criteria_slug   = $criteria_key;
													?>
													<tr class="<?php echo esc_html( $criteria_name ); ?>">
														<th><?php echo esc_html( $criteria_name ); ?></th>
														<td>
															<div class="col-8 col-sm-9 rating-group" id="<?php echo esc_attr( $criteria_slug ); ?>" data-criteria="<?php echo esc_attr( $criteria_name ); ?>">
																<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
																	<?php $filled_star_class = ( $criteria_rating >= $i ) ? 'fill_star_click' : ''; ?>
																	<input class="rating__input <?php echo esc_attr( $filled_star_class ); ?>" name="rating3" id="<?php echo esc_attr( $criteria_slug ); ?>-star-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
																	<label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label" for="<?php echo esc_attr( $criteria_slug ); ?>-star-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
																<?php } ?>
															</div>
														</td>
													</tr>
													<?php $k++; ?>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							<?php } ?>
							<div class="col-auto"><label class="font-Poppins font-weight-semibold text-muted font-size-14"><?php echo esc_html( sprintf( __( '%1$s  of 5', 'easy-reservations-reviews' ), $average_rating ) ); ?> </label>
							</div>
						</div>
					</div>
					<h5 class="mt-2 mb-1 font-popins font-size-16 font-weight-semibold">
						<?php echo esc_html( $username ); ?>
						<span class="text-muted font-lato font-weight-normal font-size-14">- <?php echo esc_html( $comment_date ); ?></span>
						<?php if ( $user_id === $current_user_id ) { ?>
							<?php
							$edit_setting = get_option( 'ersrvr_enable_edit_reservation_reviews' );
							?>
							<?php if ( 'yes' === $edit_setting  && is_user_logged_in() ) { ?>
							<span class="text-muted font-lato font-weight-normal font-size-14">- <a href="#" class="ersrvr_edit_review" data-commentid="<?php echo esc_html( $comment_id ); ?>" data-userid="<?php echo esc_html( $current_user_id ); ?>" data-postid="<?php echo esc_html( $post_id ); ?>" ><span class="fa fa-pencil-alt"></span></a></span>
							<?php } ?>
							<?php $delete_setting = get_option( 'ersrvr_enable_delete_reservation_reviews' ); ?>
							<?php if ( 'yes' === $delete_setting && is_user_logged_in() ) { ?>
							<span class="text-muted font-lato font-weight-normal font-size-14">- <a href="#" class="ersrvr_delete_review" data-commentid="<?php echo esc_html( $comment_id ); ?>" data-userid="<?php echo esc_html( $current_user_id ); ?>" data-postid="<?php echo esc_html( $post_id ); ?>" ><span class="fa fa-window-close"></span></a></span>
							<?php } ?>
						<?php } ?>
					</h5>
				</div>
				<p class="font-lato font-size-14 font-weight-normal mb-3"><?php echo esc_html( $comment_content ); ?></p>
				<?php
				$attached_ids = get_comment_meta( $comment_id, 'attached_files', true );
				if ( ! empty( $attached_ids ) && is_array( $attached_ids ) ) {
					?>
					<div class="gallery-images ersrvr_count_images_3">
						<?php
						foreach ( $attached_ids as $index => $attach_id ) {
							$gallery_images_last_index = count( $attached_ids ) - 1;
							$image_url                 = ( ! empty( $attach_id ) ) ? wp_get_attachment_url( $attach_id ) : '';
							/**
							* Last image custom class.
							* And, this should work only when the images are more than 5.
							*/
							$last_gallery_image_custom_class = '';
							$last_gallery_image_custom_text  = '';
							if ( 6 < count( $attached_ids ) && 5 === $index ) {
								$last_gallery_image_custom_class = 'ersrvr_gallery-last-image-overlay';
								$last_gallery_image_custom_text  = sprintf( __( '+%1$d images', 'easy-reservations-reviews' ), ( count( $attached_ids ) - 6 ) );
							}
							// Hide the images after 6 images.
							$display_none_image_class = ( 5 < $index ) ? 'd-none' : '';
							if ( ! empty( $image_url ) ) {
								?>
								<div data-text="<?php echo esc_html( $last_gallery_image_custom_text ); ?>" class="ersrvr-gallery-image-item gallery-image-item <?php echo esc_attr( "{$last_gallery_image_custom_class} {$display_none_image_class}" ); ?>" data-imageid="<?php echo esc_attr( $attach_id ); ?>">
									<img src="<?php echo esc_url( $image_url ); ?>" class="ersrvr_attached_files" />
								</div>
								<?php
							}
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		</li>
		<?php

		return ob_get_clean();
	}
}
