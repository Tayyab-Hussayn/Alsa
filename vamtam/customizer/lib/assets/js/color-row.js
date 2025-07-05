wp.customize.controlConstructor['vamtam-color-row'] = wp.customize.Control.extend({

	ready: function() {

		'use strict';
		var control         = this,
			colors          = control.params.choices,
			keys            = Object.keys( colors ),
			value           = this.params.value,
			is_accent_color = this.id == 'vamtam_theme[accent-color]' ? true : false,
			with_hc         = this.params.with_hc;

		if ( is_accent_color && with_hc ) {
			// fire renderWarning() onload
			this.renderWarning( control );
			control.container.find( '.automatic-contrast-colors' ).trigger( 'change' );

			if( typeof value['auto-contrast'] !== 'undefined' && value['auto-contrast'] ) {
				disableHcColorPickers();
			}
		}

		// Proxy function that handles changing the individual colors
		function vamtamColorRowChangeHandler( control, value, subSetting ) {

			var picker = control.container.find( '.vamtam-color-row-index-' + subSetting );
			picker.wpColorPicker({
				change: function() {
					// Color controls require a small delay
					setTimeout( function() {

						if ( is_accent_color && with_hc ) {
							value[ subSetting ] = picker.val();
							// Set the value
							control.setValue( value, false );

							// Trigger the change
							control.container.find( '.vamtam-color-row-index-' + subSetting ).trigger( 'change' );

							// fire renderWarning() onchange
							control.renderWarning( control );

							if ( control.params.value['auto-contrast'] && subSetting.match( /^\d$/ ) ) {
								document.documentElement.style.setProperty(
									`--vamtam-accent-color-${subSetting}-hc`,
									tinycolor.mostReadable( picker.val(), [ '#fff', '#000' ] ).toHexString()
								);
							}
						} else {
							value[ subSetting ] = picker.val();
							// Set the value
							control.setValue( value, false );

							// Trigger the change
							control.container.find( '.vamtam-color-row-index-' + subSetting ).trigger( 'change' );
						}
					}, 100 );
				},
				palettes: false,
			});

		}

		/**
		 * Disable color pickers when auto contrast is on
		 */
		function disableHcColorPickers( ) {
			let container        =  document.querySelector( control.selector );
			let hc_color_pickers = container.querySelectorAll( ".vamtam-color-row-container .vamtam-color-row-single-color-wrapper:nth-child(4)" );

			hc_color_pickers.forEach( function( hc_color_picker ) {
				if ( !hc_color_picker.classList.contains( 'vamtam-disable-color-picker' ) ) {
					hc_color_picker.classList.add( 'vamtam-disable-color-picker' );
				}
			} );

			for ( let i = 1; i <= 8; i++ ) {
				document.documentElement.style.setProperty(
					`--vamtam-accent-color-${i}-hc`,
					tinycolor.mostReadable( value[ i ], [ '#fff', '#000' ] ).toHexString()
				);
			}
		}

		/**
		 * Enable color pickers when auto contrast is off
		 */
		function enableHcColorPickers() {
			let container        =  document.querySelector( control.selector );
			let hc_color_pickers = container.querySelectorAll( ".vamtam-color-row-container .vamtam-color-row-single-color-wrapper:nth-child(4)" );

			hc_color_pickers.forEach( function( hc_color_picker ) {
				if ( hc_color_picker.classList.contains( 'vamtam-disable-color-picker' ) ) {
					hc_color_picker.classList.remove( 'vamtam-disable-color-picker' );
				}
			} );
		}

		function handleAutoContrastField( event, value, auto_contrast_checkbox ) {
			if( auto_contrast_checkbox.checked ) {
				value[ 'auto-contrast' ] = true;
				disableHcColorPickers();
			} else {
				value[ 'auto-contrast' ] = false;
				enableHcColorPickers();
			}

			control.setValue( value, false );

			control.renderWarning( control );
		}

		// The hidden field that keeps the data saved (though we never update it)
		this.settingField = this.container.find( '[data-customize-setting-link]' ).first();

		// Colors loop
		for ( var i = 0; i < Object.keys( colors ).length; i++ ) {
			if( keys[ i ] == 'auto-contrast' ) {
				const auto_contrast_id = this.id + "-auto-contrast";
				const auto_contrast_checkbox = document.getElementById( auto_contrast_id );
				auto_contrast_checkbox.addEventListener('change', (event) => handleAutoContrastField( event, value, auto_contrast_checkbox ) );

			} else {
				vamtamColorRowChangeHandler( this, value, keys[ i ] );
			}
		}

	},

	/**
	 * Set a new value for the setting
	 *
	 * @param newValue Object
	 * @param refresh If we want to refresh the previewer or not
	 */
	setValue: function( value, refresh ) {

		'use strict';

		var control  = this,
		    newValue = {};

		_.each( value, function( newSubValue, i ) {
			newValue[ i ] = newSubValue;
		});

		//newValue[ 'auto-contrast' ] = false;

		control.setting.set( newValue );

		if ( refresh ) {

			// Trigger the change event on the hidden field so
			// previewer refresh the website on Customizer
			control.settingField.trigger( 'change' );

		}

	},

	/**
	 *
	 * Manager for color contrast warning block
	 * @param template
	 * @param node
	 */
	renderWarning: function ( control ) {
		let colors = control.params.value;
		let warnings = control.getWarnings( colors );
		control.colorWarningIconManager( control, warnings );
		control.registerTooltipEventListener( control );
	},

	/**
	 *
	 * Register event for warning message tooltips
	 * @param control
	 */
	registerTooltipEventListener: function( control ) {
		control.clearTooltipEventListener( control );
		let warningElements = document.getElementsByClassName("dashicons-warning");

		for ( let i = 0; i < warningElements.length; i++ ) {
			let warningElement = warningElements[i];
			warningElement.addEventListener("mouseover", control.ttTurnOn.bind( control ) , false);
			warningElement.addEventListener("mouseout", control.ttTurnOff , false);
		}
	},

	/**
	 *
	 * Clear all event listeners for the warning icon elements
	 * @param control
	 */
	clearTooltipEventListener: function( control ) {

		for ( let i = 1; i <= 8; i++ ) {
			let iconElement = document.getElementById('color-row-warning-'+ i);
			iconElement.removeEventListener("mouseover", control.ttTurnOn.bind( control ) , false);
			iconElement.removeEventListener("mouseout", control.ttTurnOff , false);
		}
	},

	/**
	 *
	 * Turn on tooltip
	 */
	ttTurnOn: function( evt ) {
		const existing_tooltip = document.getElementById('vamtam-warning-tooltip' );
		if ( existing_tooltip ) {
			existing_tooltip.parentNode.removeChild( existing_tooltip );
		}

		const message = this.params.value['auto-contrast'] ? vamtamColorRowObj.tooltip_msg_auto : vamtamColorRowObj.tooltip_msg;

		const tooltip_element = document.createElement('div');
		tooltip_element.id = 'vamtam-warning-tooltip';
		const tooltip_inner_html = `<div class="desc">
										<p>${message}</p>
									</div>
									<div class="info">
										<div class="level"> <span class="red">A</span>AA</div>
										<div class="read-more"><a target="_blank" href="https://www.w3.org/WAI/WCAG21/quickref/?versions=2.0#distinguishable">Read more</a></div>
									</div>
									<i></i>`;
		tooltip_element.innerHTML = tooltip_inner_html;
		const container_element = evt.target.closest( '.vamtam-color-row-container' );
		container_element.appendChild( tooltip_element );
	},

	/**
	 *
	 * Turn off tooltip
	 */
	ttTurnOff: ( ( evt ) => {
		let container_element = evt.target.closest( '.vamtam-color-row-container' );

		setTimeout(function() {
			let tooltip_element = document.getElementById("vamtam-warning-tooltip");
			if( tooltip_element && container_element.contains( tooltip_element ) ) {
				container_element.removeChild( tooltip_element );
			}
		}, 1000 );
	}),


	/**
	 *
	 * Return low contrast warning items obj
	 * @param colors
	 * @returns {{aaa: Array, aa: Array}}
	 */
	getWarnings: function ( colors ) {
		let wcag2_aaa = { level: "AAA", size: "small" };
		let wcag2_aa  = { level: "AA", size: "small" };

		let aaa_warnings = [];
		let aa_warnings  = [];

		for ( let i = 1; i <= 8; i ++ ) {
			let accent_color = colors[i];

			let hc_color = colors[i + '-hc'];

			if ( colors['auto-contrast'] ) {
				hc_color = tinycolor.mostReadable( accent_color, [ '#fff', '#000' ] ).toHexString();
			}

			if ( ! tinycolor.isReadable( accent_color, hc_color, wcag2_aa ) ) {
				aa_warnings.push( i );
			} else if ( ! tinycolor.isReadable( accent_color, hc_color, wcag2_aaa ) ) {
				aaa_warnings.push( i );
			}
		}

		let warnings = {
			"aaa" : aaa_warnings,
			"aa"  : aa_warnings
		}

		return warnings;
	},

	/**
	 *
	 * Will manage the warning icons visibility next to the color pickers.
	 * @param control
	 * @param warnings
	 */
	colorWarningIconManager: function( control, warnings ) {
		control.clearIcons( control );

		if ( warnings.aaa && warnings.aaa.length ) {
			control.addIcons( warnings.aaa, 'aaa' );
		}

		if ( warnings.aa && warnings.aa.length ) {
			control.addIcons( warnings.aa, 'aa' );
		}
	},

	/**
	 *
	 * @param warnings
	 * @param warning_class_suffix
	 */
	addIcons: function( warnings, warning_class_suffix ) {
		let warning_class = 'contrast-warning-' + warning_class_suffix;
		if ( warnings || 0 !== warnings.length ) {

			for ( let i = 0; i < warnings.length; i ++ ) {

				let warning_element_id = 'color-row-warning-' + warnings[i];
				let warning_element    = document.getElementById( warning_element_id );

				if ( warning_element.classList.contains( 'dashicons-yes-alt' ) ) {
					warning_element.classList.remove( 'dashicons-yes-alt' );
				}

				warning_element.classList.add( 'dashicons-warning' );
				warning_element.classList.add( warning_class );
			}
		}

	},


	/**
	 *
	 * Clear .contrast-warning class from all color item
	 * @param control
	 */
	clearIcons: function( control ) {
		let color_row_el = document.getElementById( control.container[0].id );
		let warning_items = color_row_el.querySelectorAll( '.vamtam-color-row-container .color-row-warning .dashicons' );

		for ( let i = 0; i < warning_items.length; i ++ ) {

			if ( warning_items[i].classList.contains( 'dashicons-warning' ) ) {
				warning_items[i].classList.remove( 'dashicons-warning' );
			}

			if ( warning_items[i].classList.contains( 'contrast-warning-aaa' ) ) {
				warning_items[i].classList.remove( 'contrast-warning-aaa' );
			}

			if ( warning_items[i].classList.contains( 'contrast-warning-aa' ) ) {
				warning_items[i].classList.remove( 'contrast-warning-aa' );
			}

			if ( !warning_items[i].classList.contains( 'dashicons-yes-alt' ) ) {
				warning_items[i].classList.add( 'dashicons-yes-alt' );
			}
		}

	}


});