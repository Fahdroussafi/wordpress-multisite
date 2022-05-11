<?php
/**
 * Site container
 *
 * @package woostify
 */

// Default values.
$defaults = woostify_options();

// background color.
$wp_customize->get_setting( 'background_color' )->default = $defaults['background_color'];
$wp_customize->remove_control( 'background_color' );

$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'background_color',
		array(
			'label'          => __( 'Background Color', 'woostify' ),
			'section'        => 'background_image',
			'settings'       => array(
				'background_color',
			),
			'color_format'   => 'hex',
			'enable_opacity' => false,
			'priority'       => 5,
			'prefix'         => '',
		)
	)
);
