<?php
/**
 * NGO Shortcodes Class
 * Handles all plugin shortcode registration and rendering
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NGO_Shortcodes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'ngo_projects', array( $this, 'render_projects_shortcode' ) );
	}

	/**
	 * Render the projects shortcode
	 *
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_projects_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'status'           => 'all',
				'category'         => '',
				'limit'            => 9,
				'columns'          => 3,
				'show_pagination'  => 'yes',
				'show_search'      => 'yes',
				'show_filters'     => 'yes',
			),
			$atts,
			'ngo_projects'
		);

		// Sanitize and validate attributes
		$status            = sanitize_text_field( $atts['status'] );
		$category          = sanitize_text_field( $atts['category'] );
		$limit             = absint( $atts['limit'] );
		$columns           = absint( $atts['columns'] );
		$show_pagination   = 'yes' === sanitize_text_field( $atts['show_pagination'] );
		$show_search       = 'yes' === sanitize_text_field( $atts['show_search'] );
		$show_filters      = 'yes' === sanitize_text_field( $atts['show_filters'] );

		// Validate limit
		$limit = max( 1, min( 50, $limit ) );

		// Validate columns
		if ( ! in_array( $columns, array( 2, 3, 4 ), true ) ) {
			$columns = 3;
		}

		// Get pagination info
		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

		// Build WP_Query arguments
		$query_args = array(
			'post_type'      => 'ngo_project',
			'posts_per_page' => $limit,
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

		// Get all categories for filters
		$all_categories = get_terms(
			array(
				'taxonomy'   => 'ngo_category',
				'hide_empty' => true,
			)
		);

		ob_start();
		?>
		<div class="ngo-projects-wrapper">
			<?php if ( $show_search ) : ?>
				<div class="ngo-projects-search mb-8">
					<div class="relative">
						<input 
							type="text" 
							class="ngo-search-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" 
							placeholder="<?php esc_attr_e( 'Search projects...', 'ngo-impact-projects' ); ?>"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'ngo_project_search' ) ); ?>"
						>
						<span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $show_filters && ! empty( $all_categories ) ) : ?>
				<div class="ngo-projects-filters mb-8">
					<div class="flex flex-wrap gap-2">
						<button 
							class="ngo-filter-btn ngo-filter-active px-4 py-2 rounded-full border-2 border-green-600 bg-green-600 text-white transition-all duration-300" 
							data-status="all"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'ngo_project_filter' ) ); ?>"
						>
							<?php esc_html_e( 'All', 'ngo-impact-projects' ); ?>
						</button>
						<?php foreach ( $all_categories as $cat ) : ?>
							<button 
								class="ngo-filter-btn px-4 py-2 rounded-full border-2 border-gray-300 bg-white text-gray-700 hover:border-green-600 transition-all duration-300" 
								data-category="<?php echo esc_attr( $cat->slug ); ?>"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'ngo_project_filter' ) ); ?>"
							>
								<?php echo esc_html( $cat->name ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div 
				class="ngo-projects-grid grid gap-6"
				style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); --cols: <?php echo esc_attr( $columns ); ?>"
				data-columns="<?php echo esc_attr( $columns ); ?>"
			>
				<?php
				if ( $projects->have_posts() ) {
					while ( $projects->have_posts() ) {
						$projects->the_post();
						include NGO_PLUGIN_PATH . 'templates/partials/project-card.php';
					}
				} else {
					echo '<p class="col-span-full text-center text-gray-600">' . esc_html__( 'No projects found.', 'ngo-impact-projects' ) . '</p>';
				}
				?>
			</div>

			<?php if ( $show_pagination && $projects->max_num_pages > 1 ) : ?>
				<div class="ngo-projects-pagination mt-8 text-center">
					<?php
					echo wp_kses_post(
						paginate_links(
							array(
								'base'      => add_query_arg( 'paged', '%#%' ),
								'format'    => '?paged=%#%',
								'current'   => $paged,
								'total'     => $projects->max_num_pages,
								'prev_text' => esc_html__( 'Previous', 'ngo-impact-projects' ),
								'next_text' => esc_html__( 'Next', 'ngo-impact-projects' ),
								'type'      => 'list',
							)
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php

		wp_reset_postdata();

		return ob_get_clean();
	}
}
