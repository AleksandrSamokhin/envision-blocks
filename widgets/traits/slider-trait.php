<?php
namespace EnvisionBlocks\Traits;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

trait Slider_Trait {

	protected function register_slider_controls( $args = array() ) {

		$defaults = array(
			'section_start_condition'    => array(),
			'space_between_handle'       => 'blog_space_between',
			'space_between_default_size' => 40,
			'space_between_condition'    => array(
				'slides_to_show!' => '1',
			),
			'slides_to_show'             => 4,
			'slides_to_scroll'           => 4,
			'laptop_default'             => 3,
			'tablet_default'             => 2,
			'mobile_default'             => 1,
			'effect_default'             => 'slide',
			'slides_centered'            => '',
			'parallax'                   => '',
			'animation_speed'            => 500,
			'slide_width_handle'         => 'blog_slide_width',
			'slide_width'                => 70,
			'default_navigation'         => 'arrows',
			'navigation'                 => array(
				'both'   => esc_html__( 'Arrows and Dots', 'envision-blocks' ),
				'dots'   => esc_html__( 'Dots', 'envision-blocks' ),
				'arrows' => esc_html__( 'Arrows', 'envision-blocks' ),
				'none'   => esc_html__( 'None', 'envision-blocks' ),
			),
			'scrollbar'                  => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$this->start_controls_section(
			'section_slider_options',
			array(
				'label'     => esc_html__( 'Slider Options', 'envision-blocks' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => $args['section_start_condition'],
			)
		);

		$this->add_control(
			$args['space_between_handle'],
			array(
				'label'     => esc_html__( 'Space Between', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => $args['space_between_default_size'],
				),
				'condition' => $args['space_between_condition'],
			)
		);

		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'          => esc_html__( 'Slides to Show', 'envision-blocks' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => array(
					'' => esc_html__( 'Default', 'envision-blocks' ),
				) + $slides_to_show,
				'default'        => $args['slides_to_show'],
				'laptop_default' => $args['laptop_default'],
				'tablet_default' => $args['tablet_default'],
				'mobile_default' => $args['mobile_default'],
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'          => esc_html__( 'Slides to Scroll', 'envision-blocks' ),
				'type'           => Controls_Manager::SELECT,
				'description'    => esc_html__( 'Set how many slides are scrolled per swipe.', 'envision-blocks' ),
				'options'        => array(
					'' => esc_html__( 'Default', 'envision-blocks' ),
				) + $slides_to_show,
				'default'        => $args['slides_to_scroll'],
				'laptop_default' => $args['laptop_default'],
				'tablet_default' => $args['tablet_default'],
				'mobile_default' => $args['mobile_default'],
			)
		);

		$this->add_control(
			'slides_centered',
			array(
				'label'   => esc_html__( 'Slides Centered', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => $args['slides_centered'],
			)
		);

		$this->add_responsive_control(
			$args['slide_width_handle'],
			array(
				'label'           => esc_html__( 'Slide Width', 'envision-blocks' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => array( '%', 'custom' ),
				'default'         => array(
					'unit' => '%',
					'size' => $args['slide_width'],
				),
				'desktop_default' => array(
					'size' => $args['slide_width'],
					'unit' => '%',
				),
				'mobile_default'  => array(
					'size' => 100,
					'unit' => '%',
				),
				'range'           => array(
					'%' => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'selectors'       => array(
					'{{WRAPPER}} .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'       => array(
					'slides_centered' => 'yes',
				),
			)
		);

		if ( 'yes' === $args['parallax'] ) {
			$this->add_control(
				'parallax',
				array(
					'label'   => esc_html__( 'Parallax', 'envision-blocks' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => $args['parallax'],
				)
			);
		}

		$this->add_control(
			'navigation',
			array(
				'label'              => esc_html__( 'Navigation', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => $args['default_navigation'],
				'options'            => $args['navigation'],
				'frontend_available' => true,
			)
		);

		if ( 'yes' === $args['scrollbar'] ) {
			$this->add_control(
				'scrollbar',
				array(
					'type'    => Controls_Manager::SWITCHER,
					'label'   => esc_html__( 'Scrollbar', 'envision-blocks' ),
					'default' => 'no',
				)
			);
		}

		$this->add_control(
			'infinite',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Infinite Loop', 'envision-blocks' ),
				'default' => 'yes',
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'              => esc_html__( 'Effect', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => $args['effect_default'],
				'options'            => array(
					'slide'     => esc_html__( 'Slide', 'envision-blocks' ),
					'fade'      => esc_html__( 'Fade', 'envision-blocks' ),
					'coverflow' => esc_html__( 'Coverflow', 'envision-blocks' ),
					'cards'     => esc_html__( 'Cards', 'envision-blocks' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'coverflow_depth',
			array(
				'label'     => esc_html__( 'Depth', 'envision-blocks' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 100,
				'condition' => array(
					'effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_modifier',
			array(
				'label'     => esc_html__( 'Modifier', 'envision-blocks' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0.5,
				'condition' => array(
					'effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_rotate',
			array(
				'label'     => esc_html__( 'Rotate', 'envision-blocks' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 50,
				'condition' => array(
					'effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_stretch',
			array(
				'label'     => esc_html__( 'Stretch', 'envision-blocks' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'condition' => array(
					'effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_slide_shadows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Slide Shadows', 'envision-blocks' ),
				'default'   => 'yes',
				'condition' => array(
					'effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'per_slide_offset',
			array(
				'label'              => esc_html__( 'Per Slide Offset', 'envision-blocks' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 8,
				'frontend_available' => true,
				'condition'          => array(
					'effect' => 'cards',
				),
			)
		);

		$this->add_control(
			'per_slide_rotate',
			array(
				'label'              => esc_html__( 'Per Slide Rotate', 'envision-blocks' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 2,
				'frontend_available' => true,
				'condition'          => array(
					'effect' => 'cards',
				),
			)
		);

		$this->add_control(
			'cards_rotate',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Rotate', 'envision-blocks' ),
				'default'   => 'yes',
				'condition' => array(
					'effect' => 'cards',
				),
			)
		);

		$this->add_control(
			'cards_slide_shadows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Slide Shadows', 'envision-blocks' ),
				'default'   => 'yes',
				'condition' => array(
					'effect' => 'cards',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => esc_html__( 'Animation Speed', 'envision-blocks' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => $args['animation_speed'],
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'   => esc_html__( 'Direction', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => array(
					'ltr' => esc_html__( 'Left', 'envision-blocks' ),
					'rtl' => esc_html__( 'Right', 'envision-blocks' ),
				),
			)
		);

		$this->add_control(
			'auto_height',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Auto height', 'envision-blocks' ),
				'default' => '',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Autoplay', 'envision-blocks' ),
				'default' => 'yes',
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'       => esc_html__( 'Pause on Hover', 'envision-blocks' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => array(
					'autoplay' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'     => esc_html__( 'Pause on Interaction', 'envision-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'       => esc_html__( 'Autoplay Speed', 'envision-blocks' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5000,
				'condition'   => array(
					'autoplay' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get slider settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_slider_settings( $settings, $args = array( 'id' => 0 ) ) {
		$breakpoints = \Elementor\Core\Responsive\Responsive::get_breakpoints();
		$mobile      = strval( $breakpoints['md'] );
		$tablet      = strval( $breakpoints['lg'] );
		$laptop      = strval( $breakpoints['xl'] );
		$is_single   = 1 === absint( $settings['slides_to_show'] );

		$show_dots      = ( in_array( $settings['navigation'], array( 'dots', 'both' ) ) );
		$show_arrows    = ( in_array( $settings['navigation'], array( 'arrows', 'both' ) ) );
		$show_scrollbar = isset( $settings['scrollbar'] ) && $settings['scrollbar'] === 'yes';

		$slider_options = array(
			'speed' => absint( $settings['speed'] ),
			'loop'  => ( 'yes' === $settings['infinite'] ),
		);

		$slider_options = wp_parse_args( $args, $slider_options );

		if ( 'yes' === $settings['slides_centered'] ) {
			$slider_options['centeredSlides'] = true;
			$slider_options['slidesPerView']  = 'auto';
		} else {
			$slider_options['slidesPerView']  = ( isset( $settings['slides_to_show_mobile'] ) ) ? absint( $settings['slides_to_show_mobile'] ) : 1;
			$slider_options['slidesPerGroup'] = ( isset( $settings['slides_to_scroll_mobile'] ) ) ? absint( $settings['slides_to_scroll_mobile'] ) : 1;
			$slider_options['breakpoints']    = array(
				$mobile => array(
					'slidesPerView'  => ( isset( $settings['slides_to_show_tablet'] ) ) ? absint( $settings['slides_to_show_tablet'] ) : 2,
					'slidesPerGroup' => ( isset( $settings['slides_to_scroll_tablet'] ) ) ? absint( $settings['slides_to_scroll_tablet'] ) : 2,
				),
				$tablet => array(
					'slidesPerView'  => ( isset( $settings['slides_to_show_laptop'] ) ) ? absint( $settings['slides_to_show_laptop'] ) : 3,
					'slidesPerGroup' => ( isset( $settings['slides_to_scroll_laptop'] ) ) ? absint( $settings['slides_to_scroll_laptop'] ) : 3,
				),
				$laptop => array(
					'slidesPerView'  => ( $settings['slides_to_show'] ) ? absint( $settings['slides_to_show'] ) : 4,
					'slidesPerGroup' => ( $settings['slides_to_scroll'] ) ? absint( $settings['slides_to_scroll'] ) : 4,
				),
			);
		}

		if ( $is_single ) {
			$slider_options['effect'] = $settings['effect'];

			if ( 'fade' === $settings['effect'] ) {
				$slider_options['fadeEffect'] = array(
					'crossFade' => true,
				);
			}
		} else {
			$slider_options['slidesPerGroup'] = ( isset( $settings['slides_to_scroll_mobile'] ) ) ? absint( $settings['slides_to_scroll_mobile'] ) : 1;
		}

		if ( 'yes' === $settings['auto_height'] ) {
			$slider_options['autoHeight'] = true;
		}

		if ( isset( $settings['parallax'] ) && 'yes' === $settings['parallax'] ) {
			$slider_options['parallax'] = true;
		}

		if ( 'cards' === $settings['effect'] ) {
			$slider_options['effect'] = 'cards';

			if ( ! empty( $settings['per_slide_offset'] ) || 0 === $settings['per_slide_offset'] ) {
				$slider_options['cardsEffect']['perSlideOffset'] = $settings['per_slide_offset'];
			}

			if ( ! empty( $settings['per_slide_rotate'] ) || 0 === $settings['per_slide_rotate'] ) {
				$slider_options['cardsEffect']['perSlideRotate'] = $settings['per_slide_rotate'];
			}

			if ( 'yes' === $settings['cards_slide_shadows'] ) {
				$slider_options['cardsEffect']['slideShadows'] = true;
			} else {
				$slider_options['cardsEffect']['slideShadows'] = false;
			}

			if ( 'yes' === $settings['cards_rotate'] ) {
				$slider_options['cardsEffect']['rotate'] = true;
			} else {
				$slider_options['cardsEffect']['rotate'] = false;
			}
		}

		if ( 'coverflow' === $settings['effect'] ) {
			$slider_options['effect'] = $settings['effect'];

			if ( ! empty( $settings['coverflow_depth'] ) || 0 === $settings['coverflow_depth'] ) {
				$slider_options['coverflowEffect']['depth'] = $settings['coverflow_depth'];
			}

			if ( ! empty( $settings['coverflow_modifier'] ) || 0 === $settings['coverflow_modifier'] ) {
				$slider_options['coverflowEffect']['modifier'] = $settings['coverflow_modifier'];
			}

			if ( ! empty( $settings['coverflow_rotate'] ) || 0 === $settings['coverflow_rotate'] ) {
				$slider_options['coverflowEffect']['rotate'] = $settings['coverflow_rotate'];
			}

			if ( ! empty( $settings['coverflow_stretch'] ) || 0 === $settings['coverflow_stretch'] ) {
				$slider_options['coverflowEffect']['stretch'] = $settings['coverflow_stretch'];
			}

			if ( 'yes' === $settings['coverflow_slide_shadows'] ) {
				$slider_options['coverflowEffect']['slideShadows'] = true;
			} else {
				$slider_options['coverflowEffect']['slideShadows'] = false;
			}
		}

		if ( $settings[ $args['space_between_handle'] ] ) {
			$slider_options['spaceBetween'] = ( isset( $settings[ $args['space_between_handle'] ] ) ) ? absint( $settings[ $args['space_between_handle'] ]['size'] ) : 30;
		}

		if ( $show_dots ) {
			$slider_options['pagination'] = array(
				'el'        => '.swiper-pagination-' . $args['id'],
				'type'      => 'bullets',
				'clickable' => true,
			);
		}

		if ( $show_arrows ) {
			$slider_options['navigation'] = array(
				'nextEl' => '.envision-blocks-swiper-button-next-' . $args['id'],
				'prevEl' => '.envision-blocks-swiper-button-prev-' . $args['id'],
			);
		}

		if ( $show_scrollbar ) {
			$slider_options['scrollbar'] = array(
				'el'   => '.envision-blocks-swiper-scrollbar-' . $args['id'],
				'hide' => false,
			);
		}

		if ( 'yes' === $settings['autoplay'] ) {
			$slider_options['autoplay'] = array(
				'delay'                => $settings['autoplay_speed'],
				'disableOnInteraction' => ( 'yes' === $settings['pause_on_interaction'] ),
				'pauseOnMouseEnter'    => ( 'yes' === $settings['pause_on_hover'] ),
			);
		}

		return $slider_options;
	}

	/**
	 * Get slider navigation.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_slider_navigation( $id = 0 ) {
		?>
			<div class="envision-blocks-swiper-button envision-blocks-swiper-button-prev envision-blocks-swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="envision-blocks-swiper-button__icon"><path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z"></path></svg>
				<span class="screen-reader-text"><?php echo esc_html__( 'Previous', 'envision-blocks' ); ?></span>
			</div>
			<div class="envision-blocks-swiper-button envision-blocks-swiper-button-next envision-blocks-swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="envision-blocks-swiper-button__icon"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg>
				<span class="screen-reader-text"><?php echo esc_html__( 'Next', 'envision-blocks' ); ?></span>
			</div>
		<?php
	}
}