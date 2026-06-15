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
	<ol class="kronos-breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
		<?php
		$kronos_pos = 1;
		foreach ( $kronos_items as $item ) :
			?>
			<li class="kronos-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<?php if ( ! empty( $item['url'] ) ) : ?>
					<a itemprop="item" href="<?php echo esc_url( $item['url'] ); ?>"><span itemprop="name"><?php echo esc_html( $item['label'] ); ?></span></a>
				<?php else : ?>
					<span itemprop="name"><?php echo esc_html( $item['label'] ); ?></span>
				<?php endif; ?>
				<meta itemprop="position" content="<?php echo esc_attr( (string) $kronos_pos ); ?>" />
			</li>
			<?php
			$kronos_pos++;
		endforeach;
		?>
	</ol>
</nav>
