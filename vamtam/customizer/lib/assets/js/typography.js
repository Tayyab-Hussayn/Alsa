/* global VAMTAM_ALL_FONTS */
(function( $, undefined ) {
	'use strict';

	wp.customize.controlConstructor['vamtam-typography'] = wp.customize.Control.extend({
		ready: function() {
			var control               = this,
			    fontFamilySelector    = control.selector + ' .font-family select',
			    fontWeightSelector    = control.selector + ' .font-weight select',
			    value                 = {},
				picker;

			// Make sure everything we're going to need exists.
			_.each( control.params['default'], function( defaultParamValue, param ) {
				if ( false !== defaultParamValue ) {
					value[ param ] = defaultParamValue;
					// We need this to fix some internal value dicrepancies caused by new value types.
					if ( control.params.value[ param ] !== control.setting._value[ param ] ) {
						// The latest correct value is in control.params.value (we provided this on the backend)
						// The _value can possibly hold a deprecated value type.
						if ( control.params.value.hasOwnProperty(param) ) {
							control.setting._value[ param ] = control.params.value[ param ];
						}
					}

					if ( undefined !== control.setting._value[ param ] ) {
						value[ param ] = control.setting._value[ param ];
					}
				}
			});

			_.each( control.setting._value, function( subValue, param ) {
				if ( undefined === value[ param ] || 'undefined' === typeof value[ param ] ) {
					value[ param ] = subValue;
				}
			});

			// Renders and refreshes selectize sub-controls.
			var renderFontWeights = function( fontFamily, exactValue ) {
				var subEl = $( fontWeightSelector );

				var prev_value = exactValue || subEl.val();

				subEl.empty();

				let usedFont;

				// Get all items in the sub-list for the active font-family.
				for ( let fontId in VAMTAM_ALL_FONTS ) {
					const font = VAMTAM_ALL_FONTS[ fontId ];

					usedFont = font;

					// Find the font-family we've selected in the global array of fonts.
					if ( fontFamily === font.family ) {
						for ( let weight of font.weights ) {
							var ignore = isNaN( weight ) && weight !== 'bold' && weight !== 'normal';

							if ( ! ignore ) {
								var option = $( '<option>' );

								option.text( weight );

								subEl.append( option );
							}
						}

						break;
					}
				}

				if ( usedFont ) {
					if ( usedFont.weights.indexOf( prev_value ) > -1 ) {
						subEl.val( prev_value );
					} else {
						subEl.val( usedFont.weights.indexOf( 'normal' ) > -1 ? 'normal' : usedFont.weights[0] );
					}
				}
			};

			$( fontFamilySelector ).val( control.setting._value['font-family'] );
			var templateFunc = function (state) {
				return $( '<span style="font-family:\'' + state.text + '\';">' + state.text + '</span>' );
			};
			// Using select2 (modded as vamtamSelect2 (vamtam-select2.js) ) for the font-family dropdown for same experience across brwosers.
			$( fontFamilySelector ).vamtamSelect2({
				dropdownParent: $( control.selector + ' .font-family' ),
				templateResult: templateFunc,
				templateSelection: templateFunc,
			});

			// Font-family selective font loading.
			VAMTAM_CUSTOMIZER.controls.addSelectiveFontLoadingToSelect2( this, $( fontFamilySelector ) );

			// Render the font weights
			// Please note that when the value of font-family changes,
			// this will be destroyed and re-created.
			renderFontWeights( value['font-family'], value['font-weight'] );

			// Font-family, handle manually.
			this.container.on( 'change', '.font-family select', function() {
				// Add the value to the array and set the setting's value
				var newFamily = $( this ).val();
				if ( ! newFamily ) {
					return;
				}

				value['font-family'] = newFamily;
				control.saveValue( value );
				// Trigger changes to font-weights
				renderFontWeights( newFamily, null );

				//Update selected font option
				$( this ).css( 'font-family', value['font-family'] );
			});

			// Font-weight / Transform / Font-style / Decoration
			VAMTAM_CUSTOMIZER.controls.addSelectsChangeHandlers( this, value );
			// Font-size / Line-height / Letter-spacing
			VAMTAM_CUSTOMIZER.controls.addInputsChangeHandlers( this, value );
			//Units clicks
			VAMTAM_CUSTOMIZER.controls.addUnitHandlers( this, value );
			//Resp btns clicks
			VAMTAM_CUSTOMIZER.controls.addRespBtnHandlers( this, value );

			picker = this.container.find( '.vamtam-color-picker' );

			// Change color
			picker.wpColorPicker({
				change: function() {
					setTimeout( function() {
						// Add the value to the array and set the setting's value
						value.color = picker.val();
						control.saveValue( value );
					}, 100 );
				}
			});

			// This is for the first time (migrating from old typography controls),
			// when object values haven't been applied at all (everything used to be scalar).
			// TODO: We can safely remove this (next 2 lines) after having a theme db with the new value types stored.
			VAMTAM_CUSTOMIZER.controls.updateDummyProp( value );
			control.saveValue( value );
			setTimeout(() => {
				wp.customize.state( 'saved' ).set( true );
			}, 1);
		},

		/**
		 * Saves the value.
		 */
		saveValue: function( value ) {
			var control  = this,
			    newValue = {};

			_.each( value, function( newSubValue, i ) {
				newValue[ i ] = newSubValue;
			});

			control.setting.set( newValue );
		},
	});

	$(document).on('ready', function () {
		// Adapting to preview device changes in the customizer.
		wp.customize.previewedDevice.bind( function( newDevice ) {
			var device = newDevice === 'mobile' ? 'phone' : newDevice;
			$('.resp-btns [data-device=' + device + ']').each( function (i, respBtn) {
				$(respBtn).click();
			})
	   });
	})
})(jQuery);
