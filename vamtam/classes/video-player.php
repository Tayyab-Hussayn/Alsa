<?php

 /**
 * Renders an HTML5 video player or an iframe for embedded videos.
 *
 * @package vamtam/numerique
 */
/**
 * class VamtamVideoPlayer
 */
class VamtamVideoPlayer {
	private $is_embedded  = false;
	private $src_mp4      = '';
	private $src_webm     = '';
	private $muted        = true;
	private $fallback_src = '';
	private $video_zoom   = 1.5;
	private $player       = '';

	public function __construct( $is_embedded, $src_mp4, $src_webm, $muted, $fallback_src, $video_zoom ) {
		$this->is_embedded  = $is_embedded;
		$this->src_mp4      = $src_mp4;
		$this->src_webm     = $src_webm;
		$this->muted        = $muted;
		$this->fallback_src = $fallback_src;
		$this->video_zoom   = $video_zoom;
		$this->init();
	}

	private function youtube_valid_vid_id( $url ) {
		$p   = '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/';
		$res = preg_match( $p, $url, $matches );
		if ( ! $res ) {
			return false;
		}
		return $matches;
	}

	private function vimeo_valid_vid_id( $url ) {
		$p   = '/(http|https)?:\/\/(www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|)(\d+)(?:|\/\?)/';
		$res = preg_match( $p, $url, $matches );
		if ( ! $res ) {
			return false;
		}
		return $matches;
	}

	private function get_iframe_player() {
		$src = $this->src_mp4;

		$is_valid_src = ! empty( $src ) && is_string( $src ) && ( $this->youtube_valid_vid_id( $src ) || $this->vimeo_valid_vid_id( $src ) );

		$fallback = '<img
						src="' . esc_url( $this->fallback_src ) .
						'" title="Invalid video URL."
						alt="Invalid video URL."
						style="width: 100%; height: 100%;"
					>';

		$should_show_fallback = ! empty( $this->fallback_src ) ? $fallback : '';

		$classes                   = 'vt-embed-vp-wrap';
		$is_valid_src && $classes .= ' has-vid';
		$html                      =
			'<div class="' . esc_attr( $classes ) . '"'
				. ( $is_valid_src ? ' style="transform: scale(' . $this->video_zoom . ');"' : '' ) . '>'
				. ( $is_valid_src ? $this->create_iframe_player() : $should_show_fallback ) .
			'</div>';

		return $html;
	}

	private function create_iframe_player() {
		$src = $this->src_mp4;
		$video_id;

		$is_youtube = $this->youtube_valid_vid_id( $src );
		if ( $is_youtube ) {
			$video_id = $is_youtube[1];
		} elseif ( $this->vimeo_valid_vid_id( $src ) ) {
			$video_id = $this->vimeo_valid_vid_id( $src )[4];
		} else {
			return '';
		}

		$embed = $is_youtube
				? 'https://www.youtube.com/embed/' . $video_id . '?controls=0&showinfo=0&loop=1&playlist=' . $video_id . '&rel=0&start=0&mute=' . ( $this->muted ? '1' : '0' ) . '&disablekb=1&enablejsapi=1'
				: 'https://player.vimeo.com/video/' . $video_id . '?title=0&portrait=0&muted=' . ( $this->muted ? '1' : '0' ) . '&autopause=0&loop=1&background=0&byline=0&speed=0';

		$html = '<iframe
					title="Video Player"
					src="' . esc_url( $embed ) .
					'"frameBorder="0"
					allowFullScreen="1"
				></iframe>';

		return $html;
	}

	private function get_html5_video_player() {
		$html = '<video class="vt-vm-vid"' . ( $this->muted ? ' muted ' : '' ) . ' loop poster=' . esc_url( $this->fallback_src ) . '>
					<source src="' . esc_url( $this->src_mp4 ) . '" type="video/mp4">
					<source src="' . esc_url( $this->src_webm ) . '" type="video/webm">
					<img src="' . esc_url( $this->fallback_src ) . '" title="Your browser does not support the video tag" alt="Your browser does not support the video tag">
				</video>';
		return $html;
	}

	public function init() {
		$html = '<div class="vt-video-player-wrap">'
					. ( $this->is_embedded ? $this->get_iframe_player() : $this->get_html5_video_player() ) .
				'</div>';

		$this->player = $html;
	}

	public function get_player() {
		return $this->player;
	}
}
