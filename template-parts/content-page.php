<?php

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'kronos-page' ); ?>>
	<header class="kronos-page__header">
		<h1 class="kronos-page__title"><?php the_title(); ?></h1>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="kronos-page__media">
			<?php the_post_thumbnail( 'kronos-hero', [ 'class' => 'kronos-page__img', 'loading' => 'eager' ] ); ?>
		</figure>
	<?php endif; ?>

	<div class="kronos-page__content">
		<?php
		the_content();
		wp_link_pages( [
			'before' => '<nav class="kronos-page-links" aria-label="' . esc_attr__( 'Sayfa bağlantıları', 'kronos' ) . '">',
			'after'  => '</nav>',
		] );
		?>
	</div>
</article>
