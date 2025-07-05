<?php
/**
 * Comments template
 *
 * @package vamtam/numerique
 */

wp_reset_postdata();

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Please do not load this page directly. Thanks!' );
}

$req = get_option( 'require_name_email' ); // Checks if fields are required.

// cookies consent
$commenter = wp_get_current_commenter();
$consent   = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

// do not display anything if the post is protected or the comments are closed and there is no comment history
if (
	( ! empty( $post->post_password ) && post_password_required() ) ||
	( ! comments_open() && ! have_comments() ) ||
	! post_type_supports( get_post_type(), 'comments' ) ) {
	return;
}

$classes = 'clearboth';
if ( ! VamtamElementorBridge::is_elementor_active() ) {
	$classes .= ' limit-wrapper';
}
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<div id="comments" class="comments-wrapper">
		<?php if ( have_comments() ) : ?>
			<?php // numbers of pings and comments
			$ping_count = $comment_count = 0;
			foreach ( $comments as $comment ) {
				get_comment_type() == 'comment' ? ++$comment_count : ++$ping_count;
			}
			?>
			<h3 id="vamtam-comments-title">What do you think?</h3>
			<div class="sep-text centered keep-always">
				<div class="content">
					<?php comments_number( esc_html__( '0 Comments:', 'numerique' ), esc_html__( '1 Comment', 'numerique' ), esc_html__( '% Comments:', 'numerique' ) ); ?>
				</div>
			</div>

			<?php if ( $comment_count ) : ?>
				<div id="comments-list" class="comments">
					<?php wp_list_comments( array(
						'type'     => 'comment',
						'callback' => array( 'VamtamTemplates', 'comments' ),
						'style'    => 'div',
					) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $ping_count ) : ?>
				<div class="sep-text centered keep-always">
					<div class="content">
						<?php echo sprintf( $ping_count > 1 ? esc_html__( '%d Trackbacks:', 'numerique' ) : esc_html__( 'One Trackback:', 'numerique' ), (int) (int) $ping_count );  // xss ok ?>
					</div>
				</div>
				<div id="trackbacks-list" class="comments">
					<?php wp_list_comments( array(
						'type'       => 'pings',
						'callback'   => array( 'VamtamTemplates', 'comments' ),
						'style'      => 'div',
						'short_ping' => true,
					) ); ?>
				</div>
			<?php endif ?>
		<?php endif ?>

		<?php
			the_comments_pagination( array(
				'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous', 'numerique' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'numerique' ) . '</span>',
			) );
		?>

		<div class="respond-box">
			<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
				<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'numerique' ); ?></p>
			<?php endif; ?>
			<?php comment_form( [
				'title_reply_before' => '<h5 id="reply-title" class="comment-reply-title">',
				'title_reply_after'  => '</h5>',
			] ); ?>
		</div><!-- .respond-box -->
	</div><!-- #comments -->
</div>


