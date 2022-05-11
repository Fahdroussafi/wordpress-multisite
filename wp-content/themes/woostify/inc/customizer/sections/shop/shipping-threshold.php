<?php
/**
 * Woocommerce Shipping Threshold customizer
 *
 * @package woostify
 */

if ( ! woostify_is_woocommerce_activated() ) {
	return;
}

// Default values.
$defaults = woostify_options();

// Tabs.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_context_tabs]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Woostify_Tabs_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_context_tabs]',
		array(
			'section'  => 'woostify_shipping_threshold',
			'settings' => 'woostify_setting[shipping_threshold_context_tabs]',
			'choices'  => array(
				'general' => __( 'General', 'woostify' ),
				'design'  => __( 'Design', 'woostify' ),
			),
		)
	)
);

// Enable shipping threshold.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_enabled]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shipping_threshold_enabled'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_enabled]',
		array(
			'label'    => __( 'Enable Shipping Threshold', 'woostify' ),
			'section'  => 'woostify_shipping_threshold',
			'settings' => 'woostify_setting[shipping_threshold_enabled]',
			'tab'      => 'general',
		)
	)
);

// Enable confetti effect.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_enable_confetti_effect]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shipping_threshold_enable_confetti_effect'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_enable_confetti_effect]',
		array(
			'label'       => __( 'Enable Confetti Effect', 'woostify' ),
			'description' => __( 'Show confetti effect when reach to 100%', 'woostify' ),
			'section'     => 'woostify_shipping_threshold',
			'settings'    => 'woostify_setting[shipping_threshold_enable_confetti_effect]',
			'tab'         => 'general',
		)
	)
);

// Enable progress bar.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_enable_progress_bar]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shipping_threshold_enable_progress_bar'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_enable_progress_bar]',
		array(
			'label'    => __( 'Enable Progress Bar', 'woostify' ),
			'section'  => 'woostify_shipping_threshold',
			'settings' => 'woostify_setting[shipping_threshold_enable_progress_bar]',
			'tab'      => 'general',
		)
	)
);

// Amount.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_progress_bar_amount]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shipping_threshold_progress_bar_amount'],
		'sanitize_callback' => 'woostify_sanitize_abs_number',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_progress_bar_amount]',
		array(
			'label'       => __( 'Goal Amount', 'woostify' ),
			'description' => __( 'Amount to reach 100%. Note: use <b><code>.</code></b> for Decimal separator', 'woostify' ),
			'type'        => 'number',
			'section'     => 'woostify_shipping_threshold',
			'settings'    => 'woostify_setting[shipping_threshold_progress_bar_amount]',
			'tab'         => 'general',
		)
	)
);

// Initial Message.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_progress_bar_initial_msg]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shipping_threshold_progress_bar_initial_msg'],
		'sanitize_callback' => 'woostify_sanitize_raw_html',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_progress_bar_initial_msg]',
		array(
			'label'       => __( 'Initial Message', 'woostify' ),
			'description' => __( 'Message to show before reaching the goal. Use shortcode <b><code>[missing_amount]</code></b> to display the amount left to reach the minimum', 'woostify' ),
			'type'        => 'textarea',
			'section'     => 'woostify_shipping_threshold',
			'settings'    => 'woostify_setting[shipping_threshold_progress_bar_initial_msg]',
			'tab'         => 'general',
		)
	)
);

// Success Message.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_progress_bar_success_msg]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shipping_threshold_progress_bar_success_msg'],
		'sanitize_callback' => 'woostify_sanitize_raw_html',
	)
);
$wp_customize->add_control(
	new Woostify_Customize_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_progress_bar_success_msg]',
		array(
			'label'       => __( 'Success Message', 'woostify' ),
			'description' => __( 'Message to show after reaching 100%.', 'woostify' ),
			'type'        => 'textarea',
			'section'     => 'woostify_shipping_threshold',
			'settings'    => 'woostify_setting[shipping_threshold_progress_bar_success_msg]',
			'tab'         => 'general',
		)
	)
);

// Message initial color.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_message_color]',
	array(
		'default'           => $defaults['shipping_threshold_message_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

// Message success color.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_message_success_color]',
	array(
		'default'           => $defaults['shipping_threshold_message_success_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_message_color]',
		array(
			'label'    => __( 'Message Color', 'woostify' ),
			'section'  => 'woostify_shipping_threshold',
			'settings' => array(
				'woostify_setting[shipping_threshold_message_color]',
				'woostify_setting[shipping_threshold_message_success_color]',
			),
			'tooltips' => array(
				'Initial',
				'Success',
			),
			'tab'      => 'design',
		)
	)
);

// Progress bar initial color.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_progress_bar_color]',
	array(
		'default'           => $defaults['shipping_threshold_progress_bar_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
// Progress bar success color.
$wp_customize->add_setting(
	'woostify_setting[shipping_threshold_progress_bar_success_color]',
	array(
		'default'           => $defaults['shipping_threshold_progress_bar_success_color'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[shipping_threshold_progress_bar_color]',
		array(
			'label'    => __( 'Progress Bar Color', 'woostify' ),
			'section'  => 'woostify_shipping_threshold',
			'settings' => array(
				'woostify_setting[shipping_threshold_progress_bar_color]',
				'woostify_setting[shipping_threshold_progress_bar_success_color]',
			),
			'tooltips' => array(
				'Initial',
				'Success',
			),
			'tab'      => 'design',
		)
	)
);
