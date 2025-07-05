<?php

class Vamtam_Customizer {
	private static $instance;

	private $sections;
	private $args;

	public $dir;
	public $fields_by_id;

	protected $class_from_type_cache = array();

	private $controls;

	public static $version = '1.1.0';

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct( $args = array() ) {
		// Autoload classes on demand
		if ( function_exists( '__autoload' ) )
			spl_autoload_register( '__autoload' );
		spl_autoload_register( array( $this, 'autoload' ) );

		$this->args     = $args;
		$this->sections = array();
		$this->dir      = plugin_dir_path( __FILE__ );

		$this->class_from_type_cache = array();

		add_action( 'customize_register', array( $this, 'customize_register' ), 5 );
		add_action( 'customize_save_after', array( $this, 'customize_save_after' ) );

		add_action( 'after_setup_theme', array( $this, 'setup_options' ), 5 );

		add_action( 'wp_ajax_vamtam-customizer-control', array( $this, 'control_ajax' ) );

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );
	}

	public function customize_controls_enqueue_scripts() {
		$this->apply_theme_device_breakpoints();
	}

	public function apply_theme_device_breakpoints() {
		// If Elementor is active, we apply it's setting, otherwise the theme's default.
		// We only need the mobile setting.
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$small_breakpoint  = VamtamElementorBridge::get_site_breakpoints( 'md' );
		}

		if ( empty( $small_breakpoint ) ) {
			$small_breakpoint = 768;
		}

        $tablet_margin_left = '-' . ( ( $small_breakpoint + 1 ) / 2 ) . 'px'; //Half of -$tablet_width
        $tablet_width = ( $small_breakpoint + 1 ) . 'px';

		wp_add_inline_style( 'vamtam-customizer',
            '.wp-customizer .preview-mobile .wp-full-overlay-main {
                margin-left: -180px;
                width: 360px;
                height: 640px;
            }

            .wp-customizer .preview-tablet .wp-full-overlay-main {
                margin-left:' . $tablet_margin_left . ';
                width:' . $tablet_width . ';
            }'
		);
    }

	public function customize_save_after( $wp_customize ) {
		global $vamtam_fonts;

		$fonts_by_family = vamtam_get_fonts_by_family();

		$google_fonts = array();

		$customized = json_decode( stripslashes_deep( $_POST['customized'] ), true );

		$fields  = $this->get_fields_by_id();
		$options = $this->get_options();

		$compiler = false;

		foreach ( $fields as $id => $field ) {
			$full_id = $this->args['opt_name'] . '[' . $id . ']';

			// cache google fonts, so we can just load them later
			if ( 'typography' === $field['type'] ) {
				$font_id = $fonts_by_family[ $options[ $id ]['font-family'] ];
				$font    = $vamtam_fonts[ $font_id ];

				if ( isset( $font['gf'] ) && $font['gf'] ) {
					$google_fonts[ $font_id ][] = isset( $options[ $id ]['font-weight'] ) ? $options[ $id ]['font-weight'] : 'normal';
				}

				// This is just for backwards compatibility.
				if ( ! empty( $options[ $id ]['line-height'] ) && ! is_array( $options[ $id ]['line-height'] ) && strpos( $options[ $id ]['line-height'], 'px' ) !== false ) {
					// Normally, we shouldn't reach here. New typography options define line-height as array().
					$options[ $id ]['line-height'] = round( (int)$options[ $id ]['line-height'] / (int)$options[ $id ]['font-size'], 2 );
				}
			}

			// if a compiler option was changed
			if ( isset( $customized[ $full_id ] ) && ( isset( $field['compiler'] ) && $field['compiler'] ) ) {
				$compiler = true;
			}
		}

		$options['google_fonts'] = self::build_google_fonts_url( $google_fonts );

		$this->set_options( $options );

		if ( $compiler ) {
			do_action( "vamtam_customizer/{$this->args['opt_name']}/compiler", $options );
		}

		do_action( 'vamtam_saved_options' );
	}

	public static function build_google_fonts_url( $google_fonts ) {
		$font_imports_url = '';

		if ( is_array( $google_fonts ) && count( $google_fonts ) ) {
			$param = array();

			foreach ( $google_fonts as $font => $weights ) {
				// always include a bold version, if available
				$weights[] = 'bold';

				if ( strpos( implode( '', $weights ), 'italic' ) !== false ) {
					$weights[] = '700i';
				}

				// this is used so that we can add other mandatory weights if we have used them in css
				$weights = apply_filters( 'vamtam_customizer_font_weights', $weights, $font );

				$weights = str_replace( ' ', '', implode( ',', array_unique( $weights ) ) );

				$param[] = urlencode( $font ) . ':' . $weights;
			}

			$param = implode( '|', $param );

			$font_imports_url = 'https://fonts.googleapis.com/css?family=' . $param;
		}

		return $font_imports_url;
	}

	public function autoload( $class ) {
		$file = 'class-' . str_replace( '_', '-', strtolower( $class ) ) . '.php';

		if ( is_readable( $this->dir . $file ) ) {
			include_once $this->dir . $file;
			return;
		}

		if ( is_readable( $this->dir . 'controls/' . $file ) ) {
			include_once $this->dir . 'controls/' . $file;
			return;
		}

		if ( is_readable( $this->dir . 'ajax/' . $file ) ) {
			include_once $this->dir . 'ajax/' . $file;
			return;
		}
	}

	public function control_ajax() {
		// ucwords $delimiter param was added in 5.4.32, 5.5.16
		$type = str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $_POST['control'] ) ) );

		$class = 'Vamtam_Customize_' . $type . '_Ajax';

		if ( class_exists( $class ) ) {
			$ajax_obj = new $class( $this->get_options() );

			$method = array( $ajax_obj, 'ajax_' . $_POST['method'] );

			if ( is_callable( $method ) ) {
				call_user_func( $method );
				exit;
			}
		}

		header( 'Content-type: application/json' );

		echo json_encode( array(
			'error' => 'cannot call ' . $class . '::ajax_' . $_POST['method'],
		) );

		exit;
	}

	public function get_arg( $key ) {
		return $this->args[ $key ];
	}

	public function setup_options() {
		if ( ! isset( $GLOBALS[ $this->args['opt_name'] ] ) ) {
			$GLOBALS[ $this->args['opt_name'] ] = $this->get_options();

			if ( is_customize_preview() && isset( $_POST['customized'] ) ) {
				$raw_options = json_decode( stripslashes_deep( $_POST['customized'] ), true );

				if ( ! empty( $raw_options ) ) {
					if ( is_array( $raw_options ) ) {
						foreach ( $raw_options as $key => $value ) {
							if ( strpos( $key, $this->args['opt_name'] ) !== false ) {
								$key = str_replace( $this->args['opt_name'] . '[', '', rtrim( $key, ']' ) );

								$GLOBALS[ $this->args['opt_name'] ][ $key ] = $value;
							}
						}
					}
				}
			}
		}
	}

	public function get_options() {
		$options = get_option( $this->args['opt_name'] );

		if ( false === $options ) {
			$options = array();
		}

		return wp_parse_args( $options, $this->get_defaults() );
	}

	public function get_defaults() {
		$options = array();
		$fields  = $this->get_fields_by_id();

		foreach ( $fields as $id => $field ) {
			if ( isset( $field['default'] ) ) {
				$options[ $id ] = $field['default'];
			}
		}

		return $options;
	}

	public function set_options( $options ) {
		return update_option( $this->args['opt_name'], $options );
	}

	public function add_section( array $args ) {
		$top_level_sections = count( $this->sections );

		if ( isset( $args['fields'] ) ) {
			$args['fields'] = apply_filters( 'vamtam_customizer_fields_options', $args['fields'] );
		}

		$args['permissions'] = isset( $args['permissions'] ) ? $args['permissions'] : 'edit_theme_options';

		if ( isset( $args['subsection'] ) && $args['subsection'] && $top_level_sections > 0 ) {
			$this->sections[ $top_level_sections - 1 ]['children'][] = $args;
		} else {
			$args['children'] = array();
			$this->sections[] = $args;
		}
	}

	public function get_sections() {
		return $this->sections;
	}

	public function get_fields_by_id() {
		if ( ! isset( $this->fields_by_id ) ) {
			$this->fields_by_id = array();

			foreach ( $this->sections as $section ) {
				if ( count( $section['children'] ) > 0 ) {
					foreach ( $section['children'] as $child ) {
						foreach ( $child['fields'] as $field ) {
							$this->fields_by_id[ $field['id'] ] = $field;
						}
					}
				} else {
					if ( isset( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							$this->fields_by_id[ $field['id'] ] = $field;
						}
					}
				}
			}
		}

		return $this->fields_by_id;
	}

	public function get_fields_by_type( $type ) {
		$fields = array();

		$options = $this->get_fields_by_id();

		foreach ( $options as $id => $opt ) {
			if ( isset( $opt['type'] ) && $opt['type'] === $type ) {
				$fields[ $id ] = $opt;
			}
		}

		return $fields;
	}

	public function customize_register( WP_Customize_Manager $wp_customize ) {
		if ( method_exists( $wp_customize, 'register_section_type' ) ) {
			$wp_customize->register_section_type( 'Vamtam_Customizer_Section' );
		}

		if ( method_exists( $wp_customize, 'register_panel_type' ) ) {
			$wp_customize->register_panel_type( 'Vamtam_Customizer_Panel' );
		}

		$priority = 1;

		foreach ( $this->sections as $section ) {
			if ( empty( $section['priority'] ) ) {
				$section['priority'] = $priority++;
			}

			$parent_id = $this->args['opt_name'] . '-' . $section['id'];

			if ( count( $section['children'] ) > 0 ) {

				$wp_customize->add_panel( $parent_id, array(
					'priority'    => $section['priority'],
					'capability'  => $section['permissions'],
					'title'       => $section['title'],
					'section'     => $section,
					'description' => '',
				) );

				foreach ( $section['children'] as $child ) {
					if ( empty( $child['priority'] ) ) {
						$child['priority'] = $priority++;
					}

					$wp_customize->add_section( $this->args['opt_name'] . '-' . $child['id'], array(
						'title'       => $child['title'],
						'priority'    => $child['priority'],
						'description' => $child['description'],
						'section'     => $child,
						'capability'  => $child['permissions'],
						'panel'       => $parent_id,
					) );

					$this->setup_fields( $child, $wp_customize );
				}
			} else {
				if ( ! isset( $section['preexisting'] ) || ! $section['preexisting'] ) {
					$wp_customize->add_section( $parent_id, array(
						'priority'    => $section['priority'],
						'capability'  => $section['permissions'],
						'title'       => $section['title'],
						'section'     => $section,
						'description' => '',
					) );
				}

				$this->setup_fields( $section, $wp_customize );
			}
		}
	}

	private function setup_fields( $section, WP_Customize_Manager $wp_customize ) {
		$priority = 1;

		foreach ( $section['fields'] as $field ) {
			$field['id'] = $this->args['opt_name'] . '[' . $field['id'] . ']';

			if ( ! isset( $field['priority'] ) ) {
				$field['priority'] = $priority++;
			}

			$class = $this->class_from_type( $field['type'], $wp_customize );

			if ( $class ) {
				$type = $field['type'];

				if ( $class !== 'Vamtam_Customize_Control' && strpos( $class, 'Vamtam' ) !== false ) {
					$type = 'vamtam-' . $type;
				}

				$wp_customize->add_setting( $field['id'], array(
					'default'           => isset( $field['default'] ) ? $field['default'] : '',
					'type'              => 'option',
					'transport'         => isset( $field['transport'] ) ? $field['transport'] : 'refresh',
					'sanitize_callback' => $this->get_sanitize_callback( $field, $wp_customize ),
				) );

				$control_attrs = array_merge( $field, array(
					'section'  => isset( $field['section'] ) ? $field['section'] : $this->args['opt_name'] . '-' . $section['id'],
					'settings' => $field['id'],
					'type'     => $type,
					'field'    => $field,
				) );

				$wp_customize->add_control( new $class( $wp_customize, $field['id'], $control_attrs ) );
			}
		}
	}

	private function get_sanitize_callback( $field, $wp_customize ) {
		$callback = '';

		if ( isset( $field['sanitize_callback'] ) ) {
			$callback = $field['sanitize_callback'];
		} else {
			$class = $this->class_from_type( $field['type'], $wp_customize );

			$common_callbacks = array(
				'color'      => 'sanitize_hex_color',
				'number'     => array( __CLASS__, 'sanitize_number' ),
				'image'      => 'esc_url_raw',
				'multicheck' => array( __CLASS__, 'sanitize_array' ),
			);

			if ( isset( $common_callbacks[ $field['type'] ] ) ) {
				$callback = $common_callbacks[ $field['type'] ];
			} elseif ( is_callable( array( $class, 'sanitize_callback' ) ) ) {
				$callback = array( $class, 'sanitize_callback' );
			}
		}

		return apply_filters( 'vamtam_customize_setting_sanitize_callback', $callback );
	}

	public static function sanitize_array( $value ) {
		if ( ! is_array( $value ) ) {
			// objects should be saved as arrays
			if ( is_object( $value ) ) {
				return (array) $value;
			}

			// not an array or an object - default to an empty array
			return array();
		}

		// everything is ok
		return $value;
	}

	public static function sanitize_number( $value ) {
		$can_validate = method_exists( 'WP_Customize_Setting', 'validate' );

		if ( ! is_numeric( $value ) ) {
			return $can_validate ? new WP_Error( 'nan', esc_html__( 'Not a number', 'numerique' ) ) : null;
		}

		return intval( $value );
	}

	protected function class_from_type( $type, WP_Customize_Manager $wp_customize ) {
		if ( isset( $this->class_from_type_cache[ $type ] ) ) {
			return $this->class_from_type_cache[ $type ];
		}

		$orig_type = $type;

		// ucwords $delimiter param was added in 5.4.32, 5.5.16
		$type = str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $type ) ) );

		$class = '';

		$test_core   = 'WP_Customize_' . $type . '_Control';
		$test_vamtam = 'Vamtam_Customize_' . $type . '_Control';

		if ( class_exists( $test_vamtam ) ) {
			$class = $test_vamtam;

			try {
				$reflection = new \ReflectionMethod( $test_vamtam, 'content_template' );

				$declaringClass = $reflection->getDeclaringClass()->getName();
				$proto          = $reflection->getPrototype();

				if ( $proto && $proto->getDeclaringClass()->getName() !== $declaringClass ) {
					$wp_customize->register_control_type( $class );
				}
			} catch ( Exception $e ) {
				// getPrototype will throw if content_template() is not overriden
			}
		} elseif ( class_exists( $test_core ) ) {
			$class = $test_core;
		} else {
			$class = 'Vamtam_Customize_Control'; // default WP Core control - implements text, radio, select, etc.
		}

		$this->class_from_type_cache[ $orig_type ] = $class;

		return $class;
	}
}
