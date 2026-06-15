<?php

defined( 'ABSPATH' ) || exit;

get_header();

$kronos_author = get_queried_object();
?>
<div class="kronos-container kronos-layout">
	<div class="kronos-content">
		<header class="kronos-author-header">
			<?php echo get_avatar( $kronos_author->ID ?? 0, 96, '', '', [ 'class' => 'kronos-author-header__avatar' ] ); ?>
			<div>
				<h1 class="kronos-author-header__name"><?php echo esc_html( get_the_author_meta( 'display_name', $kronos_author->ID ?? 0 ) ); ?></h1>
				<?php if ( $bio = get_the_author_meta( 'description', $kronos_author->ID ?? 0 ) ) : ?>
					<p class="kronos-author-header__bio"><?php echo esc_html( $bio ); ?></p>
				<?php endif; ?>
			</div>
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
