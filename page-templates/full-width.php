<?php
/**
 * Template Name: Tam Genişlik
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="kronos-container kronos-fullwidth">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'kronos-page' ); ?>>
			<h1 class="kronos-page__title"><?php the_title(); ?></h1>
			<div class="kronos-page__content"><?php the_content(); ?></div>
		</article>
		<?php
	endwhile;
	?>
</div>
<?php
get_footer();
