<?php
/**
 * 404 page template
 *
 * @package vamtam/numerique
 */

get_header();

VamtamEnqueues::enqueue_style_and_print( 'vamtam-not-found' );

$elementor_theme_exist = function_exists( 'elementor_theme_do_location' );
?>
	<?php if ( ! $elementor_theme_exist || ! elementor_theme_do_location( 'single' ) ) : ?>
		<div class="clearfix">
			<div id="header-404">
				<div class="line-1"><?php echo esc_html_x( '404', 'page not found error', 'numerique' ) ?></div>
				<div class="line-2"><?php esc_html_e( 'Holy guacamole!', 'numerique' ) ?></div>
				<div class="line-3"><?php esc_html_e( 'Looks like this page is on vacation. Or just playing hard to get. At any rate... it is not here.', 'numerique' ) ?></div>
				<div class="line-4"><a href="<?php echo esc_url( home_url( '/' ) ) ?>"><?php echo esc_html__( '&larr; Go to the home page or just search...', 'numerique' ) ?></a></div>
			</div>
			<div class="page-404">
				<?php get_search_form(); ?>
			</div>
		</div>
	<?php endif ?>

<?php get_footer(); ?>


