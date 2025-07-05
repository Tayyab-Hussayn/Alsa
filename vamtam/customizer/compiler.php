<?php

function vamtam_recompile_css() {
	global $vamtam_theme;

	vamtam_customizer_compiler( $vamtam_theme );
}

// "clear cache" implementation
function vamtam_actions() {
	if ( isset( $_GET['vamtam_action'] ) ) {
		if ( 'clear_cache' === $_GET['vamtam_action'] ) {
			vamtam_recompile_css();

			wp_redirect( admin_url() );
		}
	}
}
add_action( 'admin_init', 'vamtam_actions' );

function vamtam_customizer_compiler( $options ) {
	if ( is_network_admin() ) {
		if ( class_exists( 'FLBuilderAdminSettings' ) ) {
			FLBuilderAdminSettings::clear_cache_for_all_sites();
		}
	} else {
		if ( class_exists( 'FLBuilderModel' ) ) {
			// Clear builder cache.
			FLBuilderModel::delete_asset_cache_for_all_posts();
		}

		// Clear theme cache.
		if ( class_exists( 'FLCustomizer' ) && method_exists( 'FLCustomizer', 'clear_all_css_cache' ) ) {
			FLCustomizer::clear_all_css_cache();
		}
	}

	update_option( 'vamtam-css-cache-timestamp', time() );
}
add_action( 'vamtam_customizer/' . $opt_name . '/compiler', 'vamtam_customizer_compiler', 10, 1 );
