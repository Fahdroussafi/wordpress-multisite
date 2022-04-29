<?php
/**
 * CartFlows Ca Admin Notices.
 *
 * @package CartFlows
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class Cartflows_Ca_Admin_Notices.
 */
class Cartflows_Ca_Admin_Notices {

	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class object.
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'show_admin_notices' ) );
	}

	/**
	 *  Show admin notices.
	 */
	public function show_admin_notices() {

		$image_path = esc_url( CARTFLOWS_CA_URL . 'admin/assets/images/cartflows-logo-small.jpg' );

		Astra_Notices::add_notice(
			array(
				'id'                   => 'cartflows-ca-5-star-notice',
				'type'                 => 'info',
				'class'                => 'cartflows-ca-5-star',
				'show_if'              => true,
				/* translators: %1$s white label plugin name and %2$s deactivation link */
				'message'              => sprintf(
					'<div class="notice-image" style="display: flex;">
                        <img src="%1$s" class="custom-logo" alt="CartFlows Icon" itemprop="logo" style="max-width: 90px; border-radius: 50px;"></div>
                        <div class="notice-content">
                            <div class="notice-heading">
                                %2$s
                            </div>
                            %3$s<br />
                            <div class="astra-review-notice-container">
                                <a href="%4$s" class="astra-notice-close astra-review-notice button-primary" target="_blank">
                                %5$s
                                </a>
                            <span class="dashicons dashicons-calendar"></span>
                                <a href="#" data-repeat-notice-after="%6$s" class="astra-notice-close astra-review-notice">
                                %7$s
                                </a>
                            <span class="dashicons dashicons-smiley"></span>
                                <a href="#" class="astra-notice-close astra-review-notice">
                                %8$s
                                </a>
                            </div>
                        </div>',
					$image_path,
					__( 'Hello! Seems like you have used WooCommerce Cart Abandonment Recovery plugin to recover abandoned carts. &mdash; Thanks a ton!', 'woo-cart-abandonment-recovery' ),
					__( 'Could you please do us a BIG favor and give it a 5-star rating on WordPress? This would boost our motivation and help other users make a comfortable decision while choosing the CartFlows cart abandonment plugin.', 'woo-cart-abandonment-recovery' ),
					'https://wordpress.org/support/plugin/woo-cart-abandonment-recovery/reviews/?filter=5#new-post',
					__( 'Ok, you deserve it', 'woo-cart-abandonment-recovery' ),
					MONTH_IN_SECONDS,
					__( 'Nope, maybe later', 'woo-cart-abandonment-recovery' ),
					__( 'I already did', 'woo-cart-abandonment-recovery' )
				),
				'repeat-notice-after'  => MONTH_IN_SECONDS,
				'display-notice-after' => ( 3 * WEEK_IN_SECONDS ), // Display notice after 2 weeks.
			)
		);
	}
}

Cartflows_Ca_Admin_Notices::get_instance();
