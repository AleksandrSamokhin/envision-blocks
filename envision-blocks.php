<?php

/**
 * Plugin Name: Envision Blocks
 * Description: A collection of creative and unique Elementor widgets and addons designed to enhance your website.
 * Plugin URI:  https://envision-blocks.deothemes.com/
 * Version:     1.3
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Author:      DeoThemes
 * Author URI:  https://deothemes.com/
 * Elementor tested up to: 5.0
 * Elementor Pro tested up to: 5.0
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: envision-blocks
 */
namespace EnvisionBlocks;

use EnvisionBlocks\Widgets;
use EnvisionBlocks\Utils;
use EnvisionBlocks\Modules\Template_Library;
use EnvisionBlocks\Modules\Woo_Helper;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Exit if accessed directly.
define( 'ENVISION_BLOCKS_PATH', plugin_dir_path( __FILE__ ) );
define( 'ENVISION_BLOCKS_URL', plugin_dir_url( __FILE__ ) );
define( 'ENVISION_BLOCKS_VERSION', '1.3' );
define( 'ENVISION_BLOCKS_ACF_PATH', plugin_dir_path( __FILE__ ) . '/includes/acf/' );
define( 'ENVISION_BLOCKS_ACF_URL', plugin_dir_url( __FILE__ ) . '/includes/acf/' );
if ( ! function_exists( 'envision_blocks_fs' ) ) {
	// Create a helper function for easy SDK access.
	function envision_blocks_fs() {
		global $envision_blocks_fs;
		if ( ! isset( $envision_blocks_fs ) ) {
			// Include Freemius SDK.
			require_once __DIR__ . '/freemius/start.php';
			$envision_blocks_fs = fs_dynamic_init(
				array(
					'id'                             => '15537',
					'slug'                           => 'envision-blocks',
					'premium_slug'                   => 'envision-blocks-pro',
					'type'                           => 'plugin',
					'public_key'                     => 'pk_7dd792d4eea726d0aac8448cc6619',
					'is_premium'                     => false,
					'premium_suffix'                 => 'Pro',
					'has_addons'                     => false,
					'has_paid_plans'                 => true,
					'has_affiliation'                => 'selected',
					'bundle_id'                      => '15783',
					'bundle_public_key'              => 'pk_72b76e26d60b8bd21f1e6d39ede9e',
					'bundle_license_auto_activation' => true,
					'menu'                           => array(
						'slug'    => 'envision-blocks',
						'contact' => false,
						'pricing' => false,
						'support' => false,
					),
					'is_live'                        => true,
				)
			);
		}
		return $envision_blocks_fs;
	}

	// Init Freemius.
	envision_blocks_fs();
	// Signal that SDK was initiated.
	do_action( 'envision_blocks_fs_loaded' );
}
/**
 * Plugin admin icon
 */
function envision_blocks_fs_custom_icon() {
	return __DIR__ . '/admin/assets/img/plugin_icon.png';
}

envision_blocks_fs()->add_filter( 'plugin_icon', __NAMESPACE__ . '\\envision_blocks_fs_custom_icon' );
/**
 * Main EnvisionBlocks Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class EnvisionBlocks {
	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = ENVISION_BLOCKS_VERSION;

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var EnvisionBlocks The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return EnvisionBlocks An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		$this->add_actions();
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'envision-blocks' );
	}

	/**
	 * Load WooCommerce styles and default fonts
	 */
	public function add_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 998 );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
		}
		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
		}
		// Include Files
		$this->includes();
		// Add body classes
		add_filter( 'body_class', array( $this, 'add_body_classes' ) );
		if ( 1 !== get_option( '_envision_blocks_elementor_defaults', 0 ) ) {
			add_option( '_envision_blocks_elementor_defaults', 0 );
		}
		// Update Elementor defaults
		$this->update_elementor_defaults();
		// Add custom motion animations
		// add_filter( 'elementor/controls/animations/additional_animations', [ $this, 'add_custom_animations' ] );
		// Add the widget category
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ), 9 );
		// Register assets
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
		// Register editor assets
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_editor_styles' ) );
		// Enqueue admin assets
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		// Register widgets
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		// Add extra controls
		add_action(
			'elementor/element/after_section_end',
			array( $this, 'add_extra_controls' ),
			10,
			3
		);
	}

	/**
	 * Add extra controls for the exisitng Elementor widgets
	 *
	 * @since 1.2
	 *
	 * @access public
	 */
	public function add_extra_controls( $element, $section_id ) {
		// Text editor
		if ( 'text-editor' === $element->get_name() && 'section_drop_cap' === $section_id ) {
			$element->start_controls_section(
				'envision_blocks_text_editor_extra',
				array(
					'tab'   => Controls_Manager::TAB_STYLE,
					'label' => esc_html__( 'Extra Options', 'envision-blocks' ),
				)
			);
			$element->start_controls_tabs( 'envision_blocks_text_editor_colors' );
			$element->start_controls_tab(
				'envision_blocks_text_editor_link_colors_normal',
				array(
					'label' => esc_html__( 'Normal', 'envision-blocks' ),
				)
			);
			$element->add_control(
				'envision_blocks_text_editor_link_color',
				array(
					'label'     => esc_html__( 'Link Color', 'envision-blocks' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} a' => 'color: {{VALUE}};',
					),
				)
			);
			$element->end_controls_tab();
			$element->start_controls_tab(
				'envision_blocks_text_editor_link_hover',
				array(
					'label' => esc_html__( 'Hover', 'envision-blocks' ),
				)
			);
			$element->add_control(
				'envision_blocks_text_editor_link_color_hover',
				array(
					'label'     => esc_html__( 'Link Color', 'envision-blocks' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} a:hover, {{WRAPPER}} a:focus' => 'color: {{VALUE}};',
					),
				)
			);
			$element->end_controls_tab();
			$element->end_controls_tabs();
			$element->end_controls_section();
		}
		// Button
		if ( 'button' === $element->get_name() && 'section_style' === $section_id ) {
			$element->start_controls_section(
				'envision_blocks_button_extra',
				array(
					'tab'   => Controls_Manager::TAB_STYLE,
					'label' => esc_html__( 'Extra Options', 'envision-blocks' ),
				)
			);
			$element->add_control(
				'envision_blocks_button_outline',
				array(
					'label'     => esc_html__( 'Outline', 'envision-blocks' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-button' => 'outline-width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$element->end_controls_section();
		}
	}

	/**
	 * Add Elementor Widget Categories
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'envision-blocks-widgets',
			array(
				'title' => esc_html__( 'Envision Blocks', 'envision-blocks' ),
				'icon'  => 'fa fa-plug',
			)
		);
		if ( class_exists( '\\WooCommerce' ) ) {
			$elements_manager->add_category(
				'envision-blocks-woocommerce-widgets',
				array(
					'title' => esc_html__( 'Envision Blocks WooCommerce', 'envision-blocks' ),
					'icon'  => 'fa fa-plug',
				)
			);
		}
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		if ( $this->is_plugin_installed( 'elementor/elementor.php' ) ) {
			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=elementor/elementor.php&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_elementor/elementor.php' );
			// translators: %1$s and %2$s are HTML strong tags.
			$message     = sprintf( __( '%1$sEnvision Blocks%2$s requires %1$sElementor%2$s plugin to be active. Please activate Elementor to continue.', 'envision-blocks' ), '<strong>', '</strong>' );
			$button_text = __( 'Activate Elementor', 'envision-blocks' );
		} else {
			$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
			// translators: %1$s and %2$s are HTML strong tags.
			$message     = sprintf( __( '%1$sEnvision Blocks%2$s requires %1$sElementor%2$s plugin to be installed and activated. Please install Elementor to continue.', 'envision-blocks' ), '<strong>', '</strong>' );
			$button_text = __( 'Install Elementor', 'envision-blocks' );
		}
		$button = '<p><a href="' . esc_url( $activation_url ) . '" class="button-primary">' . esc_html( $button_text ) . '</a></p>';
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', wp_kses_post( $message ), wp_kses_post( $button ) );
	}

	/**
	 * Check if a plugin is installed
	 *
	 * @since 1.0.0
	 */
	public function is_plugin_installed( $basename ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		$installed_plugins = get_plugins();
		return isset( $installed_plugins[ $basename ] );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'envision-blocks' ),
			'<strong>' . esc_html__( 'Envision Blocks', 'envision-blocks' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'envision-blocks' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'envision-blocks' ),
			'<strong>' . esc_html__( 'Envision Blocks', 'envision-blocks' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'envision-blocks' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_widgets() {
		$modules = Utils::get_available_modules( Utils::get_registered_modules() );
		foreach ( $modules as $data ) {
			$module     = $data[0];
			$class_name = str_replace( '-', '_', ucwords( $module, '-' ) );
			$class      = '\\EnvisionBlocks\\Widgets\\' . $class_name;
			include __DIR__ . '/widgets/' . $module . '.php';
			if ( class_exists( $class ) ) {
				$instance = new $class();
				Plugin::instance()->widgets_manager->register( $instance );
			}
		}
	}

	/**
	 * Load all the necessary files
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function includes() {
		require_once __DIR__ . '/admin/class-admin-options.php';
		require_once __DIR__ . '/includes/class-utils.php';
		require_once __DIR__ . '/widgets/traits/posts-trait.php';
		require_once __DIR__ . '/widgets/traits/slider-trait.php';
	}

	/**
	 * Enqueue custom CSS styles for widgets
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'envision-blocks-styles',
			plugins_url( '/assets/css/style.min.css', __FILE__ ),
			array(),
			ENVISION_BLOCKS_VERSION
		);
		wp_style_add_data( 'envision-blocks-styles', 'rtl', 'replace' );
		$default_fonts = Utils::get_option( 'default_fonts', 'envision_blocks_settings_settings' );
		if ( 0 === $default_fonts || '1' === $default_fonts ) {
			$fonts = $this->enqueue_default_fonts();
			wp_add_inline_style( 'envision-blocks-styles', $fonts );
		}
	}


	/**
	 * Enqueue front-end styles
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_frontend_styles() {
		if ( ! is_admin() ) {
			if ( 'on' === get_option( 'envision-blocks-widget-dark-mode', 'on' ) ) {
				wp_enqueue_style(
					'envision-blocks-dark-mode',
					plugins_url( 'assets/css/pro/dark-mode.min.css', __FILE__ ),
					array( 'envision-blocks-styles' ),
					ENVISION_BLOCKS_VERSION
				);
				wp_style_add_data( 'envision-blocks-dark-mode', 'rtl', 'replace' );
				wp_enqueue_script(
					'envision-blocks-dark-mode',
					plugins_url( 'assets/js/view/pro/dark-mode.min.js', __FILE__ ),
					array(),
					ENVISION_BLOCKS_VERSION,
					false
				);
			}
			if ( 'on' === get_option( 'envision-blocks-widget-offcanvas', 'on' ) || 'on' === get_option( 'envision-blocks-menu-icon', 'on' ) ) {
				wp_enqueue_style(
					'envision-blocks-offcanvas-styles',
					plugins_url( 'assets/css/pro/offcanvas.min.css', __FILE__ ),
					array( 'envision-blocks-styles' ),
					ENVISION_BLOCKS_VERSION
				);
				wp_style_add_data( 'envision-blocks-offcanvas-styles', 'rtl', 'replace' );
			}
			if ( 'on' === get_option( 'envision-blocks-widget-nav-menu', 'on' ) ) {
				wp_enqueue_style(
					'envision-blocks-nav-menu-styles',
					plugins_url( 'assets/css/pro/nav-menu.min.css', __FILE__ ),
					array( 'envision-blocks-styles' ),
					ENVISION_BLOCKS_VERSION
				);
				wp_style_add_data( 'envision-blocks-nav-menu-styles', 'rtl', 'replace' );
			}
			if ( 'on' === get_option( 'envision-blocks-widget-menu-icon', 'on' ) ) {
				wp_enqueue_style(
					'envision-blocks-menu-icon-styles',
					plugins_url( 'assets/css/pro/menu-icon.min.css', __FILE__ ),
					array( 'envision-blocks-styles' ),
					ENVISION_BLOCKS_VERSION
				);
				wp_style_add_data( 'envision-blocks-menu-icon-styles', 'rtl', 'replace' );
			}
		}
	}

	/**
	 * Enqueue default fonts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_default_fonts() {
		return "\r\n\t\t@font-face{\r\n\t\t\tfont-family: 'Inter';\r\n\t\t\tfont-weight: 400;\r\n\t\t\tfont-style: normal;\r\n\t\t\tfont-stretch: normal;\r\n\t\t\tfont-display: swap;\r\n\t\t\tsrc: url('" . ENVISION_BLOCKS_URL . 'assets/fonts/Inter/Inter-Regular.ttf' . "') format('truetype');\r\n\t\t}\r\n\r\n\t\t@font-face{\r\n\t\t\tfont-family: 'Urbanist';\r\n\t\t\tfont-weight: 400;\r\n\t\t\tfont-style: normal;\r\n\t\t\tfont-stretch: normal;\r\n\t\t\tfont-display: swap;\r\n\t\t\tsrc: url('" . ENVISION_BLOCKS_URL . 'assets/fonts/Urbanist/Urbanist-Regular.ttf' . "') format('truetype');\r\n\t\t}\r\n\r\n\t\t@font-face{\r\n\t\t\tfont-family: 'Urbanist';\r\n\t\t\tfont-weight: 500;\r\n\t\t\tfont-style: normal;\r\n\t\t\tfont-stretch: normal;\r\n\t\t\tfont-display: swap;\r\n\t\t\tsrc: url('" . ENVISION_BLOCKS_URL . 'assets/fonts/Urbanist/Urbanist-Medium.ttf' . "') format('truetype');\r\n\t\t}\r\n\r\n\t\t@font-face{\r\n\t\t\tfont-family: 'Urbanist';\r\n\t\t\tfont-weight: 700;\r\n\t\t\tfont-style: normal;\r\n\t\t\tfont-stretch: normal;\r\n\t\t\tfont-display: swap;\r\n\t\t\tsrc: url('" . ENVISION_BLOCKS_URL . 'assets/fonts/Urbanist/Urbanist-Bold.ttf' . "') format('truetype');\r\n\t\t}";
	}

	/**
	 * Enqueue custom CSS for editor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_editor_styles() {
		wp_enqueue_style( 'envision-blocks-editor-styles', plugins_url( '/assets/css/editor-style.min.css', __FILE__ ), array(), ENVISION_BLOCKS_VERSION );
		wp_style_add_data( 'envision-blocks-editor-styles', 'rtl', 'replace' );
	}

	/**
	 * Register custom JS scripts for widgets
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_scripts() {
		wp_enqueue_script(
			'envision-blocks-scripts',
			plugins_url( '/assets/js/view/scripts.min.js', __FILE__ ),
			array( 'jquery' ),
			ENVISION_BLOCKS_VERSION,
			true
		);
		wp_register_script(
			'confetti',
			plugins_url( '/assets/js/lib/confetti.min.js', __FILE__ ),
			array( 'jquery' ),
			true
		);
		wp_register_script(
			'chart-js',
			plugins_url( '/assets/js/lib/chart.umd.js', __FILE__ ),
			array( 'jquery' ),
			true
		);
		wp_register_script(
			'jquery.parallax-scroll',
			plugins_url( '/assets/js/lib/jquery.parallax-scroll.js', __FILE__ ),
			array( 'jquery' ),
			true
		);
		wp_register_script(
			'fslightbox',
			plugins_url( '/assets/js/lib/fslightbox.min.js', __FILE__ ),
			array(),
			true
		);
		wp_register_script(
			'before-after',
			plugins_url( '/assets/js/lib/before-after.min.js', __FILE__ ),
			array( 'jquery' ),
			true
		);
		wp_register_script(
			'isotope',
			plugins_url( '/assets/js/lib/isotope.pkgd.min.js', __FILE__ ),
			array( 'jquery' ),
			true
		);
		wp_register_script(
			'lazyload',
			plugins_url( '/assets/js/lib/lazyload.min.js', __FILE__ ),
			array(),
			true
		);
		wp_localize_script(
			'envision-blocks-scripts',
			'envision_blocks_elementor_data',
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'envision_blocks_ajax_nonce' ),
				'plugin_url' => esc_url( ENVISION_BLOCKS_URL ),
				'isRTL'      => is_rtl(),
			)
		);
	}

	/**
	 * Enqueue admin CSS and JS
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_admin_scripts() {
		$screen = get_current_screen();
		wp_enqueue_style(
			'envision-blocks-admin-styles',
			plugins_url( '/admin/assets/css/admin-styles.min.css', __FILE__ ),
			array(),
			ENVISION_BLOCKS_VERSION
		);
		if ( 'toplevel_page_envision-blocks' === $screen->id ) {
			wp_enqueue_style(
				'envision-blocks-admin-page-styles',
				plugins_url( '/admin/assets/css/admin-page-styles.min.css', __FILE__ ),
				array(),
				ENVISION_BLOCKS_VERSION
			);
			wp_enqueue_script(
				'envision-blocks-admin-page-scripts',
				plugins_url( '/admin/assets/js/admin-scripts.js', __FILE__ ),
				array(),
				ENVISION_BLOCKS_VERSION
			);
		}
	}

	/**
	 * Add body classes
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function add_body_classes( $classes ) {
		$classes[] = 'envision-blocks';
		return $classes;
	}

	/**
	 * Update Elementor defaults
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function update_elementor_defaults() {
		if ( 1 !== get_option( '_envision_blocks_elementor_defaults' ) ) {
			update_option(
				'elementor_cpt_support',
				array(
					'post',
					'page',
					'product',
					'eb_library',
					'portfolio',
				)
			);
			update_option( '_elementor_editor_upgrade_notice_dismissed', time() + '9999999999' );
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			update_option( 'elementor_unfiltered_files_upload', 1 );
			update_option( '_envision_blocks_elementor_defaults', 1 );
		}
	}

	/**
	 * Add custom animations
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function add_custom_animations() {
		return array(
			'Fading Smooth' => array(
				'fadeInDownSmooth'  => esc_html__( 'Fade In Down Smooth', 'envision-blocks' ),
				'fadeInLeftSmooth'  => esc_html__( 'Fade In Left Smooth', 'envision-blocks' ),
				'fadeInRightSmooth' => esc_html__( 'Fade In Right Smooth', 'envision-blocks' ),
				'fadeInUpSmooth'    => esc_html__( 'Fade In Up Smooth', 'envision-blocks' ),
			),
		);
	}
}

/**
 * Plugin activation.
 */
function envision_blocks_activate() {
	// Disable wishlist in the loop
	update_option( 'yith_wcwl_show_on_loop', 'no' );
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\envision_blocks_activate' );
EnvisionBlocks::instance();
