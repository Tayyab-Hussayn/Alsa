<?php

// The Events Calendar uses a smaller than necessary thumbnail size for its loop
add_action( 'tribe_events_before_loop', 'vamtam_tribe_events_before_loop' );
function vamtam_tribe_events_before_loop() {
	add_filter( 'tribe_event_featured_image_size', 'vamtam_tribe_event_featured_image_size__loop' );
}

add_action( 'tribe_events_after_loop', 'vamtam_tribe_events_after_loop' );
function vamtam_tribe_events_after_loop() {
	remove_filter( 'tribe_event_featured_image_size', 'vamtam_tribe_event_featured_image_size__loop' );
}

function vamtam_tribe_event_featured_image_size__loop( $size ) {
	return array( 700, 0 );
}