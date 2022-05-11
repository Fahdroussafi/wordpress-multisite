<?php
/**
 * Body typography
 *
 * @package woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Default values.
$defaults = woostify_options();

// body font family.
$wp_customize->add_setting(
	'woostify_setting[body_font_family]',
	array(
		'default'           => $defaults['body_font_family'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

// body font category.
$wp_customize->add_setting(
	'body_font_category',
	array(
		'default'           => $defaults['body_font_category'],
		'sanitize_callback' => 'sanitize_text_field',
		'type'              => 'option',
	)
);

// font font variants.
$wp_customize->add_setting(
	'body_font_family_variants',
	array(
		'default'           => $defaults['body_font_family_variants'],
		'sanitize_callback' => 'woostify_sanitize_variants',
		'type'              => 'option',
	)
);

// body font weight.
$wp_customize->add_setting(
	'woostify_setting[body_font_weight]',
	array(
		'default'           => $defaults['body_font_weight'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);

// body text transform.
$wp_customize->add_setting(
	'woostify_setting[body_font_transform]',
	array(
		'default'           => $defaults['body_font_transform'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);

// add control for body typography.
$wp_customize->add_control(
	new Woostify_Typography_Control(
		$wp_customize,
		'body_typography',
		array(
			'section'  => 'body_font_section',
			'label'    => __( 'Body Font', 'woostify' ),
			'settings' => array(
				'family'    => 'woostify_setting[body_font_family]',
				'variant'   => 'body_font_family_variants',
				'category'  => 'body_font_category',
				'weight'    => 'woostify_setting[body_font_weight]',
				'transform' => 'woostify_setting[body_font_transform]',
			),
		)
	)
);

// body font size.
$wp_customize->add_setting(
	'woostify_setting[body_font_size]',
	array(
		'default'           => $defaults['body_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[body_font_size]',
		array(
			'type'     => 'woostify-range-slider',
			'label'    => __( 'Font Size', 'woostify' ),
			'section'  => 'body_font_section',
			'settings' => array(
				'desktop' => 'woostify_setting[body_font_size]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_body_font_size_min_step', 5 ),
					'max'  => apply_filters( 'woostify_body_font_size_max_step', 60 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);

// body line height.
$wp_customize->add_setting(
	'woostify_setting[body_line_height]',
	array(
		'default'           => $defaults['body_line_height'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[body_line_height]',
		array(
			'type'        => 'woostify-range-slider',
			'description' => __( 'Line Height', 'woostify' ),
			'section'     => 'body_font_section',
			'settings'    => array(
				'desktop' => 'woostify_setting[body_line_height]',
			),
			'choices'     => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_body_line_height_min_step', 10 ),
					'max'  => apply_filters( 'woostify_body_line_height_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);
