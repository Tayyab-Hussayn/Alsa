(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

(function ($, undefined) {
	'use strict';

	var hasSelectiveRefresh = 'undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh;

	if (hasSelectiveRefresh) {
		wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {
			if (placement.partial.id && placement.partial.id === 'vamtam-custom-css-partial') {
				// The current Customizer Selective Refresh implementation
				// cannot replace <style> elements in Chrome
				//
				// As a workaround, create a new <style> element
				// and replace the one inserted by Selective Refresh (partial_el)
				// with the newly created element (new_el)

				var partial_el = placement.container[0];

				var new_el = document.createElement('style');

				new_el.id = 'front-all-css';
				new_el.innerHTML = partial_el.innerHTML;

				partial_el.id = '';

				partial_el.parentNode.replaceChild(new_el, partial_el);

				// enable UI
				document.body.classList.remove('customize-partial-refreshing');

				// give the browser some time to render the new CSS and trigger a resize event
				setTimeout(function () {
					requestAnimationFrame(function () {
						$(window).trigger('resize');
					});
				}, 200);
			}
		});
	}
})(jQuery);

},{}]},{},[1]);
