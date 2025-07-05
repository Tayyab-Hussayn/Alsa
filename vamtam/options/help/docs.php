<?php
	$theme_setup = admin_url( 'admin.php?page=vamtam_theme_setup' );
?>
<div id="vamtam-ts-help" class="vamtam-ts">
	<div id="vamtam-ts-side">
		<?php VamtamPurchaseHelper::dashboard_navigation(); ?>
	</div>
	<div id="vamtam-ts-main">
		<div class="main-content">
			<div class="vamtam-sd vamtam-box-wrap">
				<header>
					<h3><?php esc_html_e( 'Support and Documentation', 'numerique' ); ?></h3>
				</header>
				<div class="content">
					<span><?php esc_html_e( 'Thank you for purchasing our theme! Feel free to browse our knowledgebase for videos and extra help.', 'numerique' ); ?></span>
					<div class="cards-wrap">
						<span class="vamtam-box-btn">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 37"><path fill="#0073AA" d="M33.5.3l-14.9 4-.8.1H17L2.5.3C2 0 1.3.1.8.5S0 1.6 0 2.2v27.4c0 .5.2 1 .6 1.5.3.5.8.8 1.3 1l14.5 4a6.5 6.5 0 0 0 1.4.2h.3a8.5 8.5 0 0 0 1.1-.2l14.9-4c.5-.2 1-.5 1.3-1 .4-.4.6-1 .6-1.5V2.2a2 2 0 0 0-.8-1.6c-.5-.4-1-.5-1.7-.3zM16.9 34L2.5 30l-.2-.1V2.6l14.1 4h.5v27.3zm16.9-4.3l-.2.2-.1.1L19 33.8V6.5h.1l14.6-4v27zM22.6 13h.3l7.9-2.2c.2-.1.5-.3.6-.6.2-.2.2-.5.1-.8 0-.3-.2-.5-.5-.7-.3-.1-.6-.2-.9-.1l-7.8 2.2-.7.5c-.1.3-.2.6-.1 1 0 .2.2.4.4.5l.7.2zm0 6.8h.3l7.9-2.3c.2 0 .5-.3.6-.5.2-.3.2-.6.1-.9 0-.3-.2-.5-.5-.6-.3-.2-.6-.2-.9-.2l-7.8 2.3c-.3 0-.5.2-.7.5-.1.3-.2.6-.1.9 0 .2.2.4.4.6l.7.2zm0 6.7h.3l7.9-2.2c.2-.1.5-.3.6-.6.2-.2.2-.5.1-.8 0-.3-.2-.5-.5-.7-.3-.1-.6-.2-.9-.1l-7.8 2.2-.7.5c-.1.3-.2.6-.1 1 0 .2.2.4.4.5l.7.2zM13.9 11L6 8.7l-.8.1c-.3.2-.5.4-.6.7l.1.8c.2.3.4.5.7.6l7.9 2.2h.3c.2 0 .4 0 .6-.2.2-.1.4-.3.5-.6 0-.3 0-.6-.2-.9a1 1 0 0 0-.6-.5zm0 6.8L6 15.4c-.3 0-.6 0-.8.2-.3.1-.5.3-.6.6l.1.9.7.5 7.9 2.2h.3l.6-.1.5-.6c0-.3 0-.6-.2-.9-.1-.3-.3-.5-.6-.5zm0 6.7c.3.1.5.3.6.5.2.3.2.6.2 1l-.5.5-.6.2h-.3l-8-2.2-.6-.6a1 1 0 0 1 0-.8c0-.3.2-.5.5-.7.2-.1.5-.2.8-.1l7.9 2.2z"/></svg>
							<a href="https://elementor.support.vamtam.com/support/home" class="button" target="_blank">
								<?php esc_html_e( 'Knowledgebase', 'numerique' ); ?>
							</a>
						</span>
						<span class="vamtam-box-btn">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 37"><path fill="#0073AA" d="M26.8 7.4c-.4-1-.9-2-1.5-2.8a9 9 0 0 0-2.2-2.2A10.3 10.3 0 0 0 16.8.6a11.4 11.4 0 0 0-8 3.2 12.5 12.5 0 0 0-3.4 7.9 7.3 7.3 0 0 0-3.9 2.9 8.4 8.4 0 0 0-.9 8 9 9 0 0 0 1.7 2.6 7.6 7.6 0 0 0 5.3 2.4h1c.3 0 .6 0 .8-.3.2-.2.3-.5.3-.8a1.1 1.1 0 0 0-1.1-1.1h-1a5 5 0 0 1-3.8-1.8 6 6 0 0 1-1.6-4.1 6 6 0 0 1 1.3-3.8 5 5 0 0 1 3.2-2l1-.2v-1h-.1a10.6 10.6 0 0 1 2.5-7A8.8 8.8 0 0 1 16.8 3c2.2 0 4 .5 5.3 1.5 1.3 1 2.2 2.5 2.8 4.4l.3.8h.8c1 0 2 .2 3 .6 1 .4 1.7 1 2.5 1.7a7.7 7.7 0 0 1 2.3 5.4 9.5 9.5 0 0 1-1.7 5.5c-.6.8-1.3 1.4-2 1.9a5 5 0 0 1-2.7.7h-.3a1 1 0 0 0-.8.3 1 1 0 0 0-.3.8c0 .3 0 .6.3.8.2.2.5.3.8.3a8 8 0 0 0 3.8-1c1-.6 2-1.4 2.8-2.4a11.7 11.7 0 0 0 2.3-7 9.8 9.8 0 0 0-5.6-8.8c-1.1-.5-2.3-.9-3.6-1zm-4 18.1l.2.2c.2.2.2.4.2.6 0 .2 0 .3-.2.5l-4.2 4.5-.2.3-.3.2a.9.9 0 0 1-.6 0 .5.5 0 0 1-.3-.2l-.3-.2-4-4.5a.8.8 0 0 1-.3-.6c0-.3 0-.5.2-.6l.3-.2c.2-.2.4-.2.6-.2l.6.3 2.4 2.6V17a1.1 1.1 0 0 1 1.1-1.1 1 1 0 0 1 1.1 1.1v11.3l2.5-2.7c.2-.2.3-.3.6-.3.2 0 .4.1.5.3z"/></svg>
							<a href="https://elementor.support.vamtam.com/support/solutions/articles/245218-vamtam-elementor-themes-how-to-install-the-theme-via-the-admin-panel-" class="button" target="_blank">
								<?php esc_html_e( 'Installation Guide', 'numerique' ); ?>
							</a>
						</span>
						<span class="vamtam-box-btn">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 37"><path fill="#0073AA" d="M15.5 31.9l-3.3-5.1a.8.8 0 0 0-1-.3l-.4.2c-.2 0-.3.2-.3.4 0 .3 0 .5.2.7l2 3.2-.5-.1a12.4 12.4 0 0 1-4.9-2.5 13.7 13.7 0 0 1-3.5-4.2A13.5 13.5 0 0 1 3 14c.5-1.7 1.3-3.2 2.5-4.5C6.7 8.2 8 7.1 9.6 6.3c.3-.2.4-.4.5-.7.1-.3.1-.5 0-.8a1 1 0 0 0-.7-.5 1 1 0 0 0-.8 0 14.8 14.8 0 0 0-4.8 3.8 15 15 0 0 0-3.6 8.3 17.2 17.2 0 0 0 .5 6A16 16 0 0 0 6 30a16.4 16.4 0 0 0 5.8 3h.1L9 34.4c-.2 0-.3.2-.4.4v.6l.2.3.4.4h.6l5.3-2.8.3-.1.2-.3.2-.2v-.3l-.1-.3-.2-.3zM30 11.5a16 16 0 0 1 1.7 8.8 14.9 14.9 0 0 1-1.9 5.8 15.2 15.2 0 0 1-6.6 6.4H23a1 1 0 0 1-.5-.2 1 1 0 0 1-.5-.4 1 1 0 0 1 0-.9c0-.2.2-.4.5-.6a13.3 13.3 0 0 0 5.7-17.9c-.9-1.6-2-3-3.4-4.2a12.5 12.5 0 0 0-6.3-2.8l2.1 3.3c.1.1.2.4.1.6 0 .2 0 .4-.3.5l-.3.2h-.5a.8.8 0 0 1-.5-.3l-3.3-5-.2-.4-.1-.3v-.3l.1-.3.3-.2.3-.2L21.5.4h.6c.2 0 .4.1.5.3l.1.3v.6c0 .2-.2.4-.4.5l-2.8 1.4a5.7 5.7 0 0 1 1 .2l3 1.1a15.2 15.2 0 0 1 6.6 6.7z"/></svg>
							<a href="https://elementor.support.vamtam.com/support/solutions/articles/245219-vamtam-elementor-themes-how-to-update-a-vamtam-theme-and-the-bundled-plugins-" class="button" target="_blank">
								<?php esc_html_e( 'How to Update Guide', 'numerique' ); ?>
							</a>
						</span>
						<span class="vamtam-box-btn">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 37"><path fill="#0073AA" d="M22.4.8c.7 0 1.2.3 1.7.7.4.5.6 1 .6 1.7v4.5l9.1-5.4.5-.3h1l.5.5.2.7v20.2c0 .2 0 .4-.2.6l-.5.4h-.2l-.3.1a5 5 0 0 1-.4 0c-.1 0-.3 0-.4-.2l-9.3-5.4v4.4c0 .7-.2 1.2-.6 1.7-.5.4-1 .7-1.7.7h-20c-.7 0-1.3-.3-1.7-.7-.5-.5-.7-1-.7-1.7V3.2c0-.7.2-1.2.7-1.7.4-.4 1-.7 1.6-.7h20.1zm0 2.3H2.3v20.3h20.1l.1-.1v-4.4a2.2 2.2 0 0 1 1.2-2l.5-.2a2.6 2.6 0 0 1 1.2 0l.7.3 7.6 4.3V5.2l-7.6 4.3-.6.3a2.9 2.9 0 0 1-1.3 0l-.5-.1c-.4-.2-.6-.5-.9-.9a2 2 0 0 1-.3-1.1V3z"/></svg>
							<a href="https://elementor.support.vamtam.com/support/solutions/articles/245221-vamtam-elementor-themes-video-guides-for-beginners" class="button" target="_blank">
								<?php esc_html_e( 'Video Guides for Beginners', 'numerique' ); ?>
							</a>
						</span>
						<span class="vamtam-box-btn">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 37"><path fill="#0073AA" d="M.8 10.6l16.7 9.1h.2l.3.1h.5l17-9.2.3-.4.2-.6c0-.2 0-.4-.2-.6a1 1 0 0 0-.4-.4L18.7.1a1 1 0 0 0-.9 0L.8 8.6.4 9a1 1 0 0 0-.2.6l.1.6.5.4zm17.4-8.2l14.2 7.3L18 17.4 3.7 9.7l14.5-7.3zm17 14.5l-3.3-1.7-2.4 1.3 2.8 1.4-14.5 7.8-14.2-7.8 3-1.5L4 15.1.6 17a1 1 0 0 0-.4.4L0 18a1.2 1.2 0 0 0 .6 1l16.7 9a1.4 1.4 0 0 0 .5.2h.3l.3-.1L35.2 19l.5-.4.1-.6c0-.2 0-.4-.2-.6 0-.2-.2-.3-.4-.4zm0 7.9c.2 0 .3.2.4.4.2.2.2.3.2.5l-.1.6a1 1 0 0 1-.5.5l-16.8 9-.3.2h-.3a1.2 1.2 0 0 1-.5-.1L.6 26.8a1.2 1.2 0 0 1-.6-1c0-.3 0-.5.2-.6 0-.2.2-.4.4-.4l3.3-1.6 2.4 1.3-2.7 1.3 14.2 7.8 14.5-7.8-2.7-1.3 2.4-1.3 3.2 1.6z"/></svg>
							<a href="https://vamtam.com/child-themes" class="button" target="_blank">
								<?php esc_html_e( 'Child Themes', 'numerique' ); ?>
							</a>
						</span>
						<span class="vamtam-box-btn">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 37"><path fill="#0073AA" d="M3.4 9H1a1 1 0 0 0-.8.3 1 1 0 0 0-.3.8v2.3c0 .3.1.5.3.8.3.2.5.3.8.3h2.3c.3 0 .5-.1.8-.3.2-.3.3-.5.3-.8V10a1 1 0 0 0-.3-.8 1 1 0 0 0-.8-.3zm31.5 1.1H11.3a1 1 0 0 0-.8.4 1 1 0 0 0-.4.8c0 .3.1.5.4.7.2.3.4.4.8.4h23.6c.3 0 .5-.1.8-.4.2-.2.3-.4.3-.8a1 1 0 0 0-.3-.7 1 1 0 0 0-.8-.4zM3.4 18H1a1 1 0 0 0-.8.3 1 1 0 0 0-.3.8v2.3c0 .3.1.5.3.8.3.2.5.3.8.3h2.3c.3 0 .5-.1.8-.3.2-.3.3-.5.3-.8V19a1 1 0 0 0-.3-.8 1 1 0 0 0-.8-.3zm31.5 1.1H11.3a1 1 0 0 0-.8.4 1 1 0 0 0-.4.8c0 .3.1.5.4.7.2.3.4.4.8.4h23.6c.3 0 .5-.1.8-.4.2-.2.3-.4.3-.8a1 1 0 0 0-.3-.7 1 1 0 0 0-.8-.4zM3.4 0H1a1 1 0 0 0-.8.3 1 1 0 0 0-.3.8v2.3c0 .3.1.5.3.8.3.2.5.3.8.3h2.3c.3 0 .5-.1.8-.3.2-.3.3-.5.3-.8V1a1 1 0 0 0-.3-.8 1 1 0 0 0-.8-.3zm7.8 3.4a1 1 0 0 1-.7-.4 1 1 0 0 1-.4-.8c0-.3.1-.5.4-.7.2-.3.4-.4.8-.4h23.6c.3 0 .5.1.8.4.2.2.3.4.3.8 0 .3-.1.5-.3.7a1 1 0 0 1-.8.4H11.3z"/></svg>
							<a href="https://vamtam.com/changelog" class="button" target="_blank">
								<?php esc_html_e( 'Changelog', 'numerique' ); ?>
							</a>
						</span>
					</div>
				</div>
			</div>
			<div class="vamtam-hd-ss-wrap">
				<div class="vamtam-hd vamtam-box-wrap">
					<header>
						<h3><?php esc_html_e( 'Have any queries outside of the scope?', 'numerique' ); ?></h3>
					</header>
					<div class="content">
						<p class="vamtam-description"><?php esc_html_e( 'Should you have any queries outside of the scope of the help materials and the video guides don\'t hesitate to contact us.', 'numerique' ); ?></p>
						<a href="https://themeforest.net/user/vamtam" class="vamtam-ts-button button" target="_blank">
							<?php esc_html_e( 'Contact Us', 'numerique' ); ?>
						</a>
					</div>
				</div>
				<div class="vamtam-ss vamtam-box-wrap">
					<header>
						<h3><?php esc_html_e( 'Enable System Status Information Gathering', 'numerique' ); ?></h3>
					</header>
					<div class="content">
						<form method="post" action="options.php">
						<?php
							settings_fields( 'vamtam_theme_help' );
							do_settings_sections( 'vamtam_theme_help' );
						?>
						</form>
						<div id="vamtam-post-result">
							<span class="spinner"></span>
							<p style="color: green;" class="vamtam-post-msg-success"><?php echo esc_html__( 'Settings Saved Successfully', 'numerique' ) ?></p>
							<p style="color: red;" class="vamtam-post-msg-failure"><?php echo esc_html__( 'There was a problem saving the settings to the database, please try again.', 'numerique' ) ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
