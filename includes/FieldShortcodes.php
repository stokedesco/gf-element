<?php
namespace StokeGFElementor;

use GFAPI;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Handle Gravity Forms field shortcodes.
 */
class FieldShortcodes {
    /**
     * Bootstraps hooks.
     */
    public static function init() {
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
        add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_assets' ] );
        add_action( 'init', [ __CLASS__, 'register_shortcodes' ] );
    }

    /**
     * Register plugin settings.
     */
    public static function register_settings() {
        register_setting(
            'stkc_content',
            'stkc_gf_sc',
            [
                'type'              => 'array',
                'default'           => [ 'enabled' => false, 'mappings' => [] ],
                'sanitize_callback' => [ __CLASS__, 'sanitize_mappings' ],
            ]
        );
    }

    /**
     * Sanitize settings.
     *
     * @param array $input Raw input.
     *
     * @return array
     */
    public static function sanitize_mappings( $input ) {
        $out  = [ 'enabled' => ! empty( $input['enabled'] ), 'mappings' => [] ];
        $seen = [];
        $invalid = false;

        if ( ! empty( $input['mappings'] ) && is_array( $input['mappings'] ) ) {
            foreach ( $input['mappings'] as $row ) {
                $form = isset( $row['form_id'] ) ? absint( $row['form_id'] ) : 0;
                $fld  = isset( $row['field_id'] ) ? trim( wp_unslash( $row['field_id'] ) ) : '';
                $tag  = isset( $row['tag'] ) ? preg_replace( '/[^A-Za-z0-9_]/', '', $row['tag'] ) : '';
                $src  = isset( $row['source'] ) ? strtoupper( sanitize_text_field( $row['source'] ) ) : 'AUTO';

                if ( ! $form || '' === $fld || '' === $tag ) {
                    $invalid = true;
                    continue;
                }
                if ( ! preg_match( '/^\d+(?:\.\d+)?$/', $fld ) ) {
                    $invalid = true;
                    continue;
                }
                if ( ! preg_match( '/^[A-Za-z0-9_]+$/', $tag ) ) {
                    $invalid = true;
                    continue;
                }
                if ( isset( $seen[ strtolower( $tag ) ] ) ) {
                    $invalid = true;
                    continue;
                }
                if ( ! in_array( $src, [ 'AUTO', 'POST', 'GET', 'ENTRY' ], true ) ) {
                    $src = 'AUTO';
                }

                $seen[ strtolower( $tag ) ] = true;
                $out['mappings'][]         = [
                    'form_id'  => $form,
                    'field_id' => $fld,
                    'tag'      => $tag,
                    'source'   => 'AUTO' === $src ? 'auto' : $src,
                ];
            }
        }

        if ( $invalid ) {
            add_settings_error( 'stkc_gf_sc', 'stkc_gf_sc_invalid', esc_html__( 'One or more mappings were invalid and have been ignored.', 'stoke-gf-elementor' ), 'warning' );
        }

        return $out;
    }


    private static function get_forms() {
        static $forms = null;
        if ( null !== $forms ) {
            return $forms;
        }

        $forms = [];

        if ( class_exists( GFAPI::class ) ) {
            $list = GFAPI::get_forms();
            if ( is_array( $list ) ) {
                foreach ( $list as $f ) {
                    $form_id = (int) $f['id'];
                    $forms[ $form_id ] = [
                        'title'  => $f['title'],
                        'fields' => [],
                    ];

                    $meta = GFAPI::get_form( $form_id );
                    if ( $meta && isset( $meta['fields'] ) ) {
                        foreach ( $meta['fields'] as $field ) {
                            $fid   = (string) $field['id'];
                            $label = isset( $field['label'] ) ? $field['label'] : $fid;
                            $forms[ $form_id ]['fields'][] = [
                                'id'    => $fid,
                                'label' => $label . ' (' . $fid . ')',
                            ];
                            if ( ! empty( $field['inputs'] ) ) {
                                foreach ( $field['inputs'] as $input ) {
                                    $iid    = (string) $input['id'];
                                    $ilabel = isset( $input['label'] ) ? $input['label'] : $iid;
                                    $forms[ $form_id ]['fields'][] = [
                                        'id'    => $iid,
                                        'label' => $label . ': ' . $ilabel . ' (' . $iid . ')',
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $forms;
    }

    /**
     * Register admin menu.
     */
    public static function register_menu() {
        add_submenu_page(
            'gf_edit_forms',
            esc_html__( 'GF Field Shortcodes', 'stoke-gf-elementor' ),
            esc_html__( 'GF Field Shortcodes', 'stoke-gf-elementor' ),
            'manage_options',
            'stkc-gf-field-shortcodes',
            [ __CLASS__, 'render_page' ],
            10
        );
    }

    /**
     * Enqueue admin assets.
     *
     * @param string $hook Current page hook.
     */
    public static function admin_assets( $hook ) {

        if ( empty( $_GET['page'] ) || 'stkc-gf-field-shortcodes' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            return;
        }

        if ( false === strpos( $hook, 'stkc-gf-field-shortcodes' ) ) {
            return;
        }

        wp_enqueue_script(
            'stkc-gf-sc-admin',
            plugin_dir_url( __FILE__ ) . '../assets/field-shortcodes.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );

        wp_localize_script(
            'stkc-gf-sc-admin',
            'stkcGfScData',
            [
                'forms' => self::get_forms(),
                'i18n'  => [ 'selectField' => esc_html__( 'Select a field', 'stoke-gf-elementor' ) ],
            ]
        );

    }

    /**
     * Render settings page.
     */
    public static function render_page() {
        $opt      = (array) get_option( 'stkc_gf_sc', [] );
        $enabled  = ! empty( $opt['enabled'] );
        $mappings = ! empty( $opt['mappings'] ) && is_array( $opt['mappings'] ) ? $opt['mappings'] : [];
        $forms    = self::get_forms();

        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'GF Field Shortcodes', 'stoke-gf-elementor' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'stkc_content' );
                settings_errors( 'stkc_gf_sc' );
                ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable', 'stoke-gf-elementor' ); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="stkc_gf_sc[enabled]" value="1" <?php checked( $enabled ); ?> />
                                <?php esc_html_e( 'Enable Gravity Forms field shortcodes', 'stoke-gf-elementor' ); ?>
                            </label>
                        </td>
                    </tr>
                </table>

                <h2><?php esc_html_e( 'Mappings', 'stoke-gf-elementor' ); ?></h2>
                <table class="widefat stkc-gf-mappings">
                    <thead>
                    <tr>

                        <th><?php esc_html_e( 'Form', 'stoke-gf-elementor' ); ?></th>
                        <th><?php esc_html_e( 'Field', 'stoke-gf-elementor' ); ?></th>

                        <th><?php esc_html_e( 'Form ID', 'stoke-gf-elementor' ); ?></th>
                        <th><?php esc_html_e( 'Field ID', 'stoke-gf-elementor' ); ?></th>

                        <th><?php esc_html_e( 'Shortcode Tag', 'stoke-gf-elementor' ); ?></th>
                        <th><?php esc_html_e( 'Source', 'stoke-gf-elementor' ); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $mappings as $i => $m ) :
                        $form    = (int) $m['form_id'];
                        $field   = esc_attr( (string) $m['field_id'] );
                        $tag     = esc_attr( $m['tag'] );
                        $source  = esc_attr( $m['source'] );
                        $preview = sprintf( '?eid={entry_id}&f%d_%s={Field:%s}', $form, $field, $field );
                        ?>
                        <tr>

                            <td>
                                <select name="stkc_gf_sc[mappings][<?php echo esc_attr( $i ); ?>][form_id]" class="stkc-form-select">
                                    <?php foreach ( $forms as $fid => $fdata ) : ?>
                                        <option value="<?php echo esc_attr( $fid ); ?>" <?php selected( $form, $fid ); ?>><?php echo esc_html( $fdata['title'] . ' (' . $fid . ')' ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="stkc_gf_sc[mappings][<?php echo esc_attr( $i ); ?>][field_id]" class="stkc-field-select">
                                    <?php if ( isset( $forms[ $form ] ) ) :
                                        foreach ( $forms[ $form ]['fields'] as $f ) : ?>
                                            <option value="<?php echo esc_attr( $f['id'] ); ?>" <?php selected( $field, $f['id'] ); ?>><?php echo esc_html( $f['label'] ); ?></option>
                                        <?php endforeach;
                                    endif; ?>
                                </select>
                            </td>

                            <td><input type="number" class="small-text" min="1" name="stkc_gf_sc[mappings][<?php echo esc_attr( $i ); ?>][form_id]" value="<?php echo esc_attr( $form ); ?>" /></td>
                            <td><input type="text" class="small-text" name="stkc_gf_sc[mappings][<?php echo esc_attr( $i ); ?>][field_id]" value="<?php echo esc_attr( $field ); ?>" /></td>

                            <td><input type="text" class="regular-text" name="stkc_gf_sc[mappings][<?php echo esc_attr( $i ); ?>][tag]" value="<?php echo esc_attr( $tag ); ?>" /></td>
                            <td>
                                <select name="stkc_gf_sc[mappings][<?php echo esc_attr( $i ); ?>][source]">
                                    <?php foreach ( [ 'auto', 'POST', 'GET', 'ENTRY' ] as $opt ) : ?>
                                        <option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $source, $opt ); ?>><?php echo esc_html( strtoupper( $opt ) ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><button type="button" class="button stkc-remove-row" aria-label="<?php esc_attr_e( 'Remove', 'stoke-gf-elementor' ); ?>">&times;</button></td>
                        </tr>
                        <tr class="stkc-preview-row">
                            <td colspan="5">
                                <p class="description"><code><?php echo esc_html( $preview ); ?></code> <button type="button" class="button button-small stkc-copy" data-copy="<?php echo esc_attr( $preview ); ?>"><?php esc_html_e( 'Copy', 'stoke-gf-elementor' ); ?></button></p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <p><button type="button" class="button stkc-add-row"><?php esc_html_e( 'Add Mapping', 'stoke-gf-elementor' ); ?></button></p>
                <?php submit_button(); ?>
            </form>
        </div>

        <script type="text/html" id="stkc-gf-sc-row-template">
            <tr>

                <td>
                    <select name="stkc_gf_sc[mappings][__index__][form_id]" class="stkc-form-select">
                        <?php foreach ( $forms as $fid => $fdata ) : ?>
                            <option value="<?php echo esc_attr( $fid ); ?>"><?php echo esc_html( $fdata['title'] . ' (' . $fid . ')' ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="stkc_gf_sc[mappings][__index__][field_id]" class="stkc-field-select"></select>
                </td>

                <td><input type="number" class="small-text" min="1" name="stkc_gf_sc[mappings][__index__][form_id]" value="" /></td>
                <td><input type="text" class="small-text" name="stkc_gf_sc[mappings][__index__][field_id]" value="" /></td>

                <td><input type="text" class="regular-text" name="stkc_gf_sc[mappings][__index__][tag]" value="" /></td>
                <td>
                    <select name="stkc_gf_sc[mappings][__index__][source]">
                        <option value="auto">AUTO</option>
                        <option value="POST">POST</option>
                        <option value="GET">GET</option>
                        <option value="ENTRY">ENTRY</option>
                    </select>
                </td>
                <td><button type="button" class="button stkc-remove-row" aria-label="<?php esc_attr_e( 'Remove', 'stoke-gf-elementor' ); ?>">&times;</button></td>
            </tr>
            <tr class="stkc-preview-row">
                <td colspan="5">
                    <p class="description"><code></code> <button type="button" class="button button-small stkc-copy" data-copy=""><?php esc_html_e( 'Copy', 'stoke-gf-elementor' ); ?></button></p>
                </td>
            </tr>
        </script>
        <?php
    }

    /**
     * Register shortcodes at runtime.
     */
    public static function register_shortcodes() {
        $opt = (array) get_option( 'stkc_gf_sc' );
        if ( empty( $opt['enabled'] ) || empty( $opt['mappings'] ) ) {
            return;
        }

        foreach ( $opt['mappings'] as $m ) {
            $form_id  = isset( $m['form_id'] ) ? (int) $m['form_id'] : 0;
            $field_id = isset( $m['field_id'] ) ? (string) $m['field_id'] : '';
            $tag      = isset( $m['tag'] ) ? preg_replace( '/[^A-Za-z0-9_]/', '', $m['tag'] ) : '';
            $source   = isset( $m['source'] ) ? $m['source'] : 'auto';
            if ( ! $form_id || '' === $field_id || ! $tag ) {
                continue;
            }

            add_shortcode(
                $tag,
                function( $atts = [] ) use ( $form_id, $field_id, $source, $tag ) {
                    $atts = shortcode_atts( [ 'default' => '' ], $atts, $tag );

                    $clean = function( $v ) {
                        if ( is_array( $v ) ) {
                            $v = reset( $v );
                        }
                        return esc_html( sanitize_text_field( wp_unslash( (string) $v ) ) );
                    };

                    $value = '';

                    // POST (same-request confirmation).
                    $is_this_form = isset( $_POST['gform_submit'] ) && (int) $_POST['gform_submit'] === $form_id; // phpcs:ignore WordPress.Security.NonceVerification.Missing
                    if ( ( 'POST' === $source || 'auto' === $source ) && $is_this_form ) {
                        $raw = function_exists( 'rgpost' ) ? rgpost( "input_{$field_id}" ) : ( $_POST[ "input_{$field_id}" ] ?? null ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
                        if ( null !== $raw && '' !== $raw ) {
                            $value = $clean( $raw );
                        }
                    }

                    // GET (redirect with query string).
                    if ( '' === $value && ( 'GET' === $source || 'auto' === $source ) ) {
                        $param = "f{$form_id}_{$field_id}";
                        if ( isset( $_GET[ $param ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                            $value = $clean( $_GET[ $param ] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                        }
                    }

                    // ENTRY lookup.
                    if ( '' === $value && ( 'ENTRY' === $source || 'auto' === $source ) && class_exists( GFAPI::class ) ) {
                        $eid = isset( $_GET['eid'] ) ? absint( $_GET['eid'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                        if ( $eid ) {
                            $entry = GFAPI::get_entry( $eid );
                            if ( ! is_wp_error( $entry ) ) {
                                $raw = function_exists( 'rgar' ) ? rgar( $entry, $field_id ) : ( $entry[ $field_id ] ?? null );
                                if ( null !== $raw && '' !== $raw ) {
                                    $value = $clean( $raw );
                                }
                            }
                        }
                    }

                    return '' !== $value ? $value : esc_html( $atts['default'] );
                }
            );
        }
    }
}

FieldShortcodes::init();
