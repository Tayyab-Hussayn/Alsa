<?php
/**
 * Catch-all template
 *
 * @package vamtam/numerique
 */

VamtamFramework::set( 'page_title', esc_html__( 'Blog', 'numerique' ) );

get_header();
?>
<div class="page-wrapper">

	<article <?php post_class( VamtamTemplates::get_layout() ) ?>>
		<div class="page-content clearfix">
			<?php get_template_part( 'loop', 'index' ); ?>
		</div>
	</article>

	<?php get_template_part( 'sidebar' ) ?>
</div>
<?php get_footer(); ?>


