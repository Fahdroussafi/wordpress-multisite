<?php
/**
 * Checkout page customizer
 *
 * @package woostify
 */

if ( ! woostify_is_woocommerce_activated() ) {
	return;
}

// Default values.
$defaults = woostify_options();

// Checkout page layout.
$wp_customize->add_setting(
	'woostify_setting[checkout_page_layout]',
	array(
		'default'           => $defaults['checkout_page_layout'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Radio_Image_Control(
		$wp_customize,
		'woostify_setting[checkout_page_layout]',
		array(
			'label'    => __( 'Checkout Page Layout', 'woostify' ),
			'section'  => 'woocommerce_checkout',
			'settings' => 'woostify_setting[checkout_page_layout]',
			'choices'  => apply_filters(
				'woostify_setting_checkout_page_layout_choices',
				array(
					'layout-1' => WOOSTIFY_THEME_URI . 'assets/images/customizer/checkout-page/layout-default.png',
					'layout-2' => WOOSTIFY_THEME_URI . 'assets/images/customizer/checkout-page/layout-multistep.png',
					'layout-3' => WOOSTIFY_THEME_URI . 'assets/images/customizer/checkout-page/layout-minimal.png',
				)
			),
			'priority' => 0,
		)
	)
);

// Distraction Free Checkout.
$wp_customize->add_setting(
	'woostify_setting[checkout_distraction_free]',
	array(
		'default'           => $defaults['checkout_distraction_free'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[checkout_distraction_free]',
		array(
			'label'    => __( 'Distraction Free Checkout', 'woostify' ),
			'settings' => 'woostify_setting[checkout_distraction_free]',
			'section'  => 'woocommerce_checkout',
			'priority' => 0,
		)
	)
);

// Sticky place order button.
$wp_customize->add_setting(
	'woostify_setting[checkout_sticky_place_order_button]',
	array(
		'default'           => $defaults['checkout_sticky_place_order_button'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[checkout_sticky_place_order_button]',
		array(
			'label'       => __( 'Sticky Place Order Button', 'woostify' ),
			'description' => __( 'This option only available on mobile devices', 'woostify' ),
			'settings'    => 'woostify_setting[checkout_sticky_place_order_button]',
			'section'     => 'woocommerce_checkout',
			'priority'    => 0,
		)
	)
);

// Theme checkout divider.
$wp_customize->add_setting(
	'woostify_checkout_start',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'woostify_checkout_start',
		array(
			'section'  => 'woocommerce_checkout',
			'settings' => 'woostify_checkout_start',
			'type'     => 'divider',
			'priority' => 0,
		)
	)
);
