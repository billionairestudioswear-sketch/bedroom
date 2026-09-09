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
 * Enqueue styles and scripts.
 */
function mbf_enqueue_assets() {
	$ver = MBF_THEME_VERSION;

	// Always loaded: theme metadata + design tokens + base reset.
	wp_enqueue_style( 'mbf-style', get_stylesheet_uri(), array(), $ver );

	// Structural chrome present on every page.
	wp_enqueue_style( 'mbf-header', MBF_THEME_URI . '/assets/css/header.css', array( 'mbf-style' ), $ver );
	wp_enqueue_style( 'mbf-navigation', MBF_THEME_URI . '/assets/css/navigation.css', array( 'mbf-style' ), $ver );
	wp_enqueue_style( 'mbf-footer', MBF_THEME_URI . '/assets/css/footer.css', array( 'mbf-style' ), $ver );

	wp_enqueue_script( 'mbf-navigation', MBF_THEME_URI . '/assets/js/navigation.js', array(), $ver, true );

	// Homepage only. No dedicated JS: rails and category grid are pure
	// CSS + native lazy-loaded <img>, nothing to enhance with script.
	if ( is_front_page() && ! is_paged() ) {
		wp_enqueue_style( 'mbf-homepage', MBF_THEME_URI . '/assets/css/homepage.css', array( 'mbf-style' ), $ver );
	}

	// WooCommerce shop / category / tag archives.
	if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		wp_enqueue_style( 'mbf-shop', MBF_THEME_URI . '/assets/css/shop.css', array( 'mbf-style' ), $ver );
	}

	// Single product.
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style( 'mbf-shop', MBF_THEME_URI . '/assets/css/shop.css', array( 'mbf-style' ), $ver );
		wp_enqueue_style( 'mbf-product', MBF_THEME_URI . '/assets/css/product.css', array( 'mbf-style' ), $ver );
	}

	// Cart / checkout.
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() ) ) {
		wp_enqueue_style( 'mbf-cart-checkout', MBF_THEME_URI . '/assets/css/cart-checkout.css', array( 'mbf-style' ), $ver );
	}

	// My account.
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		wp_enqueue_style( 'mbf-account', MBF_THEME_URI . '/assets/css/account.css', array( 'mbf-style' ), $ver );
	}

	// Search results reuse the shop card styling when the result set is products.
	if ( is_search() ) {
		wp_enqueue_style( 'mbf-shop', MBF_THEME_URI . '/assets/css/shop.css', array( 'mbf-style' ), $ver );
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
