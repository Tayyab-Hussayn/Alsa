<?php

class Vamtam_Customize_Control extends WP_Customize_Control {
	public $type     = 'vamtam-generic';
	public $compiler = false;

	public function to_json() {
		parent::to_json();

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		} else {
			$this->json['default'] = $this->setting->default;
		}

		$this->json['value']   = $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['id']      = $this->id;
	}
}
