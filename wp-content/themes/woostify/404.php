<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package woostify
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
	do_action( 'woostify_theme_404' );
}

get_footer();
