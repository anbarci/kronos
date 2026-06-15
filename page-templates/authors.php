<?php
/**
 * Template Name: Yazarlar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kronos_authors = get_users( [
	'who'                 => 'authors',
	'has_published_posts' => [ 'post' ],
	'orderby'             => 'post_count',
	'order'               => 'DESC',
] );
?>
<div class="kronos-container kronos-authors-page">
	<?php while ( have_posts() ) : the_post(); ?>
		<h1 class="kronos-page__title"><?php the_title(); ?></h1>
		<div class="kronos-page__content"><?php the_content(); ?></div>
	<?php endwhile; ?>

	<div class="kronos-authors-grid">
		<?php foreach ( $kronos_authors as $kronos_author ) : ?>
			<article class="kronos-author-tile">
				<a href="<?php echo esc_url( get_author_posts_url( $kronos_author->ID ) ); ?>">
					<?php echo get_avatar( $kronos_author->ID, 80, '', '', [ 'class' => 'kronos-author-tile__avatar' ] ); ?>
					<span class="kronos-author-tile__name"><?php echo esc_html( $kronos_author->display_name ); ?></span>
					<span class="kronos-author-tile__count">
						<?php
						printf(
							/* translators: %d: post count. */
							esc_html__( '%d içerik', 'kronos' ),
							(int) count_user_posts( $kronos_author->ID, 'post', true )
						);
						?>
					</span>
				</a>
			</article>
		<?php endforeach; ?>
	</div>
</div>
<?php
get_footer();
