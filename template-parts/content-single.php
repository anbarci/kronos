<?php

defined( 'ABSPATH' ) || exit;
?>
<div class="kronos-reading-progress" data-kronos-progress aria-hidden="true"></div>
<article <?php post_class( 'kronos-single' ); ?> itemscope itemtype="https://schema.org/Article">
	<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

	<header class="kronos-single__header">
		<?php
		$kronos_cats = get_the_category_list( ', ' );
		if ( $kronos_cats ) :
			?>
			<div class="kronos-single__cats"><?php echo wp_kses_post( $kronos_cats ); ?></div>
		<?php endif; ?>

		<h1 class="kronos-single__title" itemprop="headline"><?php the_title(); ?></h1>

		<?php get_template_part( 'template-parts/entry-meta' ); ?>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="kronos-single__media">
			<?php
			the_post_thumbnail( 'kronos-hero', [
				'class'         => 'kronos-single__img',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'itemprop'      => 'image',
			] );
			?>
		</figure>
	<?php endif; ?>

	<?php
	$kronos_url   = rawurlencode( get_permalink() );
	$kronos_title = rawurlencode( get_the_title() );
	$kronos_share = [
		'x'        => [ 'X', '#0F1419', 'https://twitter.com/intent/tweet?url=' . $kronos_url . '&text=' . $kronos_title ],
		'facebook' => [ 'Facebook', '#1877F2', 'https://www.facebook.com/sharer/sharer.php?u=' . $kronos_url ],
		'whatsapp' => [ 'WhatsApp', '#25D366', 'https://api.whatsapp.com/send?text=' . $kronos_title . '%20' . $kronos_url ],
		'telegram' => [ 'Telegram', '#229ED9', 'https://t.me/share/url?url=' . $kronos_url . '&text=' . $kronos_title ],
		'linkedin' => [ 'LinkedIn', '#0A66C2', 'https://www.linkedin.com/sharing/share-offsite/?url=' . $kronos_url ],
		'reddit'   => [ 'Reddit', '#FF4500', 'https://www.reddit.com/submit?url=' . $kronos_url . '&title=' . $kronos_title ],
		'email'    => [ __( 'E-posta', 'kronos' ), '#64748B', 'mailto:?subject=' . $kronos_title . '&body=' . $kronos_url ],
	];
	?>
	<div class="kronos-share">
		<span class="kronos-share__label"><?php esc_html_e( 'Bu yazıyı paylaş', 'kronos' ); ?></span>
		<div class="kronos-share__buttons">
			<?php foreach ( $kronos_share as $kronos_key => $kronos_p ) : ?>
				<a class="kronos-share__btn kronos-share__btn--<?php echo esc_attr( $kronos_key ); ?>" style="--share: <?php echo esc_attr( $kronos_p[1] ); ?>" href="<?php echo esc_url( $kronos_p[2] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $kronos_p[0] ); ?>"><?php echo \Kronos\Core\Helpers::social_icon( $kronos_key ); // phpcs:ignore ?></a>
			<?php endforeach; ?>
			<button type="button" class="kronos-share__btn kronos-share__btn--copy" data-kronos-copy="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Bağlantıyı kopyala', 'kronos' ); ?>"><?php echo \Kronos\Core\Helpers::social_icon( 'link' ); // phpcs:ignore ?></button>
		</div>
	</div>

	<div class="kronos-single__content" itemprop="articleBody">
		<?php
		the_content();
		wp_link_pages( [
			'before' => '<nav class="kronos-page-links" aria-label="' . esc_attr__( 'Sayfa bağlantıları', 'kronos' ) . '">',
			'after'  => '</nav>',
		] );
		?>
	</div>

	<footer class="kronos-single__footer">
		<?php the_tags( '<div class="kronos-single__tags">', '', '</div>' ); ?>
	</footer>

	<?php get_template_part( 'template-parts/author-bio' ); ?>
	<?php get_template_part( 'template-parts/post-navigation' ); ?>
	<?php get_template_part( 'template-parts/related-posts' ); ?>
</article>
