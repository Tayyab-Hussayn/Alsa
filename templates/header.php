<?php
	$beaver_header_ids = class_exists( 'FLThemeBuilderLayoutData' ) ? FLThemeBuilderLayoutData::get_current_page_header_ids() : array();

	if ( empty( $beaver_header_ids ) ) {
		include locate_template( 'templates/header/top.php' );
	} else {
		FLThemeBuilderLayoutRenderer::render_header();
	}

	do_action( 'vamtam_after_top_header' );
