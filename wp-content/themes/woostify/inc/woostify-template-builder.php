<?php
/**
 * Theme Builder
 *
 * @package woostify
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'woostify_template_header' ) ) {
	/**
	 * Header template
	 */
	function woostify_template_header() {
		if ( function_exists( 'boostify_header_active' ) && boostify_header_active() ) {
			boostify_get_header_template();
		} else {
			get_template_part( 'template-parts/header' );
		}
	}
}

if ( ! function_exists( 'woostify_template_footer' ) ) {
	/**
	 * Footer template
	 */
	function woostify_template_footer() {
		if ( function_exists( 'boostify_footer_active' ) && boostify_footer_active() ) {
			boostify_get_footer_template();
		} else {
			get_template_part( 'template-parts/footer' );
		}
	}
}

if ( ! function_exists( 'woostify_template_single' ) ) {
	/**
	 * Single template
	 */
	function woostify_template_single() {
		get_template_part( 'template-parts/single' );
	}
}

if ( ! function_exists( 'woostify_template_archive' ) ) {
	/**
	 * Archive template
	 */
	function woostify_template_archive() {
		get_template_part( 'template-parts/archive' );
	}
}

if ( ! function_exists( 'woostify_template_404' ) ) {
	/**
	 * 404 template
	 */
	function woostify_template_404() {
		get_template_part( 'template-parts/404' );
	}
}
