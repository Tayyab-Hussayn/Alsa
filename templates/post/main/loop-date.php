<?php
/**
 * Post (in loop) date
 *
 * @package vamtam/numerique
 */


$title = get_the_title();

?>
<div class="post-row-left vamtam-meta-date">
	<div class="post-date">
		<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
			<span class="top-part">
				<?php the_time( 'd' ) ?>
			</span>
			<span class="bottom-part">
				<?php the_time( "m 'y" ) ?>
			</span>
		</a>
	</div>

	<?php get_template_part( 'templates/post/meta/author' ) ?>

	<?php get_template_part( 'templates/post/meta/comments' ); ?>

</div>


