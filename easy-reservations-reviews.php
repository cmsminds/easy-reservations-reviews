<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cmsminds.com
 * @since             1.0.0
 * @package           Easy_Reservations_Reviews
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Reservations Reviews
 * Plugin URI:        https://github.com/cmsminds/easy-reservations-reviews
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            cmsMinds
 * Author URI:        https://cmsminds.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-reservations-reviews
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EASY_RESERVATIONS_REVIEWS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-easy-reservations-reviews-activator.php
 */
function activate_easy_reservations_reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-reservations-reviews-activator.php';
	Easy_Reservations_Reviews_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-easy-reservations-reviews-deactivator.php
 */
function deactivate_easy_reservations_reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-reservations-reviews-deactivator.php';
	Easy_Reservations_Reviews_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_easy_reservations_reviews' );
register_deactivation_hook( __FILE__, 'deactivate_easy_reservations_reviews' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-reservations-reviews.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_easy_reservations_reviews() {

	$plugin = new Easy_Reservations_Reviews();
	$plugin->run();

}
run_easy_reservations_reviews();
