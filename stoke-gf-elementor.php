<?php
/**
 * Plugin Name:         Stoke Gravity Forms for Elementor
 * Plugin URI:          https://stokedesign.co/sandbox
 * Description:         Allows Gravity Forms to easily be inserted and styled in Elementor.
 * Version:             1.2b
 * Author:              Stoke Design Co
 * Author URI:          https://stokedesign.co/
 * Text Domain:         stoke-gf-elementor
 * Requires at least:   4.7
 * License:             GPLv3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace StokeGFElementor;

use GFCommon;

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/FieldShortcodes.php';

const MIN_GF_VERSION = '2.7.15';

// Add a settings link on the plugins page.
add_filter(
    'plugin_action_links_' . plugin_basename( __FILE__ ),
    function ( $links ) {
        $links[] = sprintf(
            '<a href="%s">%s</a>',

            esc_url( admin_url( 'admin.php?page=stkc-gf-field-shortcodes' ) ),

            esc_html__( 'Settings', 'stoke-gf-elementor' )
        );
        return $links;
    }
);

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

                require_once plugin_dir_path( __FILE__ ) . 'includes/Widget.php';

                // Register widget.
                $widgets_manager->register( new Widget() );

                add_action(
                        'elementor/editor/after_enqueue_styles',
                        array( Widget::class, 'enqueue_editor_styles' )
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
                esc_html__( '[plugin_name] requires Gravity Forms [version] or higher. Please upgrade Gravity Forms to use this widget.', 'stoke-gf-elementor' ),
                array(
                                        '[plugin_name]' => esc_html__( 'Stoke Gravity Forms for Elementor', 'stoke-gf-elementor' ),
                                        '[version]'     => MIN_GF_VERSION,
                                )
            )
		);

		echo "<div class='error is-dismissible' style='padding: 1.25em 0 1.25em 1em;'>$notice</div>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
);
/**
 * Enqueue frontend styles.
 *
 * @since 1.0.0
 */
function enqueue_frontend_styles() {
        wp_enqueue_style(
                'stoke-gf-elementor',
                plugin_dir_url( __FILE__ ) . 'assets/forms.css',
                array(),
                '1.0.0'
        );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_frontend_styles' );


