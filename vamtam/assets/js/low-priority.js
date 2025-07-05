/* jshint esversion: 6 */

(function( v, undefined ) {
	'use strict';

	// scroll to top button
	{
		var st_buttons = document.querySelectorAll( '.vamtam-scroll-to-top' );

		if ( st_buttons.length ) {
			var side_st_button = document.getElementById( 'scroll-to-top' ),
				pageMiddle = window.innerHeight / 2;

			if ( side_st_button ) {
				v.addScrollHandler( {
					init: function() {},
					measure: function() {
						pageMiddle = window.innerHeight / 2;
					},
					mutate: function( cpos ) {
						if ( cpos > pageMiddle ) {
							side_st_button.style.opacity   = 1;
							side_st_button.style.transform = 'translate3d(0, 0, 0)';
						} else {
							side_st_button.style.opacity   = '';
							side_st_button.style.transform = 'translate3d(0, 100%, 0)';
						}
					}
				} );
			}

			document.addEventListener( 'click', ( e ) => {
				if ( e.target.closest( '.vamtam-scroll-to-top' ) ) {
					e.preventDefault();

					// iOS Safari uses a simple animation, normal browsers use scroll-behavior:smooth
					if ( /iPad|iPhone|iPod/.test( navigator.userAgent ) && ! window.MSStream ) {
						window.scrollTo( 0, 0 );
					} else {
						window.scroll( { left: 0, top: 0, behavior: 'smooth' } );
					}
				}
			}, true );
		}
	}
})( window.VAMTAM );
