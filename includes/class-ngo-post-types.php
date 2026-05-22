<?php
/**
 * NGO Post Types Class
 * Handles registration of custom post types and taxonomies
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NGO_Post_Types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ), 10 );
		add_action( 'init', array( $this, 'register_taxonomies' ), 10 );
	}

	/**
	 * Register custom post types
	 */
	public function register_post_types() {
		$args = array(
			'labels'              => array(
				'name'          => esc_html__( 'Projects', 'ngo-impact-projects' ),
				'singular_name' => esc_html__( 'Project', 'ngo-impact-projects' ),
				'menu_name'     => esc_html__( 'NGO Projects', 'ngo-impact-projects' ),
				'add_new'       => esc_html__( 'Add New', 'ngo-impact-projects' ),
				'add_new_item'  => esc_html__( 'Add New Project', 'ngo-impact-projects' ),
				'edit_item'     => esc_html__( 'Edit Project', 'ngo-impact-projects' ),
				'view_item'     => esc_html__( 'View Project', 'ngo-impact-projects' ),
				'all_items'     => esc_html__( 'All Projects', 'ngo-impact-projects' ),
			),
			'public'              => true,
			'show_in_rest'        => true,
			'has_archive'         => true,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
			'menu_icon'           => 'dashicons-megaphone',
			'rewrite'             => array(
				'slug'       => 'ngo-projects',
				'with_front' => false,
			),
			'show_in_nav_menus'   => true,
			'rest_base'           => 'ngo-projects',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);

		register_post_type( 'ngo_project', $args );
	}

	/**
	 * Register custom taxonomies
	 */
	public function register_taxonomies() {
		$args = array(
			'labels'            => array(
				'name'          => esc_html__( 'Categories', 'ngo-impact-projects' ),
				'singular_name' => esc_html__( 'Category', 'ngo-impact-projects' ),
				'menu_name'     => esc_html__( 'Categories', 'ngo-impact-projects' ),
				'add_new_item'  => esc_html__( 'Add New Category', 'ngo-impact-projects' ),
				'edit_item'     => esc_html__( 'Edit Category', 'ngo-impact-projects' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'ngo-category',
				'with_front' => false,
			),
			'rest_base'         => 'ngo-categories',
		);

		register_taxonomy( 'ngo_category', 'ngo_project', $args );
	}
}
