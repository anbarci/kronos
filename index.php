<?php
/**
 * Ana fallback şablonu (post listesi).
 *
 * @package Kronos
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="kronos-container kronos-layout">
	<div class="kronos-content">
		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<h1 class="kronos-archive-title screen-reader-text"><?php single_post_title(); ?></h1>
			<?php endif; ?>

			<div class="kronos-posts">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_type() );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				[
					'mid_size'           => 1,
					'prev_text'          => __( 'Önceki', 'kronos' ),
					'next_text'          => __( 'Sonraki', 'kronos' ),
					'screen_reader_text' => __( 'Sayfalar arası gezinme', 'kronos' ),
					'aria_label'         => __( 'Sayfalar', 'kronos' ),
				]
			);
			?>

		<?php else : ?>

			<article class="kronos-card kronos-card--empty">
				<h2 class="kronos-card__title"><?php esc_html_e( 'İçerik bulunamadı.', 'kronos' ); ?></h2>
				<p><?php esc_html_e( 'Aradığınız içerik mevcut değil. Arama yapmayı deneyin.', 'kronos' ); ?></p>
				<?php get_search_form(); ?>
			</article>

		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
