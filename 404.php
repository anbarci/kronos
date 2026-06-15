<?php

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="kronos-container kronos-404">
	<h1 class="kronos-404__code">404</h1>
	<p class="kronos-404__text"><?php esc_html_e( 'Aradığınız sayfa bulunamadı.', 'kronos' ); ?></p>
	<?php get_search_form(); ?>
	<p><a class="kronos-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Anasayfaya dön', 'kronos' ); ?></a></p>
</div>
<?php
get_footer();
