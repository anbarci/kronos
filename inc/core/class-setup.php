<?php
/**
 * Tema kurulumu: destekler, menüler, dil, görsel boyutları.
 *
 * @package Kronos\Core
 */

namespace Kronos\Core;

defined( 'ABSPATH' ) || exit;

class Setup {

	public function register(): void {
		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ] );
		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
	}

	/**
	 * Tema desteklerini ve menüleri tanımla.
	 */
	public function after_setup_theme(): void {
		load_theme_textdomain( 'kronos', KRONOS_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor.css' );
		add_theme_support( 'custom-logo', [
			'height'      => 60,
			'width'       => 220,
			'flex-width'  => true,
			'flex-height' => true,
		] );
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		] );

		register_nav_menus( [
			'primary' => __( 'Ana Menü', 'kronos' ),
			'footer'  => __( 'Footer Menü', 'kronos' ),
		] );

		// Tema genelinde kullanılan içerik görsel boyutları.
		add_image_size( 'kronos-card', 800, 450, true );
		add_image_size( 'kronos-thumb', 400, 225, true );
		add_image_size( 'kronos-hero', 1280, 720, true );
	}

	/**
	 * Widget bölgelerini kaydet.
	 */
	public function widgets_init(): void {
		register_sidebar( [
			'name'          => __( 'Yan Sütun', 'kronos' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Ana yan sütun bölgesi.', 'kronos' ),
			'before_widget' => '<section id="%1$s" class="kronos-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="kronos-widget__title">',
			'after_title'   => '</h2>',
		] );

		for ( $i = 1; $i <= 3; $i++ ) {
			register_sidebar( [
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer %d', 'kronos' ), $i ),
				'id'            => 'footer-' . $i,
				'before_widget' => '<section id="%1$s" class="kronos-widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="kronos-widget__title">',
				'after_title'   => '</h2>',
			] );
		}
	}
}
