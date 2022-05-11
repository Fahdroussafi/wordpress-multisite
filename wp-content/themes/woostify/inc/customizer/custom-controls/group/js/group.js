/**
 * Group control JS
 *
 * @package woostify
 */

wp.customize.controlConstructor['woostify-group'] = wp.customize.Control.extend(
	{
		ready: function() {
			'use strict';

			var control       = this;
			var controlClass  = '.customize-control-woostify-group',
				footerActions = jQuery( '#customize-footer-actions' ),
				resetClicked  = false;

			jQuery( controlClass + ' .woostify-link-value-together-btn' ).unbind( 'click' );
			jQuery( controlClass + ' .woostify-link-value-together-btn' ).on(
				'click',
				function(e) {
					if (jQuery( this ).hasClass( 'dashicons-admin-links' )) {
						jQuery( this ).closest( '.woostify-group-fields-area' ).find( '.woostify-link-value-together-btn' ).removeClass( 'dashicons-admin-links linked' ).addClass( 'dashicons-editor-unlink unlinked' );
					} else {
						jQuery( this ).closest( '.woostify-group-fields-area' ).find( '.woostify-link-value-together-btn' ).removeClass( 'dashicons-editor-unlink unlinked' ).addClass( 'dashicons-admin-links linked' );
					}
				}
			)

			// Handle the reset button.
			jQuery( controlClass + ' .woostify-reset' ).on(
				'click',
				function() {
					var icon         = jQuery( this ),
						visible_area = icon.closest( '.woostify-responsive-title-area' ).next( '.woostify-group-fields-area' ).children( 'div:visible' ),
						input        = visible_area.find( '.woostify-group-input' );

					resetClicked = true;
					input.each(
						function() {
							var reset_value = jQuery( this ).data( 'reset_value' );
							jQuery( this ).val( reset_value ).trigger( 'change' );
						},
					);
					resetClicked = false;
				},
			);

			// Figure out which device icon to make active on load.
			jQuery( controlClass + ' .woostify-group-control' ).each(
				function() {
					var _this = jQuery( this );
					_this.find( '.woostify-responsive-devices-container' ).children( 'span:first-child' ).addClass( 'selected' );
					_this.find( '.woostify-group-container:first-child' ).css( 'display', 'flex' );
				},
			);

			// Do stuff when device icons are clicked.
			jQuery( controlClass + ' .woostify-responsive-devices-container > span' ).on(
				'click',
				function( event ) {
					var device = jQuery( this ).data( 'option' );

					jQuery( controlClass + ' .woostify-responsive-devices-container span' ).each(
						function() {
							var _this = jQuery( this );
							if ( device === _this.attr( 'data-option' ) ) {
								_this.addClass( 'selected' );
								_this.siblings().removeClass( 'selected' );
							}
						},
					);

					jQuery( controlClass + ' .woostify-group-fields-area > div' ).each(
						function() {
							var _this = jQuery( this );
							if ( device === _this.attr( 'data-option' ) ) {
								_this.css( 'display', 'flex' );
								_this.siblings().hide();
							}
						},
					);

					// Set the device we're currently viewing.
					wp.customize.previewedDevice.set( jQuery( event.currentTarget ).data( 'option' ) );
				},
			);

			// Set the selected devices in our control when the Customizer devices are clicked.
			footerActions.find( '.devices button' ).on(
				'click',
				function() {
					var device = jQuery( this ).data( 'device' );

					jQuery( controlClass + ' .woostify-responsive-devices-container span' ).each(
						function() {
							var _this = jQuery( this );
							if ( device === _this.attr( 'data-option' ) ) {
								_this.addClass( 'selected' );
								_this.siblings().removeClass( 'selected' );
							}
						},
					);

					jQuery( controlClass + ' .woostify-group-fields-area > div' ).each(
						function() {
							var _this = jQuery( this );
							if ( device === _this.attr( 'data-option' ) ) {
								_this.css( 'display', 'flex' );
								_this.siblings().hide();
							}
						},
					);
				},
			);

			// Apply changes when value is changed.
			control.container.on(
				'input change',
				'.woostify-group-input',
				function() {
					var field_container   = jQuery( this ).closest( '.woostify-group-fields-area' ).children( 'div:visible' );
					var curr_device       = field_container.data( 'option' );
					var value             = '';
					var negative_value    = control.params.negative_value;
					var linked_values_btn = jQuery( this ).closest( '.woostify-group-container' ).find( '.woostify-link-value-together-btn' );

					if ( linked_values_btn.hasClass( 'linked' ) && false === resetClicked) {
						field_container.find( '.woostify-group-field input' ).val( jQuery( this ).val() )
					}
					field_container.find( '.woostify-group-field' ).each(
						function() {
							var input_val        = '' !== jQuery( this ).find( 'input.woostify-group-input' ).val() ? jQuery( this ).find( 'input.woostify-group-input' ).val() : 0;
							var input_val_format = ! negative_value ? Math.abs( input_val ) : input_val;
							jQuery( this ).find( 'input.woostify-group-input' ).val( input_val_format );
							value += input_val_format + ' ';
						},
					);
					control.settings[curr_device].set( value.trim() );
				},
			);
		},
	},
);
