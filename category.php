<?php

defined( 'ABSPATH' ) || exit;

get_header();

$kronos_term   = get_queried_object();
$kronos_accent = ( $kronos_term && isset( $kronos_term->term_id ) )
	? \Kronos\Core\Helpers::cat_color( $kronos_term->term_id )
	: 'var(--brand-primary)';
?>
<div class="kronos-container kronos-layout">
	<div class="kronos-content">
		<header class="kronos-archive-header" style="--cat-accent: <?php echo esc_attr( $kronos_accent ); ?>">
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
