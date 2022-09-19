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

include 'vendor/autoload.php';

BXGX\App\Route::BXGX_hooks();

?>