// import VamtamRespControls from "./vamtam-resp-controls";

(function( $, undefined ) {
	'use strict';

	wp.customize.controlConstructor['vamtam-responsive-box-fields'] = wp.customize.Control.extend({
		ready: function() {
			var control = this,
			    value   = this.params.value;

			VAMTAM_CUSTOMIZER.controls.addInputsChangeHandlers( this, value );
			VAMTAM_CUSTOMIZER.controls.addUnitHandlers( this, value );
			VAMTAM_CUSTOMIZER.controls.addRespBtnHandlers( this, value );
		},
	});
})( jQuery );