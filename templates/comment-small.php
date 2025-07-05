<div id="comment-<?php comment_ID() ?>" <?php comment_class( ( $args['has_children'] ? 'has-children' : '' ) ) ?>>

	<div class="comment-inner">
		<?php echo vamtam_get_icon_html( array( // xss ok
			'name' => 'vamtam-theme-quote',
		) ); ?>
		<header class="comment-header">
			<h3 class="comment-author-link"><?php comment_author_link(); ?></h3>
			<?php
				if ( ( ! isset( $args['reply_allowed'] ) || $args['reply_allowed'] ) && ( $args['type'] == 'all' || get_comment_type() == 'comment' ) ) :
					comment_reply_link( array_merge( $args, array(
						'reply_text' => esc_html__( 'Reply', 'numerique' ),
						'login_text' => esc_html__( 'Log in to reply.', 'numerique' ),
						'depth'      => $depth,
						'before'     => '<h6 class="comment-reply-link">',
						'after'      => '</h6>',
					) ) );
				endif;
			?>
		</header>
		<?php comment_text() ?>
		<footer class="comment-footer">
			<div title="<?php comment_time(); ?>" class="comment-time"><?php comment_date(); ?></div>
			<?php edit_comment_link( sprintf( '[%s]', esc_html__( 'Edit', 'numerique' ) ) ) ?>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<span class='unapproved'><?php esc_html_e( 'Your comment is awaiting moderation.', 'numerique' ); ?></span>
			<?php endif ?>
		</footer>
	</div>
