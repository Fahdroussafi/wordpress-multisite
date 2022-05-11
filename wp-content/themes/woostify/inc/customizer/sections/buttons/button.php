<?php
/**
 * Button customizer
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Text color.
$wp_customize->add_setting(
	'woostify_setting[button_text_color]',
	array(
		'default'           => $defaults['button_text_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
// Hover text color.
$wp_customize->add_setting(
	'woostify_setting[button_hover_text_color]',
	array(
		'default'           => $defaults['button_hover_text_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[button_text_color]',
		array(
			'label'    => __( 'Text Color', 'woostify' ),
			'section'  => 'woostify_buttons',
			'settings' => array(
				'woostify_setting[button_text_color]',
				'woostify_setting[button_hover_text_color]',
			),
			'tooltips' => array(
				'Normal',
				'Hover',
			),
		)
	)
);

// Background color.
$wp_customize->add_setting(
	'woostify_setting[button_background_color]',
	array(
		'default'           => $defaults['button_background_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
// Hover background color.
$wp_customize->add_setting(
	'woostify_setting[button_hover_background_color]',
	array(
		'default'           => $defaults['button_hover_background_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[button_background_color]',
		array(
			'label'    => __( 'Background Color', 'woostify' ),
			'section'  => 'woostify_buttons',
			'settings' => array(
				'woostify_setting[button_background_color]',
				'woostify_setting[button_hover_background_color]',
			),
			'tooltips' => array(
				'Normal',
				'Hover',
			),
		)
	)
);

// Border radius divider.
$wp_customize->add_setting(
	'button_radius_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'button_radius_divider',
		array(
			'section'  => 'woostify_buttons',
			'settings' => 'button_radius_divider',
			'type'     => 'divider',
		)
	)
);

// Border radius.
$wp_customize->add_setting(
	'woostify_setting[buttons_border_radius]',
	array(
		'default'           => $defaults['buttons_border_radius'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[buttons_border_radius]',
		array(
			'label'    => __( 'Border Radius', 'woostify' ),
			'section'  => 'woostify_buttons',
			'settings' => array(
				'desktop' => 'woostify_setting[buttons_border_radius]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_buttons_border_radius_min_step', 0 ),
					'max'  => apply_filters( 'woostify_buttons_border_radius_max_step', 200 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);
