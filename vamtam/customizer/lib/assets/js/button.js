/*
 VamTam Button
 */

/*global jQuery*/

(function( $ ) {
	'use strict';

	$( function() {
		$( 'body' ).on( 'click', '.vamtam-import-button', function( e ) {
			e.preventDefault();

			var button = $( this );

			button.addClass( 'disabled' );

			var spinner = $( '<span></span>' ).addClass( 'spinner' ).css( 'visibility', 'visible' );

			button.after( spinner );

			$.get( button.attr( 'href' ), function( result ) {
				spinner.remove();

				if ( result.match( /all done\./i ) ) {
					button.after( button.data( 'success-msg' ) );

					wp.customize.previewer.refresh();
				} else {
					button.after( button.data( 'error-msg' ).replace( '{fullimport}', button.attr( 'href' ) ) );
				}
			} );
		} );
	} );
})( jQuery );