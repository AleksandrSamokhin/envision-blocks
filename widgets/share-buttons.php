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

class Share_Buttons extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-share-buttons', ENVISION_BLOCKS_URL . 'assets/css/share-buttons.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-share-buttons', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-share-buttons', ENVISION_BLOCKS_URL . 'assets/js/view/share-buttons.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
	}

	private static $networks = array(
		'facebook'      => array(
			'title' => 'Facebook',
		),
		'x-twitter'     => array(
			'title' => 'X',
		),
		'threads'       => array(
			'title' => 'Threads',
		),
		'linkedin'      => array(
			'title' => 'LinkedIn',
		),
		'pinterest'     => array(
			'title' => 'Pinterest',
		),
		'reddit'        => array(
			'title' => 'Reddit',
		),
		'vk'            => array(
			'title' => 'VK',
		),
		'odnoklassniki' => array(
			'title' => 'OK',
		),
		'tumblr'        => array(
			'title' => 'Tumblr',
		),

		'telegram'      => array(
			'title' => 'Telegram',
		),
		'pocket'        => array(
			'title' => 'Pocket',
		),
		'xing'          => array(
			'title' => 'XING',
		),
		'whatsapp'      => array(
			'title' => 'WhatsApp',
		),
		'email'         => array(
			'title' => 'Email',
		),
	);

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
		return 'envision-blocks-share-buttons';
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
		return esc_html__( 'Share Buttons', 'envision-blocks' );
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
		return 'eicon-share envision-blocks-icon';
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
		return array( 'envision-blocks-share-buttons' );
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
		return array( 'envision-blocks-share-buttons' );
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
		return array( 'share', 'social', 'icons', 'like' );
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
		$this->section_share_buttons_content();
		$this->section_share_buttons_style();
		$this->section_share_buttons_dark_mode_style();
	}

	/**
	 * Content > Share Buttons.
	 */
	private function section_share_buttons_content() {
		$this->start_controls_section(
			'section_share_buttons_content',
			array(
				'label' => esc_html__( 'Share Buttons', 'envision-blocks' ),
			)
		);

		$repeater = new Repeater();

		$networks = self::$networks;

		$networks_names = array_keys( $networks );

		$repeater->add_control(
			'button',
			array(
				'label'   => esc_html__( 'Network', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array_reduce(
					$networks_names,
					function ( $options, $network_name ) use ( $networks ) {
						$options[ $network_name ] = $networks[ $network_name ]['title'];
						return $options;
					},
					array()
				),
				'default' => 'facebook',
			)
		);

		$this->add_control(
			'share_buttons',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'button' => 'facebook',
					),
					array(
						'button' => 'x-twitter',
					),
					array(
						'button' => 'linkedin',
					),
				),
				'title_field' => '{{ button }}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Share Buttons.
	 */
	private function section_share_buttons_style() {
		$this->start_controls_section(
			'section_share_buttons_style',
			array(
				'label' => esc_html__( 'Style', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'share_buttons_color' );

		$this->start_controls_tab(
			'button_color_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icons color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7d7f96',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-share-buttons__btn' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_color_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => esc_html__( 'Icons color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8358ff',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-share-buttons__btn:hover, {{WRAPPER}} .envision-blocks-share-buttons__btn:focus' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-share-buttons__btn svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'envision-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envision-blocks-share-buttons' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Dark Mode.
	 */
	private function section_share_buttons_dark_mode_style() {
		$this->start_controls_section(
			'section_share_buttons_dark_mode_style',
			array(
				'label' => esc_html__( 'Dark Mode', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'share_buttons_color_dark' );

		$this->start_controls_tab(
			'button_color_normal_tab_dark',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'icon_color_dark',
			array(
				'label'     => esc_html__( 'Icons color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7d7f96',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-share-buttons__btn' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_color_hover_tab_dark',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'icon_hover_color_dark',
			array(
				'label'     => esc_html__( 'Icons color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8358ff',
				'selectors' => array(
					'.envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-share-buttons__btn:hover, .envision-blocks-scheme-dark {{WRAPPER}} .envision-blocks-share-buttons__btn:focus' => 'fill: {{VALUE}};',
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

		if ( empty( $settings['share_buttons'] ) ) {
			return;
		} ?>		

		<div class="envision-blocks-share-buttons envision-blocks-share-buttons-<?php echo esc_attr( $this->get_id() ); ?>">
			<?php foreach ( $settings['share_buttons'] as $button ) : ?>
				<?php
					$network_name         = $button['button'];
					$social_network_class = 'envision-blocks-share-buttons__btn envision-blocks-share-buttons__btn--' . $network_name;
					$title                = str_replace( ' ', '%20', get_the_title() );
					$URL                  = urlencode( get_permalink() );
					$post_thumb           = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
					$thumb                = isset( $post_thumb[0] ) ? $post_thumb[0] : '';

					$svg = array(
						'facebook'      => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="16" height="16"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>',
							'url'  => 'https://www.facebook.com/sharer/sharer.php?u=' . get_permalink(),
						),
						'x-twitter'     => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>',
							'url'  => 'https://twitter.com/intent/tweet?text=' . esc_html( $title ) . '&amp;url=' . esc_attr( $URL ),
						),
						'threads'       => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path d="M331.5 235.7c2.2 .9 4.2 1.9 6.3 2.8c29.2 14.1 50.6 35.2 61.8 61.4c15.7 36.5 17.2 95.8-30.3 143.2c-36.2 36.2-80.3 52.5-142.6 53h-.3c-70.2-.5-124.1-24.1-160.4-70.2c-32.3-41-48.9-98.1-49.5-169.6V256v-.2C17 184.3 33.6 127.2 65.9 86.2C102.2 40.1 156.2 16.5 226.4 16h.3c70.3 .5 124.9 24 162.3 69.9c18.4 22.7 32 50 40.6 81.7l-40.4 10.8c-7.1-25.8-17.8-47.8-32.2-65.4c-29.2-35.8-73-54.2-130.5-54.6c-57 .5-100.1 18.8-128.2 54.4C72.1 146.1 58.5 194.3 58 256c.5 61.7 14.1 109.9 40.3 143.3c28 35.6 71.2 53.9 128.2 54.4c51.4-.4 85.4-12.6 113.7-40.9c32.3-32.2 31.7-71.8 21.4-95.9c-6.1-14.2-17.1-26-31.9-34.9c-3.7 26.9-11.8 48.3-24.7 64.8c-17.1 21.8-41.4 33.6-72.7 35.3c-23.6 1.3-46.3-4.4-63.9-16c-20.8-13.8-33-34.8-34.3-59.3c-2.5-48.3 35.7-83 95.2-86.4c21.1-1.2 40.9-.3 59.2 2.8c-2.4-14.8-7.3-26.6-14.6-35.2c-10-11.7-25.6-17.7-46.2-17.8H227c-16.6 0-39 4.6-53.3 26.3l-34.4-23.6c19.2-29.1 50.3-45.1 87.8-45.1h.8c62.6 .4 99.9 39.5 103.7 107.7l-.2 .2zm-156 68.8c1.3 25.1 28.4 36.8 54.6 35.3c25.6-1.4 54.6-11.4 59.5-73.2c-13.2-2.9-27.8-4.4-43.4-4.4c-4.8 0-9.6 .1-14.4 .4c-42.9 2.4-57.2 23.2-56.2 41.8l-.1 .1z"/></svg>',
							'url'  => 'https://www.threads.net/intent/post?text=' . get_permalink(),
						),
						'linkedin'      => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>',
							'url'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . get_permalink() . '&amp;title=' . esc_html( $title ),
						),
						'pinterest'     => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>',
							'url'  => 'https://pinterest.com/pin/create/button/?url=' . esc_attr( $URL ) . '&amp;media=' . esc_url( $thumb ) . '&amp;description=' . esc_html( $title ),
						),
						'reddit'        => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path d="M440.3 203.5c-15 0-28.2 6.2-37.9 15.9-35.7-24.7-83.8-40.6-137.1-42.3L293 52.3l88.2 19.8c0 21.6 17.6 39.2 39.2 39.2 22 0 39.7-18.1 39.7-39.7s-17.6-39.7-39.7-39.7c-15.4 0-28.7 9.3-35.3 22l-97.4-21.6c-4.9-1.3-9.7 2.2-11 7.1L246.3 177c-52.9 2.2-100.5 18.1-136.3 42.8-9.7-10.1-23.4-16.3-38.4-16.3-55.6 0-73.8 74.6-22.9 100.1-1.8 7.9-2.6 16.3-2.6 24.7 0 83.8 94.4 151.7 210.3 151.7 116.4 0 210.8-67.9 210.8-151.7 0-8.4-.9-17.2-3.1-25.1 49.9-25.6 31.5-99.7-23.8-99.7zM129.4 308.9c0-22 17.6-39.7 39.7-39.7 21.6 0 39.2 17.6 39.2 39.7 0 21.6-17.6 39.2-39.2 39.2-22 .1-39.7-17.6-39.7-39.2zm214.3 93.5c-36.4 36.4-139.1 36.4-175.5 0-4-3.5-4-9.7 0-13.7 3.5-3.5 9.7-3.5 13.2 0 27.8 28.5 120 29 149 0 3.5-3.5 9.7-3.5 13.2 0 4.1 4 4.1 10.2.1 13.7zm-.8-54.2c-21.6 0-39.2-17.6-39.2-39.2 0-22 17.6-39.7 39.2-39.7 22 0 39.7 17.6 39.7 39.7-.1 21.5-17.7 39.2-39.7 39.2z"/></svg>',
							'url'  => 'https://www.reddit.com/submit?url=' . get_permalink(),
						),
						'vk'            => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path d="M545 117.7c3.7-12.5 0-21.7-17.8-21.7h-58.9c-15 0-21.9 7.9-25.6 16.7 0 0-30 73.1-72.4 120.5-13.7 13.7-20 18.1-27.5 18.1-3.7 0-9.4-4.4-9.4-16.9V117.7c0-15-4.2-21.7-16.6-21.7h-92.6c-9.4 0-15 7-15 13.5 0 14.2 21.2 17.5 23.4 57.5v86.8c0 19-3.4 22.5-10.9 22.5-20 0-68.6-73.4-97.4-157.4-5.8-16.3-11.5-22.9-26.6-22.9H38.8c-16.8 0-20.2 7.9-20.2 16.7 0 15.6 20 93.1 93.1 195.5C160.4 378.1 229 416 291.4 416c37.5 0 42.1-8.4 42.1-22.9 0-66.8-3.4-73.1 15.4-73.1 8.7 0 23.7 4.4 58.7 38.1 40 40 46.6 57.9 69 57.9h58.9c16.8 0 25.3-8.4 20.4-25-11.2-34.9-86.9-106.7-90.3-111.5-8.7-11.2-6.2-16.2 0-26.2.1-.1 72-101.3 79.4-135.6z"/></svg>',
							'url'  => 'https://vk.com/share.php?url=' . get_permalink(),
						),
						'odnoklassniki' => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path d="M224 137.1a39.9 39.9 0 1 0 0 79.7 39.9 39.9 0 1 0 0-79.7zM384 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64zM224 95.9A81 81 0 1 1 224 258a81 81 0 1 1 0-162.1zm59.3 168.3c16.8-13.2 29.5-5.5 34.1 3.6c7.8 16-1.1 23.7-21.5 37c-17.1 10.9-40.7 15.2-56.2 16.8l13 12.9 47.7 47.7c17.4 17.9-11 45.8-28.6 28.6c-12-12.2-29.5-29.7-47.7-47.9l0 0-47.7 47.9c-17.7 17.2-46-11-28.4-28.6c3.7-3.7 7.9-7.9 12.5-12.5c10.4-10.4 22.6-22.7 35.2-35.2l12.9-12.9c-15.4-1.6-39.3-5.7-56.6-16.8c-20.3-13.3-29.3-20.9-21.4-37c4.6-9.1 17.3-16.8 34.1-3.6c0 0 22.7 18 59.3 18s59.3-18 59.3-18z"/></svg>',
							'url'  => 'https://connect.ok.ru/offer?url=' . esc_attr( $URL ) . '&amp;title=' . esc_html( $title ) . '&amp;imageUrl=' . esc_url( $thumb ),
						),
						'tumblr'        => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path d="M448 96c0-35.3-28.7-64-64-64H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96zM256.8 416c-75.5 0-91.9-55.5-91.9-87.9v-90H135.2c-3.4 0-6.2-2.8-6.2-6.2V189.4c0-4.5 2.8-8.5 7.1-10c38.8-13.7 50.9-47.5 52.7-73.2c.5-6.9 4.1-10.2 10-10.2h44.3c3.4 0 6.2 2.8 6.2 6.2v72h51.9c3.4 0 6.2 2.8 6.2 6.2v51.1c0 3.4-2.8 6.2-6.2 6.2H249.1V321c0 21.4 14.8 33.5 42.5 22.4c3-1.2 5.6-2 8-1.4c2.2 .5 3.6 2.1 4.6 4.9L318 387.1c1 3.2 2 6.7-.3 9.1c-8.5 9.1-31.2 19.8-60.9 19.8z"/></svg>',
							'url'  => 'http://tumblr.com/widgets/share/tool?canonicalUrl=' . get_permalink(),
						),
						'telegram'      => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"/></svg>',
							'url'  => 'https://t.me/share/url?&text=' . esc_attr( $URL ) . '&amp;url=' . get_permalink(),
						),
						'pocket'        => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path d="M407.6 64h-367C18.5 64 0 82.5 0 104.6v135.2C0 364.5 99.7 464 224.2 464c124 0 223.8-99.5 223.8-224.2V104.6c0-22.4-17.7-40.6-40.4-40.6zm-162 268.5c-12.4 11.8-31.4 11.1-42.4 0C89.5 223.6 88.3 227.4 88.3 209.3c0-16.9 13.8-30.7 30.7-30.7 17 0 16.1 3.8 105.2 89.3 90.6-86.9 88.6-89.3 105.5-89.3 16.9 0 30.7 13.8 30.7 30.7 0 17.8-2.9 15.7-114.8 123.2z"/></svg>',
							'url'  => 'https://getpocket.com/save?url=' . esc_attr( $URL ),
						),
						'xing'          => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path d="M162.7 210c-1.8 3.3-25.2 44.4-70.1 123.5-4.9 8.3-10.8 12.5-17.7 12.5H9.8c-7.7 0-12.1-7.5-8.5-14.4l69-121.3c.2 0 .2-.1 0-.3l-43.9-75.6c-4.3-7.8 .3-14.1 8.5-14.1H100c7.3 0 13.3 4.1 18 12.2l44.7 77.5zM382.6 46.1l-144 253v.3L330.2 466c3.9 7.1 .2 14.1-8.5 14.1h-65.2c-7.6 0-13.6-4-18-12.2l-92.4-168.5c3.3-5.8 51.5-90.8 144.8-255.2 4.6-8.1 10.4-12.2 17.5-12.2h65.7c8 0 12.3 6.7 8.5 14.1z"/></svg>',
							'url'  => 'https://www.xing.com/spi/shares/new?url=' . esc_attr( $URL ),
						),
						'whatsapp'      => array(
							'icon' => '	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>',
							'url'  => 'whatsapp://send?text=' . get_permalink(),
						),
						'email'         => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm0 48v40.805c-22.422 18.259-58.168 46.651-134.587 106.49-16.841 13.247-50.201 45.072-73.413 44.701-23.208.375-56.579-31.459-73.413-44.701C106.18 199.465 70.425 171.067 48 152.805V112h416zM48 400V214.398c22.914 18.251 55.409 43.862 104.938 82.646 21.857 17.205 60.134 55.186 103.062 54.955 42.717.231 80.509-37.199 103.053-54.947 49.528-38.783 82.032-64.401 104.947-82.653V400H48z"/></svg>',
							'url'  => 'mailto:?subject=' . esc_html( $title ) . '&amp;body=' . esc_html( $title ) . '%20' . get_permalink(),
						),
					);
					?>

				<a
					class="<?php echo esc_attr( $social_network_class ); ?>"
					role="button"
					tabindex="0"
					href="<?php echo esc_url( $svg[ $network_name ]['url'] ); ?>"
					target="_blank"
					rel="noopener nofollow"
					aria-label="<?php printf( esc_attr__( 'Share on %s', 'envision-blocks' ), esc_attr( $network_name ) ); ?>"
				>
					<?php echo \EnvisionBlocks\Utils::sanitize_svg( 'svg', $svg[ $network_name ]['icon'] ); ?>
				</a>

			<?php endforeach; ?>
		</div>

		<?php
	}
}
