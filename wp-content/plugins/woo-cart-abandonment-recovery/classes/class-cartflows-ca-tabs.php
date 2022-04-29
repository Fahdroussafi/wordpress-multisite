<?php
/**
 * Settings.
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Cartflows_Ca_Utils.
 */
class Cartflows_Ca_Tabs {


	/**
	 * Class instance.
	 *
	 * @access private
	 * @var $instance Class instance.
	 */
	private static $instance;

		/**
		 * Initiator
		 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Cartflows_Ca_Settings constructor.
	 */
	public function __construct() {

		// Adding menu to view cart abandonment report.
		add_action( 'admin_menu', array( $this, 'abandoned_cart_tracking_menu' ), 999 );
	}

		/**
		 * Add submenu to admin menu.
		 *
		 * @since 1.1.5
		 */
	public function abandoned_cart_tracking_menu() {

		$capability = current_user_can( 'manage_woocommerce' ) ? 'manage_woocommerce' : 'manage_options';

		add_submenu_page(
			'woocommerce',
			__( 'Cart Abandonment', 'woo-cart-abandonment-recovery' ),
			__( 'Cart Abandonment', 'woo-cart-abandonment-recovery' ),
			$capability,
			WCF_CA_PAGE_NAME,
			array( $this, 'render_abandoned_cart_tracking' )
		);
	}

		/**
		 * Render table view for cart abandonment tracking.
		 *
		 * @since 1.1.5
		 */
	public function render_abandoned_cart_tracking() {

		$wcf_list_table = Cartflows_Ca_Order_Table::get_instance();

		if ( 'delete' === $wcf_list_table->current_action() ) {

			$ids = array();
			if ( isset( $_REQUEST['id'] ) && is_array( $_REQUEST['id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$ids = array_map( 'intval', $_REQUEST['id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			$deleted_row_count = empty( $ids ) ? 1 : count( $ids );

			$wcf_list_table->process_bulk_action();
			$message = '<div class="notice notice-success is-dismissible" id="message"><p>' . sprintf( __( 'Items deleted: %d', 'woo-cart-abandonment-recovery' ), $deleted_row_count ) . '</p></div>'; // phpcs:ignore
			set_transient( 'wcf_ca_show_message', $message, 5 );
			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				wp_safe_redirect( esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) );
			}
		} elseif ( 'unsubscribe' === $wcf_list_table->current_action() ) {

			global $wpdb;
			$cart_abandonment_table = $wpdb->prefix . CARTFLOWS_CA_CART_ABANDONMENT_TABLE;
			$id                     = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );

			$wpdb->update(
				$cart_abandonment_table,
				array( 'unsubscribed' => true ),
				array( 'id' => $id )
			);
			$wcf_list_table->process_bulk_action();
            $message = '<div class="notice notice-success is-dismissible" id="message"><p>' . sprintf( __( 'User(s) unsubscribed successfully!', 'woo-cart-abandonment-recovery' ) ) . '</p></div>'; // phpcs:ignore
			set_transient( 'wcf_ca_show_message', $message, 5 );
			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				wp_safe_redirect( esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) );
			}
		}
		?>

		<?php
		include_once CARTFLOWS_CART_ABANDONMENT_TRACKING_DIR . 'includes/cartflows-cart-abandonment-tabs.php';
		?>
		<?php
	}
	/**
	 * Render Cart abandonment tabs.
	 *
	 * @since 1.1.5
	 */
	public function wcf_display_tabs() {

		$wcar_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$sub_action  = filter_input( INPUT_GET, 'sub_action', FILTER_SANITIZE_STRING );

		if ( ! $wcar_action ) {
			$wcar_action            = WCF_ACTION_REPORTS;
			$active_settings        = '';
			$active_reports         = '';
			$active_email_templates = '';
		}

		switch ( $wcar_action ) {
			case WCF_ACTION_SETTINGS:
				$active_settings = 'nav-tab-active';
				break;
			case WCF_ACTION_REPORTS:
				$active_reports = 'nav-tab-active';
				break;
			case WCF_ACTION_EMAIL_TEMPLATES:
				$active_email_templates = 'nav-tab-active';
				break;
			default:
				$active_reports = 'nav-tab-active';
				break;
		}
		// phpcs:disable
	 ?>


	<div class="nav-tab-wrapper woo-nav-tab-wrapper">

        <?php
        $url = add_query_arg( array(
            'page' => WCF_CA_PAGE_NAME,
            'action' => WCF_ACTION_REPORTS
        ), admin_url( '/admin.php' ) )
        ?>
        <a href="<?php echo $url; ?>"
           class="nav-tab
        <?php
           if ( isset( $active_reports ) ) {
			echo $active_reports;}
           ?>
			">
            <?php _e( 'Report', 'woo-cart-abandonment-recovery' ); ?>
        </a>

			<?php
			$url = add_query_arg( array(
            'page' => WCF_CA_PAGE_NAME,
            'action' => WCF_ACTION_EMAIL_TEMPLATES
			), admin_url( '/admin.php' ) )
        ?>
        <a href="<?php echo $url; ?>"
           class="nav-tab
        <?php
           if ( isset( $active_email_templates ) ) {
			echo $active_email_templates;}
           ?>
			">
            <?php _e( 'Follow-Up Emails', 'woo-cart-abandonment-recovery' ); ?>
        </a>

			<?php
			$url = add_query_arg( array(
            'page' => WCF_CA_PAGE_NAME,
            'action' => WCF_ACTION_SETTINGS
			), admin_url( '/admin.php' ) )
           ?>
		<a href="<?php echo $url; ?>"
		   class="nav-tab 
			<?php
		  if ( isset( $active_settings ) ) {
                echo $active_settings;}
		  ?>
			">
      <?php _e( 'Settings', 'woo-cart-abandonment-recovery' ); ?>
		</a>

	</div>
		<?php
		// phpcs:enable
	}

	/**
	 * Render Cart abandonment settings.
	 *
	 * @since 1.1.5
	 */
	public function wcf_display_settings() {
		?>

	<form method="post" action="options.php">
		<?php settings_fields( WCF_CA_SETTINGS_OPTION_GROUP ); ?>
		<?php do_settings_sections( WCF_CA_PAGE_NAME ); ?>
		<?php submit_button(); ?>
	</form>

		<?php
	}

	/**
	 * Render Cart abandonment reports.
	 *
	 * @since 1.1.5
	 */
	public function wcf_display_reports() {

		$filter       = filter_input( INPUT_GET, 'filter', FILTER_SANITIZE_STRING );
		$filter_table = filter_input( INPUT_GET, 'filter_table', FILTER_SANITIZE_STRING );

		if ( ! $filter ) {
			$filter = 'last_month';
		}
		if ( ! $filter_table ) {
			$filter_table = WCF_CART_ABANDONED_ORDER;
		}

		$from_date   = filter_input( INPUT_GET, 'from_date', FILTER_SANITIZE_STRING );
		$to_date     = filter_input( INPUT_GET, 'to_date', FILTER_SANITIZE_STRING );
		$export_data = filter_input( INPUT_GET, 'export_data', FILTER_VALIDATE_BOOLEAN );

		switch ( $filter ) {

			case 'yesterday':
				$to_date   = gmdate( 'Y-m-d', strtotime( '-1 days' ) );
				$from_date = $to_date;
				break;
			case 'today':
				$to_date   = gmdate( 'Y-m-d' );
				$from_date = $to_date;
				break;
			case 'last_week':
				$from_date = gmdate( 'Y-m-d', strtotime( '-7 days' ) );
				$to_date   = gmdate( 'Y-m-d' );
				break;
			case 'last_month':
				$from_date = gmdate( 'Y-m-d', strtotime( '-1 months' ) );
				$to_date   = gmdate( 'Y-m-d' );
				break;
			case 'custom':
				$to_date   = $to_date ? $to_date : gmdate( 'Y-m-d' );
				$from_date = $from_date ? $from_date : $to_date;
				break;

		}

		$abandoned_report = $this->get_report_by_type( $from_date, $to_date, WCF_CART_ABANDONED_ORDER );
		$recovered_report = $this->get_report_by_type( $from_date, $to_date, WCF_CART_COMPLETED_ORDER );
		$lost_report      = $this->get_report_by_type( $from_date, $to_date, WCF_CART_LOST_ORDER );

		$wcf_list_table = Cartflows_Ca_Order_Table::get_instance();
		$wcf_list_table->prepare_items( $filter_table, $from_date, $to_date );

		if ( $export_data ) {

			$this->download_send_headers();
			echo $this->array2csv( $wcf_list_table->items ); //phpcs:ignore
			die;

		}

		$conversion_rate = 0;
		$total_orders    = ( $recovered_report['no_of_orders'] + $abandoned_report['no_of_orders'] + $lost_report['no_of_orders'] );
		if ( $total_orders ) {
			$conversion_rate = ( $recovered_report['no_of_orders'] / $total_orders ) * 100;
		}

		global  $woocommerce;
		$conversion_rate = number_format_i18n( $conversion_rate, 2 );
		$currency_symbol = get_woocommerce_currency_symbol();
		require_once CARTFLOWS_CART_ABANDONMENT_TRACKING_DIR . 'includes/cartflows-cart-abandonment-reports.php';

	}

	/**
	 * Send headers to export orders to csv format.
	 */
	public function download_send_headers() {
		$now      = gmdate( 'Y-m-d-H-i-s' );
		$filename = 'woo-cart-abandonment-recovery-export-' . $now . '.csv';

		header( 'Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate' );
		header( "Last-Modified: {$now} GMT" );

		// force download.
		header( 'Content-Type: application/force-download' );
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Type: application/download' );

		// disposition / encoding on response body.
		header( "Content-Disposition: attachment;filename={$filename}" );
		header( 'Content-Transfer-Encoding: binary' );
	}

	/**
	 * Convert users data to csv format.
	 *
	 * @param array $user_data users data.
	 */
	public function array2csv( array $user_data ) {
		if ( empty( $user_data ) ) {
			return;
		}
		ob_clean();
		ob_start();
		$data_file = fopen( 'php://output', 'w' );
		fputcsv(
			$data_file,
			array(
				'First-Name',
				'Last-Name',
				'Email',
				'Phone',
				'Products',
				'Cart-Total in ' . get_woocommerce_currency(),
				'Order-Status',
				'Unsubscribed',
				'Coupon-Code',
			)
		);

		foreach ( $user_data as $data ) {
			$name             = unserialize( $data['other_fields'] );
			$checkout_details = Cartflows_Ca_Helper::get_instance()->get_checkout_details( $data['session_id'] );
			$cart_data        = Cartflows_Ca_Helper::get_instance()->get_comma_separated_products( $checkout_details->cart_contents );
			fputcsv(
				$data_file,
				array(
					$name['wcf_first_name'],
					$name['wcf_last_name'],
					$data['email'],
					$name['wcf_phone_number'],
					$cart_data,
					$data['cart_total'],
					$data['order_status'],
					$data['unsubscribed'] ? 'Yes' : 'No',
					$data['coupon_code'],
				)
			);

		}
		fclose( $data_file ); //phpcs:ignore
		return ob_get_clean();
	}

	/**
	 *  Get Attributable revenue.
	 *  Represents the revenue generated by this campaign.
	 *
	 * @param string $from_date from date.
	 * @param string $to_date to date.
	 * @param string $type abondened|completed.
	 */
	public function get_report_by_type( $from_date, $to_date, $type = WCF_CART_ABANDONED_ORDER ) {
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . CARTFLOWS_CA_CART_ABANDONMENT_TABLE;
		$minutes                = wcf_ca()->utils->get_cart_abandonment_tracking_cut_off_time();
		$attributable_revenue   = $wpdb->get_row(
	        $wpdb->prepare( "SELECT  SUM(`cart_total`) as revenue, count('*') as no_of_orders  FROM {$cart_abandonment_table} WHERE `order_status` = %s AND DATE(`time`) >= %s AND DATE(`time`) <= %s  ",  $type, $from_date, $to_date ), // phpcs:ignore
			ARRAY_A
		);
		return $attributable_revenue;
	}

	/**
	 * Show report details for specific order.
	 */
	public function wcf_display_report_details() {

		$sesson_id = filter_input( INPUT_GET, 'session_id', FILTER_SANITIZE_STRING );

		if ( $sesson_id ) {
			$details          = Cartflows_Ca_Helper::get_instance()->get_checkout_details( $sesson_id );
			$user_details     = (object) unserialize( $details->other_fields );
			$scheduled_emails = Cartflows_Ca_Helper::get_instance()->fetch_scheduled_emails( $sesson_id );

			require_once CARTFLOWS_CART_ABANDONMENT_TRACKING_DIR . 'includes/cartflows-ca-single-report-details.php';
		}

	}

		/**
		 * Generate the view for admin product cart block.
		 *
		 * @param  string $cart_contents user cart contents details.
		 * @param  float  $cart_total user cart total.
		 * @return string
		 */
	public function get_admin_product_block( $cart_contents, $cart_total ) {

		$cart_items = unserialize( $cart_contents );

		if ( ! is_array( $cart_items ) || ! count( $cart_items ) ) {
			return;
		}

		$tr       = '';
		$total    = 0;
		$discount = 0;
		$tax      = 0;

		foreach ( $cart_items as $cart_item ) {

			if ( isset( $cart_item['product_id'] ) && isset( $cart_item['quantity'] ) && isset( $cart_item['line_total'] ) && isset( $cart_item['line_subtotal'] ) ) {
				$id        = 0 !== $cart_item['variation_id'] ? $cart_item['variation_id'] : $cart_item['product_id'];
				$discount  = number_format_i18n( $discount + ( $cart_item['line_subtotal'] - $cart_item['line_total'] ), 2 );
				$total     = number_format_i18n( $total + $cart_item['line_subtotal'], 2 );
				$tax       = number_format_i18n( $tax + $cart_item['line_tax'], 2 );
				$image_url = get_the_post_thumbnail_url( $id );
				$image_url = ! empty( $image_url ) ? $image_url : get_the_post_thumbnail_url( $cart_item['product_id'] );

				$product      = wc_get_product( $id );
				$product_name = $product ? $product->get_formatted_name() : '';

				if ( empty( $image_url ) ) {
					$image_url = CARTFLOWS_CA_URL . '/assets/images/image-placeholder.png';
				}
				$tr = $tr . '<tr  align="center">
                           <td ><img class="demo_img" width="42" height="42" src=" ' . esc_url( $image_url ) . ' "/></td>
						   <td >' . $product_name . '</td>
                           <td > ' . $cart_item['quantity'] . ' </td>
                           <td >' . wc_price( $cart_item['line_total'] ) . '</td>
                           <td  >' . wc_price( $cart_item['line_total'] ) . '</td>
                        </tr> ';
			}
		}

		return '<table align="left" cellspacing="0" class="widefat fixed striped posts">
					<thead>
		                <tr align="center">
		                   <th  >' . __( 'Item', 'woo-cart-abandonment-recovery' ) . '</th>
		                   <th  >' . __( 'Name', 'woo-cart-abandonment-recovery' ) . '</th>
		                   <th  >' . __( 'Quantity', 'woo-cart-abandonment-recovery' ) . '</th>
		                   <th  >' . __( 'Price', 'woo-cart-abandonment-recovery' ) . '</th>
		                   <th  >' . __( 'Line Subtotal', 'woo-cart-abandonment-recovery' ) . '</th>
		                </tr>
	                </thead>
	                <tbody>
	                   ' . $tr . ' 
	                   	<tr align="center" id="wcf-ca-discount">
							<td  colspan="4" >' . __( 'Discount', 'woo-cart-abandonment-recovery' ) . '</td>
							<td>' . wc_price( $discount ) . '</td>
						</tr>
						<tr align="center" id="wcf-ca-other">
							<td colspan="4" >' . __( 'Other', 'woo-cart-abandonment-recovery' ) . '</td>
							<td>' . wc_price( $tax ) . '</td>
						</tr>

						<tr align="center" id="wcf-ca-shipping">
							<td colspan="4" >' . __( 'Shipping', 'woo-cart-abandonment-recovery' ) . '</td>
							<td>' . wc_price( $discount + ( $cart_total - $total ) - $tax, 2 ) . '</td>
						</tr>
						<tr align="center" id="wcf-ca-cart-total">
							<td colspan="4" >' . __( 'Cart Total', 'woo-cart-abandonment-recovery' ) . '</td>
							<td>' . wc_price( $cart_total ) . '</td>
						</tr>
	                </tbody>
	        	</table>';
	}

	/**
	 *  Check and show warning message if cart abandonment is disabled.
	 */
	public function wcf_show_warning_ca() {
		$settings_url = add_query_arg(
			array(
				'page'   => WCF_CA_PAGE_NAME,
				'action' => WCF_ACTION_SETTINGS,
			),
			admin_url( '/admin.php' )
		);

		if ( ! wcf_ca()->utils->is_cart_abandonment_tracking_enabled() ) {
			?>
		<div class="notice notice-warning is-dismissible">
			<p>
                <?php echo __('Looks like abandonment tracking is disabled! Please enable it from  <a href=' . esc_url($settings_url) . '> <strong>settings</strong></a>.', 'woo-cart-abandonment-recovery'); // phpcs:ignore
				?>
			</p>
		</div>
			<?php
		}
	}

}
Cartflows_Ca_Tabs::get_instance();
