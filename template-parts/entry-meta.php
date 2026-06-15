<?php

defined( 'ABSPATH' ) || exit;

$kronos_reading = \Kronos\Core\Helpers::reading_time( get_the_content() );
?>
<div class="kronos-entry-meta">
	<span class="kronos-entry-meta__author" itemprop="author" itemscope itemtype="https://schema.org/Person">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 28, '', '', [ 'class' => 'kronos-entry-meta__avatar' ] ); ?>
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" itemprop="url">
			<span itemprop="name"><?php the_author(); ?></span>
		</a>
	</span>
	<time class="kronos-entry-meta__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
		<?php echo esc_html( get_the_date() ); ?>
	</time>
	<span class="kronos-entry-meta__reading"><?php echo esc_html( $kronos_reading ); ?></span>
	<?php if ( comments_open() ) : ?>
		<a class="kronos-entry-meta__comments" href="#comments"><?php comments_number( __( 'Yorum yok', 'kronos' ), __( '1 yorum', 'kronos' ), __( '% yorum', 'kronos' ) ); ?></a>
	<?php endif; ?>
</div>
