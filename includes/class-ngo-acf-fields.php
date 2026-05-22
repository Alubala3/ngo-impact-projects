<?php
/**
 * NGO ACF Fields Class
 * Registers all ACF field groups for the plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NGO_ACF_Fields {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'acf/init', array( $this, 'register_field_groups' ) );
	}

	/**
	 * Register all ACF field groups
	 */
	public function register_field_groups() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		// Field Group 1: Project Details
		acf_add_local_field_group(
			array(
				'key'                   => 'group_ngo_project_details',
				'title'                 => esc_html__( 'Project Details', 'ngo-impact-projects' ),
				'fields'                => array(
					array(
						'key'           => 'field_ngo_project_status',
						'label'         => esc_html__( 'Project Status', 'ngo-impact-projects' ),
						'name'          => 'project_status',
						'type'          => 'select',
						'choices'       => array(
							'ongoing'   => esc_html__( 'Ongoing', 'ngo-impact-projects' ),
							'completed' => esc_html__( 'Completed', 'ngo-impact-projects' ),
							'upcoming'  => esc_html__( 'Upcoming', 'ngo-impact-projects' ),
						),
						'required'      => 1,
						'return_format' => 'value',
					),
					array(
						'key'   => 'field_ngo_project_location',
						'label' => esc_html__( 'Project Location', 'ngo-impact-projects' ),
						'name'  => 'project_location',
						'type'  => 'text',
					),
					array(
						'key'             => 'field_ngo_project_start_date',
						'label'           => esc_html__( 'Start Date', 'ngo-impact-projects' ),
						'name'            => 'project_start_date',
						'type'            => 'date_picker',
						'display_format'  => 'F j, Y',
						'return_format'   => 'F j, Y',
						'required'        => 1,
					),
					array(
						'key'             => 'field_ngo_project_end_date',
						'label'           => esc_html__( 'End Date', 'ngo-impact-projects' ),
						'name'            => 'project_end_date',
						'type'            => 'date_picker',
						'display_format'  => 'F j, Y',
						'return_format'   => 'F j, Y',
						'required'        => 0,
					),
					array(
						'key'   => 'field_ngo_project_progress',
						'label' => esc_html__( 'Progress (%)', 'ngo-impact-projects' ),
						'name'  => 'project_progress',
						'type'  => 'number',
						'min'   => 0,
						'max'   => 100,
					),
					array(
						'key'   => 'field_ngo_project_funding_goal',
						'label' => esc_html__( 'Funding Goal', 'ngo-impact-projects' ),
						'name'  => 'project_funding_goal',
						'type'  => 'number',
						'min'   => 0,
					),
					array(
						'key'   => 'field_ngo_project_funding_raised',
						'label' => esc_html__( 'Funding Raised', 'ngo-impact-projects' ),
						'name'  => 'project_funding_raised',
						'type'  => 'number',
						'min'   => 0,
					),
					array(
						'key'   => 'field_ngo_beneficiaries_count',
						'label' => esc_html__( 'Beneficiaries Count', 'ngo-impact-projects' ),
						'name'  => 'beneficiaries_count',
						'type'  => 'number',
						'min'   => 0,
					),
					array(
						'key'           => 'field_ngo_logo',
						'label'         => esc_html__( 'NGO Logo', 'ngo-impact-projects' ),
						'name'          => 'ngo_logo',
						'type'          => 'image',
						'return_format' => 'id',
						'preview_size'  => 'medium',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'ngo_project',
						),
					),
				),
				'position'              => 'after_title',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
			)
		);

		// Field Group 2: Impact Statistics
		acf_add_local_field_group(
			array(
				'key'                   => 'group_ngo_impact_stats',
				'title'                 => esc_html__( 'Impact Statistics', 'ngo-impact-projects' ),
				'fields'                => array(
					array(
						'key'   => 'field_ngo_people_helped',
						'label' => esc_html__( 'People Helped', 'ngo-impact-projects' ),
						'name'  => 'people_helped',
						'type'  => 'number',
						'min'   => 0,
					),
					array(
						'key'   => 'field_ngo_children_supported',
						'label' => esc_html__( 'Children Supported', 'ngo-impact-projects' ),
						'name'  => 'children_supported',
						'type'  => 'number',
						'min'   => 0,
					),
					array(
						'key'   => 'field_ngo_trees_planted',
						'label' => esc_html__( 'Trees Planted', 'ngo-impact-projects' ),
						'name'  => 'trees_planted',
						'type'  => 'number',
						'min'   => 0,
					),
					array(
						'key'   => 'field_ngo_schools_renovated',
						'label' => esc_html__( 'Schools Renovated', 'ngo-impact-projects' ),
						'name'  => 'schools_renovated',
						'type'  => 'number',
						'min'   => 0,
					),
					array(
						'key'   => 'field_ngo_women_empowered',
						'label' => esc_html__( 'Women Empowered', 'ngo-impact-projects' ),
						'name'  => 'women_empowered',
						'type'  => 'number',
						'min'   => 0,
					),
					array(
						'key'   => 'field_ngo_water_wells_built',
						'label' => esc_html__( 'Water Wells Built', 'ngo-impact-projects' ),
						'name'  => 'water_wells_built',
						'type'  => 'number',
						'min'   => 0,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'ngo_project',
						),
					),
				),
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
			)
		);

		// Field Group 3: Story & Content
		acf_add_local_field_group(
			array(
				'key'                   => 'group_ngo_story_content',
				'title'                 => esc_html__( 'Story & Content', 'ngo-impact-projects' ),
				'fields'                => array(
					array(
						'key'   => 'field_ngo_project_objectives',
						'label' => esc_html__( 'Project Objectives', 'ngo-impact-projects' ),
						'name'  => 'project_objectives',
						'type'  => 'wysiwyg',
					),
					array(
						'key'   => 'field_ngo_project_challenges',
						'label' => esc_html__( 'Project Challenges', 'ngo-impact-projects' ),
						'name'  => 'project_challenges',
						'type'  => 'wysiwyg',
					),
					array(
						'key'   => 'field_ngo_success_stories',
						'label' => esc_html__( 'Success Stories', 'ngo-impact-projects' ),
						'name'  => 'success_stories',
						'type'  => 'wysiwyg',
					),
					array(
						'key'   => 'field_ngo_community_impact',
						'label' => esc_html__( 'Community Impact', 'ngo-impact-projects' ),
						'name'  => 'community_impact',
						'type'  => 'wysiwyg',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'ngo_project',
						),
					),
				),
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
			)
		);

		// Field Group 4: Gallery
		acf_add_local_field_group(
			array(
				'key'                   => 'group_ngo_gallery',
				'title'                 => esc_html__( 'Gallery', 'ngo-impact-projects' ),
				'fields'                => array(
					array(
						'key'           => 'field_ngo_project_gallery',
						'label'         => esc_html__( 'Project Gallery', 'ngo-impact-projects' ),
						'name'          => 'project_gallery',
						'type'          => 'gallery',
						'return_format' => 'id',
					),
					array(
						'key'   => 'field_ngo_project_video_url',
						'label' => esc_html__( 'Project Video URL', 'ngo-impact-projects' ),
						'name'  => 'project_video_url',
						'type'  => 'url',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'ngo_project',
						),
					),
				),
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
			)
		);

		// Field Group 5: Timeline
		acf_add_local_field_group(
			array(
				'key'                   => 'group_ngo_timeline',
				'title'                 => esc_html__( 'Timeline', 'ngo-impact-projects' ),
				'fields'                => array(
					array(
						'key'               => 'field_ngo_project_milestones',
						'label'             => esc_html__( 'Project Milestones', 'ngo-impact-projects' ),
						'name'              => 'project_milestones',
						'type'              => 'repeater',
						'layout'            => 'table',
						'add_button_label'  => esc_html__( 'Add Milestone', 'ngo-impact-projects' ),
						'sub_fields'        => array(
							array(
								'key'             => 'field_ngo_milestone_date',
								'label'           => esc_html__( 'Date', 'ngo-impact-projects' ),
								'name'            => 'milestone_date',
								'type'            => 'date_picker',
								'display_format'  => 'F j, Y',
								'return_format'   => 'F j, Y',
							),
							array(
								'key'   => 'field_ngo_milestone_title',
								'label' => esc_html__( 'Title', 'ngo-impact-projects' ),
								'name'  => 'milestone_title',
								'type'  => 'text',
							),
							array(
								'key'   => 'field_ngo_milestone_description',
								'label' => esc_html__( 'Description', 'ngo-impact-projects' ),
								'name'  => 'milestone_description',
								'type'  => 'textarea',
							),
						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'ngo_project',
						),
					),
				),
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
			)
		);

		// Field Group 6: Call to Action
		acf_add_local_field_group(
			array(
				'key'                   => 'group_ngo_cta',
				'title'                 => esc_html__( 'Call to Action', 'ngo-impact-projects' ),
				'fields'                => array(
					array(
						'key'   => 'field_ngo_volunteer_url',
						'label' => esc_html__( 'Volunteer URL', 'ngo-impact-projects' ),
						'name'  => 'volunteer_url',
						'type'  => 'url',
					),
					array(
						'key'   => 'field_ngo_donate_url',
						'label' => esc_html__( 'Donate URL', 'ngo-impact-projects' ),
						'name'  => 'donate_url',
						'type'  => 'url',
					),
					array(
						'key'   => 'field_ngo_contact_url',
						'label' => esc_html__( 'Contact URL', 'ngo-impact-projects' ),
						'name'  => 'contact_url',
						'type'  => 'url',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'ngo_project',
						),
					),
				),
				'position'              => 'side',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
			)
		);
	}
}
