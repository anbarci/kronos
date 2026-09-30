<?php
/**
 * Bülten abonelik toplama + yönetim.
 *
 * KVKK/GDPR: açık rıza kutusu zorunludur; rıza zamanı kaydedilir. Liste yönetim
 * panelinden görüntülenir, CSV olarak dışa aktarılır, tek tek veya toptan silinir
 * (silme/erişim taleplerini karşılamak için). Bot tuzağı ve IP başına hız sınırı
 * sahte kayıtların seçenek satırını şişirmesini engeller.
 *
 * @package Kronos\Core
 */

namespace Kronos\Core;

defined( 'ABSPATH' ) || exit;

class Newsletter {

	private const OPTION   = 'kronos_newsletter_list';
	private const CAP      = 'edit_theme_options';
	private const SLUG     = 'kronos-newsletter';
	private const LIMIT    = 5;           // IP başına deneme…
	private const WINDOW   = HOUR_IN_SECONDS; // …bu süre içinde.
	private const MAX_SIZE = 20000;       // Tek seçenek satırı için üst sınır.

	public function register(): void {
		add_action( 'admin_post_kronos_newsletter', [ $this, 'handle' ] );
		add_action( 'admin_post_nopriv_kronos_newsletter', [ $this, 'handle' ] );

		if ( is_admin() ) {
			add_action( 'admin_menu', [ $this, 'menu' ], 20 );
			add_action( 'admin_post_kronos_newsletter_export', [ $this, 'export' ] );
			add_action( 'admin_post_kronos_newsletter_delete', [ $this, 'delete' ] );
		}
	}

	/**
	 * Kayıtlı liste: [ email => [ 'time' => int, 'consent' => bool ] ].
	 * Eski sürümler düz e-posta dizisi saklıyordu; onlar rıza kaydı olmadan döner.
	 */
	private function list(): array {
		$raw = get_option( self::OPTION, [] );
		$out = [];
		foreach ( is_array( $raw ) ? $raw : [] as $key => $val ) {
			if ( is_int( $key ) && is_string( $val ) ) {
				$out[ $val ] = [ 'time' => 0, 'consent' => false ];
			} elseif ( is_string( $key ) && is_array( $val ) ) {
				$out[ $key ] = [
					'time'    => (int) ( $val['time'] ?? 0 ),
					'consent' => ! empty( $val['consent'] ),
				];
			}
		}
		return $out;
	}

	private function back( string $status ): never {
		$url = wp_get_referer() ?: home_url( '/' );
		$url = remove_query_arg( 'kronos_news', $url );
		wp_safe_redirect( add_query_arg( 'kronos_news', $status, $url ) . '#kronos-newsletter' );
		exit;
	}

	public function handle(): void {
		if ( ! isset( $_POST['kronos_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kronos_newsletter_nonce'] ) ), 'kronos_newsletter' ) ) {
			$this->back( 'expired' );
		}
		// Bot tuzağı dolduysa sessizce "başarılı" dön (bota ipucu verme).
		if ( ! empty( $_POST['kronos_hp'] ) ) {
			$this->back( '1' );
		}
		if ( empty( $_POST['kronos_consent'] ) ) {
			$this->back( 'consent' );
		}

		$ip_key = 'kronos_nl_' . md5( wp_salt( 'nonce' ) . ( $_SERVER['REMOTE_ADDR'] ?? '' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$tries  = (int) get_transient( $ip_key );
		if ( $tries >= self::LIMIT ) {
			$this->back( 'limit' );
		}
		set_transient( $ip_key, $tries + 1, self::WINDOW );

		$email = strtolower( sanitize_email( wp_unslash( $_POST['email'] ?? '' ) ) );
		if ( ! is_email( $email ) ) {
			$this->back( 'invalid' );
		}

		$list = $this->list();
		if ( ! isset( $list[ $email ] ) && count( $list ) < self::MAX_SIZE ) {
			$list[ $email ] = [ 'time' => time(), 'consent' => true ];
			update_option( self::OPTION, $list, false );
		}
		$this->back( '1' );
	}

	public function menu(): void {
		add_submenu_page(
			'kronos',
			__( 'Bülten Aboneleri', 'kronos' ),
			__( 'Bülten Aboneleri', 'kronos' ),
			self::CAP,
			self::SLUG,
			[ $this, 'render' ]
		);
	}

	public function render(): void {
		if ( ! current_user_can( self::CAP ) ) {
			return;
		}
		$list = $this->list();
		arsort( $list );
		$post = admin_url( 'admin-post.php' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Bülten Aboneleri', 'kronos' ); ?></h1>
			<?php if ( isset( $_GET['deleted'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Silindi.', 'kronos' ); ?></p></div>
			<?php endif; ?>
			<p>
				<?php
				/* translators: %d: subscriber count. */
				printf( esc_html__( 'Toplam %d abone.', 'kronos' ), count( $list ) );
				?>
				<?php esc_html_e( 'Bir kişi verisinin silinmesini isterse buradan silin. "Rıza kaydı yok" satırları tema 1.2.6 öncesinde rıza sorulmadan toplanmıştır; bunlara e-posta göndermeyin, silmeniz önerilir.', 'kronos' ); ?>
			</p>
			<?php if ( $list ) : ?>
				<p>
					<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'action', 'kronos_newsletter_export', $post ), 'kronos_newsletter_export' ) ); ?>"><?php esc_html_e( 'CSV olarak indir', 'kronos' ); ?></a>
				</p>
				<form method="post" action="<?php echo esc_url( $post ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Seçilenler kalıcı olarak silinsin mi?', 'kronos' ) ); ?>');">
					<input type="hidden" name="action" value="kronos_newsletter_delete">
					<?php wp_nonce_field( 'kronos_newsletter_delete' ); ?>
					<table class="widefat striped">
						<thead><tr><td class="check-column"></td><th><?php esc_html_e( 'E-posta', 'kronos' ); ?></th><th><?php esc_html_e( 'Kayıt / rıza zamanı', 'kronos' ); ?></th></tr></thead>
						<tbody>
						<?php foreach ( $list as $email => $row ) : ?>
							<tr>
								<th class="check-column"><input type="checkbox" name="emails[]" value="<?php echo esc_attr( $email ); ?>"></th>
								<td><?php echo esc_html( $email ); ?></td>
								<td><?php echo $row['consent'] && $row['time'] ? esc_html( wp_date( 'Y-m-d H:i', $row['time'] ) ) : esc_html__( 'Rıza kaydı yok', 'kronos' ); ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<p>
						<button type="submit" class="button"><?php esc_html_e( 'Seçilenleri sil', 'kronos' ); ?></button>
						<button type="submit" class="button" name="scope" value="noconsent"><?php esc_html_e( 'Rıza kaydı olmayanları sil', 'kronos' ); ?></button>
						<button type="submit" class="button button-link-delete" name="scope" value="all"><?php esc_html_e( 'Tümünü sil', 'kronos' ); ?></button>
					</p>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	public function export(): void {
		if ( ! current_user_can( self::CAP ) ) {
			wp_die( esc_html__( 'Yetkiniz yok.', 'kronos' ) );
		}
		check_admin_referer( 'kronos_newsletter_export' );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=bulten-aboneleri-' . gmdate( 'Y-m-d' ) . '.csv' );
		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, [ 'email', 'consent_time_utc' ], ',', '"', '' );
		foreach ( $this->list() as $email => $row ) {
			// "=", "+", "-" ile başlayan hücre Excel'de formül olarak çalışır; metne çevir.
			$cell = preg_match( '/^[=+\-@]/', $email ) ? "'" . $email : $email;
			fputcsv( $out, [ $cell, $row['consent'] && $row['time'] ? gmdate( 'c', $row['time'] ) : '' ], ',', '"', '' );
		}
		fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		exit;
	}

	public function delete(): void {
		if ( ! current_user_can( self::CAP ) ) {
			wp_die( esc_html__( 'Yetkiniz yok.', 'kronos' ) );
		}
		check_admin_referer( 'kronos_newsletter_delete' );

		$scope = isset( $_POST['scope'] ) ? sanitize_key( wp_unslash( $_POST['scope'] ) ) : '';
		$list  = $this->list();

		if ( 'all' === $scope ) {
			$list = [];
		} elseif ( 'noconsent' === $scope ) {
			$list = array_filter( $list, static fn( $row ) => $row['consent'] );
		} else {
			$emails = array_map( 'sanitize_email', (array) wp_unslash( $_POST['emails'] ?? [] ) );
			foreach ( $emails as $email ) {
				unset( $list[ $email ], $list[ strtolower( $email ) ] );
			}
		}

		if ( $list ) {
			update_option( self::OPTION, $list, false );
		} else {
			delete_option( self::OPTION );
		}
		wp_safe_redirect( add_query_arg( [ 'page' => self::SLUG, 'deleted' => 1 ], admin_url( 'admin.php' ) ) );
		exit;
	}
}
