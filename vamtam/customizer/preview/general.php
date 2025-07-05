<?php

// general

function vamtam_partial_header_logo() {
	ob_start();

	get_template_part( 'templates/header/top/logo', 'wrapper' );

	return ob_get_clean();
}

$wp_customize->selective_refresh->add_partial( 'header-logo-selective', array(
	'selector' => '.logo-wrapper',
	'settings' => array(
		'vamtam_theme[header-logo-type]',
		'vamtam_theme[custom-header-logo]',
	),
	'container_inclusive' => true,
	'render_callback'     => 'vamtam_partial_header_logo',
) );