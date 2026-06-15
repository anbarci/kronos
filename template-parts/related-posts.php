<?php

defined( 'ABSPATH' ) || exit;

$kronos_cats = wp_get_post_categories( get_the_ID() );
if ( empty( $kronos_cats ) ) {
	return;
}
$kronos_limit = \Kronos\Core\Mode::is_news() ? 6 : 3;
$kronos_related = new WP_Query( [
	'category__in'        => $kronos_cats,
	'post__not_in'        => [ get_the_ID() ],
	'posts_per_page'      => $kronos_limit,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
] );
if ( ! $kronos_related->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="kronos-related" aria-label="<?php esc_attr_e( 'İlgili içerikler', 'kronos' ); ?>">
	<h2 class="kronos-related__title"><?php echo \Kronos\Core\Mode::is_news() ? esc_html__( 'İlgili Haberler', 'kronos' ) : esc_html__( 'İlgili Yazılar', 'kronos' ); ?></h2>
	<div class="kronos-related__grid">
		<?php
		while ( $kronos_related->have_posts() ) :
			$kronos_related->the_post();
			?>
			<article class="kronos-related__card">
				<a class="kronos-related__link" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'kronos-thumb', [ 'class' => 'kronos-related__img', 'loading' => 'lazy' ] ); ?>
					<?php endif; ?>
					<h3 class="kronos-related__name"><?php the_title(); ?></h3>
				</a>
			</article>
			<?php
		endwhile;
		?>
	</div>
</section>
<?php
wp_reset_postdata();
