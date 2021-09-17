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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-reservations-reviews-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Custom admin script.
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/easy-reservations-reviews-admin.js',
			array( 'jquery' ),
			$this->version,
			true
		);

		// Localize variables.
		wp_localize_script(
			$this->plugin_name,
			'ERSRVR_Reviews_Script_Vars',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);

	}
	/**
	 * Add review setting section on easy reservations setting page.
	 *
	 * @param string $sections Holds the existing sections of easy reservations
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrvr_reviews_settings_section( $sections ){
		$sections['reviews_settings'] = esc_html__( 'Reviews Settings', 'easy-reservations-reviews' );
		return $sections;
	}
	/**
	 * Hook the receipt option in order listing page on customer's my account.
	 *
	 * @param array    $settings Holds the array of setting fiels.
	 * @param string $current_section Holds the current section screen at easy reservations setting section.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrvr_reviews_settings_fields( $settings, $current_section ){
		if( 'reviews_settings' !== $current_section ) {
			return	$settings;
		}
		$settings = ersrvr_setting_fields();
		return $settings;
	}
	
}
