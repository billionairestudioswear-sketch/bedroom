<?php
/**
 * Dynamic WooCommerce data helpers used by the homepage and header.
 *
 * Every query here is deliberately small (limited posts_per_page) and
 * cached in a transient — the homepage must never load the full
 * catalog. Nothing here creates, copies, or modifies products,
 * categories, or terms; it only reads what already exists in
 * WooCommerce.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a small set of product IDs for a homepage section, cached.
 *
 * @param string $cache_key  Transient key suffix.
 * @param array  $query_args wc_get_products() args.
 * @param int    $ttl        Cache lifetime in seconds.
 * @return int[] Product IDs.
 */
function mbf_get_cached_product_ids( $cache_key, array $query_args, $ttl = HOUR_IN_SECONDS * 6 ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	$transient_key = 'mbf_' . $cache_key;
	$cached        = get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached;
	}

	$default_args = array(
		'status' => 'publish',
		'limit'  => 8,
		'return' => 'ids',
	);

	$ids = wc_get_products( array_merge( $default_args, $query_args ) );
	$ids = is_array( $ids ) ? $ids : array();

	set_transient( $transient_key, $ids, $ttl );

	return $ids;
}

/**
 * Bestsellers, ordered by WooCommerce's own sales meta.
 *
 * @param int $limit Number of products.
 * @return int[]
 */
function mbf_get_bestseller_ids( $limit = 8 ) {
	return mbf_get_cached_product_ids(
		'bestsellers_' . $limit,
		array(
			'limit'   => $limit,
			'orderby' => 'popularity',
			'order'   => 'DESC',
		)
	);
}

/**
 * Products currently on sale.
 *
 * @param int $limit Number of products.
 * @return int[]
 */
function mbf_get_sale_ids( $limit = 8 ) {
	if ( ! function_exists( 'wc_get_product_ids_on_sale' ) ) {
		return array();
	}

	$transient_key = 'mbf_sale_' . $limit;
	$cached        = get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached;
	}

	$sale_ids = wc_get_product_ids_on_sale();
	$sale_ids = is_array( $sale_ids ) ? array_slice( array_reverse( $sale_ids ), 0, $limit ) : array();

	set_transient( $transient_key, $sale_ids, HOUR_IN_SECONDS * 3 );

	return $sale_ids;
}

/**
 * Products marked as WooCommerce "Featured".
 *
 * @param int $limit Number of products.
 * @return int[]
 */
function mbf_get_featured_ids( $limit = 8 ) {
	return mbf_get_cached_product_ids(
		'featured_' . $limit,
		array(
			'limit'    => $limit,
			'featured' => true,
			'orderby'  => 'date',
			'order'    => 'DESC',
		)
	);
}

/**
 * Newest published products.
 *
 * @param int $limit Number of products.
 * @return int[]
 */
function mbf_get_new_arrival_ids( $limit = 8 ) {
	return mbf_get_cached_product_ids(
		'new_arrivals_' . $limit,
		array(
			'limit'   => $limit,
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);
}

/**
 * Configured hero slider images, slide 1 through 5, in order. Slide 1
 * always has a value (the recovered archive image, unless overridden
 * via Customizer); slides 2-5 are simply omitted from the returned
 * array -- not included as an empty/placeholder entry -- when no
 * image has been set for them, so the template never has to render
 * (and then hide) an empty slot.
 *
 * @return string[] Image URLs, in slide order.
 */
function mbf_get_hero_slides() {
	$slides = array();

	for ( $i = 1; $i <= 5; $i++ ) {
		$mod   = ( 1 === $i ) ? 'mbf_hero_image' : "mbf_hero_slide_{$i}_image";
		$image = get_theme_mod(
			$mod,
			( 1 === $i ) ? ( defined( 'MBF_THEME_URI' ) ? MBF_THEME_URI . '/assets/images/recovered/hero-banner-1.jpg' : '' ) : ''
		);

		if ( $image ) {
			$slides[] = $image;
		}
	}

	return $slides;
}

/**
 * Top-level product categories plus their direct children, for the
 * mega menu and the "Browse Our Categories" homepage grid. Reads
 * existing product_cat terms only.
 *
 * @param int $top_level_limit Max top-level categories to return (0 = all).
 * @return array<int, array{term: WP_Term, children: WP_Term[]}>
 */
function mbf_get_shopping_categories( $top_level_limit = 0 ) {
	$transient_key = 'mbf_shopping_categories_' . $top_level_limit;
	$cached        = get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached;
	}

	$args = array(
		'taxonomy'   => 'product_cat',
		'parent'     => 0,
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	);

	if ( $top_level_limit > 0 ) {
		$args['number'] = $top_level_limit;
	}

	$top_level = get_terms( $args );
	$data      = array();

	if ( ! is_wp_error( $top_level ) && ! empty( $top_level ) ) {
		foreach ( $top_level as $term ) {
			$children = get_terms( array(
				'taxonomy'   => 'product_cat',
				'parent'     => $term->term_id,
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'number'     => 12,
			) );

			$data[] = array(
				'term'     => $term,
				'children' => is_wp_error( $children ) ? array() : $children,
			);
		}
	}

	set_transient( $transient_key, $data, HOUR_IN_SECONDS * 12 );

	return $data;
}

/**
 * Original, hand-drawn line icons for the header account/wishlist/cart
 * controls -- not Flatsome's icon font (proprietary to that theme,
 * excluded per the build brief). 24x24, stroke-based, inherits color
 * via currentColor so CSS controls its appearance.
 *
 * @param string $name One of 'account', 'wishlist', 'cart'.
 * @return string Inline <svg> markup, or '' for an unknown name.
 */
function mbf_icon_svg( $name ) {
	$icons = array(
		'account'  => '<circle cx="12" cy="8" r="3.25"></circle><path d="M4.5 20c1.4-4 4.2-6 7.5-6s6.1 2 7.5 6"></path>',
		'wishlist' => '<path d="M12 20S4 14.9 4 9.6C4 6.8 6.1 4.8 8.6 4.8c1.4 0 2.7.7 3.4 1.8.7-1.1 2-1.8 3.4-1.8 2.5 0 4.6 2 4.6 4.8 0 5.3-8 10.4-8 10.4Z"></path>',
		'cart'     => '<circle cx="9.5" cy="20" r="1.4"></circle><circle cx="17" cy="20" r="1.4"></circle><path d="M3.5 4h2.2l1.9 10.4a1.8 1.8 0 0 0 1.8 1.5h8.4a1.8 1.8 0 0 0 1.8-1.5L21 7.5H6.4"></path>',
		'instagram' => '<rect x="4" y="4" width="16" height="16" rx="4.5"></rect><circle cx="12" cy="12" r="3.6"></circle><circle cx="16.6" cy="7.4" r="0.6" fill="currentColor" stroke="none"></circle>',
		'twitter'   => '<path d="M20.5 6.2c-.6.3-1.3.5-2 .6a3.5 3.5 0 0 0-6 3.2A9.9 9.9 0 0 1 5.3 5.9a3.5 3.5 0 0 0 1.1 4.7c-.6 0-1.1-.2-1.6-.4v.1a3.5 3.5 0 0 0 2.8 3.4 3.5 3.5 0 0 1-1.6.1 3.5 3.5 0 0 0 3.3 2.4A7 7 0 0 1 4 17.6a9.9 9.9 0 0 0 5.4 1.6c6.5 0 10-5.4 10-10v-.5c.7-.5 1.3-1.1 1.8-1.8Z"></path>',
		'search'    => '<circle cx="10.5" cy="10.5" r="6.5"></circle><path d="M20 20l-4.8-4.8"></path>',
	);

	if ( empty( $icons[ $name ] ) ) {
		return '';
	}

	return '<svg class="mbf-icon mbf-icon--' . esc_attr( $name ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * Invalidate the cached homepage/menu data whenever a product or a
 * product category changes, so the theme never shows stale data.
 */
function mbf_flush_dynamic_caches() {
	global $wpdb;

	$like = $wpdb->esc_like( '_transient_mbf_' ) . '%';
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like ) );

	$like_timeout = $wpdb->esc_like( '_transient_timeout_mbf_' ) . '%';
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like_timeout ) );
}
add_action( 'save_post_product', 'mbf_flush_dynamic_caches' );
add_action( 'woocommerce_update_product', 'mbf_flush_dynamic_caches' );
add_action( 'created_product_cat', 'mbf_flush_dynamic_caches' );
add_action( 'edited_product_cat', 'mbf_flush_dynamic_caches' );
add_action( 'delete_product_cat', 'mbf_flush_dynamic_caches' );

/**
 * Render a single product card. Shared by every homepage rail and the
 * WooCommerce loop override in woocommerce/content-product.php.
 *
 * @param int $product_id Product post ID.
 */
function mbf_render_product_card( $product_id ) {
	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return;
	}
	?>
	<li class="mbf-product-card">
		<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="mbf-product-card__link">
			<span class="mbf-product-card__image">
				<?php
				echo wp_kses_post(
					$product->get_image( 'mbf-card', array( 'loading' => 'lazy' ) )
				);
				?>
				<?php if ( $product->is_on_sale() ) : ?>
					<span class="mbf-badge mbf-badge--sale"><?php esc_html_e( 'Sale', 'mybedroomfun-archive' ); ?></span>
				<?php endif; ?>
			</span>
			<span class="mbf-product-card__title"><?php echo esc_html( $product->get_name() ); ?></span>
			<span class="mbf-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
		</a>
	</li>
	<?php
}

/**
 * Output a homepage product rail (a heading plus a row of cards) for a
 * list of product IDs. Still renders the heading and an honest empty
 * state (no dummy/placeholder products) when the list is empty, so
 * the section's presence doesn't silently depend on catalog data.
 *
 * @param string $heading     Section heading text.
 * @param int[]  $product_ids Product IDs to render.
 */
function mbf_render_product_rail( $heading, array $product_ids ) {
	?>
	<section class="mbf-rail">
		<div class="mbf-container">
			<h2 class="mbf-rail__heading"><?php echo esc_html( $heading ); ?></h2>
			<?php if ( empty( $product_ids ) ) : ?>
				<p class="mbf-rail__empty"><?php esc_html_e( 'No products to show here yet.', 'mybedroomfun-archive' ); ?></p>
			<?php else : ?>
				<ul class="mbf-rail__grid">
					<?php foreach ( $product_ids as $product_id ) : ?>
						<?php mbf_render_product_card( $product_id ); ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
