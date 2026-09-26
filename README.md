# Sikora High Quality Images (Optimization)

A lightweight WordPress plugin that makes WordPress save the image files it generates at maximum quality (100).

By default, WordPress re-compresses every resized image it creates (thumbnails, medium, large, and so on) at a quality of **82**. That default favours smaller files, but it can leave visible compression artifacts, especially on photography, gradients, and detailed artwork. This plugin raises the quality to **100** for all generated images.

## Features

- Sets the quality of generated JPEG images to 100.
- Sets the quality of every format handled by `WP_Image_Editor` (JPEG, WebP, AVIF, and PNG) to 100.
- Applies during upload, when intermediate sizes are created, and when images are edited in the Media Library.
- Has no settings, no database writes, and no front-end output.

## How It Works

The plugin hooks into two WordPress filters, both at priority `99`, so it runs after most other plugins and themes that change image quality:

| Filter | Purpose |
| --- | --- |
| `wp_editor_set_quality` | Sets the quality for all image formats that `WP_Image_Editor` (GD or Imagick) processes. |
| `jpeg_quality` | A JPEG-specific fallback that catches cases `wp_editor_set_quality` might miss. |

Both callbacks return `100`, the highest value available.

## Installation

### Manual upload

1. Create a folder named `sikora-high-quality-images` in `wp-content/plugins/`.
2. Put the plugin PHP file in that folder.
3. In the WordPress admin, go to **Plugins** and activate **Sikora High Quality Images (Optimization)**.

### As a must-use plugin (optional)

To keep the plugin always active so it can't be turned off from the admin, put the PHP file directly in `wp-content/mu-plugins/`.

## Usage

The plugin works as soon as it's activated. There's nothing to configure.

### Existing images

The plugin only affects images that are **generated after activation**. Thumbnails and resized copies that already exist keep their original compression. To rebuild them at the new quality, regenerate thumbnails with WP-CLI:

```bash
wp media regenerate --yes
```

A plugin such as [Regenerate Thumbnails](https://wordpress.org/plugins/regenerate-thumbnails/) does the same job from the admin.

## Things to Consider

- **Larger files:** Quality 100 files can be much larger than those saved at WordPress's default of 82. This affects disk usage, backup size, and page weight. Consider pairing the plugin with a CDN or an image-optimization service that serves responsive, compressed variants.
- **Disk usage and uploads:** Each upload creates several generated sizes, and at quality 100 each one takes more space and slightly longer to encode. On sites where many users can upload media (such as multi-author sites or membership sites), storage can fill up faster than expected. Limit upload permissions to trusted roles, set upload size limits, and keep an eye on disk space. You can also unregister image sizes your theme doesn't use to reduce how many files each upload creates.
- **Lossless formats:** PNG is lossless, so a quality setting has little or no visual effect on it. How the value is used depends on the image editor library (GD or Imagick) on your server.
- **AVIF support:** WordPress core supports AVIF from version 6.5. Your server's GD or Imagick build also needs AVIF support.
- **Conflicts:** Other plugins or themes that hook the same filters at a priority above `99` will override this plugin.

## Customization

To use a different quality level, change the value returned by both functions:

```php
function sikora_set_jpeg_quality( $quality, $context ) {
    return 90;
}

function sikora_set_wp_editor_quality( $quality, $mime_type = null ) {
    return 90;
}
```

## Requirements

- WordPress 5.0 or later (6.5 or later for AVIF)
- PHP 7.0 or later
- The GD or Imagick PHP extension

## Changelog

### 2.0.0
- Added the `wp_editor_set_quality` filter, which applies maximum quality to JPEG, WebP, AVIF, and PNG.
- Kept the `jpeg_quality` filter as a JPEG-specific fallback.

## Author

Developed by [Sikora Collective](https://sikoracollective.com/).
