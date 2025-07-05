<?php
/**
 * Single post template
 *
 * @package vamtam/numerique
 */

get_header();

VamtamEnqueues::enqueue_style_and_print( 'vamtam-blog' );

?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<?php if( !VamtamElementorBridge::is_location_template_exits('single') ): ?>
			<div class="page-wrapper">
		<?php endif; ?>
			<?php VamtamTemplates::$in_page_wrapper = true; ?>
			<article <?php post_class( 'single-post-wrapper ' . VamtamTemplates::get_layout() )?>>
				<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
					<div class="page-content loop-wrapper clearfix full clearfix">
						<?php get_template_part( 'templates/post' ); ?>

						<?php comments_template(); ?>
					</div>
				<?php endif; ?>
			</article>

			<?php get_template_part( 'sidebar' ) ?>

		<?php if( !VamtamElementorBridge::is_location_template_exits('single') ): ?>
				</div> <!-- End of .page-wrapper -->
		<?php endif; ?>

	<?php endwhile;
endif;

get_footer();


