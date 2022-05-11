/**
 * Sortable handle
 *
 * @package woostify
 */

'use strict';

wp.customize.controlConstructor['woostify-sortable'] = wp.customize.Control.extend(
	{
		ready: function() {
			var control = this;

			// Init sortable.
			control.initSortable();

			// Set checked state for dragging Sortable.
			control.updateState();

			// Set checked state for dragging Sortable.
			control.dragend();
		},

		/**
		 * Detect dragend event.
		 */
		dragend: function() {
			var control = this,
			selector    = document.querySelector( control.selector ),
			input       = selector ? selector.querySelector( '.woostify-sortable-control-value' ) : false;

			if ( ! input ) {
				return;
			}

			input.addEventListener(
				'click',
				function() {
					control.updateValue();
				}
			);
		},

		/**
		 * Init sortable
		 */
		initSortable: function() {
			var control = this,
			selector    = document.querySelector( control.selector ),
			list        = selector ? selector.querySelector( '.woostify-sortable-control-list' ) : false;

			if ( ! list ) {
				return;
			}

			var sortable = new Sortable( list );
		},

		/**
		 * Update checked state
		 */
		updateState: function() {
			var control = this,
			selector    = document.querySelector( control.selector ),
			input       = selector ? selector.querySelectorAll( '.woostify-sortable-control-list [type=checkbox]' ) : [];

			if ( ! input.length ) {
				return;
			}

			input.forEach(
				function( el ) {
					var parentInput = el.closest( '.woostify-sortable-list-item' ),
					label           = el.closest( '.sortable-item-icon-visibility' );

					el.addEventListener(
						'click',
						function() {
							if ( el.checked ) {
								el.setAttribute( 'checked', 'checked' );
								parentInput.classList.add( 'checked' );

								label.classList.remove( 'dashicons-hidden' );
								label.classList.add( 'dashicons-visibility' );
							} else {
								el.removeAttribute( 'checked' );
								parentInput.classList.remove( 'checked' );

								label.classList.add( 'dashicons-hidden' );
								label.classList.remove( 'dashicons-visibility' );
							}

							// Update sort list.
							control.updateValue();
						}
					);
				}
			);
		},

		/**
		 * Updates the sorting list
		 */
		updateValue: function() {
			var control = this,
			newValue    = [],
			selector    = document.querySelector( control.selector ),
			list        = selector ? selector.querySelectorAll( '.woostify-sortable-list-item' ) : [];

			if ( ! list.length ) {
				return;
			}

			list.forEach(
				function( element ) {
					if ( ! element.classList.contains( 'checked' ) ) {
						return;
					}

					newValue.push( element.getAttribute( 'data-value' ) );
				}
			);

			control.setting.set( newValue );
		}
	}
);
