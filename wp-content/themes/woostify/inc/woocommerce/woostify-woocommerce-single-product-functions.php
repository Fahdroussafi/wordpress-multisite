<?php
/**
 * Single Product template functions
 *
 * @package woostify
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'woostify_get_prev_product' ) ) {
	/**
	 * Retrieves the previous product.
	 *
	 * @param bool         $in_same_term   Optional. Whether post should be in a same taxonomy term. Default false.
	 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
	 * @param string       $taxonomy       Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
	 * @return WC_Product|false Product object if successful. False if no valid product is found.
	 */
	function woostify_get_prev_product( $in_same_term = true, $excluded_terms = '', $taxonomy = 'product_cat' ) {
		$product = new Woostify_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy, true );
		return $product->get_product();
	}
}

if ( ! function_exists( 'woostify_get_next_product' ) ) {
	/**
	 * Retrieves the next product.
	 *
	 * @param bool         $in_same_term   Optional. Whether post should be in a same taxonomy term. Default false.
	 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
	 * @param string       $taxonomy       Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
	 * @return WC_Product|false Product object if successful. False if no valid product is found.
	 */
	function woostify_get_next_product( $in_same_term = true, $excluded_terms = '', $taxonomy = 'product_cat' ) {
		$product = new Woostify_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy );
		return $product->get_product();
	}
}

if ( ! function_exists( 'woostify_product_navigation' ) ) {
	/**
	 * Product navigation
	 */
	function woostify_product_navigation() {
		$prev_product = woostify_get_prev_product();
		$prev_id      = $prev_product ? $prev_product->get_id() : false;
		$next_product = woostify_get_next_product();
		$next_id      = $next_product ? $next_product->get_id() : false;

		if ( ! $prev_id && ! $next_id ) {
			return;
		}

		$content = '';
		$classes = '';

		if ( $prev_id ) {
			$classes        = ! $next_id ? 'product-nav-last' : '';
			$prev_icon      = apply_filters( 'woostify_product_navigation_prev_icon', 'arrow-circle-left' );
			$prev_image_id  = $prev_product->get_image_id();
			$prev_image_src = wp_get_attachment_image_src( $prev_image_id );
			$prev_image_alt = woostify_image_alt( $prev_image_id, __( 'Previous Product Image', 'woostify' ) );

			ob_start();
			?>
				<div class="prev-product-navigation product-nav-item">
					<a class="product-nav-item-text" href="<?php echo esc_url( get_permalink( $prev_id ) ); ?>">
						<span class="product-nav-icon">
							<?php Woostify_Icon::fetch_svg_icon( $prev_icon ); ?>
						</span>
						<span><?php esc_html_e( 'Previous', 'woostify' ); ?></span>
					</a>
					<div class="product-nav-item-content">
						<a class="product-nav-item-link" href="<?php echo esc_url( get_permalink( $prev_id ) ); ?>"></a>
						<?php if ( $prev_image_src ) { ?>
							<img src="<?php echo esc_url( $prev_image_src[0] ); ?>" alt="<?php echo esc_attr( $prev_image_alt ); ?>">
						<?php } ?>
						<div class="product-nav-item-inner">
							<h4 class="product-nav-item-title"><?php echo wp_kses_post( get_the_title( $prev_id ) ); ?></h4>
							<span class="product-nav-item-price"><?php echo wp_kses_post( $prev_product->get_price_html() ); ?></span>
						</div>
					</div>
				</div>
			<?php
			$content .= ob_get_clean();

		}

		if ( $next_id ) {
			$classes        = ! $prev_id ? 'product-nav-first' : '';
			$next_icon      = apply_filters( 'woostify_product_navigation_next_icon', 'arrow-circle-right' );
			$next_image_id  = $next_product->get_image_id();
			$next_image_src = wp_get_attachment_image_src( $next_image_id );
			$next_image_alt = woostify_image_alt( $next_image_id, __( 'Next Product Image', 'woostify' ) );

			ob_start();
			?>
				<div class="next-product-navigation product-nav-item">
					<a class="product-nav-item-text" href="<?php echo esc_url( get_permalink( $next_id ) ); ?>">
						<span><?php esc_html_e( 'Next', 'woostify' ); ?></span>
						<span class="product-nav-icon">
							<?php Woostify_Icon::fetch_svg_icon( $next_icon ); ?>
						</span>
					</a>
					<div class="product-nav-item-content">
						<a class="product-nav-item-link" href="<?php echo esc_url( get_permalink( $next_id ) ); ?>"></a>
						<div class="product-nav-item-inner">
							<h4 class="product-nav-item-title"><?php echo wp_kses_post( get_the_title( $next_id ) ); ?></h4>
							<span class="product-nav-item-price"><?php echo wp_kses_post( $next_product->get_price_html() ); ?></span>
						</div>
						<?php if ( $next_image_src ) { ?>
							<img src="<?php echo esc_url( $next_image_src[0] ); ?>" alt="<?php echo esc_attr( $next_image_alt ); ?>">
						<?php } ?>
					</div>
				</div>
			<?php
			$content .= ob_get_clean();
		}
		?>

		<div class="woostify-product-navigation <?php echo esc_attr( $classes ); ?>">
			<?php echo $content; // phpcs:ignore ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_single_product_container_open' ) ) {
	/**
	 * Product container open
	 */
	function woostify_single_product_container_open() {
		$container = woostify_site_container();
		?>
			<div class="product-page-container">
				<div class="<?php echo esc_attr( $container ); ?>">
		<?php
	}
}

if ( ! function_exists( 'woostify_single_product_gallery_open' ) ) {
	/**
	 * Single gallery product open
	 */
	function woostify_single_product_gallery_open() {
		$product_id = woostify_is_elementor_editor() ? woostify_get_last_product_id() : woostify_get_page_id();
		$product    = wc_get_product( $product_id );
		$options    = woostify_options( false );
		$gallery    = $options['shop_single_product_gallery_layout_select'];

		$gallery_id = ! empty( $product ) ? $product->get_gallery_image_ids() : array();
		$classes    = array();

		if ( 'theme' === $gallery ) {
			$classes[] = $options['shop_single_gallery_layout'] . '-style';
			$classes[] = ! empty( $gallery_id ) ? 'has-product-thumbnails' : '';
			$classes[] = $options['shop_single_image_load'] ? 'has-loading-effect' : '';
		} else {
			$classes[] = 'wc-default-gallery';
		}

		// Global variation gallery.
		woostify_global_for_vartiation_gallery( $product );
		?>
		<div class="product-gallery <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php
	}
}

if ( ! function_exists( 'woostify_get_default_gallery' ) ) {
	/**
	 * Get variation gallery
	 *
	 * @param object $product The product.
	 */
	function woostify_get_default_gallery( $product ) {
		$images = array();
		if ( empty( $product ) ) {
			return $images;
		}

		$product_id             = $product->get_id();
		$gallery_images         = $product->get_gallery_image_ids();
		$has_default_thumbnails = false;

		if ( ! empty( $gallery_images ) ) {
			$has_default_thumbnails = true;
		}

		if ( has_post_thumbnail( $product_id ) ) {
			array_unshift( $gallery_images, get_post_thumbnail_id( $product_id ) );
		}

		if ( ! empty( $gallery_images ) ) {
			foreach ( $gallery_images as $i => $image_id ) {
				$images[ $i ]                           = wc_get_product_attachment_props( $image_id );
				$images[ $i ]['image_id']               = $image_id;
				$images[ $i ]['has_default_thumbnails'] = $has_default_thumbnails;
			}
		}

		return $images;
	}
}

if ( ! function_exists( 'woostify_available_variation_gallery' ) ) {
	/**
	 * Available Gallery
	 *
	 * @param array  $available_variation Avaiable Variations.
	 * @param object $variation_product_object Product object.
	 * @param array  $variation Variations.
	 */
	function woostify_available_variation_gallery( $available_variation, $variation_product_object, $variation ) {
		$product_id         = $variation->get_parent_id();
		$variation_id       = $variation->get_id();
		$variation_image_id = $variation->get_image_id();
		$product            = wc_get_product( $product_id );

		if ( ! $product->is_type( 'variable' ) || ! class_exists( 'WC_Additional_Variation_Images' ) ) {
			return $available_variation;
		}

		$gallery_images = get_post_meta( $variation_id, '_wc_additional_variation_images', true );
		if ( ! $gallery_images ) {
			return $available_variation;
		}
		$gallery_images = explode( ',', $gallery_images );

		if ( $variation_image_id ) {
			// Add Variation Default Image.
			array_unshift( $gallery_images, $variation->get_image_id() );
		} elseif ( has_post_thumbnail( $product_id ) ) {
			// Add Product Default Image.
			array_unshift( $gallery_images, get_post_thumbnail_id( $product_id ) );
		}

		$available_variation['woostify_variation_gallery_images'] = array();
		foreach ( $gallery_images as $k => $v ) {
			$available_variation['woostify_variation_gallery_images'][ $k ] = wc_get_product_attachment_props( $v );
		}

		return $available_variation;
	}
}

if ( ! function_exists( 'woostify_get_variation_gallery' ) ) {
	/**
	 * Get variation gallery
	 *
	 * @param object $product The product.
	 */
	function woostify_get_variation_gallery( $product ) {
		$images = array();

		if ( ! is_object( $product ) || ! $product->is_type( 'variable' ) ) {
			return $images;
		}

		$variations = array_values( $product->get_available_variations() );
		$key        = class_exists( 'WC_Additional_Variation_Images' ) ? 'woostify_variation_gallery_images' : 'variation_gallery_images';

		$images = array();
		foreach ( $variations as $k ) {
			if ( ! isset( $k[ $key ] ) ) {
				$k[ $key ] = array();
				array_push( $k[ $key ], $k['image'] );
			}

			array_unshift( $k[ $key ], array( 'variation_id' => $k['variation_id'] ) );
			array_push( $images, $k[ $key ] );
		}

		return $images;
	}
}

if ( ! function_exists( 'woostify_global_for_vartiation_gallery' ) ) {
	/**
	 * Add global variation
	 *
	 * @param object $product The Product.
	 */
	function woostify_global_for_vartiation_gallery( $product ) {
		if ( ! class_exists( 'WC_Additional_Variation_Images' ) && ! class_exists( 'Woo_Variation_Gallery' ) ) {
			return;
		}

		// Woostify Variation gallery.
		wp_localize_script(
			'woostify-product-variation',
			'woostify_variation_gallery',
			woostify_get_variation_gallery( $product )
		);

		// Woostify default gallery.
		wp_localize_script(
			'woostify-product-variation',
			'woostify_default_gallery',
			woostify_get_default_gallery( $product )
		);
	}
}

if ( ! function_exists( 'woostify_single_product_gallery_image_slide' ) ) {
	/**
	 * Product gallery product image slider
	 */
	function woostify_single_product_gallery_image_slide() {
		$product_id = woostify_is_elementor_editor() ? woostify_get_last_product_id() : woostify_get_page_id();
		$product    = wc_get_product( $product_id );

		if ( empty( $product ) ) {
			return;
		}

		$image_id            = $product->get_image_id();
		$image_alt           = woostify_image_alt( $image_id, esc_attr__( 'Product image', 'woostify' ) );
		$get_size            = wc_get_image_size( 'shop_catalog' );
		$image_size          = $get_size['width'] . 'x' . ( ! empty( $get_size['height'] ) ? $get_size['height'] : $get_size['width'] );
		$image_medium_src[0] = wc_placeholder_img_src();
		$image_full_src[0]   = wc_placeholder_img_src();
		$image_srcset        = '';

		if ( $image_id ) {
			$image_medium_src = wp_get_attachment_image_src( $image_id, 'woocommerce_single' );
			$image_full_src   = wp_get_attachment_image_src( $image_id, 'full' );
			$image_size       = ( isset( $image_full_src[1] ) ? $image_full_src[1] : 800 ) . 'x' . ( isset( $image_full_src[2] ) ? $image_full_src[2] : 800 );
			$image_srcset     = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $image_id, 'woocommerce_single' ) : '';
		}

		if ( ! $image_id ) {
			$image_full_src[1] = '800';
			$image_full_src[2] = '800';
		}

		// Gallery.
		$gallery_id = $product->get_gallery_image_ids();

		// Support <img> srcset attr.
		$html_allowed                  = wp_kses_allowed_html( 'post' );
		$html_allowed['img']['srcset'] = true;
		?>

		<div class="product-images">
			<div id="product-images">
				<figure class="image-item ez-zoom">
					<a href="<?php echo esc_url( isset( $image_full_src[0] ) ? $image_full_src[0] : '#' ); ?>" data-size="<?php echo esc_attr( $image_size ); ?>" data-elementor-open-lightbox="no">
						<?php echo wp_kses( $product->get_image( 'woocommerce_single', array(), true ), $html_allowed ); ?>
					</a>
				</figure>
				<?php

				if ( ! empty( $gallery_id ) ) {
					foreach ( $gallery_id as $key ) {
						$g_full_img_src = wp_get_attachment_image_src( $key, 'full' );
						if ( empty( $g_full_img_src ) ) {
							continue;
						}
						$g_medium_img_src = wp_get_attachment_image_src( $key, 'woocommerce_single' );
						$g_image_size     = $g_full_img_src[1] . 'x' . $g_full_img_src[2];
						$g_img_alt        = woostify_image_alt( $key, esc_attr__( 'Product image', 'woostify' ) );
						$g_img_srcset     = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $key, 'woocommerce_single' ) : '';
						?>
						<figure class="image-item ez-zoom">
							<a href="<?php echo esc_url( $g_full_img_src[0] ); ?>" data-size="<?php echo esc_attr( $g_image_size ); ?>" data-elementor-open-lightbox="no">
								<img width="<?php echo esc_attr( $g_medium_img_src[1] ); ?>" height="<?php echo esc_attr( $g_medium_img_src[2] ); ?>"  src="<?php echo esc_url( $g_medium_img_src[0] ); ?>" alt="<?php echo esc_attr( $g_img_alt ); ?>" srcset="<?php echo wp_kses_post( $g_img_srcset ); ?>">
							</a>
						</figure>
						<?php
					}
				}
				?>
			</div>

			<?php do_action( 'woostify_product_images_box_end' ); ?>
		</div>

		<?php
	}
}

if ( ! function_exists( 'woostify_single_product_gallery_thumb_slide' ) ) {
	/**
	 * Product gallery product thumbnail slider
	 */
	function woostify_single_product_gallery_thumb_slide() {
		$options = woostify_options( false );
		if ( ! in_array( $options['shop_single_gallery_layout'], array( 'vertical', 'horizontal' ), true ) ) {
			return;
		}

		$product_id = woostify_is_elementor_editor() ? woostify_get_last_product_id() : woostify_get_page_id();
		$product    = wc_get_product( $product_id );

		if ( empty( $product ) ) {
			return;
		}

		$image_id        = $product->get_image_id();
		$image_alt       = woostify_image_alt( $image_id, esc_attr__( 'Product image', 'woostify' ) );
		$image_small_src = $image_id ? wp_get_attachment_image_src( $image_id, 'woocommerce_gallery_thumbnail' ) : wc_placeholder_img_src();
		$gallery_id      = $product->get_gallery_image_ids();
		?>

		<div class="product-thumbnail-images">
			<?php if ( ! empty( $gallery_id ) ) { ?>
			<div id="product-thumbnail-images">
				<?php if ( ! empty( $image_small_src ) ) { ?>
					<div class="thumbnail-item">
						<img src="<?php echo esc_url( $image_small_src[0] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
					</div>
				<?php } ?>

				<?php
				foreach ( $gallery_id as $key ) {
					$g_thumb_src = wp_get_attachment_image_src( $key, 'woocommerce_gallery_thumbnail' );
					$g_thumb_alt = woostify_image_alt( $key, esc_attr__( 'Product image', 'woostify' ) );

					if ( ! empty( $g_thumb_src ) ) {
						?>
						<div class="thumbnail-item">
							<img src="<?php echo esc_url( $g_thumb_src[0] ); ?>" alt="<?php echo esc_attr( $g_thumb_alt ); ?>">
						</div>
						<?php
					}
				}
				?>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_single_product_gallery_dependency' ) ) {
	/**
	 * Html markup for photo swipe lightbox
	 */
	function woostify_single_product_gallery_dependency() {
		// Theme options.
		$options = woostify_options( false );

		// Photoswipe markup html.
		if ( ! $options['shop_single_image_lightbox'] ) {
			return;
		}

		get_template_part( 'template-parts/content', 'photoswipe' );
	}
}

if ( ! function_exists( 'woostify_single_product_gallery_close' ) ) {
	/**
	 * Single product gallery close
	 */
	function woostify_single_product_gallery_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'woostify_single_product_container_close' ) ) {
	/**
	 * Product container close.
	 */
	function woostify_single_product_container_close() {
		?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_single_product_after_summary_open' ) ) {
	/**
	 * Container after summary open
	 */
	function woostify_single_product_after_summary_open() {
		$container = woostify_site_container();
		echo '<div class="' . esc_attr( $container ) . '">';
	}
}

if ( ! function_exists( 'woostify_single_product_after_summary_close' ) ) {
	/**
	 * Container after summary close
	 */
	function woostify_single_product_after_summary_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'woostify_single_product_wrapper_summary_open' ) ) {
	/**
	 * Wrapper product summary open
	 */
	function woostify_single_product_wrapper_summary_open() {
		?>
		<div class="product-summary">
		<?php
	}
}

if ( ! function_exists( 'woostify_single_product_wrapper_summary_close' ) ) {
	/**
	 * Wrapper product summary close
	 */
	function woostify_single_product_wrapper_summary_close() {
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_modified_quantity_stock' ) ) {
	/**
	 * Modify stock label
	 *
	 * @param string $html    Default html markup.
	 * @param object $product The product.
	 */
	function woostify_modified_quantity_stock( $html, $product ) {
		$options = woostify_options( false );
		// Remove quantity stock label if this option disabled.
		$limit = $options['shop_single_stock_product_limit'];

		$stock_quantity = $product->get_stock_quantity();

		// Only for simple product, variable work with javascript.
		if ( $stock_quantity < 1 || $product->is_type( 'variable' ) ) {
			return $html;
		}

		$number = $stock_quantity <= 10 ? $stock_quantity : wp_rand( 10, 75 );
		ob_start();
		if ( $limit >= $number || ! $limit ) {
			?>
				<div class="woostify-single-product-stock stock">

					<?php
					if ( $options['shop_single_stock_label'] ) {
						?>
							<span class="woostify-single-product-stock-label">
								<?php echo esc_html( sprintf( /* translators: %s stock quantity */ apply_filters( 'woostify_stock_message', __( 'Hurry! only %s left in stock.', 'woostify' ) ), $stock_quantity ) ); ?>
							</span>
						<?php
					}

					if ( $options['shop_single_loading_bar'] ) {
						?>
							<div class="woostify-product-stock-progress">
								<span class="woostify-single-product-stock-progress-bar" data-number="<?php echo esc_attr( $number ); ?>"></span>
							</div>
						<?php
					}
					?>

				</div>
			<?php
		}
		return ob_get_clean();
	}
}

if ( ! function_exists( 'woostify_trust_badge_image' ) ) {
	/**
	 * Trust badge image
	 */
	function woostify_trust_badge_image() {
		$options   = woostify_options( false );
		$image_url = $options['shop_single_trust_badge_image'];

		if ( ! $image_url ) {
			return;
		}
		?>
		<div class="woostify-trust-badge-box">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Trust Badge Image', 'woostify' ); ?>">
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_product_recently_viewed' ) ) {
	/**
	 * Product recently viewed
	 */
	function woostify_product_recently_viewed() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		global $post;
		$options         = woostify_options( false );
		$viewed_products = array();

		if ( ! empty( $_COOKIE['woostify_product_recently_viewed'] ) ) {
			$viewed_products = (array) explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['woostify_product_recently_viewed'] ) ) );
		}

		if ( ! in_array( $post->ID, $viewed_products, true ) ) {
			$viewed_products[] = $post->ID;
		}

		if ( count( $viewed_products ) > $options['shop_single_recently_viewed_count'] ) {
			array_shift( $viewed_products );
		}

		// Store for session only.
		wc_setcookie( 'woostify_product_recently_viewed', implode( '|', array_filter( $viewed_products ) ) );
	}
}

if ( ! function_exists( 'woostify_product_recently_viewed_template' ) ) {
	/**
	 * Display product recently viewed
	 */
	function woostify_product_recently_viewed_template() {
		$options = woostify_options( false );
		$cookies = isset( $_COOKIE['woostify_product_recently_viewed'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['woostify_product_recently_viewed'] ) ) : false;
		if ( ! $cookies || ! $options['shop_single_product_recently_viewed'] || ! is_singular( 'product' ) || woostify_elementor_has_location( 'single' ) ) {
			return;
		}

		$ids       = explode( '|', $cookies );
		$container = woostify_site_container();
		$args      = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'post__in'       => $ids,
		);

		$products_query = new WP_Query( $args );
		if ( ! $products_query->have_posts() ) {
			return;
		}
		?>

		<div class="woostify-product-recently-viewed-section">
			<div class="<?php echo esc_attr( $container ); ?>">
				<div class="woostify-product-recently-viewed-inner">
					<h2 class="woostify-product-recently-viewed-title"><?php echo esc_html( $options['shop_single_recently_viewed_title'] ); ?></h2>
					<?php
					woocommerce_product_loop_start();

					while ( $products_query->have_posts() ) :
						$products_query->the_post();

						wc_get_template_part( 'content', 'product' );
					endwhile;

					wp_reset_postdata();

					woocommerce_product_loop_end();
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woostify_disable_variations_out_of_stock' ) ) {
	/**
	 * Disable Out of Stock Variations.
	 *
	 * @param boolean $is_active The active.
	 * @param object  $variation The variation.
	 */
	function woostify_disable_variations_out_of_stock( $is_active, $variation ) {
		if ( ! $variation->is_in_stock() ) {
			return false;
		}

		return $is_active;
	}
}

if ( ! function_exists( 'woostify_single_ajax_add_to_cart_status' ) ) {
	/**
	 * On/off single ajax add to cart
	 */
	function woostify_single_ajax_add_to_cart_status() {
		if ( ( defined( 'ELEMENTOR_PRO_VERSION' ) && 'yes' === get_option( 'elementor_use_mini_cart_template' ) ) || defined( 'XOO_WSC_PLUGIN_FILE' ) ) {
			return false;
		}

		$options = woostify_options( false );
		return $options['shop_single_ajax_add_to_cart'];
	}
}

if ( ! function_exists( 'woostify_get_term_setting' ) ) {
	/**
	 * Get term setting
	 *
	 * @param string  $name         The term setting name.
	 * @param mix     $compare      Compare value.
	 * @param boolean $return_value Return condition only.
	 */
	function woostify_get_term_setting( $name = 'cat_single_ajax_add_to_cart', $compare = 'disabled', $return_value = false ) {
		if ( ! is_singular( 'product' ) ) {
			return false;
		}

		$value       = false;
		$options     = woostify_options( false );
		$page_id     = woostify_get_page_id();
		$product_cat = get_the_terms( $page_id, 'product_cat' );
		$product_tag = get_the_terms( $page_id, 'product_tag' );

		if ( ! empty( $product_cat ) ) {
			foreach ( $product_cat as $cat ) {
				$single_ajax_cat = get_term_meta( $cat->term_id, $name, true );
				if ( $compare === $single_ajax_cat ) {
					$value = $return_value ? $single_ajax_cat : true;
					break;
				}
			}
		}

		if ( ! empty( $product_tag ) ) {
			foreach ( $product_tag as $tag ) {
				$single_ajax_tag = get_term_meta( $tag->term_id, $name, true );
				if ( $compare === $single_ajax_tag ) {
					$value = $return_value ? $single_ajax_tag : true;
					break;
				}
			}
		}

		return $value;
	}
}

if ( ! function_exists( 'woostify_add_to_cart_product_simple' ) ) {
	/**
	 * Add add-to-cart value id for simple product
	 */
	function woostify_add_to_cart_product_simple() {
		global $product;
		if ( ! $product->is_type( 'simple' ) ) {
			return;
		}
		?>
		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />
		<?php
	}
}

if ( ! function_exists( 'woostify_reset_variations_link' ) ) {
	/**
	 * Modify woocommerce reset variations link
	 *
	 * @param string $output Link output.
	 */
	function woostify_reset_variations_link( $output ) {
		return '<a class="reset_variations" href="#">' . Woostify_Icon::fetch_svg_icon( 'reload', false ) . esc_html__( 'Clear', 'woostify' ) . '</a>';
	}
}
