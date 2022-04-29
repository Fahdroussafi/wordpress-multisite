<?php
/**
 * Woocommerce Cart Abandonment Recovery
 * Unscheduling the events.
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

wp_clear_scheduled_hook( 'cartflows_ca_update_order_status_action' );

$delete_data = get_option( 'wcf_ca_delete_plugin_data' );

if ( 'on' === $delete_data ) {

	$options = array(
		'wcf_ca_status',
		'wcf_ca_gdpr_status',
		'wcf_ca_coupon_code_status',
		'wcf_ca_zapier_tracking_status',
		'wcf_ca_cut_off_time',
		'wcf_ca_from_name',
		'wcf_ca_from_email',
		'wcf_ca_reply_email',
		'wcf_ca_discount_type',
		'wcf_ca_coupon_amount',
		'wcf_ca_zapier_cart_abandoned_webhook',
		'wcf_ca_gdpr_message',
		'wcf_ca_coupon_expiry',
		'wcf_ca_coupon_expiry_unit',
		'wcf_ca_excludes_orders',
		'wcf_ca_delete_plugin_data',
		'wcf_ca_version',
	);

	// Delete all options data.
	foreach ( $options as $index => $key ) {
		delete_option( $key );
	}

	// Drop the tables.
	$wpdb->get_results( "DROP TABLE IF EXISTS {$wpdb->prefix}cartflows_ca_email_templates_meta" );

	$wpdb->get_results( "DROP TABLE IF EXISTS {$wpdb->prefix}cartflows_ca_email_history" );

	$wpdb->get_results( "DROP TABLE IF EXISTS {$wpdb->prefix}cartflows_ca_email_templates" );

	$wpdb->get_results( "DROP TABLE IF EXISTS {$wpdb->prefix}cartflows_ca_cart_abandonment" );

}

