<?php

/**
 * Plugin Name: Midnight Deals for WooCommerce🌙
 * Plugin URI: https://coderpress.co/products/midnight-deals-for-woocommerce/
 * Author: CoderPress
 * Description: Offer crazy deals to the visitors they can’t say no.
 * Version: 1.0.1
 * Author: Syed Muhammad Usman
 * Author URI: https://coderpress.co/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins: woocommerce
 */
defined( 'ABSPATH' ) || exit;
if ( !function_exists( 'mdfw_fs' ) ) {
    // Create a helper function for easy SDK access.
    function mdfw_fs() {
        global $mdfw_fs;
        if ( !isset( $mdfw_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $mdfw_fs = fs_dynamic_init( array(
                'id'             => '16359',
                'slug'           => 'midnight-deals-for-woocommerce',
                'type'           => 'plugin',
                'public_key'     => 'pk_b5650379bb5c7610ab9252dc1d34f',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                    'first-path' => 'plugins.php',
                    'contact'    => false,
                    'support'    => false,
                ),
                'is_live'        => true,
            ) );
        }
        return $mdfw_fs;
    }

    // Init Freemius.
    mdfw_fs();
    // Signal that SDK was initiated.
    do_action( 'mdfw_fs_loaded' );
}
if ( !defined( 'MDWC_PLUGIN_FILE' ) ) {
    define( 'MDWC_PLUGIN_FILE', __FILE__ );
}
if ( !defined( 'MDWC_VERSION' ) ) {
    define( 'MDWC_VERSION', '1.0.1' );
}
if ( !defined( 'MDWC_PLUGIN_URL' ) ) {
    define( 'MDWC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'MDWC_PLUGIN_DIR_PATH' ) ) {
    define( 'MDWC_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
require dirname( MDWC_PLUGIN_FILE ) . '/includes/class-mdwc-init.php';
add_action( 'plugins_loaded', 'mdwc_load' );
/**
 * Loads Plugin
 *
 * @since 1.0
 * @version 1.0
 */
function mdwc_load() {
    MDWC_Init::get_instance();
}
