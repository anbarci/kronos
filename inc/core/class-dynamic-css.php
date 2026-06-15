<?php

namespace Kronos\Core;

defined( 'ABSPATH' ) || exit;

class DynamicCss {

	public function register(): void {
		add_action( 'wp_head', [ $this, 'output' ], 20 );
		add_action( 'wp_enqueue_scripts', [ $this, 'inline' ], 20 );
	}

	public function inline(): void {
		wp_add_inline_style( 'kronos-main', $this->build() );
	}

	public function output(): void {}

	private function build(): string {
		$primary   = sanitize_hex_color( (string) Options::get( 'kronos_brand_primary' ) ) ?: '#E11D48';
		$hover     = sanitize_hex_color( (string) Options::get( 'kronos_brand_hover' ) ) ?: '#BE123C';
		$secondary = sanitize_hex_color( (string) Options::get( 'kronos_brand_secondary' ) ) ?: '#1F2937';
		$bg        = sanitize_hex_color( (string) Options::get( 'kronos_bg_base' ) ) ?: '#FFFFFF';
		$text      = sanitize_hex_color( (string) Options::get( 'kronos_text_primary' ) ) ?: '#0F172A';
		$max       = absint( Options::get( 'kronos_container_max' ) ) ?: 1200;
		$radius    = absint( Options::get( 'kronos_radius' ) );
		$fontBody  = sanitize_text_field( (string) Options::get( 'kronos_font_body' ) );
		$fontHead  = sanitize_text_field( (string) Options::get( 'kronos_font_head' ) );

		$css  = ':root{';
		$css .= '--brand-primary:' . $primary . ';';
		$css .= '--brand-primary-hover:' . $hover . ';';
		$css .= '--brand-secondary:' . $secondary . ';';
		$css .= '--container-max:' . $max . 'px;';
		$css .= '--radius:' . $radius . 'px;';
		if ( $fontBody ) {
			$css .= '--font-body:"' . $fontBody . '",system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;';
		}
		if ( $fontHead ) {
			$css .= '--font-head:"' . $fontHead . '",var(--font-body);';
		}
		$css .= '}';

		$css .= ':root:not([data-theme="dark"]){';
		$css .= '--bg-base:' . $bg . ';';
		$css .= '--text-primary:' . $text . ';';
		$css .= '}';

		foreach ( get_categories( [ 'hide_empty' => true, 'number' => 30 ] ) as $cat ) {
			$col  = Helpers::cat_color( (int) $cat->term_id );
			$css .= '.kronos-widget .wp-block-categories li.cat-item-' . (int) $cat->term_id . '{border-bottom:2px solid ' . $col . ';padding-bottom:var(--sp-3);}';
		}

		return wp_strip_all_tags( $css );
	}
}
