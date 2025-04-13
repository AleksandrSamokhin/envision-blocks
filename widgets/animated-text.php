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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Animated_Text extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_script( 'typed', ENVISION_BLOCKS_URL . 'assets/js/lib/typed.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
		wp_register_script( 'envision-blocks-animated-text', ENVISION_BLOCKS_URL . 'assets/js/view/animated-text.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-animated-text';
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
		return esc_html__( 'Animated Text', 'envision-blocks' );
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
		return 'eicon-animated-headline envision-blocks-icon';
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
		return array( 'typed', 'envision-blocks-animated-text' );
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
		return array( 'animated', 'text' );
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
		$this->section_animation();

		$this->section_text_style();
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
			'text_before',
			array(
				'label'       => esc_html__( 'Before Text', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Check this ', 'envision-blocks' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'text_animated',
			array(
				'label'       => esc_html__( 'Animated Text', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Type text separated by commas', 'envision-blocks' ),
				'default'     => esc_html__( 'Amazing, Flexible, Unique', 'envision-blocks' ),
			)
		);

		$this->add_control(
			'text_after',
			array(
				'label'       => esc_html__( 'After Text', 'envision-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( ' text effect', 'envision-blocks' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'     => esc_html__( 'HTML Tag', 'envision-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default'   => 'h2',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'text_align',
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-typed' => 'text-align: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Content > Animation.
	 */
	private function section_animation() {
		$this->start_controls_section(
			'section_animation',
			array(
				'label' => esc_html__( 'Animation', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'cursor_char',
			array(
				'label'   => esc_html__( 'Cursor character', 'envision-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '_',
			)
		);

		$this->add_control(
			'type_speed',
			array(
				'type'    => Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Type speed', 'envision-blocks' ),
				'default' => 70,
			)
		);

		$this->add_control(
			'back_speed',
			array(
				'type'    => Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Backspacing speed', 'envision-blocks' ),
				'default' => 30,
			)
		);

		$this->add_control(
			'back_delay',
			array(
				'type'    => Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Time before backspacing', 'envision-blocks' ),
				'default' => 1000,
			)
		);

		$this->add_control(
			'start_delay',
			array(
				'type'    => Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Time before typing starts', 'envision-blocks' ),
				'default' => 0,
			)
		);

		$this->add_control(
			'loop',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Loop', 'envision-blocks' ),
				'default' => 'yes',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Style > Text.
	 */
	private function section_text_style() {
		$this->start_controls_section(
			'section_text_style',
			array(
				'label' => esc_html__( 'Text', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-typed__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-typed__title',
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
		$settings   = $this->get_settings_for_display();
		$typed_text = $settings['text_animated'];

		$typed_options = array(
			'typeSpeed'  => ( isset( $settings['type_speed'] ) ) ? $settings['type_speed'] : 70,
			'backSpeed'  => ( isset( $settings['back_speed'] ) ) ? $settings['back_speed'] : 30,
			'backDelay'  => ( isset( $settings['back_delay'] ) ) ? $settings['back_delay'] : 700,
			'startDelay' => ( isset( $settings['start_delay'] ) ) ? $settings['start_delay'] : 0,
			'cursorChar' => ( isset( $settings['cursor_char'] ) ) ? $settings['cursor_char'] : '',
			'loop'       => ( 'yes' !== $settings['loop'] ) ? false : true,
		);

		$this->add_render_attribute(
			'typed_options',
			array(
				'data-typed'         => wp_json_encode( $typed_options ),
				'data-widget-id'     => esc_attr( $this->get_id() ),
				'data-typed-strings' => $typed_text,
			)
		);

		?>
			<div class="envision-blocks-typed" <?php echo $this->get_render_attribute_string( 'typed_options' ); ?>>
				<<?php echo \EnvisionBlocks\Utils::validate_html_tag( $settings['tag'] ); ?> class="envision-blocks-typed__title">
					<?php if ( ! empty( $settings['text_before'] ) ) : ?>
						<span class="envision-blocks-typed__text-before"><?php echo $settings['text_before']; ?></span>					
					<?php endif; ?>

					<?php if ( ! empty( $typed_text ) ) : ?>
						<span class="envision-blocks-typed__text-animated" id="envision-blocks-typed__text-<?php echo esc_attr( $this->get_id() ); ?>"></span>
					<?php endif; ?>

					<?php if ( ! empty( $settings['text_after'] ) ) : ?>
						<span class="envision-blocks-typed__text-after"><?php echo $settings['text_after']; ?></span>					
					<?php endif; ?>
				</<?php echo \EnvisionBlocks\Utils::validate_html_tag( $settings['tag'] ); ?>>
			</div>

		<?php
	}
}