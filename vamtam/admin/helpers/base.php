<?php

/**
 *
 * @desc registers a theme activation hook
 * @param string $code : Code of the theme. This can be the base folder of your theme. Eg if your theme is in folder 'mytheme' then code will be 'mytheme'
 * @param callback $function : Function to call when theme gets activated.
 */
function vamtam_register_theme_activation_hook( $code, $function ) {
	$optionKey = 'theme_is_activated_' . $code;
	if ( ! get_option( $optionKey ) ) {
		call_user_func( $function );
		update_option( $optionKey , 1 );
	}
}

// theme activation hook
function vamtam_theme_activated() {
	if ( vamtam_validate_install() ) {
		vamtam_register_theme_activation_hook( 'vamtam_' . VAMTAM_THEME_NAME, 'vamtam_theme_activated' );

		require_once VAMTAM_DIR . 'classes/class-tgm-plugin-activation.php';
		require_once VAMTAM_SAMPLES_DIR . 'dependencies.php';

		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			if ( did_action( 'tgmpa_register' ) ) {
				vamtam_maybe_redirect_to_tgmpa();
			} else {
				add_action( 'tgmpa_register', 'vamtam_maybe_redirect_to_tgmpa', 1000, 1 );
			}
		}
	}
}

function vamtam_maybe_redirect_to_tgmpa() {
	if ( ! TGM_Plugin_Activation::get_instance()->is_tgmpa_complete() && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		wp_redirect( TGM_Plugin_Activation::get_instance()->get_tgmpa_url() );
	}
}

vamtam_register_theme_activation_hook( 'vamtam_' . VAMTAM_THEME_NAME, 'vamtam_theme_activated' );

add_action( 'admin_init', 'vamtam_validate_install' );
function vamtam_validate_install() {
	global $vamtam_errors, $vamtam_validated;
	if ( $vamtam_validated )
		return;

	$vamtam_validated = true;
	$vamtam_errors    = array();

	if ( strpos( str_replace( WP_CONTENT_DIR . '/themes/', '', get_template_directory() ), '/' ) !== false ) {
		$vamtam_errors[] = esc_html__( 'The theme must be installed in a directory which is a direct child of wp-content/themes/', 'numerique' );
	}

	if ( count( $vamtam_errors ) ) {
		if ( ! function_exists( 'vamtam_invalid_install' ) ) {
			function vamtam_invalid_install() {
				global $vamtam_errors;
				?>
					<div class="updated fade error" style="background: #FEF2F2; border: 1px solid #DFB8BB; color: #666;"><p>
						<?php esc_html_e( 'There were some some errors with your Vamtam theme setup:', 'numerique' )?>
						<ul>
							<?php foreach ( $vamtam_errors as $error ) : ?>
								<li><?php echo esc_html( $error ) ?></li>
							<?php endforeach ?>
						</ul>
					</p></div>
				<?php
			}
			add_action( 'admin_notices', 'vamtam_invalid_install' );
		}
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
		return false;
	}

	return true;
}

// Run every time the theme is activated.
function vamtam_after_theme_activation() {
	// Redirect to theme dashboard.
	wp_redirect( esc_url( admin_url( 'admin.php?page=vamtam_theme_setup' ) ) );
}
add_action("after_switch_theme", "vamtam_after_theme_activation");