<?php

namespace Kronos\Core;

defined( 'ABSPATH' ) || exit;

class Options {

	public static function defaults(): array {
		return [
			'kronos_site_mode'       => 'blog',
			'kronos_brand_primary'   => '#E11D48',
			'kronos_brand_hover'     => '#BE123C',
			'kronos_brand_secondary' => '#1F2937',
			'kronos_bg_base'         => '#F2F3F6',
			'kronos_text_primary'    => '#1A1F2B',
			'kronos_container_max'   => 1280,
			'kronos_radius'          => 10,
			'kronos_font_body'       => 'Inter',
			'kronos_font_head'       => 'Space Grotesk',
			'kronos_default_theme'   => 'light',
			'kronos_sidebar_position'=> 'right',
			'kronos_sticky_header'   => true,
			'kronos_lazyload'        => true,
			'kronos_amp_enable'      => true,
			'kronos_schema_enable'   => true,
			'kronos_schema_type'     => 'auto',
			'kronos_schema_org_name' => '',
			'kronos_schema_org_logo' => 0,
			'kronos_excerpt_length'  => 24,
			'kronos_image_quality'   => 82,
			'kronos_footer_text'     => '',
			'kronos_home_blocks'     => 'manset,latest,trending,sections',
			'kronos_ticker'          => true,
			'kronos_social_x'        => '',
			'kronos_social_facebook' => '',
			'kronos_social_instagram'=> '',
			'kronos_social_youtube'  => '',
			'kronos_social_linkedin' => '',
		];
	}

	public static function get( string $key ) {
		$defaults = self::defaults();
		$default  = $defaults[ $key ] ?? '';
		return get_theme_mod( $key, $default );
	}

	public static function bool( string $key ): bool {
		return (bool) self::get( $key );
	}

	public static function social_links(): array {
		$map = [
			'x'         => 'X',
			'facebook'  => 'Facebook',
			'instagram' => 'Instagram',
			'youtube'   => 'YouTube',
			'linkedin'  => 'LinkedIn',
		];
		$out = [];
		foreach ( $map as $key => $label ) {
			$url = self::get( 'kronos_social_' . $key );
			if ( $url ) {
				$out[ $key ] = [
					'label' => $label,
					'url'   => $url,
				];
			}
		}
		return $out;
	}
}
