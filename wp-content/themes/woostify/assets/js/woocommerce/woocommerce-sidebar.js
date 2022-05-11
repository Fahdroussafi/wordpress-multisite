/**
 * Woocommerce sidebar
 *
 * @package woostify
 */

'use strict';

// Woocommerce sidebar on mobile.
function woostifySidebarMobile() {
	var sidebar = document.querySelector( '#secondary.shop-widget' ),
		button  = document.querySelector( '#toggle-sidebar-mobile-button' ),
		overlay = document.getElementById( 'woostify-overlay' ),
		html    = document.documentElement;

	if ( ! sidebar || ! button ) {
		return;
	}

	button.classList.add( 'show' );

	button.onclick = function() {
		sidebar.classList.add( 'active' );
		button.classList.add( 'active' );
		html.classList.add( 'sidebar-mobile-open' );
		if ( overlay ) {
			overlay.classList.add( 'active' );
		}
	}

	if ( overlay ) {
		overlay.onclick = function() {
			sidebar.classList.remove( 'active' );
			overlay.classList.remove( 'active' );
			button.classList.remove( 'active' );
			html.classList.remove( 'sidebar-mobile-open' );
		}
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifySidebarMobile();
	}
);
