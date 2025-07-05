(function($, v, undefined) {
	"use strict";

	var menu_toggle     = document.getElementById( 'vamtam-fallback-main-menu-toggle' );
	var original_toggle = document.querySelector( '#main-menu > .mega-menu-wrap > .mega-menu-toggle' );
	var main_menu       = document.querySelector( '#main-menu' );

	// main menu custom toggle

	if ( menu_toggle ) {
		menu_toggle.addEventListener( 'click', function( e ) {
			e.preventDefault();

			requestAnimationFrame( function() {
				var is_open = ( original_toggle || menu_toggle ).classList.contains( 'mega-menu-open' );

				menu_toggle.classList.toggle( 'mega-menu-open', ! is_open );

				( original_toggle || main_menu ).classList.toggle( 'mega-menu-open', ! is_open );

				var topBar = document.getElementById( 'mobile-top-bar-above' );
				if ( topBar ) {
					topBar.style.display = is_open ? 'block' : 'none';
				}
			} );
		} );
	}


	// add left/right classes to submenus depending on resolution

	var allSubMenus = $( '#main-menu .sub-menu' );

	if ( allSubMenus.length ) {
		var invertPositionCallback = window.VAMTAM.debounce( function() {
			requestAnimationFrame( function() {
				var winWidth = window.innerWidth;

				allSubMenus.show().removeClass( 'invert-position' ).each( function() {
					if ( $( this ).offset().left + $( this ).width() > winWidth - 50 ) {
						$( this ).addClass( 'invert-position' );
					}
				} );

				allSubMenus.css( 'display', '' );
			} );
		}, 100 );

		invertPositionCallback();
		window.addEventListener( 'resize', invertPositionCallback, false );
	}

	// open submenus on click, only on mobile, when Max Mega Menu plugin is disabled
	if ( main_menu && main_menu.classList.contains('vamtam-basic-menu') ) {
		// var allSubMenusResponsive = $(main_menu).find('.sub-menu');
		var allMenusResponsive = $(main_menu).find('.menu-item > a');

		allMenusResponsive.on( 'click', function( event ) {
			if (!main_menu.classList.contains('mega-menu-open')) {
				return;
			}

			var menuItem = this.parentElement;

			if ( ! menuItem.classList.contains( 'menu-item-has-children' ) ) {
				return;
			} else {
				if ( this.classList.contains( 'menu-item-on' ) ) {
					if ( event.currentTarget === this && event.target !== event.currentTarget ) {
						// The click is prob on the menu toggle (vamtam-toggle-hit-target)
						// so just close the menu
						event.preventDefault();
						this.classList.remove( 'menu-item-on' );
						const submenu = $( menuItem ).find( '.sub-menu' ).first();
						submenu.attr( 'style', 'display: none !important;' );
					}
					return;
				}
			}

			event.preventDefault();

			var submenu = $( menuItem ).find( '.sub-menu' ).first();
			// allSubMenusResponsive.attr( 'style', '' );
			submenu.attr( 'style', 'display: block !important;' );
			// allMenusResponsive.not( this ).removeClass( 'menu-item-on' );
			this.classList.add( 'menu-item-on' );

			if ( ! $( this ).find( '.vamtam-toggle-hit-target' ).length ) {
				$( this ).append( `<span class="vamtam-toggle-hit-target"></span>`);
			}
		});
	}

	// adds .current-menu-item classes

	var hashes = [
		// ['top', $('<div></div>'), $('#top')]
	];

	$('#main-menu').find('.mega-menu, .menu').find('.maybe-current-menu-item, .current-menu-item').each(function() {
		var link = $('> a', this);

		if(link.prop('href').indexOf('#') > -1) {
			var link_hash = link.prop('href').split('#')[1];

			if('#'+link_hash !== window.location.hash) {
				$(this).removeClass('mega-current-menu-item current-menu-item');
			}

			hashes.push([link_hash, $(this), $('#'+link_hash)]);
		}
	});

	if ( hashes.length ) {
		var winHeight = 0;
		var documentHeight = 0;

		var prev_upmost_data = null;

		v.addScrollHandler( {
			init: function() {},
			add_current_menu_item: function( hash ) {
				// there may be more than one links with the same hash,
				// so we need to loop over all of the hashes

				for ( var i = 0; i < hashes.length; i++ ) {
					if ( hashes[i][0] === hash ) {
						hashes[i][1][0].classList.add( 'current-menu-item' );
					}
				}
			},
			measure: function( cpos ) {
				winHeight      = window.innerHeight;
				documentHeight = document.body.offsetHeight;

				this.upmost = Infinity;
				this.upmost_data = null;

				for ( var i = 0; i < hashes.length; i++ ) {
					var el = hashes[i][2];

					if ( el.length ) {
						var top = el.offset().top + 10;

						if (
							top > cpos &&
							top < this.upmost &&
							(
								top < cpos + winHeight / 2 ||
								( top < cpos + winHeight && cpos + winHeight === documentHeight )
							)
						) {
							this.upmost_data = hashes[i];
							this.upmost      = top;
						}
					}
				}
			},
			mutate: function( cpos ) {
				for ( var i = 0; i < hashes.length; i++ ) {
					if ( hashes[i][2].length ) {
						hashes[i][1][0].classList.remove( 'current-menu-item' );
						hashes[i][1][0].childNodes[0].blur();
					}
				}

				if ( this.upmost_data ) {
					this.add_current_menu_item( this.upmost_data[0] );

					// attempt to push a state to the history if the current hash is different from the previous one
					if ( 'history' in window && ( prev_upmost_data !== null ? prev_upmost_data[0] : '' ) !== this.upmost_data[0] ) {
						window.history.pushState(
							this.upmost_data[0],
							$( '> a', this.upmost_data[1] ).text(),
							( cpos !== 0 ? '#' + this.upmost_data[0] : location.href.replace( location.hash, '' ) )
						);

						prev_upmost_data = $.extend({}, this.upmost_data);
					}
				} else if ( this.upmost_data === null && prev_upmost_data !== null ) {
					this.add_current_menu_item( prev_upmost_data[0] );
				}
			}
		} );
	}

	window.addEventListener( 'load', function() {
		if ( document.querySelector( '.loop-wrapper.regular' ) ) {
			var msnry = new Masonry( '.loop-wrapper.regular', {
				itemSelector: '.list-item',
				gutter: 45,
				initLayout: false,
			});

			msnry.on( 'layoutComplete', function() {
				document.querySelector( '.loop-wrapper.regular' ).classList.add( 'masonry-loaded' );
			});

			msnry.layout();
		}
	});

})( jQuery, window.VAMTAM );
