/**
 * Install Demo
 *
 * @package woostify
 */

/* global woostify_install_demo */

'use strict';

// Activate plugin.
var activatePlugin = function( url, redirect ) {
	if ( 'undefined' === typeof( url ) || ! url ) {
		return;
	}

	var request = new Request(
		url,
		{
			method: 'GET',
			credentials: 'same-origin',
			headers: new Headers(
				{
					'Content-Type': 'text/xml'
				}
			)
		}
	);

	fetch( request )
		.then(
			function( data ) {
				location.reload();
			}
		).catch(
			function( error ) {
				console.log( error );
			}
		);
}

// Download and Install plugin.
var installPlugin = function() {
	var installBtn = document.querySelector( '.woostify-install-demo' );
	if ( ! installBtn ) {
		return;
	}

	installBtn.onclick = function( e ) {
		e.preventDefault();

		var t        = this,
			url      = t.getAttribute( 'href' ),
			slug     = t.getAttribute( 'data-slug' ),
			redirect = t.getAttribute( 'data-redirect' );

		t.innerHTML = woostify_install_demo.installing;

		t.classList.add( 'updating-message' );
		wp.updates.installPlugin(
			{
				slug: slug,
				success: function () {
					t.innerHTML = woostify_install_demo.activating + '...';
					activatePlugin( url, redirect );
				}
			}
		);
	}
}

// Activate plugin manual.
var handleActivate = function() {
	var activeButton = document.querySelector( '.woostify-active-now' );
	if ( ! activeButton ) {
		return;
	}

	activeButton.onclick = function( e ) {
		e.preventDefault();

		var t        = this,
			url      = t.getAttribute( 'href' ),
			redirect = t.getAttribute( 'data-redirect' );

		t.classList.add( 'updating-message' );
		t.innerHTML = woostify_install_demo.activating + '...';

		activatePlugin( url, redirect );
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		installPlugin();
		handleActivate();
	}
);
