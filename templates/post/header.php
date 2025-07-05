<?php

/**
 * Post heade template
 *
 * @package vamtam/numerique
 */

global $post;

$title = get_the_title();

if ( ! $blog_query->is_single() && ! empty( $title ) ) :
	?>
		<header class="single">
			<div class="content">
				<h5>
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute()?>" class="entry-title"><?php the_title(); ?></a>
				</h5>
			</div>
		</header>
	<?php
endif;


