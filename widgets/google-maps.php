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

class Google_Maps extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		\EnvisionBlocks\Utils::register_google_maps_script();
		wp_register_script( 'envision-blocks-google-maps', ENVISION_BLOCKS_URL . 'assets/js/view/google-maps.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-google-maps';
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
		return esc_html__( 'Advanced Google Maps', 'envision-blocks' );
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
		return 'eicon-google-maps envision-blocks-icon';
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
		return array( 'envision-blocks-google-maps-api', 'envision-blocks-google-maps' );
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
		return array( 'google', 'map', 'location', 'lightbox' );
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
		$this->section_addresses();
		$this->section_map();
		$this->section_map_controls();
		$this->section_skins();

		$this->section_map_style();
		$this->section_info_window_style();
	}


	/**
	 * Content > Addresses.
	 */
	private function section_addresses() {
		$this->start_controls_section(
			'section_addresses',
			array(
				'label' => esc_html__( 'Addresses', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'api_key_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => sprintf( __( '<a href="%s" target="_blank">Add your API key here</a>', 'envision-blocks' ), admin_url( 'admin.php?page=envision-blocks&tab=integrations' ) ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'latitude',
			array(
				'label'       => esc_html__( 'Latitude', 'envision-blocks' ),
				'description' => sprintf( '<a href="https://www.latlong.net/" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'envision-blocks' ), esc_html__( 'to find Latitude of your location', 'envision-blocks' ) ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'longitude',
			array(
				'label'       => esc_html__( 'Longitude', 'envision-blocks' ),
				'description' => sprintf( '<a href="https://www.latlong.net/" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'envision-blocks' ), esc_html__( 'to find Longitude of your location', 'envision-blocks' ) ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'map_title',
			array(
				'label'       => esc_html__( 'Address Title', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'marker_infowindow',
			array(
				'label'       => esc_html__( 'Display Info Window', 'envision-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => true,
				'options'     => array(
					'none'  => esc_html__( 'None', 'envision-blocks' ),
					'click' => esc_html__( 'On Mouse Click', 'envision-blocks' ),
					'load'  => esc_html__( 'On Page Load', 'envision-blocks' ),
				),
			)
		);

		$repeater->add_control(
			'map_description',
			array(
				'label'       => esc_html__( 'Address Information', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'marker_infowindow',
							'operator' => '!=',
							'value'    => 'none',
						),
					),
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'marker_icon_type',
			array(
				'label'   => esc_html__( 'Marker Icon', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'envision-blocks' ),
					'custom'  => esc_html__( 'Custom', 'envision-blocks' ),
				),
			)
		);

		$repeater->add_control(
			'marker_icon',
			array(
				'label'      => esc_html__( 'Select Marker', 'envision-blocks' ),
				'type'       => Controls_Manager::MEDIA,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'marker_icon_type',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'custom_marker_size',
			array(
				'label'       => esc_html__( 'Marker Size', 'envision-blocks' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'description' => esc_html__( 'Note: If you want to retain the image original size, then set the Marker Size as blank.', 'envision-blocks' ),
				'default'     => array(
					'size' => 30,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'marker_icon_type',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				),
			)
		);

		$this->add_control(
			'addresses',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'latitude'        => 51.503333,
						'longitude'       => -0.119562,
						'map_title'       => esc_html__( 'Coca-Cola London Eye', 'envision-blocks' ),
						'map_description' => '',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ map_title }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Map.
	 */
	private function section_map() {

		$this->start_controls_section(
			'section_map',
			array(
				'label' => esc_html__( 'Map', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'type',
			array(
				'label'   => esc_html__( 'Map Type', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'roadmap',
				'options' => array(
					'roadmap'   => esc_html__( 'Road Map', 'envision-blocks' ),
					'satellite' => esc_html__( 'Satellite', 'envision-blocks' ),
					'hybrid'    => esc_html__( 'Hybrid', 'envision-blocks' ),
					'terrain'   => esc_html__( 'Terrain', 'envision-blocks' ),
				),
			)
		);

		$this->add_control(
			'zoom',
			array(
				'label'   => esc_html__( 'Map Zoom', 'envision-blocks' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 12,
				),
				'range'   => array(
					'px' => array(
						'min' => 1,
						'max' => 22,
					),
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => esc_html__( 'Height', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 500,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 80,
						'max' => 1200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-google-map' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Map controls.
	 */
	private function section_map_controls() {

		$this->start_controls_section(
			'section_map_controls',
			array(
				'label' => esc_html__( 'Controls', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'option_streeview',
			array(
				'label'        => esc_html__( 'Street View Controls', 'envision-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'On', 'envision-blocks' ),
				'label_off'    => esc_html__( 'Off', 'envision-blocks' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'type_control',
			array(
				'label'        => esc_html__( 'Map Type Control', 'envision-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'On', 'envision-blocks' ),
				'label_off'    => esc_html__( 'Off', 'envision-blocks' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'zoom_control',
			array(
				'label'        => esc_html__( 'Zoom Control', 'envision-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'On', 'envision-blocks' ),
				'label_off'    => esc_html__( 'Off', 'envision-blocks' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'fullscreen_control',
			array(
				'label'        => esc_html__( 'Fullscreen Control', 'envision-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'On', 'envision-blocks' ),
				'label_off'    => esc_html__( 'Off', 'envision-blocks' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'scroll_zoom',
			array(
				'label'        => esc_html__( 'Zoom on Scroll', 'envision-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'On', 'envision-blocks' ),
				'label_off'    => esc_html__( 'Off', 'envision-blocks' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'auto_center',
			array(
				'label'       => esc_html__( 'Map Alignment', 'envision-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center',
				'options'     => array(
					'center'   => esc_html__( 'Center', 'envision-blocks' ),
					'moderate' => esc_html__( 'Moderate', 'envision-blocks' ),
				),
				'description' => esc_html__( 'The map is center aligned by default. If you have multiple locations & wish to make your first location as a center point, then switch to moderate mode.', 'envision-blocks' ),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Content > Skins.
	 */
	private function section_skins() {

		$this->start_controls_section(
			'section_skins',
			array(
				'label' => esc_html__( 'Skins', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'     => esc_html__( 'Map Skin', 'envision-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'standard',
				'options'   => array(
					'standard'     => esc_html__( 'Standard', 'envision-blocks' ),
					'silver'       => esc_html__( 'Silver', 'envision-blocks' ),
					'retro'        => esc_html__( 'Retro', 'envision-blocks' ),
					'dark'         => esc_html__( 'Dark', 'envision-blocks' ),
					'night'        => esc_html__( 'Night', 'envision-blocks' ),
					'aubergine'    => esc_html__( 'Aubergine', 'envision-blocks' ),
					'aqua'         => esc_html__( 'Aqua', 'envision-blocks' ),
					'classic_blue' => esc_html__( 'Classic Blue', 'envision-blocks' ),
					'earth'        => esc_html__( 'Earth', 'envision-blocks' ),
					'magnesium'    => esc_html__( 'Magnesium', 'envision-blocks' ),
					'custom'       => esc_html__( 'Custom', 'envision-blocks' ),
				),
				'condition' => array(
					'type!' => 'satellite',
				),
			)
		);

		$this->add_control(
			'map_custom_style',
			array(
				'label'       => esc_html__( 'Custom Style', 'envision-blocks' ),
				'description' => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'envision-blocks' ), esc_html__( 'to get JSON style code to style your map', 'envision-blocks' ) ),
				'type'        => Controls_Manager::TEXTAREA,
				'condition'   => array(
					'skin'  => 'custom',
					'type!' => 'satellite',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Map.
	 */
	private function section_map_style() {

		$this->start_controls_section(
			'section_map_style',
			array(
				'label' => esc_html__( 'Map', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'map_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-google-map-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Info Window.
	 */
	private function section_info_window_style() {

		$this->start_controls_section(
			'section_info_window_style',
			array(
				'label' => esc_html__( 'Info Window', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'info_window_max_width',
			array(
				'label'       => esc_html__( 'Max Width', 'envision-blocks' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 300,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'size_units'  => array( 'px' ),
				'label_block' => true,
			)
		);

		$this->add_responsive_control(
			'info_window_padding',
			array(
				'label'      => esc_html__( 'Padding', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gm-style .envision-blocks-infowindow-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => esc_html__( 'Address Title', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gm-style .envision-blocks-google-maps-infowindow-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .gm-style .envision-blocks-google-maps-infowindow-title',
			)
		);

		$this->add_control(
			'description_heading',
			array(
				'label'     => esc_html__( 'Address Description', 'envision-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gm-style .envision-blocks-google-maps-infowindow-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .gm-style .envision-blocks-google-maps-infowindow-description',
			)
		);

		$this->end_controls_section();
	}



	/**
	 * Renders Locations JSON array.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_locations() {

		$settings = $this->get_settings_for_display();

		$locations = array();

		foreach ( $settings['addresses'] as $index => $item ) {

			$latitude  = $item['latitude'];
			$longitude = $item['longitude'];

			$location_object = array(
				$latitude,
				$longitude,
			);

			$location_object[] = ( 'none' !== $item['marker_infowindow'] ) ? true : false;
			$location_object[] = $item['map_title'];
			$location_object[] = $item['map_description'];

			if (
				'custom' === $item['marker_icon_type'] && is_array( $item['marker_icon'] ) &&
				'' !== $item['marker_icon']['url']
			) {
				$location_object[] = 'custom';
				$location_object[] = $item['marker_icon']['url'];
				$location_object[] = $item['custom_marker_size']['size'];
			} else {
				$location_object[] = '';
				$location_object[] = '';
				$location_object[] = '';
			}

			$location_object[] = ( 'load' === $item['marker_infowindow'] ) ? 'iw_open' : '';

			$locations[] = $location_object;
		}

		return $locations;
	}

	/**
	 * Renders Map Control option JSON array.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_map_options() {

		$settings = $this->get_settings_for_display();

		return array(
			'zoom'              => ( ! empty( $settings['zoom']['size'] ) ) ? $settings['zoom']['size'] : 4,
			'mapTypeId'         => ( ! empty( $settings['type'] ) ) ? $settings['type'] : 'roadmap',
			'mapTypeControl'    => ( 'yes' === $settings['type_control'] ) ? true : false,
			'streetViewControl' => ( 'yes' === $settings['option_streeview'] ) ? true : false,
			'zoomControl'       => ( 'yes' === $settings['zoom_control'] ) ? true : false,
			'fullscreenControl' => ( 'yes' === $settings['fullscreen_control'] ) ? true : false,
			'gestureHandling'   => ( 'yes' === $settings['scroll_zoom'] ) ? true : false,
		);
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

		ob_start();

		$map_options = $this->get_map_options();
		$locations   = $this->get_locations();

		$this->add_render_attribute(
			'google-map',
			array(
				'id'               => 'envision-blocks-google-map-' . esc_attr( $this->get_id() ),
				'class'            => 'envision-blocks-google-map',
				'data-map_options' => wp_json_encode( $map_options ),
				'data-max-width'   => $settings['info_window_max_width']['size'],
				'data-locations'   => wp_json_encode( $locations ),
				'data-auto-center' => $settings['auto_center'],
			)
		);

		if ( 'standard' !== $settings['skin'] ) {
			if ( 'custom' !== $settings['skin'] ) {
				$this->add_render_attribute( 'google-map', 'data-predefined-style', $settings['skin'] );
			} elseif ( ! empty( $settings['map_custom_style'] ) ) {
				$this->add_render_attribute( 'google-map', 'data-custom-style', $settings['map_custom_style'] );
			}
		}

		?>

		<div class="envision-blocks-google-map-wrap" style="overflow: hidden;">
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'google-map' ) ); ?>></div>
		</div>

		<?php
		$html = ob_get_clean();
		echo wp_kses_post( $html );
	}
}
