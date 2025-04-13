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

class Before_After extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-before-after', ENVISION_BLOCKS_URL . 'assets/css/before-after.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-before-after', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-before-after', ENVISION_BLOCKS_URL . 'assets/js/view/before-after.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-before-after';
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
		return esc_html__( 'Before and After', 'envision-blocks' );
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
		return 'eicon-image-before-after envision-blocks-icon';
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
		return array( 'before-after', 'envision-blocks-before-after' );
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
		return array( 'envision-blocks-before-after' );
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
		return array( 'before', 'after', 'comparison' );
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

		$this->section_image_style();
		$this->section_icon_style();
		$this->section_labels_style();
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
			'image_before',
			array(
				'label'   => esc_html__( 'Image Before', 'envision-blocks' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_control(
			'image_after',
			array(
				'label'   => esc_html__( 'Image After', 'envision-blocks' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		// Image Size
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'exclude' => array( 'custom' ),
				'include' => array(),
				'default' => 'full',
			)
		);

		$this->add_control(
			'label_before_text',
			array(
				'label'   => esc_html__( 'Label Before', 'envision-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Before', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'label_after_text',
			array(
				'label'   => esc_html__( 'Label After', 'envision-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'After', 'envision-blocks' ),
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
				'label' => esc_html__( 'Image', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'label'     => esc_html__( 'Border', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .envision-blocks-ba-slider__img',
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-ba-slider__img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'image_box_shadow',
				'label'     => esc_html__( 'Box Shadow', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .envision-blocks-ba-slider__img',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Icon.
	 */
	private function section_icon_style() {
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-ba-slider .handle:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-ba-slider .handle:after' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Labels.
	 */
	private function section_labels_style() {
		$this->start_controls_section(
			'section_labels_style',
			array(
				'label' => esc_html__( 'Labels', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-ba-slider__ribbon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-ba-slider__ribbon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-ba-slider__ribbon',
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
		$id       = esc_attr( $this->get_id() );

		$this->add_render_attribute( 'envision-blocks-before-after', array( 'class' => array( 'envision-blocks-ba-slider', 'envision-blocks-ba-slider-' . $id ) ) );
		?>

			<div <?php echo $this->get_render_attribute_string( 'envision-blocks-before-after' ); ?> >

				<?php if ( $settings['label_before_text'] ) : ?>
					<div class="envision-blocks-ba-slider__ribbon envision-blocks-ba-slider__ribbon--before">
						<?php echo esc_html( $settings['label_before_text'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $settings['label_after_text'] ) : ?>
					<div class="envision-blocks-ba-slider__ribbon envision-blocks-ba-slider__ribbon--after">
						<?php echo esc_html( $settings['label_after_text'] ); ?>
					</div>
				<?php endif; ?>

					<?php if ( ! empty( $settings['image_after']['id'] ) ) { ?>
						<?php echo wp_get_attachment_image( $settings['image_after']['id'], $settings['image_size'], false, array( 'class' => 'envision-blocks-ba-slider__img envision-blocks-ba-slider__img-after' ) ); ?>
					<?php } ?>
				
					<div class="resize">
						<?php if ( ! empty( $settings['image_before']['id'] ) ) { ?>
							<?php echo wp_get_attachment_image( $settings['image_before']['id'], $settings['image_size'], false, array( 'class' => 'envision-blocks-ba-slider__img envision-blocks-ba-slider__img-before' ) ); ?>
						<?php } ?>
					</div>
				
				<span class="handle"></span>
			</div>

		<?php
	}
}
