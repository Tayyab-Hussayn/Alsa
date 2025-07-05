<?php

/**

Four number fields (top/right/bottom/left) with three device options (desktop/tablet/phone)

**/

class Vamtam_Customize_Responsive_Box_Fields_Control extends Vamtam_Customize_Control {
	public $type = 'vamtam-responsive-box-fields';

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
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 */
	public function to_json() {
		parent::to_json();

		$this->json['l10n'] = array(
			'top'       => esc_html__( 'Top', 'numerique' ),
			'right'     => esc_html__( 'Right', 'numerique' ),
			'bottom'    => esc_html__( 'Bottom', 'numerique' ),
			'left'      => esc_html__( 'Left', 'numerique' ),
			'padding'   => esc_html__( 'Padding', 'numerique' ),
		);
	}
	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'customizer-control-vamtam-responsive-box-fields-js',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/js/responsive-box-fields' . ( WP_DEBUG ? '' : '.min' ) . '.js',
			array( 'jquery', 'customize-base', 'vamtam-customize-controls-conditionals' ),
			Vamtam_Customizer::$version,
			true
		);

		// Inherits styles from customizer.less
	}

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
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="vamtam-responsive-box-fields resp-control">
			<div class="options">
				<!-- <h5 class="option-label">{{ data.label }}</h5> -->
				<span class="customize-control-title option-label">
					{{{ data.label }}}
				</span>
				<span class="resp-btns">
					<span data-device="desktop" class="active dashicons dashicons-desktop"/>
					<span data-device="tablet" class="dashicons dashicons-tablet"/>
					<span data-device="phone" class="dashicons dashicons-smartphone"/>
				</span>
				<span class="units">
					<span data-unit="px" class="{{ data.value['unit']['desktop'] === 'px' && 'active' }}">PX</span>
					<span data-unit="em" class="{{ data.value['unit']['desktop'] === 'em' && 'active' }}">EM</span>
					<span data-unit="%" class="{{ data.value['unit']['desktop'] === '%' && 'active' }}">%</span>
				</span>
			</div>
			<div class="values" data-type="box-fields">
				<# [ 'top', 'right', 'bottom', 'left' ].forEach( side => { #>
					<div class="field-wrap">
						<input data-value="{{ side }}" id="{{ data.id }}-{{ 'desktop' }}-{{ side }}" type="number" value="{{ data.value[ side ][ 'desktop' ] }}" oninput="jQuery(this).trigger('change');" required />
						<label for="{{ data.id }}-{{ 'desktop' }}-{{ side }}"><small>{{ data.l10n[ side ].toUpperCase() }}</small></label>
					</div>
				<# } ) #>
			</div>
		</div>
		<?php
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 */
	public function render_content() {}
}
