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
	$kronos_img   = has_post_thumbnail() ? rawurlencode( (string) get_the_post_thumbnail_url( get_the_ID(), 'full' ) ) : '';
	$kronos_share = [
		'x'        => [ 'X', '#0F1419', 'https://twitter.com/intent/tweet?url=' . $kronos_url . '&text=' . $kronos_title ],
		'facebook' => [ 'Facebook', '#1877F2', 'https://www.facebook.com/sharer/sharer.php?u=' . $kronos_url ],
		'whatsapp' => [ 'WhatsApp', '#25D366', 'https://api.whatsapp.com/send?text=' . $kronos_title . '%20' . $kronos_url ],
		'telegram' => [ 'Telegram', '#229ED9', 'https://t.me/share/url?url=' . $kronos_url . '&text=' . $kronos_title ],
		'linkedin' => [ 'LinkedIn', '#0A66C2', 'https://www.linkedin.com/sharing/share-offsite/?url=' . $kronos_url ],
		'reddit'    => [ 'Reddit', '#FF4500', 'https://www.reddit.com/submit?url=' . $kronos_url . '&title=' . $kronos_title ],
		'pinterest' => [ 'Pinterest', '#E60023', 'https://pinterest.com/pin/create/button/?url=' . $kronos_url . '&media=' . $kronos_img . '&description=' . $kronos_title ],
		'email'     => [ __( 'E-posta', 'kronos' ), '#64748B', 'mailto:?subject=' . $kronos_title . '&body=' . $kronos_url ],
	];
	$kronos_ask = __( 'Bu yazıyı özetle ve en önemli noktalarını madde madde çıkar:', 'kronos' ) . ' ' . get_permalink();
	$kronos_q   = rawurlencode( $kronos_ask );
	// Her servis URL'den prompt almıyor. Güvenlik açıkları (Reprompt vb.) nedeniyle
	// Claude (2025-10), Copilot (2026-01) URL-prefill'i kaldırdı; Gemini hiç desteklemedi;
	// Grok'ta da güvenilir bir parametre yok. Bu yüzden iki mod:
	//   'url'  => prompt bağlantıyla gider (ChatGPT, Perplexity) — tek tık, otomatik gönderim.
	//   'copy' => prompt panoya kopyalanır + sohbet sayfası açılır, kullanıcı yapıştırır.
	$kronos_ai = [
		'ChatGPT'    => [ '#10A37F', 'url',  'https://chatgpt.com/?q=' . $kronos_q ],
		'Perplexity' => [ '#20808D', 'url',  'https://www.perplexity.ai/search?q=' . $kronos_q ],
		'Claude'     => [ '#D97757', 'copy', 'https://claude.ai/new' ],
		'Gemini'     => [ '#4285F4', 'copy', 'https://gemini.google.com/app' ],
		'Grok'       => [ '#111111', 'copy', 'https://grok.com/' ],
		'Copilot'    => [ '#0A6CFF', 'copy', 'https://copilot.microsoft.com/' ],
	];
	?>
	<div class="kronos-actions">
		<button type="button" class="kronos-action-btn" data-kronos-modal-open="share" aria-haspopup="dialog"><?php echo \Kronos\Core\Helpers::icon( 'share' ); // phpcs:ignore ?><span class="kronos-action-btn__lg"><?php esc_html_e( 'Bu yazıyı paylaş', 'kronos' ); ?></span><span class="kronos-action-btn__sm"><?php esc_html_e( 'Paylaş', 'kronos' ); ?></span></button>
		<div class="kronos-fontsize" role="group" aria-label="<?php esc_attr_e( 'Yazı boyutu', 'kronos' ); ?>">
			<button type="button" class="kronos-fontsize__btn kronos-fontsize__btn--down" data-kronos-font="down" aria-label="<?php esc_attr_e( 'Yazıyı küçült', 'kronos' ); ?>">A−</button>
			<button type="button" class="kronos-fontsize__btn kronos-fontsize__btn--up" data-kronos-font="up" aria-label="<?php esc_attr_e( 'Yazıyı büyült', 'kronos' ); ?>">A+</button>
		</div>
		<button type="button" class="kronos-action-btn kronos-action-btn--ai" data-kronos-modal-open="askai" aria-haspopup="dialog"><?php echo \Kronos\Core\Helpers::icon( 'sparkle' ); // phpcs:ignore ?><span class="kronos-action-btn__lg"><?php esc_html_e( 'Yapay zekaya sor', 'kronos' ); ?></span><span class="kronos-action-btn__sm"><?php esc_html_e( 'AI Sor', 'kronos' ); ?></span></button>
	</div>

	<div class="kronos-modal" data-kronos-modal="share" hidden>
		<div class="kronos-modal__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Bu yazıyı paylaş', 'kronos' ); ?>">
			<button type="button" class="kronos-modal__close" data-kronos-modal-close aria-label="<?php esc_attr_e( 'Kapat', 'kronos' ); ?>"><?php echo \Kronos\Core\Helpers::icon( 'close' ); // phpcs:ignore ?></button>
			<span class="kronos-modal__title"><?php esc_html_e( 'Bu yazıyı paylaş', 'kronos' ); ?></span>
			<div class="kronos-share__buttons">
				<?php foreach ( $kronos_share as $kronos_key => $kronos_p ) : ?>
					<a class="kronos-share__btn kronos-share__btn--<?php echo esc_attr( $kronos_key ); ?>" style="--share: <?php echo esc_attr( $kronos_p[1] ); ?>" href="<?php echo esc_url( $kronos_p[2] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $kronos_p[0] ); ?>"><?php echo \Kronos\Core\Helpers::social_icon( $kronos_key ); // phpcs:ignore ?></a>
				<?php endforeach; ?>
				<button type="button" class="kronos-share__btn kronos-share__btn--copy" data-kronos-copy="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Bağlantıyı kopyala', 'kronos' ); ?>"><?php echo \Kronos\Core\Helpers::social_icon( 'copy' ); // phpcs:ignore ?></button>
			</div>
		</div>
	</div>

	<div class="kronos-modal" data-kronos-modal="askai" hidden>
		<div class="kronos-modal__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Yapay zekaya sor', 'kronos' ); ?>">
			<button type="button" class="kronos-modal__close" data-kronos-modal-close aria-label="<?php esc_attr_e( 'Kapat', 'kronos' ); ?>"><?php echo \Kronos\Core\Helpers::icon( 'close' ); // phpcs:ignore ?></button>
			<span class="kronos-modal__title"><?php esc_html_e( 'Yapay zekaya sor', 'kronos' ); ?></span>
			<p class="kronos-modal__desc"><?php esc_html_e( 'Bu yazının özetini veya analizini seçtiğin yapay zekadan al.', 'kronos' ); ?></p>
			<div class="kronos-ai-list">
				<?php foreach ( $kronos_ai as $kronos_ai_name => $kronos_ai_p ) : ?>
					<?php $kronos_ai_copy = ( 'copy' === $kronos_ai_p[1] ); ?>
					<a class="kronos-ai<?php echo $kronos_ai_copy ? ' kronos-ai--copy' : ''; ?>" style="--ai: <?php echo esc_attr( $kronos_ai_p[0] ); ?>" href="<?php echo esc_url( $kronos_ai_p[2] ); ?>" target="_blank" rel="noopener nofollow"<?php echo $kronos_ai_copy ? ' data-kronos-ai-copy="' . esc_attr( $kronos_ask ) . '"' : ''; ?>>
						<span class="kronos-ai__icon"><?php echo \Kronos\Core\Helpers::icon( $kronos_ai_copy ? 'copy' : 'sparkle' ); // phpcs:ignore ?></span>
						<span class="kronos-ai__name"><?php echo esc_html( $kronos_ai_name ); ?></span>
						<?php if ( $kronos_ai_copy ) : ?><span class="kronos-ai__tag"><?php esc_html_e( 'kopyala &amp; aç', 'kronos' ); ?></span><?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
			<p class="kronos-ai-note"><?php esc_html_e( '“Kopyala &amp; aç” işaretli yapay zekalar bağlantıdan soru almıyor; tıklayınca soru panoya kopyalanır, açılan sohbete yapıştırın (Ctrl/⌘+V).', 'kronos' ); ?></p>
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
