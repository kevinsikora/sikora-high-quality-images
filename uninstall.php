<?php
/**
 * Remove plugin options on uninstall.
 *
 * @package SikoraImageQuality
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$sikora_iq_option_keys = array(
	'sikora_jpeg_quality',
	'sikora_jpeg_filter_priority',
	'sikora_editor_quality',
	'sikora_editor_filter_priority',
);

foreach ( $sikora_iq_option_keys as $sikora_iq_option_key ) {
	delete_option( $sikora_iq_option_key );
}
