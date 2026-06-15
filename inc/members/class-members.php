<?php

namespace Kronos\Members;

defined( 'ABSPATH' ) || exit;

class Members {

	private array $notices = [];

	public function register(): void {
		add_shortcode( 'kronos_login', [ $this, 'login' ] );
		add_shortcode( 'kronos_register', [ $this, 'registration' ] );
		add_shortcode( 'kronos_profile', [ $this, 'profile' ] );
		add_action( 'init', [ $this, 'handle' ] );
	}

	public function handle(): void {
		if ( empty( $_POST['kronos_member_action'] ) ) {
			return;
		}
		$action = sanitize_key( wp_unslash( $_POST['kronos_member_action'] ) );

		if ( 'register' === $action && ! is_user_logged_in() ) {
			$this->do_register();
		} elseif ( 'profile' === $action && is_user_logged_in() ) {
			$this->do_profile();
		}
	}

	private function do_register(): void {
		if ( ! isset( $_POST['kronos_register_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kronos_register_nonce'] ) ), 'kronos_register' ) ) {
			return;
		}
		if ( ! get_option( 'users_can_register' ) ) {
			$this->notices[] = __( 'Kayıt kapalı.', 'kronos' );
			return;
		}
		$username = sanitize_user( wp_unslash( $_POST['username'] ?? '' ) );
		$email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$password = (string) ( $_POST['password'] ?? '' );

		$user_id = register_new_user( $username, $email );
		if ( is_wp_error( $user_id ) ) {
			$this->notices[] = $user_id->get_error_message();
			return;
		}
		if ( $password ) {
			wp_set_password( $password, $user_id );
		}
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	private function do_profile(): void {
		if ( ! isset( $_POST['kronos_profile_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kronos_profile_nonce'] ) ), 'kronos_profile' ) ) {
			return;
		}
		wp_update_user( [
			'ID'           => get_current_user_id(),
			'display_name' => sanitize_text_field( wp_unslash( $_POST['display_name'] ?? '' ) ),
			'user_url'     => esc_url_raw( wp_unslash( $_POST['user_url'] ?? '' ) ),
			'description'  => sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) ),
		] );
		$this->notices[] = __( 'Profil güncellendi.', 'kronos' );
	}

	private function notices_html(): string {
		if ( empty( $this->notices ) ) {
			return '';
		}
		$out = '<div class="kronos-notices">';
		foreach ( $this->notices as $notice ) {
			$out .= '<p class="kronos-notice">' . esc_html( $notice ) . '</p>';
		}
		return $out . '</div>';
	}

	public function login(): string {
		if ( is_user_logged_in() ) {
			return '<p>' . sprintf(
				/* translators: %s: logout url. */
				esc_html__( 'Giriş yaptınız. %s', 'kronos' ),
				'<a href="' . esc_url( wp_logout_url( home_url( '/' ) ) ) . '">' . esc_html__( 'Çıkış yap', 'kronos' ) . '</a>'
			) . '</p>';
		}
		return $this->notices_html() . wp_login_form( [ 'echo' => false ] );
	}

	public function registration(): string {
		if ( is_user_logged_in() ) {
			return '<p>' . esc_html__( 'Zaten giriş yaptınız.', 'kronos' ) . '</p>';
		}
		ob_start();
		?>
		<?php echo $this->notices_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<form class="kronos-member-form" method="post">
			<input type="hidden" name="kronos_member_action" value="register">
			<?php wp_nonce_field( 'kronos_register', 'kronos_register_nonce' ); ?>
			<p><label><?php esc_html_e( 'Kullanıcı adı', 'kronos' ); ?><input type="text" name="username" required></label></p>
			<p><label><?php esc_html_e( 'E-posta', 'kronos' ); ?><input type="email" name="email" required></label></p>
			<p><label><?php esc_html_e( 'Şifre', 'kronos' ); ?><input type="password" name="password" required></label></p>
			<p><button type="submit" class="kronos-button"><?php esc_html_e( 'Kayıt ol', 'kronos' ); ?></button></p>
		</form>
		<?php
		return (string) ob_get_clean();
	}

	public function profile(): string {
		if ( ! is_user_logged_in() ) {
			return $this->login();
		}
		$user = wp_get_current_user();
		ob_start();
		?>
		<?php echo $this->notices_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<form class="kronos-member-form" method="post">
			<input type="hidden" name="kronos_member_action" value="profile">
			<?php wp_nonce_field( 'kronos_profile', 'kronos_profile_nonce' ); ?>
			<p><label><?php esc_html_e( 'Görünen ad', 'kronos' ); ?><input type="text" name="display_name" value="<?php echo esc_attr( $user->display_name ); ?>"></label></p>
			<p><label><?php esc_html_e( 'Web sitesi', 'kronos' ); ?><input type="url" name="user_url" value="<?php echo esc_attr( $user->user_url ); ?>"></label></p>
			<p><label><?php esc_html_e( 'Hakkında', 'kronos' ); ?><textarea name="description"><?php echo esc_textarea( $user->description ); ?></textarea></label></p>
			<p><button type="submit" class="kronos-button"><?php esc_html_e( 'Kaydet', 'kronos' ); ?></button></p>
		</form>
		<p><a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Çıkış yap', 'kronos' ); ?></a></p>
		<?php
		return (string) ob_get_clean();
	}
}
