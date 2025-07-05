/**
 * Often used vanilla js functions, so that we don't need
 * to use all of underscore/jQuery
 */
(function( undefined ) {
	"use strict";

	var v = ( window.VAMTAM = window.VAMTAM || {} ); // Namespace

	// Returns a function, that, as long as it continues to be invoked, will not
	// be triggered. The function will be called after it stops being called for
	// N milliseconds. If `immediate` is passed, trigger the function on the
	// leading edge, instead of the trailing.
	v.debounce = function( func, wait, immediate ) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if ( ! immediate ) func.apply( context, args );
			};
			var callNow = immediate && ! timeout;
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
			if ( callNow ) func.apply( context, args );
		};
	};

	// vanilla jQuery.fn.offset() replacement
	// @see https://plainjs.com/javascript/styles/get-the-position-of-an-element-relative-to-the-document-24/

	v.offset = function( el ) {
		var rect = el.getBoundingClientRect(),
		scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
		scrollTop = window.pageYOffset || document.documentElement.scrollTop;
		return { top: rect.top + scrollTop, left: rect.left + scrollLeft };
	};

	// Faster scroll-based animations

	v.scroll_handlers = [];
	v.latestKnownScrollY = 0;

	var ticking = false;

	v.addScrollHandler = function( handler ) {
		requestAnimationFrame( function() {
			handler.init();
			v.scroll_handlers.push( handler );

			handler.measure( v.latestKnownScrollY );
			handler.mutate( v.latestKnownScrollY );
		} );
	};

	v.onScroll = function() {
		v.latestKnownScrollY = window.pageYOffset;

		if ( ! ticking ) {
			ticking = true;

			requestAnimationFrame( function() {
				var i;

				for ( i = 0; i < v.scroll_handlers.length; i++ ) {
					v.scroll_handlers[i].measure( v.latestKnownScrollY );
				}

				for ( i = 0; i < v.scroll_handlers.length; i++ ) {
					v.scroll_handlers[i].mutate( v.latestKnownScrollY );
				}

				ticking = false;
			} );
		}
	};

	window.addEventListener( 'scroll', v.onScroll, { passive: true } );

	// Load an async script
	v.load_script = function( src, callback ) {
		var s = document.createElement('script');
		s.type = 'text/javascript';
		s.async = true;
		s.src = src;

		if ( callback ) {
			s.onload = callback;
		}

		document.getElementsByTagName('script')[0].before( s );
	};

	v.load_style = function( href, media, callback, after ) {
		var l = document.createElement('link');
		l.rel = 'stylesheet';
		l.type = 'text/css';
		l.media = media;
		l.href = href;

		if ( callback ) {
			l.onload = callback;
		}

		if ( after ) {
			after.after( l );
		} else {
			document.getElementsByTagName('link')[0].before( l );
		}
	};

	// Checks if current window size is inside the below-max breakpoint range.
	v.isBelowMaxDeviceWidth = function () {
		return ! window.matchMedia( '(min-width: ' + VAMTAM_FRONT.max_breakpoint + 'px)' ).matches;
	};

	// Checks if current window size is inside the max breakpoint range.
	v.isMaxDeviceWidth = function () {
		return window.matchMedia( '(min-width: ' + VAMTAM_FRONT.max_breakpoint + 'px)' ).matches;
	};

	// Checks if current window size is inside the max breakpoint range.
	v.isMediumDeviceOrWider = function () {
		return window.matchMedia( '(min-width: ' + VAMTAM_FRONT.medium_breakpoint + 'px)' ).matches;
	};

	v.isMobileBrowser = /Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/.test( navigator.userAgent ) || ( /Macintosh/.test( navigator.userAgent ) && navigator.maxTouchPoints && navigator.maxTouchPoints > 2 );

	v.getScrollbarWidth = () => window.innerWidth - document.documentElement.clientWidth;

	let windowLoaded = false;

	v.waitForLoad = function( callback ) {
		if ( windowLoaded ) {
			callback();
		} else {
			window.addEventListener( 'load', callback );
		}
	};

	window.addEventListener('load', function () {
		windowLoaded = true;
	} );
})();
