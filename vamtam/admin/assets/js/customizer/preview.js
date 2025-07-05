/* jshint esnext:true */

import general from './general';
import layout from './layout';
import styles from './styles';

(function($, undefined) {
	'use strict';

	general( wp.customize, $ );
	layout( wp.customize, $ );
	styles( wp.customize, $ );

})(jQuery);