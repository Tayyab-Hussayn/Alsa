/* jshint esnext:true */

import { toggle } from './helpers';

var general = ( api, $ ) => {
	'use strict';

	api( 'vamtam_theme[show-splash-screen]', value => {
		value.bind( to => {
			if ( + to ) {
				$( 'body' ).triggerHandler( 'vamtam-preview-splash-screen' );
			}
		} );
	} );

	api( 'vamtam_theme[splash-screen-logo]', value => {
		value.bind( to => {
			var wrapper = $( '.vamtam-splash-screen-progress-wrapper' );
			var current_image = wrapper.find( '> img' );

			if ( current_image.length === 0 ) {
				current_image = $('<img />');
				wrapper.prepend( current_image );
			}

			current_image.attr( 'src', to );

			$( 'body' ).triggerHandler( 'vamtam-preview-splash-screen' );
		} );
	} );

	api( 'vamtam_theme[show-scroll-to-top]', value => {
		value.bind( to => {
			toggle( $( '#scroll-to-top' ), to );
		} );
	} );
};

export default general;