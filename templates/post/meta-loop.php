<?php
/**
 * Post metadata template
 *
 * @package vamtam/numerique
 */

?>
<?php $tags = get_the_tags(); ?>
<?php if ( $tags ) : ?>
	<div class="post-meta">
		<nav>
			<div class="the-tags vamtam-meta-tax">
				<?php the_tags( '<strong>' . esc_html__( 'Tags:', 'numerique' ) . '</strong> ', ', ', '' ); ?>
			</div>
		</nav>
	</div>
<?php endif; ?>
