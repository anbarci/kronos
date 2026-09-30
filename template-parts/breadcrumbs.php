<?php

defined( 'ABSPATH' ) || exit;

if ( is_front_page() ) {
	return;
}
$kronos_items = \Kronos\Core\Helpers::breadcrumb_items();
if ( empty( $kronos_items ) ) {
	return;
}
?>
<nav class="kronos-breadcrumbs" aria-label="<?php esc_attr_e( 'İçerik haritası', 'kronos' ); ?>">
	<ol class="kronos-breadcrumbs__list">
		<?php
		foreach ( $kronos_items as $item ) :
			?>
			<li class="kronos-breadcrumbs__item">
				<?php if ( ! empty( $item['url'] ) ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>"><span><?php echo esc_html( $item['label'] ); ?></span></a>
				<?php else : ?>
					<span><?php echo esc_html( $item['label'] ); ?></span>
				<?php endif; ?>
			</li>
			<?php
		endforeach;
		?>
	</ol>
</nav>
