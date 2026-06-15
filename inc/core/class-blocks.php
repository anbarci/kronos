<?php

namespace Kronos\Core;

defined( 'ABSPATH' ) || exit;

class Blocks {

	public static function allowed(): array {
		return [ 'manset', 'hero', 'sections', 'story', 'trending', 'latest', 'categories', 'newsletter', 'ad' ];
	}

	public static function render(): void {
		$raw    = (string) Options::get( 'kronos_home_blocks' );
		$blocks = array_filter( array_map( 'trim', explode( ',', $raw ) ) );
		$allow  = self::allowed();

		foreach ( $blocks as $block ) {
			if ( in_array( $block, $allow, true ) ) {
				get_template_part( 'template-parts/blocks/block', $block );
			}
		}
	}

	public static function query( array $args ): \WP_Query {
		return new \WP_Query( array_merge( [
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		], $args ) );
	}
}
