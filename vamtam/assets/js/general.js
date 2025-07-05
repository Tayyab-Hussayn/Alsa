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
