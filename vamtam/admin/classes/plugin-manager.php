<?php

/**
 * Plugin Manager
 *
 * @package vamtam/numerique
 */
/**
 * class VamtamPluginManager
 */
class VamtamPluginManager {
	/**
	 * TGMPA instance storage
	 *
	 * @var object
	 */
	protected $tgmpa_instance;

	/**
	 * TGMPA Menu slug
	 *
	 * @var string
	 */
	protected $tgmpa_menu_slug 	= 'tgmpa-install-plugins';

	/**
	 * TGMPA Menu url
	 *
	 * @var string
	 */
	protected $tgmpa_url 		= 'themes.php?page=tgmpa-install-plugins';

	/**
	 * Holds the current instance of the plugin manager
	 * @var VamtamPluginManager
	 */
	private static $instance 	= null;

	/**
	 * @return VamtamPluginManager
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance 	= new self;
		}

		return self::$instance;
	}

	public function __construct() {
		$this->init_globals();
		$this->init_actions();
	}

	/**
	 * Setup the class globals.
	 */
	public function init_globals() {
		$this->parent_slug     	= 'vamtam_theme_setup';
		if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
			$this->get_tgmpa_instanse();
			$this->set_tgmpa_url();
		}
		if( isset( $_POST['action'] ) && $_POST['action'] === "vamtam_setup_plugins" && wp_doing_ajax() ) {
			add_filter( 'wp_redirect', '__return_false', 999 );
		}
	}

	/**
	 * Setup the hooks, actions and filters.
	 */
	public function init_actions() {
		if ( current_user_can( 'manage_options' ) ) {

            add_action( 'admin_menu'				    , array( $this, 'admin_menus' ), 15 );
            add_action( 'admin_enqueue_scripts'		    , array( $this, 'enqueue_scripts' ) );
            add_filter( 'tgmpa_load'				    , array( $this, 'tgmpa_load' ), 10, 1 );
			add_action( 'wp_ajax_vamtam_setup_plugins'	, array( $this, 'ajax_plugins' ) );
			add_filter( 'tgmpa_admin_menu_args', function ( $menu ) {
				$menu['parent_slug'] = null; // Hide tgmpa menu without losing tgmpa-install-plugins page.
				return $menu;
			} );

			if( isset( $_POST['action'] ) && $_POST['action'] === "vamtam_setup_plugins" && wp_doing_ajax() ) {
				add_filter( 'wp_redirect', '__return_false', 999 );
			}
		}
	}

	/**
	 * Enqueue admin scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'vamtam-admin-plugin-manager'	, VAMTAM_ADMIN_ASSETS_URI . 'js/plugin-manager.js'	, array(
			'jquery'
		), '1.7.2', true );

		wp_localize_script(
			'vamtam-admin-plugin-manager' ,
			'vamtam_setup_params',
			array(
				'tgm_plugin_nonce' => array(
					'update'  => wp_create_nonce( 'tgmpa-update' ),
					'install' => wp_create_nonce( 'tgmpa-install' ),
				),
				'tgm_bulk_url'     => admin_url( $this->tgmpa_url ),
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'wpnonce'          => wp_create_nonce( 'vamtam_setup_nonce' ),
			) );
	}

    /**
     * Check for TGMPA load
     */
	public function tgmpa_load( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Get configured TGMPA instance
	 */
	public function get_tgmpa_instanse() {
		$this->tgmpa_instance 	= call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	}

	/**
	 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
	 */
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug 	= ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$tgmpa_parent_slug 		= ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';
		$this->tgmpa_url 		= $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug;
	}

	/**
	 * Add admin menus/screens.
	 */
	public function admin_menus() {

	}

	/**
	 *  Plugin installation
	 */
	public static function setup_plugins() {

		tgmpa_load_bulk_installer();
		// install plugins with TGM.
		if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
			die( 'Failed to find TGM' );
		}
		$url     = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'vamtam-setup' );
		$plugins = $this->get_plugins();

		// copied from TGM

		$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
		$fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.

		if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
			return true; // Stop the normal page form from displaying, credential request form will be shown.
		}

		// Now we have some credentials, setup WP_Filesystem.
		if ( ! WP_Filesystem( $creds ) ) {
			// Our credentials were no good, ask the user for them again.
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );

			return true;
		}

		//Can use this in case of missing descriptions. slug => desc
		$embeds_plugins_desc = array(
			'test_vamtam_plugin'        => 'test desc',
		);

		/* If we arrive here, we have the filesystem */
		// ACTIVATION DISABLED: Always consider license as valid
		$valid_key = true; // Version_Checker::is_valid_purchase_code();

		?>
		<div class="vamtam-ts">
			<?php VamtamPurchaseHelper::dashboard_navigation(); ?>

			<?php if ( $valid_key ) : ?>
				<div class="vamtam-plugin-manager vamtam-has-required-plugins">
					<h2><?php esc_html_e( 'Recommended Plugins', 'numerique' ); ?></h2>

					<?php
					$theme_name = ucfirst( wp_get_theme()->get_template() );

					if ( count( $plugins['all'] ) ) {
						?>
						<p><?php echo sprintf( esc_html( 'You can install exclusive and recommended plugins for %s here, and add or remove them later on WordPress plugins page.', 'numerique'), $theme_name ); ?></p>

						<div class="vamtam-table-wrap">
							<table class="vamtam-plugins-table widefat">
								<thead>
									<tr>
										<td id="cb" class="manage-column column-cb check-column" width="10%">
											<label class="screen-reader-text"
											for="cb-select-all"><?php esc_html_e( 'Select All', 'numerique' ); ?></label>
											<input
											id="cb-select-all" type="checkbox">
										</td>
										<th class="manage-column column-thumbnail"></th>
										<th scope="col" id="name"
										class="manage-column column-name" width="15%"><?php esc_html_e( 'Name', 'numerique' ); ?></th>
										<th scope="col" id="description"
										class="manage-column column-description" width="50%"><?php esc_html_e( 'Description', 'numerique' ); ?></th>
										<th scope="col" id="status"
										class="manage-column column-status" width="17%"><?php esc_html_e( 'Status', 'numerique' ); ?></th>
										<th scope="col" id="version"
										class="manage-column column-version" width="8%"><?php esc_html_e( 'Version', 'numerique' ); ?></th>
									</tr>
								</thead>
								<tbody class="vamtam-plugins">
									<?php
									foreach ( $plugins['all'] as $slug => $plugin ) {
										if( $this->tgmpa_instance->is_plugin_installed( $slug ) ) {
											$plugin_data 	= get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin['file_path'] );
										} else {
											$plugin_data 	= $this->get_plugin_data_by_slug( $slug );
										}
									?>
										<tr class="vamtam-plugin" data-slug="<?php echo esc_attr( $slug ); ?>" data-category="<?php echo esc_attr( $plugin['category'] ); ?>">
											<th scope="row" class="check-column">
												<input class="vamtam-check-column" name="plugin[]" value="<?php echo esc_attr($slug); ?>" type="checkbox">
												<div class="spinner"></div>
											</th>
											<td class="thumbnail column-thumbnail"
											data-colname="Thumbnail">
												<?php
													$thumbnail_size = '128x128';

													$recommended_plugins_no_store_img = array(
														'booked',
														'booked-calendar-feeds',
														'booked-frontend-agents',
														'booked-woocommerce-payments',
														'easy-charts',
														'revslider',
														'unplug-jetpack',
														'vamtam-elements-b',
														'vamtam-importers',
													);

													if( in_array( $plugin['slug'], $recommended_plugins_no_store_img ) ) {
														$thumbnail = VAMTAM_ADMIN_ASSETS_URI . 'images/def-plugin.png';
													} else{
														$thumbnail = 'https://ps.w.org/'. $plugin['slug'] .'/assets/icon-'. $thumbnail_size .'.png';
													}

													?>
												<img src="<?php echo esc_url( $thumbnail ); ?>" width="64" height="64">
											</td>
											<td class="name column-name"
											data-colname="Plugin">
												<?php echo esc_html( $plugin['name'] ); ?>
											</td>
											<td class="description column-description"
											data-colname="Description">
											<?php
												if( isset( $plugin_data['Description'] ) ) {
													echo '<p>' . $plugin_data['Description'] . '</p>';
												} else if ( isset( $embeds_plugins_desc[ $plugin['slug'] ] ) ){
													echo '<p>' . $embeds_plugins_desc[ $plugin['slug'] ] . '</p>';
												} else {
													echo 'A simple WordPress Plugin.';
												}
												if ( strpos( $slug, 'numerique' ) !== false ) {
													echo '<div class="vamtam-label">' . esc_html__('Exclusive', 'numerique') . '</div>';
												}
											?>
											</td>
											<td class="status column-status"
											data-colname="Status">
												<span>
													<?php
														if ( isset( $plugins['install'][ $slug ] ) ) {
															echo esc_html__( 'Not Installed', 'numerique' );
														} elseif ( isset( $plugins['activate'][ $slug ] ) ) {
															echo esc_html__( 'Not Activated', 'numerique' );
														}
													?>
												</span>
											</td>
											<td class="version column-version"
											data-colname="Version">
												<?php if( isset( $plugin_data['Version'] ) ) { ?>
												<span><?php echo esc_html( $plugin_data['Version'] ); ?></span>
												<?php } ?>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>

						<a id="install-plugins-btn" href="#"
							class="vamtam-button install-plugins button-next"
							data-callback="install_plugins"><?php esc_html_e( 'Install Plugins', 'numerique' ); ?></a>
						</div>

					<?php
					} else { ?>
						<div class="vamtam-plugins-success">
							<?php echo esc_html__( 'All plugins are already installed and up to date.', 'numerique'  ); ?>
						</div>
					<?php
					} ?>
				</div>
				<?php else : ?>
					<?php VamtamPurchaseHelper::registration_warning(); ?>
				<?php endif ?>
		</div>
		<?php
	}

	/**
	 * Output the tgmpa plugins list
	 */
	private function get_plugins( $custom_list = array() ) {

		if ( empty( $this->tgmpa_instance )) {
			$this->get_tgmpa_instanse();
		}

		$r = new ReflectionMethod( 'TGMPA_List_Table', 'categorize_plugins_to_views' );
		$r->setAccessible( true );

		$plugins = $r->invoke( new TGMPA_List_Table() );

		return $plugins;
	}

	/**
	 * Checks if there are open actions for the required plugins.
	 * Returns a status:
	 * 		success -> no pending actions for required plugins.
	 * 		warning -> there are pending updates for required plugins.
	 * 		error   -> there are pending installations/activations for required plugins.
	 *		fail    -> the procedure encountered a problem.
	 */
	public static function get_required_plugins_status( $custom_list = array() ) {

		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$tgmpa = TGM_Plugin_Activation::get_instance();
		} else {
			return 'fail';
		}

		$r = new ReflectionMethod( 'TGMPA_List_Table', 'categorize_plugins_to_views' );
		$r->setAccessible( true );

		$plugins = $r->invoke( new TGMPA_List_Table() );

		foreach ( $plugins as $status => $list ) {
			foreach ( $list as $slug => $plugin ) {
				if ( $plugin['required'] !== true ) {
					unset( $plugins[ $status ][ $slug ]  );
				}
			}
		}

		if ( count( $plugins['all'] ) > 0 ) {
			if ( count( $plugins['install'] ) > 0 || count( $plugins['activate'] ) > 0 ) {
				return 'error';
			}
			if ( count( $plugins['update'] ) > 0 ) {
				return 'warning';
			}
		}
		return 'success';
	}

	/**
	 * Returns the plugin data from WP.org API
	 */
	public static function get_plugin_data_by_slug( $slug = '' ) {

		if ( empty( $slug ) ) {
			return false;
		}

	    $key = sanitize_key( 'vamtam_plugin_data_'.$slug );

	    if ( false === ( $plugins = get_transient( $key ) ) ) {
			$args = array(
				'slug' => $slug,
				'fields' => array(
			 		'short_description' => true
				)
			);
			$response = wp_remote_post(
				'http://api.wordpress.org/plugins/info/1.0/',
				array(
					'body' => array(
						'action' => 'plugin_information',
						'request' => serialize( (object) $args )
					)
				)
			);
			$data    = unserialize( wp_remote_retrieve_body( $response ) );

			$plugins = is_object( $data ) ? array( 'Description' => $data->short_description , 'Version' => $data->version ) : false;

			// Set transient for next time... keep it for 24 hours
			set_transient( $key, $plugins, 24 * HOUR_IN_SECONDS );

	    }

	    return $plugins;
	}

	/**
	 * Plugins AJAX Process
	 */
	public function ajax_plugins() {
		if ( ! check_ajax_referer( 'vamtam_setup_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found', 'numerique' ) ) );
		}
		$json = array();
		// send back some json we use to hit up TGM
		$plugins = $this->get_plugins();
		// what are we doing with this plugin?
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating...', 'numerique' ),
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing...', 'numerique' ),
				);
				break;
			}
		}

		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json( $json );
		} else {
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Activated', 'numerique' ) ) );
		}
		exit;

	}

	public static function filter_tabs() {
		?>
		<div id="vamtam-plugins-filters">
			<ul>
				<li>
					<a data-filter="required" class="vamtam-filter-btn">
						<?php esc_html_e( 'Required', 'numerique' ); ?>
					</a>
				</li>
				<li>
					<a data-filter="recommended" class="vamtam-filter-btn">
						<?php esc_html_e( 'Recommended', 'numerique' ); ?>
					</a>
				</li>
			</ul>
			<hr/>
			<p id="vamtam-required-plugins-notice">
				<strong><?php esc_html_e( 'Why required plugins?', 'numerique' ); ?></strong>
				<br />
				<?php esc_html_e( 'A plugin offers additional functionality and features beyond a typical WordPress installation. You do not need additional licensing in order to use the plugins below with your theme.', 'numerique' ); ?>
			</p>
			<p id="vamtam-recommended-plugins-notice">
				<svg xmlns="http://www.w3.org/2000/svg" width="14" height="50" viewBox="0 0 14 50"><path fill-rule="evenodd" d="M7 33.3a5.4 5.4 0 0 1-5.4-5L0 5.8A5.1 5.1 0 0 1 1.1 2c.2-.2.5-.3.8-.1.2.2.3.5.1.8a4.1 4.1 0 0 0-.9 2.8l1.6 22.7a4.3 4.3 0 0 0 5 3.9c.3 0 .6.1.7.4 0 .3-.2.6-.5.7H7zm4.7-3.6h-.1a.6.6 0 0 1-.4-.7v-.7L13 5.6v-.3c0-2.3-2-4.2-4.3-4.2h-2A.6.6 0 0 1 6 .6c0-.4.3-.6.6-.6h2A5.4 5.4 0 0 1 14 5.7l-1.6 22.7-.1.9c-.1.2-.3.4-.6.4zM7 50a6.2 6.2 0 0 1-6.2-6.1A6.2 6.2 0 1 1 13 42.2c0 .3-.1.6-.4.7-.3 0-.6-.1-.7-.4a5.1 5.1 0 0 0-10 1.4 5 5 0 0 0 5.1 5 5 5 0 0 0 5-5c0-.3.3-.6.7-.6.3 0 .5.3.5.6 0 3.4-2.8 6.1-6.2 6.1z"/></svg>
				<?php esc_html_e( 'Please note that the theme doesn\'t depend on the plugins in this list to function properly. Nor does the demo content importer. Install them only if you are going to use them, otherwise, they may impact the performance of the site or put an unnecessary burden on your hosting.', 'numerique' ); ?>
			</p>
		</div>
		<?php
	}
}
