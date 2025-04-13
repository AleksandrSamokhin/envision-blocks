<?php

namespace EnvisionBlocks\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Exit if accessed directly.
class Mailchimp_Form extends Widget_Base {
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style(
			'envision-blocks-mailchimp-form',
			ENVISION_BLOCKS_URL . 'assets/css/mailchimp-form.min.css',
			array(),
			ENVISION_BLOCKS_VERSION
		);
		wp_style_add_data( 'envision-blocks-mailchimp-form', 'rtl', 'replace' );
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
		return 'envision-blocks-mailchimp-form';
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
		return esc_html__( 'Mailchimp Form', 'envision-blocks' );
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
		return 'eicon-form-vertical envision-blocks-icon';
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
		return array( 'envision-blocks-mailchimp-form' );
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
		return array( 'form', 'mailchimp' );
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
		$this->section_shortcode();
		$this->section_form_style();
		$this->section_fields_style();
		$this->section_button_style();
		$this->section_consent_checkbox_style();
	}

	/**
	 * Content > Shortcode.
	 */
	private function section_shortcode() {
		$this->start_controls_section(
			'section_shortcode',
			array(
				'label' => esc_html__( 'Mailchimp Shortcode', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		if ( ! function_exists( 'mc4wp' ) ) {
			$this->add_control(
				'mc4wp_not_installed',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . esc_html__( 'Mailchimp for WordPress is not installed.', 'envision-blocks' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">plugins page</a> and install it.', 'envision-blocks' ), admin_url( 'plugins.php' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}
		$this->add_control(
			'shortcode',
			array(
				'label'       => esc_html__( 'Mailchimp Shortcode', 'envision-blocks' ),
				'description' => sprintf( esc_html__( 'Paste the shortcode generated from %1$sMailchimp for WP%2$s', 'envision-blocks' ), '<a href="' . admin_url( 'admin.php?page=mailchimp-for-wp-forms' ) . '" target="_blank">', '</a>' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'separator'   => 'after',
				'default'     => '[mc4wp_form id="530"]',
				'placeholder' => esc_html__( '[mc4wp_form id="530"]' ),
			)
		);
		if ( function_exists( 'mc4wp' ) ) {
			$this->add_control(
				'form_code_example',
				array(
					'label'       => esc_html__( 'Form Code Example', 'envision-blocks' ),
					'description' => sprintf( esc_html__( 'Paste this code sample into your form code field in %1$sMailchimp for WP%2$s', 'envision-blocks' ), '<a href="' . admin_url( 'admin.php?page=mailchimp-for-wp-forms' ) . '" target="_blank">', '</a>' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => '<div class="envision-blocks-newsletter">
		<div class="envision-blocks-form-group envision-blocks-field-group envision-blocks-field-type-input">  
			<input type="email" name="EMAIL" placeholder="Email" required />
		</div>

		<div class="envision-blocks-form-group envision-blocks-newsletter__submit envision-blocks-field-group envision-blocks-field-type-submit">      
			<input type="submit" value="Sign up" />
		</div>

		<label class="envision-blocks-consent-checkbox">
      <input type="checkbox" name="AGREE_TO_TERMS" value="1" required=""> <a href="#" target="_blank">I have read and agree to the terms &amp; conditions</a>
    </label>
</div>',
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
		$this->add_control(
			'button_inside',
			array(
				'label'   => esc_html__( 'Button inside', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
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
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}} / 2 ); padding-left: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .mc4wp-form-fields' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
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
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mc4wp-form-fields' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				),
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
		$this->add_responsive_control(
			'fields_width',
			array(
				'label'          => esc_html__( 'Fields Width', 'envision-blocks' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'        => array(
					'unit' => '%',
					'size' => 100,
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'selectors'      => array(
					'{{WRAPPER}} .envision-blocks-field-type-input' => 'flex-basis: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'fields_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input > input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .envision-blocks-field-type-input > input:-moz-placeholder'           => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .envision-blocks-field-type-input > input::-moz-placeholder'          => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .envision-blocks-field-type-input > input:-ms-input-placeholder'      => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .envision-blocks-field-type-input > input' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fields_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-field-type-input > input',
			)
		);
		$this->add_control(
			'fields_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input > input' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'fields_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input > input' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'fields_border_width',
			array(
				'label'     => esc_html__( 'Border Width', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input > input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'fields_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input > input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'fields_padding',
			array(
				'label'     => esc_html__( 'Padding', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-input > input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'fields_height',
			array(
				'label'     => esc_html__( 'Fields height', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
					'size' => 59,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
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
		// BUTTON HOVER TABS
		$this->start_controls_tabs( 'button_tabs' );
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
					'{{WRAPPER}} .envision-blocks-field-type-submit > input, {{WRAPPER}} .envision-blocks-field-type-submit > button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .envision-blocks-field-type-submit > input, {{WRAPPER}} .envision-blocks-field-type-submit > button' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-field-type-submit > input, {{WRAPPER}} .envision-blocks-field-type-submit > button',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .envision-blocks-field-type-submit > input, {{WRAPPER}} .envision-blocks-field-type-submit > button',
			)
		);
		$this->add_control(
			'button_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit > input, {{WRAPPER}} .envision-blocks-field-type-submit > button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'button_text_padding',
			array(
				'label'     => esc_html__( 'Padding', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit > input, {{WRAPPER}} .envision-blocks-field-type-submit > button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'button_margin',
			array(
				'label'     => esc_html__( 'Margin', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-field-type-submit > input, {{WRAPPER}} .envision-blocks-field-type-submit > button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .envision-blocks-field-type-submit > input:hover, {{WRAPPER}} .envision-blocks-field-type-submit > button:hover' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .envision-blocks-field-type-submit > input:hover, {{WRAPPER}} .envision-blocks-field-type-submit > button:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .envision-blocks-field-type-submit > input:hover, {{WRAPPER}} .envision-blocks-field-type-submit > button:hover' => 'border-color: {{VALUE}};',
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
	 * Style > Consent Checkbox.
	 */
	private function section_consent_checkbox_style() {
		$this->start_controls_section(
			'section_consent_checkbox_style',
			array(
				'label' => esc_html__( 'Consent Checkbox', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'consent_checkbox_top_space',
			array(
				'label'     => esc_html__( 'Top Space', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
					'size' => 16,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-consent-checkbox' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'consent_checkbox_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-consent-checkbox' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'consent_checkbox_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-consent-checkbox a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'consent_checkbox_link_hover_color',
			array(
				'label'     => esc_html__( 'Link Hover Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-consent-checkbox a:hover, {{WRAPPER}} .envision-blocks-consent-checkbox a:focus' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'consent_checkbox_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-consent-checkbox',
			)
		);
		$this->end_controls_section();
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
		$settings  = $this->get_settings_for_display();
		$shortcode = do_shortcode( shortcode_unautop( $settings['shortcode'] ) );
		?>
			<div class="envision-blocks-mailchimp-newsletter 
			<?php
			if ( 'yes' === $settings['button_inside'] ) {
				echo 'envision-blocks-mailchimp-newsletter--button-inside';
			}
			?>
			">
				<?php
				echo $shortcode;
				?>
			</div>
		<?php
	}
}
