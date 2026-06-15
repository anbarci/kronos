<?php

namespace Kronos\Widgets;

defined( 'ABSPATH' ) || exit;

class AuthorsList extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'kronos_authors_list',
			__( 'Kronos: Yazarlar', 'kronos' ),
			[ 'description' => __( 'İçerik üreten yazarların listesi.', 'kronos' ) ]
		);
	}

	public function widget( $args, $instance ) {
		$title   = apply_filters( 'widget_title', $instance['title'] ?? __( 'Yazarlar', 'kronos' ) );
		$authors = get_users( [
			'who'                 => 'authors',
			'has_published_posts' => [ 'post' ],
			'number'              => 10,
			'orderby'             => 'post_count',
			'order'               => 'DESC',
		] );

		if ( empty( $authors ) ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '<ul class="kronos-widget-authors">';
		foreach ( $authors as $author ) {
			printf(
				'<li class="kronos-widget-authors__item"><a href="%s">%s %s</a></li>',
				esc_url( get_author_posts_url( $author->ID ) ),
				get_avatar( $author->ID, 32 ),
				esc_html( $author->display_name )
			);
		}
		echo '</ul>';
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function form( $instance ) {
		$title = $instance['title'] ?? __( 'Yazarlar', 'kronos' );
		printf(
			'<p><label for="%1$s">%2$s</label><input class="widefat" id="%1$s" name="%3$s" type="text" value="%4$s"></p>',
			esc_attr( $this->get_field_id( 'title' ) ),
			esc_html__( 'Başlık', 'kronos' ),
			esc_attr( $this->get_field_name( 'title' ) ),
			esc_attr( $title )
		);
		return '';
	}

	public function update( $new_instance, $old_instance ) {
		return [ 'title' => sanitize_text_field( $new_instance['title'] ?? '' ) ];
	}
}
