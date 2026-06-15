<?php

namespace Kronos\Customizer;

use Kronos\Core\Options;

defined( 'ABSPATH' ) || exit;

class Customizer {

	public function register(): void {
		add_action( 'customize_register', [ $this, 'register_controls' ] );
		add_action( 'customize_preview_init', [ $this, 'preview_js' ] );
	}

	public function register_controls( \WP_Customize_Manager $wp ): void {
		$defaults = Options::defaults();

		$wp->add_panel( 'kronos_panel', [
			'title'    => __( 'Kronos Tema', 'kronos' ),
			'priority' => 10,
		] );

		$wp->add_section( 'kronos_mode', [
			'title' => __( 'Site Modu', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		$wp->add_setting( 'kronos_site_mode', [
			'default'           => $defaults['kronos_site_mode'],
			'sanitize_callback' => [ $this, 'sanitize_mode' ],
			'transport'         => 'refresh',
		] );
		$wp->add_control( 'kronos_site_mode', [
			'type'        => 'radio',
			'section'     => 'kronos_mode',
			'label'       => __( 'Bu site nedir?', 'kronos' ),
			'description' => __( 'Blog veya Haber moduna göre tüm yerleşim uyarlanır.', 'kronos' ),
			'choices'     => [
				'blog' => __( 'Blog', 'kronos' ),
				'news' => __( 'Haber Sitesi', 'kronos' ),
			],
		] );
		$wp->add_setting( 'kronos_default_theme', [
			'default'           => $defaults['kronos_default_theme'],
			'sanitize_callback' => [ $this, 'sanitize_theme' ],
		] );
		$wp->add_control( 'kronos_default_theme', [
			'type'    => 'radio',
			'section' => 'kronos_mode',
			'label'   => __( 'Varsayılan görünüm', 'kronos' ),
			'choices' => [
				'light' => __( 'Açık', 'kronos' ),
				'dark'  => __( 'Koyu', 'kronos' ),
			],
		] );

		$wp->add_section( 'kronos_colors', [
			'title' => __( 'Renkler', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		foreach ( [
			'kronos_brand_primary'   => __( 'Birincil renk', 'kronos' ),
			'kronos_brand_hover'     => __( 'Birincil hover', 'kronos' ),
			'kronos_brand_secondary' => __( 'İkincil renk', 'kronos' ),
			'kronos_bg_base'         => __( 'Arka plan', 'kronos' ),
			'kronos_text_primary'    => __( 'Metin rengi', 'kronos' ),
		] as $id => $label ) {
			$wp->add_setting( $id, [
				'default'           => $defaults[ $id ],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			] );
			$wp->add_control( new \WP_Customize_Color_Control( $wp, $id, [
				'label'   => $label,
				'section' => 'kronos_colors',
			] ) );
		}

		$wp->add_section( 'kronos_typography', [
			'title' => __( 'Tipografi', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		$fonts = [ 'Space Grotesk', 'Inter', 'Sora', 'IBM Plex Sans', 'Roboto', 'system-ui' ];
		foreach ( [
			'kronos_font_head' => __( 'Başlık fontu', 'kronos' ),
			'kronos_font_body' => __( 'Metin fontu', 'kronos' ),
		] as $id => $label ) {
			$wp->add_setting( $id, [
				'default'           => $defaults[ $id ],
				'sanitize_callback' => 'sanitize_text_field',
			] );
			$wp->add_control( $id, [
				'type'    => 'select',
				'section' => 'kronos_typography',
				'label'   => $label,
				'choices' => array_combine( $fonts, $fonts ),
			] );
		}

		$wp->add_section( 'kronos_layout', [
			'title' => __( 'Yerleşim', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		$wp->add_setting( 'kronos_container_max', [
			'default'           => $defaults['kronos_container_max'],
			'sanitize_callback' => 'absint',
		] );
		$wp->add_control( 'kronos_container_max', [
			'type'    => 'number',
			'section' => 'kronos_layout',
			'label'   => __( 'Konteyner genişliği (px)', 'kronos' ),
		] );
		$wp->add_setting( 'kronos_radius', [
			'default'           => $defaults['kronos_radius'],
			'sanitize_callback' => 'absint',
		] );
		$wp->add_control( 'kronos_radius', [
			'type'    => 'number',
			'section' => 'kronos_layout',
			'label'   => __( 'Köşe yuvarlaklığı (px)', 'kronos' ),
		] );
		$wp->add_setting( 'kronos_sidebar_position', [
			'default'           => $defaults['kronos_sidebar_position'],
			'sanitize_callback' => [ $this, 'sanitize_sidebar' ],
		] );
		$wp->add_control( 'kronos_sidebar_position', [
			'type'    => 'radio',
			'section' => 'kronos_layout',
			'label'   => __( 'Yan sütun konumu', 'kronos' ),
			'choices' => [
				'right' => __( 'Sağ', 'kronos' ),
				'left'  => __( 'Sol', 'kronos' ),
				'none'  => __( 'Yok', 'kronos' ),
			],
		] );
		$wp->add_setting( 'kronos_sticky_header', [
			'default'           => $defaults['kronos_sticky_header'],
			'sanitize_callback' => 'wp_validate_boolean',
		] );
		$wp->add_control( 'kronos_sticky_header', [
			'type'    => 'checkbox',
			'section' => 'kronos_layout',
			'label'   => __( 'Sabit (sticky) header', 'kronos' ),
		] );

		$wp->add_section( 'kronos_home', [
			'title' => __( 'Anasayfa Blokları', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		$wp->add_setting( 'kronos_home_blocks', [
			'default'           => $defaults['kronos_home_blocks'],
			'sanitize_callback' => 'sanitize_text_field',
		] );
		$wp->add_control( 'kronos_home_blocks', [
			'type'        => 'text',
			'section'     => 'kronos_home',
			'label'       => __( 'Blok sırası', 'kronos' ),
			'description' => __( 'Virgülle ayır: hero, story, trending, latest, categories, newsletter, ad', 'kronos' ),
		] );

		$wp->add_section( 'kronos_performance', [
			'title' => __( 'Performans', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		foreach ( [
			'kronos_lazyload'      => __( 'Lazy loading', 'kronos' ),
			'kronos_amp_enable'    => __( 'Dahili AMP katmanı', 'kronos' ),
			'kronos_schema_enable' => __( 'Otomatik Schema.org', 'kronos' ),
			'kronos_toc_enable'    => __( 'İçindekiler (içerik tablosu)', 'kronos' ),
		] as $id => $label ) {
			$wp->add_setting( $id, [
				'default'           => $defaults[ $id ],
				'sanitize_callback' => 'wp_validate_boolean',
			] );
			$wp->add_control( $id, [
				'type'    => 'checkbox',
				'section' => 'kronos_performance',
				'label'   => $label,
			] );
		}
		$wp->add_setting( 'kronos_image_quality', [
			'default'           => $defaults['kronos_image_quality'],
			'sanitize_callback' => [ $this, 'sanitize_quality' ],
		] );
		$wp->add_control( 'kronos_image_quality', [
			'type'        => 'number',
			'section'     => 'kronos_performance',
			'label'       => __( 'Görsel kalitesi (JPEG/WebP)', 'kronos' ),
			'description' => __( '1-100 arası. Düşük değer = küçük dosya. Önerilen 78-82. Yalnızca bundan sonra yüklenen/yeniden üretilen görselleri etkiler.', 'kronos' ),
			'input_attrs' => [ 'min' => 1, 'max' => 100, 'step' => 1 ],
		] );

		$wp->add_section( 'kronos_watermark', [
			'title' => __( 'Görsel Damga (Watermark)', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		$wp->add_setting( 'kronos_watermark_enable', [
			'default'           => $defaults['kronos_watermark_enable'],
			'sanitize_callback' => 'wp_validate_boolean',
		] );
		$wp->add_control( 'kronos_watermark_enable', [
			'type'        => 'checkbox',
			'section'     => 'kronos_watermark',
			'label'       => __( 'Yüklenen görsellere damga ekle', 'kronos' ),
			'description' => __( 'Yalnızca bundan sonra yüklenen görselleri etkiler.', 'kronos' ),
		] );
		$wp->add_setting( 'kronos_watermark_type', [
			'default'           => $defaults['kronos_watermark_type'],
			'sanitize_callback' => [ $this, 'sanitize_wm_type' ],
		] );
		$wp->add_control( 'kronos_watermark_type', [
			'type'    => 'radio',
			'section' => 'kronos_watermark',
			'label'   => __( 'Damga türü', 'kronos' ),
			'choices' => [
				'text' => __( 'Yazı', 'kronos' ),
				'logo' => __( 'Logo (görsel)', 'kronos' ),
			],
		] );
		$wp->add_setting( 'kronos_watermark_text', [
			'default'           => $defaults['kronos_watermark_text'],
			'sanitize_callback' => 'sanitize_text_field',
		] );
		$wp->add_control( 'kronos_watermark_text', [
			'type'        => 'text',
			'section'     => 'kronos_watermark',
			'label'       => __( 'Damga yazısı', 'kronos' ),
			'description' => __( 'Boş bırakılırsa site adı kullanılır.', 'kronos' ),
		] );
		$wp->add_setting( 'kronos_watermark_logo', [
			'default'           => $defaults['kronos_watermark_logo'],
			'sanitize_callback' => 'absint',
		] );
		$wp->add_control( new \WP_Customize_Media_Control( $wp, 'kronos_watermark_logo', [
			'section'     => 'kronos_watermark',
			'label'       => __( 'Damga logosu', 'kronos' ),
			'mime_type'   => 'image',
			'description' => __( 'Şeffaf PNG önerilir.', 'kronos' ),
		] ) );
		$wp->add_setting( 'kronos_watermark_position', [
			'default'           => $defaults['kronos_watermark_position'],
			'sanitize_callback' => [ $this, 'sanitize_wm_position' ],
		] );
		$wp->add_control( 'kronos_watermark_position', [
			'type'    => 'select',
			'section' => 'kronos_watermark',
			'label'   => __( 'Konum', 'kronos' ),
			'choices' => [
				'top-left'     => __( 'Sol üst', 'kronos' ),
				'top-right'    => __( 'Sağ üst', 'kronos' ),
				'bottom-left'  => __( 'Sol alt', 'kronos' ),
				'bottom-right' => __( 'Sağ alt', 'kronos' ),
				'center'       => __( 'Orta', 'kronos' ),
			],
		] );
		$wp->add_setting( 'kronos_watermark_opacity', [
			'default'           => $defaults['kronos_watermark_opacity'],
			'sanitize_callback' => 'absint',
		] );
		$wp->add_control( 'kronos_watermark_opacity', [
			'type'        => 'number',
			'section'     => 'kronos_watermark',
			'label'       => __( 'Opaklık (%)', 'kronos' ),
			'input_attrs' => [ 'min' => 0, 'max' => 100, 'step' => 5 ],
		] );
		$wp->add_setting( 'kronos_watermark_size', [
			'default'           => $defaults['kronos_watermark_size'],
			'sanitize_callback' => 'absint',
		] );
		$wp->add_control( 'kronos_watermark_size', [
			'type'        => 'number',
			'section'     => 'kronos_watermark',
			'label'       => __( 'Damga boyutu', 'kronos' ),
			'description' => __( 'Yazı ve logo boyutunu ölçekler (varsayılan 18; logo için görsel genişliğinin %\'si).', 'kronos' ),
			'input_attrs' => [ 'min' => 5, 'max' => 60, 'step' => 1 ],
		] );

		$wp->add_section( 'kronos_social', [
			'title' => __( 'Sosyal Medya', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		foreach ( [
			'kronos_social_x'         => 'X',
			'kronos_social_facebook'  => 'Facebook',
			'kronos_social_instagram' => 'Instagram',
			'kronos_social_youtube'   => 'YouTube',
			'kronos_social_linkedin'  => 'LinkedIn',
		] as $id => $label ) {
			$wp->add_setting( $id, [
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			] );
			$wp->add_control( $id, [
				'type'    => 'url',
				'section' => 'kronos_social',
				'label'   => $label,
			] );
		}

		$wp->add_section( 'kronos_footer', [
			'title' => __( 'Footer', 'kronos' ),
			'panel' => 'kronos_panel',
		] );
		$wp->add_setting( 'kronos_footer_text', [
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		] );
		$wp->add_control( 'kronos_footer_text', [
			'type'    => 'textarea',
			'section' => 'kronos_footer',
			'label'   => __( 'Telif metni', 'kronos' ),
		] );
	}

	public function sanitize_mode( $value ): string {
		return in_array( $value, [ 'blog', 'news' ], true ) ? $value : 'blog';
	}

	public function sanitize_theme( $value ): string {
		return in_array( $value, [ 'light', 'dark' ], true ) ? $value : 'light';
	}

	public function sanitize_sidebar( $value ): string {
		return in_array( $value, [ 'right', 'left', 'none' ], true ) ? $value : 'right';
	}

	public function sanitize_quality( $value ): int {
		$value = absint( $value );
		if ( $value < 1 ) {
			return 82;
		}
		return min( $value, 100 );
	}

	public function sanitize_wm_type( $value ): string {
		return in_array( $value, [ 'text', 'logo' ], true ) ? $value : 'text';
	}

	public function sanitize_wm_position( $value ): string {
		return in_array( $value, [ 'top-left', 'top-right', 'bottom-left', 'bottom-right', 'center' ], true ) ? $value : 'bottom-right';
	}

	public function preview_js(): void {
		wp_enqueue_script(
			'kronos-customize-preview',
			KRONOS_URI . '/assets/js/customize-preview.js',
			[ 'customize-preview' ],
			KRONOS_VERSION,
			true
		);
	}
}
