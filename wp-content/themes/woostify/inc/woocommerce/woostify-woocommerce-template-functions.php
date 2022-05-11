<?php
/**
 * WooCommerce Template Functions.
 *
 * @package woostify
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'woostify_get_last_product_id' ) ) {
	/**
	 * Get the last ID of product, exclude Group and External Product.
	 */
	function woostify_get_last_product_id() {
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'tax_query'      => array( // phpcs:ignore
				array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => array( 'simple', 'variable' ),
						'operator' => 'IN',
					),
				),
			),
		);

		$query = get_posts( $args );
		$id    = false;

		// @codingStandardsIgnoreStart
		// Support selected preview product.
		if ( woostify_is_elementor_editor() ) {
			$post_id = isset( $_REQUEST['post'] ) ? intval( $_REQUEST['post'] ) : false;

			// When preview selected.
			if ( isset( $_POST['editor_post_id'] ) ) {
				$post_id = intval( $_POST['editor_post_id'] );
			}

			$selected_id = get_post_meta( $post_id, 'woostify_woo_builder_select_product_preview', true );

			if ( $selected_id ) {
				return $selected_id;
			}
		}
		// @codingStandardsIgnoreEnd

		if ( empty( $query ) ) {
			return false;
		}

		return $query[0];
	}
}

if ( ! function_exists( 'woostify_elementor_preview_product_page_scripts' ) ) {
	/**
	 * Global variation gallery
	 */
	function woostify_elementor_preview_product_page_scripts() {
		$product = wc_get_product( woostify_get_last_product_id() );
		if ( ! is_object( $product ) ) {
			woostify_global_for_vartiation_gallery( $product );
		}
	}
}

if ( ! function_exists( 'woostify_ajax_update_quantity_in_mini_cart' ) ) {
	/**
	 * Update product quantity in minicart
	 */
	function woostify_ajax_update_quantity_in_mini_cart() {
		check_ajax_referer( 'woostify_woocommerce_general_nonce', 'ajax_nonce' );

		if ( ! isset( $_POST['key'] ) || ! isset( $_POST['qty'] ) ) {
			wp_send_json_error();
		}

		$options                    = woostify_options( false );
		$response                   = array();
		$top_content                = $options['mini_cart_top_content_select'];
		$before_checkout_content    = $options['mini_cart_before_checkout_button_content_select'];
		$after_checkout_content     = $options['mini_cart_after_checkout_button_content_select'];
		$enabled_shipping_threshold = $options['shipping_threshold_enabled'];

		$wc_number_of_decimals = get_option( 'woocommerce_price_num_decimals', 0 );

		$cart_item_key = sanitize_text_field( wp_unslash( $_POST['key'] ) );
		$product_qty   = number_format( $_POST['qty'], $wc_number_of_decimals );

		WC()->cart->set_quantity( $cart_item_key, $product_qty );

		$count = WC()->cart->get_cart_contents_count();

		ob_start();
		$response['item']        = $count;
		$response['total_price'] = WC()->cart->get_cart_total();
		if ( ( 'fst' === $top_content || 'fst' === $before_checkout_content || 'fst' === $after_checkout_content ) && $enabled_shipping_threshold ) {
			$response['free_shipping_threshold'] = array();

			$subtotal                 = WC()->cart->subtotal;
			$goal_amount              = $options['shipping_threshold_progress_bar_amount'];
			$progress_bar_initial_msg = $options['shipping_threshold_progress_bar_initial_msg'];
			$progress_bar_success_msg = $options['shipping_threshold_progress_bar_success_msg'];
			$missing_amount           = $goal_amount - $subtotal;
			$progress_bar_initial_msg = str_replace( '[missing_amount]', wc_price( $missing_amount ), $progress_bar_initial_msg );

			$percent = 0;
			$percent = ( $subtotal / $goal_amount ) * 100;
			$percent = $percent >= 100 ? 100 : round( $percent, $wc_number_of_decimals );

			$response['free_shipping_threshold']['percent'] = $percent;
			$response['free_shipping_threshold']['message'] = $percent >= 100 ? $progress_bar_success_msg : $progress_bar_initial_msg;
		}
		$response['content'] = ob_get_clean();

		wp_send_json_success( $response );
	}
}

if ( ! function_exists( 'woostify_ajax_single_add_to_cart' ) ) {
	/**
	 * Ajax single add to cart
	 */
	function woostify_ajax_single_add_to_cart() {
		check_ajax_referer( 'woostify_woocommerce_general_nonce', 'ajax_nonce' );
		WC_AJAX::get_refreshed_fragments();
	}
}

if ( ! function_exists( 'woostify_update_quantity_mini_cart' ) ) {
	/**
	 * Update quantity in mini cart
	 *
	 * @param string $output        Output.
	 * @param array  $cart_item     Cart item.
	 * @param string $cart_item_key Cart item key.
	 */
	function woostify_update_quantity_mini_cart( $output, $cart_item, $cart_item_key ) {
		$product        = $cart_item['data'];
		$product_id     = $cart_item['product_id'];
		$stock_quantity = $product->get_stock_quantity();
		$product_price  = WC()->cart->get_product_price( $product );

		ob_start();
		?>
		<span class="mini-cart-product-infor">
			<span class="mini-cart-quantity">
				<span class="mini-cart-product-qty" data-qty="minus">
				<?php Woostify_Icon::fetch_svg_icon( 'minus' ); ?>
				</span>

				<input type="number" data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>" class="input-text qty" step="<?php echo esc_attr( apply_filters( 'woocommerce_quantity_input_step', 1, $product ) ); ?>" min="<?php echo esc_attr( apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ) ); ?>" max="<?php echo esc_attr( $stock_quantity ? $stock_quantity : '' ); ?>" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" inputmode="numeric">

				<span class="mini-cart-product-qty" data-qty="plus">
				<?php Woostify_Icon::fetch_svg_icon( 'plus' ); ?>
				</span>
			</span>

			<span class="mini-cart-product-price"><?php echo wp_kses_post( $product_price ); ?></span>
		</span>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'woostify_before_content' ) ) {
	/**
	 * Before Content
	 * Wraps all WooCommerce content in wrappers which match the theme markup
	 *
	 * @return  void
	 */
	function woostify_before_content() {
		$class = apply_filters( 'woostify_site_main_class', 'site-main' );
		?>
		<div id="primary" class="content-area">
			<main id="main" class="<?php echo esc_attr( $class ); ?>">
			<?php
	}
}

if ( ! function_exists( 'woostify_after_content' ) ) {
	/**
	 * After Content
	 * Closes the wrapping divs
	 *
	 * @return  void
	 */
	function woostify_after_content() {
		?>
			</main><!-- #main -->
		</div><!-- #primary -->

		<?php
		do_action( 'woostify_sidebar' );
	}
}

if ( ! function_exists( 'woostify_sorting_wrapper' ) ) {
	/**
	 * Sorting wrapper
	 *
	 * @return  void
	 */
	function woostify_sorting_wrapper() {
		echo '<div class="woostify-sorting">';
	}
}

if ( ! function_exists( 'woostify_sorting_wrapper_close' ) ) {
	/**
	 * Sorting wrapper close
	 *
	 * @return  void
	 */
	function woostify_sorting_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'woostify_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper
	 *
	 * @return  void
	 */
	function woostify_product_columns_wrapper() {
		$columns = wc_get_loop_prop( 'columns' );
		echo '<div class="columns-' . esc_attr( $columns ) . '">';
	}
}

if ( ! function_exists( 'woostify_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close
	 *
	 * @return  void
	 */
	function woostify_product_columns_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'woostify_shop_messages' ) ) {
	/**
	 * Woostify shop messages
	 *
	 * @uses    woostify_do_shortcode
	 */
	function woostify_shop_messages() {
		if ( is_checkout() || apply_filters( 'woostify_hide_shop_message', false ) ) {
			return;
		}

		echo do_shortcode( '[woocommerce_messages]' );
	}
}

if ( ! function_exists( 'woostify_woocommerce_pagination' ) ) {
	/**
	 * Woostify WooCommerce Pagination
	 * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
	 * but since Woostify adds pagination before that function is excuted we need a separate function to
	 * determine whether or not to display the pagination.
	 */
	function woostify_woocommerce_pagination() {
		if ( woocommerce_products_will_display() ) {
			woocommerce_pagination();
		}
	}
}

if ( ! function_exists( 'woostify_mini_cart' ) ) {
	/**
	 * Mini cart
	 */
	function woostify_mini_cart() {
		if ( ! woostify_is_woocommerce_activated() ) {
			return;
		}

		do_action( 'woocommerce_before_mini_cart' );

		if ( ! WC()->cart->is_empty() ) {
			?>
			<ul class="woocommerce-mini-cart cart_list product_list_widget">
				<?php
				do_action( 'woocommerce_before_mini_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					if (
						( function_exists( 'wc_pb_get_bundled_cart_item_container' ) && wc_pb_get_bundled_cart_item_container( $cart_item ) ) /* Support WC bundle plugin */ ||
						defined( 'WOOCO_VERSION' ) && isset( $cart_item['wooco_pos'] ) && $cart_item['wooco_pos'] // Support WPC Composite Products for WooCommerce plugin.
					) {
						continue;
					}

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$stock_quantity    = $_product->get_stock_quantity();
						?>
						<li class="woocommerce-mini-cart-item mini_cart_item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
							<?php
							echo apply_filters( // phpcs:ignore
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_attr__( 'Remove this item', 'woostify' ),
									esc_attr( $product_id ),
									esc_attr( $cart_item_key ),
									esc_attr( $_product->get_sku() )
								),
								$cart_item_key
							);
							?>
							<?php if ( empty( $product_permalink ) ) : ?>
								<?php echo $thumbnail . $product_name; // phpcs:ignore ?>
							<?php else : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>">
									<?php echo $thumbnail . $product_name; // phpcs:ignore ?>
								</a>
							<?php endif; ?>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore ?>

							<span class="mini-cart-product-infor">
								<span class="mini-cart-quantity" <?php echo esc_attr( $_product->is_sold_individually() ? 'data-sold_individually' : '' ); ?>>
									<span class="mini-cart-product-qty" data-qty="minus">
									<?php Woostify_Icon::fetch_svg_icon( 'minus' ); ?>
									</span>

									<input type="number" data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>" class="input-text qty" step="<?php echo esc_attr( apply_filters( 'woocommerce_quantity_input_step', 1, $_product ) ); ?>" min="<?php echo esc_attr( apply_filters( 'woocommerce_quantity_input_min', $_product->get_min_purchase_quantity(), $_product ) ); ?>" max="<?php echo esc_attr( $stock_quantity ? $stock_quantity : '' ); ?>" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" inputmode="numeric" <?php echo esc_attr( $_product->is_sold_individually() ? 'disabled' : '' ); ?>>

									<span class="mini-cart-product-qty" data-qty="plus">
									<?php Woostify_Icon::fetch_svg_icon( 'plus' ); ?>
									</span>
								</span>

								<span class="mini-cart-product-price"><?php echo wp_kses_post( $product_price ); ?></span>

								<?php do_action( 'woostify_mini_cart_item_after_price', $_product ); ?>
							</span>
						</li>
						<?php
					}
				}

				do_action( 'woocommerce_mini_cart_contents' );
				?>
			</ul>

			<div class="woocommerce-mini-cart__bottom">
				<p class="woocommerce-mini-cart__total total<?php echo class_exists( 'BM_Live_Price' ) ? ' bm-cart-total-price' : ''; ?>">
					<?php
					/**
					 * Hook: woocommerce_widget_shopping_cart_total.
					 *
					 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
					 */
					do_action( 'woocommerce_widget_shopping_cart_total' );
					?>
				</p>

				<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

				<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>
				<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
			</div>
			<?php
		} else {
			$options       = woostify_options( false );
			$empty_msg     = $options['mini_cart_empty_message'];
			$enable_button = $options['mini_cart_empty_enable_button'];
			?>
			<div class="woocommerce-mini-cart__empty-message">
				<div class="woostify-empty-cart">
					<div class="message-icon"><?php Woostify_Icon::fetch_svg_icon( 'shopping-cart-2' ); ?></div>
					<p class="message-text"><?php echo esc_html( $empty_msg ); ?></p>
					<?php if ( $enable_button ) { ?>
						<a class="button continue-shopping" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Continue Shopping', 'woostify' ); ?></a>
					<?php } ?>
				</div>
			</div>
			<?php
		}

		do_action( 'woocommerce_after_mini_cart' );
	}
}

if ( ! function_exists( 'woostify_woocommerce_shipping_threshold' ) ) {
	/**
	 * Shipping Threshold
	 */
	function woostify_woocommerce_shipping_threshold() {
		$options                    = woostify_options( false );
		$enabled_shipping_threshold = $options['shipping_threshold_enabled'];

		if ( ! $enabled_shipping_threshold ) {
			return;
		}

		$classes                 = array();
		$top_content             = $options['mini_cart_top_content_select'];
		$before_checkout_content = $options['mini_cart_before_checkout_button_content_select'];

		if ( 'fst' === $top_content ) {
			$classes[] = 'pos-top';
		}
		if ( 'fst' === $before_checkout_content ) {
			$classes[] = 'pos-before-checkout';
		}

		$subtotal = WC()->cart->subtotal;

		$goal_amount              = $options['shipping_threshold_progress_bar_amount'];
		$enable_progress_bar      = $options['shipping_threshold_enable_progress_bar'];
		$progress_bar_initial_msg = $options['shipping_threshold_progress_bar_initial_msg'];
		$progress_bar_success_msg = $options['shipping_threshold_progress_bar_success_msg'];

		$missing_amount           = $goal_amount - $subtotal;
		$progress_bar_initial_msg = str_replace( '[missing_amount]', wc_price( $missing_amount ), $progress_bar_initial_msg );

		$percent = 0;
		$percent = ( $subtotal / $goal_amount ) * 100;
		$percent = $percent >= 100 ? 100 : round( $percent, 0 );
		?>
		<div class="free-shipping-progress-bar <?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-progress="<?php echo esc_attr( $percent ); ?>">
			<div class="progress-bar-message"><?php echo $percent < 100 ? wp_kses_post( $progress_bar_initial_msg ) : wp_kses_post( $progress_bar_success_msg ); ?></div>
			<?php if ( $enable_progress_bar ) { ?>
				<div class="progress-bar-rail">
					<div class="progress-bar-status <?php echo $percent >= 100 ? 'success' : ''; ?>" style="min-width: <?php echo (int) $percent; ?>%">
						<div class="progress-bar-indicator"></div>
						<div class="progress-percent"><?php echo (int) $percent; ?>%</div>
					</div>
					<div class="progress-bar-left"></div>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_woocommerce_cart_sidebar' ) ) {
	/**
	 * Cart sidebar
	 */
	function woostify_woocommerce_cart_sidebar() {
		// Not print Cart sidebar if Mini Cart Template	- Elementor Pro enable || Side Cart plugin install.
		if ( ( defined( 'ELEMENTOR_PRO_VERSION' ) && 'yes' === get_option( 'elementor_use_mini_cart_template' ) ) || defined( 'XOO_WSC_PLUGIN_FILE' ) ) {
			return;
		}

		$total = WC()->cart->cart_contents_count;
		?>
			<div id="shop-cart-sidebar">
				<div class="cart-sidebar-head">
					<h4 class="cart-sidebar-title"><?php esc_html_e( 'Shopping cart', 'woostify' ); ?></h4>
					<span class="shop-cart-count"><?php echo esc_html( $total ); ?></span>
					<button id="close-cart-sidebar-btn" class="close">
					<?php Woostify_Icon::fetch_svg_icon( 'close' ); ?>
					</button>
				</div>

				<div class="cart-sidebar-content">
					<?php woostify_mini_cart(); ?>
				</div>
			</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_modify_loop_add_to_cart_class' ) ) {
	/**
	 * Modify loop add to cart class name
	 */
	function woostify_modify_loop_add_to_cart_class() {
		global $product;
		$options      = woostify_options( false );
		$button_class = 'loop-add-to-cart-btn';
		$icon_class   = '';

		if ( 'image' === $options['shop_page_add_to_cart_button_position'] ) {
			$button_class = 'loop-add-to-cart-on-image';
		} elseif ( 'icon' === $options['shop_page_add_to_cart_button_position'] ) {
			$button_class = 'loop-add-to-cart-icon-btn';
		}

		$args = array(
			'class'      => implode(
				' ',
				array_filter(
					array(
						$icon_class,
						$button_class,
						'button',
						'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
					)
				)
			),
			'attributes' => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'title'            => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);

		return $args;
	}
}

if ( ! function_exists( 'woostify_is_woocommerce_page' ) ) {
	/**
	 * Returns true if on a page which uses WooCommerce templates
	 * Cart and Checkout are standard pages with shortcodes and which are also included
	 */
	function woostify_is_woocommerce_page() {
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			return true;
		}

		$keys = array(
			'woocommerce_shop_page_id',
			'woocommerce_terms_page_id',
			'woocommerce_cart_page_id',
			'woocommerce_checkout_page_id',
			'woocommerce_pay_page_id',
			'woocommerce_thanks_page_id',
			'woocommerce_myaccount_page_id',
			'woocommerce_edit_address_page_id',
			'woocommerce_view_order_page_id',
			'woocommerce_change_password_page_id',
			'woocommerce_logout_page_id',
			'woocommerce_lost_password_page_id',
		);

		foreach ( $keys as $k ) {
			if ( get_the_ID() === get_option( $k, 0 ) ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'woostify_modifided_woocommerce_breadcrumb' ) ) {
	/**
	 * Modify breadcrumb item
	 *
	 * @param      array $default The breadcrumb item.
	 */
	function woostify_modifided_woocommerce_breadcrumb( $default ) {
		$default['delimiter']   = '<span class="item-bread delimiter">' . apply_filters( 'woostify_breadcrumb_delimiter', '&#47;' ) . '</span>';
		$default['wrap_before'] = '<nav class="woostify-breadcrumb">';
		$default['wrap_after']  = '</nav>';
		$default['before']      = '<span class="item-bread">';
		$default['after']       = '</span>';

		return $default;
	}
}

if ( ! function_exists( 'woostify_breadcrumb_for_product_page' ) ) {
	/**
	 * Add breadcrumb for Product page
	 */
	function woostify_breadcrumb_for_product_page() {
		// Hooked to `woostify_content_top` only Product page.
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		$options = woostify_options( false );

		if ( $options['shop_single_breadcrumb'] ) {
			add_action( 'woostify_content_top', 'woocommerce_breadcrumb', 40 );
		}

		if ( $options['shop_single_product_navigation'] ) {
			add_action( 'woostify_content_top', 'woostify_product_navigation', 50 );
		}
	}
}

if ( ! function_exists( 'woostify_related_products_args' ) ) {
	/**
	 * Related Products Args
	 *
	 * @param  array $args related products args.
	 * @return  array $args related products args
	 */
	function woostify_related_products_args( $args ) {
		$options = woostify_options( false );
		$args    = apply_filters(
			'woostify_related_products_args',
			array(
				'posts_per_page' => $options['shop_single_product_related_total'],
				'columns'        => $options['shop_single_product_related_columns'],
			)
		);

		return $args;
	}
}

if ( ! function_exists( 'woostify_change_woocommerce_arrow_pagination' ) ) {
	/**
	 * Change arrow for pagination
	 *
	 * @param array $args Woocommerce pagination.
	 */
	function woostify_change_woocommerce_arrow_pagination( $args ) {
		$args['prev_text'] = __( 'Prev', 'woostify' );
		$args['next_text'] = __( 'Next', 'woostify' );
		return $args;
	}
}

if ( ! function_exists( 'woostify_print_out_of_stock_label' ) ) {
	/**
	 * Print out of stock label
	 */
	function woostify_print_out_of_stock_label() {
		global $product;

		if ( ! $product ) {
			return;
		}

		$product_id   = $product->get_id();
		$out_of_stock = get_post_meta( $product_id, '_stock_status', true );
		$options      = woostify_options( false );
		$product_type = WC_Product_Factory::get_product_type( $product_id );

		if ( ! $out_of_stock || 'none' === $options['shop_page_out_of_stock_position'] || 'external' === $product_type || $product->backorders_allowed() ) {
			return;
		}

		$is_square = $options['shop_page_out_of_stock_square'] ? 'is-square' : '';

		if ( 'outofstock' === $out_of_stock ) {
			?>
			<span class="woostify-out-of-stock-label position-<?php echo esc_attr( $options['shop_page_out_of_stock_position'] ); ?> <?php echo esc_attr( $is_square ); ?>"><?php echo esc_html( $options['shop_page_out_of_stock_text'] ); ?></span>
			<?php
		}
	}
}

if ( ! function_exists( 'woostify_change_sale_flash' ) ) {
	/**
	 * Change sale flash
	 */
	function woostify_change_sale_flash() {
		global $product;
		if ( ! $product || ! is_object( $product ) || class_exists( 'BM_Price' ) ) {
			return;
		}
		$options      = woostify_options( false );
		$sale         = $product->is_on_sale();
		$price_sale   = $product->get_sale_price();
		$price        = $product->get_regular_price();
		$simple       = $product->is_type( 'simple' );
		$variable     = $product->is_type( 'variable' );
		$external     = $product->is_type( 'external' );
		$bundle       = $product->is_type( 'bundle' );
		$sale_text    = $options['shop_page_sale_text'];
		$sale_percent = $options['shop_page_sale_percent'];
		$final_price  = '';
		$out_of_stock = get_post_meta( $product->get_id(), '_stock_status', true );

		if ( 'outofstock' === $out_of_stock ) {
			return;
		}

		if ( $sale ) {
			// For simple product.
			if ( $simple || $external || $bundle ) {
				if ( $sale_percent ) {
					$final_price = ( ( $price - $price_sale ) / $price ) * 100;
					$final_price = '-' . round( $final_price ) . '%';
				} elseif ( $sale_text ) {
					$final_price = $sale_text;
				}
			} elseif ( $variable && $sale_text ) {
				// For variable product.
				$final_price = $sale_text;
			}

			if ( ! $final_price ) {
				return;
			}

			$final_price = apply_filters( 'woostify_price_flash', $final_price, $product );

			$classes[] = 'woostify-tag-on-sale onsale';
			$classes[] = 'sale-' . $options['shop_page_sale_tag_position'];
			$classes[] = $options['shop_page_sale_square'] ? 'is-square' : '';
			?>
			<span class="<?php echo esc_attr( implode( ' ', array_filter( $classes ) ) ); ?>">
				<?php echo esc_html( $final_price ); ?>
			</span>
			<?php
		}
	}
}

if ( ! function_exists( 'woostify_single_product_group_buttons' ) ) {
	/**
	 * Add group buttons for product: video, gallery open,...
	 */
	function woostify_single_product_group_buttons() {
		$options = woostify_options( false );
		$output  = '';

		$output .= woostify_product_video_button_play();

		if ( $options['shop_single_image_lightbox'] ) {
			$btn_icon = apply_filters( 'woostify_shop_single_image_lightbox_icon', 'fullscreen' );
			$output  .= '<button class="photoswipe-toggle-button">' . Woostify_Icon::fetch_svg_icon( $btn_icon, false ) . '</button>';
		}

		$buttons_output = apply_filters( 'woostify_single_product_group_buttons', $output );

		echo '<div class="product-group-btns">' . $buttons_output . '</div>'; // phpcs:ignore
	}
}

if ( ! function_exists( 'woostify_product_video_button_play' ) ) {
	/**
	 * Add button play video lightbox for product
	 */
	function woostify_product_video_button_play() {
		global $product;
		if ( ! $product || ! is_object( $product ) ) {
			return;
		}

		$output = '';

		$product_id = $product->get_id();
		$video_url  = woostify_get_metabox( $product_id, 'woostify_product_video_metabox' );

		if ( 'default' !== $video_url ) {
			$output .= '<a href="' . esc_url( $video_url ) . '" data-lity class="woostify-lightbox-button">' . Woostify_Icon::fetch_svg_icon( 'control-play', false ) . '</a>';
		}

		return $output;
	}
}

if ( ! function_exists( 'woostify_content_fragments' ) ) {
	/**
	 * Update content via ajax
	 *
	 * @param      array $fragments Fragments to refresh via AJAX.
	 */
	function woostify_content_fragments( $fragments ) {
		$options         = woostify_options( false );
		$cart_item_count = WC()->cart->cart_contents_count;

		// Get mini cart content.
		ob_start();
		woostify_mini_cart();
		$mini_cart = ob_get_clean();

		// Cart item count.
		$header_cart_count_classes = array();
		$cart_subtotal_classes     = array();
		if ( $options['header_shop_hide_zero_value_cart_count'] ) {
			$header_cart_count_classes[] = 'hide-zero-val';
		}
		if ( $options['header_shop_hide_zero_value_cart_subtotal'] ) {
			$cart_subtotal_classes[] = 'hide-zero-val';
		}
		if ( $cart_item_count < 1 ) {
			$header_cart_count_classes[] = 'hide';
			$cart_subtotal_classes[]     = 'hide';
		}
		$fragments['div.woostify-header-total-price'] = sprintf( '<div class="woostify-header-total-price %s">%s</div>', implode( ' ', $cart_subtotal_classes ), WC()->cart->get_cart_subtotal() );

		$fragments['span.shop-cart-count'] = sprintf( '<span class="shop-cart-count %s">%s</span>', implode( ' ', $header_cart_count_classes ), $cart_item_count );

		// Cart sidebar.
		$top_content                = $options['mini_cart_top_content_select'];
		$enabled_shipping_threshold = $options['shipping_threshold_enabled'];
		$enable_progress_bar        = $options['shipping_threshold_enable_progress_bar'];
		$cart_clss                  = array();

		if ( WC()->cart->is_empty() ) {
			$cart_clss[] = 'is-cart-empty';
		}
		if ( ( 'fst' === $top_content ) && $enabled_shipping_threshold ) {
			$cart_clss[] = 'has-fst';
			if ( 'fst' === $top_content ) {
				$cart_clss[] = 'has-fst-top';
			}
			if ( $enable_progress_bar ) {
				$cart_clss[] = 'has-fst-progress-bar';
			}
		}
		$fragments['div.cart-sidebar-content'] = sprintf( '<div class="cart-sidebar-content %s">%s</div>', esc_attr( implode( ' ', $cart_clss ) ), $mini_cart );

		// Wishlist counter.
		if ( 'ti' === $options['shop_page_wishlist_support_plugin'] && function_exists( 'tinv_get_option' ) && tinv_get_option( 'topline', 'show_counter' ) ) {
			$fragments['span.theme-item-count.wishlist-item-count'] = sprintf( '<span class="theme-item-count wishlist-item-count">%s</span>', woostify_get_wishlist_count() );
		}

		return $fragments;
	}
}

if ( ! function_exists( 'woostify_add_notices_html_cart_fragments' ) ) {
	/**
	 * Add notice html content to cart fragments
	 *
	 * @param      array $fragments Fragments to refresh via AJAX.
	 * @return     array $fragments Fragments to refresh via AJAX
	 */
	function woostify_add_notices_html_cart_fragments( $fragments ) {
		$all_notices  = WC()->session->get( 'wc_notices', array() );
		$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );

		ob_start();
		foreach ( $notice_types as $notice_type ) {
			if ( wc_notice_count( $notice_type ) > 0 ) {
				wc_get_template(
					"notices/{$notice_type}.php",
					array(
						'notices' => array_filter( $all_notices[ $notice_type ] ),
					)
				);
			}
		}
		$fragments['notices_html'] = ob_get_clean();

		wc_clear_notices();

		return $fragments;
	}
}

if ( ! function_exists( 'woostify_update_order_review_fragments' ) ) {
	/**
	 * Update content via ajax
	 *
	 * @param      array $fragments Fragments to refresh via AJAX.
	 */
	function woostify_update_order_review_fragments( $fragments ) {
		$get_cart = WC()->cart->get_totals();
		$price    = 'yes' === get_option( 'woocommerce_calc_taxes' ) ? ( (float) $get_cart['cart_contents_total'] + (float) $get_cart['total_tax'] ) : $get_cart['cart_contents_total'];

		$fragments['_first_step_price'] = wp_kses( wc_price( $price ), array() );

		return $fragments;
	}
}

if ( ! function_exists( 'woostify_woocommerce_loop_start' ) ) {
	/**
	 * Modify: Loop start
	 *
	 * @param string $loop_start The loop start.
	 */
	function woostify_woocommerce_loop_start( $loop_start ) {
		$options = woostify_options( false );
		$class[] = 'products';
		$class[] = 'columns-' . wc_get_loop_prop( 'columns' );
		$class[] = 'tablet-columns-' . $options['tablet_products_per_row'];
		$class[] = 'mobile-columns-' . $options['mobile_products_per_row'];
		$class   = implode( ' ', $class );
		?>
		<ul class="<?php echo esc_attr( apply_filters( 'woostify_product_catalog_columns', $class ) ); ?>">
		<?php

		// If displaying categories, append to the loop.
		$loop_html = woocommerce_maybe_show_product_subcategories();
		echo $loop_html; // phpcs:ignore
	}
}

if ( ! function_exists( 'woostify_products_per_row' ) ) {
	/**
	 * Products per row
	 */
	function woostify_products_per_row() {
		$options = woostify_options( false );

		return $options['products_per_row'];
	}
}

if ( ! function_exists( 'woostify_products_per_page' ) ) {
	/**
	 * Products per page
	 */
	function woostify_products_per_page() {
		$options = woostify_options( false );

		return $options['products_per_page'];
	}
}

if ( ! function_exists( 'woostify_product_loop_item_add_to_cart_icon' ) ) {
	/**
	 * Add to cart icon
	 */
	function woostify_product_loop_item_add_to_cart_icon() {
		$options = woostify_options( false );
		if ( 'icon' !== $options['shop_page_add_to_cart_button_position'] ) {
			return;
		}

		woostify_modified_add_to_cart_button();
	}
}

if ( ! function_exists( 'woostify_product_loop_item_wishlist_icon' ) ) {
	/**
	 * Product loop wishlist icon
	 */
	function woostify_product_loop_item_wishlist_icon() {
		$options = woostify_options( false );
		if ( 'top-right' !== $options['shop_page_wishlist_position'] || ! woostify_support_wishlist_plugin() ) {
			return;
		}

		$shortcode = ( 'ti' === $options['shop_page_wishlist_support_plugin'] ) ? '[ti_wishlists_addtowishlist]' : '[yith_wcwl_add_to_wishlist]';

		echo do_shortcode( $shortcode );
	}
}

if ( ! function_exists( 'woostify_detect_clear_cart_submit' ) ) {
	/**
	 * Clear cart button.
	 */
	function woostify_detect_clear_cart_submit() {
		global $woocommerce;

		if ( isset( $_GET['empty-cart'] ) ) { // phpcs:ignore
			$woocommerce->cart->empty_cart();
		}
	}
}

if ( ! function_exists( 'woostify_remove_woocommerce_shop_title' ) ) {
	/**
	 * Removes a woocommerce shop title.
	 */
	function woostify_remove_woocommerce_shop_title() {
		return false;
	}
}

if ( ! function_exists( 'woostify_change_cross_sells_total' ) ) {
	/**
	 * Change cross sell total
	 *
	 * @param      int $limit  The total product.
	 */
	function woostify_change_cross_sells_total( $limit ) {
		return 4;
	}
}

if ( ! function_exists( 'woostify_change_cross_sells_columns' ) ) {
	/**
	 * Change cross sell column
	 *
	 * @param      int $columns  The columns.
	 */
	function woostify_change_cross_sells_columns( $columns ) {
		return 2;
	}
}

if ( ! function_exists( 'woostify_add_product_thumbnail_to_checkout_order' ) ) {
	/**
	 * Add thumbnail image for checkout detail
	 *
	 * @param      string       $product_name   The product name.
	 * @param      array|object $cart_item      The cartesian item.
	 * @param      string       $cart_item_key  The cartesian item key.
	 */
	function woostify_add_product_thumbnail_to_checkout_order( $product_name, $cart_item, $cart_item_key ) {
		$options             = woostify_options( false );
		$multi_step_checkout = woostify_is_multi_checkout();
		if ( ! is_checkout() || ! ( $multi_step_checkout && ! is_singular( array( 'cartflows_flow', 'cartflows_step' ) ) ) ) {
			return $product_name;
		}

		$data      = $cart_item['data'];
		$image_id  = ! empty( $data ) ? $data->get_image_id() : false;
		$image_alt = woostify_image_alt( $image_id, __( 'Product Image', 'woostify' ) );
		$image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src();

		ob_start();
		?>
		<?php if ( $image_src ) { ?>
			<img class="review-order-product-image" src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
		<?php } ?>

		<span class="review-order-product-name">
			<?php echo wp_kses_post( $product_name ); ?>
		</span>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'woostify_check_shipping_method' ) ) {
	/**
	 * Check shipping method
	 */
	function woostify_check_shipping_method() {
		if ( ! woostify_is_woocommerce_activated() ) {
			return false;
		}

		return WC()->cart->needs_shipping() && WC()->cart->show_shipping();
	}
}

if ( ! function_exists( 'woostify_is_multi_checkout' ) ) {
	/**
	 * Detect multi checkout page
	 */
	function woostify_is_multi_checkout() {
		if ( ! woostify_is_woocommerce_activated() || is_singular( array( 'cartflows_flow', 'cartflows_step' ) ) ) {
			return false;
		}

		$options = woostify_options( false );
		return ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) && ! is_wc_endpoint_url( 'order-pay' ) && ( 'layout-2' === $options['checkout_page_layout'] ) );
	}
}

if ( ! function_exists( 'woostify_multi_step_checkout' ) ) {
	/**
	 * Multi step checkout
	 */
	function woostify_multi_step_checkout() {
		$container          = woostify_site_container();
		$disable_multi_step = apply_filters( 'woostify_disable_multi_step_checkout', false );

		if ( $disable_multi_step || ! woostify_is_multi_checkout() ) {
			return;
		}
		?>

		<div class="multi-step-checkout">
			<div class="<?php echo esc_attr( $container ); ?>">
				<div class="multi-step-inner">
					<span class="multi-step-item active" data-state="billing">
						<span class="item-text"><?php esc_html_e( 'Billing Details', 'woostify' ); ?></span>
					</span>

					<span class="multi-step-item" data-state="delivery">
						<span class="item-text"><?php esc_html_e( 'Delivery', 'woostify' ); ?></span>
					</span>

					<span class="multi-step-item" data-state="payment">
						<span class="item-text"><?php esc_html_e( 'Payment', 'woostify' ); ?></span>
					</span>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_multi_checkout_wrapper_start' ) ) {
	/**
	 * Wrapper start
	 */
	function woostify_multi_checkout_wrapper_start() {
		if ( ! woostify_is_multi_checkout() ) {
			return;
		}
		?>
		<div class="multi-step-checkout-wrapper first">
		<?php
	}
}

if ( ! function_exists( 'woostify_multi_checkout_wrapper_end' ) ) {
	/**
	 * First step end
	 */
	function woostify_multi_checkout_wrapper_end() {
		if ( ! woostify_is_multi_checkout() ) {
			return;
		}
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_multi_checkout_first_wrapper_start' ) ) {
	/**
	 * First wrapper start
	 */
	function woostify_multi_checkout_first_wrapper_start() {
		if ( ! woostify_is_multi_checkout() ) {
			return;
		}
		?>
		<div class="multi-step-checkout-content active" data-step="first">
		<?php
		do_action( 'woostify_multi_step_checkout_first' );
	}
}

if ( ! function_exists( 'woostify_multi_checkout_first_wrapper_end' ) ) {
	/**
	 * First wrapper end
	 */
	function woostify_multi_checkout_first_wrapper_end() {
		if ( ! woostify_is_multi_checkout() ) {
			return;
		}
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_multi_checkout_second' ) ) {
	/**
	 * Second step
	 */
	function woostify_multi_checkout_second() {
		if ( ! woostify_is_multi_checkout() ) {
			return;
		}
		?>

		<div class="multi-step-checkout-content" data-step="second">
			<div class="multi-step-review-information">
				<div class="multi-step-review-information-row" data-type="email">
					<div class="review-information-inner">
						<div class="review-information-label"><?php esc_html_e( 'Contact', 'woostify' ); ?></div>
						<div class="review-information-content"></div>
					</div>
					<span class="review-information-link"><?php esc_html_e( 'Change', 'woostify' ); ?></span>
				</div>

				<div class="multi-step-review-information-row" data-type="address">
					<div class="review-information-inner">
						<div class="review-information-label"><?php esc_html_e( 'Address', 'woostify' ); ?></div>
						<div class="review-information-content"></div>
					</div>
					<span class="review-information-link"><?php esc_html_e( 'Change', 'woostify' ); ?></span>
				</div>
			</div>

			<?php do_action( 'woostify_multi_step_checkout_second' ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_multi_checkout_third' ) ) {
	/**
	 * Third step
	 */
	function woostify_multi_checkout_third() {
		if ( ! woostify_is_multi_checkout() ) {
			return;
		}
		?>
		<div class="multi-step-checkout-content" data-step="last">
			<div class="multi-step-review-information">
				<div class="multi-step-review-information-row" data-type="email">
					<div class="review-information-inner">
						<div class="review-information-label"><?php esc_html_e( 'Contact', 'woostify' ); ?></div>
						<div class="review-information-content"></div>
					</div>
					<span class="review-information-link"><?php esc_html_e( 'Change', 'woostify' ); ?></span>
				</div>

				<div class="multi-step-review-information-row" data-type="address">
					<div class="review-information-inner">
						<div class="review-information-label"><?php esc_html_e( 'Address', 'woostify' ); ?></div>
						<div class="review-information-content"></div>
					</div>
					<span class="review-information-link"><?php esc_html_e( 'Change', 'woostify' ); ?></span>
				</div>

				<div class="multi-step-review-information-row" data-type="shipping">
					<div class="review-information-inner">
						<div class="review-information-label"><?php esc_html_e( 'Shipping', 'woostify' ); ?></div>
						<div class="review-information-content"></div>
					</div>
					<span class="review-information-link"><?php esc_html_e( 'Change', 'woostify' ); ?></span>
				</div>
			</div>

			<?php do_action( 'woostify_multi_step_checkout_third' ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_multi_checkout_button_action' ) ) {
	/**
	 * First step end
	 */
	function woostify_multi_checkout_button_action() {
		if ( ! woostify_is_multi_checkout() ) {
			return;
		}

		$label       = esc_html__( 'Place Order', 'woostify' );
		$place_order = apply_filters( 'woostify_checkout_order_button', '<button type="submit" class="multi-step-checkout-button button" name="woocommerce_checkout_place_order" id="place_order" data-value="' . $label . '">' . $label . '</button>' );
		?>
			<div class="multi-step-checkout-button-wrapper">
				<span class="multi-step-checkout-button" data-action="back"><?php Woostify_Icon::fetch_svg_icon( 'angle-left' ); ?><?php esc_html_e( 'Back', 'woostify' ); ?></span>
				<span class="multi-step-checkout-button button" data-action="continue" data-continue="<?php esc_attr_e( 'Continue to', 'woostify' ); ?>"><?php esc_html_e( 'Continue to Delivery', 'woostify' ); ?></span>
				<?php echo wp_kses_post( $place_order ); ?>
			</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_checkout_before_order_review' ) ) {
	/**
	 * Before order review
	 */
	function woostify_checkout_before_order_review() {
		$cart = WC()->cart->get_cart();
		if ( empty( $cart ) || ! woostify_is_multi_checkout() ) {
			return;
		}

		$cart_count = sprintf( /* translators: 1: single item, 2: plural items */ _n( '%s item', '%s items', count( $cart ), 'woostify' ), count( $cart ) );
		?>

		<div class="woostify-before-order-review">
			<div class="woostify-before-order-review-summary">
				<strong><?php esc_html_e( 'Order Summary', 'woostify' ); ?></strong>
				<span class="woostify-before-order-review-cart-count">(<?php echo esc_html( $cart_count ); ?>)</span>
			</div>
			<span class="woostify-before-order-review-total-price"><?php wc_cart_totals_order_total_html(); ?></span>
			<span class="woostify-before-order-review-icon">
			<?php Woostify_Icon::fetch_svg_icon( 'angle-down' ); ?>
			</span>
		</div>
		<?php
	}
}

if ( ! function_exists( 'custom_template_single_title' ) ) {
	/**
	 * Custom title
	 */
	function custom_template_single_title() {
		?>
			<h1 class="product_title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<?php
	}
}

if ( ! function_exists( 'woostify_modify_woocommerce_loop_add_to_cart_link' ) ) {
	/**
	 * Add custom svg icon to add to cart button in loop
	 *
	 * @param string $html html.
	 * @param object $product product.
	 * @param array  $args args.
	 *
	 * @return string
	 */
	function woostify_modify_woocommerce_loop_add_to_cart_link( $html, $product, $args ) {
		$icon = apply_filters( 'woostify_add_to_cart_svg_icon_name', 'shopping-cart-2' );
		return sprintf(
			'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			Woostify_Icon::fetch_svg_icon( $icon, false ) . esc_html( $product->add_to_cart_text() )
		);
	}
}

if ( ! function_exists( 'woostify_wc_demo_store_notice' ) ) {
	/**
	 * Add icon before store notice text
	 */
	function woostify_wc_demo_store_notice() {
		if ( ! is_store_notice_showing() ) {
			return;
		}

		$notice = get_option( 'woocommerce_demo_store_notice' );

		if ( empty( $notice ) ) {
			$notice = __( 'This is a demo store for testing purposes &mdash; no orders shall be fulfilled.', 'woostify' );
		}
		$notice_id = md5( $notice );

		$notice_icon         = apply_filters( 'woostify_demo_store_icon_before_text', 'info-alt' );
		$dismiss_notice_icon = apply_filters( 'woostify_demo_store_icon_before_dismiss_text', 'close' );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo apply_filters( 'woocommerce_demo_store', '<p class="woocommerce-store-notice demo_store" data-notice-id="' . esc_attr( $notice_id ) . '" style="display:none;">' . Woostify_Icon::fetch_svg_icon( $notice_icon, false ) . wp_kses_post( $notice ) . ' <a href="#" class="woocommerce-store-notice__dismiss-link">' . Woostify_Icon::fetch_svg_icon( $dismiss_notice_icon, false ) . esc_html__( 'Dismiss', 'woostify' ) . '</a></p>', $notice );
	}
}

if ( ! function_exists( 'woostify_wc_custom_product_search_form' ) ) {
	/**
	 * Custom product search form
	 *
	 * @return mixed
	 */
	function woostify_wc_custom_product_search_form() {
		global $product_search_form_index;

		if ( empty( $product_search_form_index ) ) {
			$product_search_form_index = 0;
		}

		$index = $product_search_form_index++;

		$output  = '<form role="search" method="get" class="woocommerce-product-search" action="' . esc_url( home_url( '/' ) ) . '">';
		$output .= '<label class="screen-reader-text" for="woocommerce-product-search-field-' . absint( $index ) . '">' . esc_html__( 'Search for:', 'woostify' ) . '></label>';
		$output .= '<input type="search" id="woocommerce-product-search-field-' . absint( $index ) . '" class="search-field" placeholder="' . esc_attr__( 'Search products&hellip;', 'woostify' ) . '" value="' . get_search_query() . '" name="s" />';
		$output .= '<button type="submit" value="' . esc_attr_x( 'Search', 'submit button', 'woostify' ) . '">' . esc_html_x( 'Search', 'submit button', 'woostify' ) . '</button>';
		$output .= '<input type="hidden" name="post_type" value="product" />';
		$output .= '<span class="search-form-icon">' . Woostify_Icon::fetch_svg_icon( 'search', false ) . '</span>';
		$output .= '</form>';
		return $output;
	}
}

if ( ! function_exists( 'woostify_filter_woocommerce_cart_item_remove_link' ) ) {
	/**
	 * Override WC cart item remove link
	 *
	 * @param string $sprintf Remove item link.
	 * @param string $cart_item_key Cart item key.
	 *
	 * @return array|string|string[]
	 */
	function woostify_filter_woocommerce_cart_item_remove_link( $sprintf, $cart_item_key ) {
		if ( is_cart() ) {
			return $sprintf;
		}
		$icon    = Woostify_Icon::fetch_svg_icon( 'close', false );
		$sprintf = str_replace( '</a>', $icon . '</a>', $sprintf );

		return $sprintf;
	}
}

if ( ! function_exists( 'woostify_override_woocommerce_account_navigation' ) ) {
	/**
	 * Woocommerce account navagation
	 */
	function woostify_override_woocommerce_account_navigation() {
		do_action( 'woocommerce_before_account_navigation' );
		?>
		<nav class="woocommerce-MyAccount-navigation">
			<ul>
			<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
				<?php
				$icon = '';
				switch ( $endpoint ) {
					case 'dashboard':
						$icon = 'dashboard';
						break;
					case 'orders':
						$icon = 'list';
						break;
					case 'downloads':
						$icon = 'download';
						break;
					case 'edit-address':
						$icon = 'direction';
						break;
					case 'edit-account':
						$icon = 'user';
						break;
					case 'tinv_wishlist':
						$icon = 'heart';
						break;
					case 'customer-logout':
						$icon = 'pencil-alt';
						break;
					default:
						$icon = 'dashboard';

				}
				$icon = apply_filters( 'woostify_wc_myaccount_nav_icon', $icon, $endpoint );
				?>
				<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); // phpcs:ignore ?>">
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
					<?php
						Woostify_Icon::fetch_svg_icon( $icon );
						echo esc_html( $label );
					?>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		</nav>
		<?php
		do_action( 'woocommerce_after_account_navigation' );
	}
}

if ( ! function_exists( 'woostify_disable_woocommerce_block_styles' ) ) {
	/**
	 * Remove wc blocks style
	 */
	function woostify_disable_woocommerce_block_styles() {
		$options = woostify_options( false );

		if ( $options['performance_disable_woo_blocks_styles'] ) {
			wp_dequeue_style( 'wc-blocks-style' );
		}
	}
}

if ( ! function_exists( 'woostify_product_quantity' ) ) {
	/**
	 * Display quantity input shop page
	 */
	function woostify_product_quantity() {
		$options = woostify_options( false );

		if ( 'none' === $options['shop_page_add_to_cart_button_position'] ) {
			return;
		}

		$product = wc_get_product( get_the_ID() );

		if ( $product->is_sold_individually() || 'variable' === $product->get_type() || ! $product->is_purchasable() ) {
			return;
		}

		$html = '';

		$html .= '<div class="loop-product-qty">';
		$html .= woocommerce_quantity_input(
			array(
				'min_value' => 1,
				'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
			),
			$product,
			false
		);
		$html .= '</div>';

		echo $html; // phpcs:ignore.
	}
}

if ( ! function_exists( 'woostify_checkout_form_distr_free_bg' ) ) {
	/**
	 * Checkout form background.
	 */
	function woostify_checkout_form_distr_free_bg() {
		?>
		<div class="form-distr-free-bg">
			<div class="col-left"></div>
			<div class="woostify-col right-bg"></div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_checkout_row_start' ) ) {
	/**
	 * Checkout form add row start element
	 */
	function woostify_checkout_row_start() {
		echo '<div class="woostify-row">';
	}
}

if ( ! function_exists( 'woostify_checkout_col_left_start' ) ) {
	/**
	 * Checkout form add column left start element
	 */
	function woostify_checkout_col_left_start() {
		echo '<div class="col-left">';
		echo '<div id="checkout-spacer"></div>';
		echo '<div class="woostify-woocommerce-NoticeGroup"></div>';
	}
}

if ( ! function_exists( 'woostify_checkout_back_to_cart_link' ) ) {
	/**
	 * Add back to cart link.
	 */
	function woostify_checkout_back_to_cart_link() {
		echo '<div class="back-to-cart"><a href="' . esc_url( wc_get_cart_url() ) . '" class="outlined">' . Woostify_Icon::fetch_svg_icon( 'angle-left', false ) . '<span> ' . esc_html__( 'Back to cart', 'woostify' ) . '</span></a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'woostify_checkout_col_left_end' ) ) {
	/**
	 * Checkout form add column left end element
	 */
	function woostify_checkout_col_left_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'woostify_checkout_col_right_start' ) ) {
	/**
	 * Checkout form add column right start element
	 */
	function woostify_checkout_col_right_start() {
		echo '<div class="woostify-col"><div class="col-right-inner">';
	}
}

if ( ! function_exists( 'woostify_checkout_col_right_end' ) ) {
	/**
	 * Checkout form add column right end element
	 */
	function woostify_checkout_col_right_end() {
		echo '</div></div>';
	}
}

if ( ! function_exists( 'woostify_checkout_row_end' ) ) {
	/**
	 * Checkout form add row end element
	 */
	function woostify_checkout_row_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'woostify_checkout_product_image' ) ) {
	/**
	 * Add product image and quantity before cart item product name in checkout page
	 *
	 * @param string $name Product name.
	 * @param object $cart_item Cart item data array.
	 * @param int    $cart_item_key Cart item key.
	 */
	function woostify_checkout_product_image( $name, $cart_item, $cart_item_key ) {
		if ( ! is_checkout() ) {
			return $name;
		}

		$_product  = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$thumbnail = $_product->get_image();
		$image     = '<div class="w-product-thumb">';
		$image    .= $thumbnail;
		$image    .= '<strong class="product-quantity">' . $cart_item['quantity'] . '</strong>';
		$image    .= '</div>';

		return $image . $name;
	}
}

if ( ! function_exists( 'woostify_checkout_product_quantity' ) ) {
	/**
	 * Remove product quantity after cart item product name in checkout page
	 *
	 * @param string $html quantity html.
	 * @param object $cart_item Cart item data array.
	 * @param int    $cart_item_key Cart item key.
	 */
	function woostify_checkout_product_quantity( $html, $cart_item, $cart_item_key ) {
		if ( ! is_checkout() ) {
			return $html;
		}

		return false;
	}
}

if ( ! function_exists( 'woostify_checkout_options_start' ) ) {
	/**
	 * Checkout page options start element
	 */
	function woostify_checkout_options_start() {
		?>
		<div class="checkout-options">
			<div class="woostify-row">
				<div class="col-left">
					<div class="before-checkout">
		<?php
	}
}

if ( ! function_exists( 'woostify_checkout_options_end' ) ) {
	/**
	 * Checkout page options end element
	 */
	function woostify_checkout_options_end() {
		?>
					</div>
				</div>
				<div class="woostify-col"></div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_checkout_coupon_form' ) ) {
	/**
	 * Custom coupon code form html
	 */
	function woostify_checkout_coupon_form() {
		if ( is_user_logged_in() || WC()->checkout()->is_registration_enabled() || ! WC()->checkout()->is_registration_required() ) {
			echo '<tr class="coupon-form"><td colspan="2"><div class="ajax-coupon-form loading">';
			wc_get_template(
				'checkout/form-coupon.php',
				array(
					'checkout' => WC()->checkout(),
				)
			);
			echo '</div></tr></td>';
		}
	}
}

if ( ! function_exists( 'woostify_output_product_data_tabs' ) ) {
	/**
	 * Custom product data tabs for normal layout
	 */
	function woostify_output_product_data_tabs() {
		$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

		if ( ! empty( $product_tabs ) ) :
			?>

			<div class="woocommerce-tabs wc-tabs-wrapper">
				<ul class="tabs wc-tabs" role="tablist">
					<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
						<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
							<a href="#tab-<?php echo esc_attr( $key ); ?>">
								<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
						<?php
						if ( isset( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						}
						?>
					</div>
				<?php endforeach; ?>

				<?php do_action( 'woocommerce_product_after_tabs' ); ?>
			</div>

			<?php
		endif;
	}
}

if ( ! function_exists( 'woostify_output_product_data_tabs_accordion' ) ) {
	/**
	 * Custom product data tabs for accordion layout
	 */
	function woostify_output_product_data_tabs_accordion() {
		$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
		$options      = woostify_options( false );
		$open_tab     = $options['shop_single_product_data_tabs_open'];
		if ( ! empty( $product_tabs ) ) :
			?>

			<div class="woocommerce-tabs wc-tabs-wrapper layout-accordion">
				<?php
				$i = 0;
				foreach ( $product_tabs as $key => $product_tab ) :
					?>
					<div class="woostify-tab-wrapper <?php echo ( $open_tab && 0 === $i ) ? esc_attr( 'active' ) : ''; ?>">
						<a href="javascript:;" class="woostify-accordion-title">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
							<?php Woostify_Icon::fetch_svg_icon( 'angle-down', true ); ?>
						</a>
						<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab  <?php echo ( $open_tab && 0 === $i ) ? esc_attr( 'is-visible' ) : ''; ?>" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
							<div class="woostify-tab-inner">
								<div class="woostify-tab-scroll-content">
								<?php
								if ( isset( $product_tab['callback'] ) ) {
									call_user_func( $product_tab['callback'], $key, $product_tab );
								}
								?>
								</div>
							</div>
						</div>
					</div>
					<?php
					$i++;
				endforeach;
				?>

				<?php do_action( 'woocommerce_product_after_tabs' ); ?>
			</div>

			<?php
		endif;
	}
}

if ( ! function_exists( 'woostify_custom_product_data_tabs' ) ) {
	/**
	 * Woostify custom tabs
	 *
	 * @param array $tabs default tabs.
	 */
	function woostify_custom_product_data_tabs( $tabs ) {
		global $product, $post;

		$new_tabs    = array();
		$options     = woostify_options( false );
		$custom_tabs = $options['shop_single_product_data_tabs_items'];
		$custom_tabs = json_decode( $custom_tabs );
		$new_data    = array(
			'title'    => '',
			'priority' => '',
			'callback' => '',
		);
		foreach ( $custom_tabs as $key => $custom_tab ) {
			$priority = $key * 5;
			if ( 'custom' === $custom_tab->type ) {
				$custom_tab_key              = 'custom_tab_' . $key;
				$new_data                    = array(
					'title'    => $custom_tab->name,
					'priority' => $priority,
					'callback' => 'woostify_custom_tab_callback',
				);
				$new_tabs[ $custom_tab_key ] = $new_data;
			} else {
				if ( isset( $tabs[ $custom_tab->type ] ) ) {
					$new_data                      = $tabs[ $custom_tab->type ];
					$new_data['priority']          = $key * 5;
					$new_tabs[ $custom_tab->type ] = $new_data;
				} else {
					switch ( $custom_tab->type ) {
						case 'description':
							if ( $post->post_content ) {
								$new_tabs['description'] = array(
									'title'    => __( 'Description', 'woostify' ),
									'priority' => $priority,
									'callback' => 'woocommerce_product_description_tab',
								);
							}
							break;
						case 'additional_information':
							if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
								$new_tabs['additional_information'] = array(
									'title'    => __( 'Additional information', 'woostify' ),
									'priority' => $priority,
									'callback' => 'woocommerce_product_additional_information_tab',
								);
							}
							break;
						case 'reviews':
							if ( $product && comments_open() ) {
								$new_data['reviews'] = array(
									/* translators: %s: reviews count */
									'title'    => sprintf( __( 'Reviews (%d)', 'woocommerce' ), $product->get_review_count() ),
									'priority' => $priority,
									'callback' => 'comments_template',
								);
							}
							break;
					}
				}
			}
		}

		return $new_tabs;
	}
}

if ( ! function_exists( 'woostify_custom_tab_callback' ) ) {
	/**
	 * Callback for custom tab
	 *
	 * @param string $key Tab key.
	 * @param array  $product_tab Tab data.
	 */
	function woostify_custom_tab_callback( $key, $product_tab ) {
		$options     = woostify_options( false );
		$custom_tabs = $options['shop_single_product_data_tabs_items'];
		$custom_tabs = (array) json_decode( $custom_tabs );
		$curr_index  = explode( '_', $key )[2];

		echo do_shortcode( $custom_tabs[ $curr_index ]->content );
	}
}
