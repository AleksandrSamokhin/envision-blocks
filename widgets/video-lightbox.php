<?php
namespace EnvisionBlocks\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Video_Lightbox extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-video-lightbox', ENVISION_BLOCKS_URL . 'assets/css/video-lightbox.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-video-lightbox', 'rtl', 'replace' );
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
		return 'envision-blocks-video-lightbox';
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
		return esc_html__( 'Video Lightbox', 'envision-blocks' );
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
		return 'eicon-play envision-blocks-icon';
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
		return array( 'fslightbox' );
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
		return array( 'envision-blocks-video-lightbox' );
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
		return array( 'icon', 'video', 'popup', 'lightbox' );
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
		$this->section_video();

		$this->section_icon_style();
	}


	/**
	 * Content > Icon.
	 */
	private function section_video() {
		$this->start_controls_section(
			'section_video',
			array(
				'label' => esc_html__( 'Video', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'       => esc_html__( 'Video URL', 'envision-blocks' ),
				'description' => esc_html__( 'Paste YouTube or Vimeo video URL', 'envision-blocks' ),
				'type'        => Controls_Manager::URL,
				'default'     => array(
					'url'         => 'https://www.youtube.com/watch?v=nVzVohG304A',
					'is_external' => false,
					'nofollow'    => true,
				),
				'placeholder' => 'https://www.youtube.com/watch?v=nVzVohG304A',
			)
		);

		$this->add_control(
			'self_hosted_video',
			array(
				'label'       => esc_html__( 'Self hosted video', 'envision-blocks' ),
				'description' => esc_html__( 'Link to video file (mp4 is recommended)', 'envision-blocks' ),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => array( 'video' ),
			)
		);

		$this->add_control(
			'play_button_text',
			array(
				'label'       => esc_html__( 'Text', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Watch Video', 'envision-blocks' ),
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-video-lightbox' => 'text-align: {{VALUE}};',
				),
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

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-video-lightbox__play-btn:before' => 'border-color: transparent transparent transparent {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_background_color',
			array(
				'label'     => esc_html__( 'Icon Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-video-lightbox__play-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Hover Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-video-lightbox__play-btn:hover:before' => 'border-color: transparent transparent transparent {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_background_color_hover',
			array(
				'label'     => esc_html__( 'Icon Hover Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-video-lightbox__play-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .envision-blocks-video-lightbox__play-btn:before' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}}; border-left-width: calc( {{SIZE}}{{UNIT}} + 3px );',
					'body.rtl {{WRAPPER}} .envision-blocks-video-lightbox__play-btn:before' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}}; border-right-width: calc( {{SIZE}}{{UNIT}} + 3px );',
				),
			)
		);

		$this->add_responsive_control(
			'icon_base_size',
			array(
				'label'     => esc_html__( 'Base Size', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 400,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-video-lightbox__play-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'box_border',
				'selector'  => '{{WRAPPER}} .envision-blocks-video-lightbox__play-btn',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'background_base_blur',
			array(
				'label'     => esc_html__( 'Base Blur', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-video-lightbox__play-btn' => 'backdrop-filter: blur( {{SIZE}}{{UNIT}} );',
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
		$settings  = $this->get_settings_for_display();
		$video_url = '';

		if ( ! empty( $settings['self_hosted_video']['id'] ) ) {
			$video_url = $settings['self_hosted_video']['url'];
		} elseif ( ! empty( $settings['video_url']['url'] ) ) {
			$video_url = $settings['video_url']['url'];
		}

		?>
			<div class="envision-blocks-video-lightbox">
				<a itemprop="image" href="<?php echo esc_url( $video_url ); ?>" class="envision-blocks-video-lightbox__play-btn envision-blocks-video-lightbox--icon-wave" data-fslightbox="envision-blocks-video-lightbox__gallery-<?php echo esc_attr( $this->get_id() ); ?>">
				</a>
				<?php if ( $settings['play_button_text'] ) : ?>
					<span class="envision-blocks-video-lightbox__text"><?php echo esc_html( $settings['play_button_text'] ); ?></span>
				<?php endif; ?>
			</div>

			<script>
				if (typeof refreshFsLightbox === 'function') {
				refreshFsLightbox();
				}
			</script>
		<?php
	}
}
