<?php

/**

Four number fields (top/right/bottom/left) with three device options (desktop/tablet/phone)

**/

require_once plugin_dir_path( __FILE__ ) . 'class-vamtam-customize-control.php';

class Vamtam_Customize_Responsive_Position_Control extends Vamtam_Customize_Control {
	public $type = 'vamtam-responsive-position';

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
		parent::__construct( $manager, $id, $args );
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
		$this->json['labels'] = array();
	}
	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'customizer-control-vamtam-responsive-position-js',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/js/responsive-position' . ( WP_DEBUG ? '' : '.min' ) . '.js',
			array( 'jquery', 'customize-base' ),
			Vamtam_Customizer::$version,
			true
		);

		wp_enqueue_style(
			'customizer-control-vamtam-responsive-position',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/css/responsive-position.css',
			array(),
			Vamtam_Customizer::$version
		);
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
		<div class="vamtam-responsive-position-outer-wrapper">
			<# [ 'desktop', 'tablet', 'phone' ].forEach( device => { #>
				<strong>{{ device }}</strong>
				<div class="vamtam-responsive-position-group-wrapper">
					<# [ 'top', 'right', 'bottom', 'left' ].forEach( side => { #>
						<div>
							<label for="{{ data.id }}-{{ device }}-{{ side }}">{{ side }}</label>
							<input id="{{ data.id }}-{{ device }}-{{ side }}" type="number" value="{{ data.value[ device ][ side ] }}" class="vamtam-responsive-position-{{ device }}-{{ side }}" required />
						</div>
					<# } ) #>
				</div>
			<# } ); #>
		</div>
		<?php
	}
}
