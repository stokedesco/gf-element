<?php
/**
 * Class Gravity_Forms.
 *
 * @package StokeGFElementor
 */

namespace StokeGFElementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Widget_Base;
use GFAPI;

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
}

if ( ! class_exists( Widget_Base::class ) ) {
        return;
}

/**
 * Gravity Forms widget for Elementor.
 *
 * Integrates Gravity Forms with Elementor page builder.
 *
 * @since 1.0.0
 */
class Widget extends Widget_Base {
	const ELEMENT_KEY = 'sge-gravity-form';

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
		return 'stoke_elementor_gravity_form';
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
		return __( 'Stoke GF Elementor', 'stoke-gf-elementor' );
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
                        'stoke gf elementor',
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
		return 'sge-gravity-forms-icon';
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
               $inputs = implode(
                       ', ',
                       array(
                               '{{WRAPPER}} .' . self::ELEMENT_KEY . ' input[type="text"]',
                               '{{WRAPPER}} .' . self::ELEMENT_KEY . ' input[type="email"]',
                               '{{WRAPPER}} .' . self::ELEMENT_KEY . ' input[type="url"]',
                               '{{WRAPPER}} .' . self::ELEMENT_KEY . ' input[type="tel"]',
                               '{{WRAPPER}} .' . self::ELEMENT_KEY . ' input[type="search"]',
                               '{{WRAPPER}} .' . self::ELEMENT_KEY . ' input[type="password"]',
'{{WRAPPER}} .' . self::ELEMENT_KEY . ' input[type="number"]',
'{{WRAPPER}} .' . self::ELEMENT_KEY . ' select',
)
);

$textarea = '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gfield .ginput_container textarea';

$checks_radios = implode(
        ', ',
        array(
                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .ginput_container_checkbox input[type="checkbox"]',
                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .ginput_container_radio input[type="radio"]',
        )
);

$checks_radios_checked = implode(
        ', ',
        array(
                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .ginput_container_checkbox input[type="checkbox"]:checked',
                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .ginput_container_radio input[type="radio"]:checked',
        )
);

$checks_radios_unchecked = implode(
        ', ',
        array(
                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .ginput_container_checkbox input[type="checkbox"]:not(:checked)',
                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .ginput_container_radio input[type="radio"]:not(:checked)',
        )
);

$checks_radios_labels = implode(
        ', ',
        array(
'{{WRAPPER}} .' . self::ELEMENT_KEY . ' .ginput_container_checkbox label',
'{{WRAPPER}} .' . self::ELEMENT_KEY . ' .ginput_container_radio label',
)
);
                $file_upload_button = implode(
                        ', ',
                        array(
                                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_button_select_files',
                                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' input[type="file"]::-webkit-file-upload-button',
                        )
                );

                $buttons = implode(
                        ', ',
                        array(
                                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_button',
                                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_next_button',
                                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_previous_button',
                                '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_save_link',
                        )
                );


               $this->start_controls_section(
                       'section_form',
                       array(
                               'label' => __( 'Form Settings', 'stoke-gf-elementor' ),
                       )
               );

		$this->add_control(
			'form_id',
			array(
				'label'       => __( 'Select Form', 'stoke-gf-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_forms_list(),
				'default'     => '0',
				'label_block' => true,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'        => __( 'Title', 'stoke-gf-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'stoke-gf-elementor' ),
				'label_off'    => __( 'Hide', 'stoke-gf-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'        => __( 'Description', 'stoke-gf-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'stoke-gf-elementor' ),
				'label_off'    => __( 'Hide', 'stoke-gf-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'ajax',
			array(
				'label'        => __( 'Use Ajax', 'stoke-gf-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'stoke-gf-elementor' ),
				'label_off'    => __( 'No', 'stoke-gf-elementor' ),
				'return_value' => 'yes',
			)
		);


		$this->end_controls_section();

              $this->start_controls_section(
                      'section_heading',
                       array(
                               'label' => __( 'Form Heading & Description', 'stoke-gf-elementor' ),
                               'tab'   => Controls_Manager::TAB_STYLE,
                       )
               );

               $this->add_group_control(
                       Group_Control_Typography::get_type(),
                       array(
                               'name'     => 'heading_title_typography',
                               'label'    => __( 'Title Typography', 'stoke-gf-elementor' ),
                               'selector' => '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_heading .gform_title',
                       )
               );

               $this->add_control(
                       'heading_title_color',
                       array(
                               'label'     => __( 'Title Color', 'stoke-gf-elementor' ),
                               'type'      => Controls_Manager::COLOR,
                               'selectors' => array(
                                       '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_heading .gform_title' => 'color: {{VALUE}};',
                               ),
                       )
               );

               $this->add_group_control(
                       Group_Control_Typography::get_type(),
                       array(
                               'name'     => 'heading_description_typography',
                               'label'    => __( 'Description Typography', 'stoke-gf-elementor' ),
                               'selector' => '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_heading .gform_description',
                       )
               );

               $this->add_control(
                       'heading_description_color',
                       array(
                               'label'     => __( 'Description Color', 'stoke-gf-elementor' ),
                               'type'      => Controls_Manager::COLOR,
                               'selectors' => array(
                                       '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_heading .gform_description' => 'color: {{VALUE}};',
                               ),
                       )
               );

               $this->end_controls_section();

                $this->start_controls_section(
                        'section_form_style',
                        array(
                                'label' => __( 'Form', 'stoke-gf-elementor' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        )
                );

                $this->add_control(
                        'form_background_color',
                        array(
                                'label'     => __( 'Background Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY => 'background-color: {{VALUE}};',
                                ),
                        )
                );

		$this->add_responsive_control(
			'form_alignment',
			array(
				'label'     => __( 'Alignment', 'stoke-gf-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'stoke-gf-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'stoke-gf-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'stoke-gf-elementor' ),
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
				'label'      => __( 'Max Width', 'stoke-gf-elementor' ),
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
				'label'      => __( 'Margin', 'stoke-gf-elementor' ),
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
				'label'      => __( 'Padding', 'stoke-gf-elementor' ),
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
				'label'      => __( 'Border Radius', 'stoke-gf-elementor' ),
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



               $this->end_controls_section();

               $this->start_controls_section(
                       'section_form_spacing',
                       array(
                               'label' => __( 'Form Spacing', 'stoke-gf-elementor' ),
                               'tab'   => Controls_Manager::TAB_STYLE,
                       )
               );

               $this->add_responsive_control(
                       'field_row_gap',
                       array(
                               'label'      => __( 'Row Gap', 'stoke-gf-elementor' ),
                               'type'       => Controls_Manager::SLIDER,
                               'size_units' => array( 'px', 'em' ),
                               'range'      => array(
                                       'px' => array(
                                               'min' => 0,
                                               'max' => 100,
                                       ),
                                       'em' => array(
                                               'min' => 0,
                                               'max' => 10,
                                       ),
                               ),
                               'selectors'  => array(
                                       '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_fields' => 'row-gap: {{SIZE}}{{UNIT}};',
                                       '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gfield'       => 'margin-bottom: {{SIZE}}{{UNIT}};',
                               ),
                       )
               );

               $this->add_responsive_control(
                       'field_column_gap',
                       array(
                               'label'      => __( 'Column Gap', 'stoke-gf-elementor' ),
                               'type'       => Controls_Manager::SLIDER,
                               'size_units' => array( 'px', 'em' ),
                               'range'      => array(
                                       'px' => array(
                                               'min' => 0,
                                               'max' => 100,
                                       ),
                                       'em' => array(
                                               'min' => 0,
                                               'max' => 10,
                                       ),
                               ),
                               'selectors'  => array(
                                       '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_fields' => 'column-gap: {{SIZE}}{{UNIT}};',
                               ),
                       )
               );

               $this->add_responsive_control(
                       'field_inner_padding',
                       array(
                               'label'      => __( 'Field Inner Padding', 'stoke-gf-elementor' ),
                               'type'       => Controls_Manager::DIMENSIONS,
                               'size_units' => array( 'px', 'em', '%' ),
                               'selectors'  => array(
                                       '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gfield' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                               ),
                       )
               );

               $this->end_controls_section();

               $this->section_progress();

               $this->start_controls_section(
                       'section_labels',
                       array(
                               'label' => __( 'Labels', 'stoke-gf-elementor' ),
                               'tab'   => Controls_Manager::TAB_STYLE,
                       )
               );

               $this->add_group_control(
                       Group_Control_Typography::get_type(),
                       array(
                               'name'     => 'label_typography',
                               'selector' => '{{WRAPPER}} .sge-gravity-form .gfield_label',
                       )
               );

               $this->add_control(
                       'label_color',
                       array(
                               'label'     => __( 'Text Color', 'stoke-gf-elementor' ),
                               'type'      => Controls_Manager::COLOR,
                               'selectors' => array(
                                       '{{WRAPPER}} .sge-gravity-form .gfield_label' => 'color: {{VALUE}};',
                               ),
                       )
               );

               $this->add_control(
                       'required_color',
                       array(
                               'label'     => __( 'Required Color', 'stoke-gf-elementor' ),
                               'type'      => Controls_Manager::COLOR,
                               'selectors' => array(
                                       '{{WRAPPER}} .sge-gravity-form .gfield_required' => 'color: {{VALUE}};',
                               ),
                       )
               );

               $this->end_controls_section();

               $this->start_controls_section(
                       'section_inputs',
                       array(
                               'label' => __( 'Inputs', 'stoke-gf-elementor' ),
                               'tab'   => Controls_Manager::TAB_STYLE,
                       )
               );

               $this->add_group_control(
                       Group_Control_Typography::get_type(),
                       array(
                               'name'     => 'inputs_typography',
                               'selector' => $inputs,
                       )
               );

               $this->add_control(
                       'inputs_text_color',
                       array(
                               'label'     => __( 'Text Color', 'stoke-gf-elementor' ),
                               'type'      => Controls_Manager::COLOR,
                               'selectors' => array(
                                       $inputs => 'color: {{VALUE}};',
                               ),
                       )
               );

               $placeholder_selectors = implode(
                       ', ',
                       array(
                               str_replace( ', ', '::placeholder, ', $inputs ) . '::placeholder',
                               str_replace( ', ', '::-webkit-input-placeholder, ', $inputs ) . '::-webkit-input-placeholder',
                               str_replace( ', ', ':-ms-input-placeholder, ', $inputs ) . ':-ms-input-placeholder',
                               str_replace( ', ', '::-ms-input-placeholder, ', $inputs ) . '::-ms-input-placeholder',
                               str_replace( ', ', ':-moz-placeholder, ', $inputs ) . ':-moz-placeholder',
                               str_replace( ', ', '::-moz-placeholder, ', $inputs ) . '::-moz-placeholder',
                       )
               );

               $this->add_control(
                       'inputs_placeholder_color',
                       array(
                               'label'     => __( 'Placeholder Color', 'stoke-gf-elementor' ),
                               'type'      => Controls_Manager::COLOR,
                               'selectors' => array(
                                       $placeholder_selectors => 'color: {{VALUE}};',
                               ),
                       )
               );

               $this->add_group_control(
                       Group_Control_Background::get_type(),
                       array(
                               'name'     => 'inputs_background',
                               'selector' => $inputs,
                       )
               );

               $this->add_group_control(
                       Group_Control_Border::get_type(),
                       array(
                               'name'     => 'inputs_border',
                               'selector' => $inputs,
                       )
               );

               $this->add_responsive_control(
                       'inputs_border_radius',
                       array(
                               'label'      => __( 'Border Radius', 'stoke-gf-elementor' ),
                               'type'       => Controls_Manager::DIMENSIONS,
                               'size_units' => array( 'px', 'em', '%' ),
                               'selectors'  => array(
                                       $inputs => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                               ),
                       )
               );

$this->add_responsive_control(
'inputs_padding',
array(
'label'      => __( 'Padding', 'stoke-gf-elementor' ),
'type'       => Controls_Manager::DIMENSIONS,
'size_units' => array( 'px', 'em', '%' ),
'selectors'  => array(
$inputs => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
),
)
);

$this->end_controls_section();

			$this->start_controls_section(
			'section_checks_radios',
			array(
			'label' => __( 'Checkboxes & Radios', 'stoke-gf-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			)
			);
			$this->add_group_control(
			        Group_Control_Border::get_type(),
			        array(
			                'name'     => 'checks_radios_inactive_border',
			                'selector' => $checks_radios_unchecked,
			        )
			);
			
			$this->add_control(
			        'checks_radios_inactive_background_color',
			        array(
			                'label'     => __( 'Inactive Background Color', 'stoke-gf-elementor' ),
			                'type'      => Controls_Manager::COLOR,
			                'selectors' => array(
			                        $checks_radios_unchecked => 'background-color: {{VALUE}};',
			                ),
			        )
			);
			
			$this->add_control(
			        'checks_radios_inactive_color',
			        array(
			                'label'     => __( 'Inactive Color', 'stoke-gf-elementor' ),
			                'type'      => Controls_Manager::COLOR,
			                'selectors' => array(
			                        $checks_radios_unchecked => 'color: {{VALUE}};',
			                ),
			        )
			);
			
			$this->add_group_control(
			        Group_Control_Border::get_type(),
			        array(
			                'name'     => 'checks_radios_active_border',
			                'selector' => $checks_radios_checked,
			        )
			);
			
			$this->add_control(
			        'checks_radios_active_background_color',
			        array(
			                'label'     => __( 'Active Background Color', 'stoke-gf-elementor' ),
			                'type'      => Controls_Manager::COLOR,
			                'selectors' => array(
			                        $checks_radios_checked => 'background-color: {{VALUE}};',
			                ),
			        )
			);
			
			$this->add_control(
			        'checks_radios_active_color',
			        array(
			                'label'     => __( 'Active Color', 'stoke-gf-elementor' ),
			                'type'      => Controls_Manager::COLOR,
			                'selectors' => array(
			                        $checks_radios_checked => 'color: {{VALUE}};',
			                ),
			        )
			);
			
			$this->add_responsive_control(
			        'checks_radios_border_radius',
			        array(
			                'label'      => __( 'Border Radius', 'stoke-gf-elementor' ),
			                'type'       => Controls_Manager::DIMENSIONS,
			                'size_units' => array( 'px', 'em', '%' ),
			                'selectors'  => array(
			                        $checks_radios => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ),
			        )
			);
			
			$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
			'name'     => 'checks_radios_label_typography',
			'selector' => $checks_radios_labels,
			)
			);
			
			$this->add_control(
			'checks_radios_label_color',
			array(
			'label'     => __( 'Label Color', 'stoke-gf-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
			$checks_radios_labels => 'color: {{VALUE}};',
			),
			)
			);
			
			$this->add_responsive_control(
			'checks_radios_label_spacing',
			array(
			'label'      => __( 'Label Spacing', 'stoke-gf-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em' ),
			'range'      => array(
			'px' => array(
			'min' => 0,
			'max' => 50,
			),
			'em' => array(
			'min' => 0,
			'max' => 5,
			),
			),
			'selectors'  => array(
			$checks_radios_labels => 'margin-left: {{SIZE}}{{UNIT}};',
			),
			)
			);
			
			$this->end_controls_section();

$this->start_controls_section(
'section_textarea',
array(
'label' => __( 'Textarea', 'stoke-gf-elementor' ),
'tab'   => Controls_Manager::TAB_STYLE,
)
);

$this->add_group_control(
Group_Control_Typography::get_type(),
array(
'name'     => 'textarea_typography',
'selector' => $textarea,
)
);

$this->add_control(
'textarea_text_color',
array(
'label'     => __( 'Text Color', 'stoke-gf-elementor' ),
'type'      => Controls_Manager::COLOR,
'selectors' => array(
$textarea => 'color: {{VALUE}};',
),
)
);

$textarea_placeholder_selectors = implode(
', ',
array(
$textarea . '::placeholder',
$textarea . '::-webkit-input-placeholder',
$textarea . ':-ms-input-placeholder',
$textarea . '::-ms-input-placeholder',
$textarea . ':-moz-placeholder',
$textarea . '::-moz-placeholder',
)
);

$this->add_control(
'textarea_placeholder_color',
array(
'label'     => __( 'Placeholder Color', 'stoke-gf-elementor' ),
'type'      => Controls_Manager::COLOR,
'selectors' => array(
$textarea_placeholder_selectors => 'color: {{VALUE}};',
),
)
);

$this->add_group_control(
Group_Control_Background::get_type(),
array(
'name'     => 'textarea_background',
'selector' => $textarea,
)
);

$this->add_group_control(
Group_Control_Border::get_type(),
array(
'name'     => 'textarea_border',
'selector' => $textarea,
)
);

$this->add_responsive_control(
'textarea_border_radius',
array(
'label'      => __( 'Border Radius', 'stoke-gf-elementor' ),
'type'       => Controls_Manager::DIMENSIONS,
'size_units' => array( 'px', 'em', '%' ),
'selectors'  => array(
$textarea => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
),
)
);

$this->add_responsive_control(
'textarea_padding',
array(
'label'      => __( 'Padding', 'stoke-gf-elementor' ),
'type'       => Controls_Manager::DIMENSIONS,
'size_units' => array( 'px', 'em', '%' ),
'selectors'  => array(
$textarea => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
),
)
);

$this->end_controls_section();

                $this->start_controls_section(
                        'section_buttons',
                        array(
                                'label' => __( 'Buttons', 'stoke-gf-elementor' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        )
                );

                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                                'name'     => 'buttons_typography',
                                'selector' => $buttons,
                        )
                );

                $this->add_control(
                        'buttons_text_color',
                        array(
                                'label'     => __( 'Text Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        $buttons => 'color: {{VALUE}};',
                                ),
                        )
                );

                $this->add_control(
                        'buttons_hover_text_color',
                        array(
                                'label'     => __( 'Hover Text Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        str_replace( ', ', ':hover, ', $buttons ) . ':hover' => 'color: {{VALUE}};',
                                ),
                        )
                );

                $this->add_group_control(
                        Group_Control_Background::get_type(),
                        array(
                                'name'     => 'buttons_background',
                                'selector' => $buttons,
                        )
                );

                $this->add_group_control(
                        Group_Control_Border::get_type(),
                        array(
                                'name'     => 'buttons_border',
                                'selector' => $buttons,
                        )
                );

               $this->add_responsive_control(
                       'buttons_border_radius',
                       array(
                               'label'      => __( 'Border Radius', 'stoke-gf-elementor' ),
                               'type'       => Controls_Manager::DIMENSIONS,
                               'size_units' => array( 'px', 'em', '%' ),
                               'selectors'  => array(
                                       $buttons => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                               ),
                       )
               );

                $this->add_responsive_control(
                        'buttons_padding',
                        array(
                                'label'      => __( 'Padding', 'stoke-gf-elementor' ),
                                'type'       => Controls_Manager::DIMENSIONS,
                                'size_units' => array( 'px', 'em', '%' ),
                                'selectors'  => array(
                                        $buttons => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ),
                        )
                );

                $this->end_controls_section();

                $this->start_controls_section(
                        'section_file_upload',
                        array(
                                'label' => __( 'File Upload Button', 'stoke-gf-elementor' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ),
                );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'file_upload_typography',
				'selector' => $file_upload_button,
			),
		);

		$this->add_control(
			'file_upload_text_color',
			array(
				'label'     => __( 'Text Color', 'stoke-gf-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$file_upload_button => 'color: {{VALUE}};',
				),
			),
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'file_upload_background',
				'selector' => $file_upload_button,
			),
		);

                $this->add_group_control(
                        Group_Control_Border::get_type(),
                        array(
                                'name'     => 'file_upload_border',
                                'selector' => $file_upload_button,
                        ),
                );

               $this->add_responsive_control(
                       'file_upload_border_radius',
                       array(
                               'label'      => __( 'Border Radius', 'stoke-gf-elementor' ),
                               'type'       => Controls_Manager::DIMENSIONS,
                               'size_units' => array( 'px', 'em', '%' ),
                               'selectors'  => array(
                                       $file_upload_button => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                               ),
                       )
               );

                $this->end_controls_section();

                $this->start_controls_section(
                        'section_messages',
                        array(
                                'label' => __( 'Messages', 'stoke-gf-elementor' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        )
                );

                $this->add_control(
                        'validation_error_text_color',
                        array(
                                'label'     => __( 'Validation Banner Text Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .validation_error' => 'color: {{VALUE}};',
                                ),
                        )
                );

                $this->add_control(
                        'validation_error_background_color',
                        array(
                                'label'     => __( 'Validation Banner Background Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .validation_error' => 'background-color: {{VALUE}};',
                                ),
                        )
                );

                $this->add_control(
                        'field_error_text_color',
                        array(
                                'label'     => __( 'Field Error Text Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gfield_validation_message, {{WRAPPER}} .' . self::ELEMENT_KEY . ' .validation_message' => 'color: {{VALUE}};',
                                ),
                        )
                );

                $this->add_control(
                        'confirmation_background_color',
                        array(
                                'label'     => __( 'Confirmation Background Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gform_confirmation_message' => 'background-color: {{VALUE}};',
                                ),
                        )
                );

                $this->end_controls_section();

               $this->start_controls_section(
                       'section_form_advanced',
                       array(
                               'label' => __( 'Advanced', 'stoke-gf-elementor' ),
                               'tab'   => Controls_Manager::TAB_ADVANCED,
                       )
               );

               $this->add_control(
                       'field_values',
                       array(
                               'label'       => __( 'Field Values', 'stoke-gf-elementor' ),
                               'type'        => Controls_Manager::TEXTAREA,
                               'default'     => '',
                               // translators: Do not translate placeholders in square brackets. They are placeholders.
                               'description' => strtr(
                    __( 'Enter field values in the format: [example]. [link]Learn more.[/link]', 'stoke-gf-elementor' ),
                    array(
                                               '[example]' => '<code>input_1=First Name&amp;input_2=Last Name</code>',
                                               '[link]'    => '<a href="https://docs.gravityforms.com/allow-field-to-be-populated-dynamically/#h-block" target="_blank">',
                                               '[/link]'   => '<span class="screen-reader-text">' . esc_attr__( '(This link opens in a new window.)', 'stoke-gf-elementor' ) . '</span></a>',
                                       )
               ),
                       )
               );

               $this->add_control(
                       'tabindex',
                       array(
                               'label'       => __( 'Tab Index', 'stoke-gf-elementor' ),
                               'type'        => Controls_Manager::NUMBER,
                               'default'     => 0,
                               'min'         => 0,
                               'step'        => 1,
                               'description' => __( 'Set the starting tabindex for the form.', 'stoke-gf-elementor' ),
                       )
               );

              $this->end_controls_section();

        }

        /**
         * Registers progress bar style controls.
         *
         * @since 1.0.0
         *
         * @access private
         */
        private function section_progress() {
                $this->start_controls_section(
                        'section_progress',
                        array(
                                'label' => __( 'Progress Bar', 'stoke-gf-elementor' ),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        )
                );

                $this->add_control(
                        'progress_track_color',
                        array(
                                'label'     => __( 'Track Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gf_progressbar' => 'background-color: {{VALUE}};',
                                ),
                        )
                );

                $this->add_control(
                        'progress_fill_color',
                        array(
                                'label'     => __( 'Fill Color', 'stoke-gf-elementor' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gf_progressbar_percentage' => 'background-color: {{VALUE}};',
                                ),
                        )
                );

                $this->add_responsive_control(
                        'progress_height',
                        array(
                                'label'      => __( 'Height', 'stoke-gf-elementor' ),
                                'type'       => Controls_Manager::SLIDER,
                                'size_units' => array( 'px', 'em' ),
                                'range'      => array(
                                        'px' => array(
                                                'min' => 0,
                                                'max' => 100,
                                        ),
                                        'em' => array(
                                                'min' => 0,
                                                'max' => 10,
                                        ),
                                ),
                                'selectors'  => array(
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gf_progressbar'            => 'height: {{SIZE}}{{UNIT}};',
                                        '{{WRAPPER}} .' . self::ELEMENT_KEY . ' .gf_progressbar_percentage' => 'height: {{SIZE}}{{UNIT}};',
                                ),
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
			echo esc_html__( 'Gravity Forms is not installed or activated.', 'stoke-gf-elementor' );

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
						'{message}' => esc_html__( 'Select a form from the widget settings.', 'stoke-gf-elementor' ),
					)
                );

				echo $template; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			return;
		}

		$form = GFAPI::get_form( $form_id );

		if ( ! $form ) {
			echo esc_html__( 'The selected form does not exist.', 'stoke-gf-elementor' );

			return;
		}

                $title       = 'yes' === $settings['title'];
                $description = 'yes' === $settings['description'];
                $tabindex    = (int) $settings['tabindex'];

                $field_values = null;

		if ( ! empty( $settings['field_values'] ) ) {
			$field_values = array();

			parse_str( $settings['field_values'], $field_values );

			$field_values = array_map( 'esc_html', $field_values );
		}

                $ajax = 'yes' === $settings['ajax'];

                if ( Plugin::$instance->editor->is_edit_mode() ) {
                        $ajax = true; // Force-enable Ajax in the editor to prevent JS errors, caused in part by the $form_scripts_body contents.
                }

                $this->add_render_attribute( self::ELEMENT_KEY, 'class', 'sge-gravity-form' );

                $template = strtr(
            '<div {attribute}>{form}</div>',
            array(
                                '{attribute}' => $this->get_render_attribute_string( self::ELEMENT_KEY ),
                                '{form}'      => gravity_form( $form_id, $title, $description, false, $field_values, $ajax, $tabindex, false ),
                        )
        );

                echo $template; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
			0 => __( 'Select a Form', 'stoke-gf-elementor' ),
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

		return ".elementor-element .icon .sge-gravity-forms-icon {
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
