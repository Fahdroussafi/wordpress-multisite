/**
 * Sortable handle
 *
 * @package woostify
 */

'use strict';

wp.customize.controlConstructor['radio-image'] = wp.customize.Control.extend(
	{
		ready: function() {
			var control = this,
			selector    = document.querySelector( control.selector ),
			items       = selector ? selector.querySelectorAll( '.radio-image-item' ) : [];

			if ( ! items.length ) {
				return;
			}

			firstLoop:
			for ( var i = 0, j = items.length; i < j; i++ ) {
				var input = items[i].querySelector( '.image-select' );

				items[i].addEventListener(
					'click',
					function() {
						var t = this;
						if ( t.classList.contains( 'active' ) ) {
							return;
						}

						var removeActive = t.parentNode.querySelector( '.radio-image-item.active' );
						if ( removeActive ) {
							removeActive.classList.remove( 'active' );
							var inputActive = removeActive.querySelector( '.image-select' );
							inputActive.setAttribute( 'checked', false );
						}

						t.classList.add( 'active' );
					}
				);
			}
		}
	}
);
