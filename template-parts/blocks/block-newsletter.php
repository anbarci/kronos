<?php

defined( 'ABSPATH' ) || exit;

$kronos_news_status = isset( $_GET['kronos_news'] ) ? sanitize_key( wp_unslash( $_GET['kronos_news'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$kronos_news_msgs   = [
	'1'       => __( 'Teşekkürler, bülten listesine eklendiniz.', 'kronos' ),
	'consent' => __( 'Abone olmak için açık rıza kutusunu işaretlemeniz gerekiyor.', 'kronos' ),
	'invalid' => __( 'Geçerli bir e-posta adresi girin.', 'kronos' ),
	'limit'   => __( 'Çok fazla deneme yapıldı. Lütfen daha sonra tekrar deneyin.', 'kronos' ),
	'expired' => __( 'Form süresi doldu. Sayfayı yenileyip tekrar deneyin.', 'kronos' ),
];
$kronos_privacy_url = get_privacy_policy_url();
?>
<section class="kronos-newsletter" id="kronos-newsletter" aria-label="<?php esc_attr_e( 'Bülten', 'kronos' ); ?>">
	<div class="kronos-container kronos-newsletter__inner">
		<div class="kronos-newsletter__text">
			<h2 class="kronos-newsletter__title"><?php esc_html_e( 'Bültene abone ol', 'kronos' ); ?></h2>
			<p><?php esc_html_e( 'Yeni içeriklerden ilk sen haberdar ol.', 'kronos' ); ?></p>
			<?php if ( isset( $kronos_news_msgs[ $kronos_news_status ] ) ) : ?>
				<p class="kronos-newsletter__status" role="status"><?php echo esc_html( $kronos_news_msgs[ $kronos_news_status ] ); ?></p>
			<?php endif; ?>
		</div>
		<form class="kronos-newsletter__form" data-kronos-newsletter method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="kronos_newsletter">
			<?php wp_nonce_field( 'kronos_newsletter', 'kronos_newsletter_nonce' ); ?>
			<label for="kronos-newsletter-email" class="screen-reader-text"><?php esc_html_e( 'E-posta', 'kronos' ); ?></label>
			<input type="email" id="kronos-newsletter-email" name="email" required autocomplete="email" placeholder="<?php esc_attr_e( 'E-posta adresiniz', 'kronos' ); ?>">
			<?php // Bot tuzağı: insanlar görmez, botlar doldurur. ?>
			<input type="text" name="kronos_hp" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="kronos-newsletter__hp">
			<button type="submit" class="kronos-button"><?php esc_html_e( 'Abone ol', 'kronos' ); ?></button>
			<label class="kronos-newsletter__consent">
				<input type="checkbox" name="kronos_consent" value="1" required>
				<span>
					<?php esc_html_e( 'E-posta adresimin bülten gönderimi amacıyla işlenmesine açık rıza veriyorum.', 'kronos' ); ?>
					<?php if ( $kronos_privacy_url ) : ?>
						<a href="<?php echo esc_url( $kronos_privacy_url ); ?>"><?php esc_html_e( 'Aydınlatma metni', 'kronos' ); ?></a>
					<?php endif; ?>
				</span>
			</label>
		</form>
	</div>
</section>
