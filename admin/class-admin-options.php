<?php

/**
 * Admin Options class.
 *
 * @package EnvisionBlocks
 */

namespace EnvisionBlocks\Admin;

use EnvisionBlocks\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Exit if accessed directly.
class Admin_Options {

	/**
	 * [$instance]
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Google Map Language List
	 *
	 * @var google_map_languages
	 */
	private static $google_map_languages = null;

	/**
	 * Initializes a singleton instance
	 *
	 * @return [Admin_Options]
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_submenu' ), 99 );
		add_action( 'admin_head', array( $this, 'redirect_upgrade_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_notices', array( $this, 'save_notice' ) );
	}

	public function add_admin_menu() {
		add_menu_page(
			esc_html__( 'Envision Blocks', 'envision-blocks' ),
			esc_html__( 'Envision Blocks', 'envision-blocks' ),
			'manage_options',
			'envision-blocks',
			array( $this, 'settings_page' ),
			'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KCTxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNOS43ODMzNSAwLjkwODA3MkMxMC44Nzc0IDAuODc5Nzg2IDEyLjQzMiAxLjk5MDY0IDEzLjIyNjcgMi40MDkxNEMxNC4yNDE3IDIuOTQzNjggMTUuMjgxNyAzLjM4OTE4IDE2LjI0NTcgMy45NTU1NEMxNi43MzU0IDQuMjQ0ODIgMTcuNDM1IDQuMzgyMzkgMTcuNzA4IDQuODY1MThDMTcuOTA1NyA1LjIxNDg5IDE3LjY5NTcgNS43NjUxOCAxNy44MDI0IDYuMjI5NjRDMTcuOTg5NCA3LjA0NDc5IDE3Ljg0OTcgOC4xOTE5NiAxNy44NDk3IDkuMDk1MThDMTcuODQ5NyA5LjczMDk2IDE3LjkzMjcgMTAuNTQxNiAxNy44MDI0IDExLjA5NjRWMTMuOTE2NkMxNy44MzM2IDE0LjE5MDEgMTcuODE3NiAxNC40NjY4IDE3Ljc1NSAxNC43MzUzQzE3LjQ0NzcgMTUuNDIyOCAxNi40NTUgMTUuNjY3NCAxNS43NzQgMTYuMDA4OEMxNC44MjY0IDE2LjQ4MzIgMTMuODA3NCAxNi45NzY2IDEyLjg5NjcgMTcuNTA5OUwxMi4xNDIgMTcuODI4NEMxMS41MzI3IDE4LjE4NDUgMTAuNzYyIDE4LjcwNjkgOS45NzIzNSAxOC44MjlDOS41NzIzNSAxOC44OTA3IDkuMTI0MzUgMTguNTU5MyA4Ljg4NzM1IDE4LjQxOThDOC4wODkzNSAxNy45NDk5IDcuMjE3MDIgMTcuNjA2IDYuNDM0NjkgMTcuMTQ2M0M1LjUwMTM1IDE2LjU5OCA0LjQ2ODAyIDE2LjIyNTQgMy41NTczNSAxNS42OTA5QzMuMTY0NjkgMTUuNDYwNCAyLjY0MDAyIDE1LjM1ODIgMi4zNzgwMiAxNS4wMDg1QzEuODc2MzUgMTQuMzM5IDIuMzExMzUgMTMuNDk3OCAyLjA5NTAyIDEyLjU1MjVDMS45ODAzNSAxMi4wNTIzIDIuMDkyNjkgMTEuMzg2IDIuMDk1MDIgMTAuODY5NVY3LjI3NTg5QzIuMDk1MDIgNi42MDMxNCAxLjkyODM1IDUuNTkzNTQgMi4xNDIzNSA1LjA5Mjc1QzIuNDU1MzUgNC4zNjExOCAzLjY0NTAyIDQuMDQwMzkgNC4zNTkzNSAzLjY4MjY0QzUuNTk1MDIgMy4wNjM4OSA2LjkwNDY5IDIuNDE5NzUgOC4wODU2OSAxLjcyNjc1QzguNjIxMDIgMS40MTIzOSA5LjMwMjY5IDEuMjgzNSA5Ljc4MzM1IDAuOTA4MDcyWk0xMC4wNjY3IDIuODY0MjlDOS42MDAwMiAzLjE5Mjc5IDguODY5MzUgMy4zOTIzOSA4LjMyMTM1IDMuNjM1NzFDNy4zOTAwMiA0LjA1MzU3IDYuNDQ1NjkgNC42MzIxNCA1LjUzODM1IDUuMDkyNDNDNS4xMzUwMiA1LjI5NzUgMy45ODIwMiA1LjY3NDU0IDMuODQwMzUgNi4wNDc3MkMzLjUyNTM1IDYuMzg2NSAzLjcwMzY5IDcuNTk1MDcgMy42OTg2OSA4LjE4NTU0QzMuNjkzMDIgOC44OTc1IDMuNjUwMDIgOS43MjYxNCAzLjY1MTY5IDEwLjUwNTNMMy43MDAwMiAxMS41NTE1QzMuNTg2MzcgMTIuMjkxOCAzLjYwMjM3IDEzLjA0NTIgMy43NDczNSAxMy43ODAzQzQuNTQ3MzUgMTQuMjY1NCA1LjQ3NDM1IDE0LjcwOCA2LjI5NDM1IDE1LjE5MDRDNi43NzYzNSAxNS40NzQzIDcuMzIxMDIgMTUuNTkgNy44MDM2OSAxNS44NzI4QzguMzM3MDIgMTYuMTg0NiA5LjAxMzM1IDE2LjY2NDggOS42NDMzNSAxNi44MjgxQzkuOTk0NjkgMTYuOTE5MSAxMC4zMzY3IDE2LjY1MDcgMTAuNTM5NyAxNi41NTUyQzExLjIyNCAxNi4yMzM4IDExLjgzNDQgMTUuOTIyMyAxMi40NzMgMTUuNTk5OUMxMy4zMzU0IDE1LjE2NTQgMTQuMTgzNyAxNC43OTMxIDE1LjAyIDE0LjM3MThDMTUuMzM2NyAxNC4yMTEgMTUuOTA4IDE0LjA5NzYgMTYuMTA1IDEzLjgyNTNDMTYuMzE4IDEzLjUzMTUgMTYuMTk5NCAxMi4xOTM0IDE2LjE5OTQgMTEuNjg3NUMxNi4xOTk0IDEwLjQ5NSAxNi4zNjYgOS4yMjIxNCAxNi4xOTk0IDguMTM5NTdDMTYuMDkxIDcuNDMyNDMgMTYuMjQzIDYuNTg2NzUgMTYuMTA1IDYuMDAxNzVDMTUuNDY5NCA1LjYzMDUgMTQuODE0IDUuMTk4MTggMTQuMTcxNyA0LjgxOTIxQzEzLjU1NjQgNC40NTY5NiAxMi44OTg3IDQuMjY5NTcgMTIuMjg1IDMuOTA5NTdDMTEuNzQxNyAzLjU5MiAxMC44MDIgMi45MDM4MiAxMC4wNjY3IDIuODY0MjlaTTkuODMwNjkgNC4xODIxNEMxMC40OTM3IDQuMTY5OTMgMTAuNzk0NyA0LjQ5MDA3IDExLjE5NzQgNC43Mjg1N0MxMS44NCA1LjEwNjg5IDEyLjUxMzcgNS40MTAzMiAxMy4xMzA3IDUuNzc0ODJDMTMuNDc5IDUuOTgwMjEgMTQuMDg5IDYuMDk2MjUgMTQuMjY0IDYuNDU3MjFDMTQuMjg2NyA2LjQ5OTM3IDE0LjI5ODUgNi41NDYxNyAxNC4yOTg1IDYuNTkzNjZDMTQuMjk4NSA2LjY0MTE1IDE0LjI4NjcgNi42ODc5NSAxNC4yNjQgNi43MzAxMUMxMy44NjYgNi44NjMxOCAxMy40Mzc0IDcuNDAyODYgMTIuODAxNyA3LjIzMDU3QzEyLjQ2MyA3LjA5MDQ1IDEyLjE0NTcgNi45MDY2MyAxMS44NTg0IDYuNjg0MTRDMTEuMTI5IDYuMjUzMTEgMTAuMzUyNCA1LjkxOTc5IDkuNjQxMzUgNS41MDE2MUM5LjMxMzY5IDUuMzA4NzUgOC43OTY2OSA1LjI3NjYxIDguNzQ1MDIgNC44MTkyMUM5LjEyMTYzIDQuNjMxMTEgOS40ODQzNyA0LjQxODI1IDkuODMwNjkgNC4xODIxNFpNNi45NTMzNSA1LjYzODU0QzcuNTk4NjkgNS42MzMzOSA3Ljk2MjM1IDUuOTkyMTEgOC4zNjgzNSA2LjIyOTk2QzguOTcxMzUgNi41ODUxNCA5LjU4MjAyIDYuODgyNDYgMTAuMTYwNyA3LjIzMDU3QzEwLjQ4MjQgNy40MjM0MyAxMS4xMDI0IDcuNTYyMjkgMTEuMjQ1NyA3LjkxMjk2QzExLjI4NDYgNy45ODI0NSAxMS4zMDEgOC4wNjE2MyAxMS4yOTI3IDguMTQwMjJDMTAuOTY4NiA4LjQwNyAxMC42MDIzIDguNjIxOTcgMTAuMjA3NyA4Ljc3Njk2QzkuNzc5MzUgOC44ODM2OCA5LjQzODAyIDguNTQzNjEgOS4yMTcwMiA4LjQxMzExQzguNTY5MzUgOC4wMzA2MSA3Ljg3MDAyIDcuNzM4MTEgNy4yMzYwMiA3LjM2Njg2QzYuODI4MzUgNy4xMjcwNyA1LjczNjAyIDYuODIzNjQgNS42NzkzNSA2LjMyMDYxQzUuODgzMzUgNi4yMDI2NCA2LjA5MzAyIDYuMDI4NzUgNi4yOTI2OSA1LjkxMTExQzYuNTE4MjkgNS44MzI4OCA2LjczODg5IDUuNzQxODcgNi45NTMzNSA1LjYzODU0Wk01LjExMzY5IDcuOTU4MjlDNS42OTkzNSA3Ljk4MjM5IDYuMTcxMzUgOC40MDI4MiA2LjU3NjAyIDguNjQwNjhDNy4xNjAzNSA4Ljk4Mzk2IDcuNzgyMzUgOS4xNjIwNCA4LjM2ODM1IDkuNTA1QzguODI3MDIgOS43NzMzOSA5LjM1NzM1IDEwLjE5MDYgOS45NzIwMiAxMC4yNzg0QzEwLjM0ODcgMTAuMzMyIDEwLjgzNCA5LjkzODI5IDExLjEwNTQgOS44MjM1NEMxMS45MDk0IDkuNDgxODYgMTIuNjQ3NCA5LjE2MyAxMy40MTY3IDguNzc3MjlDMTMuODUgOC41NiAxNC4yMjc0IDguMjM0MDcgMTQuODMxNyA4LjE4NTg2QzE0Ljg5NjQgOC4yMzE3OCAxNC45NDU3IDguMjk1MDUgMTQuOTczNCA4LjM2Nzc5QzE1LjE3MzQgOC42NTkzMiAxNC45OTU3IDkuNTc0MTEgMTQuOTI2IDkuODIzMjJDMTMuNzM1NyAxMC4zNzMyIDEyLjU4MDQgMTAuOTgzNiAxMS4zNDEgMTEuNTA2MkMxMC45MjcgMTEuNzIzOSAxMC41MDIgMTEuOTIxMyAxMC4wNjc0IDEyLjA5NzZDOS40ODMzNSAxMi4yNzU0IDguOTU5MzUgMTEuNzYxOCA4LjY1MjM1IDExLjU5NzJDNy40NTU2OSAxMC45NTY2IDUuOTYzMzUgMTAuNDQ5NyA1LjAxOTAyIDkuNTUwMzJDNS4wMTA2OSA5LjA0MjQ2IDQuOTQ3MzUgOC4zMTg2MSA1LjExMzY5IDcuOTU4MjlaTTE0LjY0MiAxMS4zMjRMMTQuOTI1IDExLjM2OTNMMTQuOTcyNCAxMS41MDU5QzE1LjE1NCAxMS43Njg1IDE1LjAxODcgMTIuNjk5NCAxNC45MjUgMTIuOTE2QzEzLjYxMDQgMTMuNjUxOCAxMi4yNTYyIDE0LjMyIDEwLjg2ODQgMTQuOTE3OUMxMC41Mzc3IDE1LjA1NzQgMTAuMDQ0NCAxNS41NjgxIDkuNTAxNjkgMTUuNDE4M0M5LjIxNTM3IDE1LjMwMDIgOC45NDU5NSAxNS4xNDcxIDguNzAwMDIgMTQuOTYyOUM4LjE1NTY5IDE0LjY0MTQgNy41NzAwMiAxNC40MDUyIDcuMDQ5MDIgMTQuMDk4NUM2LjU1OTM1IDEzLjgwOTMgNS4xNzIwMiAxMy4zODE0IDUuMDY4MDIgMTIuODI1QzQuOTA1MDIgMTIuNTg1NiA1LjAxMDM1IDExLjcyMTkgNS4wNjgwMiAxMS40NjA2TDUuMjA4MDIgMTEuNDE0M0M1LjU2MzAyIDExLjIwNTQgNi4xNTEzNSAxMS43NDUgNi4zODczNSAxMS44NjkxQzcuMTU5MDIgMTIuMjc1NCA3Ljk0OTM1IDEyLjU2NzYgOC42OTg2OSAxMy4wMDYzQzguOTc4NTYgMTMuMjAyOCA5LjI3ODk3IDEzLjM3MDUgOS41OTUwMiAxMy41MDY4QzEwLjAyMzQgMTMuNjM4NiAxMC43MDc0IDEzLjEyOTQgMTAuOTYxNyAxMy4wMDYzQzExLjkxMTQgMTIuNTQ5MyAxMi44NTY0IDEyLjE4MjggMTMuNzQ0NyAxMS42ODg1QzE0LjAzMDQgMTEuNTI5IDE0LjM5MTcgMTEuNTE0NiAxNC42NDIgMTEuMzI0WiIgZmlsbD0iYmxhY2siLz48L3N2Zz4=',
			2
		);
	}

	public function add_admin_submenu() {
		add_submenu_page(
			'envision-blocks',
			esc_html__( 'Upgrade', 'envision-blocks' ),
			esc_html__( 'Upgrade', 'envision-blocks' ),
			'manage_options',
			'envision_blocks_upgrade',
			array( $this, 'upgrade_page' ),
			99
		);
	}

	public function redirect_upgrade_page() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('ul#adminmenu a[href*="page=envision_blocks_upgrade"]').attr('href', 'https://envision-blocks.deothemes.com/#pricing').attr('target', '_blank').css('color', 'greenyellow');
			});
		</script>
		<?php
	}

	public function save_notice() {
		// phpcs:ignore
		if (isset($_GET['page']) && 'envision-blocks' === $_GET['page'] && isset($_GET['settings-updated']) && true === $_GET['settings-updated']) {
			?>
			<div class="envision-blocks-notice notice notice-success is-dismissible">
				<p>
					<strong>
						<?php
						echo esc_html__( 'Settings saved.', 'envision-blocks' );
						?>
					</strong>
				</p>
			</div>
			<?php
		}
	}

	public function register_settings() {
		$modules     = Utils::get_registered_modules();
		$pro_modules = Utils::get_registered_pro_modules();
		// Widgets
		foreach ( array_merge( $modules, $pro_modules ) as $title => $data ) {
			$slug = $data[0];
			register_setting(
				'envision-blocks-widgets-settings',
				'envision-blocks-widget-' . $slug,
				array(
					'default' => 'on',
				)
			);
		}
		// Woo Builder
		$woo_modules = Utils::get_woocommerce_builder_modules();
		foreach ( $woo_modules as $title => $data ) {
			$slug = $data[0];
			register_setting(
				'envision-blocks-widgets-settings',
				'envision-blocks-widget-' . $slug,
				array(
					'default' => 'on',
				)
			);
		}
		// Integrations settings
		register_setting(
			'envision-blocks-integrations-settings',
			'envision_blocks_integrations_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_options' ),
			)
		);
		// Integrations Sections
		add_settings_section(
			'envision_blocks_integrations_section',
			'',
			'',
			'envision_blocks_integrations'
		);
		// Integrations Fields
		add_settings_field(
			'envision_blocks_integrations_google_maps_api_key',
			esc_html__( 'Google Maps API key', 'envision-blocks' ),
			array( $this, 'integrations_google_maps_api_key_field' ),
			'envision_blocks_integrations',
			'envision_blocks_integrations_section'
		);
		add_settings_field(
			'envision_blocks_integrations_google_maps_language',
			esc_html__( 'Google Maps Language', 'envision-blocks' ),
			array( $this, 'integrations_google_maps_language_field' ),
			'envision_blocks_integrations',
			'envision_blocks_integrations_section'
		);
		// Settings settings
		register_setting(
			'envision-blocks-settings-settings',
			'envision_blocks_settings_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_options' ),
			)
		);
		// Settings Sections
		add_settings_section(
			'envision_blocks_settings_section',
			'',
			'',
			'envision_blocks_settings'
		);
		// Settings Fields
		$fields_settings = $this->settings_fields_settings();
		$this->settings_set_fields( $fields_settings );
	}

	/**
	 * Sanitize options
	 *
	 * @return mixed
	 * @since    1.0.0
	 */
	public function sanitize_options( $options ) {
		$sanitized = array();
		foreach ( $options as $option => $value ) {
			$sanitized[ $option ] = ( isset( $options[ $option ] ) ? sanitize_text_field( $value ) : '' );
		}
		return $sanitized;
	}

	public function settings_page() {
		?>

		<div class="envision-blocks-page-header">
			<div class="envision-blocks-page-header__container">
				<div class="envision-blocks-page-header__branding">
					<a href="
						<?php
						echo esc_url( Utils::$plugin_url );
						?>
		" target="_blank" rel="noopener">
						<img src="
							<?php
							echo esc_url( ENVISION_BLOCKS_URL . 'admin/assets/img/logo.png' );
							?>
		" class="envision-blocks-page-header__logo" alt="
		<?php
		echo esc_attr__( 'Envision Blocks', 'envision-blocks' );
		?>
		" />
					</a>
					<span class="envision-blocks-theme-version">
						<?php
						echo esc_html( ENVISION_BLOCKS_VERSION );
						?>
					</span>
				</div>
				<div class="envision-blocks-page-header__tagline">
					<span class="envision-blocks-page-header__tagline-text">
						<?php
						echo esc_html__( 'Made by ', 'envision-blocks' );
						?>
						<a href="https://deothemes.com/">
							<?php
							echo esc_html__( 'DeoThemes', 'envision-blocks' );
							?>
						</a>
					</span>
				</div>
			</div>
		</div>

		<div class="wrap envision-blocks-settings-page-wrap">
			<div class="envision-blocks-settings-header">
				<div class="envision-blocks-settings-header-left">
					<h2 style="display: none;"></h2>
					<h1 class="envision-blocks-settings-header-title">
						<?php
						echo esc_html__( 'Envision Blocks', 'envision-blocks' );
						?>
					</h1>
					<div class="envision-blocks-settings-header-subheading">
						<?php
						echo esc_html__( 'Creative and interactive widgets for Elementor', 'envision-blocks' );
						?>
					</div>
				</div>

				<div class="envision-blocks-settings-header-right">
					<ul class="envision-blocks-settings-header-shortlinks">

						<li class="envision-blocks-settings-header-shortlinks-get-help">
							<a href="https://deothemes.com/contact" target="_blank">

								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.96 16.8C12.24 16.8 12.4768 16.7032 12.6704 16.5096C12.8635 16.3165 12.96 16.08 12.96 15.8C12.96 15.52 12.8635 15.2835 12.6704 15.0904C12.4768 14.8968 12.24 14.8 11.96 14.8C11.68 14.8 11.4432 14.8968 11.2496 15.0904C11.0565 15.2835 10.96 15.52 10.96 15.8C10.96 16.08 11.0565 16.3165 11.2496 16.5096C11.4432 16.7032 11.68 16.8 11.96 16.8ZM12.08 8.56C12.4533 8.56 12.7533 8.6632 12.98 8.8696C13.2067 9.07653 13.32 9.34667 13.32 9.68C13.32 9.90667 13.2435 10.1365 13.0904 10.3696C12.9368 10.6032 12.72 10.8467 12.44 11.1C12.04 11.4467 11.7467 11.78 11.56 12.1C11.3733 12.42 11.28 12.74 11.28 13.06C11.28 13.2467 11.3501 13.4032 11.4904 13.5296C11.6301 13.6565 11.7933 13.72 11.98 13.72C12.1667 13.72 12.3333 13.6533 12.48 13.52C12.6267 13.3867 12.72 13.22 12.76 13.02C12.8 12.7933 12.8901 12.5835 13.0304 12.3904C13.1701 12.1968 13.4 11.9467 13.72 11.64C14.1333 11.2533 14.4235 10.9 14.5904 10.58C14.7568 10.26 14.84 9.90667 14.84 9.52C14.84 8.84 14.5835 8.2832 14.0704 7.8496C13.5568 7.41653 12.8933 7.2 12.08 7.2C11.52 7.2 11.0235 7.30667 10.5904 7.52C10.1568 7.73333 9.82 8.06 9.58 8.5C9.48667 8.67333 9.45333 8.8432 9.48 9.0096C9.50667 9.17653 9.6 9.31333 9.76 9.42C9.93333 9.52667 10.1235 9.56 10.3304 9.52C10.5368 9.48 10.7067 9.36667 10.84 9.18C10.9867 8.98 11.1635 8.82667 11.3704 8.72C11.5768 8.61333 11.8133 8.56 12.08 8.56ZM12 20C10.9067 20 9.87333 19.7899 8.9 19.3696C7.92667 18.9499 7.0768 18.38 6.3504 17.66C5.62347 16.94 5.05013 16.0933 4.6304 15.12C4.21013 14.1467 4 13.1067 4 12C4 10.8933 4.21013 9.85333 4.6304 8.88C5.05013 7.90667 5.62347 7.06 6.3504 6.34C7.0768 5.62 7.92667 5.04987 8.9 4.6296C9.87333 4.20987 10.9067 4 12 4C13.12 4 14.1667 4.20987 15.14 4.6296C16.1133 5.04987 16.96 5.62 17.68 6.34C18.4 7.06 18.9667 7.90667 19.38 8.88C19.7933 9.85333 20 10.8933 20 12C20 13.1067 19.7933 14.1467 19.38 15.12C18.9667 16.0933 18.4 16.94 17.68 17.66C16.96 18.38 16.1133 18.9499 15.14 19.3696C14.1667 19.7899 13.12 20 12 20ZM12 18.4C13.7867 18.4 15.3 17.7768 16.54 16.5304C17.78 15.2835 18.4 13.7733 18.4 12C18.4 10.2267 17.78 8.71653 16.54 7.4696C15.3 6.2232 13.7867 5.6 12 5.6C10.2533 5.6 8.74987 6.2232 7.4896 7.4696C6.22987 8.71653 5.6 10.2267 5.6 12C5.6 13.7733 6.22987 15.2835 7.4896 16.5304C8.74987 17.7768 10.2533 18.4 12 18.4Z" fill="#787C82"></path>
								</svg>

								<span>
									<?php
									echo esc_html__( 'Get help and support', 'envision-blocks' );
									?>
								</span>

								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M10.8626 8.04102L14.2809 12.0291L10.8626 16.0172L9.72363 15.041L12.3053 12.0291L9.72363 9.01721L10.8626 8.04102Z" fill="#757575"></path>
								</svg>

							</a>
						</li>

						<li class="envision-blocks-settings-header-shortlinks-leave-review">
							<a href="https://wordpress.org/support/plugin/envision-blocks/reviews/#new-post" title="
								<?php
								echo esc_html__( 'Leave a review', 'envision-blocks' );
								?>
		" target="_blank">

								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M12 7L13.2747 9.35645L16 9.73445L14 11.4545L14.6667 14L12 12.5682L9.33333 14L10 11.4545L8 9.73445L10.8 9.35645L12 7Z" fill="#787C82"></path>
									<path d="M13.116 21L12 20.3846L14.5714 16.0769H18.4286C18.5975 16.0772 18.7648 16.0455 18.9209 15.9837C19.077 15.922 19.2188 15.8313 19.3383 15.717C19.4577 15.6026 19.5524 15.4669 19.6169 15.3174C19.6815 15.168 19.7145 15.0078 19.7143 14.8462V7.46154C19.7145 7.29984 19.6815 7.13969 19.6169 6.99026C19.5524 6.84082 19.4577 6.70505 19.3383 6.59071C19.2188 6.47638 19.077 6.38573 18.9209 6.32396C18.7648 6.2622 18.5975 6.23053 18.4286 6.23077H5.57143C5.40251 6.23053 5.23521 6.2622 5.07911 6.32396C4.923 6.38573 4.78117 6.47638 4.66173 6.59071C4.54228 6.70505 4.44759 6.84082 4.38307 6.99026C4.31854 7.13969 4.28546 7.29984 4.28571 7.46154V14.8462C4.28546 15.0078 4.31854 15.168 4.38307 15.3174C4.44759 15.4669 4.54228 15.6026 4.66173 15.717C4.78117 15.8313 4.923 15.922 5.07911 15.9837C5.23521 16.0455 5.40251 16.0772 5.57143 16.0769H11.3571V17.3077H5.57143C4.88944 17.3077 4.23539 17.0484 3.75315 16.5867C3.27092 16.1251 3 15.499 3 14.8462V7.46154C2.99992 7.13826 3.06637 6.81814 3.19557 6.51945C3.32476 6.22077 3.51417 5.94938 3.75297 5.72079C3.99176 5.4922 4.27527 5.31088 4.58729 5.18721C4.8993 5.06353 5.23372 4.99992 5.57143 5H18.4286C18.7663 4.99992 19.1007 5.06353 19.4127 5.18721C19.7247 5.31088 20.0082 5.4922 20.247 5.72079C20.4858 5.94938 20.6752 6.22077 20.8044 6.51945C20.9336 6.81814 21.0001 7.13826 21 7.46154V14.8462C21 15.499 20.7291 16.1251 20.2468 16.5867C19.7646 17.0484 19.1106 17.3077 18.4286 17.3077H15.3204L13.116 21Z" fill="#787C82"></path>
								</svg>

								<span>
									<?php
									echo esc_html__( 'Leave a review', 'envision-blocks' );
									?>
								</span>

								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M10.8626 8.04102L14.2809 12.0291L10.8626 16.0172L9.72363 15.041L12.3053 12.0291L9.72363 9.01721L10.8626 8.04102Z" fill="#757575"></path>
								</svg>

							</a>
						</li>

						<li class="envision-blocks-settings-header-shortlinks-feedback">
							<a href="https://deothemes.canny.io/envision-blocks-feature-requests" target="_blank">

								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M6.09407 4L5.18893 4.8922L6.53571 6.22098L7.43957 5.32878L6.09343 4H6.09407ZM17.9066 4L16.5598 5.32878L17.4643 6.22098L18.8104 4.89283L17.9066 4ZM12 4.39597C11.7879 4.39851 11.5731 4.41056 11.3571 4.43658C11.3507 4.43658 11.3443 4.43531 11.3379 4.43658C8.73043 4.73165 6.65529 6.81112 6.29464 9.3735C6.00664 11.4358 6.86807 13.3059 8.30357 14.5103C8.89076 15.005 9.28793 15.6838 9.42857 16.433V20.2404H10.8943C11.118 20.6193 11.5262 20.875 12 20.875C12.4738 20.875 12.882 20.6193 13.1057 20.2404H14.5714V17.7022H14.6319V16.9483C14.6319 16.018 15.1217 15.0801 15.9176 14.351C16.9821 13.2989 17.7857 11.8045 17.7857 10.088C17.7857 6.95327 15.1719 4.36678 12 4.39597ZM12 5.6651C14.4846 5.63083 16.5 7.6386 16.5 10.088C16.5 11.4168 15.8764 12.5869 15.0131 13.4385L15.0336 13.4588C14.1757 14.2398 13.6209 15.292 13.4651 16.4337H10.6532C10.5118 15.346 10.0393 14.2933 9.14636 13.5382C8.01043 12.5863 7.3335 11.1522 7.55979 9.53278C7.84071 7.5339 9.48386 5.92654 11.4973 5.70635C11.6635 5.68347 11.8309 5.66991 11.9987 5.66573L12 5.6651ZM3 10.088V11.3572H4.92857V10.088H3ZM19.0714 10.088V11.3572H21V10.088H19.0714ZM6.53571 15.2242L5.18957 16.5523L6.09407 17.4452L7.43893 16.1164L6.53571 15.2242ZM17.4643 15.2242L16.5604 16.1164L17.9059 17.4452L18.8104 16.5523L17.4643 15.2242ZM10.7143 17.7028H13.2857V18.9719H10.7143V17.7028Z" fill="#787C82"></path>
								</svg>

								<span>
									<?php
									echo esc_html__( 'Have an idea or feedback?', 'envision-blocks' );
									?>
								</span>

								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M10.8626 8.04102L14.2809 12.0291L10.8626 16.0172L9.72363 15.041L12.3053 12.0291L9.72363 9.01721L10.8626 8.04102Z" fill="#757575"></path>
								</svg>

							</a>
						</li>

					</ul>
				</div>

			</div>

			<div class="envision-blocks-settings-page">
				<form method="post" action="options.php">
					<?php
					// phpcs:ignore
					$active_tab = (isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'widgets');
					?>

					<?php
					submit_button( esc_html__( 'Save Changes', 'envision-blocks' ), 'button-primary button-hero' );
					?>

					<div class="nav-tab-wrapper envision-blocks-nav-tab-wrapper">
						<a href="?page=envision-blocks&tab=widgets" data-tab="widgets" class="nav-tab 
								<?php
								echo ( 'widgets' === $active_tab ? 'nav-tab-active' : '' );
								?>
		">
							<?php
							echo esc_html__( 'Widgets', 'envision-blocks' );
							?>
						</a>
						<a href="?page=envision-blocks&tab=integrations" data-tab="integrations" class="nav-tab 
								<?php
								echo ( 'integrations' === $active_tab ? 'nav-tab-active' : '' );
								?>
		">
							<?php
							echo esc_html__( 'Integrations', 'envision-blocks' );
							?>
						</a>
						<a href="?page=envision-blocks&tab=settings" data-tab="settings" class="nav-tab 
								<?php
								echo ( 'settings' === $active_tab ? 'nav-tab-active' : '' );
								?>
		">
							<?php
							echo esc_html__( 'Settings', 'envision-blocks' );
							?>
						</a>
						<a href="?page=envision-blocks&tab=starter-sites" data-tab="starter-sites" class="nav-tab 
								<?php
								echo ( 'starter-sites' === $active_tab ? 'nav-tab-active' : '' );
								?>
		">
							<?php
							echo esc_html__( 'Starter Sites', 'envision-blocks' );
							?>
						</a>
						<a href="
									<?php
									echo esc_url( Utils::$plugin_url ) . '#pricing';
									?>
		" data-tab="pricing" class="nav-tab nav-tab--upgrade" target="_blank">
							<?php
							echo esc_html__( 'Get Pro', 'envision-blocks' );
							?>
						</a>
					</div>

					<?php
					if ( 'widgets' === $active_tab ) {
						?>

						<?php
						settings_fields( 'envision-blocks-widgets-settings' );
						do_settings_sections( 'envision-blocks' );
						// General Widgets
						$modules     = Utils::get_registered_modules();
						$pro_modules = Utils::get_registered_pro_modules();
						?>

						<div class="envision-blocks-tab-content envision-blocks-tab-content-widgets">
							<div class="envision-blocks-widgets envision-blocks-widgets-general">
								<?php
								foreach ( array_merge( $modules, $pro_modules ) as $title => $data ) {
									$slug          = $data[0];
									$url           = $data[1];
									$ref           = '?ref=envision-blocks-plugin-backend-elements-widget-prev' . $data[2];
									$class         = ( 'new' === $data[3] ? ' envision-blocks-widget--new' : '' );
									$default_value = 'on';
									$link_text     = esc_html__( 'View Widget Demo', 'envision-blocks' );
									if ( 'pro' === $data[3] && ! \EnvisionBlocks\envision_blocks_fs()->can_use_premium_code__premium_only() ) {
										$class = 'envision-blocks-widget--pro';
									}
									if ( 'envision-blocks-widget--pro' === $class ) {
										$default_value = 'off';
										$link_text     = '';
										$reff          = '';
									}
									echo '<div class="envision-blocks-widget ' . esc_attr( $class ) . '">';
									echo '<div>';
									echo '<h3 class="envision-blocks-widget__title">' . esc_html( $title ) . '</h3>';
									echo ( '' !== $url ? '<a href="' . esc_url( $url . $ref ) . '" target="_blank">' . esc_html( $link_text ) . '</a>' : '' );
									echo '</div>';
									echo '<fieldset class="envision-blocks-switch">';
									echo '<input type="checkbox" name="envision-blocks-widget-' . esc_attr( $slug ) . '" id="envision-blocks-widget-' . esc_attr( $slug ) . '" ' . checked( get_option( 'envision-blocks-widget-' . esc_attr( $slug ), esc_html( $default_value ) ), 'on', false ) . '>';
									echo '<label for="envision-blocks-widget-' . esc_attr( $slug ) . '"></label>';
									echo '</fieldset>';
									echo '</div>';
								}
								?>
							</div>

							<h2 class="envision-blocks-widgets-title">
								<?php
								echo esc_html__( 'Woo Builder', 'envision-blocks' );
								?>
							</h2>
							<div class="envision-blocks-widgets envision-blocks-widgets-woo">

								<?php
								$woo_builder_modules = Utils::get_woocommerce_builder_modules();
								foreach ( $woo_builder_modules as $title => $data ) {
									$slug          = $data[0];
									$url           = $data[1];
									$ref           = '?ref=envision-blocks-plugin-backend-elements-widget-prev' . $data[2];
									$default_value = ( class_exists( '\\WooCommerce' ) ? 'on' : 'off' );
									$link_text     = esc_html__( 'View Widget Demo', 'envision-blocks' );
									$class         = ( 'new' === $data[3] ? ' envision-blocks-widget--new' : '' );
									if ( 'pro' === $data[3] && ! \EnvisionBlocks\envision_blocks_fs()->can_use_premium_code__premium_only() ) {
										$class = 'envision-blocks-widget--pro';
									}
									if ( 'envision-blocks-widget--pro' === $class ) {
										$default_value = 'off';
										$link_text     = '';
									}
									echo '<div class="envision-blocks-widget ' . esc_attr( $class ) . '">';
									echo '<div>';
									echo '<h3 class="envision-blocks-widget__title">' . esc_html( $title ) . '</h3>';
									echo ( '' !== $url ? '<a href="' . esc_url( $url . $ref ) . '" target="_blank">' . esc_html( $link_text ) . '</a>' : '' );
									echo '</div>';
									echo '<fieldset class="envision-blocks-switch">';
									echo '<input type="checkbox" name="envision-blocks-widget-' . esc_attr( $slug ) . '" id="envision-blocks-widget-' . esc_attr( $slug ) . '" ' . checked( get_option( 'envision-blocks-widget-' . esc_attr( $slug ), esc_html( $default_value ) ), 'on', false ) . '>';
									echo '<label for="envision-blocks-widget-' . esc_attr( $slug ) . '"></label>';
									echo '</fieldset>';
									echo '</div>';
								}
								?>
							</div>
						</div>

						<?php
					} elseif ( 'integrations' === $active_tab ) {
						?>

						<div class="envision-blocks-tab-content envision-blocks-tab-content-integrations">
							<h1>
								<?php
								echo esc_html__( 'Integrations', 'envision-blocks' );
								?>
							</h1>
							<?php
							settings_fields( 'envision-blocks-integrations-settings' );
							do_settings_sections( 'envision_blocks_integrations' );
							?>
						</div>

						<?php
					} elseif ( 'settings' === $active_tab ) {
						?>

						<div class="envision-blocks-tab-content envision-blocks-tab-content-settings">
							<h1>
								<?php
								echo esc_html__( 'Settings', 'envision-blocks' );
								?>
							</h1>
							<?php
							settings_fields( 'envision-blocks-settings-settings' );
							do_settings_sections( 'envision_blocks_settings' );
							?>
						</div>

						<?php
					} elseif ( 'starter-sites' === $active_tab ) {
						?>

						<div class="envision-blocks-tab-content envision-blocks-tab-content-settings">
							<h1>
								<?php
								echo esc_html__( 'Starter Sites', 'envision-blocks' );
								?>
							</h1>

							<div class="envision-blocks-starter-sites-info">
								<a href="https://envision-blocks.deothemes.com/#pricing" class="button button-primary button-hero">
									<span>
										<?php
										echo esc_html__( 'Upgrade Now', 'envision-blocks' );
										?>
									</span>
								</a>
							</div>

							<ul class="envision-blocks-grid">
								<?php
								$demos = self::get_starter_sites();
								foreach ( $demos as $index => $demo ) {
									?>
									<li class="envision-blocks-demo">
										<div class="envision-blocks-demo__container">
											<a href="
														<?php
														echo esc_url( $demo['url'] );
														?>
				" class="envision-blocks-demo__url" target="_blank"
												<?php
												the_title_attribute( $demo['title'] );
												?>
												>
												<img src="
															<?php
															echo esc_url( $demo['preview'] );
															?>
				" class="envision-blocks-demo__img" alt="
											<?php
											echo esc_attr( $demo['title'] );
											?>
				" />
												<div class="envision-blocks-demo__title-holder">
													<h2 class="envision-blocks-demo__title">
														<?php
														echo esc_html( $demo['title'] );
														?>
													</h2>


												</div>
											</a>
										</div>
									</li>
									<?php
								}
								?>
							</ul>
						</div>

						<?php
					}
					?>

				</form>
			</div>
		</div>
		<?php
	}

	public function upgrade_page() {
		wp_redirect( esc_url( Utils::$plugin_url ) . '#pricing' );
		exit;
	}

	public function get_starter_sites() {
		return array(
			array(
				'title'   => esc_html__( 'SaaS', 'envision-blocks' ),
				'url'     => Utils::$plugin_url,
				'preview' => ENVISION_BLOCKS_URL . 'admin/assets/img/demos/00_saas.jpg',
				'slug'    => 'saas',
				'pro'     => true,
			),
			array(
				'title'   => esc_html__( 'Store', 'envision-blocks' ),
				'url'     => Utils::$plugin_url . '/store',
				'preview' => ENVISION_BLOCKS_URL . 'admin/assets/img/demos/01_store.jpg',
				'slug'    => 'store',
				'pro'     => true,
			),
			array(
				'title'   => esc_html__( 'Agency', 'envision-blocks' ),
				'url'     => Utils::$plugin_url . '/agency',
				'preview' => ENVISION_BLOCKS_URL . 'admin/assets/img/demos/02_agency.jpg',
				'slug'    => 'agency',
				'pro'     => true,
			),
			array(
				'title'   => esc_html__( 'Startup', 'envision-blocks' ),
				'url'     => Utils::$plugin_url . '/startup',
				'preview' => ENVISION_BLOCKS_URL . 'admin/assets/img/demos/03_startup.jpg',
				'slug'    => 'startup',
				'pro'     => true,
			),
		);
	}

	/**
	 * Integrations Google maps api key field.
	 *
	 * @since    1.0.0
	 */
	public function integrations_google_maps_api_key_field() {
		$settings = $this->get_integrations_options();
		?>

		<input type="text" class="regular-text" name="envision_blocks_integrations_settings[google_maps_key]" value="
		<?php
		echo esc_attr( $settings['google_maps_key'] );
		?>
		" />
		<p class="description">
			<?php
			// translators: %1$s: opening link tag, %2$s: closing link tag.
			printf( esc_html__( 'Enter your Google Maps API key here. If you don\'t have any, %1$s Generate a key here.%2$s', 'envision-blocks' ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">', '</a>' );
			?>
		</p>
		<?php
	}

	/**
	 * Integrations Google maps language field.
	 *
	 * @since    1.0.0
	 */
	public function integrations_google_maps_language_field() {
		$languages = $this->get_google_map_languages();
		$settings  = get_option( 'envision_blocks_integrations_settings', array() );
		?>
		<select name="envision_blocks_integrations_settings[language]" class="placeholder placeholder-active">
			<option value="">
				<?php
				esc_attr_e( 'Default', 'envision-blocks' );
				?>
			</option>
			<?php
			foreach ( $languages as $key => $value ) {
				$selected = '';
				if ( isset( $settings['language'] ) ) {
					if ( $key === $settings['language'] ) {
						$selected = 'selected="selected" ';
					}
				}
				?>
				<option value="
				<?php
				echo esc_attr( $key );
				?>
			"
					<?php
					echo esc_attr( $selected );
					?>
					>
					<?php
					echo esc_attr( $value );
					?>
				</option>
				<?php
			}
			?>
		</select>
		<?php
	}

	/**
	 * Default Fonts field.
	 *
	 * @since    1.0.0
	 */
	public function settings_checkbox_field( $args ) {
		$value        = Utils::get_option( $args['id'], $args['section'], $args['default'] );
		$html         = '<fieldset class="envision-blocks-switch">';
		$html        .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="0" />', $args['section'], $args['id'] );
		$html        .= sprintf(
			'<input type="checkbox" class="envision-blocks-switch__checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="1" %3$s />',
			$args['section'],
			$args['id'],
			checked( $value, 1, false )
		);
		$html        .= sprintf(
			'<label for="%1$s[%2$s]">%3$s</label>',
			$args['section'],
			$args['id'],
			$args['desc']
		);
		$html        .= '</fieldset>';
		$allowed_html = array(
			'fieldset' => array(
				'class' => array(),
			),
			'input'    => array(
				'type'    => array(),
				'class'   => array(),
				'id'      => array(),
				'name'    => array(),
				'value'   => array(),
				'checked' => array(),
			),
			'label'    => array(
				'for' => array(),
			),
		);
		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Register settings fields
	 *
	 * @since    1.0.0
	 */
	protected function settings_fields_settings() {
		$settings_fields = array(
			array(
				'name'              => 'default_fonts',
				'label'             => esc_html__( 'Load Default Fonts', 'envision-blocks' ),
				'default'           => 1,
				'class'             => 'envision-blocks-settings-table-row',
				'sanitize_callback' => 'sanitize_text_field',
			),
		);
		return $settings_fields;
	}

	/**
	 * Set settings fields
	 *
	 *  @param array $fields settings fields array
	 * @since    1.0.0
	 */
	protected function settings_set_fields( $fields ) {
		foreach ( $fields as $field ) {
			$name     = $field['name'];
			$label    = $field['label'];
			$callback = ( isset( $field['callback'] ) ? $field['callback'] : array( $this, 'settings_checkbox_field' ) );
			$args     = array(
				'id'                => $name,
				'class'             => ( isset( $field['class'] ) ? $field['class'] : $name ),
				'label_for'         => "envision_blocks_settings_settings[{$name}]",
				'desc'              => ( isset( $field['desc'] ) ? $field['desc'] : '' ),
				'name'              => $label,
				'default'           => ( isset( $field['default'] ) ? $field['default'] : 0 ),
				'sanitize_callback' => ( isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '' ),
				'section'           => 'envision_blocks_settings_settings',
			);
			add_settings_field(
				"envision_blocks_settings_{$name}",
				$label,
				$callback,
				'envision_blocks_settings',
				'envision_blocks_settings_section',
				$args
			);
		}
	}

	/**
	 * Returns Google Map languages List.
	 *
	 * @since 1.0.0
	 *
	 * @return array Google Map languages List.
	 */
	public function get_google_map_languages() {
		if ( null === self::$google_map_languages ) {
			self::$google_map_languages = array(
				'ar'    => __( 'Arabic', 'envision-blocks' ),
				'eu'    => __( 'Basque', 'envision-blocks' ),
				'bg'    => __( 'Bulgarian', 'envision-blocks' ),
				'bn'    => __( 'Bengali', 'envision-blocks' ),
				'ca'    => __( 'Catalan', 'envision-blocks' ),
				'cs'    => __( 'Czech', 'envision-blocks' ),
				'da'    => __( 'Danish', 'envision-blocks' ),
				'de'    => __( 'German', 'envision-blocks' ),
				'el'    => __( 'Greek', 'envision-blocks' ),
				'en'    => __( 'English', 'envision-blocks' ),
				'en-AU' => __( 'English (Australian)', 'envision-blocks' ),
				'en-GB' => __( 'English (Great Britain)', 'envision-blocks' ),
				'es'    => __( 'Spanish', 'envision-blocks' ),
				'fa'    => __( 'Farsi', 'envision-blocks' ),
				'fi'    => __( 'Finnish', 'envision-blocks' ),
				'fil'   => __( 'Filipino', 'envision-blocks' ),
				'fr'    => __( 'French', 'envision-blocks' ),
				'gl'    => __( 'Galician', 'envision-blocks' ),
				'gu'    => __( 'Gujarati', 'envision-blocks' ),
				'hi'    => __( 'Hindi', 'envision-blocks' ),
				'hr'    => __( 'Croatian', 'envision-blocks' ),
				'hu'    => __( 'Hungarian', 'envision-blocks' ),
				'id'    => __( 'Indonesian', 'envision-blocks' ),
				'it'    => __( 'Italian', 'envision-blocks' ),
				'iw'    => __( 'Hebrew', 'envision-blocks' ),
				'ja'    => __( 'Japanese', 'envision-blocks' ),
				'kn'    => __( 'Kannada', 'envision-blocks' ),
				'ko'    => __( 'Korean', 'envision-blocks' ),
				'lt'    => __( 'Lithuanian', 'envision-blocks' ),
				'lv'    => __( 'Latvian', 'envision-blocks' ),
				'ml'    => __( 'Malayalam', 'envision-blocks' ),
				'mr'    => __( 'Marathi', 'envision-blocks' ),
				'nl'    => __( 'Dutch', 'envision-blocks' ),
				'no'    => __( 'Norwegian', 'envision-blocks' ),
				'pl'    => __( 'Polish', 'envision-blocks' ),
				'pt'    => __( 'Portuguese', 'envision-blocks' ),
				'pt-BR' => __( 'Portuguese (Brazil)', 'envision-blocks' ),
				'pt-PT' => __( 'Portuguese (Portugal)', 'envision-blocks' ),
				'ro'    => __( 'Romanian', 'envision-blocks' ),
				'ru'    => __( 'Russian', 'envision-blocks' ),
				'sk'    => __( 'Slovak', 'envision-blocks' ),
				'sl'    => __( 'Slovenian', 'envision-blocks' ),
				'sr'    => __( 'Serbian', 'envision-blocks' ),
				'sv'    => __( 'Swedish', 'envision-blocks' ),
				'tl'    => __( 'Tagalog', 'envision-blocks' ),
				'ta'    => __( 'Tamil', 'envision-blocks' ),
				'te'    => __( 'Telugu', 'envision-blocks' ),
				'th'    => __( 'Thai', 'envision-blocks' ),
				'tr'    => __( 'Turkish', 'envision-blocks' ),
				'uk'    => __( 'Ukrainian', 'envision-blocks' ),
				'vi'    => __( 'Vietnamese', 'envision-blocks' ),
				'zh-CN' => __( 'Chinese (Simplified)', 'envision-blocks' ),
				'zh-TW' => __( 'Chinese (Traditional)', 'envision-blocks' ),
			);
		}
		return self::$google_map_languages;
	}

	/**
	 * Provide Integrations settings array().
	 *
	 * @param string $name slug.
	 * @return array
	 * @since 1.0.0
	 */
	public function get_integrations_options( $name = '' ) {
		$defaults     = array(
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
}

Admin_Options::instance();
