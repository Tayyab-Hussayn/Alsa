<?php

/**

Multiple color fields displayed horizontally

@see Kirki/multicolor

**/

require_once plugin_dir_path( __FILE__ ) . 'class-vamtam-customize-control.php';

class Vamtam_Customize_Color_Row_Control extends Vamtam_Customize_Control {
	public $type = 'vamtam-color-row';
	public $with_hc;

	/**
	 * Constructor.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::__construct()
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		$this->statuses = array(
			'' => esc_html__( 'Default', 'numerique' ),
		);
		if ( ! empty( $args['with_hc'] ) ) {
			$args['choices'] = $this->add_high_contrast_colors( $args['choices'] );
			$args['choices']['auto-contrast'] = 'Auto Contrast';
		}
		parent::__construct( $manager, $id, $args );
	}


	/**
	 * @param $choices
	 *
	 * @return array
	 */
	function add_high_contrast_colors( $choices ) {
		if ( empty( $choices ) ) {
			return $choices;
		}
		$choices_with_hc = $choices;
		foreach ( $choices as $key => $choice ) {
			$choices_with_hc[ $key . '-hc' ] = $choice;
		}

		return $choices_with_hc;
	}

	/**
	 * Color Palette.
	 *
	 * @access public
	 * @var bool
	 */
	public $palette = true;
	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 */
	public function to_json() {
		parent::to_json();
		$this->json['palette']  = $this->palette;
		$this->json['statuses'] = $this->statuses;
		$this->json['with_hc']  = $this->with_hc;
	}
	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'tinycolor',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/js/tinycolor.js',
			array( 'customize-base', 'wp-color-picker', 'wp-i18n' ),
			'1.4.1',
			true
		);

		wp_enqueue_script(
			'customizer-control-vamtam-color-row-js',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/js/color-row' . ( WP_DEBUG ? '' : '.min' ) . '.js',
			array( 'jquery', 'customize-base', 'wp-color-picker' ),
			Vamtam_Customizer::$version,
			true
		);

		wp_enqueue_style(
			'customizer-control-vamtam-color-row',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/css/color-row.css',
			array( 'wp-color-picker' ),
			Vamtam_Customizer::$version
		);

		$color_row_translation_array = array(
			'tooltip_msg'      => esc_html__( 'Contrasting color may be hard for people to read. Try different colors with a higher brightness difference between this accent and the associated contrasting color.', 'numerique' ),
			'tooltip_msg_auto' => esc_html__( "It's not possible to generate a color with high enough contrast given your chosen accent color. Consider changing accent color so that it is easier for people to read.", 'numerique' ),
		);
		wp_localize_script( 'customizer-control-vamtam-color-row-js', 'vamtamColorRowObj', $color_row_translation_array );
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 */
	public function render_content() {}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see Kirki_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<span class="customize-control-title">
			{{{ data.label }}}
		</span>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="vamtam-color-row-group-wrapper">
		<# if ( data.id == 'vamtam_theme[accent-color]' && data.with_hc ) { #>

			<div class="vamtam-color-row-container">
				<div class="color-row-number"></div>
				<div class="vamtam-color-row-single-color-wrapper">
					<span class="customize-control-title"> <?php echo esc_html__( 'Main Color', 'numerique' ); ?> </span>
				</div>
				<div class="color-row-warning"> </div>
				<div class="vamtam-color-row-single-color-wrapper">
					<span class="customize-control-title"> <?php echo esc_html__( 'Contrast Color', 'numerique' ); ?> </span>
				</div>
			</div>

			<# for ( let i = 1; i <= 8; i++ ) { #>
				<div id="vamtam-color-row-container-{{ i }}" class="vamtam-color-row-container">
					<div class="color-row-number">
						{{ i }}
					</div>
					<div class="vamtam-color-row-single-color-wrapper">
						<# if ( data.choices[ i ] ) { #>
						<input id="{{ data.id }}-{{ i }}" type="text" data-palette="{{ data.palette }}" data-default-color="{{ data.default[ i ] }}" data-alpha="true" value="{{ data.value[ i ] }}" class="kirki-color-control color-picker vamtam-color-row-index-{{ i }}" />
						<# } #>
					</div>
					<div class="color-row-warning">
						<span id="color-row-warning-{{ i }}" class="dashicons"></span>
					</div>
					<div class="vamtam-color-row-single-color-wrapper">
						<# let hc_key = i + '-hc'; #>
						<# if ( data.choices[ hc_key ] ) { #>
						<input id="{{ data.id }}-{{ hc_key }}" type="text" data-palette="{{ data.palette }}" data-default-color="{{ data.default[ hc_key ] }}" data-alpha="true" value="{{ data.value[ hc_key ] }}" class="kirki-color-control color-picker vamtam-color-row-index-{{ hc_key }}" />
						<# } #>
						<div class="vamtam-auto-contrast-demo" style="background:var(--vamtam-accent-color-{{ hc_key }})"></div>
					</div>
				</div>
			<# } #>

			<#
				let auto_contrast_state = '';
				if ( data.value['auto-contrast'] == true ) {
					auto_contrast_state = 'checked';
				}
			#>
			<div class="auto-contrast">
				<label>
					<input id="{{ data.id }}-auto-contrast" type="checkbox" value="" {{ auto_contrast_state }} class="automatic-contrast-colors">
					<?php esc_html_e( 'Use automatic contrast colors', 'numerique' ) ?>
				</label>
			</div>
			<# } #>

		<# if ( data.id !== 'vamtam_theme[accent-color]' ) { #>
			<# for ( key in data.choices ) { #>
				<div class="vamtam-color-row-single-color-wrapper">
					<# if ( data.choices[ key ] ) { #>
					<label for="{{ data.id }}-{{ key }}">{{ data.choices[ key ] }}</label>
					<# } #>
					<input id="{{ data.id }}-{{ key }}" type="text" data-palette="{{ data.palette }}" data-default-color="{{ data.default[ key ] }}" data-alpha="true" value="{{ data.value[ key ] }}" class="kirki-color-control color-picker vamtam-color-row-index-{{ key }}" />
				</div>
			<# } #>
		<# } #>

		</div>
		<input type="hidden" value="" {{{ data.link }}} />
		<?php
	}
}
