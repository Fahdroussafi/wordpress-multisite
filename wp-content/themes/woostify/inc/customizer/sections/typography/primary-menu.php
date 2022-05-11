<?php
/**
 * Primary menu typography
 *
 * @package woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Default values.
$defaults = woostify_options();

// menu font family.
$wp_customize->add_setting(
	'woostify_setting[menu_font_family]',
	array(
		'default'           => $defaults['menu_font_family'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

// menu font category.
$wp_customize->add_setting(
	'menu_font_category',
	array(
		'default'           => $defaults['menu_font_category'],
		'sanitize_callback' => 'sanitize_text_field',
	)
);

// font font variants.
$wp_customize->add_setting(
	'menu_font_family_variants',
	array(
		'default'           => $defaults['menu_font_family_variants'],
		'sanitize_callback' => 'woostify_sanitize_variants',
	)
);

// menu font weight.
$wp_customize->add_setting(
	'woostify_setting[menu_font_weight]',
	array(
		'default'           => $defaults['menu_font_weight'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);

// menu text transform.
$wp_customize->add_setting(
	'woostify_setting[menu_font_transform]',
	array(
		'default'           => $defaults['menu_font_transform'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);

// add control for menu typography.
$wp_customize->add_control(
	new Woostify_Typography_Control(
		$wp_customize,
		'menu_typography',
		array(
			'section'  => 'menu_font_section',
			'label'    => __( 'Menu Font', 'woostify' ),
			'settings' => array(
				'family'    => 'woostify_setting[menu_font_family]',
				'variant'   => 'menu_font_family_variants',
				'category'  => 'menu_font_category',
				'weight'    => 'woostify_setting[menu_font_weight]',
				'transform' => 'woostify_setting[menu_font_transform]',
			),
		)
	)
);

// Parent menu divider.
$wp_customize->add_setting(
	'parent_menu_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'parent_menu_divider',
		array(
			'section'  => 'menu_font_section',
			'settings' => 'parent_menu_divider',
			'type'     => 'divider',
		)
	)
);

// CUSTOM HEADING.
$wp_customize->add_setting(
	'parent_menu_title',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'parent_menu_title',
		array(
			'label'    => __( 'Parent Menu', 'woostify' ),
			'section'  => 'menu_font_section',
			'settings' => 'parent_menu_title',
			'type'     => 'hidden',
		)
	)
);

// parent menu font size.
$wp_customize->add_setting(
	'woostify_setting[parent_menu_font_size]',
	array(
		'default'           => $defaults['parent_menu_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[parent_menu_font_size]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'Font Size', 'woostify' ),
			'section'     => 'menu_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[parent_menu_font_size]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_parent_menu_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_parent_menu_font_size_max_step', 60 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// parent menu line height.
$wp_customize->add_setting(
	'woostify_setting[parent_menu_line_height]',
	array(
		'default'           => $defaults['parent_menu_line_height'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[parent_menu_line_height]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'Line Height', 'woostify' ),
			'section'     => 'menu_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[parent_menu_line_height]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_parent_menu_line_height_min_step', 10 ),
					'max'  => apply_filters( 'woostify_parent_menu_line_height_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// Primary parent menu color.
$wp_customize->add_setting(
	'woostify_setting[primary_menu_color]',
	array(
		'default'           => $defaults['primary_menu_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[primary_menu_color]',
		array(
			'label'    => __( 'Parent Menu Color', 'woostify' ),
			'section'  => 'menu_font_section',
			'settings' => array(
				'woostify_setting[primary_menu_color]',
			),
		)
	)
);

// Submenu divider.
$wp_customize->add_setting(
	'sub_menu_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'sub_menu_divider',
		array(
			'section'  => 'menu_font_section',
			'settings' => 'sub_menu_divider',
			'type'     => 'divider',
		)
	)
);

// CUSTOM HEADING.
$wp_customize->add_setting(
	'sub_menu_title',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'sub_menu_title',
		array(
			'label'    => __( 'Sub Menu', 'woostify' ),
			'section'  => 'menu_font_section',
			'settings' => 'sub_menu_title',
			'type'     => 'hidden',
		)
	)
);

// sub menu font size.
$wp_customize->add_setting(
	'woostify_setting[sub_menu_font_size]',
	array(
		'default'           => $defaults['sub_menu_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[sub_menu_font_size]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'Font Size', 'woostify' ),
			'section'     => 'menu_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[sub_menu_font_size]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_sub_menu_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_sub_menu_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// sub menu line height.
$wp_customize->add_setting(
	'woostify_setting[sub_menu_line_height]',
	array(
		'default'           => $defaults['sub_menu_line_height'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[sub_menu_line_height]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'Line Height', 'woostify' ),
			'section'     => 'menu_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[sub_menu_line_height]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_sub_menu_line_height_min_step', 10 ),
					'max'  => apply_filters( 'woostify_sub_menu_line_height_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// Primary sub menu color.
$wp_customize->add_setting(
	'woostify_setting[primary_sub_menu_color]',
	array(
		'default'           => $defaults['primary_sub_menu_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[primary_sub_menu_color]',
		array(
			'label'    => __( 'Sub-menu Color', 'woostify' ),
			'section'  => 'menu_font_section',
			'settings' => array(
				'woostify_setting[primary_sub_menu_color]',
			),
		)
	)
);
