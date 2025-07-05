<?php

/**
 * Declare plugin dependencies
 *
 * @package vamtam/numerique
 */

/**
 * Declare plugin dependencies
 */
function vamtam_register_required_plugins() {
	// This is for setting the minimum required version of a bundled plugin (not from store)
	// so tgmpa can see the new version and handle the plugin as updatable.
	$updates = get_site_transient( 'update_plugins' );

	$plugins = array(

		array(
			'name'     => esc_html__( 'Elementor', 'numerique' ),
			'slug'     => 'elementor',
			'required' => true,
			'category' => 'required',
			'version'  => '2.7.4',
		),

		array(
			'name'     => esc_html( sprintf( __( 'Vamtam Elementor Integration (%s)', 'numerique' ), VAMTAM_THEME_SLUG ) ),
			'slug'     => 'vamtam-elementor-integration-' . VAMTAM_THEME_SLUG,
			'source'   => VAMTAM_PLUGINS . 'vamtam-elementor-integration-' . VAMTAM_THEME_SLUG . '.zip',
			'required' => true,
			'category' => 'required',
			'version'  => '1.0.0',
		),

		array(
			'name'     => esc_html__( 'Vamtam Importers (E)', 'numerique' ),
			'slug'     => 'vamtam-importers-e',
			'source'   => VAMTAM_PLUGINS . 'vamtam-importers-e.zip',
			'required' => true,
			'category' => 'required',
			'version'  => ( $updates !== false && isset( $updates->response[ 'vamtam-importers-e/vamtam-importers.php' ] ) )
				? $updates->response[ 'vamtam-importers-e/vamtam-importers.php' ]->new_version
				: '1.0.0',
		),

		array(
			'name'     => esc_html__( 'Wordpress SEO', 'numerique' ),
			'slug'     => 'wordpress-seo',
			'required' => false,
			'category' => 'recommended',
		),

		array(
		),

		array(
			'name'     => esc_html__( 'Really Simple SSL', 'numerique' ),
			'slug'     => 'really-simple-ssl',
			'required' => false,
			'category' => 'recommended',
		),

		array(
			'name'     => esc_html__( 'Loco Translate', 'numerique' ),
			'slug'     => 'loco-translate',
			'required' => false,
			'category' => 'recommended',
		),

		array(
			'name'     => esc_html__( 'UpDraftPlus', 'numerique' ),
			'slug'     => 'updraftplus',
			'required' => false,
			'category' => 'recommended',
		),

		array(
			'name'     => esc_html__( 'WP Super Cache', 'numerique' ),
			'slug'     => 'wp-super-cache',
			'required' => false,
			'category' => 'recommended',
		),

		array(
			'name'     => esc_html__( 'Limit Login Attempts Reloaded', 'numerique' ),
			'slug'     => 'limit-login-attempts-reloaded',
			'required' => false,
			'category' => 'recommended',
		),

		array(
			'name'     => esc_html__( 'Wordfence', 'numerique' ),
			'slug'     => 'wordfence',
			'required' => false,
			'category' => 'recommended',
		),

		array(
			'name'     => esc_html__( 'CookieYes | GDPR Cookie Consent & Compliance Notice (CCPA Ready)', 'numerique' ),
			'slug'     => 'cookie-law-info',
			'required' => false,
			'category' => 'recommended',
		),
	);

	$config = array(
		'default_path' => '',    // Default absolute path to pre-packaged plugins
		'is_automatic' => true,  // Automatically activate plugins after installation or not
		'parent_slug' => 'vamtam_theme_setup',
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'vamtam_register_required_plugins' );

function vamtam_tgmpa_table_columns( $columns ) {
	//Filter the header columns for the plugin page
	$columns = array(
		'cb'          => '<input type="checkbox" />',
		'img'         => '',
		'plugin'      => esc_html__( 'Name', 'numerique' ),
		'description' => esc_html__( 'Description', 'numerique' ),
		'status'      => esc_html__( 'Status', 'numerique' ),
		'version'     => esc_html__( 'Version', 'numerique' ),
	);
	return $columns;
}
add_filter( 'tgmpa_table_columns', 'vamtam_tgmpa_table_columns' );

function vamtam_tgmpa_table_data_item( $item, $plugin )
{
	$thumbnail_size = '128x128';

	$recommended_plugins_no_store_img = array(
		'booked',
		'booked-calendar-feeds',
		'booked-frontend-agents',
		'booked-woocommerce-payments',
		'revslider',
		'unplug-jetpack',
		'vamtam-elementor-integration',
		'vamtam-elementor-integration-' . VAMTAM_THEME_SLUG,
		'vamtam-importers-e',
		'vamtam-product-qa',
		'woocommerce-bookings',
		'selfhost-google-fonts',
	);

	$fallback_plugin_image = VAMTAM_ADMIN_ASSETS_URI . 'images/vamtam-logo.png';

	// Plugin image
	$thumbnail = '';
	if( ! in_array( $plugin['slug'], $recommended_plugins_no_store_img ) ) {
		foreach ( [ '.png', '.jpg', '.gif' ] as $ext ) {
			foreach ( [ '128x128', '256x256' ] as $size ) {
				$thumbnail .= '<source srcset="https://ps.w.org/'. $plugin['slug'] .'/assets/icon-'. $size . $ext . '">';
			}
		}
	}

	$item['img'] = '<picture>' . $thumbnail . '<img src="' . esc_url( $fallback_plugin_image ) . '" onerror="this.parentElement.removeChild(this.parentElement.querySelector(\'source\'))" width="64" height="64" data-category="' . esc_attr( $plugin['category'] ) . '" ></picture>';

	$tgmpa_instance 	= call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

	if( $tgmpa_instance->is_plugin_installed( $plugin['slug'] ) ) {
		$plugin_data 	= get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin['file_path'] );
	} else {
		// Get from store
		$plugin_data 	= VamtamPluginManager::get_plugin_data_by_slug( $plugin['slug'] );
	}

	// Plugin Version
	if ( ! isset( $item['available_version'] ) || empty( $item['available_version'] ) ) {
		$item['available_version'] = ! empty( $plugin_data ) && $plugin_data['Version'];
	}


	// Plugin description
	if( isset( $plugin_data['Description'] ) ) {
		$item['description'] = $plugin_data['Description'];
	} else {
		$item['description'] =  esc_html__( 'A simple WordPress Plugin.', 'numerique' );
	}
	if ( strpos( $plugin['slug'], 'numerique' ) !== false ) {
		$item['description'] =  esc_html__( 'Theme Exclusive.', 'numerique' );
	}

	return $item;
}
add_filter( 'tgmpa_table_data_item', 'vamtam_tgmpa_table_data_item', 10, 2 );

// vamtam-tgmpa.js will gracefully hide the recommended plugins notice.
function vamtam_hide_tgmpa_notice() {
	$screen = get_current_screen();
	if ( is_admin() && $screen->id === 'plugins' ) {
		wp_enqueue_script( 'vamtam-tgmpa', VAMTAM_ADMIN_ASSETS_URI . 'js/vamtam-tgmpa.js', array( 'jquery' ), VamtamFramework::get_version(), true );
	}
}
add_action('admin_enqueue_scripts', 'vamtam_hide_tgmpa_notice');

/**
 * Essentially a copy of the standard tgmpa plugins page but with our dashboard and validation.
 *
 * This displays the admin page and form area where the user can select to install and activate the plugin.
 * Aborts early if we're processing a plugin installation action.
 *
 * Important!!
 * 	For this to work we need to make tgmpa's do_plugin_install public ( from protected ).
 *
 * @return null Aborts early if we're processing a plugin installation action.
 */
function vamtam_install_plugins_page() {
	$tgmpa = TGM_Plugin_Activation::get_instance();
	// Store new instance of plugin table in object.
	$plugin_table = new TGMPA_List_Table;

	// Return early if processing a plugin installation action.
	if ( ( ( 'tgmpa-bulk-install' === $plugin_table->current_action() || 'tgmpa-bulk-update' === $plugin_table->current_action() ) && $plugin_table->process_bulk_actions() ) || $tgmpa->do_plugin_install() ) {
		return;
	}

	// Force refresh of available plugin information so we'll know about manual updates/deletes.
	wp_clean_plugins_cache( false );
	$valid_key = Version_Checker::is_valid_purchase_code();
	?>
	<div id="vamtam-ts-tgmpa" class="vamtam-ts">
			<div id="vamtam-ts-side">
				<?php VamtamPurchaseHelper::dashboard_navigation(); ?>
			</div>
			<div id="vamtam-ts-main">
				<?php if ( $valid_key ) : ?>
				<?php $plugin_table->prepare_items(); ?>

				<?php
				if ( ! empty( $tgmpa->message ) && is_string( $tgmpa->message ) ) {
					echo wp_kses_post( $tgmpa->message );
				}
				?>
				<?php $plugin_table->views(); ?>

				<form id="tgmpa-plugins" action="" method="post">
					<?php VamtamPluginManager::filter_tabs(); ?>
					<input type="hidden" name="tgmpa-page" value="<?php echo esc_attr( $tgmpa->menu ); ?>" />
					<input type="hidden" name="plugin_status" value="<?php echo esc_attr( $plugin_table->view_context ); ?>" />
					<?php $plugin_table->display(); ?>
				</form>
				<?php else : ?>
					<?php VamtamPurchaseHelper::registration_warning(); ?>
				<?php endif ?>
			</div>
	</div>
	<?php
}

function vamtam_tgmpa_admin_menu_args( $args ) {
	$args['page_title'] = esc_html__( 'Install Plugins', 'numerique' );
	$args['function'] = 'vamtam_install_plugins_page';

	return $args;
}
add_filter( 'tgmpa_admin_menu_args', 'vamtam_tgmpa_admin_menu_args' );

// Hide all notices on theme setup pages.
function vamtam_in_admin_header() {
	if ( isset ( $_GET['page'] ) ) {
		$is_vamtam_ts_page = in_array( $_GET['page'], array(
			'vamtam_theme_setup',
			'tgmpa-install-plugins',
			'vamtam_theme_setup_import_content',
			'vamtam_theme_help',
		) );
		if ( $is_vamtam_ts_page ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}
}
add_action('in_admin_header', 'vamtam_in_admin_header', 1000 );

function vamtam_tgmpa_bulk_install_setup() {
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'tgmpa-install-plugins' ) {
		// this disabled the fastcgi buffering for nginx servers
		header('X-Accel-Buffering: no');
	}
}
add_action('admin_init', 'vamtam_tgmpa_bulk_install_setup');
