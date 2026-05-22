<?php
/**
 * NGO Helper Functions
 * Utility functions used throughout the plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ngo_get_project_status_badge' ) ) {
	/**
	 * Get project status badge HTML
	 *
	 * @param string $status Project status (ongoing, completed, upcoming)
	 * @return string HTML badge
	 */
	function ngo_get_project_status_badge( $status ) {
		$status = sanitize_text_field( $status );

		$status_colors = array(
			'ongoing'   => array(
				'bg'    => 'bg-green-600',
				'text'  => 'text-white',
				'label' => esc_html__( 'Ongoing', 'ngo-impact-projects' ),
			),
			'completed' => array(
				'bg'    => 'bg-gray-600',
				'text'  => 'text-white',
				'label' => esc_html__( 'Completed', 'ngo-impact-projects' ),
			),
			'upcoming'  => array(
				'bg'    => 'bg-blue-600',
				'text'  => 'text-white',
				'label' => esc_html__( 'Upcoming', 'ngo-impact-projects' ),
			),
		);

		if ( ! isset( $status_colors[ $status ] ) ) {
			$status = 'ongoing';
		}

		$colors = $status_colors[ $status ];

		return sprintf(
			'<span class="ngo-status-badge inline-block px-3 py-1 rounded-full text-sm font-semibold %s %s">%s</span>',
			esc_attr( $colors['bg'] ),
			esc_attr( $colors['text'] ),
			esc_html( $colors['label'] )
		);
	}
}

if ( ! function_exists( 'ngo_get_progress_percentage' ) ) {
	/**
	 * Calculate funding progress percentage
	 *
	 * @param float $raised Amount raised
	 * @param float $goal Total goal
	 * @return int Percentage (0-100)
	 */
	function ngo_get_progress_percentage( $raised, $goal ) {
		$raised = floatval( $raised );
		$goal   = floatval( $goal );

		if ( 0 === $goal ) {
			return 0;
		}

		$percentage = ( $raised / $goal ) * 100;
		return min( 100, absint( $percentage ) );
	}
}

if ( ! function_exists( 'ngo_format_beneficiaries' ) ) {
	/**
	 * Format beneficiaries count with proper spacing
	 *
	 * @param int $count Number of beneficiaries
	 * @return string Formatted count
	 */
	function ngo_format_beneficiaries( $count ) {
		$count = absint( $count );
		return number_format( $count ) . ' ' . _n( 'person', 'people', $count, 'ngo-impact-projects' );
	}
}

if ( ! function_exists( 'ngo_display_impact_stat' ) ) {
	/**
	 * Display impact statistic with icon and label
	 *
	 * @param int    $value The stat value
	 * @param string $icon Icon class or SVG
	 * @param string $label Stat label
	 * @return string HTML stat card
	 */
	function ngo_display_impact_stat( $value, $icon, $label ) {
		$value = absint( $value );
		$icon  = wp_kses_post( $icon );
		$label = esc_html( $label );

		return sprintf(
			'<div class="ngo-stat-card bg-white rounded-lg p-6 shadow-md text-center">\n\t\t\t\t<div class="ngo-stat-icon mb-4 text-4xl">%s</div>\n\t\t\t\t<div class="ngo-stat-value text-4xl font-bold text-green-600 mb-2">%s</div>\n\t\t\t\t<div class="ngo-stat-label text-gray-700">%s</div>\n\t\t\t</div>',
			wp_kses_post( $icon ),
			esc_html( number_format( $value ) ),
			$label
		);
	}
}

if ( ! function_exists( 'ngo_get_schema_markup' ) ) {
	/**
	 * Generate Schema.org JSON-LD markup for a project
	 *
	 * @param WP_Post $project Project post object
	 * @return string JSON-LD schema
	 */
	function ngo_get_schema_markup( $project ) {
		if ( ! isset( $project->ID ) ) {
			return '';
		}

		$project_id = absint( $project->ID );
		$status     = get_field( 'project_status', $project_id );
		$location   = get_field( 'project_location', $project_id );
		$start_date = get_field( 'project_start_date', $project_id );
		$end_date   = get_field( 'project_end_date', $project_id );

		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'Project',
			'name'          => get_the_title( $project_id ),
			'description'   => get_the_excerpt( $project_id ),
			'url'           => get_permalink( $project_id ),
			'image'         => get_the_post_thumbnail_url( $project_id ),
			'datePublished' => get_the_date( 'c', $project_id ),
		);

		if ( ! empty( $location ) ) {
			$schema['spatialCoverage'] = array(
				'@type' => 'Place',
				'name'  => $location,
			);
		}

		if ( ! empty( $start_date ) ) {
			$schema['startDate'] = $start_date;
		}

		if ( ! empty( $end_date ) ) {
			$schema['endDate'] = $end_date;
		}

		return '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
	}
}
