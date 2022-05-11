/**
 * Photo Swipe on Product Images
 *
 * @package woostify
 */

'use strict';

function initPhotoSwipe( gallerySelector ) {
	var added = false;

	// parse slide data (url, title, size ...) from DOM elements
	// (children of gallerySelector).
	var parseThumbnailElements = function( el ) {
		var thumbElements = el.childNodes,
			numNodes      = thumbElements.length,
			items         = [],
			figureEl,
			linkEl,
			size,
			item;

		for ( var i = 0; i < numNodes; i++ ) {

			figureEl = thumbElements[ i ]; // <figure> element.

			// include only element nodes.
			if ( 1 !== figureEl.nodeType ) {
				continue;
			}

			linkEl = figureEl.children[ 0 ]; // <a> element.
			if ( ! linkEl.getAttribute( 'href' ) ) {
				continue;
			}

			size = linkEl.getAttribute( 'data-size' ).split( 'x' );

			// create slide object.
			item = {
				src: linkEl.getAttribute( 'href' ),
				w: parseInt( size[ 0 ], 10 ),
				h: parseInt( size[ 1 ], 10 )
			};

			if ( linkEl.children.length > 0 ) {
				// <img> thumbnail element, retrieving thumbnail url.
				item.msrc = linkEl.children[ 0 ].getAttribute( 'src' );
			}

			item.el = figureEl; // save link to element for getThumbBoundsFn.
			items.push( item );
		}

		return items;
	};

	// find nearest parent element.
	var closest = function closest( el, fn ) {
		return el && ( fn( el ) ? el : closest( el.parentNode, fn ) );
	};

	var onToggleButtonClick = function( e ) {
		e = e || window.event;
		e.preventDefault ? e.preventDefault() : e.returnValue = false;

		var eTarget = e.target || e.srcElement;

		var productImages = eTarget.closest( '.product-images' );

		var clickedListItem = productImages.querySelectorAll( '.image-item' )[0];

		// find root element of slide.
		var slider = productImages.querySelector( '.flickity-slider' );
		if ( slider ) {
			clickedListItem = productImages.querySelector( '.image-item.is-selected' );
		}

		if ( ! clickedListItem ) {
			return;
		}

		// find index of clicked item by looping through all child nodes
		// alternatively, you may define index via data- attribute.
		var clickedGallery = clickedListItem.parentNode,
			childNodes     = clickedListItem.parentNode.childNodes,
			numChildNodes  = childNodes.length,
			nodeIndex      = 0,
			index;

		for ( var i = 0; i < numChildNodes; i++ ) {
			if ( childNodes[ i ].nodeType !== 1 ) {
				continue;
			}

			if ( childNodes[ i ] === clickedListItem ) {
				index = nodeIndex;
				break;
			}
			nodeIndex++;
		}

		if ( index >= 0 ) {
			// open PhotoSwipe if valid index found.
			openPhotoSwipe( index, clickedGallery );
		}
		return false;
	}

	// triggers when user clicks on thumbnail.
	var onThumbnailsClick = function( e ) {
		e = e || window.event;
		e.preventDefault ? e.preventDefault() : e.returnValue = false;

		var eTarget = e.target || e.srcElement;

		if ( 'A' === eTarget.tagName.toUpperCase() ) {
			return;
		}

		// find root element of slide.
		var clickedListItem = closest(
			eTarget,
			function( el ) {
				return ( el.tagName && 'FIGURE' === el.tagName.toUpperCase() );
			}
		);

		if ( ! clickedListItem ) {
			return;
		}

		// find index of clicked item by looping through all child nodes
		// alternatively, you may define index via data- attribute.
		var clickedGallery = clickedListItem.parentNode,
			childNodes     = clickedListItem.parentNode.childNodes,
			numChildNodes  = childNodes.length,
			nodeIndex      = 0,
			index;

		for ( var i = 0; i < numChildNodes; i++ ) {
			if ( childNodes[ i ].nodeType !== 1 ) {
				continue;
			}

			if ( childNodes[ i ] === clickedListItem ) {
				index = nodeIndex;
				break;
			}
			nodeIndex++;
		}

		if ( index >= 0 ) {
			// open PhotoSwipe if valid index found.
			openPhotoSwipe( index, clickedGallery );
		}
		return false;
	};

	// parse picture index and gallery index from URL (#&pid=1&gid=2).
	var photoswipeParseHash = function() {
		var hash   = window.location.hash.substring( 1 ),
			params = {};

		if ( hash.length < 5 ) {
			return params;
		}

		var vars = hash.split( '&' );
		for ( var i = 0, ij = vars.length; i < ij; i++ ) {
			if ( ! vars[ i ] ) {
				continue;
			}
			var pair = vars[ i ].split( '=' );
			if ( pair.length < 2 ) {
				continue;
			}
			params[ pair[ 0 ] ] = pair[ 1 ];
		}

		if ( params.gid ) {
			params.gid = parseInt( params.gid, 10 );
		}

		return params;
	};

	var openPhotoSwipe = function( index, galleryElement, disableAnimation, fromURL ) {
		var pswpElement = document.querySelector( '.pswp' ),
			gallery,
			options,
			items;

		items = parseThumbnailElements( galleryElement );

		// define options (if needed).
		options = {

			// define gallery index (for URL).
			galleryUID: galleryElement.getAttribute( 'data-pswp-uid' ),

			getThumbBoundsFn: function( index ) {
				// See Options -> getThumbBoundsFn section of documentation for more info.
				var thumbnail   = items[ index ].el.getElementsByTagName( 'img' )[ 0 ], // find thumbnail.
					pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
					rect        = thumbnail.getBoundingClientRect();

				return {
					x: rect.left,
					y: rect.top + pageYScroll,
					w: rect.width
				};
			}

		};

		// PhotoSwipe opened from URL.
		if ( fromURL ) {
			if ( options.galleryPIDs ) {
				// parse real index when custom PIDs are used
				// http://photoswipe.com/documentation/faq.html#custom-pid-in-url.
				for ( var j = 0, ji = items.length; j < ji; j++ ) {
					if ( items[ j ].pid == index ) {
						options.index = j;
						break;
					}
				}
			} else {
				// in URL indexes start from 1.
				options.index = parseInt( index, 10 ) - 1;
			}
		} else {
			options.index = parseInt( index, 10 );
		}

		// exit if index not found.
		if ( isNaN( options.index ) ) {
			return;
		}

		if ( disableAnimation ) {
			options.showAnimationDuration = 0;
		}

		// Pass data to PhotoSwipe and initialize it.
		gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );
		gallery.init();

		var selector = '.pswp__thumbnails';

		gallery.listen(
			'gettingData',
			function() {
				if ( added ) {
					return;
				}
				added = true;

				var oldThumbnailEls = document.querySelectorAll( selector );
				if ( oldThumbnailEls.length ) {
					oldThumbnailEls.forEach(
						function( odlThumb ) {
							odlThumb.remove();
						}
					)
				}
				setTimeout(
					function() {
						addPreviews( gallery );
					},
					200
				);
			}
		)

		gallery.listen(
			'close' ,
			function() {
				var scrollWrap  = gallery.scrollWrap;
				var pswpThumbEl = scrollWrap.closest( '.pswp' ).querySelector( '.pswp__thumbnails' );

				if ( ! pswpThumbEl ) {
					return;
				}

				pswpThumbEl.remove();
				added = false;
			}
		)
		gallery.listen(
			'afterChange',
			function() {
				var scrollWrap  = gallery.scrollWrap;
				var pswpThumbEl = scrollWrap.closest( '.pswp' ).querySelector( '.pswp__thumbnails' );

				if ( ! pswpThumbEl ) {
					return;
				}

				Object.keys( gallery.items ).forEach(
					function( k ) {
						var currThumbItem = pswpThumbEl.children[k];

						currThumbItem.classList.remove( 'active' );

						if ( gallery.getCurrentIndex() == k ) {
							currThumbItem.classList.add( 'active' );
						}
					}
				)
			}
		)
	};

	function addPreviews( gallery ) {
		var scrollWrap             = gallery.scrollWrap;
		var productImagesWrapperEl = document.querySelector( '.product-gallery' );
		var thumbnailEl            = document.createElement( 'div' );
		thumbnailEl.classList.add( 'pswp__thumbnails' );

		if ( ! productImagesWrapperEl ) {
			return;
		}

		var productThumbWrapperEl = productImagesWrapperEl.querySelector( '#product-thumbnail-images' );

		if ( ! productThumbWrapperEl ) {
			Object.keys( gallery.items ).forEach(
				function( k ) {
					var currItem   = gallery.items[k];
					var newThumbEl = document.createElement( 'div' );
					var newImgEl   = document.createElement( 'img' );

					newImgEl.setAttribute( 'src', currItem.msrc );
					newThumbEl.classList.add( 'thumbnail-item' );
					newThumbEl.appendChild( newImgEl )
					thumbnailEl.appendChild( newThumbEl );
				}
			)
		} else {
			var thumbSlider = productThumbWrapperEl.querySelector( '.flickity-slider' );
			if ( thumbSlider ) {
				thumbnailEl.innerHTML = thumbSlider.innerHTML;
			} else {
				thumbnailEl.innerHTML = productThumbWrapperEl.innerHTML;
			}
		}

		Object.keys( gallery.items ).forEach(
			function( k ) {
				var currThumbItem = thumbnailEl.children[k];
				currThumbItem.removeAttribute( 'style' );
				currThumbItem.classList.remove( 'is-selected', 'is-nav-selected' );

				if ( gallery.getCurrentIndex() == k ) {
					currThumbItem.classList.add( 'active' );
				}

				currThumbItem.addEventListener(
					'click',
					function() {
						gallery.goTo( gallery.items.indexOf( gallery.items[k] ) )
					}
				)
			}
		)

		scrollWrap.parentNode.insertBefore( thumbnailEl, scrollWrap.nextSibling );
	}

	// loop through all gallery elements and bind events.
	var galleryElements = document.querySelectorAll( gallerySelector );
	for ( var i = 0, l = galleryElements.length; i < l; i++ ) {
		var buttonEl = galleryElements[ i ].closest( '.product-images' ).querySelector( '.photoswipe-toggle-button' );

		galleryElements[ i ].setAttribute( 'data-pswp-uid', i + 1 );

		buttonEl.onclick = onToggleButtonClick;
		galleryElements[ i ].onclick = onThumbnailsClick;
	}

	// Parse URL and open gallery if it contains #&pid=3&gid=1.
	var hashData = photoswipeParseHash();
	if ( hashData.pid && hashData.gid ) {
		openPhotoSwipe( hashData.pid, galleryElements[ hashData.gid - 1 ], true, true );
	}
}

initPhotoSwipe( '#product-images' );

