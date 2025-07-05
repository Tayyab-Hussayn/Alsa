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

/* jshint multistr:true */
(function( $, undefined ) {
	"use strict";

	window.VAMTAM = window.VAMTAM || {}; // Namespace

	$(function () {
		window.VAMTAM.adminBarHeight = document.body.classList.contains( 'admin-bar' ) ? 32 : 0;

		if ( /iPad|iPhone|iPod/.test( navigator.userAgent ) && ! window.MSStream) {
			requestAnimationFrame( function() {
				document.documentElement.classList.add( 'ios-safari' );
			} );
		}

		if ( navigator.userAgent.includes( 'Safari' ) && ! navigator.userAgent.includes( 'Chrome' ) ) {
			requestAnimationFrame( function() {
				document.documentElement.classList.add( 'safari' );
			} );
		}

		// prevent hover when scrolling
		(function() {
			var wrapper = document.body,
				timer;

			window.addEventListener( 'scroll', function() {
				clearTimeout(timer);

				requestAnimationFrame( function() {
					wrapper.classList.add( 'disable-hover' );

					timer = setTimeout( function() {
						wrapper.classList.remove( 'disable-hover' );
					}, 300 );
				} );
			}, { passive:true } );
		})();

		// print trigger

		document.addEventListener( 'click', function( e ) {
			if ( e.target.closest( '.vamtam-trigger-print' ) ) {
				window.print();
				e.preventDefault();
			}
		} );

		// Code which depends on the window width
		// =====================================================================

		window.VAMTAM.resizeElements = function() {
			// video size
			$('#page .media-inner,\
				.wp-block-embed-vimeo:not(.wp-has-aspect-ratio),\
				:not(.wp-block-embed__wrapper) > .vamtam-video-frame').find('iframe, object, embed, video').each(function() {

				setTimeout( function() {
					requestAnimationFrame( function() {
						var v_width = this.offsetWidth;

						this.style.width = '100%';

						if ( this.width === '0' && this.height === '0' ) {
							this.style.height = ( v_width * 9/16 ) + 'px';
						} else {
							this.style.height = ( this.height * v_width / this.width ) + 'px';
						}

						$( this ).trigger('vamtam-video-resized');
					}.bind( this ) );
				}.bind( this ), 50 );
			});

			setTimeout( function() {
				requestAnimationFrame( function() {
					$('.mejs-time-rail').css('width', '-=1px');
				} );
			}, 100 );
		};

		window.addEventListener( 'resize', window.VAMTAM.debounce( window.VAMTAM.resizeElements, 100 ), false );
		window.VAMTAM.resizeElements();

		$( document ).ajaxSuccess(function( event, xhr, settings ) {
			const args = settings.data
			                   .split( '&' )
			                   .map( pair => pair.split( '=' ) )
			                   .reduce( (prev, curr) => { prev[ curr[0] ] = curr[1]; return prev; }, {} );

			if ( args.action === 'wishlist_remove' ) {
				const response = JSON.parse( xhr.responseText );

				if ( response.status === 1 && response.count === 0 ) {
					$( '.vamtam-empty-wishlist-notice' ).show();
					$( 'table.woosw-items' ).hide();
				}
			}
		});
	} );

	// Handles various overlay types.
	/*
		How it works:
			-keep track of all overlays (vamtam-overlay-trigger class).
			-keep track of all overlays state (active or not).
			-bind the relevant click handlers (custom for each overlay type).
			-attach a doc click handler which figures out which overlays to close or not.
			-if we switch breakpoint (below-max / max) we close all overlays to avoid conflicts.

		*Every overlay type will be a bit different so it will prob need
		some custom coding (defining the overlay's targets, close triggers).
	*/
	var vamtamOverlaysHandler = function () {
		var elsThatCauseOverlay    = document.querySelectorAll( '.vamtam-overlay-trigger' );
		var elsThatCauseOverlayArr = [];
		var prevIsBelowMax         = window.VAMTAM.isBelowMaxDeviceWidth();

		var triggerCloseHandlers = function () {
			elsThatCauseOverlayArr.forEach( function( el ) {
				if ( el.isActive ) {
					el.closeTrigger.click();
				}
			} );
		};

		var overlaysResizeHandler = function () {
			var isBelowMax = window.VAMTAM.isBelowMaxDeviceWidth();
			if ( prevIsBelowMax !== isBelowMax) {
				// We changed breakpoint (max/below-max).
				// Close all overlays.
				triggerCloseHandlers();
				prevIsBelowMax = isBelowMax;
			}
		};

		var overlayCloseHandler = function ( target ) {
			// Is this an elementor menu overlay?
			if ( $( target ).hasClass( 'elementor-menu-toggle' ) ) {
				// Button is a toggle (on/off).
				target.removeEventListener( 'click', onOverlayCloseClick );
				target.addEventListener( 'click', onOverlayCloseClick );
				return;
			}
			// Add other type of overlays here.
		};

		var onOverlayCloseClick = function ( e ) {
			// Is this an elementor menu overlay?
			var target = e.currentTarget;
			if ( $( target ).hasClass( 'elementor-menu-toggle' ) ) {
				var elRow = $( target ).closest( '.elementor-row' );
				// Elementor >= v3.0.
				if ( ! elRow.length ) {
					// v3.0 removed the .elementor-row element.
					elRow = $( target ).closest( '.elementor-container' );
				}

				if ( elRow.hasClass( 'vamtam-overlay-trigger--overlay' ) ) {
					// We need to remove the overlay
					elRow.removeClass( 'vamtam-overlay-trigger--overlay' );
					target.removeEventListener( 'click', onOverlayCloseClick ); // cause it's a toggle.
					elsThatCauseOverlayArr.forEach( function( e ) {
						if ( e.overlayTarget === target || e.closeTrigger === target ) {
							e.isActive = false;
						}
					});
				}
			}
			// Add other type of overlays here.

			var activeOverlays = $( '.vamtam-overlay-trigger--overlay .vamtam-overlay-element:visible' );
			if ( activeOverlays.length < 2 ) { // If there are other active overlays, don't activate scrollers/stt.
				// Enable page scroll.
				$( 'html, body' ).removeClass( 'vamtam-disable-scroll' );
				// Show stt.
				$( '#scroll-to-top' ).removeClass( 'hidden' );
			}
		};

		var onOverlayTriggerClick = function ( e ) {
			var target = e.currentTarget;
			// Is this an elementor menu overlay?
			if ( $( target ).hasClass( 'elementor-menu-toggle' ) ) {
				var elRow = $( target ).closest( '.elementor-row' );
				// Elementor >= v3.0.
				if ( ! elRow.length ) {
					// v3.0 removed the .elementor-row element.
					elRow = $( target ).closest( '.elementor-container' );
				}

				// This is for moving the overlay underneath the main-menu.
				if ( ! elRow.hasClass( 'vamtam-menu-nav-overlay-inside' ) ) {
					elRow.addClass( 'vamtam-menu-nav-overlay-inside' );
					$( elRow ).find( '.vamtam-overlay-element' ).css( 'top', ( $( elRow )[ 0 ].getBoundingClientRect().top + $( elRow ).height() ) + 'px' );
				}

				if ( ! elRow.hasClass( 'vamtam-overlay-trigger--overlay' ) ) {
					// We need to add the overlay class
					elRow.addClass( 'vamtam-overlay-trigger--overlay' );
					elsThatCauseOverlayArr.forEach( function( e ) {
						if ( e.overlayTarget === target || e.closeTrigger === target ) {
							e.isActive = true;
						}
					});
				} else {
					// This is a close instruction, let onOverlayCloseClick() handle it.
					return;
				}
			}
			// Add other type of overlays here.

			// Disable page scroll.
			$( 'html, body' ).addClass( 'vamtam-disable-scroll' );
			// Hide stt
			$( '#scroll-to-top' ).addClass( 'hidden' );

			// Register the overlay close handler
			overlayCloseHandler( target );
		};

		elsThatCauseOverlay.forEach( function ( el ) {
			// Is this an elementor menu overlay?
			if ( $( el ).hasClass( 'elementor-widget-nav-menu' ) ) {
				// Get menu toggle.
				var menuToggle = $( el ).find( '.elementor-menu-toggle' )[ 0 ];

				// The click listener should be on menu toggle for nav menus.
				menuToggle.removeEventListener( 'click', onOverlayTriggerClick );
				menuToggle.addEventListener( 'click', onOverlayTriggerClick );

				elsThatCauseOverlayArr.push( {
					overlayTarget: el, // The el that holds the vamtam-overlay-trigger class.
					closeTrigger: menuToggle, // The el that closes the overlay.
					isActive: false // If the overlay is active or not.
				} );

				// Add the overlay el.
				var elRow = $( el ).closest( '.elementor-row' );
				// Elementor >= v3.0.
				if ( ! elRow.length ) {
					// v3.0 removed the .elementor-row element.
					elRow = $( el ).closest( '.elementor-container' );
				}
				$( '<span class="vamtam-overlay-element"></span>' ).appendTo( elRow );

				return;
			}
			// Add other type of overlays here.
		} );

		if ( elsThatCauseOverlay.length ) {
			var docClickHandler = function ( e ) {
				elsThatCauseOverlayArr.forEach( function( el ) {
					if ( ! el.isActive ) {
						return;
					}
					// If a click happened,
					// and there is an active overlay,
					// and the click target isn't the overlay target or an element inside it,
					// then call the overlay close handler.
					if ( e.target !== el.overlayTarget && ! el.overlayTarget.contains( e.target ) ) {
						el.closeTrigger.click();
					}
				} );
			};

			document.addEventListener( 'click', docClickHandler, true ); // we need capture phase here.
			window.addEventListener( 'resize', window.VAMTAM.debounce( overlaysResizeHandler, 200 ), false );
		}
	};

	const addScrollbarWidthCSSProp = () => {
		jQuery( 'html' ).css( '--vamtam-scrollbar-width', window.VAMTAM.getScrollbarWidth() + 'px' );
	};

	// Low priority scripts are loaded later
	document.addEventListener('DOMContentLoaded', function () {
		window.VAMTAM.load_script( VAMTAM_FRONT.jspath + 'low-priority.js' );

		vamtamOverlaysHandler();

		addScrollbarWidthCSSProp();
	}, { passive: true } );

})(jQuery);

( function( $, undefined ) {
	"use strict";

	window.VAMTAM = window.VAMTAM || {}; // Namespace
	window.VAMTAM.CUSTOM_ANIMATIONS = {};

	window.VAMTAM.CUSTOM_ANIMATIONS = {
		init: function () {
			// DOM is not ready yet.
		},
		onDomReady: function () {
			this.VamtamCustomAnimations.init();
			this.VamtamCustomAnimations.scrollBasedAnims();
		},
		// Handles custom animations.
		VamtamCustomAnimations: {
			init: function() {
				this.registerAnimations();
				this.utils.watchScrollDirection();
				// this.observedAnims(); // Disabled in favor of elementorFrontend.waypoint().
			},
			registerAnimations: function () {
				var self = this;

				// Register animations here.
				var animations = [
					'stickyHeader', // Same name as function.
				];

				animations.forEach( function( animation ) {
					self[ animation ].apply( self );
				} );
			},
			// A sticky header animation.
			stickyHeader: function () {
				var $target                = $( '.vamtam-sticky-header' ),
					topScrollOffsetTrigger = 10,
					_self                  = this;

				if ( ! $target.length ) {
					return;
				}

				if ( $target.length > 1 ) {
					// There should only be one sticky header.
					$target = $target[ 0 ];
				}

				( function () { // IIFE for closure so $target is available in rAF.
					var prevAnimState,
						isTransparentHeader   = $( $target ).hasClass( 'vamtam-sticky-header--transparent-header' ),
						stickyHeight          = $( $target ).innerHeight();


					// state: fixed, scrolled up (not visible).
					var fixedHiddenState = function () {
						$( $target ).removeClass( 'vamtam-sticky-header--fixed-shown' );
						if ( ! $( $target ).hasClass( 'vamtam-sticky-header--fixed-hidden' ) ) {
							$( $target ).addClass( 'vamtam-sticky-header--fixed-hidden' );
						}
						prevAnimState = 'fixedHiddenState';
					};

					// state: fixed, scrolled down (visible).
					var fixedShownState = function () {
						$( $target ).removeClass( 'vamtam-sticky-header--fixed-hidden' );
						if ( ! $( $target ).hasClass( 'vamtam-sticky-header--fixed-shown' ) ) {
							$( $target ).addClass( 'vamtam-sticky-header--fixed-shown' );
						}
						prevAnimState = 'fixedShownState';
					};

					// state: no animation.
					var noAnimState = function () {
						$( $target ).removeClass( 'vamtam-sticky-header--fixed-shown' );
						$( $target ).removeClass( 'vamtam-sticky-header--fixed-hidden' );
						prevAnimState = 'noAnimState';
					};

					// body-padding normalization.
					const checkBodyPadding = () => {
						const leftBodyPadding  = $( 'body' ).css( 'padding-left' ),
							rightBodyPadding   = $( 'body' ).css( 'padding-right' ),
							$headerEl           = $( $target ).parents( '[data-elementor-type="header"]').first();

						if ( ! $headerEl.length ) {
							return;
						}

						// any body-padding we negate with negative margin.
						// we apply it on the headerEl cause margins on sticky header mess up the width calc.
						if ( parseInt( leftBodyPadding ) ) {
							$headerEl.css( '--vamtam-sticky-mleft', `-${leftBodyPadding}` );
						}
						if ( parseInt( rightBodyPadding ) ) {
							$headerEl.css( '--vamtam-sticky-mright', `-${rightBodyPadding}` );
						}
					};
					checkBodyPadding();

					const headerShouldAnimate = () => {
						// If a link inside the header is being hovered, we don't want to trigger the sticky header.
						if ( $( $target ).find( 'a:hover' ).length ) {
							return false;
						}
						// If a mega-menu belonging to the header is open, we don't want to trigger the sticky header.
						if ( $( '.vamtam-header-mega-menu:visible' ).length ) {
							return false;
						}

						return true;
					};

					// Initial phase

					// If passed the trigger point it should always be at fixed hidden state.
					const initialScrollPosCheck = ( pageLoad = false ) => {
						if ( window.pageYOffset >= topScrollOffsetTrigger ) {
							fixedHiddenState();
						} else if ( ! pageLoad ) {
							// Sometimes the browser's onload scroll comes after the initialScrollPosCheck() so we check on page load jic. Happens mostly when initial scroll pos is after middle of page.
							window.addEventListener( 'load', function() {
								if ( ! prevAnimState ) {
										setTimeout( () => {
											initialScrollPosCheck( true );
										}, 10 );
								}
							} );
						}
					};
					initialScrollPosCheck();

					var scrollTimer = null, lastScrollYPause = window.scrollY, lastDirection; // Used to check if the user has scrolled up far enough to trigger the sticky header.
					window.addEventListener( 'scroll', function( e ) {
						if ( scrollTimer !== null ) {
							clearTimeout( scrollTimer );
						}

						// If the user hasn't scrolled for 500ms we use that as the new Y point.
						scrollTimer = setTimeout( function() {
							lastScrollYPause = window.scrollY;
						}, 500 );

						var anim = window.VAMTAM.debounce( function() {
							if ( e.target.nodeName === '#document' ) {

									if ( ! headerShouldAnimate() ) {

										// Don't animate, but go to fixedShown state for the transparent header.
										if ( isTransparentHeader ) {
											if ( prevAnimState !== 'fixedShownState' ) {
												fixedShownState();
											}
										}

										return;
									}

								var direction =  _self.utils.getScrollDirection();

								if ( lastDirection !== direction ) {
									lastScrollYPause = window.scrollY;
								}
								lastDirection = direction;

								const scrollDifference = Math.abs( window.scrollY - lastScrollYPause ); // Pixels.
								if ( window.scrollY > stickyHeight && scrollDifference < 20 ) {
									return;
								}

								if ( direction === 'up' ) {
									if ( window.pageYOffset >= topScrollOffsetTrigger ) {
										if ( prevAnimState !== 'fixedShownState' ) {
											fixedShownState();
										}
									} else {
										if ( prevAnimState !== 'noAnimState' ) {
											noAnimState();
										}
									}
									return;
								}

								if ( direction === 'down' ) {
									if ( window.pageYOffset >= topScrollOffsetTrigger || isTransparentHeader ) { // Transparent header gets hidden right away.
										// Safe-guard for times when the opening of the cart can cause a scroll down and hide the menu (also sliding the cart upwards).
										var menuCardNotVisible = ! $( $target ).find( '.elementor-menu-cart--shown' ).length;
										if ( prevAnimState !== 'fixedHiddenState' && menuCardNotVisible ) {
											fixedHiddenState();
										}
									}
								}
							}
						}, 25 );

						if ( window.VAMTAM.isMaxDeviceWidth() ) {
							requestAnimationFrame( anim );
						} else if ( prevAnimState !== 'noAnimState' ) {
							noAnimState();
						}
					}, { passive: true } );
				} )();
			},
			// Scroll-based anims.
			scrollBasedAnims: function() {
				var scrollAnims = [
					'[data-settings*="growFromLeftScroll"]',
					'[data-settings*="growFromRightScroll"]',
				];

				var animEls = document.querySelectorAll( scrollAnims.join( ', ' ) );

				if ( ! animEls.length ) {
					return;
				}

				var observer, entries = {}, _this = this;

				var cb = function( iOEntries ) {
					iOEntries.forEach( function( entry ) {
						var currentScrollY       = entry.boundingClientRect.y,
							isInViewport         = entry.isIntersecting,
							observedEl           = entry.target,
							scrollPercentage     = Math.abs( parseFloat( ( entry.intersectionRatio * 100 ).toFixed( 2 ) ) ),
							prevScrollPercentage = entries[ observedEl.dataset.vamtam_anim_id ].lastScrollPercentage,
							lastScrollY          = entries[ observedEl.dataset.vamtam_anim_id ].lastScrollY,
							animateEl            = entries[ observedEl.dataset.vamtam_anim_id ].animateEl;

						var animate = function () {
							window.requestAnimationFrame( function() {
								animateEl.style.setProperty( '--vamtam-scroll-ratio', scrollPercentage + '%' );
							} );
						};

						if ( isInViewport && lastScrollY !== currentScrollY ) {
							if( _this.utils.getScrollDirection() === 'down') {
								if ( prevScrollPercentage < scrollPercentage ) {
									animate();
								}
							} else {
								animate();
							}
						}

						entries[ observedEl.dataset.vamtam_anim_id ].lastScrollY          = currentScrollY;
						entries[ observedEl.dataset.vamtam_anim_id ].lastScrollPercentage = scrollPercentage;
					} );
				};

				var buildThresholdList = function() {
					var thresholds = [],
						numSteps   = 50,
						i;

					for ( i = 1.0; i <= numSteps; i++ ) {
						var ratio = i / numSteps;
						thresholds.push( ratio );
					}

					thresholds.push( 0 );
					return thresholds;
				};

				const thresholds = buildThresholdList();

				animEls.forEach( function( el ) {
					if ( ! observer ) {
						var options = {
							root: null,
							rootMargin: "20% 0% 20% 0%",
							threshold: thresholds,
						};
						observer = new IntersectionObserver( cb, options );
					}

					// Init.
					el.style.setProperty( '--vamtam-scroll-ratio', '1%' );

					var observeEl;
					if ( el.classList.contains( 'elementor-widget' ) || el.classList.contains( 'elementor-column' ) ) {
						// For widgets we observe .elementor-widget-wrap
						// For columns we observe .elementor-row
						observeEl = el.parentElement;
						observeEl.setAttribute('data-vamtam_anim_id', el.dataset.id );
					} else {
						// Sections.
						// Add scroll anim wrapper.
						$( el ).before( '<div class="vamtam-scroll-anim-wrap" data-vamtam_anim_id="' + el.dataset.id + '"></div>' );
						var $wrap = $( el ).prev( '.vamtam-scroll-anim-wrap' );
						$( $wrap ).append( el );
						observeEl = $wrap[ 0 ];
					}

					entries[el.dataset.id] = {
						lastScrollY: '',
						lastScrollPercentage: '',
						observeEl: observeEl,
						animateEl: el,
					};

					observer.observe( observeEl );
				} );
			},
			// Common funcs used in custom animations.
			utils: {
				getAdminBarHeight: function () {
					return window.VAMTAM.adminBarHeight;
				},
				watchScrollDirection: function () {
					var watcher = function () {
						this.lastScrollTop = 0;
						this.utils = this;
						return {
							init: function () {
							},
							measure: function ( cpos ) {
								this.direction = cpos > this.lastScrollTop ? 'down' : 'up';
							}.bind( this ),
							mutate: function ( cpos ) {
								this.utils.getScrollDirection = function () {
									return this.direction;
								};
								this.lastScrollTop = cpos <= 0 ? 0 : cpos; // For Mobile or negative scrolling
							}.bind( this ),
						};
					}.bind( this );

					window.VAMTAM.addScrollHandler( watcher() );
				},
				isTouchDevice: function() {
					const prefixes = ' -webkit- -moz- -o- -ms- '.split( ' ' );

					const mq = function( query ) {
						return window.matchMedia( query ).matches;
					};

					if ( ( 'ontouchstart' in window ) || window.DocumentTouch && document instanceof DocumentTouch ) { // jshint ignore:line
						return true;
					}

					// include the 'heartz' as a way to have a non matching MQ to help terminate the join
					// https://git.io/vznFH
					var query = [ '(', prefixes.join( 'touch-enabled),(' ), 'heartz', ')' ].join( '' );

					return mq( query );
				},
			}
		},
	};

	window.VAMTAM.CUSTOM_ANIMATIONS.init();

	document.addEventListener('DOMContentLoaded', function () {
		window.VAMTAM.CUSTOM_ANIMATIONS.onDomReady();
	}, true );
})( jQuery );
