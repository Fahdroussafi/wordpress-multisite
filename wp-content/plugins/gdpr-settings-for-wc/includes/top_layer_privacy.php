<?php
/**
 * RGPD/GDPR Settings for WC
 * Related functions for top privacy layer in WooCommerce checkout
 **/

// Secure. This file can't be load directly
if (!defined('ABSPATH')) exit;

function wcgdprsettings_add_checkout_top_layer() {
    
    // Get label value
    $wc_gdpr_toplayer = get_option('wc_settings_tab_gdpr_top_layer', false);

    // Create only if has any value
    if( !$wc_gdpr_toplayer) return;

    $html = '<div class="wcgdpr_top_layer">';
    $html .= sanitize_textarea_field( $wc_gdpr_toplayer );
    $html .= '</div>';

    echo apply_filters( 'wcgdprsettings_top_layer', $html );

}
add_action( 'woocommerce_review_order_after_submit', 'wcgdprsettings_add_checkout_top_layer', 20 );