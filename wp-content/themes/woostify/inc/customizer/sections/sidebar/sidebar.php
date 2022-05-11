<?php
/**
 * Sidebar customizer
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// Default sidebar.
$wp_customize->add_setting(
	'woostify_setting[sidebar_default]',
	array(
		'default'           => $defaults['sidebar_default'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[sidebar_default]',
		array(
			'label'    => __( 'Default', 'woostify' ),
			'section'  => 'woostify_sidebar',
			'settings' => 'woostify_setting[sidebar_default]',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_sidebar_default_choices',
				array(
					'full'  => __( 'No sidebar', 'woostify' ),
					'left'  => __( 'Left sidebar', 'woostify' ),
					'right' => __( 'Right sidebar', 'woostify' ),
				)
			),
		)
	)
);

// Page sidebar.
$wp_customize->add_setting(
	'woostify_setting[sidebar_page]',
	array(
		'default'           => $defaults['sidebar_page'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[sidebar_page]',
		array(
			'label'    => __( 'Page', 'woostify' ),
			'section'  => 'woostify_sidebar',
			'settings' => 'woostify_setting[sidebar_page]',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_sidebar_page_choices',
				array(
					'default' => __( 'Default', 'woostify' ),
					'full'    => __( 'No sidebar', 'woostify' ),
					'left'    => __( 'Left sidebar', 'woostify' ),
					'right'   => __( 'Right sidebar', 'woostify' ),
				)
			),
		)
	)
);

// Blog sidebar divider.
$wp_customize->add_setting(
	'blog_sidebar_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'blog_sidebar_divider',
		array(
			'section'  => 'woostify_sidebar',
			'settings' => 'blog_sidebar_divider',
			'type'     => 'divider',
		)
	)
);

// Blog archive sidebar.
$wp_customize->add_setting(
	'woostify_setting[sidebar_blog]',
	array(
		'default'           => $defaults['sidebar_blog'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[sidebar_blog]',
		array(
			'label'    => __( 'Blog List', 'woostify' ),
			'section'  => 'woostify_sidebar',
			'settings' => 'woostify_setting[sidebar_blog]',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_sidebar_blog_choices',
				array(
					'default' => __( 'Default', 'woostify' ),
					'full'    => __( 'No sidebar', 'woostify' ),
					'left'    => __( 'Left sidebar', 'woostify' ),
					'right'   => __( 'Right sidebar', 'woostify' ),
				)
			),
		)
	)
);

// Blog single sidebar.
$wp_customize->add_setting(
	'woostify_setting[sidebar_blog_single]',
	array(
		'default'           => $defaults['sidebar_blog_single'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[sidebar_blog_single]',
		array(
			'label'    => __( 'Blog Single', 'woostify' ),
			'section'  => 'woostify_sidebar',
			'settings' => 'woostify_setting[sidebar_blog_single]',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_sidebar_blog_single_choices',
				array(
					'default' => __( 'Default', 'woostify' ),
					'full'    => __( 'No sidebar', 'woostify' ),
					'left'    => __( 'Left sidebar', 'woostify' ),
					'right'   => __( 'Right sidebar', 'woostify' ),
				)
			),
		)
	)
);

if ( class_exists( 'woocommerce' ) ) {
	// woocommerce divider.
	$wp_customize->add_setting(
		'woocommerce_sidebar_divider',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Woostify_Divider_Control(
			$wp_customize,
			'woocommerce_sidebar_divider',
			array(
				'section'  => 'woostify_sidebar',
				'settings' => 'woocommerce_sidebar_divider',
				'type'     => 'divider',
			)
		)
	);

	// Shop page sidebar.
	$wp_customize->add_setting(
		'woostify_setting[sidebar_shop]',
		array(
			'default'           => $defaults['sidebar_shop'],
			'sanitize_callback' => 'woostify_sanitize_choices',
			'type'              => 'option',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'woostify_setting[sidebar_shop]',
			array(
				'label'    => __( 'Shop/Product Archive', 'woostify' ),
				'section'  => 'woostify_sidebar',
				'settings' => 'woostify_setting[sidebar_shop]',
				'type'     => 'select',
				'choices'  => apply_filters(
					'woostify_setting_sidebar_shop_choices',
					array(
						'default'   => __( 'Default', 'woostify' ),
						'full'      => __( 'No sidebar', 'woostify' ),
						'left'      => __( 'Left sidebar', 'woostify' ),
						'right'     => __( 'Right sidebar', 'woostify' ),
						'offcanvas' => __( 'Offcanvas sidebar', 'woostify' ),
					)
				),
			)
		)
	);

	// Product page sidebar.
	$wp_customize->add_setting(
		'woostify_setting[sidebar_shop_single]',
		array(
			'default'           => $defaults['sidebar_shop_single'],
			'sanitize_callback' => 'woostify_sanitize_choices',
			'type'              => 'option',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'woostify_setting[sidebar_shop_single]',
			array(
				'label'    => __( 'Shop Single', 'woostify' ),
				'section'  => 'woostify_sidebar',
				'settings' => 'woostify_setting[sidebar_shop_single]',
				'type'     => 'select',
				'choices'  => apply_filters(
					'woostify_setting_sidebar_shop_single_choices',
					array(
						'default' => __( 'Default', 'woostify' ),
						'full'    => __( 'No sidebar', 'woostify' ),
						'left'    => __( 'Left sidebar', 'woostify' ),
						'right'   => __( 'Right sidebar', 'woostify' ),
					)
				),
			)
		)
	);
}

// Blog sidebar divider.
$wp_customize->add_setting(
	'width_sidebar_divider',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Divider_Control(
		$wp_customize,
		'width_sidebar_divider',
		array(
			'section'  => 'woostify_sidebar',
			'settings' => 'width_sidebar_divider',
			'type'     => 'divider',
		)
	)
);

// Width.
$wp_customize->add_setting(
	'woostify_setting[sidebar_width]',
	array(
		'default'           => $defaults['sidebar_width'],
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[sidebar_width]',
		array(
			'label'    => __( 'Sidebar Width', 'woostify' ),
			'section'  => 'woostify_sidebar',
			'settings' => array(
				'desktop' => 'woostify_setting[sidebar_width]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_sidebar_width_min_step', 0 ),
					'max'  => apply_filters( 'woostify_sidebar_width_max_step', 50 ),
					'step' => 1,
					'edit' => true,
					'unit' => '%',
				),
			),
		)
	)
);
