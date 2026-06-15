<?php

defined( 'ABSPATH' ) || exit;
?>
<section class="kronos-newsletter" aria-label="<?php esc_attr_e( 'Bülten', 'kronos' ); ?>">
	<div class="kronos-container kronos-newsletter__inner">
		<div class="kronos-newsletter__text">
			<h2 class="kronos-newsletter__title"><?php esc_html_e( 'Bültene abone ol', 'kronos' ); ?></h2>
			<p><?php esc_html_e( 'Yeni içeriklerden ilk sen haberdar ol.', 'kronos' ); ?></p>
		</div>
		<form class="kronos-newsletter__form" data-kronos-newsletter method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="kronos_newsletter">
			<?php wp_nonce_field( 'kronos_newsletter', 'kronos_newsletter_nonce' ); ?>
			<label for="kronos-newsletter-email" class="screen-reader-text"><?php esc_html_e( 'E-posta', 'kronos' ); ?></label>
			<input type="email" id="kronos-newsletter-email" name="email" required placeholder="<?php esc_attr_e( 'E-posta adresiniz', 'kronos' ); ?>">
			<button type="submit" class="kronos-button"><?php esc_html_e( 'Abone ol', 'kronos' ); ?></button>
		</form>
	</div>
</section>
