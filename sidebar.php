<?php

defined( 'ABSPATH' ) || exit;

if ( 'none' === \Kronos\Core\Options::get( 'kronos_sidebar_position' ) ) {
	return;
}
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="kronos-sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Yan sütun', 'kronos' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
