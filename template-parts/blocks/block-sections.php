<?php

defined( 'ABSPATH' ) || exit;

$kronos_cats = get_categories( [ 'orderby' => 'count', 'order' => 'DESC', 'number' => 4, 'hide_empty' => true ] );
if ( empty( $kronos_cats ) ) {
	return;
}
?>
<section class="kronos-container kronos-block kronos-colsec" aria-label="<?php esc_attr_e( 'Kategoriler', 'kronos' ); ?>">
	<h2 class="kronos-block__title"><?php esc_html_e( 'Kategorilere Göz At', 'kronos' ); ?></h2>
	<div class="kronos-colsec__grid">
		<?php
		foreach ( $kronos_cats as $kronos_cat ) :
			$q = \Kronos\Core\Blocks::query( [ 'cat' => $kronos_cat->term_id, 'posts_per_page' => 5 ] );
			if ( ! $q->have_posts() ) {
				wp_reset_postdata();
				continue;
			}
			$color = \Kronos\Core\Helpers::cat_color( $kronos_cat->term_id );
			?>
			<div class="kronos-colsec__col" style="--cat-color: <?php echo esc_attr( $color ); ?>">
				<h3 class="kronos-colsec__title"><a href="<?php echo esc_url( get_category_link( $kronos_cat->term_id ) ); ?>"><?php echo esc_html( $kronos_cat->name ); ?></a></h3>
				<ul class="kronos-colsec__list">
					<?php
					while ( $q->have_posts() ) :
						$q->the_post();
						?>
						<li class="kronos-colsec__item">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						</li>
						<?php
					endwhile;
					?>
				</ul>
			</div>
			<?php
			wp_reset_postdata();
		endforeach;
		?>
	</div>
</section>
