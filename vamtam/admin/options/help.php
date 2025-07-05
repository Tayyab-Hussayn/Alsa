<?php
return array(
	'name' => esc_html__( 'Help', 'numerique' ),
	'auto' => true,
	'config' => array(

		array(
			'name' => esc_html__( 'Help', 'numerique' ),
			'type' => 'title',
			'desc' => '',
		),

		array(
			'name' => esc_html__( 'Help', 'numerique' ),
			'type' => 'start',
			'nosave' => true,
		),
//----
		array(
			'type' => 'docs',
		),

			array(
				'type' => 'end',
			),
	),
);
