<?php
/**
 * RGPD/GDPR Settings for WC
 * Related functions for promotions checkbox in WooCommerce checkout
 **/

// Secure. This file can't be load directly
if (!defined('ABSPATH')) exit;

/*
*   WooCommerce Checkout: fields before submit button
*   Add custom checkbox for promotions
*/
function wcgdprsettings_add_checkout_checkbox() {
    
    // Get label value
    $wc_gdprpromo_label = get_option('wc_settings_tab_gdpr_promo_label', false);

    // Create only if has any value
    if( !$wc_gdprpromo_label) return;

    // Create form field
    $wc_gdprpromo_checkbox = [
        'type'  => 'checkbox',
        'class' => ['form-row wc_gdprpromo_checkbox'],
        'label_class' => ['woocommerce-form__label woocommerce-form__label-for-checkbox checkbox wc_gdprpromo_checkbox_label'],
        'input_class' => ['woocommerce-form__input woocommerce-form__input-checkbox input-checkbox wc_gdprpromo_checkbox_input'],
        'label' => sanitize_textarea_field( $wc_gdprpromo_label )
    ];

    // Add custom WooCommerce form field
    woocommerce_form_field( 'wc_gdprpromo_checkbox', $wc_gdprpromo_checkbox, __('Yes','wc_gdpr_settings'));

}
add_action( 'woocommerce_review_order_before_submit', 'wcgdprsettings_add_checkout_checkbox', 9 );

/*
*   WooCommerce Checkout: update order meta with GDPR promo consent
*/
function wcgdprsettings_update_order_meta_promo_consent( $order_id ) {

    if ( (int) isset($_POST['wc_gdprpromo_checkbox']) && $_POST['wc_gdprpromo_checkbox'] === "1") {
        update_post_meta( $order_id, 'wc_gdprpromo_checkbox', sanitize_text_field( $_POST['wc_gdprpromo_checkbox'] ) );

        //Fires custom action to third party integrations
        do_action('wc_gdprpromo_after_user_consent');
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'wcgdprsettings_update_order_meta_promo_consent' );

/*
*   WooCommerce Admin: display GDPR promo consent in WooCommerce Admin
*/
function wcgdprsettings_display_admin_order_promo_consent($order){
    echo '<p><strong>';
    echo __('Acept promotions', 'wc_gdpr_settings').':</strong> ';
    echo (get_post_meta( $order->get_id(), 'wc_gdprpromo_checkbox', true ) === "1")? __('Yes','wc_gdpr_settings') : __('No','wc_gdpr_settings');
    echo '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'wcgdprsettings_display_admin_order_promo_consent', 10, 1 );

/*
*   WooCommerce Mails: display GDPR promo consent in 'new order mail' for admin
*/
function wcgdprsettings_display_mail_order_promo_consent(  $order, $is_admin_email ) {
    
    if($is_admin_email){
        echo '<p><strong>';
        echo __('Acept promotions', 'wc_gdpr_settings').':</strong> ';
        echo (get_post_meta( $order->get_id(), 'wc_gdprpromo_checkbox', true ) === "1")? __('Yes','wc_gdpr_settings') : __('No','wc_gdpr_settings');
        echo '</p>';
    }

}
add_filter('woocommerce_email_after_order_table', 'wcgdprsettings_display_mail_order_promo_consent',10,2);