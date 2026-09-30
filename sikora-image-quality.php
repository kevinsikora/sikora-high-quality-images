<?php
/**
 * Plugin Name: Sikora Image Quality
 * Description: Set JPEG and WordPress image editor quality values and filter priorities when WordPress generates images.
 * Version: 3.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Author: Sikora Collective
 * Author URI: https://sikoracollective.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sikora-image-quality
 *
 * @package SikoraImageQuality
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Plugin version. */
define( 'SIKORA_IQ_PLUGIN_VERSION', '3.0.0' );

/** Absolute path to the main plugin file. */
define( 'SIKORA_IQ_PLUGIN_FILE', __FILE__ );

require_once __DIR__ . '/includes/options.php';
require_once __DIR__ . '/includes/filters.php';
require_once __DIR__ . '/includes/admin-page.php';

/**
 * Set default quality and priority options on first activation.
 */
function sikora_iq_activate_plugin() {
	if ( false === get_option( SIKORA_IQ_OPTION_JPEG_QUALITY, false ) ) {
		add_option( SIKORA_IQ_OPTION_JPEG_QUALITY, SIKORA_IQ_DEFAULT_QUALITY );
	}

	if ( false === get_option( SIKORA_IQ_OPTION_EDITOR_QUALITY, false ) ) {
		add_option( SIKORA_IQ_OPTION_EDITOR_QUALITY, SIKORA_IQ_DEFAULT_QUALITY );
	}

	if ( false === get_option( SIKORA_IQ_OPTION_JPEG_PRIORITY, false ) ) {
		add_option( SIKORA_IQ_OPTION_JPEG_PRIORITY, SIKORA_IQ_DEFAULT_FILTER_PRIORITY );
	}

	if ( false === get_option( SIKORA_IQ_OPTION_EDITOR_PRIORITY, false ) ) {
		add_option( SIKORA_IQ_OPTION_EDITOR_PRIORITY, SIKORA_IQ_DEFAULT_FILTER_PRIORITY );
	}
}
register_activation_hook( SIKORA_IQ_PLUGIN_FILE, 'sikora_iq_activate_plugin' );
