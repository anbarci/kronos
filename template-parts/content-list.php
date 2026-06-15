<?php
/**
 * Resimsiz liste görünümü (kategori/arşiv için).
 *
 * @package Kronos
 */

defined( 'ABSPATH' ) || exit;

$kronos_cats  = get_the_category();
$kronos_color = ! empty( $kronos_cats ) ? \Kronos\Core\Helpers::cat_color( $kronos_cats[0]->term_id ) : 'var(--brand-primary)';
$kronos_ink   = ! empty( $kronos_cats ) ? \Kronos\Core\Helpers::cat_ink( $kronos_cats[0]->term_id ) : 'var(--brand-primary)';
?>
<article <?php post_class( 'kronos-listpost' ); ?> style="--cat-color: <?php echo esc_attr( $kronos_color ); ?>; --cat-ink: <?php echo esc_attr( $kronos_ink ); ?>">
	<?php if ( ! empty( $kronos_cats ) ) : ?>
		<a class="kronos-cat-text" href="<?php echo esc_url( get_category_link( $kronos_cats[0]->term_id ) ); ?>"><?php echo esc_html( $kronos_cats[0]->name ); ?></a>
	<?php endif; ?>

	<h2 class="kronos-listpost__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

	<p class="kronos-listpost__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32, '…' ) ); ?></p>

	<div class="kronos-listpost__meta">
		<span class="kronos-listpost__author"><?php echo esc_html( get_the_author() ); ?></span>
		<span aria-hidden="true">·</span>
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</div>
</article>
