<?php

defined( 'ABSPATH' ) || exit;

use Kronos\Core\Options;
use Kronos\Core\Helpers;

$kronos_sticky  = Options::bool( 'kronos_sticky_header' ) ? ' kronos-site-header--sticky' : '';
$kronos_socials = Options::social_links();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<script>
	(function () {
		try {
			var mode = localStorage.getItem('kronos-theme');
			if (mode !== 'light' && mode !== 'dark') {
				mode = '<?php echo esc_js( Options::get( 'kronos_default_theme' ) ); ?>';
				if (window.matchMedia('(prefers-color-scheme: dark)').matches) { mode = 'dark'; }
			}
			document.documentElement.setAttribute('data-theme', mode);
		} catch (e) {}
	})();
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="kronos-skip-link" href="#kronos-main"><?php esc_html_e( 'İçeriğe geç', 'kronos' ); ?></a>

<div class="kronos-topbar">
	<div class="kronos-container kronos-topbar__inner">
		<span class="kronos-topbar__date"><?php echo esc_html( wp_date( 'j F Y, l' ) ); ?></span>
		<div class="kronos-topbar__right">
			<?php if ( $kronos_socials ) : ?>
				<div class="kronos-topbar__social">
					<?php foreach ( $kronos_socials as $key => $social ) : ?>
						<a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener noreferrer me" aria-label="<?php echo esc_attr( $social['label'] ); ?>"><?php echo Helpers::social_icon( $key ); // phpcs:ignore ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<header class="kronos-site-header<?php echo esc_attr( $kronos_sticky ); ?>" role="banner">
	<div class="kronos-container kronos-site-header__inner">
		<div class="kronos-branding">
			<?php the_custom_logo(); ?>
			<p class="kronos-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		</div>

		<nav class="kronos-nav kronos-nav--primary" aria-label="<?php esc_attr_e( 'Ana menü', 'kronos' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'kronos-nav__list',
				'fallback_cb'    => false,
				'depth'          => 2,
				'walker'         => new \Kronos\Core\NavWalker(),
			] );
			?>
		</nav>

		<div class="kronos-header-tools">
			<button type="button" class="kronos-icon-btn" data-kronos-search-toggle aria-label="<?php esc_attr_e( 'Ara', 'kronos' ); ?>"><?php echo Helpers::icon( 'search' ); // phpcs:ignore ?></button>
			<button type="button" class="kronos-icon-btn kronos-theme-toggle" data-kronos-theme-toggle aria-pressed="false" aria-label="<?php esc_attr_e( 'Koyu / açık tema', 'kronos' ); ?>">
				<span class="kronos-theme-toggle__moon" aria-hidden="true"><?php echo Helpers::icon( 'moon' ); // phpcs:ignore ?></span>
				<span class="kronos-theme-toggle__sun" aria-hidden="true"><?php echo Helpers::icon( 'sun' ); // phpcs:ignore ?></span>
			</button>
			<button type="button" class="kronos-icon-btn kronos-nav-toggle" data-kronos-nav-toggle aria-expanded="false" aria-controls="kronos-mobile-nav" aria-label="<?php esc_attr_e( 'Menü', 'kronos' ); ?>"><span class="kronos-nav-toggle__bar" aria-hidden="true"></span></button>
		</div>
	</div>
</header>

<?php if ( has_nav_menu( 'primary' ) === false ) : ?>
<div class="kronos-catbar">
	<div class="kronos-container">
		<ul class="kronos-catbar__list">
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Anasayfa', 'kronos' ); ?></a></li>
			<?php wp_list_categories( [ 'title_li' => '', 'number' => 10, 'orderby' => 'count', 'order' => 'DESC' ] ); ?>
		</ul>
	</div>
</div>
<?php endif; ?>

<div class="kronos-search-overlay" data-kronos-search hidden>
	<button type="button" class="kronos-search-overlay__close" data-kronos-search-close aria-label="<?php esc_attr_e( 'Kapat', 'kronos' ); ?>"><?php echo Helpers::icon( 'close' ); // phpcs:ignore ?></button>
	<div class="kronos-search-overlay__inner">
		<span class="kronos-search-overlay__title"><?php esc_html_e( 'Ne aramıştınız?', 'kronos' ); ?></span>
		<?php get_search_form(); ?>
		<?php
		$kronos_pop = get_categories( [ 'number' => 6, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ] );
		if ( $kronos_pop ) :
			?>
			<div class="kronos-search-overlay__pop">
				<span class="kronos-search-overlay__pop-label"><?php esc_html_e( 'Popüler:', 'kronos' ); ?></span>
				<?php foreach ( $kronos_pop as $kronos_c ) : ?>
					<a href="<?php echo esc_url( get_category_link( $kronos_c->term_id ) ); ?>"><?php echo esc_html( $kronos_c->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<div id="kronos-mobile-nav" class="kronos-mobile-nav" hidden>
	<div class="kronos-mobile-nav__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobil menü', 'kronos' ); ?>">
		<button type="button" class="kronos-mobile-nav__close" data-kronos-nav-close aria-label="<?php esc_attr_e( 'Kapat', 'kronos' ); ?>">&times;</button>
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'kronos-mobile-nav__list', 'fallback_cb' => false, 'depth' => 2 ] );
		} else {
			echo '<ul class="kronos-mobile-nav__list">';
			wp_list_categories( [ 'title_li' => '', 'number' => 12 ] );
			echo '</ul>';
		}
		?>
		<?php get_search_form(); ?>
	</div>
</div>

<?php
if ( Options::bool( 'kronos_ticker' ) && ( is_home() || is_front_page() ) ) {
	get_template_part( 'template-parts/ticker' );
}
?>

<main id="kronos-main" class="kronos-main" role="main">
