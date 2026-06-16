<?php

defined( 'ABSPATH' ) || exit;

$kronos_footer_text = \Kronos\Core\Options::get( 'kronos_footer_text' );
$kronos_socials     = \Kronos\Core\Options::social_links();
?>
</main><!-- #kronos-main -->

<footer class="kronos-site-footer" role="contentinfo">
	<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
		<div class="kronos-container kronos-footer-widgets">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
					<div class="kronos-footer-col"><?php dynamic_sidebar( 'footer-' . $i ); ?></div>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	<?php endif; ?>

	<div class="kronos-container kronos-site-footer__inner">
		<?php if ( $kronos_socials ) : ?>
			<ul class="kronos-social" aria-label="<?php esc_attr_e( 'Sosyal medya', 'kronos' ); ?>">
				<?php foreach ( $kronos_socials as $social ) : ?>
					<li><a href="<?php echo esc_url( $social['url'] ); ?>" rel="noopener noreferrer me" target="_blank"><?php echo esc_html( $social['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php
		if ( has_nav_menu( 'footer' ) ) {
			wp_nav_menu( [
				'theme_location'       => 'footer',
				'container'            => 'nav',
				'container_class'      => 'kronos-footer-nav',
				'container_aria_label' => __( 'Footer menü', 'kronos' ),
				'menu_class'           => 'kronos-footer-nav__list',
				'fallback_cb'          => false,
				'depth'                => 1,
			] );
		}
		?>

		<div class="kronos-copyright">
			<?php
			if ( $kronos_footer_text ) {
				echo wp_kses_post( $kronos_footer_text );
			} else {
				printf( '&copy; %s %s', esc_html( wp_date( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) );
			}
			?>
		</div>
	</div>
</footer>

<button type="button" class="kronos-totop" data-kronos-totop aria-label="<?php esc_attr_e( 'Yukarı çık', 'kronos' ); ?>"><?php echo \Kronos\Core\Helpers::icon( 'arrow-up' ); // phpcs:ignore ?></button>

<?php wp_footer(); ?>
<!-- Kronos teması - Telif (c) 2026 hazermedya.com (Hikmet Anbarcı). Haklar & iletişim: https://hazermedya.com -->
</body>
</html>
