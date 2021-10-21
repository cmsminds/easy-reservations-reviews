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

if ( ! function_exists( 'ersrvr_setting_fields' ) ) {
	/**
	 * Add setting fields here.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrvr_setting_fields() {
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
				'name' => __( 'For Guset User', 'easy-reservations-reviews' ),
				'type' => 'checkbox',
				'desc' => __( 'This will decide whether the guest user can fill up their reviews or not. Default is no.', 'easy-reservations-reviews' ),
				'id'   => 'ersrvr_enable_reservation_reviews_guest_users',
			),
			array(
				'name' => __( 'Edit Review', 'easy-reservations-reviews' ),
				'type' => 'checkbox',
				'desc' => __( 'This will decide whether the user can have ability to edit reviews or not.', 'easy-reservations-reviews' ),
				'id'   => 'ersrvr_enable_edit_reservation_reviews',
			),
			array(
				'name' => __( 'Delete Review', 'easy-reservations-reviews' ),
				'type' => 'checkbox',
				'desc' => __( 'This will decide whether the user can have ability to delete reviews or not.', 'easy-reservations-reviews' ),
				'id'   => 'ersrvr_enable_delete_reservation_reviews',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrvr_reviews',
			),
		);
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
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? 'yes' : 'no';
				break;
			case 'ersrvr_enable_edit_reservation_reviews':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? 'yes' : 'no';
				break;
			case 'ersrvr_enable_delete_reservation_reviews':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? 'yes' : 'no';
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
if ( ! function_exists( 'ersrvr_prepare_reviews_html' ) ) {
	/**
	 * Make Review Template HTML.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_prepare_reviews_html() {
		$html = '';
		ob_start();
		$file_path = plugin_dir_path( __DIR__ ) . 'public/templates/woocommerce/easy-reservations-reviews-html.php';
		if ( file_exists( $file_path ) ) {
			require_once $file_path;
		}
		$html .= wp_kses(
			ob_get_clean(),
			array(
				'div'      => array(
					'class'         => array(),
					'id'            => array(),
					'role'          => array(),
					'data-criteria' => array(),
					'style'         => array(),
					'aria-valuenow' => array(),
					'aria-valuemin' => array(),
					'aria-valuemax' => array(),
					'tabindex'      => array(),
					'data-text'     => array(),
					'data-imageid'  => array(),
				),
				'span'     => array(
					'class'     => array(),
					'data-cost' => array(),
				),
				'p'        => array(
					'class' => array(),
				),
				'a'        => array(
					'href'           => array(),
					'class'          => array(),
					'download'       => array(),
					'data-toggle'    => array(),
					'role'           => array(),
					'aria-expanded'  => array(),
					'aria-controls'  => array(),
					'data-commentid' => array(),
					'data-userid'    => array(),
					'data-postid'    => array(),
				),
				'h1'       => array(),
				'button'   => array(
					'type'  => array(),
					'class' => array(),
				),
				'input'    => array(
					'type'              => array(),
					'name'              => array(),
					'id'                => array(),
					'accept'            => array(),
					'placeholder'       => array(),
					'class'             => array(),
					'value'             => array(),
					'checked'           => array(),
					'multiple'          => array(),
					'data-show-upload'  => array(),
					'data-show-caption' => array(),
				),
				'form'     => array(
					'method'  => array(),
					'enctype' => array(),
					'action'  => array(),
				),
				'label'    => array(
					'class' => array(),
					'for'   => array(),
				),
				'textarea' => array(
					'name'        => array(),
					'id'          => array(),
					'class'       => array(),
					'placeholder' => array(),
				),
				'h2'       => array(
					'class' => array(),
				),
				'h3'       => array(
					'class' => array(),
				),
				'h4'       => array(
					'class' => array(),
				),
				'h5'       => array(
					'class' => array(),
				),
				'img'      => array(
					'src'   => array(),
					'class' => array(),
					'alt'   => array(),
				),
				'ul'       => array(
					'class' => array(),
				),
				'li'       => array(
					'class' => array(),
				),
				'table'    => array(
					'class' =>  array(),
				),
				'tbody'    => array(
					'class' =>  array(),
				),
				'th'       => array(
					'class' =>  array(),
				),
				'td'       => array(
					'class' =>  array(),
				),
				'tr'       => array(
					'class' =>  array(),
				),
			),
		);
		return $html;
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
		ob_start(); ?>
		<form action="" method="post" enctype="multipart/form-data">
			<?php if ( ! empty( $criterias ) && is_array( $criterias ) ) { ?>
				<div class="form-row">
					<div class="col-12">
						<label class="font-Poppins font-weight-semibold text-black font-size-14"><?php esc_html_e( 'Please reate us 1 (bad) to 5 (excellent)', 'easy-reservations-reviews' ); ?></label>
						<div id="full-stars-example-two" class="rating-group-wrapper border py-2 px-1 rounded-xl">
							<?php $k = 1; ?>
							<?php foreach ( $criterias as $criteria ) { ?>
								<?php
								$criteria_slug = strtolower( $criteria );
								$criteria_slug = str_replace( ' ', '-', $criteria_slug );
								?>
								<div class="rating-item d-flex flex-wrap align-items-center mb-3 mb-md-0">
									<div class="col-12 col-sm-6 col-md-3 mb-1 mb-sm-0"><label class="font-Poppins font-weight-semibold text-black font-size-14"><?php echo esc_html( $criteria ); ?> </label></div>
									<div class="col-12 col-sm-6 col-md-9 rating-group" id="<?php echo esc_attr( $criteria_slug ); ?>" data-criteria="<?php echo esc_attr( $criteria ); ?>">
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
						<input type="text" class="form-control mb-2" id="ersrvr_name" placeholder="Name" value="<?php echo esc_attr( $username ); ?>" />
					</div>
					<div class="col-12 col-md-6">
						<label class="font-Poppins font-weight-semibold text-black font-size-16" for="email"><?php echo esc_html( 'Email' ); ?> <span class="text-danger">*</span></label>
						<input type="email" class="form-control mb-2" id="ersrvr_email" placeholder="E-mail" value="<?php echo esc_html( $user_email ); ?>" />
					</div>
				</div>
			<?php } ?>
			<div class="form-row">
				<?php if ( ! is_user_logged_in() ) { ?>
				<div class="col-12 col-md-6">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="phone"><?php esc_html_e( 'Phone Number', 'easy-reservations-reviews' ); ?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control mb-2" id="ersrvr_phone" placeholder="Phone Number" value="<?php echo esc_html( $user_phone_number ); ?>" />
				</div>
				<?php } ?>
				<?php if ( ! is_user_logged_in() ) { ?>
					<?php $row_size_value = 6; ?>
				<?php } else { ?> 
					<?php $row_size_value = 12; ?>
				<?php } ?>
				<div class="col-12 col-md-<?php echo esc_attr( $row_size_value ); ?>">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="message"><?php esc_html_e( 'Upload Something Here', 'easy-reservations-reviews' ); ?></label>
					<div class="upload-btn-wrapper">
						<label class="control-label">Attachment(s) (Attach multiple files.)</label>
						<span class="btn btn-block btn-file px-0">
							<input id="actual-btn" name="ersrvr_actual_btn[]" type="file" class="file"  data-show-upload="true" data-show-caption="true" accept="<?php echo esc_attr( implode( ',', ersrvr_get_review_file_allowed_file_types() ) ); ?>" multiple >
						</span>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-12">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="message"><?php esc_html_e( 'Review', 'easy-reservations-reviews' ); ?> <span class="text-danger">*</span></label>
					<textarea name="message" id="ersrvr_message" class="form-control mb-2"  placeholder="Message"></textarea>
				</div>
				<div class="col-12 text-center">
					<button type="submit" class="btn ersrvr_btn_submit btn-primary px-4 py-2 font-lato font-size-18 font-weight-bold"><?php echo esc_html( $btn_text ); ?></button>
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
		$current_userid = get_current_user_id();
		$users_info     = array();
		if ( 0 === $current_userid ) {
			// The user ID is 0, therefore the current user is not logged in.
			return $users_info; // escape this function, without making any changes.
		} else {
			$user_obj          = get_userdata( $current_userid );
			$first_name        = ! empty( get_user_meta( $current_userid, 'first_name', true ) ) ? get_user_meta( $current_userid, 'first_name', true ) : '';
			$last_name         = ! empty( get_user_meta( $current_userid, 'last_name', true ) ) ? get_user_meta( $current_userid, 'last_name', true ) : '';
			$display_name      = ! empty( $user_obj->data->display_name ) ? $user_obj->data->display_name : '';
			$username          = $first_name . ' ' . $last_name;
			$username          = ( ' ' !== $username ) ? $username : $display_name;
			$user_email        = ! empty( $user_obj->data->user_email ) ? $user_obj->data->user_email : '';
			$user_phone_number = ! empty( get_user_meta( $current_userid, 'billing_phone', true ) ) ? get_user_meta( $current_userid, 'billing_phone', true ) : '';
			$users_info        = array(
				'username'          => $username,
				'user_email'        => $user_email,
				'user_phone_number' => $user_phone_number,
				'user_id'           => $current_userid,
			);
		}
		// This filters holds the user information modifications.
		return apply_filters( 'ersrvr_add_user_information', $users_info, $current_userid );
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
		$file_types = array( '.jpeg', '.jpg', '.pdf', '.png' );

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
if ( ! function_exists( 'ersrvr_html_comment_message_box' ) ) {
	/**
	 * Get HTML of total ratings
	 *
	 * @param array $allcomments holds the all comments array.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_html_comment_message_box( $allcomments ) {
		ob_start();
		foreach ( $allcomments as $get_all_comment ) {
			$commnet_id      = $get_all_comment->comment_ID;
			$user_id         = (int) $get_all_comment->user_id;
			$current_user    = ersrvr_user_logged_in_data();
			$current_user_id = (int) $current_user['user_id'];
			$comment_date    = $get_all_comment->comment_date;
			$comment_date    = gmdate( 'd M Y', strtotime( $comment_date ) );
			$user_obj        = get_userdata( $user_id );
			$first_name      = ! empty( get_user_meta( $user_id, 'first_name', true ) ) ? get_user_meta( $user_id, 'first_name', true ) : '';
			$last_name       = ! empty( get_user_meta( $user_id, 'last_name', true ) ) ? get_user_meta( $user_id, 'last_name', true ) : '';
			$display_name    = ! empty( $user_obj->data->display_name ) ? $user_obj->data->display_name : '';
			$username        = $first_name . ' ' . $last_name;
			$username        = ( ' ' !== $username ) ? $username : $display_name;
			$comment_content = $get_all_comment->comment_content;
			$average_rating  = get_comment_meta( $commnet_id, 'average_ratings', true );
			$average_rating  = (int) $average_rating;
			$criteria        = get_comment_meta( $commnet_id, 'user_criteria_ratings', true );
			$post_id         = (int) $get_all_comment->comment_post_ID;
			?>
			<li class="media mb-4 ersrvr_comment_id_<?php echo esc_attr( $commnet_id ); ?>">
				<img class="mr-3 rounded-circle" alt="user-photo" src="<?php echo esc_attr( site_url() ); ?>/wp-content/uploads/2021/08/pexels-jason-boyd-3423147-scaled.jpg" />
				<div class="media-body">
					<div class="media-title">
						<div id="full-stars-example-two" class="rating-group-wrapper">
							<div class="rating-item d-flex flex-wrap align-items-center">
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
									<div class="ersrvr-reservation-reviews-details" id="ersrvr-reservation-reviews-details-id">
										<div class="ersrvr-reservation-details-item-summary-wrapper p-3">
											<table class="table table-borderless">
												<tbody>
													<?php $k = 1; ?>
													<?php $rating_by_criteria = array(); ?>
													<?php 
													foreach( $criteria as $criteria_data ) {
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
								<div class="col-auto"><label class="font-Poppins font-weight-semibold text-muted font-size-14"><?php echo esc_html( '( '. $average_rating .' of 5 )' ); ?> </label>
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
								<?php if ( 'yes' === $edit_setting ) { ?>
								<span class="text-muted font-lato font-weight-normal font-size-14">- <a href="#" class="ersrvr_edit_review" data-commentid="<?php echo esc_html( $commnet_id ); ?>" data-userid="<?php echo esc_html( $current_user_id ); ?>" data-postid="<?php echo esc_html( $post_id ); ?>" ><span class="fa fa-pencil-alt"></span></a></span>
								<?php } ?>
								<?php $delete_setting = get_option( 'ersrvr_enable_delete_reservation_reviews' ); ?>
								<?php if ( 'yes' === $delete_setting ) { ?>
								<span class="text-muted font-lato font-weight-normal font-size-14">- <a href="#" class="ersrvr_delete_review" data-commentid="<?php echo esc_html( $commnet_id ); ?>" data-userid="<?php echo esc_html( $current_user_id ); ?>" data-postid="<?php echo esc_html( $post_id ); ?>" ><span class="fa fa-window-close"></span></a></span>
								<?php } ?>
							<?php } ?>
						</h5>
					</div>
					<p class="font-lato font-size-14 font-weight-normal mb-3"><?php echo esc_html( $comment_content ); ?></p>
					<?php
					$attached_ids   = get_comment_meta( $commnet_id, 'attached_files', true );
					if ( ! empty( $attached_ids ) && is_array( $attached_ids ) ) {
						?>
						<div class="gallery-images ersrvr_count_images_3">
							<?php 
							foreach ( $attached_ids as $index => $attach_id ) {
								$gallery_images_last_index = count( $attached_ids ) - 1;
								$image_url = ( ! empty( $attach_id ) ) ? wp_get_attachment_url( $attach_id ) : '';
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
								// debug( $last_gallery_image_custom_text );
								// die;
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
		<?php } ?>
		<?php
		return ob_get_clean();
	}
}
