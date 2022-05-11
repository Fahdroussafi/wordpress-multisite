<?php
/**
 * Woostify hooks
 *
 * @package woostify
 */

defined( 'ABSPATH' ) || exit;

/**
 * General
 */
add_action( 'woostify_sidebar', 'woostify_get_sidebar', 10 );

// Head tag.
add_action( 'wp_head', 'woostify_meta_charset', 0 );
add_action( 'wp_head', 'woostify_meta_viewport', 220 );
add_action( 'wp_head', 'woostify_rel_profile', 230 );
add_action( 'wp_head', 'woostify_facebook_social', 240 );
add_action( 'wp_head', 'woostify_pingback', 250 );

// Performance.
add_action( 'wp_enqueue_scripts', 'woostify_dequeue_scripts_and_styles' );

/**
 * Header
 */
add_action( 'woostify_theme_header', 'woostify_template_header' );
add_action( 'woostify_theme_header', 'woostify_after_header', 100 );

// Header template part.
add_action( 'woostify_template_part_header', 'woostify_view_open', 10 ); // Open #view.
add_action( 'woostify_template_part_header', 'woostify_topbar', 20 );
add_action( 'woostify_template_part_header', 'woostify_site_header', 30 );

// Inside @woostify_site_header hook.
add_action( 'woostify_site_header', 'woostify_default_container_open', 0 );
add_action( 'woostify_site_header', 'woostify_skip_links', 5 );
add_action( 'woostify_site_header', 'woostify_menu_toggle_btn', 10 );
add_action( 'woostify_site_header', 'woostify_site_branding', 20 );
add_action( 'woostify_site_header', 'woostify_primary_navigation', 30 );
add_action( 'woostify_site_header', 'woostify_header_action', 50 );
add_action( 'woostify_site_header', 'woostify_default_container_close', 200 );

// Inside @woostify_after_header hook.
add_action( 'woostify_after_header', 'woostify_page_header', 10 );
add_action( 'woostify_after_header', 'woostify_content_open', 20 ); // Open #content.
add_action( 'woostify_after_header', 'woostify_content_top', 30 );
add_action( 'woostify_after_header', 'woostify_container_open', 40 ); // Open .container.

// Inside @woostify_content_top hook.
add_action( 'woostify_content_top', 'woostify_content_top_open', 10 );
add_action( 'woostify_content_top', 'woostify_container_open', 20 );
add_action( 'woostify_content_top', 'woostify_container_close', 60 );
add_action( 'woostify_content_top', 'woostify_content_top_close', 70 );

/**
 * Page Header
 */
add_action( 'woostify_page_header_breadcrumb', 'woostify_breadcrumb', 10 );

/**
 * Footer
 */
add_action( 'woostify_theme_footer', 'woostify_before_footer', 0 );
add_action( 'woostify_theme_footer', 'woostify_template_footer' );
add_action( 'woostify_theme_footer', 'woostify_after_footer', 100 );

// Footer template part.
add_action( 'woostify_template_part_footer', 'woostify_site_footer', 10 );

// Inside @woostify_before_footer hook.
add_action( 'woostify_before_footer', 'woostify_container_close', 10 ); // Close .container.
add_action( 'woostify_before_footer', 'woostify_content_close', 10 ); // Close #content.
add_action( 'woostify_before_footer', 'woostify_sticky_footer_bar', 15 );

// Inside @woostify_after_footer hook.
add_action( 'woostify_after_footer', 'woostify_view_close', 0 ); // Close #view.
add_action( 'woostify_after_footer', 'woostify_sticky_footer_bar', 5 );
add_action( 'woostify_after_footer', 'woostify_toggle_sidebar', 10 );
add_action( 'woostify_after_footer', 'woostify_overlay', 20 );
add_action( 'woostify_after_footer', 'woostify_footer_action', 20 );
add_action( 'woostify_after_footer', 'woostify_dialog_search', 30 );
add_action( 'woostify_after_footer', 'woostify_account_login_lightbox', 40 ); // Woostify popup login.

// Inside @woostify_footer_action hook.
add_action( 'woostify_footer_action', 'woostify_scroll_to_top', 40 );

// Inside @woostify_site_footer hook.
add_action( 'woostify_footer_content', 'woostify_footer_widgets', 10 );
add_action( 'woostify_footer_content', 'woostify_credit', 20 );

// Inside @woostify_toggle_sidebar hook.
add_action( 'woostify_toggle_sidebar', 'woostify_sidebar_menu_open', 10 );
add_action( 'woostify_toggle_sidebar', 'woostify_mobile_menu_tab', 15 );
add_action( 'woostify_toggle_sidebar', 'woostify_search', 20 );
add_action( 'woostify_toggle_sidebar', 'woostify_primary_navigation', 30 );
add_action( 'woostify_toggle_sidebar', 'woostify_sidebar_menu_action', 40 );
add_action( 'woostify_toggle_sidebar', 'woostify_sidebar_menu_close', 50 );

/**
 * Posts
 */
add_action( 'woostify_loop_post', 'woostify_post_loop_image_thumbnail', 10 );
add_action( 'woostify_loop_post', 'woostify_post_loop_inner_open', 20 );
add_action( 'woostify_loop_post', 'woostify_post_header_open', 30 );
add_action( 'woostify_loop_post', 'woostify_post_structure', 40 );
add_action( 'woostify_loop_post', 'woostify_post_header_close', 50 );
add_action( 'woostify_loop_post', 'woostify_post_content', 60 );
add_action( 'woostify_loop_post', 'woostify_post_loop_inner_close', 70 );

add_action( 'woostify_loop_after', 'woostify_paging_nav', 10 );
add_action( 'woostify_post_content_after', 'woostify_post_read_more_button', 10 );

add_action( 'woostify_single_post', 'woostify_post_single_structure', 10 );
add_action( 'woostify_single_post', 'woostify_post_content', 20 );
add_action( 'woostify_single_post', 'woostify_post_tags', 30 );

add_action( 'woostify_single_post_after', 'woostify_post_nav', 10 );
add_action( 'woostify_single_post_after', 'woostify_post_author_box', 20 );
add_action( 'woostify_single_post_after', 'woostify_post_related', 30 );
add_action( 'woostify_single_post_after', 'woostify_display_comments', 40 );

/**
 * Pages
 */
add_action( 'woostify_page', 'woostify_page_content', 20 );
add_action( 'woostify_page_after', 'woostify_display_comments', 10 );


/**
 * Elementor
 */

// Template builder. See inc/woostify-template-builder.php.
add_action( 'woostify_theme_single', 'woostify_template_single' );
add_action( 'woostify_theme_archive', 'woostify_template_archive' );
add_action( 'woostify_theme_404', 'woostify_template_404' );

// Add Cart sidebar for Page using Elementor Canvas.
if ( woostify_is_woocommerce_activated() ) {
	add_action( 'elementor/page_templates/canvas/after_content', 'woostify_woocommerce_cart_sidebar', 20 );
}
add_action( 'elementor/page_templates/canvas/after_content', 'woostify_overlay', 30 );
add_action( 'elementor/page_templates/canvas/after_content', 'woostify_footer_action', 40 );
add_action( 'elementor/page_templates/canvas/after_content', 'woostify_dialog_search', 50 );
