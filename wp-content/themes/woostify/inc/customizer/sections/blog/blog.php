<?php
/**
 * Blog customizer
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Blog layout.
$wp_customize->add_setting(
	'woostify_setting[blog_list_layout]',
	array(
		'sanitize_callback' => 'woostify_sanitize_choices',
		'default'           => $defaults['blog_list_layout'],
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Radio_Image_Control(
		$wp_customize,
		'woostify_setting[blog_list_layout]',
		array(
			'section'  => 'woostify_blog',
			'settings' => 'woostify_setting[blog_list_layout]',
			'label'    => __( 'Blog Layout', 'woostify' ),
			'choices'  => apply_filters(
				'woostify_setting_blog_list_layout_choices',
				array(
					'standard' => WOOSTIFY_THEME_URI . 'assets/images/customizer/blog/standard.jpg',
					'list'     => WOOSTIFY_THEME_URI . 'assets/images/customizer/blog/list.jpg',
					'grid'     => WOOSTIFY_THEME_URI . 'assets/images/customizer/blog/grid.jpg',
					'zigzag'   => WOOSTIFY_THEME_URI . 'assets/images/customizer/blog/zigzag.jpg',
				)
			),
		)
	)
);

// Limit exerpt.
$wp_customize->add_setting(
	'woostify_setting[blog_list_limit_exerpt]',
	array(
		'sanitize_callback' => 'absint',
		'default'           => $defaults['blog_list_limit_exerpt'],
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[blog_list_limit_exerpt]',
		array(
			'section'  => 'woostify_blog',
			'settings' => 'woostify_setting[blog_list_limit_exerpt]',
			'type'     => 'number',
			'label'    => __( 'Limit Excerpt', 'woostify' ),
		)
	)
);

// End section one divider.
$wp_customize->add_setting(
	'blog_list_section_one_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'blog_list_section_one_divider',
		array(
			'section'  => 'woostify_blog',
			'settings' => 'blog_list_section_one_divider',
			'type'     => 'divider',
		)
	)
);

// Blog list structure.
$wp_customize->add_setting(
	'woostify_setting[blog_list_structure]',
	array(
		'default'           => $defaults['blog_list_structure'],
		'sanitize_callback' => 'woostify_sanitize_array',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Sortable_Control(
		$wp_customize,
		'woostify_setting[blog_list_structure]',
		array(
			'label'    => __( 'Blog List Structure', 'woostify' ),
			'section'  => 'woostify_blog',
			'settings' => 'woostify_setting[blog_list_structure]',
			'choices'  => apply_filters(
				'woostify_setting_blog_list_structure_choices',
				array(
					'image'      => __( 'Featured Image', 'woostify' ),
					'title-meta' => __( 'Title', 'woostify' ),
					'post-meta'  => __( 'Post Meta', 'woostify' ),
				)
			),
		)
	)
);

// Blog list post meta.
$wp_customize->add_setting(
	'woostify_setting[blog_list_post_meta]',
	array(
		'default'           => $defaults['blog_list_post_meta'],
		'sanitize_callback' => 'woostify_sanitize_array',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Sortable_Control(
		$wp_customize,
		'woostify_setting[blog_list_post_meta]',
		array(
			'label'    => __( 'Blog Post Meta', 'woostify' ),
			'section'  => 'woostify_blog',
			'settings' => 'woostify_setting[blog_list_post_meta]',
			'choices'  => apply_filters(
				'woostify_setting_blog_list_post_meta_choices',
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
