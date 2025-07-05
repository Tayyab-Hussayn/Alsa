<?php

class Vamtam_Customize_Multicheck_Control extends Vamtam_Customize_Control {
	public $type = 'vamtam-multicheck';

	public function enqueue() {
		wp_enqueue_script(
			'multicheck-js',
			VAMTAM_CUSTOMIZER_LIB_URL . 'assets/js/multicheck' . ( WP_DEBUG ? '' : '.min' ) . '.js',
			array( 'jquery', 'customize-base' ),
			Vamtam_Customizer::$version,
			true
		);
	}

	/**
	 * Render the control's content.
	 */
	protected function content_template() {
?>
		<span class="customize-control-title">
			{{{ data.label }}}
		</span>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<# for ( key in data.choices ) { #>
			<input id="{{ data.id }}-{{ key }}" type="checkbox" value="1" {{ key in data.value && data.value[key] !== '' ? 'checked' : '' }} data-key="{{ key }}"/>
			<label for="{{ data.id }}-{{ key }}">{{ data.choices[ key ] }}</label>
			<br>
		<# } #>

		<input type="hidden" value="" {{{ data.link }}} />
<?php
	}

	/**
	 * Don't render any content for this control from PHP.
	 */
	public function render_content() {}
}
