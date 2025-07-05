<?php

/**
 * Basic ajax class
 *
 * @package vamtam/numerique
 */

/**
 * class VamtamAjax
 */
class VamtamAjax {

	protected $actions;

	/**
	 * Hook ajax actions
	 */
	public function __construct() {
		if ( is_array( $this->actions ) ) {
			foreach ( $this->actions as $hook => $func ) {
				add_action( 'wp_ajax_vamtam-' . $hook, array( $this, $func ) );
			}
		}
	}
}


