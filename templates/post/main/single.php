<?php if ( ! VamtamElementorBridge::is_build_with_elementor() ) : ?>
	<div class="limit-wrapper single-post-meta-wrapper">
		<div class="meta-top">
			<div class="meta-left has-author">
				<div class="meta-left-top with-separator">
					<span class="author vamtam-meta-author">
						<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path fill='currentColor' d='M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z' ></path></svg>
						<?php echo esc_html( _x( 'by', 'As in: "by Author Name"', 'numerique' ) ) ?><?php the_author_posts_link()?></span>
					<span class="post-date vamtam-meta-date" itemprop="datePublished">
						<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' ><path fill='currentColor' d='M12 192h424c6.6 0 12 5.4 12 12v260c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V204c0-6.6 5.4-12 12-12zm436-44v-36c0-26.5-21.5-48-48-48h-48V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H160V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H48C21.5 64 0 85.5 0 112v36c0 6.6 5.4 12 12 12h424c6.6 0 12-5.4 12-12z' ></path></svg>
						<?php the_time( get_option( 'date_format' ) ); ?>
					</span>
					<?php get_template_part( 'templates/post/meta/comments' ); ?>
				</div>
			</div>

			<?php if ( function_exists( 'sharing_display' ) ) : ?>
				<div class="meta-right">
					<?php get_template_part( 'templates/share' ); ?>
				</div>
			<?php endif ?>
		</div>
	</div>
<?php endif; ?>

<div class="post-content-outer single-post">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-media post-media-image">
			<div class='media-inner'>
				<?php the_post_thumbnail( 'full' ) ?>
			</div>
		</div>
	<?php endif; ?>

	<?php include locate_template( 'templates/post/content.php' ); ?>
	<div class="single-post-meta-bottom limit-wrapper">
		<?php get_template_part( 'templates/post/meta/tags' ); ?>
	</div>
</div>

