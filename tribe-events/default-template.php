<?php
/**
 * Default Events Template
 * This file is the basic wrapper template for all the views if 'Default Events Template'
 * is selected in Events -> Settings -> Template -> Events Template.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/default-template.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */

use Tribe\Events\Views\V2\Template_Bootstrap;

if ( ! defined('ABSPATH') ) {
	die('-1');
}

$article_class = array( VamtamTemplates::get_layout() );

if ( ! is_singular() ) {
	VamtamFramework::set( 'page_title', tribe_get_events_title( false ) );

	array_merge( $article_class, get_post_class() );
}

get_header(); ?>
<div class="page-wrapper">
	<article class="<?php echo esc_attr( implode( ' ', $article_class ) ) ?>">
		<div class="page-content clearfix">
			<?php tribe_events_before_html(); ?>
			<?php echo tribe( Template_Bootstrap::class )->get_view_html(); ?>
			<?php tribe_events_after_html(); ?>
		</div>
	</article>

	<?php get_template_part( 'sidebar' ) ?>
</div>
<?php

get_footer();
