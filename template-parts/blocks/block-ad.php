<?php

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'footer-1' ) ) {
	$kronos_code = (string) get_theme_mod( 'kronos_home_ad_code', '' );
	if ( '' === trim( $kronos_code ) ) {
		return;
	}
	?>
	<div class="kronos-container kronos-ad" aria-label="<?php esc_attr_e( 'Reklam', 'kronos' ); ?>">
		<?php echo wp_kses_post( $kronos_code ); ?>
	</div>
	<?php
}
