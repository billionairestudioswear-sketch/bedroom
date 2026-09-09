<?php
/**
 * WooCommerce integration: theme support declaration, content wrapper
 * hooks, sidebar, and small display filters. Deliberately does not add
 * wc-product-gallery-zoom / -slider / -lightbox support, to avoid
 * pulling in extra jQuery-plugin bundles the design doesn't use.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare WooCommerce theme support.
 */
function mbf_woocommerce_setup() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 480,
		'single_image_width'    => 640,
		'product_grid'          => array(
			'default_rows'    => 4,
			'min_rows'        => 1,
			'default_columns' => 4,
			'min_columns'     => 1,
			'max_columns'     => 5,
		),
	) );
}
add_action( 'after_setup_theme', 'mbf_woocommerce_setup' );

/**
 * Replace WooCommerce's default page wrapper with the theme's own
 * semantic <main>, matching header.php / footer.php.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Whether the current request is a shop/category/tag archive — the
 * only place the two-column (content + sidebar) layout applies.
 *
 * @return bool
 */
function mbf_is_shop_archive() {
	return is_shop() || is_product_category() || is_product_tag();
}

/**
 * Open the two-column layout wrapper before <main> on shop archives
 * only; on every other WooCommerce page (single product, cart,
 * checkout, account) this is a no-op and <main> stays full width.
 */
function mbf_woocommerce_before_content_wrapper() {
	if ( mbf_is_shop_archive() ) {
		echo '<div class="mbf-shop-layout mbf-container">';
	}
}
add_action( 'woocommerce_before_main_content', 'mbf_woocommerce_before_content_wrapper', 5 );

function mbf_woocommerce_wrapper_start() {
	echo '<main id="primary" class="mbf-main mbf-main--shop">';
}
add_action( 'woocommerce_before_main_content', 'mbf_woocommerce_wrapper_start', 10 );

function mbf_woocommerce_wrapper_end() {
	echo '</main>';
}
add_action( 'woocommerce_after_main_content', 'mbf_woocommerce_wrapper_end', 10 );

/**
 * Show the shop sidebar (category navigation) on shop and category
 * archives only, never on the product page or cart/checkout.
 */
function mbf_woocommerce_sidebar() {
	if ( mbf_is_shop_archive() ) {
		get_sidebar( 'shop' );
	}
}
add_action( 'woocommerce_sidebar', 'mbf_woocommerce_sidebar', 10 );

/**
 * Close the two-column layout wrapper opened in
 * mbf_woocommerce_before_content_wrapper().
 */
function mbf_woocommerce_after_sidebar_wrapper() {
	if ( mbf_is_shop_archive() ) {
		echo '</div>';
	}
}
add_action( 'woocommerce_sidebar', 'mbf_woocommerce_after_sidebar_wrapper', 20 );

/**
 * Limit related products so a single-product page can't balloon into a
 * near-full-catalog query.
 *
 * @param array $args Related products query args.
 * @return array
 */
function mbf_related_products_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'mbf_related_products_args' );

/**
 * Keep the shop loop at 4 columns to match the card grid CSS.
 *
 * @return int
 */
function mbf_loop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'mbf_loop_columns' );

/**
 * WooCommerce breadcrumbs: wrap in the theme's container so the shop
 * navigation breadcrumb lines up with the rest of the layout.
 *
 * @param array $args Breadcrumb args.
 * @return array
 */
function mbf_breadcrumb_args( $args ) {
	$args['wrap_before'] = '<nav class="mbf-breadcrumb mbf-container" aria-label="' . esc_attr__( 'Breadcrumb', 'mybedroomfun-archive' ) . '">';
	$args['wrap_after']  = '</nav>';
	return $args;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'mbf_breadcrumb_args' );

/**
 * AJAX cart fragment so the header cart count updates after
 * add-to-cart without a full page reload. Matches the `.cart-contents`
 * selector rendered in header.php.
 *
 * @param array $fragments Existing fragments.
 * @return array
 */
function mbf_cart_link_fragment( $fragments ) {
	if ( ! function_exists( 'WC' ) ) {
		return $fragments;
	}

	ob_start();
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	?>
	<a class="cart-contents mbf-header__control" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
		<span class="mbf-header__control-label"><?php esc_html_e( 'Cart', 'mybedroomfun-archive' ); ?></span>
		<span class="count"><?php echo esc_html( $count ); ?></span>
	</a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'mbf_cart_link_fragment' );

/**
 * Wishlist destination URL. Returns '#' with a data attribute the
 * theme's JS can hook into if no wishlist plugin is active; filterable
 * so a wishlist plugin (or the site owner) can point it somewhere real.
 *
 * @return string
 */
function mbf_wishlist_url() {
	return apply_filters( 'mbf_wishlist_url', '#' );
}
