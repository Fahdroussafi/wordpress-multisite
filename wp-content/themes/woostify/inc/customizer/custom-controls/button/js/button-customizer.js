/**
 * Button js
 *
 * @package woostify
 */

wp.customize.controlConstructor['woostify-button-control'] = wp.customize.Control.extend(
	{
		ready: function () {
			'use strict'
			let control = this,
			ajax_action = control.params.ajax_action,
			button      = control.container.find( '.button' )

			if ( '' === ajax_action ) {
				return false;
			}

			button.on(
				'click',
				function() {
					let _this = jQuery( this )

					setTimeout(
						function() {
							// Send our request to the woostify_regenerate_fonts_folder function.
							jQuery.ajax(
								{
									type: 'POST',
									url: ajaxurl,
									data: {
										action: ajax_action,
										woostify_customize_nonce: woostify_customize.nonce
									},
									async: false,
									dataType: 'json',
									beforeSend: function () {
										_this.parent().find( 'p.message' ).remove();
									},
									success: function() {
										let message = '<p class="message">Successfully!</p>';
										_this.after( message );
									},
									error: function() {
										let message = '<p class="message">Failed!</p>';
										_this.after( message );
									}
								}
							);
						},
						100
					)
				}
			)
		}
	}
)
