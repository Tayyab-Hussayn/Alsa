<?php
/**
 * Archive page template
 *
 * @package vamtam/numerique
 */

VamtamFramework::set( 'page_title', get_the_archive_title() );

get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>
	<div class="page-wrapper">

		<?php VamtamTemplates::$in_page_wrapper = true; ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( VamtamTemplates::get_layout() ); ?>>
			<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) : ?>
				<div class="page-content clearfix">
					<?php rewind_posts() ?>
					<?php get_template_part( 'loop', 'archive' ) ?>
					<?php get_template_part( 'templates/share' ); ?>
				</div>
			<?php endif; ?>
		</article>

		<?php get_template_part( 'sidebar' ) ?>
	</div>
<?php endif ?>

<?php get_footer();


