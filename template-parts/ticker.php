<?php

defined( 'ABSPATH' ) || exit;

$kronos_q = new WP_Query( [
	'posts_per_page'      => 8,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
] );
if ( ! $kronos_q->have_posts() ) {
	wp_reset_postdata();
	return;
}
$kronos_items = [];
while ( $kronos_q->have_posts() ) {
	$kronos_q->the_post();
	$kronos_items[] = [ 'title' => get_the_title(), 'url' => get_permalink() ];
}
wp_reset_postdata();
?>
<div class="kronos-ticker" aria-label="<?php esc_attr_e( 'Manşetler', 'kronos' ); ?>">
	<div class="kronos-container kronos-ticker__inner">
		<span class="kronos-ticker__flash" role="img" aria-label="<?php esc_attr_e( 'Son dakika', 'kronos' ); ?>"><?php echo \Kronos\Core\Helpers::icon( 'bolt' ); // phpcs:ignore ?></span>
		<div class="kronos-ticker__viewport">
			<div class="kronos-ticker__track">
				<?php foreach ( $kronos_items as $item ) : ?>
					<a class="kronos-ticker__item" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
				<?php endforeach; ?>
				<?php foreach ( $kronos_items as $item ) : ?>
					<a class="kronos-ticker__item" href="<?php echo esc_url( $item['url'] ); ?>" aria-hidden="true" tabindex="-1"><?php echo esc_html( $item['title'] ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
