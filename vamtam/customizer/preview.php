<?php

function vamtam_customizer_setup_partial_options() {
	global $vamtam_theme;

	$raw_options = json_decode( stripslashes_deep( $_POST['customized'] ), true );

	$old_opts = $vamtam_theme;

	if ( isset( $_POST['customized'] ) && ! empty( $raw_options ) ) {
		if ( is_array( $raw_options ) ) {
			foreach ( $raw_options as $key => $value ) {
				if ( strpos( $key, 'vamtam_theme' ) !== false ) {
					$key                  = str_replace( 'vamtam_theme[', '', rtrim( $key, ']' ) );
					$vamtam_theme[ $key ] = $value;
				}
			}
		}
	}
}
add_action( 'customize_render_partials_before', 'vamtam_customizer_setup_partial_options' );

function vamtam_customize_register( WP_Customize_Manager $wp_customize ) {
	include VAMTAM_DIR . 'customizer/preview/general.php';
	include VAMTAM_DIR . 'customizer/preview/layout.php';
	include VAMTAM_DIR . 'customizer/preview/styles.php';
}
add_action( 'customize_register', 'vamtam_customize_register', 20 );
