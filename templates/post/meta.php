<?php
/**
 * Post metadata template
 *
 * @package vamtam/numerique
 */
?>
<div class="post-meta">
	<nav>
		<?php get_template_part( 'templates/post/meta/author' ) ?>

		<?php if ( ! post_password_required() ) : ?>
			<?php get_template_part( 'templates/post/meta/comments' ) ?>
			<?php get_template_part( 'templates/post/meta/tax' ) ?>
		<?php endif ?>

		<?php if ( ! is_single() ) : ?>
			<div class="blog-buttons">
				<?php edit_post_link( '<span class="icon">' . vamtam_get_icon( 'pencil' ) . '</span><span>' . esc_html__( 'Edit', 'numerique' ) . '</span>' ) ?>
			</div>
		<?php endif ?>
	</nav>
</div>


