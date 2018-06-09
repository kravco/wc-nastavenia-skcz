<?php

/*
Plugin Name: Nastavenia SK/CZ pre WooCommerce
Plugin URI: https://wordpress.org/plugins/wc-nastavenia-skcz
Description: Nastavenia WooCommerce pre Slovensko a Česko
Version: 2.0.2
Author: Webikon (Matej Kravjar)
Author URI: https://webikon.sk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: wc-nastavenia-skcz
Domain Path: /languages
WC requires at least: 3.0
WC Tested up to: 3.3.5
*/

namespace Webikon\Woocommerce_Plugin\WC_Nastavenia_SKCZ;

// protect against direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/class-wc-nastavenia-skcz.php';
Plugin::get_instance();
