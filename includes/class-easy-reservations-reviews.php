<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cmsminds.com
 * @since      1.0.0
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/includes
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Reviews {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Easy_Reservations_Reviews_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version     = ( defined( 'ERSRVR_PLUGIN_VERSION' ) ) ? ERSRVR_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'easy-reservations-reviews';

		// Initiate all the hooks and callbacks.
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Easy_Reservations_Reviews_Loader. Orchestrates the hooks of the plugin.
	 * - Easy_Reservations_Reviews_i18n. Defines internationalization functionality.
	 * - Easy_Reservations_Reviews_Admin. Defines all hooks for the admin area.
	 * - Easy_Reservations_Reviews_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once ERSRVR_PLUGIN_PATH . 'includes/class-easy-reservations-reviews-loader.php'; // The class responsible for orchestrating the actions and filters of the core plugin.
		require_once ERSRVR_PLUGIN_PATH . 'includes/class-easy-reservations-reviews-i18n.php'; // The class responsible for defining internationalization functionality of the plugin.
		require_once ERSRVR_PLUGIN_PATH . 'includes/easy-reservations-reviews-functions.php'; // The files responsible for common static functions side of the site.
		require_once ERSRVR_PLUGIN_PATH . 'admin/class-easy-reservations-reviews-admin.php'; // The class responsible for defining all actions that occur in the admin area.
		require_once ERSRVR_PLUGIN_PATH . 'public/class-easy-reservations-reviews-public.php'; // The class responsible for defining all actions that occur in the public-facing side of the site.

		$this->loader = new Easy_Reservations_Reviews_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Easy_Reservations_Reviews_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Easy_Reservations_Reviews_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Easy_Reservations_Reviews_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'ersrvr_admin_enqueue_scripts_callback' );
		$this->loader->add_filter( 'woocommerce_get_sections_easy-reservations', $plugin_admin, 'ersrvr_reviews_settings_section' );
		$this->loader->add_filter( 'woocommerce_get_settings_easy-reservations', $plugin_admin, 'ersrvr_reviews_settings_fields', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'ersrvr_add_meta_boxes_callback' );
		$this->loader->add_action( 'wp_ajax_ersrvr_submit_reviews_current_comment', $plugin_admin, 'ersrvr_submit_reviews_current_comment' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Easy_Reservations_Reviews_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'ersrvr_wp_enqueue_scripts_callback' );
		$this->loader->add_shortcode( 'ersrvr_review_form_shortcode', $plugin_public, 'ersrvr_review_form_shortcode_callback' );
		$this->loader->add_action( 'ersrv_after_item_details', $plugin_public, 'ersrvr_after_item_details_callback' );
		$this->loader->add_action( 'ersrv_after_reservation_item_title', $plugin_public, 'ersrvr_ersrv_after_reservation_item_title_callback' );
		$this->loader->add_action( 'ersrv_single_reservation_block_after_title', $plugin_public, 'ersrvr_ersrv_single_reservation_block_after_title_callback', 10, 2 );
		$this->loader->add_action( 'wp_ajax_submit_review', $plugin_public, 'ersrvr_submit_review_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_submit_review', $plugin_public, 'ersrvr_submit_review_callback' );
		

		$this->loader->add_action( 'wp_ajax_ersrvr_delete_review_comment', $plugin_public, 'ersrvr_delete_review_comment' );
		$this->loader->add_action( 'wp_ajax_nopriv_ersrvr_delete_review_comment', $plugin_public, 'ersrvr_delete_review_comment' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'ersrvr_wp_footer_callback' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Easy_Reservations_Reviews_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
