<?php
/**
 * Sticky Footer Bar
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Tabs.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_context_tabs]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Woostify_Tabs_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_context_tabs]',
		array(
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => 'woostify_setting[sticky_footer_bar_context_tabs]',
			'choices'  => array(
				'general' => __( 'General', 'woostify' ),
				'design'  => __( 'Design', 'woostify' ),
			),
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_enable]',
	array(
		'default'           => $defaults['sticky_footer_bar_enable'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'type'              => 'option',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_enable]',
		array(
			'label'    => __( 'Enable', 'woostify' ),
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => 'woostify_setting[sticky_footer_bar_enable]',
			'tab'      => 'general',
		)
	)
);

// Hide when scroll.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_hide_when_scroll]',
	array(
		'default'           => $defaults['sticky_footer_bar_hide_when_scroll'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_hide_when_scroll]',
		array(
			'label'    => __( 'Hide When Scroll', 'woostify' ),
			'settings' => 'woostify_setting[sticky_footer_bar_hide_when_scroll]',
			'section'  => 'woostify_sticky_footer_bar',
			'tab'      => 'general',
		)
	)
);

// Hide on product single page.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_hide_on_product_single]',
	array(
		'default'           => $defaults['sticky_footer_bar_hide_on_product_single'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_hide_on_product_single]',
		array(
			'label'    => __( 'Hide On Product Single Page', 'woostify' ),
			'settings' => 'woostify_setting[sticky_footer_bar_hide_on_product_single]',
			'section'  => 'woostify_sticky_footer_bar',
			'tab'      => 'general',
		)
	)
);

// Hide on cart page.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_hide_on_cart_page]',
	array(
		'default'           => $defaults['sticky_footer_bar_hide_on_cart_page'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_hide_on_cart_page]',
		array(
			'label'    => __( 'Hide On Cart Page', 'woostify' ),
			'settings' => 'woostify_setting[sticky_footer_bar_hide_on_cart_page]',
			'section'  => 'woostify_sticky_footer_bar',
			'tab'      => 'general',
		)
	)
);

// Hide on checkout page.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_hide_on_checkout_page]',
	array(
		'default'           => $defaults['sticky_footer_bar_hide_on_checkout_page'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_hide_on_checkout_page]',
		array(
			'label'    => __( 'Hide On Checkout Page', 'woostify' ),
			'settings' => 'woostify_setting[sticky_footer_bar_hide_on_checkout_page]',
			'section'  => 'woostify_sticky_footer_bar',
			'tab'      => 'general',
		)
	)
);

// Enable on devices.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_enable_on]',
	array(
		'default'           => $defaults['sticky_footer_bar_enable_on'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_enable_on]',
		array(
			'label'    => __( 'Enable On', 'woostify' ),
			'settings' => 'woostify_setting[sticky_footer_bar_enable_on]',
			'section'  => 'woostify_sticky_footer_bar',
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

$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_items]',
	array(
		'default'           => $defaults['sticky_footer_bar_items'],
		'sanitize_callback' => 'sanitize_text_field',
		'type'              => 'option',
	)
);

$wp_customize->add_control(
	new Woostify_Adv_List_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_items]',
		array(
			'label'    => __( 'Items', 'woostify' ),
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => 'woostify_setting[sticky_footer_bar_items]',
			'tab'      => 'general',
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_background]',
	array(
		'default'           => $defaults['sticky_footer_bar_background'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_background]',
		array(
			'label'    => __( 'Background', 'woostify' ),
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => array(
				'woostify_setting[sticky_footer_bar_background]',
			),
			'tab'      => 'design',
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_icon_color]',
	array(
		'default'           => $defaults['sticky_footer_bar_icon_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_icon_hover_color]',
	array(
		'default'           => $defaults['sticky_footer_bar_icon_hover_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_icon_color]',
		array(
			'label'    => __( 'Icon', 'woostify' ),
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => array(
				'woostify_setting[sticky_footer_bar_icon_color]',
				'woostify_setting[sticky_footer_bar_icon_hover_color]',
			),
			'tooltips' => array(
				'Normal',
				'Hover',
			),
			'tab'      => 'design',
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_text_color]',
	array(
		'default'           => $defaults['sticky_footer_bar_text_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_text_hover_color]',
	array(
		'default'           => $defaults['sticky_footer_bar_text_hover_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_text_color]',
		array(
			'label'    => __( 'Text', 'woostify' ),
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => array(
				'woostify_setting[sticky_footer_bar_text_color]',
				'woostify_setting[sticky_footer_bar_text_hover_color]',
			),
			'tooltips' => array(
				'Normal',
				'Hover',
			),
			'tab'      => 'design',
		)
	)
);

// Icon font size.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_icon_font_size]',
	array(
		'default'           => $defaults['sticky_footer_bar_icon_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[tablet_sticky_footer_bar_icon_font_size]',
	array(
		'default'           => $defaults['tablet_sticky_footer_bar_icon_font_size'],
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[mobile_sticky_footer_bar_icon_font_size]',
	array(
		'default'           => $defaults['mobile_sticky_footer_bar_icon_font_size'],
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_icon_font_size]',
		array(
			'type'     => 'woostify-range-slider',
			'label'    => __( 'Icon Size', 'woostify' ),
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => array(
				'desktop' => 'woostify_setting[sticky_footer_bar_icon_font_size]',
				'tablet'  => 'woostify_setting[tablet_sticky_footer_bar_icon_font_size]',
				'mobile'  => 'woostify_setting[mobile_sticky_footer_bar_icon_font_size]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_icon_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_icon_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'tablet'  => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_icon_font_size_tablet_min_step', 10 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_icon_font_size_tablet_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'mobile'  => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_icon_font_size_mobile_min_step', 10 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_icon_font_size_mobile_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Icon spacing.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_icon_spacing]',
	array(
		'default'           => $defaults['sticky_footer_bar_icon_spacing'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[tablet_sticky_footer_bar_icon_spacing]',
	array(
		'default'           => $defaults['tablet_sticky_footer_bar_icon_spacing'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[mobile_sticky_footer_bar_icon_spacing]',
	array(
		'default'           => $defaults['mobile_sticky_footer_bar_icon_spacing'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_icon_spacing]',
		array(
			'type'     => 'woostify-range-slider',
			'label'    => __( 'Icon Spacing', 'woostify' ),
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => array(
				'desktop' => 'woostify_setting[sticky_footer_bar_icon_spacing]',
				'tablet'  => 'woostify_setting[tablet_sticky_footer_bar_icon_spacing]',
				'mobile'  => 'woostify_setting[mobile_sticky_footer_bar_icon_spacing]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_icon_spacing_min_step', 0 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_icon_spacing_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'tablet'  => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_icon_spacing_tablet_min_step', 0 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_icon_spacing_tablet_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'mobile'  => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_icon_spacing_mobile_min_step', 0 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_icon_spacing_mobile_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Text font size.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_text_font_size]',
	array(
		'default'           => $defaults['sticky_footer_bar_text_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[tablet_sticky_footer_bar_text_font_size]',
	array(
		'default'           => $defaults['tablet_sticky_footer_bar_text_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[mobile_sticky_footer_bar_text_font_size]',
	array(
		'default'           => $defaults['mobile_sticky_footer_bar_text_font_size'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_text_font_size]',
		array(
			'type'     => 'woostify-range-slider',
			'label'    => __( 'Text Font Size', 'woostify' ),
			'section'  => 'woostify_sticky_footer_bar',
			'settings' => array(
				'desktop' => 'woostify_setting[sticky_footer_bar_text_font_size]',
				'tablet'  => 'woostify_setting[tablet_sticky_footer_bar_text_font_size]',
				'mobile'  => 'woostify_setting[mobile_sticky_footer_bar_text_font_size]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_text_font_size_min_step', 10 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_text_font_size_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'tablet'  => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_text_font_size_tablet_min_step', 10 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_text_font_size_tablet_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'mobile'  => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_text_font_size_mobile_min_step', 10 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_text_font_size_mobile_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
			'tab'      => 'design',
		)
	)
);

// Text font weight.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_text_font_weight]',
	array(
		'default'           => $defaults['sticky_footer_bar_text_font_weight'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_text_font_weight]',
		array(
			'label'    => __( 'Text Font Weight', 'woostify' ),
			'settings' => 'woostify_setting[sticky_footer_bar_text_font_weight]',
			'section'  => 'woostify_sticky_footer_bar',
			'type'     => 'select',
			'choices'  => array(
				'300' => __( '300', 'woostify' ),
				'400' => __( '400', 'woostify' ),
				'500' => __( '500', 'woostify' ),
				'600' => __( '600', 'woostify' ),
				'700' => __( '700', 'woostify' ),
				'800' => __( '800', 'woostify' ),
				'900' => __( '900', 'woostify' ),
			),
			'tab'      => 'design',
		)
	)
);

// padding.
$wp_customize->add_setting(
	'woostify_setting[sticky_footer_bar_padding]',
	array(
		'default'           => $defaults['sticky_footer_bar_padding'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[tablet_sticky_footer_bar_padding]',
	array(
		'default'           => $defaults['tablet_sticky_footer_bar_padding'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_setting(
	'woostify_setting[mobile_sticky_footer_bar_padding]',
	array(
		'default'           => $defaults['mobile_sticky_footer_bar_padding'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Group_Control(
		$wp_customize,
		'woostify_setting[sticky_footer_bar_padding]',
		array(
			'label'          => __( 'Spacing', 'woostify' ),
			'section'        => 'woostify_sticky_footer_bar',
			'settings'       => array(
				'desktop' => 'woostify_setting[sticky_footer_bar_padding]',
				'tablet'  => 'woostify_setting[tablet_sticky_footer_bar_padding]',
				'mobile'  => 'woostify_setting[mobile_sticky_footer_bar_padding]',
			),
			'choices'        => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_padding_min_step', 0 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_padding_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'tablet'  => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_padding_tablet_min_step', 0 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_padding_tablet_max_step', 100 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
				'mobile'  => array(
					'min'  => apply_filters( 'woostify_sticky_footer_bar_padding_mobile_min_step', 0 ),
					'max'  => apply_filters( 'woostify_sticky_footer_bar_padding_mobile_max_step', 100 ),
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
