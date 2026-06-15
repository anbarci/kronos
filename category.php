<?php

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="kronos-container kronos-layout">
	<div class="kronos-content">
		<header class="kronos-archive-header">
			<h1 class="kronos-archive-title"><?php single_cat_title(); ?></h1>
			<?php the_archive_description( '<div class="kronos-archive-desc">', '</div>' ); ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="kronos-archive-list">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'list' );
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
