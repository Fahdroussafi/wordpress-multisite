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
	'woostify_setting[scroll_to_top_context_tabs]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Woostify_Tabs_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top_context_tabs]',
		array(
			'section'  => 'woostify_scroll_to_top',
			'settings' => 'woostify_setting[scroll_to_top_context_tabs]',
			'choices'  => array(
				'general' => __( 'General', 'woostify' ),
				'design'  => __( 'Design', 'woostify' ),
			),
		)
	)
);

// Scroll to top.
$wp_customize->add_setting(
	'woostify_setting[scroll_to_top]',
	array(
		'default'           => $defaults['scroll_to_top'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top]',
		array(
			'label'    => __( 'Scroll To Top', 'woostify' ),
			'settings' => 'woostify_setting[scroll_to_top]',
			'section'  => 'woostify_scroll_to_top',
			'tab'      => 'general',
		)
	)
);

// Scroll On.
$wp_customize->add_setting(
	'woostify_setting[scroll_to_top_on]',
	array(
		'default'           => $defaults['scroll_to_top_on'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top_on]',
		array(
			'label'    => __( 'Scroll On', 'woostify' ),
			'section'  => 'woostify_scroll_to_top',
			'settings' => 'woostify_setting[scroll_to_top_on]',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_scroll_to_top_on_choices',
				array(
					'default' => __( 'Mobile + Desktop', 'woostify' ),
					'mobile'  => __( 'Mobile', 'woostify' ),
					'desktop' => __( 'Desktop', 'woostify' ),
				)
			),
			'tab'      => 'general',
		)
	)
);

// Scroll Position.
$wp_customize->add_setting(
	'woostify_setting[scroll_to_top_position]',
	array(
		'default'           => $defaults['scroll_to_top_position'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top_position]',
		array(
			'label'    => __( 'Position', 'woostify' ),
			'section'  => 'woostify_scroll_to_top',
			'settings' => 'woostify_setting[scroll_to_top_position]',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_sidebar_shop_single_choices',
				array(
					'right' => __( 'Right', 'woostify' ),
					'left'  => __( 'Left', 'woostify' ),
				)
			),
			'tab'      => 'general',
		)
	)
);

// Scroll To Top Background.
$wp_customize->add_setting(
	'woostify_setting[scroll_to_top_background]',
	array(
		'default'           => $defaults['scroll_to_top_background'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top_background]',
		array(
			'label'    => __( 'Background', 'woostify' ),
			'section'  => 'woostify_scroll_to_top',
			'settings' => array(
				'woostify_setting[scroll_to_top_background]',
			),
			'tab'      => 'design',
		)
	)
);

// Scroll To Top Color.
$wp_customize->add_setting(
	'woostify_setting[scroll_to_top_color]',
	array(
		'default'           => $defaults['scroll_to_top_color'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top_color]',
		array(
			'label'    => __( 'Color', 'woostify' ),
			'section'  => 'woostify_scroll_to_top',
			'settings' => array(
				'woostify_setting[scroll_to_top_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Icons Size.
$wp_customize->add_setting(
	'woostify_setting[scroll_to_top_icon_size]',
	array(
		'default'           => $defaults['scroll_to_top_icon_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top_icon_size]',
		array(
			'label'    => __( 'Icon Size', 'woostify' ),
			'section'  => 'woostify_scroll_to_top',
			'settings' => array(
				'desktop' => 'woostify_setting[scroll_to_top_icon_size]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_scroll_to_top_icon_size_min_step', 0 ),
					'max'  => apply_filters( 'woostify_scroll_to_top_icon_size_max_step', 200 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);


// Scroll to top Border radius.
$wp_customize->add_setting(
	'woostify_setting[scroll_to_top_border_radius]',
	array(
		'default'           => $defaults['scroll_to_top_border_radius'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top_border_radius]',
		array(
			'label'    => __( 'Border Radius', 'woostify' ),
			'section'  => 'woostify_scroll_to_top',
			'settings' => array(
				'desktop' => 'woostify_setting[scroll_to_top_border_radius]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_scroll_to_top_border_radius_min_step', 0 ),
					'max'  => apply_filters( 'woostify_scroll_to_top_border_radius_max_step', 200 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Offset Bottom.
$wp_customize->add_setting(
	'woostify_setting[scroll_to_top_offset_bottom]',
	array(
		'default'           => $defaults['scroll_to_top_offset_bottom'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[scroll_to_top_offset_bottom]',
		array(
			'label'    => __( 'Offset Bottom', 'woostify' ),
			'section'  => 'woostify_scroll_to_top',
			'settings' => array(
				'desktop' => 'woostify_setting[scroll_to_top_offset_bottom]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_scroll_to_top_offset_bottom_min_step', 0 ),
					'max'  => apply_filters( 'woostify_scroll_to_top_offset_bottom_max_step', 700 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);
