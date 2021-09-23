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
				'desc'        => __( 'This holds the submit reviews button text. Default: Submit Review', 'easy-reservations-reviews' ),
				'desc_tip'    => true,
				'id'          => 'ersrvr_submit_review_button_text',
				'placeholder' => __( 'E.g.: Submit Review', 'easy-reservations-reviews' ),
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
				'name' => __( 'Enable', 'easy-reservations-reviews' ),
				'type' => 'checkbox',
				'desc' => __( 'This will decide whether the guest user can fill up their reviews or not. Default is no.', 'easy-reservations-reviews' ),
				'id'   => 'ersrvr_enable_reservation_reviews_guest_users',
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
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
				break;
			case 'ersrvr_enable_reservation_reviews_guest_users':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
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
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
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
				),
				'span'     => array(
					'class' => array(),
				),
				'p'        => array(
					'class' => array(),
				),
				'a'        => array(
					'href'          => array(),
					'class'         => array(),
					'download'      => array(),
					'data-toggle'   => array(),
					'role'          => array(),
					'aria-expanded' => array(),
					'aria-controls' => array(),
				),
				'h1'       => array(),
				'button'   => array(
					'type'  => array(),
					'class' => array(),
				),
				'input'    => array(
					'type'        => array(),
					'name'        => array(),
					'id'          => array(),
					'accept'      => array(),
					'placeholder' => array(),
					'class'       => array(),
					'value'       => array(),
					'checked'	  => array(),
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
		$criterias = ersrvr_submit_review_criterias();
		$btn_text  = ersrvr_submit_review_button_text();
		ob_start(); ?>
		<form action="#" method="post" enctype="multipart/form-data">
			<div class="form-row">
				<div class="col-12">
					<label class="font-Poppins font-weight-semibold text-black font-size-14"><?php esc_html_e( 'Please reate us 1 (bad) to 5 (excellent)', 'easy-reservations-reviews' ); ?> <span class="text-danger"><?php esc_html_e( '*', 'easy-reservations-reviews' ); ?></span></label>
					<div id="full-stars-example-two" class="rating-group-wrapper border py-2 px-1 rounded-xl">
						<!-- rating items starts here -->
						<?php if ( is_array( $criterias ) && ! empty( $criterias ) ) { ?>
							<?php $k = 1; ?>
							<?php foreach ( $criterias as $criteria ) { 
								$criteria_slug = strtolower( $criteria );
								$criteria_slug = str_replace( ' ', '-', $criteria_slug );
								?>
								<div class="rating-item d-flex flex-wrap align-items-center">
									<div class="col-4 col-sm-3"><label class="font-Poppins font-weight-semibold text-black font-size-14"><?php echo esc_html( $criteria ); ?> </label></div>
									<div class="col-8 col-sm-9 rating-group" data-criteria="<?php echo esc_attr( $criteria ); ?>">
										<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
											<label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label" for="<?php echo esc_attr( $criteria_slug ); ?>-star-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
											<input class="rating__input" name="rating3" id="<?php echo esc_attr( $criteria_slug ); ?>-star-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
											<?php $k++; ?>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-6">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="name"><?php echo esc_html( 'Name' ); ?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control mb-2" id="name" placeholder="Name" value="<?php echo esc_attr( $username ); ?>" />
				</div>
				<div class="col-12 col-md-6">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="email"><?php echo esc_html( 'Email' ); ?> <span class="text-danger">*</span></label>
					<input type="email" class="form-control mb-2" id="email" placeholder="E-mail" value="<?php echo esc_html( $user_email ); ?>" />
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-6">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="phone"><?php esc_html_e( 'Phone Number', 'easy-reservations-reviews' ); ?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control mb-2" id="phone" placeholder="Phone Number" value="<?php echo esc_html( $user_phone_number ); ?>" />
				</div>
				<div class="col-12 col-md-6">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="message"><?php esc_html_e( 'Upload Something Here', 'easy-reservations-reviews' ); ?> <span class="text-danger">*</span></label>
					<div class="upload-btn-wrapper">
						<!-- actual upload which is hidden -->
						<input type="file" id="actual-btn" class="invisible sr-only"/>
						<!-- our custom upload button -->
						<div class="d-flex align-items-center flex-wrap cus-btn">
							<label for="actual-btn" class="btn btn-outline-fill-primary d-inline-bloxk px-3 mb-0"> <span class="fa fa-upload mr-2"></span><?php esc_html_e( 'Add File', 'easy-reservations-reviews' ); ?></label>
							<span id="file-chosen" class="font-lato font-size-14 ml-2 font-weight-semibold"><?php esc_html_e( 'No file chosen', 'easy-reservations-reviews' ); ?></span>
							<!--
								for file chosen js code
								https://codepen.io/wizardofcodez/pen/XWddObO  
								js code
								const actualBtn = document.getElementById('actual-btn');

								const fileChosen = document.getElementById('file-chosen');

								actualBtn.addEventListener('change', function(){
								fileChosen.textContent = this.files[0].name
								})
							-->

						</div>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-12">
					<label class="font-Poppins font-weight-semibold text-black font-size-16" for="message"><?php esc_html_e( 'Review', 'easy-reservations-reviews' ); ?> <span class="text-danger">*</span></label>
					<textarea name="message" id="message" class="form-control mb-2"  placeholder="Message"></textarea>
				</div>
				<div class="col-12 text-center">
					<label class="font-Poppins font-weight-normal text-black font-size-15"><?php esc_html_e( 'Thanks Again For Your Review!', 'easy-reservations-reviews' ); ?></label>
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
			);
		}
		// This filters holds the user information modifications.
		return apply_filters( 'ersrvr_add_user_information', $users_info, $current_userid );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_submit_review_button_text' ) ) {
	/**
	 * Get setting  of submit button name.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_submit_review_button_text() {
		return ersrvr_get_plugin_settings( 'ersrvr_submit_review_button_text' );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_submit_review_criterias' ) ) {
	/**
	 * Get criteria
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrvr_submit_review_criterias() {
		return ersrvr_get_plugin_settings( 'ersrvr_submit_review_criterias' );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_enable_reservation_reviews_guest_users' ) ) {
	/**
	 * Get setting of enable form display for guest users.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_enable_reservation_reviews_guest_users() {
		return ersrvr_get_plugin_settings( 'ersrvr_enable_reservation_reviews_guest_users' );
	}
}
