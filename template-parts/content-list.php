<?php
/**
 * Resimsiz liste görünümü (kategori/arşiv için).
 *
 * @package Kronos
 */

defined( 'ABSPATH' ) || exit;

$kronos_cats   = get_the_category();
$kronos_color  = ! empty( $kronos_cats ) ? \Kronos\Core\Helpers::cat_color( $kronos_cats[0]->term_id ) : 'var(--brand-primary)';
$kronos_ink    = ! empty( $kronos_cats ) ? \Kronos\Core\Helpers::cat_ink( $kronos_cats[0]->term_id ) : 'var(--brand-primary)';
$kronos_author = get_the_author();
?>
<article <?php post_class( 'kronos-listpost' ); ?> style="--cat-color: <?php echo esc_attr( $kronos_color ); ?>; --cat-ink: <?php echo esc_attr( $kronos_ink ); ?>">
	<div class="kronos-listpost__date">
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</div>

	<div class="kronos-listpost__body">
		<h2 class="kronos-listpost__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<p class="kronos-listpost__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '…' ) ); ?></p>

		<?php if ( $kronos_author ) : ?>
			<div class="kronos-listpost__meta"><?php echo esc_html( $kronos_author ); ?></div>
		<?php endif; ?>
	</div>
</article>
