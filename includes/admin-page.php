<?php
/**
 * Admin settings page for Sikora Image Quality.
 *
 * @package SikoraImageQuality
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared args for registered integer settings.
 *
 * @param string $sanitize_callback Sanitize callback name.
 * @param int    $default           Default value.
 * @return array<string, mixed>
 */
function sikora_iq_get_setting_args( $sanitize_callback, $default ) {
	return array(
		'type'              => 'integer',
		'sanitize_callback' => $sanitize_callback,
		'default'           => $default,
		'capability'        => 'manage_options',
		'show_in_rest'      => false,
	);
}

/**
 * Register settings, sections, and fields.
 */
function sikora_iq_register_admin_settings() {
	register_setting(
		'sikora_iq_settings_group',
		SIKORA_IQ_OPTION_JPEG_QUALITY,
		sikora_iq_get_setting_args( 'sikora_iq_sanitize_quality', SIKORA_IQ_DEFAULT_QUALITY )
	);

	register_setting(
		'sikora_iq_settings_group',
		SIKORA_IQ_OPTION_JPEG_PRIORITY,
		sikora_iq_get_setting_args( 'sikora_iq_sanitize_filter_priority', SIKORA_IQ_DEFAULT_FILTER_PRIORITY )
	);

	register_setting(
		'sikora_iq_settings_group',
		SIKORA_IQ_OPTION_EDITOR_QUALITY,
		sikora_iq_get_setting_args( 'sikora_iq_sanitize_quality', SIKORA_IQ_DEFAULT_QUALITY )
	);

	register_setting(
		'sikora_iq_settings_group',
		SIKORA_IQ_OPTION_EDITOR_PRIORITY,
		sikora_iq_get_setting_args( 'sikora_iq_sanitize_filter_priority', SIKORA_IQ_DEFAULT_FILTER_PRIORITY )
	);

	add_settings_section(
		'sikora_iq_jpeg_section',
		__( 'JPEG Quality', 'sikora-image-quality' ),
		'sikora_iq_render_jpeg_section_intro',
		'sikora-image-quality'
	);

	add_settings_section(
		'sikora_iq_editor_section',
		__( 'WordPress Editor Quality', 'sikora-image-quality' ),
		'sikora_iq_render_editor_section_intro',
		'sikora-image-quality'
	);

	add_settings_field(
		'sikora_iq_jpeg_controls',
		'',
		'sikora_iq_render_jpeg_controls',
		'sikora-image-quality',
		'sikora_iq_jpeg_section'
	);

	add_settings_field(
		'sikora_iq_editor_controls',
		'',
		'sikora_iq_render_editor_controls',
		'sikora-image-quality',
		'sikora_iq_editor_section'
	);
}
add_action( 'admin_init', 'sikora_iq_register_admin_settings' );

/**
 * Add the plugin settings page under Settings.
 */
function sikora_iq_add_settings_page() {
	add_options_page(
		__( 'Sikora Image Quality', 'sikora-image-quality' ),
		__( 'Sikora Image Quality', 'sikora-image-quality' ),
		'manage_options',
		'sikora-image-quality',
		'sikora_iq_render_settings_page'
	);
}
add_action( 'admin_menu', 'sikora_iq_add_settings_page' );

/**
 * Enqueue inline admin assets on the settings screen only.
 *
 * @param string $hook_suffix Current admin page hook suffix.
 */
function sikora_iq_enqueue_admin_assets( $hook_suffix ) {
	if ( 'settings_page_sikora-image-quality' !== $hook_suffix ) {
		return;
	}

	wp_register_script( 'sikora-iq-admin-settings', false, array(), SIKORA_IQ_PLUGIN_VERSION, true );
	wp_enqueue_script( 'sikora-iq-admin-settings' );
	wp_add_inline_script( 'sikora-iq-admin-settings', sikora_iq_get_admin_settings_script() );

	wp_register_style( 'sikora-iq-admin-settings', false, array(), SIKORA_IQ_PLUGIN_VERSION );
	wp_enqueue_style( 'sikora-iq-admin-settings' );
	wp_add_inline_style( 'sikora-iq-admin-settings', sikora_iq_get_admin_settings_styles() );
}
add_action( 'admin_enqueue_scripts', 'sikora_iq_enqueue_admin_assets' );

/**
 * Inline CSS for the settings page layout.
 *
 * @return string
 */
function sikora_iq_get_admin_settings_styles() {
	return '
		.sikora-iq-page-header {
			display: flex;
			align-items: baseline;
			gap: 12px;
			margin-bottom: 0;
		}
		.sikora-iq-page-header__title,
		.wrap h1.sikora-iq-page-header__title {
			font-weight: 700;
			font-size: 23px;
			line-height: 1.3;
			margin: 0;
			padding: 0;
		}
		.sikora-iq-page-header__version {
			font-size: 14px;
			line-height: 1.4;
		}
		body.settings_page_sikora-image-quality .wrap > .notice {
			margin: 8px 0 12px;
		}
		body.settings_page_sikora-image-quality .wrap > form {
			margin-top: 12px;
		}
		.sikora-iq-control-label {
			display: block;
			font-weight: 600;
			margin: 16px 0 8px;
		}
		.sikora-iq-control-row {
			display: flex;
			align-items: center;
			flex-wrap: wrap;
			gap: 12px;
			margin-bottom: 6px;
		}
		.sikora-iq-control-row input[type="range"] {
			width: min(320px, 100%);
			max-width: 100%;
		}
		.sikora-iq-default-note {
			margin: 0 0 8px;
			color: #646970;
		}
		.sikora-iq-file-types {
			color: #646970;
			font-size: 13px;
		}
		.sikora-iq-developed-by {
			margin: 12px 12px 12px 0;
			font-size: 14px;
			line-height: 1.4;
		}
		#wpfooter .sikora-iq-developed-by {
			margin: 12px 12px 12px 0;
			padding-left: 0;
		}
	';
}

/**
 * Inline JavaScript that keeps sliders and number fields in sync.
 *
 * @return string
 */
function sikora_iq_get_admin_settings_script() {
	return <<<'JS'
( function () {
	'use strict';

	/**
	 * Keep every slider and matching number input in a control row in sync.
	 *
	 * @param {HTMLElement} row Control row containing range and number inputs.
	 */
	function bindSyncedRow( row ) {
		var slider = row.querySelector( 'input[type="range"]' );
		var input = row.querySelector( 'input[type="number"]' );

		if ( ! slider || ! input ) {
			return;
		}

		var min = parseInt( input.min, 10 );
		var max = parseInt( input.max, 10 );

		if ( Number.isNaN( min ) ) {
			min = parseInt( slider.min, 10 ) || 1;
		}
		if ( Number.isNaN( max ) ) {
			max = parseInt( slider.max, 10 ) || 100;
		}

		function clamp( value ) {
			var n = parseInt( value, 10 );
			if ( Number.isNaN( n ) ) {
				n = min;
			}
			return Math.min( max, Math.max( min, n ) );
		}

		function syncFromSlider() {
			input.value = String( clamp( slider.value ) );
		}

		function syncFromInput( force ) {
			if ( ! force && '' === input.value ) {
				return;
			}
			var value = clamp( input.value );
			input.value = String( value );
			slider.value = String( value );
		}

		slider.addEventListener( 'input', syncFromSlider );
		slider.addEventListener( 'change', syncFromSlider );
		input.addEventListener( 'input', function () {
			syncFromInput( false );
		} );
		input.addEventListener( 'change', function () {
			syncFromInput( true );
		} );
		input.addEventListener( 'blur', function () {
			syncFromInput( true );
		} );

		syncFromInput( true );
	}

	function init() {
		document.querySelectorAll( '.sikora-iq-control-row' ).forEach( bindSyncedRow );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
JS;
}

/**
 * Short intro under the JPEG section heading.
 */
function sikora_iq_render_jpeg_section_intro() {
	echo '<p class="description">';
	esc_html_e(
		'Controls the jpeg_quality filter used when WordPress generates JPEG images.',
		'sikora-image-quality'
	);
	echo '</p>';
}

/**
 * Short intro under the editor section heading.
 */
function sikora_iq_render_editor_section_intro() {
	echo '<p class="description">';
	esc_html_e(
		'Controls the wp_editor_set_quality filter used when the WordPress image editor saves images.',
		'sikora-image-quality'
	);
	echo '</p>';
}

/**
 * Render a synced slider and number field row.
 *
 * @param string $label       Visible label before the slider.
 * @param string $slider_id   HTML id for the range input.
 * @param string $input_id    HTML id for the number input.
 * @param string $option_name Option name used as the number input name attribute.
 * @param int    $value       Current value.
 * @param int    $min         Minimum allowed value.
 * @param int    $max         Maximum allowed value.
 * @param string $trailing    Optional plain text shown after the number field.
 */
function sikora_iq_render_slider_number_row( $label, $slider_id, $input_id, $option_name, $value, $min, $max, $trailing = '' ) {
	?>
	<label class="sikora-iq-control-label" for="<?php echo esc_attr( $input_id ); ?>">
		<?php echo esc_html( $label ); ?>
	</label>
	<div class="sikora-iq-control-row">
		<label for="<?php echo esc_attr( $slider_id ); ?>" class="screen-reader-text">
			<?php
			echo esc_html(
				sprintf(
					/* translators: %s: control name, e.g. JPEG Quality. */
					__( '%s slider', 'sikora-image-quality' ),
					$label
				)
			);
			?>
		</label>
		<input
			type="range"
			id="<?php echo esc_attr( $slider_id ); ?>"
			min="<?php echo esc_attr( (string) $min ); ?>"
			max="<?php echo esc_attr( (string) $max ); ?>"
			step="1"
			value="<?php echo esc_attr( (string) $value ); ?>"
		/>
		<input
			type="number"
			id="<?php echo esc_attr( $input_id ); ?>"
			name="<?php echo esc_attr( $option_name ); ?>"
			min="<?php echo esc_attr( (string) $min ); ?>"
			max="<?php echo esc_attr( (string) $max ); ?>"
			step="1"
			value="<?php echo esc_attr( (string) $value ); ?>"
			class="small-text"
		/>
		<?php if ( '' !== $trailing ) : ?>
			<span class="sikora-iq-file-types"><?php echo esc_html( $trailing ); ?></span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render JPEG quality and filter priority controls.
 */
function sikora_iq_render_jpeg_controls() {
	$quality  = sikora_iq_get_jpeg_quality();
	$priority = sikora_iq_get_jpeg_filter_priority();

	$file_types = __( 'Affects JPEG (.jpg, .jpeg) images', 'sikora-image-quality' );

	sikora_iq_render_slider_number_row(
		__( 'JPEG Quality', 'sikora-image-quality' ),
		'sikora-iq-jpeg-quality-slider',
		'sikora-iq-jpeg-quality-input',
		SIKORA_IQ_OPTION_JPEG_QUALITY,
		$quality,
		SIKORA_IQ_MIN_QUALITY,
		SIKORA_IQ_MAX_QUALITY,
		$file_types
	);
	?>
	<p class="sikora-iq-default-note">
		<?php
		printf(
			/* translators: %d: WordPress default JPEG quality. */
			esc_html__( 'WordPress default image quality: %d', 'sikora-image-quality' ),
			absint( SIKORA_IQ_WP_DEFAULT_QUALITY )
		);
		?>
	</p>
	<?php
	sikora_iq_render_slider_number_row(
		__( 'JPEG Filter Priority', 'sikora-image-quality' ),
		'sikora-iq-jpeg-priority-slider',
		'sikora-iq-jpeg-priority-input',
		SIKORA_IQ_OPTION_JPEG_PRIORITY,
		$priority,
		SIKORA_IQ_MIN_PRIORITY,
		SIKORA_IQ_MAX_PRIORITY
	);
	?>
	<p class="sikora-iq-default-note">
		<?php
		printf(
			/* translators: %d: WordPress default filter priority. */
			esc_html__( 'WordPress default filter priority: %d', 'sikora-image-quality' ),
			absint( SIKORA_IQ_WP_DEFAULT_FILTER_PRIORITY )
		);
		?>
	</p>
	<?php
}

/**
 * Render WordPress editor quality and filter priority controls.
 */
function sikora_iq_render_editor_controls() {
	$quality  = sikora_iq_get_editor_quality();
	$priority = sikora_iq_get_editor_filter_priority();

	$file_types = __( 'Affects JPEG, WebP, AVIF, and PNG images', 'sikora-image-quality' );

	sikora_iq_render_slider_number_row(
		__( 'WordPress Editor Quality', 'sikora-image-quality' ),
		'sikora-iq-editor-quality-slider',
		'sikora-iq-editor-quality-input',
		SIKORA_IQ_OPTION_EDITOR_QUALITY,
		$quality,
		SIKORA_IQ_MIN_QUALITY,
		SIKORA_IQ_MAX_QUALITY,
		$file_types
	);
	?>
	<p class="sikora-iq-default-note">
		<?php
		printf(
			/* translators: %d: WordPress default editor quality. */
			esc_html__( 'WordPress default image quality: %d', 'sikora-image-quality' ),
			absint( SIKORA_IQ_WP_DEFAULT_QUALITY )
		);
		?>
	</p>
	<?php
	sikora_iq_render_slider_number_row(
		__( 'WordPress Editor Filter Priority', 'sikora-image-quality' ),
		'sikora-iq-editor-priority-slider',
		'sikora-iq-editor-priority-input',
		SIKORA_IQ_OPTION_EDITOR_PRIORITY,
		$priority,
		SIKORA_IQ_MIN_PRIORITY,
		SIKORA_IQ_MAX_PRIORITY
	);
	?>
	<p class="sikora-iq-default-note">
		<?php
		printf(
			/* translators: %d: WordPress default filter priority. */
			esc_html__( 'WordPress default filter priority: %d', 'sikora-image-quality' ),
			absint( SIKORA_IQ_WP_DEFAULT_FILTER_PRIORITY )
		);
		?>
	</p>
	<?php
}

/**
 * Whether the current admin screen is this plugin's settings page.
 *
 * @return bool
 */
function sikora_iq_is_settings_screen() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}

	$screen = get_current_screen();

	return ( $screen && 'settings_page_sikora-image-quality' === $screen->id );
}

/**
 * Print the developer credit on its own line above the WordPress footer text.
 */
function sikora_iq_render_admin_footer_credit() {
	if ( ! sikora_iq_is_settings_screen() ) {
		return;
	}
	?>
	<div class="sikora-iq-developed-by">
		<?php esc_html_e( 'Developed by', 'sikora-image-quality' ); ?>
		<a
			href="<?php echo esc_url( 'https://sikoracollective.com/' ); ?>"
			target="_blank"
			rel="noopener noreferrer"
		>
			<?php esc_html_e( 'Sikora Collective', 'sikora-image-quality' ); ?>
		</a>
	</div>
	<?php
}
add_action( 'in_admin_footer', 'sikora_iq_render_admin_footer_credit' );

/**
 * Render the full settings page.
 */
function sikora_iq_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<div class="sikora-iq-page-header">
			<h1 class="sikora-iq-page-header__title"><?php esc_html_e( 'Sikora Image Quality', 'sikora-image-quality' ); ?></h1>
			<span class="sikora-iq-page-header__version"><?php echo esc_html( SIKORA_IQ_PLUGIN_VERSION ); ?></span>
		</div>
		<hr class="wp-header-end" />

		<form action="options.php" method="post">
			<?php
			settings_fields( 'sikora_iq_settings_group' );
			do_settings_sections( 'sikora-image-quality' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
