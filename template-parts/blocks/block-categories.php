<?php

defined( 'ABSPATH' ) || exit;

$kronos_cats = get_categories( [ 'number' => 8, 'orderby' => 'count', 'order' => 'DESC' ] );
if ( empty( $kronos_cats ) ) {
	return;
}
?>
<section class="kronos-container kronos-block kronos-cats" aria-label="<?php esc_attr_e( 'Kategoriler', 'kronos' ); ?>">
	<h2 class="kronos-block__title"><?php esc_html_e( 'Kategoriler', 'kronos' ); ?></h2>
	<ul class="kronos-cats__grid">
		<?php foreach ( $kronos_cats as $kronos_cat ) : ?>
			<li class="kronos-cats__item">
				<a href="<?php echo esc_url( get_category_link( $kronos_cat->term_id ) ); ?>">
					<span class="kronos-cats__name"><?php echo esc_html( $kronos_cat->name ); ?></span>
					<span class="kronos-cats__count"><?php echo esc_html( number_format_i18n( $kronos_cat->count ) ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
