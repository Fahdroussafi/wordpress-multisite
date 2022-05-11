<?php

defined( 'ABSPATH' ) or die( 'Keep Quit' );

//add_action( 'wp_ajax_nopriv_wvs_get_available_variations', 'wvs_get_available_product_variations' );

//add_action( 'wp_ajax_wvs_get_available_variations', 'wvs_get_available_product_variations' );

add_filter( 'product_attributes_type_selector', 'wvs_product_attributes_types' );

add_action( 'init', 'wvs_settings', 2 );

add_action( 'admin_init', 'wvs_add_product_taxonomy_meta' );

// From WC 3.6+
if ( defined( 'WC_VERSION' ) && version_compare( '3.6', WC_VERSION, '<=' ) ) {
	add_action( 'woocommerce_product_option_terms', 'wvs_product_option_terms', 20, 3 );
} else {
	add_action( 'woocommerce_product_option_terms', 'wvs_product_option_terms_old', 20, 2 );
}

// Dokan Support
add_action( 'dokan_product_option_terms', 'dokan_support_wvs_product_option_terms', 20, 2 );

add_filter( 'woocommerce_ajax_variation_threshold', 'wvs_ajax_variation_threshold', 8 );

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'wvs_variation_attribute_options_html', 200, 2 );

add_filter( 'script_loader_tag', 'wvs_defer_script_load', 10, 3 );

if ( ! class_exists( 'Woo_Variation_Swatches_Pro' ) ) {
	add_filter( 'woocommerce_product_data_tabs', 'add_wvs_pro_preview_tab' );

	add_filter( 'woocommerce_product_data_panels', 'add_wvs_pro_preview_tab_panel' );
}


add_action( 'woocommerce_save_product_variation', 'wvs_clear_transient' );
add_action( 'woocommerce_update_product_variation', 'wvs_clear_transient' );
add_action( 'woocommerce_delete_product_variation', 'wvs_clear_transient' );
add_action( 'woocommerce_trash_product_variation', 'wvs_clear_transient' );

// WooCommerce -> Status -> Tools -> Clear transients
add_action( 'woocommerce_delete_product_transients', 'wvs_clear_transient' );

add_action( 'woocommerce_attribute_added', 'wvs_clear_transient', 20 );
add_action( 'woocommerce_attribute_updated', 'wvs_clear_transient', 20 );
add_action( 'woocommerce_attribute_deleted', 'wvs_clear_transient', 20 );

add_action( 'before_update_woo_variation_swatches_settings', 'wvs_clear_transient' );
add_action( 'delete_woo_variation_swatches_settings', 'wvs_clear_transient' );
add_action( 'wvs_pro_save_product_attributes', 'wvs_clear_transient' );
add_action( 'wvs_pro_reset_product_attributes', 'wvs_clear_transient' );

add_filter( 'pre_update_option_woocommerce_thumbnail_image_width', 'wvs_clear_transient' );

add_filter( 'pre_update_option_woocommerce_thumbnail_cropping', 'wvs_clear_transient' );

// Load Template
// add_filter( 'woocommerce_locate_template', 'wvs_locate_template', 10, 3 );

/*add_filter(
	'disable_wvs_admin_enqueue_scripts', function ( $default ) {
	return is_customize_preview() ? is_customize_preview() : $default;
}
);*/


// Gallery Install Notice
add_action( 'woocommerce_product_after_variable_attributes', 'wvs_install_woo_variation_gallery_notice', 10, 3 );

add_action( 'wp_ajax_install_woo_variation_gallery', 'wvs_install_woo_variation_gallery' );


// WPML Support
add_action( 'wvs_global_attribute_column', function ( $column, $term_id, $taxonomy, $attribute, $fields, $available_types ) {
	if ( class_exists( 'SitePress' ) ) {

		global $sitepress;

		$keys = wp_list_pluck( $fields, 'id' );
		// $keys = array_column($fields, 'id');

		foreach ( $keys as $key ) {
			$value = sanitize_text_field( get_term_meta( $term_id, $key, true ) );
			// $original_element_id = $sitepress->get_original_element_id( $term_id, 'tax_' . $taxonomy );
			$trid         = $sitepress->get_element_trid( $term_id, 'tax_' . $taxonomy );
			$translations = $sitepress->get_element_translations( $trid, 'tax_' . $taxonomy );

			$current_lang = $sitepress->get_current_language();
			$default_lang = $sitepress->get_default_language();

			if ( $translations && empty( $value ) ) {
				// source_language_code
				$translation = array_values( array_filter( $translations, function ( $translation ) {
					return isset( $translation->original ) && ! empty( $translation->original );
				} ) );

				$translation = array_shift( $translation );

				if ( empty( $value ) && $translation ) {
					$original_term_id = $translation->term_id;
					$original_value   = sanitize_text_field( get_term_meta( $original_term_id, $key, true ) );
					// Copy term meta from original
					update_term_meta( $term_id, $key, $original_value );
				}
			}

		}
	}
}, 10, 6 );
