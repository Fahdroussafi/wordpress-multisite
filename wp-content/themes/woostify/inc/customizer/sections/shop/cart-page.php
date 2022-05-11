<?php
/**
 * Cart page customizer
 *
 * @package woostify
 */

if ( ! woostify_is_woocommerce_activated() ) {
	return;
}

// Default values.
$defaults = woostify_options();

// Cart page layout.
$wp_customize->add_setting(
	'woostify_setting[cart_page_layout]',
	array(
		'default'           => $defaults['cart_page_layout'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Radio_Image_Control(
		$wp_customize,
		'woostify_setting[cart_page_layout]',
		array(
			'label'    => __( 'Cart Page Layout', 'woostify' ),
			'section'  => 'woostify_cart_page',
			'settings' => 'woostify_setting[cart_page_layout]',
			'choices'  => apply_filters(
				'woostify_setting_cart_page_layout_choices',
				array(
					'layout-1' => WOOSTIFY_THEME_URI . 'assets/images/customizer/cart-page/layout-1.jpg',
					'layout-2' => WOOSTIFY_THEME_URI . 'assets/images/customizer/cart-page/layout-2.jpg',
				)
			),
		)
	)
);

// Sticky proceed to checkout button.
$wp_customize->add_setting(
	'woostify_setting[cart_page_sticky_proceed_button]',
	array(
		'default'           => $defaults['cart_page_sticky_proceed_button'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[cart_page_sticky_proceed_button]',
		array(
			'label'       => __( 'Sticky Proceed To Checkout Button', 'woostify' ),
			'description' => __( 'This option only available on mobile devices', 'woostify' ),
			'settings'    => 'woostify_setting[cart_page_sticky_proceed_button]',
			'section'     => 'woostify_cart_page',
		)
	)
);
