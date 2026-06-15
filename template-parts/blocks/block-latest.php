<?php

defined( 'ABSPATH' ) || exit;

$kronos_q = \Kronos\Core\Blocks::query( [ 'posts_per_page' => 9 ] );
if ( ! $kronos_q->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="kronos-container kronos-block kronos-feed" aria-label="<?php esc_attr_e( 'Yeni Eklenenler', 'kronos' ); ?>">
	<div class="kronos-feed__head">
		<h2 class="kronos-block__title"><?php esc_html_e( 'Yeni Eklenenler', 'kronos' ); ?></h2>
		<div class="kronos-feed__nav" aria-hidden="true">
			<button type="button" class="kronos-feed__btn" data-kronos-feed-prev tabindex="-1" aria-label="<?php esc_attr_e( 'Geri', 'kronos' ); ?>">‹</button>
			<button type="button" class="kronos-feed__btn" data-kronos-feed-next tabindex="-1" aria-label="<?php esc_attr_e( 'İleri', 'kronos' ); ?>">›</button>
		</div>
	</div>
	<div class="kronos-feed__track" data-kronos-feed-track>
		<?php
		while ( $kronos_q->have_posts() ) :
			$kronos_q->the_post();
			$cats  = get_the_category();
			$color = ! empty( $cats ) ? \Kronos\Core\Helpers::cat_color( $cats[0]->term_id ) : 'var(--brand-primary)';
			?>
			<article class="kronos-feed__card" style="--cat-color: <?php echo esc_attr( $color ); ?>">
				<a class="kronos-feed__media<?php echo has_post_thumbnail() ? '' : ' kronos-card__media--ph'; ?>" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'kronos-card', [ 'class' => 'kronos-feed__img', 'loading' => 'lazy' ] ); } ?>
					<?php if ( ! empty( $cats ) ) : ?>
						<span class="kronos-feed__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
					<?php endif; ?>
				</a>
				<div class="kronos-feed__body">
					<h3 class="kronos-feed__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<span class="kronos-feed__meta"><?php echo esc_html( get_the_author() ); ?> · <?php echo esc_html( get_the_date() ); ?></span>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</section>
<?php
wp_reset_postdata();
