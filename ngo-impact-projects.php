<?php
/**
 * Plugin Name: NGO Impact Projects
 * Plugin URI: https://example.com/ngo-impact-projects
 * Description: A comprehensive WordPress plugin for NGOs, charities, and nonprofits to showcase their projects with impact statistics, galleries, timelines, and donation CTAs.
 * Version: 1.0.0
 * Author: NGO Projects Team
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ngo-impact-projects
 * Domain Path: /languages
 * Requires: 6.0
 * Requires PHP: 8.0
 * Requires Plugins: advanced-custom-fields
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'NGO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'NGO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NGO_VERSION', '1.0.0' );

/**
 * Check if ACF is installed and active
 */
function ngo_check_acf_dependency() {
	if ( ! is_plugin_active( 'advanced-custom-fields/acf.php' ) && ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
		return false;
	}
	return true;
}

/**
 * Display admin notice if ACF is not active
 */
function ngo_admin_notice() {
	if ( ! ngo_check_acf_dependency() ) {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php esc_html_e( 'NGO Impact Projects requires Advanced Custom Fields (ACF) to be installed and activated.', 'ngo-impact-projects' ); ?></p>
		</div>
		<?php
	}
}

/**
 * Load plugin textdomain
 */
function ngo_load_textdomain() {
	load_plugin_textdomain( 'ngo-impact-projects', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Autoloader for plugin classes
 */
spl_autoload_register(
	function ( $class ) {
		if ( strpos( $class, 'NGO_' ) !== 0 ) {
			return;
		}

		$file = NGO_PLUGIN_PATH . 'includes/' . str_replace( '_', '-', strtolower( $class ) ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);

/**
 * Initialize the plugin
 */
function ngo_init_plugin() {
	if ( ! ngo_check_acf_dependency() ) {
		add_action( 'admin_notices', 'ngo_admin_notice' );
		return;
	}

	// Load helper functions
	require_once NGO_PLUGIN_PATH . 'includes/class-ngo-helpers.php';

	// Initialize classes
	new NGO_Post_Types();
	new NGO_ACF_Fields();
	new NGO_Shortcodes();
	new NGO_AJAX();
	new NGO_Enqueue();
	new NGO_Admin();

	// Load admin files
	if ( is_admin() ) {
		require_once NGO_PLUGIN_PATH . 'admin/class-ngo-settings.php';
		new NGO_Settings();
	}

	// Load template functions
	require_once NGO_PLUGIN_PATH . 'includes/class-ngo-templates.php';
	new NGO_Templates();
}

/**
 * Plugin activation hook
 */
function ngo_activate_plugin() {
	if ( ! ngo_check_acf_dependency() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( esc_html__( 'NGO Impact Projects requires Advanced Custom Fields (ACF) to be installed and activated.', 'ngo-impact-projects' ) );
	}

	// Initialize post types to register them
	new NGO_Post_Types();

	// Flush rewrite rules
	flush_rewrite_rules();

	// Set up default categories
	ngo_setup_default_categories();
}

/**
 * Plugin deactivation hook
 */
function ngo_deactivate_plugin() {
	flush_rewrite_rules();
}

/**
 * Set up default project categories on activation
 */
function ngo_setup_default_categories() {
	$default_categories = array(
		'education'            => esc_html__( 'Education', 'ngo-impact-projects' ),
		'health'               => esc_html__( 'Health', 'ngo-impact-projects' ),
		'climate-action'       => esc_html__( 'Climate Action', 'ngo-impact-projects' ),
		'women-empowerment'    => esc_html__( 'Women Empowerment', 'ngo-impact-projects' ),
		'agriculture'          => esc_html__( 'Agriculture', 'ngo-impact-projects' ),
		'youth-development'    => esc_html__( 'Youth Development', 'ngo-impact-projects' ),
		'water-sanitation'     => esc_html__( 'Water & Sanitation', 'ngo-impact-projects' ),
		'emergency-relief'     => esc_html__( 'Emergency Relief', 'ngo-impact-projects' ),
	);

	foreach ( $default_categories as $slug => $name ) {
		if ( ! term_exists( $slug, 'ngo_category' ) ) {
			wp_insert_term( $name, 'ngo_category', array( 'slug' => $slug ) );
		}
	}
}

// Hook up plugin lifecycle events
register_activation_hook( __FILE__, 'ngo_activate_plugin' );
register_deactivation_hook( __FILE__, 'ngo_deactivate_plugin' );

// Load text domain
add_action( 'init', 'ngo_load_textdomain', 0 );

// Initialize plugin
add_action( 'plugins_loaded', 'ngo_init_plugin', 10 );
