<?php
/**
 * Ajax Class.
 *
 * @package EnvisionBlocks
 */

namespace EnvisionBlocks\Widgets\Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Ajax {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->load_dependencies();

		// Load more
		add_action( 'wp_ajax_nopriv_envision_blocks_widget_load_more', array( $this, 'ajax_load_more' ) );
		add_action( 'wp_ajax_envision_blocks_widget_load_more', array( $this, 'ajax_load_more' ) );

		// Filter
		add_action( 'wp_ajax_nopriv_envision_blocks_widget_post_filter', array( $this, 'ajax_post_filter' ) );
		add_action( 'wp_ajax_envision_blocks_widget_post_filter', array( $this, 'ajax_post_filter' ) );
	}

	/**
	 * Ajax post filter
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function ajax_post_filter() {
		check_ajax_referer( 'envision_blocks_ajax_nonce', 'security' );
		$settings = array();

		if ( ! empty( $_POST['data'] ) ) {
			$settings             = $this->validate_field( $_POST['data']['settings'] );
			$settings['category'] = $this->validate_field( $_POST['data']['category'] );
		}

		$args = array(
			'post_status' => array( 'publish' ),
		);

		// Post type
		if ( isset( $settings['post_type'] ) ) {
			$args['post_type'] = $settings['post_type'];
		}

		// Posts per page
		if ( isset( $settings['posts_per_page'] ) ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		// Category
		switch ( $settings['widget_type'] ) {

			case 'envision-blocks-posts':
				if ( ! empty( $settings['category'] ) ) {
					if ( '*' !== $settings['category'] ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => $settings['category'],
						);
					}
				}
				break;

			case 'envision-blocks-portfolio':
				if ( ! empty( $settings['category'] ) ) {
					if ( '*' !== $settings['category'] ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'portfolio_category',
							'field'    => 'slug',
							'terms'    => $settings['category'],
						);
					}
				}
				break;

			default:
				break;
		}

		$query = new \WP_Query( $args );

		// Render post data
		switch ( $settings['widget_type'] ) {

			case 'envision-blocks-posts':
				new Ajax_Posts( $settings, $query );
				break;

			case 'envision-blocks-portfolio':
				new Ajax_Portfolio_Posts( $settings, $query );
				break;
		}

		wp_reset_postdata();

		die();
	}

	/**
	 * Ajax Response
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function ajax_load_more() {
		check_ajax_referer( 'envision_blocks_ajax_nonce', 'security' );
		$settings = array();

		if ( ! empty( $_POST['data'] ) ) {
			$settings         = $this->validate_field( $_POST['data']['settings'] );
			$settings['page'] = absint( $_POST['data']['page'] );
		}

		$query = $this->get_query( $settings );

		// Render post data
		switch ( $settings['widget_type'] ) {

			case 'envision-blocks-posts':
				new Ajax_Posts( $settings, $query );
				break;

			case 'envision-blocks-portfolio':
				new Ajax_Portfolio_Posts( $settings, $query );
				break;
		}

		wp_reset_postdata();

		die();
	}


	/**
	 * Load the required dependencies for this class.
	 *
	 * @since    1.0.0
	 */
	public function load_dependencies() {
		require_once __DIR__ . '/ajax-posts.php';
		require_once __DIR__ . '/ajax-portfolio-posts.php';
	}


	/**
	 * Query args
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function get_query( $settings = array() ) {

		$args = array(
			'post_status' => array( 'publish' ),
		);

		// Post type
		if ( isset( $settings['post_type'] ) ) {
			$args['post_type'] = $settings['post_type'];
		}

		// Posts per page
		if ( isset( $settings['posts_per_page'] ) ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		// Category
		switch ( $settings['widget_type'] ) {

			case 'envision-blocks-posts':
				if ( ! empty( $settings['filter_item_list'] ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $settings['filter_item_list'],
					);
				}
				break;

			case 'envision-blocks-portfolio':
				if ( ! empty( $settings['filter_item_list'] ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'portfolio_category',
						'field'    => 'slug',
						'terms'    => $settings['filter_item_list'],
					);
				}
				break;

			default:
				break;
		}

		// Orderby
		if ( isset( $settings['orderby'] ) ) {
			$args['orderby'] = $settings['orderby'];
		}

		// Order
		if ( isset( $settings['order'] ) ) {
			$args['order'] = $settings['order'];
		}

		// Sticky Posts
		if ( 'yes' == $settings['ignore_sticky_posts'] ) {
			$args['ignore_sticky_posts'] = 1;
		}

		// Paged
		if ( ! empty( $settings['page'] ) ) {
			$args['paged'] = $settings['page'] + 1;
		}

		$query = new \WP_Query( $args );

		return $query;
	}


	/**
	 * Validate field
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function validate_field( $settings ) {

		if ( is_array( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( is_array( $val ) ) {
					$val = $this->validate_field( $val );
				} else {
					sanitize_text_field( $val );
				}
			}
		} elseif ( is_string( $settings ) ) {
			$settings = sanitize_text_field( $settings );
		} else {
			$settings = '';
		}

		return $settings;
	}
}

new Ajax();
