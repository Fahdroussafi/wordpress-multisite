<?php
/**
 * Header Transparent
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Tabs.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_context_tabs]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Woostify_Tabs_Control(
		$wp_customize,
		'woostify_setting[header_transparent_context_tabs]',
		array(
			'section'  => 'woostify_header_transparent',
			'settings' => 'woostify_setting[header_transparent_context_tabs]',
			'choices'  => array(
				'general' => __( 'General', 'woostify' ),
				'design'  => __( 'Design', 'woostify' ),
			),
		)
	)
);

// Enable/disable Header transparent.
$wp_customize->add_setting(
	'woostify_setting[header_transparent]',
	array(
		'default'           => $defaults['header_transparent'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[header_transparent]',
		array(
			'label'    => __( 'Enable Transparent Header', 'woostify' ),
			'settings' => 'woostify_setting[header_transparent]',
			'section'  => 'woostify_header_transparent',
			'tab'      => 'general',
		)
	)
);

// Disable on 404, Search and Archive.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_disable_archive]',
	array(
		'default'           => $defaults['header_transparent_disable_archive'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[header_transparent_disable_archive]',
		array(
			'label'    => __( 'Disable on 404, Search & Archives', 'woostify' ),
			'settings' => 'woostify_setting[header_transparent_disable_archive]',
			'section'  => 'woostify_header_transparent',
			'type'     => 'checkbox',
			'tab'      => 'general',
		)
	)
);

// Disable on Index.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_disable_index]',
	array(
		'default'           => $defaults['header_transparent_disable_index'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[header_transparent_disable_index]',
		array(
			'label'    => __( 'Disable on Blog page', 'woostify' ),
			'settings' => 'woostify_setting[header_transparent_disable_index]',
			'section'  => 'woostify_header_transparent',
			'type'     => 'checkbox',
			'tab'      => 'general',
		)
	)
);

// Disable on Pages.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_disable_page]',
	array(
		'default'           => $defaults['header_transparent_disable_page'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[header_transparent_disable_page]',
		array(
			'label'    => __( 'Disable on Pages', 'woostify' ),
			'settings' => 'woostify_setting[header_transparent_disable_page]',
			'section'  => 'woostify_header_transparent',
			'type'     => 'checkbox',
			'tab'      => 'general',
		)
	)
);

// Disable on Posts.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_disable_post]',
	array(
		'default'           => $defaults['header_transparent_disable_post'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[header_transparent_disable_post]',
		array(
			'label'    => __( 'Disable on Posts', 'woostify' ),
			'settings' => 'woostify_setting[header_transparent_disable_post]',
			'section'  => 'woostify_header_transparent',
			'type'     => 'checkbox',
			'tab'      => 'general',
		)
	)
);

// Disable on Shop page.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_disable_shop]',
	array(
		'default'           => $defaults['header_transparent_disable_shop'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[header_transparent_disable_shop]',
		array(
			'label'    => __( 'Disable on Shop page', 'woostify' ),
			'settings' => 'woostify_setting[header_transparent_disable_shop]',
			'section'  => 'woostify_header_transparent',
			'type'     => 'checkbox',
			'tab'      => 'general',
		)
	)
);

// Disable on Product page.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_disable_product]',
	array(
		'default'           => $defaults['header_transparent_disable_product'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[header_transparent_disable_product]',
		array(
			'label'    => __( 'Disable on Product page', 'woostify' ),
			'settings' => 'woostify_setting[header_transparent_disable_product]',
			'section'  => 'woostify_header_transparent',
			'type'     => 'checkbox',
			'tab'      => 'general',
		)
	)
);

// Enable on devices.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_enable_on]',
	array(
		'default'           => $defaults['header_transparent_enable_on'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[header_transparent_enable_on]',
		array(
			'label'    => __( 'Enable On', 'woostify' ),
			'settings' => 'woostify_setting[header_transparent_enable_on]',
			'section'  => 'woostify_header_transparent',
			'type'     => 'select',
			'choices'  => array(
				'desktop'     => __( 'Desktop', 'woostify' ),
				'mobile'      => __( 'Mobile', 'woostify' ),
				'all-devices' => __( 'Desktop + Mobile', 'woostify' ),
			),
			'tab'      => 'general',
		)
	)
);

// Logo Transparent.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_logo]',
	array(
		'type'              => 'option',
		'default'           => $defaults['header_transparent_logo'],
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control(
	new Woostify_Image_Control(
		$wp_customize,
		'woostify_setting[header_transparent_logo]',
		array(
			'label'    => __( 'Header Transparent Logo', 'woostify' ),
			'section'  => 'woostify_header_transparent',
			'settings' => 'woostify_setting[header_transparent_logo]',
			'tab'      => 'general',
		)
	)
);

// Menu Transparent color.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_menu_color]',
	array(
		'default'           => $defaults['header_transparent_menu_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[header_transparent_menu_color]',
		array(
			'label'    => __( 'Menu Transparent Color', 'woostify' ),
			'section'  => 'woostify_header_transparent',
			'settings' => array(
				'woostify_setting[header_transparent_menu_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Icon Transparent color.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_icon_color]',
	array(
		'default'           => $defaults['header_transparent_icon_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[header_transparent_icon_color]',
		array(
			'label'    => __( 'Icon Transparent Color', 'woostify' ),
			'section'  => 'woostify_header_transparent',
			'settings' => array(
				'woostify_setting[header_transparent_icon_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Count Transparent background.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_count_background]',
	array(
		'default'           => $defaults['header_transparent_count_background'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[header_transparent_count_background]',
		array(
			'label'    => __( 'Count Transparent Background', 'woostify' ),
			'section'  => 'woostify_header_transparent',
			'settings' => array(
				'woostify_setting[header_transparent_count_background]',
			),
			'tab'      => 'design',
		)
	)
);


// Border divider.
$wp_customize->add_setting(
	'header_transparent_border_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'header_transparent_border_divider',
		array(
			'section'  => 'woostify_header_transparent',
			'settings' => 'header_transparent_border_divider',
			'type'     => 'divider',
			'tab'      => 'design',
		)
	)
);

// Border width.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_border_width]',
	array(
		'default'           => $defaults['header_transparent_border_width'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[header_transparent_border_width]',
		array(
			'label'    => __( 'Bottom Border Width', 'woostify' ),
			'section'  => 'woostify_header_transparent',
			'settings' => array(
				'desktop' => 'woostify_setting[header_transparent_border_width]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_header_transparent_border_width_min_step', 0 ),
					'max'  => apply_filters( 'woostify_header_transparent_border_width_max_step', 20 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Border color.
$wp_customize->add_setting(
	'woostify_setting[header_transparent_border_color]',
	array(
		'default'           => $defaults['header_transparent_border_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[header_transparent_border_color]',
		array(
			'label'    => __( 'Border Color', 'woostify' ),
			'section'  => 'woostify_header_transparent',
			'settings' => array(
				'woostify_setting[header_transparent_border_color]',
			),
			'tab'      => 'design',
		)
	)
);
