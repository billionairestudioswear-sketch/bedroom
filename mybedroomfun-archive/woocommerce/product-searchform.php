<?php
/**
 * WooCommerce product search form. Icon-only submit control -- no
 * visible "Search" button text -- matching the archived header.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mbf_search_id = 'mbf-search-' . wp_unique_id();
?>
<form role="search" method="get" class="mbf-search-form woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $mbf_search_id ); ?>">
		<?php esc_html_e( 'Search for:', 'mybedroomfun-archive' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $mbf_search_id ); ?>"
		class="search-field"
		placeholder="<?php echo esc_attr_x( 'Search products…', 'placeholder', 'mybedroomfun-archive' ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s"
	/>
	<button type="submit" class="mbf-search-form__submit">
		<?php echo mbf_icon_svg( 'search' ); ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'mybedroomfun-archive' ); ?></span>
	</button>
	<input type="hidden" name="post_type" value="product" />
</form>
