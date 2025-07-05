<?php

$categories_list = get_the_category_list( ', ' );

if ( $categories_list ) :
?>
	<div class="vamtam-meta-tax the-categories">
		<span class="visuallyhidden">
			<?php esc_html_e( 'Category', 'numerique' ) ?>
		</span><?php the_category( ', ' ) // this is purely because Theme Check has no ability to see that a var's value comes from a core function ?>
	</div>
<?php
endif;
