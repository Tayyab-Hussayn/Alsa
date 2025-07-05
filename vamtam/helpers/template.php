<?php

/**
 * Various template helpers
 */

function vamtam_theme_background_styles() {
	global $post;

	$post_id = vamtam_get_the_ID();

	if (is_null( $post_id )) return;

	$bgcolor      = esc_html( vamtam_sanitize_accent( vamtam_post_meta( $post_id, 'background-color', true ), 'css' ) );
	$bgimage      = esc_url( vamtam_post_meta( $post_id, 'background-image', true ) );
	$bgrepeat     = esc_html( vamtam_post_meta( $post_id, 'background-repeat', true ) );
	$bgsize       = esc_html( vamtam_post_meta( $post_id, 'background-size', true ) );
	$bgattachment = esc_html( vamtam_post_meta( $post_id, 'background-attachment', true ) );
	$bgposition   = esc_html( vamtam_post_meta( $post_id, 'background-position', true ) );

	$page_style = '';
	if ( ! empty( $bgcolor ) ) {
		$page_style .= "background-color:$bgcolor;";
	}
	if ( ! empty( $bgimage ) ) {
		$page_style .= "background-image:url('$bgimage');";

		if ( ! empty( $bgrepeat ) ) {
			$page_style .= "background-repeat:$bgrepeat;";
		}

		if ( ! empty( $bgattachment ) ) {
			$page_style .= "background-attachment:$bgattachment;";
		}

		if ( ! empty( $bgsize ) ) {
			$page_style .= "background-size:$bgsize;";
		}
	}

	if ( ! empty( $page_style ) ) {
		echo "<style>html,#main-content{{$page_style}}</style>"; // xss ok, escaped above
	}
}
add_action( 'wp_head', 'vamtam_theme_background_styles' );

function vamtam_body_classes( $body_class ) {
	global $post;

	$has_page_header   = VamtamTemplates::has_page_header() && ! is_404();
	$has_middle_header = '';
	$is_responsive     = VamtamFramework::get( 'is_responsive' );

	$body_class[] = 'full';
	$body_class[] = 'header-layout-logo-menu';

	$body_class_conditions = array(
		'no-page-header'                     => ! $has_page_header,
		'has-page-header'                    => $has_page_header,
		'no-middle-header'                   => ! $has_middle_header,
		'has-middle-header'                  => $has_middle_header,
		'responsive-layout'                  => $is_responsive,
		'fixed-layout'                       => ! $is_responsive,
		'has-post-thumbnail'                 => is_singular() && has_post_thumbnail(),
		'single-post-one-column'             => is_single(),
		'has-blocks'                         => ! is_archive() && is_callable( 'has_blocks' ) && has_blocks() && ( ! class_exists( 'FLBuilderModel' ) || ! FLBuilderModel::is_builder_enabled() ),
		'vamtam-limited-layout'              => ! vamtam_extra_features(),
		'vamtam-is-elementor'                => VamtamElementorBridge::is_build_with_elementor(),
		'elementor-active'                   => VamtamElementorBridge::is_elementor_active(),
		'elementor-pro-active'               => VamtamElementorBridge::is_elementor_pro_active(),
		'vamtam-wc-cart-empty'               => ! vamtam_has_woocommerce() || WC()->cart->get_cart_contents_count() === 0,
		'wc-product-gallery-zoom-active'     => current_theme_supports( 'wc-product-gallery-zoom' ),
		'wc-product-gallery-lightbox-active' => current_theme_supports( 'wc-product-gallery-lightbox' ),
		'wc-product-gallery-slider-active'   => current_theme_supports( 'wc-product-gallery-slider' ),
		'vamtam-is-wishlist'                 => defined( 'WOOSW_VERSION' ) && is_page() && $post->ID == get_option( 'woosw_page_id' ),
		'vamtam-font-smoothing'              => vamtam_extra_features() && \Vamtam_Elementor_Utils::get_general_theme_site_setting( 'font_smoothing' ),
	);

	foreach ( $body_class_conditions as $class => $cond ) {
		if ( $cond ) {
			$body_class[] = $class;
		}
	}

	$body_class[] = 'layout-' . VamtamTemplates::get_layout();

	return $body_class;
}
add_filter( 'body_class', 'vamtam_body_classes' );
