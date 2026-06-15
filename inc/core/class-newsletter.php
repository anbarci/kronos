<?php

namespace Kronos\Core;

defined( 'ABSPATH' ) || exit;

class Newsletter {

	public function register(): void {
		add_action( 'admin_post_kronos_newsletter', [ $this, 'handle' ] );
		add_action( 'admin_post_nopriv_kronos_newsletter', [ $this, 'handle' ] );
	}

	public function handle(): void {
		if ( ! isset( $_POST['kronos_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kronos_newsletter_nonce'] ) ), 'kronos_newsletter' ) ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}
		$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		if ( is_email( $email ) ) {
			$list = get_option( 'kronos_newsletter_list', [] );
			$list = is_array( $list ) ? $list : [];
			if ( ! in_array( $email, $list, true ) ) {
				$list[] = $email;
				update_option( 'kronos_newsletter_list', $list, false );
			}
		}
		wp_safe_redirect( add_query_arg( 'kronos_news', '1', wp_get_referer() ?: home_url( '/' ) ) );
		exit;
	}
}
