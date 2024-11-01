<?php
/**
 * Plugin Name: Pricing Table by RadiusTheme
 * Plugin URI:
 * Description: Create Pricing Table in few min without any coding knowledge. Create Unlimited Price Table and Control the table row and arrange by drag & drop.

 * Author: RadiusTheme
 * Version: 1.0
 * Text Domain: the-pricing-table
 * Domain Path: /languages
 * Author URI: https://radiustheme.com/
 */

if ( ! defined( 'ABSPATH' ) )  exit;

define('RT_TPT_PLUGIN_PATH', dirname(__FILE__));
define('RT_TPT_PLUGIN_ACTIVE_FILE_NAME', plugin_basename(__FILE__));
define('RT_TPT_PLUGIN_URL', plugins_url('', __FILE__));
define('RT_TPT_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages');

require ('lib/init.php');