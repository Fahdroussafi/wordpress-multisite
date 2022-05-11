<?php
/**
 * The template for displaying all single posts.
 *
 * @package woostify
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
	do_action( 'woostify_theme_single' );
}

get_footer();
