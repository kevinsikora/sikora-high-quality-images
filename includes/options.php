<?php
/**
 * Option names, defaults, and sanitization for Sikora Image Quality.
 *
 * @package SikoraImageQuality
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Option key: JPEG quality (1–100). */
const SIKORA_IQ_OPTION_JPEG_QUALITY = 'sikora_jpeg_quality';

/** Option key: JPEG filter priority. */
const SIKORA_IQ_OPTION_JPEG_PRIORITY = 'sikora_jpeg_filter_priority';

/** Option key: WordPress image editor quality (1–100). */
const SIKORA_IQ_OPTION_EDITOR_QUALITY = 'sikora_editor_quality';

/** Option key: WordPress image editor filter priority. */
const SIKORA_IQ_OPTION_EDITOR_PRIORITY = 'sikora_editor_filter_priority';

/** WordPress core default JPEG/editor quality. */
const SIKORA_IQ_WP_DEFAULT_QUALITY = 82;

/** WordPress default priority when add_filter() is called without a priority. */
const SIKORA_IQ_WP_DEFAULT_FILTER_PRIORITY = 10;

/** Plugin default filter priority for quality callbacks. */
const SIKORA_IQ_DEFAULT_FILTER_PRIORITY = 99;

/** Minimum quality value accepted in the admin UI. */
const SIKORA_IQ_MIN_QUALITY = 1;

/** Maximum quality value accepted in the admin UI. */
const SIKORA_IQ_MAX_QUALITY = 100;

/** Default quality for JPEG and editor controls when no value is saved yet. */
const SIKORA_IQ_DEFAULT_QUALITY = 100;

/** Minimum filter priority accepted in the admin UI. */
const SIKORA_IQ_MIN_PRIORITY = 1;

/** Maximum filter priority accepted in the admin UI. */
const SIKORA_IQ_MAX_PRIORITY = 999;

/**
 * Clamp an integer between minimum and maximum bounds.
 *
 * @param int $value   Raw value.
 * @param int $minimum Lower bound.
 * @param int $maximum Upper bound.
 * @return int
 */
function sikora_iq_clamp_int( $value, $minimum, $maximum ) {
	$value = (int) $value;

	if ( $value < $minimum ) {
		return $minimum;
	}
	if ( $value > $maximum ) {
		return $maximum;
	}

	return $value;
}

/**
 * Sanitize a quality value (1–100).
 *
 * @param mixed $value Submitted value.
 * @return int
 */
function sikora_iq_sanitize_quality( $value ) {
	return sikora_iq_clamp_int( absint( $value ), SIKORA_IQ_MIN_QUALITY, SIKORA_IQ_MAX_QUALITY );
}

/**
 * Sanitize a filter priority value (1–999).
 *
 * @param mixed $value Submitted value.
 * @return int
 */
function sikora_iq_sanitize_filter_priority( $value ) {
	return sikora_iq_clamp_int( absint( $value ), SIKORA_IQ_MIN_PRIORITY, SIKORA_IQ_MAX_PRIORITY );
}

/**
 * JPEG quality stored for the jpeg_quality filter.
 *
 * @return int
 */
function sikora_iq_get_jpeg_quality() {
	return sikora_iq_clamp_int(
		(int) get_option( SIKORA_IQ_OPTION_JPEG_QUALITY, SIKORA_IQ_DEFAULT_QUALITY ),
		SIKORA_IQ_MIN_QUALITY,
		SIKORA_IQ_MAX_QUALITY
	);
}

/**
 * Priority for the jpeg_quality filter callback.
 *
 * @return int
 */
function sikora_iq_get_jpeg_filter_priority() {
	return sikora_iq_clamp_int(
		(int) get_option( SIKORA_IQ_OPTION_JPEG_PRIORITY, SIKORA_IQ_DEFAULT_FILTER_PRIORITY ),
		SIKORA_IQ_MIN_PRIORITY,
		SIKORA_IQ_MAX_PRIORITY
	);
}

/**
 * Quality stored for the wp_editor_set_quality filter.
 *
 * @return int
 */
function sikora_iq_get_editor_quality() {
	return sikora_iq_clamp_int(
		(int) get_option( SIKORA_IQ_OPTION_EDITOR_QUALITY, SIKORA_IQ_DEFAULT_QUALITY ),
		SIKORA_IQ_MIN_QUALITY,
		SIKORA_IQ_MAX_QUALITY
	);
}

/**
 * Priority for the wp_editor_set_quality filter callback.
 *
 * @return int
 */
function sikora_iq_get_editor_filter_priority() {
	return sikora_iq_clamp_int(
		(int) get_option( SIKORA_IQ_OPTION_EDITOR_PRIORITY, SIKORA_IQ_DEFAULT_FILTER_PRIORITY ),
		SIKORA_IQ_MIN_PRIORITY,
		SIKORA_IQ_MAX_PRIORITY
	);
}
