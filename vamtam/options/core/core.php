<?php

/**
 * Controls attached to core sections
 *
 * @package vamtam/numerique
 */


return array(
	array(
		'label'     => esc_html__( 'Header Logo Type', 'numerique' ),
		'id'        => 'header-logo-type',
		'type'      => 'switch',
		'transport' => 'postMessage',
		'section'   => 'title_tagline',
		'choices'   => array(
			'image'      => esc_html__( 'Image', 'numerique' ),
			'site-title' => esc_html__( 'Site Title', 'numerique' ),
		),
		'priority' => 8,
	),

	array(
		'label'     => esc_html__( 'Single Product Image Zoom', 'numerique' ),
		'id'        => 'wc-product-gallery-zoom',
		'type'      => 'switch',
		'transport' => 'postMessage',
		'section'   => 'woocommerce_product_images',
		'choices'   => array(
			'enabled'  => esc_html__( 'Enabled', 'numerique' ),
			'disabled' => esc_html__( 'Disabled', 'numerique' ),
		),
		// 'active_callback' => 'vamtam_extra_features',
	),
);


