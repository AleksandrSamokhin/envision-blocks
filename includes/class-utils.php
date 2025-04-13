<?php
/**
 * EnvisionBlocks Utils class.
 *
 * @link       https://deothemes.com
 * @since      1.0.0
 *
 * @package    EnvisionBlocks
 * @subpackage EnvisionBlocks/includes/
 */

namespace EnvisionBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * EnvisionBlocks Utils.
 */
class Utils {

	public static $plugin_url = 'https://envision-blocks.deothemes.com';

	/**
	* A list of safe tage for `validate_html_tag` method.
	*/
	const ALLOWED_HTML_WRAPPER_TAGS = array( 'article', 'aside', 'div', 'footer', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'main', 'nav', 'p', 'section', 'span' );

	/**
	 * Get registered modules
	 */
	public static function get_registered_modules() {
		return array(
			'Animated Text'     => array( 'animated-text', self::$plugin_url . '/animated-text', '', '' ),
			'Before & After'    => array( 'before-after', self::$plugin_url . '/before-after', '', '' ),
			'Contact Form 7'    => array( 'contact-form-7', self::$plugin_url . '/contact-form-7', '', '' ),
			'Graph'             => array( 'graph', self::$plugin_url . '/graph', '', '' ),
			'Google Maps'       => array( 'google-maps', self::$plugin_url . '/google-maps', '', '' ),
			'Hero Slider'       => array( 'hero-slider', self::$plugin_url . '/hero-slider', '', '' ),
			'Hotspot'           => array( 'hotspot', self::$plugin_url . '/hotspot', '', '' ),
			'Share Buttons'     => array( 'share-buttons', self::$plugin_url . '/share-buttons', '', '' ),
			'Testimonials Feed' => array( 'testimonials-feed', self::$plugin_url . '/testimonials-feed', '', '' ),
			'Marquee'           => array( 'marquee', self::$plugin_url . '/marquee', '', '' ),
			'Parallax'          => array( 'parallax', self::$plugin_url . '/parallax', '', '' ),
			'Posts'             => array( 'posts', self::$plugin_url . '/posts', '', '' ),
			'Vertical Tabs'     => array( 'vertical-tabs', self::$plugin_url . '/vertical-tabs', '', '' ),
			'Video Lightbox'    => array( 'video-lightbox', self::$plugin_url . '/video-lightbox', '', '' ),
			'Mailchimp Form'    => array( 'mailchimp-form', self::$plugin_url . '/mailchimp-form', '', '' ),
			'Icon Box'          => array( 'icon-box', self::$plugin_url . '/icon-box', '', '' ),
		);
	}

	/**
	 * Get registered pro modules
	 */
	public static function get_registered_pro_modules() {
		return array(
			'Creative Cursor'         => array( 'creative-cursor', self::$plugin_url . '/creative-cursor', '', 'pro' ),
			'Creative Cards Carousel' => array( 'creative-cards-carousel', self::$plugin_url . '/creative-cards-carousel', '', 'pro' ),
			'Dark Mode'               => array( 'dark-mode', self::$plugin_url . '/dark-mode', '', 'pro' ),
			'Expanding Cards'         => array( 'expanding-cards', self::$plugin_url . '/expanding-cards', '', 'pro' ),
			'Interactive Quiz'        => array( 'interactive-quiz', self::$plugin_url . '/interactive-quiz', '', 'pro' ),
			'Image Hover Menu'        => array( 'image-hover-menu', self::$plugin_url . '/image-hover-menu', '', 'pro' ),
			'Menu Icon'               => array( 'menu-icon', self::$plugin_url . '/header-builder', '', 'pro' ),
			'Menu Photo Reveal'       => array( 'menu-photo-reveal', self::$plugin_url . '/menu-photo-reveal', '', 'pro' ),
			'Nav Menu'                => array( 'nav-menu', self::$plugin_url . '/header-builder', '', 'pro' ),
			'Site Logo'               => array( 'site-logo', self::$plugin_url . '/header-builder', '', 'pro' ),
			'Sticky Scroll Section'   => array( 'sticky-scroll-section', self::$plugin_url . '/sticky-scroll-section', '', 'pro' ),
			'Offcanvas'               => array( 'offcanvas', self::$plugin_url . '/offcanvas', '', 'pro' ),
			'Parallax Carousel'       => array( 'parallax-carousel', self::$plugin_url . '/parallax-carousel', '', 'pro' ),
			'Portfolio'               => array( 'portfolio', self::$plugin_url . '/portfolio', '', 'pro' ),
			'Table'                   => array( 'table', self::$plugin_url . '/table', '', 'pro' ),
			'Testimonials Slider'     => array( 'testimonials-slider', self::$plugin_url . '/testimonials-slider', '', 'pro' ),
			'Post Title'              => array( 'post-title', self::$plugin_url . '/portfolio/sneakers/', '', 'pro' ),
			'Post Navigation'         => array( 'post-navigation', self::$plugin_url . '/portfolio/sneakers/', '', 'pro' ),
		);
	}

	/**
	** Get Enabled Modules
	*/
	public static function get_available_modules( $modules ) {
		foreach ( $modules as $title => $data ) {
			$slug = $data[0];
			if ( 'on' !== get_option( 'envision-blocks-widget-' . $slug, 'on' ) ) {
				unset( $modules[ $title ] );
			}
		}

		return $modules;
	}

	/**
	** Get Woo Builder Modules
	*/
	public static function get_woocommerce_builder_modules() {
		return array(
			'Woo Products'                       => array( 'woo-products', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Categories'             => array( 'woo-product-categories', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Image'                  => array( 'woo-product-image', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Title'                  => array( 'woo-product-title', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Price'                  => array( 'woo-product-price', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Add To Cart'            => array( 'woo-product-add-to-cart', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Short Description'      => array( 'woo-product-short-description', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Tabs'                   => array( 'woo-product-tabs', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Related'                => array( 'woo-product-related', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Rating'                 => array( 'woo-product-rating', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Stock'                  => array( 'woo-product-stock', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Additional Information' => array( 'woo-product-additional-information', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Meta'                   => array( 'woo-product-meta', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Upsell'                 => array( 'woo-product-upsell', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Notices'                => array( 'woo-product-notices', self::$plugin_url . '#pricing', '', 'pro' ),
			'Woo Product Breadcrumbs'            => array( 'woo-product-breadcrumbs', self::$plugin_url . '#pricing', '', 'pro' ),
		);
	}

	/**
	* Get the value of a settings field
	*
	* @param string  $option  settings field name
	* @param string  $section the section name this field belongs to
	* @param string  $default default text if it's not found
	* @return string
	*/
	public static function get_option( $option, $section, $default = 0 ) {
		$options = get_option( $section );

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		return $default;
	}


	/**
	** Get Available Custom Post Types or Taxonomies
	*/
	public static function get_custom_types_of( $query, $exclude_defaults = true ) {
		// Taxonomies
		if ( 'tax' === $query ) {
			$custom_types = get_taxonomies( array( 'show_in_nav_menus' => true ), 'objects' );
		
			// Post Types
		} else {
			$custom_types = get_post_types( array( 'show_in_nav_menus' => true ), 'objects' );
		}

		$custom_type_list = array();

		unset( $custom_types['e-landing-page'] );

		foreach ( $custom_types as $key => $value ) {

			if ( $exclude_defaults ) {
				if ( 'post' !== $key && 'page' !== $key && 'category' !== $key && 'post_tag' !== $key ) {
					$custom_type_list[ $key ] = $value->label;
				}
			} else {
				$custom_type_list[ $key ] = $value->label;
			}
		}

		return $custom_type_list;
	}


	/**
	* Register Google Maps script.
	*
	*
	* @since 1.0.0
	* @access public
	*/
	public static function register_google_maps_script() {
	
		$google_maps_api_url = 'https://maps.googleapis.com';
		$language            = '';
		$settings            = self::get_integrations_options();

		if ( isset( $settings['language'] ) && '' !== $settings['language'] ) {
			$language = 'language=' . $settings['language'];

			if ( 'zh-CN' === $settings['language'] || 'zh-TW' === $settings['language'] ) {
				$google_maps_api_url = 'http://maps.googleapis.cn';
			}
		}

		if ( isset( $settings['google_maps_key'] ) && '' !== $settings['google_maps_key'] ) {
			$language        = '&' . $language;
			$google_maps_url = $google_maps_api_url . '/maps/api/js?key=' . esc_attr( $settings['google_maps_key'] ) . $language . '&loading=async';
		} else {
			$google_maps_url = $google_maps_api_url . '/maps/api/js?' . $language . '&loading=async';
		}

		wp_register_script( 'envision-blocks-google-maps-api', $google_maps_url, array( 'jquery' ), true );
	}

	/**
	 * Provide Integrations settings array().
	 *
	 * @param string $name slug.
	 * @return array()
	 * @since 1.0.0
	 */
	public static function get_integrations_options( $name = '' ) {

		$defaults = array(
			'google_maps_key' => '',
			'language'        => '',
		);

		$integrations = get_option( 'envision_blocks_integrations_settings' );
		$integrations = wp_parse_args( $integrations, $defaults );

		if ( '' !== $name && isset( $integrations[ $name ] ) && '' !== $integrations[ $name ] ) {
			return $integrations[ $name ];
		} else {
			return $integrations;
		}
	}


	/**
	* Validate an HTML tag against a safe allowed list.
	*
	* @since 1.0.0
	* @param string $tag specifies the HTML Tag.
	* @access public
	*/
	public static function validate_html_tag( $tag ) {

		// Check if Elementor method exists, else we will run custom validation code.
		if ( method_exists( '\Elementor\Utils', 'validate_html_tag' ) ) {
			return \Elementor\Utils::validate_html_tag( $tag );
		} else {
			return in_array( strtolower( $tag ), self::ALLOWED_HTML_WRAPPER_TAGS, true ) ? $tag : 'div';
		}
	}

	/**
	* Check if page built with Elementor
	*
	* @since  1.0.0
	*/
	public static function is_elementor_page() {
		$elementor_page = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );

		if ( is_search() || is_archive() ) {
			return false;
		}       

		if ( (bool) $elementor_page ) {
			return true;
		} else {
			return false;
		}   
	}


	/**
	* Function that does escaping of specific html.
	* It uses wp_kses function with predefined attributes array.
	*
	* @see wp_kses()
	*
	* @param string $type - type of html element
	* @param string $content - string to escape
	*
	* @return string escaped output
	*/
	public static function sanitize_svg( $type, $content ) {
		switch ( $type ) {
			case 'svg':
				$atts = apply_filters(
					'envision_blocks_wp_kses_svg_atts',
					array(
						'svg'     => array(
							'xmlns'             => true,
							'version'           => true,
							'id'                => true,
							'class'             => true,
							'x'                 => true,
							'y'                 => true,
							'aria-hidden'       => true,
							'aria-labelledby'   => true,
							'role'              => true,
							'width'             => true,
							'height'            => true,
							'viewbox'           => true,
							'enable-background' => true,
							'focusable'         => true,
							'data-prefix'       => true,
							'data-icon'         => true,
						),
						'g'       => array(
							'stroke'       => true,
							'stroke-width' => true,
							'fill'         => true,
							'fill-opacity' => true,
						),
						'rect'    => array(
							'x'      => true,
							'y'      => true,
							'width'  => true,
							'height' => true,
						),
						'title'   => array(
							'title' => true,
						),
						'path'    => array(
							'd'            => true,
							'stroke'       => true,
							'stroke-width' => true,
							'fill'         => true,
							'fill-opacity' => true,
						),
						'polygon' => array(
							'points' => true,
						),
					)
				);
				break;

			default:
				return apply_filters( 'envision_blocks_wp_kses_custom', $content, $type );
				break;
		}

		return wp_kses( $content, $atts );
	}


	/**
	 * Get page ID by title
	 */
	public static function get_page_by_title( $page_title, $post_type = 'page' ) {
		$posts = get_posts(
			array(
				'post_type'              => $post_type,
				'title'                  => $page_title,
				'post_status'            => 'all',
				'numberposts'            => 1,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,           
				'orderby'                => 'post_date ID',
				'order'                  => 'ASC',
			)
		);

		if ( ! empty( $posts ) ) {
			$post = $posts[0];
		} else {
			$post = null;
		}

		return $post;
	}
}
