(function($, undefined) {
	'use strict';

	const hasSelectiveRefresh = (
		'undefined' !== typeof wp &&
		wp.customize &&
		wp.customize.selectiveRefresh
	);

	if ( hasSelectiveRefresh ) {
		wp.customize.selectiveRefresh.bind( 'partial-content-rendered', placement => {
			if ( placement.partial.id && placement.partial.id === 'vamtam-custom-css-partial' ) {
				// The current Customizer Selective Refresh implementation
				// cannot replace <style> elements in Chrome
				//
				// As a workaround, create a new <style> element
				// and replace the one inserted by Selective Refresh (partial_el)
				// with the newly created element (new_el)

				const partial_el = placement.container[0];

				const new_el = document.createElement( 'style' );

				new_el.id        = 'front-all-css';
				new_el.innerHTML = partial_el.innerHTML;

				partial_el.id = '';

				partial_el.parentNode.replaceChild( new_el, partial_el );

				// enable UI
				document.body.classList.remove( 'customize-partial-refreshing' );

				// give the browser some time to render the new CSS and trigger a resize event
				setTimeout( () => {
					requestAnimationFrame( () => {
						$( window ).trigger( 'resize' );
					} );
				}, 200 );
			}
		} );
	}

})(jQuery);