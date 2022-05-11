<?php
/**
 * Override default customizer panels, sections, settings or controls.
 *
 * @package     Woostify
 */

// Change background image section title & priority.
$wp_customize->get_section( 'background_image' )->panel    = 'woostify_layout';
$wp_customize->get_section( 'background_image' )->title    = __( 'Site Container', 'woostify' );
$wp_customize->get_section( 'background_image' )->priority = 10;

$wp_customize->get_control( 'background_image' )->priority = 6;

// Remove description on Site Icon.
$wp_customize->get_control( 'site_icon' )->description = '';

$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

// Chage Woocommerce panel priority, after Typography panel.
if ( class_exists( 'woocommerce' ) ) {
	$wp_customize->get_panel( 'woocommerce' )->priority = 40;
}
