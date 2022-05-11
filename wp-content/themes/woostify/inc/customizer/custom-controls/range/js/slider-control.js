/**
 * Slider control
 *
 * @package woostify
 */

wp.customize.controlConstructor['woostify-range-slider'] = wp.customize.Control.extend(
	{

		ready: function() {

			'use strict';

			var control   = this,
			value,
			thisInput,
			inputDefault,
			changeAction,
			controlClass  = '.customize-control-woostify-range-slider',
			footerActions = jQuery( '#customize-footer-actions' );

			// Set up the sliders.
			jQuery( '.woostify-slider' ).each(
				function() {
					var _this = jQuery( this ),
					_input    = _this.closest( 'label' ).find( 'input[type="number"]' );

					_this.slider(
						{
							value: _input.val(),
							min: _this.data( 'min' ),
							max: _this.data( 'max' ),
							step: _this.data( 'step' ),
							slide: function( event, ui ) {
								_input.val( ui.value ).change();
							}
						}
					);
				}
			);

			// Update the range value based on the input value.
			jQuery( controlClass + ' .woostify_range_value input[type=number]' ).on(
				'input',
				function() {
					value = jQuery( this ).attr( 'value' );

					if ( '' === value ) {
						value = -1;
					}

					jQuery( this ).closest( 'label' ).find( '.woostify-slider' ).slider( 'value', parseFloat( value ) ).change();
				}
			);

			// Handle the reset button.
			jQuery( controlClass + ' .woostify-reset' ).on(
				'click',
				function() {
					var icon     = jQuery( this ),
					visible_area = icon.closest( '.woostify-range-title-area' ).next( '.woostify-range-slider-areas' ).children( 'label:visible' ),
					input        = visible_area.find( 'input[type=number]' ),
					slider_value = visible_area.find( '.woostify-slider' ),
					visual_value = visible_area.find( '.woostify_range_value' ),
					reset_value  = input.attr( 'data-reset_value' );

					input.val( reset_value ).change();
					visual_value.find( 'input' ).val( reset_value );

					if ( '' === reset_value ) {
						reset_value = -1;
					}

					slider_value.slider( 'value', parseFloat( reset_value ) );
				}
			);

			// Figure out which device icon to make active on load.
			jQuery( controlClass + ' .woostify-range-slider-control' ).each(
				function() {
					var _this = jQuery( this );
					_this.find( '.woostify-device-controls' ).children( 'span:first-child' ).addClass( 'selected' );
					_this.find( '.range-option-area:first-child' ).show();
				}
			);

			// Do stuff when device icons are clicked.
			jQuery( controlClass + ' .woostify-device-controls > span' ).on(
				'click',
				function( event ) {
					var device = jQuery( this ).data( 'option' );

					jQuery( controlClass + ' .woostify-device-controls span' ).each(
						function() {
							var _this = jQuery( this );
							if ( device === _this.attr( 'data-option' ) ) {
								_this.addClass( 'selected' );
								_this.siblings().removeClass( 'selected' );
							}
						}
					);

					jQuery( controlClass + ' .woostify-range-slider-areas label' ).each(
						function() {
							var _this = jQuery( this );
							if ( device === _this.attr( 'data-option' ) ) {
								_this.show();
								_this.siblings().hide();
							}
						}
					);

					// Set the device we're currently viewing.
					wp.customize.previewedDevice.set( jQuery( event.currentTarget ).data( 'option' ) );
				}
			);

			// Set the selected devices in our control when the Customizer devices are clicked.
			footerActions.find( '.devices button' ).on(
				'click',
				function() {
					var device = jQuery( this ).data( 'device' );

					jQuery( controlClass + ' .woostify-device-controls span' ).each(
						function() {
							var _this = jQuery( this );
							if ( device === _this.attr( 'data-option' ) ) {
								_this.addClass( 'selected' );
								_this.siblings().removeClass( 'selected' );
							}
						}
					);

					jQuery( controlClass + ' .woostify-range-slider-areas label' ).each(
						function() {
							var _this = jQuery( this );
							if ( device === _this.attr( 'data-option' ) ) {
								_this.show();
								_this.siblings().hide();
							}
						}
					);
				}
			);

			// Apply changes when desktop slider is changed.
			control.container.on(
				'input change',
				'.desktop-range',
				function() {
					control.settings['desktop'].set( jQuery( this ).val() );
				}
			);

			// Apply changes when tablet slider is changed.
			control.container.on(
				'input change',
				'.tablet-range',
				function() {
					control.settings['tablet'].set( jQuery( this ).val() );
				}
			);

			// Apply changes when mobile slider is changed.
			control.container.on(
				'input change',
				'.mobile-range',
				function() {
					control.settings['mobile'].set( jQuery( this ).val() );
				}
			);
		}

	}
);
