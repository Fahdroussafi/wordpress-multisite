<?php
/**
 * Mobile Menu
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Tabs.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_context_tabs]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Woostify_Tabs_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_context_tabs]',
		array(
			'section'  => 'woostify_mobile_menu',
			'settings' => 'woostify_setting[mobile_menu_context_tabs]',
			'choices'  => array(
				'general' => __( 'General', 'woostify' ),
				'design'  => __( 'Design', 'woostify' ),
			),
		)
	)
);

// Hide search field.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_hide_search_field]',
	array(
		'type'              => 'option',
		'default'           => $defaults['mobile_menu_hide_search_field'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_hide_search_field]',
		array(
			'label'    => __( 'Hide Search Box', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => 'woostify_setting[mobile_menu_hide_search_field]',
			'tab'      => 'general',
		)
	)
);

// Hide Login/Register.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_hide_login]',
	array(
		'type'              => 'option',
		'default'           => $defaults['mobile_menu_hide_login'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_hide_login]',
		array(
			'label'    => __( 'Hide Login/Register Link', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => 'woostify_setting[mobile_menu_hide_login]',
			'tab'      => 'general',
		)
	)
);

// Show categories menu on mobile.
$wp_customize->add_setting(
	'woostify_setting[header_show_categories_menu_on_mobile]',
	array(
		'type'              => 'option',
		'default'           => $defaults['header_show_categories_menu_on_mobile'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[header_show_categories_menu_on_mobile]',
		array(
			'label'    => __( 'Show Categories Menu on Mobile', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => 'woostify_setting[header_show_categories_menu_on_mobile]',
			'tab'      => 'general',
		)
	)
);

// Primary menu tab title.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_primary_menu_tab_title]',
	array(
		'type'              => 'option',
		'default'           => $defaults['mobile_menu_primary_menu_tab_title'],
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_primary_menu_tab_title]',
		array(
			'label'    => __( 'Primary Menu Tab Title', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => 'woostify_setting[mobile_menu_primary_menu_tab_title]',
			'tab'      => 'general',
		)
	)
);

// Categories menu tab title.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_categories_menu_tab_title]',
	array(
		'type'              => 'option',
		'default'           => $defaults['mobile_menu_categories_menu_tab_title'],
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_categories_menu_tab_title]',
		array(
			'label'    => __( 'Categories Menu Tab Title', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => 'woostify_setting[mobile_menu_categories_menu_tab_title]',
			'tab'      => 'general',
		)
	)
);

// Design controls.

// Icon Bar Color.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_icon_bar_color]',
	array(
		'default'           => $defaults['mobile_menu_icon_bar_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_icon_bar_color]',
		array(
			'label'    => __( 'Hamburger Icon', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => array(
				'woostify_setting[mobile_menu_icon_bar_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Background.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_background]',
	array(
		'default'           => $defaults['mobile_menu_background'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_background]',
		array(
			'label'    => __( 'Background', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => array(
				'woostify_setting[mobile_menu_background]',
			),
			'tab'      => 'design',
		)
	)
);

// Text color.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_text_color]',
	array(
		'default'           => $defaults['mobile_menu_text_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_text_hover_color]',
	array(
		'default'           => $defaults['mobile_menu_text_hover_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_text_color]',
		array(
			'label'    => __( 'Text', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => array(
				'woostify_setting[mobile_menu_text_color]',
				'woostify_setting[mobile_menu_text_hover_color]',
			),
			'tooltips' => array(
				'Normal',
				'Hover',
			),
			'tab'      => 'design',
		)
	)
);

// Tab color.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_tab_color]',
	array(
		'default'           => $defaults['mobile_menu_tab_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_tab_active_color]',
	array(
		'default'           => $defaults['mobile_menu_tab_active_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_tab_color]',
		array(
			'label'    => __( 'Tab Color', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => array(
				'woostify_setting[mobile_menu_tab_color]',
				'woostify_setting[mobile_menu_tab_active_color]',
			),
			'tooltips' => array(
				'Normal',
				'Active',
			),
			'tab'      => 'design',
		)
	)
);

// Tab background.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_tab_background]',
	array(
		'default'           => $defaults['mobile_menu_tab_background'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_tab_active_background]',
	array(
		'default'           => $defaults['mobile_menu_tab_active_background'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_tab_background]',
		array(
			'label'    => __( 'Tab background', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => array(
				'woostify_setting[mobile_menu_tab_background]',
				'woostify_setting[mobile_menu_tab_active_background]',
			),
			'tooltips' => array(
				'Normal',
				'Active',
			),
			'tab'      => 'design',
		)
	)
);

// Tab padding.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_tab_padding]',
	array(
		'default'           => $defaults['mobile_menu_tab_padding'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Group_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_tab_padding]',
		array(
			'label'          => __( 'Tab Padding', 'woostify' ),
			'section'        => 'woostify_mobile_menu',
			'settings'       => array(
				'desktop' => 'woostify_setting[mobile_menu_tab_padding]',
			),
			'choices'        => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_mobile_menu_tab_padding_min_step', 0 ),
					'max'  => apply_filters( 'woostify_mobile_menu_tab_padding_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'inputs_label'   => array(
				__( 'Top', 'woostify' ),
				__( 'Right', 'woostify' ),
				__( 'Bottom', 'woostify' ),
				__( 'Left', 'woostify' ),
			),
			'tab'            => 'design',
			'negative_value' => false,
		)
	)
);

// Spacing bottom.
$wp_customize->add_setting(
	'woostify_setting[mobile_menu_nav_tab_spacing_bottom]',
	array(
		'default'           => $defaults['mobile_menu_nav_tab_spacing_bottom'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[mobile_menu_nav_tab_spacing_bottom]',
		array(
			'label'    => __( 'Nav Tab Spacing Bottom', 'woostify' ),
			'section'  => 'woostify_mobile_menu',
			'settings' => array(
				'desktop' => 'woostify_setting[mobile_menu_nav_tab_spacing_bottom]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_mobile_menu_nav_tab_spacing_bottom_min_step', 0 ),
					'max'  => apply_filters( 'woostify_mobile_menu_nav_tab_spacing_bottom_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);
