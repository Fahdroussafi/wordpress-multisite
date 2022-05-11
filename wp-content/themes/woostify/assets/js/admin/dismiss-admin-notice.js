/**
 * Dismiss admin notice
 *
 * @package woostify
 */

/*global ajaxurl, woostify_dismiss_admin_notice*/

'use strict';

// Dismiss admin notice.
var dismiss = function() {
	var notice = document.querySelectorAll( '.woostify-admin-notice' );
	if ( ! notice.length ) {
		return;
	}

	notice.forEach(
		function( element ) {
			var button = element.querySelector( '.notice-dismiss' ),
				slug   = element.getAttribute( 'data-notice' );

			if ( ! button || ! slug ) {
				return;
			}

			button.addEventListener(
				'click',
				function() {
					element.classList.add( 'updating' );

					// Request.
					var request = new Request(
						ajaxurl,
						{
							method: 'POST',
							body: 'action=dismiss_admin_notice&nonce=' + woostify_dismiss_admin_notice.nonce + '&notice=' + slug,
							credentials: 'same-origin',
							headers: new Headers(
								{
									'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
								}
							)
						}
					);

					// Fetch API.
					fetch( request )
						.then(
							function( res ) {
								if ( 200 !== res.status ) {
									console.log( 'Status Code: ' + res.status );
									throw res;
								}

								return res.json();
							}
						).then(
							function( json ) {
								if ( ! json.success ) {
									return;
								}

								element.remove();
							}
						).finally(
							function() {
								element.classList.remove( 'updating' );
							}
						);
				}
			);
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		dismiss();
	}
);
