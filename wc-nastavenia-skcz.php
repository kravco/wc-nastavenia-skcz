<?php

/*
Plugin Name: Nastavenia SK pre WooCommerce
Plugin URI: https://wordpress.org/plugins/wc-nastavenia-skcz
Description: Nastavenia WooCommerce pre Slovensko
Version: 1.0.1
Author: Webikon (Matej Kravjar)
Author URI: https://webikon.sk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: wc-nastavenia-skcz
Domain Path: /languages
*/

namespace Webikon\Woocommerce_Plugin\WC_Nastavenia_SKCZ;

// protect against direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/class-wc-nastavenia-skcz.php';
Plugin::get_instance();
