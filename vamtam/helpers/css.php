<?php

/**
 * CSS-related helpers
 *
 * @package vamtam/numerique
 */

/**
 * Map an accent name to its value
 *
 * @param  string      $color           accent name
 * @param  string|bool $deprecated
 * @return string                       hex color or the input string
 */
function vamtam_sanitize_accent( $color, $deprecated = false ) {
	if ( preg_match( '/accent(?:-color-)?(\d)/i', $color, $matches ) ) {
		$num = (int) $matches[1];

		$color = "var( --vamtam-accent-color-{$num} )";
	}

	return $color;
}


