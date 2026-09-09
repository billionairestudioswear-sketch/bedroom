<?php
/**
 * Conditional, modular asset loading. Nothing is loaded on a page that
 * doesn't need it.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cache-busting version string for one theme asset: the file's own
 * modification time, not a single hand-maintained theme version. This
 * way every enqueue auto-invalidates the instant that specific file
 * changes on disk -- no step where a developer has to remember to
 * bump a shared version number (and no risk of a browser/proxy/CDN
 * keeping a stale copy because the ?ver= query string never changed).
 *
 * @param string $relative_path Path relative to the theme root, e.g. "/assets/css/header.css".
 * @return string
 */
function mbf_asset_version( $relative_path ) {
	$file_path = MBF_THEME_DIR . $relative_path;

	if ( file_exists( $file_path ) ) {
		return (string) filemtime( $file_path );
	}

	return MBF_THEME_VERSION;
}

/**
 * Enqueue styles and scripts.
 */
function mbf_enqueue_assets() {
	// Always loaded: theme metadata + design tokens + base reset.
	wp_enqueue_style( 'mbf-style', get_stylesheet_uri(), array(), mbf_asset_version( '/style.css' ) );

	// Structural chrome present on every page.
	wp_enqueue_style( 'mbf-header', MBF_THEME_URI . '/assets/css/header.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/header.css' ) );
	wp_enqueue_style( 'mbf-navigation', MBF_THEME_URI . '/assets/css/navigation.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/navigation.css' ) );
	wp_enqueue_style( 'mbf-footer', MBF_THEME_URI . '/assets/css/footer.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/footer.css' ) );

	wp_enqueue_script( 'mbf-navigation', MBF_THEME_URI . '/assets/js/navigation.js', array(), mbf_asset_version( '/assets/js/navigation.js' ), true );

	// Homepage only. No dedicated JS: rails and category grid are pure
	// CSS + native lazy-loaded <img>, nothing to enhance with script.
	if ( is_front_page() && ! is_paged() ) {
		wp_enqueue_style( 'mbf-homepage', MBF_THEME_URI . '/assets/css/homepage.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/homepage.css' ) );
	}

	// WooCommerce shop / category / tag archives.
	if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		wp_enqueue_style( 'mbf-shop', MBF_THEME_URI . '/assets/css/shop.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/shop.css' ) );
	}

	// Single product.
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style( 'mbf-shop', MBF_THEME_URI . '/assets/css/shop.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/shop.css' ) );
		wp_enqueue_style( 'mbf-product', MBF_THEME_URI . '/assets/css/product.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/product.css' ) );
	}

	// Cart / checkout.
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() ) ) {
		wp_enqueue_style( 'mbf-cart-checkout', MBF_THEME_URI . '/assets/css/cart-checkout.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/cart-checkout.css' ) );
	}

	// My account.
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		wp_enqueue_style( 'mbf-account', MBF_THEME_URI . '/assets/css/account.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/account.css' ) );
	}

	// Search results reuse the shop card styling when the result set is products.
	if ( is_search() ) {
		wp_enqueue_style( 'mbf-shop', MBF_THEME_URI . '/assets/css/shop.css', array( 'mbf-style' ), mbf_asset_version( '/assets/css/shop.css' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'mbf_enqueue_assets' );

/**
 * Preload the first hero image on the homepage only (LCP optimization).
 * Reads the homepage's featured image / theme mod hero image if set.
 */
function mbf_preload_hero_image() {
	if ( ! is_front_page() || is_paged() ) {
		return;
	}

	$hero_image = get_theme_mod(
		'mbf_hero_image',
		MBF_THEME_URI . '/assets/images/recovered/hero-banner-1.jpg'
	);
	if ( ! $hero_image ) {
		return;
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high" />' . "\n",
		esc_url( $hero_image )
	);
}
add_action( 'wp_head', 'mbf_preload_hero_image', 1 );
