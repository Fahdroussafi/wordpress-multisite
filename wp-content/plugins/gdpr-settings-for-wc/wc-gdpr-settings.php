<?php
/**
 * @wordpress-plugin
 * Plugin Name:       GDPR Settings for WooCommerce
 * Plugin URI:        https://salonsoweb.es
 * Description:       Adapt your WooCommerce Shop to the GDPR and RGPD Regulation. Add promotion consent checkbox and first privacy layer in WooCommerce checkout easily
 * Version:           1.2.1
 * Author:            Técnico RGPD
 * Author URI:        https://tecnicorgpd.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc_gdpr_settings
 */

// Constants
if (!defined('ABSPATH')) exit;
if (!defined('wcgdprsettings_VERSION')) define('wcgdprsettings_VERSION', '1.2.1' );
if (!defined('wcgdprsettings_CSS')) define('wcgdprsettings_CSS', plugins_url( 'css/', __FILE__ ) );
if (!defined('wcgdprsettings_INC')) define('wcgdprsettings_INC', plugin_dir_path( __FILE__ ) . 'includes/' );
if (!defined('wcgdprsettings_IMG')) define('wcgdprsettings_IMG', plugins_url( 'img/', __FILE__ ) );

// Included files
include_once wcgdprsettings_INC.'functions.php';
include_once wcgdprsettings_INC.'promo_checkbox.php';
include_once wcgdprsettings_INC.'top_layer_privacy.php';
include_once wcgdprsettings_INC.'wc_gdpr_options_tab.php';

// enqueue public CSS file for WooCommerce checkout
if (!function_exists('wcgdprsettings_enqueue_scripts')) {
    function wcgdprsettings_enqueue_scripts() {
        wp_register_style('wc_gdpr_settings_styles', wcgdprsettings_CSS.'styles.css', array(), wcgdprsettings_VERSION );
        wp_enqueue_style('wc_gdpr_settings_styles');
    }
    add_action('wp_enqueue_scripts', 'wcgdprsettings_enqueue_scripts', 999);
}

// Adding textdomain
if (!function_exists('wcgdprsettings_load_textdomain')) {
    function wcgdprsettings_load_textdomain() {
        load_plugin_textdomain( 'wc_gdpr_settings', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
    add_action( 'plugins_loaded', 'wcgdprsettings_load_textdomain' );
}