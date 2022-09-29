<?php
/*
  Plugin Name:      Buy X Get X Offer
  Plugin URI:       http://wordpress.org
  Description:      Buy X Get X Offer for Woocommerce Plugin
  Author:           Yegappan
  Author URI:       http://cartrabbit.io/
  Text Domains:     buy-get
  Version:          1.0
  Requires at least:5.2
  Requires PHP:     7.2
  License:          GPL v2 or later
  License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
*/

defined( 'ABSPATH' ) || exit;

defined('BXGX_PLUGIN_FILE') or define('BXGX_PLUGIN_FILE', __FILE__);
defined('BXGX_PLUGIN_PATH') or define('BXGX_PLUGIN_PATH', plugin_dir_path(__FILE__));

// To load PSR4 autoloader
if (file_exists(BXGX_PLUGIN_PATH . '/vendor/autoload.php')) {
    require BXGX_PLUGIN_PATH . '/vendor/autoload.php';
} else {
    wp_die('Buy X Get X Plugin is unable to find the autoload file.');
}

// To check class exists
if (class_exists('BXGX\App\Route')) {
  BXGX\App\Route::hooks();
} else {
  wp_die(__('Buy X Get X Plugin is unable to find the Route class.'));
}
