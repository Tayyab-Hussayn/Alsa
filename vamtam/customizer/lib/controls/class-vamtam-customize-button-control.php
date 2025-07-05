<?php

class Vamtam_Customize_Button_Control extends Vamtam_Customize_Control {
	public $type = 'vamtam-button';

	// class name for the .button element
	public $class;

	// href attribute value
	public $href;

	// assoc array for data-* properties
	public $data;

	// button text
	public $button_title;

	/**
	 * Render the control's content.
	 */
	protected function render_content() {
		$setting_value = $this->value();

		$name = '_customize-radio-' . $this->id;

		if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif;
		if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo wp_kses( $this->description, 'vamtam-admin' ); ?></span>
		<?php endif;

		$data = array();

		if ( isset( $this->data ) ) {
			foreach ( $this->data as $attr_name => $attr_value ) {
				$data[] = 'data-' . sanitize_title_with_dashes( $attr_name ) . '="' . esc_attr( $attr_value ) . '"';
			}
		}

		$data = implode( ' ', $data );

		echo '<a href="' . ( isset( $this->href ) ? esc_attr( $this->href ) : '#' ) . '" id="' . esc_attr( $this->id ) . '" title="' . esc_attr( $this->button_title ) . '" class="button ' . esc_attr( $this->class ) . '" ' . $data . '>' . esc_html( $this->button_title ) . '</a>'; // xss ok - $data escaped above
	}

	public function enqueue() {
		wp_enqueue_script(
			'customizer-control-vamtam-button-js',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/js/button' . ( WP_DEBUG ? '' : '.min' ) . '.js',
			array( 'jquery', 'customize-base' ),
			time(),
			true
		);
	}
}
