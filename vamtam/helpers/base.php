<?php

function vamtam_get_the_ID() {
	global $post;

	if ( vamtam_has_woocommerce() && is_woocommerce() && ! is_singular( array( 'page', 'product' ) ) ) {
		return wc_get_page_id( 'shop' );
	}



	return ! is_archive() && ! is_search() && isset( $post ) ? $post->ID : null;
}

/**
 * Wrapper around get_post_meta which takes special pages into account
 *
 * @uses get_post_meta()
 *
 * @param  int    $post_id Post ID.
 * @param  string $key     Optional. The meta key to retrieve. By default, returns data for all keys.
 * @param  bool   $single  Whether to return a single value.
 * @return mixed           Will be an array if $single is false. Will be value of meta data field if $single is true.
 */
function vamtam_post_meta( $post_id, $meta = '', $single = false ) {
	$real_id = vamtam_get_the_ID();

	if ($real_id && $post_id != $real_id)
		$post_id = $real_id;

	return get_post_meta( $post_id, $meta, $single );
}

function vamtam_fix_shortcodes( $content ) {
	// array of custom shortcodes requiring the fix
	$block = join( '|', apply_filters( 'vamtam_escaped_shortcodes', array() ) );

	// opening tag
	$rep = preg_replace( "/(<p>\s*)?\[($block)(\s[^\]]+)?\](\s*<\/p>|<br \/>)?/",'[$2$3]', $content );

	// closing tag
	$rep = preg_replace( "/(?:<p>\s*)?\[\/($block)](?:\s*<\/p>|<br \/>)?/",'[/$1]', $rep );

	return $rep;
}
add_filter( 'the_content', 'vamtam_fix_shortcodes' );
add_filter( 'fl_builder_before_render_shortcodes', 'vamtam_fix_shortcodes' );

function vamtam_recursive_preg_replace( $regex, $replace, $subject ) {
	if ( is_array( $subject ) || is_object( $subject ) ) {
		foreach ( $subject as &$sub ) {
			$sub = vamtam_recursive_preg_replace( $regex, $replace, $sub );
		}
		unset( $sub );
	}
	if ( is_string( $subject ) ) {
		$subject = preg_replace( $regex, $replace, $subject );
	}
	return $subject;
}

function vamtam_get_google_fonts_subsets() {
	global $vamtam_fonts;

	$subsets = array();

	foreach ( $vamtam_fonts as $font ) {
		if ( isset( $font['gf'] ) && $font['gf'] ) {
			$subsets = array_merge( $subsets, $font['subsets'] );
		}
	}

	sort( $subsets );

	return array_combine( $subsets, $subsets );
}

function vamtam_get_fonts_by_family() {
	global $vamtam_fonts, $vamtam_fonts_by_family;

	if ( ! isset( $vamtam_fonts_by_family ) ) {
		$vamtam_fonts_by_family = array();

		foreach ( $vamtam_fonts as $id => $font ) {
			$vamtam_fonts_by_family[ $font['family'] ] = $id;
		}
	}

	return $vamtam_fonts_by_family;
}

function vamtam_use_accent_preview() {
	return ! ( ( isset( $GLOBALS['is_IE'] ) && $GLOBALS['is_IE'] ) || ( isset( $GLOBALS['is_edge'] ) && $GLOBALS['is_edge'] ) ); // IE and Edge do not support CSS variables and do not intend to any time soon (comment added 9 June 2016)
}

/**
 * Returns an array of pages in the following format:
 * 'page_slug' => 'Page title'
 *
 * @param  array  $options elements to be prepended to the result
 * @return array
 */
function vamtam_get_pages( $options = array(), $prefix = '' ) {
	$posts = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );

	foreach ( $posts as $post ) {
		$options[ $prefix . $post->post_name ] = strip_tags( $post->post_title );
	}

	return $options;
}

/**
 * Some features are only enabled if the accompanying plugin is enabled
 * @return bool
 */
function vamtam_extra_features() {
	return did_action( 'VamtamElementorIntregration/loaded' );
}

function vamtam_theme_supports( $feature, $relation = 'OR' ) {
	$supported = false;

	if ( is_array( $feature ) ) {
		// Multiple features.
		$not_supported_found = false;
		foreach ( $feature as $ftr ) {
			if ( current_theme_supports( $ftr ) || current_theme_supports( 'vamtam-elementor-widgets', $ftr ) ) {
				if ( $relation === 'OR' ) {
					$supported = true;
					break;
				}
			} else {
				if ( $relation === 'AND' ) {
					$not_supported_found = true;
					break;
				}
			}
		}

		if ( $relation === 'AND' && $not_supported_found === false ) {
			$supported = true;
		}
	} else {
		// Single feature.
		if ( current_theme_supports( $feature ) || current_theme_supports( 'vamtam-elementor-widgets', $feature ) ) {
			$supported = true;
		}
	}

	return $supported;
}

/**
 * Alias for function_exists('is_woocommerce')
 * @return bool whether WooCommerce is active
 */
function vamtam_has_woocommerce() {
	return function_exists( 'is_woocommerce' );
}
