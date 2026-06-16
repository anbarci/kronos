<?php
/**
 * Kronos teması bootstrap.
 *
 * @package Kronos
 */

defined( 'ABSPATH' ) || exit;

define( 'KRONOS_VERSION', '1.2.1' );
define( 'KRONOS_DIR', get_template_directory() );
define( 'KRONOS_URI', get_template_directory_uri() );

/**
 * PSR-4 benzeri otomatik yükleyici.
 *
 * Kronos\Core\Setup        -> inc/core/class-setup.php
 * Kronos\Schema\NewsArticle -> inc/schema/class-news-article.php
 */
spl_autoload_register(
	static function ( string $class ): void {
		$prefix = 'Kronos\\';

		if ( ! str_starts_with( $class, $prefix ) ) {
			return;
		}

		$parts = explode( '\\', substr( $class, strlen( $prefix ) ) );
		$name  = array_pop( $parts );
		$name  = strtolower( preg_replace( '/(?<!^)[A-Z]/', '-$0', $name ) );
		$dir   = strtolower( implode( '/', $parts ) );
		$file  = KRONOS_DIR . '/inc/' . ( '' !== $dir ? $dir . '/' : '' ) . 'class-' . $name . '.php';

		if ( is_readable( $file ) ) {
			require $file;
		}
	}
);

/**
 * Temayı başlat.
 */
\Kronos\Core\Theme::instance();
