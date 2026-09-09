<?php
/**
 * Shop/category loop product card.
 *
 * Overrides WooCommerce's default content-product.php only to add the
 * theme's card class and restyle via assets/css/shop.css — every
 * standard WooCommerce loop-item hook (image, sale flash, title,
 * rating, price, add-to-cart button) is preserved so core behaviour
 * and third-party plugin hooks keep working unmodified.
 *
 * @package MyBedroomFun_Archive
 * @see WC_Templates: templates/content-product.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$product = wc_get_product( get_the_ID() );

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'mbf-product-card', $product ); ?>>
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * woocommerce_before_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * woocommerce_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
