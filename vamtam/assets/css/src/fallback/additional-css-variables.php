<?php

$overlay_color = get_option( 'carousel_background_color', vamtam_get_option( 'accent-color', 5 ) );

if ( empty( $overlay_color ) ) {
	$overlay_color = '#000000';
}

$border_accent_num = 7;

return array(
	// The current CSS Variables polyfill for IE 11 does not support nested variables
	// However, these are necessary for the live preview,
	// so we use a static color for the live site and a CSS var for the customizer
	'default-bg-color' => '#fff',
	'default-line-color' => 'rgba( var( --vamtam-accent-color-' . $border_accent_num . '-rgb ), 1 )',

	'small-padding' => '20px',

	'horizontal-padding' => '50px',
	'vertical-padding' => '30px',

	'horizontal-padding-large' => '60px',
	'vertical-padding-large' => '60px',

	'no-border-link' => 'none',

	'border-radius' => '0px',
	'border-radius-oval' => '0px',
	'border-radius-small' => '0px',

	'overlay-color' => $overlay_color,
	'overlay-color-hc' => ( new VamtamColor( $overlay_color ) )->luminance > 0.4 ? '#000000' : '#ffffff',

	/** DO NOT CHANGE BELOW */
	 'box-outer-padding' => '60px',
	/** DO NOT CHANGE ABOVE  */
);
