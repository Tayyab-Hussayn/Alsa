<?php
	/**
	 * Actual, visible header. Includes the logo, menu, etc.
	 * @package vamtam/numerique
	 */

	$style_attr = '';

	if ( class_exists( 'Vamtam_Importers_E' ) && is_callable( array( 'Vamtam_Importers_E', 'set_menu_locations' ) ) ) {
		Vamtam_Importers_E::set_menu_locations();
	}

	VamtamEnqueues::enqueue_style_and_print( 'vamtam-header' );
	VamtamEnqueues::enqueue_style_and_print( 'vamtam-theme-mobile-header' );
?>
<div class="fixed-header-box" style="<?php echo esc_attr( $style_attr ) ?>">
	<header class="main-header layout-logo-menu layout-single-row header-content-wrapper">
		<?php get_template_part( 'templates/header/top/main', 'logo-menu' ) ?>
	</header>

	<?php do_action( 'vamtam_header_box' ); ?>
</div>


