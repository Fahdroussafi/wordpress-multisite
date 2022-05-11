<?php
/**
 * Blog single customizer
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Blog single structure.
$wp_customize->add_setting(
	'woostify_setting[blog_single_structure]',
	array(
		'default'           => $defaults['blog_single_structure'],
		'sanitize_callback' => 'woostify_sanitize_array',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Sortable_Control(
		$wp_customize,
		'woostify_setting[blog_single_structure]',
		array(
			'label'    => __( 'Blog Single Structure', 'woostify' ),
			'section'  => 'woostify_blog_single',
			'settings' => 'woostify_setting[blog_single_structure]',
			'choices'  => apply_filters(
				'woostify_setting_blog_single_structure_choices',
				array(
					'image'      => __( 'Featured Image', 'woostify' ),
					'title-meta' => __( 'Title', 'woostify' ),
					'post-meta'  => __( 'Post Meta', 'woostify' ),
				)
			),
		)
	)
);

// Blog single post meta.
$wp_customize->add_setting(
	'woostify_setting[blog_single_post_meta]',
	array(
		'default'           => $defaults['blog_single_post_meta'],
		'sanitize_callback' => 'woostify_sanitize_array',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Sortable_Control(
		$wp_customize,
		'woostify_setting[blog_single_post_meta]',
		array(
			'label'    => __( 'Blog Single Post Meta', 'woostify' ),
			'section'  => 'woostify_blog_single',
			'settings' => 'woostify_setting[blog_single_post_meta]',
			'choices'  => apply_filters(
				'woostify_setting_blog_single_post_meta_choices',
				array(
					'date'     => __( 'Publish Date', 'woostify' ),
					'author'   => __( 'Author', 'woostify' ),
					'category' => __( 'Category', 'woostify' ),
					'comments' => __( 'Comments', 'woostify' ),
				)
			),
		)
	)
);

// Breadcrumb divider.
$wp_customize->add_setting(
	'blog_single_author_box_start',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'blog_single_author_box_start',
		array(
			'section'  => 'woostify_blog_single',
			'settings' => 'blog_single_author_box_start',
			'type'     => 'divider',
		)
	)
);

// Author box.
$wp_customize->add_setting(
	'woostify_setting[blog_single_author_box]',
	array(
		'type'              => 'option',
		'default'           => $defaults['blog_single_author_box'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[blog_single_author_box]',
		array(
			'label'    => __( 'Author Box', 'woostify' ),
			'section'  => 'woostify_blog_single',
			'settings' => 'woostify_setting[blog_single_author_box]',
		)
	)
);

// Related post.
$wp_customize->add_setting(
	'woostify_setting[blog_single_related_post]',
	array(
		'type'              => 'option',
		'default'           => $defaults['blog_single_related_post'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[blog_single_related_post]',
		array(
			'label'    => __( 'Related Post', 'woostify' ),
			'section'  => 'woostify_blog_single',
			'settings' => 'woostify_setting[blog_single_related_post]',
		)
	)
);
