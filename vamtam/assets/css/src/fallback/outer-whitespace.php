<?php global $vamtam_theme ?>

.vamtam-box-outer-padding,
.limit-wrapper,
.header-padding {
	padding-left: 0;
	padding-right: 0;
}

.vamtam-box-outer-padding .vamtam-box-outer-padding,
.limit-wrapper .limit-wrapper {
	padding-left: 0;
	padding-right: 0;
	margin-left: 0;
	margin-right: 0;
}

@media ( min-width: <?php echo intval( $medium_breakpoint + 1 ) ?>px ) and ( max-width: <?php echo intval( $content_width ) ?>px ) {
	.vamtam-box-outer-padding,
	.limit-wrapper,
	.header-padding {
		padding-left: 40px;
		padding-right: 40px;
	}
}

@media ( max-width: <?php echo intval( $medium_breakpoint ) ?>px ) {
	.vamtam-box-outer-padding,
	.limit-wrapper,
	.header-padding {
		padding-left: 30px;
		padding-right: 30px;
	}
}

@media ( max-width: <?php echo intval( $small_breakpoint ) ?>px ) {
	.vamtam-box-outer-padding,
	.limit-wrapper,
	.header-padding {
		padding-left: 20px;
		padding-right: 20px;
	}
}
