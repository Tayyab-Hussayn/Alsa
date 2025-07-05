/* jshint esnext:true */

import { toggle } from './helpers';

var layout = ( api, $ ) => {
	'use strict';

	api( 'vamtam_theme[full-width-header]', value => {
		value.bind( to => {
			$( '.header-maybe-limit-wrapper' ).toggleClass( 'limit-wrapper', to );
		} );
	} );
};

export default layout;
