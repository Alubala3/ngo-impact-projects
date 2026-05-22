<?php
/**
 * NGO Enqueue Class
 * Handles enqueueing of CSS and JavaScript assets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NGO_Enqueue {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Check if we should enqueue assets
	 *
	 * @return bool
	 */
	private function should_enqueue() {
		// Check if on single project page
		if ( is_singular( 'ngo_project' ) ) {
			return true;
		}

		// Check if on project archive
		if ( is_post_type_archive( 'ngo_project' ) ) {
			return true;
		}

		// Check if post contains ngo_projects shortcode
		if ( is_home() || is_page() || is_single() ) {
			global $post;
			if ( isset( $post->ID ) && has_shortcode( $post->post_content, 'ngo_projects' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Enqueue assets
	 */
	public function enqueue_assets() {
		if ( ! $this->should_enqueue() ) {
			return;
		}

		// Enqueue Tailwind CSS
		wp_enqueue_style(
			'ngo-tailwind',
			NGO_PLUGIN_URL . 'assets/css/tailwind.min.css',
			array(),
			NGO_VERSION
		);

		// Enqueue custom CSS
		wp_enqueue_style(
			'ngo-public',
			NGO_PLUGIN_URL . 'assets/css/ngo-public.css',
			array( 'ngo-tailwind' ),
			NGO_VERSION
		);

		// Enqueue Swiper CSS
		wp_enqueue_style(
			'ngo-swiper',
			NGO_PLUGIN_URL . 'assets/js/swiper/swiper.min.css',
			array(),
			NGO_VERSION
		);

		// Enqueue GSAP
		wp_enqueue_script(
			'ngo-gsap',
			NGO_PLUGIN_URL . 'assets/js/gsap/gsap.min.js',
			array(),
			NGO_VERSION,
			array(
				'strategy' => 'defer',
			)
		);

		// Enqueue GSAP ScrollTrigger
		wp_enqueue_script(
			'ngo-gsap-scroll-trigger',
			NGO_PLUGIN_URL . 'assets/js/gsap/ScrollTrigger.min.js',
			array( 'ngo-gsap' ),
			NGO_VERSION,
			array(
				'strategy' => 'defer',
			)
		);

		// Enqueue Swiper JS
		wp_enqueue_script(
			'ngo-swiper',
			NGO_PLUGIN_URL . 'assets/js/swiper/swiper.min.js',
			array(),
			NGO_VERSION,
			array(
				'strategy' => 'defer',
			)
		);

		// Enqueue custom JS
		wp_enqueue_script(
			'ngo-public',
			NGO_PLUGIN_URL . 'assets/js/ngo-public.min.js',
			array( 'ngo-gsap', 'ngo-gsap-scroll-trigger', 'ngo-swiper' ),
			NGO_VERSION,
			array(
				'strategy' => 'defer',
			)
		);

		// Localize script with data
		wp_localize_script(
			'ngo-public',
			'ngoProjectsData',
			array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'nonces'       => array(
					'filter'    => wp_create_nonce( 'ngo_project_filter' ),
					'search'    => wp_create_nonce( 'ngo_project_search' ),
					'load_more' => wp_create_nonce( 'ngo_project_load_more' ),
				),
				'i18n'         => array(
					'no_results' => esc_html__( 'No projects found.', 'ngo-impact-projects' ),
				),
				'primaryColor' => sanitize_hex_color( get_option( 'ngo_primary_color', '#16a34a' ) ),
			)
		);
	}
}
