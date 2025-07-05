<?php
/**
 * Post content template
 *
 * @package vamtam/numerique
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $multipage;

$content = get_the_content();

if ( ! VamtamElementorBridge::is_build_with_elementor() && empty( $content ) && has_post_thumbnail() && ! $multipage ) return;

?>
<div class="post-content the-content the-content-parent">
	<?php
		do_action( 'vamtam_before_post_content' );

		if ( $blog_query->is_single( $post ) ) {
			the_content();
		} else {
			the_excerpt();
		}

		do_action( 'vamtam_after_post_content' );

	?>
</div>

<?php

wp_link_pages( array(
	'before' => '<nav class="navigation post-pagination" role="navigation"><span class="screen-reader-text">' . esc_html__( 'Pages:', 'numerique' ) . '</span>',
	'after'  => '</nav>',
) );
