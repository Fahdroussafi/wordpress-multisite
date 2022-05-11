/**
 * Product variation
 *
 * @package woostify
 */

/* global woostify_woocommerce_variable_product_data */

'use strict';

/**
 * Variation product
 *
 * @param      string selector  The selector.
 * @param      string form      The form.
 */
function productVariation( selector, form ) {
	var gallery        = document.querySelector( selector ),
		variationsForm = form ? form : 'form.variations_form';
	if ( ! gallery || ! jQuery( variationsForm ).length ) {
		return;
	}

	var imageWrapper = gallery.querySelector( '.image-item' );
	if ( imageWrapper == null ) {
		return;
	}
	var image       = imageWrapper ? imageWrapper.querySelector( 'img' ) : false,
		imageSrc    = image ? image.getAttribute( 'src' ) : '',
		imageSrcset = image ? image.getAttribute( 'srcset' ) : '',
		// Photoswipe + zoom.
		photoSwipe    = imageWrapper.querySelector( 'a' ),
		photoSwipeSrc = photoSwipe ? photoSwipe.getAttribute( 'href' ) : '',
		// Product thumbnail.
		thumb    = gallery.querySelector( '.thumbnail-item' ),
		thumbImg = thumb ? thumb.querySelector( 'img' ) : false,
		thumbSrc = thumbImg ? thumbImg.getAttribute( 'src' ) : '';

	if ( ! jQuery( variationsForm ).length ) {
		return;
	}

	// Support Product meta widget.
	var productMetaSku        = document.querySelector( '.elementor-widget-woostify-product-meta .sku' ),
		productMetaSkuDefault = productMetaSku ? productMetaSku.innerHTML : '',
		wpmGtinCodeWrapper    = document.querySelector( '.wpm_gtin_code_wrapper .wpm_pgw_code' );

	if ( wpmGtinCodeWrapper ) {
		wpmGtinCodeWrapper.innerHTML = productMetaSkuDefault;
	}

	jQuery( document.body ).on(
		'found_variation',
		variationsForm,
		function( event, variation ) {
			// get image url form `variation`.
			var imgSrc  = variation.image.src,
				fullSrc = variation.image.full_src,
				inStock = variation.is_in_stock;

			if ( ! imgSrc || ! fullSrc ) {
				return;
			}

			// Support Product meta widget.
			if ( productMetaSku ) {
				productMetaSku.innerHTML = variation.sku;
			}

			if ( wpmGtinCodeWrapper && variation.wpm_pgw_code ) {
				wpmGtinCodeWrapper.innerHTML = variation.wpm_pgw_code;
			}

			// Photoswipe + zoom.
			if ( photoSwipe ) {
				photoSwipe.setAttribute( 'href', fullSrc );
			}

			// Change image src image.
			if ( image && imgSrc ) {
				imageWrapper.classList.add( 'image-loading' );

				var img    = new Image();
				img.onload = function () {
					imageWrapper.classList.remove( 'image-loading' );
				}

				img.src = imgSrc;
				image.setAttribute( 'src', imgSrc );

				if ( imageSrcset ) {
					image.setAttribute( 'srcset', variation.image.srcset );
				}
			}

			// Change thumb src image.
			if ( thumbImg ) {
				thumbImg.setAttribute( 'src', variation.image.thumb_src );
			}

			// Re-init zoom handle.
			if ( 'function' === typeof( easyZoomHandle ) ) {
				easyZoomHandle();
			}

			var jsSelector    = document.querySelector( selector ),
				productImages = jsSelector ? jsSelector.querySelector( '.product-images' ) : false,
				outStockLabel = productImages ? productImages.querySelector( '.woostify-out-of-stock-label' ) : false,
				onSaleLabel   = productImages ? productImages.querySelector( '.woostify-tag-on-sale' ) : false;

			// In stock.
			if ( inStock ) {
				// Re-init stock progress bar.
				if ( variation.max_qty && 'function' === typeof( woostifyStockQuantityProgressBar ) ) {
					setTimeout(
						function() {
							woostifyStockQuantityProgressBar();
						},
						200
					);
				}

				// Remove label out of stock.
				if ( outStockLabel ) {
					outStockLabel.remove();
				}

				// Update sale tag.
				if ( onSaleLabel && woostify_woocommerce_variable_product_data.sale_tag_percent && variation.display_price != variation.display_regular_price ) {
					onSaleLabel.innerHTML = '-' + Math.round( ( ( variation.display_regular_price - variation.display_price ) / variation.display_regular_price ) * 100 ) + '%';
				}
			} else if ( 'undefined' !== typeof( woostify_woocommerce_variable_product_data ) ) {
				var outStockLabelHtml = '<span class="woostify-out-of-stock-label position-' + woostify_woocommerce_variable_product_data.out_of_stock_display + ' ' + woostify_woocommerce_variable_product_data.out_of_stock_square + '">' + woostify_woocommerce_variable_product_data.out_of_stock_text + '</span>';

				if ( ! outStockLabel ) {
					productImages.insertAdjacentHTML( 'beforeend', outStockLabelHtml );
				}
			}
		}
	);

	// Reset variation.
	jQuery( '.reset_variations' ).on(
		'click',
		function( e ) {
			e.preventDefault();

			// Support Product meta widget.
			if ( productMetaSkuDefault ) {
				productMetaSku.innerHTML = productMetaSkuDefault;
			}

			if ( wpmGtinCodeWrapper ) {
				wpmGtinCodeWrapper.innerHTML = productMetaSkuDefault;
			}

			// Reset src image.
			if ( image ) {
				imageWrapper.classList.add( 'image-loading' );

				var resetImg    = new Image();
				resetImg.onload = function () {
					imageWrapper.classList.remove( 'image-loading' );
				}

				resetImg.src = imageSrc;
				image.setAttribute( 'src', imageSrc );

				if ( imageSrcset ) {
					image.setAttribute( 'srcset', imageSrcset );
				}
			}

			if ( thumbSrc ) {
				thumbImg.setAttribute( 'src', thumbSrc );
			}

			// Photoswipe + zoom.
			if ( photoSwipeSrc ) {
				photoSwipe.setAttribute( 'href', photoSwipeSrc );
			}

			// Zoom handle.
			if ( 'function' === typeof( easyZoomHandle ) ) {
				easyZoomHandle();
			}
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		productVariation( '.product-gallery' );

		// For Elementor Preview Mode.
		if ( 'function' === typeof( onElementorLoaded ) ) {
			onElementorLoaded(
				function() {
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/global',
						function() {
							productVariation( '.product-gallery' );
						}
					);
				}
			);
		}
	}
);
