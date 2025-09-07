<?php
/**
 * Class Gravity_Forms.
 *
 * @package GravityKit\GravityFormsElementorWidget
 */

namespace GravityKit\GravityFormsElementorWidget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;
use Elementor\Widget_Base;
use GFAPI;
use GFSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Gravity Forms widget for Elementor.
 *
 * Integrates Gravity Forms with Elementor page builder.
 *
 * @since 1.0.0
 */
class Widget extends Widget_Base {
	const ELEMENT_KEY = 'gk-gravity-form';

	/**
	 * Retrieves Gravity Forms widget name.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'gk_elementor_gravity_form';
	}

	/**
	 * Retrieves Gravity Forms widget title.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Gravity Forms', 'gk-gravity-forms-elementor-widget' );
	}

	/**
	 * Retrieves the list of categories the Gravity Forms widget belongs to.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Retrieves Gravity Forms widget keywords.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array(
			'contact form',
			'form',
			'gravity forms',
			'gravityforms',
			'gravitykit',
		);
	}

	/**
	 * Returns widget icon.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'gk-gravity-forms-icon';
	}

	/**
	 * Returns widget custom icon.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget custom icon.
	 */
	public static function get_custom_icon() {
		return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 0H2C0.9 0 0 0.9 0 2V18C0 19.1 0.9 20 2 20H18C19.1 20 20 19.1 20 18V2C20 0.9 19.1 0 18 0ZM18 18H2V2H18V18Z" fill="currentColor"/>
            <path d="M4 4H16V6H4V4Z" fill="currentColor"/>
            <path d="M4 8H16V10H4V8Z" fill="currentColor"/>
            <path d="M4 12H12V14H4V12Z" fill="currentColor"/>
        </svg>';
	}

	/**
	 * Registers Gravity Forms widget controls.
	 *
	 * @since  1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_form',
			array(
				'label' => __( 'Form Settings', 'gk-gravity-forms-elementor-widget' ),
			)
		);

		$this->add_control(
			'form_id',
			array(
				'label'       => __( 'Select Form', 'gk-gravity-forms-elementor-widget' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_forms_list(),
				'default'     => '0',
				'label_block' => true,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'        => __( 'Title', 'gk-gravity-forms-elementor-widget' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'gk-gravity-forms-elementor-widget' ),
				'label_off'    => __( 'Hide', 'gk-gravity-forms-elementor-widget' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'        => __( 'Description', 'gk-gravity-forms-elementor-widget' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'gk-gravity-forms-elementor-widget' ),
				'label_off'    => __( 'Hide', 'gk-gravity-forms-elementor-widget' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'ajax',
			array(
				'label'        => __( 'Use Ajax', 'gk-gravity-forms-elementor-widget' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'gk-gravity-forms-elementor-widget' ),
				'label_off'    => __( 'No', 'gk-gravity-forms-elementor-widget' ),
				'return_value' => 'yes',
			)
		);

		$default_theme = GFSettings::is_orbital_default()
			? __( 'Orbital Theme', 'gk-gravity-forms-elementor-widget' )
			: __( 'Gravity Forms 2.5 Theme', 'gk-gravity-forms-elementor-widget' );

		$this->add_control(
			'theme',
			array(
				'label'   => __( 'Theme', 'gk-gravity-forms-elementor-widget' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					// translators: Do not translate placeholders in square brackets. They are placeholders.
					''              => strtr(
						__( 'Inherit from default [theme]', 'gk-gravity-forms-elementor-widget' ),
						array( '[theme]' => $default_theme )
					),
					'orbital'       => __( 'Orbital Theme', 'gk-gravity-forms-elementor-widget' ),
					'gravity-theme' => __( 'Gravity Forms 2.5 Theme', 'gk-gravity-forms-elementor-widget' ),
					'disable'       => __( 'Disable Theme', 'gk-gravity-forms-elementor-widget' ),
				),
				'default' => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_advanced',
			array(
				'label' => __( 'Advanced', 'gk-gravity-forms-elementor-widget' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$this->add_control(
			'field_values',
			array(
				'label'       => __( 'Field Values', 'gk-gravity-forms-elementor-widget' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				// translators: Do not translate placeholders in square brackets. They are placeholders.
				'description' => strtr(
                    __( 'Enter field values in the format: [example]. [link]Learn more.[/link]', 'gk-gravity-forms-elementor-widget' ),
                    array(
						'[example]' => '<code>input_1=First Name&amp;input_2=Last Name</code>',
						'[link]'    => '<a href="https://docs.gravityforms.com/allow-field-to-be-populated-dynamically/#h-block" target="_blank">',
						'[/link]'   => '<span class="screen-reader-text">' . esc_attr__( '(This link opens in a new window.)', 'gk-gravity-forms-elementor-widget' ) . '</span></a>',
					)
                ),
			)
		);

		$this->add_control(
			'tabindex',
			array(
				'label'       => __( 'Tab Index', 'gk-gravity-forms-elementor-widget' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'step'        => 1,
				'description' => __( 'Set the starting tabindex for the form.', 'gk-gravity-forms-elementor-widget' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_style',
			array(
				'label' => __( 'Form', 'gk-gravity-forms-elementor-widget' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_background_color',
			array(
				'label'     => __( 'Background Color', 'gk-gravity-forms-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .' . self::ELEMENT_KEY => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_alignment',
			array(
				'label'     => __( 'Alignment', 'gk-gravity-forms-elementor-widget' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'gk-gravity-forms-elementor-widget' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'gk-gravity-forms-elementor-widget' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'gk-gravity-forms-elementor-widget' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => is_rtl() ? 'right' : 'left',
				'selectors' => array(
					'{{WRAPPER}} .' . self::ELEMENT_KEY => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_max_width',
			array(
				'label'      => __( 'Max Width', 'gk-gravity-forms-elementor-widget' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1500,
					),
					'em' => array(
						'min' => 1,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .' . self::ELEMENT_KEY => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_margin',
			array(
				'label'      => __( 'Margin', 'gk-gravity-forms-elementor-widget' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .' . self::ELEMENT_KEY => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_padding',
			array(
				'label'      => __( 'Padding', 'gk-gravity-forms-elementor-widget' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .' . self::ELEMENT_KEY => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_border_radius',
			array(
				'label'      => __( 'Border Radius', 'gk-gravity-forms-elementor-widget' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .' . self::ELEMENT_KEY => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_border',
				'selector' => '{{WRAPPER}} .' . self::ELEMENT_KEY,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_box_shadow',
				'selector' => '{{WRAPPER}} .' . self::ELEMENT_KEY,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Renders Gravity Forms widget output on the frontend.
	 *
	 * @since  1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		if ( ! class_exists( 'GFAPI' ) ) {
			echo esc_html__( 'Gravity Forms is not installed or activated.', 'gk-gravity-forms-elementor-widget' );

			return;
		}

		$settings = $this->get_settings_for_display();

		$form_id = (int) $settings['form_id'];

		if ( 0 === $form_id ) {
			// Only show this message in the admin editor.
			if ( Plugin::$instance->editor->is_edit_mode() ) {
				$template = strtr(
                    '
				<div style="text-align: center; width: 100%;">
					<div style="width:120px; margin: 0 auto;">{icon}</div>
					<p>{message}</p>
				</div>',
                    array(
						'{icon}'    => self::get_filled_icon(),
						'{message}' => esc_html__( 'Select a form from the widget settings.', 'gk-gravity-forms-elementor-widget' ),
					)
                );

				echo $template; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			return;
		}

		$form = GFAPI::get_form( $form_id );

		if ( ! $form ) {
			echo esc_html__( 'The selected form does not exist.', 'gk-gravity-forms-elementor-widget' );

			return;
		}

		$title       = 'yes' === $settings['title'];
		$description = 'yes' === $settings['description'];
		$tabindex    = (int) $settings['tabindex'];
		$theme_slug  = isset( $settings['theme'] ) ? esc_html( $settings['theme'] ) : '';

		// If the theme is not set, it means we should use the default theme.
		// Only rely on methods added in 2.7.15.
		if ( empty( $theme_slug ) ) {
			$theme_slug = GFSettings::is_orbital_default() ? 'orbital' : 'gravity-theme';
		}

		$field_values = null;

		if ( ! empty( $settings['field_values'] ) ) {
			$field_values = array();

			parse_str( $settings['field_values'], $field_values );

			$field_values = array_map( 'esc_html', $field_values );
		}

		$ajax = 'yes' === $settings['ajax'];

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$ajax = true; // Force-enable Ajax in the editor to prevent JS errors, caused in part by the $form_scripts_body contents.

			add_action(
                'gform_enqueue_scripts',
                function ( $form ) use ( $theme_slug ) {
					add_filter(
                        'gform_form_theme_slug',
                        function ( $slug, $form ) use ( $theme_slug ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
                            return $theme_slug;
                        },
                        10,
                        2
					);

					// Enqueue the theme styles. This is a workaround that is much simpler than using the containers and service providers.
					switch ( $theme_slug ) {
						case 'orbital':
						default:
							wp_print_styles( 'gravity_forms_orbital_theme' );
							break;
						case 'gravity-theme':
							wp_print_styles( array( 'gform_theme', 'gform_basic' ) );
							break;
						case 'disable':
							break;
					}
				},
                1000
            );
		}

		$this->add_render_attribute( self::ELEMENT_KEY, 'class', self::ELEMENT_KEY );

		if ( 'disable' === $theme_slug ) {
			add_filter(
                'gform_disable_form_theme_css',
                $disable_theme_css = function () {
					return true;
				}
            );
		}

		$template = strtr(
            '<div {attribute}>{form}</div>',
            array(
				'{attribute}' => $this->get_render_attribute_string( self::ELEMENT_KEY ),
				'{form}'      => gravity_form( $form_id, $title, $description, false, $field_values, $ajax, $tabindex, false, $theme_slug ),
			)
        );

		echo $template; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( 'disable' === $theme_slug ) {
			remove_filter( 'gform_disable_form_theme_css', $disable_theme_css );
		}
	}

	/**
	 * Returns a list of Gravity Forms forms.
	 *
	 * @since  1.0.0
	 *
	 * @access private
	 *
	 * @return array An associative array of Gravity Forms forms.
	 */
	private function get_forms_list() {
		$forms = array(
			0 => __( 'Select a Form', 'gk-gravity-forms-elementor-widget' ),
		);

		if ( ! class_exists( 'GFAPI' ) ) {
			return $forms;
		}

		$gf_forms = GFAPI::get_forms();

		if ( is_wp_error( $gf_forms ) || empty( $gf_forms ) ) {
			return $forms;
		}

		foreach ( $gf_forms as $form ) {
			$forms[ (int) $form['id'] ] = $form['title'];
		}

		return $forms;
	}

	/**
	 * Enqueues editor styles.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue_editor_styles() {
		wp_add_inline_style(
			'elementor-editor',
			self::get_custom_icon_style()
		);
	}

	/**
	 * Returns custom icon style.
	 *
	 * @since 1.0.0
	 *
	 * @return string Custom icon CSS.
	 */
	private static function get_custom_icon_style() {
		$icon_svg         = self::get_icon_svg();
		$icon_svg_encoded = str_replace( '"', "'", $icon_svg );
		$icon_svg_url     = 'data:image/svg+xml;utf8,' . rawurlencode( $icon_svg_encoded );

		return ".elementor-element .icon .gk-gravity-forms-icon {
            width: 31px;
            height: 34px;
            display: inline-block;
            margin-top: -5px; /* Allow icon to be 5px taller */
            background-image: url('{$icon_svg_url}');
        }";
	}

	/**
	 * Returns icon SVG.
	 *
	 * @since 1.0.0
	 *
	 * @return string Icon SVG.
	 */
	protected static function get_icon_svg() {
		return '<svg viewBox="0 0 418.4 460.6"  xmlns="http://www.w3.org/2000/svg"><style>.st0{fill:#414141;stroke:#414141;stroke-width:11;stroke-miterlimit:10}</style><path class="st0" d="M209.2 15.8c11.6 0 22.4 2.6 30.5 7.3l133.7 77.2c16.8 9.6 30.5 33.3 30.5 52.8l.1 154.5c0 19.4-13.7 43.1-30.5 52.8l-133.8 77.2c-8.1 4.7-19 7.3-30.5 7.3-11.6 0-22.4-2.6-30.5-7.3L44.9 360.4c-16.8-9.7-30.5-33.4-30.5-52.8V153.1c0-19.5 13.7-43.2 30.5-52.8l133.8-77.2c8.1-4.7 19-7.3 30.5-7.3m0-1c-11.2 0-22.5 2.5-31 7.4L44.4 99.4c-17.1 9.8-31 34-31 53.7v154.5c0 19.7 13.9 43.8 31 53.7l133.8 77.2c8.5 4.9 19.7 7.4 31 7.4 11.2 0 22.5-2.5 31-7.4L374 361.3c17-9.8 31-34 31-53.7l-.1-154.5c0-19.7-13.9-43.9-31-53.7L240.2 22.2c-8.5-4.9-19.7-7.4-31-7.4z"/><path class="st0" d="M347.4 145.8v47.8H171.2c-11.3 0-19.6 3.3-26.2 10.4-14.9 15.8-22 45.9-23.1 61.2l-.1 1.1h176.5v-43.8h47.8v91.6H70.7c.1-5.1.8-28.6 5.3-55.8 4.6-28.1 14.3-66.1 34-87.2 15.9-16.8 36.6-25.4 61.6-25.4h175.8m1-.9H171.6c-25.3 0-46.3 8.7-62.3 25.7-38.6 41.1-39.6 144.6-39.6 144.6h277.4v-93.6h-49.8v43.8H122.9c1.1-16.3 8.6-45.5 22.8-60.6 6.4-6.9 14.5-10.1 25.5-10.1h177.2v-49.8z"/></svg>';
	}

	/**
	 * Returns filled icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Filled icon.
	 */
	protected static function get_filled_icon() {
		return '<svg aria-label="Gravity Forms" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 508.3 559.5" width="100%" height="100%" focusable="false" aria-hidden="true" class="dashicon dashicon-gravityforms"><style>.st0{fill:#82878c;stroke:#82878c;}</style><g><path class="st0" d="M468,109.8L294.4,9.6c-22.1-12.8-58.4-12.8-80.5,0L40.3,109.8C18.2,122.6,0,154,0,179.5V380	c0,25.6,18.1,56.9,40.3,69.7l173.6,100.2c22.1,12.8,58.4,12.8,80.5,0L468,449.8c22.2-12.8,40.3-44.2,40.3-69.7V179.6	C508.3,154,490.2,122.6,468,109.8z M399.3,244.4l-195.1,0c-11,0-19.2,3.2-25.6,10c-14.2,15.1-18.2,44.4-19.3,60.7H348v-26.4h49.9	v76.3H111.3l-1.8-23c-0.3-3.3-5.9-80.7,32.8-121.9c16.1-17.1,37.1-25.8,62.4-25.8h194.7V244.4z"></path></g></svg>';
	}
}
