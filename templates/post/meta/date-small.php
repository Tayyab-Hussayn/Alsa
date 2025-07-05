<?php 
 $date_icon = vamtam_get_icon_html( array(
 	'name' => 'vamtam-theme-calendar',
 ) );
 if ( vamtam_get_optionb( 'post-meta', 'date' ) || is_customize_preview() ) : ?>
	<div class="post-date vamtam-meta-date" <?php VamtamTemplates::display_none( vamtam_get_optionb( 'post-meta', 'date' ) ) ?>>
		<?php echo wp_kses_post( $date_icon ); ?>
		<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
			<?php the_time( get_option( 'date_format' ) ); ?>
		</a>
	</div>
<?php endif ?>