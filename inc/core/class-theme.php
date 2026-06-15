<?php
/**
 * Ana tema konteyneri.
 *
 * @package Kronos\Core
 */

namespace Kronos\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Tüm modülleri bağlayan singleton.
 */
final class Theme {

	private static ?Theme $instance = null;

	/**
	 * Tekil örnek.
	 */
	public static function instance(): Theme {
		return self::$instance ??= new self();
	}

	private function __construct() {
		$this->boot();
	}

	/**
	 * Modülleri kaydet.
	 */
	private function boot(): void {
		( new Setup() )->register();
		( new Assets() )->register();
		( new Mode() )->register();
		( new DynamicCss() )->register();
		( new InContent() )->register();
		( new Newsletter() )->register();

		( new \Kronos\Customizer\Customizer() )->register();
		( new \Kronos\Lazyload\Lazyload() )->register();
		( new \Kronos\Optimizer\Optimizer() )->register();
		( new \Kronos\Schema\Manager() )->register();
		( new \Kronos\Seo\Head() )->register();
		( new \Kronos\Amp\Amp() )->register();
		( new \Kronos\Widgets\Registrar() )->register();
		( new \Kronos\Members\Members() )->register();
		( new \Kronos\Security\Security() )->register();

		if ( is_admin() ) {
			( new \Kronos\Admin\Panel() )->register();
		}
	}

	private function __clone() {}

	public function __wakeup(): void {
		throw new \RuntimeException( 'Kronos\Core\Theme tek örnektir.' );
	}
}
