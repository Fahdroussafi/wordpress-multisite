/**
 * Ajax single add to cart
 *
 * @package Woostify Pro
 */

'use strict';

function woostifyAjaxSingleHandleError( button ) {
	// Event when added to cart.
	if ( 'function' === typeof( eventCartSidebarClose ) ) {
		eventCartSidebarClose();
	}

	// Remove loading.
	button.classList.remove( 'loading' );

	// Hide quick view popup when product added to cart.
	document.documentElement.classList.remove( 'quick-view-open' );
}

function woostifyAjaxSingleUpdateFragments( button ) {
	if ( woostify_woocommerce_general.shipping_threshold.enabled_shipping_threshold && woostify_woocommerce_general.shipping_threshold.enabled_shipping_threshold_effect ) {
		var progress_bar = document.querySelectorAll( '.free-shipping-progress-bar' ),
		percent          = 0;
		if ( progress_bar.length ) {
			percent = parseInt( progress_bar[0].getAttribute( 'data-progress' ) );
		}
	}

	fetch(
		wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
		{
			method: 'POST'
		}
	).then(
		function( response ) {
			return response.json();
		}
	).then(
		function( fr ) {
			if ( 'undefined' === typeof( fr.fragments ) ) {
				return;
			}

			Object.entries( fr.fragments ).forEach(
				function( [key, value] ) {
					let newEl = document.querySelectorAll( key );
					if ( ! newEl.length ) {
						return;
					}

					newEl.forEach(
						function( el ) {
							el.insertAdjacentHTML( 'afterend', value );
							el.remove();
						}
					);
				}
			);
		}
	).finally(
		function() {
			// Handle.
			woostifyAjaxSingleHandleError( button );

			jQuery( document.body ).trigger( 'added_to_cart' );

			progressBarConfetti( progress_bar, percent );
		}
	);
}


function woostifyAjaxSingleAddToCartButton() {
	var buttons = document.querySelectorAll( '.single_add_to_cart_button' );
	if ( ! buttons.length ) {
		return;
	}

	buttons.forEach(
		function( ele ) {
			ele.onclick = function( e ) {
				var form = ele.closest( 'form.cart' );
				if ( ! form || 'POST' !== form.method.toUpperCase() || ele.classList.contains( 'disabled' ) ) {
					return;
				}

				e.preventDefault();
				let input = form.querySelector( 'input.qty' );

				if ( null == input ) {
					input = form.querySelector( 'input[name="quantity"]' );
				}

				let inputValue = input ? Number( input.value.trim() ) : false;

				if ( ! inputValue || isNaN( inputValue ) || inputValue <= 0 ) {
					alert( woostify_woocommerce_general.qty_warning );
					return;
				}

				var form_data = new FormData( form )
				form_data.append( 'add-to-cart', form.querySelector( '[name=add-to-cart]' ).value )
				form_data.append( 'ajax_nonce', woostify_woocommerce_general.ajax_nonce )

				// Add loading.
				ele.classList.add( 'loading' );

				// Events.
				if ( 'function' === typeof( eventCartSidebarOpen ) ) {
					eventCartSidebarOpen();
				}

				if ( 'function' === typeof( closeAll ) ) {
					closeAll();
				}

				// Add loading.
				document.documentElement.classList.add( 'mini-cart-updating' );

				fetch(
					wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'woostify_single_add_to_cart' ),
					{
						method: 'POST',
						body: form_data,
					}
				).then(
					function( res ) {
						if ( ! res ) {
							return;
						}

						var res_json = res.json();

						if ( res_json.error && res_json.product_url ) {
							window.location = res_json.product_url;
							return;
						}

						// Redirect to cart option.
						if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
							window.location = wc_add_to_cart_params.cart_url;
							return;
						}

						return res_json;
					}
				).then(
					function ( result ) {
						// Add loading.
						document.documentElement.classList.remove( 'mini-cart-updating' );

						/*
						// Remove old notices.
						document.querySelector( '.content-top .woocommerce' ).innerHTML = '';
						// Add new notices.
						document.querySelector( '.content-top .woocommerce' ).innerHTML = result.fragments.notices_html;
						*/

						// Update fragments.
						woostifyAjaxSingleUpdateFragments( ele );

						// Support Buy now addon.
						if ( ele.getAttribute( 'data-checkout_url' ) ) {
							window.location = ele.getAttribute( 'data-checkout_url' );
						}
					}
				).catch(
					function() {
						// Add loading.
						document.documentElement.classList.remove( 'mini-cart-updating' );
						// Handle.
						woostifyAjaxSingleHandleError( ele );
					}
				);
			}
		}
	)
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyAjaxSingleAddToCartButton();
	}
);
