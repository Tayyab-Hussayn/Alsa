<?php

/**
 * Various filters and actions configuring some of the shortcodes
 *
 * @package vamtam/numerique
 */

/**
 * class VamtamOverrides
 */
class VamtamOverrides {

	/**
	 * add filters
	 */
	public static function filters() {
		add_filter( 'excerpt_more', array( __CLASS__, 'excerpt_more' ) );

		add_filter( 'wp_title', array( __CLASS__, 'wp_title' ) );

		add_filter( 'pre_option_page_for_posts', '__return_zero' );

		add_filter( 'option_sgf_options', [ __CLASS__, 'sgf_options' ] );

		add_filter( 'oembed_dataparse', array( __CLASS__, 'oembed_dataparse' ), 90, 3 );

		add_filter( 'nav_menu_css_class', array( __CLASS__, 'nav_menu_css_class' ), 10, 2 );
		add_filter( 'nav_menu_css_class', array( __CLASS__, 'nav_menu_css_class' ), 11, 2 );

		add_filter( 'vamtam_customizer_get_options_vamtam_theme', array( __CLASS__, 'customizer_get_options' ) );

		add_filter( 'widget_title', array( __CLASS__, 'widget_title' ), 10, 3 );

		add_filter( 'wp_link_pages_link', array( __CLASS__, 'wp_link_pages_link' ), 10, 2 );

		add_filter( 'wp_head', array( __CLASS__, 'limit_wrapper' ), 10, 2 );

		add_filter( 'render_block', array( __CLASS__, 'render_block' ), 10, 2 );

		add_action( 'wp_footer', [ __CLASS__, 'footer_additions'], 5 );

		add_filter( 'show_recent_comments_widget_style', '__return_false' );

		add_filter( 'the_password_form', [ __CLASS__, 'the_password_form' ] );

		add_filter( 'wp_kses_allowed_html', [ __CLASS__, 'wp_kses_allowed_html' ], 10, 2 );

		// similar to the_content, but without actually using the_content in order to avoid confilicts with third-party code
		add_filter( 'VamtamTemplates::page_as_template::content', 'do_blocks',                      9 );
		add_filter( 'VamtamTemplates::page_as_template::content', 'wptexturize'                       );
		add_filter( 'VamtamTemplates::page_as_template::content', 'convert_smilies',               20 );
		add_filter( 'VamtamTemplates::page_as_template::content', 'shortcode_unautop'                 );
		add_filter( 'VamtamTemplates::page_as_template::content', 'prepend_attachment'                );
		add_filter( 'VamtamTemplates::page_as_template::content', 'wp_make_content_images_responsive' );

		add_filter( 'excerpt_length', [ __CLASS__, 'excerpt_length' ], 999 );

		add_filter( 'get_comment_author', [ __CLASS__, 'vamtam_get_comment_author' ], 10, 3 );

		add_filter( 'thwepof_show_review_banner_render_time', function($val) {
			return '99999999999';
		}, 11, 1 );
	}

	// Use user's display name for comment author.
	public static function vamtam_get_comment_author( $author, $comment_id, $comment ) {
		if (  empty( $author ) || empty( $comment_id ) || empty( $comment ) ) {
			return $author;
		}

		$user = get_userdata( $comment->user_id );

		if ( $user && ! empty( $user->display_name ) ) {
			$author = $user->display_name;
		}

		return $author;
	}

	public static function excerpt_length( $words ) {
		if ( ! vamtam_extra_features() ) {
			return 20;
		}

		return $words;
	}

	public static function the_password_form( $html ) {
		return preg_replace( '/(<label for="pwbox-\d*">)(.*?)(?=<input)/', '$1<span class="visuallyhidden">$2</span>', $html );
	}

	/**
	 * Custom wp_kses contexts
	 *
	 * @param  array  $allowedtags
	 * @param  string $context
	 * @return array
	 */
	public static function wp_kses_allowed_html( $allowedtags, $context ) {
		if ( $context === 'vamtam-a-span' ) {
			return [
				'a' => [
					'aria-describedby' => true,
					'aria-details'     => true,
					'aria-label'       => true,
					'aria-labelledby'  => true,
					'aria-hidden'      => true,
					'class'            => true,
					'id'               => true,
					'style'            => true,
					'title'            => true,
					'role'             => true,
					'data-*'           => true,
					'href'             => true,
					'target'           => true,
				],
				'span' => [
					'class'  => true,
					'id'     => true,
					'style'  => true,
					'data-*' => true,
				],
			];
		}

		if ( $context === 'vamtam-admin' ) {
			return [
				'a' => [
					'aria-describedby' => true,
					'aria-details'     => true,
					'aria-label'       => true,
					'aria-labelledby'  => true,
					'aria-hidden'      => true,
					'class'            => true,
					'id'               => true,
					'style'            => true,
					'title'            => true,
					'role'             => true,
					'data-*'           => true,
					'href'             => true,
					'target'           => true,
				],
				'span' => [
					'class'  => true,
					'id'     => true,
					'style'  => true,
					'data-*' => true,
				],
				'code' => [],
				'br'   => [],
				'p'    => [
					'class' => true,
					'id'    => true,
				],
			];
		}

		return $allowedtags;
	}

	public static function sgf_options( $opt ) {
		$opt['font_display'] = 'swap';

		return $opt;
	}

	/**
	 * Extra templates
	 */
	public static function footer_additions() {
		get_template_part( 'templates/overlay-search' );

		get_template_part( 'templates/side-buttons' );
	}

	/**
	 * @param  string
	 * @param  array
	 * @return string
	 */
	public static function render_block( $block_content, $block ) {
		if ( $block['blockName'] === 'core/cover' ) {
			if ( isset( $block['attrs']['align'] ) && in_array( $block['attrs']['align'], array( 'left', 'right' ), true ) ) {
				$block_content = str_replace( "align{$block['attrs']['align']}", '', $block_content );
				$block_content = "<div class='vamtam-wp-block-cover-wrapper align{$block['attrs']['align']}'>" . $block_content . '</div>';
			}
		}

		return $block_content;
	}

	/**
	 * @return  bool	true if the pages needs the outer .limit-wrapper
	 */
	public static function limit_wrapper() {
		global $vamtam_theme, $post;

		if ( VamtamElementorBridge::is_elementor_pro_active() && VamtamElementorBridge::is_build_with_elementor() ) {
			// If Elementor pro is used and is active dont apply limit-wrapper.
			$GLOBALS['vamtam_had_limit_wrapper'] = false;
		} else if ( is_404() ) {
			// 404 should be full width.
			$GLOBALS['vamtam_had_limit_wrapper'] = false;
		} else if ( vamtam_has_woocommerce() && is_product() && VamtamElementorBridge::is_elementor_pro_active() ) {
			// Product pages are full width when Elementor Pro is active.
			$GLOBALS['vamtam_had_limit_wrapper'] = false;
		} else {
			$GLOBALS['vamtam_had_limit_wrapper'] =
				! is_singular() || ( vamtam_has_woocommerce() && is_product() ) ||
				( class_exists( 'VamtamTemplates' ) && VamtamTemplates::get_layout() !== 'full' ) ||
				! (
					( class_exists( '\Elementor\Plugin' ) ) ||
					( class_exists( 'Vamtam_Elements_B' ) && Vamtam_Elements_B::is_beaver_used() ) ||
					( is_callable( 'has_blocks' ) && has_blocks() )
				);
			}
	}


	/**
	 * Wrap the current page number in a span
	 * @param  string $link
	 * @param  int    $i
	 * @return string
	 */
	public static function wp_link_pages_link( $link, $i ) {
		if ( strpos( $link, '<a' ) !== 0 ) {
			$link = '<span class="current" aria-current="page">' . $link . '</span>';
		}

		return $link;
	}

	public static function widget_title( $title = '', $instance = null, $id_base = null ) {
		if ( ! is_null( $instance ) && $id_base === 'recent-posts' && empty( $instance['title'] ) ) {
			return '';
		}

		return $title;
	}

	public static function wp_title( $title ) {
		if ( empty( $title ) && ( is_home() || is_front_page() ) ) {
			$description = get_bloginfo( 'description' );
			return get_bloginfo( 'name' ) . ( ! empty( $description ) ? ' | ' . $description : '' );
		}

		return $title;
	}

	/**
	 * Remove unnecessary menu item classes
	 *
	 * @param  array  $classes current menu item classes
	 * @param  object $item    menu item
	 * @param  object $args    menu item args
	 * @return array           filtered classes
	 */
	public static function nav_menu_css_class( $classes, $item ) {
		if ( isset( $item->url ) && strpos( $item->url, '#' ) !== false && ( $key = array_search( 'mega-current-menu-item', $classes ) ) !== false ) {
			unset( $classes[ $key ] );
			$classes[] = 'maybe-current-menu-item';

			$GLOBALS['vamtam_menu_had_hash'] = true;
		}

		if ( isset( $GLOBALS['vamtam_menu_had_hash'] ) && $GLOBALS['vamtam_menu_had_hash'] ) {
			$classes = array_diff( $classes, array( 'mega-current-menu-item', 'mega-current-menu-ancestor', 'mega-current-menu-parent' ) );
		}

		return $classes;
	}

	/**
	 * Wrap oEmbeds in .vamtam-video-frame
	 *
	 * @param  string $output original oembed output
	 * @param  object $data   data from the oEmbed provider
	 * @param  string $url    original embed url
	 * @return string         $output wrapped in additional html
	 */
	public static function oembed_dataparse( $output, $data, $url ) {
		if ( $data->type == 'video' && ! ( has_blocks() && doing_filter( 'the_content' ) ) )
			return '<div class="vamtam-video-frame">' . $output . '</div>';

		return $output;
	}

	/**
	 * Sets the excerpt ending
	 *
	 * @param  string $more original ending
	 * @return string         excerpt ending
	 */
	public static function excerpt_more( $more ) {
		return '...';
	}
}

