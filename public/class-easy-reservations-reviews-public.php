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
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
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
	public function enqueue_scripts() {
		global $wp_registered_widgets, $post, $wp_query;
		// Active style file based on the active theme.
		$current_theme            = get_option( 'stylesheet' );
		$active_style             = ersrvr_get_active_stylesheet( $current_theme );
		$active_style_url         = ( ! empty( $active_style['url'] ) ) ? $active_style['url'] : '';
		$active_style_path        = ( ! empty( $active_style['path'] ) ) ? $active_style['path'] : '';
		
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
			plugin_dir_url( __FILE__ ) . 'js/easy-reservations-reviews-public.js', 
			array( 'jquery' ), 
			$this->version, 
			false 
		);

		// Enqueue the common public style.
		wp_enqueue_style(
			$this->plugin_name . '-common',
			ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-common.css',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-common.css' )
		);

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
		echo ersrvr_prepare_reviews_html();
		return ob_get_clean();
	}
	/**
	 * Add review setting section on easy reservations setting page.
	 *
	 * @param string $reservation_id Holds the reservation product id.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrvr_after_item_details_callback( $reservation_id ){
		$product = get_product( $reservation_id );
		// check product is reservation type or not
    	if( $product->is_type( 'reservation' ) ) { 
			$ersrvr_reservation_button_text          = ersrvr_submit_review_button_text();
			$ersrvr_reservation_review_criteria      = ersrvr_submit_review_criterias();
			$ersrvr_reservation_review_guest_setting = ersrvr_enable_reservation_reviews_guest_users();
			echo ersrvr_prepare_reviews_html();
		}
	}

}
