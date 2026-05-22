<?php
/**
 * NGO Settings Page Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have permission to access this page.', 'ngo-impact-projects' ) );
}

$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
?>

<div class="wrap">
	<h1><?php esc_html_e( 'NGO Projects Settings', 'ngo-impact-projects' ); ?></h1>

	<div class="nav-tab-wrapper">
		<a href="?page=ngo-settings&tab=general" class="nav-tab <?php echo 'general' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'General', 'ngo-impact-projects' ); ?>
		</a>
		<a href="?page=ngo-settings&tab=cta" class="nav-tab <?php echo 'cta' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Call to Action', 'ngo-impact-projects' ); ?>
		</a>
		<a href="?page=ngo-settings&tab=styles" class="nav-tab <?php echo 'styles' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Styles', 'ngo-impact-projects' ); ?>
		</a>
		<a href="?page=ngo-settings&tab=advanced" class="nav-tab <?php echo 'advanced' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Advanced', 'ngo-impact-projects' ); ?>
		</a>
	</div>

	<div class="tab-content">
		<?php if ( 'general' === $active_tab ) : ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'ngo_general_settings' ); ?>
				
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ngo_default_status"><?php esc_html_e( 'Default Status Filter', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<select id="ngo_default_status" name="ngo_default_status">
								<option value="all" <?php selected( get_option( 'ngo_default_status' ), 'all' ); ?>><?php esc_html_e( 'All', 'ngo-impact-projects' ); ?></option>
								<option value="ongoing" <?php selected( get_option( 'ngo_default_status' ), 'ongoing' ); ?>><?php esc_html_e( 'Ongoing', 'ngo-impact-projects' ); ?></option>
								<option value="completed" <?php selected( get_option( 'ngo_default_status' ), 'completed' ); ?>><?php esc_html_e( 'Completed', 'ngo-impact-projects' ); ?></option>
								<option value="upcoming" <?php selected( get_option( 'ngo_default_status' ), 'upcoming' ); ?>><?php esc_html_e( 'Upcoming', 'ngo-impact-projects' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ngo_default_columns"><?php esc_html_e( 'Default Grid Columns', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<select id="ngo_default_columns" name="ngo_default_columns">
								<option value="2" <?php selected( get_option( 'ngo_default_columns' ), '2' ); ?>>2</option>
								<option value="3" <?php selected( get_option( 'ngo_default_columns' ), '3' ); ?>>3</option>
								<option value="4" <?php selected( get_option( 'ngo_default_columns' ), '4' ); ?>>4</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ngo_items_per_page"><?php esc_html_e( 'Items Per Page', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<input type="number" id="ngo_items_per_page" name="ngo_items_per_page" value="<?php echo esc_attr( get_option( 'ngo_items_per_page', 9 ) ); ?>" min="1" max="50" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ngo_enable_ajax"><?php esc_html_e( 'Enable AJAX Features', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<input type="checkbox" id="ngo_enable_ajax" name="ngo_enable_ajax" value="1" <?php checked( get_option( 'ngo_enable_ajax' ), 1 ); ?> />
							<label for="ngo_enable_ajax"><?php esc_html_e( 'Enable filtering, searching, and load more via AJAX', 'ngo-impact-projects' ); ?></label>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		<?php endif; ?>

		<?php if ( 'cta' === $active_tab ) : ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'ngo_cta_settings' ); ?>
				
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ngo_global_volunteer_url"><?php esc_html_e( 'Global Volunteer URL', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<input type="url" id="ngo_global_volunteer_url" name="ngo_global_volunteer_url" value="<?php echo esc_attr( get_option( 'ngo_global_volunteer_url' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Used as fallback when project-specific URL is empty', 'ngo-impact-projects' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ngo_global_donate_url"><?php esc_html_e( 'Global Donate URL', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<input type="url" id="ngo_global_donate_url" name="ngo_global_donate_url" value="<?php echo esc_attr( get_option( 'ngo_global_donate_url' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Used as fallback when project-specific URL is empty', 'ngo-impact-projects' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ngo_global_contact_url"><?php esc_html_e( 'Global Contact URL', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<input type="url" id="ngo_global_contact_url" name="ngo_global_contact_url" value="<?php echo esc_attr( get_option( 'ngo_global_contact_url' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Used as fallback when project-specific URL is empty', 'ngo-impact-projects' ); ?></p>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		<?php endif; ?>

		<?php if ( 'styles' === $active_tab ) : ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'ngo_style_settings' ); ?>
				
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ngo_primary_color"><?php esc_html_e( 'Primary Color', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<input type="text" id="ngo_primary_color" name="ngo_primary_color" value="<?php echo esc_attr( get_option( 'ngo_primary_color', '#16a34a' ) ); ?>" class="ngo-color-picker" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ngo_accent_color"><?php esc_html_e( 'Accent Color', 'ngo-impact-projects' ); ?></label></th>
						<td>
							<input type="text" id="ngo_accent_color" name="ngo_accent_color" value="<?php echo esc_attr( get_option( 'ngo_accent_color', '#f0fdf4' ) ); ?>" class="ngo-color-picker" />
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		<?php endif; ?>

		<?php if ( 'advanced' === $active_tab ) : ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'ngo_advanced_settings' ); ?>
				
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Cache Management', 'ngo-impact-projects' ); ?></th>
						<td>
							<button type="button" id="ngo_clear_cache" class="button"><?php esc_html_e( 'Clear Cache', 'ngo-impact-projects' ); ?></button>
							<p class="description"><?php esc_html_e( 'Click to clear plugin cache', 'ngo-impact-projects' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Reset All Settings', 'ngo-impact-projects' ); ?></th>
						<td>
							<button type="button" id="ngo_reset_settings" class="button button-secondary" onclick="if(confirm('<?php esc_attr_e( 'Are you sure you want to reset all settings? This cannot be undone.', 'ngo-impact-projects' ); ?>')) { location.href = '<?php echo esc_url( wp_nonce_url( add_query_arg( 'ngo_action', 'reset_all' ), 'ngo_reset_nonce' ) ); ?>' }">
								<?php esc_html_e( 'Reset All', 'ngo-impact-projects' ); ?>
							</button>
							<p class="description"><?php esc_html_e( 'Reset plugin settings to defaults (data is not deleted)', 'ngo-impact-projects' ); ?></p>
						</td>
					</tr>
				</table>
			</form>
		<?php endif; ?>
	</div>
</div>

<style>
	.tab-content {
		background: white;
		padding: 20px;
		margin-top: 20px;
		border-radius: 4px;
	}
</style>
