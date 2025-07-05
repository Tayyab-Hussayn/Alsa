<div class="logo-wrapper">
	<?php
		global $vamtam_theme_customizer, $vamtam_theme;

		$logo_type = vamtam_get_option( 'header-logo-type' );

		$logo    = isset( $vamtam_theme['custom-header-logo'] ) ? $vamtam_theme['custom-header-logo'] : '';
		$logo_id = attachment_url_to_postid( $logo );

		// attempt to load the value of the core logo option
		// it it's not found, migrate our old option to the core option
		$core_logo_id    = get_theme_mod( 'custom_logo' );
		$core_logo_image = $core_logo_id ? wp_get_attachment_image_src( $core_logo_id, 'full' ) : '';

		if ( $core_logo_image ) {
			$logo    = $core_logo_image[0];
			$logo_id = $core_logo_id;
		} else if ( ! empty( $logo_id ) ) {
			// set the core logo option and delete our old option
			set_theme_mod( 'custom_logo', $logo_id );
			$vamtam_theme_customizer->set_options( array_diff_key( $vamtam_theme_customizer->get_options(), array( 'custom-header-logo' => '' ) ) );
		}

		$logo_size = array(
			'width'  => 0,
			'height' => 0,
		);

		$logo_style = '';

		if ( $logo_type == 'image' && $logo_id ) {
			$logo_meta = get_post_meta( $logo_id, '_wp_attachment_metadata', true );

			$size_coefficient = get_post_mime_type( $logo_id ) === 'image/svg+xml' ? 1 : 2;

			$logo_size = array(
				'width'  => isset( $logo_meta['width'] ) ? intval( $logo_meta['width'] ) / $size_coefficient : 0,
				'height' => isset( $logo_meta['height'] ) ? intval( $logo_meta['height'] ) / $size_coefficient : 0,
			);

			if ( ! empty( $logo_size['height'] ) ) {
				$logo_style = "max-height: {$logo_size['height']}px;";
			}
		}
	?>
	<div class="logo-tagline">
		<a href="<?php echo esc_url( home_url( '/' ) ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ) ?>" class="logo <?php if ( empty( $logo ) || $logo_type === 'site-title' ) echo 'text-logo' ?>" style="min-width:<?php echo (int) $logo_size['width'] / 2 ?>px"><?php
			if ( isset( $logo ) && ! empty( $logo ) && $logo_type === 'image' ) :
			?>
				<?php
					// The only reason for the repeated calls to image_hwstring()
					// is because if we do not do this, we will get a false positive
					// on the Envato Theme Check
				?>
				<img src="<?php echo esc_url( $logo ) ?>" alt="<?php bloginfo( 'name' )?>" class="normal-logo" <?php echo image_hwstring( $logo_size['width'], $logo_size['height'] ) ?> style="<?php echo esc_attr( $logo_style ) ?>"/>
			<?php
			else :
				bloginfo( 'name' );
			endif;
			?>
		</a>
		<?php
			$description = get_bloginfo( 'description' );
			if ( ! empty( $description ) ) :
		?>
				<span class="site-tagline"><?php echo wp_kses_post( $description ) ?></span>
		<?php endif ?>
	</div>

	<div class="mobile-logo-additions">
		<?php // show this for any header layout if Max Mega Menu is missing ?>
		<?php if ( has_nav_menu( 'primary-menu' ) ) : ?>
			<div id="vamtam-fallback-main-menu-toggle"></div>
		<?php endif ?>
	</div>
</div>

