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
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Graph extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_script( 'envision-blocks-graph', ENVISION_BLOCKS_URL . 'assets/js/view/graph.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-graph';
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
		return esc_html__( 'Graph', 'envision-blocks' );
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
		return 'eicon-skill-bar envision-blocks-icon';
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
		return array( 'chart-js', 'envision-blocks-graph' );
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
		return array( 'bar', 'progress', 'chart', 'graph', 'pie' );
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
		$this->section_graph_style();
		$this->section_legend_style();
		$this->section_ticks_style();
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

		$repeater = new Repeater();

		$repeater->add_control(
			'graph_dataset_values',
			array(
				'label'   => esc_html__( 'Dataset Values', 'envision-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '30,20,30,60,20', 'envision-blocks' ),
				'dynamic' => false,
			)
		);

		$repeater->add_control(
			'graph_dataset_labels',
			array(
				'label'   => esc_html__( 'Dataset Labels', 'envision-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Example Label 1', 'envision-blocks' ),
				'dynamic' => false,
			)
		);

		$repeater->add_control(
			'graph_border_color',
			array(
				'label'     => esc_html__( 'Line Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#131740',
				'separator' => 'before',
			)
		);

		$repeater->add_control(
			'graph_hover_border_color',
			array(
				'label'       => esc_html__( 'Hover Line Color', 'envision-blocks' ),
				'description' => esc_html__( 'Only for bar graphs', 'envision-blocks' ),
				'type'        => Controls_Manager::COLOR,
			)
		);

		$repeater->add_control(
			'graph_filling_modes',
			array(
				'label'       => esc_html__( 'Filling Modes', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Only for line graphs. Ex. values: origin, -1, -2, +1, +2 ...', 'envision-blocks' ),
				'separator'   => 'before',
				'dynamic'     => false,
			)
		);

		$repeater->add_control(
			'graph_background_color',
			array(
				'label'   => esc_html__( 'Bar Color', 'envision-blocks' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			)
		);

		$repeater->add_control(
			'graph_hover_background_color',
			array(
				'label'       => esc_html__( 'Hover Bar Color', 'envision-blocks' ),
				'description' => esc_html__( 'Only for bar graphs', 'envision-blocks' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
			)
		);

		$repeater->add_control(
			'graph_linear',
			array(
				'label'       => esc_html__( 'Linear Mode', 'envision-blocks' ),
				'description' => esc_html__( 'Only for line graphs', 'envision-blocks' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
			)
		);

		$this->add_control(
			'graphs',
			array(
				'label'       => esc_html__( 'Datasets', 'envision-blocks' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'graph_dataset_values'   => esc_html__( '30,20,30,80,20', 'envision-blocks' ),
						'graph_dataset_labels'   => esc_html__( 'Hydro Power', 'envision-blocks' ),
						'graph_border_color'     => '#131740',
						'graph_background_color' => '#131740',

					),
					array(
						'graph_dataset_values'   => esc_html__( '40,50,40,30,50', 'envision-blocks' ),
						'graph_dataset_labels'   => esc_html__( 'Solar Panel', 'envision-blocks' ),
						'graph_border_color'     => '#FFE168',
						'graph_background_color' => '#FFE168',
					),
					array(
						'graph_dataset_values'   => esc_html__( '50,20,30,50,70', 'envision-blocks' ),
						'graph_dataset_labels'   => esc_html__( 'Wind Power', 'envision-blocks' ),
						'graph_border_color'     => '#009f00',
						'graph_background_color' => '#009f00',
					),
				),
				'title_field' => '{{{ graph_dataset_labels }}}',
			)
		);

		$this->add_control(
			'graph_data_labels',
			array(
				'label'       => esc_html__( 'Data Labels', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'     => esc_html__( '2022, 2023, 2024, 2025', 'envision-blocks' ),
				'description' => esc_html__( 'Separate labels with commas', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'graph_type',
			array(
				'label'   => esc_html__( 'Graph Type', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bar',
				'options' => array(
					'line' => esc_html__( 'Line', 'envision-blocks' ),
					'bar'  => esc_html__( 'Bar', 'envision-blocks' ),
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Graph.
	 */
	private function section_graph_style() {
		$this->start_controls_section(
			'section_graph_style',
			array(
				'label' => esc_html__( 'Graph', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'graph_size',
			array(
				'label'      => esc_html__( 'Graph Size', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 1000,
					),
				),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-graph__canvas' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'graph_aspect_ratio',
			array(
				'label'              => esc_html__( 'Graph Aspect Ratio', 'envision-blocks' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'graph_align',
			array(
				'label'     => esc_html__( 'Graph Alignment', 'envision-blocks' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-graph__canvas' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_width',
			array(
				'type'  => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Border Width', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'hover_border_width',
			array(
				'label'     => esc_html__( 'Hover Border Width', 'envision-blocks' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => array( 'graph_type' => 'bar' ),
			)
		);

		$this->add_control(
			'bar_size',
			array(
				'label'      => esc_html__( 'Bar Size', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'responsive' => true,
				'default'    => array(
					'size' => 0.9,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'size_units' => array( 'px' ),
				'condition'  => array( 'graph_type' => 'bar' ),
			)
		);

		$this->add_control(
			'cat_size',
			array(
				'label'      => esc_html__( 'Category Size', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'responsive' => true,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'size_units' => array( 'px' ),
				'condition'  => array( 'graph_type' => 'bar' ),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Legend.
	 */
	private function section_legend_style() {
		$this->start_controls_section(
			'section_legend_style',
			array(
				'label' => esc_html__( 'Legend', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'enable_chart_legend',
			array(
				'label'   => esc_html__( 'Enable Chart Legend', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'legend_position',
			array(
				'label'   => esc_html__( 'Legend Position', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'top'    => esc_html__( 'Top', 'envision-blocks' ),
					'left'   => esc_html__( 'Left', 'envision-blocks' ),
					'bottom' => esc_html__( 'Bottom', 'envision-blocks' ),
					'right'  => esc_html__( 'Right', 'envision-blocks' ),
				),
			)
		);

		$this->add_control(
			'legend_alignment',
			array(
				'label'   => esc_html__( 'Legend Alignment', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''       => esc_html__( 'Default', 'envision-blocks' ),
					'start'  => esc_html__( 'Start', 'envision-blocks' ),
					'center' => esc_html__( 'Center', 'envision-blocks' ),
					'end'    => esc_html__( 'End', 'envision-blocks' ),
				),
			)
		);

		$this->add_control(
			'legend_bar_width',
			array(
				'label'     => esc_html__( 'Legend Bar Width', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'legend_bar_height',
			array(
				'label' => esc_html__( 'Legend Bar Height', 'envision-blocks' ),
				'type'  => Controls_Manager::SLIDER,
			)
		);

		$this->add_control(
			'legend_bar_margin',
			array(
				'label' => esc_html__( 'Legend Bar Margin', 'envision-blocks' ),
				'type'  => Controls_Manager::SLIDER,
			)
		);

		$this->add_control(
			'legend_label_color',
			array(
				'label'       => esc_html__( 'Legend Label Color', 'envision-blocks' ),
				'description' => esc_html__( 'Only for bar graphs', 'envision-blocks' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'default'     => '',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'    => 'legend_label_font',
				'dynamic' => true,
			)
		);

		$this->add_control(
			'legend_label_font_size',
			array(
				'label' => esc_html__( 'Legend Label Font Size', 'envision-blocks' ),
				'type'  => Controls_Manager::SLIDER,
			)
		);

		$this->add_control(
			'legend_label_font_weight',
			array(
				'label'   => esc_html__( 'Legend Label Font Weight', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'100' => esc_html__( 'Thin (100)', 'envision-blocks' ),
					'200' => esc_html__( 'Extra Light (200)', 'envision-blocks' ),
					'300' => esc_html__( 'Light (300)', 'envision-blocks' ),
					'400' => esc_html__( 'Normal (400)', 'envision-blocks' ),
					'500' => esc_html__( 'Medium (500)', 'envision-blocks' ),
					'600' => esc_html__( 'Semi Bold (600)', 'envision-blocks' ),
					'700' => esc_html__( 'Bold (700)', 'envision-blocks' ),
					'800' => esc_html__( 'Extra Bold (800)', 'envision-blocks' ),
					'900' => esc_html__( 'Black (900)', 'envision-blocks' ),
					''    => esc_html__( 'Default', 'envision-blocks' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Ticks.
	 */
	private function section_ticks_style() {
		$this->start_controls_section(
			'section_ticks_style',
			array(
				'label' => esc_html__( 'Ticks', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ticks_min',
			array(
				'type'  => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Minimum Data Value', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'ticks_max',
			array(
				'type'  => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Maximum Data Value', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'ticks_step',
			array(
				'type'  => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Step Size', 'envision-blocks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'ticks_font',
			)
		);

		$this->add_control(
			'ticks_label_color',
			array(
				'label'   => esc_html__( 'Label Color', 'envision-blocks' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#5a5d79',
			)
		);

		$this->add_control(
			'ticks_grid_lines_color',
			array(
				'label'   => esc_html__( 'Grid Lines Color', 'envision-blocks' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#e7e8ec',
			)
		);

		$this->end_controls_section();
	}

		/**
		 * Get data attributes
		 *
		 * @return Array JSON encoded array of data
		 */
	private function get_data_attrs( $settings ) {
		$data = array();
		$temp = array();

		foreach ( $settings['graphs'] as $index => $item ) {
			$temp['data-values'][]                  = ! empty( $item['graph_dataset_values'] ) ? $item['graph_dataset_values'] : 0;
			$temp['data-item-labels'][]             = ! empty( $item['graph_dataset_labels'] ) ? $item['graph_dataset_labels'] : '';
			$temp['data-background-colors'][]       = ! empty( $item['graph_background_color'] ) ? $item['graph_background_color'] : '#131740';
			$temp['data-hover-background-colors'][] = ! empty( $item['graph_hover_background_color'] ) ? $graph['item_hover_background_color'] : '#131740';
			$temp['data-border-colors'][]           = ! empty( $item['graph_border_color'] ) ? $item['graph_border_color'] : '#131740';
			$temp['data-hover-border-colors'][]     = ! empty( $item['graph_hover_border_color'] ) ? $item['graph_hover_border_color'] : '#131740';
			$temp['data-fill'][]                    = ! empty( $item['graph_filling_modes'] ) ? $item['graph_filling_modes'] : false;
			$temp['data-linear'][]                  = ( 'yes' === $item['graph_linear'] ) ? 0 : 0.4;
		}

		$temp['data-labels'] = ! empty( $settings['graph_data_labels'] ) ? explode( ',', $settings['graph_data_labels'] ) : '';

		$temp['data-border-width']       = ( '' !== $settings['border_width'] ) ? intval( $settings['border_width'] ) : 3;
		$temp['data-hover-border-width'] = ( '' !== $settings['hover_border_width'] ) ? intval( $settings['hover_border_width'] ) : 3;

		$temp['data-bar-size'] = ! empty( $settings['bar_size']['size'] ) ? $settings['bar_size']['size'] : 0.4;
		$temp['data-cat-size'] = ! empty( $settings['cat_size']['size'] ) ? $settings['cat_size']['size'] : 0.65;

		$temp['data-type']         = 'line' === $settings['graph_type'];
		$temp['data-aspect-ratio'] = ! empty( $settings['graph_aspect_ratio']['size'] ) ? $settings['graph_aspect_ratio']['size'] : 1;

		$temp['data-ticks']['min']           = ! empty( $settings['ticks_min'] ) ? intval( $settings['ticks_min'] ) : '';
		$temp['data-ticks']['max']           = ! empty( $settings['ticks_max'] ) ? intval( $settings['ticks_max'] ) : '';
		$temp['data-ticks']['step']          = ! empty( $settings['ticks_step'] ) ? intval( $settings['ticks_step'] ) : '';
		$temp['data-ticks-font']             = ! empty( $settings['ticks_font_font_family'] ) ? $settings['ticks_font_font_family'] : 'Karla, sans-serif';
		$temp['data-ticks-label-color']      = ! empty( $settings['ticks_label_color'] ) ? $settings['ticks_label_color'] : '';
		$temp['data-ticks-grid-lines-color'] = ! empty( $settings['ticks_grid_lines_color'] ) ? $settings['ticks_grid_lines_color'] : '';

		$temp['data-enable-legend']            = ( 'yes' === $settings['enable_chart_legend'] ) ? true : false;
		$temp['data-legend-position']          = ! empty( $settings['legend_position'] ) ? $settings['legend_position'] : 'top';
		$temp['data-legend-alignment']         = ! empty( $settings['legend_alignment'] ) ? $settings['legend_alignment'] : '';
		$temp['data-legend-bar-width']         = ! empty( $settings['legend_bar_width']['size'] ) ? intval( $settings['legend_bar_width']['size'] ) : 16;
		$temp['data-legend-bar-height']        = ! empty( $settings['legend_bar_height']['size'] ) ? intval( $settings['legend_bar_height']['size'] ) : 16;
		$temp['data-legend-bar-margin']        = ! empty( $settings['legend_bar_margin']['size'] ) ? intval( $settings['legend_bar_margin']['size'] ) : '';
		$temp['data-legend-label-color']       = ! empty( $settings['legend_label_color'] ) ? $settings['legend_label_color'] : '';
		$temp['data-legend-label-font']        = ! empty( $settings['legend_label_font_font_family'] ) ? $settings['legend_label_font_font_family'] : 'Karla, sans-serif';
		$temp['data-legend-label-font-size']   = ! empty( $settings['legend_label_font_size']['size'] ) ? intval( $settings['legend_label_font_size']['size'] ) : '';
		$temp['data-legend-label-font-weight'] = ! empty( $settings['legend_label_font_weight'] ) ? $settings['legend_label_font_weight'] : '';

		foreach ( $temp as $key => $value ) {
			if ( is_array( $value ) || is_bool( $value ) ) {
				$data[ $key ] = wp_json_encode( $value );
			} elseif ( ! empty( $value ) ) {
				$data[ $key ] = $value;
			}
		}

		return $data;
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
		$settings      = $this->get_settings_for_display();
		$atts          = $this->get_data_attrs( $settings );
		$atts['class'] = 'envision-blocks-graph envision-blocks-graph-' . esc_attr( $this->get_id() );

		$this->add_render_attribute(
			'graph',
			$atts
		);

		?>

		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'graph' ) ); ?>>
			<div class="envision-blocks-graph__canvas" style="display:flex;">
				<canvas></canvas>
			</div>
		</div>

		<?php
	}
}
