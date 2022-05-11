<?php
/**
 * Woocommerce mini cart customizer
 *
 * @package woostify
 */

if ( ! woostify_is_woocommerce_activated() ) {
	return;
}

// Default values.
$defaults = woostify_options();

// GENERAL SECTION.
$wp_customize->add_setting(
	'mini_cart_general_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'mini_cart_general_section',
		array(
			'label'      => __( 'General', 'woostify' ),
			'section'    => 'woostify_mini_cart',
			'dependency' => array(
				'woostify_setting[mini_cart_background_color]',
				'woostify_setting[mini_cart_empty_message]',
				'woostify_setting[mini_cart_empty_enable_button]',
			),
		)
	)
);

// Background color.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_background_color]',
	array(
		'default'           => $defaults['mini_cart_background_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[mini_cart_background_color]',
		array(
			'label'    => __( 'Background', 'woostify' ),
			'section'  => 'woostify_mini_cart',
			'settings' => array(
				'woostify_setting[mini_cart_background_color]',
			),
		)
	)
);

// Empty cart message.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_empty_message]',
	array(
		'default'           => $defaults['mini_cart_empty_message'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_raw_html',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[mini_cart_empty_message]',
		array(
			'label'    => __( 'Empty Cart Message', 'woostify' ),
			'settings' => 'woostify_setting[mini_cart_empty_message]',
			'section'  => 'woostify_mini_cart',
			'type'     => 'textarea',
		)
	)
);

// Enable button.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_empty_enable_button]',
	array(
		'type'              => 'option',
		'default'           => $defaults['mini_cart_empty_enable_button'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[mini_cart_empty_enable_button]',
		array(
			'label'    => __( 'Enable Empty Cart Button', 'woostify' ),
			'section'  => 'woostify_mini_cart',
			'settings' => 'woostify_setting[mini_cart_empty_enable_button]',
		)
	)
);

// TOP CONTENT SECTION.
$wp_customize->add_setting(
	'mini_cart_top_content_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'mini_cart_top_content_section',
		array(
			'label'      => __( 'Top content', 'woostify' ),
			'section'    => 'woostify_mini_cart',
			'dependency' => array(
				'woostify_setting[mini_cart_top_content_select]',
				'woostify_setting[mini_cart_top_content_custom_html]',
			),
		)
	)
);

// Select content.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_top_content_select]',
	array(
		'default'           => $defaults['mini_cart_top_content_select'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[mini_cart_top_content_select]',
		array(
			'label'    => __( 'Select Content', 'woostify' ),
			'settings' => 'woostify_setting[mini_cart_top_content_select]',
			'section'  => 'woostify_mini_cart',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_mini_cart_content_choices',
				array(
					''            => __( 'None', 'woostify' ),
					'custom_html' => __( 'Custom HTML', 'woostify' ),
					'fst'         => __( 'Free Shipping Threshold', 'woostify' ),
				)
			),
		)
	)
);

// Top Content Custom HTML.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_top_content_custom_html]',
	array(
		'default'           => $defaults['mini_cart_top_content_custom_html'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_raw_html',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[mini_cart_top_content_custom_html]',
		array(
			'label'    => __( 'Custom HTML', 'woostify' ),
			'settings' => 'woostify_setting[mini_cart_top_content_custom_html]',
			'section'  => 'woostify_mini_cart',
			'type'     => 'textarea',
		)
	)
);

// BEFORE CHECKOUT BUTTON CONTENT SECTION.
$wp_customize->add_setting(
	'mini_cart_before_checkout_button_content_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'mini_cart_before_checkout_button_content_section',
		array(
			'label'      => __( 'Before checkout button', 'woostify' ),
			'section'    => 'woostify_mini_cart',
			'dependency' => array(
				'woostify_setting[mini_cart_before_checkout_button_content_select]',
				'woostify_setting[mini_cart_before_checkout_button_content_custom_html]',
			),
		)
	)
);

// Select content.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_before_checkout_button_content_select]',
	array(
		'default'           => $defaults['mini_cart_before_checkout_button_content_select'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[mini_cart_before_checkout_button_content_select]',
		array(
			'label'    => __( 'Select Content', 'woostify' ),
			'settings' => 'woostify_setting[mini_cart_before_checkout_button_content_select]',
			'section'  => 'woostify_mini_cart',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_mini_cart_content_choices',
				array(
					''            => __( 'None', 'woostify' ),
					'custom_html' => __( 'Custom HTML', 'woostify' ),
					'fst'         => __( 'Free Shipping Threshold', 'woostify' ),
				)
			),
		)
	)
);

// Before checkout button Content Custom HTML.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_before_checkout_button_content_custom_html]',
	array(
		'default'           => $defaults['mini_cart_before_checkout_button_content_custom_html'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_raw_html',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[mini_cart_before_checkout_button_content_custom_html]',
		array(
			'label'    => __( 'Custom HTML', 'woostify' ),
			'settings' => 'woostify_setting[mini_cart_before_checkout_button_content_custom_html]',
			'section'  => 'woostify_mini_cart',
			'type'     => 'textarea',
		)
	)
);

// AFTER CHECKOUT BUTTON CONTENT SECTION.
$wp_customize->add_setting(
	'mini_cart_after_checkout_button_content_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'mini_cart_after_checkout_button_content_section',
		array(
			'label'      => __( 'After checkout button', 'woostify' ),
			'section'    => 'woostify_mini_cart',
			'dependency' => array(
				'woostify_setting[mini_cart_after_checkout_button_content_select]',
				'woostify_setting[mini_cart_after_checkout_button_content_custom_html]',
			),
		)
	)
);

// Select content.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_after_checkout_button_content_select]',
	array(
		'default'           => $defaults['mini_cart_after_checkout_button_content_select'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[mini_cart_after_checkout_button_content_select]',
		array(
			'label'    => __( 'Select Content', 'woostify' ),
			'settings' => 'woostify_setting[mini_cart_after_checkout_button_content_select]',
			'section'  => 'woostify_mini_cart',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_mini_cart_content_choices',
				array(
					''            => __( 'None', 'woostify' ),
					'custom_html' => __( 'Custom HTML', 'woostify' ),
					'fst'         => __( 'Free Shipping Threshold', 'woostify' ),
				)
			),
		)
	)
);

// After checkout button Content Custom HTML.
$wp_customize->add_setting(
	'woostify_setting[mini_cart_after_checkout_button_content_custom_html]',
	array(
		'default'           => $defaults['mini_cart_after_checkout_button_content_custom_html'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_raw_html',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[mini_cart_after_checkout_button_content_custom_html]',
		array(
			'label'    => __( 'Custom HTML', 'woostify' ),
			'settings' => 'woostify_setting[mini_cart_after_checkout_button_content_custom_html]',
			'section'  => 'woostify_mini_cart',
			'type'     => 'textarea',
		)
	)
);
