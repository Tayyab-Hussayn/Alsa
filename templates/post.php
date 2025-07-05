<?php

/**
 * The common code for the single and looped post template
 *
 * @package vamtam/numerique
 */

	global $post, $wp_query;

	if ( ! isset( $blog_query ) ) {
		$blog_query = $wp_query;
	}

	$article_class = array( 'post-article' );

	if ( $blog_query->is_single( $post ) ) {
		$article_class[] = 'single';
	}
?>
<div class="<?php echo esc_attr( implode( ' ', $article_class ) ); ?>" itemscope itemtype="<?php class_exists( 'VamtamBlogModule' ) && VamtamBlogModule::schema_itemtype(); ?>" itemid="<?php the_permalink() ?>">
	<?php class_exists( 'VamtamBlogModule' ) && VamtamBlogModule::schema_meta(); ?>
	<div>
		<?php
			if ( $blog_query->is_single( $post ) ) {
				include locate_template( 'templates/post/main/single.php' );
			} else {
				include locate_template( 'templates/post/main/loop.php' );
			}
		?>
	</div>
</div>


