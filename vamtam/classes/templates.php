<?php
/**
 * Various static template helpers
 *
 * @package vamtam/numerique
 */
/**
 * class VamtamTemplates
 */
class VamtamTemplates {

	private static $layout_cache = false;

	public static $in_page_wrapper = false;

	/**
	 * Returns the current layout type and defines VAMTAM_LAYOUT accordingly
	 *
	 * @return string current page layout
	 */
	public static function get_layout() {
		global $post;

		if( VamtamElementorBridge::is_elementor_pro_active() ) {
			return 'full';
		}

		if ( ! self::$layout_cache ) {
			$has_left  = VamtamSidebars::get_instance()->has_sidebar( 'left' );

			$layout_type = 'full';

			if ( $has_left ) {
				$layout_type = 'left-only';
			}

			self::$layout_cache = $layout_type;
		}

		return self::$layout_cache;
	}

	/**
	 * Echoes a pagination in the form of 1 2 [3] 4 5
	 */
	public static function pagination_list( $query = null, $format = '', $base = '' ) {
		if ( is_null( $query ) ) {
			$query = $GLOBALS['wp_query'];
		}

		$total_pages = (int) $query->max_num_pages;

		$output = '';

		if ( $total_pages > 1 ) {
			$big = PHP_INT_MAX;

			if ( isset( $query->query_vars['paged'] ) && $query->query_vars['paged'] ) {
				$current_page = $query->query_vars['paged'];
			} else {
				$current_page = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 );
			}

			$current_page = max( 1, $current_page );

			$output .= '<div class="navigation vamtam-pagination-wrapper">';

			$output .= '<span class="pages screen-reader-text">' . sprintf( esc_html__( 'Page %1$d of %2$d', 'numerique' ), (int) $current_page, (int) $total_pages ) . '</span>';

			$output .= paginate_links( array( // xss ok
				'base'      => empty( $base ) ? str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ) : $base,
				'format'    => empty( $format ) ? '?paged=%#%' : $format,
				'current'   => $current_page,
				'total'     => $total_pages,
				'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Prev', 'numerique' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'numerique' ) . '</span>',
			) );
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Checks whether the main content area is currently being printed
	 *
	 * @return bool True immediately before displaying the left sidebar, false after the right sidebar has been displayed
	 */
	public static function in_page_wrapper() {
		return self::$in_page_wrapper;
	}

	/**
	 * Displays the pagination code based on the theme options or $pagination_type
	 *
	 * @param  string|null $pagination_type		overrides the pagination settings
	 * @param  bool        $echo                print or return the pagination code
	 * @param  object|null $query               WP_Query object
	 */
	public static function pagination( $echo = true, $query = null ) {
		$output = apply_filters( 'vamtam_pagination', null );

		if ( is_null( $output ) ) {
			$output = self::pagination_list( $query );
		}

		if ( $echo ) {
			echo apply_filters( 'vamtam_pagination_output', $output ); // xss ok
		} else {
			return $output;
		}
	}

	/**
	 * Checks whether the current page has a title
	 *
	 * @return boolean whether the current page has a title
	 */
	public static function has_page_header() {
		$post_id = vamtam_get_the_ID();

		// the event listing has its own title below the filter
		if ( ( function_exists( 'tribe_is_events_home' ) && tribe_is_events_home() ) || is_post_type_archive( 'tribe_events' ) ) {
			return false;
		}

		if ( is_null( $post_id ) || is_search() ) {
			return true;
		}

		if ( vamtam_has_woocommerce() && is_product()  ) {
			return false;
		}

		return true;
	}

	/**
	 * Displays the page header
	 *
	 * @param  bool $placed whether the title has already been output
	 * @param  string|null $title if set, overrides the current post title
	 */
	public static function page_header( $placed = false, $title = null ) {
		if ( $placed ) return;

		global $post;

		if ( is_null( $title ) ) {
			$title = get_the_title();

			if ( empty( $title ) ) {
				$title = esc_html__( 'Untitled', 'numerique' );
			}
		}

		$description = '';

		if ( is_archive() ) {
			$description = get_the_archive_description();
		}

		if ( VamtamTemplates::has_page_header() && ! empty( $title ) ) {
			include locate_template( 'templates/header/page-title.php' );
		}
	}

	/**
	 * Comments template
	 *
	 * @param  object $comment comment data
	 * @param  array $args    comment arguments
	 * @param  int $depth   comment depth
	 */
	public static function comments( $comment, $args, $depth ) {
		include locate_template( 'templates/comment' . ( isset( $args['vamtam-layout'] ) ? '-' . $args['vamtam-layout'] : '' ) . '.php' );
	}

	/**
	 * Returns the list of all embeddable sliders to be used in the config generator
	 *
	 * @return array list of sliders
	 */
	public static function get_all_sliders() {
		return array_merge( self::get_layer_sliders(), self::get_rev_sliders() );
	}

	/**
	 * Returns the list of Revolution Slider sliders in 'revslider-ID' => 'Name' array
	 * @return array list of Revolution Slider WP sliders
	 */
	public static function get_rev_sliders( $prefix = 'revslider-' ) {
		$result = array();

		if ( class_exists( 'RevSlider' ) ) {
			$revslider = new RevSlider();
			$sliders   = $revslider->getArrSliders();

			foreach ( $sliders as $item ) {
				$result[ $prefix . $item->getAlias() ] = $item->getTitle();
			}
		}

		return $result;
	}

	/**
	 * Returns the list of LayerSlider sliders in 'layerslider-ID' => 'Name' array
	 * @return array list of LayerSlider WP sliders
	 */
	public static function get_layer_sliders( $prefix = 'layerslider-' ) {
		$result = array();

		if ( class_exists( 'LS_Sliders' ) ) {
			$sliders = LS_Sliders::find(
				array(
				'orderby' => 'date_m',
				'limit' => 10000,
				'data' => false,
				)
			);

			foreach ( $sliders as $item ) {
				$result[ $prefix . $item['id'] ] = $item['name'];
			}
		}

		return $result;
	}

	/**
	 * Prints display: none if $visible is false
	 *
	 * @param  bool $visible
	 */
	public static function display_none( $visible, $with_attr = true ) {
		if ( ! $visible ) {
			if ( $with_attr ) {
				echo 'style="display:none"';
			} else {
				echo 'display:none;';
			}
		}
	}

	public static function the_author_posts_link_with_icon() {
		global $authordata;
		if ( ! is_object( $authordata ) ) {
			return;
		}

		$link = sprintf(
			'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
			esc_url( get_author_posts_url( $authordata->ID, $authordata->user_nicename ) ),
			esc_attr( sprintf( __( 'Posts by %s', 'numerique' ), get_the_author() ) ),
			vamtam_get_icon_html( array(
				'name' => 'vamtam-theme-pencil2',
			) ) . '' . esc_html( get_the_author() )
		);

		/**
		 * Filters the link to the author page of the author of the current post.
		 *
		 * @since 2.9.0
		 *
		 * @param string $link HTML link.
		 */
		echo apply_filters( 'the_author_posts_link', $link );
	}

	/**
	 * True if the top-most .limit-wrapper has to be used for this page
	 *
	 * @return bool
	 */
	public static function had_limit_wrapper() {
		return apply_filters( 'vamtam_had_limit_wrapper', isset( $GLOBALS['vamtam_had_limit_wrapper'] ) ? $GLOBALS['vamtam_had_limit_wrapper'] : true );
	}

	public static function page_as_template( $slug, $echo = true ) {
		$posts = get_posts( array(
			'name'           => $slug,
			'post_type'      => 'any',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
		) );

		if ( ! isset( $posts[0] ) ) {
			return '';
		}

		$content = apply_filters( 'VamtamTemplates::page_as_template::content', $posts[0]->post_content );

		if ( $echo ) {
			echo apply_filters( 'VamtamTemplates::page_as_template', $content, $slug ); // xss ok
		}

		return $content;
	}
}


