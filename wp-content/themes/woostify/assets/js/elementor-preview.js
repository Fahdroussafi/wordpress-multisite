/**
 * Elementor preview
 *
 * @package Woostify Pro
 */

'use strict';

// Elementor not print a 'product' class for product item. We add this. Please fix it.
var pleaseFixIt = function() {
	var products = document.querySelectorAll( '.products > li' );
	if ( ! products.length ) {
		return;
	}

	products.forEach(
		function( el ) {
			el.classList.add( 'product' );
		}
	);
}

// DOM loaded.
document.addEventListener(
	'DOMContentLoaded',
	function() {
		// Only for Preview mode.
		if ( 'function' === typeof( onElementorLoaded ) ) {
			onElementorLoaded(
				function() {
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/global',
						function() {
							pleaseFixIt();
						}
					);
				}
			);
		}
	}
);
