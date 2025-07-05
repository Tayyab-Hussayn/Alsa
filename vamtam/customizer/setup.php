<?php

define( 'VAMTAM_CUSTOMIZER_LIB_PATH', plugin_dir_path( __FILE__ ) . 'lib/' );
define( 'VAMTAM_CUSTOMIZER_LIB_URL', VAMTAM_URI . 'customizer/lib/' );

$opt_name = apply_filters( 'vamtam_theme/opt_name', 'vamtam_theme' );

include VAMTAM_CUSTOMIZER_LIB_PATH . 'class-vamtam-customizer.php';

$GLOBALS['vamtam_theme_customizer'] = new Vamtam_Customizer( array(
	'opt_name' => $opt_name,
) );

require_once VAMTAM_DIR . 'customizer/compiler.php';
require_once VAMTAM_DIR . 'customizer/option-filters.php';

// load the option definitions
$sections = array(
	'core',
);

foreach ( $sections as $section ) {
	include VAMTAM_OPTIONS . "$section/section.php";
}

function vamtam_custom_css_options() {
	// extract compiler options from option definitions

	$type_map = array();

	$options = $GLOBALS['vamtam_theme_customizer']->get_fields_by_id();

	foreach ( $options as $opt ) {
		if ( isset( $opt['compiler'] ) && $opt['compiler'] ) {
			$type_map[ 'vamtam_theme[' . $opt['id'] . ']' ] = $opt['type'];
		}
	}

	return $type_map;
}

function vamtam_option_types_used() {
	$options = $GLOBALS['vamtam_theme_customizer']->get_fields_by_id();

	$types = array();

	foreach ( $options as $opt ) {
		$types[] = $opt['type'];
	}

	sort( $types );

	return array_unique( $types );
}

function vamtam_rename_customize_section( WP_Customize_Manager $wp_customize  ) {
	$wp_customize->get_section( 'static_front_page' )->title = esc_html__( 'Page Settings', 'numerique' );
}
add_action( 'customize_register', 'vamtam_rename_customize_section', 20 );
