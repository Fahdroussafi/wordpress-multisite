<?php
/**
 * Site Title & Tagline
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Default container.
$default_container = array(
	'normal'               => __( 'Normal', 'woostify' ),
	'boxed'                => __( 'Boxed', 'woostify' ),
	'content-boxed'        => __( 'Content Boxed', 'woostify' ),
	'full-width'           => __( 'Full Width / Contained', 'woostify' ),
	'full-width-stretched' => __( 'Full Width / Stretched', 'woostify' ),
);

// Other container.
$other_container = array(
	'default'              => __( 'Default', 'woostify' ),
	'normal'               => __( 'Normal', 'woostify' ),
	'boxed'                => __( 'Boxed', 'woostify' ),
	'content-boxed'        => __( 'Content Boxed', 'woostify' ),
	'full-width'           => __( 'Full Width / Contained', 'woostify' ),
	'full-width-stretched' => __( 'Full Width / Stretched', 'woostify' ),
);

// Divider.
$wp_customize->add_setting(
	'site_container_other_element_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'site_container_other_element_divider',
		array(
			'section'  => 'background_image',
			'settings' => 'site_container_other_element_divider',
			'type'     => 'divider',
		)
	)
);

// Container width.
$wp_customize->add_setting(
	'woostify_setting[container_width]',
	array(
		'sanitize_callback' => 'woostify_sanitize_int',
		'default'           => $defaults['container_width'],
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[container_width]',
		array(
			'section'  => 'background_image',
			'settings' => 'woostify_setting[container_width]',
			'type'     => 'number',
			'label'    => __( 'Width', 'woostify' ),
		)
	)
);

// Default contailer.
$wp_customize->add_setting(
	'woostify_setting[default_container]',
	array(
		'type'              => 'option',
		'default'           => $defaults['default_container'],
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[default_container]',
		array(
			'label'    => __( 'Default Container', 'woostify' ),
			'section'  => 'background_image',
			'type'     => 'select',
			'settings' => 'woostify_setting[default_container]',
			'choices'  => $default_container,
		)
	)
);

// Page container.
$wp_customize->add_setting(
	'woostify_setting[page_container]',
	array(
		'type'              => 'option',
		'default'           => $defaults['page_container'],
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[page_container]',
		array(
			'label'    => __( 'Page Container', 'woostify' ),
			'section'  => 'background_image',
			'type'     => 'select',
			'settings' => 'woostify_setting[page_container]',
			'choices'  => $other_container,
		)
	)
);

// Blog single container.
$wp_customize->add_setting(
	'woostify_setting[blog_single_container]',
	array(
		'type'              => 'option',
		'default'           => $defaults['blog_single_container'],
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[blog_single_container]',
		array(
			'label'    => __( 'Blog Single Container', 'woostify' ),
			'section'  => 'background_image',
			'type'     => 'select',
			'settings' => 'woostify_setting[blog_single_container]',
			'choices'  => $other_container,
		)
	)
);

// Archive container.
$wp_customize->add_setting(
	'woostify_setting[archive_container]',
	array(
		'type'              => 'option',
		'default'           => $defaults['archive_container'],
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[archive_container]',
		array(
			'label'    => __( 'Archives Container', 'woostify' ),
			'section'  => 'background_image',
			'type'     => 'select',
			'settings' => 'woostify_setting[archive_container]',
			'choices'  => $other_container,
		)
	)
);

// Shop container.
if ( woostify_is_woocommerce_activated() ) {
	// Divider.
	$wp_customize->add_setting(
		'site_container_end_divider',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Woostify_Divider_Control(
			$wp_customize,
			'site_container_end_divider',
			array(
				'section'  => 'background_image',
				'settings' => 'site_container_end_divider',
				'type'     => 'divider',
			)
		)
	);

	// Shop container.
	$wp_customize->add_setting(
		'woostify_setting[shop_container]',
		array(
			'type'              => 'option',
			'default'           => $defaults['shop_container'],
			'sanitize_callback' => 'woostify_sanitize_choices',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'woostify_setting[shop_container]',
			array(
				'label'    => __( 'Shop Container', 'woostify' ),
				'section'  => 'background_image',
				'type'     => 'select',
				'settings' => 'woostify_setting[shop_container]',
				'choices'  => $other_container,
			)
		)
	);

	// Shop single container.
	$wp_customize->add_setting(
		'woostify_setting[shop_single_container]',
		array(
			'type'              => 'option',
			'default'           => $defaults['shop_single_container'],
			'sanitize_callback' => 'woostify_sanitize_choices',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'woostify_setting[shop_single_container]',
			array(
				'label'    => __( 'Shop Single Container', 'woostify' ),
				'section'  => 'background_image',
				'type'     => 'select',
				'settings' => 'woostify_setting[shop_single_container]',
				'choices'  => $other_container,
			)
		)
	);
}
