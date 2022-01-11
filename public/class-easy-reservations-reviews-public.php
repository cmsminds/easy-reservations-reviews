<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cmsminds.com
 * @since      1.0.0
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/public
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Reviews_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the JavaScript & stylesheet for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function ersrvr_wp_enqueue_scripts_callback() {
		global $wp_registered_widgets, $post, $wp_query;

		// Active style file based on the active theme.
		$current_theme     = get_option( 'stylesheet' );
		$active_style      = ersrvr_get_active_stylesheet( $current_theme );
		$active_style_url  = ( ! empty( $active_style['url'] ) ) ? $active_style['url'] : '';
		$active_style_path = ( ! empty( $active_style['path'] ) ) ? $active_style['path'] : '';

		// Enque Style file.
		if ( ! empty( $active_style_url ) && ! empty( $active_style_path ) ) {
			wp_enqueue_style(
				$this->plugin_name,
				$active_style_url,
				array(),
				filemtime( $active_style_path ),
			);
		}

		// Enque Javascript file.
		wp_enqueue_script(
			$this->plugin_name,
			ERSRVR_PLUGIN_URL . 'public/js/easy-reservations-reviews-public.js',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/js/easy-reservations-reviews-public.js' ),
			true
		);

		wp_enqueue_script(
			$this->plugin_name . '-fileinput',
			ERSRVR_PLUGIN_URL . 'public/js/fileinput.js',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/js/fileinput.js' ),
			true
		);

		// Enqueue the common public style.
		wp_enqueue_style(
			$this->plugin_name . '-common',
			ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-common.css',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-common.css' )
		);
		wp_enqueue_style(
			$this->plugin_name . '-fileinput',
			ERSRVR_PLUGIN_URL . 'public/css/fileinput.css',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/css/fileinput.css' )
		);

		// Localized strings.
		$localized_strings = array(
			'ajaxurl'                                => admin_url( 'admin-ajax.php' ),
			'user_logged_in'                         => ( is_user_logged_in() ) ? 'yes' : 'no',
			'toast_error_heading'                    => __( 'Ooops! Error..', 'easy-reservations-reviews' ),
			'invalid_reviews_message_error_text'     => __( 'Please provide review text.', 'easy-reservations-reviews' ),
			'invalid_reviews_email_error_text'       => __( 'Please provide your email address.', 'easy-reservations-reviews' ),
			'invalid_reviews_email_regex_error_text' => __( 'Please review the email address provided.', 'easy-reservations-reviews' ),
			'invalid_reviews_phone_error_text'       => __( 'Please provide your contact number.', 'easy-reservations-reviews' ),
			'invalid_reviews_name_error_text'        => __( 'Please provide your name.', 'easy-reservations-reviews' ),
			'review_cannot_be_submitted'             => __( 'There are a few errors that need to be addressed before submitting the review.', 'easy-reservations-reviews' ),
			'toast_success_heading'                  => __( 'Woohhoooo! Success..', 'easy-reservations-reviews' ),
			'review_file_allowed_extensions'         => ersrvr_get_review_file_allowed_file_types(),
			'review_file_invalid_file_error'         => sprintf( __( 'Invalid file selected. Allowed extensions are: %1$s', 'easy-reservations-reviews' ), implode( ', ', ersrvr_get_review_file_allowed_file_types() ) ),
		);

		/**
		 * This hook fires in public side of the site.
		 *
		 * This filter helps in modifying the localized strings used on the public side.
		 *
		 * @param array $localized_strings List of localized strings.
		 * @return array
		 * @since 1.0.0
		 */
		$localized_strings = apply_filters( 'ersrvr_public_localized_strings', $localized_strings );

		// Localize variables.
		wp_localize_script( $this->plugin_name, 'ERSRVR_Reviews_Public_Script_Vars', $localized_strings );
	}

	/**
	 * Review Form Shortcode.
	 *
	 * @param array $args Holds the shortcode arguments.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrvr_review_form_shortcode_callback( $args = array() ) {
		// Return, if it's admin panel.
		if ( is_admin() ) {
			return;
		}

		ob_start();
		require_once ERSRVR_PLUGIN_PATH . 'public/templates/woocommerce/easy-reservations-reviews-html.php';
		return ob_get_clean();
	}
	/**
	 * Add review setting section on easy reservations setting page.
	 *
	 * @param string $reservation_id Holds the reservation product id.
	 * @since 1.0.0
	 */
	public function ersrvr_after_item_details_callback( $reservation_id ) {
		echo do_shortcode( '[ersrvr_review_form_shortcode]' );
	}

	/**
	 * Review summary on the reservation item header.
	 *
	 * @param int $item_id Reservation item ID.
	 * @since 1.0.0
	 */
	public function ersrvr_ersrv_after_reservation_item_title_callback( $item_id ) {
		?>
		<div class="review d-flex justify-content-center align-items-center color-white mb-3 font-size-17 font-weight-normal">
			<img src="<?php echo esc_url ( ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
			<span class="ml-2">(1 review)</span>
		</div>
		<?php
	}

	/**
	 * Add review summary on the reservation item blocks html.
	 *
	 * @param int    $item_id Reservation item ID.
	 * @param string $page Current page.
	 * @since 1.0.0
	 */
	public function ersrvr_ersrv_single_reservation_block_after_title_callback( $item_id, $page ) {
		?>
		<div class="review-stars mb-3">
			<img src="<?php echo esc_url ( ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
		</div>
		<?php
	}

	/**
	 * Submit Review Form Data
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_submit_review_callback() {
		$user_information = ersrvr_user_logged_in_data();
		// Posted data.
		$posted_array          = filter_input_array( INPUT_POST );
		$reviewer_email        = ( is_user_logged_in() ) ? $user_information['email'] : filter_input( INPUT_POST, 'reviewer_email', FILTER_SANITIZE_STRING );
		$reviewer_name         = ( is_user_logged_in() ) ? $user_information['name'] : filter_input( INPUT_POST, 'reviewer_name', FILTER_SANITIZE_STRING );
		$reviewer_phone        = ( is_user_logged_in() ) ? $user_information['phone'] : filter_input( INPUT_POST, 'reviewer_phone', FILTER_SANITIZE_STRING );
		$reviewer_message      = filter_input( INPUT_POST, 'reviewer_message', FILTER_SANITIZE_STRING );
		$item_id               = filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$attachments           = ( ! empty( $_FILES['review_attachments'] ) ) ? $_FILES['review_attachments'] : array();
		$review_attachment_ids = array();
		$user_ratings          = filter_input( INPUT_POST, 'user_ratings' );
		$user_ratings          = ( ! empty( $user_ratings ) ) ? json_decode( $user_ratings, true ) : array();
		$need_manual_approval  = ersrvr_get_plugin_settings( 'ersrvr_review_needs_manual_approval' );

		// Get the attachments so they can be uploaded to the media.
		if ( ! empty( $attachments['name'] ) && is_array( $attachments['name'] ) ) {
			// Iterate through the files to upload them.
			foreach ( $attachments['name'] as $attachment_index => $attachment_filename ) {
				$attachment_tmp_name = $attachments['tmp_name'][ $attachment_index ];
				$upload_attachment   = wp_upload_bits( $attachment_filename, null, file_get_contents( $attachment_tmp_name ) );
				if ( ! $upload_attachment['error'] ) {
					$wp_filetype   = wp_check_filetype( $attachment_filename, null );
					$attachment    = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_parent'    => 0,
						'post_title'     => preg_replace('/\.[^.]+$/', '', $attachment_filename ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);
					$attachment_id = wp_insert_attachment( $attachment, $upload_attachment['file'] );
					if ( ! is_wp_error( $attachment_id ) ) {
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_attachment['file'] );
						wp_update_attachment_metadata( $attachment_id,  $attachment_data );
					}

					$review_attachment_ids[] = $attachment_id; // Collect the attachment files here.
				}
			}
		}

		// Prepare the review data to be inserted into the database.
		$review_data = array(
			'comment_agent'        => $_SERVER['HTTP_USER_AGENT'],
			'comment_approved'     => ( ! empty( $need_manual_approval ) && 'no' === $need_manual_approval ) ? 1 : 0,
			'comment_author'       => $reviewer_name,
			'comment_author_email' => $reviewer_email,
			'comment_author_IP'    => $_SERVER['REMOTE_ADDR'],
			'comment_content'      => $reviewer_message,
			'comment_post_ID'      => $item_id,
			'user_id'              => get_current_user_id(),
			'comment_meta'         => array(
				'user_ratings'       => $user_ratings,
				'review_attachments' => $review_attachment_ids,
				'reviewer_phone'     => $reviewer_phone,
			),
		);

		/**
		 * This hook fires on the AJAX call that adds a new comment.
		 *
		 * This filter helps you to modify the data before the comment is saved in the database.
		 *
		 * @param array $review_data Revoew data.
		 * @return array
		 * @since 1.0.0
		 */
		$review_data = apply_filters( 'ersrvr_posted_review_data', $review_data );

		// Insert the comment now.
		wp_insert_comment( $review_data );

		// Success message.
		if ( ! empty( $need_manual_approval ) && 'no' === $need_manual_approval ) {
			$toast_message = __( 'Your review has been published. Thank you for your efforts.', 'easy-reservations-reviews' );
		} else {
			$toast_message = __( 'Thank you for providing your review. It is under moderation and will be approved soon.', 'easy-reservations-reviews' );
		}

		/**
		 * This hook fires on the AJAX call that adds a new comment.
		 *
		 * This filter helps you to modify the success message that appears once the review has been added to the database.
		 *
		 * @param string $toast_message AJAX success message.
		 * @param string $need_manual_approval Review needs manual approval.
		 * @return string
		 * @since 1.0.0
		 */
		$toast_message = apply_filters( 'ersrvr_review_added_success_message', $toast_message, $need_manual_approval );

		// Send the AJAX response now.
		wp_send_json_success(
			array(
				'code'          => 'review-added',
				'toast_message' => $toast_message,
			)
		);
		wp_die();
	}

	/**
	 * Add custom assets to footer section.
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_wp_footer_callback() {
		global $post, $wp_query;
		require_once ERSRVR_PLUGIN_PATH . 'public/templates/modals/edit-review.php';
	}

	/**
	 * Delete row of comments.
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_delete_review_comment() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		// Check if action mismatches.
		if ( empty( $action ) || 'ersrvr_delete_review_comment' !== $action ) {
			wp_die();
		}
		$comment_id = filter_input( INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT );
		$post_id    = filter_input( INPUT_POST, 'postid', FILTER_SANITIZE_NUMBER_INT );
		if ( ! empty( $comment_id ) ) {
			wp_delete_comment( $comment_id, true );
		}
		$get_all_comments    = get_comments(
			array(
				'post_id' => $post_id,
			),
		);
		$html                = ersrvr_html_of_total_review();
		$status_zero_comment = ! empty( $get_all_comments ) ? 'available' : 'not_available';
		$response            = array(
			'code'                => 'ersrvr_delete_comments_success',
			'deleted_comment_id'  => $comment_id,
			'html'                => $html,
			'status_zero_comment' => $status_zero_comment,
		);
		wp_send_json_success( $response );
		wp_die();
	}

}
