<?php

/**
 * Framework admin enhancements
 *
 * @author Nikolay Yordanov <me@nyordanov.com>
 * @package vamtam/numerique
 */

/**
 * class VamtamAdmin
 */
class VamtamAdmin {
	/**
	 * Initialize the theme admin
	 */
	public static function actions() {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			add_action( 'admin_init', array( 'VamtamUpdateNotice', 'check' ) );
		}

		add_action( 'admin_footer', array( __CLASS__, 'icons_selector' ) );

		add_filter( 'admin_notices', array( __CLASS__, 'update_warning' ) );

		add_action( 'admin_init', array( __CLASS__, 'setup_settings' ) );

		self::load_functions();

		new VamtamPurchaseHelper;
		new VamtamHelpPage;

		require_once VAMTAM_ADMIN_HELPERS . 'updates/version-checker.php';

		if ( ! get_option( VAMTAM_THEME_SLUG . '_vamtam_theme_activated', false ) ) {
			update_option( VAMTAM_THEME_SLUG . '_vamtam_theme_activated', true );
			delete_option( 'default_comment_status' );
		}
	}

	public static function setup_settings() {}

	public static function update_warning() {
		if ( did_action( 'load-update-core.php' ) ) {
			echo '<div class="updated notice fade is-dismissible"><p><strong>';
;
			esc_html_e( 'Hey, just a polite reminder that if you update WordPress you will also need to update your theme and plugins.', 'numerique' );
			echo '</strong>';
			echo '</p></div>';
		}

		if ( did_action( 'load-update-core.php' ) || did_action( 'load-themes.php' ) ) {
			echo '<div class="notice notice-success is-dismissible"><p><strong>';
;
			esc_html_e( 'VamTam theme resources: ', 'numerique' );
			echo '</strong>';
			echo '<a href="https://vamtam.com/child-themes" target="_blank">';
			esc_html_e( 'Sample child themes', 'numerique' );
			echo '</a>; ';
			echo '<a href="https://vamtam.com/changelog" target="_blank">';
			esc_html_e( 'Changelog', 'numerique' );
			echo '</a>';
			echo '</p></div>';
		}
	}

	public static function icons_selector() {
		?>
		<div class="vamtam-config-icons-selector hidden">
			<input type="search" placeholder="<?php esc_attr_e( 'Filter icons', 'numerique' ) ?>" class="icons-filter"/>
			<div class="icons-wrapper spinner">
				<input type="radio" value="" checked="checked"/>
			</div>
		</div>
		<?php
	}

	/**
	 * Admin helper functions
	 */
	private static function load_functions() {
		require_once VAMTAM_ADMIN_HELPERS . 'base.php';
	}
}


