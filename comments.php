<?php

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="kronos-comments" aria-label="<?php esc_attr_e( 'Yorumlar', 'kronos' ); ?>">
	<?php if ( have_comments() ) : ?>
		<h2 class="kronos-comments__title">
			<?php
			$kronos_count = get_comments_number();
			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s Yorum', '%s Yorum', $kronos_count, 'kronos' ) ),
				'<span>' . esc_html( number_format_i18n( $kronos_count ) ) . '</span>'
			);
			?>
		</h2>

		<ol class="kronos-comments__list">
			<?php
			wp_list_comments( [
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 48,
				'callback'    => [ '\Kronos\Core\Comments', 'item' ],
			] );
			?>
		</ol>

		<?php
		the_comments_pagination( [
			'prev_text' => __( '‹ Önceki', 'kronos' ),
			'next_text' => __( 'Sonraki ›', 'kronos' ),
		] );
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="kronos-comments__closed"><?php esc_html_e( 'Yorumlar kapalı.', 'kronos' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	$kronos_cf_args = [
		'class_form'           => 'kronos-comment-form',
		'class_submit'         => 'kronos-button',
		'title_reply'          => __( 'Yorum yaz', 'kronos' ),
		'title_reply_before'   => '<h3 id="reply-title" class="kronos-comment-form__title screen-reader-text">',
		'title_reply_after'    => '</h3>',
		'comment_notes_before' => '',
	];
	if ( comments_open() ) :
		?>
		<details class="kronos-commentform">
			<summary class="kronos-commentform__head">
				<span class="kronos-commentform__icon"><?php echo \Kronos\Core\Helpers::icon( 'comment' ); // phpcs:ignore ?></span>
				<span class="kronos-commentform__label"><?php esc_html_e( 'Yorum Yaz', 'kronos' ); ?></span>
				<span class="kronos-commentform__toggle" aria-hidden="true"><?php echo \Kronos\Core\Helpers::icon( 'plus' ); // phpcs:ignore ?></span>
			</summary>
			<div class="kronos-commentform__body">
				<?php comment_form( $kronos_cf_args ); ?>
			</div>
		</details>
		<?php
	else :
		comment_form( $kronos_cf_args );
	endif;
	?>
</section>
