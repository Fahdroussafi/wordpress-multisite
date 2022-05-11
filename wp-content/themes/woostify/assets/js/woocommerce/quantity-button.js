/**
 * Quantity button
 *
 * @package woostify
 */

'use strict';

// Create Minus button.
var minusBtn = function() {
	var minusBtn = document.createElement( 'span' );
	var icon     = get_svg_icon( 'minus' );

	minusBtn.setAttribute( 'class', 'product-qty' );
	minusBtn.setAttribute( 'data-qty', 'minus' );
	minusBtn.innerHTML = icon;

	return minusBtn;
}

// Create Plus button.
var plusBtn = function() {
	var plusBtn = document.createElement( 'span' );
	var icon    = get_svg_icon( 'plus' );

	plusBtn.setAttribute( 'class', 'product-qty' );
	plusBtn.setAttribute( 'data-qty', 'plus' );
	plusBtn.innerHTML = icon;

	return plusBtn;
}

// Add Minus and Plus button on Product Quantity.
function customQuantity() {
	var quantity = document.querySelectorAll( '.quantity' );
	if ( ! quantity.length ) {
		return;
	}

	// Foreach.
	quantity.forEach(
		function( ele ) {
			// Input.
			var input = ele.querySelector( 'input.qty' );
			if ( ! input ) {
				return;
			}

			// Add class ajax-ready on first load.
			input.classList.add( 'ajax-ready' );

			// Append Minus button before Input.
			if ( ! ele.querySelector( '.product-qty[data-qty="minus"]' ) ) {
				ele.insertBefore( minusBtn(), input );
			}

			// Append Plus button after Input.
			if ( ! ele.querySelector( '.product-qty[data-qty="plus"]' ) ) {
				ele.appendChild( plusBtn() );
			}

			// Vars.
			var cart         = ele.closest( 'form.cart' ),
				buttons      = ele.querySelectorAll( '.product-qty' ),
				maxInput     = Number( input.getAttribute( 'max' ) || -1 ),
				currInputVal = input.value,
				eventChange  = new Event( 'change', { bubbles: true } );

			// Check valid quantity.
			input.addEventListener(
				'change',
				function() {
					var inputVal  = input.value,
						min       = Number( input.getAttribute( 'min' ) || 0 ),
						ajaxReady = function() {
							input.classList.remove( 'ajax-ready' );
						};

					if ( Number( inputVal ) < min || isNaN( inputVal ) || ( maxInput > 0 && ( Number( inputVal ) > maxInput ) ) ) {
						alert( woostify_woocommerce_general.qty_warning );
						input.value = currInputVal
						return;
					}

					// When quantity updated.
					input.classList.add( 'ajax-ready' );

					var loopWrapper = input.closest( '.product-loop-wrapper' );
					if ( loopWrapper ) {
						var ajaxAddToCartBtn = loopWrapper.querySelector( '.add_to_cart_button' );
						if ( ajaxAddToCartBtn ) {
							ajaxAddToCartBtn.setAttribute( 'data-quantity', inputVal );
						}
					}
				}
			);

			// Minus & Plus button click.
			for ( var i = 0, j = buttons.length; i < j; i++ ) {
				buttons[i].onclick = function() {
					// Variables.
					var t        = this,
						current  = Number( input.value || 0 ),
						step     = Number( input.getAttribute( 'step' ) || 1 ),
						min      = Number( input.getAttribute( 'min' ) || 0 ),
						max      = Number( input.getAttribute( 'max' ) ),
						dataType = t.getAttribute( 'data-qty' );

					if ( 'minus' === dataType && current >= step ) { // Minus button.
						if ( current <= min || ( current - step ) < min ) {
							return;
						}

						input.value = Number( ( current - step ).toFixed( step.countDecimals() ) );
					} else if ( 'plus' === dataType ) { // Plus button.
						if ( max && ( current >= max || ( current + step ) > max ) ) {
							return;
						}

						input.value = Number( ( current + step ).toFixed( step.countDecimals() ) );
					}

					// Trigger event.
					input.dispatchEvent( eventChange );

					// Remove disable attribute on Update Cart button on Cart page.
					var updateCart = document.querySelector( '[name=\'update_cart\']' );
					if ( updateCart ) {
						updateCart.disabled = false;
					}
				}
			}
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		// For preview mode.
		if ( 'function' === typeof( onElementorLoaded ) ) {
			onElementorLoaded(
				function() {
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/woostify-product-add-to-cart.default',
						function() {
							customQuantity();
						}
					);
				}
			);
		}

		// For frontend mode.
		customQuantity();
	}
);
