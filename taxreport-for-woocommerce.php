<?php

/**
 *
 * @link              http://www.adils.me
 * @since             1.0.0
 * @package           taxreport_for_woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       TaxReport for WooCommerce
 * Plugin URI:        http://www.adils.me
 * Description:       Let your customers download tax report on orders
 * Version:           1.0.0
 * Author:            Adil
 * Author URI:        http://www.adils.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       taxreport_for_woocommerce
 * Domain Path:       /languages
 * Requires at least: 4.9.0
 * Tested up to:      4.9.6
 * WC requires at least:  3.4.0
 * WC tested up to:  3.4.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

require_once plugin_dir_path( __FILE__ ) . 'class-loader.php';
require_once plugin_dir_path( __FILE__ ) . 'class-taxreport.php';

define( 'TAXREPORT_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

$version = TAXREPORT_FOR_WOOCOMMERCE_VERSION;
$plugin_name = 'taxreport_for_woocommerce';
$context = new TaxReport($plugin_name, $version);

function activate() {
}

function deactivate() {
}

register_activation_hook( __FILE__, 'activate' );
register_deactivation_hook( __FILE__, 'deactivate' );

function load_hooks($context) {
  $loader = new Loader();

  $loader->add_action( 'plugins_loaded', $context, 'taxreport_load_textdomain' );
  // $loader->add_action( 'admin_enqueue_scripts', $context, 'enqueue_assets_admin' );
  $loader->add_action( 'wp_enqueue_scripts', $context, 'enqueue_assets_public' );

  $loader->add_filter( 'woocommerce_account_menu_items', $context, 'customer_menu_item' );
  $loader->add_filter( 'woocommerce_get_query_vars', $context, 'customer_menu_query_vars', 0 );
  $loader->add_action( 'woocommerce_account_taxreport_endpoint', $context, 'customer_menu_content', 1 );

  $loader->add_action('wp_loaded', $context, 'csv_download', 0);

  $loader->run();
}

function initialize() {
  $context = new TaxReport('taxreport_for_woocommerce', TAXREPORT_FOR_WOOCOMMERCE_VERSION);
  load_hooks($context);
}

//// Initiate if woocommerce is active and ready

if(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
  add_action('woocommerce_init', 'initialize');
}