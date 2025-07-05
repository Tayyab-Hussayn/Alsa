<?php

/**
 * Suggest things to do after a theme update
 *
 * @package vamtam/numerique
 */
class VamtamUpdateNotice {
	/**
	 * Key for the option which holds the last theme version
	 *
	 * @var string
	 */
	public static $last_version_key = '-vamtam-last-theme-version';

	/**
	 * checks if the theme has been updated
	 * and the update message has not been dismissed
	 */
	public static function check() {
		$current_version    = VamtamFramework::get_version();
		$last_known_version = get_option( VAMTAM_THEME_SLUG . self::$last_version_key );

		if ( $current_version !== $last_known_version ) {
			vamtam_recompile_css();

			update_option( VAMTAM_THEME_SLUG . self::$last_version_key, VamtamFramework::get_version() );
		}
	}
}


