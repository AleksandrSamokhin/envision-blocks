<?php
/**
 * Ajax Blog Posts Class.
 *
 * @package EnvisionBlocks
 */

namespace EnvisionBlocks\Widgets\Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Ajax_Posts {

	use \EnvisionBlocks\Traits\Posts_Trait;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct( $settings, $query ) {
		$this->init( $settings, $query );
	}


	/**
	 * Initialize all actions
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init( $settings, $query ) {

		// Render layout
		if ( isset( $settings['posts_layout'] ) ) {

			switch ( $settings['posts_layout'] ) {

				case 'grid':
					$this->render_posts( $settings, $query, 'grid', true );
					break;

				// case 'minimal':
				// $this->render_posts( $settings, $query, 'minimal', true );
				// break;

				case 'slider':
					break;

				default:
					$this->render_posts( $settings, $query, 'grid' );
					break;
			}
		}
	}
}
