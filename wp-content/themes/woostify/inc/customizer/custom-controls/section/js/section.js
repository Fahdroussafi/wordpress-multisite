/**
 * Section
 *
 * @package woostify
 */

'use strict';

wp.customize.controlConstructor['woostify-section'] = wp.customize.Control.extend(
	{
		/**
		 * Ready
		 */
		ready: function() {
			var control = this,
			selector    = document.querySelector( control.selector ),
			state       = 1;

			if ( ! selector ) {
				return;
			}

			// Trigger.
			wp.customize.bind(
				'ready',
				function() {
					control.dependencies( state );
				}
			);

			// Arrow event.
			selector.addEventListener(
				'click',
				function() {
					if ( 1 === state ) {
						selector.classList.add( 'active' );
						state = 2;
					} else {
						selector.classList.remove( 'active' );
						state = 1;
					}

					control.dependencies( state );
				}
			);
		},

		/**
		 * Dependency
		 */
		dependencies: function( state ) {
			var control = this,
			dependency  = control.params.dependency;

			if ( ! dependency.length ) {
				return;
			}

			for ( var i = 0, j = dependency.length; i < j; i ++ ) {
				var depen     = wp.customize.control( dependency[i] ),
				depenSelector = depen ? document.querySelector( depen.selector ) : false;

				if ( ! depenSelector ) {
					continue;
				}

				if ( 1 === state ) {
					depenSelector.classList.add( 'woostify-section-hide' );
				} else {
					depenSelector.classList.remove( 'woostify-section-hide' );
				}
			}
		}
	}
);
