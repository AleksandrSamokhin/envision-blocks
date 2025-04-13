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
use Elementor\Plugin;

use EnvisionBlocks\Modules\Woo_Helper;
use EnvisionBlocks\Modules\Woo_Widgets_Data;
use EnvisionBlocks\Traits\Slider_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Parallax extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-parallax', ENVISION_BLOCKS_URL . 'assets/css/parallax.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-parallax', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-parallax', ENVISION_BLOCKS_URL . 'assets/js/view/parallax.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-parallax';
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
		return esc_html__( 'Parallax', 'envision-blocks' );
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
		return 'eicon-parallax envision-blocks-icon';
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
		return array( 'jquery.parallax-scroll', 'envision-blocks-parallax' );
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
		return array( 'envision-blocks-parallax' );
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
		return array( 'parallax' );
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
		$this->section_content_style();
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
			'main_image',
			array(
				'label'      => esc_html__( 'Image', 'envision-blocks' ),
				'type'       => Controls_Manager::MEDIA,
				'default'    => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'main_image',
				'exclude' => array( 'custom' ),
				'include' => array(),
				'default' => 'full',
			)
		);

		$this->add_control(
			'main_image_parallax_level',
			array(
				'label'   => esc_html__( 'Main Image Parallax Level', 'envision-blocks' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '',
			)
		);

		// Parallax Images
		$repeater = new Repeater();

		$repeater->add_control(
			'parallax_image',
			array(
				'label'      => esc_html__( 'Parallax Image', 'envision-blocks' ),
				'type'       => Controls_Manager::MEDIA,
				'default'    => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'show_label' => false,
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'parallax_image',
				'exclude' => array( 'custom' ),
				'include' => array(),
				'default' => 'full',
			)
		);

		$repeater->add_control(
			'parallax_image_position',
			array(
				'label'   => esc_html__( 'Image Position', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'top-left'     => esc_html__( 'Top Left', 'envision-blocks' ),
					'top-right'    => esc_html__( 'Top Right', 'envision-blocks' ),
					'bottom-left'  => esc_html__( 'Bottom Left', 'envision-blocks' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'envision-blocks' ),
				),
				'default' => 'bottom-right',
			)
		);

		$repeater->add_responsive_control(
			'parallax_image_vertical_offset',
			array(
				'label'      => esc_html__( 'Vertical Offset', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', '%' ),
				'range'      => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -50,
						'max' => 50,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.envision-blocks-parallax__image-position--top-left'       => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}}.envision-blocks-parallax__image-position--top-right'      => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}}.envision-blocks-parallax__image-position--bottom-left'  => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}}.envision-blocks-parallax__image-position--bottom-right' => 'bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$repeater->add_responsive_control(
			'parallax_image_horizontal_offset',
			array(
				'label'      => esc_html__( 'Horizontal Offset', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', '%' ),
				'range'      => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -50,
						'max' => 50,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.envision-blocks-parallax__image-position--top-left'       => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}}.envision-blocks-parallax__image-position--top-right'      => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}}.envision-blocks-parallax__image-position--bottom-left'  => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}}.envision-blocks-parallax__image-position--bottom-right' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$repeater->add_control(
			'image_parallax_z_index',
			array(
				'label'     => esc_html__( 'Z-index', 'envision-blocks' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'image_parallax_level',
			array(
				'label'   => esc_html__( 'Image Parallax Level', 'envision-blocks' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '',
			)
		);

		$this->add_control(
			'parallax_images',
			array(
				'label'       => esc_html__( 'Parallax Images', 'envision-blocks' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'parallax_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
				),
				'title_field' => '{{{ parallax_image.label }}}',
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
			'style_main_image_heading',
			array(
				'label' => esc_html__( 'Main Image', 'envision-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'style_main_image_height',
			array(
				'label'      => esc_html__( 'Height', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 2000,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-parallax__main-image img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'style_main_image_object_fit',
			array(
				'label'     => esc_html__( 'Object Fit', 'envision-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'style_main_image_height[size]!' => '',
				),
				'options'   => array(
					''        => esc_html__( 'Default', 'envision-blocks' ),
					'fill'    => esc_html__( 'Fill', 'envision-blocks' ),
					'cover'   => esc_html__( 'Cover', 'envision-blocks' ),
					'contain' => esc_html__( 'Contain', 'envision-blocks' ),
				),
				'default'   => 'cover',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-parallax__main-image img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'style_main_image_border',
				'selector' => '{{WRAPPER}} .envision-blocks-parallax__main-image img',
			)
		);

		$this->add_control(
			'style_main_image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-parallax__main-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'main_image_box_shadow',
				'selector'  => '{{WRAPPER}} .envision-blocks-parallax__main-image img',
				'separator' => '',
			)
		);

		$this->add_control(
			'style_parallax_images_heading',
			array(
				'label'     => esc_html__( 'Parallax Images', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'style_parallax_images_border',
				'selector' => '{{WRAPPER}} .envision-blocks-parallax__image img',
			)
		);

		$this->add_control(
			'style_parallax_images_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-parallax__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'parallax_images_box_shadow',
				'selector'  => '{{WRAPPER}} .envision-blocks-parallax__image img',
				'separator' => '',
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

		$data                       = array();
		$data['data-parallax-main'] = $settings['main_image_parallax_level'];
		if ( ( 'integer' === gettype( $settings['main_image_parallax_level'] ) ) && ( 0 === $settings['main_image_parallax_level'] ) ) {
			$data['data-parallax-main'] = 'parallax-disabled';
		}

		$this->add_render_attribute(
			array(
				'main-image' => array(
					'data-parallax-main' => $data['data-parallax-main'],
				),
			)
		);

		?>

		<div class="envision-blocks-parallax">
			<div class="envision-blocks-parallax__images">
				<div class="envision-blocks-parallax__main-image-holder">
					<div class="envision-blocks-parallax__main-image-zoom-holder">
						<?php if ( ! empty( $settings['main_image'] ) ) : ?>
							<div class="envision-blocks-parallax__main-image" <?php echo $this->get_render_attribute_string( 'main-image' ); ?>>
								<?php if ( ! empty( $settings['main_image']['id'] ) ) { ?>
									<?php echo wp_get_attachment_image( $settings['main_image']['id'], $settings['main_image_size'], false ); ?>
								<?php } ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( ! empty( $settings['parallax_images'] ) ) { ?>
					<?php foreach ( $settings['parallax_images'] as $index => $item ) { ?>
						<?php

							$data['data-parallax'] = $item['image_parallax_level'];
						if ( ( 'integer' === gettype( $item['image_parallax_level'] ) ) && ( 0 === $item['image_parallax_level'] ) ) {
							$data['data-parallax'] = 'parallax-disabled';
						}

							$repeater_setting_key = $this->get_repeater_setting_key( 'parallax_image', 'parallax_images', $index );

							$this->add_render_attribute(
								array(
									$repeater_setting_key => array(
										'class'         => array(
											'envision-blocks-parallax__image',
											'envision-blocks-parallax__image-position--' . $item['parallax_image_position'],
											'elementor-repeater-item-' . $item['_id'],
										),
										'data-parallax' => $data['data-parallax'],
									),
								)
							);
						?>
						<div <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>>
							<?php if ( ! empty( $item['parallax_image']['id'] ) ) { ?>
								<?php echo wp_get_attachment_image( $item['parallax_image']['id'], $item['parallax_image_size'], false ); ?>
							<?php } ?>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		
		<?php
	}
}
