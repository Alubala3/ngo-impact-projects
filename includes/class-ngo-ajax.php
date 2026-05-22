<?php
/**
 * NGO AJAX Class
 * Handles AJAX requests for filtering and searching projects
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NGO_AJAX {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_ngo_filter_projects', array( $this, 'filter_projects' ) );
		add_action( 'wp_ajax_nopriv_ngo_filter_projects', array( $this, 'filter_projects' ) );
		add_action( 'wp_ajax_ngo_search_projects', array( $this, 'search_projects' ) );
		add_action( 'wp_ajax_nopriv_ngo_search_projects', array( $this, 'search_projects' ) );
		add_action( 'wp_ajax_ngo_load_more', array( $this, 'load_more' ) );
		add_action( 'wp_ajax_nopriv_ngo_load_more', array( $this, 'load_more' ) );
	}

	/**
	 * Filter projects by category or status
	 */
	public function filter_projects() {
		// Verify nonce
		check_ajax_referer( 'ngo_project_filter', 'nonce' );

		// Sanitize inputs
		$category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';
		$status   = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'all';
		$paged    = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

		// Build query arguments
		$query_args = array(
			'post_type'      => 'ngo_project',
			'posts_per_page' => 9,
			'paged'          => $paged,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array( 'relation' => 'AND' ),
			'tax_query'      => array( 'relation' => 'AND' ),
		);

		// Add status filter
		if ( 'all' !== $status ) {
			$query_args['meta_query'][] = array(
				'key'     => 'project_status',
				'value'   => $status,
				'compare' => '=',
			);
		}

		// Add category filter
		if ( ! empty( $category ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'ngo_category',
				'field'    => 'slug',
				'terms'    => $category,
			);
		}

		// Execute query
		$projects = new WP_Query( $query_args );

		// Build HTML output
		ob_start();
		if ( $projects->have_posts() ) {
			while ( $projects->have_posts() ) {
				$projects->the_post();
				include NGO_PLUGIN_PATH . 'templates/partials/project-card.php';
			}
		} else {
			echo '<p class="col-span-full text-center text-gray-600">' . esc_html__( 'No projects found.', 'ngo-impact-projects' ) . '</p>';
		}
		$html = ob_get_clean();

		wp_reset_postdata();

		// Return JSON response
		wp_send_json_success(
			array(
				'html'      => $html,
				'count'     => $projects->post_count,
				'total'     => $projects->found_posts,
				'max_pages' => $projects->max_num_pages,
			)
		);
	}

	/**
	 * Search projects
	 */
	public function search_projects() {
		// Verify nonce
		check_ajax_referer( 'ngo_project_search', 'nonce' );

		// Sanitize input
		$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

		if ( empty( $search ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Search term is required.', 'ngo-impact-projects' ) ) );
		}

		// Build query arguments
		$query_args = array(
			'post_type'      => 'ngo_project',
			'posts_per_page' => 9,
			'orderby'        => 'relevance',
			'order'          => 'DESC',
			's'              => $search,
		);

		// Execute query
		$projects = new WP_Query( $query_args );

		// Build HTML output
		ob_start();
		if ( $projects->have_posts() ) {
			while ( $projects->have_posts() ) {
				$projects->the_post();
				include NGO_PLUGIN_PATH . 'templates/partials/project-card.php';
			}
		} else {
			echo '<p class="col-span-full text-center text-gray-600">' . esc_html__( 'No projects found.', 'ngo-impact-projects' ) . '</p>';
		}
		$html = ob_get_clean();

		wp_reset_postdata();

		// Return JSON response
		wp_send_json_success(
			array(
				'html'  => $html,
				'count' => $projects->post_count,
				'total' => $projects->found_posts,
			)
		);
	}

	/**
	 * Load more projects (pagination)
	 */
	public function load_more() {
		// Verify nonce
		check_ajax_referer( 'ngo_project_load_more', 'nonce' );

		// Sanitize inputs
		$paged    = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
		$status   = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'all';
		$category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

		// Build query arguments
		$query_args = array(
			'post_type'      => 'ngo_project',
			'posts_per_page' => 9,
			'paged'          => $paged,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array( 'relation' => 'AND' ),
			'tax_query'      => array( 'relation' => 'AND' ),
		);

		// Add status filter
		if ( 'all' !== $status ) {
			$query_args['meta_query'][] = array(
				'key'     => 'project_status',
				'value'   => $status,
				'compare' => '=',
			);
		}

		// Add category filter
		if ( ! empty( $category ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'ngo_category',
				'field'    => 'slug',
				'terms'    => $category,
			);
		}

		// Execute query
		$projects = new WP_Query( $query_args );

		// Build HTML output
		ob_start();
		if ( $projects->have_posts() ) {
			while ( $projects->have_posts() ) {
				$projects->the_post();
				include NGO_PLUGIN_PATH . 'templates/partials/project-card.php';
			}
		}
		$html = ob_get_clean();

		wp_reset_postdata();

		// Return JSON response
		wp_send_json_success(
			array(
				'html'      => $html,
				'count'     => $projects->post_count,
				'total'     => $projects->found_posts,
				'max_pages' => $projects->max_num_pages,
			)
		);
	}
}
