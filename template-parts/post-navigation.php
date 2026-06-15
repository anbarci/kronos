<?php

defined( 'ABSPATH' ) || exit;

$kronos_prev = get_previous_post();
$kronos_next = get_next_post();
if ( ! $kronos_prev && ! $kronos_next ) {
	return;
}
?>
<nav class="kronos-postnav" aria-label="<?php esc_attr_e( 'Yazılar arası gezinme', 'kronos' ); ?>">
	<?php if ( $kronos_prev ) : ?>
		<a class="kronos-postnav__link kronos-postnav__link--prev" href="<?php echo esc_url( get_permalink( $kronos_prev ) ); ?>" rel="prev">
			<?php if ( has_post_thumbnail( $kronos_prev ) ) : ?>
				<span class="kronos-postnav__thumb"><?php echo get_the_post_thumbnail( $kronos_prev, 'kronos-thumb', [ 'loading' => 'lazy', 'alt' => '' ] ); ?></span>
			<?php endif; ?>
			<span class="kronos-postnav__text">
				<span class="kronos-postnav__label"><?php esc_html_e( '‹ Önceki yazı', 'kronos' ); ?></span>
				<span class="kronos-postnav__title"><?php echo esc_html( get_the_title( $kronos_prev ) ); ?></span>
			</span>
		</a>
	<?php else : ?>
		<span></span>
	<?php endif; ?>

	<?php if ( $kronos_next ) : ?>
		<a class="kronos-postnav__link kronos-postnav__link--next" href="<?php echo esc_url( get_permalink( $kronos_next ) ); ?>" rel="next">
			<span class="kronos-postnav__text">
				<span class="kronos-postnav__label"><?php esc_html_e( 'Sonraki yazı ›', 'kronos' ); ?></span>
				<span class="kronos-postnav__title"><?php echo esc_html( get_the_title( $kronos_next ) ); ?></span>
			</span>
			<?php if ( has_post_thumbnail( $kronos_next ) ) : ?>
				<span class="kronos-postnav__thumb"><?php echo get_the_post_thumbnail( $kronos_next, 'kronos-thumb', [ 'loading' => 'lazy', 'alt' => '' ] ); ?></span>
			<?php endif; ?>
		</a>
	<?php endif; ?>
</nav>
