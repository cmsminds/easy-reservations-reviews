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
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the JavaScriptn & stylesheet for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-reservations-reviews-admin.css', array(), $this->version, 'all' );
		// Custom admin script.
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/easy-reservations-reviews-admin.js',
			array( 'jquery' ),
			$this->version,
			true
		);
		wp_enqueue_style(
			$this->plugin_name . '-common',
			ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-common.css',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-common.css' )
		);

		// Localize variables.
		wp_localize_script(
			$this->plugin_name,
			'ERSRVR_Reviews_Script_Vars',
			array(
				'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
				'add_criteria_button_text'     => __( 'Add Criteria', 'easy-reservations-reviews' ),
				'add_criterias_promptbox_text' => __( 'New Criteria', 'easy-reservations-reviews' ),
				'add_same_criteria_error'      => __( 'The criteria already exists. Please add a different criteria.', 'easy-reservations-reviews' ),
			)
		);

	}
	/**
	 * Add review setting section on easy reservations setting page.
	 *
	 * @param string $sections Holds the existing sections of easy reservations.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrvr_reviews_settings_section( $sections ) {
		$sections['reviews'] = esc_html__( 'Reviews', 'easy-reservations-reviews' );
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
		if ( 'reviews' !== $current_section ) {
			return $settings;
		}
		$settings = ersrvr_setting_fields();
		return $settings;
	}
	/**
	 * Add Custom meta in comment for reviews.
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_add_metabox_in_comment_screen() {
		$get_comment_id = filter_input( INPUT_GET, 'c', FILTER_SANITIZE_NUMBER_INT );
		if ( ! empty( $get_comment_id ) && isset( $get_comment_id ) ) {
			$get_average_ratings       = get_comment_meta( $get_comment_id, 'average_ratings', true );
			$get_user_criteria_ratings = get_comment_meta( $get_comment_id, 'user_criteria_ratings', true );
			if ( ! empty( $get_user_criteria_ratings ) && is_array( $get_user_criteria_ratings ) ) {
				add_meta_box( 'ersrvr_add_reviews_data', __( 'Reviews' ), 'ersrvr_add_reviews_data', 'comment', 'normal' );
			}
		}
		/**
		 * Function to add output data.
		 */
		function ersrvr_add_reviews_data() {
			$get_comment_id            = filter_input( INPUT_GET, 'c', FILTER_SANITIZE_NUMBER_INT );
			$get_average_ratings       = get_comment_meta( $get_comment_id, 'average_ratings', true );
			$get_user_criteria_ratings = get_comment_meta( $get_comment_id, 'user_criteria_ratings', true ); ?>
			<?php if ( ! empty( $get_user_criteria_ratings ) && is_array( $get_user_criteria_ratings ) ) { ?>
				<div class="form-row">
					<div class="col-12">
						<div id="full-stars-example-two" class="rating-group-wrapper border py-2 px-1 rounded-xl">
							<?php $k = 1; ?>
							<?php foreach ( $get_user_criteria_ratings as $get_user_criteria_rating ) { ?>
								<?php
								$criteria_name = ucfirst( $get_user_criteria_rating['closest_criteria'] );
								$criteria_name = str_replace( '-', ' ', $criteria_name );
								$criteria_slug = $get_user_criteria_rating['closest_criteria'];
								?>
								<div class="rating-item d-flex flex-wrap align-items-center">
									<div class="col-4 col-sm-3"><label class="font-Poppins font-weight-semibold text-black font-size-14"><?php echo esc_html( $criteria_name ); ?> </label></div>
									<div class="col-8 col-sm-9 rating-group" id="<?php echo esc_attr( $criteria_slug ); ?>" data-criteria="<?php echo esc_attr( $criteria_name ); ?>">
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
			<div class="col-4 col-sm-3">
				<label class="font-Poppins font-weight-semibold text-black font-size-14"><?php esc_html_e( 'Avrage Ratings', 'easy-reservations-reviews' ); ?> </label>
			</div>
			<div class="col-8 col-sm-9 rating-group">
				<h2><?php echo esc_html( $get_average_ratings ); ?></h2>
				<!-- <input class="rating__input" name="rating3" id="rating3-1" value="1" type="radio">
				<label aria-label="1 star" class="rating__label" for="rating3-1"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
				<input class="rating__input" name="rating3" id="rating3-2" value="2" type="radio">
				<label aria-label="2 stars" class="rating__label" for="rating3-2"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
				<input class="rating__input" name="rating3" id="rating3-3" value="3" type="radio">
				<label aria-label="3 stars" class="rating__label" for="rating3-3"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
				<input class="rating__input" name="rating3" id="rating3-4" value="4" type="radio">
				<label aria-label="4 stars" class="rating__label" for="rating3-4"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
				<input class="rating__input" name="rating3" id="rating3-5" value="5" type="radio">
				<label aria-label="5 stars" class="rating__label" for="rating3-5"><span class="rating__icon rating__icon--star fa fa-star"></span></label> -->
			</div>
		<?php }
	}
}
