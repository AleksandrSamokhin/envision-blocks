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

class Testimonials_Feed extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-testimonials-feed', ENVISION_BLOCKS_URL . 'assets/css/testimonials-feed.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-testimonials-feed', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-testimonials-feed', ENVISION_BLOCKS_URL . 'assets/js/view/testimonials-feed.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-testimonials-feed';
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
		return esc_html__( 'Testimonials Feed', 'envision-blocks' );
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
		return 'eicon-posts-masonry envision-blocks-icon';
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
		return array( 'envision-blocks-testimonials-feed' );
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
		return array( 'envision-blocks-testimonials-feed' );
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
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->section_content();
		$this->section_grid_style();
	}


	/**
	 * Content > Content.
	 */
	private function section_content() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Testimonials', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'testimonial_image',
			array(
				'label'     => esc_html__( 'Testimonial Image', 'envision-blocks' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'testimonial_video_type!' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'testimonial_video_type',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Video', 'envision-blocks' ),
				'default' => '',
			)
		);

		$repeater->add_control(
			'testimonial_video',
			array(
				'label'       => esc_html__( 'Testimonial Video', 'envision-blocks' ),
				'description' => esc_html__( 'Link to video file (mp4 is recommended)', 'envision-blocks' ),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => array( 'video' ),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'   => array(
					'testimonial_video_type' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'testimonial_video_poster',
			array(
				'label'     => esc_html__( 'Video Poster', 'envision-blocks' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'testimonial_video_type' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'testimonial_rating',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Testimonial Rating', 'envision-blocks' ),
				'default' => 'yes',
			)
		);

		$repeater->add_control(
			'testimonial_text',
			array(
				'label'      => esc_html__( 'Testimonial Text', 'envision-blocks' ),
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => esc_html__( 'Testimonial Text', 'envision-blocks' ),
				'show_label' => false,
			)
		);

		$repeater->add_control(
			'testimonial_name',
			array(
				'label'   => esc_html__( 'Name', 'envision-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Testimonial Name', 'envision-blocks' ),
			)
		);

		$repeater->add_control(
			'testimonial_company',
			array(
				'label'   => esc_html__( 'Company', 'envision-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Testimonial Company', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'testimonials',
			array(
				'label'       => esc_html__( 'Testimonials', 'envision-blocks' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'testimonial_text'    => esc_html__( 'I was impressed by the professionalism and creativity of this company.', 'envision-blocks' ),
						'testimonial_name'    => esc_html__( 'Jack F.', 'envision-blocks' ),
						'testimonial_company' => esc_html__( 'Freelancer', 'envision-blocks' ),
					),
					array(
						'testimonial_text'    => esc_html__( 'I couldn\'t be happier with the services provided by this company. They went above and beyond to meet our needs and delivered exceptional results. Highly recommended!', 'envision-blocks' ),
						'testimonial_name'    => esc_html__( 'Sarah Johnson', 'envision-blocks' ),
						'testimonial_company' => esc_html__( 'CEO of TechSavvy Inc.', 'envision-blocks' ),
					),
					array(
						'testimonial_text'    => esc_html__( 'Working with this team was a game-changer for our business. Their expertise and dedication helped us achieve our goals faster than we ever thought possible. Thank you!', 'envision-blocks' ),
						'testimonial_name'    => esc_html__( 'John Smith', 'envision-blocks' ),
						'testimonial_company' => esc_html__( 'Global Solutions', 'envision-blocks' ),
					),
				),
				'title_field' => '{{{ testimonial_name }}}',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Grid.
	 */
	private function section_grid_style() {
		$this->start_controls_section(
			'section_grid_style',
			array(
				'label' => esc_html__( 'Grid', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Columns
		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'envision-blocks' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => 4,
				'tablet_default' => 3,
				'mobile_default' => 1,
				'options'        => array(
					4 => 4,
					3 => 3,
					2 => 2,
					1 => 1,
				),
				'selectors'      => array(
					'{{WRAPPER}} .envision-blocks-testimonials-feed' => 'column-count: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'columns_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 24,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-testimonials-feed' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rows_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 24,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-testimonials-feed__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
		$settings = $this->get_settings_for_display(); ?>

			<ul class="envision-blocks-testimonials-feed">

				<?php
				foreach ( $settings['testimonials'] as $index => $item ) :
					$repeater_name_setting_key    = $this->get_repeater_setting_key( 'testimonial_name', 'testimonials', $index );
					$repeater_company_setting_key = $this->get_repeater_setting_key( 'testimonial_company', 'testimonials', $index );
					$repeater_text_setting_key    = $this->get_repeater_setting_key( 'testimonial_text', 'testimonials', $index );
					$this->add_render_attribute( $repeater_name_setting_key, 'class', 'envision-blocks-testimonials-feed__testimonial-author' );
					$this->add_render_attribute( $repeater_company_setting_key, 'class', 'envision-blocks-testimonials-feed__testimonial-company' );
					$this->add_render_attribute( $repeater_text_setting_key, 'class', 'envision-blocks-testimonials-feed__testimonial-text' );
					?>

					<li class="envision-blocks-testimonials-feed__item envision-blocks-testimonials-feed__item-<?php echo esc_attr( $this->get_id() ); ?>">
						<div class="envision-blocks-testimonials-feed__testimonial">

							<div class="envision-blocks-testimonials-feed__media-holder">

								<?php if ( ! empty( $item['testimonial_video']['id'] ) && 'yes' === $item['testimonial_video_type'] ) { ?>
									<div class="envision-blocks-testimonials-feed__video-holder">

										<?php $video_poster = ! empty( $item['testimonial_video_poster']['id'] ) ? $item['testimonial_video_poster']['url'] : ''; ?>

										<video class="envision-blocks-testimonials-feed__video" playsinline poster="<?php echo esc_url( $video_poster ); ?>">
											<source src="<?php echo esc_url( $item['testimonial_video']['url'] ); ?>" type="video/mp4">
										</video>

										<div class="envision-blocks-testimonials-feed__video-details">

											<div class="envision-blocks-testimonials-feed__video-buttons">
												<button type="button" class="envision-blocks-testimonials-feed__play-btn" title="<?php the_title_attribute( __( 'Play Video', 'envision-blocks' ) ); ?>">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM10.6219 8.41459L15.5008 11.6672C15.6846 11.7897 15.7343 12.0381 15.6117 12.2219C15.5824 12.2658 15.5447 12.3035 15.5008 12.3328L10.6219 15.5854C10.4381 15.708 10.1897 15.6583 10.0672 15.4745C10.0234 15.4088 10 15.3316 10 15.2526V8.74741C10 8.52649 10.1791 8.34741 10.4 8.34741C10.479 8.34741 10.5562 8.37078 10.6219 8.41459Z"></path></svg>
												</button>

												<button type="button" class="envision-blocks-testimonials-feed__pause-btn" title="<?php the_title_attribute( __( 'Pause Video', 'envision-blocks' ) ); ?>">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM9 9H11V15H9V9ZM13 9H15V15H13V9Z"></path></svg>
												</button>
											</div>

											<div class="envision-blocks-testimonials-feed__video-review">
												<?php if ( ! empty( $item['testimonial_name'] ) ) : ?>
													<h3 <?php echo $this->get_render_attribute_string( $repeater_name_setting_key ); ?>><?php echo esc_html( $item['testimonial_name'] ); ?></h3>
												<?php endif; ?>
												<?php if ( 'yes' === $item['testimonial_rating'] ) : ?>
													<div class="envision-blocks-testimonials-feed__testimonial-rating">
														<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
															<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/></svg>
														<?php } ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</div>

								<?php } ?>

							</div>
							
							<div class="envision-blocks-testimonials-feed__body">

								<?php if ( 'yes' === $item['testimonial_rating'] && 'yes' !== $item['testimonial_video_type'] ) : ?>
									<div class="envision-blocks-testimonials-feed__testimonial-rating">
										<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
											<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/></svg>
										<?php } ?>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $item['testimonial_text'] ) ) : ?>
									<p <?php echo $this->get_render_attribute_string( $repeater_text_setting_key ); ?>><?php echo esc_html( $item['testimonial_text'] ); ?></p>
								<?php endif; ?>
								
								<?php if ( 'yes' !== $item['testimonial_video_type'] ) : ?>
									<div class="envision-blocks-testimonials-feed__testimonial-cite">
										<?php if ( ! empty( $item['testimonial_image']['id'] ) ) { ?>
											<?php echo wp_get_attachment_image( $item['testimonial_image']['id'], 'full', false, array( 'class' => 'envision-blocks-testimonials-feed__testimonial-img' ) ); ?>
										<?php } ?>
										<div class="envision-blocks-testimonials-feed__testimonial-author-info">
											<?php if ( ! empty( $item['testimonial_name'] ) ) : ?>
												<h3 <?php echo $this->get_render_attribute_string( $repeater_name_setting_key ); ?>><?php echo esc_html( $item['testimonial_name'] ); ?></h3>
											<?php endif; ?>
											<?php if ( ! empty( $item['testimonial_company'] ) ) : ?>
												<span <?php echo $this->get_render_attribute_string( $repeater_company_setting_key ); ?>><?php echo esc_html( $item['testimonial_company'] ); ?></span>
											<?php endif; ?>
										</div>
									</div>
								<?php endif; ?>

							</div>

						</div>
					</li>

				<?php endforeach; ?>

			</ul>

		<?php
	}
}
