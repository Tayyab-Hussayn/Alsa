<?php

/**
 * Post heade template
 *
 * @package vamtam/numerique
 */

global $post;

$title = get_the_title();

?>

<header class="single">
	<div class="content">
		<h4>
			<a href="<?php the_permalink() ?>" title="<?php the_title_attribute()?>"><?php
				if ( ! empty( $title ) ) {
					the_title();
				} else {
					the_date();
				}
			?></a>
		</h4>
	</div>
</header>

