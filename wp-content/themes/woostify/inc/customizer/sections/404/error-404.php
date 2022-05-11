<?php
/**
 * Woocommerce shop single customizer
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Custom text.
$wp_customize->add_setting(
	'woostify_setting[error_404_text]',
	array(
		'default'           => $defaults['error_404_text'],
		'sanitize_callback' => 'woostify_sanitize_raw_html',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[error_404_text]',
		array(
			'label'    => __( 'Custom Text', 'woostify' ),
			'type'     => 'textarea',
			'section'  => 'woostify_error',
			'settings' => 'woostify_setting[error_404_text]',
		)
	)
);

// Background.
$wp_customize->add_setting(
	'woostify_setting[error_404_image]',
	array(
		'default'           => $defaults['error_404_image'],
		'type'              => 'option',
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'woostify_setting[error_404_image]',
		array(
			'label'    => __( 'Background', 'woostify' ),
			'section'  => 'woostify_error',
			'settings' => 'woostify_setting[error_404_image]',
		)
	)
);
