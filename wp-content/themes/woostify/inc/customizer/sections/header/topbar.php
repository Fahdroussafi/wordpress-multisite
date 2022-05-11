<?php
/**
 * Topbar
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Tabs.
$wp_customize->add_setting(
	'woostify_setting[topbar_context_tabs]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Woostify_Tabs_Control(
		$wp_customize,
		'woostify_setting[topbar_context_tabs]',
		array(
			'section'  => 'woostify_topbar',
			'settings' => 'woostify_setting[topbar_context_tabs]',
			'choices'  => array(
				'general' => __( 'General', 'woostify' ),
				'design'  => __( 'Design', 'woostify' ),
			),
		)
	)
);

// Display topbar.
$wp_customize->add_setting(
	'woostify_setting[topbar_display]',
	array(
		'type'              => 'option',
		'default'           => $defaults['topbar_display'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[topbar_display]',
		array(
			'label'    => __( 'Topbar Display', 'woostify' ),
			'section'  => 'woostify_topbar',
			'settings' => 'woostify_setting[topbar_display]',
			'tab'      => 'general',
		)
	)
);

// Topbar color.
$wp_customize->add_setting(
	'woostify_setting[topbar_text_color]',
	array(
		'default'           => $defaults['topbar_text_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[topbar_text_color]',
		array(
			'label'        => __( 'Text Color', 'woostify' ),
			'section'      => 'woostify_topbar',
			'settings'     => array(
				'woostify_setting[topbar_text_color]',
			),
			'color_format' => 'hex',
			'tab'          => 'design',
		)
	)
);

// Background color.
$wp_customize->add_setting(
	'woostify_setting[topbar_background_color]',
	array(
		'default'           => $defaults['topbar_background_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[topbar_background_color]',
		array(
			'label'    => __( 'Background Color', 'woostify' ),
			'section'  => 'woostify_topbar',
			'settings' => array(
				'woostify_setting[topbar_background_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Space.
$wp_customize->add_setting(
	'woostify_setting[topbar_space]',
	array(
		'default'           => $defaults['topbar_space'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[topbar_space]',
		array(
			'label'    => __( 'Space', 'woostify' ),
			'section'  => 'woostify_topbar',
			'settings' => array(
				'desktop' => 'woostify_setting[topbar_space]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_topbar_min_step', 0 ),
					'max'  => apply_filters( 'woostify_topbar_max_step', 50 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Content divider.
$wp_customize->add_setting(
	'topbar_content_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'topbar_content_divider',
		array(
			'section'  => 'woostify_topbar',
			'settings' => 'topbar_content_divider',
			'type'     => 'divider',
			'tab'      => 'general',
		)
	)
);

// Topbar left.
$wp_customize->add_setting(
	'woostify_setting[topbar_left]',
	array(
		'default'           => $defaults['topbar_left'],
		'sanitize_callback' => 'woostify_sanitize_raw_html',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[topbar_left]',
		array(
			'label'    => __( 'Content Left', 'woostify' ),
			'section'  => 'woostify_topbar',
			'settings' => 'woostify_setting[topbar_left]',
			'type'     => 'textarea',
			'tab'      => 'general',
		)
	)
);

// Topbar center.
$wp_customize->add_setting(
	'woostify_setting[topbar_center]',
	array(
		'default'           => $defaults['topbar_center'],
		'sanitize_callback' => 'woostify_sanitize_raw_html',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[topbar_center]',
		array(
			'label'    => __( 'Content Center', 'woostify' ),
			'section'  => 'woostify_topbar',
			'settings' => 'woostify_setting[topbar_center]',
			'type'     => 'textarea',
			'tab'      => 'general',
		)
	)
);

// Topbar right.
$wp_customize->add_setting(
	'woostify_setting[topbar_right]',
	array(
		'default'           => $defaults['topbar_right'],
		'sanitize_callback' => 'woostify_sanitize_raw_html',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[topbar_right]',
		array(
			'label'    => __( 'Content Right', 'woostify' ),
			'section'  => 'woostify_topbar',
			'settings' => 'woostify_setting[topbar_right]',
			'type'     => 'textarea',
			'tab'      => 'general',
		)
	)
);
