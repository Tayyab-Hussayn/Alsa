<?php
/**
 * Author template
 *
 * @package vamtam/numerique
 */

$author      = $GLOBALS['authordata'];
$description = get_the_author_meta( 'description', $author->ID );

VamtamFramework::set( 'page_title', "<a href='" . get_author_posts_url( $author->ID ) . "' rel='me'>" . ( $author->data->display_name ) . '</a>' );

rewind_posts();
get_header();

?>

<div class="page-wrapper">
	<?php VamtamTemplates::$in_page_wrapper = true; ?>

	<article class="<?php echo esc_attr( VamtamTemplates::get_layout() ) ?>">
		<div class="page-content clearfix">
			<?php if ( ! empty( $description ) ) : ?>
				<div class="author-info-box clearfix">
					<div class="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'user_email', $author->ID ), 60 ); ?>
					</div>
					<div class="author-description">
						<h4><?php echo esc_html( sprintf( __( 'About %s', 'numerique' ), $author->data->display_name ) ); ?></h4>
						<?php echo wp_kses( $description, 'user_description' ) ?>
					</div>
				</div>
			<?php endif; ?>
			<?php rewind_posts() ?>
			<?php if ( have_posts() ) : ?>
				<?php get_template_part( 'loop', 'archive' ) ?>
			<?php else : ?>
				<h2 class="no-posts-by-author"><?php sprintf( esc_html__( '%s has not published any posts yet', 'numerique' ), $author->data->display_name ) ?></h2>
			<?php endif ?>
		</div>
	</article>

	<?php get_template_part( 'sidebar' ) ?>
</div>

<?php get_footer(); ?>
