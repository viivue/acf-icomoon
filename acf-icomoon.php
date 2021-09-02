<?php
/**
 * Plugin Name:       Support For Icomoon with Advanced Custom Fields
 * Plugin URI:        https://wordpress.org/plugins/acf-icomoon/
 * Description:       🔩 Add a field to select icons from a selection.json file generated by IcoMoon
 * Version:           3.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            ViiVue
 * Author URI:        https://www.viivue.com/
 * Text Domain:       acf-icomoon
 */

// If this file is called directly, abort.
if(!defined('WPINC')){
	die;
}

// Exit if accessed directly
if(!defined('ABSPATH')){
	exit;
}

/**
 * Definitions
 */

define('ACFICOMOON_VERSION', '3.2');
define("ACFICOMOON_DIR", plugin_dir_path(__FILE__));
define("ACFICOMOON_ASSETS_URL", plugin_dir_url(__FILE__) . 'assets/');
define("ACFICOMOON_STYLESHEET_DIR", get_stylesheet_directory());

/**
 * Include functions
 */

// helper
include_once(ACFICOMOON_DIR . 'includes/helper.php');

// main functions
include_once(ACFICOMOON_DIR . 'includes/acf-icomoon.php');

/**
 * Init Functions
 */

add_action('init', 'viivue_icomoon_init');
function viivue_icomoon_init(){
	// WPBakery Page Builder Param
	if(is_plugin_active('js_composer/js_composer.php')){
		include_once(ACFICOMOON_DIR . 'includes/js_composer.php');
	}
}