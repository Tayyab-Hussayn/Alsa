<?php

/**
 * Help page
 *
 * @package vamtam/numerique
 */
class VamtamHelpPage {

	public static $mu_plugin_opt_name;

	/**
	 * Actions
	 */
	public function __construct() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 21 );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
}

	public static function admin_menu() {
		add_submenu_page( 'vamtam_theme_setup', esc_html__( 'Help', 'numerique' ), esc_html__( 'Help', 'numerique' ), 'edit_theme_options', 'vamtam_theme_help', array( __CLASS__, 'page' ) );
	}

	public static function settings_section() {
	}

	public static function admin_init() {
		add_settings_section(
			'vamtam_help_settings_section',
			'',
			array( __CLASS__, 'settings_section' ),
			'vamtam_theme_help'
		);

		add_settings_field(
			'vamtam-system-status-opt-in',
			esc_html__( 'Enable System Status Information Gathering', 'numerique' ),
			array( __CLASS__, 'radio' ),
			'vamtam_theme_help',
			'vamtam_help_settings_section',
			array(
				'vamtam-system-status-opt-in',
				true,
			)
		);

		register_setting(
			'vamtam_theme_help',
			'vamtam-system-status-opt-in'
		);
	}

	public static function page() {
		include VAMTAM_OPTIONS . 'help/docs.php';
	}

	public static function radio( $args ) {
		$value = vamtam_sanitize_bool( get_option( $args[0], $args[1] ) );

		echo '<label><input type="radio" id="' . esc_attr( $args[0] ) . '-on" name="' . esc_attr( $args[0] ) . '" value="1" ' . checked( $value, true, false ) . '/> ' . esc_html__( 'On', 'numerique' ) . '</label> ';
		echo '<label><input type="radio" id="' . esc_attr( $args[0] ) . '-off" name="' . esc_attr( $args[0] ) . '" value="0" ' . checked( $value, false, false ) . '/> ' . esc_html__( 'Off', 'numerique' ) . '</label>';

		echo '<p class="description">' . esc_html__( 'By enabling this option you will opt in to automatically send our support system detailed information about your website. Please note that we might be able to respond more quickly if you leave this disabled. We advise you to turn on this option before opening a support ticket.', 'numerique' ) . '</p>';
	}
}


