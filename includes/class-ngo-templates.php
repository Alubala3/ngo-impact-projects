<?php
/**
 * NGO Templates Class
 * Handles template loading and filtering
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NGO_Templates {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'load_template' ), 99 );
	}

	/**
	 * Load custom templates for NGO projects
	 *
	 * @param string $template Template path
	 * @return string Template path
	 */
	public function load_template( $template ) {
		if ( is_post_type_archive( 'ngo_project' ) ) {
			$plugin_template = NGO_PLUGIN_PATH . 'templates/archive-ngo-project.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		if ( is_singular( 'ngo_project' ) ) {
			$plugin_template = NGO_PLUGIN_PATH . 'templates/single-ngo-project.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}
}
