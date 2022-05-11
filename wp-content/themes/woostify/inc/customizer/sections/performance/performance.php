<?php
/**
 * Performance customizer
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();


$wp_customize->add_setting(
	'woostify_setting[enabled_dynamic_css]',
	array(
		'default'           => $defaults['enabled_dynamic_css'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'type'              => 'option',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[enabled_dynamic_css]',
		array(
			'label'    => __( 'Enable Dynamic CSS', 'woostify' ),
			'section'  => 'woostify_performance',
			'settings' => 'woostify_setting[enabled_dynamic_css]',
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[reset_dynamic_css_file]',
	array(
		'default'           => '',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Button_Control(
		$wp_customize,
		'woostify_setting[reset_dynamic_css_file]',
		array(
			'label'        => __( 'Dynamic CSS Print Method', 'woostify' ),
			'description'  => __( 'Click the button to resset dynamic css file.', 'woostify' ),
			'section'      => 'woostify_performance',
			'settings'     => 'woostify_setting[reset_dynamic_css_file]',
			'button_label' => __( 'Reset Dynamic CSS File', 'woostify' ),
			'button_class' => 'button-secondary woostify-reset-dynamic-css',
			'button_link'  => 'javascript:;',
			'ajax_action'  => 'woostify_reset_dynamic_stylesheet_folder',
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[load_google_fonts_locally]',
	array(
		'default'           => $defaults['load_google_fonts_locally'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'type'              => 'option',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[load_google_fonts_locally]',
		array(
			'label'    => __( 'Load Google Fonts Locally', 'woostify' ),
			'section'  => 'woostify_performance',
			'settings' => 'woostify_setting[load_google_fonts_locally]',
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[load_google_fonts_locally_preload]',
	array(
		'default'           => $defaults['load_google_fonts_locally_preload'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'type'              => 'option',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[load_google_fonts_locally_preload]',
		array(
			'label'    => __( 'Preload Local Font', 'woostify' ),
			'section'  => 'woostify_performance',
			'settings' => 'woostify_setting[load_google_fonts_locally_preload]',
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[load_google_fonts_locally_clear]',
	array(
		'default'           => '',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Button_Control(
		$wp_customize,
		'woostify_setting[load_google_fonts_locally_clear]',
		array(
			'label'        => __( 'Clear Local Fonts Cache', 'woostify' ),
			'description'  => __( 'Click the button to reset the local fonts cache.', 'woostify' ),
			'section'      => 'woostify_performance',
			'settings'     => 'woostify_setting[load_google_fonts_locally_clear]',
			'button_label' => __( 'Clear', 'woostify' ),
			'button_class' => 'button-secondary woostify-clear-font-files',
			'button_link'  => 'javascript:;',
			'ajax_action'  => 'woostify_regenerate_fonts_folder',
		)
	)
);

$wp_customize->add_setting(
	'woostify_setting[performance_disable_woo_blocks_styles]',
	array(
		'default'           => $defaults['performance_disable_woo_blocks_styles'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[performance_disable_woo_blocks_styles]',
		array(
			'label'    => __( 'Disable Woocommerce Blocks CSS', 'woostify' ),
			'section'  => 'woostify_performance',
			'settings' => 'woostify_setting[performance_disable_woo_blocks_styles]',
		)
	)
);
