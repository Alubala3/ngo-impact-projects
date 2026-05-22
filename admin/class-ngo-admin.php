<?php
/**
 * NGO Admin Class
 * Handles admin panel functionality and custom columns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NGO_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'manage_ngo_project_posts_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_ngo_project_posts_custom_column', array( $this, 'populate_custom_columns' ), 10, 2 );
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		// Main menu item is created by CPT, so we just add submenus
		add_submenu_page(
			'edit.php?post_type=ngo_project',
			esc_html__( 'NGO Dashboard', 'ngo-impact-projects' ),
			esc_html__( 'Dashboard', 'ngo-impact-projects' ),
			'manage_options',
			'ngo-dashboard',
			array( $this, 'render_dashboard' )
		);

		add_submenu_page(
			'edit.php?post_type=ngo_project',
			esc_html__( 'NGO Settings', 'ngo-impact-projects' ),
			esc_html__( 'Settings', 'ngo-impact-projects' ),
			'manage_options',
			'ngo-settings',
			array( $this, 'render_settings' )
		);
	}

	/**
	 * Render dashboard page
	 */
	public function render_dashboard() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'ngo-impact-projects' ) );
		}

		// Get statistics
		$total_projects   = wp_count_posts( 'ngo_project' )->publish;
		$ongoing_count    = $this->count_projects_by_status( 'ongoing' );
		$completed_count  = $this->count_projects_by_status( 'completed' );
		$upcoming_count   = $this->count_projects_by_status( 'upcoming' );
		$total_beneficiaries = $this->get_total_beneficiaries();

		include NGO_PLUGIN_PATH . 'admin/views/dashboard.php';
	}

	/**
	 * Render settings page
	 */
	public function render_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'ngo-impact-projects' ) );
		}

		include NGO_PLUGIN_PATH . 'admin/views/settings.php';
	}

	/**
	 * Count projects by status
	 *
	 * @param string $status Project status
	 * @return int Count
	 */
	private function count_projects_by_status( $status ) {
		$args = array(
			'post_type'      => 'ngo_project',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => 'project_status',
					'value'   => $status,
					'compare' => '=',
				),
			),
		);

		$query = new WP_Query( $args );
		return $query->found_posts;
	}

	/**
	 * Get total beneficiaries across all projects
	 *
	 * @return int Total beneficiaries
	 */
	private function get_total_beneficiaries() {
		$args = array(
			'post_type'      => 'ngo_project',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		$query = new WP_Query( $args );
		$total = 0;

		foreach ( $query->posts as $post_id ) {
			$beneficiaries = get_field( 'beneficiaries_count', $post_id );
			if ( ! empty( $beneficiaries ) ) {
				$total += absint( $beneficiaries );
			}
		}

		return $total;
	}

	/**
	 * Add custom columns to project list
	 *
	 * @param array $columns Existing columns
	 * @return array Modified columns
	 */
	public function add_custom_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;

			if ( 'title' === $key ) {
				$new_columns['ngo_status']       = esc_html__( 'Status', 'ngo-impact-projects' );
				$new_columns['ngo_location']     = esc_html__( 'Location', 'ngo-impact-projects' );
				$new_columns['ngo_beneficiaries'] = esc_html__( 'Beneficiaries', 'ngo-impact-projects' );
				$new_columns['ngo_progress']     = esc_html__( 'Progress', 'ngo-impact-projects' );
			}
		}

		return $new_columns;
	}

	/**
	 * Populate custom columns
	 *
	 * @param string $column_key Column key
	 * @param int    $post_id Post ID
	 */
	public function populate_custom_columns( $column_key, $post_id ) {
		switch ( $column_key ) {
			case 'ngo_status':
				$status = get_field( 'project_status', $post_id );
				echo wp_kses_post( ngo_get_project_status_badge( $status ) );
				break;

			case 'ngo_location':
				$location = get_field( 'project_location', $post_id );
				echo esc_html( $location );
				break;

			case 'ngo_beneficiaries':
				$beneficiaries = get_field( 'beneficiaries_count', $post_id );
				if ( ! empty( $beneficiaries ) ) {
					echo esc_html( number_format( absint( $beneficiaries ) ) );
				}
				break;

			case 'ngo_progress':
				$progress = get_field( 'project_progress', $post_id );
				if ( ! empty( $progress ) ) {
					echo esc_html( absint( $progress ) . '%' );
				}
				break;
		}
	}

	/**
	 * Add dashboard widget
	 */
	public function add_dashboard_widget() {
		wp_add_dashboard_widget(
			'ngo_dashboard_widget',
			esc_html__( 'NGO Projects Overview', 'ngo-impact-projects' ),
			array( $this, 'render_dashboard_widget' )
		);
	}

	/**
	 * Render dashboard widget
	 */
	public function render_dashboard_widget() {
		$total_projects      = wp_count_posts( 'ngo_project' )->publish;
		$ongoing_count       = $this->count_projects_by_status( 'ongoing' );
		$completed_count     = $this->count_projects_by_status( 'completed' );
		$upcoming_count      = $this->count_projects_by_status( 'upcoming' );
		$total_beneficiaries = $this->get_total_beneficiaries();

		?>
		<div class="ngo-widget-content">
			<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
				<div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
					<strong><?php esc_html_e( 'Total Projects', 'ngo-impact-projects' ); ?></strong>
					<div style="font-size: 24px; color: #16a34a; margin-top: 10px;"><?php echo esc_html( $total_projects ); ?></div>
				</div>
				<div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
					<strong><?php esc_html_e( 'Ongoing', 'ngo-impact-projects' ); ?></strong>
					<div style="font-size: 24px; color: #16a34a; margin-top: 10px;"><?php echo esc_html( $ongoing_count ); ?></div>
				</div>
				<div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
					<strong><?php esc_html_e( 'Completed', 'ngo-impact-projects' ); ?></strong>
					<div style="font-size: 24px; color: #6b7280; margin-top: 10px;"><?php echo esc_html( $completed_count ); ?></div>
				</div>
				<div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
					<strong><?php esc_html_e( 'Upcoming', 'ngo-impact-projects' ); ?></strong>
					<div style="font-size: 24px; color: #3b82f6; margin-top: 10px;"><?php echo esc_html( $upcoming_count ); ?></div>
				</div>
			</div>
			<div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
				<strong><?php esc_html_e( 'Total Beneficiaries', 'ngo-impact-projects' ); ?></strong>
				<div style="font-size: 28px; color: #16a34a; margin-top: 10px; font-weight: bold;"><?php echo esc_html( number_format( $total_beneficiaries ) ); ?></div>
			</div>
			<div style="margin-top: 20px;">
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=ngo_project' ) ); ?>" class="button button-primary" style="margin-right: 10px;">
					<?php esc_html_e( 'Add New Project', 'ngo-impact-projects' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ngo_project' ) ); ?>" class="button">
					<?php esc_html_e( 'View All Projects', 'ngo-impact-projects' ); ?>
				</a>
			</div>
		</div>
		<?php
	}
}
