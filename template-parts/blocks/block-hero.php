<?php

defined( 'ABSPATH' ) || exit;

$kronos_q = \Kronos\Core\Blocks::query( [ 'posts_per_page' => 5 ] );
if ( ! $kronos_q->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="kronos-container kronos-hero" aria-label="<?php esc_attr_e( 'Öne çıkanlar', 'kronos' ); ?>">
	<div class="kronos-hero__grid">
		<?php
		$kronos_i = 0;
		while ( $kronos_q->have_posts() ) :
			$kronos_q->the_post();
			$kronos_class = 0 === $kronos_i ? 'kronos-hero__main' : 'kronos-hero__item';
			$kronos_cats  = get_the_category();
			?>
			<article class="<?php echo esc_attr( $kronos_class ); ?>">
				<a class="kronos-hero__cover" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 0 === $kronos_i ? 'kronos-hero' : 'kronos-thumb', [
							'class'         => 'kronos-hero__img',
							'loading'       => 0 === $kronos_i ? 'eager' : 'lazy',
							'fetchpriority' => 0 === $kronos_i ? 'high' : 'auto',
						] );
					}
					?>
				</a>
				<div class="kronos-hero__body">
					<?php if ( ! empty( $kronos_cats ) ) : ?>
						<a class="kronos-hero__cat" href="<?php echo esc_url( get_category_link( $kronos_cats[0]->term_id ) ); ?>"><?php echo esc_html( $kronos_cats[0]->name ); ?></a>
					<?php endif; ?>
					<h2 class="kronos-hero__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</div>
			</article>
			<?php
			$kronos_i++;
		endwhile;
		?>
	</div>
</section>
<?php
wp_reset_postdata();
