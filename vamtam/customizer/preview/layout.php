<?php

function vamtam_partial_header_text_main() {
	ob_start();

	get_template_part( 'templates/header/top/text-main' );

	return ob_get_clean();
}


function vamtam_partial_header_layout() {
	ob_start();

	get_template_part( 'templates/header/top' );

	return ob_get_clean();
}

$wp_customize->selective_refresh->add_partial( 'header-layout-selective', array(
	'selector' => '.fixed-header-box:not( .hbox-filler )',
	'settings' => array(
		'vamtam_theme[header-height]',
	),
	'container_inclusive' => true,
	'render_callback'     => 'vamtam_partial_header_layout',
) );
