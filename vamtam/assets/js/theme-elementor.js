// Theme-Elementor related code for both frontend and editor.
( function( $, undefined ) {
	"use strict";

	window.VAMTAM = window.VAMTAM || {}; // Namespace

	$( function() {
		var isFrontend = ! window.elementorFrontend.isEditMode();

		if ( isFrontend ) {
			$( document ).ready( function () {
			} );
		} else {
			$(window).on('elementor/frontend/init', function(){
				window.elementor.on('document:loaded', function(){
					// The timeout is for ensuring that when the function is run, the frontend DOM has
					// been created (when on editor). Can't seem to find a proper event for this from Elementor.
					setTimeout( function() {
					}, 1000 );
				} );
			} );
		}
	});
})( jQuery );
