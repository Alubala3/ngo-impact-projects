<?php
/**
 * NGO Settings Class
 * Handles plugin settings and options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NGO_Settings {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		// General settings
		register_setting( 'ngo_general_settings', 'ngo_default_status' );
		register_setting( 'ngo_general_settings', 'ngo_default_columns' );
		register_setting( 'ngo_general_settings', 'ngo_items_per_page' );
		register_setting( 'ngo_general_settings', 'ngo_enable_ajax' );

		// CTA settings
		register_setting( 'ngo_cta_settings', 'ngo_global_volunteer_url' );
		register_setting( 'ngo_cta_settings', 'ngo_global_donate_url' );
		register_setting( 'ngo_cta_settings', 'ngo_global_contact_url' );

		// Style settings
		register_setting( 'ngo_style_settings', 'ngo_primary_color' );
		register_setting( 'ngo_style_settings', 'ngo_accent_color' );

		// Advanced settings
		register_setting( 'ngo_advanced_settings', 'ngo_cache_version' );
	}

	/**
	 * Enqueue admin assets
	 */
	public function enqueue_admin_assets() {
		if ( isset( $_GET['page'] ) && 'ngo-settings' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			wp_add_inline_script(
				'wp-color-picker',
				"
				jQuery(document).ready(function($) {
					$('.ngo-color-picker').wpColorPicker();
				});
			"
			);
		}
	}
}
