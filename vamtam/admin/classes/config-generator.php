<?php
/**
 * A meta programming helper used to generated the html code needed for a configuration page
 *
 * @package vamtam/numerique
 */

/**
 * class VamtamConfigGenerator
 */
class VamtamConfigGenerator {

	/**
	 * Options page name
	 * @var string
	 */
	public $name;

	/**
	 * Options page config
	 * @var array
	 */
	protected $options;

	/**
	 * Initialize the generator
	 *
	 * @param string $name
	 * @param array $options definitions for the config page
	 */
	public function __construct( $name, $options ) {

		$this->name    = $name;
		$this->options = $options;

		if ( isset( $_POST['save-vamtam-config'] ) )
			$this->save_config();

		$this->render();
	}

	/**
	 * Save the current page config
	 */
	private function save_config() {
		vamtam_save_config( $this->options );

		global $vamtam_config_messages;

		$vamtam_config_messages .= '<div class="message updated fade"><p><strong>Updated Successfully</strong></p></div>';
	}

	/**
	 * Single options row template
	 * @param  string $template template name
	 * @param  array  $value    options row config
	 */
	protected function tpl( $template, $value ) {
		extract( $value );
		if ( ! isset( $desc ) )
			$desc = '';

		if ( ! isset( $default ) )
			$default = null;

		if ( ! isset( $class ) )
			$class = '';

		include VAMTAM_ADMIN_HELPERS . "config-generator/$template.php";
	}

	/**
	 * Renders the option page
	 */
	private function render() {
		echo '<div class="wrap vamtam-config-page">';
		echo '<form method="post" action="">';
		if ( isset( $_GET['allowreset'] ) ) {
			echo '<input type="hidden" name="doreset" value="true" />';
		}

		if ( isset( $_GET['cacheonly'] ) ) {
			echo '<input type="hidden" name="cacheonly" value="true" />';
		}

		foreach ( $this->options as $option ) {
			if ( method_exists( $this, $option['type'] ) ) {
				$this->{$option['type']}( $option );
			}
			else {
				$this->tpl( $option['type'], $option );
			}
		}

		echo '</div>'; // #theme-config

		if ( ! isset( $this->options[0]['no-save-button'] ) ) {
			$this->tpl( 'save', array() );
		}

		echo '</form>';
		echo '</div>';
	}

	/**
	 * Auto fill <select> options
	 * @param  string $type autofill type
	 * @return array        options list
	 */
	public static function get_select_target_config( $type ) {
		return self::target_config( $type );
	}

	/**
	 * Auto fill <select> options
	 * @param  string $type autofill type
	 * @return array        options list
	 */
	public static function target_config( $type ) {
		$config = array();
		switch ( $type ) {
			case 'page':
				$entries                  = get_pages( 'title_li=&orderby=name' );
				foreach ( $entries as $key => $entry )
					$config[ $entry->ID ] = $entry->post_title;
			break;
			case 'cat':
				$entries                       = get_categories( 'orderby=name&hide_empty=0' );
				foreach ( $entries as $key => $entry )
					$config[ $entry->term_id ] = $entry->name;
			break;
			case 'post':
				$entries                  = get_posts( 'orderby=title&numberposts=-1&order=ASC' );
				foreach ( $entries as $key => $entry )
					$config[ $entry->ID ] = $entry->post_title;
			break;
		}

		return $config;
	}
}



