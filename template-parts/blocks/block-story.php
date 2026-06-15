<?php

defined( 'ABSPATH' ) || exit;

$kronos_q = \Kronos\Core\Blocks::query( [ 'posts_per_page' => 10, 'meta_key' => '_thumbnail_id' ] );
if ( ! $kronos_q->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="kronos-container kronos-stories" aria-label="<?php esc_attr_e( 'Hikâyeler', 'kronos' ); ?>">
	<ul class="kronos-stories__track">
		<?php
		while ( $kronos_q->have_posts() ) :
			$kronos_q->the_post();
			?>
			<li class="kronos-stories__item">
				<a href="<?php the_permalink(); ?>">
					<span class="kronos-stories__ring">
						<?php the_post_thumbnail( 'kronos-thumb', [ 'class' => 'kronos-stories__img', 'loading' => 'lazy' ] ); ?>
					</span>
					<span class="kronos-stories__label"><?php echo esc_html( wp_trim_words( get_the_title(), 4, '…' ) ); ?></span>
				</a>
			</li>
			<?php
		endwhile;
		?>
	</ul>
</section>
<?php
wp_reset_postdata();
