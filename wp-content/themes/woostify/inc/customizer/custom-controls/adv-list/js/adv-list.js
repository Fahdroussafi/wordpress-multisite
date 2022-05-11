/**
 * Advanced List JS
 *
 * @package woostify
 */

wp.customize.controlConstructor['woostify-adv-list'] = wp.customize.Control.extend(
	{
		ready: function() {
			'use strict';
			var control        = this;
			var list_item_wrap = control.container.find( '.woostify-adv-list-items' );

			function update_value() {
				var value = {}
				list_item_wrap.find( '.woostify-sortable-list-item-wrap' ).each(
					function( item_idx, item_obj ) {
						var item_wrap   = jQuery( item_obj )
						value[item_idx] = {}
						item_wrap.each(
							function( control_idx, control_obj ) {
								var item_control          = jQuery( control_obj )
								var is_visibility         = item_control.find( '.woostify-adv-list-checkbox' ).is( ':checked' )
								value[item_idx]['hidden'] = ! is_visibility
								item_control.find( '.woostify-adv-list-control' ).each(
									function( input_idx, input_obj ) {
										var field_name              = jQuery( input_obj ).data( 'field_name' )
										value[item_idx][field_name] = jQuery( input_obj ).find( '.woostify-adv-list-input' ).val()
									},
								)
							},
						)
					},
				)
				control.settings['default'].set( JSON.stringify( value ) );
			}

			function display_item_options( el ) {
				el.each(
					function() {
						var options_wrap = jQuery( this ).closest( '.adv-list-item-content' )
						var type         = jQuery( this ).val()
						switch ( type ) {
							case 'custom':
								options_wrap.find( '.woostify-adv-list-control' ).removeClass( 'hide' )
								options_wrap.find( '.woostify-adv-list-control.shortcode-field' ).addClass( 'hide' )
								break
							case 'wishlist':
							case 'cart':
							case 'search':
								options_wrap.find( '.woostify-adv-list-control:not(.type-field)' ).addClass( 'hide' )
								options_wrap.find( '.woostify-adv-list-control.name-field' ).removeClass( 'hide' )
								options_wrap.find( '.woostify-adv-list-control.icon-field' ).removeClass( 'hide' )
								break
							case 'shortcode':
								options_wrap.find( '.woostify-adv-list-control:not(.type-field)' ).addClass( 'hide' )
								options_wrap.find( '.woostify-adv-list-control.shortcode-field' ).removeClass( 'hide' )
								break
							default:
								options_wrap.find( '.woostify-adv-list-control' ).removeClass( 'hide' )
						}
					},
				)
			}

			display_item_options( list_item_wrap.find( '.woostify-adv-list-select' ) );

			var icon_field     = control.container.find( '.icon-field.woostify-adv-list-control' );
			var icon_list_area = icon_field.find( '.icon-list-wrap' );

			icon_list_area.each(
				function( idx, obj ) {
					var selected_data = jQuery( obj ).data( 'selected' );
					jQuery.getJSON(
						woostify_svg_icons.file_url,
						function(data){
							jQuery.each(
								data,
								function(k, v) {
									var active_class = selected_data === k ? ' active' : '';
									var template     = '<span class="icon-list__icon' + active_class + '" data-icon="' + k + '">';
									template        += v;
									template        += '</span>';
									jQuery( obj ).append( template );
								}
							);
						}
					);
				}
			);

			control.container.find( '.woostify-adv-list-select' ).on(
				'change',
				function() {
					update_value()

					display_item_options( jQuery( this ) )
				},
			)
			control.container.find( '.woostify-adv-list-input--name' ).on(
				'keyup',
				function() {
					var item_wrap = jQuery( this ).closest( '.woostify-sortable-list-item-wrap' )
					item_wrap.find( '.sortable-item-name' ).text( jQuery( this ).val() )
				},
			)
			control.container.find( '.woostify-adv-list-items input[type=checkbox]' ).on(
				'click',
				function() {
					var checkbox  = jQuery( this )
					var item      = checkbox.closest( '.woostify-adv-list-item' )
					var item_wrap = checkbox.closest( '.woostify-sortable-list-item-wrap' )
					var label     = checkbox.parent()
					if ( ! checkbox.is( ':checked' ) ) {
						item.removeClass( 'checked' )
						item_wrap.removeClass( 'checked' )
						label.removeClass( 'dashicons-visibility' )
						label.addClass( 'dashicons-hidden' )
						item_wrap.find( '.adv-list-item-content' ).hide()
					} else {
						item.addClass( 'checked' )
						item_wrap.addClass( 'checked' )
						label.removeClass( 'dashicons-hidden' )
						label.addClass( 'dashicons-visibility' )
					}
					update_value()
				},
			)
			control.container.find( '.woostify-adv-list-input' ).on(
				'blur',
				function() {
					update_value()
				},
			)

			control.container.find( '.woostify-icon-remove-btn' ).on(
				'click',
				function() {
					var field_control = jQuery( this ).closest( '.woostify-adv-list-control' )
					jQuery( this ).parent().addClass( 'hide' )
					jQuery( this ).parent().find( 'img' ).attr( 'src', '' )
					field_control.find( 'input.woostify-adv-list-input' ).val( '' )
					update_value( jQuery( '.woostify-adv-list-items' ) )
				},
			)

			control.container.find( '.woostify-adv-list-items' ).sortable(
				{
					handle: '.woostify-adv-list-item',
					update: function( event, ui ) {
						update_value()
					},
				},
			)
			control.container.find( '.woostify-adv-list-items' ).disableSelection()

			jQuery( document ).on(
				'click',
				'.icon-list__icon',
				function() {
					var icon           = jQuery( this ).data( 'icon' )
					var icon_container = jQuery( this ).parent()
					var field_control  = jQuery( this ).closest( '.woostify-adv-list-control' )
					var input          = field_control.find( '.woostify-adv-list-input' )

					input.val( icon )
					icon_container.find( '.icon-list__icon' ).removeClass( 'active' )
					jQuery( this ).addClass( 'active' )
					field_control.find( '.selected-icon' ).html( jQuery( this ).html() )
					update_value()
				},
			)

			control.container.find( '.select-icon-act .open-icon-list' ).on(
				'click',
				function() {
					var field_control = jQuery( this ).closest( '.woostify-adv-list-control' )
					var icon_list     = field_control.find( '.icon-list' );
					icon_list.slideToggle(
						500,
						function() {
							if ( jQuery( this ).hasClass( 'open' ) ) {
								jQuery( this ).delay( 500 ).removeClass( 'open' );
							} else {
								jQuery( this ).delay( 500 ).addClass( 'open' );
							}
						}
					)
				},
			)

			control.container.find( '.icon-list__search input' ).on(
				'keyup search',
				function() {
					var value          = jQuery( this ).val().toLowerCase()
					var icon_list      = jQuery( this ).closest( '.icon-list' )
					var icon_list_wrap = icon_list.find( '.icon-list-wrap' )
					icon_list_wrap.find( '.icon-list__icon' ).filter(
						function() {
							var icon_name = jQuery( this ).data( 'icon' ).toLowerCase().replaceAll( '-', ' ' )
							var check     = icon_name.indexOf( value ) > - 1
							jQuery( this ).toggle( check )
						},
					)
				},
			)

			control.container.find( '.select-icon-act .remove-icon' ).on(
				'click',
				function() {
					var field_control = jQuery( this ).closest( '.woostify-adv-list-control' )
					var input         = field_control.find( '.woostify-adv-list-input' )

					input.val( '' )
					field_control.find( '.icon-list__icon' ).removeClass( 'active' )
					field_control.find( '.selected-icon' ).html( '' )
					update_value()
				},
			)

			jQuery( document ).on(
				'click',
				'body',
				function(e) {
					var icon_list     = jQuery( '.icon-list.open' );
					var icon_list_btn = jQuery( '.select-icon-act .open-icon-list' );
					// if the target of the click isn't the container nor a descendant of the container.
					if ( ! icon_list.is( e.target ) && icon_list.has( e.target ).length === 0 && ! icon_list_btn.is( e.target ) ) {
						icon_list.slideUp( 500 );
						icon_list.delay( 500 ).removeClass( 'open' );
					}
				}
			)
		}
	}
)
