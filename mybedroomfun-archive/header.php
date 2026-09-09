<?php
/**
 * Site header: announcement bar, utility links, search-centered
 * header, shipping message, account/wishlist/cart controls, and the
 * dark navigation bar with the "Shopping Categories" mega menu.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="mbf-skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'mybedroomfun-archive' ); ?></a>

<header class="mbf-header">

	<div class="mbf-announcement">
		<p class="mbf-container">
			<?php echo esc_html( get_theme_mod( 'mbf_announcement_text', __( 'Wellness and intimacy essentials.', 'mybedroomfun-archive' ) ) ); ?>
		</p>
	</div>

	<?php
	$mbf_instagram_url = get_theme_mod( 'mbf_social_instagram_url', '' );
	$mbf_twitter_url   = get_theme_mod( 'mbf_social_twitter_url', '' );
	$mbf_has_utility    = has_nav_menu( 'utility' );
	$mbf_has_social     = $mbf_instagram_url || $mbf_twitter_url;
	?>
	<?php if ( $mbf_has_utility || $mbf_has_social ) : ?>
		<div class="mbf-utility mbf-container">
			<?php if ( $mbf_has_utility ) : ?>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'utility',
					'container'      => false,
					'menu_class'     => 'mbf-utility__menu',
					'depth'          => 1,
				) );
				?>
			<?php endif; ?>
			<?php if ( $mbf_has_social ) : ?>
				<div class="mbf-utility__social">
					<?php if ( $mbf_instagram_url ) : ?>
						<a href="<?php echo esc_url( $mbf_instagram_url ); ?>" target="_blank" rel="noopener noreferrer nofollow" title="<?php esc_attr_e( 'Instagram', 'mybedroomfun-archive' ); ?>">
							<?php echo mbf_icon_svg( 'instagram' ); ?>
							<span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'mybedroomfun-archive' ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( $mbf_twitter_url ) : ?>
						<a href="<?php echo esc_url( $mbf_twitter_url ); ?>" target="_blank" rel="noopener noreferrer nofollow" title="<?php esc_attr_e( 'Twitter', 'mybedroomfun-archive' ); ?>">
							<?php echo mbf_icon_svg( 'twitter' ); ?>
							<span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'mybedroomfun-archive' ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="mbf-header__main mbf-container">
		<div class="mbf-header__brand">
			<?php
			// Falls back to the recovered archived logo file (bundled in
			// the theme) until a real logo is set via Customize > Site
			// Identity -- see ASSET-INVENTORY.md.
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a class="mbf-header__site-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img
						src="<?php echo esc_url( MBF_THEME_URI . '/assets/images/recovered/mybedroomfun-logo.png' ); ?>"
						alt="<?php bloginfo( 'name' ); ?>"
						class="mbf-header__logo-fallback"
						width="180"
						height="43"
					/>
				</a>
				<?php
			}
			?>
		</div>

		<div class="mbf-header__search">
			<?php
			if ( function_exists( 'get_product_search_form' ) ) {
				get_product_search_form();
			} else {
				get_search_form();
			}
			?>
		</div>

		<div class="mbf-header__right">
			<p class="mbf-shipping-message">
				<?php echo esc_html( get_theme_mod( 'mbf_shipping_message', __( 'Shipping details are confirmed at checkout.', 'mybedroomfun-archive' ) ) ); ?>
			</p>
			<div class="mbf-header__controls">
				<a
					class="mbf-header__control"
					href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : admin_url() ); ?>"
					title="<?php esc_attr_e( 'Account', 'mybedroomfun-archive' ); ?>"
				>
					<?php echo mbf_icon_svg( 'account' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Account', 'mybedroomfun-archive' ); ?></span>
				</a>
				<a
					class="mbf-header__control"
					href="<?php echo esc_url( function_exists( 'mbf_wishlist_url' ) ? mbf_wishlist_url() : '#' ); ?>"
					title="<?php esc_attr_e( 'Wishlist', 'mybedroomfun-archive' ); ?>"
				>
					<?php echo mbf_icon_svg( 'wishlist' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Wishlist', 'mybedroomfun-archive' ); ?></span>
				</a>
				<?php if ( function_exists( 'WC' ) ) : ?>
					<a
						class="cart-contents mbf-header__control"
						href="<?php echo esc_url( wc_get_cart_url() ); ?>"
						title="<?php esc_attr_e( 'Cart', 'mybedroomfun-archive' ); ?>"
					>
						<?php echo mbf_icon_svg( 'cart' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Cart', 'mybedroomfun-archive' ); ?></span>
						<span class="count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<nav class="mbf-nav" aria-label="<?php esc_attr_e( 'Primary', 'mybedroomfun-archive' ); ?>">
		<div class="mbf-container mbf-nav__inner">

			<?php
			/**
			 * The "Shopping Categories" button is static UI chrome, not
			 * data -- it always renders, matching the archive. Only the
			 * dropdown panel underneath is data-driven: it lists real
			 * categories when they exist, or an honest empty-state
			 * message when they don't (WooCommerce inactive, or active
			 * with no categories yet) -- never fabricated links.
			 */
			$mbf_categories = function_exists( 'mbf_get_shopping_categories' ) ? mbf_get_shopping_categories() : array();
			?>
			<div class="mbf-mega">
				<button
					type="button"
					class="mbf-mega__trigger"
					aria-expanded="false"
					aria-controls="mbf-mega-panel"
				>
					<?php esc_html_e( 'Shopping Categories', 'mybedroomfun-archive' ); ?>
				</button>
				<div id="mbf-mega-panel" class="mbf-mega__panel" hidden>
					<?php if ( ! empty( $mbf_categories ) ) : ?>
						<ul class="mbf-mega__columns">
							<?php foreach ( $mbf_categories as $mbf_category ) : ?>
								<li class="mbf-mega__column">
									<a class="mbf-mega__parent" href="<?php echo esc_url( get_term_link( $mbf_category['term'] ) ); ?>">
										<?php echo esc_html( $mbf_category['term']->name ); ?>
									</a>
									<?php if ( ! empty( $mbf_category['children'] ) ) : ?>
										<ul class="mbf-mega__children">
											<?php foreach ( $mbf_category['children'] as $mbf_child ) : ?>
												<li>
													<a href="<?php echo esc_url( get_term_link( $mbf_child ) ); ?>">
														<?php echo esc_html( $mbf_child->name ); ?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<p class="mbf-mega__empty">
							<?php esc_html_e( 'No product categories yet.', 'mybedroomfun-archive' ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>

			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mbf-nav__menu',
				'fallback_cb'    => false,
				'depth'          => 1,
			) );
			?>
		</div>
	</nav>

</header>
