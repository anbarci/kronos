<?php

defined( 'ABSPATH' ) || exit;

$kronos_q = \Kronos\Core\Blocks::query( [
	'posts_per_page' => 6,
	'orderby'        => 'comment_count',
	'order'          => 'DESC',
] );
if ( ! $kronos_q->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="kronos-container kronos-block kronos-trending" aria-label="<?php esc_attr_e( 'Popüler', 'kronos' ); ?>">
	<h2 class="kronos-block__title"><?php esc_html_e( 'Popüler İçerikler', 'kronos' ); ?></h2>
	<div class="kronos-posts">
		<?php
		while ( $kronos_q->have_posts() ) :
			$kronos_q->the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;
		?>
	</div>
</section>
<?php
wp_reset_postdata();
