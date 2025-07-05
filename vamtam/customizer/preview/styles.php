<?php

global $wp_customize;

// all compiler options

$compiler_options = array_keys( vamtam_custom_css_options() );

// all typography options

$typography_options = $GLOBALS['vamtam_theme_customizer']->get_fields_by_type( 'typography' );

function vamtam_customizer_preview_fonts_url() {
	global $vamtam_fonts, $vamtam_theme;

	$fonts_by_family = vamtam_get_fonts_by_family();

	$google_fonts = array();

	$typography_options = $GLOBALS['vamtam_theme_customizer']->get_fields_by_type( 'typography' );

	foreach ( $typography_options as $id => $field ) {
		$font_id = $fonts_by_family[ $vamtam_theme[ $id ]['font-family'] ];
		$font    = $vamtam_fonts[ $font_id ];

		if ( isset( $font['gf'] ) && $font['gf'] ) {
			$google_fonts[ $font_id ][] = isset( $vamtam_theme[ $id ]['font-weight'] ) ? $vamtam_theme[ $id ]['font-weight'] : 'normal';
		}
	}

	$font_imports_url = Vamtam_Customizer::build_google_fonts_url( $google_fonts );

	return $font_imports_url;
}
