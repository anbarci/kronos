<?php

defined( 'ABSPATH' ) || exit;

$kronos_id = 'kronos-search-' . wp_unique_id();
?>
<form role="search" method="get" class="kronos-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $kronos_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Ara:', 'kronos' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $kronos_id ); ?>" class="kronos-search-form__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Ara…', 'kronos' ); ?>" />
	<button type="submit" class="kronos-search-form__submit"><?php esc_html_e( 'Ara', 'kronos' ); ?></button>
</form>
