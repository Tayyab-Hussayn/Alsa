<?php
/**
 * Attachment template
 *
 * @package vamtam/numerique
 */

VamtamFramework::set( 'page_title', esc_html__( 'Attachment', 'numerique' ) );

get_header();
?>

<?php if ( have_posts() ) : the_post(); ?>
	<div class="page-wrapper">
		<?php VamtamTemplates::$in_page_wrapper = true; ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( VamtamTemplates::get_layout() ); ?>>
			<div class="page-content clearfix">
				<?php rewind_posts() ?>

				<div class="entry-attachment">
					<?php if ( wp_attachment_is_image() ) :
						$attachments = array_values( get_children( array(
							'post_parent' => $post->post_parent,
							'post_status' => 'inherit',
							'post_type' => 'attachment',
							'post_mime_type' => 'image',
							'order' => 'ASC',
							'orderby' => 'menu_order ID',
						) ) );
						foreach ( $attachments as $k => $attachment ) {
							if ( $attachment->ID == $post->ID ) {
								break;
							}
						}
						$k++;
						// If there is more than 1 image attachment in a gallery
						if ( count( $attachments ) > 1 ) {
							if ( isset( $attachments[ $k ] ) ) {
								// get the URL of the next image attachment
								$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
							} else {
								// or get the URL of the first image attachment
								$next_attachment_url = get_attachment_link( $attachments[0]->ID );
							}
						} else {
							// or, if there's only 1 image attachment, get the URL of the image
							$next_attachment_url = wp_get_attachment_url();
						}
					?>
						<p class="attachment"><a href="<?php echo esc_url( $next_attachment_url ) ?>" title="<?php the_title_attribute(); ?>" rel="attachment" class="thumbnail"><?php
							$attachment_size = apply_filters( 'vamtam_attachment_size', 900 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_size, 9999 ) );
						?></a></p>

						<div id="nav-below" class="navigation">
							<div class="nav-previous"><?php previous_image_link( false ); ?></div>
							<div class="nav-next"><?php next_image_link( false ); ?></div>
						</div><!-- #nav-below -->
					<?php else : ?>
						<a href="<?php echo esc_url( wp_get_attachment_url() ) ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php the_title() ?></a>
					<?php endif; ?>
				</div><!-- .entry-attachment -->

				<div class="entry-caption">
					<?php
						if ( ! empty( $post->post_excerpt ) ) {
							the_excerpt();
						}
					?>
					<?php
						if ( wp_attachment_is_image() ) {
							$metadata = wp_get_attachment_metadata();
							printf( esc_html__( 'Original size is %s pixels', 'numerique' ),
								sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
									esc_url( wp_get_attachment_url() ),
									esc_attr__( 'Link to full-size image', 'numerique' ),
									intval( $metadata['width'] ),
									intval( $metadata['height'] )
								)
							);
						}
					?>
				</div>

				<?php the_content( wp_kses( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'numerique' ), 'vamtam-a-span' ) ); ?>

				<?php get_template_part( 'templates/share' ); ?>
			</div>
		</article>

		<?php get_template_part( 'sidebar' ) ?>
	</div>
<?php endif ?>

<?php get_footer(); ?>


