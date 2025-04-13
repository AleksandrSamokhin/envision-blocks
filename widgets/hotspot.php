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
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Hotspot extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-hotspot', ENVISION_BLOCKS_URL . 'assets/css/hotspot.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-hotspot', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-hotspot', ENVISION_BLOCKS_URL . 'assets/js/view/hotspot.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-hotspot';
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
		return esc_html__( 'Hotspot', 'envision-blocks' );
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
		return 'eicon-image-hotspot envision-blocks-icon';
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
		return array( 'envision-blocks-hotspot' );
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
		return array( 'envision-blocks-hotspot' );
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
		return array( 'hotspot', 'image', 'tooltip' );
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
		$this->section_image();
		$this->section_hotspot();
		$this->section_tooltip();

		$this->section_image_style();
		$this->section_hotspot_style();
		$this->section_tooltip_style();
	}


	/**
	 * Content > Image.
	 */
	private function section_image() {
		$this->start_controls_section(
			'section_image',
			array(
				'label' => esc_html__( 'Image', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Choose Image', 'envision-blocks' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'envision-blocks' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Hotspot.
	 */
	private function section_hotspot() {
		$this->start_controls_section(
			'section_hotspot',
			array(
				'label' => esc_html__( 'Hotspot', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'hotspot_repeater' );

		$repeater->start_controls_tab(
			'hotspot_content_tab',
			array(
				'label' => esc_html__( 'Content', 'envision-blocks' ),
			)
		);

		$repeater->add_control(
			'hotspot_label',
			array(
				'label'       => esc_html__( 'Label', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'hotspot_link',
			array(
				'label'       => esc_html__( 'Link', 'envision-blocks' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'envision-blocks' ),
			)
		);

		$repeater->add_control(
			'hotspot_icon',
			array(
				'label'       => esc_html__( 'Icon', 'envision-blocks' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$repeater->add_control(
			'hotspot_icon_position',
			array(
				'label'                => esc_html__( 'Icon Position', 'envision-blocks' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start' => array(
						'title' => esc_html__( 'Icon Start', 'envision-blocks' ),
						'icon'  => 'eicon-h-align-left',
					),
					'end'   => array(
						'title' => esc_html__( 'Icon End', 'envision-blocks' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'start' => 'grid-column: 1;',
					'end'   => 'grid-column: 2;',
				),
				'condition'            => array(
					'hotspot_icon[value]!'  => '',
					'hotspot_label[value]!' => '',
				),
				'selectors'            => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .envision-blocks-hotspot__icon' => '{{VALUE}}',
				),
				'default'              => 'start',
			)
		);

		$repeater->add_control(
			'hotspot_icon_spacing',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => '5',
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .envision-blocks-hotspot__button' =>
							'grid-gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'hotspot_icon[value]!'  => '',
					'hotspot_label[value]!' => '',
				),
			)
		);

		$repeater->add_control(
			'hotspot_custom_size',
			array(
				'label'       => esc_html__( 'Custom Hotspot Size', 'envision-blocks' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Off', 'envision-blocks' ),
				'label_on'    => esc_html__( 'On', 'envision-blocks' ),
				'default'     => 'no',
				'description' => esc_html__( 'Set custom Hotspot size that will only affect this specific hotspot.', 'envision-blocks' ),
			)
		);

		$repeater->add_control(
			'hotspot_width',
			array(
				'label'      => esc_html__( 'Min Width', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--hotspot-min-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'hotspot_custom_size' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'hotspot_height',
			array(
				'label'      => esc_html__( 'Min Height', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--hotspot-min-height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'hotspot_custom_size' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'hotspot_tooltip_content',
			array(
				'render_type' => 'template',
				'label'       => esc_html__( 'Tooltip Content', 'envision-blocks' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'Add Your Tooltip Text Here', 'envision-blocks' ),
			)
		);

		$repeater->add_control(
			'hotspot_tooltip_price_content',
			array(
				'render_type' => 'template',
				'label'       => esc_html__( 'Tooltip Price Content', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '$99', 'envision-blocks' ),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'hotspot_position_tab',
			array(
				'label' => esc_html__( 'POSITION', 'envision-blocks' ),
			)
		);

		$repeater->add_control(
			'hotspot_horizontal',
			array(
				'label'   => esc_html__( 'Horizontal Orientation', 'envision-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'right' : 'left',
				'options' => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'envision-blocks' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'envision-blocks' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'toggle'  => false,
			)
		);

		$repeater->add_responsive_control(
			'hotspot_offset_x',
			array(
				'label'      => esc_html__( 'Offset', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => '50',
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' =>
							'{{hotspot_horizontal.VALUE}}: {{SIZE}}%; --hotspot-translate-x: {{SIZE}}%;',
				),
			)
		);

		$repeater->add_control(
			'hotspot_vertical',
			array(
				'label'   => esc_html__( 'Vertical Orientation', 'envision-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default' => 'top',
				'toggle'  => false,
			)
		);

		$repeater->add_responsive_control(
			'hotspot_offset_y',
			array(
				'label'      => esc_html__( 'Offset', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => '50',
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' =>
							'{{hotspot_vertical.VALUE}}: {{SIZE}}%; --hotspot-translate-y: {{SIZE}}%;',
				),
			)
		);

		$repeater->add_control(
			'hotspot_tooltip_position',
			array(
				'label'       => esc_html__( 'Custom Tooltip Properties', 'envision-blocks' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Off', 'envision-blocks' ),
				'label_on'    => esc_html__( 'On', 'envision-blocks' ),
				'default'     => 'no',
				'description' => sprintf( esc_html__( 'Set custom Tooltip opening that will only affect this specific hotspot.', 'envision-blocks' ), '<code>|</code>' ),
			)
		);

		$repeater->add_control(
			'hotspot_heading',
			array(
				'label'     => esc_html__( 'Box', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'hotspot_tooltip_position' => 'yes',
				),
			)
		);

		$repeater->add_responsive_control(
			'hotspot_position',
			array(
				'label'       => esc_html__( 'Position', 'envision-blocks' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'right'  => array(
						'title' => esc_html__( 'Left', 'envision-blocks' ),
						'icon'  => 'eicon-h-align-left',
					),
					'bottom' => array(
						'title' => esc_html__( 'Top', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-top',
					),
					'left'   => array(
						'title' => esc_html__( 'Right', 'envision-blocks' ),
						'icon'  => 'eicon-h-align-right',
					),
					'top'    => array(
						'title' => esc_html__( 'Bottom', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .envision-blocks-hotspot--tooltip-position' => 'right: initial;bottom: initial;left: initial;top: initial;{{VALUE}}: calc(100% + 10px );',
				),
				'condition'   => array(
					'hotspot_tooltip_position' => 'yes',
				),
				'render_type' => 'template',
			)
		);

		$repeater->add_responsive_control(
			'hotspot_tooltip_width',
			array(
				'label'      => esc_html__( 'Min Width', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2000,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .envision-blocks-hotspot__tooltip' => 'min-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'hotspot_tooltip_position' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'hotspot_tooltip_text_wrap',
			array(
				'label'     => esc_html__( 'Text Wrap', 'envision-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'envision-blocks' ),
				'label_on'  => esc_html__( 'On', 'envision-blocks' ),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--white-space: normal',
				),
				'default'   => 'yes',
				'condition' => array(
					'hotspot_tooltip_position' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'hotspot',
			array(
				'label'              => esc_html__( 'Hotspot', 'envision-blocks' ),
				'type'               => Controls_Manager::REPEATER,
				'fields'             => $repeater->get_controls(),
				'title_field'        => '{{{ hotspot_label }}}',
				'default'            => array(
					array(
						// Default #1 circle
					),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'hotspot_animation',
			array(
				'label'     => esc_html__( 'Animation', 'envision-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'envision-blocks-hotspot--soft-beat' => esc_html__( 'Soft Beat', 'envision-blocks' ),
					'envision-blocks-hotspot--expand'    => esc_html__( 'Expand', 'envision-blocks' ),
					'envision-blocks-hotspot--overlay'   => esc_html__( 'Overlay', 'envision-blocks' ),
					''                                   => esc_html__( 'None', 'envision-blocks' ),
				),
				'default'   => 'envision-blocks-hotspot--soft-beat',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hotspot_sequenced_animation',
			array(
				'label'              => esc_html__( 'Sequenced Animation', 'envision-blocks' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => esc_html__( 'Off', 'envision-blocks' ),
				'label_on'           => esc_html__( 'On', 'envision-blocks' ),
				'default'            => 'no',
				'frontend_available' => true,
				'render_type'        => 'none',
			)
		);

		$this->add_control(
			'hotspot_sequenced_animation_duration',
			array(
				'label'              => esc_html__( 'Sequence Duration (ms)', 'envision-blocks' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min' => 100,
						'max' => 20000,
					),
				),
				'condition'          => array(
					'hotspot_sequenced_animation' => 'yes',
				),
				'frontend_available' => true,
				'render_type'        => 'ui',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Tooltip.
	 */
	private function section_tooltip() {
		$this->start_controls_section(
			'section_tooltip',
			array(
				'label' => esc_html__( 'Tooltip', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'tooltip_position',
			array(
				'label'              => esc_html__( 'Position', 'envision-blocks' ),
				'type'               => Controls_Manager::CHOOSE,
				'default'            => 'top',
				'toggle'             => false,
				'options'            => array(
					'right'  => array(
						'title' => esc_html__( 'Left', 'envision-blocks' ),
						'icon'  => 'eicon-h-align-left',
					),
					'bottom' => array(
						'title' => esc_html__( 'Top', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-top',
					),
					'left'   => array(
						'title' => esc_html__( 'Right', 'envision-blocks' ),
						'icon'  => 'eicon-h-align-right',
					),
					'top'    => array(
						'title' => esc_html__( 'Bottom', 'envision-blocks' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'          => array(
					'{{WRAPPER}} .envision-blocks-hotspot--tooltip-position' => 'right: initial;bottom: initial;left: initial;top: initial;{{VALUE}}: calc(100% + 10px );',
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'tooltip_trigger',
			array(
				'label'              => esc_html__( 'Trigger', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'mouseenter' => esc_html__( 'Hover', 'envision-blocks' ),
					'click'      => esc_html__( 'Click', 'envision-blocks' ),
					'none'       => esc_html__( 'None', 'envision-blocks' ),
				),
				'default'            => 'mouseenter',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'tooltip_animation',
			array(
				'label'              => esc_html__( 'Animation', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'envision-blocks-hotspot--fade-in-out' => esc_html__( 'Fade In/Out', 'envision-blocks' ),
					'envision-blocks-hotspot--fade-grow'   => esc_html__( 'Fade Grow', 'envision-blocks' ),
					'envision-blocks-hotspot--fade-direction' => esc_html__( 'Fade By Direction', 'envision-blocks' ),
					'envision-blocks-hotspot--slide-direction' => esc_html__( 'Slide By Direction', 'envision-blocks' ),
				),
				'default'            => 'envision-blocks-hotspot--fade-in-out',
				'placeholder'        => esc_html__( 'Enter your image caption', 'envision-blocks' ),
				'condition'          => array(
					'tooltip_trigger!' => 'none',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'tooltip_animation_duration',
			array(
				'label'     => esc_html__( 'Duration (ms)', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--tooltip-transition-duration: {{SIZE}}ms;',
				),
				'condition' => array(
					'tooltip_trigger!' => 'none',
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
				'label' => esc_html__( 'Image', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => esc_html__( 'Width', 'envision-blocks' ),
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
					'{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label'          => esc_html__( 'Max Width', 'envision-blocks' ),
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
					'{{WRAPPER}} img' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => esc_html__( 'Height', 'envision-blocks' ),
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
					'{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => esc_html__( 'Object Fit', 'envision-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'height[size]!' => '',
				),
				'options'   => array(
					''        => esc_html__( 'Default', 'envision-blocks' ),
					'fill'    => esc_html__( 'Fill', 'envision-blocks' ),
					'cover'   => esc_html__( 'Cover', 'envision-blocks' ),
					'contain' => esc_html__( 'Contain', 'envision-blocks' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'object-position',
			array(
				'label'     => esc_html__( 'Object Position', 'envision-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'center center' => esc_html__( 'Center Center', 'envision-blocks' ),
					'center left'   => esc_html__( 'Center Left', 'envision-blocks' ),
					'center right'  => esc_html__( 'Center Right', 'envision-blocks' ),
					'top center'    => esc_html__( 'Top Center', 'envision-blocks' ),
					'top left'      => esc_html__( 'Top Left', 'envision-blocks' ),
					'top right'     => esc_html__( 'Top Right', 'envision-blocks' ),
					'bottom center' => esc_html__( 'Bottom Center', 'envision-blocks' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'envision-blocks' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'envision-blocks' ),
				),
				'default'   => 'center center',
				'selectors' => array(
					'{{WRAPPER}} img' => 'object-position: {{VALUE}};',
				),
				'condition' => array(
					'object-fit' => 'cover',
				),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} img',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} img',
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
					'{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} img',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Hotspot.
	 */
	private function section_hotspot_style() {
		$this->start_controls_section(
			'section_hotspot_style',
			array(
				'label' => esc_html__( 'Hotspot', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style_hotspot_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--hotspot-color: {{VALUE}};',
				),
				'default'   => '#ffffff',
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
			)
		);

		$this->add_control(
			'style_hotspot_outer_color',
			array(
				'label'       => esc_html__( 'Outer Color', 'envision-blocks' ),
				'description' => esc_html__( 'Outer color of circle', 'envision-blocks' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#13163FCC',
				'selectors'   => array(
					'{{WRAPPER}}' => '--hotspot-outer-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'style_hotspot_size',
			array(
				'label'      => esc_html__( 'Size', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 300,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--hotspot-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'style_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-hotspot__label',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_responsive_control(
			'style_hotspot_width',
			array(
				'label'      => esc_html__( 'Min Width', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--hotspot-min-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'style_hotspot_height',
			array(
				'label'      => esc_html__( 'Min Height', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--hotspot-min-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'style_hotspot_box_color',
			array(
				'label'     => esc_html__( 'Box Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--hotspot-box-color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
			)
		);

		$this->add_responsive_control(
			'style_hotspot_padding',
			array(
				'label'      => esc_html__( 'Padding', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--hotspot-padding: {{SIZE}}{{UNIT}};',
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
			)
		);

		$this->add_control(
			'style_hotspot_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--hotspot-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'unit' => 'px',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'style_hotspot_box_shadow',
				'selector' => '
					{{WRAPPER}} .envision-blocks-hotspot:not(.envision-blocks-hotspot--circle) .envision-blocks-hotspot__button,
					{{WRAPPER}} .envision-blocks-hotspot.envision-blocks-hotspot--circle .envision-blocks-hotspot__button .envision-blocks-hotspot__outer-circle
				',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Tooltip.
	 */
	private function section_tooltip_style() {
		$this->start_controls_section(
			'section_tooltip_style',
			array(
				'label' => esc_html__( 'Tooltip', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style_tooltip_text_wrap',
			array(
				'label'     => esc_html__( 'Text Wrap', 'envision-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'envision-blocks' ),
				'label_on'  => esc_html__( 'On', 'envision-blocks' ),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-hotspot__tooltip' => '--white-space: normal',
				),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'style_tooltip_heading_title',
			array(
				'label'     => esc_html__( 'Title', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'style_tooltip_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} :where( h1, h2, h3, h4, h5, h6 )' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'style_tooltip_heading_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-hotspot__tooltip :where( h1, h2, h3, h4, h5, h6 )',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
			)
		);

		$this->add_control(
			'style_tooltip_text_title',
			array(
				'label'     => esc_html__( 'Text', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'style_tooltip_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tooltip-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'style_tooltip_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-hotspot__tooltip',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
			)
		);

		$this->add_responsive_control(
			'style_tooltip_align',
			array(
				'label'     => esc_html__( 'Alignment', 'envision-blocks' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
						'title' => esc_html__( 'Justified', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--tooltip-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'style_tooltip_price_title',
			array(
				'label'     => esc_html__( 'Price', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'style_tooltip_price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tooltip-price-color: {{VALUE}};',
				),
				'default'   => '#131740',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'style_tooltip_price_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-hotspot__tooltip-price',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
			)
		);

		$this->add_control(
			'style_tooltip_heading',
			array(
				'label'     => esc_html__( 'Box', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'style_tooltip_width',
			array(
				'label'      => esc_html__( 'Width', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2000,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 180,
					'unit' => 'px',
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--tooltip-min-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'style_tooltip_padding',
			array(
				'label'      => esc_html__( 'Padding', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--tooltip-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'unit'   => 'px',
					'left'   => '16',
					'top'    => '16',
					'right'  => '16',
					'bottom' => '12',
				),
			)
		);

		$this->add_control(
			'style_tooltip_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tooltip-color: {{VALUE}}',
				),
				'default'   => '#ffffff',
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
			)
		);

		$this->add_control(
			'style_tooltip_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--tooltip-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'unit'   => 'px',
					'left'   => '8',
					'top'    => '8',
					'right'  => '8',
					'bottom' => '8',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'style_tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .envision-blocks-hotspot__tooltip',
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

		$is_tooltip_direction_animation = 'envision-blocks-hotspot--slide-direction' === $settings['tooltip_animation'] || 'envision-blocks-hotspot--fade-direction' === $settings['tooltip_animation'];
		$show_tooltip                   = 'none' === $settings['tooltip_trigger'];
		$sequenced_animation_class      = 'yes' === $settings['hotspot_sequenced_animation'] ? 'envision-blocks-hotspot--sequenced' : '';

		// Main Image
		Group_Control_Image_Size::print_attachment_image_html( $settings, 'image', 'image' );

		// Hotspot
		foreach ( $settings['hotspot'] as $key => $hotspot ) :
			$is_circle           = ! $hotspot['hotspot_label'] && ! $hotspot['hotspot_icon']['value'];
			$is_only_icon        = ! $hotspot['hotspot_label'] && $hotspot['hotspot_icon']['value'];
			$hotspot_position_x  = '%' === $hotspot['hotspot_offset_x']['unit'] ? 'envision-blocks-hotspot--position-' . $hotspot['hotspot_horizontal'] : '';
			$hotspot_position_y  = '%' === $hotspot['hotspot_offset_y']['unit'] ? 'envision-blocks-hotspot--position-' . $hotspot['hotspot_vertical'] : '';
			$is_hotspot_link     = ! empty( $hotspot['hotspot_link']['url'] );
			$hotspot_element_tag = $is_hotspot_link ? 'a' : 'div';

			// hotspot attributes
			$hotspot_repeater_setting_key = $this->get_repeater_setting_key( 'hotspot', 'hotspots', $key );
			$this->add_render_attribute(
				$hotspot_repeater_setting_key,
				array(
					'class' => array(
						'envision-blocks-hotspot',
						'elementor-repeater-item-' . $hotspot['_id'],
						$sequenced_animation_class,
						$hotspot_position_x,
						$hotspot_position_y,
						$is_hotspot_link ? 'envision-blocks-hotspot--link' : '',
						( 'click' === $settings['tooltip_trigger'] && $is_hotspot_link ) ? 'envision-blocks-hotspot--no-tooltip' : '',
					),
				)
			);
			if ( $is_circle ) {
				$this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'envision-blocks-hotspot--circle' );
			}
			if ( $is_only_icon ) {
				$this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'envision-blocks-hotspot--icon' );
			}

			if ( $is_hotspot_link ) {
				$this->add_link_attributes( $hotspot_repeater_setting_key, $hotspot['hotspot_link'] );
			}

			// hotspot trigger attributes
			$trigger_repeater_setting_key = $this->get_repeater_setting_key( 'trigger', 'hotspots', $key );
			$this->add_render_attribute(
				$trigger_repeater_setting_key,
				array(
					'class' => array(
						'envision-blocks-hotspot__button',
						$settings['hotspot_animation'],
					),
				)
			);

			// direction mask attributes
			$direction_mask_repeater_setting_key = $this->get_repeater_setting_key( 'envision-blocks-hotspot__direction-mask', 'hotspots', $key );
			$this->add_render_attribute(
				$direction_mask_repeater_setting_key,
				array(
					'class' => array(
						'envision-blocks-hotspot__direction-mask',
						( $is_tooltip_direction_animation ) ? 'envision-blocks-hotspot--tooltip-position' : '',
					),
				)
			);

			// tooltip attributes
			$tooltip_custom_position      = ( $is_tooltip_direction_animation && $hotspot['hotspot_tooltip_position'] && $hotspot['hotspot_position'] ) ? 'envision-blocks-hotspot--override-tooltip-animation-from-' . $hotspot['hotspot_position'] : '';
			$tooltip_repeater_setting_key = $this->get_repeater_setting_key( 'tooltip', 'hotspots', $key );
			$this->add_render_attribute(
				$tooltip_repeater_setting_key,
				array(
					'class' => array(
						'envision-blocks-hotspot__tooltip',
						( $show_tooltip ) ? 'envision-blocks-hotspot--show-tooltip' : '',
						( ! $is_tooltip_direction_animation ) ? 'envision-blocks-hotspot--tooltip-position' : '',
						( ! $show_tooltip ) ? $settings['tooltip_animation'] : '',
						$tooltip_custom_position,
					),
				)
			); ?>

			<?php // Hotspot ?>
			<<?php Utils::print_validated_html_tag( $hotspot_element_tag ); ?> <?php $this->print_render_attribute_string( $hotspot_repeater_setting_key ); ?>>

				<?php // Hotspot Trigger ?>
				<div <?php $this->print_render_attribute_string( $trigger_repeater_setting_key ); ?>>
					<?php if ( $is_circle ) : ?>
						<div class="envision-blocks-hotspot__outer-circle"></div>
						<div class="envision-blocks-hotspot__inner-circle"></div>
					<?php else : ?>
						<?php if ( $hotspot['hotspot_icon']['value'] ) : ?>
							<div class="envision-blocks-hotspot__icon"><?php Icons_Manager::render_icon( $hotspot['hotspot_icon'] ); ?></div>
						<?php endif; ?>
						<?php if ( $hotspot['hotspot_label'] ) : ?>
							<div class="envision-blocks-hotspot__label">
							<?php
								// PHPCS - the main text of a widget should not be escaped.
								echo wp_kses_post( $hotspot['hotspot_label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<?php // Hotspot Tooltip ?>
				<?php if ( $hotspot['hotspot_tooltip_content'] && ! ( 'click' === $settings['tooltip_trigger'] && $is_hotspot_link ) ) : ?>
					<?php if ( $is_tooltip_direction_animation ) : ?>
						<div <?php $this->print_render_attribute_string( $direction_mask_repeater_setting_key ); ?>>
					<?php endif; ?>
					<div <?php $this->print_render_attribute_string( $tooltip_repeater_setting_key ); ?> >
						<?php
						// PHPCS - the main text of a widget should not be escaped.
						echo wp_kses_post( $hotspot['hotspot_tooltip_content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<span class="envision-blocks-hotspot__tooltip-price">' . wp_kses_post( $hotspot['hotspot_tooltip_price_content'] ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
					<?php if ( $is_tooltip_direction_animation ) : ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

			</<?php Utils::print_validated_html_tag( $hotspot_element_tag ); ?>>

			<?php
		endforeach;
	}

	/**
	 * Render Hotspot widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  2.9.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		const image = {
			id: settings.image.id,
			url: settings.image.url,
			size: settings.image_size,
			dimension: settings.image_custom_dimension,
			model: view.getEditModel()
		};

		const imageUrl = elementor.imagesManager.getImageUrl( image );

		#>
		<img src="{{ imageUrl }}" title="" alt="">
		<#
		const isTooltipDirectionAnimation = (settings.tooltip_animation==='envision-blocks-hotspot--slide-direction' || settings.tooltip_animation==='envision-blocks-hotspot--fade-direction' ) ? true : false;
		const showTooltip = ( settings.tooltip_trigger === 'none' );

		_.each( settings.hotspot, ( hotspot, index ) => {
			const iconHTML = elementor.helpers.renderIcon( view, hotspot.hotspot_icon, {}, 'i' , 'object' );

			const isCircle = !hotspot.hotspot_label && !hotspot.hotspot_icon.value;
			const isOnlyIcon = !hotspot.hotspot_label && hotspot.hotspot_icon.value;
			const hotspotPositionX = '%' === hotspot.hotspot_offset_x.unit ? 'envision-blocks-hotspot--position-' + hotspot.hotspot_horizontal : '';
			const hotspotPositionY = '%' === hotspot.hotspot_offset_y.unit ? 'envision-blocks-hotspot--position-' + hotspot.hotspot_vertical : '';
			const hotspotLink = hotspot.hotspot_link.url;
			const hotspotElementTag = hotspotLink ? 'a': 'div';

			// hotspot attributes
			const hotspotRepeaterSettingKey = view.getRepeaterSettingKey( 'hotspot', 'hotspots', index );
			view.addRenderAttribute( hotspotRepeaterSettingKey, {
				'class' : [
					'envision-blocks-hotspot',
					'elementor-repeater-item-' + hotspot._id,
					hotspotPositionX,
					hotspotPositionY,
					hotspotLink ? 'envision-blocks-hotspot--link' : '',,
				]
			});

			if ( isCircle ) {
				view.addRenderAttribute( hotspotRepeaterSettingKey, 'class', 'envision-blocks-hotspot--circle' );
			}

			if ( isOnlyIcon ) {
				view.addRenderAttribute( hotspotRepeaterSettingKey, 'class', 'envision-blocks-hotspot--icon' );
			}

			// hotspot trigger attributes
			const triggerRepeaterSettingKey = view.getRepeaterSettingKey( 'trigger', 'hotspots', index );
			view.addRenderAttribute(triggerRepeaterSettingKey, {
				'class' : [
					'envision-blocks-hotspot__button',
					settings.hotspot_animation,
					//'hotspot-trigger-' + hotspot.hotspot_icon_position
				]
			});

			//direction mask attributes
			const directionMaskRepeaterSettingKey = view.getRepeaterSettingKey( 'envision-blocks-hotspot__direction-mask', 'hotspots', index );
			view.addRenderAttribute(directionMaskRepeaterSettingKey, {
				'class' : [
					'envision-blocks-hotspot__direction-mask',
					( isTooltipDirectionAnimation ) ? 'envision-blocks-hotspot--tooltip-position' : ''
				]
			});

			//tooltip attributes
			const tooltipCustomPosition = ( isTooltipDirectionAnimation && hotspot.hotspot_tooltip_position && hotspot.hotspot_position ) ? 'envision-blocks-hotspot--override-tooltip-animation-from-' + hotspot.hotspot_position : '';
			const tooltipRepeaterSettingKey = view.getRepeaterSettingKey('tooltip', 'hotspots', index);
			view.addRenderAttribute( tooltipRepeaterSettingKey, {
				'class': [
					'envision-blocks-hotspot__tooltip',
					( showTooltip ) ? 'envision-blocks-hotspot--show-tooltip' : '',
					( !isTooltipDirectionAnimation ) ? 'envision-blocks-hotspot--tooltip-position' : '',
					( !showTooltip ) ? settings.tooltip_animation : '',
					tooltipCustomPosition
				],
			});

			#>
			<{{{ hotspotElementTag }}} {{{ view.getRenderAttributeString( hotspotRepeaterSettingKey ) }}}>

					<?php // Hotspot Trigger ?>
					<div {{{ view.getRenderAttributeString( triggerRepeaterSettingKey ) }}}>
						<# if ( isCircle ) { #>
						<div class="envision-blocks-hotspot__outer-circle"></div>
						<div class="envision-blocks-hotspot__inner-circle"></div>
						<# } else { #>
						<# if (hotspot.hotspot_icon.value){ #>
						<div class="envision-blocks-hotspot__icon">{{{ iconHTML.value }}}</div>
						<# } #>
						<# if ( hotspot.hotspot_label ){ #>
						<div class="envision-blocks-hotspot__label">{{{ hotspot.hotspot_label }}}</div>
						<# } #>
						<# } #>
					</div>

					<?php // Hotspot Tooltip ?>
					<# if( hotspot.hotspot_tooltip_content && ! ( 'click' === settings.tooltip_trigger && hotspotLink ) ){ #>
					<# if( isTooltipDirectionAnimation ){ #>
					<div {{{ view.getRenderAttributeString( directionMaskRepeaterSettingKey ) }}}>
						<# } #>
						<div {{{ view.getRenderAttributeString( tooltipRepeaterSettingKey ) }}}>
							{{{ hotspot.hotspot_tooltip_content }}}
							<span class="envision-blocks-hotspot__tooltip-price">{{{ hotspot.hotspot_tooltip_price_content }}}</span>
						</div>
						<# if( isTooltipDirectionAnimation ){ #>
					</div>
					<# } #>
					<# } #>

			</{{{ hotspotElementTag }}}>
		<# }); #>
		<?php
	}
}
