<?php
/**
 * Footer template
 *
 * @package vamtam/numerique
 */

$footer_onepage = ( ! is_page_template( 'onepage.php' ) );

?>

<?php if ( ! defined( 'VAMTAM_NO_PAGE_CONTENT' ) ) : ?>

			</div><!-- #main -->

		</div><!-- #main-content -->

		<?php if ( ( $footer_onepage || is_customize_preview() ) && function_exists( 'elementor_theme_do_location' ) && elementor_location_exits( 'footer' ) ) : ?>
			<div class="footer-wrapper" style="<?php VamtamTemplates::display_none( $footer_onepage, false ) ?>">
				<footer id="main-footer" class="main-footer">
					<?php elementor_theme_do_location( 'footer' ) ?>
				</footer>
			</div>
		<?php endif ?>

<?php endif // VAMTAM_NO_PAGE_CONTENT ?>
</div><!-- / #page -->

<?php if ( VamtamTemplates::had_limit_wrapper() ) : ?>
			</div> <!-- .limit-wrapper -->
<?php endif ?>

<?php wp_footer(); ?>
</body>
</html>
