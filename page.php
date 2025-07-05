<?php
/**
 * Single page template
 *
 * @package vamtam/numerique
 */

get_header();
?>

<?php if ( have_posts() ) : the_post(); ?>

	<?php if( ! VamtamElementorBridge::is_location_template_exits('single') ): ?>
			<div class="page-wrapper">
	<?php endif; ?>

		<?php VamtamTemplates::$in_page_wrapper = true; ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( VamtamTemplates::get_layout() ); ?>>
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<div class="page-content clearfix the-content-parent">
				<?php
					the_content();

					wp_link_pages( array(
						'before' => '<nav class="navigation post-pagination" role="navigation"><span class="screen-reader-text">' . esc_html__( 'Pages:', 'numerique' ) . '</span>',
						'after'  => '</nav>',
					) );
				?>
				<?php get_template_part( 'templates/share' ); ?>
			</div>
		<?php endif; ?>
			<?php comments_template( '', true ); ?>
		</article>

		<?php
			if( ! VamtamElementorBridge::is_elementor_pro_active() ) {
				get_template_part( 'sidebar' );
			}
		?>

	<?php if( ! VamtamElementorBridge::is_location_template_exits('single') ): ?>
			</div> <!-- End of .page-wrapper -->
	<?php endif; ?>


<?php endif;

get_footer();


