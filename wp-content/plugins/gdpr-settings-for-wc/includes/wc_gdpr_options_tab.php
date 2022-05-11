<?php
/**
 * RGPD/GDPR Settings for WC
 * Related functions for custom settings tab in WooCommerce Admin panel
 **/

// Secure. This file can't be load directly
if (!defined('ABSPATH')) exit;


// Create new WooCommerce setting tab for GDPR Promo settings
class WC_Settings_Tab_gdpr {

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_gdpr', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_gdpr', __CLASS__ . '::update_settings' );
    }
    
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_gdpr'] = __( 'GDPR Settings', 'wc_gdpr_settings' );
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'     => __( 'GDPR Checkout Settings', 'wc_gdpr_settings' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_gdpr_section_title'
            ),
            'title' => array(
                'name' => __( 'GDPR Promo consent label', 'wc_gdpr_settings' ),
                'type' => 'text',
                'desc_tip' =>  true,
                'desc' => __( 'For example: "I agree to receive offers, news and other recommendations on products or services". Leave empty for diable this option', 'wc_gdpr_settings' ),
                'id'   => 'wc_settings_tab_gdpr_promo_label'
            ),
            'description' => array(
                'name' => __( 'GDPR top privacy layer', 'wc_gdpr_settings' ),
                'type' => 'textarea',
                'desc'=> '<a href="https://tecnicorgpd.com/cursos-rgpd/?utm_campaign=wc-settings-for-wc-dashboard&utm_medium=banner&utm_source=referral" target="_blank" rel="nofollow" style="float:right; max-width:20%;"><img src="'.wcgdprsettings_IMG.'by-tecnicorgpd-wp.png" alt="Técnico RGPD.com"></a>',
                'desc_tip' => __( 'You can include here the first top layer for GPDR. Allow html tags', 'wc_gdpr_settings' ),
                //'desc_tip' =>  true,
                'css' => 'height:150px; float: left; width: 400px; max-width: 80%;',
                'id'   => 'wc_settings_tab_gdpr_top_layer',
                'default' => __('Responsible: YOUR COMPANY | Purpose: supply the order and all related operations . | Legitimation: Your consent. | Additional information: You will find additional information about our privacy policy in the link below.', 'wc_gdpr_settings'),
            ),
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_gdpr_section_end'
            )
        );

        return apply_filters( 'wc_settings_tab_gdpr_settings', $settings );
    }

}

WC_Settings_Tab_gdpr::init();