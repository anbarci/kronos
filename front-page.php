<?php

defined( 'ABSPATH' ) || exit;

if ( 'posts' !== get_option( 'show_on_front' ) && ! is_home() ) {
	$kronos_has_blocks = (string) \Kronos\Core\Options::get( 'kronos_home_blocks' );
	if ( '' === $kronos_has_blocks ) {
		get_template_part( 'index' );
		return;
	}
}

get_header();

$kronos_desc = get_bloginfo( 'description' );
?>
<h1 class="screen-reader-text"><?php bloginfo( 'name' ); ?><?php echo $kronos_desc ? ': ' . esc_html( $kronos_desc ) : ''; ?></h1>
<div class="kronos-home kronos-home--<?php echo esc_attr( \Kronos\Core\Mode::current() ); ?>">
	<?php \Kronos\Core\Blocks::render(); ?>
</div>
<?php
get_footer();
