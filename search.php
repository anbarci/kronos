<?php

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="kronos-container kronos-layout">
	<div class="kronos-content">
		<header class="kronos-archive-header">
			<h1 class="kronos-archive-title">
				<?php
				printf(
					/* translators: %s: search query. */
					esc_html__( '“%s” için sonuçlar', 'kronos' ),
					'<span>' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
			<?php get_search_form(); ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="kronos-posts">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_type() );
				endwhile;
				?>
			</div>
			<?php
			the_posts_pagination( [
				'prev_text'  => __( 'Önceki', 'kronos' ),
				'next_text'  => __( 'Sonraki', 'kronos' ),
				'aria_label' => __( 'Sayfalar', 'kronos' ),
			] );
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php
get_footer();
