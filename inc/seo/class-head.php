<?php

namespace Kronos\Seo;

defined( 'ABSPATH' ) || exit;

class Head {

	public function register(): void {
		if ( $this->seo_plugin_active() ) {
			return;
		}
		add_action( 'wp_head', [ $this, 'output' ], 5 );
	}

	private function seo_plugin_active(): bool {
		return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'SEOPress' );
	}

	private function description(): string {
		if ( is_singular() ) {
			$excerpt = get_the_excerpt();
			return $excerpt ? wp_strip_all_tags( $excerpt ) : '';
		}
		if ( is_category() || is_tag() || is_tax() ) {
			return wp_strip_all_tags( (string) term_description() );
		}
		return get_bloginfo( 'description' );
	}

	public function output(): void {
		$desc  = mb_substr( trim( $this->description() ), 0, 160 );
		$title = wp_get_document_title();
		$url   = is_singular() ? get_permalink() : home_url( add_query_arg( [] ) );
		$image = '';

		if ( is_singular() && has_post_thumbnail() ) {
			$image = (string) get_the_post_thumbnail_url( get_the_ID(), 'kronos-hero' );
		}

		$tags = [];
		if ( $desc ) {
			$tags[] = [ 'name' => 'description', 'content' => $desc ];
		}
		$tags[] = [ 'property' => 'og:site_name', 'content' => get_bloginfo( 'name' ) ];
		$tags[] = [ 'property' => 'og:locale', 'content' => str_replace( '-', '_', (string) get_bloginfo( 'language' ) ) ];
		$tags[] = [ 'property' => 'og:type', 'content' => is_singular( 'post' ) ? 'article' : 'website' ];
		$tags[] = [ 'property' => 'og:title', 'content' => $title ];
		$tags[] = [ 'property' => 'og:url', 'content' => $url ];
		if ( $desc ) {
			$tags[] = [ 'property' => 'og:description', 'content' => $desc ];
		}
		if ( $image ) {
			$tags[] = [ 'property' => 'og:image', 'content' => $image ];
		}
		$tags[] = [ 'name' => 'twitter:card', 'content' => $image ? 'summary_large_image' : 'summary' ];
		$tags[] = [ 'name' => 'twitter:title', 'content' => $title ];
		if ( $desc ) {
			$tags[] = [ 'name' => 'twitter:description', 'content' => $desc ];
		}
		if ( $image ) {
			$tags[] = [ 'name' => 'twitter:image', 'content' => $image ];
		}

		$out = "\n";
		foreach ( $tags as $tag ) {
			$attr = isset( $tag['property'] ) ? 'property' : 'name';
			$key  = $tag['property'] ?? $tag['name'];
			$out .= sprintf(
				'<meta %1$s="%2$s" content="%3$s" />' . "\n",
				$attr,
				esc_attr( $key ),
				esc_attr( $tag['content'] )
			);
		}
		echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
