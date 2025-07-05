(function( $, undefined ) {
	'use strict';
	wp.customize.controlConstructor['vamtam-multicheck'] = wp.customize.Control.extend({

		ready: function() {
			var control = this;

			control.settingField = control.container.find( '[data-customize-setting-link]' ).first();

			control.container.on( 'change', 'input[type=checkbox]', function() {
				var new_value = {};

				// reset values;
				for ( var key in control.params.choices ) {
					new_value[ key ] = '';
				}

				control.container.find( 'input[type="checkbox"]:checked' ).each( function() {
					new_value[ $( this ).data( 'key' ) ] = this.value;
				} );

				control.setting.set( new_value );
			});
		}

	});
})( jQuery );