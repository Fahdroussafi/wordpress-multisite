/**
 * Sticky Footer Bar js
 *
 * @package woostify
 */

wp.customize.controlConstructor['woostify-color-group'] = wp.customize.Control.extend(
	{
		ready: function () {
			'use strict'
			let control         = this
			let control_wrap    = control.container.find( '.woostify-color-group-control' )
			let control_id      = control_wrap.data( 'control_id' )
			let color_format    = control.params.color_format
			let enable_swatches = control.params.enable_swatches
			let swatches        = control.params.swatches
			let swatchLabels    = control.params.swatchLabels

			jQuery.each(
				woostify_color_group.elementor_colors,
				function( c_idx, c_val ) {
					swatches.push( c_val.color );
					swatchLabels.push( c_val.title );
				}
			)

			let args = {
				el: '.btn',
				theme: 'monolith',
				autoReposition: false,
				inline: false,
				container: '.woostify-color-group-control',
				lockOpacity: false,
				comparison: false,
				default: '',
					defaultRepresentation: 'RGBA',
					adjustableNumbers: true,
					swatches: ! enable_swatches ? false : swatches,
					swatchLabels: swatchLabels,
					useAsButton: true,
					components: {
						// Main components.
						preview: false,
						opacity: true,
						hue: true,
						// Input / output Options.
						interaction: {
							hex: true,
							rgba: true,
							input: true,
							clear: true,
						},
				},
			}

			let global_color_settings = [
				'woostify_setting[theme_color]',
				'woostify_setting[text_color]',
				'woostify_setting[accent_color]',
				'woostify_setting[extra_color_1]',
				'woostify_setting[extra_color_2]',
			]
			createColorPicker( control );
			control.container.find( '.woostify-reset' ).on(
				'click',
				function () {
					control.container.find( 'div.pcr-app' ).remove()
					let inputs          = control.container.find( 'input.color-group-value' )
					let buttons         = control.container.find( '.woostify-color-group-btn' )
					let container       = jQuery( this ).closest( '.woostify-color-group-control' )
					let container_class = container.attr( 'class' ).split( ' ' )[2]
					jQuery.each(
						control.params.settings,
						function ( idx ) {
							let reset_value = jQuery( inputs[idx] ).data( 'reset_value' )
							jQuery( buttons[idx] ).css( 'color', reset_value )
							control.settings[idx].set( reset_value )

							args.el                    = buttons[idx]
							args.container             = '.' + container_class
							args.default               = reset_value
							args.defaultRepresentation = color_format.toUpperCase()
							let pickr2                 = new Pickr( args )
							jQuery( args.el ).css(
								'color',
								'' !== args.default ? args.default : 'rgba(255,255,255,0)'
							)
							pickr2.on(
								'change',
								function ( color ) {
									document.body.classList.add( 'color-updating-class-name' )
									control.settings[idx].set( colorFormat( color, color_format ).toString( 0 ) )
								},
							).on(
								'clear',
								function ( instance ) {
									instance.options.el.style.color = 'rgba(255,255,255,0)'
									control.settings[idx].set( instance.options.default )
								},
							).on(
								'changestop',
								function( source, instance ) {
									document.body.classList.remove( 'color-updating-class-name' );
								}
							)
							pickr2.applyColor()
						},
					)
				},
			)

			function colorFormat( color, format = 'rgba' ) {
				// hsva.toHSVA() - Converts the object to a hsva array.
				// hsva.toHSLA() - Converts the object to a hsla array.
				// hsva.toRGBA() - Converts the object to a rgba array.
				// hsva.toHEXA() - Converts the object to a hexa-decimal array.
				// hsva.toCMYK() - Converts the object to a cmyk array.
				// hsva.clone() - Clones the color object.
				let new_color
				switch ( format ) {
					case 'rgba':
						new_color = color.toRGBA()
						break
					case 'hex':
						new_color = color.toHEXA()
						break
					case 'hsva':
						new_color = color.toHSVA()
						break
					case 'hsla':
						new_color = color.toHSLA()
						break
					case 'cmyk':
						new_color = color.toCMYK()
						break
					default:
						new_color = color.clone()
				}
				return new_color
			}

			function createColorPicker( control ) {
				jQuery.each(
					control.params.settings,
					function ( idx, obj ) {
						let btn_id_arr             = obj.split( '[' )
						let btn_id                 = (
							'undefined' === typeof btn_id_arr[1]
						) ? btn_id_arr[0] : btn_id_arr[1].split( ']' )[0]
						args.el                    = '.btn-' + btn_id
						args.container             = '.woostify-color-group-control-' + control_id
						args.default               = '' !== control.settings[idx].get() ? control.settings[idx].get() : 'rgba(255,255,255,0)'
						args.defaultRepresentation = color_format.toUpperCase()
						args.swatches              = ! enable_swatches ? false : control.params.swatches
						let pickr                  = new Pickr( args )
						jQuery( args.el ).css( 'color', args.default )
						pickr.on(
							'change',
							function ( color, source, instance ) {
								document.body.classList.add( 'color-updating-class-name' );
								instance.options.el.style.color = colorFormat( color, color_format ).toString( 0 )
								control.settings[idx].set( colorFormat( color, color_format ).toString( 0 ) )
							},
						).on(
							'clear',
							function ( instance ) {
								instance.options.el.style.color = 'rgba(255,255,255,0)'
								control.settings[idx].set( instance.options.default )
							},
						).on(
							'changestop',
							function( source, instance ) {
								document.body.classList.remove( 'color-updating-class-name' );
							}
						)

						if ( global_color_settings.indexOf( obj ) !== -1 ) {
							let swatch_idx = global_color_settings.indexOf( obj );
							pickr.on(
								'changestop',
								function( source, instance ) {
									let new_color = instance._color.toHEXA().toString( 0 )
									let pickrs    = document.querySelectorAll( '.customize-control-woostify-color-group:not(.woostify-global-color)' );
									document.body.classList.remove( 'color-updating-class-name' );
									pickrs.forEach(
										function( pobj ) {
											let control_id   = pobj.children[0].getAttribute( 'data-control_id' );
											let prefix       = pobj.children[0].getAttribute( 'data-prefix' );
											let setting_name = '' === prefix ? control_id : prefix + '[' + control_id + ']'
											wp.customize.control(
												setting_name,
												function( setting_control ) {
													setting_control.container.find( 'div.pcr-app' ).remove()
													let setting_color_format     = setting_control.params.color_format
													let setting_enable_swatches  = setting_control.params.enable_swatches
													let setting_swatches         = setting_control.params.swatches
													setting_swatches[swatch_idx] = new_color
													jQuery.each(
														setting_control.params.settings,
														function ( idx, obj ) {
															let btn_id_arr             = obj.split( '[' )
															let btn_id                 = (
																'undefined' === typeof btn_id_arr[1]
															) ? btn_id_arr[0] : btn_id_arr[1].split( ']' )[0]
															args.el                    = '.btn-' + btn_id
															args.container             = '.woostify-color-group-control-' + control_id
															args.default               = '' !== setting_control.settings[idx].get() ? setting_control.settings[idx].get() : 'rgba(255,255,255,0)'
															args.defaultRepresentation = setting_color_format.toUpperCase()
															args.swatches              = ! setting_enable_swatches ? false : setting_swatches
															let pickr                  = new Pickr( args )
															jQuery( args.el ).css( 'color', args.default )
															pickr.on(
																'change',
																function ( color, source, instance ) {
																	document.body.classList.add( 'color-updating-class-name' );
																	instance.options.el.style.color = colorFormat( color, color_format ).toString( 0 )
																	setting_control.settings[idx].set( colorFormat( color, color_format ).toString( 0 ) )
																},
															).on(
																'clear',
																function ( instance ) {
																	instance.options.el.style.color = 'rgba(255,255,255,0)'
																	setting_control.settings[idx].set( instance.options.default )
																},
															).on(
																'changestop',
																function( source, instance ) {
																	document.body.classList.remove( 'color-updating-class-name' );
																}
															)

															pickr.applyColor()

														},
													)
												}
											);
										}
									)
									// Get all targets.
									let targets = document.querySelectorAll( '[rel="tooltip"]' );

									// Loop targets.
									let target_length = targets.length;
									for (let i = 0; i < target_length; i++) {
										// Add listeners.
										targets[i].addEventListener(
											'mouseenter',
											function ()
											{
												addTooltip( this, document.querySelector( '.woostify-swatch-tooltip' ) );
											},
											false
										);
										targets[i].addEventListener(
											'mouseleave',
											function ()
											{
												removeTooltip( this, document.querySelector( '.woostify-swatch-tooltip' ) );
											},
											false
										);
										targets[i].addEventListener(
											'click',
											function () {
												// Get the active tooltip.
												let tooltip = document.querySelector( '.woostify-swatch-tooltip' );
												// Get the target.
												let target = event.target.closest( '[rel="tooltip"]' );
												// Check if the tooltip exists or not.
												if (tooltip === null) {
													// Add a tooltip.
													addTooltip( target, tooltip );
												} else {
													// Remove a tooltip.
													removeTooltip( target, tooltip );
												}
											},
											false
										);
									}
								}
							)
						}

						pickr.applyColor()
					},
				)
			}

			// Get all targets.
			let targets = document.querySelectorAll( '[rel="tooltip"]' );

			// Loop targets.
			let target_length = targets.length;
			for (let i = 0; i < target_length; i++) {
				// Add listeners.
				targets[i].addEventListener(
					'mouseenter',
					function ()
					{
						addTooltip( this, document.querySelector( '.woostify-swatch-tooltip' ) );
					},
					false
				);
				targets[i].addEventListener(
					'mouseleave',
					function ()
					{
						removeTooltip( this, document.querySelector( '.woostify-swatch-tooltip' ) );
					},
					false
				);
				targets[i].addEventListener(
					'click',
					function () {
						// Get the active tooltip.
						let tooltip = document.querySelector( '.woostify-swatch-tooltip' );
						// Get the target.
						let target = event.target.closest( '[rel="tooltip"]' );
						// Check if the tooltip exists or not.
						if (tooltip === null) {
							// Add a tooltip.
							addTooltip( target, tooltip );
						} else {
							// Remove a tooltip.
							removeTooltip( target, tooltip );
						}
					},
					false
				);
			}
			// Add a tooltip.
			function addTooltip(target, tooltip)
			{
				// Get the title.
				var title = target.getAttribute( 'title' );
				// Make sure that title not is null or empty.
				if (title === null || title === '' || tooltip !== null) {
					return false;
				}
				// Remove the title of the target.
				target.removeAttribute( 'title' );
				// Add a tooltip.
				tooltip = document.createElement( 'div' );
				tooltip.setAttribute( 'class', 'woostify-swatch-tooltip' );
				tooltip.innerHTML = title;
				target.insertAdjacentElement( 'beforeend', tooltip );
				tooltip.classList.add( 'top' );
			}

			// Remove a tooltip.
			function removeTooltip(target, tooltip)
			{
				if (tooltip !== null) {
					// Reset the title and remove the tooltip.
					target.setAttribute( 'title', tooltip.innerHTML );
					tooltip.remove();
				}

			} // End of the removeTooltip method.
		},
	},
)
