<?php

defined( 'ABSPATH' ) || exit;

$kronos_q = \Kronos\Core\Blocks::query( [ 'posts_per_page' => 5 ] );
if ( ! $kronos_q->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="kronos-container kronos-manset" aria-label="<?php esc_attr_e( 'Manşet', 'kronos' ); ?>">
	<div class="kronos-manset__grid">
		<?php
		$i = 0;
		while ( $kronos_q->have_posts() ) :
			$kronos_q->the_post();
			$cats  = get_the_category();
			$color = ! empty( $cats ) ? \Kronos\Core\Helpers::cat_color( $cats[0]->term_id ) : 'var(--brand-primary)';
			$ink   = ! empty( $cats ) ? \Kronos\Core\Helpers::cat_ink( $cats[0]->term_id ) : 'var(--brand-primary)';

			if ( 0 === $i ) :
				?>
				<article class="kronos-manset__lead" style="--cat-color: <?php echo esc_attr( $color ); ?>">
					<a class="kronos-manset__cover" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'kronos-hero', [ 'class' => 'kronos-manset__img', 'loading' => 'eager', 'fetchpriority' => 'high' ] );
						}
						?>
					</a>
					<div class="kronos-manset__body">
						<?php if ( ! empty( $cats ) ) : ?>
							<a class="kronos-cat-badge" href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a>
						<?php endif; ?>
						<h2 class="kronos-manset__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="kronos-manset__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></p>
						<span class="kronos-manset__meta"><?php echo esc_html( get_the_author() ); ?> · <?php echo esc_html( get_the_date() ); ?></span>
					</div>
				</article>
				<ul class="kronos-manset__side">
				<?php
			else :
				?>
				<li class="kronos-manset__item" style="--cat-color: <?php echo esc_attr( $color ); ?>; --cat-ink: <?php echo esc_attr( $ink ); ?>">
					<a class="kronos-manset__thumb<?php echo has_post_thumbnail() ? '' : ' kronos-card__media--ph'; ?>" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'kronos-thumb', [ 'class' => 'kronos-manset__thumb-img', 'loading' => 'lazy' ] );
						}
						?>
					</a>
					<div>
						<?php if ( ! empty( $cats ) ) : ?>
							<a class="kronos-cat-text" href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a>
						<?php endif; ?>
						<h3 class="kronos-manset__item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</li>
				<?php
			endif;
			$i++;
		endwhile;
		?>
		</ul>
	</div>
</section>
<?php
wp_reset_postdata();
