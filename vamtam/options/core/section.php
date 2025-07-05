<?php

global $vamtam_theme_customizer;

$thispath = VAMTAM_OPTIONS . 'core/';

$vamtam_theme_customizer->add_section( array(
	'title'       => '',
	'description' => '',
	'id'          => 'vamtam-core',
	'preexisting' => true,
	'fields'      => include $thispath . 'core.php',
) );
