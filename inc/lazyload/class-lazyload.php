<?php

namespace Kronos\Lazyload;

use Kronos\Core\Options;

defined( 'ABSPATH' ) || exit;

class Lazyload {

	public function register(): void {
		if ( ! Options::bool( 'kronos_lazyload' ) ) {
			return;
		}
		add_filter( 'the_content', [ $this, 'images' ], 20 );
		add_filter( 'the_content', [ $this, 'iframes' ], 21 );
		add_filter( 'wp_lazy_loading_enabled', '__return_true' );
	}

	private function is_amp(): bool {
		return \Kronos\Amp\Amp::is_request();
	}

	public function images( string $html ): string {
		if ( is_admin() || is_feed() || $this->is_amp() || '' === trim( $html ) ) {
			return $html;
		}
		return (string) preg_replace_callback(
			'/<img\b[^>]*>/i',
			static function ( $m ) {
				$tag = $m[0];
				if ( ! preg_match( '/\bloading=/i', $tag ) ) {
					$tag = str_replace( '<img', '<img loading="lazy"', $tag );
				}
				if ( ! preg_match( '/\bdecoding=/i', $tag ) ) {
					$tag = str_replace( '<img', '<img decoding="async"', $tag );
				}
				return $tag;
			},
			$html
		);
	}

	public function iframes( string $html ): string {
		if ( is_admin() || is_feed() || $this->is_amp() ) {
			return $html;
		}
		return (string) preg_replace_callback(
			'#<iframe\b[^>]*src=["\']([^"\']*(?:youtube\.com|youtube-nocookie\.com|youtu\.be)[^"\']*)["\'][^>]*></iframe>#i',
			static function ( $m ) {
				if ( ! preg_match( '#(?:youtu\.be/|v=|embed/)([A-Za-z0-9_-]{6,})#', $m[1], $id ) ) {
					return $m[0];
				}
				$vid = esc_attr( $id[1] );
				$thumb = esc_url( 'https://i.ytimg.com/vi/' . $vid . '/hqdefault.jpg' );
				return '<div class="kronos-embed" data-kronos-embed="youtube" data-id="' . $vid . '">'
					. '<button type="button" class="kronos-embed__play" aria-label="' . esc_attr__( 'Videoyu oynat', 'kronos' ) . '">'
					. '<img class="kronos-embed__poster" src="' . $thumb . '" alt="" loading="lazy" decoding="async" width="480" height="360">'
					. '<span class="kronos-embed__icon" aria-hidden="true">▶</span>'
					. '</button></div>';
			},
			$html
		);
	}
}
