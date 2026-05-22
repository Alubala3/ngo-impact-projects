<?php
/**
 * NGO Admin Dashboard Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php esc_html_e( 'NGO Projects Dashboard', 'ngo-impact-projects' ); ?></h1>

	<div class="ngo-dashboard-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
		
		<div class="ngo-stat-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
			<h3 style="color: #666; margin: 0; font-size: 14px; text-transform: uppercase;"><?php esc_html_e( 'Total Projects', 'ngo-impact-projects' ); ?></h3>
			<div style="font-size: 36px; font-weight: bold; color: #16a34a; margin-top: 10px;">
				<?php echo esc_html( $total_projects ); ?>
			</div>
		</div>

		<div class="ngo-stat-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
			<h3 style="color: #666; margin: 0; font-size: 14px; text-transform: uppercase;"><?php esc_html_e( 'Ongoing Projects', 'ngo-impact-projects' ); ?></h3>
			<div style="font-size: 36px; font-weight: bold; color: #16a34a; margin-top: 10px;">
				<?php echo esc_html( $ongoing_count ); ?>
			</div>
		</div>

		<div class="ngo-stat-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
			<h3 style="color: #666; margin: 0; font-size: 14px; text-transform: uppercase;"><?php esc_html_e( 'Completed Projects', 'ngo-impact-projects' ); ?></h3>
			<div style="font-size: 36px; font-weight: bold; color: #6b7280; margin-top: 10px;">
				<?php echo esc_html( $completed_count ); ?>
			</div>
		</div>

		<div class="ngo-stat-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
			<h3 style="color: #666; margin: 0; font-size: 14px; text-transform: uppercase;"><?php esc_html_e( 'Upcoming Projects', 'ngo-impact-projects' ); ?></h3>
			<div style="font-size: 36px; font-weight: bold; color: #3b82f6; margin-top: 10px;">
				<?php echo esc_html( $upcoming_count ); ?>
			</div>
		</div>

		<div class="ngo-stat-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
			<h3 style="color: #666; margin: 0; font-size: 14px; text-transform: uppercase;"><?php esc_html_e( 'Total Beneficiaries', 'ngo-impact-projects' ); ?></h3>
			<div style="font-size: 36px; font-weight: bold; color: #16a34a; margin-top: 10px;">
				<?php echo esc_html( number_format( $total_beneficiaries ) ); ?>
			</div>
		</div>

	</div>

	<div style="margin-top: 30px;">
		<h2><?php esc_html_e( 'Quick Actions', 'ngo-impact-projects' ); ?></h2>
		<p>
			<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=ngo_project' ) ); ?>" class="button button-primary" style="margin-right: 10px;">
				<?php esc_html_e( 'Add New Project', 'ngo-impact-projects' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ngo_project' ) ); ?>" class="button" style="margin-right: 10px;">
				<?php esc_html_e( 'View All Projects', 'ngo-impact-projects' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ngo_category&post_type=ngo_project' ) ); ?>" class="button">
				<?php esc_html_e( 'Manage Categories', 'ngo-impact-projects' ); ?>
			</a>
		</p>
	</div>
</div>
