<?php
/**
 * Liste görünümü için yazı kartı.
 *
 * @package Kronos
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'kronos-card' ); ?> aria-labelledby="post-<?php the_ID(); ?>-title">

	<a class="kronos-card__media<?php echo has_post_thumbnail() ? '' : ' kronos-card__media--ph'; ?>" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'kronos-card', [ 'class' => 'kronos-card__img', 'loading' => 'lazy' ] );
		}
		?>
	</a>

	<div class="kronos-card__body">
		<?php
		$kronos_cats = get_the_category_list( ', ' );
		if ( $kronos_cats ) :
			?>
			<div class="kronos-card__cats"><?php echo wp_kses_post( $kronos_cats ); ?></div>
		<?php endif; ?>

		<h2 class="kronos-card__title" id="post-<?php the_ID(); ?>-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<div class="kronos-card__meta">
			<span class="kronos-card__author"><?php echo esc_html( get_the_author() ); ?></span>
			<time class="kronos-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>
		</div>

		<p class="kronos-card__excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?>
		</p>
	</div>
</article>
