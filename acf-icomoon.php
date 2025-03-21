<?php
/**
 * Plugin Name:       Support For Icomoon with Advanced Custom Fields
 * Plugin URI:        https://wordpress.org/plugins/acf-icomoon/
 * Description:       🔩 Add a field to select icons from a selection.json file generated by IcoMoon
 * Version:           4.0.13
 * Requires at least: 6.6.2
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

define("ACFICOMOON_VERSION", '4.0.13');
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
 * WPBakery Page Builder
 */

add_action('vc_before_init', 'viivue_icomoon_vc_param');
function viivue_icomoon_vc_param(){
	include_once(ACFICOMOON_DIR . 'compatibility/js_composer.php');
}

/**
 * Admin notices
 */

add_action('admin_notices', 'viivue_icomoon_admin_notices');
function viivue_icomoon_admin_notices(){
	if(!function_exists('get_field')){
		$notice = sprintf(__(
			'Warning: Please activate plugin <a href="%s">Advanced Custom Fields</a> to use plugin Support For Icomoon with Advanced Custom Fields.',
			'acf-icomoon'
		), admin_url('plugins.php'));
		
		echo '<div class="notice notice-warning"><p>' . $notice . '</p></div>';
	}
}

/**
 *  Enqueue assets FE
 */

add_action('wp_footer', 'viivue_acf_icomoon_enqueue_assets');
function viivue_acf_icomoon_enqueue_assets(){
	global $vii_acf_icomoon_empty_json;
	if($vii_acf_icomoon_empty_json){
		wp_enqueue_style('viivue-acf-icomoon-fonts', ACFICOMOON_ASSETS_URL . "css/fonts.css", false, ACFICOMOON_VERSION);
	}
}