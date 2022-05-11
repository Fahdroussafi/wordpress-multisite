<?php
/**
 * Site Title & Tagline
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Retina logo.
$wp_customize->add_setting(
	'woostify_setting[retina_logo]',
	array(
		'type'              => 'option',
		'default'           => $defaults['retina_logo'],
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'woostify_setting[retina_logo]',
		array(
			'label'    => __( 'Retina Logo (Optional)', 'woostify' ),
			'section'  => 'title_tagline',
			'settings' => 'woostify_setting[retina_logo]',
			'priority' => 8,
		)
	)
);

// Logo mobile.
$wp_customize->add_setting(
	'woostify_setting[logo_mobile]',
	array(
		'type'              => 'option',
		'default'           => $defaults['logo_mobile'],
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'woostify_setting[logo_mobile]',
		array(
			'label'    => __( 'Mobile Logo (Optional)', 'woostify' ),
			'section'  => 'title_tagline',
			'settings' => 'woostify_setting[logo_mobile]',
			'priority' => 8,
		)
	)
);

// Above logo width divider.
$wp_customize->add_setting(
	'above_logo_with_color_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'above_logo_with_color_divider',
		array(
			'section'  => 'title_tagline',
			'settings' => 'above_logo_with_color_divider',
			'type'     => 'divider',
			'priority' => 8,
		)
	)
);

// Logo width.
$wp_customize->add_setting(
	'woostify_setting[logo_width]',
	array(
		'default'           => $defaults['logo_width'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_setting(
	'woostify_setting[tablet_logo_width]',
	array(
		'default'           => $defaults['tablet_logo_width'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_setting(
	'woostify_setting[mobile_logo_width]',
	array(
		'default'           => $defaults['mobile_logo_width'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[logo_width]',
		array(
			'type'     => 'woostify-range-slider',
			'label'    => __( 'Logo Width', 'woostify' ),
			'section'  => 'title_tagline',
			'settings' => array(
				'desktop' => 'woostify_setting[logo_width]',
				'tablet'  => 'woostify_setting[tablet_logo_width]',
				'mobile'  => 'woostify_setting[mobile_logo_width]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_logo_desktop_width_min_step', 50 ),
					'max'  => apply_filters( 'woostify_logo_desktop_width_max_step', 500 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'tablet'  => array(
					'min'  => apply_filters( 'woostify_logo_tablet_width_min_step', 50 ),
					'max'  => apply_filters( 'woostify_logo_tablet_width_max_step', 500 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'mobile'  => array(
					'min'  => apply_filters( 'woostify_logo_mobile_width_min_step', 50 ),
					'max'  => apply_filters( 'woostify_logo_mobile_width_max_step', 500 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'priority' => 8,
		)
	)
);

// Under logo width divider.
$wp_customize->add_setting(
	'under_logo_with_color_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'under_logo_with_color_divider',
		array(
			'section'  => 'title_tagline',
			'settings' => 'under_logo_with_color_divider',
			'type'     => 'divider',
			'priority' => 8,
		)
	)
);
