<?php
/**
 * Archive Product template functions
 *
 * @package woostify
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'woostify_loop_product_wrapper_open' ) ) {
	/**
	 * Loop product wrapper open tag
	 */
	function woostify_loop_product_wrapper_open() {
		?>
		<div class="product-loop-wrapper">
		<?php
	}
}

if ( ! function_exists( 'woostify_loop_product_image_wrapper_open' ) ) {
	/**
	 * Loop product image wrapper open tag
	 */
	function woostify_loop_product_image_wrapper_open() {
		$options = woostify_options( false );
		$class[] = 'product-loop-image-wrapper';
		$class[] = 'zoom' === $options['shop_page_product_image_hover'] ? $options['shop_page_product_image_hover'] . '-hover' : '';
		$class[] = $options['shop_page_product_image_equal_height'] ? 'has-equal-image-height' : '';
		$class[] = apply_filters( 'woostify_additional_class_loop_product_image', '' );
		$class   = trim( implode( ' ', $class ) );

		echo '<div class="' . esc_attr( $class ) . '">';
	}
}

if ( ! function_exists( 'woostify_product_loop_item_action' ) ) {
	/**
	 * Product loop action
	 */
	function woostify_product_loop_item_action() {
		?>
		<div class="product-loop-action"><?php do_action( 'woostify_product_loop_item_action_item' ); ?></div>
		<?php
	}
}

if ( ! function_exists( 'woostify_loop_product_link_open' ) ) {
	/**
	 * Loop product link open
	 */
	function woostify_loop_product_link_open() {
		// open tag <a>.
		woocommerce_template_loop_product_link_open();
	}
}

if ( ! function_exists( 'woostify_loop_product_hover_image' ) ) {
	/**
	 * Loop product hover image
	 */
	function woostify_loop_product_hover_image() {
		$options = woostify_options( false );
		if ( 'swap' !== $options['shop_page_product_image_hover'] ) {
			return;
		}

		global $product;
		$gallery    = $product->get_gallery_image_ids();
		$size       = 'woocommerce_thumbnail';
		$image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

		// Hover image.
		if ( ! empty( $gallery ) ) {
			$hover = wp_get_attachment_image_src( $gallery[0], $image_size );
			if ( ! empty( $hover ) ) {
				?>
				<span class="product-loop-hover-image" style="background-image: url(<?php echo esc_url( $hover[0] ); ?>);"></span>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'woostify_loop_product_image' ) ) {
	/**
	 * Loop product image
	 */
	function woostify_loop_product_image() {
		global $product;

		if ( ! $product ) {
			return '';
		}

		$size    = 'woocommerce_thumbnail';
		$img_id  = $product->get_image_id();
		$img_alt = woostify_image_alt( $img_id, esc_attr__( 'Product image', 'woostify' ) );

		$image_attr = array(
			'alt'   => $img_alt,
			'class' => 'attachment-' . $size . ' size-' . $size . ' product-loop-image',
		);

		echo $product->get_image( $size, $image_attr ); // phpcs:ignore
	}
}

if ( ! function_exists( 'woostify_loop_product_link_close' ) ) {
	/**
	 * Loop product link close
	 */
	function woostify_loop_product_link_close() {
		// close tag </a>.
		woocommerce_template_loop_product_link_close();
	}
}

if ( ! function_exists( 'woostify_modified_add_to_cart_button' ) ) {
	/**
	 * Woostify add to cart button
	 */
	function woostify_modified_add_to_cart_button() {
		$args = woostify_modify_loop_add_to_cart_class();
		woocommerce_template_loop_add_to_cart( $args );
	}
}

if ( ! function_exists( 'woostify_loop_product_add_to_cart_on_image' ) ) {
	/**
	 * Product add to cart ( On image )
	 */
	function woostify_loop_product_add_to_cart_on_image() {
		$options = woostify_options( false );
		if ( 'image' !== $options['shop_page_add_to_cart_button_position'] ) {
			return;
		}

		woostify_modified_add_to_cart_button();
	}
}

if ( ! function_exists( 'woostify_product_loop_item_wishlist_icon_bottom' ) ) {
	/**
	 * Product loop wishlist icon on bottom right
	 */
	function woostify_product_loop_item_wishlist_icon_bottom() {
		$options = woostify_options( false );
		if ( 'bottom-right' !== $options['shop_page_wishlist_position'] || ! woostify_support_wishlist_plugin() ) {
			return;
		}

		$shortcode = ( 'ti' === $options['shop_page_wishlist_support_plugin'] ) ? '[ti_wishlists_addtowishlist]' : '[yith_wcwl_add_to_wishlist]';
		?>

		<div class="loop-wrapper-wishlist">
			<?php echo do_shortcode( $shortcode ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_loop_product_image_wrapper_close' ) ) {
	/**
	 * Loop product image wrapper close tag
	 */
	function woostify_loop_product_image_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'woostify_loop_product_content_open' ) ) {
	/**
	 * Product loop content open
	 */
	function woostify_loop_product_content_open() {
		$options = woostify_options( false );
		$class   = 'text-' . $options['shop_page_product_alignment'];

		echo '<div class="product-loop-content ' . esc_attr( $class ) . '">';
	}
}

if ( ! function_exists( 'woostify_loop_product_content_close' ) ) {
	/**
	 * Product loop content close
	 */
	function woostify_loop_product_content_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'woostify_loop_product_wrapper_close' ) ) {
	/**
	 * Loop product wrapper close tag
	 */
	function woostify_loop_product_wrapper_close() {
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_add_template_loop_product_category' ) ) {
	/**
	 * Loop product category.
	 */
	function woostify_add_template_loop_product_category() {
		$options = woostify_options( false );
		if ( ! $options['shop_page_product_category'] ) {
			return;
		}
		?>
		<div class="woocommerce-loop-product__category">
			<?php
			global $product;
			$product_id = $product->get_ID();
			echo wp_kses_post( wc_get_product_category_list( $product_id ) );
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_add_template_loop_product_title' ) ) {
	/**
	 * Loop product title.
	 */
	function woostify_add_template_loop_product_title() {
		$options = woostify_options( false );
		if ( ! $options['shop_page_product_title'] ) {
			return;
		}
		?>
		<h2 class="woocommerce-loop-product__title">
			<?php
				woocommerce_template_loop_product_link_open();
				the_title();
				woocommerce_template_loop_product_link_close();
			?>
		</h2>
		<?php
	}
}

if ( ! function_exists( 'woostify_loop_product_rating' ) ) {
	/**
	 * Loop product rating
	 */
	function woostify_loop_product_rating() {
		$options = woostify_options( false );
		if ( ! $options['shop_page_product_rating'] ) {
			return;
		}

		global $product;
		echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore
	}
}

if ( ! function_exists( 'woostify_loop_product_meta_open' ) ) {
	/**
	 * Loop product meta open
	 */
	function woostify_loop_product_meta_open() {
		global $product;
		$options = woostify_options( false );

		$class = (
			! $options['shop_page_product_price'] ||
			( 'external' === $product->get_type() && '' === $product->get_price() ) ||
			'bottom' !== $options['shop_page_add_to_cart_button_position'] ||
			defined( 'YITH_WCQV_VERSION' )
		) ? 'no-transform' : '';

		echo '<div class="product-loop-meta ' . esc_attr( $class ) . '">';
		echo '<div class="animated-meta">';
	}
}

if ( ! function_exists( 'woostify_loop_product_price' ) ) {
	/**
	 * Loop product price
	 */
	function woostify_loop_product_price() {
		$options = woostify_options( false );
		if ( ! $options['shop_page_product_price'] ) {
			return;
		}

		global $product;
		$price_html = $product->get_price_html();

		if ( $price_html ) {
			?>
			<span class="price"><?php echo wp_kses_post( $price_html ); ?></span>
			<?php
		}
	}
}

if ( ! function_exists( 'woostify_loop_product_add_to_cart_button' ) ) {
	/**
	 * Loop product add to cart button
	 */
	function woostify_loop_product_add_to_cart_button() {
		$options = woostify_options( false );
		if ( in_array( $options['shop_page_add_to_cart_button_position'], array( 'none', 'image', 'icon' ), true ) ) {
			return;
		}

		$args = woostify_modify_loop_add_to_cart_class();
		woocommerce_template_loop_add_to_cart( $args );
	}
}

if ( ! function_exists( 'woostify_loop_product_meta_close' ) ) {
	/**
	 * Loop product meta close
	 */
	function woostify_loop_product_meta_close() {
		echo '</div></div>';
	}
}

if ( ! function_exists( 'woostify_toggle_sidebar_mobile_button' ) ) {
	/**
	 * Toggle sidebar mobile button
	 */
	function woostify_toggle_sidebar_mobile_button() {
		$icon = apply_filters( 'woostify_toggle_sidebar_mobile_button_icon', 'filter' );
		?>
		<button id="toggle-sidebar-mobile-button" class="<?php echo esc_attr( $icon ); ?>">
			<?php Woostify_Icon::fetch_svg_icon( $icon ); ?>
			<?php esc_html_e( 'Filter', 'woostify' ); ?></button>
		<?php
	}
}

if ( ! function_exists( 'woostify_woocommerce_toolbar_left_open_div' ) ) {
	/**
	 * Toolbar left open div
	 */
	function woostify_woocommerce_toolbar_left_open_div() {
		?>
		<div class="woostify-toolbar-left">
		<?php
	}
}
if ( ! function_exists( 'woostify_woocommerce_toolbar_left_close_div' ) ) {
	/**
	 * Toolbar left close div
	 */
	function woostify_woocommerce_toolbar_left_close_div() {
		?>
		</div>
		<?php
	}
}
