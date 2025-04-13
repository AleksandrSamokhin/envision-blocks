<?php
namespace EnvisionBlocks\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Icon_Box extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-icon-box', ENVISION_BLOCKS_URL . 'assets/css/icon-box.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-icon-box', 'rtl', 'replace' );
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
		return 'envision-blocks-icon-box';
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
		return esc_html__( 'Icon Box', 'envision-blocks' );
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
		return 'eicon-icon-box envision-blocks-icon';
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
		return array( 'envision-blocks-icon-box' );
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

	public function get_keywords() {
		return array( 'icon', 'box' );
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
		$this->section_content();
		$this->section_box_style();
		// $this->section_text_style();
		$this->section_dark_mode_style();
	}

	/**
	 * Content > Content.
	 */
	private function section_content() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'image',
			array(
				'label'       => esc_html__( 'Image', 'envision-blocks' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'image_dark_mode',
			array(
				'label'       => esc_html__( 'Dark mode image', 'envision-blocks' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Description', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'link',
			array(
				'type'        => Controls_Manager::URL,
				'label'       => esc_html__( 'Link', 'envision-blocks' ),
				'placeholder' => 'https://example.com',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Box.
	 */
	private function section_box_style() {

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'Box', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'box_height',
			array(
				'label'      => esc_html__( 'Box Height', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-icon-box__container' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_background',
				'label'    => esc_html__( 'Box Background', 'envision-blocks' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .envision-blocks-icon-box__container',
			)
		);

		$this->add_control(
			'hover_background_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover Background Color', 'envision-blocks' ),
				'default'   => '',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-icon-box__container:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_text_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover Text Color', 'envision-blocks' ),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-icon-box__container:hover .envision-blocks-icon-box__title, {{WRAPPER}} .envision-blocks-icon-box__container:hover .envision-blocks-icon-box__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-icon-box__container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'box_border',
				'selector'  => '{{WRAPPER}} .envision-blocks-icon-box__container',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-icon-box__container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .envision-blocks-icon-box__container',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Text.
	 */
	private function section_text_style() {

		$this->start_controls_section(
			'section_text_style',
			array(
				'label' => esc_html__( 'Text', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'   => esc_html__( 'Separator', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->start_controls_tabs( 'tabs_text_color' );

		$this->start_controls_tab(
			'tab_text_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-marquee__text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .envision-blocks-marquee__separator' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_text_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-marquee__text:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .envision-blocks-marquee__text:hover .envision-blocks-marquee__separator' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-marquee__text',
			)
		);

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
			'box_heading_dark',
			array(
				'label' => esc_html__( 'Box', 'envision-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_background_dark',
				'label'    => esc_html__( 'Box Background', 'envision-blocks' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-icon-box__container',
			)
		);

		$this->add_control(
			'hover_background_color_dark',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover Background Color', 'envision-blocks' ),
				'default'   => '',
				'separator' => 'before',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-icon-box__container:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_text_color_dark',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover Text Color', 'envision-blocks' ),
				'default'   => '',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-icon-box__container:hover .envision-blocks-icon-box__title, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-icon-box__container:hover .envision-blocks-icon-box__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'box_border_dark',
				'selector'  => '.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-icon-box__container',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_box_shadow_dark',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-icon-box__container',
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
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );
		}
		?>

			<div class="envision-blocks-icon-box">
				<?php if ( ! empty( $settings['link']['url'] ) ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'url' ); ?> class="envision-blocks-icon-box__container envision-blocks-icon-box__url">
				<?php else : ?>
					<div class="envision-blocks-icon-box__container">
				<?php endif; ?>

					<?php if ( ! empty( $settings['image'] ) ) : ?>
						<?php echo wp_get_attachment_image( $settings['image']['id'], 'full', '', array( 'class' => 'envision-blocks-icon-box__img' ) ); ?>
						<?php if ( $settings['image_dark_mode']['id'] ) : ?>
							<?php echo wp_get_attachment_image( $settings['image_dark_mode']['id'], 'full', '', array( 'class' => 'envision-blocks-icon-box__img envision-blocks-icon-box__img--dark-mode' ) ); ?>
						<?php endif; ?>							
					<?php endif; ?>
					
					<div class="envision-blocks-icon-box__info">
						<?php if ( ! empty( $settings['title'] ) ) : ?>
							<h3 class="envision-blocks-icon-box__title"><?php echo wp_kses_post( $settings['title'] ); ?>
							</h3>
						<?php endif; ?>

						<?php if ( ! empty( $settings['description'] ) ) : ?>
							<span class="envision-blocks-icon-box__description"><?php echo wp_kses_post( $settings['description'] ); ?>
							</span>
						<?php endif; ?>
					</div>

				<?php if ( ! empty( $settings['link']['url'] ) ) : ?>						
					</a>
				<?php else : ?>
					</div>
				<?php endif; ?>
			</div>					

		<?php
	}
}
