<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cmsminds.com
 * @since      1.0.0
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/admin
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Reviews_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Comment ID.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $comment_id The current comment ID on the comment edit screen.
	 */
	private $comment_id;

	/**
	 * Comment object.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $comment The current comment object on the comment edit screen.
	 */
	private $comment;

	/**
	 * Comment post ID.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $comment_post_id The current comment post ID on the comment edit screen.
	 */
	private $comment_post_id;

	/**
	 * Comment post ID is a reservation item.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var bool $comment_post_id_is_reservation If the comment post ID is a reservation item.
	 */
	private $comment_post_id_is_reservation;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name                    = $plugin_name;
		$this->version                        = $version;
		$this->comment_id                     = filter_input( INPUT_GET, 'c', FILTER_SANITIZE_NUMBER_INT ); // Get details about the comment ID and related post ID.
		$this->comment_post_id                = false;
		$this->comment_post_id_is_reservation = false;

		// If the comment ID is available.
		if ( ! is_null( $this->comment_id ) ) {
			$this->comment         = get_comment( $this->comment_id );
			$this->comment_post_id = ( ! empty( $this->comment->comment_post_ID ) ) ? $this->comment->comment_post_ID : false;
		}
	}

	/**
	 * Register the JavaScriptn & stylesheet for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function ersrvr_admin_enqueue_scripts_callback() {
		// If the comment post ID is available, check if the post ID is a reservation item.
		$this->comment_post_id_is_reservation = ( false !== $this->comment_post_id ) ? ersrv_product_is_reservation( $this->comment_post_id ) : $this->comment_post_id_is_reservation;

		// Enqueue the font-awesome style.
		wp_enqueue_style(
			$this->plugin_name . '-font-awesome-style',
			ERSRVR_PLUGIN_URL . 'admin/css/fontawesome/all.min.css',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'admin/css/fontawesome/all.min.css' )
		);

		// Enqueue the custom admin style.
		wp_enqueue_style(
			$this->plugin_name,
			ERSRVR_PLUGIN_URL . 'admin/css/easy-reservations-reviews-admin.css',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'admin/css/easy-reservations-reviews-admin.css' )
		);

		// Enqueue the common style file for the star ratings.
		wp_enqueue_style(
			$this->plugin_name . '-common',
			ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-common.css',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-common.css' )
		);

		// Enqueue jQuery script.
		wp_enqueue_script( 'jquery' );

		// Enqueue the media uploader script.
		if ( $this->comment_post_id_is_reservation ) {
			wp_enqueue_media();
		}

		// Custom admin script.
		wp_enqueue_script(
			$this->plugin_name,
			ERSRVR_PLUGIN_URL . 'admin/js/easy-reservations-reviews-admin.js',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'admin/js/easy-reservations-reviews-admin.js' ),
			true
		);

		// Localized strings.
		$localized_strings = array(
			'ajaxurl'                          => admin_url( 'admin-ajax.php' ),
			'add_criteria_button_text'         => __( 'Add Criteria', 'easy-reservations-reviews' ),
			'add_criterias_promptbox_text'     => __( 'New Criteria', 'easy-reservations-reviews' ),
			'add_same_criteria_error'          => __( 'The criteria already exists. Please add a different criteria.', 'easy-reservations-reviews' ),
			'existing_criteria_result'         => ( $this->comment_post_id_is_reservation ) ? get_comment_meta( $this->comment_id, 'user_criteria_ratings', true ) : array(),
			'is_reservation_comment'           => ( $this->comment_post_id_is_reservation ) ? 1 : -1,
			'reviewer_phone_field_label'       => __( 'Phone', 'easy-reservations-reviews' ),
			'reviewer_phone'                   => get_comment_meta( $this->comment_id, 'reviewer_phone', true ),
			'remove_attachment_confirm_text'   => __( 'Are you sure you want to delete the attachment? Click OK to confirm.', 'easy-reservations-reviews' ),
			'media_uploader_modal_header'      => __( 'Upload Review Attachments', 'easy-reservations-reviews' ),
			'review_attachments_allowed_types' => ersrvr_get_review_file_allowed_file_types(),
		);

		/**
		 * This hook fires in admin side of the site.
		 *
		 * This filter helps in modifying the localized strings used on the admin side.
		 *
		 * @param array $localized_strings List of localized strings.
		 * @return array
		 * @since 1.0.0
		 */
		$localized_strings = apply_filters( 'ersrvr_admin_localized_strings', $localized_strings );

		// Localize variables.
		wp_localize_script( $this->plugin_name, 'ERSRVR_Reviews_Script_Vars', $localized_strings );
	}

	/**
	 * Add review setting section on easy reservations setting page.
	 *
	 * @param string $sections Holds the existing sections of easy reservations.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrvr_reviews_settings_section( $sections ) {
		$sections['reviews'] = __( 'Reviews', 'easy-reservations-reviews' );

		return $sections;
	}

	/**
	 * Add Custom fields to review section.
	 *
	 * @param array  $settings Holds the array of setting fiels.
	 * @param string $current_section Holds the current section screen at easy reservations setting section.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrvr_reviews_settings_fields( $settings, $current_section ) {
		// Return, if the current section is not for reviews.
		if ( 'reviews' !== $current_section ) {
			return $settings;
		}

		// Return the plugin settings.
		return ersrvr_plugin_settings_fields();
	}

	/**
	 * Add metabox to the comment screen.
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_add_meta_boxes_callback() {
		// Return, if the comment post ID is not a reservation item.
		if ( false === $this->comment_post_id_is_reservation ) {
			return;
		}

		// Metabox for reviews basic data.
		add_meta_box(
			'ersrvr-review-basic-data',
			__( 'Review Basic Information', 'easy-reservations-reviews' ),
			array( $this, 'ersrvr_add_reviews_data' ),
			'comment',
			'normal'
		);

		// Metabox for reviews attachments.
		add_meta_box(
			'ersrvr-review-attachments',
			__( 'Review Attachments', 'easy-reservations-reviews' ),
			array( $this, 'ersrvr_review_attachments_callback' ),
			'comment',
			'normal',
			'high'
		);
	}

	/**
	 * Manage the review basic data.
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_add_reviews_data() {
		$user_ratings                   = get_comment_meta( $this->comment_id, 'user_ratings', true );
		$user_ratings_by_criteria_slugs = array_column( $user_ratings, 'criteria_name' );
		$criterias                      = ersrvr_get_plugin_settings( 'ersrvr_submit_review_criterias' );
		?>
		<div class="form-row">
			<div class="col-12">
				<label class="font-Poppins font-weight-semibold text-black font-size-14"><?php esc_html_e( 'Please reate us 1 (bad) to 5 (excellent)', 'easy-reservations-reviews' ); ?></label>
				<div class="rating-group-wrapper border py-2 px-1 rounded-xl">
					<?php
					$k = 1;
					foreach ( $criterias as $criteria ) {
						$criteria_slug       = strtolower( $criteria );
						$criteria_slug       = str_replace( ' ', '-', $criteria_slug );
						$criteria_slug_index = array_search( $criteria_slug, $user_ratings_by_criteria_slugs, true );
						$user_rating         = ( false !== $criteria_slug_index && ! empty( $user_ratings[ $criteria_slug_index ]['rating'] ) ) ? (int) $user_ratings[ $criteria_slug_index ]['rating'] : 0;
						?>
						<div class="rating-item d-flex flex-wrap align-items-center">
							<div class="col-4 col-sm-3"><label class="font-Poppins font-weight-semibold text-black font-size-14"><?php echo esc_html( $criteria ); ?> </label></div>
							<div class="col-8 col-sm-9 rating-group" id="<?php echo esc_attr( $criteria_slug ); ?>" data-criteria="<?php echo esc_attr( $criteria ); ?>">
								<?php for ( $i = 1; $i <= 5; $i++ ) {
									$filled_star_class = ( $i <= $user_rating ) ? 'fill_star_click' : '';
									?>
									<input class="rating__input <?php echo esc_attr( $filled_star_class ); ?>" name="rating3" id="<?php echo esc_attr( $criteria_slug ); ?>-star-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
									<label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label <?php echo esc_attr( $filled_star_class ); ?>" for="<?php echo esc_attr( $criteria_slug ); ?>-star-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
									<?php $k++; ?>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Manage the review attachments.
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_review_attachments_callback() {
		$attached_ids = get_comment_meta( $this->comment_id, 'review_attachments', true );
		?>
		<div class="ersrvr-review-attachments-container">
			<?php if ( ! empty( $attached_ids ) && is_array( $attached_ids ) ) { ?>
				<div class="gallery-images ersrvr_count_images_3">
					<?php
					foreach ( $attached_ids as $attachment_id ) {
						$image_url = ( ! empty( $attachment_id ) ) ? wp_get_attachment_url( $attachment_id ) : '';

						// Skip if the image doesn't exist.
						if ( empty( $image_url ) ) {
							continue;
						}
						?>
						<div class="ersrvr-gallery-image-item" data-imageid="<?php echo esc_attr( $attachment_id ); ?>">
							<img alt="<?php echo esc_attr( basename( $image_url ) ); ?>" src="<?php echo esc_url( $image_url ); ?>" class="ersrvr_attached_files" />
							<a href="javascript:void(0)" class="delete-link">
								<span class="icon"><span class="dashicons dashicons-dismiss"></span></span>
								<span class="text sr-only"><?php esc_html_e( 'Delete', 'easy-reservations-reviews' ); ?></span>
							</a>
						</div>
						<?php
					}
					?>
				</div>
			<?php } ?>
		</div>
		<div class="ersrvr-add-more-attachments-to-review">
			<a href="javascript:void(0);"><?php esc_html_e( 'Add review attachments', 'easy-reservations-reviews' ); ?></a>
		</div>
		<?php
	}

	/**
	 * AJAX to remove the review attachment and update the database.
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_remove_review_attachment_callback() {
		$review_id     = (int) filter_input( INPUT_POST, 'review_id', FILTER_SANITIZE_NUMBER_INT );
		$attachment_id = (int) filter_input( INPUT_POST, 'attachment_id', FILTER_SANITIZE_NUMBER_INT );

		// Get the attachments from the review meta.
		$review_attachments = get_comment_meta( $review_id, 'review_attachments', true );
		$attachment_index   = array_search( $attachment_id, $review_attachments );

		// Remove the index if found.
		if ( false !== $attachment_index ) {
			unset( $review_attachments[ $attachment_index ] );
		}

		// Reset the indexes if the array has elements.
		if ( ! empty( $review_attachments ) ) {
			$review_attachments = array_values( $review_attachments );
			update_comment_meta( $review_id, 'review_attachments', $review_attachments );
		} else {
			delete_comment_meta( $review_id, 'review_attachments' );
		}

		// Send the AJAX response.
		wp_send_json_success(
			array(
				'code' => 'review-attachment-removed',
			)
		);
		wp_die();
	}

	/**
	 * Update the custom fields once comment is updated from admin panel.
	 *
	 * @param 
	 * @since 1.0.0
	 */
	public function ersrvr_edit_comment_callback( $comment_id, $data ) {
		$reviewer_phone = filter_input( INPUT_POST, 'newcomment_author_phone', FILTER_SANITIZE_STRING );

		// If the reviewer phone is available, update it.
		if ( ! empty( $reviewer_phone ) ) {
			update_comment_meta( $comment_id, 'reviewer_phone', $reviewer_phone );
		} else {
			delete_comment_meta( $review_id, 'reviewer_phone' );
		}
	}

	/**
	 * AJAX to add new attachments to the review and update the database.
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_add_review_attachments_callback() {
		$posted_array    = filter_input_array( INPUT_POST );
		$review_id       = (int) filter_input( INPUT_POST, 'review_id', FILTER_SANITIZE_NUMBER_INT );
		$new_attachments = ( ! empty( $posted_array['new_attachments'] ) ) ? $posted_array['new_attachments'] : array();

		// Get the attachments from the review meta.
		$review_attachments = get_comment_meta( $review_id, 'review_attachments', true );

		// Merge the new attachments with the old attachments.
		$review_attachments = array_unique( array_merge( $review_attachments, $new_attachments ) );

		// Update the database with the new set of review attachments.
		update_comment_meta( $review_id, 'review_attachments', $review_attachments );

		// Send the AJAX response.
		wp_send_json_success(
			array(
				'code' => 'review-attachments-added',
			)
		);
		wp_die();
	}
}
