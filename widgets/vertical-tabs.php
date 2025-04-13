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

class Vertical_Tabs extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-vertical-tabs', ENVISION_BLOCKS_URL . 'assets/css/vertical-tabs.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-vertical-tabs', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-vertical-tabs', ENVISION_BLOCKS_URL . 'assets/js/view/vertical-tabs.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-vertical-tabs';
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
		return esc_html__( 'Vertical Tabs', 'envision-blocks' );
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
		return 'eicon-thumbnails-right envision-blocks-icon';
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
		return array( 'envision-blocks-vertical-tabs' );
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
		return array( 'envision-blocks-vertical-tabs' );
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
		return array( 'project', 'portfolio', 'tabs', 'vertical' );
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
		$this->section_tabs_content();
		$this->section_tabs_style();
		$this->section_image_style();
	}

	/**
	 * Content > Tabs Content.
	 */
	private function section_tabs_content() {
		$this->start_controls_section(
			'section_tabs_content',
			array(
				'label' => esc_html__( 'Tabs', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'activate_on_click',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Activate on Click', 'envision-blocks' ),
				'default' => '',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			array(
				'label'       => esc_html__( 'Title', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'tab_title_link',
			array(
				'type'        => Controls_Manager::URL,
				'label'       => esc_html__( 'Title Link', 'envision-blocks' ),
				'placeholder' => 'https://example.com',
			)
		);

		$repeater->add_control(
			'tab_image',
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
			'tabs',
			array(
				'label'       => esc_html__( 'Tabs', 'envision-blocks' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'tab_title' => esc_html__( 'Online Store', 'envision-blocks' ),
					),
					array(
						'tab_title' => esc_html__( 'Local Business', 'envision-blocks' ),
					),
					array(
						'tab_title' => esc_html__( 'Magazine', 'envision-blocks' ),
					),
					array(
						'tab_title' => esc_html__( 'Blog', 'envision-blocks' ),
					),
					array(
						'tab_title' => esc_html__( 'Portfolio', 'envision-blocks' ),
					),
					array(
						'tab_title' => esc_html__( 'Services', 'envision-blocks' ),
					),
				),
				'fields'      => $repeater->get_controls(),
				'separator'   => 'before',
				'title_field' => '{{{ tab_title }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Tabs.
	 */
	private function section_tabs_style() {

		$this->start_controls_section(
			'section_tabs_style',
			array(
				'label' => esc_html__( 'Tabs', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tabs_style',
			array(
				'label'       => esc_html__( 'Tabs Style', 'envision-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'headings',
				'label_block' => true,
				'options'     => array(
					'headings' => esc_html__( 'Headings', 'envision-blocks' ),
					'tabs'     => esc_html__( 'Tabs', 'envision-blocks' ),
				),
			)
		);

		$this->add_responsive_control(
			'content_gap',
			array(
				'label'      => esc_html__( 'Content gap', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'size' => 24,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-vertical-tabs__row' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tabs_space_between',
			array(
				'label'      => esc_html__( 'Tabs space between', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-vertical-tabs__list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'counter_heading',
			array(
				'label'     => esc_html__( 'Counter', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_counter',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Show Counter', 'envision-blocks' ),
				'default' => '',
			)
		);

		$this->add_responsive_control(
			'counter_valign',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'envision-blocks' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-vertical-tabs__item-counter' => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'counter_spacing',
			array(
				'label'     => esc_html__( 'Counter spacing', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 24,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .envision-blocks-vertical-tabs__item-counter' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .envision-blocks-vertical-tabs__item-counter' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'counter_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-vertical-tabs__item-counter',
			)
		);

		$this->add_control(
			'tabs_heading',
			array(
				'label'     => esc_html__( 'Tabs', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_colors' );

		$this->start_controls_tab(
			'tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-vertical-tabs__list-item:not(.envision-blocks-vertical-tabs__list-item--active) .envision-blocks-vertical-tabs__item-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'tab_hover_color',
			array(
				'label'     => esc_html__( 'Text color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-vertical-tabs__list-item:focus, {{WRAPPER}} .envision-blocks-vertical-tabs__list-item:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active',
			array(
				'label' => esc_html__( 'Active', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'title_active_color',
			array(
				'label'     => esc_html__( 'Title active color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-vertical-tabs__list-item--active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-vertical-tabs__item-title',
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
				'selector' => '{{WRAPPER}} .envision-blocks-vertical-tabs__img',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .envision-blocks-vertical-tabs__img',
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
					'{{WRAPPER}} .envision-blocks-vertical-tabs__img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .envision-blocks-vertical-tabs__img',
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

		$this->add_render_attribute(
			array(
				'tabs' => array(
					'class'                  => 'envision-blocks-vertical-tabs envision-blocks-vertical-tabs-' . esc_attr( $this->get_id() ) . ' envision-blocks-vertical-tabs--' . esc_attr( $settings['tabs_style'] ),
					'data-activate-on-click' => esc_attr( $settings['activate_on_click'] ),
				),
			)
		); ?>

		<div <?php echo $this->get_render_attribute_string( 'tabs' ); ?>>
			<div class="envision-blocks-vertical-tabs__row">
				<div class="envision-blocks-vertical-tabs__col envision-blocks-vertical-tabs__col--left">
					<ul class="envision-blocks-vertical-tabs__list">
						<?php
						foreach ( $settings['tabs'] as $index => $item ) :
							$link_setting_key = $this->get_repeater_setting_key( 'tab_title_link', 'tabs', $index );
							$this->add_render_attribute( $link_setting_key, array( 'class' => array( 'envision-blocks-vertical-tabs__title-url' ) ) );
							$this->add_link_attributes( $link_setting_key, $item['tab_title_link'] );
							?>

								<?php if ( $item['tab_title'] ) : ?>
									<li class="envision-blocks-vertical-tabs__list-item 
									<?php
									if ( 0 === $index ) {
										echo 'envision-blocks-vertical-tabs__list-item--active';}
									?>
									">

										<?php
										if ( 'yes' === $settings['show_counter'] ) {
											$formattedCounter = sprintf( '%02d', $index + 1 );
											echo '<span class="envision-blocks-vertical-tabs__item-counter">' . esc_html( $formattedCounter ) . '</span>';
										}
										?>

										<span class="envision-blocks-vertical-tabs__item-title">
											<?php if ( ! empty( $item['tab_title_link']['url'] ) ) : ?>
												<a <?php echo $this->get_render_attribute_string( $link_setting_key ); ?>>
											<?php endif; ?>												

												<?php echo esc_html( $item['tab_title'] ); ?>
												
												<?php if ( 'headings' === $settings['tabs_style'] ) : ?> 
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg>
												<?php endif; ?>

											<?php if ( ! empty( $item['tab_title_link']['url'] ) ) : ?>
												</a>
											<?php endif; ?>
										</span>
									</li>
								<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
				
				<div class="envision-blocks-vertical-tabs__col envision-blocks-vertical-tabs__col--right">
					<div class="envision-blocks-vertical-tabs__ratio"></div>
					<?php
					foreach ( $settings['tabs'] as $index => $item ) :
						$image_setting_key = $this->get_repeater_setting_key( 'tab_image', 'tabs', $index );
						?>
						
						<?php if ( ! empty( $item['tab_image'] ) ) : ?>
							<?php if ( 0 === $index ) : ?>							
								<?php echo wp_get_attachment_image( $item['tab_image']['id'], $settings['image_size'], '', array( 'class' => 'envision-blocks-vertical-tabs__img envision-blocks-vertical-tabs__img--active' ) ); ?>
							<?php else : ?>
								<?php echo wp_get_attachment_image( $item['tab_image']['id'], $settings['image_size'], '', array( 'class' => 'envision-blocks-vertical-tabs__img' ) ); ?>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

			</div>
		</div>

		<?php
	}
}
