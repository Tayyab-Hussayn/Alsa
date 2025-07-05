<?php

/**
	Typograohy control

	@see Kirki/typography
 */

class Vamtam_Customize_Typography_Control extends Vamtam_Customize_Control {
	public $type = 'vamtam-typography';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'vamtam-select2',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/js/select2/vamtam-select2.min.js',
			array( 'jquery', 'vamtam-customize-controls-conditionals' ),
			'4.0.12',
			true
		);

		wp_enqueue_style(
			'vamtam-select2',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/css/select2/vamtam-select2.min.css',
			array(),
			'4.0.12'
		);


		wp_enqueue_script(
			'customizer-control-vamtam-typography-js',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/js/typography' . ( WP_DEBUG ? '' : '.min' ) . '.js',
			array( 'jquery', 'customize-base', 'wp-color-picker', 'vamtam-customize-controls-conditionals' ),
			Vamtam_Customizer::$version,
			true
		);

		wp_enqueue_style(
			'customizer-control-vamtam-typography',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/css/typography.css',
			array( 'wp-color-picker' ),
			Vamtam_Customizer::$version
		);
		// Inherits styles from customizer.less

		wp_localize_script( 'customize-base', 'VAMTAM_ALL_FONTS', $GLOBALS['vamtam_fonts'] );

		// Exporting custom font-faces to customizer
		wp_add_inline_style( 'customizer-control-vamtam-typography', VamtamEnqueues::get_custom_fonts_css() );
	}
	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 */
	public function to_json() {
		parent::to_json();
		$this->add_values_backwards_compatibility();

		$defaults = array(
			'font-family'     => false,
			'font-size'       => array(
				'desktop' => 16,
				'tablet'  => 16,
				'phone'   => 16,
				'unit'    => array(
					'desktop' => 'px',
					'tablet'  => 'px',
					'phone'   => 'px',
				)
			),
			'font-weight'     => 'normal',
			'line-height'     => array(
				'desktop' => 1,
				'tablet'  => 1,
				'phone'   => 1,
				'unit'    => array(
					'desktop' => 'px',
					'tablet'  => 'px',
					'phone'   => 'px',
				)
			),
			'color'           => '#000000',
			'letter-spacing'  => array(
				'desktop' => 0,
				'tablet'  => 0,
				'phone'   => 0,
				'unit'    => array(
					'desktop' => 'px',
					'tablet'  => 'px',
					'phone'   => 'px',
				)
			),
			'transform'       => 'none',
			'font-style'      => 'normal',
			'decoration'      => 'none',
		);

		$this->json['exclude-decoration'] = empty( $this->json['default']['exclude-decoration'] ) ? '' : '1';

		// This is for adjusting decoration values set prior to the option removal.
		if ( ! empty( $this->json['exclude-decoration'] ) && ! empty( $this->value()['decoration'] ) ) {
			$this->json['value']['decoration'] = '';
		}

		$this->json['default'] = wp_parse_args( $this->json['default'], $defaults );

		$this->json['l10n'] = array(
			'font-family'                => esc_html__( 'Font Family', 'numerique' ),
			'select-font-family'         => esc_html__( 'Select Font Family', 'numerique' ),
			'font-weight'                     => esc_html__( 'Weight', 'numerique' ),
			'transform'                  => array (
				'label'        => esc_html__( 'Transform', 'numerique' ),
				'uppercase'    => esc_html__( 'Uppercase', 'numerique' ),
				'lowercase'    => esc_html__( 'Lowercase', 'numerique' ),
				'capitalize'   => esc_html__( 'Capitalize', 'numerique' ),
				'normal'       => esc_html__( 'Normal', 'numerique' ),
			),
			'font-style'                 => array (
				'label'        => esc_html__( 'Style', 'numerique' ),
				'normal'       => esc_html__( 'Normal', 'numerique' ),
				'italic'       => esc_html__( 'Italic', 'numerique' ),
				'oblique'      => esc_html__( 'Oblique', 'numerique' ),
			),
			'decoration'                 => array (
				'label'        => esc_html__( 'Decoration', 'numerique' ),
				'underline'    => esc_html__( 'Underline', 'numerique' ),
				'overline'     => esc_html__( 'Overline', 'numerique' ),
				'line-through' => esc_html__( 'Line Through', 'numerique' ),
				'none'         => esc_html__( 'None', 'numerique' ),
			),
			'font-size'                  => esc_html__( 'Font Size', 'numerique' ),
			'line-height'                => esc_html__( 'Line Height', 'numerique' ),
			'color'                      => esc_html__( 'Font Color', 'numerique' ),
			'letter-spacing'             => esc_html__( 'Letter Spacing', 'numerique' ),
			'letter-spacing-description' => wp_kses( __( 'Either <code>normal</code> or a length unit', 'numerique' ), [ 'code' => [] ] ),
		);
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
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="wrapper">
			<# if ( '' == data.value['font-family'] ) { data.value['font-family'] = data.default['font-family']; } #>

			<div class="color">
				<h5>{{ data.l10n['color'] }}</h5>
				<input type="text" data-palette="{{ data.palette }}" data-default-color="{{ data.default['color'] }}" value="{{ data.value['color'] }}" class="vamtam-color-picker" />
			</div>

			<div class="font-family">
				<h5>{{ data.l10n['font-family'] }}</h5>
				<select data-id="vamtam-typography-font-family-{{{ data.id }}}" placeholder="{{ data.l10n['select-font-family'] }}" style="font-family:{{ data.value['font-family'] }};">
					<# for ( var font in VAMTAM_ALL_FONTS ) { #>
						<# var fontFamily = VAMTAM_ALL_FONTS[font].family; #>
						<# var hasOpenGroup = false #>
						<# if ( ! fontFamily ) { #>
								<# if ( hasOpenGroup ) { #>
									</optgroup>
								<# } #>
								<optgroup label="{{ font }}">
								<# hasOpenGroup = true #>
						<# } else { #>
							<option value="{{ fontFamily }}" style="font-family:{{ fontFamily ? fontFamily : 'auto' }};" {{ ! fontFamily ? 'disabled' : '' }}>{{ font }}</option>
						<# } #>
					<# } #>
					<# if ( hasOpenGroup ) { #>
						</optgroup>
					<# } #>
				</select>
			</div>

			<div class="font-size resp-control">
				<div class="options">
					<h5 class="option-label">{{ data.l10n['font-size'] }}</h5>
					<span class="resp-btns">
						<span data-device="desktop" class="active dashicons dashicons-desktop"/>
						<span data-device="tablet" class="dashicons dashicons-tablet"/>
						<span data-device="phone" class="dashicons dashicons-smartphone"/>
					</span>
					<span class="units">
						<span data-unit="px" class="{{ data.value['font-size']['unit']['desktop'] === 'px' && 'active' }}">PX</span>
						<span data-unit="em" class="{{ data.value['font-size']['unit']['desktop'] === 'em' && 'active' }}">EM</span>
						<span data-unit="rem" class="{{ data.value['font-size']['unit']['desktop'] === 'rem' && 'active' }}">REM</span>
						<span data-unit="vw" class="{{ data.value['font-size']['unit']['desktop'] === 'vw' && 'active' }}">VW</span>
					</span>
				</div>
				<div class="values" data-type="slider">
					<input data-value="font-size" type="range" value="{{ parseInt( data.value['font-size']['desktop'], 10 ) }}" min="0" max="200" step="1" oninput="jQuery(this).trigger('change');" />
					<input data-value="font-size" type="number" value="{{ parseInt( data.value['font-size']['desktop'], 10 ) }}" min="0" max="200" step="1" oninput="jQuery(this).trigger('change');" />
				</div>
			</div>

			<!-- Font Weight -->
			<div class="font-weight base-control select-control">
				<div class="options">
					<h5 class="option-label">{{ data.l10n['font-weight'] }}</h5>
				</div>
				<div class="values">
					<select data-value="font-weight" data-id="vamtam-typography-font-weight-{{{ data.id }}}"></select>
				</div>
			</div>

			<!-- Transform -->
			<div class="transform base-control select-control">
				<div class="options">
					<h5 class="option-label">{{ data.l10n['transform']['label'] }}</h5>
				</div>
				<div class="values">
					<select data-value="transform" data-id="vamtam-typography-transform-{{{ data.id }}}">
						<option {{ data.value['transform'] === 'none'       ? 'selected="1"' : '' }} value="none">{{ data.l10n['transform']['normal'] }}</option>
						<option {{ data.value['transform'] === 'uppercase'  ? 'selected="1"' : '' }} value="uppercase">{{ data.l10n['transform']['uppercase'] }}</option>
						<option {{ data.value['transform'] === 'lowercase'  ? 'selected="1"' : '' }} value="lowercase">{{ data.l10n['transform']['lowercase'] }}</option>
						<option {{ data.value['transform'] === 'capitalize' ? 'selected="1"' : '' }} value="capitalize">{{ data.l10n['transform']['capitalize'] }}</option>
					</select>
				</div>
			</div>

			<!-- Font Style -->
			<div class="font-style base-control select-control">
				<div class="options">
					<h5 class="option-label">{{ data.l10n['font-style']['label'] }}</h5>
				</div>
				<div class="values">
					<select data-value="font-style" data-id="vamtam-typography-font-style-{{{ data.id }}}">
						<option {{ data.value['font-style'] === 'normal'  ? 'selected="1"' : '' }} value="normal">{{ data.l10n['font-style']['normal'] }}</option>
						<option {{ data.value['font-style'] === 'italic'  ? 'selected="1"' : '' }} value="italic">{{ data.l10n['font-style']['italic'] }}</option>
						<option {{ data.value['font-style'] === 'oblique' ? 'selected="1"' : '' }} value="oblique">{{ data.l10n['font-style']['oblique'] }}</option>
					</select>
				</div>
			</div>

			<!-- Decoration -->
			<# if ( ! data['exclude-decoration'] ) { #>
				<div class="decoration base-control select-control">
					<div class="options">
						<h5 class="option-label">{{ data.l10n['decoration']['label'] }}</h5>
					</div>
					<div class="values">
						<select data-value="decoration" data-id="vamtam-typography-decoration-{{{ data.id }}}">
							<option {{ data.value['decoration'] === 'none'         ? 'selected="1"' : '' }} value="none">{{ data.l10n['decoration']['none'] }}</option>
							<option {{ data.value['decoration'] === 'underline'    ? 'selected="1"' : '' }} value="underline">{{ data.l10n['decoration']['underline'] }}</option>
							<option {{ data.value['decoration'] === 'overline'     ? 'selected="1"' : '' }} value="overline">{{ data.l10n['decoration']['overline'] }}</option>
							<option {{ data.value['decoration'] === 'line-through' ? 'selected="1"' : '' }} value="line-through">{{ data.l10n['decoration']['line-through'] }}</option>
						</select>
					</div>
				</div>
			<# } #>
			<!-- Line Height -->
			<div class="line-height resp-control">
				<div class="options">
					<h5 class="option-label">{{ data.l10n['line-height'] }}</h5>
					<span class="resp-btns">
						<span data-device="desktop" class="active dashicons dashicons-desktop"/>
						<span data-device="tablet" class="dashicons dashicons-tablet"/>
						<span data-device="phone" class="dashicons dashicons-smartphone"/>
					</span>
					<span class="units">
						<span data-unit="px" class="{{ data.value['line-height']['unit']['desktop'] === 'px' && 'active' }}">PX</span>
						<span data-unit="em" class="{{ data.value['line-height']['unit']['desktop'] === 'em' && 'active' }}">EM</span>
						<span data-unit="" class="{{ data.value['line-height']['unit']['desktop'] === '' && 'active' }}">Ø</span>
					</span>
				</div>
				<div class="values" data-type="slider">
					<input data-value="line-height" type="range" value="{{ parseFloat( data.value['line-height']['desktop'], 10 ) }}" min="0" max="100" step="0.05" oninput="jQuery(this).trigger('change');" />
					<input data-value="line-height" type="number" value="{{ parseFloat( data.value['line-height']['desktop'], 10 ) }}" min="0" max="100" step="0.05" oninput="jQuery(this).trigger('change');" />
				</div>
			</div>

			<!-- Letter Spacing -->
			<div class="letter-spacing resp-control">
				<div class="options">
					<h5 class="option-label">{{ data.l10n['letter-spacing'] }}</h5>
					<span class="resp-btns">
						<span data-device="desktop" class="active dashicons dashicons-desktop"/>
						<span data-device="tablet" class="dashicons dashicons-tablet"/>
						<span data-device="phone" class="dashicons dashicons-smartphone"/>
					</span>
					<span class="units">
						<span class="active" data-unit="px">PX</span>
					</span>
				</div>
				<div class="values" data-type="slider">
					<input data-value="letter-spacing" type="range" value="{{ parseInt( data.value['letter-spacing']['desktop'], 10 ) }}" min="-100" max="100" step="1" oninput="jQuery(this).trigger('change');" />
					<input data-value="letter-spacing" type="number" value="{{ parseInt( data.value['letter-spacing']['desktop'], 10 ) }}" min="-100" max="100" step="1" oninput="jQuery(this).trigger('change');" />
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Adds backwards-compatibility for values.
	 *
	 * @access protected
	 */
	protected function add_values_backwards_compatibility() {
		$value      = $this->value();
		$old_values = array(
			'font-family'    => '',
			'font-size'      => '',
			'line-height'    => '',
			'letter-spacing' => '',
			'color'          => '',
		);

		// Font-size is now responsive (array), used to be scalar.
		if ( isset( $value['font-size'] ) && ! is_array( $value['font-size'] ) ) {
			$val = (int) filter_var( $value['font-size'], FILTER_SANITIZE_NUMBER_INT );
			$value['font-size'] = array(
				'desktop' => $val,
				'tablet'  => $val,
				'phone'   => $val,
				'unit'    => array(
					'desktop' => 'px',
					'tablet'  => 'px',
					'phone'   => 'px',
				),
			);
		}

		// Line-height is now responsive (array), used to be scalar (pixels or unitless).
		if ( isset( $value['line-height'] ) && ! is_array( $value['line-height'] ) ) {
			$is_pixels = strpos( $value['line-height'], 'px' ) !== false;
			$val = $is_pixels ? filter_var( $value['line-height'], FILTER_SANITIZE_NUMBER_INT ) : $value['line-height'];
			$value['line-height'] = array(
				'desktop' => is_numeric( $val ) ? $val : 1,
				'tablet'  => is_numeric( $val ) ? $val : 1,
				'phone'   => is_numeric( $val ) ? $val : 1,
				'unit'    => array(
					'desktop' => $is_pixels ? 'px' : '',
					'tablet'  => $is_pixels ? 'px' : '',
					'phone'   => $is_pixels ? 'px' : '',
				),
			);
		}

		// Letter-spacing is now responsive (array), used to be scalar.
		if ( isset( $value['letter-spacing'] ) && ! is_array( $value['letter-spacing'] ) ) {
			$val = (int) filter_var( $value['letter-spacing'], FILTER_SANITIZE_NUMBER_INT );
			$value['letter-spacing'] = array(
				'desktop' => is_numeric( $val ) ? $val : 0,
				'tablet'  => is_numeric( $val ) ? $val : 0,
				'phone'   => is_numeric( $val ) ? $val : 0,
				'unit'    => array(
					'desktop' => 'px',
					'tablet'  => 'px',
					'phone'   => 'px',
				),
			);
		}

		// Font-weight
		if ( ! isset( $value['font-weight'] ) ) {
			$value['font-weight'] = 'normal';
		}

		//Variant to font-weight
		if ( isset( $value['variant'] ) ) {

			$variant = explode( ' ', $value['variant'] );

			if ( count( $variant ) === 2 ) {
				list( $weight, $style ) = $variant;
			} elseif ( $variant[0] === 'italic' ) {
				$value['font-style'] = 'italic';
			} else {
				$value['font-weight'] = $variant[0];
			}
		}

		// Transform
		if ( ! isset( $value['transform'] ) || empty( $value['transform'] ) ) {
			$value['transform'] = 'none';
		}

		// Font-style
		if ( ! isset( $value['font-style'] ) || empty( $value['font-style'] ) ) {
			$value['font-style'] = 'normal';
		}

		// Decoration
		if ( ! isset( $value['decoration'] ) || empty( $value['decoration'] ) ) {
			$value['decoration'] = 'none';
		}

		$this->json['value'] = wp_parse_args( $value, $old_values );

		// Cleanup.
		if ( isset( $this->json['value']['variant'] ) ) {
			unset( $this->json['value']['variant'] );
		}
	}

	/**
	 * Don't render any content for this control from PHP.
	 */
	public function render_content() {}
}
