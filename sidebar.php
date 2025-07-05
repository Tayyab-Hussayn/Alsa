<?php

$layout_type = VamtamTemplates::get_layout();

if ( 'left-only' === $layout_type ) : ?>
	<aside class="<?php echo esc_attr( apply_filters( 'vamtam_left_sidebar_class', 'left vamtam-body-sidebar', $layout_type ) ) ?>">
		<?php VamtamSidebars::get_instance()->get_sidebar( 'left' ); ?>
	</aside>
<?php endif;

VamtamTemplates::$in_page_wrapper = false;
