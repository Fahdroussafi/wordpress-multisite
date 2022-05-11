<?php
/**
 * Page Header
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Tabs.
$wp_customize->add_setting(
	'woostify_setting[page_header_context_tabs]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Woostify_Tabs_Control(
		$wp_customize,
		'woostify_setting[page_header_context_tabs]',
		array(
			'section'  => 'woostify_page_header',
			'settings' => 'woostify_setting[page_header_context_tabs]',
			'choices'  => array(
				'general' => __( 'General', 'woostify' ),
				'design'  => __( 'Design', 'woostify' ),
			),
		)
	)
);

// Page header display.
$wp_customize->add_setting(
	'woostify_setting[page_header_display]',
	array(
		'default'           => $defaults['page_header_display'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[page_header_display]',
		array(
			'label'    => __( 'Page Header Display', 'woostify' ),
			'settings' => 'woostify_setting[page_header_display]',
			'section'  => 'woostify_page_header',
			'tab'      => 'general',
		)
	)
);

// Breadcrumb divider.
$wp_customize->add_setting(
	'page_header_breadcrumb_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'page_header_breadcrumb_divider',
		array(
			'section'  => 'woostify_page_header',
			'settings' => 'page_header_breadcrumb_divider',
			'type'     => 'divider',
			'tab'      => 'general',
		)
	)
);

// Page title.
$wp_customize->add_setting(
	'woostify_setting[page_header_title]',
	array(
		'default'           => $defaults['page_header_title'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[page_header_title]',
		array(
			'label'    => __( 'Title', 'woostify' ),
			'settings' => 'woostify_setting[page_header_title]',
			'section'  => 'woostify_page_header',
			'tab'      => 'general',
		)
	)
);

// Breadcrumb.
$wp_customize->add_setting(
	'woostify_setting[page_header_breadcrumb]',
	array(
		'default'           => $defaults['page_header_breadcrumb'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[page_header_breadcrumb]',
		array(
			'label'    => __( 'Breadcrumb', 'woostify' ),
			'settings' => 'woostify_setting[page_header_breadcrumb]',
			'section'  => 'woostify_page_header',
			'tab'      => 'general',
		)
	)
);

// Text align.
$wp_customize->add_setting(
	'woostify_setting[page_header_text_align]',
	array(
		'default'           => $defaults['page_header_text_align'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[page_header_text_align]',
		array(
			'label'    => __( 'Text Align', 'woostify' ),
			'settings' => 'woostify_setting[page_header_text_align]',
			'section'  => 'woostify_page_header',
			'type'     => 'select',
			'choices'  => array(
				'left'    => __( 'Left', 'woostify' ),
				'center'  => __( 'Center', 'woostify' ),
				'right'   => __( 'Right', 'woostify' ),
				'justify' => __( 'Page Title / Breadcrumb', 'woostify' ),
			),
			'tab'      => 'general',
		)
	)
);

// Title color.
$wp_customize->add_setting(
	'woostify_setting[page_header_title_color]',
	array(
		'default'           => $defaults['page_header_title_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[page_header_title_color]',
		array(
			'label'    => __( 'Title Color', 'woostify' ),
			'section'  => 'woostify_page_header',
			'settings' => array(
				'woostify_setting[page_header_title_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Breadcrumb text color.
$wp_customize->add_setting(
	'woostify_setting[page_header_breadcrumb_text_color]',
	array(
		'default'           => $defaults['page_header_breadcrumb_text_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[page_header_breadcrumb_text_color]',
		array(
			'label'    => __( 'Breadcrumb Color', 'woostify' ),
			'section'  => 'woostify_page_header',
			'settings' => array(
				'woostify_setting[page_header_breadcrumb_text_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Background color.
$wp_customize->add_setting(
	'woostify_setting[page_header_background_color]',
	array(
		'default'           => $defaults['page_header_background_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[page_header_background_color]',
		array(
			'label'    => __( 'Background Color', 'woostify' ),
			'section'  => 'woostify_page_header',
			'settings' => array(
				'woostify_setting[page_header_background_color]',
			),
			'tab'      => 'design',
		)
	)
);

// Background image.
$wp_customize->add_setting(
	'woostify_setting[page_header_background_image]',
	array(
		'type'              => 'option',
		'default'           => $defaults['page_header_background_image'],
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Image_Control(
		$wp_customize,
		'woostify_setting[page_header_background_image]',
		array(
			'label'    => __( 'Background Image', 'woostify' ),
			'section'  => 'woostify_page_header',
			'settings' => 'woostify_setting[page_header_background_image]',
			'tab'      => 'design',
		)
	)
);

// Background image size.
$wp_customize->add_setting(
	'woostify_setting[page_header_background_image_size]',
	array(
		'default'           => $defaults['page_header_background_image_size'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[page_header_background_image_size]',
		array(
			'label'    => __( 'Background Size', 'woostify' ),
			'settings' => 'woostify_setting[page_header_background_image_size]',
			'section'  => 'woostify_page_header',
			'type'     => 'select',
			'choices'  => array(
				'auto'    => __( 'Default', 'woostify' ),
				'cover'   => __( 'Cover', 'woostify' ),
				'contain' => __( 'Contain', 'woostify' ),
			),
			'tab'      => 'design',
		)
	)
);

// Background image repeat.
$wp_customize->add_setting(
	'woostify_setting[page_header_background_image_repeat]',
	array(
		'default'           => $defaults['page_header_background_image_repeat'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[page_header_background_image_repeat]',
		array(
			'label'    => __( 'Background Repeat', 'woostify' ),
			'settings' => 'woostify_setting[page_header_background_image_repeat]',
			'section'  => 'woostify_page_header',
			'type'     => 'select',
			'choices'  => array(
				'repeat'    => __( 'Default', 'woostify' ),
				'no-repeat' => __( 'No Repeat', 'woostify' ),
				'repeat-x'  => __( 'Repeat X', 'woostify' ),
				'repeat-y'  => __( 'Repeat Y', 'woostify' ),
				'space'     => __( 'Space', 'woostify' ),
				'round'     => __( 'Round', 'woostify' ),
			),
			'tab'      => 'design',
		)
	)
);

// Background image position.
$wp_customize->add_setting(
	'woostify_setting[page_header_background_image_position]',
	array(
		'default'           => $defaults['page_header_background_image_position'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[page_header_background_image_position]',
		array(
			'label'    => __( 'Background Position', 'woostify' ),
			'settings' => 'woostify_setting[page_header_background_image_position]',
			'section'  => 'woostify_page_header',
			'type'     => 'select',
			'choices'  => array(
				'left-top'      => __( 'Left Top', 'woostify' ),
				'left-center'   => __( 'Left Center', 'woostify' ),
				'left-bottom'   => __( 'Left Bottom', 'woostify' ),
				'center-top'    => __( 'Center Top', 'woostify' ),
				'center-center' => __( 'Center Center', 'woostify' ),
				'center-bottom' => __( 'Center Bottom', 'woostify' ),
				'right-top'     => __( 'Right Top', 'woostify' ),
				'right-center'  => __( 'Right Center', 'woostify' ),
				'right-bottom'  => __( 'Right Bottom', 'woostify' ),
			),
			'tab'      => 'design',
		)
	)
);

// Background image attachment.
$wp_customize->add_setting(
	'woostify_setting[page_header_background_image_attachment]',
	array(
		'default'           => $defaults['page_header_background_image_attachment'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[page_header_background_image_attachment]',
		array(
			'label'    => __( 'Background Attachment', 'woostify' ),
			'settings' => 'woostify_setting[page_header_background_image_attachment]',
			'section'  => 'woostify_page_header',
			'type'     => 'select',
			'choices'  => array(
				'scroll' => __( 'Default', 'woostify' ),
				'fixed'  => __( 'Fixed', 'woostify' ),
				'local'  => __( 'Local', 'woostify' ),
			),
			'tab'      => 'design',
		)
	)
);

// Padding divider.
$wp_customize->add_setting(
	'page_header_spacing_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'page_header_spacing_divider',
		array(
			'section'  => 'woostify_page_header',
			'settings' => 'page_header_spacing_divider',
			'type'     => 'divider',
			'tab'      => 'design',
		)
	)
);

// Padding top.
$wp_customize->add_setting(
	'woostify_setting[page_header_padding_top]',
	array(
		'default'           => $defaults['page_header_padding_top'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[page_header_padding_top]',
		array(
			'label'    => __( 'Padding Top', 'woostify' ),
			'section'  => 'woostify_page_header',
			'settings' => array(
				'desktop' => 'woostify_setting[page_header_padding_top]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_page_header_padding_top_min_step', 0 ),
					'max'  => apply_filters( 'woostify_page_header_padding_top_max_step', 200 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Padding bottom.
$wp_customize->add_setting(
	'woostify_setting[page_header_padding_bottom]',
	array(
		'default'           => $defaults['page_header_padding_bottom'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[page_header_padding_bottom]',
		array(
			'label'    => __( 'Padding Bottom', 'woostify' ),
			'section'  => 'woostify_page_header',
			'settings' => array(
				'desktop' => 'woostify_setting[page_header_padding_bottom]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_page_header_padding_bottom_min_step', 0 ),
					'max'  => apply_filters( 'woostify_page_header_padding_bottom_max_step', 200 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Margin bottom.
$wp_customize->add_setting(
	'woostify_setting[page_header_margin_bottom]',
	array(
		'default'           => $defaults['page_header_margin_bottom'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[page_header_margin_bottom]',
		array(
			'label'    => __( 'Margin Bottom', 'woostify' ),
			'section'  => 'woostify_page_header',
			'settings' => array(
				'desktop' => 'woostify_setting[page_header_margin_bottom]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_page_header_margin_bottom_min_step', 0 ),
					'max'  => apply_filters( 'woostify_page_header_margin_bottom_max_step', 200 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);
