/**
 * Product images
 *
 * @package woostify
 */

/* global woostify_product_images_slider_options, woostify_variation_gallery, woostify_default_gallery */

'use strict';

// Carousel widget.
function renderSlider( selector, options ) {
	var element = document.querySelectorAll( selector );
	if ( ! element.length ) {
		return;
	}

	for ( var i = 0, j = element.length; i < j; i++ ) {
		if ( element[i].classList.contains( 'flickity-enabled' ) ) {
			continue;
		}

		var slider = new Flickity( options.container, options );
	}
}


// Create product images item.
function createImages( fullSrc, src, size ) {
	var item  = '<figure class="image-item ez-zoom" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">';
		item += '<a href=' + fullSrc + ' data-size=' + size + ' itemprop="contentUrl" data-elementor-open-lightbox="no">';
		item += '<img src=' + src + ' itemprop="thumbnail">';
		item += '</a>';
		item += '</figure>';

	return item;
}

// Create product thumbnails item.
function createThumbnails( src ) {
	var item  = '<div class="thumbnail-item">';
		item += '<img src="' + src + '">';
		item += '</div>';

	return item;
}

// Sticky summary for list layout.
function woostifyStickySummary() {
	if ( ! woostify_woocommerce_general.enabled_sticky_product_summary ) {
		return;
	}
	var gallery = document.querySelector( '.has-gallery-list-layout .product-gallery.has-product-thumbnails' ),
		summary = document.querySelector( '.has-gallery-list-layout .product-summary' );
	if ( ! gallery || ! summary || window.innerWidth < 992 ) {
		return;
	}

	if ( gallery.offsetHeight <= summary.offsetHeight ) {
		return;
	}

	var sidebarStickCmnKy = new WSYSticky(
		'.summary.entry-summary',
		{
			stickyContainer: '.product-page-container',
			marginTop: parseInt( woostify_woocommerce_general.sticky_top_space ),
			marginBottom: parseInt( woostify_woocommerce_general.sticky_bottom_space )
		}
	);

	// Update sticky when found variation.
	jQuery( 'form.variations_form' ).on(
		'found_variation',
		function() {
			sidebarStickCmnKy.update();
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function(){
		var gallery           = document.querySelector( '.product-gallery' ),
			productThumbnails = document.getElementById( 'product-thumbnail-images' ),
			noSliderLayout    = gallery ? ( gallery.classList.contains( 'column-style' ) || gallery.classList.contains( 'grid-style' ) ) : false;

		var prevBtn = document.createElement( "button" );
		var nextBtn = document.createElement( "button" );

		var mobileSlider;

		// Product images.
		var imageCarousel,
			options = woostify_product_images_slider_options.main;

		// Product thumbnails.
		var firstImage       = gallery ? gallery.querySelector( '.image-item img' ) : false,
			firstImageHeight = firstImage ? firstImage.offsetHeight : 0;

		var thumbCarousel,
			thumbOptions = woostify_product_images_slider_options.thumb;

		if (
			window.matchMedia( '( min-width: 768px )' ).matches &&
			gallery &&
			gallery.classList.contains( 'vertical-style' )
		) {
			thumbOptions.draggable = false;
		}

		if ( productThumbnails ) {
			options.on = {
				ready: function() {
					changeImageCarouselButtonIcon();
					if ( window.matchMedia( '( min-width: 768px )' ).matches && gallery && gallery.classList.contains( 'vertical-style' ) ) {
						calculateVerticalSliderHeight();
					}
				}
			}

			imageCarousel = new Flickity( options.container, options );

			calculateThumbnailTotalWidth();

			if ( gallery ) {
				if ( window.matchMedia( '( max-width: 767px )' ).matches ) {
					thumbCarousel = new Flickity( thumbOptions.container, thumbOptions );
				} else {
					if ( gallery.classList.contains( 'vertical-style' ) ) {
						verticalThumbnailSliderAction();
						addThumbButtons();
					} else {
						thumbCarousel = new Flickity( thumbOptions.container, thumbOptions );
					}
				}
			}
		}

		function calculateVerticalSliderHeight() {
			var currFirstImage                = gallery ? gallery.querySelector( '.image-item img' ) : false;
			var currFirstImageHeight          = currFirstImage ? currFirstImage.offsetHeight : 0;
			productThumbnails.style.maxHeight = currFirstImageHeight + 'px';
		}

		function calculateThumbnailTotalWidth() {
			if ( ! productThumbnails ) {
				return;
			}

			if ( gallery && ( gallery.classList.contains( 'horizontal-style' ) || window.matchMedia( '( max-width: 767px )' ).matches ) ) {
				var thumbEls   = productThumbnails.querySelectorAll( '.thumbnail-item' );
				var totalWidth = 0;

				if ( thumbEls.length ) {
					thumbEls.forEach(
						function( thumbEl ) {
							var thumbWidth = thumbEl.offsetWidth;
							thumbWidth    += parseInt( window.getComputedStyle( thumbEl ).getPropertyValue( 'margin-left' ) );
							thumbWidth    += parseInt( window.getComputedStyle( thumbEl ).getPropertyValue( 'margin-right' ) );
							totalWidth    += thumbWidth;
						}
					);
				}

				if ( totalWidth >= productThumbnails.offsetWidth ) {
					thumbOptions.groupCells = '60%';
					thumbOptions.wrapAround = true;
					if ( thumbCarousel && thumbCarousel.slider ) {
						thumbCarousel.destroy();
						thumbCarousel = new Flickity( thumbOptions.container, thumbOptions );
					}
				} else {
					thumbOptions.groupCells = '3';
					thumbOptions.wrapAround = false;
					if ( thumbCarousel && thumbCarousel.slider ) {
						thumbCarousel.destroy();
						thumbCarousel = new Flickity( thumbOptions.container, thumbOptions );
					}
				}
			}
		}

		function changeImageCarouselButtonIcon() {
			var imageNextBtn = document.querySelector( '.flickity-button.next' );
			var imagePrevBtn = document.querySelector( '.flickity-button.previous' );

			if ( imageNextBtn ) {
				imageNextBtn.innerHTML = woostify_product_images_slider_options.next_icon;
			}

			if ( imagePrevBtn ) {
				imagePrevBtn.innerHTML = woostify_product_images_slider_options.prev_icon;
			}
		}

		window.addEventListener(
			'resize',
			function() {
				if ( window.matchMedia( '( min-width: 768px )' ).matches && gallery && gallery.classList.contains( 'vertical-style' ) && productThumbnails ) {
					calculateVerticalSliderHeight();
					verticalThumbnailSliderAction();

					displayThumbButtons();
				}
				calculateThumbnailTotalWidth();
			}
		);

		function displayThumbButtons() {
			var thumbs           = productThumbnails.querySelectorAll( '.thumbnail-item' );
			var totalThumbHeight = 0;
			if ( thumbs.length ) {
				thumbs.forEach(
					function( thumb ) {
						var thumbHeight   = thumb.offsetHeight;
						thumbHeight      += parseInt( window.getComputedStyle( thumb ).getPropertyValue( 'margin-top' ) );
						thumbHeight      += parseInt( window.getComputedStyle( thumb ).getPropertyValue( 'margin-bottom' ) );
						totalThumbHeight += thumbHeight;
					}
				)
			}

			if ( totalThumbHeight > productThumbnails.offsetHeight ) {
				productThumbnails.classList.add( 'has-buttons' );
				nextBtn.style.display = 'block';
				prevBtn.style.display = 'block';
			} else {
				productThumbnails.classList.remove( 'has-buttons' );
				nextBtn.style.display = 'none';
				prevBtn.style.display = 'none';
			}
		}
		function addThumbButtons() {
			var productThumbnailsWrapper = productThumbnails.parentElement;
			prevBtn.classList.add( 'thumb-btn', 'thumb-prev-btn', 'prev' );
			prevBtn.innerHTML = woostify_product_images_slider_options.vertical_prev_icon;

			nextBtn.classList.add( 'thumb-btn', 'thumb-next-btn', 'next' );
			nextBtn.innerHTML = woostify_product_images_slider_options.vertical_next_icon;

			productThumbnailsWrapper.appendChild( prevBtn );
			productThumbnailsWrapper.appendChild( nextBtn );

			displayThumbButtons();

			var thumbButtons = document.querySelectorAll( '.thumb-btn' );
			if ( thumbButtons.length ) {
				thumbButtons.forEach(
					function( thumbBtn ) {
						thumbBtn.addEventListener(
							'click',
							function() {
								var currBtn = this;
								if ( currBtn.classList.contains( 'prev' ) ) {
									imageCarousel.previous();
								} else {
									imageCarousel.next();
								}
							}
						)
					}
				)
			}
		}

		// For Grid layout on mobile.
		function woostifyGalleryCarouselMobile() {
			var mobileGallery = document.querySelector( '.has-gallery-list-layout .product-gallery.has-product-thumbnails' );
			if ( ! mobileGallery || window.innerWidth > 991 ) {
				return;
			}

			options.on   = {
				ready: function() {
					changeImageCarouselButtonIcon();
				}
			}
			mobileSlider = new Flickity( '#product-images', options );
		}

		function verticalThumbnailSliderAction() {
			var thumbNav       = productThumbnails;
			var thumbNavImages = thumbNav.querySelectorAll( '.thumbnail-item' );

			thumbNavImages[0].classList.add( 'is-nav-selected' );
			thumbNavImages[0].classList.add( 'is-selected' );

			thumbNavImages.forEach(
				function( thumbNavImg, thumbIndex ) {
					thumbNavImg.addEventListener(
						'click',
						function() {
							imageCarousel.select( thumbIndex );
						}
					);
				}
			);

			var thumbImgHeight = 0 < imageCarousel.selectedIndex ? thumbNavImages[imageCarousel.selectedIndex].offsetHeight : thumbNavImages[0].offsetHeight;
			var thumbHeight    = thumbNav.offsetHeight;

			imageCarousel.on(
				'select',
				function() {
					thumbNav.querySelectorAll( '.thumbnail-item' ).forEach(
						function( thumb ) {
							thumb.classList.remove( 'is-nav-selected', 'is-selected' );
						}
					)

					var selected = 0 <= imageCarousel.selectedIndex ? thumbNavImages[ imageCarousel.selectedIndex ] : thumbNavImages[ 0 ];
					selected.classList.add( 'is-nav-selected', 'is-selected' );

					var scrollY = selected.offsetTop + thumbNav.scrollTop - ( thumbHeight + thumbImgHeight ) / 2;
					thumbNav.scrollTo(
						{
							top: scrollY,
							behavior: 'smooth',
						}
					);
				}
			);
		}

		// Reset carousel.
		function resetCarousel() {
			if ( imageCarousel && imageCarousel.slider ) {
				imageCarousel.select( 0 )
			}
			if ( mobileSlider && mobileSlider.slider ) {
				mobileSlider.select( 0 )
			}
		}

		// Update gallery.
		function updateGallery( data, reset, variationId ) {
			if ( ! data.length || document.documentElement.classList.contains( 'quick-view-open' ) ) {
				return;
			}

			// For Elementor Preview Mode.
			if ( ! gallery ) {
				gallery = document.querySelector( '.product-gallery' );
			}

			var images     = '',
				thumbnails = '';

			for ( var i = 0, j = data.length; i < j; i++ ) {
				if ( reset ) {
					// For reset variation.
					var size = data[i].full_src_w + 'x' + data[i].full_src_h;

					images     += createImages( data[i].full_src, data[i].src, size );
					thumbnails += createThumbnails( data[i].gallery_thumbnail_src );
				} else if ( variationId && variationId == data[i][0].variation_id ) {
					// Render new item for new Slider.
					if ( 1 >= ( data[i].length - 1 ) ) {
						thumbnails = '';
						for ( var x = 1, y = data[i].length; x < y; x++ ) {
							var size = data[i][x].full_src_w + 'x' + data[i][x].full_src_h;
							images  += createImages( data[i][x].full_src, data[i][x].src, size );
						}
					} else {
						for ( var x = 1, y = data[i].length; x < y; x++ ) {
							var size    = data[i][x].full_src_w + 'x' + data[i][x].full_src_h;
							images     += createImages( data[i][x].full_src, data[i][x].src, size );
							thumbnails += createThumbnails( data[i][x].gallery_thumbnail_src );
						}
					}
				}
			}

			if ( imageCarousel && imageCarousel.slider ) {
				imageCarousel.destroy();
			}

			if ( thumbCarousel && thumbCarousel.slider ) {
				thumbCarousel.destroy();
			}

			if ( mobileSlider && mobileSlider.slider ) {
				mobileSlider.destroy();
			}

			// Append new markup html.
			if ( images && document.querySelector( '.product-images' ) ) {
				document.querySelector( '.product-images' ).querySelector( '#product-images' ).innerHTML = images;
			}

			if ( document.querySelector( '.product-thumbnail-images' ) ) {
				if ( '' !== thumbnails ) {
					var productThumbnailsWrapper = document.querySelector( '.product-thumbnail-images' ).querySelector( '#product-thumbnail-images' );

					if ( ! productThumbnailsWrapper ) {
						productThumbnailsWrapper = document.createElement( 'div' );
						productThumbnailsWrapper.setAttribute( 'id', 'product-thumbnail-images' );
					}

					document.querySelector( '.product-thumbnail-images' ).appendChild( productThumbnailsWrapper ).innerHTML = thumbnails;

					if ( document.querySelector( '.product-gallery' ) ) {
						document.querySelector( '.product-gallery' ).classList.add( 'has-product-thumbnails' );
					}
				} else {
					document.querySelector( '.product-thumbnail-images' ).innerHTML = '';
				}
			}

			// Re-init slider.
			if ( ! noSliderLayout ) {
				productThumbnails = document.getElementById( 'product-thumbnail-images' );
				if ( productThumbnails ) {
					options.on = {
						ready: function() {
							changeImageCarouselButtonIcon();
							if ( window.matchMedia( '( min-width: 768px )' ).matches && gallery && gallery.classList.contains( 'vertical-style' ) ) {
								calculateVerticalSliderHeight();
							}
						}
					}

					imageCarousel = new Flickity( options.container, options );

					calculateThumbnailTotalWidth();

					if ( gallery ) {
						if ( window.matchMedia( '( max-width: 767px )' ).matches ) {
							thumbCarousel = new Flickity( thumbOptions.container, thumbOptions );
						} else {
							if ( gallery.classList.contains( 'vertical-style' ) ) {
								verticalThumbnailSliderAction();
								addThumbButtons();
							} else {
								thumbCarousel = new Flickity( thumbOptions.container, thumbOptions );
							}
						}
					}
				}
			} else {
				woostifyGalleryCarouselMobile();
			}

			// Hide thumbnail slider if only thumbnail item.
			var getThumbnailSlider = document.querySelectorAll( '.product-thumbnail-images .thumbnail-item' );
			if ( document.querySelector( '.product-thumbnail-images' ) ) {
				if ( getThumbnailSlider.length < 2 ) {
					document.querySelector( '.product-thumbnail-images' ).classList.add( 'has-single-thumbnail-image' );
				} else if ( document.querySelector( '.product-thumbnail-images' ) ) {
					document.querySelector( '.product-thumbnail-images' ).classList.remove( 'has-single-thumbnail-image' );
				}
			}

			// Re-init easyzoom.
			if ( 'function' === typeof( easyZoomHandle ) ) {
				easyZoomHandle();
			}

			// Re-init Photo Swipe.
			if ( 'function' === typeof( initPhotoSwipe ) ) {
				initPhotoSwipe( '#product-images' );
			}

			setTimeout(
				function() {
					window.dispatchEvent( new Event( 'resize' ) );
				},
				200
			);
		}

		// Carousel action.
		function carouselAction() {
			// Trigger variation.
			jQuery( 'form.variations_form' ).on(
				'found_variation',
				function( e, variation ) {
					resetCarousel();

					// Update slider height.
					setTimeout(
						function() {
							window.dispatchEvent( new Event( 'resize' ) );
						},
						200
					);

					if ( 'undefined' !== typeof( woostify_variation_gallery ) && woostify_variation_gallery.length ) {
						updateGallery( woostify_variation_gallery, false, variation.variation_id );
					}
				}
			);

			// Trigger reset.
			jQuery( '.reset_variations' ).on(
				'click',
				function(){
					if ( 'undefined' !== typeof( woostify_variation_gallery ) && woostify_variation_gallery.length ) {
						updateGallery( woostify_default_gallery, true );
					}

					resetCarousel();

					// Update slider height.
					setTimeout(
						function() {
							window.dispatchEvent( new Event( 'resize' ) );
						},
						200
					);

					if ( document.body.classList.contains( 'elementor-editor-active' ) || document.body.classList.contains( 'elementor-editor-preview' ) ) {
						if ( ! document.getElementById( 'product-thumbnail-images' ) ) {
							document.querySelector( '.product-gallery' ).classList.remove( 'has-product-thumbnails' );
						}
					}
				}
			);
		}
		carouselAction();

		// Grid and One column to carousel layout on mobile.
		woostifyGalleryCarouselMobile();

		// Load event.
		window.addEventListener(
			'load',
			function() {
				woostifyStickySummary();

				setTimeout(
					function() {
						window.dispatchEvent( new Event( 'resize' ) );
					},
					200
				);
			}
		);

		// For Elementor Preview Mode.
		if ( 'function' === typeof( onElementorLoaded ) ) {
			onElementorLoaded(
				function() {
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/global',
						function() {
							if ( document.getElementById( 'product-thumbnail-images' ) ) {
								renderSlider( options.container, options );
								renderSlider( thumbOptions.container, thumbOptions );
							}
							carouselAction();
						}
					);
				}
			);
		}
	}
);
