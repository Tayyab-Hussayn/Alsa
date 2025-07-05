<?php

class Vamtam_Customize_Image_Select_Control extends Vamtam_Customize_Control {
	public $type = 'vamtam-image-select';

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

		foreach ( $this->choices as $value => $image ) :
			?>
			<label>
				<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link();
checked( $setting_value, $value ); ?> />
				<img src="<?php echo esc_url( $image['img'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ) ?>" title="<?php echo esc_attr( $image['alt'] ) ?>" />
			</label>
			<?php
		endforeach;
	}
}
