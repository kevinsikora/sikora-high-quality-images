<?php
/**
 * WordPress image quality filters.
 *
 * @package SikoraImageQuality
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register quality filters using saved priority values.
 */
function sikora_iq_register_quality_filters() {
	add_filter(
		'jpeg_quality',
		'sikora_iq_filter_jpeg_quality',
		sikora_iq_get_jpeg_filter_priority(),
		2
	);

	add_filter(
		'wp_editor_set_quality',
		'sikora_iq_filter_editor_quality',
		sikora_iq_get_editor_filter_priority(),
		2
	);
}
add_action( 'plugins_loaded', 'sikora_iq_register_quality_filters' );

/**
 * Apply configured JPEG quality.
 *
 * @param int    $quality Current quality from WordPress or other filters.
 * @param string $context Context such as image_resize or edit_image.
 * @return int
 */
function sikora_iq_filter_jpeg_quality( $quality, $context ) {
	unset( $context );

	return sikora_iq_get_jpeg_quality();
}

/**
 * Apply configured image editor quality.
 *
 * @param int         $quality   Current quality from WordPress or other filters.
 * @param string|null $mime_type Image MIME type.
 * @return int
 */
function sikora_iq_filter_editor_quality( $quality, $mime_type = null ) {
	unset( $mime_type );

	return sikora_iq_get_editor_quality();
}
