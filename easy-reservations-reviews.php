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
 * Description:       This is Add-ons for Easy Reservation Plugin. This plugin holds add reservation reviews on reservation products.
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
define( 'ERSRVR_PLUGIN_VERSION', '1.0.0' );

// Plugin path.
if ( ! defined( 'ERSRVR_PLUGIN_PATH' ) ) {
	define( 'ERSRVR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

// Plugin URL.
if ( ! defined( 'ERSRVR_PLUGIN_URL' ) ) {
	define( 'ERSRVR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

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
/**
 * This initiates the plugin.
 * Checks for the required plugins to be installed and active.
 *
 * @since 1.0.0
 */
function ersrvr_plugins_loaded_callback() {
	$active_plugins   = get_option( 'active_plugins' );
	$is_ersrvr_active = in_array( 'easy-reservations/easy-reservations.php', $active_plugins, true );

	if ( current_user_can( 'activate_plugins' ) && false === $is_ersrvr_active ) {
		add_action( 'admin_notices', 'ersrvr_admin_notices_callback' );
	} else {
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ersrvr_plugin_actions_callback' );
		run_easy_reservations_reviews();
	}
}
add_action( 'plugins_loaded', 'ersrvr_plugins_loaded_callback' );
/**
 * Show admin notice for the required plugins not active or installed.
 *
 * @since 1.0.0
 */
function ersrvr_admin_notices_callback() {
	$this_plugin_data = get_plugin_data( __FILE__ );
	$this_plugin      = $this_plugin_data['Name'];
	$ersrv_plugin     = 'Easy Reservations';
	?>
	<div class="error">
		<p>
			<?php
			/* translators: 1: %s: strong tag open, 2: %s: strong tag close, 3: %s: this plugin, 4: %s: woocommerce plugin, 5: anchor tag for woocommerce plugin, 6: anchor tag close */
			echo wp_kses_post( sprintf( __( '%1$s%3$s%2$s is ineffective as it requires %1$s%4$s%2$s to be installed and active. Click %5$shere%6$s to install or activate it.', 'wc-quick-buy' ), '<strong>', '</strong>', esc_html( $this_plugin ), esc_html( $ersrv_plugin ), '<a target="_blank" href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '">', '</a>' ) );
			?>
		</p>
	</div>
	<?php
}
/**
 * This function adds custom plugin actions.
 *
 * @param array $links Links array.
 * @return array
 * @since 1.0.0
 */
function ersrvr_plugin_actions_callback( $links ) {
	$this_plugin_links = array(
		'<a title="' . __( 'Settings', 'easy-reservations-reviews' ) . '" href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=easy-reservations&section=reviews' ) ) . '">' . __( 'Settings', 'easy-reservations-reviews' ) . '</a>',
		'<a title="' . __( 'Docs', 'easy-reservations-reviews' ) . '" href="javascript:void(0);">' . __( 'Docs', 'easy-reservations-reviews' ) . '</a>',
		'<a title="' . __( 'Support', 'easy-reservations-reviews' ) . '" href="javascript:void(0);">' . __( 'Support', 'easy-reservations-reviews' ) . '</a>',
		'<a title="' . __( 'Changelog', 'easy-reservations-reviews' ) . '" href="javascript:void(0);">' . __( 'Changelog', 'easy-reservations-reviews' ) . '</a>',
	);

	return array_merge( $this_plugin_links, $links );
}
