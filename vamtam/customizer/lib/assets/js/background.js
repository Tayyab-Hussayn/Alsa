(function( $, undefined ) {
	'use strict';

	wp.customize.controlConstructor['vamtam-background'] = wp.customize.Control.extend({
		ready: function() {
			var control = this,
				value = {};

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

			control.value = value;
			control.params.value = value;

			// Shortcut so that we don't have to use _.bind every time we add a callback.
			_.bindAll( control, 'removeFile', 'openFrame', 'select' );

			// Bind events, with delegation to facilitate re-rendering.
			control.container.on( 'click keydown', '.upload-button', control.openFrame );
			control.container.on( 'click keydown', '.thumbnail-image img', control.openFrame );
			control.container.on( 'click keydown', '.remove-button', control.removeFile );

			// Gradient Type / Gradient Position
			VAMTAM_CUSTOMIZER.controls.addSelectsChangeHandlers( this, value );
			// Background Type / Gradient Locations / Gradient Angle
			VAMTAM_CUSTOMIZER.controls.addInputsChangeHandlers( this, value );
			// Color pickers
			control.initializeColorPickers();

			// Background Type / Gradient Type
			control.container.on( 'change', '.background-type input[type="radio"], .background-gradient-type select', function() {
				// Render the control template.
				control.renderContent();
				// Color pickers.
				control.initializeColorPickers();
			} );
		},

		initializeColorPickers: function () {
			// Color pickers.
			VAMTAM_CUSTOMIZER.controls.addColorPickerHandlers( this, this.value );
		},
		/**
		 * Open the media modal.
		 */
		openFrame: function( event ) {
			if ( wp.customize.utils.isKeydownButNotEnterEvent( event ) ) {
				return;
			}

			event.preventDefault();

			if ( ! this.frame ) {
				this.initFrame();
			}

			this.frame.open();
		},

		/**
		 * Create a media modal select frame, and store it so the instance can be reused when needed.
		 */
		initFrame: function() {
			this.frame = wp.media({
				button: {
					text: this.params.button_labels.frame_button
				},
				states: [
					new wp.media.controller.Library({
						title:     this.params.button_labels.frame_title,
						library:   wp.media.query({ type: this.params.mime_type }),
						multiple:  false,
						date:      false
					})
				]
			});

			// When a file is selected, run a callback.
			this.frame.on( 'select', this.select );
		},

		/**
		 * Callback handler for when an attachment is selected in the media modal.
		 * Gets the selected image information, and sets it within the control.
		 */
		select: function() {
			// Get the attachment from the modal frame.
			var attachment = this.frame.state().get( 'selection' ).first().toJSON();

			// Set the Customizer setting; the callback takes care of rendering.
			this.setImage( attachment );
		},

		/**
		 * Called when the "Remove" link is clicked. Empties the setting.
		 *
		 * @param {object} event jQuery Event object
		 */
		removeFile: function( event ) {
			if ( wp.customize.utils.isKeydownButNotEnterEvent( event ) ) {
				return;
			}
			event.preventDefault();

			this.setImage( {} );
		},

		setImage: function( attachment ) {
			this.value[ 'background-image' ] = attachment ? attachment.url : '';
			this.value[ 'background-image-attachment' ] = attachment ? attachment : '';
			VAMTAM_CUSTOMIZER.controls.saveValue( this.value, this );

			this.renderContent(); // Render the control to show the new image

			this.initializeColorPickers();
		},
	});
})( jQuery );