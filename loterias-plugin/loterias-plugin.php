<?php
/**
 * Plugin Name: Loterias Plugin
 * Description: Exibe os resultados das Loterias Caixa usando um shortcode.
 * Version: 1.0
 * Author: Daniel Pellicano
 *
 * @package LoteriasPlugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/** Load style. */
function loterias_plugin_enqueue_scripts() {
	wp_enqueue_style( 'loterias-plugin-style', LOTERIAS_PLUGIN_URL . 'assets/css/loterias-plugin.css', array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'loterias_plugin_enqueue_scripts' );

// Define Constants.
define( 'LOTERIAS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LOTERIAS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files.
require_once LOTERIAS_PLUGIN_PATH . 'includes/class-loterias-post-type.php';
require_once LOTERIAS_PLUGIN_PATH . 'includes/class-loterias-api.php';
require_once LOTERIAS_PLUGIN_PATH . 'includes/class-loterias-shortcode.php';
require_once LOTERIAS_PLUGIN_PATH . 'includes/class-loterias-plugin.php';

/** Initialize the plugin. */
function loterias_plugin_init() {
	Loterias_Plugin::init();
}
add_action( 'plugins_loaded', 'loterias_plugin_init' );
