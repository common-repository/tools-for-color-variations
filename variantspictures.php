<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://decli.fr
 * @since             1.0.0
 * @package           Variantspictures
 *
 * @wordpress-plugin
 * Plugin Name:       Tools for color variations.
 * Plugin URI:        https://decli.fr/woocommerce
 * Description:       Administrative tools for color variations of WooCommerce products.
 * Version:           1.0.0
 * Author:            benoit fremont
 * Author URI:        https://decli.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       variantspictures
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
define( 'VARIANTSPICTURES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-variantspictures-activator.php
 */
function activate_variantspictures() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-variantspictures-activator.php';
	Variantspictures_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-variantspictures-deactivator.php
 */
function deactivate_variantspictures() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-variantspictures-deactivator.php';
	Variantspictures_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_variantspictures' );
register_deactivation_hook( __FILE__, 'deactivate_variantspictures' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-variantspictures.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_variantspictures() {

	$plugin = new Variantspictures();
	$plugin->run();

}
run_variantspictures();
