<?php

namespace Kronos\Seo;

defined( 'ABSPATH' ) || exit;

class Canonical {

	public function register(): void {
		if ( $this->seo_plugin_active() ) {
			return;
		}
		add_filter( 'get_canonical_url', [ $this, 'filter' ], 10, 2 );
		add_action( 'add_meta_boxes', [ $this, 'box' ] );
		add_action( 'save_post', [ $this, 'save' ], 10, 2 );
	}

	private function seo_plugin_active(): bool {
		return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'SEOPress' );
	}

	public function filter( $canonical_url, $post ) {
		$custom = get_post_meta( $post->ID, '_kronos_canonical', true );
		return $custom ? esc_url( $custom ) : $canonical_url;
	}

	public function box(): void {
		add_meta_box(
			'kronos_canonical',
			__( 'Kanonik URL (Kronos)', 'kronos' ),
			[ $this, 'render' ],
			'post',
			'side',
			'low'
		);
	}

	public function render( $post ): void {
		wp_nonce_field( 'kronos_canonical', 'kronos_canonical_nonce' );
		$value = (string) get_post_meta( $post->ID, '_kronos_canonical', true );
		echo '<p style="margin:0 0 6px">' . esc_html__( 'Bu yazı önce başka bir sitede yayımlandıysa, orijinal (kanonik) adresini gir. Boş bırakılırsa bu yazının kendi adresi kullanılır.', 'kronos' ) . '</p>';
		printf(
			'<input type="url" id="kronos_canonical_field" name="kronos_canonical" value="%s" placeholder="https://..." style="width:100%%" />',
			esc_attr( $value )
		);
	}

	public function save( $post_id, $post ): void {
		if ( ! isset( $_POST['kronos_canonical_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kronos_canonical_nonce'] ) ), 'kronos_canonical' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || 'post' !== $post->post_type ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$value = isset( $_POST['kronos_canonical'] ) ? esc_url_raw( wp_unslash( $_POST['kronos_canonical'] ) ) : '';
		if ( $value ) {
			update_post_meta( $post_id, '_kronos_canonical', $value );
		} else {
			delete_post_meta( $post_id, '_kronos_canonical' );
		}
	}
}
