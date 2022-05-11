/**
 * General JS
 *
 * @package Woostify
 */

// Get all Prev element siblings.
var prevSiblings = function( target ) {
	var siblings = [],
		n        = target;

	while ( n = n.previousElementSibling ) {
		siblings.push( n );
	}

	return siblings;
}

// Get all Next element siblings.
var nextSiblings = function( target ) {
	var siblings = [],
		n        = target;

	while ( n = n.nextElementSibling ) {
		siblings.push( n );
	}

	return siblings;
}

// Get all element siblings.
var siblings = function( target ) {
	var prev = prevSiblings( target ) || [],
		next = nextSiblings( target ) || [];

	return prev.concat( next );
}

// SECTION TAB SETTINGS.
var woostifyWelcomeTabSettings = function() {
	var section = document.querySelector( '.woostify-welcome-settings-section-tab' );
	if ( ! section ) {
		return;
	}

	var button = section.querySelectorAll( '.tab-head-button' );
	if ( ! button.length ) {
		return;
	}

	button.forEach(
		function( element ) {
			element.onclick = function() {
				var id          = element.hash ? element.hash.substr( 1 ) : '',
					idSiblings  = siblings( element ),
					tab         = section.querySelector( '.woostify-setting-tab-content[data-tab="' + id + '"]' ),
					tabSiblings = siblings( tab );

				// Active current tab heading.
				element.classList.add( 'active' );
				if ( idSiblings.length ) {
					idSiblings.forEach(
						function( el ) {
							el.classList.remove( 'active' );
						}
					);
				}

				// Active current tab content.
				tab.classList.add( 'active' );
				if ( tabSiblings.length ) {
					tabSiblings.forEach(
						function( el ) {
							el.classList.remove( 'active' );
						}
					);
				}
			}
		}
	);

	// Trigger first click. Active tab.
	window.addEventListener(
		'load',
		function() {
			var currentTab = section.querySelector( 'a[href="' + window.location.hash + '"]' ),
				generalTab = section.querySelector( 'a[href="#dashboard"]' );

			if ( currentTab ) {
				currentTab.click();
			} else if ( generalTab ) {
				generalTab.click();
			}
		}
	);
}

var woostifyMoveWordpressUpdateVersionNotice = function() {
	var notice = document.querySelector( '.update-nag' );
	if ( ! notice ) {
		return;
	}

	var notice_clone = notice.cloneNode( true );
	var notices_wrap = document.querySelector( '.woostify-notices-wrap' );

	if ( ! notices_wrap ) {
		return;
	}

	notice.remove();
	notices_wrap.prepend( notice_clone );
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyWelcomeTabSettings();
		woostifyMoveWordpressUpdateVersionNotice();
	}
);
