/**
 * Woostify get pro version
 *
 * @package woostify
 */

( function( $, api ) {
	api.sectionConstructor[ 'woostify-pro-section' ] = api.Section.extend(
		{

				// No events for this type of section.
			attachEvents: function() {},

				// Always make the section active.
			isContextuallyActive: function() {
				return true;
			}
		}
	);
} )( jQuery, wp.customize );
