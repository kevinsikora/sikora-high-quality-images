=== Sikora Image Quality ===
Contributors: sikoracollective
Tags: images, jpeg, quality, media, upload, webp, avif
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.0
Stable tag: 3.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Control the quality WordPress uses when it generates and saves images, with separate settings for JPEG and the WordPress image editor.

== Description ==

When you upload an image, WordPress creates multiple resized copies (thumbnail, medium, large, and other sizes). WordPress compresses those files using a default quality of **82**. That favors smaller files, but it can cause visible compression artifacts. Higher values preserve detail but use more disk space and bandwidth.

**Why these settings are needed**

WordPress's default may not match your site. Photography and portfolio sites often need higher quality. Sites with limited storage may prefer lower values. This plugin lets you set the quality WordPress uses without writing custom code.

**What the plugin does**

It adds **Settings → Sikora Image Quality** with two sections:

* **JPEG Quality** — quality for generated JPEG images. Affects JPEG (.jpg, .jpeg) only.
* **WordPress Editor Quality** — quality when WordPress's image editor saves images. Affects JPEG, WebP, AVIF (WordPress 6.5+ when your server supports it), and PNG. PNG is lossless, so the quality value has little or no visual effect.

In each section you get:

* A quality slider and number field kept in sync (integers **1–100**). Plugin default: **100**. WordPress default shown on screen: **82**.
* A filter priority slider and number field kept in sync (integers **1–999**). Plugin default: **99**. WordPress default shown on screen: **10**.

**What filter priority does**

WordPress runs filters in priority order. Lower numbers run earlier; higher numbers run later. A later callback can override an earlier one. WordPress's default priority is **10**. This plugin defaults to **99** so its quality settings usually apply even if another plugin or theme also changes image quality. Lower the priority if you want another plugin to take precedence.

Your settings apply only to images generated or re-saved after you save them. Existing thumbnails are not changed automatically. Uninstalling the plugin removes its settings from the database.

License: **GNU General Public License v2.0 or later**.

== Installation ==

1. Upload the `sikora-image-quality` folder to `/wp-content/plugins/`, or install the zip via **Plugins → Add New → Upload Plugin**.
2. Activate **Sikora Image Quality** on the **Plugins** screen.
3. Open **Settings → Sikora Image Quality** and set your quality and filter priority values.

== Frequently Asked Questions ==

= Why change image quality? =

WordPress defaults to **82**. Raise it for more detail, or lower it for smaller files.

= What does filter priority do? =

It controls whether this plugin's quality values run before or after other plugins that change the same settings. Use a higher value (such as **99**) if another plugin is overriding you.

= Which file formats are affected? =

* **JPEG Quality:** JPEG (.jpg, .jpeg) only.
* **WordPress Editor Quality:** JPEG, WebP, AVIF (when supported), and PNG.

= Will existing images change? =

No. Only new or re-saved generated images use the new settings.

== Changelog ==

= 3.0.0 =
* Settings screen with JPEG Quality and WordPress Editor Quality sections.
* Synced quality controls (1–100, default 100) and filter priority controls (1–999, default 99).
* Shows WordPress defaults (quality 82, filter priority 10) and which file types each setting affects.
* Removes plugin settings from the database on uninstall.

== Upgrade Notice ==

= 3.0.0 =
New settings screen for JPEG and editor quality. Review **Settings → Sikora Image Quality** after upgrading.
