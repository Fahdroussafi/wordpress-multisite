/* global wpforms_admin */
/**
 * WPForms Forms Overview.
 *
 * @since 1.7.3
 */

'use strict';

var WPFormsForms = window.WPFormsForms || {};

WPFormsForms.Overview = WPFormsForms.Overview || ( function( document, window, $ ) {

	/**
	 * Public functions and properties.
	 *
	 * @since 1.7.3
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * Start the engine.
		 *
		 * @since 1.7.3
		 */
		init: function() {

			$( app.ready );
		},

		/**
		 * Document ready.
		 *
		 * @since 1.7.3
		 */
		ready: function() {

			app.events();
		},

		/**
		 * Register JS events.
		 *
		 * @since 1.7.3
		 */
		events: function() {

			$( document )
				.on( 'click', '#wpforms-overview .wp-list-table .delete a, #wpforms-overview .wp-list-table .duplicate a', app.confirmSingleAction )
				.on( 'click', '#wpforms-overview .button.delete-all', app.confirmSingleAction )
				.on( 'click', '#wpforms-overview .bulkactions #doaction', app.confirmBulkAction )
				.on( 'click', '#wpforms-overview-table #wpforms-reset-filter .reset', app.resetSearch );

		},

		/**
		 * Confirm forms deletion and duplications.
		 *
		 * @since 1.7.3
		 *
		 * @param {object} event Event object.
		 */
		confirmSingleAction: function( event ) {

			event.preventDefault();

			var $link = $( this ),
				url = $link.attr( 'href' ),
				msg = $link.hasClass( 'delete-all' ) ?  wpforms_admin.form_delete_all_confirm : '';

			if ( msg === '' ) {
				msg = $link.parent().hasClass( 'delete' ) ? wpforms_admin.form_delete_confirm : wpforms_admin.form_duplicate_confirm;
			}

			app.confirmModal( msg, { 'url': url } );
		},

		/**
		 * Confirm forms bulk deletion.
		 *
		 * @since 1.7.3
		 *
		 * @param {object} event Event object.
		 */
		confirmBulkAction: function( event ) {

			var $button = $( this ),
				$form = $button.closest( 'form' ),
				action = $form.find( '#bulk-action-selector-top' ).val();

			if ( action !== 'delete' ) {
				return;
			}

			event.preventDefault();

			app.confirmModal( wpforms_admin.form_delete_n_confirm, { 'bulk': true, 'form': $form } );
		},

		/**
		 * Open confirmation modal.
		 *
		 * @since 1.7.3
		 *
		 * @param {string} msg  Confirmation modal content.
		 * @param {object} args Additional arguments
		 */
		confirmModal: function( msg, args ) {

			$.confirm( {
				title: wpforms_admin.heads_up,
				content: msg,
				icon: 'fa fa-exclamation-circle',
				type: 'orange',
				buttons: {
					confirm: {
						text: wpforms_admin.ok,
						btnClass: 'btn-confirm',
						keys: [ 'enter' ],
						action: function() {

							if ( args.url ) {
								window.location = args.url;

								return;
							}

							if ( args.bulk ) {
								args.form.submit();
							}
						},
					},
					cancel: {
						text: wpforms_admin.cancel,
						keys: [ 'esc' ],
					},
				},
			} );
		},

		/**
		 * Reset search form.
		 *
		 * @since 1.7.3
		 *
		 * @param {object} event Event object.
		 */
		resetSearch: function( event ) {

			// Reset search term.
			$( '#wpforms-overview-search-term' ).val( '' );

			// Submit the form.
			$( this ).closest( 'form' ).submit();
		},
	};

	// Provide access to public functions/properties.
	return app;

}( document, window, jQuery ) );

WPFormsForms.Overview.init();
