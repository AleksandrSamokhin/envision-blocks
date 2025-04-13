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
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Marquee extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-marquee', ENVISION_BLOCKS_URL . 'assets/css/marquee.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-marquee', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-marquee', ENVISION_BLOCKS_URL . 'assets/js/view/marquee.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-marquee';
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
		return esc_html__( 'Marquee', 'envision-blocks' );
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
		return 'eicon-page-transition envision-blocks-icon';
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
		return array( 'imagesloaded', 'envision-blocks-marquee' );
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
		return array( 'envision-blocks-marquee' );
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
		return array( 'marquee', 'animation' );
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
		$this->section_marquee_content();
		$this->section_marquee_slider();
		$this->section_layout_style();
		$this->section_image_style();
		$this->section_text_style();
		$this->section_dark_mode_style();
	}

	/**
	 * Content > Marquee Content.
	 */
	private function section_marquee_content() {
		$this->start_controls_section(
			'section_marquee_content',
			array(
				'label' => esc_html__( 'Marquee', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'marquee_image',
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

		$repeater->add_control(
			'marquee_image_dark_mode',
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

		$repeater->add_control(
			'marquee_text',
			array(
				'label'       => esc_html__( 'Text', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'item_link',
			array(
				'type'        => Controls_Manager::URL,
				'label'       => esc_html__( 'Link', 'envision-blocks' ),
				'placeholder' => 'https://example.com',
			)
		);

		$this->add_control(
			'marquee',
			array(
				'label'       => esc_html__( 'Items', 'envision-blocks' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'marquee_image' => array( 'url' => Utils::get_placeholder_image_src() ),
					),
					array(
						'marquee_image' => array( 'url' => Utils::get_placeholder_image_src() ),
					),
					array(
						'marquee_image' => array( 'url' => Utils::get_placeholder_image_src() ),
					),
					array(
						'marquee_image' => array( 'url' => Utils::get_placeholder_image_src() ),
					),
					array(
						'marquee_image' => array( 'url' => Utils::get_placeholder_image_src() ),
					),
				),
				'separator'   => 'before',
				'title_field' => '{{{ marquee_image.id }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Slider.
	 */
	private function section_marquee_slider() {
		$this->start_controls_section(
			'section_marquee_slider',
			array(
				'label' => esc_html__( 'Slider', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'h-direction',
			array(
				'label'     => esc_html__( 'Direction', 'envision-blocks' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'-1' => array(
						'title' => esc_html__( 'Forward', 'envision-blocks' ),
						'icon'  => 'eicon-arrow-right',
					),
					'1'  => array(
						'title' => esc_html__( 'Backward', 'envision-blocks' ),
						'icon'  => 'eicon-arrow-left',
					),
				),
				'default'   => '1',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-marquee' => '--direction: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'vertical',
			array(
				'label'   => esc_html__( 'Vertical', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_responsive_control(
			'container_height',
			array(
				'label'      => esc_html__( 'Container height', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vh' ),
				'default'    => array(
					'unit' => 'vh',
					'size' => 100,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 3000,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-marquee' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'vertical' => 'yes',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'     => esc_html__( 'Speed', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-marquee' => '--speed: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'     => esc_html__( 'Pause on hover', 'envision-blocks' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'true'  => array(
						'title' => esc_html__( 'Yes', 'envision-blocks' ),
						'icon'  => 'eicon-check',
					),
					'false' => array(
						'title' => esc_html__( 'No', 'envision-blocks' ),
						'icon'  => 'eicon-editor-close',
					),
				),
				'default'   => 'true',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-marquee' => '--pause-on-hover: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Layout.
	 */
	private function section_layout_style() {

		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => esc_html__( 'Layout', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'items_gap',
			array(
				'label'      => esc_html__( 'Items gap', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-marquee__animation' => '--items-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .envision-blocks-marquee__separator' => 'margin-inline-start: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => esc_html__( 'Item Width', 'envision-blocks' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .envision-blocks-marquee__item' => '--item-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => esc_html__( 'Item Height', 'envision-blocks' ),
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
					'{{WRAPPER}} .envision-blocks-marquee__item' => '--item-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Image.
	 */
	private function section_image_style() {

		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Images', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Image Size
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'exclude' => array( 'custom' ),
				'default' => 'large',
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .envision-blocks-marquee__img',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .envision-blocks-marquee__img',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-marquee__img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .envision-blocks-marquee__img',
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
			'image_heading_dark',
			array(
				'label' => esc_html__( 'Image', 'envision-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'image_border_color_dark',
			array(
				'label'     => esc_html__( 'Border Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-marquee__img' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_heading_dark',
			array(
				'label' => esc_html__( 'Text', 'envision-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->start_controls_tabs( 'tabs_text_color_dark' );

		$this->start_controls_tab(
			'tab_text_color_normal_dark',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'text_color_dark',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-marquee__text' => 'color: {{VALUE}};',
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-marquee__separator' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_text_color_hover_dark',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'text_color_hover_dark',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-marquee__text:hover' => 'color: {{VALUE}};',
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-marquee__text:hover .envision-blocks-marquee__separator' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
		$classes  = 'envision-blocks-marquee envision-blocks-marquee-' . esc_attr( $this->get_id() );

		if ( 'yes' === $settings['vertical'] ) {
			$classes .= ' envision-blocks-marquee--vertical';
		}

		$this->add_render_attribute(
			array(
				'marquee' => array(
					'class'                => esc_attr( $classes ),
					'data-v-direction'     => esc_attr( $settings['vertical'] ),
					'data-viewport-offset' => '0.01',
				),
			)
		); ?>

		<div <?php echo $this->get_render_attribute_string( 'marquee' ); ?>>
			<div class="envision-blocks-marquee__animation">
				<?php
				foreach ( $settings['marquee'] as $index => $item ) :
					$image_setting_key = $this->get_repeater_setting_key( 'image', 'marquee', $index );
					$this->add_link_attributes( $image_setting_key, $item['item_link'] );
					?>

					<div class="envision-blocks-marquee__item 
					<?php
					if ( ! $item['marquee_image_dark_mode']['id'] ) {
						echo 'envision-blocks-marquee__item--no-dark-image';}
					?>
					">
						<?php if ( ! empty( $item['item_link']['url'] ) ) : ?>
							<a <?php echo $this->get_render_attribute_string( $image_setting_key ); ?> class="envision-blocks-marquee__url">
						<?php endif; ?>

							<?php if ( ! empty( $item['marquee_image'] ) ) : ?>
								<?php echo wp_get_attachment_image( $item['marquee_image']['id'], $settings['image_size'], '', array( 'class' => 'envision-blocks-marquee__img' ) ); ?>
								<?php if ( $item['marquee_image_dark_mode']['id'] ) : ?>
									<?php echo wp_get_attachment_image( $item['marquee_image_dark_mode']['id'], $settings['image_size'], '', array( 'class' => 'envision-blocks-marquee__img envision-blocks-marquee__img--dark-mode' ) ); ?>
								<?php endif; ?>							
							<?php endif; ?>

							<?php if ( ! empty( $item['marquee_text'] ) ) : ?>
								<span class="envision-blocks-marquee__text"><?php echo esc_html__( $item['marquee_text'] ); ?>
								<?php
								if ( 'yes' === $settings['separator'] ) :
									?>
									<span class="envision-blocks-marquee__separator"></span><?php endif; ?>
								</span>
							<?php endif; ?>

						<?php if ( ! empty( $item['item_link']['url'] ) ) : ?>						
							</a>
						<?php endif; ?>
					</div>

				<?php endforeach; ?>
			</div>
		</div>

		<?php
	}
}
