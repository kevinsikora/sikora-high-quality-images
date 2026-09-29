<?php
/**
 * Plugin Name: Sikora High Quality Images (Optimization)
 * Description: Sets WordPress generated image sizes to use maximum image quality.
 * Version: 2.0.0
 * Author: <a href="https://sikoracollective.com/">Sikora Collective</a>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Set JPEG image quality to 100.
 *
 * Runs when WordPress creates resized JPEG images during upload
 * and when JPEG images are edited/saved.
 * 
 * This is needed to handle cases that could be missed by
 * the wp_editor_set_quality filter. 
 *
 * @param int    $quality Current JPEG quality.
 * @param string $context Context, such as image_resize or edit_image.
 * @return int
 */
function sikora_set_jpeg_quality( $quality, $context ) {
    return 100;     // 100 is the highest value
}
add_filter( 'jpeg_quality', 'sikora_set_jpeg_quality', 99, 2 );

/**
 * Set image editor quality to 100 for all formats handled by WP_Image_Editor.
 *
 * Applies to JPEG, WebP, AVIF, and PNG images processed through
 * the WordPress image editor during upload, resizing, and editing.
 *
 * @param int         $quality   Current image quality.
 * @param string|null $mime_type Image mime type (e.g. image/jpeg, image/webp).
 * @return int
 */
 function sikora_set_wp_editor_quality( $quality, $mime_type = null ) {
    return 100;     // 100 is the highest value
}
add_filter( 'wp_editor_set_quality', 'sikora_set_wp_editor_quality', 99, 2 );
