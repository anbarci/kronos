<?php

namespace Kronos\Admin;

use Kronos\Core\Options;

defined( 'ABSPATH' ) || exit;

class Panel {

	private const CAP  = 'edit_theme_options';
	private const SLUG = 'kronos';

	public function register(): void {
		add_action( 'admin_menu', [ $this, 'menu' ], 9 );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
		add_action( 'admin_post_kronos_save_settings', [ $this, 'save' ] );
	}

	public function menu(): void {
		add_menu_page(
			__( 'Kronos Tema', 'kronos' ),
			__( 'Kronos', 'kronos' ),
			self::CAP,
			self::SLUG,
			[ $this, 'render' ],
			'dashicons-superhero',
			59
		);
		add_submenu_page( self::SLUG, __( 'Tema Ayarları', 'kronos' ), __( 'Tema Ayarları', 'kronos' ), self::CAP, self::SLUG, [ $this, 'render' ] );
	}

	private function groups(): array {
		$fonts = [ 'Space Grotesk' => 'Space Grotesk', 'Inter' => 'Inter', 'Sora' => 'Sora', 'IBM Plex Sans' => 'IBM Plex Sans', 'Roboto' => 'Roboto', 'system-ui' => 'Sistem' ];

		return [
			'genel'      => [
				'label'  => __( 'Genel', 'kronos' ),
				'fields' => [
					'kronos_site_mode'     => [ 'type' => 'radio', 'label' => __( 'Site modu', 'kronos' ), 'choices' => [ 'blog' => __( 'Blog', 'kronos' ), 'news' => __( 'Haber', 'kronos' ) ] ],
					'kronos_default_theme' => [ 'type' => 'radio', 'label' => __( 'Varsayılan görünüm', 'kronos' ), 'choices' => [ 'light' => __( 'Açık', 'kronos' ), 'dark' => __( 'Koyu', 'kronos' ) ] ],
				],
			],
			'renkler'    => [
				'label'  => __( 'Renk Paleti', 'kronos' ),
				'fields' => [
					'kronos_brand_primary'   => [ 'type' => 'color', 'label' => __( 'Birincil renk', 'kronos' ) ],
					'kronos_brand_hover'     => [ 'type' => 'color', 'label' => __( 'Birincil hover', 'kronos' ) ],
					'kronos_brand_secondary' => [ 'type' => 'color', 'label' => __( 'İkincil renk', 'kronos' ) ],
					'kronos_bg_base'         => [ 'type' => 'color', 'label' => __( 'Zemin (açık tema)', 'kronos' ) ],
					'kronos_text_primary'    => [ 'type' => 'color', 'label' => __( 'Metin (açık tema)', 'kronos' ) ],
				],
			],
			'tipografi'  => [
				'label'  => __( 'Tipografi', 'kronos' ),
				'fields' => [
					'kronos_font_head' => [ 'type' => 'select', 'label' => __( 'Başlık fontu', 'kronos' ), 'choices' => $fonts ],
					'kronos_font_body' => [ 'type' => 'select', 'label' => __( 'Metin fontu', 'kronos' ), 'choices' => $fonts ],
				],
			],
			'duzen'      => [
				'label'  => __( 'Yerleşim', 'kronos' ),
				'fields' => [
					'kronos_container_max'    => [ 'type' => 'number', 'label' => __( 'Konteyner genişliği (px)', 'kronos' ) ],
					'kronos_radius'           => [ 'type' => 'number', 'label' => __( 'Köşe yuvarlaklığı (px)', 'kronos' ) ],
					'kronos_sidebar_position' => [ 'type' => 'select', 'label' => __( 'Yan sütun', 'kronos' ), 'choices' => [ 'right' => __( 'Sağ', 'kronos' ), 'left' => __( 'Sol', 'kronos' ), 'none' => __( 'Yok', 'kronos' ) ] ],
					'kronos_sticky_header'    => [ 'type' => 'checkbox', 'label' => __( 'Sabit header', 'kronos' ), 'hint' => __( 'Kaydırırken üstte sabit kalsın', 'kronos' ) ],
				],
			],
			'schema'     => [
				'label'  => __( 'Schema & SEO', 'kronos' ),
				'fields' => [
					'kronos_schema_enable'   => [ 'type' => 'checkbox', 'label' => __( 'Otomatik Schema (JSON-LD)', 'kronos' ), 'hint' => __( 'Yapısal veri çıktısı', 'kronos' ) ],
					'kronos_schema_type'     => [ 'type' => 'select', 'label' => __( 'Makale şeması', 'kronos' ), 'choices' => [ 'auto' => __( 'Otomatik (moda göre)', 'kronos' ), 'BlogPosting' => 'BlogPosting', 'NewsArticle' => 'NewsArticle' ], 'desc' => __( 'Otomatik: blog modunda BlogPosting, haber modunda NewsArticle.', 'kronos' ) ],
					'kronos_schema_org_name' => [ 'type' => 'text', 'label' => __( 'Yayıncı adı', 'kronos' ), 'desc' => __( 'Boş bırakılırsa site adı kullanılır.', 'kronos' ) ],
					'kronos_schema_org_logo' => [ 'type' => 'media', 'label' => __( 'Yayıncı logosu', 'kronos' ), 'desc' => __( 'Schema publisher logosu (boşsa özel logo).', 'kronos' ) ],
					'kronos_amp_enable'      => [ 'type' => 'checkbox', 'label' => __( 'Dahili AMP katmanı', 'kronos' ), 'hint' => __( '/amp/ sürümü', 'kronos' ) ],
				],
			],
			'performans' => [
				'label'  => __( 'Performans', 'kronos' ),
				'fields' => [
					'kronos_lazyload' => [ 'type' => 'checkbox', 'label' => __( 'Lazy loading', 'kronos' ), 'hint' => __( 'Görsel/iframe geç yükleme', 'kronos' ) ],
					'kronos_ticker'     => [ 'type' => 'checkbox', 'label' => __( 'Manşet akış şeridi', 'kronos' ), 'hint' => __( 'Anasayfada kayan başlıklar (etiketsiz)', 'kronos' ) ],
					'kronos_toc_enable' => [ 'type' => 'checkbox', 'label' => __( 'İçindekiler (TOC)', 'kronos' ), 'hint' => __( 'Uzun yazılarda otomatik içerik tablosu', 'kronos' ) ],
				],
			],
			'gorsel'     => [
				'label'  => __( 'Görsel & Damga', 'kronos' ),
				'fields' => [
					'kronos_image_quality'      => [ 'type' => 'number', 'label' => __( 'Görsel kalitesi (JPEG/WebP)', 'kronos' ), 'desc' => __( '1-100. Düşük değer = küçük dosya. Sadece bundan sonra yüklenen görselleri etkiler.', 'kronos' ) ],
					'kronos_watermark_enable'   => [ 'type' => 'checkbox', 'label' => __( 'Watermark / damga', 'kronos' ), 'hint' => __( 'Yüklenen görsellere yazı veya logo damgası ekle', 'kronos' ) ],
					'kronos_watermark_type'     => [ 'type' => 'radio', 'label' => __( 'Damga türü', 'kronos' ), 'choices' => [ 'text' => __( 'Yazı', 'kronos' ), 'logo' => __( 'Logo (görsel)', 'kronos' ) ] ],
					'kronos_watermark_text'     => [ 'type' => 'text', 'label' => __( 'Damga yazısı', 'kronos' ), 'desc' => __( 'Boş bırakılırsa site adı kullanılır.', 'kronos' ) ],
					'kronos_watermark_logo'     => [ 'type' => 'media', 'label' => __( 'Damga logosu', 'kronos' ), 'desc' => __( 'Şeffaf PNG önerilir.', 'kronos' ) ],
					'kronos_watermark_position' => [ 'type' => 'select', 'label' => __( 'Konum', 'kronos' ), 'choices' => [ 'top-left' => __( 'Sol üst', 'kronos' ), 'top-right' => __( 'Sağ üst', 'kronos' ), 'bottom-left' => __( 'Sol alt', 'kronos' ), 'bottom-right' => __( 'Sağ alt', 'kronos' ), 'center' => __( 'Orta', 'kronos' ) ] ],
					'kronos_watermark_opacity'  => [ 'type' => 'number', 'label' => __( 'Opaklık (%)', 'kronos' ) ],
					'kronos_watermark_size'     => [ 'type' => 'number', 'label' => __( 'Damga boyutu', 'kronos' ), 'desc' => __( 'Yazı ve logo boyutunu ölçekler (varsayılan 18).', 'kronos' ) ],
				],
			],
			'sosyal'     => [
				'label'  => __( 'Sosyal Medya', 'kronos' ),
				'fields' => [
					'kronos_social_x'         => [ 'type' => 'url', 'label' => 'X' ],
					'kronos_social_facebook'  => [ 'type' => 'url', 'label' => 'Facebook' ],
					'kronos_social_instagram' => [ 'type' => 'url', 'label' => 'Instagram' ],
					'kronos_social_youtube'   => [ 'type' => 'url', 'label' => 'YouTube' ],
					'kronos_social_linkedin'  => [ 'type' => 'url', 'label' => 'LinkedIn' ],
				],
			],
			'icerik'     => [
				'label'  => __( 'İçerik & Footer', 'kronos' ),
				'fields' => [
					'kronos_excerpt_length' => [ 'type' => 'number', 'label' => __( 'Özet kelime sayısı', 'kronos' ) ],
					'kronos_home_blocks'    => [ 'type' => 'text', 'label' => __( 'Anasayfa blokları', 'kronos' ), 'desc' => __( 'Virgülle: manset, latest, trending, sections, categories, hero, story, newsletter', 'kronos' ) ],
					'kronos_section_cats'   => [ 'type' => 'text', 'label' => __( '"Kategorilere Göz At" kategorileri', 'kronos' ), 'desc' => __( 'Virgülle kategori slug\'ları (örn: gundem,teknoloji,spor). Boş bırakılırsa otomatik (en çok yazılı 4 kategori).', 'kronos' ) ],
					'kronos_footer_text'    => [ 'type' => 'textarea', 'label' => __( 'Footer telif metni', 'kronos' ) ],
				],
			],
		];
	}

	public function assets( string $hook ): void {
		if ( 'toplevel_page_kronos' !== $hook ) {
			return;
		}
		wp_enqueue_style( 'kronos-admin', KRONOS_URI . '/assets/css/admin.css', [ 'wp-color-picker' ], KRONOS_VERSION );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_media();
		wp_add_inline_script(
			'wp-color-picker',
			'jQuery(function($){' .
			'$(".kronos-color").wpColorPicker();' .
			'$(".kronos-media-btn").on("click",function(e){e.preventDefault();var b=$(this);var f=wp.media({title:"Görsel seç",button:{text:"Kullan"},multiple:false});f.on("select",function(){var a=f.state().get("selection").first().toJSON();var u=(a.sizes&&a.sizes.thumbnail)?a.sizes.thumbnail.url:a.url;b.siblings("input").val(a.id);b.siblings(".kronos-media-prev").html("<img src=\\""+u+"\\" style=\\"max-width:90px;height:auto;border-radius:6px\\">");});f.open();});' .
			'$(".kronos-media-clear").on("click",function(e){e.preventDefault();var b=$(this);b.siblings("input").val("");b.siblings(".kronos-media-prev").empty();});' .
			'});'
		);
	}

	public function render(): void {
		$groups = $this->groups();
		$tab    = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : (string) array_key_first( $groups ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $groups[ $tab ] ) ) {
			$tab = (string) array_key_first( $groups );
		}
		?>
		<div class="wrap kronos-admin">
			<h1><?php esc_html_e( 'Kronos Tema Ayarları', 'kronos' ); ?></h1>
			<p class="kronos-admin__links">
				<a class="button" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Özelleştirici (canlı önizleme)', 'kronos' ); ?></a>
				<?php if ( class_exists( '\WPContentBot\Plugin' ) ) : ?>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=wpcb-dashboard' ) ); ?>"><?php esc_html_e( 'İçerik Botu', 'kronos' ); ?></a>
				<?php endif; ?>
			</p>

			<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Ayarlar kaydedildi.', 'kronos' ); ?></p></div>
			<?php endif; ?>

			<h2 class="nav-tab-wrapper">
				<?php foreach ( $groups as $key => $group ) : ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=kronos&tab=' . $key ) ); ?>" class="nav-tab <?php echo $key === $tab ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $group['label'] ); ?></a>
				<?php endforeach; ?>
			</h2>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="kronos_save_settings">
				<input type="hidden" name="tab" value="<?php echo esc_attr( $tab ); ?>">
				<?php wp_nonce_field( 'kronos_save_settings' ); ?>
				<table class="form-table" role="presentation"><tbody>
					<?php
					foreach ( $groups[ $tab ]['fields'] as $key => $field ) {
						$this->field( $key, $field );
					}
					?>
				</tbody></table>
				<?php submit_button( __( 'Ayarları Kaydet', 'kronos' ) ); ?>
			</form>

			<p class="description"><?php esc_html_e( 'Bu ayarlar Özelleştirici ile aynı değerleri kullanır (theme_mod) — ikisinden de düzenleyebilirsiniz.', 'kronos' ); ?></p>
		</div>
		<?php
	}

	private function field( string $key, array $f ): void {
		$val = Options::get( $key );
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $f['label'] ) . '</label></th><td>';

		switch ( $f['type'] ) {
			case 'color':
				printf( '<input type="text" class="kronos-color" id="%1$s" name="%1$s" value="%2$s" data-default-color="%2$s">', esc_attr( $key ), esc_attr( (string) $val ) );
				break;
			case 'text':
				printf( '<input type="text" class="regular-text" id="%1$s" name="%1$s" value="%2$s">', esc_attr( $key ), esc_attr( (string) $val ) );
				break;
			case 'url':
				printf( '<input type="url" class="regular-text" id="%1$s" name="%1$s" value="%2$s" placeholder="https://">', esc_attr( $key ), esc_attr( (string) $val ) );
				break;
			case 'number':
				printf( '<input type="number" class="small-text" id="%1$s" name="%1$s" value="%2$s">', esc_attr( $key ), esc_attr( (string) $val ) );
				break;
			case 'textarea':
				printf( '<textarea class="large-text" rows="3" id="%1$s" name="%1$s">%2$s</textarea>', esc_attr( $key ), esc_textarea( (string) $val ) );
				break;
			case 'checkbox':
				printf( '<label><input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s> %3$s</label>', esc_attr( $key ), checked( (bool) $val, true, false ), esc_html( $f['hint'] ?? '' ) );
				break;
			case 'radio':
				foreach ( $f['choices'] as $cv => $cl ) {
					printf( '<label style="margin-right:16px"><input type="radio" name="%1$s" value="%2$s" %3$s> %4$s</label>', esc_attr( $key ), esc_attr( (string) $cv ), checked( $val, $cv, false ), esc_html( $cl ) );
				}
				break;
			case 'select':
				echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
				foreach ( $f['choices'] as $cv => $cl ) {
					printf( '<option value="%s" %s>%s</option>', esc_attr( (string) $cv ), selected( $val, $cv, false ), esc_html( $cl ) );
				}
				echo '</select>';
				break;
			case 'media':
				$img = $val ? wp_get_attachment_image( (int) $val, 'thumbnail', false, [ 'style' => 'max-width:90px;height:auto;border-radius:6px' ] ) : '';
				printf(
					'<input type="hidden" name="%1$s" value="%2$s"><span class="kronos-media-prev">%3$s</span> <button class="button kronos-media-btn">%4$s</button> <button class="button kronos-media-clear">%5$s</button>',
					esc_attr( $key ),
					esc_attr( (string) $val ),
					$img, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					esc_html__( 'Görsel seç', 'kronos' ),
					esc_html__( 'Temizle', 'kronos' )
				);
				break;
		}

		if ( ! empty( $f['desc'] ) ) {
			echo '<p class="description">' . esc_html( $f['desc'] ) . '</p>';
		}
		echo '</td></tr>';
	}

	public function save(): void {
		if ( ! current_user_can( self::CAP ) ) {
			wp_die( esc_html__( 'Yetkiniz yok.', 'kronos' ) );
		}
		check_admin_referer( 'kronos_save_settings' );

		$groups = $this->groups();
		$tab    = isset( $_POST['tab'] ) ? sanitize_key( wp_unslash( $_POST['tab'] ) ) : '';

		if ( isset( $groups[ $tab ] ) ) {
			foreach ( $groups[ $tab ]['fields'] as $key => $f ) {
				$raw = $_POST[ $key ] ?? null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				switch ( $f['type'] ) {
					case 'color':
						set_theme_mod( $key, sanitize_hex_color( (string) $raw ) ?: '' );
						break;
					case 'text':
						set_theme_mod( $key, sanitize_text_field( wp_unslash( (string) $raw ) ) );
						break;
					case 'url':
						set_theme_mod( $key, esc_url_raw( wp_unslash( (string) $raw ) ) );
						break;
					case 'number':
						set_theme_mod( $key, absint( $raw ) );
						break;
					case 'textarea':
						set_theme_mod( $key, wp_kses_post( wp_unslash( (string) $raw ) ) );
						break;
					case 'checkbox':
						set_theme_mod( $key, isset( $_POST[ $key ] ) );
						break;
					case 'media':
						set_theme_mod( $key, absint( $raw ) );
						break;
					case 'radio':
					case 'select':
						$v = (string) $raw;
						set_theme_mod( $key, array_key_exists( $v, $f['choices'] ) ? $v : Options::get( $key ) );
						break;
				}
			}
		}

		wp_safe_redirect( add_query_arg( [ 'page' => self::SLUG, 'tab' => $tab, 'updated' => 1 ], admin_url( 'admin.php' ) ) );
		exit;
	}
}
