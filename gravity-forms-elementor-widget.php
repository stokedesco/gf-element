<?php
/**
 * Plugin Name:         Gravity Forms Widget for Elementor
 * Plugin URI:          https://www.gravitykit.com/products/gravity-forms-elementor-widget/?utm_source=plugin&utm_campaign=elementor-widget&utm_content=pluginuri
 * Description:         Integrates Gravity Forms with Elementor page builder. Simple. Configurable. Powerful.
 * Version:             1.0.1
 * Author:              GravityKit
 * Author URI:          https://www.gravitykit.com/?utm_source=plugin&utm_campaign=elementor-widget&utm_content=authoruri
 * Text Domain:         gk-gravity-forms-elementor-widget
 * Requires at least:   4.7
 * License:             GPLv3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace GravityKit\GravityFormsElementorWidget;

use GFCommon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

const MIN_GF_VERSION = '2.7.15';

require_once plugin_dir_path( __FILE__ ) . 'vendor_prefixed/gravitykit/foundation/src/preflight_check.php';

if ( ! Foundation\should_load( __FILE__ ) ) {
	return;
}

/**
 * Check if Gravity Forms is installed and activated, and if the minimum required version is met.
 *
 * @since 1.0.0
 *
 * @return bool True if Gravity Forms is installed and activated, and the minimum required version is met. False otherwise.
 */
function meets_gravity_forms_requirements() {
	return class_exists( 'GFCommon' ) && version_compare( GFCommon::$version, MIN_GF_VERSION, '>' );
}

/**
 * Register the Elementor widget.
 */
add_action(
    'elementor/widgets/register',
    function ( $widgets_manager ) {
		if ( ! meets_gravity_forms_requirements() ) {
			return;
		}

		// Register widget.
		$widgets_manager->register( new Widget() );

		add_action(
            'elementor/editor/after_enqueue_styles',
            array(
				'GravityKit\GravityFormsElementorWidget\Widget',
				'enqueue_editor_styles',
            )
		);
	}
);

/*
 * Display a notice if Gravity Forms is not installed or the minimum required version is not met.
 */
add_action(
    'admin_notices',
    function () {
		if ( meets_gravity_forms_requirements() ) {
			return;
		}

		$notice = wpautop(
            strtr(
            // translators: Do not translate [version]; it is replaced with the minimum required Gravity Forms version required.
                esc_html__( '[plugin_name] requires Gravity Forms [version] or higher. Please upgrade Gravity Forms to use this widget.', 'gk-gravity-forms-elementor-widget' ),
                array(
					'[plugin_name]' => esc_html__( 'Gravity Forms Widget for Elementor', 'gk-gravity-forms-elementor-widget' ),
					'[version]'     => MIN_GF_VERSION,
                )
            )
		);

		echo "<div class='error is-dismissible' style='padding: 1.25em 0 1.25em 1em;'>$notice</div>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
);

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'vendor_prefixed/autoload.php';

Foundation\Core::register( __FILE__, array( 'no_admin_menu' => true ) );
