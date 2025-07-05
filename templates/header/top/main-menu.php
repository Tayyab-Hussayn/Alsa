<nav id="main-menu" class="<?php if ( ! class_exists( 'Mega_Menu' ) ) echo 'vamtam-basic-menu'?>">
	<?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
	<a href="#main" title="<?php esc_attr_e( 'Skip to content', 'numerique' ); ?>" class="visuallyhidden"><?php esc_html_e( 'Skip to content', 'numerique' ); ?></a>
	<?php
		wp_enqueue_script( 'vamtam-fallback' );

		$location = apply_filters( 'vamtam_header_menu_location', 'primary-menu' );

		if ( has_nav_menu( $location ) ) {
			wp_nav_menu(array(
				'theme_location' => $location,
				'walker'         => new VamtamMenuWalker(),
				'link_before'    => '<span>',
				'link_after'     => '</span>',
			));
		}
	?>
</nav>
