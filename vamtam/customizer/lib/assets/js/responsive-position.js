(function( $, undefined ) {
	'use strict';

	wp.customize.controlConstructor['vamtam-responsive-position'] = wp.customize.Control.extend({
		ready: function() {
			var control = this,
			    value   = JSON.parse( JSON.stringify( this.params.value ) );

			console.log( this );

			this.container.on( 'change', 'input[type=number]', function( e ) {
				let where = e.target.id.match( /(\w+)-(\w+)$/ );

				value[ where[1] ][ where[2] ] = e.target.value;

				control.setting.set( JSON.parse( JSON.stringify( value ) ) );
			});
		},
	});
})( jQuery );