<?php
/**
 * Plugin Name:     Leaky Paywall - Bulk Generate Coupons
 * Plugin URI:      http://zeen101.com
 * Description:     Bulk generate Leaky Paywall Coupon Codes
 * Author:          Zeen101 Development Team
 * Author URI:      http://zeen101.com
 * Text Domain:     bulk-generate-coupons
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Bulk_Generate_Coupons
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bulk-generate-coupons.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bulk_generate_coupons() {
	$plugin = new Bulk_Generate_Coupons();
	$plugin->run();
}
run_bulk_generate_coupons();