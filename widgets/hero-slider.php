<?php
namespace EnvisionBlocks\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Hero_Slider extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-hero-slider', ENVISION_BLOCKS_URL . 'assets/css/hero-slider.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-hero-slider', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-hero-slider', ENVISION_BLOCKS_URL . 'assets/js/view/hero-slider.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-hero-slider';
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
		return esc_html__( 'Hero Slider', 'envision-blocks' );
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
		return 'eicon-post-slider envision-blocks-icon';
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array( 'swiper', 'lazyload', 'envision-blocks-hero-slider' );
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
		return array( 'envision-blocks-hero-slider' );
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
		return array( 'hero', 'slider', 'carousel' );
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
		$this->section_slider_content();
		$this->section_slider_options();

		$this->section_slider_style();
		$this->section_content_style();
		$this->section_button_style();
		$this->section_dots_style();
	}


	/**
	 * Content > Slider Content.
	 */
	private function section_slider_content() {
		$this->start_controls_section(
			'section_slider_content',
			array(
				'label' => esc_html__( 'Slides', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'slide_content_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'envision-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'center',
			)
		);

		$repeater->add_control(
			'slide_background_image',
			array(
				'label'       => esc_html__( 'Background Image', 'envision-blocks' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'slide_video_type!' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_video_type',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Video', 'envision-blocks' ),
				'default' => '',
			)
		);

		$repeater->add_control(
			'slide_background_video',
			array(
				'label'       => esc_html__( 'Background Video', 'envision-blocks' ),
				'description' => esc_html__( 'Link to video file (mp4 is recommended)', 'envision-blocks' ),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => array( 'video' ),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'   => array(
					'slide_video_type' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_video_poster',
			array(
				'label'     => esc_html__( 'Video Poster', 'envision-blocks' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'slide_video_type' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_heading',
			array(
				'label'       => esc_html__( 'Slide Heading', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'slide_text',
			array(
				'label'       => esc_html__( 'Slide Text', 'envision-blocks' ),
				'type'        => Controls_Manager::WYSIWYG,
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'slide_button_text',
			array(
				'label'       => esc_html__( 'Button Text', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'separator'   => 'before',
				'label_block' => true,
				'default'     => esc_html__( 'Request Service', 'envision-blocks' ),
				'placeholder' => esc_html__( 'Button Text', 'envision-blocks' ),
			)
		);

		$repeater->add_control(
			'slide_button_link',
			array(
				'type'        => Controls_Manager::URL,
				'label'       => esc_html__( 'Button Link', 'envision-blocks' ),
				'placeholder' => 'https://example.com',
			)
		);

		$this->add_control(
			'slides',
			array(
				'label'       => esc_html__( 'Slides', 'envision-blocks' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'slide_heading'     => esc_html__( 'Contemporary Design', 'envision-blocks' ),
						'slide_text'        => esc_html__( 'Unleash Your Potential: Let Every Slide Tell Your Story of Triumph!', 'envision-blocks' ),
						'slide_button_text' => esc_html__( 'Shop only for $399', 'envision-blocks' ),
						'slide_button_link' => '#',
					),
					array(
						'slide_heading'     => esc_html__( 'Put it in neutrals', 'envision-blocks' ),
						'slide_text'        => esc_html__( 'Ignite Your Imagination: Journey Through Captivating Slides', 'envision-blocks' ),
						'slide_button_text' => esc_html__( 'Shop Now', 'envision-blocks' ),
						'slide_button_link' => '#',
					),
					array(
						'slide_heading'     => esc_html__( 'Discover Limitless Horizons', 'envision-blocks' ),
						'slide_text'        => esc_html__( 'Every Slide Unveils New Beginnings and Endless Opportunities!', 'envision-blocks' ),
						'slide_button_text' => esc_html__( 'Shop Now', 'envision-blocks' ),
						'slide_button_link' => '#',
					),
				),
				'fields'      => $repeater->get_controls(),
				'separator'   => 'before',
				'title_field' => '{{{ slide_heading }}}',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Content > Slider Options.
	 */
	private function section_slider_options() {
		$this->start_controls_section(
			'section_slider_options',
			array(
				'label' => esc_html__( 'Slider Options', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'animate_layers',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Animate Layers', 'envision-blocks' ),
				'default' => 'yes',
			)
		);

		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'              => esc_html__( 'Slides to Show', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'' => esc_html__( 'Default', 'envision-blocks' ),
				) + $slides_to_show,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'              => esc_html__( 'Slides to Scroll', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => esc_html__( 'Set how many slides are scrolled per swipe.', 'envision-blocks' ),
				'options'            => array(
					'' => esc_html__( 'Default', 'envision-blocks' ),
				) + $slides_to_show,
				'condition'          => array(
					'slides_to_show!' => '1',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'              => esc_html__( 'Navigation', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'both',
				'options'            => array(
					'both'   => esc_html__( 'Arrows and Dots', 'envision-blocks' ),
					'arrows' => esc_html__( 'Arrows', 'envision-blocks' ),
					'dots'   => esc_html__( 'Dots', 'envision-blocks' ),
					'none'   => esc_html__( 'None', 'envision-blocks' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'infinite',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Infinite Loop', 'envision-blocks' ),
				'default' => 'yes',
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'              => esc_html__( 'Effect', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => array(
					'slide' => esc_html__( 'Slide', 'envision-blocks' ),
					'fade'  => esc_html__( 'Fade', 'envision-blocks' ),
				),
				'condition'          => array(
					'slides_to_show' => '1',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => esc_html__( 'Animation Speed', 'envision-blocks' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'   => esc_html__( 'Direction', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => array(
					'ltr' => esc_html__( 'Left', 'envision-blocks' ),
					'rtl' => esc_html__( 'Right', 'envision-blocks' ),
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Autoplay', 'envision-blocks' ),
				'default' => 'yes',
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'       => esc_html__( 'Pause on Hover', 'envision-blocks' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => array(
					'autoplay' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'     => esc_html__( 'Pause on Interaction', 'envision-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed', 'envision-blocks' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Slider.
	 */
	private function section_slider_style() {

		$this->start_controls_section(
			'section_slider_style',
			array(
				'label' => esc_html__( 'Slider', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'slider_height',
			array(
				'label'      => esc_html__( 'Slider height', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'default'    => array(
					'unit' => 'vh',
					'size' => 72,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 2000,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__img-holder, {{WRAPPER}} .envision-blocks-hero-slider__img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slider_main_container_width',
			array(
				'label'      => esc_html__( 'Slider main container width', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1920,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-hero-slider .container' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slider_text_container_width',
			array(
				'label'           => esc_html__( 'Slider text container width', 'envision-blocks' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => array( 'px', '%' ),
				'devices'         => array( 'desktop', 'tablet', 'mobile' ),
				'desktop_default' => array(
					'size' => 50,
					'unit' => '%',
				),
				'tablet_default'  => array(
					'size' => 70,
					'unit' => '%',
				),
				'mobile_default'  => array(
					'size' => 100,
					'unit' => '%',
				),
				'range'           => array(
					'px' => array(
						'min' => 100,
						'max' => 1500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'       => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__text-holder' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Content.
	 */
	private function section_content_style() {

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Content', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title Heading Tag', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1' => esc_html__( 'H1', 'envision-blocks' ),
					'h2' => esc_html__( 'H2', 'envision-blocks' ),
					'h3' => esc_html__( 'H3', 'envision-blocks' ),
					'h4' => esc_html__( 'H4', 'envision-blocks' ),
					'h5' => esc_html__( 'H5', 'envision-blocks' ),
					'h6' => esc_html__( 'H6', 'envision-blocks' ),
				),
				'default' => 'h1',
			)
		);

		$this->add_control(
			'slider_heading_color',
			array(
				'label'     => esc_html__( 'Heading color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_heading_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-hero-slider__heading',
			)
		);

		$this->add_control(
			'slider_text_color',
			array(
				'label'     => esc_html__( 'Text color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_text_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-hero-slider__text',
			)
		);

		$this->add_responsive_control(
			'slider_text_top_space',
			array(
				'label'      => esc_html__( 'Text top space', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__text' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'slider_overlay_background_color',
			array(
				'label'     => esc_html__( 'Overlay background color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba( 0, 0, 0, .3 )',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-hero-slider .envision-blocks-bg-overlay::before' => 'background-color: {{VALUE}};',
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
			'slider_button_top_space',
			array(
				'label'      => esc_html__( 'Button top space', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 34,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__button' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Add the tabbed control.
		$this->tabbed_button_controls();

		$this->add_control(
			'button_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-hero-slider__button a',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Tabs for the Style > Button section.
	 */
	private function tabbed_button_controls() {

		$this->start_controls_tabs( 'tabs_background' );

		$this->start_controls_tab(
			'tab_background_normal',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'label'    => esc_html__( 'Button Background', 'envision-blocks' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .envision-blocks-hero-slider__button a',
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__button a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'label'     => esc_html__( 'Border', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .envision-blocks-hero-slider__button a',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .envision-blocks-hero-slider__button a',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_hover',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'label'    => esc_html__( 'Button Background', 'envision-blocks' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .envision-blocks-hero-slider__button a:hover',
			)
		);

		$this->add_control(
			'button_hover_text_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__button a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_hover_border',
				'label'     => esc_html__( 'Border', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .envision-blocks-hero-slider__button a:hover',
			)
		);

		$this->add_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__button a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_hover_box_shadow',
				'selector'  => '{{WRAPPER}} .envision-blocks-hero-slider__button a:hover',
				'separator' => '',
			)
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label'       => esc_html__( 'Transition Duration', 'envision-blocks' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 0.3,
				),
				'range'       => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .envision-blocks-hero-slider__button a' => 'transition: all {{SIZE}}s ease;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}


	/**
	 * Style > Dots.
	 */
	private function section_dots_style() {
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'     => esc_html__( 'Dots', 'envision-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'dots_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet::after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'dots_active_color',
			array(
				'label'     => esc_html__( 'Active Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .envision-blocks-slider--dots-inside .swiper-pagination-bullet:focus, {{WRAPPER}} .envision-blocks-slider--dots-inside .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dots_horizontal_space',
			array(
				'label'     => esc_html__( 'Horizontal Space', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 6,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dots_bottom_space',
			array(
				'label'     => esc_html__( 'Bottom Space', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-slider--dots-inside .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
				),
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
		$settings    = $this->get_settings_for_display();
		$breakpoints = Responsive::get_breakpoints();
		$mobile      = strval( $breakpoints['md'] );
		$tablet      = strval( $breakpoints['lg'] );
		$show_dots   = ( in_array( $settings['navigation'], array( 'dots', 'both' ) ) );
		$show_arrows = ( in_array( $settings['navigation'], array( 'arrows', 'both' ) ) );
		$is_single   = 1 === absint( $settings['slides_to_show'] );

		if ( $settings['slides'] ) {

			$slider_options = array(
				'speed'         => absint( $settings['speed'] ),

				'preloadImages' => false,
				'lazy'          => true,

				'loop'          => ( 'yes' === $settings['infinite'] ),
				'slidesPerView' => ( $settings['slides_to_show_mobile'] ) ? absint( $settings['slides_to_show_mobile'] ) : 1,

				'breakpoints'   => array(
					$mobile => array(
						'slidesPerView'  => ( $settings['slides_to_show_tablet'] ) ? absint( $settings['slides_to_show_tablet'] ) : 1,
						'slidesPerGroup' => ( $settings['slides_to_scroll_tablet'] ) ? absint( $settings['slides_to_scroll_tablet'] ) : 1,
					),
					$tablet => array(
						'slidesPerView'  => ( $settings['slides_to_show'] ) ? absint( $settings['slides_to_show'] ) : 1,
						'slidesPerGroup' => ( $settings['slides_to_scroll'] ) ? absint( $settings['slides_to_scroll'] ) : 1,
					),
				),

			);

			if ( $is_single ) {
				$slider_options['effect'] = $settings['effect'];

				if ( 'fade' === $settings['effect'] ) {
					$slider_options['fadeEffect'] = array(
						'crossFade' => true,
					);
				}
			} else {
				$slider_options['slidesPerGroup'] = ( $settings['slides_to_scroll_mobile'] ) ? absint( $settings['slides_to_scroll_mobile'] ) : 1;
			}

			if ( $show_dots ) {
				$slider_options['pagination'] = array(
					'el'        => '.swiper-pagination',
					'type'      => 'bullets',
					'clickable' => true,
				);
			}

			if ( $show_arrows ) {
				$slider_options['navigation'] = array(
					'nextEl' => '.envision-blocks-swiper-button-next-' . esc_attr( $this->get_id() ),
					'prevEl' => '.envision-blocks-swiper-button-prev-' . esc_attr( $this->get_id() ),
				);
			}

			if ( 'yes' === $settings['autoplay'] ) {
				$slider_options['autoplay'] = array(
					'delay'                => $settings['autoplay_speed'],
					'disableOnInteraction' => ( 'yes' === $settings['pause_on_interaction'] ),
					'pauseOnMouseEnter'    => ( 'yes' === $settings['pause_on_hover'] ),
				);
			}

			$this->add_render_attribute(
				array(
					'carousel'         => array(
						'class'                => 'swiper-container envision-blocks-slider envision-blocks-slider--dots-inside envision-blocks-hero-slider envision-blocks-hero-slider-' . esc_attr( $this->get_id() ),
						'data-slider-settings' => wp_json_encode( $slider_options ),
						'dir'                  => $settings['direction'],
					),
					'carousel-wrapper' => array(
						'class' => 'swiper-wrapper',
					),
				)
			);

			if ( $show_arrows ) {
				$this->add_render_attribute( 'carousel', 'class', 'envision-blocks-slider--arrows' );
			}

			$slides_count = count( $settings['slides'] );
			?>

			<!-- Slider main container -->
			<div <?php echo $this->get_render_attribute_string( 'carousel' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'carousel-wrapper' ); ?>>					

					<?php
					foreach ( $settings['slides'] as $index => $item ) :

						$button_setting_key = $this->get_repeater_setting_key( 'slider_button', 'slides', $index );
						$align_setting_key  = $this->get_repeater_setting_key( 'slide_content_alignment', 'slides', $index );

						$this->add_render_attribute(
							$button_setting_key,
							array(
								'class' => array( 'elementor-button', 'elementor-size-lg' ),
							)
						);

						$this->add_render_attribute(
							$align_setting_key,
							array(
								'class' => array(
									'envision-blocks-hero-slider__text-outer',
									'envision-blocks-hero-slider__text-outer--align-' . $item['slide_content_alignment'],
								),
							)
						);

						$this->add_link_attributes( $button_setting_key, $item['slide_button_link'] );
						?>
							<div class="swiper-slide">

								<div class="envision-blocks-hero-slider__content-holder">									

									<figure class="envision-blocks-hero-slider__img-holder envision-blocks-bg-overlay">
										<div class="envision-blocks-hero-slider__bg-img">

										<?php if ( 'yes' === $item['slide_video_type'] && $item['slide_background_video']['id'] ) : ?>
												<?php $video_poster = ! empty( $item['slide_video_poster']['id'] ) ? $item['slide_video_poster']['url'] : ''; ?>

												<video class="lazy" width="100%" data-src="<?php echo esc_url( $item['slide_background_video']['url'] ); ?>"
															preload muted loop playsinline autoplay														
															data-poster="<?php echo esc_url( $video_poster ); ?>"
														>
													<source type="video/mp4" data-src="<?php echo esc_url( $item['slide_background_video']['url'] ); ?>">
												</video>
											<?php elseif ( ! empty( $item['slide_background_image']['id'] ) ) : ?>
												<?php echo wp_get_attachment_image( $item['slide_background_image']['id'], 'full', '', array( 'class' => 'envision-blocks-hero-slider__img' ) ); ?>
											<?php endif; ?>
											
										</div>
									</figure>							
									
									<div class="container">
										<div <?php echo $this->get_render_attribute_string( $align_setting_key ); ?>>
											<div class="envision-blocks-hero-slider__text-holder">

											<?php if ( $item['slide_heading'] ) : ?>
													<<?php echo esc_html( \EnvisionBlocks\Utils::validate_html_tag( $settings['title_tag'] ) ); ?> class="envision-blocks-hero-slider__heading 
													<?php
													if ( 'yes' === $settings['animate_layers'] ) {
														echo 'envision-blocks-slider-animation';}
													?>
													">
														<?php echo esc_html( $item['slide_heading'] ); ?>
													</<?php echo esc_html( \EnvisionBlocks\Utils::validate_html_tag( $settings['title_tag'] ) ); ?>>
												<?php endif; ?>

											<?php if ( $item['slide_text'] ) : ?>
													<div class="envision-blocks-hero-slider__text 
													<?php
													if ( 'yes' === $settings['animate_layers'] ) {
														echo 'envision-blocks-slider-animation';}
													?>
													">
														<?php echo esc_html( $item['slide_text'] ); ?>
													</div>											
												<?php endif; ?>

											<?php if ( isset( $item['slide_button_text'] ) ) : ?>
													<div class="envision-blocks-hero-slider__button elementor-widget-button 
													<?php
													if ( 'yes' === $settings['animate_layers'] ) {
														echo 'envision-blocks-slider-animation';}
													?>
													"> 
														<a <?php echo $this->get_render_attribute_string( $button_setting_key ); ?>>
															<span <?php echo $this->get_render_attribute_string( 'slide_button_text' ); ?>><?php echo esc_html( $item['slide_button_text'] ); ?></span>
														</a>
													</div>
												<?php endif; ?>

											</div>
										</div>
									</div>

								</div>
							</div>
					<?php endforeach; ?>

					</div> <!-- .swiper-wrapper -->

					<?php if ( 1 < $slides_count ) : ?>
						<?php if ( $show_dots ) : ?>
							<div class="swiper-pagination"></div>
						<?php endif; ?>

						<?php if ( $show_arrows ) : ?>
							<div class="envision-blocks-swiper-button envision-blocks-swiper-button--is-arrow envision-blocks-swiper-button-prev envision-blocks-swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
								<svg width="64" height="64" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5" stroke="currentColor" class="envision-blocks-swiper-button__icon">
									<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
								</svg>
								<span class="screen-reader-text"><?php echo esc_html__( 'Previous', 'envision-blocks' ); ?></span>
							</div>
							<div class="envision-blocks-swiper-button envision-blocks-swiper-button--is-arrow envision-blocks-swiper-button-next envision-blocks-swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
								<svg width="64" height="64" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5" stroke="currentColor" class="envision-blocks-swiper-button__icon">
									<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
								</svg>
								<span class="screen-reader-text"><?php echo esc_html__( 'Next', 'envision-blocks' ); ?></span>
							</div>
						<?php endif; ?>
					<?php endif; ?>

				</div> <!-- .swiper-container -->
			<?php
		}
	}
}
