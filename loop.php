<?php

/**
 * Catch-all post loop
 */

VamtamEnqueues::enqueue_style_and_print( 'vamtam-blog' );

$wrapper_class = array();

$wrapper_class[] = 'regular';

if ( ! vamtam_extra_features() ) {
	$wrapper_class[] = 'masonry';
}

?>
<div class="loop-wrapper <?php echo esc_attr( implode( ' ', $wrapper_class ) ) ?>">
<?php

	do_action( 'vamtam_before_main_loop' );

	$i = 0;

	if ( ! isset( $blog_query ) ) {
		$blog_query = $GLOBALS['wp_query'];
	}

	if ( $blog_query->have_posts() ) :
		while ( $blog_query->have_posts() ) : $blog_query->the_post();
			$post_class   = array();
			$post_class[] = 'page-content post-header clearfix';

			if ( ! $blog_query->is_single() ) {
				$post_class[] = 'list-item';
			}
?>
			<div <?php post_class( implode( ' ', $post_class ) ) ?>>
				<div>
					<?php include locate_template( 'templates/post.php' );	?>
				</div>
			</div>
<?php
			$i++;
		endwhile;
	endif;

	do_action( 'vamtam_after_main_loop' );
?>
</div>

<?php

VamtamTemplates::pagination( true, $blog_query );
