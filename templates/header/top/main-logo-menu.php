<div class="header-maybe-limit-wrapper header-padding">
	<div class="header-contents">
		<div class="first-row">
			<?php
				$megamenu_settings = get_option( 'megamenu_settings' );
				$mobile_top_bar = isset( $megamenu_settings['vamtam-mobile-top-bar'] ) ? stripslashes( $megamenu_settings['vamtam-mobile-top-bar'] ) : '';
			?>
			<?php if ( ! empty( $mobile_top_bar ) ) : ?>
				<div id="mobile-top-bar-above" class="mobile-top-bar"><?php echo do_shortcode( $mobile_top_bar ); ?></div>
			<?php endif ?>
			<?php get_template_part( 'templates/header/top/logo' ) ?>
		</div>

		<div class="second-row">
			<div id="menus">
				<?php get_template_part( 'templates/header/top/main-menu' ) ?>
			</div>
		</div>

		<?php do_action( 'vamtam_header_cart' ) ?>
	</div>
</div>
