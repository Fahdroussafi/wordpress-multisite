<?php
/**
 * Color customizer
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Theme color.
$wp_customize->add_setting(
	'woostify_setting[theme_color]',
	array(
		'default'           => $defaults['theme_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[theme_color]',
		array(
			'label'           => __( 'Theme Color', 'woostify' ),
			'section'         => 'woostify_color',
			'settings'        => array(
				'woostify_setting[theme_color]',
			),
			'enable_swatches' => false,
			'is_global_color' => true,
		)
	)
);

// Text Color.
$wp_customize->add_setting(
	'woostify_setting[text_color]',
	array(
		'default'           => $defaults['text_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[text_color]',
		array(
			'label'           => __( 'Text Color', 'woostify' ),
			'section'         => 'woostify_color',
			'settings'        => array(
				'woostify_setting[text_color]',
			),
			'enable_swatches' => false,
			'is_global_color' => true,
		)
	)
);

// Accent Color.
$wp_customize->add_setting(
	'woostify_setting[accent_color]',
	array(
		'default'           => $defaults['accent_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[accent_color]',
		array(
			'label'           => __( 'Link / Accent Color', 'woostify' ),
			'section'         => 'woostify_color',
			'settings'        => array(
				'woostify_setting[accent_color]',
			),
			'enable_swatches' => false,
			'is_global_color' => true,
		)
	)
);

// Link Hover Color.
$wp_customize->add_setting(
	'woostify_setting[link_hover_color]',
	array(
		'default'           => $defaults['link_hover_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[link_hover_color]',
		array(
			'label'           => __( 'Link Hover Color', 'woostify' ),
			'section'         => 'woostify_color',
			'settings'        => array(
				'woostify_setting[link_hover_color]',
			),
			'enable_swatches' => false,
			'is_global_color' => true,
		)
	)
);

// Extra Color 1.
$wp_customize->add_setting(
	'woostify_setting[extra_color_1]',
	array(
		'default'           => $defaults['extra_color_1'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[extra_color_1]',
		array(
			'label'           => __( 'Extra Color 1', 'woostify' ),
			'section'         => 'woostify_color',
			'settings'        => array(
				'woostify_setting[extra_color_1]',
			),
			'enable_swatches' => false,
			'is_global_color' => true,
		)
	)
);

// Extra Color 2.
$wp_customize->add_setting(
	'woostify_setting[extra_color_2]',
	array(
		'default'           => $defaults['extra_color_2'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[extra_color_2]',
		array(
			'label'           => __( 'Extra Color 2', 'woostify' ),
			'section'         => 'woostify_color',
			'settings'        => array(
				'woostify_setting[extra_color_2]',
			),
			'enable_swatches' => false,
			'is_global_color' => true,
		)
	)
);
