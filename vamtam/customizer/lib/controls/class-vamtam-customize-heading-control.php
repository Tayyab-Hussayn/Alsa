<?php

class Vamtam_Customize_Heading_Control extends Vamtam_Customize_Control {
	public $type = 'vamtam-heading';

	/**
	 * Render the control's content.
	 */
	protected function render_content() {
		if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo wp_kses( $this->description, 'vamtam-admin' ); ?></span>
		<?php endif;
	}
}
