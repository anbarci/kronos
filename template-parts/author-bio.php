<?php

defined( 'ABSPATH' ) || exit;

$kronos_bio = get_the_author_meta( 'description' );
if ( ! $kronos_bio ) {
	return;
}
?>
<aside class="kronos-author-bio" aria-label="<?php esc_attr_e( 'Yazar hakkında', 'kronos' ); ?>">
	<?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', '', [ 'class' => 'kronos-author-bio__avatar' ] ); ?>
	<div class="kronos-author-bio__body">
		<a class="kronos-author-bio__name" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a>
		<p class="kronos-author-bio__text"><?php echo esc_html( $kronos_bio ); ?></p>
	</div>
</aside>
