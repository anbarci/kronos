<?php

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="kronos-container kronos-layout kronos-layout--single">
	<div class="kronos-content">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'single' );

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php
get_footer();
