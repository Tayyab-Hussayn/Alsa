<?php

/**
 * LESSPHP wrapper
 *
 * @package vamtam/numerique
 */

/**
 * class VamtamLessBridge
 */
class VamtamLessBridge {

	/**
	 * List of option names which are known to be percentages
	 *
	 * @var array
	 */
	public static $percentages = array(
		'left-sidebar-width',
		'right-sidebar-width',
	);

	/**
	 * List of option names which are known to be numbers
	 *
	 * @var array
	 */
	public static $numbers = array(
	);

	public static function prepare_vars_for_export( $vars_raw, $with_additions = true ) {
		global $wpdb, $vamtam_defaults;

		$vars_raw = self::flatten_vars( apply_filters( 'vamtam_less_vars', $vars_raw ) );

		$vars = array();

		foreach ( $vars_raw as $name => $value ) {
			if ( is_null( $value ) ) {
				continue;
			}

			if ( preg_match( '/-background-image-attachment-/', $name ) ) {
				unset( $vars[ $name ] );
				continue;
			}
			if ( trim( $value ) === '' && preg_match( '/\bbackground-image\b/i', $name ) ) {
				$vars[ $name ] = '';
				continue;
			}

			if ( preg_match( '/^[-\w\d]+$/i', $name ) ) {
				$vars[ $name ] = self::prepare( $name, $value );
			}
		}

		if ( $with_additions ) {
			$vars = array_merge(
				$vars,
				apply_filters( 'vamtam-additional-css-variables', include( VAMTAM_FB_CSS_DIR . 'additional-css-variables.php' ) )
			);
		}

		// -----------------------------------------------------------------------------
		$out = array();

		foreach ( $vars as $name => $value_raw ) {
			$value = $value_raw;

			if ( ! $value_raw && ! is_numeric( $value_raw ) ) {
				$value = '';

				if ( strpos( $name, 'background-attachment' ) !== false ) {
					$value = 'scroll';
				} elseif ( strpos( $name, 'background-position' ) !== false ) {
					$value = 'left top';
				} elseif ( strpos( $name, 'background-repeat' ) !== false ) {
					$value = 'no-repeat';
				} elseif ( strpos( $name, 'background-size' ) !== false ) {
					$value = 'auto';
				}
			}

			if ( ! is_null( $value_raw ) && $value_raw !== '' ) {
				if ( preg_match( '/-background-image$/', $name ) ) {
					$out[ $name ] = $value === '' ? 'none' : "url({$value})";
				} elseif ( preg_match( '/-(desktop|tablet|phone)$/', $name ) ) {
					if ( is_numeric( $value ) ) {
						// If it's not unitless, there should be a relevant prop with the unit.
						// Simple naming conventions apply here.
						if ( preg_match( '/padding|margin/', $name ) ) {
							// These got one unit for multiple values (dimensions).
							$unit_prop_name = str_replace(
								[ '-top-', '-right-', '-bottom-', '-left-' ],
								[ '-unit-', '-unit-', '-unit-', '-unit-' ],
								$name );
						} else {
							$unit_prop_name = str_replace(
							[ 'desktop', 'tablet', 'phone' ],
							[ 'unit-desktop', 'unit-tablet', 'unit-phone' ] ,
							$name );
						}

						if ( isset( $vars[ $unit_prop_name ] ) ) {
							$out[ $name ] = $value . $vars[ $unit_prop_name ];
						} else {
							//Is unitless.
							$out[ $name ] = $value;
						}
					}
				} elseif ( ! preg_match( '/-(variant|font-size|line-height|letter-spacing)$/', $name ) ) {
					$out[ $name ] = $value;
				}
			}
		}

		// Cleanup phase
		// One more loop but we dont have to depend on prop order.
		foreach ($out as $name => $value) {
			// Background values cleanup.
			if ( preg_match( '/-background-type$/', $name ) ) {
				if ( $out[ $name ] === 'gradient' ) {
					// We dont need the default props.
					unset( $out[ str_replace( 'type', 'image', $name ) ] );
					unset( $out[ str_replace( 'type', 'color', $name ) ] );
					unset( $out[ str_replace( 'type', 'repeat', $name ) ] );
					unset( $out[ str_replace( 'type', 'attachment', $name ) ] );
					unset( $out[ str_replace( 'type', 'size', $name ) ] );
					unset( $out[ str_replace( 'type', 'position', $name ) ] );

					// Generate gradient string from props.
					$type       = $out[ str_replace( 'type', 'gradient-type', $name ) ];
					$angle      = $out[ str_replace( 'type', 'gradient-angle', $name ) ];
					$position   = $out[ str_replace( 'type', 'gradient-position', $name ) ];
					$color_1    = $out[ str_replace( 'type', 'gradient-color-1', $name ) ];
					$color_2    = $out[ str_replace( 'type', 'gradient-color-2', $name ) ];
					$location_1 = $out[ str_replace( 'type', 'gradient-location-1', $name ) ];
					$location_2 = $out[ str_replace( 'type', 'gradient-location-2', $name ) ];
					$generated  =  $type . '-gradient(' .
								( $type === 'linear' ? $angle . 'deg' : 'at ' . $position ) . ', ' .
								$color_1 . ' ' . $location_1 . '%, ' .
								$color_2 . ' ' . $location_2 . '%)';
					$out[ str_replace( 'type', 'generated', $name ) ] = $generated;

					// We generated the gradient string, we dont need the gradient props anymore.
					unset( $out[ str_replace( 'type', 'gradient-color-1', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-color-2', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-location-1', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-location-2', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-type', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-angle', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-position', $name ) ] );

				} elseif ( $out[ $name ] === 'default' ) {
					// We dont want the gradient props.
					unset( $out[ str_replace( 'type', 'gradient-color-1', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-color-2', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-location-1', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-location-2', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-type', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-angle', $name ) ] );
					unset( $out[ str_replace( 'type', 'gradient-position', $name ) ] );

					// Generate bg string from props.
					$image      = isset( $out[ str_replace( 'type', 'image', $name ) ] ) ? $out[ str_replace( 'type', 'image', $name ) ] : 'none';
					$color      = $out[ str_replace( 'type', 'color', $name ) ];
					$repeat     = $out[ str_replace( 'type', 'repeat', $name ) ];
					$attachment = $out[ str_replace( 'type', 'attachment', $name ) ];
					$size       = $out[ str_replace( 'type', 'size', $name ) ];
					$position   = $out[ str_replace( 'type', 'position', $name ) ];

					$generated = "$image $position / $size $repeat $attachment $color";
					$out[ str_replace( 'type', 'generated', $name ) ] = $generated;

					// We generated the bg string, we dont need the bg props anymore, except bg-color.
					unset( $out[ str_replace( 'type', 'image', $name ) ] );
					unset( $out[ str_replace( 'type', 'repeat', $name ) ] );
					unset( $out[ str_replace( 'type', 'attachment', $name ) ] );
					unset( $out[ str_replace( 'type', 'size', $name ) ] );
					unset( $out[ str_replace( 'type', 'position', $name ) ] );
				}
				// We dont need the type as well.
				unset( $out[ $name ] );
			}

			// Unit values cleanup.
			if ( preg_match( '/-(desktop|tablet|phone)$/', $name ) ) {
				if ( ! is_numeric( $value ) && preg_match( '/-unit-/', $name ) ) {
					// We remove vars that contain just unit values so we dont output them as css vars.
					unset( $out[ $name ] );
				}
			}
		}

		return $out;
	}

	private static function flatten_vars( $vars, $prefix = '' ) {
		$flat_vars = array();

		foreach ( $vars as $key => $var ) {
			if ( is_array( $var ) ) {
				$flat_vars = array_merge( $flat_vars, self::flatten_vars( $var, $prefix . $key . '-' ) );

				unset( $flat_vars[ $key ] );
			} else {
				$flat_vars[ $prefix . $key ] = $var;
			}
		}

		return $flat_vars;
	}

	/**
	 * Sanitizes a variable
	 *
	 * @param  string  $name           option name
	 * @param  string  $value          option value from db
	 * @param  boolean $returnOriginal whether to return the db value if no good sanitization is found
	 * @return int|string|null         sanitized value
	 */
	private static function prepare( $name, $value, $returnOriginal = false ) {
		$good          = true;
		$name          = preg_replace( '/^vamtam_/', '', $name );
		$originalValue = $value;

		// duck typing values
		if ( preg_match( '/(^share|^has-|^show|-last$|-subsets$|-google$)/i', $name ) ) {
			$good = false;
		} elseif ( preg_match( '/(%|px|em|vw|vh)$/i', $value ) ) { // definitely a number, leave it as is

		} elseif ( preg_match( '/^\\\\[0-9a-f]+$/', $value ) ) { // icon fonts
			$value = "'$value'";
		} elseif ( is_numeric( $value ) ) { // most likely dimensions, must differentiate between percentages and pixels
			if ( in_array( $name, self::$percentages ) ) {
				$value .= '%';
			} elseif ( in_array( $name, self::$numbers ) || strpos( $name, 'line-height' ) !== false ) {
				// as it is
			} elseif ( preg_match( '/(size|width|height)$/', $name ) || preg_match( '/(padding|margin)$/', $name ) ) { // treat as px
				$value .= 'px';
			}
		} elseif ( preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $value ) || ( $value === '' && preg_match( '/-color$/', $name ) ) ) { // colors
			// as is
		} elseif ( preg_match( '/-font-family$/', $name ) && strpos( $value, ',' ) !== false ) {
			// as is
		} elseif ( preg_match( '/-(transform|decoration|font-weight|background-type|background-gradient-type|)$/', $name ) ) {
			// as is
		} elseif ( preg_match( '/^http|^url/i', $value ) || ( preg_match( '/(family|weight)$/', $name ) && isset( $value[0] ) && ! in_array( $value[0], array( '"', "'" ) ) ) ) { // urls and other strings
			$value = "'" . str_replace( "'", '"', $value ) . "'";
		} elseif ( preg_match( '/^accent(?:-color-)?\d$/', $value ) ) { // accents
			$value = vamtam_sanitize_accent( $value );
		} else {
			if ( ! preg_match( '/\bfamily\b|\burl\b|\bcolor\b|\bbody-link\b/i', $name ) ) {
				// check keywords
				$keywords   = explode( ' ', 'top right bottom left fixed static scroll cover contain auto repeat repeat-x repeat-y no-repeat center normal italic bold 100 200 300 400 500 600 700 800 900 transparent' );
				$sub_values = explode( ' ', $value );
				foreach ( $sub_values as $s ) {
					if ( ! in_array( $s, $keywords ) ) {
						$good = false;
						break;
					}
				}
			}
		}

		return $good ? $value : ( $returnOriginal ? $originalValue : null );
	}
}


