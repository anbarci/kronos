<?php

defined( 'ABSPATH' ) || exit;
?>
<div class="kronos-card kronos-card--empty">
	<h2 class="kronos-card__title"><?php esc_html_e( 'Bir şey bulunamadı.', 'kronos' ); ?></h2>
	<p><?php esc_html_e( 'Aramanızı değiştirip tekrar deneyebilirsiniz.', 'kronos' ); ?></p>
	<?php get_search_form(); ?>
</div>
