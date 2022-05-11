<?php
/**
 * Footer widgets column
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Tabs.
$wp_customize->add_setting(
	'woostify_setting[footer_context_tabs]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Woostify_Tabs_Control(
		$wp_customize,
		'woostify_setting[footer_context_tabs]',
		array(
			'section'  => 'woostify_footer',
			'settings' => 'woostify_setting[footer_context_tabs]',
			'choices'  => array(
				'general' => __( 'General', 'woostify' ),
				'design'  => __( 'Design', 'woostify' ),
			),
		)
	)
);

// Footer display.
$wp_customize->add_setting(
	'woostify_setting[footer_display]',
	array(
		'default'           => $defaults['footer_display'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[footer_display]',
		array(
			'label'    => __( 'Footer Display', 'woostify' ),
			'settings' => 'woostify_setting[footer_display]',
			'section'  => 'woostify_footer',
			'tab'      => 'general',
		)
	)
);

// Space.
$wp_customize->add_setting(
	'woostify_setting[footer_space]',
	array(
		'default'           => $defaults['footer_space'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[footer_space]',
		array(
			'label'    => __( 'Space', 'woostify' ),
			'section'  => 'woostify_footer',
			'settings' => array(
				'desktop' => 'woostify_setting[footer_space]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_footer_space_min_step', 0 ),
					'max'  => apply_filters( 'woostify_footer_space_max_step', 200 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Footer widget columns.
$wp_customize->add_setting(
	'woostify_setting[footer_column]',
	array(
		'default'           => $defaults['footer_column'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[footer_column]',
		array(
			'label'    => __( 'Widget Columns', 'woostify' ),
			'settings' => 'woostify_setting[footer_column]',
			'section'  => 'woostify_footer',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_footer_column_choices',
				array(
					0 => 0,
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
				)
			),
			'tab'      => 'general',
		)
	)
);

// Footer background color divider.
$wp_customize->add_setting(
	'footer_background_color_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'footer_background_color_divider',
		array(
			'section'  => 'woostify_footer',
			'settings' => 'footer_background_color_divider',
			'type'     => 'divider',
			'tab'      => 'design',
		)
	)
);

// Footer Background.
$wp_customize->add_setting(
	'woostify_setting[footer_background_color]',
	array(
		'default'           => $defaults['footer_background_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[footer_background_color]',
		array(
			'label'    => __( 'Background Color', 'woostify' ),
			'section'  => 'woostify_footer',
			'settings' => array(
				'woostify_setting[footer_background_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Footer heading color.
$wp_customize->add_setting(
	'woostify_setting[footer_heading_color]',
	array(
		'default'           => $defaults['footer_heading_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[footer_heading_color]',
		array(
			'label'    => __( 'Heading Color', 'woostify' ),
			'section'  => 'woostify_footer',
			'settings' => array(
				'woostify_setting[footer_heading_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Footer link color.
$wp_customize->add_setting(
	'woostify_setting[footer_link_color]',
	array(
		'default'           => $defaults['footer_link_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[footer_link_color]',
		array(
			'label'    => __( 'Link Color', 'woostify' ),
			'section'  => 'woostify_footer',
			'settings' => array(
				'woostify_setting[footer_link_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Footer text color.
$wp_customize->add_setting(
	'woostify_setting[footer_text_color]',
	array(
		'default'           => $defaults['footer_text_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[footer_text_color]',
		array(
			'label'    => __( 'Text Color', 'woostify' ),
			'section'  => 'woostify_footer',
			'settings' => array(
				'woostify_setting[footer_text_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Footer text divider.
$wp_customize->add_setting(
	'footer_text_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'footer_text_divider',
		array(
			'section'  => 'woostify_footer',
			'settings' => 'footer_text_divider',
			'type'     => 'divider',
			'tab'      => 'general',
		)
	)
);

// Custom text.
$wp_customize->add_setting(
	'woostify_setting[footer_custom_text]',
	array(
		'default'           => $defaults['footer_custom_text'],
		'sanitize_callback' => 'woostify_sanitize_raw_html',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[footer_custom_text]',
		array(
			'label'    => __( 'Custom Text', 'woostify' ),
			'type'     => 'textarea',
			'section'  => 'woostify_footer',
			'settings' => 'woostify_setting[footer_custom_text]',
			'tab'      => 'general',
		)
	)
);
