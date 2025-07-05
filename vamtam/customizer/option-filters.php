<?php

$user_fonts = apply_filters( 'vamtam_user_fonts', array() );

function vamtam_customizer_inject_defaults( $options ) {
	global $vamtam_defaults, $vamtam_all_fonts;

	foreach ( $options as &$opt ) {
		if ( isset( $opt['id'] ) && isset( $vamtam_defaults[ $opt['id'] ] ) ) {
			$opt['default'] = $vamtam_defaults[ $opt['id'] ];
		}
	}

	return $options;
}
add_filter( 'vamtam_customizer_fields_options', 'vamtam_customizer_inject_defaults' );
