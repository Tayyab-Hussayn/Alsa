<?php

/**
 * Basic wrappers around WP core functions
 *
 * This file is loaded early by the theme
 *
 * @package vamtam/numerique
 */

/**
 * get_option wrapper
 *
 * @param  string $name option   name
 * @param  mixed  $default       default value
 * @param  bool   $stripslashes  whether to filter the result with stripslashes()
 *
 * @return mixed                 option value
 */

function vamtam_get_option( $name, $sub = null ) {
	global $vamtam_theme, $vamtam_defaults;

	$option = $vamtam_theme[ $name ] ?? ( $vamtam_defaults[ $name ] ?? '' );

	if ( ! is_null( $sub ) && is_array( $option ) ) {
		$option = $option[ $sub ];
	}

	if ( is_string( $option ) ) {
		if ( $option === 'true' ) {
			$option = true;
		} elseif ( $option === 'false' ) {
			$option = false;
		}
	}

	return apply_filters( 'vamtam_get_option', $option, $name, $sub );
}

/**
 * Same as vamtam_get_option, but converts '1' and '0' to booleans
 *
 * @uses   vamtam_get_option()
 *
 * @param  string $name option   name
 * @param  mixed  $default       default value
 * @param  bool   $stripslashes  whether to filter the result with stripslashes()
 *
 * @return mixed                 option value
 */
function vamtam_get_optionb( $name, $sub = null ) {
	$value = vamtam_get_option( $name, $sub );

	if ( $value === '1' || $value === 'true' ) {
		return true;
	}

	if ( $value === '0' || $value === 'false' ) {
		return false;
	}

	return is_bool( $value ) ? $value : false;
}

/**
 * Converts '1', '0', 'true' and 'false' to booleans, otherwise returns $value
 * @param  mixed $value original value
 * @return mixed        sanitized value
 */
function vamtam_sanitize_bool( $value ) {
	if ( $value === '1' || $value === 'true' ) {
		return true;
	}

	if ( $value === '0' || $value === 'false' ) {
		return false;
	}

	return $value;
}
