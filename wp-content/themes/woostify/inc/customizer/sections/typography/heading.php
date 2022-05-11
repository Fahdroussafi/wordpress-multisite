<?php
/**
 * Heading typography
 *
 * @package woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Default values.
$defaults = woostify_options();

// Heading font family.
$wp_customize->add_setting(
	'woostify_setting[heading_font_family]',
	array(
		'default'           => $defaults['heading_font_family'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

// Heading font family.
$wp_customize->add_setting(
	'heading_font_category',
	array(
		'default'           => $defaults['heading_font_category'],
		'sanitize_callback' => 'sanitize_text_field',
		'type'              => 'option',
	)
);

// Heading font variants.
$wp_customize->add_setting(
	'heading_font_family_variants',
	array(
		'default'           => $defaults['heading_font_family_variants'],
		'sanitize_callback' => 'woostify_sanitize_variants',
		'type'              => 'option',
	)
);

// Heading font weight.
$wp_customize->add_setting(
	'woostify_setting[heading_font_weight]',
	array(
		'default'           => $defaults['heading_font_weight'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);

// Heading text transform.
$wp_customize->add_setting(
	'woostify_setting[heading_font_transform]',
	array(
		'default'           => $defaults['heading_font_transform'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);

// Generate options.
$wp_customize->add_control(
	new Woostify_Typography_Control(
		$wp_customize,
		'heading_typography',
		array(
			'section'  => 'heading_font_section',
			'label'    => __( 'Heading Font', 'woostify' ),
			'settings' => array(
				'family'    => 'woostify_setting[heading_font_family]',
				'variant'   => 'heading_font_family_variants',
				'category'  => 'heading_font_category',
				'weight'    => 'woostify_setting[heading_font_weight]',
				'transform' => 'woostify_setting[heading_font_transform]',
			),
		)
	)
);

// heading line height.
$wp_customize->add_setting(
	'woostify_setting[heading_line_height]',
	array(
		'default'           => $defaults['heading_line_height'],
		'sanitize_callback' => 'sanitize_text_field',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[heading_line_height]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'Line Height', 'woostify' ),
			'section'     => 'heading_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[heading_line_height]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_heading_line_height_min_step', 0 ),
					'max'  => apply_filters( 'woostify_heading_line_height_max_step', 20 ),
					'step' => 1,
					'edit' => true,
					'unit' => '',
				),
			),
		)
	)
);

// Heading color.
$wp_customize->add_setting(
	'woostify_setting[heading_color]',
	array(
		'default'           => $defaults['heading_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[heading_color]',
		array(
			'label'    => __( 'Heading Color', 'woostify' ),
			'section'  => 'heading_font_section',
			'settings' => array(
				'woostify_setting[heading_color]',
			),
		)
	)
);

// Heading font size divider.
$wp_customize->add_setting(
	'heading_font_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'heading_font_divider',
		array(
			'section'  => 'heading_font_section',
			'settings' => 'heading_font_divider',
			'type'     => 'divider',
		)
	)
);

// Heading font size title.
$wp_customize->add_setting(
	'heading_font_size_title',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'heading_font_size_title',
		array(
			'label'    => __( 'Font Size', 'woostify' ),
			'section'  => 'heading_font_section',
			'settings' => 'heading_font_size_title',
			'type'     => 'hidden',
		)
	)
);

// h1.
$wp_customize->add_setting(
	'woostify_setting[heading_h1_font_size]',
	array(
		'default'           => $defaults['heading_h1_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[heading_h1_font_size]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'H1', 'woostify' ),
			'section'     => 'heading_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[heading_h1_font_size]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_heading_h1_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_heading_h1_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// h2.
$wp_customize->add_setting(
	'woostify_setting[heading_h2_font_size]',
	array(
		'default'           => $defaults['heading_h2_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[heading_h2_font_size]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'H2', 'woostify' ),
			'section'     => 'heading_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[heading_h2_font_size]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_heading_h2_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_heading_h2_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// h3.
$wp_customize->add_setting(
	'woostify_setting[heading_h3_font_size]',
	array(
		'default'           => $defaults['heading_h3_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[heading_h3_font_size]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'H3', 'woostify' ),
			'section'     => 'heading_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[heading_h3_font_size]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_heading_h3_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_heading_h3_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// h4.
$wp_customize->add_setting(
	'woostify_setting[heading_h4_font_size]',
	array(
		'default'           => $defaults['heading_h4_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[heading_h4_font_size]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'H4', 'woostify' ),
			'section'     => 'heading_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[heading_h4_font_size]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_heading_h4_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_heading_h4_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// h5.
$wp_customize->add_setting(
	'woostify_setting[heading_h5_font_size]',
	array(
		'default'           => $defaults['heading_h5_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[heading_h5_font_size]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'H5', 'woostify' ),
			'section'     => 'heading_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[heading_h5_font_size]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_heading_h5_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_heading_h5_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// h6.
$wp_customize->add_setting(
	'woostify_setting[heading_h6_font_size]',
	array(
		'default'           => $defaults['heading_h6_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[heading_h6_font_size]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'H6', 'woostify' ),
			'section'     => 'heading_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[heading_h6_font_size]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_heading_h6_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_heading_h6_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);
