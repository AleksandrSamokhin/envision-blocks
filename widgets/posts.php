<?php
namespace EnvisionBlocks\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;

use EnvisionBlocks\Traits\Posts_Trait;
use EnvisionBlocks\Traits\Slider_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Posts extends Widget_Base {

	use Posts_Trait;
	use Slider_Trait;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'envision-blocks-posts', ENVISION_BLOCKS_URL . 'assets/css/posts.min.css', array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-posts', 'rtl', 'replace' );
		wp_register_script( 'envision-blocks-posts', ENVISION_BLOCKS_URL . 'assets/js/view/posts.min.js', array( 'elementor-frontend' ), ENVISION_BLOCKS_VERSION, true );
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
		return 'envision-blocks-posts';
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
		return esc_html__( 'Posts', 'envision-blocks' );
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
		return array( 'swiper', 'imagesloaded', 'isotope', 'envision-blocks-posts' );
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
		return array( 'envision-blocks-posts' );
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
		return array( 'blog', 'posts' );
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
		$this->section_layout();
		$this->section_posts();

		$this->register_slider_controls(
			array(
				'section_start_condition'    => array( 'posts_layout' => 'slider' ),
				'space_between_handle'       => 'posts_space_between',
				'space_between_default_size' => 20,
				'slide_width_handle'         => 'posts_slide_width',
				'default_navigation'         => 'dots',
			)
		);

		$this->section_image();
		$this->section_content();
		$this->section_excerpt();

		$this->section_filter_style();
		$this->section_content_style();
		$this->section_grid_style();
		$this->section_image_style();
		$this->section_title_style();
		$this->section_category_style();
		$this->section_meta_style();
		$this->section_excerpt_style();
		$this->section_pagination_style();
	}

	/**
	 * Content > Layout.
	 */
	private function section_layout() {

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'posts_layout',
			array(
				'label'   => esc_html__( 'Layout', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'   => esc_html__( 'Grid', 'envision-blocks' ),
					'slider' => esc_html__( 'Slider', 'envision-blocks' ),
				),
			)
		);

		// Featured post
		$this->add_control(
			'featured_post',
			array(
				'label'     => esc_html__( 'First Featured Post', 'envision-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'posts_layout!' => 'slider',
				),
			)
		);

		// Columns
		$this->add_responsive_control(
			'post_columns',
			array(
				'label'              => esc_html__( 'Columns', 'envision-blocks' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 4,
				'tablet_default'     => 6,
				'mobile_default'     => 12,
				'options'            => array(
					3  => 4,
					4  => 3,
					6  => 2,
					12 => 1,
				),
				'condition'          => array(
					'posts_layout!' => 'slider',
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Posts.
	 */
	private function section_posts() {
		$this->start_controls_section(
			'section_posts',
			array(
				'label' => esc_html__( 'Query', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Posts per page
		$this->add_control(
			'posts_per_page',
			array(
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Posts per page', 'envision-blocks' ),
				'placeholder' => esc_html__( 'Posts per page', 'envision-blocks' ),
				'separator'   => 'before',
				'default'     => 7,
			)
		);

		// Orderby
		$this->add_control(
			'orderby',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Order by', 'envision-blocks' ),
				'default' => 'date',
				'options' => array(
					'date'          => esc_html__( 'Date', 'envision-blocks' ),
					'title'         => esc_html__( 'Title', 'envision-blocks' ),
					'modified'      => esc_html__( 'Modified date', 'envision-blocks' ),
					'menu_order'    => esc_html__( 'Menu Order', 'envision-blocks' ),
					'comment_count' => esc_html__( 'Comment count', 'envision-blocks' ),
					'rand'          => esc_html__( 'Random', 'envision-blocks' ),
				),
			)
		);

		// Order
		$this->add_control(
			'order',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Order', 'envision-blocks' ),
				'default' => 'DESC',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending', 'envision-blocks' ),
					'DESC' => esc_html__( 'Descending', 'envision-blocks' ),
				),
			)
		);

		// Ignore sticky posts
		$this->add_control(
			'ignore_sticky_posts',
			array(
				'label'     => esc_html__( 'Ignore Sticky Posts', 'envision-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default'   => 'yes',
			)
		);

		// Show filter
		$this->add_control(
			'filter_show',
			array(
				'label'     => esc_html__( 'Show filter', 'envision-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'posts_layout!' => 'slider',
				),
			)
		);

		$this->add_control(
			'filter_all_text',
			array(
				'type'      => Controls_Manager::TEXT,
				'label'     => esc_html__( 'All Text', 'envision-blocks' ),
				'default'   => esc_html__( 'All', 'envision-blocks' ),
				'condition' => array(
					'filter_show' => 'yes',
				),
			)
		);

		// Filter Categories
		$this->add_control(
			'filter_item_list',
			array(
				'label'       => esc_html__( 'Categories', 'envision-blocks' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_post_categories(),
				'multiple'    => true,
				'label_block' => true,
				'description' => esc_html__( 'Remove all categories to show all. This option affects filter as well.', 'envision-blocks' ),
			)
		);

		// Display pagination.
		$this->add_control(
			'post_pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'envision-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'numbered',
				'separator' => 'before',
				'options'   => array(
					'disabled'  => esc_html__( 'No Pagination', 'envision-blocks' ),
					'numbered'  => esc_html__( 'Numbered', 'envision-blocks' ),
					'load_more' => esc_html__( 'Load More', 'envision-blocks' ),
				),
				'condition' => array(
					'posts_layout!' => 'slider',
				),
			)
		);

		$this->add_control(
			'pagination_load_more_text',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'Load More Text', 'envision-blocks' ),
				'placeholder' => esc_html__( 'Load More', 'envision-blocks' ),
				'default'     => esc_html__( 'Load More', 'envision-blocks' ),
				'condition'   => array( 'post_pagination' => 'load_more' ),
			)
		);

		// Specific Posts by ID.
		$this->add_control(
			'post_ids',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'Show specific posts by ID', 'envision-blocks' ),
				'placeholder' => esc_html__( 'ex.: 256, 54, 78', 'envision-blocks' ),
				'description' => esc_html__( 'Paste post ID\'s separated by commas. To find ID, click edit post and you\'ll find it in the browser address bar', 'envision-blocks' ),
				'default'     => '',
				'separator'   => 'before',
				'label_block' => true,
			)
		);

		$this->end_controls_section();
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

		// Hide image
		$this->add_control(
			'image_hide',
			array(
				'label'   => esc_html__( 'Hide Image', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		// Image Size
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'exclude'   => array( 'custom' ),
				'include'   => array(),
				'default'   => 'large',
				'condition' => array(
					'image_hide!' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Content.
	 */
	private function section_content() {
		$this->start_controls_section(
			'section_meta',
			array(
				'label' => esc_html__( 'Meta', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Hide date
		$this->add_control(
			'date_hide',
			array(
				'label'   => esc_html__( 'Hide Date', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		// Hide author
		$this->add_control(
			'author_hide',
			array(
				'label'   => esc_html__( 'Hide Author', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		// Hide categories
		$this->add_control(
			'categories_hide',
			array(
				'label'   => esc_html__( 'Hide Category', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Content > Excerpt.
	 */
	private function section_excerpt() {
		$this->start_controls_section(
			'section_excerpt',
			array(
				'label' => esc_html__( 'Excerpt', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Hide excerpt
		$this->add_control(
			'excerpt_hide',
			array(
				'label'   => esc_html__( 'Hide Excerpt', 'envision-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		// Excerpt length
		$this->add_control(
			'excerpt_length',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Excerpt length (words)', 'envision-blocks' ),
				'condition' => array( 'excerpt_hide' => '' ),
				'default'   => 15,
			)
		);

		$this->end_controls_section();
	}



	/**
	 * Style > Filter.
	 */
	private function section_filter_style() {
		$this->start_controls_section(
			'section_filter_style',
			array(
				'label'     => esc_html__( 'Filter', 'envision-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'filter_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_align',
			array(
				'label'   => esc_html__( 'Filter alignment', 'envision-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'text-left'   => array(
						'title' => esc_html__( 'Left', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'text-center' => array(
						'title' => esc_html__( 'Center', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'text-right'  => array(
						'title' => esc_html__( 'Right', 'envision-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default' => 'text-center',
				'toggle'  => true,
			)
		);

		// Filter active color
		$this->add_control(
			'filter_active_color',
			array(
				'label'     => esc_html__( 'Filter active color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-isotope-filter a.active, {{WRAPPER}} .envision-blocks-isotope-filter a:hover, {{WRAPPER}} .envision-blocks-isotope-filter a:focus' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		// Filter links color
		$this->add_control(
			'filter_links_color',
			array(
				'label'     => esc_html__( 'Filter links color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-isotope-filter a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_typography',
				'selector' => '{{WRAPPER}} .envision-blocks-isotope-filter a',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Content.
	 */
	private function section_content_style() {
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Content', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_padding',
			array(
				'label'     => esc_html__( 'Padding', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .entry__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'posts_layout!' => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'content_border',
				'label'     => esc_html__( 'Border', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .entry__body',
				'condition' => array(
					'posts_layout!' => 'grid',
				),
			)
		);

		$this->add_control(
			'content_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .entry__body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'posts_layout!' => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'content_box_shadow',
				'label'     => esc_html__( 'Box Shadow', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .entry',
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

		// Columns gap.
		$this->add_responsive_control(
			'grid_style_columns_gap',
			array(
				'label'     => esc_html__( 'Columns gap', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-masonry-item' => 'padding-right: calc( {{SIZE}}{{UNIT}} / 2 ); padding-left: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .envision-blocks-masonry-grid__posts' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
				),
			)
		);

		// Rows gap.
		$this->add_responsive_control(
			'grid_style_rows_gap',
			array(
				'label'     => esc_html__( 'Rows gap', 'envision-blocks' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => array(
					'size' => 56,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .entry' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
			'image_height',
			array(
				'label'          => esc_html__( 'Height', 'envision-blocks' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .entry__img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_object_fit',
			array(
				'label'     => esc_html__( 'Object Fit', 'envision-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'image_height[size]!' => '',
				),
				'options'   => array(
					''        => esc_html__( 'Default', 'envision-blocks' ),
					'fill'    => esc_html__( 'Fill', 'envision-blocks' ),
					'cover'   => esc_html__( 'Cover', 'envision-blocks' ),
					'contain' => esc_html__( 'Contain', 'envision-blocks' ),
				),
				'default'   => 'cover',
				'selectors' => array(
					'{{WRAPPER}} .entry__img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'image_css_filter' );

		$this->start_controls_tab(
			'image_filter_normal',
			array(
				'label' => esc_html__( 'Normal', 'envision-blocks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .entry__img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_filter_hover',
			array(
				'label' => esc_html__( 'Hover', 'envision-blocks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .entry__img:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .entry__img-holder',
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .entry__img-holder' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .entry__img-holder',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Title.
	 */
	private function section_title_style() {
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Title', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry__title:hover a' => 'color: {{VALUE}}; background-image: linear-gradient(to right, {{VALUE}} 0%, {{VALUE}} 100%);',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .entry__title',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title Heading Tag', 'envision-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1' => esc_html__( 'H1', 'envision-blocks' ),
					'h2' => esc_html__( 'H2', 'envision-blocks' ),
					'h3' => esc_html__( 'H3', 'envision-blocks' ),
					'h4' => esc_html__( 'H4', 'envision-blocks' ),
					'h5' => esc_html__( 'H5', 'envision-blocks' ),
					'h6' => esc_html__( 'H6', 'envision-blocks' ),
				),
				'default' => 'h2',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Category.
	 */
	private function section_category_style() {
		$this->start_controls_section(
			'section_category_style',
			array(
				'label' => esc_html__( 'Category', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'category_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry__body .entry__meta-category a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'category_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry__body .entry__meta-category a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_typography',
				'selector' => '{{WRAPPER}} .entry__meta-category',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Meta.
	 */
	private function section_meta_style() {
		$this->start_controls_section(
			'section_meta_style',
			array(
				'label' => esc_html__( 'Meta', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry__meta-item, {{WRAPPER}} .entry__meta-item a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry__meta-item a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .entry__meta-item, {{WRAPPER}} .entry__meta-item a',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Excerpt.
	 */
	private function section_excerpt_style() {
		$this->start_controls_section(
			'section_excerpt_style',
			array(
				'label' => esc_html__( 'Excerpt', 'envision-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry__excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .entry__excerpt',
			)
		);

		$this->end_controls_section();
	}



	/**
	 * Style > Pagination.
	 */
	private function section_pagination_style() {
		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => esc_html__( 'Pagination', 'envision-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_pagination!' => 'disabled',
				),
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
					'{{WRAPPER}} .envision-blocks-pagination' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_load_more_padding',
			array(
				'label'     => esc_html__( 'Padding', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-load-more__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_control(
			'pagination_load_more_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-load-more__button' => 'background-color: {{VALUE}};',
				),
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_control(
			'pagination_load_more_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-load-more__button' => 'color: {{VALUE}};',
				),
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_control(
			'pagination_load_more_hover_background_color',
			array(
				'label'     => esc_html__( 'Hover Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-load-more__button:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_control(
			'pagination_load_more_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-load-more__button:hover' => 'color: {{VALUE}};',
				),
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'pagination_load_more_border',
				'label'     => esc_html__( 'Border', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .envision-blocks-load-more__button',
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_control(
			'pagination_load_more_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'envision-blocks' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .envision-blocks-load-more__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_load_more_box_shadow',
				'label'     => esc_html__( 'Box Shadow', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .envision-blocks-load-more__button',
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'pagination_load_more_typography',
				'selector'  => '{{WRAPPER}} .envision-blocks-load-more__button',
				'condition' => array( 'post_pagination' => 'load_more' ),
			)
		);

		$this->add_control(
			'pagination_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .page-numbers' => 'background-color: {{VALUE}};',
				),
				'condition' => array( 'post_pagination' => 'numbered' ),
			)
		);

		$this->add_control(
			'pagination_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .page-numbers' => 'color: {{VALUE}};',
				),
				'condition' => array( 'post_pagination' => 'numbered' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'pagination_border',
				'label'     => esc_html__( 'Border', 'envision-blocks' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .page-numbers:not(.current)',
				'condition' => array( 'post_pagination' => 'numbered' ),
			)
		);

		$this->add_control(
			'pagination_active_background_color',
			array(
				'label'     => esc_html__( 'Active Background Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .page-numbers.current, {{WRAPPER}} .page-numbers:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array( 'post_pagination' => 'numbered' ),
			)
		);

		$this->add_control(
			'pagination_active_text_color',
			array(
				'label'     => esc_html__( 'Active Text Color', 'envision-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .page-numbers.current, {{WRAPPER}} .page-numbers:hover' => 'color: {{VALUE}};',
				),
				'condition' => array( 'post_pagination' => 'numbered' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'pagination_typography',
				'selector'  => '{{WRAPPER}} .page-numbers',
				'condition' => array( 'post_pagination' => 'numbered' ),
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
		$query    = $this->get_query( $settings );

		$string_ID  = $settings['post_ids'];
		$post_ID    = ( ! empty( $string_ID ) ) ? array_map( 'intval', explode( ',', $string_ID ) ) : '';
		$ajax_param = '';

		if ( 'yes' == $settings['filter_show'] ) {
			echo $this->render_filter_items( $settings );
		}

		// AJAX parameters
		if ( ! empty( $query ) && is_object( $query ) ) {
			$ajax_param = $this->_ajax_param( $settings );
		}

		// Max page
		$page_max = ( ! empty( $query->max_num_pages ) ) ? $query->max_num_pages : 1;

		// Open load more container
		echo '<div class="envision-blocks-load-more-container"
							data-page_max="' . esc_attr( $page_max ) . '"
							data-page="1"
							data-settings=\'' . wp_json_encode( $ajax_param ) . '\'>';

		// Open row / Start loop
		$columns = ( ! empty( $settings['post_columns_mobile'] ) ? 'envision-blocks-col-' . $settings['post_columns_mobile'] : '' ) . ( ! empty( $settings['post_columns_tablet'] ) ? ' envision-blocks-col-md-' . $settings['post_columns_tablet'] : '' ) . ( ! empty( $settings['post_columns'] ) ? ' envision-blocks-col-lg-' . $settings['post_columns'] : '' );

		if ( 'slider' === $settings['posts_layout'] ) {
			$show_dots      = ( in_array( $settings['navigation'], array( 'dots', 'both' ) ) );
			$show_arrows    = ( in_array( $settings['navigation'], array( 'arrows', 'both' ) ) );
			$slider_options = $this->get_slider_settings(
				$settings,
				array(
					'id'                   => esc_attr( $this->get_id() ),
					'space_between_handle' => 'posts_space_between',
				)
			);

			$this->add_render_attribute(
				array(
					'carousel' => array(
						'class'                => 'swiper-container envision-blocks-slider envision-blocks-posts-slider envision-blocks-posts-slider-' . esc_attr( $this->get_id() ),
						'data-slider-settings' => wp_json_encode( $slider_options ),
						'dir'                  => $settings['direction'],
					),
				)
			);
		} else {
			$this->add_render_attribute(
				'grid',
				'class',
				array(
					'envision-blocks-row envision-blocks-masonry-grid__posts',
					'envision-blocks-masonry-grid__posts-' . esc_attr( $this->get_id() ),
				)
			);
		}

		if ( $query->have_posts() ) :

			if ( 'slider' === $settings['posts_layout'] ) : ?>
				<!-- Slider main container -->
				<div <?php echo $this->get_render_attribute_string( 'carousel' ); ?>>
					<div class="swiper-wrapper">
			<?php else : ?>
				<div <?php echo $this->get_render_attribute_string( 'grid' ); ?>>
					<?php
					echo '<div class="envision-blocks-grid-sizer ' . esc_attr( $columns ) . '"></div>';
			endif;

				// Render posts layout
			if ( isset( $settings['posts_layout'] ) ) {

				switch ( $settings['posts_layout'] ) {

					case 'grid':
						$this->render_posts( $settings, $query, 'grid' );
						break;

					case 'slider':
						$this->render_posts( $settings, $query, 'slider' );
						break;

					default:
						$this->render_posts( $settings, $query, 'grid' );
						break;
				}
			}

			if ( 'slider' === $settings['posts_layout'] ) {
					$slides_count = $settings['posts_per_page'];
					echo '</div> <!-- .swiper-wrapper -->';

				if ( 1 < $slides_count ) :
					?>
						<?php if ( $show_dots ) : ?>
							<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
						<?php endif; ?>
					<?php
					endif;

				echo '</div> <!-- .swiper-container -->';

				if ( $show_arrows && 1 < $slides_count ) :
					?>
					<?php $this->get_slider_navigation( esc_attr( $this->get_id() ) ); ?>
					<?php
				endif;

			} else {
				echo '</div> <!-- .envision-blocks-row -->';
			}
			?>
			
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php
		endif;

		// Close load more container
		echo '</div> <!-- .envision-blocks-load-more-container -->';

		// Render Pagination
		if ( 'slider' !== $settings['posts_layout'] ) {
			$this->render_pagination( $settings, $query );
		}
	}


	/**
	 * Get posts Query based on the settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return array posts query
	 */
	protected function get_query( $settings ) {
		$string_ID = $settings['post_ids'];
		$post_ID   = ( ! empty( $string_ID ) ) ? array_map( 'intval', explode( ',', $string_ID ) ) : '';

		$args = array(
			'post_type'   => 'post',
			'post_status' => 'publish',
		);

		// Posts per page
		if ( ! empty( $settings['posts_per_page'] ) ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		// Category
		if ( ! empty( $settings['filter_item_list'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $settings['filter_item_list'],
			);
		}

		// Order by
		if ( ! empty( $settings['orderby'] ) ) {
			$args['orderby'] = $settings['orderby'];
		}

		// Order
		if ( ! empty( $settings['order'] ) ) {
			$args['order'] = $settings['order'];
		}

		// Sticky Posts
		if ( 'yes' == $settings['ignore_sticky_posts'] ) {
			$args['ignore_sticky_posts'] = 1;
		}

		// Pagination.
		if ( ! empty( $settings['post_pagination'] ) ) {
			if ( is_front_page() ) {
				$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
			} else {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			}
			$args['paged'] = $paged;
		}

		// Specific Posts by ID's
		if ( ! empty( $settings['post_ids'] ) ) {
			$args['post__in'] = $post_ID;
		}

		// Query
		$query = new \WP_Query( $args );

		return $query;
	}


	/**
	 * AJAX parameters.
	 *
	 * @since 1.0.0
	 * @return string String of data attributes
	 * @access protected
	 */
	protected function _ajax_param( $settings ) {

		if ( empty( $settings ) ) {
			return false;
		}

		$param             = array();
		$param['block_id'] = esc_attr( $this->get_id() );

		// Post Type
		$param['post_type'] = 'post';

		// Widget Type
		$param['widget_type'] = $this->get_name();

		$attributes = array(
			'posts_layout',
			'image_hide',
			'image_size',
			'ignore_sticky_posts',
			'author_hide',
			'categories_hide',
			'date_hide',
			'excerpt_hide',
			'excerpt_length',
			'posts_per_page',
			'post_columns_mobile',
			'post_columns_tablet',
			'post_columns',
			'filter_item_list',
			'title_tag',
			'orderby',
			'order',
		);

		foreach ( $attributes as $attribute ) {
			if ( isset( $settings[ $attribute ] ) ) {
				$param[ $attribute ] = $settings[ $attribute ];
			}
		}

		return $param;
	}


	/**
	 * Render filter items.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_filter_items( $settings ) {

		$categories_list = $settings['filter_item_list'];

		$align = ( $settings['filter_align'] ) ? $settings['filter_align'] : '';

		$this->add_render_attribute(
			'filter',
			'class',
			array(
				'envision-blocks-isotope-filter',
				$align,
				'envision-blocks-isotope-filter-' . esc_attr( $this->get_id() ),
			)
		);

		$terms = $this->get_filter_list( $categories_list );

		if ( ! empty( $terms ) ) {
			?>
			<div <?php echo $this->get_render_attribute_string( 'filter' ); ?>>
				<?php if ( $settings['filter_all_text'] ) : ?>
					<a href="#" class="envision-blocks-filter active" data-filter="*"><?php echo esc_html( $settings['filter_all_text'] ); ?></a>
				<?php endif; ?>
				<?php foreach ( $terms as $term ) : ?>
					<a href="#" class="envision-blocks-filter" data-filter="<?php echo $term->slug; ?>"><?php echo $term->name; ?></a>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}


	/**
	 * Get the list of filter categories.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return array filter list items
	 */
	protected function get_filter_list( $categories_list = '' ) {

		$filter_list = array();

		$categories = get_categories(
			array(
				'include'  => $categories_list,
				'exclude'  => '1',
				'number'   => 50,
				'taxonomy' => 'category',
			)
		);

		// check category input
		if ( ! empty( $categories_list ) ) {

			foreach ( $categories_list as $cat ) {
				foreach ( $categories as $category ) {
					if ( $cat == $category->slug ) {
						$filter_list[] = $category;
					}
				}
			}
		} else {
			foreach ( $categories as $category ) {
				$filter_list[] = $category;
			}
		}

		return $filter_list;
	}


	/**
	 * Render pagination.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_pagination( $settings, $query ) {

		if ( 'disabled' == $settings['post_pagination'] || $query->max_num_pages < 2 ) {
			return;
		}

		if ( 'numbered' == $settings['post_pagination'] ) {
			if ( is_front_page() ) {
				$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
			} else {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			}
		}

		// Pagination
		if ( 'numbered' == $settings['post_pagination'] ) :
			$paginate_args = array(
				'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				'current'   => max( 1, $paged ),
				'total'     => $query->max_num_pages,
				'prev_text' => is_rtl() ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path fill="none" d="M0 0h24v24H0z"></path><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"></path></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path fill="none" d="M0 0h24v24H0z"></path><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"></path></svg>',
				'next_text' => is_rtl() ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path fill="none" d="M0 0h24v24H0z"></path><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"></path></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path fill="none" d="M0 0h24v24H0z"></path><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"></path></svg>',
			);

			$pagination = paginate_links( $paginate_args );
			?>
			<nav class="envision-blocks-pagination envision-blocks-posts-pagination" itemscope itemtype="https://schema.org/SiteNavigationElement">
				<?php echo $pagination; ?>
			</nav>

		<?php elseif ( 'load_more' == $settings['post_pagination'] ) : ?>
			<nav class="envision-blocks-load-more" itemscope itemtype="https://schema.org/SiteNavigationElement">
				<button class="envision-blocks-btn envision-blocks-btn--lg envision-blocks-btn--color envision-blocks-load-more__button">
					<span><?php echo esc_html( $settings['pagination_load_more_text'] ); ?></span>
				</button>
			</nav>
			<?php
		endif;
	}

	/**
	 * Get post categories.
	 */
	private function get_post_categories() {
		$options = array();

		if ( ! empty( 'category' ) ) {
			// Get categories for post type.
			$terms = get_terms(
				array(
					'taxonomy'   => 'category',
					'hide_empty' => false,
				)
			);

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( isset( $term ) ) {
						if ( isset( $term->slug ) && isset( $term->name ) ) {
							$options[ $term->slug ] = $term->name;
						}
					}
				}
			}
		}

		return $options;
	}
}
