/* jshint esnext:true */

(function($, undefined) {
	'use strict';

	// Funcs shared across common vamtam controls. Common conventions for the html apply.
	// Check Vamtam_Customize_Typography_Control for html structure.
	window.VAMTAM_CUSTOMIZER = {
		controls: {
			// Adds change handlers for inputs (based on UI type).
			addInputsChangeHandlers: function (control, value) {
				var _self = this;
				control.container.on( 'change', '.resp-control input, .base-control.input-control input', function() {
				// Add the value to the array and set the setting's value
				var device = $(this).closest('.values').siblings('.options').find('.resp-btns .active').attr('data-device');
				var isResposnsive = device ? true : false;
				var val = $(this).attr('data-value');
				var uiType = $(this).closest('.values').attr('data-type');
				var newVal = $(this).val();
				// Check UI type
				switch (uiType) {
					case 'slider':
						// Numeric
						if ( val.includes( '.' ) ) {
							// val is an object path.
							_self.set( value, val, Number( newVal ) );
							_self.updateDummyProp(value);
						} else if ( isResposnsive ) {
							value[val][device] = Number( newVal );
							_self.updateDummyProp(value);
						} else {
							value[val] = Number( newVal );
						}
						// Update sibling
						$(this).siblings('input').val( newVal );
						break;
					case 'box-fields':
						// Numeric
						if ( val.includes( '.' ) ) {
							// val is an object path.
							_self.set( value, val, Number( newVal ) );
							_self.updateDummyProp(value);
						} else if ( isResposnsive ) {
							value[val][device] = Number( newVal );
							_self.updateDummyProp(value);
						} else {
							value[val] = Number( newVal );
						}
						break;
					default:
						// Most common case is plain text.
						if ( val.includes( '.' ) ) {
							// val is an object path.
							_self.set( value, val, newVal );
							_self.updateDummyProp(value);
						} else if ( isResposnsive ) {
							value[val][device] = newVal;
							_self.updateDummyProp(value);
						} else {
							value[val] = newVal;
						}
						break;
				}

				_self.saveValue( value, control );
				});
			},
			// Units clicks
			addUnitHandlers: function (control, value) {
				var _self = this;
				control.container.on( 'click', '.units [data-unit]', function(event, causesUpdate = true) {
					$(this).addClass('active');
					$(this).siblings().removeClass('active');
					var options = $(this).closest('.options');
					var values = $(options).siblings('.values');
					var device = $(options).find('.resp-btns .active').attr('data-device');
					var ui_type = $(values).attr('data-type');
					var val, targets;

					// Check UI type
					switch ($(values).attr('data-type')) {
					// f.e. case 'slider' || 'box-fields':
					default:
						// Most common case is the input element.
						targets = $(values).find('input');
						break;
					}

					$(targets).each( function (i, target) {
						val = $(target).attr('data-value');
					} )

					if ( ! causesUpdate ) {
						return;
					}

					// Check UI type
					switch (ui_type) {
						// Add the value to the array and set the setting's value
						case 'box-fields':
							value['unit'][device] = $( this ).attr('data-unit');
							break;
						default:
							value[val]['unit'][device] = $( this ).attr('data-unit');;
							break;
					}
					_self.updateDummyProp(value);
					_self.saveValue( value, control );
				});
			},
			// Resp btns clicks
			addRespBtnHandlers: function (control, value) {
				control.container.on( 'click', '.resp-btns [data-device]', function() {
					$(this).addClass('active');
					$(this).siblings().removeClass('active');
					var device = $(this).attr('data-device');
					var options = $(this).closest('.options');
					var values = $(options).siblings('.values');
					var ui_type = $(values).attr('data-type');

					var val, targets, unitVal, unitForDevice;

					// Check UI type
					switch (ui_type) {
					// f.e. case 'slider' || 'box-fields':
					default:
						// Most common case is the input element.
						targets = $(values).find('input');
						break;
					}

					$(targets).each( function ( i, target ) {
						val = $(target).attr('data-value');
						// Update field value for device.
						$(target).val(value[val][device]);
					});

					// Check UI type
					switch (ui_type) {
					case 'box-fields':
						unitVal = value['unit'][device];
						break;
					default:
						unitVal = value[val]['unit'][device];
						break;
					}

					// Update units for device
					unitForDevice = $(options).find('.units [data-unit="' + unitVal + '"]');
					$(unitForDevice).trigger('click', [ false ]);
				});
			},
			// Object value types dont trigger customizer changes cause of shallow equality check.
			// We use a dummy prop to trick the customizer (works better than new Object refs).
			updateDummyProp: function (value) {
				value['customizer-dummy'] = value['customizer-dummy'] ? ! value['customizer-dummy'] : true;
			},
			// Adds change handlers for selects.
			addSelectsChangeHandlers: function (control, value) {
				var _self = this;
				control.container.on( 'change', '.base-control.select-control .values select', function() {
					// Add the value to the array and set the setting's value
					var val = $(this).attr('data-value');
					var newVal = $(this).val();
					if ( val.includes( '.' ) ) {
						// val is an object path.
						_self.set( value, val, newVal )
						_self.updateDummyProp(value);
					} else {
						value[val] = newVal;
					}

					_self.saveValue( value, control );
				});
			},
			// Adds change handlers for color picker (needs wpColorPicker).
			addColorPickerHandlers: function (control, value) {
				var _self = this;
				var $pickers = control.container.find( 'resp-control .vamtam-color-picker, .base-control.color-picker-control .vamtam-color-picker' );
				// Bind color change.
				$pickers.each( function (i, picker) {
					var val = $(this).attr('data-value');
					var device = $(this).closest('.values').siblings('.options').find('.resp-btns .active').attr('data-device');
					var isResposnsive = device ? true : false;
					$(picker).wpColorPicker({
						change: function() {
							setTimeout( function() {
								// Add the value to the array and set the setting's value
								var newColor = $(picker).val();
								if ( val.includes( '.' ) ) {
									// val is an object path.
									_self.set( value, val, newColor )
									_self.updateDummyProp(value);
								} else if ( isResposnsive ) {
									value[val][device] = newColor;
									_self.updateDummyProp(value);
								} else {
									value[val] = newColor;
								}

								_self.saveValue(value, control);
							}, 100 );
						}
					});
				} )
			},
			// Adds scroll based font loading to a select2 instance.
			addSelectiveFontLoadingToSelect2: function (control, select2) {
				var enqueueFontsInView = function() {
					const containerOffset = control.$previewContainer.offset(),
						  top             = containerOffset.top,
						  bottom          = top + control.$previewContainer.innerHeight(),
						  fontsInView     = [];

					control.$previewContainer.children().find( 'li:visible' ).each( function( index, font ) {
						const $font = $( font ),
							  offset = $font.offset();
						if ( offset && offset.top > top && offset.top < bottom ) {
							fontsInView.push( $font );
						}
				  	} );

				  	fontsInView.forEach( function( font ) {
						const fontFamily = $( font ).find( 'span' ).html();
						control.enqueueFont( fontFamily );
				  	} );
				};

				var enqueueFont = function( font ) {
					if ( -1 !== control.enqueuedFonts.indexOf( font ) ) {
						return;
					}

					let fontUrl;

					if ( VAMTAM_ALL_FONTS[ font ] && VAMTAM_ALL_FONTS[ font ].gf ) {
						fontUrl = encodeURI( 'https://fonts.googleapis.com/css?family=' + font + '&text=' + font );
					}

					if ( ! _.isEmpty( fontUrl ) ) {
						$( 'head' ).find( 'link:last' ).after( '<link href="' + fontUrl + '" rel="stylesheet" type="text/css">' );
					}

					control.enqueuedFonts.push( font );
				};

				var typeStopDetection = {
				  idle: 350,
				  timeOut: null,
				  action() {
					const self = this.typeStopDetection;
					clearTimeout( self.timeOut );
					self.timeOut = setTimeout( function() {
					  control.enqueueFontsInView();
					}, self.idle );
				  },
				};

				var scrollStopDetection = {
				  idle: 350,
				  timeOut: null,
				  onScroll() {
					const self = this.scrollStopDetection;
					clearTimeout( self.timeOut );
					self.timeOut = setTimeout( function() {
					  control.enqueueFontsInView();
					}, self.idle );
				  },
				};

				control.enqueueFontsInView  = enqueueFontsInView.bind( control );
				control.enqueueFont         = enqueueFont.bind( control );
				control.typeStopDetection   = typeStopDetection;
				control.scrollStopDetection = scrollStopDetection;

				control.enqueuedFonts       = [],
				control.$previewContainer   = null;

				// Load selected font.
				control.enqueueFont( $( control.selector ).find( '.select2-selection__rendered[role="textbox"] > span' ).text() );

				select2.on( 'select2:open', function() {
					control.$previewContainer = $( control.selector ).find( '.select2-results__options[role="listbox"]:visible' );
					// Load initial.
					setTimeout( function() {
						control.enqueueFontsInView();
					}, 100 );

					// On search
					$( control.selector ).find( 'input.select2-search__field:visible' ).on( 'keyup', function() {
						control.typeStopDetection.action.apply( control );
					} );

					// On scroll
					control.$previewContainer.on( 'scroll', function() {
						control.scrollStopDetection.onScroll.apply( control );
					} );
				} );
			},
			// Saves the value.
			saveValue: function( value, control ) {
				var	newValue = {};

				_.each( value, function( newSubValue, i ) {
					newValue[ i ] = newSubValue;
				});

				control.setting.set( newValue );
			},
			// Sets an object path-based value. (like lodash's _set())
			set: function( obj, path, value ) {
				if (Object(obj) !== obj) return obj; // When obj is not an object
				// If not yet an array, get the keys from the string-path
				if (!Array.isArray(path)) path = path.toString().match(/[^.[\]]+/g) || [];
				path.slice(0,-1).reduce((a, c, i) => // Iterate all of them except the last one
					 Object(a[c]) === a[c] // Does the key exist and is its value an object?
						 // Yes: then follow that path
						 ? a[c]
						 // No: create the key. Is the next key a potential array-index?
						 : a[c] = Math.abs(path[i+1])>>0 === +path[i+1]
							   ? [] // Yes: assign a new array object
							   : {}, // No: assign a new plain object
					 obj)[path[path.length-1]] = value; // Finally assign the value to the last key
				return obj; // Return the top-level object to allow chaining
			},
		},
	}

	var api = wp.customize;

	// toggle visibility of some controls based on a setting's value
	// @see wp-admin/js/customize-controls.js
	$.each({
		'vamtam_theme[header-logo-type]': [
			{
				controls: [ 'vamtam_theme[custom-header-logo]' ],
				callback: function( to ) { return 'image' === to; }
			},
		],

	}, ( settingId, conditions ) => {
		api( settingId, setting => {
			$.each( conditions, ( cndi, o ) => {
				$.each( o.controls, ( i, controlId ) => {
					api.control( controlId, ( control ) => {
						var visibility = ( to ) => {
							control.container.toggle( o.callback( to ) );
						};

						visibility( setting.get() );
						setting.bind( visibility );
					});
				});
			} );
		});
	});
})(jQuery);