<?php
namespace EnvisionBlocks\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
add_filter( 'wpcf7_autop_or_not', '__return_false' );

class Contact_Form_7 extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-contact-form-7', ENVISION_BLOCKS_URL . 'assets/css/contact-form-7.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-contact-form-7', 'rtl', 'replace' );
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'envision-blocks-contact-form-7';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Contact Form 7', 'envision-blocks' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal envision-blocks-icon';
	}

	/**
	 * Retrieve the list of styles the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'envision-blocks-contact-form-7' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'envision-blocks-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'form', 'contact' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->section_form();

		$this->section_form_style();
		$this->section_fields_style();
		$this->section_button_style();
		$this->section_dark_mode_style();
	}


	/**
	 * Content > Form.
	 */
	private function section_form() {
		$this->start_controls_section(
			'section_form',
			array(
				'label' => esc_html__( 'Contact Form 7', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$forms = $this->get_available_cf7forms();

		if ( ! class_exists( '\WPCF7' ) ) {
			$this->add_control(
				'contact_form_7_not_installed',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . esc_html__( 'Contact Form 7 is not installed.', 'envision-blocks' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">plugins page</a> and install it.', 'envision-blocks' ), admin_url( 'plugins.php' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		if ( ! empty( $forms ) ) {
			$this->add_control(
				'contact_form_7_posts',
				array(
					'label'       => esc_html__( 'Form', 'envision-blocks' ),
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Contact Forms screen</a> to manage your forms.', 'envision-blocks' ), admin_url( '?page=wpcf7' ) ),
					'type'        => Controls_Manager::SELECT,
					'options'     => $this->get_available_cf7forms(),
				)
			);
		} else {
			$this->add_control(
				'contact_form_7_posts',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . esc_html__( 'There are no forms in your site.', 'envision-blocks' ) . '</strong><br>' . sprintf( esc_html__( 'Go to the <a href="%s" target="_blank">Contact Forms screen</a> to create one.', 'envision-blocks' ), admin_url( '?page=wpcf7' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->end_controls_section();
	}


	/**
	 * Style > Form.
	 */
	private function section_form_style() {
		$this->start_controls_section(
			'section_form_style',
			array(
				'label' => esc_html__( 'Form', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'columns_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 40,
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-row .envision-blocks-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}} / 2 ); padding-left: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .envision-blocks-field-row' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
				),
			)
		);

		$this->add_responsive_control(
			'rows_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .envision-blocks-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_label',
			array(
				'label'     => esc_html__( 'Label', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_spacing',
			array(
				'label'     => esc_html__( 'Label Spacing', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'   => array(
					'size' => 6,
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field > label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field > label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mark_required_color',
			array(
				'label'     => esc_html__( 'Mark Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field > label abbr' => 'color: {{COLOR}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-field > label',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Fields.
	 */
	private function section_fields_style() {
		$this->start_controls_section(
			'section_fields_style',
			array(
				'label' => esc_html__( 'Fields', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'fields_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input input::-webkit-input-placeholder, {{WRAPPER}} .envision-blocks-field-type-textarea textarea::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .envision-blocks-field-type-input input:-moz-placeholder, {{WRAPPER}} .envision-blocks-field-type-textarea textarea:-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .envision-blocks-field-type-input input::-moz-placeholder, {{WRAPPER}} .envision-blocks-field-type-textarea textarea::-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .envision-blocks-field-type-input input:-ms-input-placeholder, {{WRAPPER}} .envision-blocks-field-type-textarea textarea:-ms-input-placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fields_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input input, {{WRAPPER}} .envision-blocks-field-type-textarea textarea, {{WRAPPER}} .envision-blocks-field-type-input select' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fields_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-field-type-input input, {{WRAPPER}} .envision-blocks-field-type-textarea textarea, {{WRAPPER}} .envision-blocks-field-type-input select',
			)
		);

		$this->add_control(
			'fields_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input input, {{WRAPPER}} .envision-blocks-field-type-textarea textarea, {{WRAPPER}} .envision-blocks-field-type-input select' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fields_border_focus_color',
			array(
				'label'     => esc_html__( 'Border Focus Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input input:focus, {{WRAPPER}} .envision-blocks-field-type-textarea textarea:focus, {{WRAPPER}} .envision-blocks-field-type-input select:focus' => 'border-color: {{VALUE}}; outline: 0;',
				),
			)
		);

		$this->add_control(
			'fields_background_focus_color',
			array(
				'label'     => esc_html__( 'Background Focus Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input input:focus, {{WRAPPER}} .envision-blocks-field-type-textarea textarea:focus, {{WRAPPER}} .envision-blocks-field-type-input select:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'fields_border',
				'label'     => esc_html__( 'Border', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .envision-blocks-field-type-input input, {{WRAPPER}} .envision-blocks-field-type-textarea textarea, {{WRAPPER}} .envision-blocks-field-type-input select',
			)
		);

		$this->add_control(
			'fields_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input input, {{WRAPPER}} .envision-blocks-field-type-textarea textarea, {{WRAPPER}} .envision-blocks-field-type-input select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'fields_padding',
			array(
				'label'     => esc_html__( 'Text Padding', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input input, {{WRAPPER}} .envision-blocks-field-type-textarea textarea, {{WRAPPER}} .envision-blocks-field-type-input select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'fields_height',
			array(
				'label'     => esc_html__( 'Fields height', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 46,
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input input, {{WRAPPER}} .envision-blocks-field-type-input select' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Button.
	 */
	private function section_button_style() {
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Button', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'cf7_button_top_space',
			array(
				'label'     => esc_html__( 'Top Space', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'cf7_button_align',
			array(
				'label'        => esc_html__( 'Align', 'envision-blocks' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'options'      => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Stretch', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'devices'      => array( 'desktop', 'tablet', 'mobile' ),
				'prefix_class' => 'envision-blocks-contact-form-7-button--align-',
			)
		);

		// Button Size
		$this->add_control(
			'cf7_button_size',
			array(
				'label'        => esc_html__( 'Size', 'envision-blocks' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'xl',
				'options'      => array(
					'xs' => esc_html__( 'Extra Small', 'elementor' ),
					'sm' => esc_html__( 'Small', 'elementor' ),
					'md' => esc_html__( 'Medium', 'elementor' ),
					'lg' => esc_html__( 'Large', 'elementor' ),
					'xl' => esc_html__( 'Extra Large', 'elementor' ),
				),
				'prefix_class' => 'envision-blocks-contact-form-7-button--size-',
			)
		);

		// BUTTON HOVER TABS

		$this->start_controls_tabs(
			'button_tabs'
		);

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit input' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-field-type-submit input',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .envision-blocks-field-type-submit input',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_text_padding',
			array(
				'label'     => esc_html__( 'Text Padding', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit input:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit input:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit input:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'button_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// END BUTTON HOVER TABS

		$this->end_controls_section();
	}

	/**
	 * Style > Dark Mode.
	 */
	private function section_dark_mode_style() {
		$this->start_controls_section(
			'section_dark_mode_style',
			array(
				'label' => esc_html__( 'Dark Mode', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_label_dark_mode',
			array(
				'label'     => esc_html__( 'Label', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color_dark_mode',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field > label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mark_required_color_dark_mode',
			array(
				'label'     => esc_html__( 'Mark Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field > label abbr' => 'color: {{COLOR}};',
				),
			)
		);

		$this->add_control(
			'heading_fields_dark_mode',
			array(
				'label'     => esc_html__( 'Fields', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'fields_background_color_dark_mode',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-input input, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-textarea textarea, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-input select' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fields_border_color_dark_mode',
			array(
				'label'     => esc_html__( 'Border Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-input input, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-textarea textarea, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-input select' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fields_border_focus_color_dark_mode',
			array(
				'label'     => esc_html__( 'Border Focus Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-input input:focus, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-textarea textarea:focus, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-input select:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fields_background_focus_color_dark_mode',
			array(
				'label'     => esc_html__( 'Background Focus Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-input input:focus, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-textarea textarea:focus, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-input select:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading_button_dark_mode',
			array(
				'label'     => esc_html__( 'Button', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// BUTTON HOVER TABS
		$this->start_controls_tabs(
			'button_tabs_dark_mode'
		);

		$this->start_controls_tab(
			'tab_button_normal_dark_mode',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'button_background_color_dark_mode',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-submit input' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color_dark_mode',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-submit input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_border_color_dark_mode',
			array(
				'label'     => esc_html__( 'Border Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-submit input' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_dark_mode',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'button_hover_background_color_dark_mode',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-submit input:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_text_color_dark_mode',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-submit input:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color_dark_mode',
			array(
				'label'     => esc_html__( 'Border Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-field-type-submit input:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'button_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	/**
	 * Get the array of available CF7 Form Posts.
	 */
	public function get_available_cf7forms() {
		$wpcf7_posts = get_posts(
			array(
				'post_type' => 'wpcf7_contact_form',
				'showposts' => 999,
			)
		);
		$options     = array();
		if ( ! empty( $wpcf7_posts ) && ! is_wp_error( $wpcf7_posts ) ) {
			foreach ( $wpcf7_posts as $post ) {
				$options[ $post->ID ] = $post->post_title;
			}
			return $options;
		}
	}


	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! isset( $settings['contact_form_7_posts'] ) ) {
			return;
		}

		?>
			<div class="envision-blocks-contact-form-7">
				<?php echo do_shortcode( '[contact-form-7 id="' . $settings['contact_form_7_posts'] . '"]' ); ?>
			</div>
		<?php
	}
}
