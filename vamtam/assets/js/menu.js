(function($, v, undefined) {
	"use strict";

	var mainHeader      = $('header.main-header');
	var header_contents = mainHeader.find( '.header-contents' );
	var menu_toggle     = document.getElementById( 'vamtam-fallback-main-menu-toggle' );
	var original_toggle = document.querySelector( '#main-menu > .mega-menu-wrap > .mega-menu-toggle' );

	// scrolling below

	var smoothScrollTimer, smoothScrollCallback;

	var smoothScrollListener = function() {
		clearTimeout( smoothScrollTimer );

		smoothScrollTimer = setTimeout( scrollToElComplete, 200 );
	};

	var scrollToElComplete = function() {
		window.removeEventListener( 'scroll', smoothScrollListener, { passive: true } );
		v.blockStickyHeaderAnimation = false;

		if ( smoothScrollCallback ) {
			smoothScrollCallback();
		}
	};

	var scrollToEl = function( el, duration, callback ) {
		requestAnimationFrame( function() {
			var el_offset = el.offset().top;

			v.blockStickyHeaderAnimation = true;

			// measure header height
			var header_height = 0;
			header_height = header_contents.height() || 0;


			var scroll_position = el_offset - v.adminBarHeight - header_height;

			smoothScrollCallback = callback;

			window.addEventListener( 'scroll', smoothScrollListener, { passive: true } );

			window.scroll( { left: 0, top: scroll_position, behavior: 'smooth' } );

			if ( el.attr( 'id' ) ) {
				if ( history.pushState ) {
					history.pushState( null, null, '#' + el.attr( 'id' ) );
				} else {
					window.location.hash = el.attr( 'id' );
				}
			}

			menu_toggle && menu_toggle.classList.remove( 'mega-menu-open' );
			original_toggle && original_toggle.classList.remove( 'mega-menu-open' );
		} );
	};

	$( document.body ).on('click', '.vamtam-animated-page-scroll[href], .vamtam-animated-page-scroll [href], .vamtam-animated-page-scroll [data-href]', function(e) {
		var href = $( this ).prop( 'href' ) || $( this ).data( 'href' );
		var el   = $( '#' + ( href ).split( "#" )[1] );

		var l  = document.createElement('a');
		l.href = href;

		if(el.length && l.pathname === window.location.pathname) {
			menu_toggle && menu_toggle.classList.remove( 'mega-menu-open' );
			original_toggle && original_toggle.classList.remove( 'mega-menu-open' );

			scrollToEl( el );
			e.preventDefault();
		}
	});

	if ( window.location.hash !== "" &&
		(
			$( '.vamtam-animated-page-scroll[href*="' + window.location.hash + '"]' ).length ||
			$( '.vamtam-animated-page-scroll [href*="' + window.location.hash + '"]').length ||
			$( '.vamtam-animated-page-scroll [data-href*="'+window.location.hash+'"]' ).length
		)
	) {
		var el = $( window.location.hash );

		if ( el.length > 0 ) {
			$( window ).add( 'html, body, #page' ).scrollTop( 0 );
		}

		setTimeout( function() {
			scrollToEl( el );
		}, 400 );
	}
})( jQuery, window.VAMTAM );
