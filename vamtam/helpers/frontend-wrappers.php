<?php

$GLOBALS['vamtam_block_level'] = 0;

function vamtam_render_block_add_wrapper( $html, $block ) {
	global $post;
	$is_elementor = class_exists( 'VamtamElementorBridge' ) && VamtamElementorBridge::is_elementor_pro_active() && VamtamElementorBridge::is_build_with_elementor();

	if ( $is_elementor ) {
		// We don't want the wrappers on elementor pages.
		return $html;
	}

	$GLOBALS['vamtam_block_level']--;

	if ( $GLOBALS['vamtam_block_level'] > 0 ) {
		return $html;
	}

	$attr_name = $block['blockName'] === 'vamtam-gutenberg-blocks/row' ? 'width' : 'align';

	// if not wide or full, either in the width/align attribute or as a hardcoded class,
	// because of course there would be two different ways to do the same thing in Gutenberg
	if (
		(
			! isset( $block['attrs'][ $attr_name ] ) ||
			! in_array( $block['attrs'][ $attr_name ], [ 'wide', 'full' ], true )
		) && (
			! isset( $block['attrs']['className'] ) ||
			(
				strpos( $block['attrs']['className'], 'alignwide' ) === false &&
				strpos( $block['attrs']['className'], 'alignfull' ) === false
			)
		) && (
			! isset( $block['innerHTML'] ) ||
			(
				strpos( $block['innerHTML'], 'alignwide' ) === false &&
				strpos( $block['innerHTML'], 'alignfull' ) === false
			)
		)
	) {
		return '<div class="vgblk-rw-wrapper limit-wrapper">' . $html . '</div><!-- .vgblk-rw-wrapper -->';
	}

	return $html;
}
add_filter( 'render_block', 'vamtam_render_block_add_wrapper', 100, 2 );

add_filter( 'pre_render_block', function( $pre_render, $block ) {
	$GLOBALS['vamtam_block_level']++;
}, 10, 2 );

function vamtam_remove_block_wrappers( $html ) {
	return str_replace( '</div><!-- .vgblk-rw-wrapper --><div class="vgblk-rw-wrapper limit-wrapper">', '', $html );
}
add_filter( 'the_content', 'vamtam_remove_block_wrappers' );
add_filter( 'VamtamTemplates::page_as_template::content', 'vamtam_remove_block_wrappers' );
