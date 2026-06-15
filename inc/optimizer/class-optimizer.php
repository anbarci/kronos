<?php

namespace Kronos\Optimizer;

defined( 'ABSPATH' ) || exit;

class Optimizer {

	public function register(): void {
		add_action( 'init', [ $this, 'cleanup' ] );
		add_filter( 'wp_resource_hints', [ $this, 'resource_hints' ], 10, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue' ], 100 );
		add_filter( 'wp_editor_set_quality', [ $this, 'image_quality' ], 10, 2 );
		add_action( 'wp_head', [ $this, 'preload_lcp' ], 2 );
	}

	public function cleanup(): void {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
		add_filter( 'the_generator', '__return_empty_string' );
	}

	public function resource_hints( array $hints, string $relation ): array {
		return $hints;
	}

	public function dequeue(): void {
		if ( ! is_admin() ) {
			wp_deregister_script( 'wp-embed' );
		}
	}

	public function image_quality( $quality, $mime = '' ): int {
		$q = (int) \Kronos\Core\Options::get( 'kronos_image_quality' );
		if ( $q < 1 || $q > 100 ) {
			return (int) $quality;
		}
		return $q;
	}

	public function preload_lcp(): void {
		if ( ! is_front_page() || is_paged() || \Kronos\Amp\Amp::is_request() ) {
			return;
		}
		$raw    = (string) \Kronos\Core\Options::get( 'kronos_home_blocks' );
		$blocks = array_values( array_filter( array_map( 'trim', explode( ',', $raw ) ) ) );
		$first  = $blocks[0] ?? '';
		if ( ! in_array( $first, [ 'manset', 'hero' ], true ) ) {
			return;
		}
		$q = \Kronos\Core\Blocks::query( [ 'posts_per_page' => 1 ] );
		if ( ! $q->have_posts() ) {
			wp_reset_postdata();
			return;
		}
		$q->the_post();
		$id = get_post_thumbnail_id();
		wp_reset_postdata();
		if ( ! $id ) {
			return;
		}
		$src = wp_get_attachment_image_url( $id, 'kronos-hero' );
		if ( ! $src ) {
			return;
		}
		$srcset = wp_get_attachment_image_srcset( $id, 'kronos-hero' );
		$sizes  = wp_get_attachment_image_sizes( $id, 'kronos-hero' );
		printf(
			'<link rel="preload" as="image" href="%s"%s%s fetchpriority="high">' . "\n",
			esc_url( $src ),
			$srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '"' : '',
			( $srcset && $sizes ) ? ' imagesizes="' . esc_attr( $sizes ) . '"' : ''
		);
	}
}
