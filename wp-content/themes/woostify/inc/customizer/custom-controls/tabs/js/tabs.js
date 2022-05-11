/**
 * Load media uploader on pages with our custom metabox
 *
 * @package woostify
 */

( function( $ ) {

	'use strict';

	wp.customize.bind(
		'ready',
		function () {
			function displayOption(el, tab) {
				el.find( 'li.customize-control' ).each(
					function() {
						var attr_tab = $( this ).attr( 'data-tab' );
						if ( typeof attr_tab !== 'undefined' && attr_tab !== false ) {
							if ( attr_tab === tab ) {
								$( this ).show();
							} else {
								$( this ).hide();
							}
						}
					}
				);
			}
			$( document ).on(
				'click',
				'.woostify-tab-button:not(.disabled-btn)',
				function() {
					var curr_tab  = $( this ),
					curr_tab_data = curr_tab.data( 'tab' ),
					pane_child    = curr_tab.closest( '.customize-pane-child' );

					curr_tab.parent().find( 'li' ).removeClass( 'active' );
					curr_tab.addClass( 'active' );

					pane_child.find( 'li.customize-control' ).each(
						function() {
							var attr_tab = $( this ).attr( 'data-tab' );
							if ( typeof attr_tab !== 'undefined' && attr_tab !== false ) {
								if ( attr_tab === curr_tab_data ) {
									$( this ).show();
								} else {
									$( this ).hide();
								}
							}
						}
					);
				}
			);

			$( document ).ready(
				function() {
					$( '.woostify-component-tabs' ).each(
						function() {
							var curr_comp_tab = $( this );
							curr_comp_tab.find( 'li.woostify-tab-button' )[0].click();
						}
					);
				}
			)
		}
	);
} )( jQuery );
